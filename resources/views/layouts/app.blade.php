<!DOCTYPE html>
<html lang="ar" dir="rtl" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'MadaaQ') }}</title>
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        .sidebar-scroll::-webkit-scrollbar { width: 4px; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 4px; }
        .sidebar-scroll::-webkit-scrollbar-track { background-color: transparent; }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="h-full font-tajawal text-gray-900 antialiased" x-data="{ sidebarOpen: false }">

    <!-- Mobile Sidebar Backdrop -->
    <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900/80 z-40 lg:hidden" @click="sidebarOpen = false"></div>

    <!-- Sidebar -->
    <div :class="sidebarOpen ? 'translate-x-0' : 'translate-x-full lg:translate-x-0'" class="fixed inset-y-0 right-0 z-50 w-72 bg-white border-l border-gray-200 transition-transform duration-300 ease-in-out shadow-xl lg:shadow-none">
        
        <!-- Logo -->
        <div class="flex h-16 shrink-0 items-center gap-3 px-6 border-b border-gray-100">
            <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-indigo-600 text-white shadow-sm shadow-indigo-200">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
            <span class="text-xl font-bold tracking-tight text-gray-900">MadaaQ <span class="text-indigo-600">Admin</span></span>
            
            <button @click="sidebarOpen = false" class="mr-auto lg:hidden text-gray-500">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 overflow-y-auto sidebar-scroll px-4 py-6 space-y-1">
            
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-700 hover:bg-gray-50 hover:text-indigo-600' }} group flex items-center gap-x-3 rounded-md px-3 py-2 text-sm font-semibold leading-6 transition-all duration-200">
                <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('dashboard') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                لوحة التحكم
            </a>

            <!-- Network Section -->
            <div x-data="{ open: {{ request()->routeIs('network.*', 'servers.*', 'routers.*') ? 'true' : 'false' }} }" class="pt-4">
                <button @click="open = !open" class="flex w-full items-center justify-between rounded-md px-3 py-2 text-sm font-semibold leading-6 text-gray-700 hover:bg-gray-50 hover:text-indigo-600 group transition-all duration-200">
                    <div class="flex items-center gap-x-3">
                        <svg class="h-5 w-5 shrink-0 text-gray-400 group-hover:text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        إدارة الشبكة
                    </div>
                    <svg class="h-4 w-4 shrink-0 text-gray-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" /></svg>
                </button>
                <div x-show="open" x-collapse x-cloak class="mt-1 space-y-1 pr-11">
                    <a href="{{ route('servers.index') }}" class="block rounded-md py-2 pr-2 text-sm font-medium {{ request()->routeIs('servers.*') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }}">سيرفرات مايكروتك</a>
                    <a href="{{ route('network.internet-sources.index') }}" class="block rounded-md py-2 pr-2 text-sm font-medium {{ request()->routeIs('network.internet-sources.*') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }}">مصادر الانترنت</a>
                    <a href="{{ route('network.towers.index') }}" class="block rounded-md py-2 pr-2 text-sm font-medium {{ request()->routeIs('network.towers.*') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }}">الأبراج</a>
                    <a href="{{ route('routers.index') }}" class="block rounded-md py-2 pr-2 text-sm font-medium {{ request()->routeIs('routers.*') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }}">معدات الشبكة</a>
                    <a href="{{ route('network.backups.index') }}" class="block rounded-md py-2 pr-2 text-sm font-medium {{ request()->routeIs('network.backups.*') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }}">النسخ الاحتياطية</a>
                </div>
            </div>

            <!-- Hotspot Section -->
            <div x-data="{ open: {{ request()->routeIs('hotspot.*') ? 'true' : 'false' }} }" class="pt-2">
                <button @click="open = !open" class="flex w-full items-center justify-between rounded-md px-3 py-2 text-sm font-semibold leading-6 text-gray-700 hover:bg-gray-50 hover:text-purple-600 group transition-all duration-200">
                    <div class="flex items-center gap-x-3">
                        <svg class="h-5 w-5 shrink-0 text-gray-400 group-hover:text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/></svg>
                        هوت سبوت
                    </div>
                    <svg class="h-4 w-4 shrink-0 text-gray-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" /></svg>
                </button>
                <div x-show="open" x-collapse x-cloak class="mt-1 space-y-1 pr-11">
                    <a href="{{ route('hotspot.profiles.index') }}" class="block rounded-md py-2 pr-2 text-sm font-medium {{ request()->routeIs('hotspot.profiles.*') ? 'text-purple-600 bg-purple-50' : 'text-gray-600 hover:text-purple-600 hover:bg-gray-50' }}">الباقات والبروفايلات</a>
                    <a href="{{ route('hotspot.vouchers.index') }}" class="block rounded-md py-2 pr-2 text-sm font-medium {{ request()->routeIs('hotspot.vouchers.index') ? 'text-purple-600 bg-purple-50' : 'text-gray-600 hover:text-purple-600 hover:bg-gray-50' }}">إدارة الكروت</a>
                    <a href="{{ route('hotspot.vouchers.create') }}" class="block rounded-md py-2 pr-2 text-sm font-medium {{ request()->routeIs('hotspot.vouchers.create') ? 'text-purple-600 bg-purple-50' : 'text-gray-600 hover:text-purple-600 hover:bg-gray-50' }}">طباعة كروت</a>
                </div>
            </div>

            <!-- Broadband Section -->
            <div x-data="{ open: {{ request()->routeIs('broadband.*') ? 'true' : 'false' }} }" class="pt-2">
                <button @click="open = !open" class="flex w-full items-center justify-between rounded-md px-3 py-2 text-sm font-semibold leading-6 text-gray-700 hover:bg-gray-50 hover:text-blue-600 group transition-all duration-200">
                    <div class="flex items-center gap-x-3">
                        <svg class="h-5 w-5 shrink-0 text-gray-400 group-hover:text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        برودباند (PPPoE)
                    </div>
                    <svg class="h-4 w-4 shrink-0 text-gray-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" /></svg>
                </button>
                <div x-show="open" x-collapse x-cloak class="mt-1 space-y-1 pr-11">
                    <a href="{{ route('broadband.profiles.index') }}" class="block rounded-md py-2 pr-2 text-sm font-medium {{ request()->routeIs('broadband.profiles.*') ? 'text-blue-600 bg-blue-50' : 'text-gray-600 hover:text-blue-600 hover:bg-gray-50' }}">الباقات والاشتراكات</a>
                    <a href="{{ route('broadband.users.index') }}" class="block rounded-md py-2 pr-2 text-sm font-medium {{ request()->routeIs('broadband.users.*') ? 'text-blue-600 bg-blue-50' : 'text-gray-600 hover:text-blue-600 hover:bg-gray-50' }}">المشتركين النشطين</a>
                </div>
            </div>
            
            <!-- Monitoring Section -->
            <div x-data="{ open: {{ request()->routeIs('network.monitoring.*', 'network.live-monitoring', 'network.sessions.*') ? 'true' : 'false' }} }" class="pt-2">
                <button @click="open = !open" class="flex w-full items-center justify-between rounded-md px-3 py-2 text-sm font-semibold leading-6 text-gray-700 hover:bg-gray-50 hover:text-emerald-600 group transition-all duration-200">
                    <div class="flex items-center gap-x-3">
                        <svg class="h-5 w-5 shrink-0 text-gray-400 group-hover:text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        المراقبة والتقارير
                    </div>
                    <svg class="h-4 w-4 shrink-0 text-gray-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" /></svg>
                </button>
                <div x-show="open" x-collapse x-cloak class="mt-1 space-y-1 pr-11">
                    <a href="{{ route('network.live-monitoring') }}" class="block rounded-md py-2 pr-2 text-sm font-medium {{ request()->routeIs('network.live-monitoring') ? 'text-emerald-600 bg-emerald-50' : 'text-gray-600 hover:text-emerald-600 hover:bg-gray-50' }}">مراقبة حية</a>
                    <a href="{{ route('network.sessions.index') }}" class="block rounded-md py-2 pr-2 text-sm font-medium {{ request()->routeIs('network.sessions.*') ? 'text-emerald-600 bg-emerald-50' : 'text-gray-600 hover:text-emerald-600 hover:bg-gray-50' }}">الجلسات النشطة</a>
                    <a href="{{ route('network.monitoring.bandwidth') }}" class="block rounded-md py-2 pr-2 text-sm font-medium {{ request()->routeIs('network.monitoring.bandwidth') ? 'text-emerald-600 bg-emerald-50' : 'text-gray-600 hover:text-emerald-600 hover:bg-gray-50' }}">استهلاك البيانات</a>
                </div>
            </div>

            <!-- CRM Section -->
            <div x-data="{ open: {{ request()->routeIs('crm.*', 'whatsapp.*') ? 'true' : 'false' }} }" class="pt-2">
                <button @click="open = !open" class="flex w-full items-center justify-between rounded-md px-3 py-2 text-sm font-semibold leading-6 text-gray-700 hover:bg-gray-50 hover:text-pink-600 group transition-all duration-200">
                    <div class="flex items-center gap-x-3">
                        <svg class="h-5 w-5 shrink-0 text-gray-400 group-hover:text-pink-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        العملاء والتسويق
                    </div>
                    <svg class="h-4 w-4 shrink-0 text-gray-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" /></svg>
                </button>
                <div x-show="open" x-collapse x-cloak class="mt-1 space-y-1 pr-11">
                    <a href="{{ route('crm.clients.index') }}" class="block rounded-md py-2 pr-2 text-sm font-medium {{ request()->routeIs('crm.clients.*') ? 'text-pink-600 bg-pink-50' : 'text-gray-600 hover:text-pink-600 hover:bg-gray-50' }}">دليل العملاء</a>
                    <a href="{{ route('whatsapp.index') }}" class="block rounded-md py-2 pr-2 text-sm font-medium {{ request()->routeIs('whatsapp.*') ? 'text-pink-600 bg-pink-50' : 'text-gray-600 hover:text-pink-600 hover:bg-gray-50' }}">واتساب</a>
                    <a href="{{ route('crm.campaigns.create') }}" class="block rounded-md py-2 pr-2 text-sm font-medium {{ request()->routeIs('crm.campaigns.*') ? 'text-pink-600 bg-pink-50' : 'text-gray-600 hover:text-pink-600 hover:bg-gray-50' }}">الحملات التسويقية</a>
                </div>
            </div>

            <!-- Finance Section -->
            <div x-data="{ open: {{ request()->routeIs('accounting.*') ? 'true' : 'false' }} }" class="pt-2">
                <button @click="open = !open" class="flex w-full items-center justify-between rounded-md px-3 py-2 text-sm font-semibold leading-6 text-gray-700 hover:bg-gray-50 hover:text-amber-600 group transition-all duration-200">
                    <div class="flex items-center gap-x-3">
                        <svg class="h-5 w-5 shrink-0 text-gray-400 group-hover:text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        المالية والفواتير
                    </div>
                    <svg class="h-4 w-4 shrink-0 text-gray-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" /></svg>
                </button>
                <div x-show="open" x-collapse x-cloak class="mt-1 space-y-1 pr-11">
                    <a href="{{ route('accounting.invoices.index') }}" class="block rounded-md py-2 pr-2 text-sm font-medium {{ request()->routeIs('accounting.invoices.*') ? 'text-amber-600 bg-amber-50' : 'text-gray-600 hover:text-amber-600 hover:bg-gray-50' }}">الفواتير</a>
                    <a href="{{ route('accounting.reports.index') }}" class="block rounded-md py-2 pr-2 text-sm font-medium {{ request()->routeIs('accounting.reports.*') ? 'text-amber-600 bg-amber-50' : 'text-gray-600 hover:text-amber-600 hover:bg-gray-50' }}">التقارير المالية</a>
                </div>
            </div>

            <!-- System Section -->
            <div x-data="{ open: {{ request()->routeIs('settings.*', 'staff.*', 'roles.*') ? 'true' : 'false' }} }" class="pt-2">
                <button @click="open = !open" class="flex w-full items-center justify-between rounded-md px-3 py-2 text-sm font-semibold leading-6 text-gray-700 hover:bg-gray-50 hover:text-slate-600 group transition-all duration-200">
                    <div class="flex items-center gap-x-3">
                        <svg class="h-5 w-5 shrink-0 text-gray-400 group-hover:text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        إعدادات النظام
                    </div>
                    <svg class="h-4 w-4 shrink-0 text-gray-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" /></svg>
                </button>
                <div x-show="open" x-collapse x-cloak class="mt-1 space-y-1 pr-11">
                    <a href="{{ route('settings.index') }}" class="block rounded-md py-2 pr-2 text-sm font-medium {{ request()->routeIs('settings.*') ? 'text-slate-600 bg-slate-50' : 'text-gray-600 hover:text-slate-600 hover:bg-gray-50' }}">الإعدادات العامة</a>
                    <a href="{{ route('staff.index') }}" class="block rounded-md py-2 pr-2 text-sm font-medium {{ request()->routeIs('staff.*') ? 'text-slate-600 bg-slate-50' : 'text-gray-600 hover:text-slate-600 hover:bg-gray-50' }}">الموظفين</a>
                    <a href="{{ route('roles.index') }}" class="block rounded-md py-2 pr-2 text-sm font-medium {{ request()->routeIs('roles.*') ? 'text-slate-600 bg-slate-50' : 'text-gray-600 hover:text-slate-600 hover:bg-gray-50' }}">الأدوار</a>
                </div>
            </div>

        </nav>
        
        <!-- User Profile (Bottom) -->
        <div class="border-t border-gray-100 p-4">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 flex-shrink-0 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="truncate text-sm font-semibold text-gray-900">{{ auth()->user()->name }}</p>
                    <p class="truncate text-xs text-gray-500">{{ auth()->user()->email }}</p>
                </div>
                <!-- Logout -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-gray-400 hover:text-red-600 transition" title="تسجيل خروج">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="flex flex-1 flex-col lg:mr-72 h-full">
        <!-- Top Header (Search & Notifications) -->
        <header class="flex h-16 shrink-0 items-center justify-between gap-x-4 border-b border-gray-200 bg-white px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-8">
            <div class="flex flex-1 gap-x-4 self-stretch lg:gap-x-6">
                <!-- Sidebar Toggle (Mobile) -->
                <button @click="sidebarOpen = true" type="button" class="-m-2.5 p-2.5 text-gray-700 lg:hidden">
                    <span class="sr-only">Open sidebar</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>

                <!-- Search (Placeholder) -->
                <form class="relative flex flex-1" action="#" method="GET">
                    <label for="search-field" class="sr-only">بحث</label>
                    <svg class="pointer-events-none absolute inset-y-0 right-0 h-full w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" />
                    </svg>
                    <input id="search-field" class="block h-full w-full border-0 py-0 pr-8 pl-0 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm" placeholder="ابحث في النظام..." type="search" name="search">
                </form>

                <div class="flex items-center gap-x-4 lg:gap-x-6">
                    <!-- Notifications -->
                    <button type="button" class="-m-2.5 p-2.5 text-gray-400 hover:text-gray-500">
                        <span class="sr-only">View notifications</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                        </svg>
                    </button>
                    <!-- Separator -->
                    <div class="hidden lg:block lg:h-6 lg:w-px lg:bg-gray-200" aria-hidden="true"></div>
                    <!-- Profile Dropdown (Simplified) -->
                    <a href="{{ route('settings.index') }}" class="flex items-center gap-x-4 px-3 py-2 text-sm font-semibold leading-6 text-gray-900 hover:bg-gray-50 rounded-md">
                         <span class="hidden lg:flex lg:items-center">
                            <span class="ml-4 text-sm font-semibold leading-6 text-gray-900" aria-hidden="true">{{ auth()->user()->name }}</span>
                            <svg class="ml-2 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" /></svg>
                        </span>
                    </a>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="py-6 px-4 sm:px-6 lg:px-8 h-full overflow-y-auto">
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="mb-4 rounded-md bg-green-50 p-4 shadow-sm border border-green-200">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" /></svg>
                        </div>
                        <div class="mr-3">
                            <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 rounded-md bg-red-50 p-4 shadow-sm border border-red-200">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" /></svg>
                        </div>
                        <div class="mr-3">
                            <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
    
    @stack('scripts')
</body>
</html>
