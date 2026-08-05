<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\PostType;
use App\Services\PublishingPlanner;
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
            'brief' => ['nullable', 'string'],
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
    public function generatePlan(Event $event, PublishingPlanner $planner)
    {
        $types = PostType::whereNotNull('default_offset_days')
            ->orderBy('sort_order')
            ->get();

        $order = (int) ($event->tasks()->max('sort_order') ?? 0);
        $planner->loadCalendar();

        foreach ($types as $pt) {
            $date = null;
            if ($event->event_date) {
                // Ønsket dato fra posttypens standard-offset, deretter spredt
                // kalender-bevisst så det ikke klumper med resten av planen.
                $desired = $event->event_date->copy()->addDays($pt->default_offset_days);
                $date = $planner->suggestDate($desired);
            }

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
     * Bruk godkjente forslag fra plan-gjennomgangen.
     * add = nye oppgaver, adjust = endre dato/kanal på eksisterende. Sletter aldri noe.
     */
    public function applyPlan(Request $request, Event $event)
    {
        $data = $request->validate([
            'add' => ['array'],
            'add.*.label' => ['required', 'string', 'max:255'],
            'add.*.date' => ['nullable', 'date'],
            'add.*.platform' => ['nullable', 'string', 'max:30'],
            'add.*.format' => ['nullable', 'string', 'max:30'],
            'adjust' => ['array'],
            'adjust.*.id' => ['required', 'integer'],
            'adjust.*.date' => ['nullable', 'date'],
            'adjust.*.platform' => ['nullable', 'string', 'max:30'],
            'adjust.*.format' => ['nullable', 'string', 'max:30'],
            'remove' => ['array'],
            'remove.*' => ['integer'],
        ]);

        $order = (int) ($event->tasks()->max('sort_order') ?? 0);

        foreach ($data['add'] ?? [] as $a) {
            $event->tasks()->create([
                'label' => $a['label'],
                'publish_date' => $a['date'] ?? null,
                'platform' => $a['platform'] ?? null,
                'format' => $a['format'] ?? null,
                'status' => 'planlagt',
                'sort_order' => ++$order,
            ]);
        }

        foreach ($data['adjust'] ?? [] as $adj) {
            // Kun oppgaver som faktisk tilhører dette arrangementet.
            $task = $event->tasks()->find($adj['id']);
            if (! $task) {
                continue;
            }
            $fields = [];
            if (array_key_exists('date', $adj) && $adj['date']) {
                $fields['publish_date'] = $adj['date'];
            }
            if (! empty($adj['platform'])) {
                $fields['platform'] = $adj['platform'];
            }
            if (! empty($adj['format'])) {
                $fields['format'] = $adj['format'];
            }
            if ($fields) {
                $task->update($fields);
            }
        }

        // Godkjente fjerninger – kun oppgaver som tilhører dette arrangementet (soft delete).
        foreach ($data['remove'] ?? [] as $rid) {
            $task = $event->tasks()->find($rid);
            if ($task) {
                $task->delete();
            }
        }

        return response()->json($event->fresh()->toCard());
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
