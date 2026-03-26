<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('entrance', function (Blueprint $table) {
            if (! Schema::hasColumn('entrance', 'applying_for')) {
                $table->string('applying_for', 50)->nullable()->after('candidates_current_class');
            }
            if (! Schema::hasColumn('entrance', 'fathers_phone')) {
                $table->string('fathers_phone', 30)->nullable()->after('fathers_address');
            }
            if (! Schema::hasColumn('entrance', 'mothers_phone')) {
                $table->string('mothers_phone', 30)->nullable()->after('mothers_address');
            }
            if (! Schema::hasColumn('entrance', 'guardians_phone')) {
                $table->string('guardians_phone', 30)->nullable()->after('guardians_address');
            }
        });
    }

    public function down(): void
    {
        Schema::table('entrance', function (Blueprint $table) {
            $table->dropColumn(['applying_for', 'fathers_phone', 'mothers_phone', 'guardians_phone']);
        });
    }
};
