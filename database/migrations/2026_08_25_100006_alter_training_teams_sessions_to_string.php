<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Økter per uke kan være et spenn ("2-3" for senior), ikke bare ett tall.
        Schema::table('training_teams', function (Blueprint $t) {
            $t->string('sessions_per_week', 20)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('training_teams', function (Blueprint $t) {
            $t->unsignedTinyInteger('sessions_per_week')->nullable()->change();
        });
    }
};
