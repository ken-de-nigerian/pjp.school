<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Creates settings table if it does not exist. Safe for production.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('settings')) {
            return;
        }

        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('slogan')->nullable();
            $table->string('address')->nullable();
            $table->string('term')->nullable();
            $table->string('session')->nullable();
            $table->string('segment')->nullable();
            $table->string('closed')->nullable();
            $table->string('resumption')->nullable();
            $table->string('timezone')->nullable();
            $table->string('scratch_card')->nullable();
            $table->string('bulk_sms')->nullable();
            $table->string('maintenance_mode')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
