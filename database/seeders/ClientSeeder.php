<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;
use App\Models\Zone;
use App\Models\SubZone;
use App\Models\Package;
use App\Models\ServiceProfile;
use App\Models\MikrotikRouter;
use App\Models\Box;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        // Ensure we have some base data
        $zones = Zone::all();
        $packages = Package::all();
        if ($packages->isEmpty()) {
            throw new \Exception('No packages found. Please run PackageSeeder first.');
        }
        
        $profiles = ServiceProfile::all();
        if ($profiles->isEmpty()) {
            $profiles = collect([
                ServiceProfile::create(['name' => '10M Basic', 'bandwidth_up' => 10, 'bandwidth_down' => 10]),
                ServiceProfile::create(['name' => '20M Speed', 'bandwidth_up' => 20, 'bandwidth_down' => 20]),
            ]);
        }

        $server = MikrotikRouter::first();
        if (!$server) {
            $server = MikrotikRouter::create([
                'name' => 'Primary Router',
                'ip_address' => '10.0.0.1',
                'username' => 'admin',
                'password' => encrypt('password'),
            ]);
        }

        $box = Box::first();

        // Create 20 dummy clients
        for ($i = 0; $i < 20; $i++) {
            $zone = $zones->random();
            $subZone = SubZone::where('zone_id', $zone->id)->first();
            $package = $packages->random();
            $profile = $profiles->random();

            Client::create([
                'name' => $faker->name,
                'username' => $faker->unique()->userName,
                'password' => '123456', // Simple password for testing
                'mobile' => $faker->phoneNumber,
                'email' => $faker->unique()->safeEmail,
                'address' => $faker->address,
                'zone_id' => $zone->id,
                'sub_zone_id' => $subZone ? $subZone->id : null,
                'package_id' => $package->id,
                'service_profile_id' => $profile->id,
                'server_id' => $server->id,
                'box_id' => $box ? $box->id : null,
                'connection_type' => 'pppoe',
                'protocol_type' => 'IPv4',
                'monthly_fee' => $package->price,
                'monthly_fee_due' => rand(0, 1) ? 0 : $package->price,
                'payment_status' => rand(0, 1) ? 'paid' : 'unpaid',
                'status' => 'active',
                'joining_date' => $faker->dateTimeBetween('-1 year', 'now'),
                'billing_start_month' => date('Y-m'),
                'expire_date' => date('Y-m-') . str_pad(rand(1, 28), 2, '0', STR_PAD_LEFT),
                'device_mac_serial' => $i < 3 ? ['48575443ABC12345', '48575443DEF67890', '48575443GHI11223'][$i] : strtoupper($faker->bothify('ONU####????####')),
                'is_vip' => $faker->boolean(10),
            ]);
        }
    }
}
