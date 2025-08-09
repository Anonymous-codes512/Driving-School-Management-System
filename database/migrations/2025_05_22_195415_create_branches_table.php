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
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address');
            $table->string('branch_phone_number')->nullable();
            $table->string('branch_email_address')->nullable();
            $table->string('website')->nullable();
            $table->string('opening_hours')->nullable();
            $table->string(column: 'closing_hours')->nullable();
            $table->string('slots_length')->nullable();
            $table->string('branch_code')->nullable();
            $table->string('location')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->foreignId('owner_id')->constrained('school_owners')->onDelete('cascade'); // owner relation
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade'); // owner relation
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};
