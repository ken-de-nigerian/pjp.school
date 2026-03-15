<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Creates pin_code, unused_pins, used_pins tables if they do not exist.
 * Safe for production; matches legacy schema.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('pin_code')) {
            Schema::create('pin_code', function (Blueprint $table) {
                $table->id();
                $table->string('session')->nullable();
                $table->string('pin')->nullable();
                $table->string('serial_number')->nullable();
                $table->dateTime('upload_date')->nullable();
            });
        }

        if (! Schema::hasTable('unused_pins')) {
            Schema::create('unused_pins', function (Blueprint $table) {
                $table->id();
                $table->string('session')->nullable();
                $table->string('pins')->nullable();
                $table->string('serial_number')->nullable();
                $table->dateTime('upload_date')->nullable();
            });
        }

        if (! Schema::hasTable('used_pins')) {
            Schema::create('used_pins', function (Blueprint $table) {
                $table->id();
                $table->string('pins')->nullable();
                $table->string('reg_number')->nullable();
                $table->unsignedInteger('used_count')->default(0);
                $table->string('class')->nullable();
                $table->string('term')->nullable();
                $table->string('session')->nullable();
                $table->dateTime('time_used')->nullable();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('used_pins');
        Schema::dropIfExists('unused_pins');
        Schema::dropIfExists('pin_code');
    }
};
