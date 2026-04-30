# Source Catalog: cat1

## resources/views/auth\login.blade.php

```php
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - مذاق (Madaaq)</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Cairo', sans-serif; }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-2xl shadow-xl">
        <div class="text-center">
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                تسجيل الدخول
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                أهلاً بك مجدداً في نظام مذاق
            </p>
        </div>
        
        <form class="mt-8 space-y-6" method="POST" action="{{ route('login') }}">
            @csrf

            <div class="space-y-4">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">البريد الإلكتروني</label>
                    <input id="email" name="email" type="email" autocomplete="email" required class="appearance-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="البريد الإلكتروني" value="{{ old('email') }}">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">كلمة المرور</label>
                    <input id="password" name="password" type="password" autocomplete="current-password" required class="appearance-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="كلمة المرور">
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember_me" name="remember" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="remember_me" class="ml-2 block text-sm text-gray-900 mr-2">تذكرني</label>
                </div>

                @if (Route::has('password.request'))
                    <div class="text-sm">
                        <a href="{{ route('password.request') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
                            نسيت كلمة المرور؟
                        </a>
                    </div>
                @endif
            </div>

            <div>
                <button type="submit" class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-md transition-all transform hover:scale-[1.01]">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-indigo-500 group-hover:text-indigo-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                        </svg>
                    </span>
                    دخول النظام
                </button>
            </div>
            
            <div class="text-center mt-4 border-t pt-4">
                <p class="text-sm text-gray-600">
                    ليس لديك حساب؟
                    <a href="{{ route('register') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
                        أنشئ حساباً جديداً
                    </a>
                </p>
                <p class="mt-2 text-sm text-gray-500">
                    <a href="/" class="hover:underline">العودة للرئيسية</a>
                </p>
            </div>
        </form>
    </div>
</body>
</html>

```

## resources/views/auth\register.blade.php

```php
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إنشاء حساب جديد - مذاق (Madaaq)</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Cairo', sans-serif; }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-2xl shadow-xl">
        <div class="text-center">
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                إنشاء حساب مزود خدمة
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                انضم إلينا وابدأ في إدارة شبكتك اليوم
            </p>
        </div>
        
        @if(session('error'))
            <div class="bg-red-50 text-red-700 p-4 rounded-lg mb-6 border border-red-100">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 text-red-700 p-4 rounded-lg mb-6 border border-red-100 list-disc list-inside">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form class="mt-8 space-y-6" action="{{ route('register') }}" method="POST">
            @csrf
            
            <div class="space-y-4">
                <div>
                    <label for="company_name" class="block text-sm font-medium text-gray-700 mb-1">اسم الشركة / الشبكة</label>
                    <input id="company_name" name="company_name" type="text" required class="appearance-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="مثال: شبكة الأفق">
                </div>

                <div>
                    <label for="domain" class="block text-sm font-medium text-gray-700 mb-1">معرف النطاق (Subdomain)</label>
                    <div class="ltr flex items-center" dir="ltr">
                        <span class="p-3 bg-gray-100 border border-r-0 border-gray-300 rounded-l-lg text-gray-500 text-sm">.madaaq.com</span>
                        <input id="domain" name="domain" type="text" required class="appearance-none block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-r-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-left" placeholder="domain">
                    </div>
                </div>

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">الاسم الكامل</label>
                    <input id="name" name="name" type="text" required class="appearance-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="محمد أحمد">
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">البريد الإلكتروني</label>
                    <input id="email" name="email" type="email" autocomplete="email" required class="appearance-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="name@example.com">
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1 text-right">رقم الهاتف</label>
                    <div class="relative flex" dir="ltr">
                        <select name="country_code" class="appearance-none border border-gray-300 border-r-0 rounded-l-lg px-3 py-3 bg-gray-50 text-gray-700 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm w-32">
                            <option value="+963" selected>🇸🇾 +963</option>
                            <option value="+966">🇸🇦 +966</option>
                            <option value="+971">🇦🇪 +971</option>
                            <option value="+20">🇪🇬 +20</option>
                            <option value="+90">🇹🇷 +90</option>
                        </select>
                        <input id="phone" name="phone" type="tel" required class="appearance-none relative block w-full px-3 py-3 border border-gray-300 rounded-r-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="5xxxxxxxx">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">كلمة المرور</label>
                    <input id="password" name="password" type="password" autocomplete="new-password" required class="appearance-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm">
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">تأكيد كلمة المرور</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required class="appearance-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm">
                </div>

                <div class="flex items-center">
                    <input id="terms" name="terms" type="checkbox" required class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="terms" class="ml-2 block text-sm text-gray-900 mr-2">
                        أوافق على <a href="{{ route('terms') }}" target="_blank" class="text-indigo-600 hover:text-indigo-500 underline">الشروط والأحكام</a> و <a href="{{ route('privacy') }}" target="_blank" class="text-indigo-600 hover:text-indigo-500 underline">سياسة الخصوصية</a>
                    </label>
                </div>
            </div>

            <div>
                <button type="submit" class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-md transition-all transform hover:scale-[1.01]">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-indigo-500 group-hover:text-indigo-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </span>
                    إنشاء الحساب
                </button>
            </div>
            
            <div class="text-center mt-4">
                <p class="text-sm text-gray-600">
                    لديك حساب بالفعل؟
                    <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
                        تسجيل الدخول
                    </a>
                </p>
                <p class="mt-2 text-sm text-gray-500">
                    <a href="/" class="hover:underline">العودة للرئيسية</a>
                </p>
            </div>
        </form>
    </div>
</body>
</html>

```

## resources/views/auth\verify-email.blade.php

```php
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تأكيد البريد الإلكتروني - مذاق (Madaaq)</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Cairo', sans-serif; }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-2xl shadow-xl text-center">
        <div>
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                تأكيد البريد الإلكتروني
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                شكراً لتسجيلك! يرجى التحقق من بريدك الإلكتروني والنقر على الرابط المرسل إليك لتفعيل حسابك.
            </p>
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="bg-green-50 text-green-700 p-4 rounded-lg mb-6 border border-green-100 text-sm">
                تم إرسال رابط تحقق جديد إلى بريدك الإلكتروني.
            </div>
        @endif

        <div class="mt-8 space-y-4">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-md">
                    إعادة إرسال بريد التحقق
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-sm font-medium text-gray-500 hover:text-gray-700 underline">
                    تسجيل الخروج
                </button>
            </form>
        </div>
    </div>
</body>
</html>

```

