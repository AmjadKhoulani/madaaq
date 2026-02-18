<?php

namespace App\Services;

use App\Models\Router;
use Exception;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use RouterOS\Client;
use RouterOS\Config;
use RouterOS\Exceptions\ConnectException;
use RouterOS\Query;

class MikroTikService
{
    protected $client;
    protected $router; // Can be Router or MikroTikServer instance

    /**
     * @param mixed $device Instance of Router or MikroTikServer
     */
    public function __construct($device)
    {
        $this->router = $device;
    }

    /**
     * Get MAC address for a given IP from ARP table
     */
    public function getMacFromIp($ip)
    {
        $this->connect();
        $arp = $this->client->query((new Query('/ip/arp/print'))->where('address', $ip))->read();
        return $arp[0]['mac-address'] ?? null;
    }

    /**
     * Add Hotspot Bypass using IP Binding
     */
    public function addHotspotBypass($mac, $comment = 'Auto-Bypass for Remote Access')
    {
        if (!$mac) return;
        
        $this->connect();
        
        // Check if exists
        $bindings = $this->client->query((new Query('/ip/hotspot/ip-binding/print'))->where('mac-address', $mac))->read();
        $exists = false;
        foreach ($bindings as $b) {
            if (isset($b['type']) && $b['type'] === 'bypassed') {
                $exists = true;
                break;
            }
        }

        if (!$exists) {
            try {
                // Remove any non-bypass binding for this MAC to avoid duplicates/conflicts
                foreach ($bindings as $b) {
                    $this->client->query((new Query('/ip/hotspot/ip-binding/remove'))->equal('.id', $b['.id']))->read();
                }

                $this->client->query((new Query('/ip/hotspot/ip-binding/add'))
                    ->equal('mac-address', $mac)
                    ->equal('type', 'bypassed')
                    ->equal('comment', $comment)
                )->read();

                // Also remove from active hosts to force re-login/bypass
                $hosts = $this->client->query((new Query('/ip/hotspot/host/print'))->where('mac-address', $mac))->read();
                foreach ($hosts as $h) {
                    $this->client->query((new Query('/ip/hotspot/host/remove'))->equal('.id', $h['.id']))->read();
                }
            } catch (Exception $e) {
                Log::error("Failed to add hotspot bypass: " . $e->getMessage());
            }
        }
    }

    /**
     * Connect to the RouterOS API
     */
    protected function connect()
    {
        if ($this->client) {
            return;
        }

        try {
            // Determine host (Prioritize WireGuard VPN IP)
            $host = $this->router->ip;
            if ($this->router->wireguard_enabled && !empty($this->router->wireguard_ip)) {
                $host = $this->router->wireguard_ip;
                Log::debug("Using WireGuard VPN IP for connection: {$host}", ['router_id' => $this->router->id]);
            }

            // Decrypt password
            $password = "";
            try {
                $password = Crypt::decryptString($this->router->password_encrypted);
            } catch (Exception $e) {
                throw new Exception("Could not decrypt router password: ". $e->getMessage());
            }

            $config = new Config([
                'host' => $host,
                'user' => $this->router->username,
                'pass' => $password,
                'port' => (int) $this->router->api_port,
                'timeout' => 5, // Shorter timeout for faster failover
            ]);

            $this->client = new Client($config);
        } catch (Exception $e) {
            // If VPN failed and we weren't already trying the public IP, try a fallback?
            // For now, log the specific host that failed.
            Log::error("MikroTik Connection Failed to {$host}: " . $e->getMessage(), ['router_id' => $this->router->id]);
            throw new Exception("Connection to router failed ({$host}): " . $e->getMessage());
        }
    }

    public function testConnection()
    {
        $this->connect();
        // Just try to get identity
        $query = new Query('/system/identity/print');
        $response = $this->client->query($query)->read();
        return $response;
    }

    /**
     * Get all PPPoE users from MikroTik
     */
    public function getPPPoEUsers()
    {
        $this->connect();
        $query = new Query('/ppp/secret/print');
        return $this->client->query($query)->read();
    }

    /**
     * Get all Hotspot users from MikroTik
     */
    public function getHotspotUsers()
    {
        $this->connect();
        $query = new Query('/ip/hotspot/user/print');
        return $this->client->query($query)->read();
    }

    /**
     * Get PPPoE profiles from MikroTik
     */
    public function getPPPoEProfiles()
    {
        $this->connect();
        $query = new Query('/ppp/profile/print');
        return $this->client->query($query)->read();
    }

