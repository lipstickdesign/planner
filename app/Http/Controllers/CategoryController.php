<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:80'],
            'color' => ['nullable', 'regex:/^#[0-9a-fA-F]{6}$/'],
        ]);

        $sort = (int) Category::max('sort_order') + 1;
        $cat = Category::create([
            'name' => $data['name'],
            'color' => $data['color'] ?? '#5a7184',
            'sort_order' => $sort,
        ]);

        return response()->json(['ok' => true, 'category' => [
            'id' => $cat->id, 'name' => $cat->name, 'color' => $cat->color,
            'archived' => false, 'events' => 0,
        ]]);
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:80'],
            'color' => ['nullable', 'regex:/^#[0-9a-fA-F]{6}$/'],
        ]);

        $category->update([
            'name' => $data['name'],
            'color' => $data['color'] ?? $category->color,
        ]);

        return response()->json(['ok' => true]);
    }

    /** Arkiver / gjenopprett (skjuler fra nedtrekk, men beholder historikk). */
    public function archive(Request $request, Category $category)
    {
        $category->update(['archived_at' => $category->archived_at ? null : now()]);

        return response()->json(['ok' => true, 'archived' => (bool) $category->fresh()->archived_at]);
    }

    public function destroy(Request $request, Category $category)
    {
        if ($category->events()->exists()) {
            return response()->json([
                'errors' => ['kategori' => ['Kategorien har arrangement knyttet til seg. Arkiver den i stedet for å slette.']],
            ], 422);
        }

        $category->destinations()->update(['category_id' => null]);
        $category->delete();

        return response()->json(['ok' => true]);
    }
}
