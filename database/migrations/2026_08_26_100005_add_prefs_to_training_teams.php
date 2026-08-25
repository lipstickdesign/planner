<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Strukturerte preferanser som analysen kan bruke direkte.
        Schema::table('training_teams', function (Blueprint $t) {
            $t->json('avoid_days')->nullable()->after('notes');        // ukedager laget helst ikke vil trene
            $t->string('latest_end', 5)->nullable()->after('avoid_days'); // seneste sluttid hverdager, «HH:MM»
        });
    }

    public function down(): void
    {
        Schema::table('training_teams', function (Blueprint $t) {
            $t->dropColumn(['avoid_days', 'latest_end']);
        });
    }
};
