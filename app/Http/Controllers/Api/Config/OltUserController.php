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
                    array_merge($userData, ['olt_id' => $olt->id])
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
}
