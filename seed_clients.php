<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Client;
use App\Models\Zone;
use App\Models\Package;
use App\Models\User;

echo "Starting Manual Seed...\n";

try {
    $zone = Zone::firstOrCreate(['name' => 'Test Zone'], ['description' => 'Test Zone']);
    $package = Package::firstOrCreate(['name' => '20Mbps'], ['price' => 1000]);
    $user = User::first(); 

    if (!$user) {
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'mobile' => '01000000000'
        ]);
    }

    $clients = [
        [
            'name' => 'John Doe',
            'username' => 'johndoe',
            'client_id_display' => 'C-1001',
            'mobile' => '01711111111',
            'address' => 'House 10, Road 5, Dhaka',
            'monthly_fee' => 1000,
            'monthly_fee_due' => 1500, 
            'status' => 'active',
            'email' => 'johndoe@example.com',
            'zone_id' => $zone->id,
            'package_id' => $package->id,
            'billing_type' => 'postpaid',
            'payment_status' => 'unpaid',
            'connection_date' => now()->subMonths(2),
            'expire_date' => now()->addDays(10),
            'password' => bcrypt('123456')
        ],
        [
            'name' => 'Jane Smith',
            'username' => 'janesmith',
            'client_id_display' => 'C-1002',
            'mobile' => '01822222222',
            'address' => 'Flat 4B, Gulshan, Dhaka',
            'monthly_fee' => 2000,
            'monthly_fee_due' => 4000, 
            'status' => 'active',
            'email' => 'jane@example.com',
            'zone_id' => $zone->id,
            'package_id' => $package->id,
            'billing_type' => 'postpaid',
            'payment_status' => 'unpaid',
            'connection_date' => now()->subMonths(2),
            'expire_date' => now()->addDays(10),
            'password' => bcrypt('123456')
        ]
    ];

    foreach ($clients as $data) {
        Client::updateOrCreate(
            ['username' => $data['username']],
            $data
        );
        echo "Seeded: " . $data['name'] . "\n";
    }

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
