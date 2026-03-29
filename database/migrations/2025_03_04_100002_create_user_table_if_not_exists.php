<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('users')) {
            return;
        }

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email')->nullable();
            $table->index('email', 'users_email_index');
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('othername')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('country')->nullable();
            $table->string('gender')->nullable();
            $table->string('lga')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->date('employment_date')->nullable();
            $table->string('assigned_class')->nullable();
            $table->string('subject_to_teach')->nullable();
            $table->string('imagelocation')->nullable();
            $table->string('password');
            $table->dateTime('registration_date')->nullable();
            $table->dateTime('password_change_date')->nullable();
            $table->unsignedTinyInteger('form-teacher')->nullable();
            $table->unsignedTinyInteger('modify_results')->nullable();
            $table->rememberToken();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
