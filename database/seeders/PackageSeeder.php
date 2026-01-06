<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Package;

class PackageSeeder extends Seeder
{
    public function run(): void
    {
        $packages = [
            [
                'name' => '5 Mbps Home Starter',
                'bandwidth_allocation' => '5 Mbps',
                'price' => 500.00,
                'type' => 'monthly',
                'is_active' => true,
            ],
            [
                'name' => '10 Mbps Standard',
                'bandwidth_allocation' => '10 Mbps',
                'price' => 800.00,
                'type' => 'monthly',
                'is_active' => true,
            ],
            [
                'name' => '20 Mbps Ultra Speed',
                'bandwidth_allocation' => '20 Mbps',
                'price' => 1200.00,
                'type' => 'monthly',
                'is_active' => true,
            ],
            [
                'name' => '30 Mbps Premium Plus',
                'bandwidth_allocation' => '30 Mbps',
                'price' => 1500.00,
                'type' => 'monthly',
                'is_active' => true,
            ],
            [
                'name' => '50 Mbps Corporate Gold',
                'bandwidth_allocation' => '50 Mbps',
                'price' => 3500.00,
                'type' => 'corporate',
                'is_active' => true,
            ],
            [
                'name' => '100 Mbps Dedicated Fiber',
                'bandwidth_allocation' => '100 Mbps',
                'price' => 7000.00,
                'type' => 'corporate',
                'is_active' => true,
            ],
            [
                'name' => 'Real IP Add-on',
                'bandwidth_allocation' => 'N/A',
                'price' => 200.00,
                'type' => 'monthly',
                'is_active' => true,
            ],
        ];

        foreach ($packages as $pkg) {
            Package::updateOrCreate(
                ['name' => $pkg['name']],
                $pkg
            );
        }
    }
}
