<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\Olt\Drivers\ZteDriver;
use App\Models\Olt;

class ZteDriverTest extends TestCase
{
    public function test_it_parses_zte_onu_list_correctly()
    {
        $olt = new Olt([
            'host' => '127.0.0.1',
            'port' => 23,
            'username' => 'admin',
            'password' => 'encrypted_admin'
        ]);
        
        $driver = new ZteDriver($olt);
        
        $mockOutput = "
gpon-onu_1/1/1:1    ZTEG12345678    online    working
gpon-onu_1/1/1:2    ZTEG87654321    offline   los
gpon-onu_1/1/2:1    ZTEGABCDEF12    online    working
        ";

        $onus = $driver->parseOnuList($mockOutput);

        $this->assertCount(3, $onus);
        $this->assertEquals('gpon-onu_1/1/1', $onus[0]['interface']);
        $this->assertEquals('1', $onus[0]['ont_id']);
        $this->assertEquals('ZTEG12345678', $onus[0]['serial_number']);
        $this->assertEquals('Online', $onus[0]['status']);

        $this->assertEquals('Offline', $onus[1]['status']);
        $this->assertEquals('gpon-onu_1/1/2', $onus[2]['interface']);
    }

    public function test_it_parses_zte_signals_correctly()
    {
        $olt = new Olt([
            'host' => '127.0.0.1',
            'port' => 23,
            'username' => 'admin',
            'password' => 'encrypted_admin'
        ]);

        $driver = new ZteDriver($olt);
        
        $mockOutput = "
gpon-onu_1/1/1:1    -19.50(dbm)
gpon-onu_1/1/1:2    -28.45(dbm)
gpon-onu_1/1/2:1    -17.20(dbm)
        ";

        $signals = $driver->parseSignals($mockOutput);

        $this->assertCount(3, $signals);
        $this->assertEquals('-19.50', $signals['gpon-onu_1/1/1:1']);
        $this->assertEquals('-28.45', $signals['gpon-onu_1/1/1:2']);
        $this->assertEquals('-17.20', $signals['gpon-onu_1/1/2:1']);
    }
}
