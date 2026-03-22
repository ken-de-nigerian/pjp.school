<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('positions')) {
            return;
        }

        if (Schema::hasColumn('positions', 'remark')) {
            return;
        }

        Schema::table('positions', function (Blueprint $table) {
            $table->text('remark')->nullable();
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('positions')) {
            return;
        }

        if (! Schema::hasColumn('positions', 'remark')) {
            return;
        }

        Schema::table('positions', function (Blueprint $table) {
            $table->dropColumn('remark');
        });
    }
};
