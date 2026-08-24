<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Rutenettet blir fasit: en tildeling kan være fri etikett (Kamper, Friidrett)
        // uten lag-kobling, og kan være låst hos annen klubb (Spind/Bobcats).
        Schema::table('training_assignments', function (Blueprint $t) {
            $t->dropForeign(['training_team_id']);
        });
        Schema::table('training_assignments', function (Blueprint $t) {
            $t->foreignId('training_team_id')->nullable()->change();
            $t->string('label')->nullable()->after('training_team_id');
            $t->string('org', 40)->default('FLIK')->after('label');
            $t->boolean('locked')->default(false)->after('org');
        });
        Schema::table('training_assignments', function (Blueprint $t) {
            $t->foreign('training_team_id')->references('id')->on('training_teams')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('training_assignments', function (Blueprint $t) {
            $t->dropForeign(['training_team_id']);
            $t->dropColumn(['label', 'org', 'locked']);
        });
        Schema::table('training_assignments', function (Blueprint $t) {
            $t->foreignId('training_team_id')->nullable(false)->change();
            $t->foreign('training_team_id')->references('id')->on('training_teams')->cascadeOnDelete();
        });
    }
};
