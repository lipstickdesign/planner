<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Destination;
use App\Models\Event;
use Illuminate\Database\Seeder;

/**
 * Importerer de ekte, produserte tekstene fra Google Drive (flik_content.json)
 * inn på de tilhørende arrangementene. For hvert arrangement i fila erstattes
 * oppgavene med de faktiske postene (tekst, dato, kanal). Andre arrangement røres ikke.
 *
 * Kjør: php8.3 artisan db:seed --class=DriveContentSeeder
 */
class DriveContentSeeder extends Seeder
{
    public function run(): void
    {
        $flik = Company::where('slug', 'flik')->first();
        if (! $flik) {
            return;
        }
        app()->instance('currentCompany', $flik);

        $path = database_path('seeders/data/flik_content.json');
        if (! is_file($path)) {
            $this->command->warn('Fant ikke flik_content.json');

            return;
        }
        $json = json_decode(file_get_contents($path), true);
        if (empty($json['events'])) {
            return;
        }

        $destMap = Destination::pluck('id', 'name'); // navn => id

        foreach ($json['events'] as $ev) {
            $event = Event::where('title', $ev['match'])->first();
            if (! $event) {
                $this->command->warn('Fant ikke arrangement: '.$ev['match']);

                continue;
            }

            if (! empty($ev['brief'])) {
                $event->brief = $ev['brief'];
            }
            if (! empty($ev['landing'])) {
                $event->landing_url = $event->landing_url ?: $ev['landing'];
            }
            $event->save();

            // Erstatt oppgavene med de ekte postene (idempotent)
            $event->tasks()->forceDelete();

            $destIds = [];
            foreach (($ev['pages'] ?? []) as $page) {
                if (isset($destMap[$page])) {
                    $destIds[] = $destMap[$page];
                }
            }

            $order = 0;
            foreach ($ev['posts'] as $post) {
                $task = $event->tasks()->create([
                    'label' => $post['label'],
                    'publish_date' => $post['date'] ?? null,
                    'status' => $ev['status'] ?? 'planlagt',
                    'body_draft' => $post['body'] ?? null,
                    'sort_order' => $order++,
                ]);
                if ($destIds) {
                    $task->destinations()->sync($destIds);
                }
            }

            $this->command->info('Importert: '.$ev['match'].' ('.count($ev['posts']).' poster)');
        }
    }
}
