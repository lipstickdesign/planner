<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Lag som IKKE kan trene samtidig (samme barn på tvers av idrett), som lag-id-liste.
        Schema::table('training_teams', function (Blueprint $t) {
            $t->json('no_collide')->nullable()->after('allowed_facilities');
        });
    }

    public function down(): void
    {
        Schema::table('training_teams', function (Blueprint $t) {
            $t->dropColumn('no_collide');
        });
    }
};
