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
        Schema::table('users', function (Blueprint $table) {
            $table->string('nid_number')->nullable()->after('mobile');
            $table->foreignId('division_id')->nullable()->constrained('divisions')->nullOnDelete()->after('nid_number');
            $table->foreignId('district_id')->nullable()->constrained('districts')->nullOnDelete()->after('division_id');
            $table->foreignId('thana_id')->nullable()->constrained('thanas')->nullOnDelete()->after('district_id');
            $table->string('address')->nullable()->after('thana_id');
            $table->string('company_name')->nullable()->after('address');
            $table->string('profile_pic')->nullable()->after('company_name');
            $table->string('nid_front')->nullable()->after('profile_pic');
            $table->string('nid_back')->nullable()->after('nid_front');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['division_id']);
            $table->dropForeign(['district_id']);
            $table->dropForeign(['thana_id']);
            $table->dropColumn(['nid_number', 'division_id', 'district_id', 'thana_id', 'address', 'company_name', 'profile_pic', 'nid_front', 'nid_back']);
        });
    }
};
