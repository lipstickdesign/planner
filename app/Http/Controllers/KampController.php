<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Kamp;
use App\Services\KampImportService;
use Illuminate\Http\Request;

class KampController extends Controller
{
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