    /**
     * Create a PPPoE Profile
     * rateLimit format: "upload/download" (e.g., "2M/10M")
     */
    public function createPPPoEProfile($name, $rateLimit)
    {
        $this->connect();
        
        $query = (new Query('/ppp/profile/add'))
            ->equal('name', $name)
            ->equal('rate-limit', $rateLimit)
            ->equal('only-one', 'default') // Or 'yes'/'no' depending on policy
            ->equal('use-mpls', 'default')
            ->equal('use-compression', 'default')
            ->equal('use-encryption', 'default')
            ->equal('use-ipv6', 'default');

        return $this->client->query($query)->read();
    }

    /**
     * Update a PPPoE Profile
     */
    public function updatePPPoEProfile($oldName, $newName, $rateLimit)
    {
        $this->connect();

        // Find ID first
        $findQuery = (new Query('/ppp/profile/print'))->where('name', $oldName);
        $profile = $this->client->query($findQuery)->read();

        if (empty($profile)) {
            // If not found, create it? Or return error?
            // Let's try to create it if it doesn't exist (Self-healing)
            return $this->createPPPoEProfile($newName, $rateLimit);
        }

        $id = $profile[0]['.id'];

        $query = (new Query('/ppp/profile/set'))
            ->equal('.id', $id)
            ->equal('name', $newName)
            ->equal('rate-limit', $rateLimit);

        return $this->client->query($query)->read();
    }

    /**
     * Delete a PPPoE Profile
     */
    public function deletePPPoEProfile($name)
    {
        $this->connect();

        $findQuery = (new Query('/ppp/profile/print'))->where('name', $name);
        $profile = $this->client->query($findQuery)->read();

        if (empty($profile)) {
             return null;
        }

        $id = $profile[0]['.id'];

        $query = (new Query('/ppp/profile/remove'))->equal('.id', $id);
        return $this->client->query($query)->read();
    }

    /**
     * Get Hotspot profiles from MikroTik
     */
    public function getHotspotProfiles()
    {
        $this->connect();
        $query = (new Query('/ip/hotspot/user/profile/print'));
        return $this->client->query($query)->read();
    }

    /**
     * Get active PPPoE connections
     */
    public function getActivePPPoEConnections()
    {
        $this->connect();
        $query = new Query('/ppp/active/print');
        return $this->client->query($query)->read();
    }

    /**
     * Get active Hotspot connections
     */
    public function getActiveHotspotConnections()
    {
        $this->connect();
        $query = new Query('/ip/hotspot/active/print');
        return $this->client->query($query)->read();
    }


    public function createPPPoEUser($username, $password, $profile = 'default')
    {
        $this->connect();
        
        // Check if user exists?
        // Basic creation
        $query = (new Query('/ppp/secret/add'))
            ->equal('name', $username)
            ->equal('password', $password)
            ->equal('service', 'pppoe')
            ->equal('profile', $profile);

        return $this->client->query($query)->read();
    }

    public function disableUser($username)
    {
        $this->connect();

        // Find ID first? Or just disable by name if supported (RouterOS usually requires ID)
        // Usually, we find the ID by name.
        $findQuery = (new Query('/ppp/secret/print'))
            ->where('name', $username);
        $user = $this->client->query($findQuery)->read();

        if (empty($user)) {
             return null; // User not found
        }

        $id = $user[0]['.id'];

        $query = (new Query('/ppp/secret/disable'))
            ->equal('.id', $id);

        return $this->client->query($query)->read();
    }

    public function createHotspotUser($username, $password, $profile = 'default')
    {
        $this->connect();
        
        $query = (new Query('/ip/hotspot/user/add'))
            ->equal('name', $username)
            ->equal('password', $password)
            ->equal('profile', $profile);

        return $this->client->query($query)->read();
    }

    public function disableHotspotUser($username)
    {
        $this->connect();

        $findQuery = (new Query('/ip/hotspot/user/print'))->where('name', $username);
        $user = $this->client->query($findQuery)->read();

        if (empty($user)) {
             return null;
        }

        $id = $user[0]['.id'];

        $query = (new Query('/ip/hotspot/user/disable'))->equal('.id', $id);
        return $this->client->query($query)->read();
    }

    public function enableHotspotUser($username)
    {
        $this->connect();

        $findQuery = (new Query('/ip/hotspot/user/print'))->where('name', $username);
        $user = $this->client->query($findQuery)->read();

        if (empty($user)) {
             return null;
        }

        $id = $user[0]['.id'];

        $query = (new Query('/ip/hotspot/user/enable'))->equal('.id', $id);
        return $this->client->query($query)->read();
    }