## resources/views/layouts\admin.blade.php

```php
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

```

## resources/views/layouts\app.blade.php

```php
<!DOCTYPE html>
<html lang="ar" dir="rtl" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'MadaaQ') }}</title>
    
    <!-- Google Font - Rubik & Tajawal -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@300;400;500;600;700;800;900&family=Tajawal:wght@300;400;500;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Rubik', 'Tajawal', sans-serif; background: radial-gradient(circle at top left, #f8fafc, #f1f5f9); min-height: 100vh; }
        .font-inter { font-family: 'Inter', sans-serif; }
        
        /* Premium Translucent Scrollbar */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(0, 0, 0, 0.05); border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(0, 0, 0, 0.1); }

        /* Glassmorphism Utilities */
        .glass-panel { background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.3); }
        .glass-sidebar { background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(30px); -webkit-backdrop-filter: blur(30px); border-left: 1px solid rgba(255, 255, 255, 0.2); }
        
        /* Subtle Glow */
        .nav-active-glow { box-shadow: 0 0 20px -5px rgba(79, 70, 229, 0.3); }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="h-full selection:bg-indigo-100 selection:text-indigo-900 antialiased lg:flex relative overflow-x-hidden" x-data="{ sidebarOpen: false }">
    <!-- Floating Background Orbs -->
    <div class="fixed top-[-10%] left-[-10%] w-[40%] h-[40%] bg-indigo-200/20 rounded-full blur-[120px] -z-10 animate-pulse"></div>
    <div class="fixed bottom-[-10%] right-[-10%] w-[30%] h-[30%] bg-blue-200/20 rounded-full blur-[100px] -z-10"></div>
    <div class="fixed top-[20%] right-[5%] w-[15%] h-[15%] bg-purple-200/10 rounded-full blur-[80px] -z-10"></div>


    <!-- Mobile Sidebar Backdrop -->
    <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-40 lg:hidden" @click="sidebarOpen = false"></div>

    <!-- Sidebar -->
    <div :class="sidebarOpen ? 'translate-x-0' : 'translate-x-full lg:translate-x-0'" class="fixed inset-y-0 right-0 z-50 w-72 glass-sidebar transition-transform duration-500 ease-in-out shadow-[20px_0_50px_-30px_rgba(0,0,0,0.05)] lg:static lg:inset-auto lg:flex lg:flex-col">
        
        <!-- Logo -->
        <div class="flex h-20 shrink-0 items-center gap-3 px-6 border-b border-white/20 bg-white/30">
            <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-600 to-blue-600 text-white shadow-lg shadow-indigo-200/50 transform rotate-3">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
            <div>
                <span class="text-xl font-black tracking-tight text-gray-900">Madaa<span class="text-indigo-600 font-extrabold italic">Q</span></span>
                <p class="text-[9px] uppercase tracking-widest text-indigo-400 font-bold -mt-1 font-inter">ISP Control</p>
            </div>
            
            <button @click="sidebarOpen = false" class="mr-auto lg:hidden text-gray-500 hover:text-indigo-600 transition-colors">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 overflow-y-auto sidebar-scroll px-4 py-6 space-y-1">
            
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white nav-active-glow translate-x-[-4px]' : 'text-gray-500 hover:bg-white/50 hover:text-indigo-600' }} group flex items-center gap-x-3 rounded-xl px-4 py-3 text-sm font-bold leading-6 transition-all duration-300">
                <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-gray-400 group-hover:text-indigo-600' }}" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
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
                    <a href="{{ route('network.towers.index') }}" class="block rounded-md py-2 pr-2 text-sm font-medium {{ request()->routeIs('network.towers.*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50' }}">مواقع الشبكة (Sites)</a>
                    <a href="{{ route('maps.index') }}" class="block rounded-md py-2 pr-2 text-sm font-medium {{ request()->routeIs('maps.index') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50' }}">الخريطة الجغرافية</a>
                    <a href="{{ route('routers.index') }}" class="block rounded-md py-2 pr-2 text-sm font-medium {{ request()->routeIs('routers.*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50' }}">تجهيزات الشبكة</a>
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
            <!-- Support & Contact (Indigo) -->
            <div class="pt-2">
                <a href="{{ route('support.index') }}" 
                   class="flex items-center gap-x-3 rounded-xl px-4 py-3 text-sm font-bold leading-6 transition-all duration-300 {{ request()->routeIs('support.*') ? 'bg-indigo-600 text-white nav-active-glow translate-x-[-4px]' : 'text-gray-500 hover:bg-white/50 hover:text-indigo-600' }}">
                    <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('support.*') ? 'text-white' : 'text-gray-400 group-hover:text-indigo-600' }}" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z" />
                    </svg>
                    الدعم الفني المباشر
                </a>
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
                    <svg class="pointer-events-none absolute inset-y-0 right-4 h-full w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" />
                    </svg>
                    <input id="search-field" class="block h-full w-full border-0 py-0 pr-12 pl-0 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm transition-all" placeholder="ابحث في النظام..." type="search" name="search">
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

```

## resources/views/layouts\guest.blade.php

