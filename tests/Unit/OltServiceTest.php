<?php

namespace Tests\Unit;

use App\Models\Olt;
use App\Services\Olt\OltService;
use App\Services\Olt\Drivers\HuaweiDriver;
use App\Services\Olt\Drivers\ZteDriver;
use Tests\TestCase;

class OltServiceTest extends TestCase
{
    public function test_it_returns_correct_driver_for_huawei()
    {
        $olt = new Olt();
        $olt->type = 'HUAWEI';
        $olt->host = '127.0.0.1';
        $olt->port = 22;
        $olt->username = 'admin';
        $olt->password = 'secret'; // Encrypted/Decrypted mock handled if needed, but here simple object

        $service = new OltService();
        try {
            $driver = $service->getDriver($olt);
            $this->assertInstanceOf(HuaweiDriver::class, $driver);
        } catch (\Exception $e) {
             // connection might fail in constructor if it tries to connect immediately, 
             // but our drivers wait for connect() method.
             // BaseDriver constructor just sets olt property.
             $this->fail($e->getMessage());
        }
    }

    public function test_it_returns_correct_driver_for_zte()
    {
        $olt = new Olt();
        $olt->type = 'ZTE C320';
        
        $service = new OltService();
        $driver = $service->getDriver($olt);
        
        $this->assertInstanceOf(ZteDriver::class, $driver);
    }
}
