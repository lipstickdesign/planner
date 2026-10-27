<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Kamp;
use App\Services\KampImportService;
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

        return view('kamper', compact('kampdata', 'matches', 'sports', 'company'));
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
