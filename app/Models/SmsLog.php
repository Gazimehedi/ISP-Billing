<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'content',
        'provider',
        'message_type',
        'type',
        'sms_count',
        'status',
        'response_msg'
    ];
}