```php
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'MadaaQ') }} - Pro Portal</title>

    <!-- Google Font - Rubik & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@300;400;500;600;700;800;900&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body { 
            font-family: 'Rubik', 'Inter', sans-serif; 
        }
        .bg-premium-light {
            background-color: #f8fafc;
            background-image: 
                radial-gradient(at 0% 0%, rgba(124, 58, 237, 0.05) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(37, 99, 235, 0.05) 0px, transparent 50%);
            position: relative;
            overflow-x: hidden;
        }
        .brand-gradient {
            background: linear-gradient(135deg, #7c3aed 0%, #2563eb 100%);
        }
    </style>
</head>
<body class="bg-premium-light text-slate-900 antialiased selection:bg-indigo-100">
    <div class="min-h-screen flex flex-col items-center justify-center p-6 sm:p-12">
        <div class="mb-10 text-center transform transition-all duration-500 hover:scale-105">
            <a href="/" class="inline-flex flex-col items-center gap-4">
                <div class="w-16 h-16 brand-gradient rounded-3xl flex items-center justify-center shadow-xl shadow-indigo-200">
                    <svg class="w-10 h-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <div class="space-y-1">
                    <h1 class="text-4xl font-black brand-gradient bg-clip-text text-transparent tracking-tight uppercase">MadaaQ</h1>
                    <p class="text-slate-400 text-[10px] font-bold tracking-[0.3em] uppercase">ISP Management Experience</p>
                </div>
            </a>
        </div>

        <div class="w-full sm:max-w-md">
            @yield('content')
        </div>

        <div class="mt-12 text-slate-400 text-xs font-medium">
            &copy; {{ date('Y') }} MadaaQ Pro. <span class="mx-1 opacity-30">|</span> Powered by High Speed Tech
        </div>
    </div>
</body>
</html>

```

## resources/views/layouts\portal.blade.php

```php
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

        /* Fallback sizes if Tailwind CSS fails to load */
        .w-3 { width: 0.75rem; } .h-3 { height: 0.75rem; }
        .w-4 { width: 1rem; } .h-4 { height: 1rem; }
        .w-5 { width: 1.25rem; } .h-5 { height: 1.25rem; }
        .w-6 { width: 1.5rem; } .h-6 { height: 1.5rem; }
        .w-7 { width: 1.75rem; } .h-7 { height: 1.75rem; }
        .w-8 { width: 2rem; } .h-8 { height: 2rem; }
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

```

## resources/views/components\modal.blade.php

```php
@props(['name', 'title'])

<div
    x-data="{ show: false, name: '{{ $name }}' }"
    x-show="show"
    x-on:open-modal.window="if ($event.detail === name) show = true"
    x-on:close-modal.window="if ($event.detail === name) show = false"
    x-on:keydown.escape.window="show = false"
    style="display: none;"
    class="fixed inset-0 z-50 overflow-y-auto"
    role="dialog"
    aria-modal="true"
    aria-labelledby="modal-title"
>
    <!-- Overlay -->
    <div x-show="show" x-transition.opacity class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity"></div>

    <!-- Panel -->
    <div x-show="show" x-transition.scale.origin.center class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-gray-200">
            
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-gradient-to-r from-blue-50 to-indigo-50">
                <h3 class="text-lg font-bold leading-6 text-gray-900" id="modal-title">
                    {{ $title }}
                </h3>
                <button type="button" @click="show = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Body -->
            <div class="bg-white">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>

```

## resources/views/components\whatsapp-button.blade.php

```php
@php
$notificationService = new \App\Services\NotificationService();
$whatsappConfigured = $notificationService->isWhatsAppConfigured();
$whatsappType = $notificationService->getWhatsAppType();
@endphp

@if($whatsappConfigured)
    @if($whatsappType === 'regular')
        {{-- Regular WhatsApp Number - Direct Link --}}
        @php
            $whatsappLink = $notificationService->getWhatsAppLink($message ?? 'مرحباً، أحتاج إلى مساعدة.');
        @endphp
        <a href="{{ $whatsappLink }}" 
           target="_blank"
           class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
            </svg>
            <span>{{ $buttonText ?? 'تواصل عبر واتساب' }}</span>
        </a>
    @else
        {{-- WhatsApp API - Show button but needs backend implementation --}}
        <button 
            onclick="alert('ميزة إرسال الرسائل التلقائية قيد التطوير')"
            class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all opacity-75 cursor-not-allowed">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
            </svg>
            <span>{{ $buttonText ?? 'تواصل عبر واتساب' }}</span>
        </button>
    @endif
@endif

```

## resources/views/vendor\mail\html\button.blade.php

```php
@props([
    'url',
    'color' => 'primary',
    'align' => 'center',
])
<table class="action" align="{{ $align }}" width="100%" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td align="{{ $align }}">
<table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td align="{{ $align }}">
<table border="0" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td>
<a href="{{ $url }}" class="button button-{{ $color }}" target="_blank" rel="noopener">{!! $slot !!}</a>
</td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>
</table>

```

## resources/views/vendor\mail\html\footer.blade.php

```php
<tr>
<td>
<table class="footer" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td class="content-cell" align="center">
{{ Illuminate\Mail\Markdown::parse($slot) }}
</td>
</tr>
</table>
</td>
</tr>

```

## resources/views/vendor\mail\html\header.blade.php

```php
@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
MadaaQ | مدى كيو
</a>
</td>
</tr>

```

## resources/views/vendor\mail\html\layout.blade.php

```php
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>{{ config('app.name') }}</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="color-scheme" content="light">
<meta name="supported-color-schemes" content="light">
<style>
@media only screen and (max-width: 600px) {
.inner-body {
width: 100% !important;
}

.footer {
width: 100% !important;
}
}

@media only screen and (max-width: 500px) {
.button {
width: 100% !important;
}
}
</style>
{!! $head ?? '' !!}
</head>
<body>

<table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td align="center">
<table class="content" width="100%" cellpadding="0" cellspacing="0" role="presentation">
{!! $header ?? '' !!}

<!-- Email Body -->
<tr>
<td class="body" width="100%" cellpadding="0" cellspacing="0" style="border: hidden !important;">
<table class="inner-body" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation">
<!-- Body content -->
<tr>
<td class="content-cell">
{!! Illuminate\Mail\Markdown::parse($slot) !!}

{!! $subcopy ?? '' !!}
</td>
</tr>
</table>
</td>
</tr>

{!! $footer ?? '' !!}
</table>
</td>
</tr>
</table>
</body>
</html>

```

## resources/views/vendor\mail\html\message.blade.php

