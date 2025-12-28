<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
    Route::post('mikrotik-routers/test-connection', [App\Http\Controllers\Api\Config\MikrotikRouterController::class, 'testConnection']);
    Route::post('mikrotik-routers/{id}/test-connection', [App\Http\Controllers\Api\Config\MikrotikRouterController::class, 'testConnectionById']);
    Route::apiResource('mikrotik-routers', App\Http\Controllers\Api\Config\MikrotikRouterController::class);

    // Zone
    Route::apiResource('zones', App\Http\Controllers\Api\Config\ZoneController::class);

    // Sub Zone
    Route::apiResource('sub-zones', App\Http\Controllers\Api\Config\SubZoneController::class);

    // Box
    Route::apiResource('boxes', App\Http\Controllers\Api\Config\BoxController::class);

    // Client Type
    Route::apiResource('client-types', App\Http\Controllers\Api\Config\ClientTypeController::class);

    // Connection Type
    Route::apiResource('connection-types', App\Http\Controllers\Api\Config\ConnectionTypeController::class);
});
