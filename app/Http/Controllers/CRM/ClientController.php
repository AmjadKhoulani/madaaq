<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\ClientNote;
use App\Models\ClientActivity;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $query = Client::with(['package', 'router']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $clients = $query->latest()->paginate(20);

        return view('crm.clients.index', compact('clients'));
    }

    public function create()
    {
        $packages = \App\Models\Package::all();
        $routers = \App\Models\Router::all();
        
        // Load servers with both administrative towers and uplink (hierarchy) towers
        $servers = \App\Models\MikroTikServer::with([
            'towers.ssids', 'towers.routers', 'towers.devices',
            'uplinkTowers.ssids', 'uplinkTowers.routers', 'uplinkTowers.devices'
        ])->get();

        // Merge towers for each server so the view sees them all in one list
        $servers->each(function($server) {
            $server->all_towers = $server->towers->merge($server->uplinkTowers)->unique('id');
        });

        $deviceModels = \App\Models\DeviceModel::all();
        
        $lastIp = Client::latest()->whereNotNull('ip')->value('ip');

        return view('crm.clients.create', compact('packages', 'routers', 'servers', 'deviceModels', 'lastIp'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|unique:clients,username', // Phone used as username
            'password' => 'required|string|min:6', // Portal Password
            'service_password' => 'required|string', // PPPoE/Service Password
            'email' => 'nullable|email|unique:clients,email',
            'type' => 'required|in:pppoe,hotspot',
            'mikrotik_server_id' => 'nullable|exists:mikrotik_servers,id',
            'package_id' => 'nullable|exists:packages,id',
            'tower_id' => 'nullable|exists:towers,id',
            'price' => 'nullable|numeric',
            'pppoe_username' => 'nullable|string|max:255',
            'hotspot_username' => 'nullable|string|max:255',
            'ssid' => 'nullable|string|max:255',
            // Connection Details
            'connection_mode' => 'nullable|in:wireless,cable',
            'cpe_model' => 'nullable|string|max:255',
            'switch_port' => 'nullable|string',
            'cpe_ip' => 'nullable|ip',
            'cpe_mac' => 'nullable|string',
            'tower_device_id' => 'nullable|integer',
            // IP & Limits
            'ip_address' => 'nullable|ip',
            'data_limit' => 'nullable|numeric', // GB input
            'duration_days' => 'nullable|integer|min:1',
            // Map
            'lat' => 'nullable|numeric',
            'lng' => 'nullable|numeric',
        ]);

        $data = $validated;
        $data['username'] = $validated['phone']; 
        $data['password'] = \Illuminate\Support\Facades\Hash::make($validated['password']);
        
        // Map fields
        $data['ip'] = $validated['ip_address'] ?? null;
        if (isset($validated['price'])) {
            $data['custom_price'] = $validated['price'];
        }

        // Calculate Expiry
        if ($request->filled('duration_days')) {
            $data['expires_at'] = now()->addDays((int) $request->duration_days);
        } elseif ($request->filled('package_id')) {
            $package = \App\Models\Package::find($request->package_id);
            if ($package && $package->duration) {
                $data['expires_at'] = now()->addDays((int) $package->duration);
            }
        }

        // Data Limit: GB to Bytes (storing as custom_data_limit_mb or custom_data_limit_bytes depending on schema, usually MB in these systems but let's see fillable)
        // Checked: Client model has custom_data_limit_mb
        if ($request->filled('data_limit')) {
            $data['custom_data_limit_mb'] = $validated['data_limit'] * 1024;
        }

        $data['status'] = 'active';

        $client = Client::create($data);

        // SYNC WITH MIKROTIK
        if ($client->mikrotik_server_id) {
            try {
                $server = \App\Models\MikroTikServer::find($client->mikrotik_server_id);
                $service = new \App\Services\MikroTikService($server);
                
                if ($client->type === 'pppoe') {
                     $username = $client->pppoe_username ?? $client->username;
                     $service->createPPPoEUser($username, $request->service_password, optional($client->package)->name ?? 'default');
                } elseif ($client->type === 'hotspot') {
                     $service->createHotspotUser($client->username, $request->service_password, $client->package->name ?? 'default');
                }
            } catch (\Exception $e) {
                \Log::error("MikroTik Sync Failed: " . $e->getMessage());
                session()->flash('warning', 'فشلت المزامنة: ' . $e->getMessage());
            }
        }

        return redirect()->route('crm.clients.show', $client)
            ->with('success', 'تم إضافة العميل بنجاح');
    }

    public function show(Client $client)
    {
        $client->load(['package', 'router', 'invoices', 'clientNotes', 'activities']);

        $stats = [
            'total_invoices' => $client->invoices->count(),
            'paid_invoices' => $client->invoices->where('status', 'paid')->count(),
            'total_paid' => $client->invoices->where('status', 'paid')->sum('amount'),
            'pending_amount' => $client->invoices->where('status', 'unpaid')->sum('amount'),
        ];

        return view('crm.clients.show', compact('client', 'stats'));
    }

    public function edit(Client $client)
    {
        $packages = \App\Models\Package::all();
        $routers = \App\Models\Router::all();
        
        // Load with relationships
        $serversData = \App\Models\MikroTikServer::with(['towers.devices', 'towers.ssids', 'uplinkTowers.devices', 'uplinkTowers.ssids'])->get();
        
        // Manual mapping to ensure no data loss during serialization and include all towers
        $servers = $serversData->map(function($server) {
            // Combine administrative and uplink towers
            $allTowers = $server->towers->merge($server->uplinkTowers)->unique('id');
            
            return [
                'id' => $server->id,
                'name' => $server->name,
                'towers' => $allTowers->map(function($tower) {
                    return [
                        'id' => $tower->id,
                        'name' => $tower->name,
                        'lat' => (float)$tower->lat,
                        'lng' => (float)$tower->lng,
                        'devices' => $tower->devices->map(function($device) {
                            return [
                                'id' => $device->id,
                                'name' => $device->name,
                                'ip' => $device->ip,
                            ];
                        })->values()->all(),
                        'ssids' => $tower->ssids->map(function($ssid) {
                            return [
                                'id' => $ssid->id,
                                'ssid_name' => $ssid->ssid_name,
                                'tower_device_id' => $ssid->tower_device_id,
                            ];
                        })->values()->all(),
                    ];
                })->values()->all(),
            ];
        })->values()->all();

        $deviceModels = \App\Models\DeviceModel::all();

        return view('crm.clients.edit', compact('client', 'packages', 'routers', 'servers', 'deviceModels'));
    }

    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|unique:clients,username,' . $client->id, 
            'password' => 'nullable|string|min:6', 
            'service_password' => 'nullable|string', 
            'email' => 'nullable|email|unique:clients,email,' . $client->id,
            'type' => 'required|in:pppoe,hotspot',
            'mikrotik_server_id' => 'nullable|exists:mikrotik_servers,id',
            'package_id' => 'nullable|exists:packages,id',
            'tower_id' => 'nullable|exists:towers,id',
            'price' => 'nullable|numeric',
            'status' => 'required|in:active,inactive,suspended',
            'pppoe_username' => 'nullable|string|max:255',
            'hotspot_username' => 'nullable|string|max:255',
            'ssid_id' => 'nullable|exists:tower_ssids,id', 
            'expires_at' => 'nullable|date',
            // Connection Details
            'connection_mode' => 'nullable|in:wireless,cable',
            'cpe_model' => 'nullable|string|max:255',
            'switch_port' => 'nullable|string',
            'cpe_ip' => 'nullable|ip',
            'cpe_mac' => 'nullable|string',
            'tower_device_id' => 'nullable|exists:tower_devices,id',
            // IP & Limits
            'ip_address' => 'nullable|ip',
            'data_limit' => 'nullable|numeric',
            // Map
            'lat' => 'nullable|numeric',
            'lng' => 'nullable|numeric',
        ]);

        $data = $validated;
        $data['username'] = $validated['phone'];
        $data['ip'] = $validated['ip_address'] ?? $client->ip;
        
        // Handle SSID mapping: we need to store ssid_id
        if (isset($validated['ssid_id'])) {
            $data['ssid_id'] = $validated['ssid_id'];
            // Also store ssid name for legacy compatibility if needed
            $ssidObj = \App\Models\TowerSSID::find($validated['ssid_id']);
            if ($ssidObj) {
                $data['ssid'] = $ssidObj->ssid_name;
            }
        }

        if (isset($validated['price'])) {
            $data['custom_price'] = $validated['price'];
        }

        if (isset($validated['data_limit'])) {
            $data['custom_data_limit_mb'] = $validated['data_limit'] * 1024;
        }

        if ($request->filled('password')) {
            $data['password'] = \Illuminate\Support\Facades\Hash::make($validated['password']);
        } else {
            unset($data['password']);
        }

        $client->update($data);

        return redirect()->route('crm.clients.show', $client)
            ->with('success', 'تم تعديل بيانات العميل بنجاح');
    }

    public function addNote(Client $client, Request $request)
    {
        $validated = $request->validate([
            'content' => 'required|string',
            'type' => 'required|in:general,technical,billing',
        ]);

        ClientNote::create([
            'client_id' => $client->id,
            'user_id' => auth()->id(),
            'content' => $validated['content'],
            'type' => $validated['type'],
        ]);

        ClientActivity::log($client, 'note_added', 'تمت إضافة ملاحظة جديدة');

        return redirect()->back()->with('success', 'تمت إضافة الملاحظة بنجاح');
    }

    public function createCampaign()
    {
        return view('crm.campaigns.create');
    }

    public function sendCampaign(Request $request)
    {
        $validated = $request->validate([
            'target_type' => 'required|in:all,active,expiring,inactive',
            'message' => 'required|string',
        ]);

        // Get target clients
        $query = Client::whereNotNull('phone');

        switch ($validated['target_type']) {
            case 'active':
                $query->where('status', 'active');
                break;
            case 'expiring':
                $query->where('status', 'active')
                      ->whereNotNull('expires_at')
                      ->whereBetween('expires_at', [now(), now()->addDays(7)]);
                break;
            case 'inactive':
                $query->where('status', 'inactive');
                break;
        }

        $clients = $query->get();

        if ($clients->isEmpty()) {
            return redirect()->back()->with('error', 'لا يوجد عملاء مستهدفين');
        }

        // Send messages
        $notificationService = app(\App\Services\NotificationService::class);
        $sent = 0;
        $failed = 0;

        foreach ($clients as $client) {
            $personalizedMessage = str_replace(
                ['{{name}}', '{{username}}', '{{package}}'],
                [$client->name ?? $client->username, $client->username, $client->package->name ?? 'N/A'],
                $validated['message']
            );

            try {
                $notificationService->sendWhatsApp($client->phone, $personalizedMessage);
                $sent++;
            } catch (\Exception $e) {
                $failed++;
            }
        }

        return redirect()->route('crm.clients.index')
            ->with('success', "تم إرسال {$sent} رسالة بنجاح" . ($failed > 0 ? " و {$failed} فشلت" : ""));
    }

    public function updatePassword(Request $request, Client $client)
    {
        $request->validate([
            'password' => 'required|string|min:6',
        ]);

        $client->update([
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
        ]);

        return back()->with('success', 'تم تحديث كلمة المرور بنجاح');
    }

    public function sendCredentials(Request $request, Client $client)
    {
        if (!$client->phone) {
            return back()->with('error', 'لا يوجد رقم هاتف لهذا العميل');
        }

        $tenant = auth()->user()->tenant;
        if (!$tenant || !$tenant->is_subdomain_enabled || !$tenant->domain) {
             return back()->with('error', 'بوابة العملاء غير مفعلة');
        }
        
        $appHost = parse_url(config('app.url'), PHP_URL_HOST);
        $appHost = str_replace('www.', '', $appHost);
        $portalUrl = str_contains($tenant->domain, '.') ? $tenant->domain : $tenant->domain . '.' . $appHost;
        $portalUrl = 'http://' . $portalUrl;

        $message = "مرحباً {$client->name},\n\n";
        $message .= "بيانات الدخول الخاصة بك إلى بوابة العملاء:\n";
        $message .= "الرابط: {$portalUrl}\n";
        $message .= "اسم المستخدم: {$client->username}\n";
        $message .= "\nشكراً لاشتراكك معنا.";

        $notificationService = app(\App\Services\NotificationService::class);
        try {
            $notificationService->sendWhatsApp($client->phone, $message);
            return back()->with('success', 'تم إرسال بيانات الدخول عبر واتساب');
        } catch (\Exception $e) {
             return back()->with('error', 'فشل الإرسال: ' . $e->getMessage());
        }
    }

    public function renewForm(Client $client)
    {
        $packages = \App\Models\Package::all();
        return view('crm.clients.renew', compact('client', 'packages'));
    }

    public function renew(Request $request, Client $client)
    {
        $validated = $request->validate([
            'renew_mode' => 'required|in:extend,reset',
            'duration_days' => 'nullable|integer|min:1',
            'data_limit' => 'nullable|numeric|min:0', // GB
            'price' => 'nullable|numeric|min:0',
        ]);

        $updates = [];
        $logMessage = '';

        // Handle Duration
        if ($request->filled('duration_days')) {
            $days = (int) $validated['duration_days'];
            if ($validated['renew_mode'] === 'reset') {
                $updates['expires_at'] = now()->addDays($days);
                $logMessage .= "Renewal (Reset): Set expiry to " . $updates['expires_at']->format('Y-m-d') . ". ";
            } else {
                // If already expired, start from now. If active, add to current expiry.
                $baseDate = $client->expires_at && $client->expires_at > now() ? clone $client->expires_at : now();
                $updates['expires_at'] = $baseDate->addDays($days);
                $logMessage .= "Renewal (Extend): Extended expiry to " . $updates['expires_at']->format('Y-m-d') . ". ";
            }
        }

        // Handle Data Limit
        if ($request->filled('data_limit')) {
            $limitGb = (float) $validated['data_limit'];
            $limitBytes = $limitGb * 1024 * 1024 * 1024;
            if ($validated['renew_mode'] === 'reset') {
                $updates['data_limit'] = $limitBytes;
                // Ideally also reset usage counters if stored locally
                 $logMessage .= "Data Limit: Reset to {$validated['data_limit']} GB. ";
            } else {
                $currentLimit = $client->data_limit ?? 0;
                $updates['data_limit'] = $currentLimit + $limitBytes;
                 $logMessage .= "Data Limit: Added {$validated['data_limit']} GB. ";
            }
        }

        // Create Invoice if price provided
        if ($request->filled('price')) {
             \App\Models\Invoice::create([
                'tenant_id' => auth()->user()->tenant_id,
                'client_id' => $client->id,
                'invoice_number' => 'INV-' . strtoupper(uniqid()),
                'amount' => $validated['price'],
                'status' => 'paid', // Assuming immediate payment for renewal
                'due_date' => now(),
                'paid_at' => now(),
                'description' => 'Tجديد اشتراك: ' . $logMessage,
            ]);
        }

        if (!empty($updates)) {
            $updates['status'] = 'active'; // Reactivate if suspended
            $client->update($updates);
            
            // Sync with MikroTik (re-enable user, update limits)
            if ($client->mikrotik_server_id) {
                try {
                     $server = $client->mikrotikServer ?? \App\Models\MikroTikServer::find($client->mikrotik_server_id);
                     if ($server) {
                        $service = new \App\Services\MikroTikService($server);
                        if ($client->type === 'pppoe') {
                            $service->enableUser($client->username);
                            // TODO: Update specific limits if MikroTik API supports set-limit separately
                        } elseif ($client->type === 'hotspot') {
                            $service->enableHotspotUser($client->username);
                        }
                     }
                } catch (\Exception $e) {
                    \Log::error("MikroTik Renewal Sync Failed: " . $e->getMessage());
                    session()->flash('warning', 'تم التجديد محلياً ولكن فشلت المزامنة مع المايكروتك');
                }
            }
        }

        ClientActivity::log($client, 'renew', $logMessage);

        return redirect()->route('crm.clients.show', $client)
            ->with('success', 'تم تجديد الاشتراك بنجاح');
    }

    public function toggleStatus(Request $request, Client $client)
    {
        $newStatus = $client->status === 'active' ? 'suspended' : 'active';
        $client->update(['status' => $newStatus]);
        
        $action = $newStatus === 'active' ? 'activated' : 'suspended';
        $logMessage = $newStatus === 'active' ? 'تم تفعيل الحساب' : 'تم تعطيل الحساب';

        // Sync with MikroTik
        if ($client->mikrotik_server_id) {
            try {
                $server = $client->mikrotikServer ?? \App\Models\MikroTikServer::find($client->mikrotik_server_id);
                if ($server) {
                    $service = new \App\Services\MikroTikService($server);
                    if ($client->type === 'pppoe') {
                        if ($newStatus === 'active') {
                            $service->enableUser($client->username);
                        } else {
                            $service->disableUser($client->username);
                            // Also kick active session
                            $service->kickUser($client->username); 
                        }
                    } elseif ($client->type === 'hotspot') {
                        if ($newStatus === 'active') {
                            $service->enableHotspotUser($client->username);
                        } else {
                            $service->disableHotspotUser($client->username);
                            $service->kickHotspotUser($client->username); 
                        }
                    }
                }
            } catch (\Exception $e) {
                \Log::error("MikroTik Status Sync Failed: " . $e->getMessage());
                session()->flash('warning', 'تم تغيير الحالة محلياً ولكن فشلت المزامنة مع المايكروتك');
            }
        }

        ClientActivity::log($client, 'status_changed', "Status changed to $newStatus");

        return back()->with('success', $logMessage);
    }
}
        

