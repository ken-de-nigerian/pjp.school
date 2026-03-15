<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Phase 3: Fix settings table - scratch_card, bulk_sms, maintenance_mode
 * should be INT(1) (0/1) not string. Uses raw SQL to avoid doctrine/dbal dependency.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('settings')) {
            return;
        }

        $driver = Schema::getConnection()->getDriverName();
        if ($driver === 'mysql') {
            foreach (['scratch_card', 'bulk_sms', 'maintenance_mode'] as $col) {
                if (Schema::hasColumn('settings', $col)) {
                    DB::statement("ALTER TABLE settings MODIFY {$col} TINYINT(1) UNSIGNED NULL DEFAULT 0");
                }
            }
        }
        // SQLite / others: column type left as-is; Setting model casts to integer for consistency.
    }

    public function down(): void
    {
        if (! Schema::hasTable('settings')) {
            return;
        }

        $driver = Schema::getConnection()->getDriverName();
        if ($driver === 'mysql') {
            foreach (['scratch_card', 'bulk_sms', 'maintenance_mode'] as $col) {
                if (Schema::hasColumn('settings', $col)) {
                    DB::statement("ALTER TABLE settings MODIFY {$col} VARCHAR(255) NULL");
                }
            }
        }
    }
};
