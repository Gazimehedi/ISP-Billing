<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'client_id',
        'collected_by',
        'amount',
        'discount',
        'transaction_id',
        'method',
        'status',
        'note',
        'date'
    ];

    protected $casts = [
        'date' => 'datetime',
        'amount' => 'decimal:2',
        'discount' => 'decimal:2',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function collector()
    {
        return $this->belongsTo(User::class, 'collected_by');
    }
}
