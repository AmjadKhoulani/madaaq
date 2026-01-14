<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index()
    {
        $packages = Package::where('type', 'pppoe')->orWhere('type', 'hotspot')->get(); 
        // Filter based on client type? 
        // For now show all or filter by client type.
        
        $client = auth('client')->user();
        if ($client) {
            $packages = Package::where('type', $client->type)->get();
        }

        return view('portal.packages.index', compact('packages', 'client'));
    }
}
