<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $t) {
            if (! Schema::hasColumn('tasks', 'responsible_user_id')) {
                $t->foreignId('responsible_user_id')->nullable()->after('format')->constrained('users')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $t) {
            if (Schema::hasColumn('tasks', 'responsible_user_id')) {
                $t->dropConstrainedForeignId('responsible_user_id');
            }
        });
    }
};
