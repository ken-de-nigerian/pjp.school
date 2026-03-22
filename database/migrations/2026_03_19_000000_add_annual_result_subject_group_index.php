<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Speeds up GROUP BY reg_number, subjects, class_arm, term, session on annual_result.
 */
return new class extends Migration
{
    private const TABLE = 'annual_result';

    private const INDEX = 'annual_result_reg_subject_classarm_term_session_idx';

    public function up(): void
    {
        if (! Schema::hasTable(self::TABLE)) {
            return;
        }

        try {
            Schema::table(self::TABLE, function (Blueprint $blueprint): void {
                $blueprint->index(
                    ['reg_number', 'subjects', 'class_arm', 'term', 'session'],
                    self::INDEX
                );
            });
        } catch (\Throwable) {
            // Index may already exist
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable(self::TABLE)) {
            return;
        }

        try {
            Schema::table(self::TABLE, fn (Blueprint $b) => $b->dropIndex(self::INDEX));
        } catch (\Throwable) {
            //
        }
    }
};
