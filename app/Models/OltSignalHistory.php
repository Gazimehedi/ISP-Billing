<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OltSignalHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'olt_user_id',
        'rx_power',
        'tx_power',
        'onu_temp',
        'onu_voltage',
        'captured_at'
    ];

    protected $casts = [
        'rx_power' => 'decimal:2',
        'tx_power' => 'decimal:2',
        'onu_temp' => 'decimal:2',
        'onu_voltage' => 'decimal:2',
        'captured_at' => 'datetime'
    ];

    /**
     * Get the OLT user that owns this signal history record.
     */
    public function oltUser()
    {
        return $this->belongsTo(OltUser::class);
    }
}
