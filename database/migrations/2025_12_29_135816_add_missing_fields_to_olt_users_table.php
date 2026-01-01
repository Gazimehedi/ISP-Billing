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
            $table->string('vlan')->nullable()->after('signal');
            $table->string('distance')->nullable()->after('vlan');
            $table->string('temp')->nullable()->after('distance');
            $table->string('mode')->nullable()->after('temp'); // ONU Mode (Bridge/Route)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('olt_users', function (Blueprint $table) {
            $table->dropColumn(['vlan', 'distance', 'temp', 'mode']);
        });
    }
};
