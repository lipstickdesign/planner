<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kamper', function (Blueprint $t) {
            $t->string('external_uid')->nullable()->after('id');
            $t->string('home_team')->nullable()->after('title');
            $t->string('away_team')->nullable()->after('home_team');
            $t->string('tournament')->nullable()->after('away_team');
            $t->string('source')->nullable()->after('note'); // f.eks. 'fotball.no'
            $t->index(['company_id', 'external_uid']);
        });

        // Seed FLIKs offisielle kalender-feed + klubb-aliaser (for hjemmekamp-filter).
        $flik = DB::table('companies')->where('slug', 'flik')->first();
        if ($flik) {
            $settings = json_decode($flik->settings ?? '{}', true) ?: [];
            $settings['football_ical_url'] = 'https://www.fotball.no/footballapi/Calendar/GetCalendarForClub?clubId=3270';
            $settings['club_aliases'] = ['Farsund & Lista IK', 'FLIK'];
            DB::table('companies')->where('id', $flik->id)->update(['settings' => json_encode($settings)]);
        }
    }

    public function down(): void
    {
        Schema::table('kamper', function (Blueprint $t) {
            $t->dropIndex(['company_id', 'external_uid']);
            $t->dropColumn(['external_uid', 'home_team', 'away_team', 'tournament', 'source']);
        });
    }
};
