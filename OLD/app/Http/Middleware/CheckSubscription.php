<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && !$user->hasAccess()) {
            // If API request, return 403
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Subscription required.'], 403);
            }

            // Redirect to pricing page
            return redirect()->route('subscription.index')->with('error', 'عذراً، يجب عليك اختيار باقة اشتراك أو بدء فترة تجريبية للوصول إلى هذه الصفحة.');
        }

        return $next($request);
    }
}
