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

        if (Schema::hasTable('users') && ! Schema::hasColumn('users', 'remember_token')) {
            Schema::table('users', function (Blueprint $table) {
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

        if (Schema::hasTable('users') && Schema::hasColumn('users', 'remember_token')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('remember_token');
            });
        }
    }
};
