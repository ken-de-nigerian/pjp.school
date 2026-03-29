<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Creates notifications table if it does not exist. Safe for production.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('notifications')) {
            return;
        }

        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->text('message')->nullable();
            $table->dateTime('date_added')->nullable();
            $table->index('date_added', 'notifications_date_added_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
