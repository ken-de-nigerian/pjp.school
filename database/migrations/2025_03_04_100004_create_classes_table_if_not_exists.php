<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Creates classes table if it does not exist. Safe for production.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('classes')) {
            return;
        }

        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('class_name')->nullable();
            $table->index('class_name', 'classes_class_name_index');
            $table->dateTime('time_added')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
};
