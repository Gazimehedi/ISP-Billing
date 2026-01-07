<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Employee extends Model
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'role_id',
        'name',
        'mobile',
        'password',
        'father_name',
        'nid',
        'gender',
        'salary',
        'due',
        'salary_deduction',
        'salary_type',
        'division',
        'district',
        'thana',
        'address',
        'reference_name',
        'reference_address',
        'reference_mobile',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'status' => 'boolean',
        'salary' => 'decimal:2',
        'due' => 'decimal:2',
        'salary_deduction' => 'decimal:2',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
