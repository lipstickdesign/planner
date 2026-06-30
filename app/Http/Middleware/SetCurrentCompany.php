<?php

namespace App\Http\Middleware;

use App\Models\Company;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Setter aktivt selskap (tenant) for innlogget bruker.
 * Plattform-admin og byrå-brukere kan bytte selskap via session('active_company_id').
 */
class SetCurrentCompany
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user) {
            $companyId = session('active_company_id');
            $company = null;

            if ($companyId) {
                $company = $user->is_platform_admin
                    ? Company::find($companyId)
                    : $user->companies()->where('companies.id', $companyId)->first();
            }

            if (! $company) {
                $company = $user->is_platform_admin
                    ? Company::query()->first()
                    : $user->companies()->first();
            }

            if ($company) {
                app()->instance('currentCompany', $company);
                session(['active_company_id' => $company->id]);
            }
        }

        return $next($request);
    }
}
