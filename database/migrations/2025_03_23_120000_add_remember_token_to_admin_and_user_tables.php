<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Required for "Remember me" on admin and teacher login (SessionGuard persists a token here).
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('admin') && ! Schema::hasColumn('admin', 'remember_token')) {
            Schema::table('admin', function (Blueprint $table) {
                $table->rememberToken();
            });
        }

        if (Schema::hasTable('user') && ! Schema::hasColumn('user', 'remember_token')) {
            Schema::table('user', function (Blueprint $table) {
                $table->rememberToken();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('admin') && Schema::hasColumn('admin', 'remember_token')) {
            Schema::table('admin', function (Blueprint $table) {
                $table->dropColumn('remember_token');
            });
        }

        if (Schema::hasTable('user') && Schema::hasColumn('user', 'remember_token')) {
            Schema::table('user', function (Blueprint $table) {
                $table->dropColumn('remember_token');
            });
        }
    }
};
