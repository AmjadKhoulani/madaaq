@extends('layouts.admin')

@section('title', 'مركز الاستخبارات والتقارير')

@section('content')
<div class="space-y-10 pb-20 animate-in fade-in slide-in-from-bottom-4 duration-700" dir="rtl">
    
    <!-- Strategic Header -->
    <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-8">
        <div>
            <div class="flex items-center gap-3 mb-3">
                <span class="w-12 h-1 bg-admin rounded-full"></span>
                <p class="text-[10px] font-black text-admin uppercase tracking-[0.3em] font-inter">Intelligence & Analytics Center</p>
            </div>
            <h1 class="text-4xl font-black text-slate-900 tracking-tighter italic">مركز <span class="text-admin">الاستخبارات</span> والتقارير</h1>
            <p class="text-slate-400 font-bold mt-2 uppercase tracking-widest text-[11px] opacity-80 italic">تحليل الأداء المالي، نمو قاعدة الشركاء، ورصد المؤشرات الإستراتيجية</p>
        </div>
    </div>

    <!-- Reports Matrix -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        
        <!-- Financial Report -->
        <a href="{{ route('admin.reports.financial') }}" class="glass-card p-10 bg-white/40 group relative overflow-hidden block border-b-8 border-emerald-500/20">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-emerald-500/5 rounded-full blur-2xl group-hover:scale-150 transition-transform"></div>
            <div class="w-14 h-14 bg-emerald-500/10 rounded-2xl flex items-center justify-center text-emerald-600 mb-8 shadow-sm group-hover:rotate-12 transition-transform">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trending-up"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
            </div>
            <h3 class="text-2xl font-black text-slate-900 italic tracking-tighter mb-3 uppercase font-inter">التقارير المالية</h3>
            <p class="text-xs text-slate-400 font-bold leading-relaxed opacity-70">سجل الفواتير، التدفقات النقدية، وتحليل الإيرادات عبر النطاق الزمني.</p>
            <div class="mt-8 flex items-center gap-2 text-emerald-600 text-[10px] font-black uppercase tracking-widest opacity-0 group-hover:opacity-100 transition-all translate-x-4 group-hover:translate-x-0">
                فتح بروتوكول التدقيق <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            </div>
        </a>

        <!-- Tenants Report -->
        <a href="{{ route('admin.tenants.index') }}" class="glass-card p-10 bg-white/40 group relative overflow-hidden block border-b-8 border-admin/20">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-admin/5 rounded-full blur-2xl group-hover:scale-150 transition-transform"></div>
            <div class="w-14 h-14 bg-admin/10 rounded-2xl flex items-center justify-center text-admin mb-8 shadow-sm group-hover:-rotate-12 transition-transform">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-layers"><path d="m12.83 2.18a2 2 0 0 0-1.66 0L2.6 6.08a1 1 0 0 0 0 1.83l8.58 3.91a2 2 0 0 0 1.66 0l8.58-3.9a1 1 0 0 0 0-1.83Z"/><path d="m22 17.65-9.17 4.16a2 2 0 0 1-1.66 0L2 17.65"/><path d="m22 12.65-9.17 4.16a2 2 0 0 1-1.66 0L2 12.65"/></svg>
            </div>
            <h3 class="text-2xl font-black text-slate-900 italic tracking-tighter mb-3 uppercase font-inter">تقارير الشركات</h3>
            <p class="text-xs text-slate-400 font-bold leading-relaxed opacity-70">قاعدة بيانات الشركاء، حالات الاشتراك، وتحليل نمو القطاعات.</p>
            <div class="mt-8 flex items-center gap-2 text-admin text-[10px] font-black uppercase tracking-widest opacity-0 group-hover:opacity-100 transition-all translate-x-4 group-hover:translate-x-0">
                فتح مصفوفة البيانات <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            </div>
        </a>

        <!-- Clients Report -->
        <a href="{{ route('crm.clients.index') }}" class="glass-card p-10 bg-white/40 group relative overflow-hidden block border-b-8 border-cyan-500/20">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-cyan-500/5 rounded-full blur-2xl group-hover:scale-150 transition-transform"></div>
            <div class="w-14 h-14 bg-cyan-500/10 rounded-2xl flex items-center justify-center text-cyan-600 mb-8 shadow-sm group-hover:scale-110 transition-transform">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </div>
            <h3 class="text-2xl font-black text-slate-900 italic tracking-tighter mb-3 uppercase font-inter">المستخدمين النهائيين</h3>
            <p class="text-xs text-slate-400 font-bold leading-relaxed opacity-70">تحليلات المشتركين (SSID)، رصد النشاط، وحوكمة الهويات الرقمية.</p>
            <div class="mt-8 flex items-center gap-2 text-cyan-600 text-[10px] font-black uppercase tracking-widest opacity-0 group-hover:opacity-100 transition-all translate-x-4 group-hover:translate-x-0">
                فتح سجل الهوية <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            </div>
        </a>

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
