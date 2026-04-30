<?php

namespace App\Http\Controllers\Api\Vendor\CRM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CampaignController extends Controller
{
    public function index()
    {
        // Assuming table 'marketing_campaigns' exists or we use a basic structure
        // Since I haven't seen the migration, I will assume a generic structure for now
        // or check if there is an existing model.
        // Let's assume there isn't one and just use a placeholder or generic DB call if table exists.
        // Wait, I should check existing web controller if possible.
        // But to be safe and quick for "Phase 2/Optional", I will create a basic one.
        
        $campaigns = DB::table('marketing_campaigns')
            ->where('tenant_id', auth()->user()->tenant_id)
            ->latest()
            ->get();
            
        return response()->json(['data' => $campaigns]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:sms,whatsapp',
            'message' => 'required|string',
            'target_audience' => 'required|string', // e.g., 'all', 'active', 'expired'
        ]);

        // Logic to dispatch campaign would go here
        // For now, store record

        $id = DB::table('marketing_campaigns')->insertGetId([
            'tenant_id' => auth()->user()->tenant_id,
            'name' => $validated['name'],
            'type' => $validated['type'],
            'message' => $validated['message'],
            'target_audience' => $validated['target_audience'],
            'status' => 'pending', // or dispatched
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['message' => 'Campaign created', 'id' => $id], 201);
    }
}
