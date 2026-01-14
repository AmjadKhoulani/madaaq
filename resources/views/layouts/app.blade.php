<!DOCTYPE html>
<html lang="ar" dir="rtl" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'نظام إدارة ISP') }}</title>
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-slate-50 text-slate-900 overflow-x-hidden min-h-screen flex flex-col bg-grid-pattern" x-data="{ 
    mobileMenuOpen: false,
    darkMode: localStorage.getItem('darkMode') === 'true'
}"  
      x-init="$watch('darkMode', val => { localStorage.setItem('darkMode', val); document.body.classList.toggle('dark-mode', val); }); if(darkMode) document.body.classList.add('dark-mode')">

    <!-- Background Blobs -->
    <div class="blob w-[500px] h-[500px] rounded-full top-0 right-[-100px] opacity-30 fixed pointer-events-none"></div>
    <div class="blob w-[400px] h-[400px] rounded-full bottom-0 left-[-100px] opacity-30 bg-purple-500 animation-delay-2000 fixed pointer-events-none"></div>

    <!-- Impersonation Banner -->
    @if(session('impersonated_by'))
        <div class="bg-red-600 text-white px-4 py-2 text-center text-sm font-bold shadow-lg z-50 flex items-center justify-center gap-4 relative">
            <span>⚠️ أنت تقوم حالياً بتصفح النظام كـ {{ auth()->user()->name }}</span>
            <a href="{{ route('admin.impersonate.leave') }}" class="bg-white text-red-600 px-3 py-1 rounded-full text-xs hover:bg-gray-100 transition-colors">
                عودة لحساب المسؤول (Admin Panel)
            </a>
        </div>
    @endif

    @auth
    <!-- Horizontal Top Navbar -->
    <nav class="glass shadow-sm sticky top-0 z-50 transition-all duration-300">
        <div class="px-4">
            <!-- Main Navigation Row -->
            <div class="flex items-center justify-between h-20">
                <!-- Logo -->
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <div>
                        <h1 class="gradient-text font-bold text-xl leading-none">MadaaQ</h1>
                        <p class="text-indigo-600 text-xs font-medium">نظام إدارة الشبكات</p>
                    </div>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden lg:flex items-center gap-2 flex-1 justify-center">
                    <!-- الرئيسية -->
                    <a href="{{ route('dashboard') }}" class="px-4 py-2.5 rounded-lg text-base font-medium transition {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        🏠 الرئيسية
                    </a>
                    <div class="h-6 w-px bg-gray-300 opacity-30"></div>

                    <!-- Terminal -->
                    <a href="{{ route('network.commands.index') }}" class="px-4 py-2.5 rounded-lg text-base font-medium transition {{ request()->routeIs('network.commands.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        💻 Terminal
                    </a>
                    <div class="h-6 w-px bg-gray-300 opacity-30"></div>

                    <!-- الشبكة - Dropdown -->
                    <div class="dropdown relative">
                        <button class="px-4 py-2.5 rounded-lg text-base font-medium text-gray-700 hover:bg-gray-100 transition flex items-center gap-1.5">
                            🌐 الشبكة
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/></svg>
                        </button>
                        <div class="dropdown-menu absolute right-0 w-64 glass rounded-xl shadow-xl py-2 mt-1">
                            <a href="{{ route('hotspot.profiles.index') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm hover:bg-indigo-50 transition text-gray-700">
                                <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/></svg>
                                هوت سبوت
                            </a>
                            <a href="{{ route('hotspot.vouchers.index') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm hover:bg-indigo-50 transition text-gray-700">
                                <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 00-2 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01m-.01 4h.01"/></svg>
                                مراقبة الكروت المطبوعة
                            </a>
                            <a href="{{ route('hotspot.vouchers.create') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm hover:bg-indigo-50 transition text-gray-700">
                                <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                                طباعة كروت جديدة
                            </a>
                            <a href="{{ route('broadband.profiles.index') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm hover:bg-indigo-50 transition text-gray-700">
                                <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                برودباند
                            </a>
                            <hr class="my-1 border-gray-100">
                            <a href="{{ route('routers.index') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm hover:bg-indigo-50 transition text-gray-700">
                                <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/></svg>
                                معدات الشبكة
                            </a>
                            <a href="{{ route('servers.index') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm hover:bg-indigo-50 transition text-gray-700">
                                <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/></svg>
                                السيرفرات
                            </a>
                            <a href="{{ route('network.backups.index') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm hover:bg-indigo-50 transition text-gray-700">
                                <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                                النسخ الاحتياطية
                            </a>
                            <a href="{{ route('network.towers.index') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm hover:bg-indigo-50 transition text-gray-700">
                                <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                الأبراج
                            </a>
                            <a href="{{ route('network.internet-sources.index') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm hover:bg-indigo-50 transition text-gray-700">
                                <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                                مصادر الانترنت
                            </a>
                        </div>
                    </div>
                    <div class="h-6 w-px bg-gray-300 opacity-30"></div>

                    <!-- المراقبة - Dropdown -->
                    <div class="dropdown relative">
                        <button class="px-4 py-2.5 rounded-lg text-base font-medium text-gray-700 hover:bg-gray-100 transition flex items-center gap-1.5">
                            📊 المراقبة
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/></svg>
                        </button>
                        <div class="dropdown-menu absolute right-0 w-60 glass rounded-xl shadow-xl py-2 mt-1">
                            <a href="{{ route('network.live-monitoring') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm hover:bg-indigo-50 transition text-gray-700">
                                <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                مراقبة حية
                            </a>
                            <a href="{{ route('network.sessions.index') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm hover:bg-indigo-50 transition text-gray-700">
                                <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                الجلسات النشطة
                            </a>
                            <a href="{{ route('network.queues.index') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm hover:bg-indigo-50 transition text-gray-700">
                                <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                السرعات
                            </a>
                            <a href="{{ route('network.monitoring.index') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm hover:bg-indigo-50 transition text-gray-700">
                                <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                حالة الشبكة
                            </a>
                            <a href="{{ route('network.monitoring.bandwidth') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm hover:bg-indigo-50 transition text-gray-700">
                                <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 12l-4-4m4 4l4-4m6-4v12m0-12l-4 4m4-4l4 4"/></svg>
                                استهلاك البيانات
                            </a>
                        </div>
                    </div>
                    <div class="h-6 w-px bg-gray-300 opacity-30"></div>

                    <!-- الخرائط - Dropdown -->
                    <div class="dropdown relative">
                        <button class="px-4 py-2.5 rounded-lg text-base font-medium text-gray-700 hover:bg-gray-100 transition flex items-center gap-1.5">
                            🗺️ الخرائط
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/></svg>
                        </button>
                        <div class="dropdown-menu absolute right-0 w-64 glass rounded-xl shadow-xl py-2 mt-1">
                            <a href="{{ route('network.topology.index') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm hover:bg-indigo-50 transition text-gray-700">
                                <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                                خريطة الشبكة
                            </a>
                            <a href="{{ route('maps.index') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm hover:bg-indigo-50 transition text-gray-700">
                                <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                الخريطة الجغرافية
                            </a>
                            <a href="{{ route('network.website.analytics') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm hover:bg-indigo-50 transition text-gray-700">
                                <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                                أكثر المواقع زيارة
                            </a>
                            <a href="{{ route('network.website.blocked') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm hover:bg-indigo-50 transition text-gray-700">
                                <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                المواقع المحظورة
                            </a>
                        </div>
                    </div>
                    <div class="h-6 w-px bg-gray-300 opacity-30"></div>

                    <!-- العملاء -->
                    <a href="{{ route('crm.clients.index') }}" class="px-4 py-2.5 rounded-lg text-base font-medium transition {{ request()->routeIs('crm.clients.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        👥 العملاء
                    </a>
                    <div class="h-6 w-px bg-gray-300 opacity-30"></div>

                    <!-- WhatsApp - Fixed Icon -->
                    <a href="{{ route('whatsapp.index') }}" class="px-4 py-2.5 rounded-lg text-base font-medium transition {{ request()->routeIs('whatsapp.*') ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-100' }} inline-flex items-center gap-1.5">
                        <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                        واتساب
                    </a>
                    <div class="h-6 w-px bg-gray-300 opacity-30"></div>

                    <!-- التسويق -->
                    <a href="{{ route('crm.campaigns.create') }}" class="px-4 py-2.5 rounded-lg text-base font-medium transition {{ request()->routeIs('crm.campaigns.*') ? 'bg-purple-50 text-purple-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        📢 التسويق
                    </a>
                    <div class="h-6 w-px bg-gray-300 opacity-30"></div>

                    <!-- المالية - Dropdown -->
                    <div class="dropdown relative">
                        <button class="px-3 py-2 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-100 transition flex items-center gap-1">
                            💰 المالية
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/></svg>
                        </button>
                        <div class="dropdown-menu absolute right-0 w-56 glass rounded-xl shadow-xl py-2 mt-1">
                            <a href="{{ route('accounting.reports.index') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm hover:bg-indigo-50 transition text-gray-700">
                                <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 2v-6m-9 10h12a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                التقارير
                            </a>
                            <a href="{{ route('accounting.invoices.index') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm hover:bg-indigo-50 transition text-gray-700">
                                <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                الفواتير
                            </a>
                        </div>
                    </div>
                    <div class="h-6 w-px bg-gray-300 opacity-30"></div>

                    <!-- النظام - Dropdown -->
                    <div class="dropdown relative">
                        <button class="px-3 py-2 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-100 transition flex items-center gap-1">
                            ⚙️ النظام
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/></svg>
                        </button>
                        <div class="dropdown-menu absolute right-0 w-64 glass rounded-xl shadow-xl py-2 mt-1">
                            <a href="{{ route('staff.index') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm hover:bg-indigo-50 transition text-gray-700">
                                <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                الموظفين
                            </a>
                            <a href="{{ route('roles.index') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm hover:bg-indigo-50 transition text-gray-700">
                                <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                الأدوار والصلاحيات
                            </a>
                            <a href="{{ route('activity-logs.index') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm hover:bg-indigo-50 transition text-gray-700">
                                <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                سجل النشاطات
                            </a>
                            <a href="{{ route('mobile-app.index') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm hover:bg-indigo-50 transition text-gray-700">
                                <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                تطبيق العملاء
                            </a>
                            <hr class="my-1 border-gray-100">
                            <a href="{{ route('settings.index') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm hover:bg-indigo-50 transition text-gray-700">
                                <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                الإعدادات
                            </a>
                            <a href="{{ route('subscription.index') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm hover:bg-indigo-50 transition text-gray-700">
                                <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                باقتي واشتراكي
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Right Side: Dark Mode + Logout -->
                <div class="flex items-center gap-3">
                    <!-- Dark Mode Toggle -->
                    <button @click="darkMode = !darkMode" class="p-2 rounded-lg hover:bg-slate-100 transition" title="تبديل الوضع">
                        <svg x-show="!darkMode" x-cloak class="w-5 h-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                        </svg>
                        <svg x-show="darkMode" x-cloak class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"/>
                        </svg>
                    </button>



                    <!-- Logout -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="px-4 py-2 rounded-lg text-sm font-medium text-red-600 hover:bg-red-50 transition flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            خروج
                        </button>
                    </form>

                    <!-- Mobile Menu Toggle -->
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden p-2 rounded-lg hover:bg-gray-100">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileMenuOpen" x-cloak class="lg:hidden border-t border-gray-200 py-2 px-4 space-y-1 overflow-y-auto max-h-[calc(100vh-5rem)]">
            <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-lg text-sm font-medium hover:bg-gray-50 {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-700' : 'text-gray-700' }}">🏠 الرئيسية</a>
            
            <a href="{{ route('network.commands.index') }}" class="block px-3 py-2 rounded-lg text-sm font-medium hover:bg-gray-50 {{ request()->routeIs('network.commands.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700' }}">💻 Terminal</a>
            
            <!-- Network Dropdown -->
            <div x-data="{ open: false }" class="space-y-1">
                <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">
                    <span class="flex items-center gap-2">🌐 الشبكة</span>
                    <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" fill="currentColor" viewBox="0 0 20 20"><path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/></svg>
                </button>
                <div x-show="open" x-cloak class="pl-6 space-y-1">
                    <a href="{{ route('hotspot.profiles.index') }}" class="block px-3 py-2 rounded-lg text-sm text-gray-600 hover:bg-gray-50">هوت سبوت</a>
                    <a href="{{ route('hotspot.vouchers.index') }}" class="block px-3 py-2 rounded-lg text-sm text-gray-600 hover:bg-gray-50">مراقبة الكروت</a>
                    <a href="{{ route('hotspot.vouchers.create') }}" class="block px-3 py-2 rounded-lg text-sm text-gray-600 hover:bg-gray-50">طباعة كروت جديدة</a>
                    <a href="{{ route('broadband.profiles.index') }}" class="block px-3 py-2 rounded-lg text-sm text-gray-600 hover:bg-gray-50">برودباند</a>
                    <a href="{{ route('routers.index') }}" class="block px-3 py-2 rounded-lg text-sm text-gray-600 hover:bg-gray-50">معدات الشبكة</a>
                    <a href="{{ route('servers.index') }}" class="block px-3 py-2 rounded-lg text-sm text-gray-600 hover:bg-gray-50">السيرفرات</a>
                    <a href="{{ route('network.towers.index') }}" class="block px-3 py-2 rounded-lg text-sm text-gray-600 hover:bg-gray-50">الأبراج</a>
                    <a href="{{ route('network.internet-sources.index') }}" class="block px-3 py-2 rounded-lg text-sm text-gray-600 hover:bg-gray-50">مصادر الانترنت</a>
                </div>
            </div>

            <!-- Monitoring Dropdown -->
            <div x-data="{ open: false }" class="space-y-1">
                <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">
                    <span class="flex items-center gap-2">📊 المراقبة</span>
                    <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" fill="currentColor" viewBox="0 0 20 20"><path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/></svg>
                </button>
                <div x-show="open" x-cloak class="pl-6 space-y-1">
                    <a href="{{ route('network.live-monitoring') }}" class="block px-3 py-2 rounded-lg text-sm text-gray-600 hover:bg-gray-50">مراقبة حية</a>
                    <a href="{{ route('network.sessions.index') }}" class="block px-3 py-2 rounded-lg text-sm text-gray-600 hover:bg-gray-50">الجلسات النشطة</a>
                    <a href="{{ route('network.queues.index') }}" class="block px-3 py-2 rounded-lg text-sm text-gray-600 hover:bg-gray-50">السرعات</a>
                    <a href="{{ route('network.monitoring.index') }}" class="block px-3 py-2 rounded-lg text-sm text-gray-600 hover:bg-gray-50">حالة الشبكة</a>
                    <a href="{{ route('network.monitoring.bandwidth') }}" class="block px-3 py-2 rounded-lg text-sm text-gray-600 hover:bg-gray-50">استهلاك البيانات</a>
                </div>
            </div>

            <!-- Maps Dropdown -->
            <div x-data="{ open: false }" class="space-y-1">
                <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">
                    <span class="flex items-center gap-2">🗺️ الخرائط</span>
                    <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" fill="currentColor" viewBox="0 0 20 20"><path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/></svg>
                </button>
                <div x-show="open" x-cloak class="pl-6 space-y-1">
                    <a href="{{ route('network.topology.index') }}" class="block px-3 py-2 rounded-lg text-sm text-gray-600 hover:bg-gray-50">خريطة الشبكة</a>
                    <a href="{{ route('maps.index') }}" class="block px-3 py-2 rounded-lg text-sm text-gray-600 hover:bg-gray-50">الخريطة الجغرافية</a>
                    <a href="{{ route('network.website.analytics') }}" class="block px-3 py-2 rounded-lg text-sm text-gray-600 hover:bg-gray-50">أكثر المواقع زيارة</a>
                    <a href="{{ route('network.website.blocked') }}" class="block px-3 py-2 rounded-lg text-sm text-gray-600 hover:bg-gray-50">المواقع المحظورة</a>
                </div>
            </div>

            <a href="{{ route('crm.clients.index') }}" class="block px-3 py-2 rounded-lg text-sm font-medium hover:bg-gray-50 {{ request()->routeIs('crm.clients.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700' }}">👥 العملاء</a>
            
            <a href="{{ route('whatsapp.index') }}" class="block px-3 py-2 rounded-lg text-sm font-medium hover:bg-gray-50 {{ request()->routeIs('whatsapp.*') ? 'bg-green-50 text-green-700' : 'text-gray-700' }}">💬 واتساب</a>
            
            <a href="{{ route('crm.campaigns.create') }}" class="block px-3 py-2 rounded-lg text-sm font-medium hover:bg-gray-50 {{ request()->routeIs('crm.campaigns.*') ? 'bg-purple-50 text-purple-700' : 'text-gray-700' }}">📢 التسويق</a>

            <!-- Finance Dropdown -->
            <div x-data="{ open: false }" class="space-y-1">
                <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">
                    <span class="flex items-center gap-2">💰 المالية</span>
                    <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" fill="currentColor" viewBox="0 0 20 20"><path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/></svg>
                </button>
                <div x-show="open" x-cloak class="pl-6 space-y-1">
                    <a href="{{ route('accounting.reports.index') }}" class="block px-3 py-2 rounded-lg text-sm text-gray-600 hover:bg-gray-50">التقارير</a>
                    <a href="{{ route('accounting.invoices.index') }}" class="block px-3 py-2 rounded-lg text-sm text-gray-600 hover:bg-gray-50">الفواتير</a>
                </div>
            </div>

            <!-- System Dropdown -->
            <div x-data="{ open: false }" class="space-y-1">
                <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">
                    <span class="flex items-center gap-2">⚙️ النظام</span>
                    <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" fill="currentColor" viewBox="0 0 20 20"><path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/></svg>
                </button>
                <div x-show="open" x-cloak class="pl-6 space-y-1">
                    <a href="{{ route('staff.index') }}" class="block px-3 py-2 rounded-lg text-sm text-gray-600 hover:bg-gray-50">الموظفين</a>
                    <a href="{{ route('roles.index') }}" class="block px-3 py-2 rounded-lg text-sm text-gray-600 hover:bg-gray-50">الأدوار والصلاحيات</a>
                    <a href="{{ route('activity-logs.index') }}" class="block px-3 py-2 rounded-lg text-sm text-gray-600 hover:bg-gray-50">سجل النشاطات</a>
                    <a href="{{ route('mobile-app.index') }}" class="block px-3 py-2 rounded-lg text-sm text-gray-600 hover:bg-gray-50">تطبيق العملاء</a>
                    <a href="{{ route('settings.index') }}" class="block px-3 py-2 rounded-lg text-sm text-gray-600 hover:bg-gray-50">الإعدادات</a>
                    <a href="{{ route('subscription.index') }}" class="block px-3 py-2 rounded-lg text-sm text-gray-600 hover:bg-gray-50">باقتي واشتراكي</a>
                </div>
            </div>
        </div>
    </nav>
    @endauth

    <!-- Main Content -->
    <main class="flex-1 {{ request()->routeIs('whatsapp.*') ? 'flex flex-col overflow-hidden' : 'p-6 overflow-y-auto' }}">
        @if(session('success'))
            <div class="glass border-l-4 border-green-500 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center gap-3 shadow-lg animate-pulse">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="glass border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-lg mb-6 flex items-center gap-3 shadow-lg animate-pulse">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="font-medium">{{ session('error') }}</span>
            </div>
        @endif
        
        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>
