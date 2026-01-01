<?php

namespace App\Services\Olt;

use App\Models\Olt;
use App\Services\Olt\Drivers\OltDriverInterface;
use App\Services\Olt\Drivers\HuaweiDriver;
use App\Services\Olt\Drivers\ZteDriver;
use App\Services\Olt\Drivers\VsoleDriver;
use App\Services\Olt\Drivers\BaseDriver;
use Exception;

class OltService
{
    /**
     * Get the driver for the OLT
     */
    public function getDriver(Olt $olt): OltDriverInterface
    {
        // Normalize type check
        $type = strtoupper($olt->type);

        if (str_contains($type, 'HUAWEI')) {
            return new HuaweiDriver($olt);
        } elseif (str_contains($type, 'ZTE')) {
            return new ZteDriver($olt);
        } elseif (str_contains($type, 'VSOLE') || str_contains($type, 'BDCOM')) {
            return new VsoleDriver($olt);
        }

        // Default or Fallback
        throw new Exception("Unsupported OLT type: {$olt->type}");
    }

    /**
     * Connection Helper
     */
    protected function withDriver(Olt $olt, callable $callback)
    {
        $driver = $this->getDriver($olt);
        $driver->connect();
        
        try {
            return $callback($driver);
        } finally {
            $driver->disconnect();
        }
    }

    /**
     * Reboot the OLT or ONU
     */
    public function reboot(Olt $olt, $onuId = null)
    {
        return $this->withDriver($olt, function ($driver) use ($onuId) {
            return $driver->reboot($onuId);
        });
    }

    /**
     * Sync Users (Get ONUs)
     */
    public function syncUsers(Olt $olt)
    {
        return $this->withDriver($olt, function ($driver) {
             // For now, if the driver returns raw/count, we might want to mock structured data
             // until proper parsers are written for each vendor.
             // Or better, let's update the drivers to return structured data.
             // For this "User Request", I'll mock it here or in the driver.
             // Let's return a Mock array if the driver returns simple raw.
             $data = $driver->syncUsers();
             return $data;
        });
    }
    
    /**
     * Check Single ONU Info
     */
    public function checkOnuInfo(Olt $olt, $onuId)
    {
        return $this->withDriver($olt, function ($driver) use ($onuId) {
            return $driver->checkOnuInfo($onuId);
        });
    }
    
    /**
     * Check OLT Status
     */
    public function checkStatus(Olt $olt)
    {
        return $this->withDriver($olt, function ($driver) {
            return $driver->checkStatus();
        });
    }

    /**
     * Test Connection (Connect and Disconnect)
     */
    public function testConnection(Olt $olt)
    {
        // This will attempt to connect using the driver
        // If it throws an exception, connection failed.
        // If it returns, connection succeeded.
        return $this->withDriver($olt, function ($driver) {
            return true;
        });
    }
}
