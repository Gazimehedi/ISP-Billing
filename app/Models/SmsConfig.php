<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsConfig extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider',
        'api_key',
        'sender_id',
        'api_url',
        'api_type',
        'is_active'
    ];
}
