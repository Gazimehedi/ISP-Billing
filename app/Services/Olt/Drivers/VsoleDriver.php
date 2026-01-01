<?php

namespace App\Services\Olt\Drivers;

class VsoleDriver extends BaseDriver
{
    public function checkStatus()
    {
        return $this->execute('show system info');
    }

    public function reboot($onuId = null)
    {
        if ($onuId) {
            // VSOLE EPON often: epon reboot onu port Id
            return $this->execute("epon reboot onu $onuId");
        } else {
            $this->execute('reboot');
            return true;
        }
    }

    public function syncUsers()
    {
        try {
            // 1. Get EPON ONU information
            $output = $this->execute('show epon onu-information');
            $onus = $this->parseOnuList($output);

            // 2. Get optical transceiver diagnostics
            $signalOutput = $this->execute('show epon optical-transceiver-diagnosis');
            $signals = $this->parseSignals($signalOutput);

            // Merge signals into ONU array
            foreach ($onus as &$onu) {
                $key = "{$onu['interface']}:{$onu['ont_id']}";
                if (isset($signals[$key])) {
                    $onu['signal'] = $signals[$key];
                }
            }

            return $onus;

        } catch (\Exception $e) {
            // Fallback to demo data if connection fails
            if (config('app.debug')) {
                return [
                    [
                        'name' => 'VSOLE ONU Demo 1',
                        'serial_number' => 'BDCOM12345678',
                        'interface' => 'epon0/1',
                        'ont_id' => '1',
                        'status' => 'Online',
                        'signal' => '-18.8',
                        'vlan' => '100',
                        'distance' => '380m',
                        'temp' => 'N/A',
                        'mode' => 'Bridge',
                    ],
                    [
                        'name' => 'VSOLE ONU Demo 2',
                        'serial_number' => 'BDCOM87654321',
                        'interface' => 'epon0/1',
                        'ont_id' => '2',
                        'status' => 'Offline',
                        'signal' => '-29.1',
                        'vlan' => '101',
                        'distance' => '950m',
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
        return $this->execute("show epon onu $onuId");
    }

    /**
     * Parse VSOLE/BDCOM EPON ONU information
     * Format expected:
     * epon0/1:1    BDCOM12345678    online    380
     */
    public function parseOnuList($output)
    {
        $onus = [];
        // Pattern: epon0/1:1    BDCOM12345678    online    380
        $pattern = '/(epon\d+\/\d+):(\d+)\s+([A-Z0-9]{12,16})\s+(\w+)\s+(\d+)/i';

        if (preg_match_all($pattern, $output, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $onus[] = [
                    'interface' => $match[1],
                    'ont_id' => $match[2],
                    'serial_number' => $match[3],
                    'name' => "ONU_{$match[2]}",
                    'status' => ucfirst($match[4]), // online/offline
                    'distance' => $match[5] . 'm',
                    'mode' => 'Bridge',
                    'vlan' => 'N/A'
                ];
            }
        }

        return $onus;
    }

    /**
     * Parse VSOLE optical transceiver diagnostics
     * Format: epon0/1:1    -18.50
     */
    public function parseSignals($output)
    {
        $signals = [];
        $pattern = '/(epon\d+\/\d+):(\d+)\s+([-]?\d+\.\d+)/';

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
     * Provision a new ONU on VSOLE/BDCOM OLT
     */
    public function provisionOnu($serialNumber, $interface, $profile, $name)
    {
        // VSOLE: epon bind-onu mac {serial} {interface}
        $this->execute("configure terminal");
        $this->execute("epon bind-onu mac {$serialNumber} {$interface}");
        $this->execute("exit");
        
        return true;
    }

    /**
     * Deactivate ONU
     */
    public function deactivateOnu($interface, $serialNumber)
    {
        $this->execute("configure terminal");
        $this->execute("no epon bind-onu {$interface}");
        $this->execute("exit");
        
        return true;
    }

    /**
     * Configure VLAN for ONU
     */
    public function configureVlan($interface, $vlanId)
    {
        $this->execute("configure terminal");
        $this->execute("interface {$interface}");
        $this->execute("epon onu port 1 ctc vlan mode tag {$vlanId}");
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
        $this->execute("interface {$interface}");
        $this->execute("epon onu port 1 ctc rate-limit {$profile->bandwidth_down} {$profile->bandwidth_up}");
        $this->execute("exit");
        $this->execute("exit");
        
        return true;
    }
}
