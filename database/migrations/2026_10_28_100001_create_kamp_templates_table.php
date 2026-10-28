<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Maler for kampplakater – per idrett og type (oversikt/enkeltkamp), delt på klubben.
        Schema::create('kamp_templates', function (Blueprint $t) {
            $t->id();
            $t->foreignId('company_id')->constrained()->cascadeOnDelete();
            $t->string('sport', 80);
            $t->string('mode', 10);              // 'ov' | 'ek'
            $t->string('name', 120);
            $t->json('data');                    // felt + bilde-URLer
            $t->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $t->timestamps();
            $t->unique(['company_id', 'sport', 'mode', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kamp_templates');
    }
};
