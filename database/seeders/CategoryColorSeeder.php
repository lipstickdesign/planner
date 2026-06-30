<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Company;
use Illuminate\Database\Seeder;

/**
 * Oppdaterer idrettsfargene til FLIK-paletten (marine, blå, oransje, magenta + valører).
 * Ikke-destruktiv: endrer kun farger, rører ikke arrangement/oppgaver.
 * Kjør: php8.3 artisan db:seed --class=CategoryColorSeeder
 */
class CategoryColorSeeder extends Seeder
{
    public function run(): void
    {
        $flik = Company::where('slug', 'flik')->first();
        if (! $flik) {
            return;
        }
        app()->instance('currentCompany', $flik);

        $colors = [
            'Fotball' => '#2f6fd6',        // royal blå
            'Håndball' => '#fb471f',       // oransje
            'Volleyball' => '#6aaae4',     // lys blå (draktfarge)
            'Friidrett' => '#1c3155',      // marine
            'Turn' => '#cc2170',           // magenta
            'Frisbeegolf' => '#2a8a9e',    // blågrønn (valør)
            'Bue' => '#9c1a4f',            // burgunder
            'VIA' => '#ec3400',            // dyp oransjerød
            'Administrasjon' => '#5b6b86', // skifer
        ];

        foreach ($colors as $name => $hex) {
            Category::where('name', $name)->update(['color' => $hex]);
        }
    }
}
