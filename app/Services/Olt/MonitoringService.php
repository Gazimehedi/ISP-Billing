<?php

namespace App\Services\Olt;

use App\Models\Olt;
use App\Models\OltUser;
use App\Models\OltSignalHistory;
use App\Models\OltAlert;
use App\Services\Olt\OltService;
use Illuminate\Support\Facades\Log;

class MonitoringService
{
    protected $oltService;

    public function __construct(OltService $oltService)
    {
        $this->oltService = $oltService;
    }

    /**
     * Sync signal data for all ONUs across all OLTs
     */
    public function syncAllSignals()
    {
        $olts = Olt::where('is_active', true)->get();
        $totalSynced = 0;
        $errors = [];

        foreach ($olts as $olt) {
            try {
                $synced = $this->syncOltSignals($olt);
                $totalSynced += $synced;
            } catch (\Exception $e) {
                $errors[] = "OLT {$olt->name}: " . $e->getMessage();
                Log::error("Failed to sync signals for OLT {$olt->id}", [
                    'error' => $e->getMessage()
                ]);
            }
        }

        return [
            'total_synced' => $totalSynced,
            'errors' => $errors
        ];
    }

    /**
     * Sync signal data for a specific OLT
     */
    public function syncOltSignals(Olt $olt)
    {
        $oltUsers = OltUser::where('olt_id', $olt->id)->get();
        $synced = 0;

        foreach ($oltUsers as $oltUser) {
            try {
                // Fetch current signal info
                $signalInfo = $this->oltService->getOnuSignalInfo($olt, $oltUser->interface);
                
                if ($signalInfo) {
                    // Store in history
                    OltSignalHistory::create([
                        'olt_user_id' => $oltUser->id,
                        'rx_power' => $signalInfo['rx_power'] ?? null,
                        'tx_power' => $signalInfo['tx_power'] ?? null,
                        'onu_temp' => $signalInfo['temperature'] ?? null,
                        'onu_voltage' => $signalInfo['voltage'] ?? null,
                        'captured_at' => now()
                    ]);

                    // Update current signal in olt_users table
                    $oltUser->update([
                        'signal' => $signalInfo['rx_power'] ?? null,
                        'temp' => $signalInfo['temperature'] ?? null
                    ]);

                    // Check for alerts
                    $this->checkSignalAlert($oltUser, $signalInfo);
                    
                    $synced++;
                }
            } catch (\Exception $e) {
                Log::warning("Failed to sync signal for ONU {$oltUser->id}", [
                    'error' => $e->getMessage()
                ]);
            }
        }

        return $synced;
    }

    /**
     * Check if signal levels warrant an alert
     */
    protected function checkSignalAlert(OltUser $oltUser, array $signalInfo)
    {
        $rxPower = $signalInfo['rx_power'] ?? null;
        
        if ($rxPower === null) {
            return;
        }

        // Define thresholds
        $criticalThreshold = -28; // dBm
        $warningThreshold = -25;  // dBm

        $severity = null;
        $message = null;

        if ($rxPower < $criticalThreshold) {
            $severity = 'critical';
            $message = "Critical: RX Power is {$rxPower} dBm (below {$criticalThreshold} dBm)";
        } elseif ($rxPower < $warningThreshold) {
            $severity = 'warning';
            $message = "Warning: RX Power is {$rxPower} dBm (below {$warningThreshold} dBm)";
        }

        if ($severity) {
            // Check if we already have a recent alert for this ONU
            $recentAlert = OltAlert::where('olt_user_id', $oltUser->id)
                ->where('type', 'signal_low')
                ->where('created_at', '>', now()->subHours(1))
                ->first();

            if (!$recentAlert) {
                OltAlert::create([
                    'olt_user_id' => $oltUser->id,
                    'type' => 'signal_low',
                    'severity' => $severity,
                    'message' => $message,
                    'is_resolved' => false
                ]);
            }
        }
    }

    /**
     * Get monitoring summary statistics
     */
    public function getSummary()
    {
        $totalOlts = Olt::count();
        $activeOlts = Olt::where('is_active', true)->count();
        $totalOnus = OltUser::count();
        $onlineOnus = OltUser::where('status', 'online')->count();
        $offlineOnus = OltUser::where('status', 'offline')->count();
        $unresolvedAlerts = OltAlert::where('is_resolved', false)->count();
        $criticalAlerts = OltAlert::where('is_resolved', false)
            ->where('severity', 'critical')
            ->count();

        return [
            'total_olts' => $totalOlts,
            'active_olts' => $activeOlts,
            'total_onus' => $totalOnus,
            'online_onus' => $onlineOnus,
            'offline_onus' => $offlineOnus,
            'unresolved_alerts' => $unresolvedAlerts,
            'critical_alerts' => $criticalAlerts
        ];
    }

    /**
     * Get signal trend data for a specific ONU
     */
    public function getSignalTrends($oltUserId, $hours = 24)
    {
        return OltSignalHistory::where('olt_user_id', $oltUserId)
            ->where('captured_at', '>=', now()->subHours($hours))
            ->orderBy('captured_at', 'asc')
            ->get(['rx_power', 'tx_power', 'onu_temp', 'captured_at']);
    }
}
