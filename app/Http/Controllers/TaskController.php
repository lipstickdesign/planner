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
            'platform' => ['nullable', Rule::in(['facebook', 'instagram', 'tiktok', 'snapchat', 'linkedin', 'youtube'])],
            'format' => ['nullable', Rule::in(['post', 'story'])],
            'draft_url' => ['nullable', 'string', 'max:500'],
            'body_draft' => ['nullable', 'string'],
            'destination_ids' => ['array'],
            'destination_ids.*' => ['integer', 'exists:destinations,id'],
        ];
    }

    /** Endre kun status – brukt for direkte statusbytte i lista. */
    public function updateStatus(Request $request, Task $task)
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(['planlagt', 'under_arbeid', 'klar', 'publisert'])],
        ]);
        $task->update(['status' => $data['status']]);

        return response()->json($task->event->fresh()->toCard());
    }

    public function store(Request $request, Event $event)
    {
        $data = $request->validate($this->rules());

        $task = $event->tasks()->create([
            'label' => $data['label'],
            'publish_date' => $data['publish_date'] ?? null,
            'status' => $data['status'],
            'platform' => $data['platform'] ?? null,
            'format' => $data['format'] ?? null,
            'draft_url' => $data['draft_url'] ?? null,
            'body_draft' => $data['body_draft'] ?? null,
            'sort_order' => (int) $event->tasks()->max('sort_order') + 1,
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
            'platform' => $data['platform'] ?? null,
            'format' => $data['format'] ?? null,
            'draft_url' => $data['draft_url'] ?? null,
            'body_draft' => $data['body_draft'] ?? null,
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
