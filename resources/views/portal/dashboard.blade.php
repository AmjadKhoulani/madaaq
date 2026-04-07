@extends('layouts.portal')

@section('content')
<div class="max-w-5xl mx-auto space-y-8">
    <!-- Header Card -->
    <div class="bg-gradient-to-r from-blue-900 to-indigo-900 rounded-3xl p-8 text-white shadow-xl relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-full opacity-10">
            <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                <path d="M0 100 C 20 0 50 0 100 100 Z" fill="white" />
            </svg>
        </div>
        <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex items-center gap-6">
                <div class="w-20 h-20 bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center border border-white/20">
                    <span class="text-3xl">👋</span>
                </div>
                <div>
                    <h1 class="text-3xl font-bold mb-1">مرحباً، {{ $client->name ?? $client->username }}</h1>
                    <div class="flex items-center gap-2 text-blue-200">
                        <span class="text-xs uppercase font-bold tracking-wider opacity-75">نوع الخدمة:</span>
                        @if(($client->package->technology_type ?? 'wireless') === 'fiber')
                            <span class="flex items-center gap-1 text-xs font-bold bg-white/20 px-2 py-0.5 rounded">🌐 FIBER (FTTH)</span>
                        @elseif(($client->package->technology_type ?? 'wireless') === 'wireless')
                            <span class="flex items-center gap-1 text-xs font-bold bg-white/20 px-2 py-0.5 rounded">📡 WIRELESS (WISP)</span>
                        @elseif(($client->package->technology_type ?? 'wireless') === 'dsl')
                            <span class="flex items-center gap-1 text-xs font-bold bg-white/20 px-2 py-0.5 rounded">🔌 DSL / COPPER</span>
                        @else
                            <span class="flex items-center gap-1 text-xs font-bold bg-white/20 px-2 py-0.5 rounded">📡 WIRELESS</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="text-center md:text-left">
                <div class="text-sm text-blue-200 mb-1">الحالة</div>
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full {{ $usage['status'] === 'online' ? 'bg-green-500/20 text-green-300 border border-green-500/30' : 'bg-red-500/20 text-red-300 border border-red-500/30' }}">
                    <span class="w-2 h-2 rounded-full {{ $usage['status'] === 'online' ? 'bg-green-400 animate-pulse' : 'bg-red-400' }}"></span>
                    <span class="font-bold">{{ $usage['status'] === 'online' ? 'متصل' : 'غير متصل' }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Package -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center gap-4">

            <div>
                <p class="text-gray-500 text-xs font-semibold uppercase">السرعة الحالية</p>
                <div class="flex items-center gap-2">
                    <span class="text-xl font-bold text-gray-900" title="تحميل">{{ $usage['download_speed'] }} <small class="text-xs text-gray-400">Mbps</small></span>
                    <span class="text-xs text-gray-300">|</span>
                    <span class="text-lg font-bold text-gray-700" title="رفع">{{ $usage['upload_speed'] }} <small class="text-xs text-gray-400">Mbps</small></span>
                </div>
                <p class="text-xs text-purple-600 mt-1">
                    @if($usage['status'] === 'online')
                    مدة الاتصال: {{ $usage['uptime'] }}
                    @else
                    غير متصل حالياً
                    @endif
                </p>
            </div>
        </div>

        <!-- Expiry -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center gap-4">

            <div>
                <p class="text-gray-500 text-xs font-semibold uppercase">نهاية الاشتراك</p>
                <h3 class="text-xl font-bold text-gray-900 font-mono">
                    {{ $client->expires_at ? $client->expires_at->format('Y-m-d') : '--' }}
                </h3>
                <p class="text-xs text-orange-600 mt-1">
                    @if($client->expires_at)
                    سيتوقف بعد {{ $client->expires_at->diffInDays() }} يوم
                    @else
                    تاريخ غير محدد
                    @endif
                </p>
            </div>
        </div>

        <!-- Balance -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center gap-4">

            <div>
                <p class="text-gray-500 text-xs font-semibold uppercase">سعر التجديد</p>
                <h3 class="text-xl font-bold text-gray-900 font-mono">
                    @money($client->price ?? $client->package->price ?? 0)
                </h3>
                <p class="text-xs text-green-600 mt-1">باقة {{ $client->package->name ?? '---' }}</p>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Usage Chart (Mock) -->
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100">
            <h3 class="font-bold text-gray-900 mb-6">استهلاك البيانات (تجريبي)</h3>
            <div class="h-48 flex items-end justify-between px-2 gap-2">
                @foreach([40, 65, 30, 85, 50, 75, 60] as $h)
                <div class="w-full bg-blue-100 rounded-t-lg relative group">
                    <div style="height: {{ $h }}%" class="bg-blue-500 rounded-t-lg absolute bottom-0 w-full transition-all group-hover:bg-blue-600"></div>
                </div>
                @endforeach
            </div>
            <div class="flex justify-between text-xs text-gray-400 mt-2 font-mono">
                <span>السبت</span><span>الأحد</span><span>الاثنين</span><span>الثلاثاء</span><span>الأربعاء</span><span>الخميس</span><span>الجمعة</span>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="space-y-4">
            <h3 class="font-bold text-gray-900 mb-2">إجراءات سريعة</h3>
            
            @php
                $waNumber = \App\Models\Setting::getValue('whatsapp_regular_number', '963999000000');
            @endphp
            <a href="https://wa.me/{{ $waNumber }}?text=مرحباً، أرغب بتجديد اشتراكي رقم {{ $client->id }}" target="_blank" class="flex items-center gap-4 p-5 bg-green-50 rounded-2xl border border-green-100 hover:bg-green-100 transition group">
                <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center shadow-lg group-hover:scale-110 transition">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                </div>
                <div>
                    <h4 class="font-bold text-gray-900">تجديد الاشتراك</h4>
                    <p class="text-xs text-green-700">تواصل عبر واتساب لطلب التجديد</p>
                </div>
            </a>

            <div class="flex items-center gap-4 p-5 bg-white border border-gray-200 rounded-2xl hover:border-gray-300 transition cursor-pointer">
                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center text-gray-500">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636a9 9 0 010 12.728m0 0l-2.829-2.829m2.829 2.829L21 21M15.536 8.464a5 5 0 010 7.072m0 0l-2.829-2.829m-4.243 2.829a4.978 4.978 0 01-1.414-2.83m-1.414 5.658a9 9 0 01-2.167-9.238m7.824 2.167a1 1 0 111.414 1.414m-1.414-1.414L3 3m8.293 8.293l1.414 1.414"/></svg>
                </div>
                <div>
                    <h4 class="font-bold text-gray-900">الإبلاغ عن عطل</h4>
                    <p class="text-xs text-gray-500">لديك مشكلة في الاتصال؟</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
