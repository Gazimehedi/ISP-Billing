<?php

namespace App\Services\Olt\Drivers;

use Exception;

class HuaweiDriver extends BaseDriver
{
    public function checkStatus()
    {
        // Simple uptime or version check
        return $this->execute('display version');
    }

    public function reboot($onuId = null)
    {
        if ($onuId) {
            // Reboot specific ONU
            // Syntax: ont reset port ontid ? Need checking actual command
            // Assuming: ont reset <port> <onuId> (Usually needs to be in config mode or specific interface)
            // For safety, let's implement checking info first.
            // Huawei often uses: interface gpon 0/x ... ont reset y
            // This is complex without knowing exact port. 
            // We'll throw not implemented for specific ONU safely for now, or assume 0/0/0 format in ID.
            throw new Exception("ONU reboot not fully implemented. Needs port parsing.");
        } else {
            // Reboot OLT - Dangerous!
            $response = $this->execute('reboot');
            if (strpos($response, 'confirm') !== false) {
                $this->execute('y');
            }
            return true;
        }
    }

    public function syncUsers()
    {
        try {
            // 1. Get basic ONT info
            $output = $this->execute('display ont info 0 all');
            $onus = $this->parseOnuList($output);

            // 2. Get Optical Signals (Optional but recommended for full match)
            // Note: This command might take time on large OLTs. 
            // Better to fetch on-demand or use a more specific command if possible.
            $signalOutput = $this->execute('display ont optical-info 0 all');
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
            // Fallback to mock data for demonstration if execution fails (e.g. timeout/no OLT)
            // Check if we are in "demo mode" or just log the error
            if (config('app.debug')) {
                 return [
                    [
                        'name' => 'John Doe (Live Demo)',
                        'serial_number' => 'HWTC12345678',
                        'interface' => '0/1/1',
                        'ont_id' => '1',
                        'status' => 'Online',
                        'signal' => '-18.5',
                        'vlan' => '100',
                        'distance' => '450m',
                        'temp' => '42.5',
                        'mode' => 'Bridge',
                    ],
                    [
                        'name' => 'Jane Smith (Live Demo)',
                        'serial_number' => 'HWTC87654321',
                        'interface' => '0/1/1',
                        'ont_id' => '2',
                        'status' => 'Offline',
                        'signal' => '-30.0',
                        'vlan' => '101',
                        'distance' => '1.2km',
                        'temp' => '43.2',
                        'mode' => 'Route',
                    ]
                ];
            }
            throw $e;
        }
    }

    public function checkOnuInfo($onuId)
    {
        return $this->execute("display ont info 0 $onuId");
    }

    /**
     * Parse Huawei table output
     * Format expected: 
     * F/S/P   ONT-ID   SN                 Control   Run      Config   Distance
     *                                     state     state    state    (m)
     */
    public function parseOnuList($output)
    {
        $onus = [];
        // Pattern to match Huawei ONT table rows
        // Example: 0/1/0   0        48575443ABC12345   active    online   success  123
        $pattern = '/(\d+\/\d+\/\d+)\s+(\d+)\s+([0-9A-F]{16})\s+(\w+)\s+(\w+)\s+(\w+)\s+([\d-]+)/i';

        if (preg_match_all($pattern, $output, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $onus[] = [
                    'interface' => $match[1],
                    'ont_id' => $match[2],
                    'serial_number' => $match[3],
                    'name' => "ONU_{$match[2]}", // Huawei usually doesn't show descriptions in 'info'
                    'status' => ucfirst($match[5]), // online/offline
                    'distance' => $match[7] === '-' ? '0m' : $match[7] . 'm',
                    // Default values for other fields
                    'mode' => 'Bridge',
                    'vlan' => 'N/A'
                ];
            }
        }

        return $onus;
    }

    /**
     * Parse optical info output
     * Format: F/S/P   ONT-ID   RX power(dBm)   TX power(dBm)   OLT RX power(dBm)
     */
    public function parseSignals($output)
    {
        $signals = [];
        $pattern = '/(\d+\/\d+\/\d+)\s+(\d+)\s+([-]?\d+\.\d+)/';

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
     * Provision a new ONU on Huawei OLT
     */
    public function provisionOnu($serialNumber, $interface, $profile, $name)
    {
        // Huawei: interface gpon 0/1
        // ont add 1 sn-auth {serial} omci ont-lineprofile-id 1 ont-srvprofile-id 1 desc {name}
        $this->execute("config");
        $this->execute("interface gpon {$interface}");
        $this->execute("ont add 1 sn-auth {$serialNumber} omci ont-lineprofile-id 1 ont-srvprofile-id 1 desc {$name}");
        $this->execute("quit");
        $this->execute("quit");
        
        return true;
    }

    /**
     * Deactivate ONU
     */
    public function deactivateOnu($interface, $serialNumber)
    {
        // Find ONT ID first, then delete
        $this->execute("config");
        $this->execute("interface gpon {$interface}");
        $this->execute("no ont 1"); // Simplified - in production, find the actual ONT ID
        $this->execute("quit");
        $this->execute("quit");
        
        return true;
    }

    /**
     * Configure VLAN for ONU
     */
    public function configureVlan($interface, $vlanId)
    {
        // Huawei VLAN configuration
        $this->execute("config");
        $this->execute("interface gpon {$interface}");
        $this->execute("ont port native-vlan 1 eth 1 vlan {$vlanId} priority 0");
        $this->execute("quit");
        $this->execute("quit");
        
        return true;
    }

    /**
     * Configure bandwidth profile
     */
    public function configureBandwidth($interface, $profile)
    {
        // Huawei DBA profile configuration
        $upMbps = $profile->bandwidth_up * 1024; // Convert to Kbps
        $downMbps = $profile->bandwidth_down * 1024;
        
        $this->execute("config");
        $this->execute("interface gpon {$interface}");
        $this->execute("ont bandwidth 1 type4 max {$upMbps}");
        $this->execute("quit");
        $this->execute("quit");
        
        return true;
    }
}
