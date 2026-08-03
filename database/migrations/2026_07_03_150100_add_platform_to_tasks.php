<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $t) {
            $t->string('platform')->nullable()->after('status'); // facebook, instagram, tiktok …
            $t->string('format')->nullable()->after('platform'); // post | story
        });
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $t) {
            $t->dropColumn(['platform', 'format']);
        });
    }
};
