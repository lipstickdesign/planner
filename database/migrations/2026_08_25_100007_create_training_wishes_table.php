<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('training_wishes', function (Blueprint $t) {
            $t->id();
            $t->foreignId('company_id')->constrained()->cascadeOnDelete();
            $t->foreignId('training_team_id')->constrained()->cascadeOnDelete();
            $t->unsignedTinyInteger('priority')->default(1); // 1–3
            $t->string('weekday');                            // Mandag … Søndag
            $t->string('time')->nullable();                   // f.eks. "17:30" eller "17:30-18:30"
            $t->string('note')->nullable();                   // begrunnelse
            $t->timestamps();
            $t->index(['company_id', 'training_team_id']);
        });

        Schema::table('training_teams', function (Blueprint $t) {
            // Trenernes utilgjengelighet (fritekst nå; struktureres ved behov senere).
            $t->string('coach_unavailable')->nullable()->after('external_ref');
        });

        // Fotball: alle lag skal ha én innendørsøkt (blankregel). Settes på
        // eksisterende fotballag uten å røre andre felter (ingen re-seed).
        DB::statement(
            "UPDATE training_teams tt JOIN categories c ON c.id = tt.category_id ".
            "SET tt.requires_indoor = 1 WHERE LOWER(c.name) = 'fotball'"
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('training_wishes');
        Schema::table('training_teams', function (Blueprint $t) {
            $t->dropColumn('coach_unavailable');
        });
    }
};
