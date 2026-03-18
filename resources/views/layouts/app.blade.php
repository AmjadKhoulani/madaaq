<!DOCTYPE html>
<html lang="ar" dir="rtl" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'MadaaQ') }}</title>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        .sidebar-scroll::-webkit-scrollbar { width: 4px; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 4px; }
        .sidebar-scroll::-webkit-scrollbar-track { background-color: transparent; }
        /* Safety rule: prevent SVG icons from growing too large */
        svg { max-width: 100%; }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="h-full font-tajawal text-gray-900 antialiased lg:flex" x-data="{ sidebarOpen: false }">

    <!-- Mobile Sidebar Backdrop -->
    <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900/80 z-40 lg:hidden" @click="sidebarOpen = false"></div>

    <!-- Sidebar -->
    <div :class="sidebarOpen ? 'translate-x-0' : 'translate-x-full lg:translate-x-0'" class="fixed inset-y-0 right-0 z-50 w-72 bg-white border-l border-gray-200 transition-transform duration-300 ease-in-out shadow-xl lg:static lg:inset-auto lg:flex lg:flex-col lg:shadow-none">
        
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
                <svg class="h-6 w-6 shrink-0 {{ request()->routeIs('dashboard') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-600' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                </svg>
                لوحة التحكم
            </a>

            <!-- Network Section (Cyan) -->
            <div x-data="{ open: {{ request()->routeIs('network.*', 'servers.*', 'routers.*') ? 'true' : 'false' }} }" class="pt-4">
                <button @click="open = !open" class="flex w-full items-center justify-between rounded-md px-3 py-2 text-sm font-semibold leading-6 text-gray-700 hover:bg-gray-50 hover:text-indigo-600 group transition-all duration-200 cursor-pointer">
                    <div class="flex items-center gap-x-3">
                        <svg class="h-6 w-6 shrink-0 text-gray-400 group-hover:text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 17.25v-.228a4.5 4.5 0 00-.12-1.03l-2.268-9.64a3.375 3.375 0 00-3.285-2.602H7.923a3.375 3.375 0 00-3.285 2.602l-2.268 9.64a4.5 4.5 0 00-.12 1.03v.228m19.5 0a3 3 0 01-3 3H5.25a3 3 0 01-3-3m19.5 0a3 3 0 00-3-3H5.25a3 3 0 00-3 3m16.5 0h.008v.008h-.008v-.008zm-3 0h.008v.008h-.008v-.008z" />
                        </svg>
                        إدارة الشبكة
                    </div>
                    <svg class="h-4 w-4 shrink-0 text-gray-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" /></svg>
                </button>
                <div x-show="open" x-collapse x-cloak class="mt-1 space-y-1 pr-11">
                    <a href="{{ route('servers.index') }}" class="block rounded-md py-2 pr-2 text-sm font-medium {{ request()->routeIs('servers.*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50' }}">سيرفرات مايكروتك</a>
                    <a href="{{ route('network.internet-sources.index') }}" class="block rounded-md py-2 pr-2 text-sm font-medium {{ request()->routeIs('network.internet-sources.*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50' }}">مصادر الانترنت</a>
                    <a href="{{ route('network.towers.index') }}" class="block rounded-md py-2 pr-2 text-sm font-medium {{ request()->routeIs('network.towers.*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50' }}">الأبراج</a>
                    <a href="{{ route('maps.index') }}" class="block rounded-md py-2 pr-2 text-sm font-medium {{ request()->routeIs('maps.index') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50' }}">الخريطة الجغرافية</a>
                    <a href="{{ route('routers.index') }}" class="block rounded-md py-2 pr-2 text-sm font-medium {{ request()->routeIs('routers.*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50' }}">معدات الشبكة</a>
                    <a href="{{ route('network.backups.index') }}" class="block rounded-md py-2 pr-2 text-sm font-medium {{ request()->routeIs('network.backups.*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50' }}">النسخ الاحتياطية</a>
                </div>
            </div>

            <!-- Hotspot Section (Fuchsia) -->
            <div x-data="{ open: {{ request()->routeIs('hotspot.*') ? 'true' : 'false' }} }" class="pt-2">
                <button @click="open = !open" class="flex w-full items-center justify-between rounded-md px-3 py-2 text-sm font-semibold leading-6 text-gray-700 hover:bg-gray-50 hover:text-indigo-600 group transition-all duration-200 cursor-pointer">
                    <div class="flex items-center gap-x-3">
                        <svg class="h-6 w-6 shrink-0 text-gray-400 group-hover:text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M8.288 15.038a5.25 5.25 0 017.424 0M5.106 11.856c3.807-3.808 9.98-3.808 13.788 0M1.924 8.674c5.565-5.565 14.587-5.565 20.152 0M12.53 18.22l-.53.53-.53-.53a.75.75 0 011.06 0z" />
                        </svg>
                        هوت سبوت
                    </div>
                    <svg class="h-4 w-4 shrink-0 text-gray-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" /></svg>
                </button>
                <div x-show="open" x-collapse x-cloak class="mt-1 space-y-1 pr-11">
                    <a href="{{ route('hotspot.profiles.index') }}" class="block rounded-md py-2 pr-2 text-sm font-medium {{ request()->routeIs('hotspot.profiles.*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50' }}">الباقات والبروفايلات</a>
                    <a href="{{ route('hotspot.vouchers.index') }}" class="block rounded-md py-2 pr-2 text-sm font-medium {{ request()->routeIs('hotspot.vouchers.index') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50' }}">إدارة الكروت</a>
                    <a href="{{ route('hotspot.vouchers.create') }}" class="block rounded-md py-2 pr-2 text-sm font-medium {{ request()->routeIs('hotspot.vouchers.create') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50' }}">طباعة كروت</a>
                </div>
            </div>

            <!-- Broadband Section (Blue) -->
            <div x-data="{ open: {{ request()->routeIs('broadband.*') ? 'true' : 'false' }} }" class="pt-2">
                <button @click="open = !open" class="flex w-full items-center justify-between rounded-md px-3 py-2 text-sm font-semibold leading-6 text-gray-700 hover:bg-gray-50 hover:text-indigo-600 group transition-all duration-200 cursor-pointer">
                    <div class="flex items-center gap-x-3">
                        <svg class="h-6 w-6 shrink-0 text-gray-400 group-hover:text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418" />
                        </svg>
                        برودباند (PPPoE)
                    </div>
                    <svg class="h-4 w-4 shrink-0 text-gray-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" /></svg>
                </button>
                <div x-show="open" x-collapse x-cloak class="mt-1 space-y-1 pr-11">
                    <a href="{{ route('broadband.profiles.index') }}" class="block rounded-md py-2 pr-2 text-sm font-medium {{ request()->routeIs('broadband.profiles.*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50' }}">الباقات والاشتراكات</a>
                    <a href="{{ route('broadband.users.index') }}" class="block rounded-md py-2 pr-2 text-sm font-medium {{ request()->routeIs('broadband.users.*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50' }}">المشتركين النشطين</a>
                </div>
            </div>
            
            <!-- Monitoring Section (Emerald) -->
            <div x-data="{ open: {{ request()->routeIs('network.monitoring.*', 'network.live-monitoring', 'network.sessions.*') ? 'true' : 'false' }} }" class="pt-2">
                <button @click="open = !open" class="flex w-full items-center justify-between rounded-md px-3 py-2 text-sm font-semibold leading-6 text-gray-700 hover:bg-gray-50 hover:text-indigo-600 group transition-all duration-200 cursor-pointer">
                    <div class="flex items-center gap-x-3">
                        <svg class="h-6 w-6 shrink-0 text-gray-400 group-hover:text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5M9 11.25v1.5M12 9v3.75m3-6v6" />
                        </svg>
                        المراقبة والتقارير
                    </div>
                    <svg class="h-4 w-4 shrink-0 text-gray-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" /></svg>
                </button>
                <div x-show="open" x-collapse x-cloak class="mt-1 space-y-1 pr-11">
                    <a href="{{ route('network.live-monitoring') }}" class="block rounded-md py-2 pr-2 text-sm font-medium {{ request()->routeIs('network.live-monitoring') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50' }}">مراقبة حية</a>
                    <a href="{{ route('network.sessions.index') }}" class="block rounded-md py-2 pr-2 text-sm font-medium {{ request()->routeIs('network.sessions.*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50' }}">الجلسات النشطة</a>
                    <a href="{{ route('network.monitoring.bandwidth') }}" class="block rounded-md py-2 pr-2 text-sm font-medium {{ request()->routeIs('network.monitoring.bandwidth') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50' }}">استهلاك البيانات</a>
                </div>
            </div>

            <!-- CRM Section (Rose) -->
            <div x-data="{ open: {{ request()->routeIs('crm.*', 'whatsapp.*') ? 'true' : 'false' }} }" class="pt-2">
                <button @click="open = !open" class="flex w-full items-center justify-between rounded-md px-3 py-2 text-sm font-semibold leading-6 text-gray-700 hover:bg-gray-50 hover:text-indigo-600 group transition-all duration-200 cursor-pointer">
                    <div class="flex items-center gap-x-3">
                        <svg class="h-6 w-6 shrink-0 text-gray-400 group-hover:text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                        </svg>
                        العملاء والتسويق
                    </div>
                    <svg class="h-4 w-4 shrink-0 text-gray-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" /></svg>
                </button>
                <div x-show="open" x-collapse x-cloak class="mt-1 space-y-1 pr-11">
                    <a href="{{ route('crm.clients.index') }}" class="block rounded-md py-2 pr-2 text-sm font-medium {{ request()->routeIs('crm.clients.*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50' }}">دليل العملاء</a>
                    <a href="{{ route('whatsapp.index') }}" class="block rounded-md py-2 pr-2 text-sm font-medium {{ request()->routeIs('whatsapp.*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50' }}">واتساب</a>
                    <a href="{{ route('crm.campaigns.create') }}" class="block rounded-md py-2 pr-2 text-sm font-medium {{ request()->routeIs('crm.campaigns.*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50' }}">الحملات التسويقية</a>
                </div>
            </div>

            <!-- Finance Section (Amber) -->
            <div x-data="{ open: {{ request()->routeIs('accounting.*') ? 'true' : 'false' }} }" class="pt-2">
                <button @click="open = !open" class="flex w-full items-center justify-between rounded-md px-3 py-2 text-sm font-semibold leading-6 text-gray-700 hover:bg-gray-50 hover:text-indigo-600 group transition-all duration-200 cursor-pointer">
                    <div class="flex items-center gap-x-3">
                        <svg class="h-6 w-6 shrink-0 text-gray-400 group-hover:text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
  <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75a.75.75 0 01-.75-.75V15m.75 0H3m0 0h-.375a.75.75 0 01-.75-.75V5.625a.75.75 0 01.75-.75H3.75m1.5 0h.375a.75.75 0 01.75.75v9.75a.75.75 0 01-.75.75h-.375m-.75 0H4.5m0 0v.75c0 .414.336.75.75.75H5.25m0 0h13.5m0-13.5h.375c.621 0 1.125.504 1.125 1.125v6.75c0 .621-.504 1.125-1.125 1.125H18.75A.75.75 0 0118 14.25V5.25a.75.75 0 00-.75-.75h-.375m0 0H6.375" />
