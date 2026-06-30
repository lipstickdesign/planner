<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    private function rules(): array
    {
        return [
            'label' => ['required', 'string', 'max:255'],
            'publish_date' => ['nullable', 'date'],
            'status' => ['required', Rule::in(['planlagt', 'under_arbeid', 'klar', 'publisert'])],
            'draft_url' => ['nullable', 'string', 'max:500'],
            'destination_ids' => ['array'],
            'destination_ids.*' => ['integer', 'exists:destinations,id'],
        ];
    }

    public function store(Request $request, Event $event)
    {
        $data = $request->validate($this->rules());

        $task = $event->tasks()->create([
            'label' => $data['label'],
            'publish_date' => $data['publish_date'] ?? null,
            'status' => $data['status'],
            'draft_url' => $data['draft_url'] ?? null,
        ]);
        $task->destinations()->sync($data['destination_ids'] ?? []);

        return response()->json($event->fresh()->toCard(), 201);
    }

    public function update(Request $request, Task $task)
    {
        $data = $request->validate($this->rules());

        $task->update([
            'label' => $data['label'],
            'publish_date' => $data['publish_date'] ?? null,
            'status' => $data['status'],
            'draft_url' => $data['draft_url'] ?? null,
        ]);
        $task->destinations()->sync($data['destination_ids'] ?? []);

        return response()->json($task->event->fresh()->toCard());
    }

    public function destroy(Task $task)
    {
        $event = $task->event;
        $task->delete();

        return response()->json($event->fresh()->toCard());
    }
}