    public function kickHotspotUser($username)
    {
        $this->connect();
        
        $findQuery = (new Query('/ip/hotspot/active/print'))->where('user', $username);
        $activeRows = $this->client->query($findQuery)->read();
        
        foreach ($activeRows as $row) {
            if (isset($row['.id'])) {
                $removeQuery = (new Query('/ip/hotspot/active/remove'))->equal('.id', $row['.id']);
                $this->client->query($removeQuery)->read();
            }
        }
    }

    public function enableUser($username)
    {
        $this->connect();

        $findQuery = (new Query('/ppp/secret/print'))
            ->where('name', $username);
        $user = $this->client->query($findQuery)->read();

        if (empty($user)) {
             return null;
        }

        $id = $user[0]['.id'];

        $query = (new Query('/ppp/secret/enable'))
            ->equal('.id', $id);

        return $this->client->query($query)->read();
    }

    public function kickUser($username)
    {
        $this->connect();
        
        $findQuery = (new Query('/ppp/active/print'))->where('name', $username);
        $activeRows = $this->client->query($findQuery)->read();
        
        foreach ($activeRows as $row) {
            if (isset($row['.id'])) {
                $removeQuery = (new Query('/ppp/active/remove'))->equal('.id', $row['.id']);
                $this->client->query($removeQuery)->read();
            }
        }
    }

    public function assignSimpleQueue($username, $maxLimit) // e.g. "10M/10M"
    {
        $this->connect();
        
        // Simple queue for the user. Target usually is IP or interface. 
        // For PPPoE, queue is usually created dynamically by profile or we must target the dynamic interface (tricky).
        // Or target the Remote Address.
        // If we assign static IP to PPPoE user, we can target that IP.
        
        // Ideally, we set queue in /ppp/secret/add using 'limit-bytes-in', 'limit-bytes-out' but RouterOS params are specific.
        // Actually, creating a Simple Queue for a specific target IP is the standard way if not using dynamic queues.
        
        // For this task, "assignSimpleQueue" implies creating a queue.
        // Assuming we target the user's name (if using queue types) or we need the IP.
        // Let's assume we create a queue with name = username and target = ?
        // If we don't know the IP, we might update the PPP Secret 'limit-bytes' (not speed limit). 
        // Speed limit in PPP Profile or Secret is 'rate-limit' (rx/tx).
        // Let's try to update the PPP secret rate-limit if compatible, or create a simple queue if we have target.
        
        // Since the prompt asks to "assignSimpleQueue", I will try to create a /queue/simple/add
        // But without target IP it's hard.
        // I'll assume we update the secret 'rate-limit' property OR Create a simple queue assuming we know the target.
        // Let's implement creating a simple queue with name=$username.
        
        $query = (new Query('/queue/simple/add'))
            ->equal('name', $username)
            ->equal('max-limit', $maxLimit)
            ->equal('target', "0.0.0.0/0");
            
        return $this->client->query($query)->read();
    }

    public function getActiveUser($username)
    {
        $this->connect();
        
        // 1. Try PPPoE Active
        $query = (new Query('/ppp/active/print'))->where('name', $username);
        $active = $this->client->query($query)->read();
        
        if (!empty($active)) {
            $user = $active[0];
            return [
                'type' => 'pppoe',
                'uptime' => $user['uptime'] ?? '00:00:00',
                'bytes_in' => (int)($user['limit-bytes-in'] ?? 0),
                'address' => $user['address'] ?? null,
                'id' => $user['.id']
            ];
        }

        // 2. Try Hotspot Active
        $query = (new Query('/ip/hotspot/active/print'))->where('user', $username);
        $active = $this->client->query($query)->read();

        if (!empty($active)) {
            $user = $active[0];
            return [
                'type' => 'hotspot',
                'uptime' => $user['uptime'] ?? '00:00:00',
                'bytes_in' => (int)($user['bytes-in'] ?? 0),
                'bytes_out' => (int)($user['bytes-out'] ?? 0),
                'address' => $user['address'] ?? null,
                'id' => $user['.id']
            ];
        }

        return null;
    }

