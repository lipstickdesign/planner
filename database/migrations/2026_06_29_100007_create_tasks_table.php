<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->foreignId('post_type_id')->nullable()->constrained()->nullOnDelete();
            $table->string('label');
            $table->text('body_draft')->nullable();
            $table->string('draft_url')->nullable();
            $table->date('publish_date')->nullable();
            $table->time('scheduled_time')->nullable();
            $table->enum('status', ['planlagt', 'under_arbeid', 'klar', 'publisert'])->default('planlagt');
            $table->enum('approval_status', ['utkast', 'til_godkjenning', 'godkjent'])->default('utkast');
            $table->foreignId('responsible_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->index(['company_id', 'publish_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
