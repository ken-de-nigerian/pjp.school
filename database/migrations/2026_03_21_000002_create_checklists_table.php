<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('checklists')) {
            return;
        }

        Schema::create('checklists', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('term', 20);
            $table->string('session', 20);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('position')->default(0);
            $table->timestamps();
            $table->index(
                ['term', 'session', 'is_active'],
                'checklists_term_session_active_index'
            );
            $table->index(
                ['term', 'session', 'position'],
                'checklists_term_session_position_index'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('checklists');
    }
};
