<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Resultatet: hvilket lag som er lagt til hvilken blokk. Skiller mellom
        // tildelt blokk og faktisk treningstid (kortere økt teller ikke som brudd).
        Schema::create('training_assignments', function (Blueprint $t) {
            $t->id();
            $t->foreignId('company_id')->constrained()->cascadeOnDelete();
            $t->foreignId('training_season_id')->constrained()->cascadeOnDelete();
            $t->foreignId('training_team_id')->constrained()->cascadeOnDelete();
            $t->foreignId('training_facility_id')->constrained()->cascadeOnDelete();
            $t->unsignedTinyInteger('zone')->nullable();
            $t->string('weekday');
            $t->time('block_start');
            $t->time('block_end');
            $t->time('actual_start')->nullable();   // faktisk treningstid inni blokka
            $t->time('actual_end')->nullable();
            $t->text('reason')->nullable();         // maskinens/menneskets begrunnelse
            $t->boolean('manual_override')->default(false);
            $t->unsignedInteger('version')->default(1);
            $t->foreignId('changed_by')->nullable()->constrained('users')->nullOnDelete();
            $t->timestamps();
            $t->index(['company_id', 'training_season_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('training_assignments');
    }
};
