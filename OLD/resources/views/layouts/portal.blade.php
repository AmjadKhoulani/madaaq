<!DOCTYPE html>
<html lang="ar" dir="rtl" class="h-full scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'MadaaQ') }} - بوابة المشترك</title>
    
    <!-- Google Fonts: Tajawal, Rubik, Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800;900&family=Rubik:wght@300;400;500;600;700;800;900&family=Tajawal:wght@300;400;500;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>[x-cloak] { display: none !important; }</style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="h-full selection:bg-customer/10 selection:text-customer antialiased relative overflow-x-hidden bg-slate-50">
    
    <!-- Customer Focused Background Orbs -->
    <div class="bg-orb top-[-10%] left-[-10%] w-[40%] h-[40%] bg-customer/5"></div>
    <div class="bg-orb bottom-[-10%] right-[-10%] w-[30%] h-[30%] bg-blue-400/5"></div>

    <!-- Navigation Architecture -->
    <header class="sticky top-0 z-50 bg-white/70 backdrop-blur-md border-b border-slate-200/60">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center w-11 h-11 rounded-2xl bg-radiant-indigo text-white shadow-lg shadow-customer/20 transform rotate-2">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <div>
                    <span class="text-2xl font-black text-slate-900 tracking-tighter">بوابة<span class="text-customer">المشتركين</span></span>
                </div>
            </div>

            <div class="flex items-center gap-6">
                <!-- Desktop Navigation -->
                <nav class="hidden md:flex items-center gap-2">
                    <a href="{{ route('portal.dashboard', ['tenant_domain' => request()->getHost()]) }}" 
                       class="px-5 py-2.5 rounded-xl text-sm font-bold transition-all duration-300 {{ request()->routeIs('portal.dashboard') ? 'bg-customer text-white shadow-lg shadow-customer/20' : 'text-slate-500 hover:text-customer hover:bg-customer/5' }}">الرئيسية</a>
                    
                    <a href="{{ route('portal.invoices.index', ['tenant_domain' => request()->getHost()]) }}" 
                       class="px-5 py-2.5 rounded-xl text-sm font-bold transition-all duration-300 {{ request()->routeIs('portal.invoices.*') ? 'bg-customer text-white shadow-lg shadow-customer/20' : 'text-slate-500 hover:text-customer hover:bg-customer/5' }}">الفواتير</a>
                </nav>
                
                <div class="h-8 w-px bg-slate-200 hidden md:block"></div>

                <!-- User Context -->
                <div class="flex items-center gap-4">
                    <div class="hidden sm:block text-left">
                        <p class="text-xs font-black text-slate-900 leading-tight">{{ Auth::guard('client')->user()->username }}</p>
                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Subscriber Account</p>
                    </div>
                    
                    <form method="POST" action="{{ route('portal.logout', ['tenant_domain' => request()->getHost()]) }}">
                        @csrf
                        <button type="submit" class="p-2.5 rounded-xl bg-slate-100 text-slate-400 hover:text-red-500 hover:bg-red-50 transition-all">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/></svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content Mainframe -->
    <main class="max-w-7xl mx-auto px-6 py-12">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200/60 py-12">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">&copy; {{ date('Y') }} MadaaQ ISP Management Systems</p>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
