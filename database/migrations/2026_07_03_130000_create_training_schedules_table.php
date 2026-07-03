<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('training_schedules', function (Blueprint $t) {
            $t->id();
            $t->foreignId('company_id')->constrained()->cascadeOnDelete();
            $t->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $t->unsignedTinyInteger('weekday'); // ISO: 1=mandag .. 7=søndag
            $t->string('group_label')->nullable(); // f.eks. "G10", "Knøtt", "Alle"
            $t->time('start_time')->nullable();
            $t->time('end_time')->nullable();
            $t->string('location')->nullable();
            $t->string('note')->nullable();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('training_schedules');
    }
};
