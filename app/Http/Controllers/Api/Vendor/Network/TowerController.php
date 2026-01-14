<?php

namespace App\Http\Controllers\Api\Vendor\Network;

use App\Http\Controllers\Controller;
use App\Models\Tower;
use Illuminate\Http\Request;

class TowerController extends Controller
{
    public function index()
    {
        $towers = Tower::where('tenant_id', auth()->user()->tenant_id)
            ->withCount(['ssids', 'clients'])
            ->get();
            
        return response()->json(['data' => $towers]);
    }

    public function show($id)
    {
        $tower = Tower::where('tenant_id', auth()->user()->tenant_id)
            ->with(['ssids', 'costs'])
            ->withCount('clients')
            ->findOrFail($id);

        $tower->append('total_equipment_cost');

        return response()->json($tower);
    }
}
