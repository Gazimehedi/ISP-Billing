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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained('roles')->onDelete('cascade');
            $table->string('name');
            $table->string('mobile')->unique();
            $table->string('password');
            $table->string('father_name')->nullable();
            $table->string('nid')->nullable();
            $table->string('gender')->default('Male'); // Male, Female, Other
            
            // Salary & Financials
            $table->decimal('salary', 10, 2)->default(0);
            $table->decimal('due', 10, 2)->default(0);
            $table->decimal('salary_deduction', 10, 2)->default(0);
            $table->string('salary_type')->default('Monthly');
            
            // Address & Location
            $table->string('division')->nullable();
            $table->string('district')->nullable();
            $table->string('thana')->nullable();
            $table->text('address')->nullable();
            
            // Reference Info
            $table->string('reference_name')->nullable();
            $table->string('reference_address')->nullable();
            $table->string('reference_mobile')->nullable();
            
            $table->boolean('status')->default(true); // Active/Inactive
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
