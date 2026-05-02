<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class TenantMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Check for Subdomain (Vendor Client Portal)
        $host = $request->getHost();
        $domainParts = explode('.', $host);
        
        // Assuming structure: subdomain.domain.com OR subdomain.localhost
        // If 3 parts (s.d.c) -> subdomain is [0]
        // If 2 parts (d.c) or (s.l) -> could be tricky locally. 
        // Logic: if host is NOT matching config('app.url') host, and has a subdomain.
        
        $appUrl = config('app.url'); // e.g., http://localhost:8000
        $appHost = parse_url($appUrl, PHP_URL_HOST); // localhost

        if ($host !== $appHost) {
            // 1. Try finding by Full Domain (e.g., demo.test)
            $tenant = \App\Models\Tenant::where('domain', $host)->first();
            
            // 2. If not found, try by Subdomain (e.g., vendor1.localhost)
            if (!$tenant && count($domainParts) > 0) {
                $subdomain = $domainParts[0];
                $tenant = \App\Models\Tenant::where('domain', $subdomain)->first();
            }
            
            if ($tenant) {
                // Check if Subdomain feature is enabled for this tenant
                if (!$tenant->is_subdomain_enabled) {
                    abort(404); // Or redirect to main domain
                }

                // Bind tenant to container context for the Client Portal
                app()->instance('tenant', $tenant);
                app()->instance('tenant.id', $tenant->id);
                // Share with all views
                view()->share('currentTenant', $tenant); 
                return $next($request);
            }
        }

        // 2. Fallback: Authenticated User (Vendor Dashboard)
        if (\Illuminate\Support\Facades\Auth::check()) {
            $user = \Illuminate\Support\Facades\Auth::user();
            if ($user->tenant_id) {
                app()->instance('tenant.id', $user->tenant_id);
            }
        }

        return $next($request);
    }
}
