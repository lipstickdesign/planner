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
    /** iframe-variant (isolert, med auto-høyde). */
    public function feed(string $slug)
    {
        [$company, $sport, $kamper] = $this->collect($slug);
        $days = $this->groupDays($kamper);

        return view('embed.kamper', compact('company', 'days', 'sport'));
    }

    /**
     * Uten-iframe-variant: en <script> som skriver kampene rett inn i nettsiden,
     * så de arver sidens font og flyter naturlig (ingen fast høyde).
     */
    public function feedJs(string $slug)
    {
        [$company, $sport, $kamper] = $this->collect($slug);
        $days = $this->groupDays($kamper);

        $html = view('embed.kamper_list', compact('company', 'days', 'sport'))->render();

        $js = '(function(){var s=document.currentScript;if(!s)return;'
            .'var w=document.createElement("div");w.innerHTML='.json_encode($html, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES).';'
            .'while(w.firstChild){s.parentNode.insertBefore(w.firstChild,s);}})();';

        return response($js)
            ->header('Content-Type', 'application/javascript; charset=utf-8')
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Cache-Control', 'public, max-age=900');
    }

    /** @return array{0:Company,1:?string,2:array} */
    private function collect(string $slug): array
    {
        $company = Company::where('slug', $slug)->firstOrFail();
        app()->instance('currentCompany', $company);

        // Valgfritt: egen feed per idrett (?sport=Fotball). Uten = alle idretter.
        $sport = trim((string) request('sport')) ?: null;

        $today = now('Europe/Oslo')->startOfDay();
        $end = $today->copy()->addDays(7);

        // Cache-nøkkel inkluderer dagens dato → ruller automatisk ved midnatt.
        $cacheKey = 'kampfeed_'.$company->id.'_'.$today->format('Ymd').'_'.($sport ? mb_strtolower($sport) : 'alle');
        $kamper = Cache::remember($cacheKey, now()->addMinutes(30), function () use ($today, $end, $sport) {
            return Kamp::with('category')
                ->where('home', true)
                ->whereBetween('match_date', [$today->format('Y-m-d'), $end->format('Y-m-d')])
                ->when($sport, fn ($q) => $q->whereHas('category', fn ($c) => $c->whereRaw('LOWER(name) = ?', [mb_strtolower($sport)])))
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
                    'sport' => $k->category?->name,
                    'color' => $k->category?->color,
                ])->values()->all();
        });

        return [$company, $sport, $kamper];
    }

    /** Grupper kamper per dag med norsk datotekst (gjøres i PHP – ikke i Blade). */
    private function groupDays(array $kamper): array
    {
        $groups = [];
        foreach ($kamper as $m) {
            $groups[$m['date'] ?? ''][] = $m;
        }

        $days = [];
        foreach ($groups as $date => $matches) {
            $label = '';
            if ($date) {
                try {
                    $label = ucfirst(\Carbon\Carbon::parse($date)->locale('nb')->isoFormat('dddd D. MMMM'));
                } catch (\Throwable $e) {
                    $label = $date;
                }
            }
            $days[] = ['label' => $label, 'matches' => $matches];
        }

        return $days;
    }
}
