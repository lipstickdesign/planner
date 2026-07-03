<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\DashboardLayout;
use Illuminate\Http\Request;

class DashboardLayoutController extends Controller
{
    private function company(): ?Company
    {
        return app()->bound('currentCompany') ? app('currentCompany') : null;
    }

    private function rules(): array
    {
        return [
            'layout' => ['required', 'array'],
            'layout.a' => ['present', 'array'],
            'layout.b' => ['present', 'array'],
        ];
    }

    /** Lagre innlogget brukers eget oppsett. */
    public function save(Request $request)
    {
        $data = $request->validate($this->rules());
        $company = $this->company();
        if (! $company) {
            return response()->json(['error' => 'Fant ikke selskap.'], 422);
        }

        DashboardLayout::updateOrCreate(
            ['company_id' => $company->id, 'user_id' => $request->user()->id],
            ['layout' => $data['layout']]
        );

        return response()->json(['ok' => true]);
    }

    /** Lagre som standard for hele klubben (kun admin – gated i rute). */
    public function saveDefault(Request $request)
    {
        $data = $request->validate($this->rules());
        $company = $this->company();
        if (! $company) {
            return response()->json(['error' => 'Fant ikke selskap.'], 422);
        }

        DashboardLayout::updateOrCreate(
            ['company_id' => $company->id, 'user_id' => null],
            ['layout' => $data['layout']]
        );

        return response()->json(['ok' => true]);
    }

    /** Nullstill: slett brukerens eget oppsett så standarden gjelder igjen. */
    public function reset(Request $request)
    {
        $company = $this->company();
        if ($company) {
            DashboardLayout::where('company_id', $company->id)
                ->where('user_id', $request->user()->id)
                ->delete();
        }

        return response()->json(['ok' => true]);
    }
}
