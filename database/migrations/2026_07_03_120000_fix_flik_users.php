<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Helge: ny e-post (rolle/admin beholdes – pivot er koblet på user_id)
        DB::table('users')->where('email', 'helge@flik.no')
            ->update(['email' => 'dagligleder@flik.no']);

        // Karstein: ny e-post
        DB::table('users')->where('email', 'fotball@flik.no')
            ->update(['email' => 'sportsligansvarlig@flik.no']);

        // Siv erstattes av Trond (leder av hovedstyret)
        DB::table('users')->where('email', 'siv@flik.no')
            ->update(['name' => 'Trond', 'email' => 'leder@flik.no']);

        // Oppdater titler/områder i company_user-pivot
        $flik = DB::table('companies')->where('slug', 'flik')->value('id');
        if ($flik) {
            $set = function (string $email, string $title, string $area) use ($flik) {
                $uid = DB::table('users')->where('email', $email)->value('id');
                if ($uid) {
                    DB::table('company_user')
                        ->where('company_id', $flik)
                        ->where('user_id', $uid)
                        ->update(['title' => $title, 'area' => $area]);
                }
            };
            $set('sportsligansvarlig@flik.no', 'Sportslig ansvarlig', 'Sport / alle grupper');
            $set('leder@flik.no', 'Leder hovedstyret', 'Hovedstyret / godkjenning');
        }
    }

    public function down(): void
    {
        DB::table('users')->where('email', 'dagligleder@flik.no')
            ->update(['email' => 'helge@flik.no']);
        DB::table('users')->where('email', 'sportsligansvarlig@flik.no')
            ->update(['email' => 'fotball@flik.no']);
        DB::table('users')->where('email', 'leder@flik.no')
            ->update(['name' => 'Siv', 'email' => 'siv@flik.no']);
    }
};
