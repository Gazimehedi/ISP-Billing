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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            
            // Foreign Keys
            $table->foreignId('package_id')->nullable()->constrained('packages');
            $table->foreignId('zone_id')->nullable()->constrained('zones');
            $table->foreignId('sub_zone_id')->nullable()->constrained('sub_zones');
            $table->foreignId('box_id')->nullable()->constrained('boxes');
            $table->foreignId('service_profile_id')->nullable()->constrained('service_profiles'); // For OLT integration
            $table->foreignId('employee_id')->nullable()->constrained('users'); // Assigned support staff
            
            // Connection Info
            $table->string('username')->unique(); // PPPoE Username
            $table->string('password'); // PPPoE Password
            $table->string('package_alias')->nullable();
            $table->string('connection_type')->default('pppoe'); // pppoe, static
            $table->ipAddress('remote_address')->nullable(); // IP Address
            $table->macAddress('mac_address')->nullable();
            
            // Personal Info
            $table->string('name'); // Client Name
            $table->string('client_id_display')->unique()->nullable(); // Manual ID e.g., CL-1001
            $table->string('mobile');
            $table->string('nid')->nullable();
            $table->string('flat')->nullable();
            $table->string('house_no')->nullable();
            $table->string('road_no')->nullable();
            $table->text('address')->nullable();
            $table->decimal('lat', 10, 8)->nullable();
            $table->decimal('long', 11, 8)->nullable();
            
            // Billing Info
            $table->string('billing_type')->default('monthly'); // monthly, one_time
            $table->string('payment_status')->default('unpaid'); // paid, unpaid
            $table->string('status')->default('active'); // active, inactive, expired, suspended
            $table->string('subscription_type')->default('prepaid'); // prepaid, postpaid
            
            $table->decimal('monthly_fee', 10, 2)->default(0);
            $table->decimal('monthly_fee_due', 10, 2)->default(0);
            $table->decimal('connection_fee', 10, 2)->default(0);
            $table->decimal('balance', 10, 2)->default(0);
            
            $table->date('expire_date')->nullable();
            $table->date('connection_date')->nullable();
            $table->date('billing_date')->nullable();
            
            // Settings & Extras
            $table->string('image')->nullable();
            $table->boolean('is_vip')->default(false);
            $table->boolean('is_auto_disable')->default(true);
            $table->text('remarks')->nullable();
            
            // References
            $table->string('ref_name')->nullable();
            $table->string('ref_mobile')->nullable();
            $table->string('caller_id')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
