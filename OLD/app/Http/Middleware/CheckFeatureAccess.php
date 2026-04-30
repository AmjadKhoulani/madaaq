<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckFeatureAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $feature): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        if (!$user->hasFeature($feature)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'ميزة غير متاحة في باقتك الحالية. يرجى الترقية إلى Pro.'], 403);
            }
            
            return redirect()->route('subscription.index')->with('error', 'هذه الميزة متاحة فقط في الباقة الاحترافية (Pro). يرجى الترقية للمتابعة.');
        }

        return $next($request);
    }
}
