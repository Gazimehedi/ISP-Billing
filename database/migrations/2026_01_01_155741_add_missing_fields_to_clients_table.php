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
            // Personal Information
            $table->string('occupation')->nullable()->after('mobile');
            $table->string('gender')->nullable()->after('occupation');
            $table->date('dob')->nullable()->after('gender');
            $table->string('father_name')->nullable()->after('dob');
            $table->string('mother_name')->nullable()->after('father_name');
            $table->string('res_form_no')->nullable()->after('nid');
            $table->string('profile_pic')->nullable();
            $table->string('nid_pic')->nullable();
            $table->string('res_form_pic')->nullable();

            // Contact Information
            $table->string('email')->nullable()->after('mobile');
            $table->string('phone')->nullable()->after('mobile');
            $table->string('facebook_url')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('twitter_url')->nullable();
            $table->string('district')->nullable();
            $table->string('upazila')->nullable();
            $table->text('permanent_address')->nullable();
            $table->boolean('is_same_address')->default(false);

            // Network & Product Information
            $table->string('server_id')->nullable(); // Select2
            $table->string('protocol_type')->nullable(); // e.g. Static/PPPoE
            $table->string('cable_meter')->nullable();
            $table->string('fiber_code')->nullable();
            $table->string('core_no')->nullable();
            $table->string('core_color')->nullable();
            $table->string('device_id')->nullable();
            $table->string('device_mac_serial')->nullable();
            $table->string('vendor_id')->nullable();
            $table->date('device_purchase_date')->nullable();

            // Service Information
            $table->date('joining_date')->nullable();
            $table->string('billing_start_month')->nullable(); // MM/YYYY
            $table->string('ref_by')->nullable();
            $table->string('affiliator_id')->nullable();
            $table->boolean('send_greeting_sms')->default(false);
            $table->json('connected_by')->nullable(); // Multi-select Employees
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn([
                'occupation', 'gender', 'dob', 'father_name', 'mother_name', 'res_form_no',
                'profile_pic', 'nid_pic', 'res_form_pic', 'email', 'phone', 
                'facebook_url', 'linkedin_url', 'twitter_url', 'district', 'upazila',
                'permanent_address', 'is_same_address', 'server_id', 'protocol_type',
                'cable_meter', 'fiber_code', 'core_no', 'core_color', 'device_id',
                'device_mac_serial', 'vendor_id', 'device_purchase_date',
                'joining_date', 'billing_start_month', 'ref_by', 'affiliator_id',
                'send_greeting_sms', 'connected_by'
            ]);
        });
    }
};
