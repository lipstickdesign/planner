<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('training_facilities', function (Blueprint $t) {
            $t->id();
            $t->foreignId('company_id')->constrained()->cascadeOnDelete();
            $t->string('name');                       // f.eks. "Alcoa fotballhall"
            $t->string('type')->nullable();           // hall / kunstgress / gress / friidrett
            $t->unsignedTinyInteger('zones')->default(1); // antall soner banen kan deles i
            $t->json('allowed_sports')->nullable();   // ["Fotball","Friidrett"]
            $t->string('status')->default('aktiv');   // aktiv / kommende (f.eks. Listahallen)
            $t->json('opening_hours')->nullable();    // { "Mandag": ["16:00","22:00"], ... }
            $t->timestamps();
            $t->index('company_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('training_facilities');
    }
};
