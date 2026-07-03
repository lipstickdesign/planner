<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    private function company(): ?Company
    {
        return app()->bound('currentCompany') ? app('currentCompany') : null;
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'role' => ['required', Rule::in(['admin', 'medlem'])],
            'title' => ['nullable', 'string', 'max:255'],
            'area' => ['nullable', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $company = $this->company();
        if (! $company) {
            return response()->json(['error' => 'Fant ikke selskap.'], 422);
        }

        $user = User::firstOrCreate(
            ['email' => $data['email']],
            ['name' => $data['name'], 'password' => Hash::make($data['password'])]
        );

        $company->users()->syncWithoutDetaching([
            $user->id => [
                'title' => $data['title'] ?? null,
                'area' => $data['area'] ?? null,
                'role' => $data['role'],
                'status' => 'active',
            ],
        ]);

        return response()->json(['ok' => true]);
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'title' => ['nullable', 'string', 'max:255'],
            'area' => ['nullable', 'string', 'max:255'],
            'role' => ['nullable', Rule::in(['admin', 'medlem'])],
        ]);

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->save();

        $company = $this->company();
        if ($company) {
            $pivot = [
                'title' => $data['title'] ?? null,
                'area' => $data['area'] ?? null,
            ];
            // Rolle kan ikke endres for plattform-superadmin
            if (! $user->is_platform_admin && ! empty($data['role'])) {
                $pivot['role'] = $data['role'];
            }
            $company->users()->updateExistingPivot($user->id, $pivot);
        }

        return response()->json(['ok' => true]);
    }

    public function resetPassword(Request $request, User $user)
    {
        $data = $request->validate(['password' => ['required', 'string', 'min:8']]);
        $user->password = Hash::make($data['password']);
        $user->save();

        return response()->json(['ok' => true]);
    }

    public function updateRole(Request $request, User $user)
    {
        $data = $request->validate(['role' => ['required', Rule::in(['admin', 'medlem'])]]);
        $company = $this->company();
        if ($company) {
            $company->users()->updateExistingPivot($user->id, ['role' => $data['role']]);
        }

        return response()->json(['ok' => true]);
    }

    public function destroy(Request $request, User $user)
    {
        if ($user->id === $request->user()->id) {
            return response()->json(['error' => 'Du kan ikke fjerne deg selv.'], 422);
        }
        $company = $this->company();
        if ($company) {
            $company->users()->detach($user->id);
        }

        return response()->json(['ok' => true]);
    }
}
