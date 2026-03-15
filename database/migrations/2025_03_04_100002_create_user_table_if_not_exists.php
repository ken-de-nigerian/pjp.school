<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Creates user table (teachers) if it does not exist. Safe for production (no-op if table exists).
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('user')) {
            return;
        }

        Schema::create('user', function (Blueprint $table) {
            $table->string('userId', 64)->primary();
            $table->string('email')->nullable();
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('country')->nullable();
            $table->string('gender')->nullable();
            $table->string('profileImage')->nullable();
            $table->string('password');
            $table->dateTime('registration_date')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user');
    }
};
