<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

/**
 * Legacy installs used table "user". The application expects "users" (Teacher model).
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('users') || ! Schema::hasTable('user')) {
            return;
        }

        Schema::rename('user', 'users');
    }

    public function down(): void
    {
        if (Schema::hasTable('user') || ! Schema::hasTable('users')) {
            return;
        }

        Schema::rename('users', 'user');
    }
};
