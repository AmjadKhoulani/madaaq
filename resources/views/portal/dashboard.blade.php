@extends('layouts.portal')

@section('content')
<div class="max-w-6xl mx-auto space-y-10">
    
    <!-- Hero Command Card -->
    <div class="bg-primary p-10 rounded-lg text-white shadow-2xl relative overflow-hidden flex flex-col md:flex-row items-center justify-between gap-8">
        <div class="absolute inset-0 opacity-10 pointer-events-none">
            <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                <path d="M0 100 C 30 0 70 0 100 100 Z" fill="white" />
            </svg>
        </div>
        
        <div class="relative z-10 flex items-center gap-8 text-right">
            <div class="w-20 h-20 bg-white/20 backdrop-blur-lg rounded-xl flex items-center justify-center border border-white/20 text-3xl">
                🚀
            </div>
            <div>
                <h1 class="text-3xl font-black mb-2 italic">مرحباً، {{ $client->name ?? $client->username }}</h1>
                <div class="flex items-center gap-3">
                    <span class="text-[10px] uppercase font-black tracking-widest text-primary-fixed opacity-80 font-headline">تقنية الاتصال:</span>
                    <span class="px-3 py-1 bg-white/10 rounded text-[11px] font-black tracking-widest uppercase">
                        @if(($client->package->technology_type ?? 'wireless') === 'fiber')
                            🌐 Fiber Optic
                        @else
                            📡 Wireless Link
                        @endif
                    </span>
                </div>
            </div>
        </div>

        <div class="relative z-10 text-center md:text-left">
            <p class="text-[10px] font-black uppercase tracking-widest text-primary-fixed mb-2 font-headline">حالة الاتصال الفوري</p>
            <div class="inline-flex items-center gap-3 px-6 py-3 rounded-lg {{ $usage['status'] === 'online' ? 'bg-secondary-container text-on-secondary-container shadow-[0_0_20px_rgba(0,108,74,0.3)]' : 'bg-error-container text-on-error-container' }} transition-all">
                <span class="w-2.5 h-2.5 rounded-full {{ $usage['status'] === 'online' ? 'bg-secondary animate-pulse' : 'bg-error' }}"></span>
                <span class="font-black text-sm">{{ $usage['status'] === 'online' ? 'نشط ومتصل' : 'غير متصل حالياً' }}</span>
            </div>
        </div>
    </div>

    <!-- Quick Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Speed Command -->
        <div class="bg-surface-container-lowest p-8 rounded-lg shadow-sm border border-outline-variant/10">
            <div class="flex items-center justify-between mb-4">
                <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest font-headline">السرعة المخصصة</p>
                <span class="material-symbols-outlined text-primary text-xl">speed</span>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-4xl font-manrope font-black text-primary">{{ $usage['download_speed'] }}</span>
                <span class="text-xs font-bold text-slate-400 uppercase font-headline">Mbps DL</span>
            </div>
            <p class="text-[10px] font-bold text-secondary mt-3 flex items-center gap-1 uppercase tracking-tighter">
                <span class="material-symbols-outlined text-[14px]">timer</span>
                @if($usage['status'] === 'online') مدة الاتصال: {{ $usage['uptime'] }} @else النظام جاهز للربط @endif
            </p>
        </div>

        <!-- Billing Cycle -->
        <div class="bg-surface-container-lowest p-8 rounded-lg shadow-sm border border-outline-variant/10">
            <div class="flex items-center justify-between mb-4">
                <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest font-headline">انتهاء الدورة</p>
                <span class="material-symbols-outlined text-primary text-xl">calendar_today</span>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-4xl font-manrope font-black text-primary">{{ $client->expires_at ? $client->expires_at->format('d/m') : '--' }}</span>
                <span class="text-xs font-bold text-slate-400 font-headline">CYCLE END</span>
            </div>
            <p class="text-[10px] font-bold text-error mt-3 flex items-center gap-1 uppercase tracking-tighter">
                <span class="material-symbols-outlined text-[14px]">event_busy</span>
                @if($client->expires_at) سيتوقف بعد {{ $client->expires_at->diffInDays() }} يوم @else تاريخ مفتوح @endif
            </p>
        </div>

        <!-- Package Details -->
        <div class="bg-surface-container-lowest p-8 rounded-lg shadow-sm border border-outline-variant/10">
            <div class="flex items-center justify-between mb-4">
                <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest font-headline">تكلفة التجديد</p>
                <span class="material-symbols-outlined text-primary text-xl">account_balance_wallet</span>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-4xl font-manrope font-black text-primary">{{ number_format($client->price ?? $client->package->price ?? 0) }}</span>
                <span class="text-xs font-bold text-slate-400 font-headline">SAR</span>
            </div>
            <p class="text-[10px] font-bold text-primary mt-3 flex items-center gap-1 uppercase tracking-tighter">
                <span class="material-symbols-outlined text-[14px]">inventory_2</span>
                باقة {{ $client->package->name ?? '---' }}
            </p>
        </div>
    </div>

    <!-- Data Visualization Area -->
    <div class="grid grid-cols-12 gap-8">
        
        <!-- Usage History (Simulated) -->
        <div class="col-span-12 lg:col-span-12 bg-surface-container-lowest p-8 rounded-lg shadow-sm border border-outline-variant/10">
            <div class="flex justify-between items-center mb-10">
                <div>
                    <h3 class="text-xl font-black text-primary">تحليل الاستهلاك الأسبوعي</h3>
                    <p class="text-xs text-slate-400 font-medium">مراقبة حجم البيانات المنقولة في الوقت الفعلي</p>
                </div>
            </div>
            <div class="h-64 flex items-end justify-between px-4 gap-2">
                @foreach([40, 65, 30, 85, 50, 75, 60] as $h)
                    <div class="flex-1 bg-primary/10 rounded-t relative group overflow-hidden">
                        <div style="height: {{ $h }}%" class="bg-primary/30 rounded-t absolute bottom-0 w-full transition-all group-hover:bg-primary/50"></div>
                        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                            <span class="text-[9px] font-black text-primary font-manrope">{{ $h }}%</span>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="grid grid-cols-7 text-center text-[10px] text-slate-400 font-black uppercase tracking-widest mt-6 font-headline">
                <span>السبت</span><span>الأحد</span><span>الاينين</span><span>الثلاثاء</span><span>الأربعاء</span><span>الخميس</span><span>الجمعة</span>
            </div>
        </div>

        <!-- Command Buttons -->
        <div class="col-span-12 lg:col-span-6 space-y-4">
             @php
                $waNumber = \App\Models\Setting::getValue('whatsapp_regular_number', '963999000000');
            @endphp
            <a href="https://wa.me/{{ $waNumber }}?text=مرحباً، أرغب بتجديد اشتراكي رقم {{ $client->id }}" target="_blank" class="flex items-center justify-between p-6 bg-surface-container-low border border-outline-variant/10 rounded-lg hover:bg-surface-container-high transition-all group">
                <div class="flex items-center gap-5">
                    <div class="w-14 h-14 bg-secondary text-white rounded-lg flex items-center justify-center shadow-lg group-hover:scale-105 transition-transform">
                        <span class="material-symbols-outlined text-2xl" style="font-variation-settings: 'FILL' 1;">sms</span>
                    </div>
                    <div>
                        <h4 class="text-lg font-black text-primary">طلب تجديد فوري</h4>
                        <p class="text-xs text-slate-500 font-medium">تواصل مباشر مع قسم المالية عبر واتساب</p>
                    </div>
                </div>
                <span class="material-symbols-outlined text-primary opacity-0 group-hover:opacity-100 transition-opacity">arrow_back</span>
            </a>
        </div>

        <div class="col-span-12 lg:col-span-6 space-y-4">
             <div class="flex items-center justify-between p-6 bg-surface-container-low border border-outline-variant/10 rounded-lg hover:border-error/20 transition-all group cursor-pointer">
                <div class="flex items-center gap-5">
                    <div class="w-14 h-14 bg-white border border-outline-variant/10 text-error rounded-lg flex items-center justify-center shadow-sm group-hover:bg-error-container/20">
                        <span class="material-symbols-outlined text-2xl">report</span>
                    </div>
                    <div>
                        <h4 class="text-lg font-black text-primary">الإبلاغ عن خلل فني</h4>
                        <p class="text-xs text-slate-500 font-medium">تفتيح تذكرة صيانة لدى الفريق الهندسي</p>
                    </div>
                </div>
                <span class="material-symbols-outlined text-primary opacity-0 group-hover:opacity-100 transition-opacity">arrow_back</span>
            </div>
        </div>
    </div>
</div>
@endsection
