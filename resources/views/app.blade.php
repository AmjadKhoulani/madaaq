<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl" class="h-full scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'MadaaQ') }}</title>

        <!-- Google Fonts: Tajawal, Rubik, Inter -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800;900&family=Rubik:wght@300;400;500;600;700;800;900&family=Tajawal:wght@300;400;500;700;800;900&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="h-full selection:bg-vendor/10 selection:text-vendor antialiased bg-slate-50 overflow-x-hidden font-rubik relative">
        <!-- Persistent Floating Orbs for the entire Inertia App -->
        <div class="bg-orb top-[-10%] left-[-10%] w-[45%] h-[45%] bg-vendor/10"></div>
        <div class="bg-orb bottom-[-10%] right-[-10%] w-[35%] h-[35%] bg-blue-400/10"></div>
        
        @inertia
    </body>
</html>
