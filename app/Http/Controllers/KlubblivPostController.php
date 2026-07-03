<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\KlubblivPost;
use App\Services\PublishingPlanner;
use Illuminate\Http\Request;

class KlubblivPostController extends Controller
{
    private function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'content_idea_id' => ['nullable', 'exists:content_ideas,id'],
            'body_draft' => ['nullable', 'string'],
            'publish_date' => ['nullable', 'date'],
            'destination_ids' => ['nullable', 'array'],
            'destination_ids.*' => ['integer'],
            'status' => ['nullable', 'string', 'max:30'],
            'auto_date' => ['nullable', 'boolean'],
        ];
    }

    private function destMap(): array
    {
        return Destination::pluck('name', 'id')->all();
    }

    public function store(Request $request, PublishingPlanner $planner)
    {
        $data = $request->validate($this->rules());

        // Foreslå dato kalender-bevisst hvis ønsket / hvis dato mangler
        if (! empty($data['auto_date']) || empty($data['publish_date'])) {
            $desired = ! empty($data['publish_date'])
                ? \Carbon\Carbon::parse($data['publish_date'])
                : \Carbon\Carbon::today()->addDays(3);
            $data['publish_date'] = $planner->suggestDate($desired, $data['destination_ids'] ?? [])->format('Y-m-d');
        }
        unset($data['auto_date']);

        $data['sort_order'] = (int) (KlubblivPost::max('sort_order') ?? 0) + 1;
        $data['status'] = $data['status'] ?? 'planlagt';

        $post = KlubblivPost::create($data);

        return response()->json($post->card($this->destMap()), 201);
    }

    public function update(Request $request, KlubblivPost $klubblivPost)
    {
        $data = $request->validate($this->rules());
        unset($data['auto_date']);
        $klubblivPost->update($data);

        return response()->json($klubblivPost->fresh()->card($this->destMap()));
    }

    public function destroy(KlubblivPost $klubblivPost)
    {
        $klubblivPost->delete();

        return response()->json(['ok' => true]);
    }
}
