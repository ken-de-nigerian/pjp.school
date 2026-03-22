<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fees', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('amount', 12, 2);
            $table->string('category');
            $table->string('term');
            $table->string('session');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['term', 'session', 'is_active']);
            $table->index(['category', 'term', 'session']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fees');
    }
};
