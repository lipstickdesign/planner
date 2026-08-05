<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Company;
use App\Models\Kamp;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

/**
 * Importerer hjemmekamper fra en fotball.no iCal-feed (klubbens offisielle
 * «Abonner på kalender»-lenke) inn i kamper-tabellen. Kun hjemmekamper –
 * altså der et av klubbens lag står som hjemmelag.
 */
class KampImportService
{
    /**
     * @return array{imported:int,updated:int,total:int,home:int,skipped:int}
     */
    public function import(Company $company): array
    {
        $settings = is_array($company->settings) ? $company->settings : [];
        $url = $settings['football_ical_url'] ?? null;
        if (! $url) {
            throw new \RuntimeException('Ingen kamp-feed er satt opp for klubben ennå.');
        }
        $aliases = $settings['club_aliases'] ?? [$company->name];

        $resp = Http::timeout(30)
            ->withHeaders(['User-Agent' => 'VivuPlanner/1.0 (+https://planner.vivu.no)'])
            ->get($url);

        if (! $resp->successful()) {
            throw new \RuntimeException('Kunne ikke hente kalenderen fra fotball.no ('.$resp->status().').');
        }

        $events = $this->parseIcal($resp->body());

        $catId = Category::where('company_id', $company->id)
            ->whereRaw('LOWER(name) = ?', ['fotball'])
            ->value('id');

        $imported = 0;
        $updated = 0;
        $home = 0;
        $skipped = 0;

        foreach ($events as $e) {
            [$homeTeam, $awayTeam] = $this->teams($e);
            if ($homeTeam === '' && $awayTeam === '') {
                $skipped++;
                continue;
            }

            // Kun hjemmekamper: et av klubbens aliaser må matche HJEMMElaget.
            $isHome = false;
            foreach ($aliases as $a) {
                if ($a !== '' && mb_stripos($homeTeam, $a) !== false) {
                    $isHome = true;
                    break;
                }
            }
            if (! $isHome) {
                continue;
            }
            $home++;

            $start = $this->parseStart($e['DTSTART'] ?? '');
            if (! $start) {
                $skipped++;
                continue;
            }

            $postponed = (bool) preg_match('/\bUtsatt\b/u', $e['SUMMARY'] ?? '');

            $record = Kamp::updateOrCreate(
                ['company_id' => $company->id, 'external_uid' => $e['UID'] ?? null],
                [
                    'category_id' => $catId,
                    'title' => trim($homeTeam.' - '.$awayTeam),
                    'home_team' => $homeTeam,
                    'away_team' => $awayTeam,
                    'tournament' => $e['_tournament'] ?? null,
                    'match_date' => $start->format('Y-m-d'),
                    'match_time' => $start->format('H:i:s'),
                    'location' => $e['LOCATION'] ?? null,
                    'home' => true,
                    'note' => $postponed ? 'Utsatt' : null,
                    'source' => 'fotball.no',
                ]
            );

            $record->wasRecentlyCreated ? $imported++ : $updated++;
        }

        return [
            'imported' => $imported,
            'updated' => $updated,
            'total' => count($events),
            'home' => $home,
            'skipped' => $skipped,
        ];
    }

    /** Del iCal-tekst i events med utfoldede (unfolded) egenskaper. */
    private function parseIcal(string $raw): array
    {
        $raw = str_replace("\r\n", "\n", $raw);
        // Utfold linjer: fortsettelseslinjer starter med mellomrom/tab.
        $raw = preg_replace("/\n[ \t]/", '', $raw);
        $lines = explode("\n", $raw);

        $events = [];
        $cur = null;
        foreach ($lines as $line) {
            if ($line === 'BEGIN:VEVENT') {
                $cur = [];

                continue;
            }
            if ($line === 'END:VEVENT') {
                if ($cur !== null) {
                    $cur['_tournament'] = $this->tournament($cur['DESCRIPTION'] ?? '');
                    $events[] = $cur;
                }
                $cur = null;

                continue;
            }
            if ($cur === null) {
                continue;
            }
            $pos = strpos($line, ':');
            if ($pos === false) {
                continue;
            }
            $name = substr($line, 0, $pos);
            $value = substr($line, $pos + 1);
            // Fjern parametere (f.eks. DTSTART;TZID=Europe/Oslo)
            $name = strtoupper(explode(';', $name)[0]);
            $cur[$name] = $value;
        }

        return $events;
    }

    /** Hent hjemme-/bortelag fra DESCRIPTION (renest), fallback SUMMARY. */
    private function teams(array $e): array
    {
        $desc = $e['DESCRIPTION'] ?? '';
        $parts = explode('\n', $desc); // iCal bruker literal \n mellom tekstlinjer
        $matchLine = isset($parts[2]) ? trim($parts[2]) : '';

        if ($matchLine === '' || strpos($matchLine, ' - ') === false) {
            // Fallback: SUMMARY = "Hjemme - Borte -" (evt. " Utsatt")
            $matchLine = (string) ($e['SUMMARY'] ?? '');
            $matchLine = preg_replace('/\s+(Utsatt)?\s*-?\s*$/u', '', $matchLine);
        }

        if (strpos($matchLine, ' - ') === false) {
            return ['', ''];
        }
        $bits = explode(' - ', $matchLine, 2);

        return [trim($bits[0]), trim(preg_replace('/\s*-\s*$/', '', $bits[1] ?? ''))];
    }

    private function tournament(string $desc): ?string
    {
        $parts = explode('\n', $desc);
        $t = trim($parts[0] ?? '');

        return $t !== '' ? $t : null;
    }

    private function parseStart(string $val): ?Carbon
    {
        $val = trim($val);
        if ($val === '') {
            return null;
        }
        try {
            if (str_ends_with($val, 'Z')) {
                return Carbon::createFromFormat('Ymd\THis\Z', $val, 'UTC')->setTimezone('Europe/Oslo');
            }

            return Carbon::createFromFormat('Ymd\THis', $val, 'Europe/Oslo');
        } catch (\Throwable $e) {
            return null;
        }
    }
}
