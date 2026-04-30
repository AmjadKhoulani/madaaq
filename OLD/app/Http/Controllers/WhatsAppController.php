<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WhatsAppController extends Controller
{
    public function index()
    {
        $clients = \App\Models\Client::with(['package'])
            ->where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get();
            
        $isConfigured = \App\Models\Setting::getValue('whatsapp_token') && \App\Models\Setting::getValue('whatsapp_phone_id');

        return view('whatsapp.index', compact('clients', 'isConfigured'));
    }

    public function show($id)
    {
        $clients = \App\Models\Client::with(['package'])
            ->where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get();
            
        $client = \App\Models\Client::with(['package', 'tower', 'invoices' => function($q) {
            $q->latest()->take(3);
        }])->findOrFail($id);

        // Fetch messages
        $messages = \DB::table('whatsapp_messages')
            ->where('client_id', $client->id)
            ->orderBy('created_at', 'asc')
            ->get();

        $isConfigured = \App\Models\Setting::getValue('whatsapp_token') && \App\Models\Setting::getValue('whatsapp_phone_id');

        return view('whatsapp.index', compact('clients', 'client', 'messages', 'isConfigured'));
    }

    public function store(\Illuminate\Http\Request $request, \App\Models\Client $client)
    {
        $request->validate(['message' => 'required']);

        $service = new \App\Services\NotificationService();
        if (!$service->isWhatsAppConfigured()) {
            return back()->with('error', 'خدمة الواتساب غير مهيأة. يرجى ضبط الإعدادات أولاً.');
        }

        try {
            // Send via service
            $service->sendWhatsApp($client->phone, $request->message);

            // Save to DB for history
            \DB::table('whatsapp_messages')->insert([
                'client_id' => $client->id,
                'direction' => 'outbound',
                'body' => $request->message,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return back()->with('success', 'تم إرسال الرسالة بنجاح');
        } catch (\Exception $e) {
            return back()->with('error', 'فشل في إرسال الرسالة: ' . $e->getMessage());
        }
    }
}
