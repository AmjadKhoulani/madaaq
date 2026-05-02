@extends('layouts.portal')

@section('title', 'سجل الفواتير السيادي')

@section('content')
<div class="space-y-10 pb-20 animate-in fade-in slide-in-from-bottom-4 duration-700" dir="rtl">
    
    <!-- Strategic Header -->
    <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-8">
        <div>
            <div class="flex items-center gap-3 mb-3">
                <span class="w-12 h-1 bg-portal rounded-full"></span>
                <p class="text-[10px] font-black text-portal uppercase tracking-[0.3em] font-inter">Personal Fiscal Ledger</p>
            </div>
            <h1 class="text-4xl font-black text-slate-900 tracking-tighter italic">سجل <span class="text-portal">الفواتير</span> والمدفوعات</h1>
            <p class="text-slate-400 font-bold mt-2 uppercase tracking-widest text-[11px] opacity-80 italic">ملخص العمليات المالية وتدقيق سجلات السداد السيادية</p>
        </div>
    </div>

    <!-- Fiscal Matrix Ledger -->
    <div class="glass-card overflow-hidden bg-white/40 border-white/60">
        @if($invoices->isEmpty())
            <div class="py-40 flex flex-col items-center gap-8 text-center">
                <div class="w-32 h-32 rounded-[2.5rem] bg-slate-900 text-portal flex items-center justify-center border-8 border-white/20 shadow-2xl relative group">
                    <div class="absolute inset-0 bg-portal/20 opacity-20 blur-2xl animate-pulse"></div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="relative z-10"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" x2="8" y1="13" y2="13"/><line x1="16" x2="8" y1="17" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                </div>
                <div>
                    <h3 class="text-3xl font-black text-slate-900 italic tracking-tighter uppercase">مجموع السجل صفر (Null Ledger)</h3>
                    <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.3em] max-w-sm mt-3 opacity-70 italic font-inter">No financial records detected in your personal domain.</p>
                </div>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-right border-collapse">
                    <thead>
                        <tr class="bg-slate-900 text-white/40 text-[10px] font-black uppercase tracking-[0.3em] italic font-inter">
                            <th class="px-10 py-8">هوية السند (Invoice ID)</th>
                            <th class="px-8 py-8">أفق الإصدار</th>
                            <th class="px-8 py-8 text-center">القيمة الاستحقاقية</th>
                            <th class="px-8 py-8 text-center">وضعية التسوية</th>
                            <th class="px-10 py-8"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/20">
                        @foreach($invoices as $invoice)
                        <tr class="group hover:bg-white/60 transition-all duration-500">
                            <td class="px-10 py-8">
                                <div class="flex items-center gap-5 justify-end">
                                    <div class="text-right">
                                        <h4 class="text-lg font-black text-slate-900 italic tracking-tighter group-hover:translate-x-2 transition-transform uppercase font-inter">#{{ $invoice->id }}</h4>
                                        <p class="text-[9px] font-black text-slate-300 uppercase tracking-widest italic font-inter opacity-60">{{ $invoice->notes ?? 'SUBSCRIPTION_RENEWAL' }}</p>
                                    </div>
                                    <div class="w-12 h-12 rounded-xl bg-slate-900 text-portal flex items-center justify-center shadow-xl group-hover:scale-110 transition-all">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/></svg>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-8">
                                <div class="flex flex-col items-end">
                                    <span class="text-[13px] font-black text-slate-700 italic font-inter">{{ $invoice->created_at->format('Y-m-d') }}</span>
                                    <span class="text-[8px] font-black text-slate-300 uppercase tracking-widest mt-1 font-inter">Fiscal Timestamp</span>
                                </div>
                            </td>
                            <td class="px-8 py-8 text-center">
                                <div class="flex flex-col items-center">
                                    <span class="text-2xl font-black text-portal italic font-inter group-hover:scale-110 transition-transform">{{ number_format($invoice->amount, 2) }}</span>
                                    <span class="text-[8px] font-black text-slate-300 uppercase tracking-widest mt-1 font-inter">{{ \App\Models\Setting::getValue('currency', 'SAR') }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-8 text-center">
                                @if($invoice->status == 'paid')
                                    <div class="inline-flex items-center justify-center gap-3 px-5 py-2.5 rounded-xl text-[9px] font-black uppercase tracking-widest border border-emerald-500/20 bg-emerald-500/10 text-emerald-600 shadow-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                        مدفوعة (Paid)
                                    </div>
                                @else
                                    <div class="inline-flex items-center justify-center gap-3 px-5 py-2.5 rounded-xl text-[9px] font-black uppercase tracking-widest border border-amber-500/20 bg-amber-500/10 text-amber-600 shadow-sm animate-pulse">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                        قيد الانتظار (Pending)
                                    </div>
                                @endif
                            </td>
                            <td class="px-10 py-8">
                                @if($invoice->status != 'paid')
                                    <a href="{{ route('portal.invoices.pay', ['tenant_domain' => request()->getHost(), 'invoice' => $invoice->id]) }}" class="btn-radiant btn-portal px-6 py-3 text-[10px] font-black uppercase tracking-[0.2em] opacity-0 group-hover:opacity-100 transition-all translate-x-4 group-hover:translate-x-0 inline-flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                                        دفع الآن
                                    </a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-10 py-8 bg-slate-900/5 border-t border-white/40 flex justify-end">
                {{ $invoices->links('vendor.pagination.simple-monolith') }}
            </div>
        @endif
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
