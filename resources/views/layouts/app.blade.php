<!DOCTYPE html>
<html lang="ar" dir="rtl" class="h-full scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'MadaaQ') }} - Vendor Dashboard</title>
    
    <!-- Google Fonts: Tajawal (Titles), Rubik (UI), Inter (Numbers) -->
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
<body class="h-full selection:bg-vendor/10 selection:text-vendor antialiased lg:flex relative overflow-x-hidden" x-data="{ sidebarOpen: false }">
    
    <!-- Premium Background Experience -->
    <div class="bg-orb top-[-10%] left-[-10%] w-[45%] h-[45%] bg-vendor/10"></div>
    <div class="bg-orb bottom-[-10%] right-[-10%] w-[35%] h-[35%] bg-blue-400/10"></div>
    <div class="bg-orb top-[20%] right-[5%] w-[20%] h-[20%] bg-purple-400/5"></div>

    <!-- Mobile Sidebar Backdrop -->
    <div x-show="sidebarOpen" 
         x-transition:enter="transition-opacity ease-linear duration-300" 
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100" 
         x-transition:leave="transition-opacity ease-linear duration-300" 
         x-transition:leave-start="opacity-100" 
         x-transition:leave-end="opacity-0" 
         class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-40 lg:hidden" 
         @click="sidebarOpen = false"></div>

    <!-- Sidebar: Glass Architecture -->
    <aside :class="sidebarOpen ? 'translate-x-0' : 'translate-x-full lg:translate-x-0'" 
           class="fixed inset-y-0 right-0 z-50 w-72 glass-sidebar transition-transform duration-500 cubic-bezier(0.4, 0, 0.2, 1) lg:static lg:flex lg:flex-col">
        
        <!-- Logo Section -->
        <div class="flex h-20 shrink-0 items-center gap-3 px-8 border-b border-white/40">
            <div class="flex items-center justify-center w-11 h-11 rounded-2xl bg-radiant-indigo text-white shadow-lg shadow-vendor/30 transform rotate-2">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
            <div>
                <span class="text-2xl font-black tracking-tighter text-slate-900">مدى<span class="text-vendor">Q</span></span>
                <p class="text-[9px] uppercase tracking-[0.2em] text-slate-400 font-black -mt-1 font-inter">ISP COMMAND CENTER</p>
            </div>
            
            <button @click="sidebarOpen = false" class="mr-auto lg:hidden text-slate-400 hover:text-vendor transition-colors">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Main Navigation -->
        <nav class="flex-1 overflow-y-auto px-4 py-6 space-y-1.5 custom-scrollbar">
            
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}" 
               class="group flex items-center gap-x-3 rounded-2xl px-4 py-3.5 text-sm font-bold transition-all duration-300 {{ request()->routeIs('dashboard') ? 'bg-vendor text-white shadow-vendor-glow translate-x-[-4px]' : 'text-slate-500 hover:bg-white/60 hover:text-vendor' }}">
                <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-slate-400 group-hover:text-vendor' }}" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                </svg>
                لوحة التحكم
            </a>

            <!-- Menu Group: Network -->
            <div x-data="{ open: {{ request()->routeIs('network.*', 'servers.*', 'routers.*') ? 'true' : 'false' }} }" class="pt-3">
                <button @click="open = !open" class="flex w-full items-center justify-between rounded-2xl px-4 py-3.5 text-sm font-bold text-slate-600 hover:bg-white/60 hover:text-vendor group transition-all">
                    <div class="flex items-center gap-x-3">
                        <svg class="h-5 w-5 shrink-0 text-slate-400 group-hover:text-vendor" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 17.25v-.228a4.5 4.5 0 00-.12-1.03l-2.268-9.64a3.375 3.375 0 00-3.285-2.602H7.923a3.375 3.375 0 00-3.285 2.602l-2.268 9.64a4.5 4.5 0 00-.12 1.03v.228m19.5 0a3 3 0 01-3 3H5.25a3 3 0 01-3-3m19.5 0a3 3 0 00-3-3H5.25a3 3 0 00-3 3m16.5 0h.008v.008h-.008v-.008zm-3 0h.008v.008h-.008v-.008z" />
                        </svg>
                        إدارة الشبكة
                    </div>
                    <svg class="h-4 w-4 shrink-0 text-slate-400 transition-transform duration-300" :class="open ? 'rotate-180' : ''" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" /></svg>
                </button>
                <div x-show="open" x-collapse x-cloak class="mt-1 space-y-1 pr-9">
                    <a href="{{ route('servers.index') }}" class="block rounded-xl py-2.5 pr-4 text-[13px] font-bold {{ request()->routeIs('servers.*') ? 'text-vendor bg-vendor/5 border-r-2 border-vendor' : 'text-slate-500 hover:text-vendor hover:bg-white/40' }}">السيرفرات المركزية</a>
                    <a href="{{ route('network.towers.index') }}" class="block rounded-xl py-2.5 pr-4 text-[13px] font-bold {{ request()->routeIs('network.towers.*') ? 'text-vendor bg-vendor/5 border-r-2 border-vendor' : 'text-slate-500 hover:text-vendor hover:bg-white/40' }}">إدارة الأبراج والمواقع</a>
                    <a href="{{ route('maps.index') }}" class="block rounded-xl py-2.5 pr-4 text-[13px] font-bold {{ request()->routeIs('maps.index') ? 'text-vendor bg-vendor/5 border-r-2 border-vendor' : 'text-slate-500 hover:text-vendor hover:bg-white/40' }}">الخريطة الجغرافية</a>
                    <a href="{{ route('routers.index') }}" class="block rounded-xl py-2.5 pr-4 text-[13px] font-bold {{ request()->routeIs('routers.*') ? 'text-vendor bg-vendor/5 border-r-2 border-vendor' : 'text-slate-500 hover:text-vendor hover:bg-white/40' }}">تجهيزات المحطات</a>
                </div>
            </div>

            <!-- Menu Group: Users & Services -->
            <div x-data="{ open: {{ request()->routeIs('crm.*', 'broadband.*', 'hotspot.*') ? 'true' : 'false' }} }" class="pt-3">
                <button @click="open = !open" class="flex w-full items-center justify-between rounded-2xl px-4 py-3.5 text-sm font-bold text-slate-600 hover:bg-white/60 hover:text-vendor group transition-all">
                    <div class="flex items-center gap-x-3">
                        <svg class="h-5 w-5 shrink-0 text-slate-400 group-hover:text-vendor" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                        العملاء والخدمات
                    </div>
                    <svg class="h-4 w-4 shrink-0 text-slate-400 transition-transform duration-300" :class="open ? 'rotate-180' : ''" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" /></svg>
                </button>
                <div x-show="open" x-collapse x-cloak class="mt-1 space-y-1 pr-9">
                    <a href="{{ route('crm.clients.index') }}" class="block rounded-xl py-2.5 pr-4 text-[13px] font-bold {{ request()->routeIs('crm.clients.*') ? 'text-vendor bg-vendor/5 border-r-2 border-vendor' : 'text-slate-500 hover:text-vendor hover:bg-white/40' }}">دليل المشتركين</a>
                    <a href="{{ route('broadband.users.index') }}" class="block rounded-xl py-2.5 pr-4 text-[13px] font-bold {{ request()->routeIs('broadband.users.*') ? 'text-vendor bg-vendor/5 border-r-2 border-vendor' : 'text-slate-500 hover:text-vendor hover:bg-white/40' }}">اشتراكات البرودباند</a>
                    <a href="{{ route('hotspot.vouchers.index') }}" class="block rounded-xl py-2.5 pr-4 text-[13px] font-bold {{ request()->routeIs('hotspot.vouchers.*') ? 'text-vendor bg-vendor/5 border-r-2 border-vendor' : 'text-slate-500 hover:text-vendor hover:bg-white/40' }}">كروت الهوتسبوت</a>
                </div>
            </div>

            <!-- Finance -->
            <a href="{{ route('accounting.invoices.index') }}" 
               class="group flex items-center gap-x-3 rounded-2xl px-4 py-3.5 text-sm font-bold transition-all duration-300 {{ request()->routeIs('accounting.invoices.*') ? 'bg-vendor text-white shadow-vendor-glow translate-x-[-4px]' : 'text-slate-500 hover:bg-white/60 hover:text-vendor' }}">
                <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('accounting.invoices.*') ? 'text-white' : 'text-slate-400 group-hover:text-vendor' }}" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75a.75.75 0 01-.75-.75V15m.75 0H3m0 0h-.375a.75.75 0 01-.75-.75V5.625a.75.75 0 01.75-.75H3.75m1.5 0h.375a.75.75 0 01.75.75v9.75a.75.75 0 01-.75.75h-.375m-.75 0H4.5m0 0v.75c0 .414.336.75.75.75H5.25m0 0h13.5m0-13.5h.375c.621 0 1.125.504 1.125 1.125v6.75c0 .621-.504 1.125-1.125 1.125H18.75A.75.75 0 0118 14.25V5.25a.75.75 0 00-.75-.75h-.375m0 0H6.375" />
                </svg>
                المالية والفواتير
            </a>

            <!-- Support -->
            <a href="{{ route('support.index') }}" 
               class="group flex items-center gap-x-3 rounded-2xl px-4 py-3.5 text-sm font-bold transition-all duration-300 {{ request()->routeIs('support.*') ? 'bg-vendor text-white shadow-vendor-glow translate-x-[-4px]' : 'text-slate-500 hover:bg-white/60 hover:text-vendor' }}">
                <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('support.*') ? 'text-white' : 'text-slate-400 group-hover:text-vendor' }}" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z" />
                </svg>
                الدعم الفني
            </a>

        </nav>
        
        <!-- User Context: Premium Sidebar Bottom -->
        <div class="border-t border-white/40 p-6">
            <div class="flex items-center gap-4 px-2">
                <div class="h-11 w-11 shrink-0 rounded-2xl bg-radiant-indigo flex items-center justify-center text-white font-black shadow-lg">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="truncate text-sm font-black text-slate-900">{{ auth()->user()->name }}</p>
                    <p class="truncate text-[10px] text-slate-400 font-bold uppercase tracking-widest">{{ auth()->user()->roles->first()->name ?? 'Vendor' }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-slate-300 hover:text-red-500 transition-colors">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/></svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Viewport -->
    <div class="flex flex-1 flex-col h-full min-w-0">
        
        <!-- Ultra-Clean Header -->
        <header class="flex h-20 shrink-0 items-center justify-between gap-x-6 bg-white/40 backdrop-blur-md px-8 border-b border-white/60">
            
            <!-- Global Search -->
            <div class="flex flex-1 items-center gap-x-4">
                <button @click="sidebarOpen = true" class="p-2 text-slate-400 lg:hidden">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" /></svg>
                </button>
                
                <div class="relative w-full max-w-md hidden sm:block">
                    <svg class="absolute inset-y-0 right-4 h-full w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
                    <input class="block w-full border-0 bg-white/50 rounded-2xl py-3 pr-11 text-sm font-bold placeholder:text-slate-400 focus:ring-4 focus:ring-vendor/5 focus:bg-white transition-all shadow-sm" placeholder="ابحث عن محطة، مشترك، أو عنوان IP..." type="search">
                </div>
            </div>

            <!-- Actions & Profile -->
            <div class="flex items-center gap-x-6">
                <!-- Notifications -->
                <button class="relative text-slate-400 hover:text-vendor transition-colors">
                    <span class="absolute -top-1 -right-1 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-[10px] font-black text-white ring-2 ring-white">3</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" /></svg>
                </button>

                <div class="h-8 w-px bg-slate-200"></div>

                <!-- Fast Actions -->
                <a href="{{ route('crm.clients.create') }}" class="hidden md:flex items-center gap-2 btn-radiant btn-vendor px-5 py-2.5 text-xs font-black uppercase tracking-wider">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                    إضافة مشترك
                </a>
            </div>
        </header>

        <!-- Main Content Core -->
        <main class="flex-1 overflow-y-auto p-8 custom-scrollbar">
            
            <!-- Dynamic Flash Alerts -->
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="mb-8 glass-card bg-emerald-50/80 border-emerald-200/50 p-4">
                    <div class="flex items-center gap-3">
                        <div class="flex-shrink-0 text-emerald-500"><svg class="h-6 w-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg></div>
                        <p class="text-sm font-bold text-emerald-800">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="mb-8 glass-card bg-red-50/80 border-red-200/50 p-4">
                    <div class="flex items-center gap-3">
                        <div class="flex-shrink-0 text-red-500"><svg class="h-6 w-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" /></svg></div>
                        <p class="text-sm font-bold text-red-800">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
    
    @stack('scripts')
</body>
</html>
