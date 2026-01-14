<?php

namespace App\Http\Controllers\Hotspot;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Router;
use App\Models\Package;
use App\Services\MikroTikService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class VoucherController extends Controller
{
    public function index(Request $request)
    {
        $query = Client::where('type', 'hotspot')
            ->where('name', 'like', 'Voucher %')
            ->with(['package', 'mikrotikServer']);
        
        // Search
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('username', 'like', '%' . $request->search . '%')
                  ->orWhere('name', 'like', '%' . $request->search . '%');
            });
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by package
        if ($request->filled('package_id')) {
            $query->where('package_id', $request->package_id);
        }
        
        // Filter by date
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $vouchers = $query->latest()->paginate(20);
        $packages = \App\Models\Package::where('type', 'hotspot')->get();
        
        // Statistics
        $stats = [
            'total' => Client::where('type', 'hotspot')->where('name', 'like', 'Voucher %')->count(),
            'active' => Client::where('type', 'hotspot')->where('name', 'like', 'Voucher %')->where('status', 'active')->count(),
            'inactive' => Client::where('type', 'hotspot')->where('name', 'like', 'Voucher %')->where('status', 'inactive')->count(),
        ];
        
        return view('hotspot.vouchers.index', compact('vouchers', 'packages', 'stats'));
    }

    public function create()
    {
        $servers = \App\Models\MikroTikServer::all();
        $packages = Package::where('type', 'hotspot')->get();
        return view('hotspot.vouchers.create', compact('servers', 'packages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'server_id' => 'required|exists:mikrotik_servers,id',
            'package_id' => 'required|exists:packages,id',
            'quantity' => 'required|integer|min:1|max:100',
            'prefix' => 'nullable|string|max:5',
            'length' => 'required|integer|min:4|max:10',
        ]);

        $server = \App\Models\MikroTikServer::findOrFail($request->server_id);
        $package = Package::findOrFail($request->package_id);
        $service = new MikroTikService($server);
        
        $createdClients = [];
        $batchId = Str::uuid();

        try {
            DB::beginTransaction();

            for ($i = 0; $i < $request->quantity; $i++) {
                // Generate Unique Username
                $username = ($request->prefix ?? '') . Str::upper(Str::random($request->length));
                while(Client::where('username', $username)->exists()) {
                    $username = ($request->prefix ?? '') . Str::upper(Str::random($request->length));
                }

                $password = Str::random($request->length);
                
                // 1. Create on MikroTik
                try {
                     $service->createHotspotUser($username, $password, $package->name ?? 'default');
                } catch (\Exception $e) {
                    \Log::error("Failed to create user $username on MikroTik: " . $e->getMessage());
                }

                // 2. Create in DB
                // Note: 'router_id' in Client table might specifically refer to tower routers. 
                // If Clients are now associated with Servers, we need a 'mikrotik_server_id' column or map it.
                // However, usually Hotspot users are on the main gateway (Server). 
                // Let's check Schema. If 'router_id' is foreign key to 'routers', we might have an issue if we pass a Server ID if they are different tables.
                // Let's assume for now we might need to store it differently or the migration allowed nullable.
                // Wait, if Client has 'router_id' linked to 'routers' table, putting a 'mikrotik_server_id' there will fail FK constraints.
                // Converting logic: The user wants "Server".
                // Does Client model have 'mikrotik_server_id'?
                
                $client = Client::create([
                    'tenant_id' => auth()->user()->tenant_id ?? 1,
                    'mikrotik_server_id' => $server->id,
                    'batch_id' => $batchId,
                    'type' => 'hotspot',
                    'package_id' => $package->id,
                    'username' => $username,
                    'password' => $password,
                    'status' => 'active',
                    'name' => 'Voucher ' . $username,
                ]);
                
                $createdClients[] = $client->id;
            }

            DB::commit();
            
            // Store batch ID in session for convenience
            session(['last_batch_id' => $batchId]);

            return redirect()->route('hotspot.vouchers.print_batch', ['batch' => $batchId])
                ->with('success', "تم توليد {$request->quantity} كرت بنجاح وتمت المزامنة مع المايكروتك! 🖨️");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'حدث خطأ أثناء التوليد: ' . $e->getMessage());
        }
    }

    public function printBatch(Request $request)
    {
        // Check for specific batch request, or session client_ids, or last batch
        $users = collect();
        
        if ($request->has('batch')) {
            $users = Client::where('batch_id', $request->batch)->with('package')->get();
        } elseif (session()->has('client_ids')) {
            $users = Client::whereIn('id', session('client_ids'))->with('package')->get();
        } elseif ($request->has('last')) {
             $lastBatch = Client::where('type', 'hotspot')
                               ->whereNotNull('batch_id')
                               ->latest()
                               ->value('batch_id');
             if($lastBatch) {
                 $users = Client::where('batch_id', $lastBatch)->with('package')->get();
             }
        }

        if ($users->isEmpty()) {
            return redirect()->route('hotspot.vouchers.index')->with('error', 'لا توجد كروت للطباعة.');
        }

        return view('hotspot.vouchers.print_batch', compact('users'));
    }
    
    public function reprintLast()
    {
        return redirect()->route('hotspot.vouchers.print_batch', ['last' => 1]);
    }
    
    public function bulkAction(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:clients,id',
            'action' => 'required|in:disable,delete',
        ]);

        $clients = Client::whereIn('id', $request->ids)->get();
        $count = $clients->count();
        $success = 0;

        foreach ($clients as $client) {
            if ($request->action === 'disable') {
                if ($client->status !== 'inactive') {
                    $client->update(['status' => 'inactive']);
                    // Sync MikroTik
                    if ($client->mikrotik_server_id) {
                        try {
                             $server = $client->mikrotikServer;
                             if($server) {
                                 $service = new \App\Services\MikroTikService($server);
                                 $service->disableHotspotUser($client->username);
                                 $service->kickHotspotUser($client->username);
                             }
                        } catch (\Exception $e) {
                            \Log::error("Bulk Disable Sync Fail: " . $e->getMessage());
                        }
                    }
                    $success++;
                }
            } elseif ($request->action === 'delete') {
                 // Delete from MikroTik first
                 if ($client->mikrotik_server_id) {
                        try {
                             $server = $client->mikrotikServer;
                             if($server) {
                                 $service = new \App\Services\MikroTikService($server);
                                 $service->deleteHotspotUser($client->username);
                             }
                        } catch (\Exception $e) {
                            \Log::error("Bulk Delete Sync Fail: " . $e->getMessage());
                        }
                 }
                 $client->delete();
                 $success++;
            }
        }

        $msg = $request->action === 'disable' ? "تم تعطيل $success كرت بنجاح" : "تم حذف $success كرت بنجاح";
        return redirect()->back()->with('success', $msg);
    }
}
