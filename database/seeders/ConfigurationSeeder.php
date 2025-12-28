<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MikrotikRouter;
use App\Models\Zone;
use App\Models\SubZone;
use App\Models\Box;
use App\Models\ClientType;
use App\Models\ConnectionType;
use Illuminate\Support\Facades\Crypt;

class ConfigurationSeeder extends Seeder
{
    public function run()
    {
        // 1. Mikrotik Router
        MikrotikRouter::firstOrCreate(['name' => 'Main Gateway'], [
            'ip_address' => '192.168.88.1',
            'username' => 'admin',
            'password' => Crypt::encryptString('123456'),
            'port' => 8728,
            'sync_timer_unit' => 'minutes',
            'sync_timer_value' => 5,
        ]);

        // 2. Zones
        $dhaka = Zone::firstOrCreate(['name' => 'Dhaka'], ['description' => 'Capital City']);
        $ctg = Zone::firstOrCreate(['name' => 'Chittagong'], ['description' => 'Port City']);

        // 3. Sub Zones
        $uttara = SubZone::firstOrCreate(['name' => 'Uttara', 'zone_id' => $dhaka->id], ['description' => 'Sector 1-14']);
        $gulshan = SubZone::firstOrCreate(['name' => 'Gulshan', 'zone_id' => $dhaka->id], ['description' => 'Diplomatic Zone']);
        $halishahar = SubZone::firstOrCreate(['name' => 'Halishahar', 'zone_id' => $ctg->id], ['description' => 'Residential Area']);

        // 4. Boxes
        Box::firstOrCreate(['name' => 'Box-Uttara-01'], ['zone_id' => $dhaka->id, 'sub_zone_id' => $uttara->id, 'description' => 'Sector 4 Junction']);
        Box::firstOrCreate(['name' => 'Box-Uttara-02'], ['zone_id' => $dhaka->id, 'sub_zone_id' => $uttara->id, 'description' => 'Sector 7 Market']);
        Box::firstOrCreate(['name' => 'Box-Gulshan-01'], ['zone_id' => $dhaka->id, 'sub_zone_id' => $gulshan->id, 'description' => 'Circle 1']);
        Box::firstOrCreate(['name' => 'Box-Hali-01'], ['zone_id' => $ctg->id, 'sub_zone_id' => $halishahar->id, 'description' => 'Block B']);

        // 5. Client Types
        ClientType::firstOrCreate(['name' => 'Home User'], ['description' => 'Residential broadband']);
        ClientType::firstOrCreate(['name' => 'Corporate'], ['description' => 'Business dedicated line']);
        ClientType::firstOrCreate(['name' => 'SME'], ['description' => 'Small business shared']);

        // 6. Connection Types
        ConnectionType::firstOrCreate(['name' => 'PPPoE'], ['description' => 'Point-to-Point Protocol over Ethernet']);
        ConnectionType::firstOrCreate(['name' => 'Static IP'], ['description' => 'Dedicated IP assignment']);
    }
}
