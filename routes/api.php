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

    Route::post('olt-users/bulk-action', [OltUserController::class, 'bulkAction']);
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

    // Employee Management
    Route::apiResource('employees', App\Http\Controllers\Api\Employee\EmployeeController::class);
    Route::apiResource('roles', App\Http\Controllers\Api\Employee\RoleController::class);

    // Box
    Route::apiResource('boxes', App\Http\Controllers\Api\Config\BoxController::class);

    // Client Type
    Route::apiResource('client-types', App\Http\Controllers\Api\Config\ClientTypeController::class);

    // Connection Type
    Route::apiResource('connection-types', App\Http\Controllers\Api\Config\ConnectionTypeController::class);

    Route::get('monitoring/alerts', [App\Http\Controllers\Api\Config\MonitoringController::class, 'getAlerts']);
    Route::post('monitoring/alerts/{alertId}/resolve', [App\Http\Controllers\Api\Config\MonitoringController::class, 'resolveAlert']);
});

// Billing
Route::get('billing/list', [\App\Http\Controllers\Api\Finance\BillingController::class, 'index']);
Route::post('billing/bulk-status', [\App\Http\Controllers\Api\Finance\BillingController::class, 'bulkStatus']);
Route::post('billing/bulk-zone', [\App\Http\Controllers\Api\Finance\BillingController::class, 'bulkZone']);
Route::post('billing/bulk-extend', [\App\Http\Controllers\Api\Finance\BillingController::class, 'bulkExtend']);
Route::get('billing/export/excel', [\App\Http\Controllers\Api\Finance\BillingController::class, 'exportExcel']);
Route::get('billing/invoices/bulk', [\App\Http\Controllers\Api\Finance\BillingController::class, 'downloadBulkInvoice']);
Route::get('billing/export/pdf', [\App\Http\Controllers\Api\Finance\BillingController::class, 'exportPdf']);

Route::post('communication/sms/bulk', [\App\Http\Controllers\Api\Finance\BillingController::class, 'sendBulkSms']);
Route::post('communication/email/bulk', [\App\Http\Controllers\Api\Finance\BillingController::class, 'sendBulkEmail']);

Route::post('mikrotik/sync', [\App\Http\Controllers\Api\Config\MikrotikRouterController::class, 'sync']);

// Daily Collection
Route::get('billing/daily-collections', [\App\Http\Controllers\Api\Finance\DailyCollectionController::class, 'index']);
Route::post('billing/daily-collections', [\App\Http\Controllers\Api\Finance\DailyCollectionController::class, 'store']);
Route::post('billing/daily-collections/approve', [\App\Http\Controllers\Api\Finance\DailyCollectionController::class, 'approveBulk']);
Route::post('billing/daily-collections/delete', [\App\Http\Controllers\Api\Finance\DailyCollectionController::class, 'deleteBulk']);
Route::get('billing/daily-collections/export', [\App\Http\Controllers\Api\Finance\DailyCollectionController::class, 'export']);
Route::get('billing/daily-collections/search-client', [\App\Http\Controllers\Api\Finance\DailyCollectionController::class, 'searchClient']);

// Webhook Payments
Route::get('billing/webhook-payments', [\App\Http\Controllers\Api\Finance\WebhookPaymentController::class, 'index']);
Route::get('billing/webhook-payments/export', [\App\Http\Controllers\Api\Finance\WebhookPaymentController::class, 'export']);
Route::get('billing/webhook-payments/{id}', [\App\Http\Controllers\Api\Finance\WebhookPaymentController::class, 'show']);
