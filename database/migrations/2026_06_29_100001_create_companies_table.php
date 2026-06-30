<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('org_type')->default('idrettsklubb');
            $table->string('logo_path')->nullable();
            $table->string('brand_primary', 7)->default('#00529b');
            $table->string('brand_accent', 7)->default('#ffb81c');
            $table->string('font')->default('Ubuntu');
            $table->string('timezone')->default('Europe/Oslo');
            $table->json('settings')->nullable();
            $table->enum('status', ['trial', 'active', 'suspended'])->default('trial');
            $table->string('stripe_id')->nullable();
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
