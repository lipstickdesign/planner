<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Company;
use App\Models\TrainingFacility;
use App\Models\TrainingTeam;
use Illuminate\Http\Request;

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

        return view('training.index');
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
