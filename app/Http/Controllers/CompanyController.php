<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CompanyController extends Controller
{
    /** Kun superadmin, eller admin i nettopp dette selskapet. */
    private function guard(Request $request, Company $company): void
    {
        if (! $request->user()->is_platform_admin) {
            $cur = app()->bound('currentCompany') ? app('currentCompany') : null;
            abort_unless($cur && $cur->id === $company->id, 403);
        }
    }

    public function update(Request $request, Company $company)
    {
        $this->guard($request, $company);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'theme' => ['required', Rule::in(['blue', 'red', 'green'])],
        ]);

        $settings = $company->settings ?? [];
        $settings['subtitle'] = $data['subtitle'] ?? null;
        $settings['theme'] = $data['theme'];

        $company->update(['name' => $data['name'], 'settings' => $settings]);

        return response()->json(['ok' => true]);
    }

    /** Rediger lista over hovedmål (lagres i settings – ingen logikk henger på disse). */
    public function updateGoals(Request $request, Company $company)
    {
        $this->guard($request, $company);

        $data = $request->validate([
            'goals' => ['present', 'array'],
            'goals.*' => ['string', 'max:60'],
        ]);

        $goals = array_values(array_unique(array_filter(
            array_map(fn ($g) => trim($g), $data['goals']),
            fn ($g) => $g !== ''
        )));

        $settings = $company->settings ?? [];
        $settings['goals'] = $goals;
        $company->update(['settings' => $settings]);

        return response()->json(['ok' => true, 'goals' => $goals]);
    }

    public function uploadLogo(Request $request, Company $company)
    {
        $this->guard($request, $company);

        $request->validate([
            'logo' => ['required', 'file', 'mimes:png,jpg,jpeg,svg', 'max:2048'],
        ]);

        $file = $request->file('logo');
        $ext = strtolower($file->getClientOriginalExtension() ?: $file->extension());
        $name = 'company-'.$company->id.'-'.time().'.'.$ext;
        $dir = public_path('uploads/brand');
        if (! is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
        $file->move($dir, $name);

        $company->update(['logo_path' => '/uploads/brand/'.$name]);

        return response()->json(['ok' => true, 'path' => $company->logo_path]);
    }

    public function removeLogo(Request $request, Company $company)
    {
        $this->guard($request, $company);
        $company->update(['logo_path' => null]);

        return response()->json(['ok' => true]);
    }
}
