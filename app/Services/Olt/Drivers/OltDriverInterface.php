<?php

namespace App\Services\Olt\Drivers;

interface OltDriverInterface
{
    /**
     * Connect to the OLT (SSH/Telnet)
     */
    public function connect();

    /**
     * Disconnect from the OLT
     */
    public function disconnect();

    /**
     * Check connection status
     */
    public function isConnected(): bool;

    /**
     * Get OLT info or check availability
     */
    public function checkStatus();

    /**
     * Reboot the OLT or specific ONU
     * @param string|null $onuId If null, reboot OLT. If set, reboot ONU.
     */
    public function reboot($onuId = null);

    /**
     * Sync users/Get ONUs list
     * @return array
     */
    public function syncUsers();

    /**
     * Get detailed info for a specific ONU
     * @param string $onuId
     */
    public function checkOnuInfo($onuId);
    
    // Provisioning methods
    public function provisionOnu($serialNumber, $interface, $profile, $name);
    public function deactivateOnu($interface, $serialNumber);
    public function configureVlan($interface, $vlanId);
    public function configureBandwidth($interface, $profile);
}
