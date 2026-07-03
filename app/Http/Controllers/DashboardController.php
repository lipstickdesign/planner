<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Company;
use App\Models\Destination;
use App\Models\Event;

class DashboardController extends Controller
{
    public function index()
    {
        $company = Company::where('slug', 'flik')->first();

        if ($company) {
            app()->instance('currentCompany', $company);
        }

        $events = Event::with(['category', 'responsible', 'tasks.destinations'])
            ->orderBy('event_date')
            ->get()
            ->map(fn (Event $e) => $e->toCard())
            ->values();

        $user = auth()->user();

        $teamUsers = $company
            ? $company->users()->orderBy('name')->get()->map(fn ($u) => [
                'id' => $u->id,
                'name' => $u->name,
                'email' => $u->email,
                'title' => $u->pivot->title,
                'area' => $u->pivot->area,
                'role' => $u->pivot->role ?? 'medlem',
                'is_platform_admin' => (bool) $u->is_platform_admin,
            ])->values()
            : collect();

        return view('dashboard', [
            'company' => $company,
            'events' => $events,
            'user' => $user,
            'canEdit' => $user->isCompanyAdmin(),
            'categories' => Category::orderBy('sort_order')->get(['id', 'name', 'color']),
            'members' => $company ? $company->users()->orderBy('name')->get(['users.id', 'name']) : collect(),
            'destinations' => Destination::orderBy('name')->get(['id', 'name']),
            'teamUsers' => $teamUsers,
        ]);
    }
}
