<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('company_user', function (Blueprint $table) {
            $table->string('role')->default('medlem')->after('area');
        });

        // Sett daglig leder og superadmin som admin
        $adminIds = DB::table('users')
            ->whereIn('email', ['roger@havdurdesign.no', 'helge@flik.no'])
            ->pluck('id');
        if ($adminIds->isNotEmpty()) {
            DB::table('company_user')->whereIn('user_id', $adminIds)->update(['role' => 'admin']);
        }
    }

    public function down(): void
    {
        Schema::table('company_user', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