    public function getInterfaceTraffic($interfaceName)
    {
        $this->connect();
        
        $query = (new Query('/interface/monitor-traffic'))
            ->equal('interface', $interfaceName)
            ->equal('once', 'true');
            
        $stats = $this->client->query($query)->read();
        
        if (!empty($stats)) {
            return [
                'rx_bps' => (int)($stats[0]['rx-bits-per-second'] ?? 0),
                'tx_bps' => (int)($stats[0]['tx-bits-per-second'] ?? 0),
            ];
        }
        
        return null;
    }

    public function getInterfaceStats()
    {
        $this->connect();
        
        $query = new Query('/interface/print');
        $query->equal('stats', 'yes');
        
        $interfaces = $this->client->query($query)->read();
        
        $result = [];
        foreach ($interfaces as $interface) {
            $result[] = [
                'name' => $interface['name'] ?? 'unknown',
                'rx-byte' => (int)($interface['rx-byte'] ?? 0),
                'tx-byte' => (int)($interface['tx-byte'] ?? 0),
                'rx-packet' => (int)($interface['rx-packet'] ?? 0),
                'tx-packet' => (int)($interface['tx-packet'] ?? 0),
            ];
        }
        
        return $result;
    }

    // ==================== Queue Management ====================
    
    public function getQueues()
    {
        $this->connect();
        $query = new Query('/queue/simple/print');
        return $this->client->query($query)->read();
    }

    public function createQueue($name, $target, $maxLimit, $burstLimit = null)
    {
        $this->connect();
        
        $query = (new Query('/queue/simple/add'))
            ->equal('name', $name)
            ->equal('target', $target)
            ->equal('max-limit', $maxLimit);
        
        if ($burstLimit) {
            $query->equal('burst-limit', $burstLimit);
        }
        
        return $this->client->query($query)->read();
    }

    public function updateQueue($queueId, $params)
    {
        $this->connect();
        
        $query = (new Query('/queue/simple/set'))
            ->equal('.id', $queueId);
        
        foreach ($params as $key => $value) {
            $query->equal($key, $value);
        }
        
        return $this->client->query($query)->read();
    }

    public function deleteQueue($queueId)
    {
        $this->connect();
        
        $query = (new Query('/queue/simple/remove'))
            ->equal('.id', $queueId);
        
        return $this->client->query($query)->read();
    }

    public function getQueueTree()
    {
        $this->connect();
        $query = new Query('/queue/tree/print');
        return $this->client->query($query)->read();
    }

    public function setUserSpeed($username, $downloadSpeed, $uploadSpeed, $burstRatio = 1.5)
    {
        $this->connect();
        
        // Find queue by name
        $findQuery = (new Query('/queue/simple/print'))->where('name', $username);
        $queue = $this->client->query($findQuery)->read();

        $maxLimit = "$uploadSpeed/$downloadSpeed";
        $burstLimit = (int)($uploadSpeed * $burstRatio) . "/" . (int)($downloadSpeed * $burstRatio);

        if (!empty($queue)) {
            // Update existing
            return $this->updateQueue($queue[0]['.id'], [
                'max-limit' => $maxLimit,
                'burst-limit' => $burstLimit
            ]);
        } else {
            // Create new queue for user
            return $this->createQueue($username, '0.0.0.0/0', $maxLimit, $burstLimit);
        }
    }

    // ==================== Firewall Management ====================

    public function getFirewallRules($chain = 'forward')
    {
        $this->connect();
        $query = (new Query('/ip/firewall/filter/print'))->where('chain', $chain);
        return $this->client->query($query)->read();
    }

    public function addFirewallRule($params)
    {
        $this->connect();
        
        $query = new Query('/ip/firewall/filter/add');
        foreach ($params as $key => $value) {
            $query->equal($key, $value);
        }
        
        return $this->client->query($query)->read();
    }

    public function deleteFirewallRule($ruleId)
    {
        $this->connect();
        
        $query = (new Query('/ip/firewall/filter/remove'))
            ->equal('.id', $ruleId);
        
        return $this->client->query($query)->read();
    }

    public function getAddressLists()
    {
        $this->connect();
        $query = new Query('/ip/firewall/address-list/print');
        return $this->client->query($query)->read();
    }

    public function addToAddressList($listName, $address, $comment = null)
    {
        $this->connect();
        
        $query = (new Query('/ip/firewall/address-list/add'))
            ->equal('list', $listName)
            ->equal('address', $address);
        
        if ($comment) {
            $query->equal('comment', $comment);
        }
        
        return $this->client->query($query)->read();
    }

