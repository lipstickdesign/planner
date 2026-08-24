<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\TrainingAssignment;
use App\Models\TrainingFacility;
use App\Models\TrainingSeason;
use Illuminate\Database\Seeder;

class TrainingPlan2027ASeeder extends Seeder
{
    /**
     * Importerer treningsplanen fra «Treningstider utendørs 2027A.xlsx»
     * (utarbeidet av sportslig ansvarlig og daglig leder) inn i rutenettet
     * som utgangspunkt. Idempotent: tømmer sesongens tildelinger og legger inn på nytt.
     */
    public function run(): void
    {
        $company = Company::first();
        if (! $company) {
            $this->command?->warn('Ingen selskap funnet – hopper over.');

            return;
        }

        $season = TrainingSeason::firstOrCreate(
            ['company_id' => $company->id, 'name' => '2026/2027 ute'],
            ['type' => 'ute', 'is_active' => true]
        );

        $path = database_path('seeders/data/treningstider-2027a.json');
        $data = json_decode(file_get_contents($path), true);

        $norm = fn (string $s) => preg_replace('/\s+/', '', str_replace('bane', '', mb_strtolower($s)));

        // Koble/opprett anlegg (matcher eksisterende på normalisert navn)
        $existing = TrainingFacility::where('company_id', $company->id)->get()->keyBy(fn ($f) => $norm($f->name));
        $facId = [];
        foreach ($data['venues'] as $v) {
            $key = $norm($v);
            $f = $existing->get($key);
            if (! $f) {
                $f = TrainingFacility::create([
                    'company_id' => $company->id,
                    'name' => $v,
                    'type' => str_contains(mb_strtolower($v), 'hall') ? 'innendørs' : 'utendørs',
                    'status' => 'aktiv',
                    'allowed_sports' => ['Fotball'],
                ]);
                $existing->put($key, $f);
            }
            $facId[$v] = $f->id;
        }

        // Tøm og re-importer sesongens tildelinger
        TrainingAssignment::where('company_id', $company->id)
            ->where('training_season_id', $season->id)->delete();

        $n = 0;
        foreach ($data['blocks'] as $b) {
            TrainingAssignment::create([
                'company_id' => $company->id,
                'training_season_id' => $season->id,
                'training_facility_id' => $facId[$b['venue']],
                'training_team_id' => null,
                'label' => $b['text'],
                'org' => $b['org'],
                'locked' => $b['org'] !== 'FLIK',
                'weekday' => $b['day'],
                'block_start' => $b['start'],
                'block_end' => $b['endx'],
                'version' => 1,
            ]);
            $n++;
        }

        $this->command?->info("Importerte {$n} blokker til rutenettet (sesong {$season->name}).");
    }
}
