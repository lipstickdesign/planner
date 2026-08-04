<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\KlubblivPost;
use App\Services\PublishingPlanner;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
            'repeat' => ['nullable', Rule::in(['monthly', 'quarterly', 'yearly'])],
            'repeat_count' => ['nullable', 'integer', 'min:1', 'max:24'],
        ];
    }

    private function destMap(): array
    {
        return Destination::pluck('name', 'id')->all();
    }

    public function store(Request $request, PublishingPlanner $planner)
    {
        $data = $request->validate($this->rules());

        $repeat = $data['repeat'] ?? null;
        $count = max(1, (int) ($data['repeat_count'] ?? 1));
        unset($data['repeat'], $data['repeat_count']);

        // Foreslå dato kalender-bevisst hvis ønsket / hvis dato mangler
        if (! empty($data['auto_date']) || empty($data['publish_date'])) {
            $desired = ! empty($data['publish_date'])
                ? \Carbon\Carbon::parse($data['publish_date'])
                : \Carbon\Carbon::today()->addDays(3);
            $data['publish_date'] = $planner->suggestDate($desired, $data['destination_ids'] ?? [])->format('Y-m-d');
        }
        unset($data['auto_date']);
        $data['status'] = $data['status'] ?? 'planlagt';

        // Én post – eller flere hvis satt til å gjenta seg (månedlig/kvartalsvis/årlig)
        $base = \Carbon\Carbon::parse($data['publish_date']);
        $order = (int) (KlubblivPost::max('sort_order') ?? 0);
        $times = $repeat ? $count : 1;
        $map = $this->destMap();
        $created = [];

        for ($i = 0; $i < $times; $i++) {
            $d = $base->copy();
            if ($repeat === 'monthly') {
                $d->addMonthsNoOverflow($i);
            } elseif ($repeat === 'quarterly') {
                $d->addMonthsNoOverflow($i * 3);
            } elseif ($repeat === 'yearly') {
                $d->addYears($i);
            }
            $row = $data;
            $row['publish_date'] = $d->format('Y-m-d');
            $row['sort_order'] = ++$order;
            $created[] = KlubblivPost::create($row)->card($map);
        }

        return response()->json(['posts' => $created], 201);
    }

    public function update(Request $request, KlubblivPost $klubblivPost)
    {
        $data = $request->validate($this->rules());
        unset($data['auto_date'], $data['repeat'], $data['repeat_count']);
        $klubblivPost->update($data);

        return response()->json($klubblivPost->fresh()->card($this->destMap()));
    }

    /** Endre kun status – for raskt statusbytte i lista. */
    public function updateStatus(Request $request, KlubblivPost $klubblivPost)
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(['planlagt', 'under_arbeid', 'klar', 'publisert'])],
        ]);
        $klubblivPost->update(['status' => $data['status']]);

        return response()->json($klubblivPost->fresh()->card($this->destMap()));
    }

    public function destroy(KlubblivPost $klubblivPost)
    {
        $klubblivPost->delete();

        return response()->json(['ok' => true]);
    }
}
