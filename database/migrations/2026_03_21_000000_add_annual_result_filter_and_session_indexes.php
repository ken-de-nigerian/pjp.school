<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * annual_result performance (results_by_params / aggregation / report flows).
 *
 * Existing (do not duplicate in down()):
 * - annual_result_class_arm_term_session_idx (class_arm, term, session)
 * - annual_result_reg_number_idx (reg_number)
 * - annual_result_reg_subject_classarm_term_session_idx (reg_number, subjects, class_arm, term, session)
 *
 * Adds:
 * 1) Leading filter for student report + publish: (reg_number, class_arm, term, session)
 * 2) DISTINCT / ORDER BY session for dropdowns
 * 3) Upload / delete-by-context style filters: (class, term, session, subjects)
 *
 * Suggested EXPLAIN (MySQL):
 * EXPLAIN SELECT ... FROM annual_result WHERE reg_number = ? AND class_arm = ? AND term = ? AND session = ? GROUP BY ...
 */
return new class extends Migration
{
    private const TABLE = 'annual_result';

    public function up(): void
    {
        if (! Schema::hasTable(self::TABLE)) {
            return;
        }

        $this->safeIndex(
            'annual_result_reg_classarm_term_session_idx',
            ['reg_number', 'class_arm', 'term', 'session']
        );

        $this->safeIndex(
            'annual_result_session_idx',
            ['session']
        );

        $this->safeIndex(
            'annual_result_class_term_session_subjects_idx',
            ['class', 'term', 'session', 'subjects']
        );
    }

    public function down(): void
    {
        if (! Schema::hasTable(self::TABLE)) {
            return;
        }

        foreach ([
            'annual_result_reg_classarm_term_session_idx',
            'annual_result_session_idx',
            'annual_result_class_term_session_subjects_idx',
        ] as $index) {
            try {
                Schema::table(self::TABLE, fn (Blueprint $b) => $b->dropIndex($index));
            } catch (\Throwable) {
                //
            }
        }
    }

    /**
     * @param  list<string>  $columns
     */
    private function safeIndex(string $indexName, array $columns): void
    {
        try {
            Schema::table(self::TABLE, function (Blueprint $blueprint) use ($indexName, $columns): void {
                $blueprint->index($columns, $indexName);
            });
        } catch (\Throwable) {
            // Index may already exist (re-run migration, manual add, etc.)
        }
    }
};
