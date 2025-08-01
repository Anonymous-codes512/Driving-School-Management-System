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
        Schema::create('other_expenses', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->enum('expense_type', ['Salary', 'Utilities', 'Other']);
            $table->decimal('amount', 8, 2);
            $table->date('expense_date');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('other_expenses');
    }
};
