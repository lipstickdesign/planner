<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Kamp;
use App\Models\KampTemplate;
use App\Services\KampImportService;
use App\Services\NifTaService;
use Illuminate\Http\Request;

class KampController extends Controller
{
    /** Kampplakat-generator: neste hjemmekamper per idrett, klart til nedlasting som bilde. */
    public function plakat()
    {
        $company = app()->bound('currentCompany') ? app('currentCompany') : Company::first();
        if (! $company) {
            abort(404);
        }

        $today = now('Europe/Oslo')->startOfDay();
        $end = $today->copy()->addDays(7);

        $kamper = Kamp::with('category')->where('company_id', $company->id)
            ->where('home', true)->whereNotNull('match_date')
            ->whereBetween('match_date', [$today->format('Y-m-d'), $end->format('Y-m-d')])
            ->orderBy('match_date')->orderBy('match_time')->get();

        $grouped = [];
        foreach ($kamper as $k) {
            $sport = $k->category?->name ?: 'Annet';
            $d = $k->match_date instanceof \Carbon\Carbon ? $k->match_date : \Carbon\Carbon::parse($k->match_date);
            $label = ucfirst($d->locale('nb')->isoFormat('dddd DD.MM.YY'));
            $home = $k->home_team ?: null;
            $away = $k->away_team ?: null;
            $match = ($home && $away) ? ($home.' – '.$away) : ($k->title ?: 'Kamp');
            $grouped[$sport][$label][] = [
                'time' => $k->match_time ? substr((string) $k->match_time, 0, 5) : '',
                'team' => '',
                'serie' => $k->tournament ?: '',
                'hall' => $k->location ?: '',
                'match' => $match,
            ];
        }

        $kampdata = [];
        foreach ($grouped as $sport => $days) {
            foreach ($days as $label => $matches) {
                $kampdata[$sport][] = ['day' => $label, 'matches' => $matches];
            }
        }

        // Flat liste til enkeltkamp-velgeren
        $matches = $kamper->map(function (Kamp $k) {
            $d = $k->match_date instanceof \Carbon\Carbon ? $k->match_date : \Carbon\Carbon::parse($k->match_date);

            return [
                'sport' => $k->category?->name ?: 'Annet',
                'date' => $d->format('Y-m-d'),
                'time' => $k->match_time ? substr((string) $k->match_time, 0, 5) : '',
                'home' => $k->home_team ?: 'FLIK',
                'away' => $k->away_team ?: '',
                'location' => $k->location ?: '',
                'tournament' => $k->tournament ?: '',
                'label' => ($k->home_team ?: 'FLIK').' – '.($k->away_team ?: '?').' · '.$d->isoFormat('DD.MM'),
            ];
        })->values();

        $sports = \App\Models\Category::where('company_id', $company->id)->whereNull('archived_at')
            ->orderBy('sort_order')->pluck('name')->values();

        $templates = KampTemplate::where('company_id', $company->id)
            ->orderBy('sport')->orderBy('name')->get()->map->card()->values();

        $sponsors = \App\Models\Sponsor::where('company_id', $company->id)
            ->orderBy('sort_order')->orderBy('name')->get()->map->card()->values();

        // «Kamper & feed»-fanen: kommende hjemmekamper til administrasjon + kategorier til skjemaet.
        $manageKamper = Kamp::with('category')->where('company_id', $company->id)
            ->where('home', true)->whereNotNull('match_date')
            ->whereDate('match_date', '>=', $today->format('Y-m-d'))
            ->orderBy('match_date')->orderBy('match_time')->get()
            ->map(fn (Kamp $k) => $k->card())->values();

        $categories = \App\Models\Category::where('company_id', $company->id)->whereNull('archived_at')
            ->orderBy('sort_order')->get(['id', 'name', 'color'])->values();

        $canEdit = auth()->user()?->isCompanyAdmin() ?? false;
        $slug = $company->slug;
        $feeds = [
            'fotball' => ! empty(($company->settings['football_ical_url'] ?? null)),
            'nif' => ! empty(config('services.nif.client_id')) && ! empty(config('services.nif.club_orgid')),
        ];

        return view('kamper', compact('kampdata', 'matches', 'sports', 'company', 'templates', 'sponsors', 'manageKamper', 'categories', 'canEdit', 'slug', 'feeds'));
    }

    /** Importer hjemmekamper fra NIF Turneringsadmin (håndball m.m.). */
    public function importNif(NifTaService $service)
    {
        $company = app()->bound('currentCompany') ? app('currentCompany') : Company::first();
        if (! $company) {
            return response()->json(['error' => 'Fant ikke selskap.'], 422);
        }
        $orgid = config('services.nif.club_orgid');
        if (! $orgid) {
            return response()->json(['error' => 'NIF-feed er ikke satt opp (mangler NIF_CLUB_ORGID).'], 422);
        }

        try {
            $result = $service->import($company, (string) $orgid);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }

        return response()->json(['ok' => true, 'result' => $result, 'kamper' => $this->upcomingHomeCards($company)]);
    }

