<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Company;
use App\Models\Kamp;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

/**
 * Henter kommende kamper for en klubb fra NIF Turneringsadmin (TA) sin
 * TRT-tjeneste og importerer hjemmekampene i kamper-tabellen.
 *
 * Autentisering: OAuth2 client_credentials mot id.nif.no (Basic auth-header).
 * Endepunkt: {data_url}/api/v1/ta/ScheduledMatches/club/{orgid}
 * Scope: data_ta_scheduledmatches_read
 *
 * Gjelder KUN klubbens egne kamper (bruksrett gitt til klubben, ikke
 * leverandør). Hemmeligheter settes i .env.
 */
class NifTaService
{
    /** Hent (og cache) et access token via client_credentials. */
    public function token(bool $fresh = false): string
    {
        $id = config('services.nif.client_id');
        $secret = config('services.nif.client_secret');
        if (! $id || ! $secret) {
            throw new \RuntimeException('NIF_CLIENT_ID / NIF_CLIENT_SECRET mangler i .env.');
        }

        if ($fresh) {
            Cache::forget('nif_ta_token');
        }

        return Cache::remember('nif_ta_token', now()->addMinutes(45), function () use ($id, $secret) {
            $resp = Http::asForm()->withBasicAuth($id, $secret)->timeout(30)
                ->post(config('services.nif.token_url'), [
                    'grant_type' => 'client_credentials',
                    'scope' => config('services.nif.scope'),
                ]);

            if (! $resp->successful()) {
                throw new \RuntimeException('NIF token feilet ('.$resp->status().'): '.mb_substr($resp->body(), 0, 300));
            }
            $token = $resp->json('access_token');
            if (! $token) {
                throw new \RuntimeException('NIF token-respons manglet access_token.');
            }

            return $token;
        });
    }

    /** Hent kommende kamper for en klubb (orgtype 5). Returnerer rå rader fra TA. */
    public function scheduledMatchesForClub(string $orgid): array
    {
        $url = rtrim(config('services.nif.data_url'), '/').'/api/v1/ta/ScheduledMatches/club/'.rawurlencode($orgid);

        $resp = Http::withToken($this->token())->acceptJson()->timeout(45)->get($url);
        if ($resp->status() === 401) {
            // Token kan ha utløpt – hent ferskt og prøv én gang til.
            $resp = Http::withToken($this->token(true))->acceptJson()->timeout(45)->get($url);
        }
        if (! $resp->successful()) {
            throw new \RuntimeException('NIF kamp-henting feilet ('.$resp->status().'): '.mb_substr($resp->body(), 0, 300));
        }

        $data = $resp->json();

        return is_array($data) ? $data : [];
    }

    /**
     * Full synk av klubbens HJEMMEkamper fra TA inn i kamper-tabellen.
     * Erstatter tidligere importerte (source=nif-ta) i én transaksjon –
     * manuelt lagt inn kamper (source=null) og fotball.no røres ikke.
     *
     * @return array{synced:int,total:int,home:int,breakdown:array}
     */
    public function import(Company $company, string $orgid): array
    {
        $matches = $this->scheduledMatchesForClub($orgid);

        $defaultCat = config('services.nif.default_category', 'Håndball');
        $catId = Category::where('company_id', $company->id)
            ->whereRaw('LOWER(name) = ?', [mb_strtolower($defaultCat)])
            ->value('id');

        $rows = [];
        $home = 0;
        $breakdown = []; // admOrgId => antall, for å oppdage flere idretter

        foreach ($matches as $m) {
            $adm = (string) ($m['admOrgId'] ?? '?');
            $breakdown[$adm] = ($breakdown[$adm] ?? 0) + 1;

            // Hjemmekamp = klubbens lag står som hjemmelag.
            $isHome = (string) ($m['hometeamClubOrgId'] ?? '') === (string) $orgid;
            if (! $isHome) {
                continue;
            }

            $date = ! empty($m['matchDate']) ? Carbon::parse($m['matchDate'])->format('Y-m-d') : null;
            if (! $date) {
                continue;
            }
            $home++;

            // matchStartTime er et heltall på formen HHMM (f.eks. 1400 = 14:00).
            $st = $m['matchStartTime'] ?? null;
            $time = null;
            if ($st !== null && $st !== '') {
                $p = sprintf('%04d', (int) $st);
                $time = substr($p, 0, 2).':'.substr($p, 2, 2).':00';
            }

            $rows[] = [
                'company_id' => $company->id,
                'category_id' => $catId,
                'external_uid' => 'nif-'.($m['matchId'] ?? $m['id'] ?? uniqid()),
                'title' => trim(($m['hometeam'] ?? '').' - '.($m['awayteam'] ?? '')),
                'home_team' => $m['hometeam'] ?? null,
                'away_team' => $m['awayteam'] ?? null,
                'tournament' => $m['tournamentName'] ?? null,
                'match_date' => $date,
                'match_time' => $time,
                'location' => $m['activityAreaName'] ?? null,
                'home' => true,
                'note' => null,
                'source' => 'nif-ta',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::transaction(function () use ($company, $rows) {
            Kamp::where('company_id', $company->id)->where('source', 'nif-ta')->delete();
            foreach (array_chunk($rows, 200) as $chunk) {
                DB::table('kamper')->insert($chunk);
            }
        });

        return [
            'synced' => count($rows),
            'total' => count($matches),
            'home' => $home,
            'breakdown' => $breakdown,
        ];
    }
}
