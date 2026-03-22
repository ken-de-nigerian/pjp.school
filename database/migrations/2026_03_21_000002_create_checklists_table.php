<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('checklists', function (Blueprint $table) {
            $table->id();
            $table->text('title');
            $table->string('term');
            $table->string('session');
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('position')->default(0);
            $table->timestamps();

            $table->index(['term', 'session', 'is_active']);
            $table->index(['term', 'session', 'position']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('checklists');
    }
};
