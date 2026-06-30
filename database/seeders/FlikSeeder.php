<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Company;
use App\Models\Destination;
use App\Models\Event;
use App\Models\Plan;
use App\Models\PostType;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class FlikSeeder extends Seeder
{
    public function run(): void
    {
        // ---- Selskap (tenant) ----
        $flik = Company::firstOrCreate(
            ['slug' => 'flik'],
            [
                'name' => 'Farsund og Lista Idrettsklubb',
                'org_type' => 'idrettsklubb',
                'brand_primary' => '#00529b',
                'brand_accent' => '#ffb81c',
                'font' => 'Ubuntu',
                'status' => 'active',
            ]
        );

        // Gjør FLIK til aktivt selskap, slik at company_id settes automatisk
        app()->instance('currentCompany', $flik);

        // ---- Brukere / ansvarspersoner ----
        $people = [
            ['Roger Henriksen', 'roger@havdurdesign.no', true,  'SoMe & innhold (Havdur Design)', 'Innhold / alle kanaler'],
            ['Helge', 'helge@flik.no', false, 'Daglig leder', 'Hovedside / godkjenning'],
            ['Karstein', 'fotball@flik.no', false, 'Fotballgruppa', 'Fotball'],
            ['Siv', 'siv@flik.no', false, 'Frivillig', 'Volleyball'],
        ];
        $users = [];
        foreach ($people as [$name, $email, $admin, $title, $area]) {
            $u = User::firstOrCreate(
                ['email' => $email],
                ['name' => $name, 'password' => Hash::make('password'), 'is_platform_admin' => $admin]
            );
            $flik->users()->syncWithoutDetaching([
                $u->id => ['title' => $title, 'area' => $area, 'status' => 'active'],
            ]);
            $users[$name] = $u->id;
        }

        // ---- Kategorier (idretter) ----
        $catColors = [
            'Fotball' => '#2e8b40', 'Håndball' => '#c62828', 'Volleyball' => '#f2a516',
            'Friidrett' => '#1565c0', 'Turn' => '#7b3fb0', 'Frisbeegolf' => '#0f8f8f',
            'Bue' => '#7a5232', 'VIA' => '#c2185b', 'Administrasjon' => '#5a7184',
        ];
        $cats = [];
        $i = 0;
        foreach ($catColors as $name => $color) {
            $cats[$name] = Category::firstOrCreate(
                ['company_id' => $flik->id, 'name' => $name],
                ['color' => $color, 'sort_order' => $i++]
            )->id;
        }

        // ---- Destinasjoner (FB-sider) ----
        $dest = [];
        $dest['FLIK'] = Destination::firstOrCreate(
            ['company_id' => $flik->id, 'name' => 'FLIK'],
            ['platform' => 'facebook']
        )->id;
        $destMap = [
            'FLIK Fotball' => 'Fotball', 'FLIK Håndball' => 'Håndball', 'FLIK Volleyball' => 'Volleyball',
            'FLIK Friidrett' => 'Friidrett', 'FLIK Turn' => 'Turn', 'FLIK Frisbeegolf' => 'Frisbeegolf',
            'FLIK Bueskyting' => 'Bue', 'FLIK VIA' => 'VIA',
        ];
        foreach ($destMap as $dname => $cname) {
            $dest[$dname] = Destination::firstOrCreate(
                ['company_id' => $flik->id, 'name' => $dname],
                ['platform' => 'facebook', 'category_id' => $cats[$cname] ?? null]
            )->id;
        }

        // ---- Posttyper (globalt bibliotek) ----
        $postTypes = [
            ['teaser', 'Teaser / hold av dato', 'Skape forventning', -35],
            ['lansering', 'Lansering / kunngjøring', 'Fortelle at noe skjer', -28],
            ['paamelding', 'Påmelding åpner', 'Konvertere', -21],
            ['info', 'Info / praktisk', 'Informere', -14],
            ['paaminnelse', 'Påminnelse', 'Dytte de som nøler', -7],
            ['siste_sjanse', 'Siste sjanse', 'Skape hastverk', -2],
            ['paa_dagen', 'På dagen', 'Vise stemning', 0],
            ['oppsummering', 'Oppsummering / takk', 'Bygge stolthet', 2],
        ];
        foreach ($postTypes as $idx => [$key, $name, $purpose, $offset]) {
            PostType::firstOrCreate(
                ['company_id' => null, 'key' => $key],
                ['name' => $name, 'purpose' => $purpose, 'default_offset_days' => $offset, 'sort_order' => $idx]
            );
        }

        // ---- Eventer (årshjul 2026) ----
        $H = 'https://app.hoopit.io/join/farsund-og-lista-idrettsklubb-828171';
        $events = [
            // [date, cat, type, title, goal, recurrence, approval, landing, signup, ansvarlig]
            ['2026-01-11', 'Fotball', 'Event', 'Påmelding fotball', 'Rekruttering', 'yearly', 'utkast', '', $H, 'Helge'],
            ['2026-01-10', 'Volleyball', 'Turnering', 'Volleyballturnering mini & teen #3', 'Rekruttering', 'yearly', 'til_godkjenning', '', '', 'Roger Henriksen'],
            ['2026-02-15', 'Fotball', 'Administrasjon', 'Søknad Tine', 'Admin', 'yearly', 'utkast', '', '', 'Helge'],
            ['2026-02-01', 'Volleyball', 'Turnering', 'Regionsmesterskap', 'Konkurranse', 'once', 'utkast', '', '', 'Roger Henriksen'],
            ['2026-03-23', 'Administrasjon', 'Administrasjon', 'Årsmøte', 'Admin', 'yearly', 'utkast', '', '', 'Helge'],
            ['2026-03-21', 'Fotball', 'Turnering', 'Lions Cup', 'Konkurranse', 'yearly', 'til_godkjenning', '', '', 'Karstein'],
            ['2026-03-10', 'Håndball', 'Administrasjon', 'Påmelding håndball', 'Rekruttering', 'yearly', 'utkast', '', $H, 'Helge'],
            ['2026-03-18', 'Volleyball', 'Rekruttering', 'Påmelding Volleyballskole', 'Rekruttering', 'yearly', 'utkast', 'https://flik.no/sandvolleyball-sommerskole/', $H, 'Roger Henriksen'],
            ['2026-03-25', 'Bue', 'Event', 'Sommerstart', 'Aktivitet', 'yearly', 'internt', '', '', ''],
            ['2026-03-23', 'Administrasjon', 'Event', 'Påskeknask', 'Økonomi', 'yearly', 'utkast', '', '', 'Helge'],
            ['2026-04-25', 'Håndball', 'Turnering', 'Flekkefjord Sparebank Cup', 'Konkurranse', 'yearly', 'til_godkjenning', '', '', 'Roger Henriksen'],
            ['2026-04-20', 'Frisbeegolf', 'Event', 'PGA-turnering (vår)', 'Konkurranse', 'yearly', 'utkast', '', '', ''],
            ['2026-05-10', 'Administrasjon', 'Administrasjon', 'Lotterisøknad', 'Admin', 'yearly', 'utkast', '', '', ''],
            ['2026-05-25', 'Friidrett', 'Event', 'Farsund Maraton', 'Aktivitet', 'yearly', 'utkast', 'http://www.farsundmaraton.no', '', ''],
            ['2026-05-27', 'Friidrett', 'Event', 'Rekrutteringsstevne', 'Rekruttering', 'yearly', 'utkast', 'https://flik.no/rekrutteringsstevner-friidrett/', '', ''],
            ['2026-06-02', 'VIA', 'Event', 'Sommertreff', 'Inkludering', 'yearly', 'internt', '', '', ''],
            ['2026-06-05', 'Fotball', 'Administrasjon', 'Påmelding Tine', 'Rekruttering', 'yearly', 'utkast', 'https://flik.no/tine-fotballskole', $H, ''],
            ['2026-06-10', 'Administrasjon', 'Administrasjon', 'Innrapportering NIF', 'Admin', 'yearly', 'utkast', '', '', ''],
            ['2026-06-10', 'Friidrett', 'Event', 'Rekrutteringsstevne', 'Rekruttering', 'yearly', 'utkast', 'https://flik.no/rekrutteringsstevner-friidrett/', '', ''],
            ['2026-06-12', 'Håndball', 'Administrasjon', 'Påmelding håndballskole', 'Rekruttering', 'yearly', 'utkast', 'https://flik.no/flik-handballskole', $H, ''],
            ['2026-06-15', 'Friidrett', 'Event', 'Påmelding Strandmila', 'Rekruttering', 'yearly', 'utkast', '', '', ''],
            ['2026-06-22', 'Volleyball', 'Event', 'Sandvolleyballskole', 'Rekruttering', 'yearly', 'utkast', 'https://flik.no/sandvolleyball-sommerskole/', $H, 'Roger Henriksen'],
            ['2026-06-28', 'Frisbeegolf', 'Event', 'Frisbeegolf-familiedag', 'Konkurranse', 'yearly', 'utkast', '', '', ''],
            ['2026-08-10', 'Friidrett', 'Event', 'Friidrettsskolen', 'Rekruttering', 'yearly', 'utkast', 'https://flik.no/flik-friidrettsskole/', $H, ''],
            ['2026-08-21', 'Fotball', 'Event', 'Tine Fotballskole', 'Rekruttering', 'yearly', 'utkast', 'https://flik.no/tine-fotballskole', $H, 'Roger Henriksen'],
            ['2026-08-25', 'Volleyball', 'Event', 'Start Galla', 'Konkurranse', 'yearly', 'utkast', '', '', ''],
            ['2026-09-11', 'Håndball', 'Event', 'Håndballskole', 'Rekruttering', 'yearly', 'utkast', 'https://flik.no/flik-handballskole', $H, ''],
            ['2026-09-15', 'Friidrett', 'Event', 'Strandmila', 'Konkurranse', 'yearly', 'utkast', '', '', ''],
            ['2026-09-20', 'Turn', 'Event', 'Turnstevne', 'Aktivitet', 'yearly', 'utkast', '', '', ''],
            ['2026-09-22', 'Frisbeegolf', 'Event', 'PGA-turnering (høst)', 'Konkurranse', 'yearly', 'utkast', '', '', ''],
            ['2026-09-28', 'VIA', 'Event', 'Superlekene', 'Inkludering', 'yearly', 'utkast', '', '', ''],
            ['2026-10-10', 'Fotball', 'Event', 'Toyotacup', 'Konkurranse', 'yearly', 'utkast', '', '', ''],
            ['2026-10-18', 'Volleyball', 'Event', 'Mini-turnering', 'Aktivitet', 'yearly', 'utkast', '', '', ''],
            ['2026-10-25', 'Turn', 'Event', 'Overnatting Listahallen', 'Fellesskap', 'yearly', 'utkast', '', '', ''],
            ['2026-11-15', 'Administrasjon', 'Event', 'Gavebasar', 'Økonomi', 'yearly', 'utkast', '', '', ''],
            ['2026-11-25', 'Volleyball', 'Event', 'Galla-turnering', 'Aktivitet', 'yearly', 'utkast', '', '', ''],
            ['2026-12-01', 'Administrasjon', 'Event', 'Kalendersalg', 'Økonomi', 'yearly', 'utkast', '', '', ''],
            ['2026-12-28', 'Fotball', 'Event', 'Romjulscup', 'Konkurranse', 'yearly', 'utkast', '', '', ''],
            ['2026-12-10', 'Turn', 'Event', 'Juleavslutning', 'Fellesskap', 'yearly', 'utkast', '', '', ''],
        ];

        $eventByTitle = [];
        foreach ($events as [$date, $cat, $type, $title, $goal, $recur, $appr, $landing, $signup, $ansv]) {
            $e = Event::create([
                'category_id' => $cats[$cat] ?? null,
                'title' => $title,
                'type' => $type,
                'goal' => $goal,
                'event_date' => $date,
                'recurrence' => $recur,
                'approval_status' => $appr,
                'landing_url' => $landing ?: null,
                'signup_url' => $signup ?: null,
                'responsible_user_id' => $ansv ? ($users[$ansv] ?? null) : null,
                'created_by' => $users['Roger Henriksen'] ?? null,
            ]);
            $eventByTitle[$title] = $e->id;
        }

        // ---- Oppgaver (utvalgte eventer med publiseringsplan) ----
        $plans = [
            'Volleyballturnering mini & teen #3' => [
                ['Teaser – hold av datoen', '2025-12-18', 'under_arbeid', ['FLIK Volleyball', 'FLIK']],
                ['Påmelding', '2025-12-22', 'under_arbeid', ['FLIK Volleyball', 'FLIK']],
                ['Infopost', '2025-12-29', 'under_arbeid', ['FLIK Volleyball']],
                ['Siste sjanse', '2026-01-04', 'under_arbeid', ['FLIK Volleyball', 'FLIK']],
                ['På dagen', '2026-01-10', 'under_arbeid', ['FLIK Volleyball']],
                ['Etter turnering', '2026-01-12', 'under_arbeid', ['FLIK Volleyball', 'FLIK']],
            ],
            'Årsmøte' => [
                ['Innkalling + dokumenter', '2026-02-18', 'planlagt', ['FLIK']],
                ['Publisering på nettside', '2026-02-18', 'planlagt', ['FLIK']],
            ],
            'Lions Cup' => [
                ['Komité starter', '2026-01-29', 'planlagt', []],
                ['Post 2 – teaser', '2026-02-12', 'planlagt', ['FLIK Fotball']],
                ['Post 3 – påmelding', '2026-02-26', 'planlagt', ['FLIK Fotball']],
                ['Post 4 – praktisk', '2026-03-12', 'planlagt', ['FLIK Fotball']],
            ],
            'Påskeknask' => [
                ['Info på nettside', null, 'planlagt', ['FLIK']],
                ['Salgsstart', '2026-02-23', 'planlagt', ['FLIK']],
                ['Påminnelse om å kjøpe lodd', '2026-03-09', 'planlagt', ['FLIK']],
                ['Publisering av vinnere', '2026-03-24', 'planlagt', ['FLIK']],
            ],
            'Flekkefjord Sparebank Cup' => [
                ['Post 1 – Lansering', '2026-03-11', 'planlagt', ['FLIK Håndball', 'FLIK']],
                ['Post 2 – Klasser og datoer', '2026-03-18', 'planlagt', ['FLIK Håndball']],
                ['Post 3 – Pris + praktisk', '2026-04-01', 'planlagt', ['FLIK Håndball']],
                ['Post 4 – Hvorfor bli med?', '2026-04-08', 'planlagt', ['FLIK Håndball']],
                ['Post 5 – Påminnelse', '2026-04-14', 'planlagt', ['FLIK Håndball']],
                ['Post 6 – Siste sjanse (Story)', '2026-04-16', 'planlagt', ['FLIK Håndball']],
            ],
        ];
        foreach ($plans as $title => $tasks) {
            $order = 0;
            foreach ($tasks as [$label, $date, $status, $pages]) {
                $t = Task::create([
                    'event_id' => $eventByTitle[$title],
                    'label' => $label,
                    'publish_date' => $date,
                    'status' => $status,
                    'sort_order' => $order++,
                ]);
                $ids = [];
                foreach ($pages as $p) {
                    if (isset($dest[$p])) {
                        $ids[] = $dest[$p];
                    }
                }
                if ($ids) {
                    $t->destinations()->attach($ids);
                }
            }
        }

        // ---- Standard abonnementsplaner ----
        Plan::firstOrCreate(['name' => 'Klubb'], ['base_price' => 99000, 'seat_price' => 9900]);
        Plan::firstOrCreate(['name' => 'Byrå'], ['base_price' => 199000, 'seat_price' => 7900]);
    }
}
