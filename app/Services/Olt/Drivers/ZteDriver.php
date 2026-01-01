<?php

namespace App\Services\Olt\Drivers;

use Exception;

class ZteDriver extends BaseDriver
{
    public function checkStatus()
    {
        return $this->execute('show version');
    }

    public function reboot($onuId = null)
    {
        if ($onuId) {
            // ZTE: gpon-onu-reset gpon-onu_1/2/1:1
            return $this->execute("gpon-onu-reset $onuId"); 
        } else {
            $this->execute('reboot');
            $this->execute('yes'); // Confirmation
            return true;
        }
    }

    public function syncUsers()
    {
        try {
            // 1. Get ONU state information
            $output = $this->execute('show gpon onu state');
            $onus = $this->parseOnuList($output);

            // 2. Get optical power information
            $signalOutput = $this->execute('show pon power attenuation');
            $signals = $this->parseSignals($signalOutput);

            // Merge signals into ONU array
            foreach ($onus as &$onu) {
                $key = "{$onu['interface']}:{$onu['ont_id']}";
                if (isset($signals[$key])) {
                    $onu['signal'] = $signals[$key];
                }
            }

            return $onus;

        } catch (Exception $e) {
            // Fallback to demo data if connection fails
            if (config('app.debug')) {
                return [
                    [
                        'name' => 'ZTE ONU Demo 1',
                        'serial_number' => 'ZTEG12345678',
                        'interface' => 'gpon-onu_1/1/1',
                        'ont_id' => '1',
                        'status' => 'Online',
                        'signal' => '-19.2',
                        'vlan' => '100',
                        'distance' => '520m',
                        'temp' => 'N/A',
                        'mode' => 'Bridge',
                    ],
                    [
                        'name' => 'ZTE ONU Demo 2',
                        'serial_number' => 'ZTEG87654321',
                        'interface' => 'gpon-onu_1/1/2',
                        'ont_id' => '2',
                        'status' => 'Offline',
                        'signal' => '-28.5',
                        'vlan' => '101',
                        'distance' => '1.1km',
                        'temp' => 'N/A',
                        'mode' => 'Route',
                    ]
                ];
            }
            throw $e;
        }
    }

    public function checkOnuInfo($onuId)
    {
        return $this->execute("show gpon onu detail-info $onuId");
    }

    /**
     * Parse ZTE ONU state output
     * Format expected:
     * gpon-onu_1/1/1:1    ZTEG12345678    online    working
     */
    public function parseOnuList($output)
    {
        $onus = [];
        // Pattern: gpon-onu_1/1/1:1    ZTEG12345678    online    working
        $pattern = '/(gpon-onu_\d+\/\d+\/\d+):(\d+)\s+([A-Z0-9]{12,16})\s+(\w+)\s+(\w+)/i';

        if (preg_match_all($pattern, $output, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $onus[] = [
                    'interface' => $match[1],
                    'ont_id' => $match[2],
                    'serial_number' => $match[3],
                    'name' => "ONU_{$match[2]}",
                    'status' => ucfirst($match[4]), // online/offline
                    'distance' => 'N/A', // ZTE requires separate command for distance
                    'mode' => 'Bridge',
                    'vlan' => 'N/A'
                ];
            }
        }

        return $onus;
    }

    /**
     * Parse ZTE optical power output
     * Format: gpon-onu_1/1/1:1    -19.50(dbm)
     */
    public function parseSignals($output)
    {
        $signals = [];
        $pattern = '/(gpon-onu_\d+\/\d+\/\d+):(\d+)\s+([-]?\d+\.\d+)/';

        if (preg_match_all($pattern, $output, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $signals["{$match[1]}:{$match[2]}"] = $match[3];
            }
        }
        return $signals;
    }

    public function parseOnuInfo($output)
    {
        return ['raw' => $output];
    }

    /**
     * Provision a new ONU on ZTE OLT
     */
    public function provisionOnu($serialNumber, $interface, $profile, $name)
    {
        // ZTE: onu {interface}:{id} type {type} sn {serial}
        $this->execute("configure terminal");
        $this->execute("pon-onu-mng {$interface}:1");
        $this->execute("type ZTE-F660");
        $this->execute("sn {$serialNumber}");
        $this->execute("exit");
        $this->execute("exit");
        
        return true;
    }

    /**
     * Deactivate ONU
     */
    public function deactivateOnu($interface, $serialNumber)
    {
        $this->execute("configure terminal");
        $this->execute("no pon-onu-mng {$interface}:1");
        $this->execute("exit");
        
        return true;
    }

    /**
     * Configure VLAN for ONU
     */
    public function configureVlan($interface, $vlanId)
    {
        $this->execute("configure terminal");
        $this->execute("pon-onu-mng {$interface}:1");
        $this->execute("vlan port eth_0/1 mode tag {$vlanId}");
        $this->execute("exit");
        $this->execute("exit");
        
        return true;
    }

    /**
     * Configure bandwidth profile
     */
    public function configureBandwidth($interface, $profile)
    {
        $this->execute("configure terminal");
        $this->execute("pon-onu-mng {$interface}:1");
        $this->execute("flow 1 priority 0 vid {$profile->vlan_id}");
        $this->execute("exit");
        $this->execute("exit");
        
        return true;
    }
}
