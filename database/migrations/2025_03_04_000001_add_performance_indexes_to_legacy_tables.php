<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Non-destructive migration: adds indexes only to improve query performance.
 * Does not drop or modify columns. Safe to run on production.
 * Skips tables that do not exist; skips indexes that already exist.
 */
return new class extends Migration
{
    public function up(): void
    {
        $this->safeIndex('students', 'students_status_class_idx', ['status', 'class']);
        $this->safeIndex('students', 'students_reg_number_status_idx', ['reg_number', 'status']);
        $this->safeIndex('students', 'students_class_arm_status_idx', ['class_arm', 'status']);
        $this->safeIndex('annual_result', 'annual_result_class_arm_term_session_idx', ['class_arm', 'term', 'session']);
        $this->safeIndex('annual_result', 'annual_result_reg_number_idx', ['reg_number']);
        $this->safeIndex('positions', 'positions_class_term_session_idx', ['class', 'term', 'session']);
        $this->safeIndex('positions', 'positions_reg_number_class_term_session_idx', ['reg_number', 'class', 'term', 'session']);
        $this->safeIndex('attendance_list', 'attendance_list_class_term_session_segment_idx', ['class', 'term', 'session', 'segment']);
        $this->safeIndex('behavioral', 'behavioral_class_term_session_segment_idx', ['class', 'term', 'session', 'segment']);
        $this->safeIndex('used_pins', 'used_pins_pins_idx', ['pins']);
    }

    /** @param list<string> $columns */
    private function safeIndex(string $table, string $indexName, array $columns): void
    {
        if (! Schema::hasTable($table)) {
            return;
        }

        try {
            Schema::table($table, function (Blueprint $blueprint) use ($indexName, $columns): void {
                $blueprint->index($columns, $indexName);
            });
        } catch (\Throwable) {
            // Index may already exist; skip
        }
    }

    public function down(): void
    {
        $tables = [
            'students' => ['students_status_class_idx', 'students_reg_number_status_idx', 'students_class_arm_status_idx'],
            'annual_result' => ['annual_result_class_arm_term_session_idx', 'annual_result_reg_number_idx'],
            'positions' => ['positions_class_term_session_idx', 'positions_reg_number_class_term_session_idx'],
            'attendance_list' => ['attendance_list_class_term_session_segment_idx'],
            'behavioral' => ['behavioral_class_term_session_segment_idx'],
            'used_pins' => ['used_pins_pins_idx'],
        ];
        foreach ($tables as $table => $indexes) {
            if (! Schema::hasTable($table)) {
                continue;
            }
            foreach ($indexes as $index) {
                try {
                    Schema::table($table, fn (Blueprint $b) => $b->dropIndex($index));
                } catch (\Throwable) {
                    // Index may not exist
                }
            }
        }
    }
};
