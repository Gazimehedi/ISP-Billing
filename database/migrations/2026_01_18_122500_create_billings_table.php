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
        Schema::create('billings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            
            $table->date('month')->comment('Billing month, e.g. 2026-01-01');
            $table->string('invoice_no')->unique()->nullable();
            
            $table->decimal('amount', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('paid', 10, 2)->default(0);
            $table->decimal('due', 10, 2)->default(0);
            
            $table->string('status')->default('unpaid'); // unpaid, paid, partial, overpaid
            $table->string('payment_method')->nullable();
            $table->string('transaction_id')->nullable();
            
            $table->date('generated_date')->nullable();
            $table->date('due_date')->nullable();
            $table->date('paid_date')->nullable();
            
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billings');
    }
};
