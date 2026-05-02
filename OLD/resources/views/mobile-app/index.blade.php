@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto space-y-12">
    
    <!-- Hero / Header Section -->
    <div class="relative bg-white rounded-[2rem] overflow-hidden shadow-xl border border-gray-100">
        <div class="absolute inset-0 bg-gradient-to-r from-blue-50 to-indigo-50/50"></div>
        <div class="absolute top-0 right-0 w-1/3 h-full bg-gradient-to-l from-blue-100/40 to-transparent skew-x-12 opacity-50"></div>
        
        <div class="relative z-10 px-8 py-16 md:py-20 lg:px-16 flex flex-col md:flex-row items-center justify-between gap-12">
            <div class="md:w-1/2 space-y-8">
                <div>
                    <span class="inline-flex items-center px-4 py-1.5 rounded-full bg-blue-600/10 text-blue-700 text-sm font-bold mb-4 border border-blue-100">
                        <span class="w-2 h-2 rounded-full bg-blue-600 ml-2 animate-pulse"></span>
                        أنشئ تطبيقك الخاص
                    </span>
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-gray-900 leading-[1.15] tracking-tight">
                        حول شركتك إلى <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">تطبيق احترافي</span>
                    </h1>
                </div>
                
                <p class="text-lg text-gray-500 leading-relaxed max-w-xl">
                    امنح عملائك تجربة استخدام لا تُنسى. تطبيق مخصص باسمك وشعارك، يتيح لهم متابعة باقاتهم، تسديد الفواتير، والتواصل مع الدعم الفني بلمسة واحدة.
                </p>

                @if(!$app)
                <div class="flex flex-col sm:flex-row gap-4">
                    @if(auth()->user()->isPro())
                    <a href="{{ route('mobile-app.create') }}" class="inline-flex items-center justify-center px-8 py-4 text-base font-bold text-white bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl hover:shadow-lg hover:shadow-blue-500/30 transition transform hover:-translate-y-0.5">
                        🚀 ابدأ بإعداد تطبيقك
                    </a>
                    @else
                    <a href="{{ route('subscription.index') }}" class="inline-flex items-center justify-center px-8 py-4 text-base font-bold text-white bg-gradient-to-r from-purple-600 to-pink-600 rounded-xl hover:shadow-lg hover:shadow-purple-500/30 transition transform hover:-translate-y-0.5">
                        ⭐ اشتراك بالباقة
                    </a>
                    @endif
                    <a href="#features" class="inline-flex items-center justify-center px-8 py-4 text-base font-bold text-gray-700 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition">
                        معرفة المزيد
                    </a>
                </div>
                @endif
            </div>

            <!-- Modern Phone Illustration -->
            <div class="md:w-1/2 flex justify-center relative">
                <div class="relative w-[280px] h-[580px] bg-gray-900 rounded-[3rem] border-8 border-gray-900 shadow-2xl transform rotate-[-6deg] hover:rotate-0 transition duration-700 ease-out z-10 ring-1 ring-gray-900/50">
                    <!-- Notch -->
                    <div class="absolute top-0 left-1/2 transform -translate-x-1/2 h-6 w-32 bg-gray-900 rounded-b-2xl z-20"></div>
                    
                    <!-- Screen -->
                    <div class="absolute inset-0 rounded-[2.5rem] overflow-hidden bg-white flex flex-col">
                        <!-- App Header -->
                        <div class="h-48 bg-gradient-to-br from-blue-600 to-indigo-700 p-6 pt-12 text-white relative overflow-hidden">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-2xl -mr-10 -mt-10"></div>
                            <div class="flex justify-between items-center mb-6 relative z-10">
                                <div class="w-8 h-8 bg-white/20 rounded-lg backdrop-blur-sm"></div>
                                <div class="w-8 h-8 bg-white/20 rounded-full backdrop-blur-sm"></div>
                            </div>
                            <div class="space-y-2 relative z-10">
                                <div class="h-3 w-20 bg-white/40 rounded-full"></div>
                                <div class="h-6 w-32 bg-white rounded-lg"></div>
                            </div>
                        </div>
                        
                        <!-- App Body -->
                        <div class="flex-1 bg-gray-50 p-4 space-y-4">
                            <!-- Stats Cards -->
                            <div class="grid grid-cols-2 gap-3">
                                <div class="bg-white p-3 rounded-2xl shadow-sm h-24"></div>
                                <div class="bg-white p-3 rounded-2xl shadow-sm h-24"></div>
                            </div>
                            <!-- List Items -->
                            <div class="bg-white p-4 rounded-2xl shadow-sm h-16 flex items-center gap-3">
                                <div class="w-10 h-10 bg-gray-100 rounded-full"></div>
                                <div class="flex-1 space-y-2">
                                    <div class="h-2 w-24 bg-gray-100 rounded-full"></div>
                                    <div class="h-2 w-16 bg-gray-50 rounded-full"></div>
                                </div>
                            </div>
                            <div class="bg-white p-4 rounded-2xl shadow-sm h-16 flex items-center gap-3">
                                <div class="w-10 h-10 bg-gray-100 rounded-full"></div>
                                <div class="flex-1 space-y-2">
                                    <div class="h-2 w-24 bg-gray-100 rounded-full"></div>
                                    <div class="h-2 w-16 bg-gray-50 rounded-full"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Bottom Nav -->
                        <div class="h-16 bg-white border-t border-gray-100 flex justify-around items-center px-6">
                            <div class="w-6 h-6 bg-blue-500 rounded-full"></div>
                            <div class="w-6 h-6 bg-gray-200 rounded-full"></div>
                            <div class="w-6 h-6 bg-gray-200 rounded-full"></div>
                            <div class="w-6 h-6 bg-gray-200 rounded-full"></div>
                        </div>
                    </div>
                </div>

                <!-- Decorative Blob -->
                <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-gradient-to-r from-blue-100 to-indigo-100 rounded-full blur-3xl -z-10 opacity-60"></div>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 px-6 py-4 rounded-xl flex items-center gap-3 font-medium">
        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        {{ session('success') }}
    </div>
    @endif

    @if($app)
    <!-- Dashboard View (For existing app) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Main App Card -->
        <div class="lg:col-span-8 bg-white rounded-[1.5rem] shadow-lg border border-gray-100 overflow-hidden">
            <div class="p-8">
                <div class="flex flex-col md:flex-row gap-8 items-center md:items-start text-center md:text-right">
                    <!-- App Icon -->
                     <div class="relative group shrink-0">
                        @if($app->icon_path)
                        <img src="{{ asset('storage/' . $app->icon_path) }}" class="w-40 h-40 rounded-[2rem] shadow-xl group-hover:scale-105 transition duration-300 object-cover border border-gray-100" alt="App Icon">
                        @else
                        <div class="w-40 h-40 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-[2rem] shadow-xl flex items-center justify-center text-white text-6xl font-bold border border-white/20">
                            {{ substr($app->app_name, 0, 1) }}
                        </div>
                        @endif
                        <div class="absolute -bottom-3 -right-3">
                             {!! $app->status_badge !!}
                        </div>
                    </div>

                    <div class="flex-1 space-y-4">
                        <div>
                            <h2 class="text-3xl font-bold text-gray-900">{{ $app->app_name }}</h2>
                            <p class="text-indigo-600 font-medium">{{ $app->app_name_en ?? 'Unknown Package Name' }}</p>
                        </div>
                        
                        <p class="text-gray-500 leading-relaxed max-w-lg">
                            {{ $app->description ?? 'لا يوجد وصف متاح للتطبيق.' }}
                        </p>

                        <div class="flex flex-wrap gap-3 justify-center md:justify-start pt-2">
                             @if($app->status === 'completed' && $app->aab_file_path)
                            <a href="{{ asset('storage/' . $app->aab_file_path) }}" download class="inline-flex items-center gap-2 px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-xl font-bold shadow-md shadow-green-200 transition">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                تحميل التطبيق (AAB)
                            </a>
                            @else
                            <button disabled class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-100 text-gray-400 rounded-xl font-bold cursor-not-allowed">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                جاري التجهيز...
                            </button>
                            @endif
                            
                            <a href="{{ route('mobile-app.create', ['edit' => 1]) }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 rounded-xl font-bold transition">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                                إعدادات التطبيق
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Detail Grid -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-8 pt-8 border-t border-gray-100">
                    <div class="p-4 bg-gray-50 rounded-2xl text-center">
                        <div class="text-xs text-gray-500 mb-1">الإصدار الحالي</div>
                        <div class="text-xl font-bold text-gray-900">1.0.0</div>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-2xl text-center">
                        <div class="text-xs text-gray-500 mb-1">تاريخ الطلب</div>
                        <div class="text-xl font-bold text-gray-900">{{ $app->created_at->format('Y-m-d') }}</div>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-2xl text-center">
                        <div class="text-xs text-gray-500 mb-1">اللون الأساسي</div>
                        <div class="flex items-center justify-center gap-2 mt-1">
                            <div class="w-6 h-6 rounded-full shadow-sm border border-gray-200" style="background: {{ $app->primary_color }}"></div>
                            <span class="text-sm font-mono text-gray-600">{{ $app->primary_color }}</span>
                        </div>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-2xl text-center">
                        <div class="text-xs text-gray-500 mb-1">اللون الثانوي</div>
                        <div class="flex items-center justify-center gap-2 mt-1">
                             <div class="w-6 h-6 rounded-full shadow-sm border border-gray-200" style="background: {{ $app->secondary_color }}"></div>
                             <span class="text-sm font-mono text-gray-600">{{ $app->secondary_color }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="lg:col-span-4 space-y-6">
            <!-- Stores Status -->
            <div class="bg-white rounded-[1.5rem] shadow-lg border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    حالة المتاجر
                </h3>
                <div class="space-y-3">
                    <a href="#" class="flex items-center p-3 rounded-xl border border-gray-100 hover:bg-gray-50 transition group">
                        <div class="w-10 h-10 bg-gray-900 rounded-lg flex items-center justify-center text-white shrink-0 group-hover:scale-110 transition">
                            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor"><path d="M17.9188 3.90759C17.4438 4.4507 16.4094 5.41383 15.5344 5.97071C14.9438 6.33821 14.3594 6.65946 14.3406 6.65946C14.3219 6.65946 14.2531 6.63196 14.1844 6.59821C13.8219 6.42071 12.3969 5.82071 11.8219 5.67696C11.3281 5.55196 10.7469 5.47071 10.1344 5.44321C9.30939 5.40321 8.31564 5.50321 7.48439 5.71446C6.95939 5.85196 6.08439 6.15196 5.54689 6.39446L5.27189 6.51321L4.76564 6.08446C4.49064 5.85196 3.74064 5.27696 3.16564 4.84196L2.12189 4.00321L2.24689 3.82571C2.55939 3.35696 3.35314 2.62571 4.00939 2.18446C5.83439 0.956458 7.99064 0.268958 10.6969 0.0451583C11.8906 -0.0485917 13.6781 0.0326583 14.7156 0.221158C16.5219 0.575408 18.0844 1.30671 19.1594 2.29421C19.4781 2.59421 19.9719 3.22571 19.9719 3.29421C19.9719 3.31546 19.0031 4.01546 18.8031 4.14671C18.7844 4.15296 18.3594 3.71796 17.9188 3.90759Z"/></svg>
                        </div>
                        <div class="mr-3 flex-1">
                            <div class="text-sm font-bold text-gray-900">Google Play</div>
                            <div class="text-xs text-green-600 font-medium">قيد المراجعة</div>
                        </div>
                        <svg class="w-5 h-5 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>

                    <a href="#" class="flex items-center p-3 rounded-xl border border-gray-100 hover:bg-gray-50 transition group">
                        <div class="w-10 h-10 bg-black rounded-lg flex items-center justify-center text-white shrink-0 group-hover:scale-110 transition">
                            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor"><path d="M18.71 19.5C17.88 20.74 17 21.95 15.66 21.97C14.32 22 13.89 21.18 12.37 21.18C10.84 21.18 10.37 21.95 9.09997 22C7.78997 22.05 6.79997 20.68 5.95997 19.47C4.24997 17 2.93997 12.45 4.69997 9.39C5.56997 7.87 7.12997 6.91 8.81997 6.88C10.1 6.86 11.32 7.75 12.11 7.75C12.89 7.75 14.37 6.68 15.92 6.84C16.57 6.87 18.39 7.1 19.56 8.82C19.47 8.88 17.39 10.1 17.41 12.63C17.44 15.65 20.06 16.66 20.09 16.67C20.06 16.74 19.67 18.11 18.71 19.5ZM13 3.5C13.73 2.67 14.94 2.04 15.94 2C16.07 3.17 15.6 4.35 14.9 5.19C14.21 6.04 13.07 6.7 11.95 6.61C11.8 5.46 12.36 4.26 13 3.5Z"/></svg>
                        </div>
                        <div class="mr-3 flex-1">
                            <div class="text-sm font-bold text-gray-900">App Store</div>
                            <div class="text-xs text-yellow-600 font-medium">بانتظار الموافقة</div>
                        </div>
                         <svg class="w-5 h-5 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>

            <!-- Support Card -->
            <div class="bg-blue-600 rounded-[1.5rem] shadow-lg p-6 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-3xl -mr-16 -mt-16"></div>
                
                <h3 class="text-lg font-bold mb-2">هل تحتاج مساعدة؟</h3>
                <p class="text-blue-100 text-sm mb-4">فريق الدعم جاهز لمساعدتك في نشر تطبيقك وربطه مع المتاجر.</p>
                
                <button class="w-full py-3 bg-white text-blue-700 font-bold rounded-xl shadow-lg hover:shadow-xl transition">
                    تواصل مع الدعم
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Features Section (Always Visible) -->
    <div id="features" class="py-12">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">لماذا تحتاج تطبيقاً خاصاً؟</h2>
            <p class="text-gray-500 max-w-2xl mx-auto">تعرف على الميزات التي ستجعل عملائك يفضلون التعامل معك</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Feature 1 -->
            <div class="bg-white p-8 rounded-[2rem] border border-gray-100 shadow-sm hover:shadow-xl transition duration-300 group">
                <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-blue-600 transition duration-300">
                    <svg class="w-8 h-8 text-blue-600 group-hover:text-white transition duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">إشعارات فورية</h3>
                <p class="text-gray-500 leading-relaxed">
                    تواصل مع عملائك مجاناً. أرسل إشعارات عند قرب انتهاء الاشتراك، أو عند إصدار فاتورة جديدة، أو للعروض الخاصة.
                </p>
            </div>

            <!-- Feature 2 -->
            <div class="bg-white p-8 rounded-[2rem] border border-gray-100 shadow-sm hover:shadow-xl transition duration-300 group">
                <div class="w-16 h-16 bg-indigo-50 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-indigo-600 transition duration-300">
                    <svg class="w-8 h-8 text-indigo-600 group-hover:text-white transition duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">دفع إلكتروني متكامل</h3>
                <p class="text-gray-500 leading-relaxed">
                    دع عملائك يجددون اشتراكاتهم بأنفسهم عبر التطبيق باستخدام بوابات الدفع الإلكتروني، مما يقلل الضغط على فريقك.
                </p>
            </div>

            <!-- Feature 3 -->
            <div class="bg-white p-8 rounded-[2rem] border border-gray-100 shadow-sm hover:shadow-xl transition duration-300 group">
                <div class="w-16 h-16 bg-purple-50 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-purple-600 transition duration-300">
                    <svg class="w-8 h-8 text-purple-600 group-hover:text-white transition duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">لوحة تحكم للعميل</h3>
                <p class="text-gray-500 leading-relaxed">
                    امنح العميل القدرة على معرفة استهلاكه، سرعة اتصاله، وتاريخ انتهاء باقته في أي وقت ومن أي مكان.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
