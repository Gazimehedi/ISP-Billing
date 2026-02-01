<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WebhookPayment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'client_id',
        'transaction_id',
        'amount',
        'payment_method',
        'payment_from',
        'status',
        'gateway_response',
        'processed_at'
    ];

    protected $casts = [
        'gateway_response' => 'array',
        'processed_at' => 'datetime',
        'amount' => 'decimal:2'
    ];

    /**
     * Get the client that owns the webhook payment.
     */
    public function client()
    {
        return $this->belongsTo(\App\Models\Client::class);
    }
}
