<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Creates annual_result table if it does not exist. Safe for production.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('annual_result')) {
            return;
        }

        Schema::create('annual_result', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('studentId')->nullable();
            $table->string('class')->nullable();
            $table->string('class_arm')->nullable();
            $table->string('term')->nullable();
            $table->string('session')->nullable();
            $table->string('subjects')->nullable();
            $table->string('name')->nullable();
            $table->string('reg_number')->nullable();
            $table->string('segment')->nullable();
            $table->decimal('ca', 10, 2)->nullable();
            $table->decimal('assignment', 10, 2)->nullable();
            $table->decimal('exam', 10, 2)->nullable();
            $table->decimal('total', 10, 2)->nullable();
            $table->unsignedTinyInteger('status')->default(1);
            $table->dateTime('date_added')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('annual_result');
    }
};
