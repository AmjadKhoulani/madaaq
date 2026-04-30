<!DOCTYPE html>
<html lang="ar" dir="rtl" class="h-full scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'MadaaQ') }} - Super Admin Control</title>
    
    <!-- Google Fonts: Tajawal, Rubik, Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800;900&family=Rubik:wght@300;400;500;600;700;800;900&family=Tajawal:wght@300;400;500;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>[x-cloak] { display: none !important; }</style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="h-full selection:bg-super-admin/10 selection:text-super-admin antialiased lg:flex relative overflow-x-hidden bg-slate-950" x-data="{ sidebarOpen: false }">
    
    <!-- Super Admin High-End Background -->
    <div class="bg-orb top-[-10%] left-[-10%] w-[50%] h-[50%] bg-super-admin/10"></div>
    <div class="bg-orb bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-purple-500/10"></div>

    <!-- Mobile Sidebar Backdrop -->
    <div x-show="sidebarOpen" 
         x-transition:enter="transition-opacity ease-linear duration-300" 
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100" 
         x-transition:leave="transition-opacity ease-linear duration-300" 
         x-transition:leave-start="opacity-100" 
         x-transition:leave-end="opacity-0" 
         class="fixed inset-0 bg-slate-950/60 backdrop-blur-md z-40 lg:hidden" 
         @click="sidebarOpen = false"></div>

    <!-- Sidebar: Dark Glass Mode -->
    <aside :class="sidebarOpen ? 'translate-x-0' : 'translate-x-full lg:translate-x-0'" 
           class="fixed inset-y-0 right-0 z-50 w-72 glass-sidebar-dark transition-transform duration-500 cubic-bezier(0.4, 0, 0.2, 1) lg:static lg:flex lg:flex-col">
        
        <!-- Branding Unit -->
        <div class="flex h-20 shrink-0 items-center gap-3 px-8 border-b border-white/5">
            <div class="flex items-center justify-center w-11 h-11 rounded-2xl bg-radiant-purple text-white shadow-lg shadow-super-admin/30 transform rotate-2">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            </div>
            <div>
                <span class="text-2xl font-black tracking-tighter text-white">مدى<span class="text-super-admin">Control</span></span>
                <p class="text-[9px] uppercase tracking-[0.2em] text-slate-500 font-black -mt-1 font-inter">SUPER ADMIN UNIT</p>
            </div>
            
            <button @click="sidebarOpen = false" class="mr-auto lg:hidden text-slate-500 hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Navigation Matrix -->
        <nav class="flex-1 overflow-y-auto px-4 py-6 space-y-1.5 custom-scrollbar text-slate-400">
            
            <p class="px-6 text-[10px] font-black text-slate-600 uppercase tracking-widest mb-4 italic">Core Governance</p>

            <!-- Dashboard -->
            <a href="{{ route('admin.dashboard') }}" 
               class="group flex items-center gap-x-3 rounded-2xl px-4 py-3.5 text-sm font-bold transition-all duration-300 {{ request()->routeIs('admin.dashboard') ? 'bg-super-admin text-white shadow-admin-glow translate-x-[-4px]' : 'hover:bg-white/5 hover:text-white' }}">
                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25a2.25 2.25 0 01-2.25 2.25h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25h-2.25a2.25 2.25 0 01-2.25-2.25v-2.25z" />
                </svg>
                التحليل العالمي
            </a>

            <!-- Tenants Management -->
            <a href="{{ route('admin.tenants.index') }}" 
               class="group flex items-center gap-x-3 rounded-2xl px-4 py-3.5 text-sm font-bold transition-all duration-300 {{ request()->routeIs('admin.tenants.*') ? 'bg-super-admin text-white shadow-admin-glow translate-x-[-4px]' : 'hover:bg-white/5 hover:text-white' }}">
                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                </svg>
                إدارة المزودين
            </a>

            <!-- Global Invoices -->
            <a href="{{ route('admin.subscriptions.index') }}" 
               class="group flex items-center gap-x-3 rounded-2xl px-4 py-3.5 text-sm font-bold transition-all duration-300 {{ request()->routeIs('admin.subscriptions.*') ? 'bg-super-admin text-white shadow-admin-glow translate-x-[-4px]' : 'hover:bg-white/5 hover:text-white' }}">
                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75-10.5h16.5a2.25 2.25 0 012.25 2.25v9a2.25 2.25 0 01-2.25 2.25H3.75a2.25 2.25 0 01-2.25-2.25v-9a2.25 2.25 0 012.25-2.25z" />
                </svg>
                الطلبات والاشتراكات
            </a>

            <p class="px-6 text-[10px] font-black text-slate-600 uppercase tracking-widest mt-10 mb-4 italic">Registry & Support</p>

            <!-- Global Reports -->
            <a href="{{ route('admin.reports.index') }}" 
               class="group flex items-center gap-x-3 rounded-2xl px-4 py-3.5 text-sm font-bold transition-all duration-300 {{ request()->routeIs('admin.reports.*') ? 'bg-super-admin text-white shadow-admin-glow translate-x-[-4px]' : 'hover:bg-white/5 hover:text-white' }}">
                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                </svg>
                التقارير المالية
            </a>

            <!-- System Settings -->
            <a href="{{ route('admin.settings.index') }}" 
               class="group flex items-center gap-x-3 rounded-2xl px-4 py-3.5 text-sm font-bold transition-all duration-300 {{ request()->routeIs('admin.settings.*') ? 'bg-super-admin text-white shadow-admin-glow translate-x-[-4px]' : 'hover:bg-white/5 hover:text-white' }}">
                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12a7.5 7.5 0 0015 0m-15 0a7.5 7.5 0 1115 0m-15 0H3m16.5 0H21m-1.5 0H12m-8.457 3.077l1.41-.513m14.095-5.13l1.41-.513M5.106 17.785l1.15-.964m11.49-9.642l1.149-.964M7.501 19.795l.75-1.3m7.5-12.99l.75-1.3m-10.607 10.607l.513-1.41m5.13-14.095l.513-1.41M17.785 5.106l-.964 1.15m-9.642 11.49l-.964 1.149m-1.3-7.501l-1.3.75m12.99 7.5l-1.3.75" />
                </svg>
                إعدادات المنصة
            </a>

        </nav>
        
        <!-- Super Admin Profile Section -->
        <div class="border-t border-white/5 p-6">
            <div class="flex items-center gap-4 px-2">
                <div class="h-11 w-11 shrink-0 rounded-2xl bg-radiant-purple flex items-center justify-center text-white font-black shadow-lg">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <div class="flex-1 min-w-0 text-white">
                    <p class="truncate text-sm font-black">{{ auth()->user()->name }}</p>
                    <p class="truncate text-[10px] text-super-admin font-black uppercase tracking-widest">Global Architect</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-slate-500 hover:text-red-500 transition-colors">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/></svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Super Admin Content Matrix -->
    <div class="flex flex-1 flex-col h-full min-w-0 bg-slate-950">
        
        <!-- Deep Space Header -->
        <header class="flex h-20 shrink-0 items-center justify-between gap-x-6 bg-slate-900/40 backdrop-blur-md px-8 border-b border-white/5">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = true" class="p-2 text-slate-500 lg:hidden">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" /></svg>
                </button>
                <h2 class="text-xl font-black text-white tracking-tighter">وحدة الإشراف العليا <span class="text-super-admin mx-2">|</span> <span class="text-slate-400 text-sm font-bold uppercase tracking-widest">Intelligence Hub</span></h2>
            </div>
            
            <div class="flex items-center gap-x-6">
                <div class="flex items-center gap-3 px-4 py-2 rounded-2xl bg-super-admin/5 border border-super-admin/10">
                    <span class="flex h-2 w-2 rounded-full bg-super-admin animate-pulse"></span>
                    <span class="text-[10px] font-black text-super-admin uppercase tracking-[0.2em]">All Systems Online</span>
                </div>
            </div>
        </header>

        <!-- Main Operational Mainframe -->
        <main class="flex-1 overflow-y-auto p-8 custom-scrollbar">
            
            <!-- Global Protocol Alerts -->
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="mb-8 glass-card bg-emerald-500/10 border-emerald-500/20 p-5 flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-emerald-500 flex items-center justify-center text-white shadow-lg shadow-emerald-500/20">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <div>
                        <p class="text-xs font-black text-emerald-400 uppercase tracking-widest">Protocol Executed</p>
                        <p class="text-sm font-bold text-slate-300 mt-0.5">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <div class="max-w-[1600px] mx-auto">
                @yield('content')
            </div>
        </main>
    </div>
    
    @stack('scripts')
</body>
</html>
