<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Event;

class PublicWheelController extends Controller
{
    /**
     * Offentlig, innebygdbart årshjul (iframe til klubbens nettside).
     * Ikke-klikkbare prikker, uten Administrasjon og interne arrangement.
     */
    public function wheel(string $slug)
    {
        $company = Company::where('slug', $slug)->firstOrFail();
        app()->instance('currentCompany', $company);

        $events = Event::with('category')
            ->whereNotNull('event_date')
            ->where('approval_status', '!=', 'internt')
            ->get()
            ->filter(fn (Event $e) => $e->category && $e->category->name !== 'Administrasjon')
            ->map(fn (Event $e) => [
                'date' => optional($e->event_date)->format('Y-m-d'),
                'title' => $e->title,
                'sport' => $e->category->name,
                'color' => $e->category->color,
            ])->values();

        return view('embed.wheel', [
            'company' => $company,
            'events' => $events,
        ]);
    }
}
