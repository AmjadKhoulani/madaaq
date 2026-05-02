<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NotificationTemplate;
use App\Models\Tenant;

class NotificationTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::first();
        
        if (!$tenant) {
            $this->command->warn('No tenant found. Skipping notification templates.');
            return;
        }

        $templates = [
            // Expiry Warnings
            [
                'name' => 'expiry_warning_3days',
                'channel' => 'sms',
                'subject' => null,
                'content' => 'مرحباً {{username}}، اشتراكك في باقة {{package_name}} سينتهي بعد {{days_remaining}} أيام في {{expiry_date}}. يرجى التجديد لتجنب انقطاع الخدمة.',
            ],
            [
                'name' => 'expiry_warning_1day',
                'channel' => 'sms',
                'subject' => null,
                'content' => 'عزيزي {{username}}، تذكير عاجل: اشتراكك سينتهي غداً {{expiry_date}}. جدد الآن لتجنب قطع الخدمة.',
            ],
            [
                'name' => 'subscription_expired',
                'channel' => 'sms',
                'subject' => null,
                'content' => '{{username}}، انتهى اشتراكك في {{expiry_date}}. يرجى التجديد لاستعادة الخدمة.',
            ],

            // Payment Notifications
            [
                'name' => 'payment_received',
                'channel' => 'sms',
                'subject' => null,
                'content' => 'شكراً {{username}}! تم استلام دفعتك بمبلغ {{amount}}. تم تجديد اشتراكك حتى {{new_expiry}}.',
            ],

            // Service Notifications
            [
                'name' => 'service_activated',
                'channel' => 'sms',
                'subject' => null,
                'content' => 'مرحباً {{username}}، تم تفعيل خدمتك بنجاح. اسم المستخدم: {{username}}، كلمة المرور: {{password}}',
            ],
            [
                'name' => 'service_suspended',
                'channel' => 'sms',
                'subject' => null,
                'content' => '{{username}}، تم تعليق خدمتك بسبب {{reason}}. للاستفسار اتصل بنا.',
            ],

            // Email Templates
            [
                'name' => 'invoice_generated',
                'channel' => 'email',
                'subject' => 'فاتورة جديدة - {{invoice_number}}',
                'content' => "عزيزي {{username}},\n\nتم إصدار فاتورة جديدة:\nرقم الفاتورة: {{invoice_number}}\nالمبلغ: {{amount}}\nتاريخ الاستحقاق: {{due_date}}\n\nيرجى الدفع في الموعد المحدد.\n\nشكراً لك",
            ],
        ];

        foreach ($templates as $template) {
            NotificationTemplate::updateOrCreate(
                ['name' => $template['name'], 'tenant_id' => $tenant->id],
                array_merge($template, ['active' => true])
            );
        }

        $this->command->info('Notification templates seeded successfully!');
    }
}
