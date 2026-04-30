<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    public function create()
    {
        return view('crm.campaigns.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'target' => 'required|in:all,active,expiring,inactive',
            'message' => 'required|string',
            'channel' => 'required|in:whatsapp,sms,email',
        ]);

        $notificationService = new \App\Services\NotificationService();
        if ($request->channel === 'whatsapp' && !$notificationService->isWhatsAppConfigured()) {
            return redirect()->back()->with('error', 'خدمة الواتساب غير مهيأة. يرجى ضبط الإعدادات أولاً قبل إرسال الحملات.');
        }

        // Get target clients based on selection
        $clients = $this->getTargetClients($request->target);

        $sent = 0;
        $failed = 0;

        foreach ($clients as $client) {
            try {
                // Send message based on channel
                if ($request->channel === 'whatsapp') {
                    $this->sendWhatsApp($client, $request->message);
                } elseif ($request->channel === 'sms') {
                    $this->sendSMS($client, $request->message);
                } else {
                    $this->sendEmail($client, $request->message);
                }
                $sent++;
            } catch (\Exception $e) {
                $failed++;
            }
        }

        return redirect()->back()->with('success', "تم إرسال {$sent} رسالة بنجاح. فشل: {$failed}");
    }

    private function getTargetClients($target)
    {
        // CRITICAL: Filter by current vendor's tenant_id
        $query = Client::where('tenant_id', auth()->user()->tenant_id);

        switch ($target) {
            case 'active':
                $query->where('status', 'active');
                break;
            case 'expiring':
                $query->where('status', 'active')
                    ->whereBetween('expires_at', [now(), now()->addDays(7)]);
                break;
            case 'inactive':
                $query->where('status', 'inactive');
                break;
            // 'all' - no additional filter (but still filtered by tenant_id)
        }

        return $query->whereNotNull('phone')->get();
    }

    private function sendWhatsApp($client, $message)
    {
        $message = str_replace([
            '{{name}}',
            '{{username}}',
            '{{package}}'
        ], [
            $client->name ?? $client->username,
            $client->username,
            $client->package->name ?? 'N/A'
        ], $message);

        if ($client->phone) {
            $notificationManager = new \App\Services\NotificationService();
            $notificationManager->sendWhatsApp($client->phone, $message);
        }
    }

    private function sendSMS($client, $message)
    {
        $message = str_replace([
            '{{name}}',
            '{{username}}',
            '{{package}}'
        ], [
            $client->name ?? $client->username,
            $client->username,
            $client->package->name ?? 'N/A'
        ], $message);

        if ($client->phone) {
            $notificationManager = new \App\Services\NotificationService();
            $notificationManager->sendSMS($client->phone, $message);
        }
    }

    private function sendEmail($client, $message)
    {
        $message = str_replace([
            '{{name}}',
            '{{username}}',
            '{{package}}'
        ], [
            $client->name ?? $client->username,
            $client->username,
            $client->package->name ?? 'N/A'
        ], $message);

        if ($client->email) {
            $notificationManager = new \App\Services\NotificationService();
            $notificationManager->sendEmail($client->email, "تنبيه من SmartISP", $message);
        }
    }
}
