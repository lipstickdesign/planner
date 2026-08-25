<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\TrainingAssignment;
use App\Models\TrainingFacility;
use App\Models\TrainingSeason;
use App\Models\TrainingTeam;
use Illuminate\Database\Seeder;

class TrainingIndoor2026Seeder extends Seeder
{
    /**
     * Innendørs treningstider 2026/27 (håndball, volley, turn, basket, bue + eksterne)
     * i Listahallen og Alcoa flerbrukshall. Disse er satt av klubben/IR og er FASTE i år –
     * importeres som låste blokker som fotballplanleggingen må jobbe rundt. Idempotent.
     */
    public function run(): void
    {
        $company = Company::first();
        if (! $company) {
            return;
        }
        $season = TrainingSeason::firstOrCreate(
            ['company_id' => $company->id, 'name' => '2026/2027 ute'],
            ['type' => 'ute', 'is_active' => true]
        );

        $data = json_decode(file_get_contents(database_path('seeders/data/treningstider-inne-2026-27.json')), true);

        $norm = fn (string $s) => preg_replace('/\s+/', '', str_replace('bane', '', mb_strtolower($s)));
        $facilities = TrainingFacility::where('company_id', $company->id)->get()->keyBy(fn ($f) => $norm($f->name));

        $tnorm = fn (string $s) => preg_replace('/[^a-z0-9]/', '', mb_strtolower($s));
        $teams = TrainingTeam::where('company_id', $company->id)->get()->keyBy(fn ($t) => $tnorm($t->name));

        // Tøm tidligere låste blokker KUN i disse to hallene (så re-kjøring ikke lager dubletter,
        // og uten å røre fotballens Spind/Bobcats-låser på andre anlegg).
        $hallIds = collect(['Listahallen', 'Alcoa flerbrukshall'])
            ->map(fn ($n) => optional($facilities->get($norm($n)))->id)->filter()->values()->all();
        if ($hallIds) {
            TrainingAssignment::where('company_id', $company->id)
                ->where('training_season_id', $season->id)
                ->whereIn('training_facility_id', $hallIds)
                ->where('locked', true)->delete();
        }

        $n = 0;
        foreach ($data as $b) {
            $key = $norm($b['venue']);
            $f = $facilities->get($key);
            if (! $f) {
                $f = TrainingFacility::create([
                    'company_id' => $company->id,
                    'name' => $b['venue'],
                    'type' => 'innendørs',
                    'status' => 'aktiv',
                    'allowed_sports' => ['Håndball', 'Volleyball', 'Turn', 'Basketball'],
                ]);
                $facilities->put($key, $f);
            }
            $team = $teams->get($tnorm($b['label']));
            TrainingAssignment::create([
                'company_id' => $company->id,
                'training_season_id' => $season->id,
                'training_facility_id' => $f->id,
                'training_team_id' => $team?->id,
                'label' => $b['label'],
                'org' => $b['org'],
                'locked' => true,
                'weekday' => $b['day'],
                'block_start' => $b['start'],
                'block_end' => $b['end'],
            ]);
            $n++;
        }

        $this->command?->info("Importerte {$n} innendørs-blokker (låst) til Listahallen + Alcoa flerbrukshall.");
    }
}