```php
<x-mail::layout>
{{-- Header --}}
<x-slot:header>
<x-mail::header :url="config('app.url')">
{{ config('app.name') }}
</x-mail::header>
</x-slot:header>

{{-- Body --}}
{!! $slot !!}

{{-- Subcopy --}}
@isset($subcopy)
<x-slot:subcopy>
<x-mail::subcopy>
{!! $subcopy !!}
</x-mail::subcopy>
</x-slot:subcopy>
@endisset

{{-- Footer --}}
<x-slot:footer>
<x-mail::footer>
© {{ date('Y') }} {{ config('app.name') }}. {{ __('All rights reserved.') }}
</x-mail::footer>
</x-slot:footer>
</x-mail::layout>

```

## resources/views/vendor\mail\html\panel.blade.php

```php
<table class="panel" width="100%" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td class="panel-content">
<table width="100%" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td class="panel-item">
{{ Illuminate\Mail\Markdown::parse($slot) }}
</td>
</tr>
</table>
</td>
</tr>
</table>


```

## resources/views/vendor\mail\html\subcopy.blade.php

```php
<table class="subcopy" width="100%" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td>
{{ Illuminate\Mail\Markdown::parse($slot) }}
</td>
</tr>
</table>

```

## resources/views/vendor\mail\html\table.blade.php

```php
<div class="table">
{{ Illuminate\Mail\Markdown::parse($slot) }}
</div>

```

## resources/views/vendor\mail\text\button.blade.php

```php
{{ $slot }}: {{ $url }}

```

## resources/views/vendor\mail\text\footer.blade.php

```php
{{ $slot }}

```

## resources/views/vendor\mail\text\header.blade.php

```php
{{ $slot }}: {{ $url }}

```

## resources/views/vendor\mail\text\layout.blade.php

```php
{!! strip_tags($header ?? '') !!}

{!! strip_tags($slot) !!}
@isset($subcopy)

{!! strip_tags($subcopy) !!}
@endisset

{!! strip_tags($footer ?? '') !!}

```

## resources/views/vendor\mail\text\message.blade.php

```php
<x-mail::layout>
    {{-- Header --}}
    <x-slot:header>
        <x-mail::header :url="config('app.url')">
            {{ config('app.name') }}
        </x-mail::header>
    </x-slot:header>

    {{-- Body --}}
    {{ $slot }}

    {{-- Subcopy --}}
    @isset($subcopy)
        <x-slot:subcopy>
            <x-mail::subcopy>
                {{ $subcopy }}
            </x-mail::subcopy>
        </x-slot:subcopy>
    @endisset

    {{-- Footer --}}
    <x-slot:footer>
        <x-mail::footer>
            © {{ date('Y') }} {{ config('app.name') }}. @lang('All rights reserved.')
        </x-mail::footer>
    </x-slot:footer>
</x-mail::layout>

```

## resources/views/vendor\mail\text\panel.blade.php

```php
{{ $slot }}

```

## resources/views/vendor\mail\text\subcopy.blade.php

```php
{{ $slot }}

```

## resources/views/vendor\mail\text\table.blade.php

```php
{{ $slot }}

```

## resources/views/welcome.blade.php

