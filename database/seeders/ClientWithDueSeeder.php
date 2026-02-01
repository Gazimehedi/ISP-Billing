<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;
use App\Models\Zone;
use App\Models\Package;
use App\Models\User;

class ClientWithDueSeeder extends Seeder
{
    public function run()
    {
        // Ensure we have at least one Zone, Package, and User
        $zone = Zone::firstOrCreate(['name' => 'Test Zone']);
        $package = Package::firstOrCreate(['name' => '20Mbps', 'price' => 1000]);
        $user = User::first() ?? User::factory()->create();

        $clients = [
            [
                'name' => 'John Doe',
                'username' => 'johndoe',
                'client_id_display' => 'C-1001',
                'mobile' => '01711111111',
                'address' => 'House 10, Road 5, Dhaka',
                'monthly_fee' => 1000,
                'monthly_fee_due' => 1500, // Has Due
                'status' => 'active',
                'email' => 'johndoe@example.com'
            ],
            [
                'name' => 'Jane Smith',
                'username' => 'janesmith',
                'client_id_display' => 'C-1002',
                'mobile' => '01822222222',
                'address' => 'Flat 4B, Gulshan, Dhaka',
                'monthly_fee' => 2000,
                'monthly_fee_due' => 4000, // High Due
                'status' => 'active',
                'email' => 'jane@example.com'
            ],
            [
                'name' => 'Robert Brown',
                'username' => 'robertb',
                'client_id_display' => 'C-1003',
                'mobile' => '01933333333',
                'address' => 'Banani, Dhaka',
                'monthly_fee' => 1200,
                'monthly_fee_due' => 1200, // 1 Month Due
                'status' => 'active',
                'email' => 'bob@example.com'
            ],
            [
                'name' => 'Alice White',
                'username' => 'alicew',
                'client_id_display' => 'C-1004',
                'mobile' => '01644444444',
                'address' => 'Mirpur 10, Dhaka',
                'monthly_fee' => 800,
                'monthly_fee_due' => 0, // No Due
                'status' => 'active',
                'email' => 'alice@example.com'
            ]
        ];

        foreach ($clients as $data) {
            Client::updateOrCreate(
                ['username' => $data['username']],
                array_merge($data, [
                    'zone_id' => $zone->id,
                    'package_id' => $package->id,
                    'billing_type' => 'postpaid',
                    'payment_status' => $data['monthly_fee_due'] > 0 ? 'unpaid' : 'paid',
                    'connection_date' => now()->subMonths(2),
                    'expire_date' => now()->addDays(10),
                ])
            );
        }
    }
}
