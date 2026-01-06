<?php

namespace App\Http\Controllers\Api\Config;

use App\Http\Controllers\Controller;
use App\Models\Olt;
use App\Models\OltUser;
use App\Services\Olt\OltService;
use Illuminate\Http\Request;

class OltUserController extends Controller
{
    /**
     * Get list of OLT users (with optional filtering)
     */
    public function index(Request $request)
    {
        $query = OltUser::with('olt');

        if ($request->has('search') && $request->search) {
             $search = $request->search;
             $query->where(function($q) use ($search) {
                 $q->where('name', 'like', "%$search%")
                   ->orWhere('serial_number', 'like', "%$search%")
                   ->orWhere('interface', 'like', "%$search%");
             });
        }
        
        if ($request->has('olt_id') && $request->olt_id) {
            $query->where('olt_id', $request->olt_id);
        }

        $users = $query->latest()->paginate($request->per_page ?? 15);
        
        $users->getCollection()->transform(function($user) {
            $user->olt_name = $user->olt->name ?? 'N/A';
            return $user;
        });

        return response()->json($users);
    }

    /**
     * Sync users from a specific OLT
     */
    public function sync(Olt $olt, OltService $service)
    {
        try {
            $syncedData = $service->syncUsers($olt);
            
            $count = 0;
            foreach($syncedData as $userData) {
                // Ignore raw data if it sneaks in
                if (!isset($userData['serial_number'])) continue;

                OltUser::updateOrCreate(
                    ['olt_id' => $olt->id, 'serial_number' => $userData['serial_number']],
                    array_merge($userData, [
                        'olt_id' => $olt->id,
                        'ont_id' => $userData['ont_id'] ?? null
                    ])
                );
                $count++;
            }

            return response()->json([
                'status' => 'success',
                'message' => "Synced $count users successfully",
                'data' => $syncedData
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Sync failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Perform bulk actions on OLT users
     */
    public function bulkAction(Request $request, OltService $service)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'action' => 'required|string|in:reboot,sync_signal,deactivate',
        ]);

        $ids = $validated['ids'];
        $action = $validated['action'];
        $results = [];

        // Group users by OLT to minimize connections
        $usersByOlt = OltUser::with('olt')->whereIn('id', $ids)->get()->groupBy('olt_id');

        foreach ($usersByOlt as $oltId => $users) {
            $olt = $users->first()->olt;
            try {
                $driver = $service->getDriver($olt);
                $driver->connect();

                foreach ($users as $user) {
                    try {
                        switch ($action) {
                            case 'reboot':
                                // Most drivers might need interface + ont_id or serial
                                // We'll pass both if available to the driver's reboot method
                                // Assuming we update OltService->reboot or call driver directly
                                $driver->reboot($user->ont_id ?: $user->serial_number);
                                $results[] = ['id' => $user->id, 'status' => 'success', 'message' => 'Reboot command sent'];
                                break;
                            case 'sync_signal':
                                $info = $driver->checkOnuInfo($user->ont_id ?: $user->serial_number);
                                // If driver returns parsed signal, we update it
                                if (isset($info['signal'])) {
                                     $user->update(['signal' => $info['signal']]);
                                }
                                $results[] = ['id' => $user->id, 'status' => 'success', 'message' => 'Signal synced', 'data' => $info];
                                break;
                            case 'deactivate':
                                $driver->deactivateOnu($user->interface, $user->serial_number);
                                $user->update(['status' => 'Deactivated']);
                                $results[] = ['id' => $user->id, 'status' => 'success', 'message' => 'Deactivated'];
                                break;
                        }
                    } catch (\Exception $e) {
                        $results[] = ['id' => $user->id, 'status' => 'error', 'message' => $e->getMessage()];
                    }
                }

                $driver->disconnect();
            } catch (\Exception $e) {
                foreach ($users as $user) {
                    $results[] = ['id' => $user->id, 'status' => 'error', 'message' => "OLT [{$olt->name}] connection failed: " . $e->getMessage()];
                }
            }
        }

        return response()->json([
            'status' => 'success',
            'results' => $results
        ]);
    }
}
