<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WhatsAppController extends Controller
{
    public function index()
    {
        // Return clients who have messages or just recent clients
        $clients = Client::where('tenant_id', auth()->user()->tenant_id)
            ->with(['package'])
            ->where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get(); // Should ideally select distinct clients with messages

        return response()->json(['data' => $clients]);
    }

    public function show($clientId)
    {
        $client = Client::where('tenant_id', auth()->user()->tenant_id)->findOrFail($clientId);
        
        $messages = DB::table('whatsapp_messages')
            ->where('client_id', $client->id)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'client' => $client,
            'messages' => $messages
        ]);
    }

    public function store(Request $request, $clientId)
    {
        $client = Client::where('tenant_id', auth()->user()->tenant_id)->findOrFail($clientId);
        $request->validate(['message' => 'required|string']);

        $service = new \App\Services\NotificationService();
        
        // Check config from Settings table for tenant
        // Assuming settings are global or tenant based logic is in service
        // For now, proceed.

        try {
            $service->sendWhatsApp($client->phone, $request->message);

            DB::table('whatsapp_messages')->insert([
                'client_id' => $client->id,
                'direction' => 'outbound',
                'body' => $request->message,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json(['message' => 'Message sent successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to send: ' . $e->getMessage()], 500);
        }
    }
}
