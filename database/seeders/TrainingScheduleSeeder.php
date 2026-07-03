<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Company;
use App\Models\TrainingSchedule;
use Illuminate\Database\Seeder;

class TrainingScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $flik = Company::where('slug', 'flik')->first();
        if (! $flik) {
            return;
        }
        app()->instance('currentCompany', $flik);

        $cats = Category::pluck('id', 'name')->all();

        // EKSEMPEL-treningstider – FLIK redigerer selv. weekday: 1=man .. 7=søn
        $rows = [
            ['Fotball', 1, '17:00', '18:30', 'Listahallen kunstgress'],
            ['Fotball', 3, '17:00', '18:30', 'Listahallen kunstgress'],
            ['Håndball', 2, '16:30', '18:00', 'Listahallen'],
            ['Håndball', 4, '16:30', '18:00', 'Listahallen'],
            ['Volleyball', 4, '18:00', '20:00', 'Listahallen'],
            ['Friidrett', 3, '17:30', '19:00', 'Farsund stadion'],
            ['Turn', 1, '17:00', '18:30', 'Listahallen'],
        ];

        foreach ($rows as [$cat, $weekday, $start, $end, $loc]) {
            TrainingSchedule::firstOrCreate(
                [
                    'company_id' => $flik->id,
                    'category_id' => $cats[$cat] ?? null,
                    'weekday' => $weekday,
                    'start_time' => $start,
                ],
                [
                    'end_time' => $end,
                    'location' => $loc,
                    'note' => 'Eksempel – rediger',
                ]
            );
        }
    }
}
