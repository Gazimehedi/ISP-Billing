<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Olt;
use App\Models\OltUser;
use App\Models\OltSignalHistory;
use App\Models\OltAlert;
use App\Models\Zone;
use App\Models\SubZone;
use App\Models\Package;
use App\Models\ServiceProfile;
use Carbon\Carbon;

class MonitoringSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Zone & SubZone
        $zone = Zone::firstOrCreate(['name' => 'Main Zone']);
        $subZone = SubZone::firstOrCreate(['name' => 'Block A', 'zone_id' => $zone->id]);

        // 2. Create Service Profile
        $profile = ServiceProfile::firstOrCreate(['name' => '10M Basic']);

        // 3. Create OLT
        $olt = Olt::create([
            'name' => 'Core OLT 01',
            'type' => 'HUAWEI_GPON',
            'host' => '192.168.1.100',
            'port' => 161,
            'username' => 'admin',
            'password' => 'admin123',
            'status' => true,
            'is_active' => true,
        ]);

        // 4. Create ONU Users
        $onus = [
            ['name' => 'Mehedi Hasan', 'serial_number' => '48575443ABC12345', 'interface' => 'gpon-olt_1/1/1:1'],
            ['name' => 'John Doe', 'serial_number' => '48575443DEF67890', 'interface' => 'gpon-olt_1/1/1:2'],
            ['name' => 'Jane Smith', 'serial_number' => '48575443GHI11223', 'interface' => 'gpon-olt_1/1/1:3'],
        ];

        foreach ($onus as $index => $onuData) {
            $user = OltUser::create([
                'olt_id' => $olt->id,
                'service_profile_id' => $profile->id,
                'name' => $onuData['name'],
                'serial_number' => $onuData['serial_number'],
                'interface' => $onuData['interface'],
                'status' => $index === 2 ? 'offline' : 'online',
                'signal' => $index === 0 ? -19.50 : ($index === 1 ? -26.80 : null),
                'temp' => 45.5,
                'zone_id' => $zone->id,
                'sub_zone_id' => $subZone->id,
            ]);

            // 5. Create Signal History for the last 24 hours (one point every 4 hours)
            for ($i = 24; $i >= 0; $i -= 4) {
                $baseSignal = $index === 1 ? -26.00 : -19.00;
                $variation = rand(-100, 100) / 100; // Small variation
                
                OltSignalHistory::create([
                    'olt_user_id' => $user->id,
                    'rx_power' => $baseSignal + $variation,
                    'tx_power' => 2.5 + ($variation / 2),
                    'onu_temp' => 42 + rand(0, 5),
                    'onu_voltage' => 3.3,
                    'captured_at' => Carbon::now()->subHours($i),
                ]);
            }

            // 6. Create an Alert for the low signal user
            if ($index === 1) {
                OltAlert::create([
                    'olt_user_id' => $user->id,
                    'type' => 'signal_low',
                    'severity' => 'warning',
                    'message' => 'Warning: RX Power is -26.80 dBm (below -25 dBm)',
                    'is_resolved' => false,
                ]);
            }
            
            // 7. Create an Alert for the offline user
            if ($index === 2) {
                 OltAlert::create([
                    'olt_user_id' => $user->id,
                    'type' => 'offline',
                    'severity' => 'critical',
                    'message' => 'Critical: ONU is offline',
                    'is_resolved' => false,
                ]);
            }
        }
    }
}
