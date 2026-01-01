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
        Schema::create('olt_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('olt_id')->constrained('olts')->onDelete('cascade');
            $table->string('name')->nullable(); // ONU Name
            $table->string('serial_number')->nullable(); // E.g., ZTEG12345678 or MAC
            $table->string('interface')->nullable(); // E.g., gpon-onu_1/2/1:1
            $table->string('status')->default('Offline'); // Online, Offline, LOS
            $table->string('signal')->nullable(); // Received Optical Power
            $table->string('ip_address')->nullable();
            $table->string('mac_address')->nullable();
            $table->string('zone_id')->nullable(); // For mapping
            $table->string('sub_zone_id')->nullable();
            $table->timestamps();
            
            // Indexes for faster lookup
            $table->index(['olt_id', 'serial_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('olt_users');
    }
};
