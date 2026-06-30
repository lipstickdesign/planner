<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Event;

class DashboardController extends Controller
{
    public function index()
    {
        $company = Company::where('slug', 'flik')->first();

        // Sett aktivt selskap (tenant) for skoperte spørringer.
        if ($company) {
            app()->instance('currentCompany', $company);
        }

        $approvalMap = [
            'utkast' => 'Utkast',
            'til_godkjenning' => 'Til godkjenning',
            'godkjent' => 'Godkjent',
            'internt' => 'Internt',
        ];
        $statusMap = [
            'planlagt' => 'Planlagt',
            'under_arbeid' => 'Under arbeid',
            'klar' => 'Klar for publisering',
            'publisert' => 'Publisert',
        ];

        $events = Event::with(['category', 'responsible', 'tasks.destinations'])
            ->orderBy('event_date')
            ->get()
            ->map(function (Event $e) use ($approvalMap, $statusMap) {
                return [
                    'id' => $e->id,
                    'date' => optional($e->event_date)->format('Y-m-d'),
                    'sport' => $e->category->name ?? 'Administrasjon',
                    'color' => $e->category->color ?? '#5a7184',
                    'type' => $e->type,
                    'title' => $e->title,
                    'mal' => $e->goal,
                    'desc' => $e->description,
                    'ansvarlig' => $e->responsible->name ?? null,
                    'recur' => $e->recurrence,
                    'approval' => $approvalMap[$e->approval_status] ?? 'Utkast',
                    'landing' => $e->landing_url,
                    'hoopit' => $e->signup_url,
                    'notat' => $e->internal_note,
                    'posts' => $e->tasks
                        ->sortBy('publish_date')
                        ->values()
                        ->map(function ($t) use ($statusMap) {
                            return [
                                'label' => $t->label,
                                'date' => optional($t->publish_date)->format('Y-m-d'),
                                'status' => $statusMap[$t->status] ?? 'Planlagt',
                                'pages' => $t->destinations->pluck('name')->all(),
                                'text' => $t->draft_url,
                            ];
                        })->all(),
                ];
            })
            ->values();

        return view('dashboard', [
            'company' => $company,
            'events' => $events,
            'user' => auth()->user(),
        ]);
    }
}
