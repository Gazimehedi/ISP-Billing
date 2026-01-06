<?php

namespace App\Http\Controllers\Api\Config;

use App\Http\Controllers\Controller;
use App\Services\Olt\MonitoringService;
use App\Models\OltAlert;
use Illuminate\Http\Request;

class MonitoringController extends Controller
{
    protected $monitoringService;

    public function __construct(MonitoringService $monitoringService)
    {
        $this->monitoringService = $monitoringService;
    }

    /**
     * Get monitoring dashboard summary
     */
    public function getSummary()
    {
        $summary = $this->monitoringService->getSummary();
        return response()->json($summary);
    }

    /**
     * Sync all OLT signals manually
     */
    public function syncSignals()
    {
        $result = $this->monitoringService->syncAllSignals();
        
        return response()->json([
            'status' => 'success',
            'message' => "Synced {$result['total_synced']} ONU signals",
            'data' => $result
        ]);
    }

    /**
     * Get signal trends for a specific ONU
     */
    public function getSignalTrends(Request $request, $oltUserId)
    {
        $hours = $request->input('hours', 24);
        $trends = $this->monitoringService->getSignalTrends($oltUserId, $hours);
        
        return response()->json($trends);
    }

    /**
     * Get recent alerts
     */
    public function getAlerts(Request $request)
    {
        $query = OltAlert::with('oltUser.olt');

        if ($request->has('unresolved_only') && $request->boolean('unresolved_only')) {
            $query->where('is_resolved', false);
        }

        if ($request->has('severity')) {
            $query->where('severity', $request->input('severity'));
        }

        $alerts = $query->latest()->paginate($request->input('per_page', 20));
        
        return response()->json($alerts);
    }

    /**
     * Resolve an alert
     */
    public function resolveAlert($alertId)
    {
        $alert = OltAlert::findOrFail($alertId);
        $alert->update([
            'is_resolved' => true,
            'resolved_at' => now()
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Alert resolved successfully'
        ]);
    }
}
