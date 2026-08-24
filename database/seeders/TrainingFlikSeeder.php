<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Company;
use App\Models\TrainingSeason;
use App\Models\TrainingTeam;
use Illuminate\Database\Seeder;

/**
 * Importerer FLIKs 60 lag (fra klubbens lagdata) inn i treningstids-modulen.
 * Idempotent: nøkkel på (company_id, external_ref), så den kan kjøres flere ganger.
 * Kjør: php artisan db:seed --class=TrainingFlikSeeder
 */
class TrainingFlikSeeder extends Seeder
{
    public function run(): void
    {
        $flik = Company::where('slug', 'flik')->first();
        if (! $flik) {
            $this->command?->warn('Fant ikke FLIK – hopper over.');

            return;
        }

        $season = TrainingSeason::firstOrCreate(
            ['company_id' => $flik->id, 'name' => '2026/2027 ute'],
            ['type' => 'ute', 'is_active' => true]
        );

        $cats = Category::where('company_id', $flik->id)->get();
        $catId = function (?string $idrett) use ($cats): ?int {
            $l = mb_strtolower(trim((string) $idrett));
            foreach ($cats as $c) {
                if (mb_strtolower($c->name) === $l) {
                    return $c->id;
                }
            }
            if (str_starts_with($l, 'bue')) { // Bueskyting → «Bue»
                foreach ($cats as $c) {
                    if (mb_strtolower($c->name) === 'bue') {
                        return $c->id;
                    }
                }
            }

            return null;
        };

        $path = database_path('seeders/data/training-flik-lagdata.json');
        $data = json_decode(file_get_contents($path), true);
        $count = 0;

        foreach (($data['lag'] ?? []) as $t) {
            if (empty($t['id']) || empty($t['navn'])) {
                continue;
            }
            TrainingTeam::updateOrCreate(
                ['company_id' => $flik->id, 'external_ref' => $t['id']],
                [
                    'category_id' => $catId($t['idrett'] ?? null),
                    'name' => $t['navn'],
                    'birth_year' => $t['arskull'] ?? null,
                    'grade' => $t['trinn'] ?? null,
                    'players' => $t['antall_spillere'] ?? null,
                    'coaches' => $t['antall_trenere'] ?? null,
                    'area_indoor' => $t['areal_inne'] ?? null,
                    'area_outdoor' => $t['areal_ute'] ?? null,
                    'sessions_per_week' => $t['okter_per_uke']['okter'] ?? null,
                    'requires_indoor' => false,
                ]
            );
            $count++;
        }

        $this->command?->info("Importerte {$count} lag til sesong «{$season->name}».");
    }
}
