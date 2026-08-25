<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Fritekst-notater/ønsker på lagets «infokort» – brukes også i AI-analysen.
        Schema::table('training_teams', function (Blueprint $t) {
            $t->text('notes')->nullable()->after('coach_unavailable');
        });
    }

    public function down(): void
    {
        Schema::table('training_teams', function (Blueprint $t) {
            $t->dropColumn('notes');
        });
    }
};
