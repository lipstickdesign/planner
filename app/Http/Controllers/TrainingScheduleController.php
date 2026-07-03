<?php

namespace App\Http\Controllers;

use App\Models\TrainingSchedule;
use Illuminate\Http\Request;

class TrainingScheduleController extends Controller
{
    private function rules(): array
    {
        return [
            'category_id' => ['nullable', 'exists:categories,id'],
            'weekday' => ['required', 'integer', 'min:1', 'max:7'],
            'group_label' => ['nullable', 'string', 'max:100'],
            'start_time' => ['nullable', 'string', 'max:5'],
            'end_time' => ['nullable', 'string', 'max:5'],
            'location' => ['nullable', 'string', 'max:255'],
            'note' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function store(Request $request)
    {
        $schedule = TrainingSchedule::create($request->validate($this->rules()));

        return response()->json($schedule->fresh('category')->card(), 201);
    }

    public function update(Request $request, TrainingSchedule $trainingSchedule)
    {
        $trainingSchedule->update($request->validate($this->rules()));

        return response()->json($trainingSchedule->fresh('category')->card());
    }

    public function destroy(TrainingSchedule $trainingSchedule)
    {
        $trainingSchedule->delete();

        return response()->json(['ok' => true]);
    }
}
