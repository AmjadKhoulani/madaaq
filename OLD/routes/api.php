<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\RouterController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\TenantController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Public Sync Endpoints (MikroTik)
Route::match(['get', 'post'], 'servers/{server}/sync', [\App\Http\Controllers\Api\Vendor\MikroTikServerController::class, 'syncServer'])->name('api.server.sync');

// Public API (Development Mode)
// Route::apiResource('routers', RouterController::class);
// Route::apiResource('packages', PackageController::class);
// Route::apiResource('clients', ClientController::class);
// Route::apiResource('tenants', TenantController::class);

// Client Mobile App API
Route::prefix('v1/client')->group(function () {
    Route::post('login', [\App\Http\Controllers\Api\Client\AuthController::class, 'login']);
    
    // Authenticated Routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('config', [\App\Http\Controllers\Api\Client\ConfigController::class, 'index']);
        Route::get('user', [\App\Http\Controllers\Api\Client\AuthController::class, 'user']);
        Route::post('logout', [\App\Http\Controllers\Api\Client\AuthController::class, 'logout']);
        
        Route::get('dashboard', [\App\Http\Controllers\Api\Client\DashboardController::class, 'index']);
        
        Route::get('invoices', [\App\Http\Controllers\Api\Client\InvoiceController::class, 'index']);
        Route::get('invoices/{invoice}', [\App\Http\Controllers\Api\Client\InvoiceController::class, 'show']);
        
        Route::get('packages', [\App\Http\Controllers\Api\Client\PackageController::class, 'index']);
    });
});

// Vendor Mobile App API (Admin)
Route::prefix('v1/vendor')->name('api.vendor.')->group(function () {
    Route::post('login', [\App\Http\Controllers\Api\Vendor\AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('user', [\App\Http\Controllers\Api\Vendor\AuthController::class, 'user']);
        Route::post('logout', [\App\Http\Controllers\Api\Vendor\AuthController::class, 'logout']);
        
        Route::get('dashboard', [\App\Http\Controllers\Api\Vendor\DashboardController::class, 'index']);
        
        Route::apiResource('clients', \App\Http\Controllers\Api\Vendor\ClientController::class);
        Route::post('clients/{client}/block', [\App\Http\Controllers\Api\Vendor\ClientController::class, 'block']);
        Route::post('clients/{client}/renew', [\App\Http\Controllers\Api\Vendor\ClientController::class, 'renew']);

        // Finance
        Route::prefix('finance')->group(function () {
            Route::get('invoices', [\App\Http\Controllers\Api\Vendor\FinanceController::class, 'index']);
            Route::get('invoices/{invoice}', [\App\Http\Controllers\Api\Vendor\FinanceController::class, 'show']);
        });

        // Hotspot
        Route::prefix('hotspot')->group(function () {
            Route::get('vouchers', [\App\Http\Controllers\Api\Vendor\HotspotController::class, 'vouchers']);
            Route::post('vouchers', [\App\Http\Controllers\Api\Vendor\HotspotController::class, 'storeVoucher']);
            Route::get('users', [\App\Http\Controllers\Api\Vendor\HotspotController::class, 'users']);
            Route::get('packages', [\App\Http\Controllers\Api\Vendor\HotspotController::class, 'packages']);
        });

        // Broadband (PPPoE)
        Route::prefix('broadband')->group(function () {
            Route::get('users', [\App\Http\Controllers\Api\Vendor\BroadbandController::class, 'users']);
            Route::get('profiles', [\App\Http\Controllers\Api\Vendor\BroadbandController::class, 'profiles']);
        });

        // Network
        Route::prefix('network')->group(function () {
            Route::get('sessions', [\App\Http\Controllers\Api\Vendor\NetworkController::class, 'sessions']);
            Route::post('sessions/{session}/disconnect', [\App\Http\Controllers\Api\Vendor\NetworkController::class, 'disconnectSession']);
            Route::get('topology', [\App\Http\Controllers\Api\Vendor\NetworkController::class, 'topology']);
            Route::get('analytics', [\App\Http\Controllers\Api\Vendor\NetworkController::class, 'analytics']);
            
            // Towers
            Route::apiResource('towers', \App\Http\Controllers\Api\Vendor\Network\TowerController::class);
        });

        // Reports
        Route::prefix('reports')->group(function () {
            Route::get('financial', [\App\Http\Controllers\Api\Vendor\ReportController::class, 'financial']);
        });

        // Infrastructure
        Route::apiResource('routers', \App\Http\Controllers\Api\Vendor\RouterController::class);
        Route::apiResource('servers', \App\Http\Controllers\Api\Vendor\MikroTikServerController::class);
        Route::post('servers/{server}/test-connection', [\App\Http\Controllers\Api\Vendor\MikroTikServerController::class, 'testConnection']);
        Route::post('servers/{server}/import-pppoe', [\App\Http\Controllers\Api\Vendor\MikroTikServerController::class, 'importPPPoE']);
        Route::post('servers/{server}/import-hotspot', [\App\Http\Controllers\Api\Vendor\MikroTikServerController::class, 'importHotspot']);
        Route::get('servers/{server}/webfig-url', [\App\Http\Controllers\Api\Vendor\MikroTikServerController::class, 'getWebfigUrl']);
        Route::get('servers/{server}/setup-script', [\App\Http\Controllers\Api\Vendor\MikroTikServerController::class, 'getSetupScript']);

        // Communication & Marketing
        Route::get('whatsapp', [\App\Http\Controllers\Api\Vendor\WhatsAppController::class, 'index']);
        Route::get('whatsapp/{client}', [\App\Http\Controllers\Api\Vendor\WhatsAppController::class, 'show']);
        Route::post('whatsapp/{client}', [\App\Http\Controllers\Api\Vendor\WhatsAppController::class, 'store']);
        
        Route::apiResource('campaigns', \App\Http\Controllers\Api\Vendor\CRM\CampaignController::class)->only(['index', 'store']);

        // Extras
        Route::resource('staff', \App\Http\Controllers\Api\Vendor\StaffController::class)->only(['index', 'store']);
        Route::get('activity-logs', [\App\Http\Controllers\Api\Vendor\ActivityLogController::class, 'index']);
        
        Route::post('settings/profile', [\App\Http\Controllers\Api\Vendor\SettingController::class, 'updateProfile']);
        Route::post('settings/tenant', [\App\Http\Controllers\Api\Vendor\SettingController::class, 'updateTenantDetails']);
        Route::post('settings/password', [\App\Http\Controllers\Api\Vendor\SettingController::class, 'updatePassword']);
    });
});
