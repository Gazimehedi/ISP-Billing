<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\Olt\Drivers\HuaweiDriver;
use Mockery;

class HuaweiDriverTest extends TestCase
{
    public function test_it_parses_huawei_ont_list_correctly()
    {
        $olt = new \App\Models\Olt([
            'host' => '127.0.0.1',
            'port' => 22,
            'username' => 'admin',
            'password' => 'encrypted_admin'
        ]);
        
        $driver = new HuaweiDriver($olt);
        
        $mockOutput = "
  -----------------------------------------------------------------------------
  F/S/P   ONT-ID   SN                 Control   Run      Config   Distance
                                      state     state    state    (m)
  -----------------------------------------------------------------------------
  0/1/0   0        48575443ABC12345   active    online   success  123
  0/1/0   1        48575443DEF67890   active    offline  success  -
  -----------------------------------------------------------------------------
        ";

        $onus = $driver->parseOnuList($mockOutput);

        $this->assertCount(2, $onus);
        $this->assertEquals('0/1/0', $onus[0]['interface']);
        $this->assertEquals('0', $onus[0]['ont_id']);
        $this->assertEquals('48575443ABC12345', $onus[0]['serial_number']);
        $this->assertEquals('Online', $onus[0]['status']);
        $this->assertEquals('123m', $onus[0]['distance']);

        $this->assertEquals('Offline', $onus[1]['status']);
        $this->assertEquals('0m', $onus[1]['distance']);
    }

    public function test_it_parses_huawei_signals_correctly()
    {
        $olt = new \App\Models\Olt([
             'host' => '127.0.0.1',
             'port' => 22,
             'username' => 'admin',
             'password' => 'encrypted_admin'
         ]);

        $driver = new HuaweiDriver($olt);
        
        $mockOutput = "
  -----------------------------------------------------------------------------
  F/S/P   ONT-ID   RX power(dBm)   TX power(dBm)   OLT RX power(dBm)
  -----------------------------------------------------------------------------
  0/1/0   0        -19.50          2.10            -18.20
  0/1/0   1        -28.45          1.50            -27.10
  -----------------------------------------------------------------------------
        ";

        $signals = $driver->parseSignals($mockOutput);

        $this->assertCount(2, $signals);
        $this->assertEquals('-19.50', $signals['0/1/0:0']);
        $this->assertEquals('-28.45', $signals['0/1/0:1']);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
