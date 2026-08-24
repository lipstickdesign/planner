<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\TrainingTeam;

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

    /**
     * Kontroll-visningen (regelsjekk). Foreløpig matet med FLIKs 2026/2027-data
     * innebygd i visningen. Bit 2b importerer lag til databasen (Lag-siden).
     */
    public function index()
    {
        $this->guard();

        return view('training.index');
    }

    /** Lag-oversikt – leser de importerte lagene fra databasen. */
    public function teams()
    {
        $this->guard();
        $company = $this->company();

        $teams = $company
            ? TrainingTeam::where('company_id', $company->id)
                ->with('category')
                ->orderBy('name')
                ->get()
            : collect();

        return view('training.lag', [
            'teams' => $teams,
            'company' => $company,
        ]);
    }
}
