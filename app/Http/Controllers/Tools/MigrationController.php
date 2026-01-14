<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Package;
use App\Models\Router;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MigrationController extends Controller
{
    public function index()
    {
        return view('tools.migration.index');
    }

    public function importFromMikrotik(Request $request)
    {
        $request->validate([
            'mikrotik_host' => 'required|string',
            'mikrotik_username' => 'required|string',
            'mikrotik_password' => 'required|string',
            'mikrotik_port' => 'nullable|integer',
            'import_type' => 'required|in:pppoe,hotspot,both',
        ]);

        try {
            // Connect to MikroTik
            $connection = $this->connectToMikrotik(
                $request->mikrotik_host,
                $request->mikrotik_username,
                $request->mikrotik_password,
                $request->mikrotik_port ?? 8728
            );

            if (!$connection) {
                return back()->with('error', 'فشل الاتصال بجهاز MikroTik. تحقق من البيانات.');
            }

            $imported = [
                'clients' => 0,
                'packages' => 0,
                'errors' => []
            ];

            // Import PPPoE Users
            if (in_array($request->import_type, ['pppoe', 'both'])) {
                $pppoeResult = $this->importPPPoEUsers($connection);
                $imported['clients'] += $pppoeResult['count'];
                $imported['errors'] = array_merge($imported['errors'], $pppoeResult['errors']);
            }

            // Import Hotspot Users
            if (in_array($request->import_type, ['hotspot', 'both'])) {
                $hotspotResult = $this->importHotspotUsers($connection);
                $imported['clients'] += $hotspotResult['count'];
                $imported['errors'] = array_merge($imported['errors'], $hotspotResult['errors']);
            }

            $message = "تم استيراد {$imported['clients']} عميل بنجاح!";
            if (count($imported['errors']) > 0) {
                $message .= " مع " . count($imported['errors']) . " خطأ.";
            }

            return back()->with('success', $message)->with('import_details', $imported);

        } catch (\Exception $e) {
            Log::error('MikroTik Import Error: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء الاستيراد: ' . $e->getMessage());
        }
    }

    public function importFromFile(Request $request)
    {
        $request->validate([
            'import_file' => 'required|file|mimes:csv,txt',
            'file_type' => 'required|in:pppoe,hotspot',
        ]);

        try {
            $file = $request->file('import_file');
            $data = array_map('str_getcsv', file($file->getRealPath()));
            
            $imported = 0;
            $errors = [];

            DB::beginTransaction();

            foreach ($data as $index => $row) {
                if ($index === 0) continue; // Skip header

                try {
                    if ($request->file_type === 'pppoe') {
                        $this->createPPPoEClient($row);
                    } else {
                        $this->createHotspotClient($row);
                    }
                    $imported++;
                } catch (\Exception $e) {
                    $errors[] = "السطر {$index}: " . $e->getMessage();
                }
            }

            DB::commit();

            $message = "تم استيراد {$imported} عميل من الملف!";
            if (count($errors) > 0) {
                $message .= " مع " . count($errors) . " خطأ.";
            }

            return back()->with('success', $message)->with('import_errors', $errors);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'حدث خطأ أثناء استيراد الملف: ' . $e->getMessage());
        }
    }

    private function connectToMikrotik($host, $username, $password, $port)
    {
        try {
            // Check if RouterOS API package is installed
            if (!class_exists('\EvilFreelancer\RouterOS\Client')) {
                Log::warning('RouterOS API package not installed. Using mock connection.');
                return null;
            }

            $config = [
                'host' => $host,
                'user' => $username,
                'pass' => $password,
                'port' => (int) $port,
            ];

            $client = new \EvilFreelancer\RouterOS\Client($config);
            
            // Test connection
            $client->connect();
            
            return $client;
        } catch (\Exception $e) {
            Log::error('MikroTik Connection Error: ' . $e->getMessage());
            return null;
        }
    }

    private function importPPPoEUsers($connection)
    {
        if (!$connection) {
            return ['count' => 0, 'errors' => ['فشل الاتصال بالجهاز']];
        }

        try {
            $result = $connection->query('/ppp/secret/print')->read();
            
            $imported = 0;
            $errors = [];

            foreach ($result as $secret) {
                try {
                    // Check if user already exists
                    $exists = Client::where('username', $secret['name'])->exists();
                    if ($exists) {
                        continue;
                    }

                    Client::create([
                        'tenant_id' => auth()->user()->tenant_id ?? 1,
                        'username' => $secret['name'],
                        'password' => $secret['password'] ?? 'password',
                        'type' => 'pppoe',
                        'status' => $secret['disabled'] === 'true' ? 'inactive' : 'active',
                    ]);
                    
                    $imported++;
                } catch (\Exception $e) {
                    $errors[] = "خطأ في استيراد {$secret['name']}: " . $e->getMessage();
                }
            }

            return ['count' => $imported, 'errors' => $errors];
        } catch (\Exception $e) {
            return ['count' => 0, 'errors' => ['خطأ في قراءة البيانات: ' . $e->getMessage()]];
        }
    }

    private function importHotspotUsers($connection)
    {
        if (!$connection) {
            return ['count' => 0, 'errors' => ['فشل الاتصال بالجهاز']];
        }

        try {
            $result = $connection->query('/ip/hotspot/user/print')->read();
            
            $imported = 0;
            $errors = [];

            foreach ($result as $user) {
                try {
                    // Check if user already exists
                    $exists = Client::where('username', $user['name'])->exists();
                    if ($exists) {
                        continue;
                    }

                    Client::create([
                        'tenant_id' => auth()->user()->tenant_id ?? 1,
                        'username' => $user['name'],
                        'password' => $user['password'] ?? 'password',
                        'type' => 'hotspot',
                        'status' => $user['disabled'] === 'true' ? 'inactive' : 'active',
                    ]);
                    
                    $imported++;
                } catch (\Exception $e) {
                    $errors[] = "خطأ في استيراد {$user['name']}: " . $e->getMessage();
                }
            }

            return ['count' => $imported, 'errors' => $errors];
        } catch (\Exception $e) {
            return ['count' => 0, 'errors' => ['خطأ في قراءة البيانات: ' . $e->getMessage()]];
        }
    }

    private function createPPPoEClient($row)
    {
        // Row format: username, password, service/package, profile
        Client::create([
            'tenant_id' => auth()->user()->tenant_id ?? 1,
            'username' => $row[0],
            'password' => $row[1],
            'type' => 'pppoe',
            'status' => 'active',
        ]);
    }

    private function createHotspotClient($row)
    {
        // Row format: username, password, profile
        Client::create([
            'tenant_id' => auth()->user()->tenant_id ?? 1,
            'username' => $row[0],
            'password' => $row[1],
            'type' => 'hotspot',
            'status' => 'active',
        ]);
    }
}
