<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Command Center</title>

    <!-- Google Font - Rubik -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { font-family: 'Rubik', sans-serif; background: radial-gradient(circle at top right, #f8fafc, #f1f5f9); }
        .font-inter { font-family: 'Inter', sans-serif; }
        
        /* Premium Translucent Scrollbar */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(0, 0, 0, 0.05); border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(0, 0, 0, 0.1); }

        /* Glassmorphism Utilities */
        .glass-panel { background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.3); }
        .glass-sidebar { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(25px); -webkit-backdrop-filter: blur(25px); border-left: 1px solid rgba(255, 255, 255, 0.2); }
        
        /* Subtle Glow */
        .nav-active-glow { box-shadow: 0 0 20px -5px rgba(79, 70, 229, 0.3); }

        img { max-width: 100%; height: auto; }

        /* Fallback sizes if Tailwind CSS fails to load */
        .w-3 { width: 0.75rem; } .h-3 { height: 0.75rem; }
        .w-4 { width: 1rem; } .h-4 { height: 1rem; }
        .w-5 { width: 1.25rem; } .h-5 { height: 1.25rem; }
        .w-6 { width: 1.5rem; } .h-6 { height: 1.5rem; }
        .w-7 { width: 1.75rem; } .h-7 { height: 1.75rem; }
        .w-8 { width: 2rem; } .h-8 { height: 2rem; }
    </style>
</head>
    <div class="min-h-screen flex selection:bg-indigo-100 selection:text-indigo-900 overflow-hidden" x-data="{ sidebarOpen: true }">
        
        <!-- Sidebar -->
        <aside class="fixed inset-y-0 right-0 z-50 w-72 glass-sidebar transition-transform duration-500 ease-in-out transform lg:translate-x-0 lg:static lg:inset-0 shadow-[20px_0_50px_-30px_rgba(0,0,0,0.05)]"
               :class="sidebarOpen ? 'translate-x-0' : 'translate-x-full'">
            
            <!-- Logo Section -->
            <div class="h-20 flex items-center px-8 border-b border-white/20 bg-white/30">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-600 to-blue-600 flex items-center justify-center text-white font-black shadow-lg shadow-indigo-200/50 transform -rotate-3">
                        M
                    </div>
                    <div>
                        <span class="text-xl font-black tracking-tight text-gray-900">Madaa<span class="text-indigo-600 font-extrabold italic">Q</span></span>
                        <p class="text-[9px] uppercase tracking-widest text-indigo-400 font-bold -mt-1 font-inter">Control Panel</p>
                    </div>
                </div>
            </div>

            <!-- Nav -->
            <nav class="flex-1 px-3 py-6 space-y-1 overflow-y-auto">
                <p class="px-3 text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">النظام</p>
                
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center px-4 py-3 rounded-xl text-sm font-bold transition-all duration-300 group {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 text-white nav-active-glow translate-x-[-4px]' : 'text-gray-500 hover:text-indigo-600 hover:bg-white/50' }}">
                    <svg class="w-5 h-5 ml-3 {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-gray-400 group-hover:text-indigo-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    لوحة القيادة
                </a>

                <p class="px-3 text-xs font-bold text-gray-400 uppercase tracking-wider mt-6 mb-2">إدارة المشتركين</p>

                <a href="{{ route('admin.tenants.index') }}" 
                   class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-colors group {{ request()->routeIs('admin.tenants.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 ml-3 {{ request()->routeIs('admin.tenants.*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    الشركات (Tenants)
                </a>

                <a href="{{ route('admin.subscriptions.index') }}" 
                   class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-colors group {{ request()->routeIs('admin.subscriptions.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 ml-3 {{ request()->routeIs('admin.subscriptions.*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    الاشتراكات والمدفوعات
                </a>

                <a href="{{ route('admin.users.index') }}" 
                   class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-colors group {{ request()->routeIs('admin.users.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 ml-3 {{ request()->routeIs('admin.users.*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    إدارة المستخدمين (Staff)
                </a>

                <a href="{{ route('admin.reports.index') }}" 
                   class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-colors group {{ request()->routeIs('admin.reports.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 ml-3 {{ request()->routeIs('admin.reports.*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z"/></svg>
                    مركز التقارير
                </a>

                <p class="px-3 text-xs font-bold text-gray-400 uppercase tracking-wider mt-6 mb-2">الأدوات</p>

                <a href="{{ route('admin.settings.index') }}" class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-colors group text-gray-600 hover:text-gray-900 hover:bg-gray-100">
                    <svg class="w-5 h-5 ml-3 text-gray-400 group-hover:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    الإعدادات العامة
                </a>
            </nav>
            
            <!-- User Footer -->
            <div class="p-4 border-t border-gray-200 bg-gray-50">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-xs font-bold text-indigo-600">SA</div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500">Super Admin</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2 text-xs font-medium text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        تسجيل خروج
                    </button>
                </form>
            </div>
        </aside>

        <!-- Content Wrapper -->
        <div class="flex-1 flex flex-col min-h-screen overflow-hidden">
            
            <!-- Mobile Header -->
            <header class="h-16 flex items-center justify-between px-6 lg:hidden bg-white/70 backdrop-blur-md border-b border-white/20">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center text-white font-bold">M</div>
                    <span class="text-lg font-black text-gray-900 tracking-tight">MadaaQ</span>
                </div>
                <button @click="sidebarOpen = !sidebarOpen" class="p-2 text-gray-500 hover:text-indigo-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </header>

            <!-- Main Scrollable Area -->
            <main class="flex-1 overflow-y-auto p-4 lg:p-10">
                @if(session('success'))
                    <div class="mb-8 px-6 py-4 rounded-2xl glass-panel text-gray-800 flex items-center gap-4 animate-in fade-in slide-in-from-top-4 duration-500">
                        <div class="w-10 h-10 rounded-full bg-green-500/10 border border-green-500/20 flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <div>
                            <p class="font-bold text-sm">تمت العملية بنجاح</p>
                            <p class="text-xs text-gray-500">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif
                
                <div class="max-w-7xl mx-auto">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>
</body>
</html>
