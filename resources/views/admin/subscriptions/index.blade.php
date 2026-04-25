@extends('layouts.admin')

@section('title', 'حوكمة الاشتراكات')

@section('content')
<div class="space-y-10 pb-20 animate-in fade-in slide-in-from-bottom-4 duration-700" dir="rtl">
    
    <!-- Strategic Header -->
    <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-8">
        <div>
            <div class="flex items-center gap-3 mb-3">
                <span class="w-12 h-1 bg-admin rounded-full"></span>
                <p class="text-[10px] font-black text-admin uppercase tracking-[0.3em] font-inter">Global Subscription Governance</p>
            </div>
            <h1 class="text-4xl font-black text-slate-900 tracking-tighter italic">إدارة <span class="text-admin">الاشتراكات</span> والفواتير</h1>
            <p class="text-slate-400 font-bold mt-2 uppercase tracking-widest text-[11px] opacity-80 italic">مراقبة التدفقات المالية، تفعيل الامتيازات، وحوكمة سداد الشركاء</p>
        </div>
    </div>

    <!-- Analytical Precision Filters -->
    <div class="glass-card p-8 bg-white/60 border-white/60">
        <form action="{{ route('admin.subscriptions.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="space-y-3">
                <label class="text-[10px] font-black text-admin uppercase tracking-[0.3em] flex items-center gap-2 mr-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                    محرك التدقيق
                </label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="رقم الفاتورة أو الشركة..." class="w-full bg-white/50 border-white/60 rounded-2xl px-5 py-3.5 text-sm font-bold focus:ring-4 focus:ring-admin/5 transition-all">
            </div>

            <div class="space-y-3">
                <label class="text-[10px] font-black text-admin uppercase tracking-[0.3em] flex items-center gap-2 mr-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                    بروتوكول الحالة
                </label>
                <select name="status" class="w-full bg-white/50 border-white/60 rounded-2xl px-5 py-3.5 text-sm font-bold focus:ring-4 focus:ring-admin/5 transition-all appearance-none cursor-pointer">
                    <option value="all">كل الحالات</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>مدفوع (Paid)</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>قيد الانتظار (Pending)</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>مرفوض (Rejected)</option>
                </select>
            </div>

            <div class="space-y-3">
                <label class="text-[10px] font-black text-admin uppercase tracking-[0.3em] flex items-center gap-2 mr-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                    منصة الدفع
                </label>
                <select name="payment_method" class="w-full bg-white/50 border-white/60 rounded-2xl px-5 py-3.5 text-sm font-bold focus:ring-4 focus:ring-admin/5 transition-all appearance-none cursor-pointer">
                    <option value="all">كل طرق الدفع</option>
                    @foreach($gateways as $key => $label)
                        <option value="{{ $key }}" {{ request('payment_method') == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end gap-3">
                <button type="submit" class="flex-1 h-14 bg-slate-900 text-white rounded-2xl text-[11px] font-black uppercase tracking-widest hover:bg-admin transition-all shadow-lg active:scale-95">تطبيق الفلتر</button>
                <a href="{{ route('admin.subscriptions.index') }}" class="h-14 px-6 bg-white border border-slate-200 rounded-2xl flex items-center justify-center text-slate-400 hover:text-rose-600 transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                </a>
            </div>
        </form>
    </div>

    <!-- Fiscal Ledger Matrix -->
    <div class="glass-card overflow-hidden bg-white/40 border-white/60">
        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse">
                <thead>
                    <tr class="bg-slate-900 text-white/40 text-[10px] font-black uppercase tracking-[0.3em] italic font-inter">
                        <th class="px-10 py-8">هوية السند (Invoice ID)</th>
                        <th class="px-8 py-8">الكيان المستفيد</th>
                        <th class="px-8 py-8 text-center">القيمة الاستحقاقية</th>
                        <th class="px-8 py-8 text-center">بروتوكول السداد</th>
                        <th class="px-8 py-8 text-center">وضعية التسوية</th>
                        <th class="px-10 py-8"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/20">
                    @forelse($invoices as $invoice)
                        <tr class="group hover:bg-white/60 transition-all duration-500">
                            <td class="px-10 py-8">
                                <div class="text-right">
                                    <h4 class="text-lg font-black text-slate-900 italic tracking-tighter group-hover:translate-x-2 transition-transform uppercase font-inter">#{{ $invoice->invoice_number }}</h4>
                                    <div class="flex items-center gap-2 mt-1 opacity-40 justify-end">
                                        <span class="text-[9px] font-black uppercase tracking-widest font-inter">{{ $invoice->created_at->format('Y-m-d H:i') }}</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-8">
                                <div class="flex items-center gap-4 justify-end">
                                    <div class="text-right">
                                        <p class="text-[14px] font-black text-slate-700 leading-none mb-1">{{ $invoice->tenant->name ?? 'محذوف' }}</p>
                                        <p class="text-[9px] font-black text-slate-300 uppercase tracking-widest italic font-inter opacity-60">
                                            @if($invoice->plan_name == 'basic_annual')
                                                الخطة الأساسية (Basic)
                                            @elseif($invoice->plan_name == 'pro_annual')
                                                الخطة الاحترافية (Pro)
                                            @else
                                                {{ $invoice->plan_name }}
                                            @endif
                                        </p>
                                    </div>
                                    <div class="w-10 h-10 rounded-xl bg-slate-900 text-admin flex items-center justify-center shadow-xl">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9h18v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9Z"/><path d="m3 9 2.45-4.9A2 2 0 0 1 7.24 3h9.52a2 2 0 0 1 1.8 1.1L21 9"/><path d="M12 3v6"/></svg>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-8 text-center">
                                <div class="flex flex-col items-center">
                                    <span class="text-2xl font-black text-admin italic font-inter group-hover:scale-110 transition-transform">${{ number_format($invoice->amount, 2) }}</span>
                                    <span class="text-[8px] font-black text-slate-300 uppercase tracking-widest mt-1 font-inter">Global USD Value</span>
                                </div>
                            </td>
                            <td class="px-8 py-8">
                                <div class="flex flex-col items-center gap-2">
                                    <span class="px-4 py-1.5 rounded-xl bg-slate-900 text-white text-[9px] font-black uppercase tracking-widest shadow-lg italic">
                                        @if($invoice->payment_method == 'sham_cash')
                                            Sham Cash
                                        @elseif($invoice->payment_method == 'syriatel_cash')
                                            Syriatel Cash
                                        @elseif($invoice->payment_method == 'turkish_iban')
                                            Turkish IBAN
                                        @else
                                            {{ ucfirst($invoice->payment_method) }}
                                        @endif
                                    </span>
                                    @if($invoice->receipt_image)
                                        <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($invoice->receipt_image) }}" target="_blank" class="text-[8px] font-black text-admin underline uppercase tracking-widest flex items-center gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                                            View Receipt
                                        </a>
                                    @endif
                                </div>
                            </td>
                            <td class="px-8 py-8 text-center">
                                @if($invoice->status == 'paid')
                                    <div class="inline-flex items-center justify-center gap-3 px-5 py-2.5 rounded-xl text-[9px] font-black uppercase tracking-widest border border-emerald-500/20 bg-emerald-500/10 text-emerald-600 shadow-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                        مكتملة
                                    </div>
                                @elseif($invoice->status == 'pending')
                                    <div class="inline-flex items-center justify-center gap-3 px-5 py-2.5 rounded-xl text-[9px] font-black uppercase tracking-widest border border-amber-500/20 bg-amber-500/10 text-amber-600 shadow-sm animate-pulse">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                        بانتظار التدقيق
                                    </div>
                                @else
                                    <div class="inline-flex items-center justify-center gap-3 px-5 py-2.5 rounded-xl text-[9px] font-black uppercase tracking-widest border border-rose-500/20 bg-rose-500/10 text-rose-500 opacity-60">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" x2="9" y1="9" y2="15"/><line x1="9" x2="15" y1="9" y2="15"/></svg>
                                        مرفوضة
                                    </div>
                                @endif
                            </td>
                            <td class="px-10 py-8">
                                @if($invoice->status == 'pending')
                                    <div class="flex items-center justify-end gap-3 opacity-0 group-hover:opacity-100 transition-all translate-x-4 group-hover:translate-x-0">
                                        <form action="{{ route('admin.subscriptions.approve', $invoice->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من قبول الدفع وتفعيل الاشتراك؟')">
                                            @csrf
                                            <button type="submit" class="px-5 py-3 bg-emerald-500 text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg hover:bg-emerald-600 transition-all active:scale-95">قبول وتفعيل</button>
                                        </form>
                                        <form action="{{ route('admin.subscriptions.reject', $invoice->id) }}" method="POST" onsubmit="return confirm('رفض الفاتورة؟')">
                                            @csrf
                                            <button type="submit" class="w-12 h-12 bg-white shadow-xl border border-white/60 rounded-xl flex items-center justify-center text-slate-400 hover:text-rose-600 transition-all active:scale-95">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" x2="9" y1="9" y2="15"/><line x1="9" x2="15" y1="9" y2="15"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                @elseif($invoice->status == 'paid')
                                    <div class="flex justify-end items-center text-emerald-500 opacity-40 italic text-[10px] font-black uppercase tracking-widest gap-2">
                                        Settlement Verified <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-40 flex flex-col items-center gap-8 text-center col-span-full">
                                <div class="w-32 h-32 rounded-[2.5rem] bg-slate-900 text-admin flex items-center justify-center border-8 border-white/20 shadow-2xl relative group">
                                    <div class="absolute inset-0 bg-admin/20 opacity-20 blur-2xl animate-pulse"></div>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="relative z-10"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/><line x1="7" x2="7" y1="15" y2="15"/><line x1="12" x2="12" y1="15" y2="15"/></svg>
                                </div>
                                <div>
                                    <h3 class="text-3xl font-black text-slate-900 italic tracking-tighter uppercase">مجموع السجل صفر (Null Ledger)</h3>
                                    <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.3em] max-w-sm mt-3 opacity-70 italic font-inter">No financial records detected in the current sovereign domain.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Strategic Pagination -->
        <div class="px-10 py-8 bg-slate-900/5 border-t border-white/40 flex justify-between items-center">
            <div class="text-[9px] font-black text-slate-400 uppercase tracking-widest font-inter opacity-40">Fiscal Registry v4.2</div>
            <div class="flex gap-2">
                {{ $invoices->links('vendor.pagination.simple-monolith') }}
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
