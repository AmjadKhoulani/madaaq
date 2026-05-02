<?php

namespace App\Http\Controllers;

use App\Models\Router;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RouterManagementController extends Controller
{
    private $serverDomain = 'your-domain.com'; // غيّر للدومين الخاص بك
    private $wireguardPort = 51820;
    private $wireguardPublicKey = ''; // سيتم توليده
    private $wireguardNetwork = '10.10.0.0/16';

    public function index()
    {
        $routers = Router::with('tower')->latest()->get();
        return view('router-management.index', compact('routers'));
    }

    public function generateScript(Router $router)
    {
        // Generate unique credentials
        $username = strtoupper(Str::random(8));
        $password = Str::random(8);
        $routerIp = $this->getNextRouterIp();
        
        // Store credentials
        $router->update([
            'api_username' => $username,
            'api_password' => $password,
            'vpn_ip' => $routerIp,
        ]);

        $script = $this->buildMikroTikScript($router, $username, $password, $routerIp);

        return view('router-management.script', compact('router', 'script'));
    }

    private function buildMikroTikScript($router, $username, $password, $routerIp)
    {
        $serverDomain = $this->serverDomain;
        $serverPublicKey = env('WIREGUARD_PUBLIC_KEY', 'YOUR_SERVER_PUBLIC_KEY');
        $routerId = $router->id;

        return <<<SCRIPT
# ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
#  سكريبت ربط تلقائي - نظام إدارة ISP
#  Router: {$router->name}
#  Generated: {now()->format('Y-m-d H:i:s')}
# ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

# 1. إعداد WireGuard VPN
/interface wireguard
add listen-port={$this->wireguardPort} mtu=1420 name=isp-vpn

/interface wireguard peers
add allowed-address={$this->wireguardNetwork} \\
    endpoint-address={$serverDomain} \\
    endpoint-port={$this->wireguardPort} \\
    interface=isp-vpn \\
    name=isp-server \\
    persistent-keepalive=25s \\
    public-key="{$serverPublicKey}"

# 2. إعداد IP Address
/ip address
add address={$routerIp}/16 interface=isp-vpn network={$this->wireguardNetwork}

# 3. تفعيل API
/ip service
set api disabled=no port=8728

# 4. إنشاء مجموعة المستخدمين
/user group
add name=isp-manager policy=ftp,read,write,test,api

# 5. حذف المستخدم القديم (إن وجد)
/user remove [/user find comment="isp-auto"]

# 6. إنشاء مستخدم API
/user
add name={$username} \\
    password={$password} \\
    address=10.10.0.1 \\
    comment=isp-auto \\
    group=isp-manager

# 7. إزالة المهمة المجدولة القديمة
/system scheduler remove [find name=isp-sync]

# 8. إنشاء مهمة المزامنة التلقائية (كل 15 ثانية)
/system scheduler
add interval=15s \\
    name=isp-sync \\
    on-event="/system script run isp-sync-script" \\
    start-time=startup

# 9. إزالة السكريبت القديم
/system script remove [find name=isp-sync-script]

# 10. إنشاء سكريبت المزامنة
/system script
add name=isp-sync-script source="
:log info \"ISP Auto-Sync: Starting sync...\"
/tool fetch http-method=post \\
    keep-result=yes \\
    url=https://{$serverDomain}/api/routers/{$routerId}/sync
:delay 2s
:if ([/file find name=router-sync.rsc] != \"\") do={
    :log info \"ISP Auto-Sync: Applying configuration...\"
    /import router-sync.rsc
    /file remove router-sync.rsc
    :log info \"ISP Auto-Sync: Sync completed successfully\"
} else={
    :log warning \"ISP Auto-Sync: No sync file received\"
}
"

# ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
# ✅ تم! الراوتر الآن متصل بالمنصة
# 
# معلومات الاتصال:
# - Router ID: {$routerId}
# - VPN IP: {$routerIp}
# - API User: {$username}
# - API Password: {$password}
# 
# سيتم المزامنة تلقائياً كل 15 ثانية
# ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
SCRIPT;
    }

    public function syncRouter(Router $router)
    {
        // Generate sync script with current users/packages
        $syncScript = $this->generateSyncScript($router);
        
        return response($syncScript)
            ->header('Content-Type', 'text/plain')
            ->header('Content-Disposition', 'attachment; filename="router-sync.rsc"');
    }

    private function generateSyncScript($router)
    {
        $script = "; ISP Auto-Sync Script\n";
        $script .= "; Generated: " . now()->format('Y-m-d H:i:s') . "\n";
        $script .= "; Router: {$router->name}\n\n";

        // Sync PPPoE Users
        $pppoeClients = Client::where('type', 'pppoe')
            ->where('status', 'active')
            ->where('router_id', $router->id)
            ->get();

        if ($pppoeClients->count() > 0) {
            $script .= "# PPPoE Users Sync\n";
            foreach ($pppoeClients as $client) {
                $profile = $client->package ? $client->package->name : 'default';
                $script .= ":if ([:len [/ppp secret find name=\"{$client->username}\"]] = 0) do={ ";
                $script .= "/ppp secret add name=\"{$client->username}\" password=\"{$client->password}\" profile=\"{$profile}\" comment=\"Auto-synced\" ";
                $script .= "} else={ ";
                $script .= "/ppp secret set [find name=\"{$client->username}\"] password=\"{$client->password}\" profile=\"{$profile}\" ";
                $script .= "}\n";
            }
            $script .= "\n";
        } else {
            $script .= "# No PPPoE users assigned to this router\n\n";
        }

        // Sync Hotspot Users
        $hotspotClients = Client::where('type', 'hotspot')
            ->where('status', 'active')
            ->where('router_id', $router->id)
            ->get();

        if ($hotspotClients->count() > 0) {
            $script .= "# Hotspot Users Sync\n";
            foreach ($hotspotClients as $client) {
                $profile = $client->package ? $client->package->name : 'default';
                $script .= ":if ([:len [/ip hotspot user find name=\"{$client->username}\"]] = 0) do={ ";
                $script .= "/ip hotspot user add name=\"{$client->username}\" password=\"{$client->password}\" profile=\"{$profile}\" comment=\"Auto-synced\" ";
                $script .= "} else={ ";
                $script .= "/ip hotspot user set [find name=\"{$client->username}\"] password=\"{$client->password}\" profile=\"{$profile}\" ";
                $script .= "}\n";
            }
            $script .= "\n";
        } else {
            $script .= "# No Hotspot users assigned to this router\n\n";
        }

        // Log - always include this
        $script .= ":log info \"ISP Auto-Sync: {$pppoeClients->count()} PPPoE + {$hotspotClients->count()} Hotspot users synced\"\n";

        return $script;
    }

    private function getNextRouterIp()
    {
        // Get last router IP and increment
        $lastRouter = Router::whereNotNull('vpn_ip')
            ->orderBy('vpn_ip', 'desc')
            ->first();

        if (!$lastRouter) {
            return '10.10.1.1';
        }

        // Increment IP
        $parts = explode('.', $lastRouter->vpn_ip);
        $parts[3]++;
        
        if ($parts[3] > 254) {
            $parts[3] = 1;
            $parts[2]++;
        }

        return implode('.', $parts);
    }
}
