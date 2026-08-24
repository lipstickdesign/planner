<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Company;
use App\Models\TrainingAssignment;
use App\Models\TrainingAvailability;
use App\Models\TrainingFacility;
use App\Models\TrainingPlanVersion;
use App\Models\TrainingSeason;
use App\Models\TrainingTeam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class TrainingController extends Controller
{
    /** Skjult for alle andre enn superadmin mens modulen bygges. */
    private function guard(): void
    {
        abort_unless((bool) auth()->user()?->is_platform_admin, 403);
    }

    private function company(): ?Company
    {
        return app()->bound('currentCompany') ? app('currentCompany') : Company::first();
    }

    /** Kontroll-visningen (regelsjekk), foreløpig med innebygd data. */
    public function index()
    {
        $this->guard();

        return view('training.kontroll');
    }

    /** Lag-oversikt – redigerbar liste over lagene i databasen. */
    public function teams()
    {
        $this->guard();
        $company = $this->company();

        $teams = $company
            ? TrainingTeam::where('company_id', $company->id)->with(['category', 'wishes'])->orderBy('name')->get()
                ->map(fn (TrainingTeam $t) => $this->card($t))->values()
            : collect();

        $cats = $company
            ? Category::where('company_id', $company->id)->whereNull('archived_at')
                ->orderBy('sort_order')->get(['id', 'name', 'color'])
            : collect();

        return view('training.lag', [
            'teams' => $teams,
            'cats' => $cats,
            'company' => $company,
        ]);
    }

    public function storeTeam(Request $request)
    {
        $this->guard();
        $team = TrainingTeam::create($this->validated($request));
        $this->syncWishes($team, $request);

        return response()->json($this->card($team->fresh(['category', 'wishes'])));
    }

    public function updateTeam(Request $request, TrainingTeam $team)
    {
        $this->guard();
        $team->update($this->validated($request));
        $this->syncWishes($team, $request);

        return response()->json($this->card($team->fresh(['category', 'wishes'])));
    }

    /** Erstatt lagets ønsker med de innsendte (kun rader med ukedag). */
    private function syncWishes(TrainingTeam $team, Request $request): void
    {
        $request->validate([
            'wishes' => ['nullable', 'array'],
            'wishes.*.priority' => ['nullable', 'integer', 'min:1', 'max:3'],
            'wishes.*.weekday' => ['nullable', 'string', 'max:20'],
            'wishes.*.time' => ['nullable', 'string', 'max:40'],
            'wishes.*.note' => ['nullable', 'string', 'max:255'],
        ]);

        $team->wishes()->delete();
        foreach ($request->input('wishes', []) as $i => $w) {
            if (empty($w['weekday'])) {
                continue;
            }
            $team->wishes()->create([
                'company_id' => $team->company_id,
                'priority' => $w['priority'] ?? ($i + 1),
                'weekday' => $w['weekday'],
                'time' => $w['time'] ?? null,
                'note' => $w['note'] ?? null,
            ]);
        }
    }

    public function destroyTeam(TrainingTeam $team)
    {
        $this->guard();
        $team->delete();

        return response()->json(['ok' => true]);
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'birth_year' => ['nullable', 'string', 'max:40'],
            'grade' => ['nullable', 'string', 'max:40'],
            'players' => ['nullable', 'integer', 'min:0', 'max:999'],
            'coaches' => ['nullable', 'integer', 'min:0', 'max:99'],
            'sessions_per_week' => ['nullable', 'string', 'max:20'],
            'area_indoor' => ['nullable', 'string', 'max:60'],
            'area_outdoor' => ['nullable', 'string', 'max:60'],
            'requires_indoor' => ['nullable', 'boolean'],
            'coach_unavailable' => ['nullable', 'string', 'max:255'],
        ]);
    }

    /* ---------- Rutenett (tildeling) ---------- */

    private function season(Company $company): TrainingSeason
    {
        return TrainingSeason::firstOrCreate(
            ['company_id' => $company->id, 'name' => '2026/2027 ute'],
            ['type' => 'ute', 'is_active' => true]
        );
    }

    /** Farge for en blokk: lagets idrettsfarge, ellers etter eier. */
    private function blockColor(TrainingAssignment $a): string
    {
        if ($a->team?->category?->color) {
            return $a->team->category->color;
        }

        return match ($a->org) {
            'Spind' => '#fb471f',
            'Bobcats' => '#1a9aa0',
            default => '#2f6fd6',
        };
    }

    private function assignmentCard(TrainingAssignment $a): array
    {
        return [
            'id' => $a->id,
            'facility_id' => $a->training_facility_id,
            'weekday' => $a->weekday,
            'block_start' => substr((string) $a->block_start, 0, 5),
            'block_end' => substr((string) $a->block_end, 0, 5),
            'team_id' => $a->training_team_id,
            'label' => $a->label ?? $a->team?->name,
            'org' => $a->org ?? 'FLIK',
            'locked' => (bool) $a->locked,
            'color' => $this->blockColor($a),
        ];
    }

    public function grid()
    {
        $this->guard();
        $company = $this->company();
        if (! $company) {
            abort(404);
        }
        $season = $this->season($company);

        $facilities = TrainingFacility::where('company_id', $company->id)->orderBy('name')
            ->get()->map(fn (TrainingFacility $f) => $this->facilityCard($f))->values();

        $teams = TrainingTeam::where('company_id', $company->id)->with('category')->orderBy('name')
            ->get()->map(fn (TrainingTeam $t) => [
                'id' => $t->id, 'name' => $t->name,
                'sport' => $t->category?->name, 'color' => $t->category?->color,
            ])->values();

        $assignments = TrainingAssignment::where('company_id', $company->id)
            ->where('training_season_id', $season->id)->with('team.category')
            ->get()->map(fn (TrainingAssignment $a) => $this->assignmentCard($a))->values();

        $versions = $this->versionList($company, $season);

        return view('training.rutenett', compact('facilities', 'teams', 'assignments', 'versions', 'company'));
    }

    /* ---------- Versjoner (øyeblikksbilder av rutenettet) ---------- */

    private function versionList(Company $company, TrainingSeason $season)
    {
        return TrainingPlanVersion::where('company_id', $company->id)
            ->where('training_season_id', $season->id)->orderByDesc('created_at')
            ->get()->map(fn (TrainingPlanVersion $v) => [
                'id' => $v->id,
                'name' => $v->name,
                'is_auto' => $v->is_auto,
                'created_at' => $v->created_at?->format('d.m.Y H:i'),
                'count' => is_array($v->snapshot) ? count($v->snapshot) : 0,
            ])->values();
    }

    /** Serialiser gjeldende arbeidsplan til et øyeblikksbilde. */
    private function snapshotCurrent(Company $company, TrainingSeason $season): array
    {
        return TrainingAssignment::where('company_id', $company->id)
            ->where('training_season_id', $season->id)->get()
            ->map(fn (TrainingAssignment $a) => [
                'facility_id' => $a->training_facility_id,
                'team_id' => $a->training_team_id,
                'label' => $a->label,
                'org' => $a->org,
                'locked' => (bool) $a->locked,
                'weekday' => $a->weekday,
                'block_start' => substr((string) $a->block_start, 0, 5),
                'block_end' => substr((string) $a->block_end, 0, 5),
            ])->values()->all();
    }

    /** Lag en versjon av gjeldende plan (brukes av «Lagre» og auto før AI). */
    private function makeVersion(Company $company, TrainingSeason $season, string $name, bool $auto = false): TrainingPlanVersion
    {
        return TrainingPlanVersion::create([
            'company_id' => $company->id,
            'training_season_id' => $season->id,
            'name' => $name,
            'snapshot' => $this->snapshotCurrent($company, $season),
            'is_auto' => $auto,
            'created_by' => auth()->id(),
        ]);
    }

    public function saveVersion(Request $request)
    {
        $this->guard();
        $company = $this->company();
        $data = $request->validate(['name' => ['required', 'string', 'max:80']]);
        $season = $this->season($company);
        $this->makeVersion($company, $season, $data['name']);

        return response()->json(['versions' => $this->versionList($company, $season)]);
    }

    public function restoreVersion(TrainingPlanVersion $version)
    {
        $this->guard();
        $company = $this->company();
        $season = $this->season($company);

        // Ta en auto-versjon av det som er nå, så «gjenopprett» også kan angres
        $this->makeVersion($company, $season, 'Før gjenoppretting '.now()->format('d.m H:i'), true);

        DB::transaction(function () use ($company, $season, $version) {
            TrainingAssignment::where('company_id', $company->id)
                ->where('training_season_id', $season->id)->delete();

            foreach ($version->snapshot ?? [] as $row) {
                $fid = $row['facility_id'] ?? $row['training_facility_id'] ?? null;
                if (! $fid || empty($row['weekday']) || empty($row['block_start']) || empty($row['block_end'])) {
                    continue;
                }
                TrainingAssignment::create([
                    'company_id' => $company->id,
                    'training_season_id' => $season->id,
                    'training_facility_id' => $fid,
                    'training_team_id' => $row['team_id'] ?? $row['training_team_id'] ?? null,
                    'label' => $row['label'] ?? null,
                    'org' => $row['org'] ?? 'FLIK',
                    'locked' => $row['locked'] ?? false,
                    'weekday' => $row['weekday'],
                    'block_start' => $row['block_start'],
                    'block_end' => $row['block_end'],
                    'manual_override' => true,
                ]);
            }
        });

        $assignments = TrainingAssignment::where('company_id', $company->id)
            ->where('training_season_id', $season->id)->with('team.category')
            ->get()->map(fn (TrainingAssignment $a) => $this->assignmentCard($a))->values();

        return response()->json([
            'assignments' => $assignments,
            'versions' => $this->versionList($company, $season),
        ]);
    }

    public function destroyVersion(TrainingPlanVersion $version)
    {
        $this->guard();
        $company = $this->company();
        $version->delete();

        return response()->json(['versions' => $this->versionList($company, $this->season($company))]);
    }

    /* ---------- AI-forslag (steg 4) ---------- */

    private function extractJson(string $text): array
    {
        $start = strpos($text, '{');
        $end = strrpos($text, '}');
        if ($start === false || $end === false || $end < $start) {
            return [];
        }
        $j = json_decode(substr($text, $start, $end - $start + 1), true);

        return is_array($j) ? $j : [];
    }

    /** Robust uthenting av blokk-lista fra modellsvar (kodegjerder, ren array, eller {blocks:[...]}). */
    private function extractBlocks(string $text): array
    {
        $t = trim(preg_replace('/```(?:json)?/i', '', $text));

        $j = json_decode($t, true);
        if (is_array($j)) {
            if (isset($j['blocks']) && is_array($j['blocks'])) {
                return $j['blocks'];
            }
            if (array_is_list($j)) {
                return $j;
            }
        }
        // Klipp ut { ... }
        $s = strpos($t, '{');
        $e = strrpos($t, '}');
        if ($s !== false && $e !== false && $e > $s) {
            $j = json_decode(substr($t, $s, $e - $s + 1), true);
            if (isset($j['blocks']) && is_array($j['blocks'])) {
                return $j['blocks'];
            }
        }
        // Klipp ut [ ... ]
        $s = strpos($t, '[');
        $e = strrpos($t, ']');
        if ($s !== false && $e !== false && $e > $s) {
            $j = json_decode(substr($t, $s, $e - $s + 1), true);
            if (is_array($j) && array_is_list($j)) {
                return $j;
            }
        }

        return [];
    }

    public function aiPropose(Request $request)
    {
        $this->guard();
        $company = $this->company();
        $data = $request->validate([
            'scope' => ['required', 'in:alle,dag,anlegg'],
            'day' => ['nullable', 'string', 'max:20'],
            'facility_id' => ['nullable', 'exists:training_facilities,id'],
            'instruction' => ['nullable', 'string', 'max:500'],
        ]);

        $key = config('services.anthropic.key');
        if (! $key) {
            return response()->json(['error' => 'AI er ikke satt opp (mangler API-nøkkel).'], 400);
        }
        $model = config('services.anthropic.model_pro') ?: 'claude-opus-5';
        $season = $this->season($company);

        $facilities = TrainingFacility::where('company_id', $company->id)->get();
        $teams = TrainingTeam::where('company_id', $company->id)->with(['category', 'wishes'])->get();
        $assign = TrainingAssignment::where('company_id', $company->id)
            ->where('training_season_id', $season->id)->get();

        // Kontekst til modellen
        $facLines = $facilities->map(fn ($f) => '- '.$f->name.' (idretter: '
            .(is_array($f->allowed_sports) ? implode(', ', $f->allowed_sports) : '–')
            .', soner: '.($f->zones ?? 1).', status: '.($f->status ?? 'aktiv').')')->implode("\n");

        $teamLines = $teams->map(function (TrainingTeam $t) {
            $w = $t->relationLoaded('wishes') ? $t->wishes->map(fn ($x) => ($x->weekday ?? '')
                .' '.($x->time ?? ''))->filter()->implode('; ') : '';

            return '- '.$t->name.' ('.($t->category?->name ?? 'idrett?').'): '
                .'økter/uke='.($t->sessions_per_week ?? '?')
                .', innekrav='.($t->requires_indoor ? 'ja' : 'nei')
                .($t->players ? ', spillere='.$t->players : '')
                .($w ? ', ønsker: '.$w : '')
                .($t->coach_unavailable ? ', trener utilgj.: '.$t->coach_unavailable : '');
        })->implode("\n");

        $lockLines = $assign->where('locked', true)->map(fn ($a) => '- '
            .$facilities->firstWhere('id', $a->training_facility_id)?->name.' · '.$a->weekday.' '
            .substr((string) $a->block_start, 0, 5).'–'.substr((string) $a->block_end, 0, 5)
            .' ('.$a->org.')')->implode("\n") ?: '(ingen)';

        $scopeText = match ($data['scope']) {
            'dag' => 'KUN for '.($data['day'] ?? '').'. Ikke foreslå noe for andre dager.',
            'anlegg' => 'KUN for anlegget «'.($facilities->firstWhere('id', $data['facility_id'])?->name).'». Ikke foreslå noe for andre anlegg.',
            default => 'for hele uken (mandag–fredag).',
        };

        $system = 'Du er en erfaren fotball-koordinator som fordeler treningstider for en idrettsklubb. '
            .'Du får anlegg, lag (med behov og ønsker), og låste tider som IKKE kan brukes. '
            .'Lag et forslag til fordeling '.$scopeText.' '
            ."\n\nREGLER:\n"
            ."- Hvert lag skal ha så mange økter per uke som angitt (økter/uke).\n"
            ."- Fotballag med innekrav=ja skal ha minst én økt i «Alcoa fotball hall».\n"
            ."- Yngste lag trener tidligst (fra 16:00), eldste sist (mot 22:00).\n"
            ."- Unngå fredager når det er mulig – mange lag ønsker ikke å trene fredag.\n"
            ."- Samme lag kan ALDRI være på to anlegg samtidig – MEN 3er bane A, B og C er én delt ressurs, så samme lag kan stå på alle tre samtidig.\n"
            ."- Bruk aldri tider som er låst hos annen klubb (listen under).\n"
            ."- Bruk kun anlegg som tillater lagets idrett.\n"
            ."- Tider er mellom 16:00 og 22:00 i 30-minutters steg. Respekter lagenes ønsker og trenernes utilgjengelighet der det går.\n"
            ."- Maks ETT lag per sone samtidig på samme anlegg. De fleste anlegg har 1 sone = kun ett lag om gangen. Sett aldri to eller flere lag på samme anlegg til samme tid med mindre «soner» er større enn 1 (unntaket er 3er bane A/B/C som er én delt ressurs).\n"
            ."- Gi ALDRI et lag flere økter enn «økter/uke». Ved forslag for én enkelt dag: maks én økt per lag den dagen.\n"
            ."- Ikke fyll ledige felt bare for å fylle – det er helt greit at et anlegg står tomt. Kvalitet framfor mengde.\n\n"
            .'Svar KUN med JSON på formen {"blocks":[{"facility":"<anleggsnavn>","weekday":"Mandag","start":"16:00","end":"17:30","team":"<lagnavn>"}]}. '
            .'Bruk eksakte anleggsnavn og lagnavn fra listene. Ingen forklaring utenfor JSON.';

        $ctx = "ANLEGG:\n".$facLines."\n\nLAG:\n".$teamLines."\n\nLÅSTE TIDER (kan ikke brukes):\n".$lockLines
            .($data['instruction'] ? "\n\nEKSTRA INSTRUKS FRA BRUKER:\n".$data['instruction'] : '');

        // Prøv pro-modellen; fall tilbake til arbeidsmodellen om den ikke finnes.
        $models = array_values(array_unique(array_filter([$model, config('services.anthropic.model')])));
        $resp = null;
        $errBody = '';
        foreach ($models as $m) {
            $resp = Http::withHeaders([
                'x-api-key' => $key,
                'anthropic-version' => '2023-06-01',
                'content-type' => 'application/json',
            ])->timeout(180)->post('https://api.anthropic.com/v1/messages', [
                'model' => $m,
                'max_tokens' => 8000,
                'system' => $system,
                'messages' => [['role' => 'user', 'content' => $ctx]],
            ]);
            if ($resp->successful()) {
                $model = $m;
                break;
            }
            $errBody = 'modell '.$m.' → '.$resp->status().' '.mb_substr($resp->body(), 0, 400);
        }

        if (! $resp || ! $resp->successful()) {
            return response()->json(['error' => 'AI-tjenesten svarte ikke: '.$errBody], 502);
        }

        // Opus kan legge et «thinking»-blokk først – hent teksten fra ALLE text-blokker.
        $rawText = collect($resp->json('content', []))
            ->where('type', 'text')->pluck('text')->implode('');
        $blocks = $this->extractBlocks($rawText);
        if (! is_array($blocks) || ! count($blocks)) {
            return response()->json([
                'error' => 'AI ga ikke et tolkbart forslag (modell: '.$model.'). Modellen svarte: '
                    .mb_substr(trim($rawText), 0, 500),
            ], 422);
        }

        // Oppslag for validering
        $norm = fn (string $s) => preg_replace('/\s+/', '', mb_strtolower($s));
        $facByName = $facilities->keyBy(fn ($f) => $norm($f->name));
        $teamByName = $teams->keyBy(fn ($t) => $norm($t->name));
        $days = ['Mandag', 'Tirsdag', 'Onsdag', 'Torsdag', 'Fredag'];
        $timeOk = fn ($t) => (bool) preg_match('/^([01]?\d|2[0-2]):[0-5]\d$/', (string) $t);

        // Trygghet: auto-versjon FØR vi rører planen
        $this->makeVersion($company, $season, 'Før AI '.now()->format('d.m H:i'), true);

        // Slett gjeldende IKKE-låste FLIK-blokker i scope
        $del = TrainingAssignment::where('company_id', $company->id)
            ->where('training_season_id', $season->id)->where('locked', false);
        if ($data['scope'] === 'dag') {
            $del->where('weekday', $data['day']);
        } elseif ($data['scope'] === 'anlegg') {
            $del->where('training_facility_id', $data['facility_id']);
        }
        $del->delete();

        $n = 0;
        foreach ($blocks as $b) {
            $f = $facByName->get($norm((string) ($b['facility'] ?? '')));
            $wd = $b['weekday'] ?? null;
            $st = $b['start'] ?? null;
            $en = $b['end'] ?? null;
            if (! $f || ! in_array($wd, $days, true) || ! $timeOk($st) || ! $timeOk($en) || $st >= $en) {
                continue;
            }
            // hold oss innenfor scope
            if ($data['scope'] === 'dag' && $wd !== $data['day']) {
                continue;
            }
            if ($data['scope'] === 'anlegg' && $f->id !== (int) $data['facility_id']) {
                continue;
            }
            $team = $teamByName->get($norm((string) ($b['team'] ?? '')));
            TrainingAssignment::create([
                'company_id' => $company->id,
                'training_season_id' => $season->id,
                'training_facility_id' => $f->id,
                'training_team_id' => $team?->id,
                'label' => $b['team'] ?? null,
                'org' => 'FLIK',
                'locked' => false,
                'weekday' => $wd,
                'block_start' => $st,
                'block_end' => $en,
                'manual_override' => false,
            ]);
            $n++;
        }

        // Lagre forslaget som egen navngitt versjon
        $this->makeVersion($company, $season, 'AI-forslag '.now()->format('d.m H:i'));

        $assignments = TrainingAssignment::where('company_id', $company->id)
            ->where('training_season_id', $season->id)->with('team.category')
            ->get()->map(fn (TrainingAssignment $a) => $this->assignmentCard($a))->values();

        return response()->json([
            'assignments' => $assignments,
            'versions' => $this->versionList($company, $season),
            'placed' => $n,
            'model' => $model,
            'note' => ($model !== $models[0] && $errBody) ? 'Brukte '.$model.' – pro-modellen feilet ('.$errBody.').' : null,
        ]);
    }

    private function validatedAssignment(Request $request): array
    {
        return $request->validate([
            'facility_id' => ['required', 'exists:training_facilities,id'],
            'team_id' => ['nullable', 'exists:training_teams,id'],
            'label' => ['nullable', 'string', 'max:120'],
            'org' => ['nullable', 'string', 'max:40'],
            'locked' => ['nullable', 'boolean'],
            'weekday' => ['required', 'string', 'max:20'],
            'block_start' => ['required', 'string', 'max:5'],
            'block_end' => ['required', 'string', 'max:5'],
        ]);
    }

    public function storeAssignment(Request $request)
    {
        $this->guard();
        $company = $this->company();
        $data = $this->validatedAssignment($request);
        $season = $this->season($company);

        $a = TrainingAssignment::create([
            'company_id' => $company->id,
            'training_season_id' => $season->id,
            'training_facility_id' => $data['facility_id'],
            'training_team_id' => $data['team_id'] ?? null,
            'label' => $data['label'] ?? null,
            'org' => $data['org'] ?? 'FLIK',
            'locked' => $request->boolean('locked'),
            'weekday' => $data['weekday'],
            'block_start' => $data['block_start'],
            'block_end' => $data['block_end'],
            'manual_override' => true,
        ]);

        return response()->json($this->assignmentCard($a->load('team.category')));
    }

    public function updateAssignment(Request $request, TrainingAssignment $assignment)
    {
        $this->guard();
        $data = $this->validatedAssignment($request);

        $assignment->update([
            'training_facility_id' => $data['facility_id'],
            'training_team_id' => $data['team_id'] ?? null,
            'label' => $data['label'] ?? null,
            'org' => $data['org'] ?? 'FLIK',
            'locked' => $request->boolean('locked'),
            'weekday' => $data['weekday'],
            'block_start' => $data['block_start'],
            'block_end' => $data['block_end'],
            'manual_override' => true,
        ]);

        return response()->json($this->assignmentCard($assignment->fresh('team.category')));
    }

    public function destroyAssignment(TrainingAssignment $assignment)
    {
        $this->guard();
        $assignment->delete();

        return response()->json(['ok' => true]);
    }

    /* ---------- Anlegg ---------- */

    public function facilities()
    {
        $this->guard();
        $company = $this->company();

        $facilities = $company
            ? TrainingFacility::where('company_id', $company->id)->orderBy('name')->get()
                ->map(fn (TrainingFacility $f) => $this->facilityCard($f))->values()
            : collect();

        $sports = $company
            ? Category::where('company_id', $company->id)->whereNull('archived_at')
                ->orderBy('sort_order')->pluck('name')->values()
            : collect();

        return view('training.anlegg', [
            'facilities' => $facilities,
            'sports' => $sports,
            'company' => $company,
        ]);
    }

    public function storeFacility(Request $request)
    {
        $this->guard();
        $f = TrainingFacility::create($this->validatedFacility($request));

        return response()->json($this->facilityCard($f));
    }

    public function updateFacility(Request $request, TrainingFacility $facility)
    {
        $this->guard();
        $facility->update($this->validatedFacility($request));

        return response()->json($this->facilityCard($facility->fresh()));
    }

    public function destroyFacility(TrainingFacility $facility)
    {
        $this->guard();
        $facility->delete();

        return response()->json(['ok' => true]);
    }

    private function validatedFacility(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'type' => ['nullable', 'string', 'max:40'],
            'zones' => ['nullable', 'integer', 'min:1', 'max:20'],
            'status' => ['nullable', 'string', 'max:20'],
            'allowed_sports' => ['nullable', 'array'],
            'allowed_sports.*' => ['string', 'max:60'],
        ]);
    }

    private function facilityCard(TrainingFacility $f): array
    {
        return [
            'id' => $f->id,
            'name' => $f->name,
            'type' => $f->type,
            'zones' => $f->zones,
            'status' => $f->status,
            'allowed_sports' => $f->allowed_sports ?? [],
        ];
    }

    private function card(TrainingTeam $t): array
    {
        return [
            'id' => $t->id,
            'name' => $t->name,
            'category_id' => $t->category_id,
            'sport' => $t->category?->name,
            'color' => $t->category?->color,
            'birth_year' => $t->birth_year,
            'grade' => $t->grade,
            'players' => $t->players,
            'coaches' => $t->coaches,
            'sessions_per_week' => $t->sessions_per_week,
            'area_indoor' => $t->area_indoor,
            'area_outdoor' => $t->area_outdoor,
            'requires_indoor' => (bool) $t->requires_indoor,
            'coach_unavailable' => $t->coach_unavailable,
            'wishes' => $t->relationLoaded('wishes')
                ? $t->wishes->map(fn ($w) => [
                    'priority' => $w->priority,
                    'weekday' => $w->weekday,
                    'time' => $w->time,
                    'note' => $w->note,
                ])->values()
                : [],
        ];
    }
}
