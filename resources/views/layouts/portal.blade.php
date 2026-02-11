<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>بوابة المشتركين</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Rubik', sans-serif; }
        /* Safety rule for icons */
        img { max-width: 100%; height: auto; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">
    <nav class="bg-white shadow-sm border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center gap-8">
                    <span class="font-bold text-xl text-indigo-600">لوحة المشترك</span>
                    
                    <!-- Desktop Nav -->
                    <div class="hidden md:flex items-center gap-1">
                        <a href="{{ route('portal.dashboard', ['tenant_domain' => request()->getHost()]) }}" class="px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('portal.dashboard') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50' }}">
                            الرئيسية
                        </a>
                        <a href="{{ route('portal.invoices.index', ['tenant_domain' => request()->getHost()]) }}" class="px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('portal.invoices.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50' }}">
                            فواتيري
                        </a>
                        <a href="{{ route('portal.packages.index', ['tenant_domain' => request()->getHost()]) }}" class="px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('portal.packages.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50' }}">
                            الباقات
                        </a>
                    </div>
                </div>
                <div class="flex items-center">
                    <div class="ml-4 flex items-center md:ml-6">
                        <span class="text-sm font-medium text-gray-500 ml-2">مرحباً, {{ Auth::guard('client')->user()->username }}</span>
                        <form method="POST" action="{{ route('portal.logout', ['tenant_domain' => request()->getHost()]) }}">
                            @csrf
                            <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-800">خروج</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <main class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @yield('content')
        </div>
    </main>
</body>
</html>