```php
{{-- Deployment Test: 2026-04-18 v2 --}}
<!DOCTYPE html>
<html lang="ar" dir="rtl" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'MadaaQ ISP Manager') }} | مدى كيو</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/intersect@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Tajawal', sans-serif; }
        
        .blob {
            position: absolute;
            background: linear-gradient(180deg, rgba(79, 70, 229, 0.4) 0%, rgba(124, 58, 237, 0.4) 100%);
            filter: blur(80px);
            z-index: -1;
            animation: move 10s infinite alternate;
        }
        @keyframes move {
            from { transform: translate(0, 0) scale(1); }
            to { transform: translate(20px, -20px) scale(1.1); }
        }
        
        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .bg-grid-pattern {
            background-image: 
                linear-gradient(to right, rgba(0,0,0,0.05) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(0,0,0,0.05) 1px, transparent 1px);
            background-size: 40px 40px;
        }
        
        /* Reveal Animation Classes */
        .reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s cubic-bezier(0.5, 0, 0, 1);
        }
        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>
<body class="antialiased bg-slate-50 text-slate-900 overflow-x-hidden">

    <!-- Navbar -->
    <nav class="fixed w-full z-50 glass shadow-sm transition-all duration-300" 
         x-data="{ scrolled: false }" 
         @scroll.window="scrolled = (window.pageYOffset > 20)"
         :class="{ 'shadow-md bg-white/95': scrolled, 'glass': !scrolled }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <div class="flex-shrink-0 flex items-center gap-3">
                    <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center text-white shadow-indigo-200 shadow-lg">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <span class="font-extrabold text-2xl tracking-tight text-slate-800">مدى كيو</span>
                </div>
                <div class="hidden md:flex items-center space-x-8 space-x-reverse">
                    <a href="#features" class="text-base font-semibold text-slate-600 hover:text-indigo-600 transition duration-200">الميزات</a>
                    <a href="#how-it-works" class="text-base font-semibold text-slate-600 hover:text-indigo-600 transition duration-200">كيف يعمل؟</a>
                    <a href="#pricing" class="text-base font-semibold text-slate-600 hover:text-indigo-600 transition duration-200">الأسعار</a>
                </div>
                <!-- Auth Buttons -->
                <div class="flex items-center space-x-4 space-x-reverse">
                    @auth
                         <a href="{{ url('/dashboard') }}" class="inline-flex items-center px-5 py-2.5 rounded-xl border border-transparent text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 transition shadow-lg shadow-indigo-600/20 transform hover:-translate-y-0.5">
                            لوحة التحكم
                            <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-base font-semibold text-slate-600 hover:text-indigo-600 transition">تسجيل الدخول</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="hidden md:inline-flex items-center justify-center px-6 py-2.5 rounded-xl text-white bg-slate-900 hover:bg-slate-800 transition font-bold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                ابدأ الآن
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden bg-grid-pattern">
        <!-- Blobs -->
        <div class="blob w-[500px] h-[500px] rounded-full top-0 right-[-100px] opacity-30"></div>
        <div class="blob w-[400px] h-[400px] rounded-full bottom-0 left-[-100px] opacity-30 bg-purple-500 animation-delay-2000"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center z-10">
            <div x-data="{ show: false }" x-init="setTimeout(() => show = true, 100)" class="transition-all duration-1000 transform" :class="show ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'">
                <div class="inline-flex items-center px-3 py-1 rounded-full bg-indigo-50 border border-indigo-100 text-indigo-600 text-sm font-bold mb-6">
                    <span class="flex h-2 w-2 rounded-full bg-indigo-600 ml-2 animate-pulse"></span>
                    نظام إدارة الشبكات الأحدث لعام 2025
                </div>
                <h1 class="text-5xl md:text-7xl font-black tracking-tight mb-8 text-slate-900 leading-tight">
                    أدر شبكتك <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600">بذكاء</span> <br />
                    وضاعف أرباحك
                </h1>
                <p class="mt-4 text-xl md:text-2xl text-slate-600 max-w-3xl mx-auto mb-10 leading-relaxed font-medium">
                    حل سحابي متكامل يمنحك السيطرة الكاملة على المشتركين، الفواتير، والأبراج. <br class="hidden md:block">
                    قل وداعاً للأخطاء اليدوية ومشاكل المزامنة.
                </p>
                
                <div class="flex flex-col sm:flex-row justify-center gap-4 mb-16">
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="px-8 py-4 rounded-2xl bg-indigo-600 text-white font-bold text-lg hover:bg-indigo-700 transition shadow-xl shadow-indigo-600/30 hover:-translate-y-1 flex items-center justify-center">
                            ابدأ تجربتك المجانية
                              <svg class="mr-2 w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" /></svg>
                        </a>
                    @else
                         <a href="{{ route('login') }}" class="px-8 py-4 rounded-2xl bg-indigo-600 text-white font-bold text-lg hover:bg-indigo-700 transition shadow-xl shadow-indigo-600/30 hover:-translate-y-1 flex items-center justify-center">
                            الدخول للنظام
                            <svg class="mr-2 w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" /></svg>
                        </a>
                    @endif
                    <a href="#features" class="px-8 py-4 rounded-2xl bg-white text-slate-700 font-bold text-lg border border-slate-200 hover:bg-slate-50 transition shadow-lg hover:-translate-y-1 flex items-center justify-center">
                        <svg class="ml-2 w-5 h-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        شاهد الفيديو
                    </a>
                </div>
            </div>

            <!-- UI Mockup -->
            <div x-data="{ show: false }" x-intersect.threshold.0.1="show = true" class="reveal relative mx-auto max-w-5xl" :class="{ 'active': show }">
                <div class="relative rounded-2xl bg-slate-900/50 p-3 ring-1 ring-inset ring-slate-900/10 lg:rounded-3xl lg:p-4 backdrop-blur-sm">
                    <img src="{{ asset('img/dashboard.png') }}" alt="MadaaQ Dashboard" class="rounded-xl shadow-2xl w-full border border-slate-200">
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Section -->
    <div class="bg-indigo-900 py-16 text-white relative overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center divide-x divide-indigo-800 divide-x-reverse">
                <div x-data="{ show: false }" x-intersect.threshold.0.5="show = true" :class="{ 'opacity-100 translate-y-0': show, 'opacity-0 translate-y-4': !show }" class="transition duration-700">
                    <p class="text-4xl md:text-5xl font-black text-indigo-300 mb-2">+500</p>
                    <p class="text-indigo-100 font-medium">شبكة معتمدة</p>
                </div>
                <div x-data="{ show: false }" x-intersect.threshold.0.5="show = true" :class="{ 'opacity-100 translate-y-0': show, 'opacity-0 translate-y-4': !show }" class="transition duration-700 delay-100">
                    <p class="text-4xl md:text-5xl font-black text-pink-300 mb-2">99.9%</p>
                    <p class="text-indigo-100 font-medium">وقت تشغيل</p>
                </div>
                <div x-data="{ show: false }" x-intersect.threshold.0.5="show = true" :class="{ 'opacity-100 translate-y-0': show, 'opacity-0 translate-y-4': !show }" class="transition duration-700 delay-200">
                    <p class="text-4xl md:text-5xl font-black text-emerald-300 mb-2">+1M</p>
                    <p class="text-indigo-100 font-medium">مشترك فعال</p>
                </div>
                <div x-data="{ show: false }" x-intersect.threshold.0.5="show = true" :class="{ 'opacity-100 translate-y-0': show, 'opacity-0 translate-y-4': !show }" class="transition duration-700 delay-300">
                    <p class="text-4xl md:text-5xl font-black text-yellow-300 mb-2">24/7</p>
                    <p class="text-indigo-100 font-medium">دعم فني</p>
                </div>
            </div>
        </div>
    </div>

    <!-- How it Works -->
    <div id="how-it-works" class="py-24 bg-white relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
             <div class="text-center mb-20">
                <h2 class="text-indigo-600 font-bold tracking-wide uppercase text-sm">كيف يعمل النظام؟</h2>
                <h3 class="mt-2 text-3xl md:text-4xl font-extrabold text-slate-900">انطلق في 3 خطوات بسيطة</h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 relative">
                <!-- Line -->
                <div class="hidden md:block absolute top-12 right-0 left-0 h-0.5 bg-indigo-100 z-0"></div>

                <!-- Step 1 -->
                <div x-data="{ show: false }" x-intersect.threshold.0.2="show = true" class="reveal relative z-10 text-center bg-white p-4" :class="{ 'active': show }">
                    <div class="w-24 h-24 mx-auto bg-indigo-50 border-4 border-white shadow-xl rounded-full flex items-center justify-center text-3xl font-bold text-indigo-600 mb-6">1</div>
                    <h4 class="text-xl font-bold text-slate-900 mb-3">أنشئ حسابك</h4>
                    <p class="text-slate-500 leading-relaxed">سجل في المنصة بدقيقة واحدة، واختر الباقة التي تناسب حجم شبكتك.</p>
                </div>
                 <!-- Step 2 -->
                <div x-data="{ show: false }" x-intersect.threshold.0.2="show = true" class="reveal relative z-10 text-center bg-white p-4 delay-200" :class="{ 'active': show }">
                    <div class="w-24 h-24 mx-auto bg-pink-50 border-4 border-white shadow-xl rounded-full flex items-center justify-center text-3xl font-bold text-pink-600 mb-6">2</div>
                    <h4 class="text-xl font-bold text-slate-900 mb-3">اربط أجهزتك</h4>
                    <p class="text-slate-500 leading-relaxed">أضف راوترات الميكروتيك الخاصة بك وسيتم مزامنة المشتركين والباقات تلقائياً.</p>
                </div>
                 <!-- Step 3 -->
                <div x-data="{ show: false }" x-intersect.threshold.0.2="show = true" class="reveal relative z-10 text-center bg-white p-4 delay-400" :class="{ 'active': show }">
                    <div class="w-24 h-24 mx-auto bg-emerald-50 border-4 border-white shadow-xl rounded-full flex items-center justify-center text-3xl font-bold text-emerald-600 mb-6">3</div>
                    <h4 class="text-xl font-bold text-slate-900 mb-3">ابدأ التحصيل</h4>
                    <p class="text-slate-500 leading-relaxed">راقب شبكتك، اطبع البطاقات، وحصل فواتيرك إلكترونياً وبكل سهولة.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Grid (Redesigned) -->
    <div id="features" class="py-24 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-20">
                <h2 class="text-3xl md:text-5xl font-extrabold text-slate-900 mb-6">ميزات تفوق التوقعات</h2>
                <p class="text-xl text-slate-500 max-w-2xl mx-auto">كل أداة تحتاجها تم تصميمها بعناية لخدمة مزودي الإنترنت.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature Card Template -->
                <div x-data="{ show: false }" x-intersect.threshold.0.1="show = true" class="reveal bg-white rounded-3xl p-8 shadow-sm hover:shadow-2xl hover:shadow-indigo-500/10 transition duration-300 border border-slate-100 group" :class="{ 'active': show }">
                    <div class="w-16 h-16 rounded-2xl bg-indigo-50 group-hover:bg-indigo-600 transition duration-300 flex items-center justify-center text-indigo-600 group-hover:text-white mb-6">
                         <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" /></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">مزامنة ميكروتيك</h3>
                    <p class="text-slate-500 leading-relaxed">تحكم كامل براوتراتك دون الحاجة للدخول لـ Winbox. إدارة المستخدمين، البروفايلات، والسرعات بضغطة زر.</p>
                </div>

                <div x-data="{ show: false }" x-intersect.threshold.0.1="show = true" class="reveal bg-white rounded-3xl p-8 shadow-sm hover:shadow-2xl hover:shadow-pink-500/10 transition duration-300 border border-slate-100 group delay-100" :class="{ 'active': show }">
                    <div class="w-16 h-16 rounded-2xl bg-pink-50 group-hover:bg-pink-600 transition duration-300 flex items-center justify-center text-pink-600 group-hover:text-white mb-6">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">فواتير آلية</h3>
                    <p class="text-slate-500 leading-relaxed">نظام محاسبي ذكي يرسل الفواتير تلقائياً عبر SMS وواتساب، مع دعم الدفع الإلكتروني المباشر.</p>
                </div>

                <div x-data="{ show: false }" x-intersect.threshold.0.1="show = true" class="reveal bg-white rounded-3xl p-8 shadow-sm hover:shadow-2xl hover:shadow-sky-500/10 transition duration-300 border border-slate-100 group delay-200" :class="{ 'active': show }">
                    <div class="w-16 h-16 rounded-2xl bg-sky-50 group-hover:bg-sky-600 transition duration-300 flex items-center justify-center text-sky-600 group-hover:text-white mb-6">
                         <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" /></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">خريطة الأبراج</h3>
                    <p class="text-slate-500 leading-relaxed">شاهد توزيع أبراجك ومشتركيك على الخريطة، وحدد مناطق التغطية والمشاكل فور حدوثها.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Pricing (Cards with heavy shadows and highlights) -->
    <div id="pricing" class="py-24 bg-white relative overflow-hidden">
        <div class="absolute inset-0 bg-slate-50 skew-y-3 transform origin-bottom-left z-0 h-full"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-extrabold text-slate-900">باقات مرنة تناسب الجميع</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto items-center">
                 <!-- Starter -->
                <div x-data="{ show: false }" x-intersect.threshold.0.1="show = true" class="reveal bg-white rounded-3xl shadow-xl p-8 border border-slate-100 hover:-translate-y-2 transition duration-300" :class="{ 'active': show }">
                    <h3 class="text-xl font-semibold text-slate-500">البداية</h3>
                    <div class="my-6">
                        <span class="text-5xl font-black text-slate-900">$49</span>
                        <span class="text-slate-400 font-medium">/شهر</span>
                    </div>
                    <ul class="space-y-4 mb-8">
                        <li class="flex items-center text-slate-700 font-medium"><svg class="w-5 h-5 text-indigo-500 ml-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> 5 راوترات</li>
                        <li class="flex items-center text-slate-700 font-medium"><svg class="w-5 h-5 text-indigo-500 ml-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> مستخدمين غير محدودين</li>
                        <li class="flex items-center text-slate-700 font-medium"><svg class="w-5 h-5 text-indigo-500 ml-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> دعم فني عبر البريد</li>
                    </ul>
                    <a href="{{ Route::has('register') ? route('register') : route('login') }}" class="block w-full py-4 text-center rounded-xl border-2 border-slate-100 font-bold text-slate-600 hover:border-indigo-600 hover:text-indigo-600 transition">تجربة مجانية</a>
                </div>

                <!-- Pro (Highlight) -->
                <div x-data="{ show: false }" x-intersect.threshold.0.1="show = true" class="reveal bg-slate-900 rounded-3xl shadow-2xl p-10 border border-slate-800 transform scale-105 relative z-20" :class="{ 'active': show }">
                    <div class="absolute top-0 right-0 left-0 h-2 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-t-3xl"></div>
                    <div class="flex justify-between items-center">
                        <h3 class="text-xl font-bold text-white">احترافية</h3>
                        <span class="px-3 py-1 bg-gradient-to-r from-indigo-500 to-purple-500 text-white text-xs font-bold rounded-full">الأكثر طلباً</span>
                    </div>
                    <div class="my-8">
                        <span class="text-6xl font-black text-white">$99</span>
                        <span class="text-slate-400 font-medium">/شهر</span>
                    </div>
                    <ul class="space-y-5 mb-10">
                        <li class="flex items-center text-slate-300 font-medium"><svg class="w-5 h-5 text-indigo-400 ml-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> راوترات غير محدودة</li>
                        <li class="flex items-center text-slate-300 font-medium"><svg class="w-5 h-5 text-indigo-400 ml-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> مراقبة الشبكة الحية</li>
                        <li class="flex items-center text-slate-300 font-medium"><svg class="w-5 h-5 text-indigo-400 ml-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> دعم فني VIP</li>
                        <li class="flex items-center text-slate-300 font-medium"><svg class="w-5 h-5 text-indigo-400 ml-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> تطبيق للمشتركين</li>
                    </ul>
                    <a href="{{ Route::has('register') ? route('register') : route('login') }}" class="block w-full py-5 text-center rounded-xl bg-indigo-600 text-white font-bold text-lg hover:bg-indigo-700 transition shadow-lg shadow-indigo-600/20">اشترك الآن</a>
                </div>

                <!-- Enterprise -->
                <div x-data="{ show: false }" x-intersect.threshold.0.1="show = true" class="reveal bg-white rounded-3xl shadow-xl p-8 border border-slate-100 hover:-translate-y-2 transition duration-300" :class="{ 'active': show }">
                    <h3 class="text-xl font-semibold text-slate-500">شركات</h3>
                    <div class="my-6">
                        <span class="text-4xl font-black text-slate-900">مخصص</span>
                    </div>
                    <ul class="space-y-4 mb-8">
                        <li class="flex items-center text-slate-700 font-medium"><svg class="w-5 h-5 text-indigo-500 ml-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> سيرفر خاص</li>
                        <li class="flex items-center text-slate-700 font-medium"><svg class="w-5 h-5 text-indigo-500 ml-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> تطبيق باسم شركتك</li>
                        <li class="flex items-center text-slate-700 font-medium"><svg class="w-5 h-5 text-indigo-500 ml-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> مدير حساب خاص</li>
                    </ul>
                    <a href="#" class="block w-full py-4 text-center rounded-xl border-2 border-slate-100 font-bold text-slate-600 hover:border-slate-800 hover:text-slate-800 transition">اتصل بنا</a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="py-24 bg-slate-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
             <div class="text-center mb-16">
                <h2 class="text-3xl font-extrabold text-slate-900">أسئلة شائعة</h2>
            </div>
            
            <div class="space-y-4" x-data="{ selected: null }">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                    <button @click="selected !== 1 ? selected = 1 : selected = null" class="w-full px-6 py-5 text-right flex justify-between items-center focus:outline-none">
                        <span class="font-bold text-lg text-slate-800">هل أحتاج لخبرة تقنية لاستخدام النظام؟</span>
                        <svg class="w-5 h-5 text-slate-400 transform transition-transform duration-200" :class="selected === 1 ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="selected === 1" x-collapse>
                        <div class="px-6 pb-6 text-slate-600 leading-relaxed">
                            كلا، تم تصميم النظام ليكون سهل الاستخدام لأي شخص. نقوم أيضاً بتوفير شروحات فيديو ودعم فني لمساعدتك في كل خطوة.
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                    <button @click="selected !== 2 ? selected = 2 : selected = null" class="w-full px-6 py-5 text-right flex justify-between items-center focus:outline-none">
                        <span class="font-bold text-lg text-slate-800">هل يدعم النظام الدفع الإلكتروني؟</span>
                         <svg class="w-5 h-5 text-slate-400 transform transition-transform duration-200" :class="selected === 2 ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                     <div x-show="selected === 2" x-collapse>
                        <div class="px-6 pb-6 text-slate-600 leading-relaxed">
                            نعم، يدعم النظام بوابات دفع متعددة مثل PayPal و Stripe، بالإضافة إلى التكامل مع المحافظ المحلية مثل سيريتل كاش وشام كاش.
                        </div>
                    </div>
                </div>
                
                 <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                    <button @click="selected !== 3 ? selected = 3 : selected = null" class="w-full px-6 py-5 text-right flex justify-between items-center focus:outline-none">
                        <span class="font-bold text-lg text-slate-800">هل يمكنني تجربة النظام قبل الشراء؟</span>
                         <svg class="w-5 h-5 text-slate-400 transform transition-transform duration-200" :class="selected === 3 ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                     <div x-show="selected === 3" x-collapse>
                        <div class="px-6 pb-6 text-slate-600 leading-relaxed">
                            بالتأكيد! نوفر خطة مجانية أو فترة تجريبية لتستكشف كافة الميزات وتتأكد من أنها تلبي احتياجاتك.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-slate-900 pt-20 pb-10 text-slate-400 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-16">
                <div class="col-span-1 md:col-span-1">
                    <span class="text-white text-2xl font-bold block mb-6">مدى كيو</span>
                    <p class="text-sm leading-loose">الشريك التقني الأول لمزودي خدمة الإنترنت في المنطقة. نساعدك على النمو بكل ثقة.</p>
                </div>
                 <div>
                    <h4 class="text-white font-bold mb-6">المنتج</h4>
                    <ul class="space-y-3 text-sm">
                        <li><a href="#" class="hover:text-white transition">الميزات</a></li>
                        <li><a href="#" class="hover:text-white transition">الأسعار</a></li>
                        <li><a href="#" class="hover:text-white transition">التحديثات</a></li>
                    </ul>
                </div>
                 <div>
                    <h4 class="text-white font-bold mb-6">الدعم</h4>
                    <ul class="space-y-3 text-sm">
                        <li><a href="#" class="hover:text-white transition">مركز المساعدة</a></li>
                        <li><a href="#" class="hover:text-white transition">تواصل معنا</a></li>
                        <li><a href="#" class="hover:text-white transition">حالة الخدمة</a></li>
                    </ul>
                </div>
                 <div>
                    <h4 class="text-white font-bold mb-6">قانوني</h4>
                    <ul class="space-y-3 text-sm">
                        <li><a href="{{ route('privacy') }}" class="hover:text-white transition">الخصوصية</a></li>
                        <li><a href="{{ route('terms') }}" class="hover:text-white transition">الشروط</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-slate-800 pt-8 text-center text-sm">
                &copy; {{ date('Y') }} مدى كيو. جميع الحقوق محفوظة.
            </div>
        </div>
    </footer>

</body>
</html>

```

