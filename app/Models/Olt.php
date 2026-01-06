<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Olt extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'pon_ports',
        'username',
        'password',
        'host',
        'port',
        'status',
        'is_active',
    ];

    protected $hidden = [
        'password',
    ];

    /**
     * Set the password (encrypted).
     */
    public function setPasswordAttribute($value)
    {
        if ($value) {
            $this->attributes['password'] = Crypt::encryptString($value);
        }
    }

    /**
     * Get the password (decrypted).
     */
    public function getPasswordAttribute($value)
    {
        if ($value) {
            try {
                return Crypt::decryptString($value);
            } catch (\Exception $e) {
                return null;
            }
        }
        return null;
    }
}
