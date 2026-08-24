<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Navngitte øyeblikksbilder av rutenettet. Én arbeidsplan (training_assignments)
        // + lagrede versjoner man kan gjenopprette. Auto-versjon tas før AI kjører.
        Schema::create('training_plan_versions', function (Blueprint $t) {
            $t->id();
            $t->foreignId('company_id')->constrained()->cascadeOnDelete();
            $t->foreignId('training_season_id')->constrained()->cascadeOnDelete();
            $t->string('name');
            $t->json('snapshot');            // array av tildelinger
            $t->boolean('is_auto')->default(false);
            $t->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $t->timestamps();
            $t->index(['company_id', 'training_season_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('training_plan_versions');
    }
};
