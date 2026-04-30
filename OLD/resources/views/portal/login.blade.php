<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>بوابة المشتركين | تسجيل الدخول</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Cairo', sans-serif; }
        .font-english { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="bg-gray-900 min-h-screen flex items-center justify-center relative overflow-hidden">
    
    <!-- Background Decor -->
    <div class="absolute inset-0 bg-gradient-to-br from-gray-900 via-blue-900 to-gray-900"></div>
    <div class="absolute top-0 left-0 w-full h-full opacity-10 pointer-events-none">
        <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
            <path d="M0 0 L100 0 L100 100 L0 100 Z" fill="url(#grid)" />
            <defs>
                <pattern id="grid" width="4" height="4" patternUnits="userSpaceOnUse">
                    <rect width="4" height="4" fill="white" />
                    <rect width="3.5" height="3.5" fill="black" />
                </pattern>
            </defs>
        </svg>
    </div>
    <div class="absolute -top-40 -right-40 w-96 h-96 bg-blue-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
    <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>

    <div class="relative w-full max-w-md p-6">
        <div class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-3xl shadow-2xl p-8 md:p-10">
            <div class="text-center mb-10">
                <div class="w-20 h-20 bg-gradient-to-tr from-blue-500 to-purple-600 rounded-2xl mx-auto flex items-center justify-center shadow-lg mb-6 transform rotate-3 hover:rotate-6 transition duration-300">
                    <svg class="w-10 h-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                </div>
                <h1 class="text-3xl font-extrabold text-white mb-2 tracking-wide">أهلاً بك</h1>
                <p class="text-blue-200 text-sm">سجل دخولك لمتابعة استهلاكك وإدارة حسابك</p>
            </div>

            <form method="POST" action="{{ route('portal.login.submit', ['tenant_domain' => request()->getHost()]) }}" class="space-y-6">
                @csrf
                <div class="space-y-2">
                    <label class="text-xs font-bold text-blue-200 mr-1 uppercase opacity-80">اسم المستخدم أو رقم الهاتف</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-blue-300 group-focus-within:text-blue-400 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        </div>
                        <input type="text" name="username" required 
                            class="w-full pl-11 pr-4 py-4 bg-gray-800/50 border border-gray-700 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 transition font-english" 
                            placeholder="username / 09xxxxxxxx">
                    </div>
                    @error('username')
                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <label class="text-xs font-bold text-blue-200 mr-1 uppercase opacity-80">كلمة المرور</label>
                    </div>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-blue-300 group-focus-within:text-blue-400 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                        <input type="password" name="password" required 
                            class="w-full pl-11 pr-4 py-4 bg-gray-800/50 border border-gray-700 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 transition font-english" 
                            placeholder="••••••••">
                    </div>
                </div>

                <button type="submit" class="w-full py-4 px-6 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-blue-600/30 transform hover:-translate-y-0.5 transition duration-200 flex items-center justify-center gap-2">
                    <span>تسجيل الدخول</span>
                    <svg class="w-5 h-5 rtl:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </button>
            </form>
            
            <!-- Activation Links Removed as per request -->
            {{-- 
            <div class="mt-8 text-center space-y-2">
                <a href="{{ route('portal.setup.request') }}" class="block text-sm text-blue-400 font-bold hover:text-blue-300 transition">مشترك جديد / تفعيل الحساب</a>
                <a href="{{ route('portal.setup.request') }}" class="block text-xs text-blue-300/60 hover:text-blue-200 transition">هل نسيت كلمة المرور؟</a>
            </div>
            --}}
        </div>
        
        <div class="text-center mt-6">
            <p class="text-gray-500 text-xs">© {{ date('Y') }} جميع الحقوق محفوظة</p>
        </div>
    </div>
</body>
</html>
