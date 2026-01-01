<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\Olt\Drivers\VsoleDriver;
use App\Models\Olt;

class VsoleDriverTest extends TestCase
{
    public function test_it_parses_vsole_onu_list_correctly()
    {
        $olt = new Olt([
            'host' => '127.0.0.1',
            'port' => 23,
            'username' => 'admin',
            'password' => 'encrypted_admin'
        ]);
        
        $driver = new VsoleDriver($olt);
        
        $mockOutput = "
epon0/1:1    BDCOM12345678    online    380
epon0/1:2    BDCOM87654321    offline   950
epon0/2:1    BDCOMABCDEF12    online    520
        ";

        $onus = $driver->parseOnuList($mockOutput);

        $this->assertCount(3, $onus);
        $this->assertEquals('epon0/1', $onus[0]['interface']);
        $this->assertEquals('1', $onus[0]['ont_id']);
        $this->assertEquals('BDCOM12345678', $onus[0]['serial_number']);
        $this->assertEquals('Online', $onus[0]['status']);
        $this->assertEquals('380m', $onus[0]['distance']);

        $this->assertEquals('Offline', $onus[1]['status']);
        $this->assertEquals('950m', $onus[1]['distance']);
    }

    public function test_it_parses_vsole_signals_correctly()
    {
        $olt = new Olt([
            'host' => '127.0.0.1',
            'port' => 23,
            'username' => 'admin',
            'password' => 'encrypted_admin'
        ]);

        $driver = new VsoleDriver($olt);
        
        $mockOutput = "
epon0/1:1    -18.50
epon0/1:2    -29.10
epon0/2:1    -17.80
        ";

        $signals = $driver->parseSignals($mockOutput);

        $this->assertCount(3, $signals);
        $this->assertEquals('-18.50', $signals['epon0/1:1']);
        $this->assertEquals('-29.10', $signals['epon0/1:2']);
        $this->assertEquals('-17.80', $signals['epon0/2:1']);
    }
}
