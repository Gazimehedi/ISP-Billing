<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Config\MikrotikRouterController;
use App\Http\Controllers\Api\Config\OltController;
use App\Http\Controllers\Api\Config\OltUserController;
use App\Http\Controllers\Api\Config\ZoneController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Configuration Modules
Route::prefix('config')->group(function () {
    // Mikrotik Router
    Route::post('mikrotik-routers/test-connection', [MikrotikRouterController::class, 'testConnection']);
    Route::post('mikrotik-routers/{id}/test-connection', [MikrotikRouterController::class, 'testConnectionById']);
    Route::apiResource('mikrotik-routers', MikrotikRouterController::class);
    Route::post('mikrotik-routers/{mikrotik_router}/test-connection', [MikrotikRouterController::class, 'testConnection']);

    // OLT Configuration
    Route::apiResource('olts', OltController::class);
    Route::post('olts/{olt}/test-connection', [OltController::class, 'testConnection']);
    // Route::post('olts/{olt}/reboot', [OltController::class, 'reboot']); // Moved to below
    // Route::post('olts/{olt}/sync-users', [OltController::class, 'syncUsers']); // Deprecated in favor of OltUserController
    
    Route::post('olts/{olt}/reboot', [OltController::class, 'reboot']);
    Route::post('olts/{olt}/sync-users', [OltUserController::class, 'sync']);
    Route::get('olts/{olt}/check-onu/{onuId}', [OltController::class, 'checkOnuInfo']);

    Route::apiResource('olt-users', OltUserController::class);

    // Service Profiles
    Route::apiResource('service-profiles', App\Http\Controllers\Api\Config\ServiceProfileController::class);

    // ONU Provisioning
    Route::post('olts/{olt}/provision-onu', [App\Http\Controllers\Api\Config\OnuProvisioningController::class, 'provision']);
    Route::post('olt-users/{onu}/deactivate', [App\Http\Controllers\Api\Config\OnuProvisioningController::class, 'deactivate']);
    Route::post('olt-users/{onu}/update-vlan', [App\Http\Controllers\Api\Config\OnuProvisioningController::class, 'updateVlan']);
    Route::post('olt-users/{onu}/update-bandwidth', [App\Http\Controllers\Api\Config\OnuProvisioningController::class, 'updateBandwidth']);

    // Zone
    Route::apiResource('zones', ZoneController::class);

    // Client Management
    Route::apiResource('clients', App\Http\Controllers\Api\Client\ClientController::class);
    Route::apiResource('packages', App\Http\Controllers\Api\Client\PackageController::class); // Need to create PackageController briefly or just use a simple one

    // Sub Zone
    Route::apiResource('sub-zones', App\Http\Controllers\Api\Config\SubZoneController::class);

    // Box
    Route::apiResource('boxes', App\Http\Controllers\Api\Config\BoxController::class);

    // Client Type
    Route::apiResource('client-types', App\Http\Controllers\Api\Config\ClientTypeController::class);

    // Connection Type
    Route::apiResource('connection-types', App\Http\Controllers\Api\Config\ConnectionTypeController::class);
});
