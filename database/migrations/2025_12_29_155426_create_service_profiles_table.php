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
        Schema::create('service_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('description')->nullable();
            
            // Bandwidth settings (in Mbps)
            $table->integer('bandwidth_up')->default(100);
            $table->integer('bandwidth_down')->default(100);
            
            // VLAN configuration
            $table->enum('vlan_mode', ['transparent', 'tag', 'translation'])->default('transparent');
            $table->integer('vlan_id')->nullable();
            $table->integer('cvlan_id')->nullable(); // Customer VLAN for translation mode
            
            // QoS settings
            $table->integer('priority')->default(0); // 0-7
            
            // Status
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_profiles');
    }
};
