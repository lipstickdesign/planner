<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\TrainingFacility;
use Illuminate\Database\Seeder;

/**
 * Legger inn FLIKs anlegg (haller + baner) med tillatte idretter og status.
 * Idempotent: firstOrCreate på navn, så den kan kjøres flere ganger uten
 * å overskrive manuelle endringer. Kjør: php artisan db:seed --class=TrainingFlikFacilitiesSeeder
 */
class TrainingFlikFacilitiesSeeder extends Seeder
{
    public function run(): void
    {
        $flik = Company::where('slug', 'flik')->first();
        if (! $flik) {
            return;
        }

        // [navn, type, antall soner, tillatte idretter, status]
        $facs = [
            ['Alcoa fotballhall', 'hall', 2, ['Fotball', 'Friidrett'], 'aktiv'],
            ['Alcoa flerbrukshall', 'hall', 2, ['Volleyball', 'Håndball'], 'aktiv'],
            ['Listahallen', 'hall', 2, ['Turn', 'Håndball'], 'aktiv'],
            ['Eilert', 'hall', 2, ['Bue', 'Håndball', 'Basketball'], 'aktiv'],
            ['Alcoa kunstgress', 'kunstgress', 3, ['Fotball'], 'aktiv'],
            ['Alcoa gressbane', 'gress', 2, ['Fotball'], 'aktiv'],
            ['Lista ungdomsskole', 'kunstgress', 2, ['Fotball', 'Friidrett'], 'kommende'],
            ['Vanse stadion', 'friidrett', 1, ['Friidrett'], 'aktiv'],
        ];

        $count = 0;
        foreach ($facs as [$name, $type, $zones, $sports, $status]) {
            TrainingFacility::firstOrCreate(
                ['company_id' => $flik->id, 'name' => $name],
                ['type' => $type, 'zones' => $zones, 'allowed_sports' => $sports, 'status' => $status]
            );
            $count++;
        }

        $this->command?->info("Anlegg på plass ({$count} sjekket).");
    }
}
