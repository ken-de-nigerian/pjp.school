<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;

/**
 * Intentionally empty: schema is managed outside Laravel migrations (legacy DB).
 * Laravel default users / password_reset_tokens / sessions tables are not created here.
 */
return new class extends Migration
{
    public function up(): void {}

    public function down(): void {}
};