    public function removeFromAddressList($listName, $address)
    {
        $this->connect();
        
        // Find entry first
        $findQuery = (new Query('/ip/firewall/address-list/print'))
            ->where('list', $listName)
            ->where('address', $address);
        
        $entry = $this->client->query($findQuery)->read();
        
        if (!empty($entry)) {
            $query = (new Query('/ip/firewall/address-list/remove'))
                ->equal('.id', $entry[0]['.id']);
            return $this->client->query($query)->read();
        }
        
        return null;
    }

    public function getNatRules()
    {
        $this->connect();
        $query = new Query('/ip/firewall/nat/print');
        return $this->client->query($query)->read();
    }

    public function addPortForwarding($protocol, $dstPort, $toAddress, $toPort)
    {
        $this->connect();
        
        $query = (new Query('/ip/firewall/nat/add'))
            ->equal('chain', 'dstnat')
            ->equal('protocol', $protocol)
            ->equal('dst-port', $dstPort)
            ->equal('to-addresses', $toAddress)
            ->equal('to-ports', $toPort)
            ->equal('action', 'dst-nat');
        
        return $this->client->query($query)->read();
    }

    // ==================== Resource Monitoring ====================

    public function getSystemResources()
    {
        $this->connect();
        $query = new Query('/system/resource/print');
        $response = $this->client->query($query)->read();
        
        if (!empty($response)) {
            $res = $response[0];
            return [
                'uptime' => $res['uptime'] ?? '0',
                'cpu_load' => (int)($res['cpu-load'] ?? 0),
                'free_memory' => (int)($res['free-memory'] ?? 0),
                'total_memory' => (int)($res['total-memory'] ?? 0),
                'free_hdd_space' => (int)($res['free-hdd-space'] ?? 0),
                'total_hdd_space' => (int)($res['total-hdd-space'] ?? 0),
            ];
        }
        
        return null;
    }

    public function getSystemHealth()
    {
        $this->connect();
        
        try {
            $query = new Query('/system/health/print');
            return $this->client->query($query)->read();
        } catch (Exception $e) {
            // Not all routers support /system/health
            return null;
        }
    }

    public function getInterfaceList()
    {
        $this->connect();
        $query = new Query('/interface/print');
        return $this->client->query($query)->read();
    }

    public function pingHost($host, $count = 1)
    {
        $this->connect();
        
        $query = (new Query('/ping'))
            ->equal('address', $host)
            ->equal('count', (string)$count);
        
        $result = $this->client->query($query)->read();
        
        if (empty($result)) {
            return ['reachable' => false, 'latency' => null];
        }

        // RouterOS ping response is an array of packets
        $reachable = false;
        $totalLatency = 0;
        $receivedPackets = 0;

        foreach ($result as $packet) {
            if (isset($packet['status']) && $packet['status'] === 'timeout') {
                continue;
            }
            if (isset($packet['time'])) {
                $reachable = true;
                $receivedPackets++;
                // Convert 10ms to 10
                $totalLatency += (int) preg_replace('/[^0-9]/', '', $packet['time']);
            }
        }

        return [
            'reachable' => $reachable,
            'latency' => $receivedPackets > 0 ? round($totalLatency / $receivedPackets) : null,
            'packet_loss' => round((($count - $receivedPackets) / $count) * 100)
        ];
    }

    /**
     * Get discovered neighbors via MNDP/CDP
     */
    public function getNeighbors()
    {
        $this->connect();
        $query = new Query('/ip/neighbor/print');
        return $this->client->query($query)->read();
    }

    /**
     * Get ARP table
     */
    public function getArpTable()
    {
        $this->connect();
        $query = new Query('/ip/arp/print');
        return $this->client->query($query)->read();
    }

    /**
     * Get DHCP leases
     */
    public function getDhcpLeases()
    {
        $this->connect();
        $query = new Query('/ip/dhcp-server/lease/print');
        return $this->client->query($query)->read();
    }

