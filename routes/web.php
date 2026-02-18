<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// --------------------------------------------------------------------------
// TENANT / SUBDOMAIN ROUTES (Client Portal)
// --------------------------------------------------------------------------
// Matches any domain EXCEPT localhost/127.0.0.1 (The Main App)
Route::domain('{tenant_domain}')
    ->where(['tenant_domain' => '^(?!localhost|127\.0\.0\.1|madaaq\.com|www\.madaaq\.com).*$'])
    ->group(function () {
        
        Route::middleware('tenant')->name('portal.')->group(function () {
            // Account Setup / Forgot Password
            Route::get('setup', [\App\Http\Controllers\Portal\AccountSetupController::class, 'showRequestForm'])->name('setup.request');
            Route::post('setup/check', [\App\Http\Controllers\Portal\AccountSetupController::class, 'checkPhone'])->name('setup.check');
            Route::get('setup/email/{token}', [\App\Http\Controllers\Portal\AccountSetupController::class, 'showEmailForm'])->name('setup.email');
            Route::post('setup/send-link', [\App\Http\Controllers\Portal\AccountSetupController::class, 'sendResetLink'])->name('setup.send-link');
            Route::get('setup/reset/{token}', [\App\Http\Controllers\Portal\AccountSetupController::class, 'showResetForm'])->name('setup.reset');
            Route::post('setup/reset', [\App\Http\Controllers\Portal\AccountSetupController::class, 'reset'])->name('setup.update');
            
            Route::get('/', [\App\Http\Controllers\Portal\AuthController::class, 'showLoginForm'])->name('login');
            Route::post('login', [\App\Http\Controllers\Portal\AuthController::class, 'login'])->name('login.submit');
            
            Route::middleware('auth:client')->group(function () {
                Route::get('/dashboard', [\App\Http\Controllers\Portal\DashboardController::class, 'index'])->name('dashboard');
                Route::get('/invoices', [\App\Http\Controllers\Portal\InvoiceController::class, 'index'])->name('invoices.index');
                Route::get('/invoices/{invoice}', [\App\Http\Controllers\Portal\InvoiceController::class, 'show'])->name('invoices.show');
                Route::get('/invoices/{invoice}/pay', [\App\Http\Controllers\Portal\InvoiceController::class, 'pay'])->name('invoices.pay');
                Route::post('/invoices/{invoice}/pay', [\App\Http\Controllers\Portal\InvoiceController::class, 'initiate'])->name('invoices.initiate');
                
                Route::get('/packages', [\App\Http\Controllers\Portal\PackageController::class, 'index'])->name('packages.index');
                Route::post('logout', [\App\Http\Controllers\Portal\AuthController::class, 'logout'])->name('logout');
            });
        });
    });

