@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center gap-2 text-sm text-gray-500">
        <a href="{{ route('mobile-app.index') }}" class="hover:text-indigo-600">تطبيق الموبايل</a>
        <span>›</span>
        <span class="text-gray-900">تحميل التطبيق</span>
    </div>

    <div class="bg-white rounded-2xl shadow-xl border border-indigo-50 overflow-hidden">
        <div class="p-8 text-center space-y-6">
            <div class="w-20 h-20 bg-indigo-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            </div>
            
            <h2 class="text-3xl font-bold text-gray-900">تحميل تطبيق Vendors</h2>
            <p class="text-gray-500 max-w-lg mx-auto">
                قم بتحميل التطبيق الرسمي لإدارة شبكتك وعملائك من أي مكان. متوفر الآن لأجهزة أندرويد و iOS (قريباً).
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mt-8">
                <a href="#" class="flex items-center gap-3 px-6 py-3 bg-gray-900 text-white rounded-xl hover:bg-gray-800 transition shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <svg class="w-8 h-8 text-green-400" viewBox="0 0 24 24" fill="currentColor"><path d="M17.9188 3.90759C17.4438 4.4507 16.4094 5.41383 15.5344 5.97071C14.9438 6.33821 14.3594 6.65946 14.3406 6.65946C14.3219 6.65946 14.2531 6.63196 14.1844 6.59821C13.8219 6.42071 12.3969 5.82071 11.8219 5.67696C11.3281 5.55196 10.7469 5.47071 10.1344 5.44321C9.30939 5.40321 8.31564 5.50321 7.48439 5.71446C6.95939 5.85196 6.08439 6.15196 5.54689 6.39446L5.27189 6.51321L4.76564 6.08446C4.49064 5.85196 3.74064 5.27696 3.16564 4.84196L2.12189 4.00321L2.24689 3.82571C2.55939 3.35696 3.35314 2.62571 4.00939 2.18446C5.83439 0.956458 7.99064 0.268958 10.6969 0.0451583C11.8906 -0.0485917 13.6781 0.0326583 14.7156 0.221158C16.5219 0.575408 18.0844 1.30671 19.1594 2.29421C19.4781 2.59421 19.9719 3.22571 19.9719 3.29421C19.9719 3.31546 19.0031 4.01546 18.8031 4.14671C18.7844 4.15296 18.3594 3.71796 17.9188 3.90759Z"/></svg>
                    <div class="text-right">
                        <span class="text-xs text-gray-400 block font-light">GET IT ON</span>
                        <span class="font-bold text-lg leading-none">Google Play</span>
                    </div>
                </a>

                <button disabled class="flex items-center gap-3 px-6 py-3 bg-gray-100 text-gray-400 rounded-xl cursor-not-allowed border border-gray-200">
                    <svg class="w-8 h-8 text-gray-300" viewBox="0 0 24 24" fill="currentColor"><path d="M18.71 19.5C17.88 20.74 17 21.95 15.66 21.97C14.32 22 13.89 21.18 12.37 21.18C10.84 21.18 10.37 21.95 9.09997 22C7.78997 22.05 6.79997 20.68 5.95997 19.47C4.24997 17 2.93997 12.45 4.69997 9.39C5.56997 7.87 7.12997 6.91 8.81997 6.88C10.1 6.86 11.32 7.75 12.11 7.75C12.89 7.75 14.37 6.68 15.92 6.84C16.57 6.87 18.39 7.1 19.56 8.82C19.47 8.88 17.39 10.1 17.41 12.63C17.44 15.65 20.06 16.66 20.09 16.67C20.06 16.74 19.67 18.11 18.71 19.5ZM13 3.5C13.73 2.67 14.94 2.04 15.94 2C16.07 3.17 15.6 4.35 14.9 5.19C14.21 6.04 13.07 6.7 11.95 6.61C11.8 5.46 12.36 4.26 13 3.5Z"/></svg>
                    <div class="text-right">
                        <span class="text-xs text-gray-400 block font-light">Coming Soon</span>
                        <span class="font-bold text-lg leading-none">App Store</span>
                    </div>
                </button>
            </div>
            
            <div class="mt-8 pt-8 border-t border-gray-100 grid grid-cols-1 md:grid-cols-3 gap-6 text-sm text-gray-500">
                <div class="flex flex-col items-center">
                    <span class="font-semibold text-gray-900 text-lg">v1.2.0</span>
                    <span>الإصدار الحالي</span>
                </div>
                <div class="flex flex-col items-center">
                    <span class="font-semibold text-gray-900 text-lg text-emerald-600">Stable</span>
                    <span>حالة النظام</span>
                </div>
                <div class="flex flex-col items-center">
                    <span class="font-semibold text-gray-900 text-lg">15MB</span>
                    <span>حجم التنزيل</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
