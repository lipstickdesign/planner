<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\PostType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EventController extends Controller
{
    private function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'type' => ['nullable', 'string', 'max:50'],
            'goal' => ['nullable', 'string', 'max:100'],
            'event_date' => ['required', 'date'],
            'recurrence' => ['required', Rule::in(['none', 'yearly'])],
            'approval_status' => ['required', Rule::in(['utkast', 'til_godkjenning', 'godkjent', 'internt'])],
            'landing_url' => ['nullable', 'string', 'max:500'],
            'signup_url' => ['nullable', 'string', 'max:500'],
            'internal_note' => ['nullable', 'string'],
            'responsible_user_id' => ['nullable', 'exists:users,id'],
        ];
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules());
        $data['created_by'] = $request->user()->id;

        $event = Event::create($data);

        return response()->json($event->toCard(), 201);
    }

    public function update(Request $request, Event $event)
    {
        $event->update($request->validate($this->rules()));

        return response()->json($event->fresh()->toCard());
    }

    public function destroy(Event $event)
    {
        $event->delete();

        return response()->json(['ok' => true]);
    }

    /**
     * Foreslå en publiseringsplan: lager oppgaver ut fra posttype-biblioteket,
     * med datoer beregnet fra eventdatoen (f.eks. teaser −35 dager, påminnelse −7).
     */
    public function generatePlan(Event $event)
    {
        $types = PostType::whereNotNull('default_offset_days')
            ->orderBy('sort_order')
            ->get();

        $order = (int) ($event->tasks()->max('sort_order') ?? 0);

        foreach ($types as $pt) {
            $date = $event->event_date
                ? $event->event_date->copy()->addDays($pt->default_offset_days)
                : null;

            $event->tasks()->create([
                'post_type_id' => $pt->id,
                'label' => $pt->name,
                'publish_date' => $date,
                'status' => 'planlagt',
                'sort_order' => ++$order,
            ]);
        }

        return response()->json($event->fresh()->toCard(), 201);
    }

    /**
     * Kopier event (med oppgaver/tekster) til neste år, med alle datoer flyttet ett år frem.
     * Tekstene kopieres som de er – brukeren kan så oppdatere dem for nytt år med AI.
     */
    public function duplicateNextYear(Request $request, Event $event)
    {
        $event->loadMissing('tasks.destinations');

        $copy = $event->replicate();
        $copy->event_date = $event->event_date ? $event->event_date->copy()->addYear() : null;
        $copy->approval_status = 'utkast';
        $copy->created_by = $request->user()->id;
        $copy->save();

        foreach ($event->tasks as $t) {
            $newTask = $t->replicate();
            $newTask->event_id = $copy->id;
            $newTask->publish_date = $t->publish_date ? $t->publish_date->copy()->addYear() : null;
            $newTask->status = 'planlagt';
            $newTask->save();
            $newTask->destinations()->sync($t->destinations->pluck('id')->all());
        }

        return response()->json($copy->fresh()->toCard(), 201);
    }

    /**
     * Endre rekkefølgen på oppgavene i et event (drag/flytt opp-ned).
     */
    public function reorderTasks(Request $request, Event $event)
    {
        $data = $request->validate([
            'order' => ['required', 'array'],
            'order.*' => ['integer'],
        ]);

        foreach ($data['order'] as $index => $taskId) {
            $event->tasks()->where('id', $taskId)->update(['sort_order' => $index]);
        }

        return response()->json($event->fresh()->toCard());
    }
}
