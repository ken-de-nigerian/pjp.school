<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;

/**
 * Intentionally empty: schema is managed outside Laravel migrations (legacy DB).
 * Laravel default jobs / job_batches / failed_jobs tables are not created here.
 */
return new class extends Migration
{
    public function up(): void {}

    public function down(): void {}
};
