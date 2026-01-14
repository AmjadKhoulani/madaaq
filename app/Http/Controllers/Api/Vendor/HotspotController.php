<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Package;
use App\Models\MikroTikServer;
use App\Services\MikroTikService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class HotspotController extends Controller
{
    public function vouchers(Request $request)
    {
        // Vouchers are essentially Clients with a specific naming convention or just generic hotspot users created in batch.
        // Assuming 'Voucher' prefix for now as seen in VoucherController
        $query = Client::where('tenant_id', auth()->user()->tenant_id)
            ->where('type', 'hotspot')
            ->where('name', 'like', 'Voucher%')
            ->latest();

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $vouchers = $query->paginate(20);

        return response()->json($vouchers);
    }

    public function storeVoucher(Request $request)
    {
        $request->validate([
            'server_id' => 'required|exists:mikrotik_servers,id',
            'package_id' => 'required|exists:packages,id',
            'quantity' => 'required|integer|min:1|max:100',
            'prefix' => 'nullable|string|max:5',
            'length' => 'required|integer|min:4|max:10',
        ]);

        $server = MikroTikServer::findOrFail($request->server_id);
        $package = Package::findOrFail($request->package_id);
        $service = new MikroTikService($server);
        
        $createdClients = [];
        
        try {
            DB::beginTransaction();

            for ($i = 0; $i < $request->quantity; $i++) {
                $username = ($request->prefix ?? '') . Str::upper(Str::random($request->length));
                while(Client::where('username', $username)->exists()) {
                    $username = ($request->prefix ?? '') . Str::upper(Str::random($request->length));
                }

                $password = Str::random($request->length);
                
                try {
                     $service->createHotspotUser($username, $password, $package->name ?? 'default');
                } catch (\Exception $e) {
                    \Log::error("Failed to create user $username on MikroTik: " . $e->getMessage());
                }

                $client = Client::create([
                    'tenant_id' => auth()->user()->tenant_id ?? 1,
                    'mikrotik_server_id' => $server->id,
                    'type' => 'hotspot',
                    'package_id' => $package->id,
                    'username' => $username,
                    'password' => $password,
                    'status' => 'active',
                    'name' => 'Voucher ' . $username,
                ]);
                
                $createdClients[] = $client;
            }

            DB::commit();

            return response()->json([
                'message' => "Generated {$request->quantity} vouchers successfully",
                'data' => $createdClients
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error generating vouchers: ' . $e->getMessage()], 500);
        }
    }

    public function users(Request $request)
    {
        $query = Client::where('tenant_id', auth()->user()->tenant_id)
            ->where('type', 'hotspot')
            ->with('package')
            ->latest();

        $users = $query->paginate(20);
        return response()->json($users);
    }

    public function packages()
    {
        $packages = Package::where('type', 'hotspot')->get();
        return response()->json($packages);
    }
}
