<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $flik = DB::table('companies')->where('slug', 'flik')->first();
        if (! $flik) {
            return;
        }

        $exists = DB::table('categories')
            ->where('company_id', $flik->id)->where('name', 'Sandvolleyball')->first();

        if (! $exists) {
            $sort = (int) DB::table('categories')->where('company_id', $flik->id)->max('sort_order') + 1;
            $catId = DB::table('categories')->insertGetId([
                'company_id' => $flik->id,
                'name' => 'Sandvolleyball',
                'color' => '#e2725b',
                'sort_order' => $sort,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            if (! DB::table('destinations')->where('company_id', $flik->id)->where('name', 'FLIK Sandvolleyball')->exists()) {
                DB::table('destinations')->insert([
                    'company_id' => $flik->id,
                    'name' => 'FLIK Sandvolleyball',
                    'platform' => 'facebook',
                    'category_id' => $catId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        $flik = DB::table('companies')->where('slug', 'flik')->first();
        if (! $flik) {
            return;
        }
        DB::table('destinations')->where('company_id', $flik->id)->where('name', 'FLIK Sandvolleyball')->delete();
        DB::table('categories')->where('company_id', $flik->id)->where('name', 'Sandvolleyball')->delete();
    }
};
