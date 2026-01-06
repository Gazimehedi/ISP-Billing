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
        Schema::create('olts', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('type'); // OLT Vendor Name (e.g., VSOLE_EPON)
            $table->integer('pon_ports')->default(8);
            $table->string('username')->nullable();
            $table->text('password')->nullable(); // Encrypted
            $table->string('host');
            $table->integer('port')->default(161);
            $table->boolean('status')->default(true);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('olts');
    }
};
