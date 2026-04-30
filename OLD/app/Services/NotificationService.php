<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\NotificationTemplate;
use App\Models\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Check if WhatsApp is configured
     */
    public function isWhatsAppConfigured()
    {
        $type = $this->getWhatsAppType();
        
        if ($type === 'regular') {
            $number = \App\Models\Setting::getValue('whatsapp_regular_number');
            return !empty($number);
        }
        
        // For API type
        $token = \App\Models\Setting::getValue('whatsapp_token');
        $phoneId = \App\Models\Setting::getValue('whatsapp_phone_id');
        return !empty($token) && !empty($phoneId);
    }

    /**
     * Get WhatsApp type (api or regular)
     */
    public function getWhatsAppType()
    {
        return \App\Models\Setting::getValue('whatsapp_type', 'api');
    }

    /**
     * Get WhatsApp contact link (for regular type)
     */
    public function getWhatsAppLink($message = '')
    {
        $number = \App\Models\Setting::getValue('whatsapp_regular_number');
        if (empty($number)) {
            return null;
        }
        
        $url = "https://wa.me/{$number}";
        if (!empty($message)) {
            $url .= '?text=' . urlencode($message);
        }
        
        return $url;
    }

    /**
     * Send notification to client
     */
    public function send(Client $client, string $templateName, array $variables = [], string $channel = null)
    {
        // Get template
        $template = NotificationTemplate::where('name', $templateName)
            ->where('active', true)
            ->first();

        if (!$template) {
            Log::warning("Notification template not found: {$templateName}");
            return false;
        }

        // Determine channel
        $channel = $channel ?? $template->channel;

        // Get recipient
        $recipient = $this->getRecipient($client, $channel);
        if (!$recipient) {
            Log::warning("No recipient found for client {$client->id} on channel {$channel}");
            return false;
        }

        // Render content
        $content = $template->render($variables);

        // Create notification record
        $notification = Notification::create([
            'client_id' => $client->id,
            'channel' => $channel,
            'type' => $templateName,
            'recipient' => $recipient,
            'content' => $content,
            'status' => 'pending',
        ]);

        // Send based on channel
        try {
            switch ($channel) {
                case 'sms':
                    $this->sendSMS($recipient, $content);
                    break;
                case 'whatsapp':
                    $this->sendWhatsApp($recipient, $content);
                    break;
                case 'email':
                    $this->sendEmail($recipient, $template->subject, $content);
                    break;
            }

            $notification->update([
                'status' => 'sent',
                'sent_at' => now(),
            ]);

            return true;
        } catch (\Exception $e) {
            $notification->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            Log::error("Notification failed: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get recipient based on channel
     */
    protected function getRecipient(Client $client, string $channel): ?string
    {
        switch ($channel) {
            case 'sms':
            case 'whatsapp':
                return $client->phone ?? null;
            case 'email':
                return $client->email ?? null;
            default:
                return null;
        }
    }

    /**
     * Send SMS via Twilio or local gateway
     */
    protected function sendSMS(string $to, string $message)
    {
        $provider = config('services.sms.provider', 'twilio');

        if ($provider === 'twilio') {
            $sid = config('services.twilio.sid');
            $token = config('services.twilio.token');
            $from = config('services.twilio.from');

            if (!$sid || !$token || !$from) {
                throw new \Exception('Twilio credentials not configured');
            }

            $response = Http::withBasicAuth($sid, $token)
                ->asForm()
                ->post("https://api.twilio.com/2010-04-01/Accounts/{$sid}/Messages.json", [
                    'From' => $from,
                    'To' => $to,
                    'Body' => $message,
                ]);

            if (!$response->successful()) {
                throw new \Exception('SMS sending failed: ' . $response->body());
            }
        }
        
        // Add support for local gateways here
    }

    /**
     * Send WhatsApp message
     */
    /**
     * Send WhatsApp message via Meta Cloud API
     */
    protected function sendWhatsApp(string $to, string $message)
    {
        $token = \App\Models\Setting::getValue('whatsapp_token');
        $phoneId = \App\Models\Setting::getValue('whatsapp_phone_id');

        if (!$token || !$phoneId) {
            // Fallback to env or log error
            Log::warning('WhatsApp Cloud API credentials not configured.');
            return;
        }

        // Format phone number (remove leading +, ensure country code)
        // Meta requires country code without +. 
        $to = preg_replace('/[^0-9]/', '', $to); 

        $response = Http::withToken($token)
            ->post("https://graph.facebook.com/v17.0/{$phoneId}/messages", [
                'messaging_product' => 'whatsapp',
                'to' => $to,
                'type' => 'text',
                'text' => [
                    'preview_url' => false,
                    'body' => $message
                ]
            ]);

        if (!$response->successful()) {
            throw new \Exception('WhatsApp Cloud API sending failed: ' . $response->body());
        }
    }

    /**
     * Send email
     */
    protected function sendEmail(string $to, string $subject, string $content)
    {
        Mail::raw($content, function ($message) use ($to, $subject) {
            $message->to($to)
                    ->subject($subject);
        });
    }
}
