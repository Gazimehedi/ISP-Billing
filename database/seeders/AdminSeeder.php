<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::create([
            'name' => 'Super Admin',
            'mobile' => '01730891770',
            'password' => \Illuminate\Support\Facades\Hash::make('123456'),
            'role' => 'admin',
        ]);
    }
}