    /** Kommende hjemmekamper som kort (til administrasjonslista). */
    private function upcomingHomeCards(Company $company): array
    {
        return Kamp::with('category')->where('company_id', $company->id)
            ->where('home', true)->whereNotNull('match_date')
            ->whereDate('match_date', '>=', now('Europe/Oslo')->startOfDay()->format('Y-m-d'))
            ->orderBy('match_date')->orderBy('match_time')->get()
            ->map(fn (Kamp $k) => $k->card())->values()->all();
    }

    /** Lagre (eller oppdatere) en kampplakat-mal. Bilder sendes som data-URL og lagres som fil. */
    public function saveTemplate(Request $request)
    {
        $company = app()->bound('currentCompany') ? app('currentCompany') : Company::first();
        if (! $company) {
            return response()->json(['error' => 'Fant ikke selskap.'], 422);
        }

        $data = $request->validate([
            'sport' => ['required', 'string', 'max:80'],
            'mode' => ['required', 'in:ov,ek'],
            'name' => ['required', 'string', 'max:120'],
            'data' => ['required', 'array'],
            'images' => ['nullable', 'array'],
        ]);

        $payload = $data['data'];
        foreach (($data['images'] ?? []) as $key => $val) {
            $payload[$key] = $this->storeImageData($val, $company->id);
        }

        $tpl = KampTemplate::updateOrCreate(
            ['company_id' => $company->id, 'sport' => $data['sport'], 'mode' => $data['mode'], 'name' => $data['name']],
            ['data' => $payload, 'created_by' => $request->user()?->id]
        );

        return response()->json(['ok' => true, 'template' => $tpl->fresh()->card()]);
    }

    /** Slett en mal. */
    public function deleteTemplate(KampTemplate $template)
    {
        foreach (['titleUrl', 'bgUrl', 'oppUrl', 'sponUrl'] as $key) {
            $url = $template->data[$key] ?? null;
            if (is_string($url) && str_starts_with($url, '/uploads/kampmaler/')) {
                @unlink(public_path(ltrim($url, '/')));
            }
        }
        $template->delete();

        return response()->json(['ok' => true]);
    }

    /** Dekoder en data-URL til fil og returnerer offentlig sti. Allerede-lagrede URLer / null beholdes. */
    private function storeImageData($val, int $companyId): ?string
    {
        if (! is_string($val) || $val === '') {
            return null;
        }
        if (! str_starts_with($val, 'data:')) {
            return $val; // allerede en lagret URL
        }
        // Godta alle bildeformater (png/jpg/jpeg/webp/gif/svg/avif), base64 eller URL-kodet (svg).
        if (! preg_match('#^data:image/([a-zA-Z0-9.+-]+)([^,]*),(.*)$#s', $val, $m)) {
            return null;
        }
        $extMap = [
            'jpeg' => 'jpg', 'jpg' => 'jpg', 'png' => 'png', 'webp' => 'webp',
            'gif' => 'gif', 'svg+xml' => 'svg', 'svg' => 'svg', 'avif' => 'avif',
        ];
        $subtype = strtolower($m[1]);
        $ext = $extMap[$subtype] ?? null;
        if ($ext === null) {
            return null;
        }
        $bin = (stripos($m[2], 'base64') !== false)
            ? base64_decode($m[3], true)
            : rawurldecode($m[3]);
        if ($bin === false || $bin === '') {
            return null;
        }
        $dir = public_path('uploads/kampmaler');
        if (! is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
        $name = 'm'.$companyId.'-'.bin2hex(random_bytes(6)).'.'.$ext;
        file_put_contents($dir.'/'.$name, $bin);

        return '/uploads/kampmaler/'.$name;
    }

    /** Importer hjemmekamper fra klubbens fotball.no-feed. */
    public function import(Request $request, KampImportService $service)
    {
        $company = app()->bound('currentCompany') ? app('currentCompany') : Company::first();
        if (! $company) {
            return response()->json(['error' => 'Fant ikke selskap.'], 422);
        }

        try {
            $result = $service->import($company);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }

        $kamper = Kamp::with('category')
            ->whereNotNull('match_date')
            ->whereDate('match_date', '>=', now()->subDay())
            ->orderBy('match_date')->orderBy('match_time')
            ->get()->map(fn (Kamp $k) => $k->card())->values();

        return response()->json(['ok' => true, 'result' => $result, 'kamper' => $kamper]);
    }

    private function rules(): array
    {
        return [
            'category_id' => ['nullable', 'exists:categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'match_date' => ['required', 'date'],
            'match_time' => ['nullable', 'string', 'max:5'],
            'location' => ['nullable', 'string', 'max:255'],
            'home' => ['nullable', 'boolean'],
            'note' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function store(Request $request)
    {
        $kamp = Kamp::create($request->validate($this->rules()));

        return response()->json($kamp->fresh('category')->card(), 201);
    }

    public function update(Request $request, Kamp $kamp)
    {
        $kamp->update($request->validate($this->rules()));

        return response()->json($kamp->fresh('category')->card());
    }

    public function destroy(Kamp $kamp)
    {
        $kamp->delete();

        return response()->json(['ok' => true]);
    }
}
