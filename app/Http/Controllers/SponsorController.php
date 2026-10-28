<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Sponsor;
use Illuminate\Http\Request;

class SponsorController extends Controller
{
    /** Legg til en sponsor (navn + logofil). */
    public function store(Request $request)
    {
        $company = app()->bound('currentCompany') ? app('currentCompany') : Company::first();
        if (! $company) {
            return response()->json(['error' => 'Fant ikke selskap.'], 422);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'logo' => ['required', 'file', 'max:4096'],
        ]);

        // Sjekk filtype på extension (unngår flaky SVG-MIME-deteksjon).
        $file = $request->file('logo');
        $ext = strtolower($file->getClientOriginalExtension() ?: $file->extension());
        if (! in_array($ext, ['png', 'jpg', 'jpeg', 'svg', 'webp', 'gif'], true)) {
            return response()->json(['error' => 'Ugyldig filtype. Bruk PNG, SVG, JPG eller WebP.'], 422);
        }
        $dir = public_path('uploads/sponsors');
        if (! is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
        $name = 'sponsor-'.$company->id.'-'.bin2hex(random_bytes(5)).'.'.$ext;
        $file->move($dir, $name);

        $sponsor = Sponsor::create([
            'company_id' => $company->id,
            'name' => $request->string('name'),
            'logo_path' => '/uploads/sponsors/'.$name,
            'sort_order' => (int) (Sponsor::where('company_id', $company->id)->max('sort_order') + 1),
        ]);

        return response()->json(['ok' => true, 'sponsor' => $sponsor->card()], 201);
    }

    /** Slett en sponsor. */
    public function destroy(Sponsor $sponsor)
    {
        if (is_string($sponsor->logo_path) && str_starts_with($sponsor->logo_path, '/uploads/sponsors/')) {
            @unlink(public_path(ltrim($sponsor->logo_path, '/')));
        }
        $sponsor->delete();

        return response()->json(['ok' => true]);
    }
}
