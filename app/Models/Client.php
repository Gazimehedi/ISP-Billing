<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'package_id', 'zone_id', 'sub_zone_id', 'box_id', 'service_profile_id', 'employee_id',
        'username', 'password', 'package_alias', 'connection_type', 'remote_address', 'mac_address',
        'name', 'client_id_display', 'mobile', 'email', 'phone', 'nid', 'res_form_no',
        'flat', 'house_no', 'road_no', 'address', 'permanent_address', 'lat', 'long',
        'billing_type', 'payment_status', 'status', 'subscription_type',
        'monthly_fee', 'monthly_fee_due', 'connection_fee', 'balance',
        'expire_date', 'connection_date', 'billing_date', 'joining_date', 'dob',
        'occupation', 'gender', 'father_name', 'mother_name',
        'facebook_url', 'linkedin_url', 'twitter_url', 'district', 'upazila',
        'is_same_address', 'server_id', 'protocol_type', 'cable_meter', 'fiber_code',
        'core_no', 'core_color', 'device_id', 'device_mac_serial', 'vendor_id',
        'device_purchase_date', 'billing_start_month', 'ref_by', 'affiliator_id',
        'send_greeting_sms', 'connected_by', 'remarks', 'image',
        'profile_pic', 'nid_pic', 'res_form_pic', 'is_vip', 'is_auto_disable'
    ];

    protected $casts = [
        'expire_date' => 'date',
        'connection_date' => 'date',
        'billing_date' => 'date',
        'joining_date' => 'date',
        'dob' => 'date',
        'device_purchase_date' => 'date',
        'is_vip' => 'boolean',
        'is_auto_disable' => 'boolean',
        'send_greeting_sms' => 'boolean',
        'is_same_address' => 'boolean',
        'connected_by' => 'json',
        'monthly_fee' => 'decimal:2',
        'monthly_fee_due' => 'decimal:2',
        'balance' => 'decimal:2',
    ];

    // Relationships
    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    public function subZone()
    {
        return $this->belongsTo(SubZone::class);
    }

    public function box()
    {
        return $this->belongsTo(Box::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function serviceProfile()
    {
        return $this->belongsTo(ServiceProfile::class);
    }
    
    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function server()
    {
        return $this->belongsTo(MikrotikRouter::class, 'server_id');
    }
}
