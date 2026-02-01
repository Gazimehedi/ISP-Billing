<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Billing extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'client_id',
        'month',
        'invoice_no',
        'amount',
        'discount',
        'paid',
        'due',
        'status',
        'payment_method',
        'transaction_id',
        'generated_date',
        'due_date',
        'paid_date',
        'notes'
    ];

    protected $casts = [
        'month' => 'date',
        'generated_date' => 'date',
        'due_date' => 'date',
        'paid_date' => 'date',
        'amount' => 'decimal:2',
        'discount' => 'decimal:2',
        'paid' => 'decimal:2',
        'due' => 'decimal:2',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
