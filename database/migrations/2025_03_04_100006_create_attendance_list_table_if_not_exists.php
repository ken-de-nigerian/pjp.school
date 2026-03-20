<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Creates attendance_list table if it does not exist. Safe for production.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('attendance_list')) {
            return;
        }

        Schema::create('attendance_list', function (Blueprint $table) {
            $table->id();
            $table->string('class')->nullable();
            $table->string('term')->nullable();
            $table->string('session')->nullable();
            $table->string('segment')->nullable();
            $table->string('name')->nullable();
            $table->string('reg_number')->nullable();
            $table->string('class_roll_call')->nullable();
            $table->dateTime('date_added')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_list');
    }
};
