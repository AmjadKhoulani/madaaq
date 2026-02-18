<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        // General Settings
        $settings = [
            'currency' => Setting::getValue('currency', 'ر.س'),
            'company_name' => Setting::getValue('company_name', 'SmartISP'),
            'company_logo' => Setting::getValue('company_logo', null),
            'secondary_currency' => Setting::getValue('secondary_currency', ''),
            'exchange_rate' => Setting::getValue('exchange_rate', 1),
            'auto_suspend_enabled' => Setting::getValue('auto_suspend_enabled', 1),
            'default_grace_period' => Setting::getValue('default_grace_period', 0),
            'invoice_generation_days' => Setting::getValue('invoice_generation_days', 3),
            'whatsapp_token' => Setting::getValue('whatsapp_token', ''),
            'whatsapp_phone_id' => Setting::getValue('whatsapp_phone_id', ''),
            'whatsapp_business_id' => Setting::getValue('whatsapp_business_id', ''),
            'whatsapp_type' => Setting::getValue('whatsapp_type', 'api'),
            'whatsapp_regular_number' => Setting::getValue('whatsapp_regular_number', ''),
        ];

        // Payment Gateways Settings
        $gateways = [
            'stripe' => ['stripe_public_key', 'stripe_secret_key', 'stripe_active'],
            'paypal' => ['paypal_client_id', 'paypal_secret', 'paypal_active'],
            'cham_cash' => ['cham_cash_merchant_id', 'cham_cash_secret_key', 'cham_cash_active'],
            'cham_cash_personal' => ['cham_cash_personal_wallet', 'cham_cash_personal_qr', 'cham_cash_personal_active'],
            'syriatel_cash' => ['syriatel_cash_merchant_id', 'syriatel_cash_pin', 'syriatel_cash_active'],
        ];

        foreach ($gateways as $gateway => $keys) {
            foreach ($keys as $key) {
                $settings[$key] = Setting::getValue($key);
            }
        }
        
        // Pass current tenant to view
        $tenant = auth()->user()->tenant;
        
        return view('settings.index', compact('settings', 'tenant')); // Passing array instead of individual vars
    }

    public function update(Request $request)
    {
        $data = $request->except('_token', 'tab', 'subdomain'); // 'tab' to keep current tab active if needed

        if ($request->has('profile_name')) {
            $user = auth()->user();
            $user->update([
                'name' => $request->profile_name,
                'phone' => $request->profile_phone,
            ]);

            if ($request->filled('profile_password')) {
                $request->validate([
                    'profile_password' => 'min:8|confirmed',
                ]);
                $user->update([
                    'password' => \Illuminate\Support\Facades\Hash::make($request->profile_password),
                ]);
            }
            // Logic handled, remove from settings data
            unset($data['profile_name'], $data['profile_phone'], $data['profile_password'], $data['profile_password_confirmation']);
        }

        // Handle Subdomain Toggle & Update
        $user = auth()->user();
        if ($user->tenant) {
            // Update Toggle Status
            $isEnabled = $request->has('is_subdomain_enabled');
            $user->tenant->update(['is_subdomain_enabled' => $isEnabled]);

            // Only update subdomain if enabled and filled
            if ($isEnabled && $request->filled('subdomain')) {
                $subdomain = strtolower($request->subdomain);
                
                // Construct full domain based on app URL
                // e.g. if APP_URL=http://madaaq.com, and subdomain=vip -> vip.madaaq.com
                // We will store just the subdomain part usually if the middleware logic handles it,
                // BUT looking at TenantMiddleware, it checks strictly against 'domain' column.
                // It checks: $tenant = \App\Models\Tenant::where('domain', $subdomain)->first(); (Line 38)
                // AFTER splitting parts.
                // So if the user enters 'vendor1', we should probably store 'vendor1.madaaq.com' or just 'vendor1'?
                // Middleware Line 38 logic: $subdomain = $domainParts[0]; ... where('domain', $subdomain)
                // Wait, if I store 'vendor1.madaaq.com' in DB, and middleware checks 'vendor1', it won't match.
                // Let's re-read middleware.
                
                // Middleware Line 33: $tenant = \App\Models\Tenant::where('domain', $host)->first(); (Full match)
                // Middleware Line 38: $tenant = \App\Models\Tenant::where('domain', $subdomain)->first(); (Short match)
                
                // So it tries BOTH full domain AND just the subdomain part.
                // To be safe and consistent, we should probably encourage storing the FULL domain if possible, 
                // but if we only ask for subdomain prefix, we can store just that or construct it.
                // Let's store the PREFIX for now if that's what the middleware looks for in the second check, 
                // OR better: store the full domain to avoid collisions with other TLDs if expanded later.
                // However, based on user request "subdomain", let's assume valid subdomain string.
                
                // Let's validate
                 $request->validate([
                    'subdomain' => [
                        'required', 
                        'string', 
                        'regex:/^[a-zA-Z0-9\-]+$/', // Alphanumeric and hyphens
                        'min:3', 
                        'max:20',
                        \Illuminate\Validation\Rule::unique('tenants', 'domain')->ignore($user->tenant_id)
                    ]
                ], [
                    'subdomain.unique' => 'هذا النطاق الفرعي محجوز مسبقاً، يرجى اختيار اسم آخر.',
                    'subdomain.regex' => 'النطاق الفرعي يجب أن يحتوي على أحرف وأرقام إنجليزية فقط.'
                ]);

                $user->tenant->update(['domain' => $subdomain]);
            }
        }

        if ($request->hasFile('company_logo')) {
            $request->validate(['company_logo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048']);
            $path = $request->file('company_logo')->store('settings', 'public');
            Setting::setValue('company_logo', $path);
            unset($data['company_logo']);
        }

        // Handle File Upload for QR Code
        if ($request->hasFile('cham_cash_personal_qr')) {
            $path = $request->file('cham_cash_personal_qr')->store('payments/qr', 'public');
            Setting::setValue('cham_cash_personal_qr', $path);
            unset($data['cham_cash_personal_qr']);
        }

        // Handle Checkboxes (unchecked checkboxes are not sent)
        $checkboxes = ['auto_suspend_enabled', 'stripe_active', 'paypal_active', 'cham_cash_active', 'cham_cash_personal_active', 'syriatel_cash_active'];
        foreach ($checkboxes as $checkbox) {
            Setting::setValue($checkbox, $request->has($checkbox) ? 1 : 0);
            unset($data[$checkbox]); // Remove because we handled it
        }

        foreach ($data as $key => $value) {
            if ($value !== null) {
                Setting::setValue($key, $value);
            }
        }

        return redirect()->route('settings.index')->with('success', 'تم حفظ الإعدادات بنجاح');
    }
    public function checkSubdomain(Request $request)
    {
        $request->validate([
            'subdomain' => ['required', 'string', 'min:3', 'max:20', 'regex:/^[a-zA-Z0-9\-]+$/'],
        ]);

        $subdomain = $request->subdomain;
        $user = auth()->user();
        
        // Check if exists
        $exists = \App\Models\Tenant::where('domain', $subdomain)
            ->where('id', '!=', $user->tenant_id) // Ignore current tenant's own domain
            ->exists();

        if ($exists) {
            return response()->json(['available' => false, 'message' => 'هذا النطاق الفرعي محجوز مسبقاً']);
        }

        return response()->json(['available' => true, 'message' => 'النطاق الفرعي متاح']);
    }

    public function toggleLayout(Request $request)
    {
        $tenant = auth()->user()->tenant;
        if (!$tenant) {
            return back()->with('error', 'No tenant associated with this user.');
        }
        $settings = $tenant->settings ?? [];
        
        // Toggle Logic
        $current = $settings['layout_mode'] ?? 'wizard';
        $settings['layout_mode'] = ($current === 'wizard') ? 'enterprise' : 'wizard';
        
        $tenant->settings = $settings;
        $tenant->save();

        return back()->with('success', 'تم تغيير نمط العرض بنجاح');
    }
}
