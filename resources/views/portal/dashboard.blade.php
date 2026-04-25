@extends('layouts.portal')

@section('title', 'مركز العمليات الشخصي')

@section('content')
<div class="space-y-10 pb-20 animate-in fade-in slide-in-from-bottom-4 duration-700" dir="rtl">
    
    <!-- Hero Status Command -->
    <div class="glass-card p-10 bg-slate-900 text-white relative overflow-hidden flex flex-col lg:flex-row items-center justify-between gap-10 border-none">
        <div class="absolute inset-0 bg-gradient-to-br from-portal/20 to-transparent pointer-events-none"></div>
        <div class="absolute -right-20 -top-20 w-80 h-80 bg-portal/10 rounded-full blur-[100px] pointer-events-none"></div>
        
        <div class="relative z-10 flex items-center gap-8 text-right w-full lg:w-auto">
            <div class="w-20 h-20 bg-white/10 backdrop-blur-xl rounded-[2rem] flex items-center justify-center border border-white/10 text-portal shadow-2xl group transition-transform hover:rotate-6">
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-zap fill-portal animate-pulse"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
            </div>
            <div>
                <h1 class="text-3xl font-black mb-3 italic tracking-tighter">أهلاً بك، {{ $client->name ?? $client->username }}</h1>
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-2 px-4 py-1.5 bg-white/5 rounded-xl border border-white/10 text-[10px] font-black uppercase tracking-widest text-portal-light">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                        تقنية الاتصال: 
                        @if(($client->package->technology_type ?? 'wireless') === 'fiber')
                            Fiber Optic
                        @else
                            Wireless Link
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="relative z-10 flex flex-col items-center lg:items-start gap-4">
            <p class="text-[10px] font-black uppercase tracking-[0.4em] text-white/40 font-inter italic">Instant Connectivity Status</p>
            <div class="inline-flex items-center gap-4 px-8 py-4 rounded-2xl {{ $usage['status'] === 'online' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 shadow-emerald-500/10' : 'bg-rose-500/10 text-rose-400 border border-rose-500/20 shadow-rose-500/10' }} transition-all">
                <span class="relative flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-current opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-current"></span>
                </span>
                <span class="font-black text-sm uppercase tracking-widest">{{ $usage['status'] === 'online' ? 'نشط ومتصل (Live)' : 'غير متصل (Offline)' }}</span>
            </div>
        </div>
    </div>

    <!-- Telemetry Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        
        <!-- Speed Pulse -->
        <div class="glass-card p-8 bg-white/40 group relative overflow-hidden border-b-4 border-portal/20">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-portal/5 rounded-full blur-2xl group-hover:scale-150 transition-transform"></div>
            <div class="flex justify-between items-start mb-8">
                <div class="w-12 h-12 bg-portal/10 rounded-2xl flex items-center justify-center text-portal shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m16 10-4 4-4-4"/></svg>
                </div>
                <div class="text-[9px] font-black text-slate-400 uppercase tracking-[0.3em] font-inter">Speed Allocation</div>
            </div>
            <div class="flex items-baseline gap-2">
                <h3 class="text-4xl font-black text-slate-900 italic tracking-tighter font-inter">{{ $usage['download_speed'] }}</h3>
                <span class="text-[10px] font-black text-portal uppercase tracking-widest font-inter">Mbps DL</span>
            </div>
            <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.4em] mt-4 italic flex items-center gap-2">
                 @if($usage['status'] === 'online') 
                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                    مدة الاتصال: {{ $usage['uptime'] }}
                 @else 
                    <span class="w-1.5 h-1.5 bg-slate-300 rounded-full"></span>
                    النظام جاهز للربط الفوري 
                 @endif
            </p>
        </div>

        <!-- Expiry Matrix -->
        <div class="glass-card p-8 bg-white/40 group relative overflow-hidden border-b-4 border-amber-500/20">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-amber-500/5 rounded-full blur-2xl group-hover:scale-150 transition-transform"></div>
            <div class="flex justify-between items-start mb-8">
                <div class="w-12 h-12 bg-amber-500/10 rounded-2xl flex items-center justify-center text-amber-600 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                </div>
                <div class="text-[9px] font-black text-slate-400 uppercase tracking-[0.3em] font-inter">Cycle Horizon</div>
            </div>
            <div class="flex items-baseline gap-2">
                <h3 class="text-4xl font-black text-slate-900 italic tracking-tighter font-inter">{{ $client->expires_at ? $client->expires_at->format('d/m') : '--/--' }}</h3>
                <span class="text-[10px] font-black text-amber-600 uppercase tracking-widest font-inter">Expiry</span>
            </div>
            <p class="text-[9px] font-black text-rose-500 uppercase tracking-[0.4em] mt-4 italic flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M12 9v4"/><path d="M12 17h.01"/><path d="m4.93 4.93 14.14 14.14"/><path d="M21 3 3 21"/></svg>
                @if($client->expires_at) 
                    ينتهي الصلاحية خلال {{ $client->expires_at->diffInDays() }} يوم
                @else 
                    دورة اشتراك مفتوحة 
                @endif
            </p>
        </div>

        <!-- Financial Node -->
        <div class="glass-card p-8 bg-white/40 group relative overflow-hidden border-b-4 border-emerald-500/20">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-emerald-500/5 rounded-full blur-2xl group-hover:scale-150 transition-transform"></div>
            <div class="flex justify-between items-start mb-8">
                <div class="w-12 h-12 bg-emerald-500/10 rounded-2xl flex items-center justify-center text-emerald-600 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                </div>
                <div class="text-[9px] font-black text-slate-400 uppercase tracking-[0.3em] font-inter">Renewal Value</div>
            </div>
            <div class="flex items-baseline gap-2">
                <h3 class="text-4xl font-black text-slate-900 italic tracking-tighter font-inter">{{ number_format($client->price ?? $client->package->price ?? 0) }}</h3>
                <span class="text-[10px] font-black text-emerald-600 uppercase tracking-widest font-inter">SAR</span>
            </div>
            <p class="text-[9px] font-black text-portal uppercase tracking-[0.4em] mt-4 italic font-inter">
                PACKAGE: {{ $client->package->name ?? 'DEFAULT_PROFILE' }}
            </p>
        </div>

    </div>

    <!-- Analytical Insights -->
    <div class="grid grid-cols-12 gap-8">
        
        <!-- Usage Matrix -->
        <div class="col-span-12 glass-card p-10 bg-white/40 overflow-hidden">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-12 gap-6">
                <div>
                    <h3 class="text-2xl font-black text-slate-900 italic tracking-tighter uppercase font-inter">تحليل مصفوفة الاستهلاك</h3>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mt-1 italic">Weekly Traffic Intelligence Analysis</p>
                </div>
                <div class="flex gap-4">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 bg-portal rounded-full"></span>
                        <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest font-inter">Traffic</span>
                    </div>
                    <div class="flex items-center gap-2 opacity-30">
                        <span class="w-3 h-3 bg-slate-300 rounded-full"></span>
                        <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest font-inter">Limit</span>
                    </div>
                </div>
            </div>

            <div class="h-64 flex items-end justify-between px-4 gap-4 lg:gap-8">
                @foreach([40, 65, 30, 85, 50, 75, 60] as $h)
                    <div class="flex-1 bg-slate-100 rounded-2xl relative group overflow-hidden h-full">
                        <div style="height: {{ $h }}%" class="bg-portal rounded-2xl absolute bottom-0 w-full transition-all duration-700 group-hover:scale-110 group-hover:brightness-110 shadow-lg shadow-portal/20">
                             <div class="absolute inset-0 bg-gradient-to-t from-black/10 to-transparent"></div>
                        </div>
                        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                            <span class="text-[11px] font-black text-white font-inter tracking-tighter drop-shadow-md">{{ $h }}%</span>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="grid grid-cols-7 text-center text-[9px] font-black text-slate-400 uppercase tracking-[0.3em] mt-8 font-inter">
                <span>SAT</span><span>SUN</span><span>MON</span><span>TUE</span><span>WED</span><span>THU</span><span>FRI</span>
            </div>
        </div>

        <!-- Tactical Command Units -->
        <div class="col-span-12 lg:col-span-6">
            @php $waNumber = \App\Models\Setting::getValue('whatsapp_regular_number', '963999000000'); @endphp
            <a href="https://wa.me/{{ $waNumber }}?text=مرحباً، أرغب بتجديد اشتراكي رقم {{ $client->id }}" target="_blank" class="glass-card p-8 bg-white/40 group flex items-center justify-between border-portal/10 hover:border-portal transition-all duration-500">
                <div class="flex items-center gap-6">
                    <div class="w-16 h-16 bg-portal text-white rounded-[1.5rem] flex items-center justify-center shadow-radiant group-hover:scale-110 group-hover:rotate-6 transition-all duration-500">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-credit-card"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                    </div>
                    <div>
                        <h4 class="text-xl font-black text-slate-900 italic tracking-tighter uppercase font-inter leading-none mb-2">تجديد الاشتراك</h4>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic leading-tight">Instant Renewal via WhatsApp Gateway</p>
                    </div>
                </div>
                <div class="w-10 h-10 rounded-full border border-portal/20 flex items-center justify-center text-portal group-hover:bg-portal group-hover:text-white transition-all duration-500">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-left"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
                </div>
            </a>
        </div>

        <div class="col-span-12 lg:col-span-6">
            <div class="glass-card p-8 bg-white/40 group flex items-center justify-between border-rose-500/10 hover:border-rose-500 transition-all duration-500 cursor-pointer">
                <div class="flex items-center gap-6">
                    <div class="w-16 h-16 bg-rose-500 text-white rounded-[1.5rem] flex items-center justify-center shadow-lg group-hover:scale-110 group-hover:-rotate-6 transition-all duration-500 shadow-rose-500/20">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-life-buoy"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="4"/><line x1="4.93" x2="9.17" y1="4.93" y2="9.17"/><line x1="14.83" x2="19.07" y1="14.83" y2="19.07"/><line x1="14.83" x2="19.07" y1="9.17" y2="4.93"/><line x1="4.93" x2="9.17" y1="19.07" y2="14.83"/></svg>
                    </div>
                    <div>
                        <h4 class="text-xl font-black text-slate-900 italic tracking-tighter uppercase font-inter leading-none mb-2">الدعم الفني</h4>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic leading-tight">Engineering Maintenance Ticket Protocol</p>
                    </div>
                </div>
                <div class="w-10 h-10 rounded-full border border-rose-500/20 flex items-center justify-center text-rose-500 group-hover:bg-rose-500 group-hover:text-white transition-all duration-500">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-left"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
.glass-card {
    @apply border border-white/40 shadow-glass rounded-[2.5rem] transition-all duration-500;
}
.glass-card:hover {
    @apply border-white/60 shadow-radiant -translate-y-1;
}
</style>
@endsection
