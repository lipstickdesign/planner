<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

/**
 * Superadmin-panel for å administrere kunder (selskaper).
 * Kun plattform-superadmin har tilgang. Brukere knyttes kun til
 * selskapet de opprettes under – superadmin ser alle via kontobytte.
 */
class CustomerController extends Controller
{
    private function guard(Request $request): void
    {
        abort_unless($request->user()->is_platform_admin, 403);
    }

    public function index(Request $request)
    {
        $this->guard($request);

        $rows = Company::orderBy('name')->get()->map(fn (Company $c) => [
            'id' => $c->id,
            'name' => $c->name,
            'slug' => $c->slug,
            'org_type' => $c->org_type,
            'users' => DB::table('company_user')->where('company_id', $c->id)->count(),
            'events' => DB::table('events')->where('company_id', $c->id)->whereNull('deleted_at')->count(),
        ])->values();

        return response()->json(['customers' => $rows]);
    }

    public function store(Request $request)
    {
        $this->guard($request);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'org_type' => ['required', Rule::in(['idrettsklubb', 'bedrift', 'forening', 'annet'])],
            'theme' => ['required', Rule::in(['blue', 'red', 'green'])],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'admin_name' => ['required', 'string', 'max:255'],
            'admin_email' => ['required', 'email', 'max:255'],
            'admin_password' => ['required', 'string', 'min:8'],
        ]);

        // Unik slug
        $slug = Str::slug($data['name']) ?: 'kunde';
        $base = $slug;
        $n = 2;
        while (Company::where('slug', $slug)->exists()) {
            $slug = $base.'-'.$n++;
        }

        $company = Company::create([
            'name' => $data['name'],
            'slug' => $slug,
            'org_type' => $data['org_type'],
            'settings' => [
                'theme' => $data['theme'],
                'subtitle' => $data['subtitle'] ?? null,
            ],
        ]);

        // Første admin – knyttes KUN til dette selskapet.
        // Finnes e-posten fra før (f.eks. byrå med flere kunder), gjenbrukes brukeren.
        $admin = User::firstOrCreate(
            ['email' => $data['admin_email']],
            ['name' => $data['admin_name'], 'password' => Hash::make($data['admin_password'])]
        );

        $company->users()->syncWithoutDetaching([
            $admin->id => [
                'title' => 'Administrator',
                'role' => 'admin',
                'status' => 'active',
            ],
        ]);

        // Starter-innhold så appen er brukbar med en gang.
        $company->categories()->create(['name' => 'Generelt', 'color' => '#5a7184', 'sort_order' => 0]);

        return response()->json(['ok' => true, 'customer' => [
            'id' => $company->id,
            'name' => $company->name,
            'slug' => $company->slug,
            'org_type' => $company->org_type,
            'users' => 1,
            'events' => 0,
        ]]);
    }
}
