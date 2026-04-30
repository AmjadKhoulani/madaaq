<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;
use Illuminate\Support\Facades\Auth;

class ConfigController extends Controller
{
    /**
     * Get Branding Configuration
     * This endpoint should be callable either:
     * 1. After login (Authenticated) -> Fetches tenant from user.
     * 2. Before login (Unauthenticated) -> IF we have a way to identify tenant (e.g. subdomain or special app ID).
     * 
     * For now, the user said "White-label... branding details based on settings".
     * Since the app is generic, it might need to know the tenant BEFORE login to show the correct logo/color on login screen?
     * OR the user enters a "Code" or simply the login screen is generic, and AFTER login the app themes itself?
     * The prompt says: "Client (End User) belongs to Vendor... App fetches branding...".
     * Usually, in mobile apps, you might have a "Company Code" input first, or it's just generic until login.
     * Let's implement it as Authenticated for now, or require a 'tenant_id' parameter if public.
     * 
     * Strategy:
     * - Authenticated: Return current user's tenant branding.
     * - Unauthenticated: User might provide 'domain' or 'code' to fetch branding.
     */
    public function index(Request $request)
    {
        $tenant = null;

        if (Auth::guard('sanctum')->check()) {
            $client = Auth::guard('sanctum')->user();
            if ($client->tenant) {
                $tenant = $client->tenant;
            }
        } 
        
        // Fallback or Public Check (e.g. ?domain=xyz.com)
        // if (!$tenant && $request->has('domain')) {
        //     $tenant = \App\Models\Tenant::where('domain', $request->domain)->first();
        // }

        if (!$tenant) {
            // Return Default / Super Admin Branding
            return response()->json([
                'app_name' => config('app.name'),
                'logo_url' => asset('logo.png'), 
                'primary_color' => '#4F46E5',
                'support_contact' => null,
                'is_default' => true
            ]);
        }

        return response()->json([
            'app_name' => $tenant->mobile_app_name ?? $tenant->name,
            'logo_url' => $tenant->logo ? asset('storage/' . $tenant->logo) : asset('logo.png'),
            'primary_color' => $tenant->primary_color ?? '#4F46E5',
            'support_contact' => $tenant->support_contact,
            'is_default' => false
        ]);
    }
}