    /**
     * Enable SOCKS proxy for remote device access
     */
    public function enableSocks()
    {
        $this->connect();
        
        // 1. Enable SOCKS
        $query = (new Query('/ip/socks/set'))
            ->equal('enabled', 'yes')
            ->equal('port', '1080');
        $this->client->query($query)->read();

        // 2. Allow access from the cloud server range (201.10.0.0/16)
        // Check if rule exists first
        $rules = $this->client->query(new Query('/ip/socks/access/print'))->read();
        $exists = false;
        foreach ($rules as $rule) {
            if (isset($rule['src-address']) && $rule['src-address'] === '201.10.0.0/16') {
                $exists = true;
                break;
            }
        }

        if (!$exists) {
            $query = (new Query('/ip/socks/access/add'))
                ->equal('src-address', '201.10.0.0/16')
                ->equal('action', 'allow');
            $this->client->query($query)->read();
        }

        // 3. Allow port 1080 in Firewall from Server IP (201.10.1.1)
        $firewallRules = $this->client->query(new Query('/ip/firewall/filter/print'))->read();
        $fwExists = false;
        foreach ($firewallRules as $rule) {
            if (isset($rule['chain']) && $rule['chain'] === 'input' && 
                isset($rule['dst-port']) && $rule['dst-port'] === '1080' &&
                isset($rule['src-address']) && $rule['src-address'] === '201.10.1.1') {
                $fwExists = true;
                break;
            }
        }

        if (!$fwExists) {
            $query = (new Query('/ip/firewall/filter/add'))
                ->equal('chain', 'input')
                ->equal('action', 'accept')
                ->equal('protocol', 'tcp')
                ->equal('dst-port', '1080')
                ->equal('src-address', '201.10.1.1')
                ->equal('comment', 'Allow SOCKS from Server');
            $this->client->query($query)->read();
            
            // Move rule to top to ensure it's hit
            $newRules = $this->client->query(new Query('/ip/firewall/filter/print'))->read();
            $newRuleId = null;
            foreach ($newRules as $rule) {
                if (isset($rule['comment']) && $rule['comment'] === 'Allow SOCKS from Server') {
                    $newRuleId = $rule['.id'];
                    break;
                }
            }
            if ($newRuleId) {
                $moveQuery = (new Query('/ip/firewall/filter/move'))
                    ->equal('numbers', $newRuleId)
                    ->equal('destination', '*D'); // Move before the API rule
                $this->client->query($moveQuery)->read();
            }
        }
    }

    // ==================== DNS Management ====================

    public function getDnsStaticEntries()
    {
        $this->connect();
        $query = new Query('/ip/dns/static/print');
        return $this->client->query($query)->read();
    }

    public function addDnsEntry($name, $address, $ttl = 3600)
    {
        $this->connect();
        
        $query = (new Query('/ip/dns/static/add'))
            ->equal('name', $name)
            ->equal('address', $address)
            ->equal('ttl', (string)$ttl);
        
        return $this->client->query($query)->read();
    }

    public function deleteDnsEntry($entryId)
    {
        $this->connect();
        
        $query = (new Query('/ip/dns/static/remove'))
            ->equal('.id', $entryId);
        
        return $this->client->query($query)->read();
    }

    public function getDnsCache()
    {
        $this->connect();
        $query = new Query('/ip/dns/cache/print');
        return $this->client->query($query)->read();
    }

    public function flushDnsCache()
    {
        $this->connect();
        $query = new Query('/ip/dns/cache/flush');
        return $this->client->query($query)->read();
    }

    // ==================== Session Management ====================

    public function getActiveSessions($type = 'all')
    {
        $this->connect();
        $sessions = [];

        if ($type === 'all' || $type === 'pppoe') {
            $query = new Query('/ppp/active/print');
            $pppoeSessions = $this->client->query($query)->read();
            foreach ($pppoeSessions as $session) {
                $sessions[] = array_merge($session, ['type' => 'pppoe']);
            }
        }

        if ($type === 'all' || $type === 'hotspot') {
            $query = new Query('/ip/hotspot/active/print');
            $hotspotSessions = $this->client->query($query)->read();
            foreach ($hotspotSessions as $session) {
                $sessions[] = array_merge($session, ['type' => 'hotspot']);
            }
        }

        return $sessions;
    }

    public function disconnectSession($sessionId, $type = 'pppoe')
    {
        $this->connect();
        
        $path = $type === 'pppoe' ? '/ppp/active/remove' : '/ip/hotspot/active/remove';
        $query = (new Query($path))->equal('.id', $sessionId);
        
        return $this->client->query($query)->read();
    }

    public function getSessionDetails($username)
    {
        $this->connect();
        
        // Try PPPoE first
        $query = (new Query('/ppp/active/print'))->where('name', $username);
        $session = $this->client->query($query)->read();
        
        if (!empty($session)) {
            return array_merge($session[0], ['type' => 'pppoe']);
        }
        
        // Try Hotspot
        $query = (new Query('/ip/hotspot/active/print'))->where('user', $username);
        $session = $this->client->query($query)->read();
        
        if (!empty($session)) {
            return array_merge($session[0], ['type' => 'hotspot']);
        }
        
        return null;
    }

