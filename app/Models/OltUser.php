<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OltUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'olt_id',
        'service_profile_id',
        'name',
        'serial_number',
        'interface',
        'status',
        'signal',
        'vlan',
        'distance',
        'temp',
        'mode',
        'ip_address',
        'mac_address',
        'zone_id',
        'sub_zone_id'
    ];

    public function olt()
    {
        return $this->belongsTo(Olt::class);
    }

    /**
     * Get the service profile assigned to this ONU
     */
    public function serviceProfile()
    {
        return $this->belongsTo(ServiceProfile::class);
    }
}