// --------------------------------------------------------------------------
// MAIN DOMAIN ROUTES (Vendor Dashboard & Super Admin)
// --------------------------------------------------------------------------
Route::group([], function () {
    
    // Public API Routes (MikroTik Sync)
    Route::post('/api/routers/{router}/sync', [\App\Http\Controllers\RouterManagementController::class, 'syncRouter'])->name('api.router.sync');
    Route::post('/api/servers/{server}/backups/upload', [\App\Http\Controllers\Network\ServerBackupController::class, 'upload'])->name('api.server.backup.upload');

    // Root Route (Landing Page)
    Route::get('/', function () {
        return view('welcome');
    })->name('home');

    // Authentication Routes
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Registration Routes
    Route::get('/register', [\App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [\App\Http\Controllers\Auth\RegisterController::class, 'register']);
    
    // Legal Pages
    Route::view('/privacy-policy', 'privacy')->name('privacy');
    Route::view('/terms-and-conditions', 'terms')->name('terms');

    // Email Verification Routes
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->middleware('auth')->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (\Illuminate\Foundation\Auth\EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect()->route('dashboard')->with('success', 'تم تأكيد بريدك الإلكتروني بنجاح!');
    })->middleware(['auth', 'signed'])->name('verification.verify');

    Route::post('/email/verification-notification', function (Illuminate\Http\Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', 'Verification link sent!');
    })->middleware(['auth', 'throttle:6,1'])->name('verification.send');

    // Admin Routes (Super Admin)
    Route::prefix('admin')->name('admin.')->middleware(['auth', 'is_admin'])->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    
    // Tenant Management
    // Admin Resources
    Route::resource('tenants', \App\Http\Controllers\Admin\TenantController::class);
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class)->only(['index']); // Read-only for now global view

    Route::patch('tenants/{tenant}/toggle', [\App\Http\Controllers\Admin\TenantController::class, 'toggleStatus'])->name('tenants.toggle');
    Route::post('tenants/{tenant}/impersonate', [\App\Http\Controllers\Admin\TenantController::class, 'impersonate'])->name('tenants.impersonate');
        // Reports Routes
        Route::get('reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
        Route::get('reports/financial', [\App\Http\Controllers\Admin\ReportController::class, 'financial'])->name('reports.financial');
        Route::get('reports/tenants', [\App\Http\Controllers\Admin\ReportController::class, 'tenants'])->name('reports.tenants');
        Route::get('reports/clients', [\App\Http\Controllers\Admin\ReportController::class, 'clients'])->name('reports.clients');

        // Subscriptions
        Route::get('subscriptions', [\App\Http\Controllers\Admin\SubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::post('subscriptions/{invoice}/approve', [\App\Http\Controllers\Admin\SubscriptionController::class, 'approve'])->name('subscriptions.approve');
    Route::post('subscriptions/{invoice}/reject', [\App\Http\Controllers\Admin\SubscriptionController::class, 'reject'])->name('subscriptions.reject');
    Route::post('subscriptions/{tenant}/extend', [\App\Http\Controllers\Admin\SubscriptionController::class, 'extend'])->name('subscriptions.extend');

        // Settings
        Route::get('settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
        Route::post('settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
    });

    // Vendor Dashboard Routes (Protected)
    Route::middleware(['auth', 'tenant'])->group(function () {
        // Impersonation Leave Route (Accessible by anyone with a session key, verified in controller)
        Route::get('admin/impersonate/leave', [\App\Http\Controllers\Admin\TenantController::class, 'leaveImpersonation'])->name('admin.impersonate.leave');

        // Subscription Routes (Accessible without active subscription)
        Route::get('subscription', [\App\Http\Controllers\SubscriptionController::class, 'index'])->name('subscription.index');
        Route::post('subscription/subscribe', [\App\Http\Controllers\SubscriptionController::class, 'subscribe'])->name('subscription.subscribe');
        Route::post('subscription/trial', [\App\Http\Controllers\SubscriptionController::class, 'startTrial'])->name('subscription.trial');
    
        // Protected Routes (Require Access)
        Route::middleware(['subscription'])->group(function () {
            // Dashboard
            Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
            Route::get('/api/stats', [\App\Http\Controllers\DashboardController::class, 'getStats'])->name('api.stats');
            
            // Routers (Network Equipment)
            Route::resource('routers', \App\Http\Controllers\RouterController::class);
            Route::any('/routers/{router}/webfig/{path?}', [\App\Http\Controllers\Network\ProxyController::class, 'webfig'])
                ->where('path', '.*')
                ->name('routers.webfig');
            Route::get('/routers/{router}/script', [\App\Http\Controllers\RouterController::class, 'getScript'])->name('routers.script');
            Route::get('/api/devices/search', [\App\Http\Controllers\RouterController::class, 'searchDevices'])->name('api.devices.search');
            
            // Mobile Webfig Handler (Token based login)
            Route::get('/webfig/mobile/{token}', [\App\Http\Controllers\Network\ProxyController::class, 'handleMobileToken'])
                ->name('webfig.mobile');

            // Webfig Trap Route (Catches requests that escape the proxy to root /webfig)
            Route::any('/webfig/{path?}', [\App\Http\Controllers\Network\ProxyController::class, 'handleTrap'])
                ->where('path', '.*')
                ->name('webfig.trap');
    
            // MikroTik Servers Management
            Route::prefix('servers')->name('servers.')->group(function () {
                Route::get('/', [\App\Http\Controllers\MikroTikServerController::class, 'index'])->name('index');
                Route::get('/create', [\App\Http\Controllers\MikroTikServerController::class, 'create'])->name('create');
                Route::post('/', [\App\Http\Controllers\MikroTikServerController::class, 'store'])->name('store');
                Route::get('/{server}', [\App\Http\Controllers\MikroTikServerController::class, 'show'])->name('show');
                Route::get('/{server}/edit', [\App\Http\Controllers\MikroTikServerController::class, 'edit'])->name('edit');
                Route::put('/{server}', [\App\Http\Controllers\MikroTikServerController::class, 'update'])->name('update');
                Route::get('/{server}/setup-script', [\App\Http\Controllers\MikroTikServerController::class, 'getSetupScript'])->name('setup-script');
                Route::post('/{server}/test-connection', [\App\Http\Controllers\MikroTikServerController::class, 'testConnection'])->name('test-connection');
                Route::post('/{server}/import-pppoe', [\App\Http\Controllers\MikroTikServerController::class, 'importPPPoE'])->name('import-pppoe');
                Route::post('/{server}/import-hotspot', [\App\Http\Controllers\MikroTikServerController::class, 'importHotspot'])->name('import-hotspot');
                Route::post('/{server}/import-pppoe-profiles', [\App\Http\Controllers\MikroTikServerController::class, 'importPPPoEProfiles'])->name('import-pppoe-profiles');
                Route::post('/{server}/import-hotspot-profiles', [\App\Http\Controllers\MikroTikServerController::class, 'importHotspotProfiles'])->name('import-hotspot-profiles');
                Route::get('/{server}/status', [\App\Http\Controllers\MikroTikServerController::class, 'getServerStatus'])->name('status');
                Route::get('/{server}/interfaces', [\App\Http\Controllers\MikroTikServerController::class, 'getInterfaces'])->name('interfaces');
                Route::get('/{server}/interfaces', [\App\Http\Controllers\MikroTikServerController::class, 'getInterfaces'])->name('interfaces');
                Route::get('/backups/{backup}/download', [\App\Http\Controllers\Network\ServerBackupController::class, 'download'])->name('backups.download');
                Route::delete('/{server}', [\App\Http\Controllers\MikroTikServerController::class, 'destroy'])->name('destroy');
            });
    
            // Broadband (PPPoE)
            Route::prefix('broadband')->name('broadband.')->group(function () {
                Route::resource('profiles', \App\Http\Controllers\Broadband\ProfileController::class);
                Route::resource('users', \App\Http\Controllers\Broadband\UserController::class);
            });
    
            // Hotspot
            Route::prefix('hotspot')->name('hotspot.')->group(function () {
                Route::resource('profiles', \App\Http\Controllers\Hotspot\ProfileController::class);
                
                // Vouchers
                Route::get('vouchers', [\App\Http\Controllers\Hotspot\VoucherController::class, 'index'])->name('vouchers.index');
                Route::get('vouchers/create', [\App\Http\Controllers\Hotspot\VoucherController::class, 'create'])->name('vouchers.create');
                Route::get('vouchers/reprint-last', [\App\Http\Controllers\Hotspot\VoucherController::class, 'reprintLast'])->name('vouchers.reprint_last');
                Route::post('vouchers/bulk-action', [\App\Http\Controllers\Hotspot\VoucherController::class, 'bulkAction'])->name('vouchers.bulk_action');
                Route::post('vouchers', [\App\Http\Controllers\Hotspot\VoucherController::class, 'store'])->name('vouchers.store');
                Route::get('vouchers/print-batch', [\App\Http\Controllers\Hotspot\VoucherController::class, 'printBatch'])->name('vouchers.print_batch');
    
                Route::get('users/{user}/print', [\App\Http\Controllers\Hotspot\UserController::class, 'print'])->name('users.print');
                Route::resource('users', \App\Http\Controllers\Hotspot\UserController::class);
            });
    
            // Accounting & Invoices
            Route::prefix('accounting')->name('accounting.')->group(function () {
                Route::resource('invoices', \App\Http\Controllers\Accounting\InvoiceController::class);
                Route::get('reports', [\App\Http\Controllers\Accounting\ReportController::class, 'index'])
                    ->middleware('feature:financial_reports')
                    ->name('reports.index');
            });
    
            // CRM
            Route::prefix('crm')->name('crm.')->group(function () {
                Route::resource('clients', \App\Http\Controllers\CRM\ClientController::class);
                Route::put('clients/{client}/update-password', [\App\Http\Controllers\CRM\ClientController::class, 'updatePassword'])->name('clients.update-password');
                Route::post('clients/{client}/send-credentials', [\App\Http\Controllers\CRM\ClientController::class, 'sendCredentials'])->name('clients.send-credentials');
                Route::get('clients/{client}/renew', [\App\Http\Controllers\CRM\ClientController::class, 'renewForm'])->name('clients.renew');
                Route::post('clients/{client}/renew', [\App\Http\Controllers\CRM\ClientController::class, 'renew'])->name('clients.renew.post');
                Route::post('clients/{client}/notes', [\App\Http\Controllers\CRM\ClientController::class, 'addNote'])->name('clients.notes.store');
                Route::post('clients/{client}/activities', [\App\Http\Controllers\CRM\ClientController::class, 'addActivity'])->name('clients.activities.store');
                Route::patch('clients/{client}/toggle-status', [\App\Http\Controllers\CRM\ClientController::class, 'toggleStatus'])->name('clients.toggle-status');
                
                Route::get('campaigns/create', [\App\Http\Controllers\CRM\CampaignController::class, 'create'])->name('campaigns.create');
                Route::post('campaigns', [\App\Http\Controllers\CRM\CampaignController::class, 'store'])->name('campaigns.store');
    
                // Payment Settings
                Route::get('settings/payments', [\App\Http\Controllers\CRM\Settings\PaymentGatewayController::class, 'index'])->name('settings.payments.index');
                Route::post('settings/payments', [\App\Http\Controllers\CRM\Settings\PaymentGatewayController::class, 'update'])->name('settings.payments.update');
            });
    
            // Towers
            Route::prefix('network')->name('network.')->group(function () {
                Route::get('/backups', [\App\Http\Controllers\Network\ServerBackupController::class, 'index'])->name('backups.index');
                Route::resource('towers', \App\Http\Controllers\Network\TowerController::class);
                Route::post('towers/{tower}/devices', [\App\Http\Controllers\Network\TowerDeviceController::class, 'store'])->name('towers.devices.store');
                Route::delete('towers/{tower}/devices/{device}', [\App\Http\Controllers\Network\TowerDeviceController::class, 'destroy'])->name('towers.devices.destroy');
                Route::resource('internet-sources', \App\Http\Controllers\Network\InternetSourceController::class);
                
                // Dynamic Commands
                Route::get('commands', [\App\Http\Controllers\Network\CommandController::class, 'index'])->name('commands.index');
                Route::post('commands/execute', [\App\Http\Controllers\Network\CommandController::class, 'execute'])->name('commands.execute');

                // Tower SSIDs
                Route::post('towers/{tower}/ssids', [\App\Http\Controllers\Network\TowerSSIDController::class, 'store'])->name('towers.ssids.store');
                Route::put('towers/{tower}/ssids/{ssid}', [\App\Http\Controllers\Network\TowerSSIDController::class, 'update'])->name('towers.ssids.update');
                Route::delete('towers/{tower}/ssids/{ssid}', [\App\Http\Controllers\Network\TowerSSIDController::class, 'destroy'])->name('towers.ssids.destroy');
    
                // Tower Monthly Costs
                Route::post('towers/{tower}/costs', [\App\Http\Controllers\Network\TowerMonthlyCostController::class, 'store'])->name('towers.costs.store');
                Route::delete('towers/{tower}/costs/{cost}', [\App\Http\Controllers\Network\TowerMonthlyCostController::class, 'destroy'])->name('towers.costs.destroy');

                // Device Models
                Route::post('device-models/quick-store', [\App\Http\Controllers\Network\DeviceModelController::class, 'quickStore'])->name('device-models.quick-store');
            });
    
            // Staff & Roles
            Route::resource('staff', \App\Http\Controllers\Staff\StaffController::class);
            Route::resource('roles', \App\Http\Controllers\Staff\RoleController::class);
            
            // Activity Logs
            Route::get('activity-logs', [\App\Http\Controllers\ActivityLogController::class, 'index'])->name('activity-logs.index');
            
            // Network Monitoring
            Route::get('network/monitoring', [\App\Http\Controllers\Network\MonitoringController::class, 'index'])->name('network.monitoring.index');
            Route::patch('network/monitoring/alert/{alert}/resolve', [\App\Http\Controllers\Network\MonitoringController::class, 'resolveAlert'])->name('network.monitoring.alert.resolve');
            Route::get('network/bandwidth', [\App\Http\Controllers\Network\MonitoringController::class, 'bandwidth'])->name('network.monitoring.bandwidth');
            
            // Network Topology
            Route::get('network/topology', [\App\Http\Controllers\Network\TopologyController::class, 'index'])->name('network.topology.index');
            
            // Network Discovery
            Route::get('network/discovery', [\App\Http\Controllers\Network\DiscoveryController::class, 'index'])->name('network.discovery.index');
            Route::get('network/discovery/scan/{server}', [\App\Http\Controllers\Network\DiscoveryController::class, 'scan'])->name('network.discovery.scan');
            Route::post('network/discovery/link', [\App\Http\Controllers\Network\DiscoveryController::class, 'linkDevice'])->name('network.discovery.link');
            Route::any('/network/discovery/webfig/{server}/{ip}/{path?}', [\App\Http\Controllers\Network\ProxyController::class, 'remoteWebfig'])
                ->where('path', '.*')
                ->name('network.discovery.webfig');
            
            // Website Analytics & Blocking
            Route::get('network/website/analytics', [\App\Http\Controllers\Network\WebsiteController::class, 'analytics'])->name('network.website.analytics');
            Route::get('network/website/blocked', [\App\Http\Controllers\Network\WebsiteController::class, 'blocked'])->name('network.website.blocked');
            Route::post('network/website/block', [\App\Http\Controllers\Network\WebsiteController::class, 'store'])->name('network.website.block');
            Route::delete('network/website/{domain}', [\App\Http\Controllers\Network\WebsiteController::class, 'destroy'])->name('network.website.destroy');
            Route::patch('network/website/{domain}/toggle', [\App\Http\Controllers\Network\WebsiteController::class, 'toggle'])->name('network.website.toggle');
    
            // Advanced Network Management (NEW)
            Route::get('network/live-monitoring', [\App\Http\Controllers\Network\LiveMonitoringController::class, 'index'])->name('network.live-monitoring');
            Route::get('api/network/realtime-stats', [\App\Http\Controllers\Network\LiveMonitoringController::class, 'getRealtimeStats'])->name('api.network.realtime');
            
            Route::get('network/queues', [\App\Http\Controllers\Network\QueueController::class, 'index'])->name('network.queues.index');
            Route::post('network/queues/set-speed', [\App\Http\Controllers\Network\QueueController::class, 'setSpeed'])->name('network.queues.set-speed');
            
            Route::get('network/sessions', [\App\Http\Controllers\Network\SessionController::class, 'index'])->name('network.sessions.index');
            Route::post('network/sessions/disconnect', [\App\Http\Controllers\Network\SessionController::class, 'disconnect'])->name('network.sessions.disconnect');
    
    
            // Router Management (Auto-Sync)
            Route::prefix('router-management')->name('router-management.')->group(function () {
                Route::get('/', [\App\Http\Controllers\RouterManagementController::class, 'index'])->name('index');
                Route::get('/{router}/script', [\App\Http\Controllers\RouterManagementController::class, 'generateScript'])->name('script');
            });
    

    
            // Tools
            Route::prefix('tools')->name('tools.')->group(function () {
                Route::get('migration', [\App\Http\Controllers\Tools\MigrationController::class, 'index'])->name('migration.index');
                Route::post('migration/import-mikrotik', [\App\Http\Controllers\Tools\MigrationController::class, 'importFromMikrotik'])->name('migration.import-mikrotik');
                Route::post('migration/import-file', [\App\Http\Controllers\Tools\MigrationController::class, 'importFromFile'])->name('migration.import-file');
            });
    
            // Network Map
            Route::get('network-map', [\App\Http\Controllers\MapController::class, 'index'])->name('maps.index');
            
            // Settings
            Route::get('settings', [\App\Http\Controllers\SettingController::class, 'index'])->name('settings.index');
            Route::post('settings', [\App\Http\Controllers\SettingController::class, 'update'])->name('settings.update');

            Route::post('settings/check-subdomain', [\App\Http\Controllers\SettingController::class, 'checkSubdomain'])->name('settings.check-subdomain');
    
            // Mobile App
            Route::prefix('mobile-app')->name('mobile-app.')->group(function () {
                Route::get('/', [\App\Http\Controllers\MobileAppController::class, 'index'])->name('index');
                Route::get('/create', [\App\Http\Controllers\MobileAppController::class, 'create'])->name('create');
                Route::post('/store', [\App\Http\Controllers\MobileAppController::class, 'store'])->name('store');
                Route::get('/download', [\App\Http\Controllers\MobileAppController::class, 'download'])->name('download');
            });
    
            // Payment Routes
            Route::post('payment/generate-link', [\App\Http\Controllers\Payment\PaymentController::class, 'generatePaymentLink'])->name('payment.generate');
            Route::get('payment/paypal/success', [\App\Http\Controllers\Payment\PaymentController::class, 'paypalSuccess'])->name('payment.paypal.success');
            Route::get('payment/paypal/cancel', [\App\Http\Controllers\Payment\PaymentController::class, 'paypalCancel'])->name('payment.paypal.cancel');
    
            // WhatsApp Chat
            Route::get('whatsapp', [\App\Http\Controllers\WhatsAppController::class, 'index'])->name('whatsapp.index');
            Route::get('whatsapp/{client}', [\App\Http\Controllers\WhatsAppController::class, 'show'])->name('whatsapp.show');
            Route::post('whatsapp/{client}', [\App\Http\Controllers\WhatsAppController::class, 'store'])->name('whatsapp.store');
        });
    });
});


