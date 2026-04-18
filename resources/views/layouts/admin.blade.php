<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>MadaaQ | @yield('title', 'Intelligence Hub')</title>
    
    <!-- Fonts: High Intensity Matrix -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@300;400;500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    
    <!-- Radiant Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 300, 'GRAD' 0, 'opsz' 24; font-size: 20px; }
    </style>
</head>
<body class="bg-surface-light text-slate-800 font-body antialiased overflow-x-hidden" x-data="{ sidebarOpen: true }">
    
    <!-- Radiant Sidebar Shell -->
    <aside class="fixed inset-y-0 right-0 z-50 w-72 bg-slate-900 shadow-2xl transition-transform duration-500 ease-out lg:translate-x-0"
           :class="sidebarOpen ? 'translate-x-0' : 'translate-x-full'">
        
        <!-- Branding Pulse -->
        <div class="h-24 flex items-center px-10 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-accent-gradient opacity-10 blur-3xl -mr-16 -mt-16"></div>
            <div class="relative z-10">
                <h1 class="text-3xl font-black text-white tracking-tighter italic">مدى كيو</h1>
                <p class="text-[9px] font-black text-neon-cyan uppercase tracking-[0.3em] mt-1 font-headline opacity-60">Intelligence Hub v3</p>
            </div>
        </div>

        <!-- Navigation Matrix -->
        <nav class="mt-8 space-y-2 px-4 selection:bg-neon-cyan/30">
            <p class="px-6 text-[10px] font-black text-slate-500 uppercase tracking-widest mb-4 italic">Core Governance</p>
            
            <a href="{{ route('admin.dashboard') }}" 
               class="nav-item group @if(request()->routeIs('admin.dashboard')) nav-item-active @else nav-item-inactive @endif">
                <span class="material-symbols-outlined group-hover:rotate-12 transition-transform">dashboard</span>
                <span class="text-xs uppercase font-black tracking-widest">لوحة القيادة</span>
            </a>

            <p class="px-6 text-[10px] font-black text-slate-500 uppercase tracking-widest mt-10 mb-4 italic">Asset Management</p>

            <a href="{{ route('admin.tenants.index') }}" 
               class="nav-item group @if(request()->routeIs('admin.tenants.*') || request()->routeIs('crm.clients.*')) nav-item-active @else nav-item-inactive @endif">
                <span class="material-symbols-outlined">group</span>
                <span class="text-xs uppercase font-black tracking-widest">المشتركين والعملاء</span>
            </a>

            <a href="{{ route('servers.index') }}" 
               class="nav-item group @if(request()->routeIs('servers.*') || request()->routeIs('routers.*')) nav-item-active @else nav-item-inactive @endif">
                <span class="material-symbols-outlined">dns</span>
                <span class="text-xs uppercase font-black tracking-widest">البنية التحتية</span>
            </a>

            <a href="{{ route('network.live-monitoring') }}" 
               class="nav-item group @if(request()->routeIs('network.live-monitoring')) nav-item-active @else nav-item-inactive @endif">
                <span class="material-symbols-outlined">leak_add</span>
                <span class="text-xs uppercase font-black tracking-widest">المراقبة الحية</span>
            </a>

            <p class="px-6 text-[10px] font-black text-slate-500 uppercase tracking-widest mt-10 mb-4 italic">System Registry</p>

            <a href="{{ route('admin.settings.index') }}" 
               class="nav-item group @if(request()->routeIs('admin.settings.*')) nav-item-active @else nav-item-inactive @endif">
                <span class="material-symbols-outlined">tune</span>
                <span class="text-xs uppercase font-black tracking-widest">الإعدادات</span>
            </a>
        </nav>

        <!-- Profile Decommission -->
        <div class="absolute bottom-6 w-full px-8">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center justify-between p-4 bg-white/5 border border-white/5 rounded-2xl text-slate-400 hover:text-error hover:bg-error/10 hover:border-error/20 transition-all group">
                    <span class="text-[10px] font-black uppercase tracking-widest">Sign Out Hub</span>
                    <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">logout</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Content Matrix -->
    <div class="transition-all duration-500 ease-in-out" :class="sidebarOpen ? 'mr-72' : 'mr-0'">
        
        <!-- Floating Glass Header -->
        <header class="sticky top-0 z-40 h-20 px-10 flex items-center justify-between glass-panel mx-6 mt-4 rounded-2xl shadow-2xl shadow-primary/5">
            <div class="flex items-center gap-6">
                <button @click="sidebarOpen = !sidebarOpen" class="w-10 h-10 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-center text-slate-400 hover:text-primary hover:border-primary transition-all shadow-sm">
                    <span class="material-symbols-outlined" x-text="sidebarOpen ? 'menu_open' : 'menu'"></span>
                </button>
                <div class="h-8 w-px bg-slate-200 hidden md:block"></div>
                <div class="relative hidden md:block group">
                    <input type="text" placeholder="Quick Command Discovery..." 
                           class="bg-slate-50 border-none rounded-xl pr-12 pl-6 py-2.5 text-[11px] font-black uppercase tracking-widest text-slate-500 focus:ring-4 focus:ring-primary/5 w-80 transition-all">
                    <span class="material-symbols-outlined absolute right-4 top-2 text-slate-300 group-hover:text-primary transition-colors">search</span>
                </div>
            </div>

            <div class="flex items-center gap-6">
                <!-- Status Notifications -->
                <div class="flex items-center gap-2">
                    <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 relative">
                        <span class="material-symbols-outlined">notifications</span>
                        <span class="absolute top-2.5 right-2.5 w-2 h-2 bg-error rounded-full border-2 border-white"></span>
                    </div>
                </div>

                <!-- Admin Identity -->
                <div class="flex items-center gap-4 pl-2">
                    <div class="text-left hidden sm:block">
                        <p class="text-[11px] font-black text-primary uppercase italic tracking-tighter leading-none">{{ Auth::user()->name }}</p>
                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-[0.2em] mt-1">Authorized Architect</p>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-accent-gradient p-0.5 shadow-glow-purple">
                        <div class="w-full h-full bg-slate-900 rounded-[14px] flex items-center justify-center font-black text-white text-sm">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Operational Content -->
        <main class="p-10 min-h-screen animate-in fade-in slide-in-from-bottom-4 duration-700">
            @if(session('success'))
                <div class="mb-10 p-6 glass-card bg-emerald-500/10 border-emerald-500/20 rounded-2xl flex items-center gap-5">
                    <div class="w-10 h-10 bg-emerald-500 rounded-xl flex items-center justify-center text-white shadow-glow-cyan">
                        <span class="material-symbols-outlined">verified</span>
                    </div>
                    <div>
                        <p class="text-xs font-black text-emerald-600 uppercase tracking-widest">Protocol Executed Successfully</p>
                        <p class="text-[10px] font-bold text-slate-600 mt-0.5">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <div class="max-w-7xl mx-auto">
                @yield('content')
            </div>
        </main>

        <!-- Footer Registry -->
        <footer class="px-10 py-8 border-t border-slate-200/50 flex flex-col md:flex-row items-center justify-between gap-4">
            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest italic">MadaaQ ISP Management Systems &copy; 2026</p>
            <div class="flex items-center gap-6">
                <a href="#" class="text-[9px] font-black text-slate-400 hover:text-primary transition-colors uppercase tracking-widest">Registry Terms</a>
                <a href="#" class="text-[9px] font-black text-slate-400 hover:text-primary transition-colors uppercase tracking-widest">Core Status</a>
            </div>
        </footer>
    </div>

</body>
</html>