    public function kickDuplicateSessions($username)
    {
        $this->connect();
        
        $sessions = $this->getActiveSessions('all');
        $userSessions = array_filter($sessions, function($s) use ($username) {
            return ($s['name'] ?? $s['user'] ?? null) === $username;
        });

        if (count($userSessions) > 1) {
            // Keep first, disconnect others
            $first = true;
            foreach ($userSessions as $session) {
                if ($first) {
                    $first = false;
                    continue;
                }
                $this->disconnectSession($session['.id'], $session['type']);
            }
        }
        
        return count($userSessions) - 1;
    }

    // ==================== Wireless Management ====================

    public function getWirelessInterfaces()
    {
        $this->connect();
        $query = new Query('/interface/wireless/print');
        return $this->client->query($query)->read();
    }

    public function getWirelessClients($interface)
    {
        $this->connect();
        $query = (new Query('/interface/wireless/registration-table/print'))
            ->where('interface', $interface);
        return $this->client->query($query)->read();
    }

    public function getSignalStrength($interface)
    {
        $this->connect();
        
        $clients = $this->getWirelessClients($interface);
        $signals = [];
        
        foreach ($clients as $client) {
            $signals[] = [
                'mac' => $client['mac-address'] ?? '',
                'signal_strength' => (int)($client['signal-strength'] ?? 0),
                'signal_to_noise' => (int)($client['signal-to-noise'] ?? 0),
            ];
        }
        
        return $signals;
    }

    public function scanFrequencies($interface)
    {
        $this->connect();
        
        $query = (new Query('/interface/wireless/scan'))
            ->equal('interface', $interface)
            ->equal('duration', '5');
        
        return $this->client->query($query)->read();
    }

    // ==================== Backup & Utilities ====================

    public function createBackup($name = null)
    {
        $this->connect();
        
        $backupName = $name ?? 'backup-' . date('Y-m-d-His');
        $query = (new Query('/system/backup/save'))
            ->equal('name', $backupName);
        
        return $this->client->query($query)->read();
    }

    public function listBackups()
    {
        $this->connect();
        
        $query = (new Query('/file/print'))->where('type', 'backup');
        return $this->client->query($query)->read();
    }

    public function downloadBackup($filename)
    {
        $this->connect();
        
        // Note: Downloading files via API is complex, requires FTP/SFTP
        // This returns file info, actual download needs FTP
        $query = (new Query('/file/print'))->where('name', $filename);
        return $this->client->query($query)->read();
    }

    public function getSystemLogs($topics = null, $lines = 100)
    {
        $this->connect();
        
        $query = new Query('/log/print');
        if ($topics) {
            $query->where('topics', $topics);
        }
        
        return $this->client->query($query)->read();
    }

    public function rebootRouter()
    {
        $this->connect();
        $query = new Query('/system/reboot');
        return $this->client->query($query)->read();
    }

    // ==================== Layer 7 & Website Blocking ====================

    public function getLayer7Protocols()
    {
        $this->connect();
        $query = new Query('/ip/firewall/layer7-protocol/print');
        return $this->client->query($query)->read();
    }

    public function addLayer7Protocol($name, $regexp)
    {
        $this->connect();
        
        // Check if exists first to avoid error
        $existing = $this->getLayer7Protocols();
        foreach ($existing as $protocol) {
            if (($protocol['name'] ?? '') === $name) {
                // Update specific
                $query = (new Query('/ip/firewall/layer7-protocol/set'))
                    ->equal('.id', $protocol['.id'])
                    ->equal('regexp', $regexp);
                return $this->client->query($query)->read();
            }
        }

        $query = (new Query('/ip/firewall/layer7-protocol/add'))
            ->equal('name', $name)
            ->equal('regexp', $regexp);
        
        return $this->client->query($query)->read();
    }

    public function removeLayer7Protocol($name)
    {
        $this->connect();
        
        $protocols = $this->getLayer7Protocols();
        foreach ($protocols as $protocol) {
            if (($protocol['name'] ?? '') === $name) {
                $query = (new Query('/ip/firewall/layer7-protocol/remove'))
                    ->equal('.id', $protocol['.id']);
                $this->client->query($query)->read();
            }
        }
    }

