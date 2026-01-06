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
        Schema::create('olt_signal_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('olt_user_id')->constrained('olt_users')->onDelete('cascade');
            $table->decimal('rx_power', 8, 2)->nullable();
            $table->decimal('tx_power', 8, 2)->nullable();
            $table->decimal('onu_temp', 8, 2)->nullable();
            $table->decimal('onu_voltage', 8, 2)->nullable();
            $table->timestamp('captured_at')->useCurrent();
            $table->timestamps();
            
            $table->index(['olt_user_id', 'captured_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('olt_signal_histories');
    }
};
