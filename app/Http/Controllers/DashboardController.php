<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Company;
use App\Models\ContentIdea;
use App\Models\DashboardLayout;
use App\Models\Destination;
use App\Models\Event;
use App\Models\Kamp;
use App\Models\KlubblivPost;
use App\Models\TrainingSchedule;
use App\Services\WeatherService;

class DashboardController extends Controller
{
    public function index(WeatherService $weather)
    {
        $user = auth()->user();

        // Aktivt selskap kommer fra SetCurrentCompany (brukerens eget / valgt).
        // Superadmin uten valgt selskap lander på første; ellers første selskap brukeren tilhører.
        $company = app()->bound('currentCompany') ? app('currentCompany') : null;
        if (! $company) {
            $company = $user->is_platform_admin
                ? Company::query()->orderBy('name')->first()
                : $user->companies()->first();
            if ($company) {
                app()->instance('currentCompany', $company);
            }
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

        $destMap = Destination::pluck('name', 'id')->all();

        $klubbliv = KlubblivPost::orderBy('publish_date')
            ->get()
            ->map(fn (KlubblivPost $p) => $p->card($destMap))
            ->values();

        $ideas = ContentIdea::orderBy('group')->orderBy('sort_order')
            ->get()
            ->map(fn (ContentIdea $i) => $i->card())
            ->values();

        $training = TrainingSchedule::with('category')
            ->orderBy('weekday')->orderBy('start_time')
            ->get()
            ->map(fn (TrainingSchedule $t) => $t->card())
            ->values();

        $kamper = Kamp::with('category')
            ->whereNotNull('match_date')
            ->whereDate('match_date', '>=', now()->subDay())
            ->orderBy('match_date')->orderBy('match_time')
            ->get()
            ->map(fn (Kamp $k) => $k->card())
            ->values();

        $cSettings = ($company && is_array($company->settings)) ? $company->settings : [];
        $brand = [
            'name' => $company->name ?? 'Vivu Planner',
            'subtitle' => ($cSettings['subtitle'] ?? null) ?: ($company->name ?? null),
            'theme' => $cSettings['theme'] ?? 'blue',
            'mark' => $company?->logo_path ?: null,
        ];

        // Selskaper brukeren kan bytte mellom (superadmin ser alle)
        $companies = $user->is_platform_admin
            ? Company::orderBy('name')->get(['id', 'name'])
            : $user->companies()->orderBy('name')->get(['companies.id', 'companies.name']);

        // Dashboard-oppsett: brukerens eget → selskaps-standard → innebygd standard
        $builtinDefault = ['a' => ['tellere', 'publiser', 'utenplan', 'travle', 'tomrom'], 'b' => ['idag', 'tips']];
        $companyDefault = DashboardLayout::whereNull('user_id')->first();
        $userLayout = DashboardLayout::where('user_id', $user->id)->first();
        $resolvedDefault = $companyDefault ? $companyDefault->layout : $builtinDefault;
        $layout = $userLayout ? $userLayout->layout : $resolvedDefault;

        return view('dashboard', [
            'company' => $company,
            'events' => $events,
            'user' => $user,
            'canEdit' => $user->isCompanyAdmin(),
            'categories' => Category::orderBy('sort_order')->get(['id', 'name', 'color']),
            'members' => $company ? $company->users()->orderBy('name')->get(['users.id', 'name']) : collect(),
            'destinations' => Destination::orderBy('name')->get(['id', 'name']),
            'teamUsers' => $teamUsers,
            'klubbliv' => $klubbliv,
            'ideas' => $ideas,
            'training' => $training,
            'kamper' => $kamper,
            'weather' => $weather->week(),
            'layout' => $layout,
            'layoutDefault' => $resolvedDefault,
            'hasUserLayout' => (bool) $userLayout,
            'isSuperadmin' => (bool) $user->is_platform_admin,
            'companies' => $companies,
            'currentCompanyId' => $company?->id,
            'brand' => $brand,
        ]);
    }
}
