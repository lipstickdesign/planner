<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('klubbliv_posts', function (Blueprint $t) {
            $t->id();
            $t->foreignId('company_id')->constrained()->cascadeOnDelete();
            $t->foreignId('content_idea_id')->nullable()->constrained()->nullOnDelete();
            $t->string('title');
            $t->text('body_draft')->nullable();
            $t->date('publish_date')->nullable();
            $t->json('destination_ids')->nullable();
            $t->string('status')->default('planlagt');
            $t->unsignedInteger('sort_order')->default(0);
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('klubbliv_posts');
    }
};
