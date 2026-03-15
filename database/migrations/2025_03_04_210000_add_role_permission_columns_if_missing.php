<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Phase 4: Add legacy role permission columns to roles table.
 * Safe: only adds columns if they don't exist (for existing Laravel installs).
 * Legacy DB may already have these.
 */
return new class extends Migration
{
    private const PERMISSION_COLUMNS = [
        'attendance',
        'view_uploaded_attendance',
        'behavioural_analysis',
        'view_uploaded_behavioural_analysis',
        'manage_subjects',
        'upload_result',
        'view_uploaded_results',
        'publish_result',
        'view_published_results',
        'transcript',
        'check_result_status',
        'manage_students',
        'manage_teachers',
        'manage_staffs',
        'online_entrance',
        'manage_scratch_card',
        'news',
        'bulk_sms',
        'general_settings',
    ];

    public function up(): void
    {
        if (! Schema::hasTable('roles')) {
            return;
        }

        Schema::table('roles', function (Blueprint $table) {
            foreach (self::PERMISSION_COLUMNS as $col) {
                if (! Schema::hasColumn('roles', $col)) {
                    $table->unsignedTinyInteger($col)->default(0);
                }
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('roles')) {
            return;
        }

        Schema::table('roles', function (Blueprint $table) {
            foreach (self::PERMISSION_COLUMNS as $col) {
                if (Schema::hasColumn('roles', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
