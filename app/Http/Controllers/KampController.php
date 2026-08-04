<?php

namespace App\Http\Controllers;

use App\Models\Kamp;
use Illuminate\Http\Request;

class KampController extends Controller
{
    private function rules(): array
    {
        return [
            'category_id' => ['nullable', 'exists:categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'match_date' => ['required', 'date'],
            'match_time' => ['nullable', 'string', 'max:5'],
            'location' => ['nullable', 'string', 'max:255'],
            'home' => ['nullable', 'boolean'],
            'note' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function store(Request $request)
    {
        $kamp = Kamp::create($request->validate($this->rules()));

        return response()->json($kamp->fresh('category')->card(), 201);
    }

    public function update(Request $request, Kamp $kamp)
    {
        $kamp->update($request->validate($this->rules()));

        return response()->json($kamp->fresh('category')->card());
    }

    public function destroy(Kamp $kamp)
    {
        $kamp->delete();

        return response()->json(['ok' => true]);
    }
}
