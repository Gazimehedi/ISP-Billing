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
        Schema::table('olt_users', function (Blueprint $table) {
            $table->foreignId('service_profile_id')->nullable()->after('olt_id')->constrained()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('olt_users', function (Blueprint $table) {
            $table->dropForeign(['service_profile_id']);
            $table->dropColumn('service_profile_id');
        });
    }
};
