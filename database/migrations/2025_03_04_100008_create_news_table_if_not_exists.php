<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Creates news table if it does not exist. Safe for production.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('news')) {
            return;
        }

        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->uuid('newsid')->unique();
            $table->string('title')->nullable();
            $table->text('content')->nullable();
            $table->string('image')->nullable();
            $table->dateTime('date_added')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
