<?php

declare(strict_types=1);

use App\Support\Coercion;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Non-destructive: adds indexes only (consolidates former 2025_03_04_000001_add_performance_indexes_to_legacy_tables
 * plus annual_result / payment follow-ups). Timestamp is late so this runs after all table-creating migrations.
 * Skips missing tables/columns; ignores duplicate index errors.
 */
return new class extends Migration
{
    public function up(): void
    {
        $paymentTable = Coercion::string(config('payments.logging.table'), 'payment_transactions');

        $this->safeIndex('students', 'students_status_class_idx', ['status', 'class']);
        $this->safeIndex('students', 'students_reg_number_status_idx', ['reg_number', 'status']);
        $this->safeIndex('students', 'students_class_arm_status_idx', ['class_arm', 'status']);
        $this->safeIndex('students', 'students_status_fee_status_index', ['status', 'fee_status']);
        $this->safeIndex('students', 'students_house_index', ['house']);
        $this->safeIndex('students', 'students_category_index', ['category']);

        $this->safeIndex('annual_result', 'annual_result_class_arm_term_session_idx', ['class_arm', 'term', 'session']);
        $this->safeIndex('annual_result', 'annual_result_reg_number_idx', ['reg_number']);
        $this->safeIndex('annual_result', 'annual_result_student_id_idx', ['studentId']);
        $this->safeIndex('annual_result', 'annual_result_status_idx', ['status']);
        $this->safeIndex('annual_result', 'annual_result_date_added_idx', ['date_added']);
        $this->safeIndex('annual_result', 'annual_result_reg_subject_classarm_term_session_idx', ['reg_number', 'subjects', 'class_arm', 'term', 'session']);
        $this->safeIndex('annual_result', 'annual_result_reg_classarm_term_session_idx', ['reg_number', 'class_arm', 'term', 'session']);
        $this->safeIndex('annual_result', 'annual_result_session_idx', ['session']);
        $this->safeIndex('annual_result', 'annual_result_class_term_session_subjects_idx', ['class', 'term', 'session', 'subjects']);

        $this->safeIndex('positions', 'positions_class_term_session_idx', ['class', 'term', 'session']);
        $this->safeIndex('positions', 'positions_reg_number_class_term_session_idx', ['reg_number', 'class', 'term', 'session']);
        $this->safeIndex('attendance_list', 'attendance_list_class_term_session_segment_idx', ['class', 'term', 'session', 'segment']);
        $this->safeIndex('behavioral', 'behavioral_class_term_session_segment_idx', ['class', 'term', 'session', 'segment']);
        $this->safeIndex('used_pins', 'used_pins_pins_idx', ['pins']);

        $this->safeIndex($paymentTable, 'payment_transactions_email_index', ['email']);
        $this->safeIndex($paymentTable, 'payment_transactions_paid_at_index', ['paid_at']);
    }

    public function down(): void
    {
        $paymentTable = Coercion::string(config('payments.logging.table'), 'payment_transactions');

        $tables = [
            'students' => [
                'students_status_class_idx',
                'students_reg_number_status_idx',
                'students_class_arm_status_idx',
                'students_status_fee_status_index',
                'students_house_index',
                'students_category_index',
            ],
            'annual_result' => [
                'annual_result_class_arm_term_session_idx',
                'annual_result_reg_number_idx',
                'annual_result_student_id_idx',
                'annual_result_status_idx',
                'annual_result_date_added_idx',
                'annual_result_reg_subject_classarm_term_session_idx',
                'annual_result_reg_classarm_term_session_idx',
                'annual_result_session_idx',
                'annual_result_class_term_session_subjects_idx',
            ],
            'positions' => [
                'positions_class_term_session_idx',
                'positions_reg_number_class_term_session_idx',
            ],
            'attendance_list' => ['attendance_list_class_term_session_segment_idx'],
            'behavioral' => ['behavioral_class_term_session_segment_idx'],
            'used_pins' => ['used_pins_pins_idx'],
        ];
        $tables[$paymentTable] = [
            'payment_transactions_email_index',
            'payment_transactions_paid_at_index',
        ];

        foreach ($tables as $table => $indexes) {
            if (! Schema::hasTable($table)) {
                continue;
            }
            foreach ($indexes as $index) {
                try {
                    Schema::table($table, fn (Blueprint $b) => $b->dropIndex($index));
                } catch (Throwable) {
                    //
                }
            }
        }
    }

    /**
     * @param  list<string>  $columns
     */
    private function safeIndex(string $table, string $indexName, array $columns): void
    {
        if (! Schema::hasTable($table)) {
            return;
        }

        foreach ($columns as $column) {
            if (! Schema::hasColumn($table, $column)) {
                return;
            }
        }

        try {
            Schema::table($table, function (Blueprint $blueprint) use ($indexName, $columns): void {
                $blueprint->index($columns, $indexName);
            });
        } catch (Throwable) {
            //
        }
    }
};
