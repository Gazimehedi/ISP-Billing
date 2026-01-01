<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'bandwidth_up',
        'bandwidth_down',
        'vlan_mode',
        'vlan_id',
        'cvlan_id',
        'priority',
        'is_active'
    ];

    protected $casts = [
        'bandwidth_up' => 'integer',
        'bandwidth_down' => 'integer',
        'vlan_id' => 'integer',
        'cvlan_id' => 'integer',
        'priority' => 'integer',
        'is_active' => 'boolean'
    ];

    /**
     * Get ONUs using this profile
     */
    public function oltUsers()
    {
        return $this->hasMany(OltUser::class, 'service_profile_id');
    }
}
