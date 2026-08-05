<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Kamp;
use Illuminate\Support\Facades\Cache;

class PublicKampController extends Controller
{
    /**
     * Offentlig, innebygdbar kampoversikt (iframe til klubbens nettside).
     * Viser hjemmekamper fra i dag og sju dager fram – et rullende vindu
     * som alltid er oppdatert uavhengig av ukedag.
     */
    public function feed(string $slug)
    {
        $company = Company::where('slug', $slug)->firstOrFail();
        app()->instance('currentCompany', $company);

        $today = now('Europe/Oslo')->startOfDay();
        $end = $today->copy()->addDays(7);

        // Cache-nøkkel inkluderer dagens dato → ruller automatisk ved midnatt.
        // Kort levetid slik at nye importer/endringer slår inn i løpet av kort tid.
        $cacheKey = 'kampfeed_'.$company->id.'_'.$today->format('Ymd');
        $kamper = Cache::remember($cacheKey, now()->addMinutes(30), function () use ($today, $end) {
            return Kamp::query()
                ->where('home', true)
                ->whereBetween('match_date', [$today->format('Y-m-d'), $end->format('Y-m-d')])
                ->orderBy('match_date')->orderBy('match_time')
                ->get()
                ->map(fn (Kamp $k) => [
                    'date' => optional($k->match_date)->format('Y-m-d'),
                    'time' => $k->match_time ? substr((string) $k->match_time, 0, 5) : null,
                    'home_team' => $k->home_team ?: $k->title,
                    'away_team' => $k->away_team,
                    'title' => $k->title,
                    'location' => $k->location,
                    'tournament' => $k->tournament,
                    'note' => $k->note,
                ])->values()->all();
        });

        return view('embed.kamper', [
            'company' => $company,
            'kamper' => $kamper,
        ]);
    }
}
