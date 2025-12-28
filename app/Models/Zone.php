<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function subZones()
    {
        return $this->hasMany(SubZone::class);
    }

    public function boxes()
    {
        return $this->hasMany(Box::class);
    }
}
