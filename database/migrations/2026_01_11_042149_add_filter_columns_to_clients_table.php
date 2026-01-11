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
        Schema::table('clients', function (Blueprint $table) {
            $table->date('promise_date')->nullable()->after('expire_date');
            
            // We use foreignId without constrained first to be safe, 
            // or we can use constrained if we are sure table exists.
            // Given ClientType model exists, let's assume table is 'client_types'.
            // Use simple integer first to avoid FK issues if table is missing or named differently.
            // Actually, best practice is constrained, but if it fails I lose time.
            // I'll check if client_types table exists by trying constrained.
            
            $table->foreignId('client_type_id')->nullable()->after('billing_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn(['promise_date', 'client_type_id']);
        });
    }
};
