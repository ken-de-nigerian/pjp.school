<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adds legacy teacher columns to user table if missing. Safe for production.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('user')) {
            return;
        }

        $columns = [
            'othername' => fn (Blueprint $t) => $t->string('othername')->nullable(),
            'date_of_birth' => fn (Blueprint $t) => $t->date('date_of_birth')->nullable(),
            'lga' => fn (Blueprint $t) => $t->string('lga')->nullable(),
            'state' => fn (Blueprint $t) => $t->string('state')->nullable(),
            'city' => fn (Blueprint $t) => $t->string('city')->nullable(),
            'employment_date' => fn (Blueprint $t) => $t->date('employment_date')->nullable(),
            'assigned_class' => fn (Blueprint $t) => $t->string('assigned_class')->nullable(),
            'subject_to_teach' => fn (Blueprint $t) => $t->string('subject_to_teach')->nullable(),
            'password_change_date' => fn (Blueprint $t) => $t->dateTime('password_change_date')->nullable(),
            'form_teacher' => fn (Blueprint $t) => $t->unsignedTinyInteger('form_teacher')->nullable(),
            'modify_results' => fn (Blueprint $t) => $t->unsignedTinyInteger('modify_results')->nullable(),
        ];

        foreach ($columns as $name => $callback) {
            if (! Schema::hasColumn('user', $name)) {
                Schema::table('user', $callback);
            }
        }
    }

    public function down(): void
    {
        // Optional: drop added columns. Omitted to avoid data loss.
    }
};
