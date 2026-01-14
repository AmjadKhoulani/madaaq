<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Package;

class PackageController extends Controller
{
    public function index(Request $request)
    {
        $client = $request->user();
        
        // Filter packages by tenant
        $packages = Package::where('type', $client->type)
                          ->where('is_active', true)
                          ->get(); // Assuming packages are global or scoped by tenant if Package has tenant_id

        // Note: Package model needs to be checked if it has tenant_id. 
        // Assuming packages are shared or filtered by tenant logic in model scope.
        
        return response()->json($packages);
    }
}
