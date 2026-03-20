<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Creates behavioral table if it does not exist. Safe for production.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('behavioral')) {
            return;
        }

        Schema::create('behavioral', function (Blueprint $table) {
            $table->id();
            $table->string('class')->nullable();
            $table->string('term')->nullable();
            $table->string('session')->nullable();
            $table->string('segment')->nullable();
            $table->string('name')->nullable();
            $table->string('reg_number')->nullable();
            $table->string('neatness')->nullable();
            $table->string('music')->nullable();
            $table->string('sports')->nullable();
            $table->string('attentiveness')->nullable();
            $table->string('punctuality')->nullable();
            $table->string('health')->nullable();
            $table->string('politeness')->nullable();
            $table->dateTime('date_added')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('behavioral');
    }
};
