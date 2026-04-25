<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>دخول بوابة الاستخبارات الإدارية - MadaaQ</title>
    
    <!-- Design Foundation -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&family=Manrope:wght@800&family=Noto+Sans+Arabic:wght@400;700;900&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        vendor: '#6366f1',
                        'vendor-light': '#818cf8',
                        'vendor-dark': '#4f46e5',
                    },
                    fontFamily: {
                        sans: ['Noto Sans Arabic', 'Inter', 'sans-serif'],
                        inter: ['Inter', 'sans-serif'],
                        manrope: ['Manrope', 'sans-serif'],
                    },
                    boxShadow: {
                        'glass': '0 8px 32px 0 rgba(31, 38, 135, 0.07)',
                        'radiant': '0 0 50px -12px rgba(99, 102, 241, 0.3)',
                    }
                }
            }
        }
    </script>

    <style>
        .bg-mesh {
            background-color: #ffffff;
            background-image: 
                radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.1) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(165, 180, 252, 0.1) 0px, transparent 50%),
                radial-gradient(at 0% 100%, rgba(99, 102, 241, 0.05) 0px, transparent 50%),
                radial-gradient(at 100% 0%, rgba(165, 180, 252, 0.05) 0px, transparent 50%);
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.4);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.6);
        }

        .input-radiant {
            @apply w-full bg-white/50 border-white/60 rounded-2xl px-6 h-14 font-bold text-slate-900 transition-all duration-300;
            border-width: 1px;
        }
        .input-radiant:focus {
            @apply ring-4 ring-vendor/5 border-vendor outline-none bg-white;
        }

        .btn-radiant {
            @apply relative overflow-hidden transition-all duration-500 active:scale-95;
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        }
        .btn-radiant:hover {
            box-shadow: 0 0 30px rgba(99, 102, 241, 0.4);
            transform: translateY(-2px);
        }

        @keyframes float {
            0% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(5deg); }
            100% { transform: translateY(0px) rotate(0deg); }
        }
        .float-icon { animation: float 6s ease-in-out infinite; }
    </style>
</head>
<body class="bg-mesh min-h-screen flex items-center justify-center p-6 sm:p-12 overflow-hidden selection:bg-vendor selection:text-white">

    <!-- Decorative Elements -->
    <div class="fixed top-0 left-0 w-full h-full pointer-events-none z-0">
        <div class="absolute top-[10%] left-[15%] w-64 h-64 bg-vendor/5 rounded-full blur-[100px] animate-pulse"></div>
        <div class="absolute bottom-[10%] right-[15%] w-96 h-96 bg-indigo-500/5 rounded-full blur-[120px] animate-pulse"></div>
    </div>

    <div class="max-w-md w-full relative z-10 animate-in fade-in slide-in-from-bottom-8 duration-1000">
        
        <!-- Brand Unit -->
        <div class="text-center mb-12">
            <div class="inline-flex items-center justify-center w-24 h-24 rounded-[2.5rem] bg-slate-900 shadow-radiant mb-8 float-icon relative group">
                <div class="absolute inset-0 bg-vendor/20 opacity-0 group-hover:opacity-100 blur-2xl transition-opacity duration-1000"></div>
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="text-vendor relative z-10"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            </div>
            <h1 class="text-4xl font-black text-slate-900 tracking-tighter italic uppercase mb-2">Madaa<span class="text-vendor">Q</span></h1>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.4em] italic font-inter opacity-70">Administrative Intelligence Gateway</p>
        </div>

        <!-- Login Matrix -->
        <div class="glass-card p-10 sm:p-12 rounded-[3rem] shadow-glass relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-vendor/5 rounded-full blur-3xl -mr-16 -mt-16 pointer-events-none"></div>
            
            <div class="relative z-10">
                <div class="mb-10 text-right">
                    <h2 class="text-2xl font-black text-slate-900 italic tracking-tighter mb-1">تسجيل الدخول</h2>
                    <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest italic opacity-80">بروتوكول التحقق من الهوية السيادية</p>
                </div>

                <form method="POST" action="{{ route('login') }}" class="space-y-8">
                    @csrf

                    <!-- Identity Input -->
                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mr-4 block text-right">البريد الإلكتروني (Personnel ID)</label>
                        <div class="relative group">
                            <div class="absolute right-6 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-vendor transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                            </div>
                            <input name="email" type="email" required class="input-radiant pr-16 font-inter" placeholder="id@madaaq.net" value="{{ old('email') }}">
                        </div>
                        @error('email')
                            <p class="text-rose-500 text-[10px] font-black mr-4 uppercase tracking-widest mt-2 italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Secret Input -->
                    <div class="space-y-3">
                        <div class="flex justify-between items-center px-4 flex-row-reverse">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">كلمة المرور (Secret Key)</label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-[10px] font-black text-vendor uppercase tracking-widest hover:underline italic">نسيت الشيفرة؟</a>
                            @endif
                        </div>
                        <div class="relative group">
                            <div class="absolute right-6 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-vendor transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                            </div>
                            <input name="password" type="password" required class="input-radiant pr-16 font-inter tracking-[0.5em]" placeholder="••••••••">
                        </div>
                        @error('password')
                            <p class="text-rose-500 text-[10px] font-black mr-4 uppercase tracking-widest mt-2 italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tactical Options -->
                    <div class="flex items-center justify-end px-4">
                        <label class="flex items-center gap-3 cursor-pointer group select-none">
                            <span class="text-[11px] font-black text-slate-500 uppercase tracking-widest italic group-hover:text-vendor transition-colors">تذكر الهوية في هذه المحطة</span>
                            <div class="relative">
                                <input type="checkbox" name="remember" class="sr-only peer">
                                <div class="w-5 h-5 bg-white/50 border-white/60 border rounded-lg peer-checked:bg-vendor peer-checked:border-vendor transition-all flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" class="opacity-0 peer-checked:opacity-100 transition-opacity"><polyline points="20 6 9 17 4 12"/></svg>
                                </div>
                            </div>
                        </label>
                    </div>

                    <!-- Commit Trigger -->
                    <button type="submit" class="btn-radiant w-full py-5 rounded-2xl text-white font-black text-xs uppercase tracking-[0.4em] shadow-radiant flex items-center justify-center gap-4 group">
                        <span>دخول النظام</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="group-hover:-translate-x-1 transition-transform"><path d="m15 18-6-6 6-6"/></svg>
                    </button>
                </form>

                <!-- Footer Protocol -->
                <div class="mt-12 pt-8 border-t border-white/40 text-center">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic mb-4">
                        لا تملك هوية وصول؟
                        <a href="{{ route('register') }}" class="text-vendor hover:underline ml-1">بدء بروتوكول التسجيل</a>
                    </p>
                    <a href="/" class="text-[9px] font-black text-slate-300 uppercase tracking-[0.5em] hover:text-slate-500 transition-colors italic">Back to Central Command</a>
                </div>
            </div>
        </div>

        <!-- Compliance & Security Notice -->
        <p class="text-center mt-12 text-[8px] font-black text-slate-400 uppercase tracking-[0.4em] opacity-40 italic">
            SECURE ACCESS PROTOCOL V5.2.0 | ENCRYPTED BY MADAQA CORE
        </p>
    </div>

</body>
</html>
