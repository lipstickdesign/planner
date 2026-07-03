<?php

namespace App\Http\Controllers;

use App\Models\ContentIdea;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ContentIdeaController extends Controller
{
    private function rules(): array
    {
        return [
            'group' => ['required', Rule::in(['verving', 'engasjement', 'praktisk', 'motivasjon', 'sesong', 'medlem'])],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules());
        $data['sort_order'] = (int) (ContentIdea::max('sort_order') ?? 0) + 1;
        $idea = ContentIdea::create($data);

        return response()->json($idea->card(), 201);
    }

    public function update(Request $request, ContentIdea $contentIdea)
    {
        $contentIdea->update($request->validate($this->rules()));

        return response()->json($contentIdea->fresh()->card());
    }

    public function destroy(ContentIdea $contentIdea)
    {
        $contentIdea->delete();

        return response()->json(['ok' => true]);
    }
}
