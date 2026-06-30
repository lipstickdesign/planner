<?php

namespace App\Http\Controllers;

use App\Models\Event;
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
}
