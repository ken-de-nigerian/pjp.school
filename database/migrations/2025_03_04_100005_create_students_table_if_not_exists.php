<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Creates students table if it does not exist. Safe for production.
 * Minimal columns for test suite; production may have more.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('students')) {
            return;
        }

        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('reg_number')->nullable();
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('othername')->nullable();
            $table->string('dob')->nullable();
            $table->string('gender')->nullable();
            $table->string('class')->nullable();
            $table->string('class_arm')->nullable();
            $table->string('subjects')->nullable();
            $table->unsignedTinyInteger('status')->default(0);
            $table->unsignedTinyInteger('fee_status')->nullable();
            $table->string('house')->nullable();
            $table->string('category')->nullable();
            $table->string('imagelocation')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('lga')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->string('nationality')->nullable();
            $table->text('address')->nullable();
            $table->string('father_name')->nullable();
            $table->string('father_occupation')->nullable();
            $table->string('father_phone')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('mother_occupation')->nullable();
            $table->string('mother_phone')->nullable();
            $table->string('sponsor_name')->nullable();
            $table->string('sponsor_occupation')->nullable();
            $table->string('sponsor_phone')->nullable();
            $table->text('sponsor_address')->nullable();
            $table->string('relationship')->nullable();
            $table->dateTime('time_of_reg')->nullable();
            $table->dateTime('left_school_date')->nullable();
            $table->dateTime('graduation_date')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
