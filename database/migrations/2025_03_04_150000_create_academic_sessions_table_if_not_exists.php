<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Creates academic_sessions table for school year list (e.g. 2024/2025).
 * Legacy uses "sessions" with column "year"; Laravel reserves "sessions" for HTTP, so we use academic_sessions.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('academic_sessions')) {
            return;
        }

        Schema::create('academic_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('year', 50)->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_sessions');
    }
};
