<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedInteger('base_price')->default(99000); // øre = 990 kr
            $table->unsignedInteger('seat_price')->default(9900);  // øre = 99 kr
            $table->string('stripe_price_base')->nullable();
            $table->string('stripe_price_seat')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
