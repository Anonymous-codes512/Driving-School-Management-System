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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->enum('course_category', ['regular', 'custom']);
            $table->string('name')->nullable();
            $table->foreignId('car_model_id')->nullable()->constrained('car_models')->onDelete('cascade');
            $table->integer('duration_days')->nullable();
            $table->integer('duration_minutes')->nullable();
            $table->decimal('fees', 8, 2);
            $table->enum('course_type', ['male', 'female', 'both']);
            $table->decimal('discount', 5, 2)->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