## resources/views/privacy.blade.php

```php
@extends('layouts.guest')

@section('content')
<div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8 bg-white shadow-md rounded-lg mt-10">
    <div class="text-center mb-10">
        <h1 class="text-3xl font-bold text-gray-900">سياسة الخصوصية</h1>
        <p class="mt-2 text-gray-600">آخر تحديث: {{ date('Y-m-d') }}</p>
    </div>

    <div class="prose prose-lg prose-indigo mx-auto text-gray-700" dir="rtl">
        <h3>1. مقدمة</h3>
        <p>نحن في "مدى كيو" (MadaaQ) نلتزم بحماية خصوصيتك وضمان أمان بياناتك الشخصية. توضح هذه السياسة كيفية جمعنا واستخدامنا وحمايتنا لمعلوماتك.</p>

        <h3>2. البيانات التي نجمعها</h3>
        <p>قد نجمع المعلومات التالية:</p>
        <ul>
            <li>المعلومات الشخصية: الاسم، البريد الإلكتروني، رقم الهاتف.</li>
            <li>معلومات الشركة: اسم الشبكة، النطاق، العنوان.</li>
            <li>البيانات التقنية: عناوين IP، نوع المتصفح، وسجلات الاستخدام.</li>
        </ul>

        <h3>3. كيفية استخدام البيانات</h3>
        <p>نستخدم بياناتك للأغراض التالية:</p>
        <ul>
            <li>توفير وتحسين خدماتنا.</li>
            <li>إدارة حسابك واشتراكك.</li>
            <li>التواصل معك بخصوص التحديثات والعروض.</li>
            <li>ضمان أمان النظام ومنع الاحتيال.</li>
        </ul>

        <h3>4. مشاركة البيانات</h3>
        <p>لا نقوم ببيع أو تأجير بياناتك لأطراف ثالثة. قد نشارك معلوماتك فقط مع مزودي الخدمة الموثوقين (مثل بوابات الدفع) لتسهيل عملياتك.</p>

        <h3>5. حماية البيانات</h3>
        <p>نتبع معايير أمان عالمية لحماية بياناتك من الوصول غير المصرح به، التغيير، أو الإتلاف.</p>

        <h3>6. الاتصال بنا</h3>
        <p>إذا كان لديك أي أسئلة حول سياسة الخصوصية، يرجى التواصل معنا عبر البريد الإلكتروني: support@madaaq.com</p>
    </div>
    
    <div class="mt-8 text-center">
        <a href="{{ url('/') }}" class="text-indigo-600 hover:text-indigo-500 font-medium">العودة للصفحة الرئيسية</a>
    </div>
</div>
@endsection

```

