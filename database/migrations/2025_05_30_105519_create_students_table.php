<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('father_or_husband_name')->nullable();
            $table->string('cnic')->unique()->nullable();
            $table->string('address');
            $table->string('status');
            $table->string('phone');
            $table->date('admission_date');
            $table->date('course_end_date');
            $table->string('course_duration');
            $table->string('optional_phone')->nullable();
            $table->string('email')->nullable();
            $table->string('coupon_code')->nullable();
            $table->string('pickup_sector')->nullable();
            $table->text('timing_preference')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            $table->foreignId('instructor_id')->nullable()->constrained('instructors')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