</svg>
                        المالية والفواتير
                    </div>
                    <svg class="h-4 w-4 shrink-0 text-gray-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" /></svg>
                </button>
                <div x-show="open" x-collapse x-cloak class="mt-1 space-y-1 pr-11">
                    <a href="{{ route('accounting.invoices.index') }}" class="block rounded-md py-2 pr-2 text-sm font-medium {{ request()->routeIs('accounting.invoices.*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50' }}">الفواتير</a>
                    <a href="{{ route('accounting.reports.index') }}" class="block rounded-md py-2 pr-2 text-sm font-medium {{ request()->routeIs('accounting.reports.*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50' }}">التقارير المالية</a>
                </div>
            </div>

            <!-- System Section (Slate) -->
            <div x-data="{ open: {{ request()->routeIs('settings.*', 'staff.*', 'roles.*') ? 'true' : 'false' }} }" class="pt-2">
                <button @click="open = !open" class="flex w-full items-center justify-between rounded-md px-3 py-2 text-sm font-semibold leading-6 text-gray-700 hover:bg-gray-50 hover:text-indigo-600 group transition-all duration-200 cursor-pointer">
                    <div class="flex items-center gap-x-3">
                        <svg class="h-6 w-6 shrink-0 text-gray-400 group-hover:text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
  <path stroke-linecap="round" stroke-linejoin="round" d="M10.343 3.94c.09-.542.56-.94 1.11-.94h1.093c.55 0 1.02.398 1.11.94l.149.894c.07.424.384.764.78.93.398.164.855.142 1.205-.108l.737-.527a1.125 1.125 0 011.45.12l.773.774c.39.389.44 1.002.12 1.45l-.527.737c-.25.35-.272.806-.107 1.204.165.397.505.71.93.78l.893.15c.543.09.94.56.94 1.109v1.094c0 .55-.397 1.02-.94 1.11l-.893.149c-.425.07-.765.383-.93.78-.165.398-.143.854.107 1.204l.527.738c.32.447.269 1.06-.12 1.45l-.774.773a1.125 1.125 0 01-1.449.12l-.738-.527c-.35-.25-.806-.272-1.203-.107-.397.165-.71.505-.781.929l-.149.894c-.09.542-.56.94-1.11.94h-1.094c-.55 0-1.02-.398-1.11-.94l-.149-.894c-.07-.424-.384-.764-.78-.929-.398-.164-.855-.142-1.205.108l-.737.527a1.125 1.125 0 01-1.45-.12l-.773-.774a1.125 1.125 0 01-.12-1.45l.527-.737c.25-.35.273-.806.108-1.204-.165-.397-.505-.71-.93-.78l-.894-.15c-.542-.09-.94-.56-.94-1.109v-1.094c0-.55.398-1.02.94-1.11l.894-.149c.424-.07.765-.383.93-.78.165-.398.143-.854-.107-1.204l-.527-.738a1.125 1.125 0 01.12-1.45l.773-.773a1.125 1.125 0 011.45-.12l.738.527c.35.25.806.272 1.204.107.397-.165.71-.505.78-.929l.149-.894z" />
  <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
</svg>
                        إعدادات النظام
                    </div>
                    <svg class="h-4 w-4 shrink-0 text-gray-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" /></svg>
                </button>
                <div x-show="open" x-collapse x-cloak class="mt-1 space-y-1 pr-11">
                    <a href="{{ route('settings.index') }}" class="block rounded-md py-2 pr-2 text-sm font-medium {{ request()->routeIs('settings.*') ? 'text-slate-600 bg-slate-50' : 'text-gray-600 hover:text-slate-600 hover:bg-gray-50' }}">الإعدادات العامة</a>
                    <a href="{{ route('mobile-app.index') }}" class="block rounded-md py-2 pr-2 text-sm font-medium {{ request()->routeIs('mobile-app.*') ? 'text-slate-600 bg-slate-50' : 'text-gray-600 hover:text-slate-600 hover:bg-gray-50' }}">تطبيقات الموبايل</a>
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
    <div class="flex flex-1 flex-col h-full min-w-0">
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
