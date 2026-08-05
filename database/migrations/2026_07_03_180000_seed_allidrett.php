<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $flik = DB::table('companies')->where('slug', 'flik')->first();
        if (! $flik) {
            return;
        }

        // ---- Allidrett som egen idrett (kategori) ----
        $cat = DB::table('categories')
            ->where('company_id', $flik->id)->where('name', 'Allidrett')->first();

        if (! $cat) {
            $sort = (int) DB::table('categories')->where('company_id', $flik->id)->max('sort_order') + 1;
            $catId = DB::table('categories')->insertGetId([
                'company_id' => $flik->id,
                'name' => 'Allidrett',
                'color' => '#00a4a6',
                'sort_order' => $sort,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $catId = $cat->id;
        }

        if (! DB::table('destinations')->where('company_id', $flik->id)->where('name', 'FLIK Allidrett')->exists()) {
            DB::table('destinations')->insert([
                'company_id' => $flik->id,
                'name' => 'FLIK Allidrett',
                'platform' => 'facebook',
                'category_id' => $catId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // ---- Ett arrangement for hele tilbudet (ikke ett per trening) ----
        $exists = DB::table('events')
            ->where('company_id', $flik->id)
            ->where('title', 'FLIK Allidrett 2026/2027')
            ->first();
        if ($exists) {
            return;
        }

        $creator = DB::table('company_user')->where('company_id', $flik->id)->value('user_id')
            ?? DB::table('users')->value('id');

        $eventId = DB::table('events')->insertGetId([
            'company_id' => $flik->id,
            'category_id' => $catId,
            'title' => 'FLIK Allidrett 2026/2027',
            'type' => 'Event',
            'goal' => 'Rekruttering',
            'description' => 'Allidrett for 1.–2. klasse, onsdager kl. 17–18. Idretten roterer uke for uke '
                .'(friidrett, sandvolleyball, sykkel, turn, fotball, svømming, håndball, volleyball). '
                .'Gratis for FLIK-medlemmer. Oppstart/info onsdag 19. august på Vanse stadion. Påmelding via Hoopit.',
            'event_date' => '2026-08-19',
            'recurrence' => 'yearly',
            'approval_status' => 'utkast',
            'signup_url' => 'https://flik.no/allidrett',
            'created_by' => $creator,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // ---- Tasks: oppstart + én påminnelse per idrett + avslutning ----
        // (maks én task per idrett – ikke én per trening)
        $tasks = [
            // [label, publish_date, platform, format]
            ['Oppstart allidrett – meld på! (info, påmelding, gratis for medlemmer)', '2026-08-12', 'facebook', 'post'],
            ['Denne uka: friidrett på allidrett – bli med!', '2026-08-19', 'instagram', 'story'],
            ['Denne uka: sandvolleyball på allidrett', '2026-09-16', 'instagram', 'story'],
            ['Denne uka: sykkel på allidrett', '2026-10-07', 'instagram', 'story'],
            ['Denne uka: turn på allidrett', '2026-10-21', 'instagram', 'story'],
            ['Denne uka: fotball på allidrett', '2026-11-04', 'instagram', 'story'],
            ['Denne uka: svømming på allidrett', '2026-11-18', 'instagram', 'story'],
            ['Denne uka: håndball på allidrett', '2026-12-02', 'instagram', 'story'],
            ['Denne uka: volleyball på allidrett', '2027-01-06', 'instagram', 'story'],
            ['Avslutning allidrett – takk for året! (bildepost)', '2027-05-19', 'facebook', 'post'],
        ];

        $sort = 0;
        foreach ($tasks as [$label, $date, $platform, $format]) {
            DB::table('tasks')->insert([
                'company_id' => $flik->id,
                'event_id' => $eventId,
                'label' => $label,
                'publish_date' => $date,
                'status' => 'planlagt',
                'platform' => $platform,
                'format' => $format,
                'sort_order' => $sort++,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        $flik = DB::table('companies')->where('slug', 'flik')->first();
        if (! $flik) {
            return;
        }
        $event = DB::table('events')->where('company_id', $flik->id)
            ->where('title', 'FLIK Allidrett 2026/2027')->first();
        if ($event) {
            DB::table('tasks')->where('event_id', $event->id)->delete();
            DB::table('events')->where('id', $event->id)->delete();
        }
        DB::table('destinations')->where('company_id', $flik->id)->where('name', 'FLIK Allidrett')->delete();
        DB::table('categories')->where('company_id', $flik->id)->where('name', 'Allidrett')->delete();
    }
};
