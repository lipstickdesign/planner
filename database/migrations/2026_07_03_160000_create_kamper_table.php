<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kamper', function (Blueprint $t) {
            $t->id();
            $t->foreignId('company_id')->constrained()->cascadeOnDelete();
            $t->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $t->string('title'); // motstander / kampnavn
            $t->date('match_date');
            $t->time('match_time')->nullable();
            $t->string('location')->nullable();
            $t->boolean('home')->default(true); // hjemmekamp
            $t->string('note')->nullable();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kamper');
    }
};
