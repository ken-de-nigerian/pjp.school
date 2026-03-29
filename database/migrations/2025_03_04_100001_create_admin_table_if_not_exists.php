<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Creates admin table if it does not exist. Safe for production (no-op if table exists).
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('admin')) {
            return;
        }

        Schema::create('admin', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->index('email', 'admin_email_index');
            $table->string('phone')->nullable();
            $table->string('password');
            $table->string('profileImage')->nullable();
            $table->unsignedBigInteger('user_type')->nullable();
            $table->string('security')->nullable();
            $table->dateTime('joined')->nullable();
            $table->dateTime('password_change_date')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin');
    }
};
