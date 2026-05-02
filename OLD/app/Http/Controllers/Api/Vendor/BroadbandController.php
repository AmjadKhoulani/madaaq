<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Package;
use Illuminate\Http\Request;

class BroadbandController extends Controller
{
    public function users(Request $request)
    {
        $query = Client::where('tenant_id', auth()->user()->tenant_id)
            ->where('type', 'pppoe')
            ->with(['package', 'router', 'tower', 'ssid'])
            ->latest();

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('username', 'like', "%$search%")
                  ->orWhere('phone', 'like', "%$search%");
            });
        }

        $users = $query->paginate(20);

        return response()->json($users);
    }

    public function profiles(Request $request)
    {
        $profiles = Package::where('type', 'pppoe')->get();
        return response()->json([
            'data' => $profiles,
            'currency' => \App\Models\Setting::getValue('currency', 'SAR')
        ]);
    }
}
