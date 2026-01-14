<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'tenant' => \App\Http\Middleware\TenantMiddleware::class,
            'subscription' => \App\Http\Middleware\CheckSubscription::class,
            'is_admin' => \App\Http\Middleware\IsAdmin::class,
            'feature' => \App\Http\Middleware\CheckFeatureAccess::class,
        ]);
        
        $middleware->validateCsrfTokens(except: [
            '/api/routers/*',  // Exclude Router Sync from CSRF
            'payment/*',       // Good practice for payment webhooks
        ]);
        
        $middleware->redirectTo(function ($request) {
            $host = $request->getHost();
            $isMainApp = in_array($host, ['localhost', '127.0.0.1', 'madaaq.com', 'www.madaaq.com']);
            
            if (!$isMainApp) {
                return route('portal.login', ['tenant_domain' => $host]);
            }
            
            return route('login');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
