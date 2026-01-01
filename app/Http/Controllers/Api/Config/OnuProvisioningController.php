<?php

namespace App\Http\Controllers\Api\Config;

use App\Http\Controllers\Controller;
use App\Models\Olt;
use App\Models\OltUser;
use App\Models\ServiceProfile;
use App\Services\Olt\OltService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OnuProvisioningController extends Controller
{
    /**
     * Provision a new ONU on the OLT
     */
    public function provision(Request $request, Olt $olt, OltService $service)
    {
        $validator = Validator::make($request->all(), [
            'serial_number' => 'required|string|max:16',
            'service_profile_id' => 'required|exists:service_profiles,id',
            'interface' => 'required|string', // e.g., "0/1/1" for Huawei, "gpon-onu_1/1/1" for ZTE
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            // Get service profile
            $profile = ServiceProfile::findOrFail($request->service_profile_id);

            // Provision ONU via driver
            $driver = $service->getDriver($olt);
            $driver->connect();
            
            $result = $driver->provisionOnu(
                $request->serial_number,
                $request->interface,
                $profile,
                $request->name ?? "ONU_{$request->serial_number}"
            );

            $driver->disconnect();

            // Create OltUser record
            $oltUser = OltUser::create([
                'olt_id' => $olt->id,
                'service_profile_id' => $profile->id,
                'name' => $request->name ?? "ONU_{$request->serial_number}",
                'serial_number' => $request->serial_number,
                'interface' => $request->interface,
                'status' => 'Online',
                'signal' => 'N/A',
                'vlan' => $profile->vlan_id,
                'mode' => $profile->vlan_mode
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'ONU provisioned successfully',
                'data' => $oltUser
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Provisioning failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Deactivate an ONU
     */
    public function deactivate(OltUser $onu, OltService $service)
    {
        try {
            $olt = $onu->olt;
            $driver = $service->getDriver($olt);
            $driver->connect();
            
            $driver->deactivateOnu($onu->interface, $onu->serial_number);
            
            $driver->disconnect();

            // Update status
            $onu->update(['status' => 'Deactivated']);

            return response()->json([
                'status' => 'success',
                'message' => 'ONU deactivated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Deactivation failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update ONU VLAN configuration
     */
    public function updateVlan(Request $request, OltUser $onu, OltService $service)
    {
        $validator = Validator::make($request->all(), [
            'vlan_id' => 'required|integer|min:1|max:4094'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $olt = $onu->olt;
            $driver = $service->getDriver($olt);
            $driver->connect();
            
            $driver->configureVlan($onu->interface, $request->vlan_id);
            
            $driver->disconnect();

            // Update record
            $onu->update(['vlan' => $request->vlan_id]);

            return response()->json([
                'status' => 'success',
                'message' => 'VLAN updated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'VLAN update failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update ONU bandwidth profile
     */
    public function updateBandwidth(Request $request, OltUser $onu, OltService $service)
    {
        $validator = Validator::make($request->all(), [
            'service_profile_id' => 'required|exists:service_profiles,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $profile = ServiceProfile::findOrFail($request->service_profile_id);
            
            $olt = $onu->olt;
            $driver = $service->getDriver($olt);
            $driver->connect();
            
            $driver->configureBandwidth($onu->interface, $profile);
            
            $driver->disconnect();

            // Update record
            $onu->update(['service_profile_id' => $profile->id]);

            return response()->json([
                'status' => 'success',
                'message' => 'Bandwidth profile updated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Bandwidth update failed: ' . $e->getMessage()
            ], 500);
        }
    }
}
