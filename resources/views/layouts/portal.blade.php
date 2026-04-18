<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>بوابة المشتركين | مدى كيو</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@300;400;500;600;700&family=Manrope:wght@400;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS (Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    </style>
</head>
<body class="bg-surface text-on-surface font-body overflow-x-hidden antialiased">
    
    <!-- Navigation Monolith -->
    <nav class="bg-white/80 backdrop-blur-md border-b border-outline-variant/15 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center gap-10">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-primary flex items-center justify-center text-white font-bold text-lg">M</div>
                        <span class="font-black text-xl text-primary tracking-tight">مدى كيو</span>
                    </div>
                    
                    <!-- Desktop Nav -->
                    <div class="hidden md:flex items-center gap-2">
                        <a href="{{ route('portal.dashboard', ['tenant_domain' => request()->getHost()]) }}" 
                           class="px-4 py-2 rounded-lg text-sm font-bold transition-all {{ request()->routeIs('portal.dashboard') ? 'bg-primary text-white' : 'text-slate-500 hover:text-primary hover:bg-surface-container-low' }}">
                            لوحة التحكم
                        </a>
                        <a href="{{ route('portal.invoices.index', ['tenant_domain' => request()->getHost()]) }}" 
                           class="px-4 py-2 rounded-lg text-sm font-bold transition-all {{ request()->routeIs('portal.invoices.*') ? 'bg-primary text-white' : 'text-slate-500 hover:text-primary hover:bg-surface-container-low' }}">
                            الفواتير
                        </a>
                    </div>
                </div>

                <div class="flex items-center gap-6">
                    <div class="flex items-center gap-3">
                        <div class="text-left">
                            <p class="text-xs font-bold text-primary leading-tight">{{ Auth::guard('client')->user()->username }}</p>
                            <p class="text-[9px] text-slate-400 font-headline uppercase tracking-widest">Subscriber Account</p>
                        </div>
                        <form method="POST" action="{{ route('portal.logout', ['tenant_domain' => request()->getHost()]) }}">
                            @csrf
                            <button type="submit" class="p-2 text-slate-400 hover:text-error transition-colors">
                                <span class="material-symbols-outlined text-xl">logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content Monolith -->
    <main class="py-12 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-surface-container-low border-t border-outline-variant/10 py-12 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-slate-400">
            <p class="text-xs font-bold uppercase tracking-widest font-headline">&copy; {{ date('Y') }} MadaaQ Networking Solutions</p>
        </div>
    </footer>

</body>
</html>
