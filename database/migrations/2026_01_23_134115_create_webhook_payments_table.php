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
        Schema::create('webhook_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->string('transaction_id')->unique();
            $table->decimal('amount', 10, 2);
            $table->string('payment_method')->default('bkash'); // bkash, nagad, rocket, etc.
            $table->string('payment_from')->nullable(); // Sender's phone/account
            $table->string('status')->default('pending'); // pending, completed, failed, refunded
            $table->json('gateway_response')->nullable(); // Full webhook response
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('webhook_payments');
    }
};
