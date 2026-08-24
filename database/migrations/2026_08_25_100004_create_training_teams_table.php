<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Lag i fordelingsmodulen. Speilede felter kopieres inn ved sesongstart
        // (Hoopit forblir fasit). Fordelingsfeltene eies av Vivu.
        Schema::create('training_teams', function (Blueprint $t) {
            $t->id();
            $t->foreignId('company_id')->constrained()->cascadeOnDelete();
            $t->foreignId('category_id')->nullable()->constrained()->nullOnDelete(); // idrett → farge fra Vivu

            // Speilet fra Hoopit/regneark
            $t->string('name');
            $t->string('birth_year')->nullable();  // årskull, kan være spenn ("2015-2016")
            $t->string('grade')->nullable();       // skoletrinn (viktig for kryssidrett)
            $t->unsignedSmallInteger('players')->nullable();
            $t->unsignedSmallInteger('coaches')->nullable();

            // Fordelingsfelter (Vivu eier)
            $t->string('area_indoor')->nullable();   // hel hall / halv hall / kan dele
            $t->string('area_outdoor')->nullable();  // hel bane / halv bane / 3er-bane …
            $t->unsignedTinyInteger('sessions_per_week')->nullable();
            $t->boolean('requires_indoor')->default(false);
            $t->json('allowed_facilities')->nullable(); // begrenset til visse anlegg
            $t->string('external_ref')->nullable();     // f.eks. Hoopit-id for senere synk

            $t->timestamps();
            $t->index('company_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('training_teams');
    }
};