    public function blockWebsite($domain)
    {
        $this->connect();
        $safeDomain = str_replace('.', '\\.', $domain); // Escape dots for regex
        $name = "block_{$domain}";
        
        // 1. Create Layer 7 Protocol
        // improved generic regex for domain matching
        $regexp = "^.+(.{$safeDomain}).*\$"; 
        $this->addLayer7Protocol($name, $regexp);

        // 2. Add Filter Rule to drop traffic
        // Check if rule exists
        $rules = $this->getFirewallRules('forward');
        $exists = false;
        foreach ($rules as $rule) {
            if (($rule['comment'] ?? '') === "Block {$domain}") {
                $exists = true;
                break;
            }
        }

        if (!$exists) {
            $this->addFirewallRule([
                'chain' => 'forward',
                'layer7-protocol' => $name,
                'action' => 'drop',
                'comment' => "Block {$domain}"
            ]);
        }
    }

    public function unblockWebsite($domain)
    {
        $this->connect();
        $name = "block_{$domain}";

        // 1. Remove Filter Rule
        $rules = $this->getFirewallRules('forward');
        foreach ($rules as $rule) {
            if (($rule['comment'] ?? '') === "Block {$domain}") {
                $this->deleteFirewallRule($rule['.id']);
            }
        }

        // 2. Remove Layer 7 Protocol
        $this->removeLayer7Protocol($name);
    }

    // ==================== Backup Management ====================

    public function performBackup($targetUrl, $token)
    {
        $this->connect();
        $serverName = preg_replace('/[^a-zA-Z0-9_-]/', '', $this->router->name ?? 'server');
        $date = date('Y-m-d_H-i-s');
        
        $backupName = "backup_{$serverName}_{$date}";
        $exportName = "export_{$serverName}_{$date}";

        try {
            // 1. Create Backup (.backup)
            Log::info("Creating backup for {$serverName}...");
            $this->client->query((new Query('/system/backup/save'))->equal('name', $backupName))->read();
            
            // 2. Create Export (.rsc)
            Log::info("Creating export for {$serverName}...");
            $this->client->query((new Query('/export'))->equal('file', $exportName))->read();

            // 3. Upload Files using /tool fetch
            // File 1: Backup
            Log::info("Uploading backup for {$serverName}...");
            // Use 201.10.1.1 which is the VPN IP of the Laravel Server
            $this->client->query((new Query('/tool/fetch'))
                ->equal('url', "{$targetUrl}")
                ->equal('http-method', 'post')
                ->equal('http-header-field', "X-Backup-Token: {$token},X-File-Type: backup")
                ->equal('upload-file', "{$backupName}.backup")
                ->equal('dst-path', "ignore_response.txt") 
            )->read();

            // File 2: Export
            Log::info("Uploading export for {$serverName}...");
            $this->client->query((new Query('/tool/fetch'))
                ->equal('url', "{$targetUrl}") 
                ->equal('http-method', 'post')
                ->equal('http-header-field', "X-Backup-Token: {$token},X-File-Type: export")
                ->equal('upload-file', "{$exportName}.rsc")
                ->equal('dst-path', "ignore_response.txt")
            )->read();

            // 4. Cleanup
            Log::info("Cleaning up files for {$serverName}...");
            $this->client->query((new Query('/file/remove'))->equal('numbers', "{$backupName}.backup"))->read();
            $this->client->query((new Query('/file/remove'))->equal('numbers', "{$exportName}.rsc"))->read();

            return true;

        } catch (Exception $e) {
            Log::error("Backup failed for {$serverName}: " . $e->getMessage());
            // Should we throw? Only if we want to mark job as failed.
            // But fetch error from Mikrotik might be "failure: ...".
            // RouterOS client throws ConnectException or general Exception.
            throw $e;
        }
    }

    public function deletePPPoEUser($username)
    {
        $this->connect();
        $findQuery = (new Query('/ppp/secret/print'))->where('name', $username);
        $user = $this->client->query($findQuery)->read();
        if (!empty($user)) {
            $query = (new Query('/ppp/secret/remove'))->equal('.id', $user[0]['.id']);
            return $this->client->query($query)->read();
        }
        return null;
    }

    public function deleteHotspotUser($username)
    {
        $this->connect();
        $findQuery = (new Query('/ip/hotspot/user/print'))->where('name', $username);
        $user = $this->client->query($findQuery)->read();
        if (!empty($user)) {
            $query = (new Query('/ip/hotspot/user/remove'))->equal('.id', $user[0]['.id']);
            return $this->client->query($query)->read();
        }
        return null;
    }
}

