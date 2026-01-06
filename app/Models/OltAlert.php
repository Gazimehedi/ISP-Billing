<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OltAlert extends Model
{
    use HasFactory;

    protected $fillable = [
        'olt_user_id',
        'type',
        'severity',
        'message',
        'is_resolved',
        'resolved_at'
    ];

    protected $casts = [
        'is_resolved' => 'boolean',
        'resolved_at' => 'datetime'
    ];

    /**
     * Get the OLT user associated with this alert
     */
    public function oltUser()
    {
        return $this->belongsTo(OltUser::class);
    }
}
