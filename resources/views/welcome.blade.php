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
    
    <!-- FAQ Section -->
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