## resources/views/terms.blade.php

```php
@extends('layouts.guest')

@section('content')
<div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8 bg-white shadow-md rounded-lg mt-10">
    <div class="text-center mb-10">
        <h1 class="text-3xl font-bold text-gray-900">الشروط والأحكام</h1>
        <p class="mt-2 text-gray-600">آخر تحديث: {{ date('Y-m-d') }}</p>
    </div>

    <div class="prose prose-lg prose-indigo mx-auto text-gray-700" dir="rtl">
        <h3>1. قبول الشروط</h3>
        <p>باستخدامك لمنصة "مدى كيو"، فإنك توافق على الالتزام بهذه الشروط والأحكام. إذا كنت لا توافق على أي جزء منها، يرجى عدم استخدام الخدمة.</p>

        <h3>2. الحسابات والاشتراكات</h3>
        <p>أنت مسؤول عن الحفاظ على سرية بيانات دخولك. تحتفظ "مدى كيو" بالحق في تعليق أو إنهاء حسابك في حال انتهاك هذه الشروط.</p>

        <h3>3. الاستخدام المقبول</h3>
        <p>يجب استخدام الخدمة لأغراض قانونية ومشروعة فقط. يُحظر استخدام النظام لأي أنشطة ضارة، احتيالية، أو تضر بالبنية التحتية للشبكة.</p>

        <h3>4. الملكية الفكرية</h3>
        <p>جميع حقوق الملكية الفكرية المتعلقة بالمنصة ومحتواها (باستثناء بياناتك) تعود لشركة "مدى كيو".</p>

        <h3>5. حدود المسؤولية</h3>
        <p>نحن نسعى لتقديم خدمة عالية الجودة، ولكننا لا نضمن أن الخدمة ستكون خالية من الأخطاء أو الانقطاعات. لا تتحمل "مدى كيو" مسؤولية أي أضرار غير مباشرة ناتجة عن استخدام الخدمة.</p>

        <h3>6. التعديلات</h3>
        <p>نحتفظ بالحق في تعديل هذه الشروط في أي وقت. سيتم إشعارك بأي تغييرات جوهرية.</p>
    </div>
    
    <div class="mt-8 text-center">
        <a href="{{ url('/') }}" class="text-indigo-600 hover:text-indigo-500 font-medium">العودة للصفحة الرئيسية</a>
    </div>
</div>
@endsection

```

## resources/views/app.blade.php

```php
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'MadaaQ') }}</title>

        <!-- Premium Architectural Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@300;400;500;600;700&family=Manrope:wght@400;600;700;800&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-body antialiased bg-background text-on-surface selection:bg-primary-fixed selection:text-on-primary-fixed">
        @inertia
    </body>
</html>

```

