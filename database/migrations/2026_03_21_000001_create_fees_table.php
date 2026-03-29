<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('fees')) {
            return;
        }

        Schema::create('fees', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('amount', 12);
            $table->string('category', 50);
            $table->string('term', 20);
            $table->string('session', 20);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index(['term', 'session', 'is_active'], 'fees_term_session_active_index');
            $table->index(['category', 'term', 'session'], 'fees_category_term_session_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fees');
    }
};
