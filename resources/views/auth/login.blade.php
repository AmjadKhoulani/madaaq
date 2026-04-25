<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - MadaaQ</title>
    
    <!-- Design Foundation -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700;800;900&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        vendor: '#4f46e5',
                    },
                    fontFamily: {
                        tajawal: ['Tajawal', 'sans-serif'],
                        inter: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <style>
        body { font-family: 'Tajawal', sans-serif; }
        .bg-mesh {
            background-color: #0f172a;
            background-image: 
                radial-gradient(at 0% 0%, rgba(79, 70, 229, 0.2) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(124, 58, 237, 0.1) 0px, transparent 50%);
        }
        
        .input-radiant {
            @apply w-full bg-slate-50 border border-slate-200 rounded-2xl px-6 h-16 font-bold text-slate-900 transition-all duration-300;
        }
        .input-radiant:focus {
            @apply ring-4 ring-indigo-600/5 border-indigo-600 outline-none bg-white;
        }

        .btn-radiant {
            @apply relative overflow-hidden transition-all duration-500 active:scale-95 bg-slate-900 text-white font-black text-sm uppercase tracking-[0.3em];
        }
        .btn-radiant:hover {
            @apply bg-indigo-700 shadow-xl shadow-indigo-600/20;
        }

        @keyframes float {
            0% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(5deg); }
            100% { transform: translateY(0px) rotate(0deg); }
        }
        .float-icon { animation: float 6s ease-in-out infinite; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex overflow-hidden">

    <!-- 1. Visual Side (Left) -->
    <div class="hidden lg:flex lg:w-1/2 bg-mesh relative items-center justify-center overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-[radial-gradient(circle_at_center,#ffffff_1px,transparent_1px)] [background-size:40px_40px]"></div>
        
        <div class="relative z-10 p-20 text-right">
            <div class="mb-12">
                <div class="w-16 h-16 bg-indigo-600 rounded-2xl flex items-center justify-center text-white shadow-2xl mb-8 float-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="currentColor" stroke="none"><path d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                </div>
                <h2 class="text-5xl font-black text-white leading-tight mb-6">
                    أدر شبكتك <br />
                    <span class="text-indigo-400">بذكاء استثنائي</span>
                </h2>
                <p class="text-slate-400 text-xl max-w-md leading-relaxed font-medium">
                    انضم لمئات مزودي الخدمة الذين اختاروا مدى كيو لتطوير أعمالهم.
                </p>
            </div>
            
            <div class="space-y-6">
                <div class="flex items-center gap-4 text-white/80">
                    <div class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-indigo-400"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    </div>
                    <span class="text-sm font-bold">أمان وموثوقية بنسبة 99.9%</span>
                </div>
                <div class="flex items-center gap-4 text-white/80">
                    <div class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-indigo-400"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    </div>
                    <span class="text-sm font-bold">إدارة متكاملة للمشتركين</span>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Login Side (Right) -->
    <div class="w-full lg:w-1/2 flex items-center justify-center p-8 sm:p-20 bg-white lg:rounded-r-[4rem] shadow-2xl relative z-20">
        <div class="max-w-md w-full">
            <!-- Brand Mobile -->
            <div class="lg:hidden text-center mb-10">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-indigo-600 shadow-xl mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="white"><path d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                </div>
            </div>

            <div class="mb-12">
                <h3 class="text-3xl font-black text-slate-900 mb-3 tracking-tight">تسجيل الدخول</h3>
                <p class="text-slate-400 font-bold text-sm">أهلاً بك مجدداً في <span class="text-indigo-600">مدى كيو</span></p>
            </div>

            <form method="POST" action="{{ route('login') }}" class="space-y-8">
                @csrf
                <div class="space-y-3">
                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest mr-4 block">البريد الإلكتروني</label>
                    <div class="relative group">
                        <div class="absolute right-6 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-indigo-600 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        </div>
                        <input name="email" type="email" required class="input-radiant pr-16" placeholder="name@example.com" value="{{ old('email') }}">
                    </div>
                    @error('email')
                        <p class="text-rose-500 text-[10px] font-black mr-4 uppercase tracking-widest mt-2 italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-3">
                    <div class="flex justify-between items-center px-4">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest">كلمة المرور</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-[10px] font-black text-indigo-600 uppercase tracking-widest hover:underline italic">نسيت كلمة المرور؟</a>
                        @endif
                    </div>
                    <div class="relative group">
                        <div class="absolute right-6 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-indigo-600 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        </div>
                        <input name="password" type="password" required class="input-radiant pr-16 tracking-[0.5em]" placeholder="••••••••">
                    </div>
                </div>

                <div class="flex items-center justify-end px-4">
                    <label class="flex items-center gap-3 cursor-pointer group select-none">
                        <span class="text-[11px] font-black text-slate-500 uppercase tracking-widest italic group-hover:text-indigo-600 transition-colors">تذكر الهوية في هذه المحطة</span>
                        <div class="relative">
                            <input type="checkbox" name="remember" class="sr-only peer">
                            <div class="w-6 h-6 bg-slate-100 border-slate-200 border-2 rounded-lg peer-checked:bg-indigo-600 peer-checked:border-indigo-600 transition-all flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" class="opacity-0 peer-checked:opacity-100 transition-opacity"><polyline points="20 6 9 17 4 12"/></svg>
                            </div>
                        </div>
                    </label>
                </div>

                <button type="submit" class="btn-radiant w-full py-6 rounded-2xl flex items-center justify-center gap-4 group">
                    <span>دخول النظام</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="group-hover:-translate-x-1 transition-transform"><path d="m15 18-6-6 6-6"/></svg>
                </button>
            </form>

            <div class="mt-16 pt-10 border-t border-slate-100 text-center">
                <p class="text-xs font-bold text-slate-400 mb-6">
                    ليس لديك حساب؟
                    <a href="{{ route('register') }}" class="text-indigo-600 hover:underline mr-1 font-black">أنشئ حسابك الآن</a>
                </p>
                <p class="text-[8px] font-black text-slate-300 uppercase tracking-[0.4em] opacity-40">
                    SECURE ACCESS PROTOCOL V5.2.0
                </p>
            </div>
        </div>
    </div>

</body>
</html>
