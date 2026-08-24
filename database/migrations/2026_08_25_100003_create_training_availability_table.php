<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // IR-tildelingen: hvilke tidsvinduer (vilkårlige, 30-min oppløsning) FLIK
        // disponerer per anlegg/sone/dag, og hva som er låst hos andre klubber.
        Schema::create('training_availability', function (Blueprint $t) {
            $t->id();
            $t->foreignId('company_id')->constrained()->cascadeOnDelete();
            $t->foreignId('training_season_id')->constrained()->cascadeOnDelete();
            $t->foreignId('training_facility_id')->constrained()->cascadeOnDelete();
            $t->unsignedTinyInteger('zone')->nullable();  // hvilken sone (null = hele flaten)
            $t->string('weekday');                        // Mandag … Fredag
            $t->time('from_time');
            $t->time('to_time');
            $t->string('owner')->default('FLIK');         // FLIK / Spind / Bobcats …
            $t->string('status')->default('disponibel');  // disponibel / laast
            $t->timestamps();
            $t->index(['company_id', 'training_season_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('training_availability');
    }
};
