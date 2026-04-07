@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header & Stats -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-12">
        <div class="relative">
            <div class="absolute -top-4 -right-4 w-24 h-24 bg-emerald-500/10 rounded-full blur-2xl -z-10"></div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">الفواتير والتحصيل المالي</h1>
            <p class="text-xs font-bold text-emerald-600 uppercase tracking-widest mt-1 flex items-center gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-600 animate-pulse shadow-[0_0_8px_rgba(16,185,129,0.5)]"></span>
                Financial Ledger & Revenue Hub
            </p>
        </div>
        <a href="{{ route('accounting.invoices.create') }}" class="inline-flex items-center gap-3 px-8 py-3 bg-gray-900 hover:bg-black text-white font-black rounded-2xl shadow-xl hover:shadow-gray-900/20 transition-all transform hover:scale-105 active:scale-95 group">
            <svg class="w-5 h-5 group-hover:rotate-90 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            <span>إنشاء فاتورة جديدة</span>
        </a>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
        <!-- Total Revenue -->
        <div class="glass-panel rounded-3xl p-8 relative overflow-hidden group hover:translate-y-[-4px] transition-all duration-500 hover:shadow-2xl hover:shadow-emerald-500/10">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-emerald-500/10 rounded-full blur-3xl group-hover:bg-emerald-500/20 transition-colors"></div>
            <div class="relative z-10 text-right">
                <p class="text-gray-400 text-[10px] font-black uppercase tracking-widest mb-1">إجمالي الإيرادات</p>
                <p class="text-3xl font-black text-emerald-600 tracking-tighter">@money($stats['total_revenue'])</p>
                <div class="mt-4 flex items-center text-[10px] font-black text-emerald-600 uppercase tracking-widest">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-600 ml-2 animate-pulse"></span>
                    Total Collected
                </div>
            </div>
        </div>
        
        <!-- Unpaid Amount -->
        <div class="glass-panel rounded-3xl p-8 relative overflow-hidden group hover:translate-y-[-4px] transition-all duration-500 hover:shadow-2xl hover:shadow-rose-500/10">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-rose-500/10 rounded-full blur-3xl group-hover:bg-rose-500/20 transition-colors"></div>
            <div class="relative z-10 text-right">
                <p class="text-gray-400 text-[10px] font-black uppercase tracking-widest mb-1">المستحقات المعلقة</p>
                <p class="text-3xl font-black text-rose-600 tracking-tighter">@money($stats['unpaid_amount'])</p>
                <div class="mt-4 flex items-center text-[10px] font-black text-rose-600 uppercase tracking-widest">
                    <span class="w-1.5 h-1.5 rounded-full bg-rose-600 ml-2 animate-pulse"></span>
                    Pending Dues
                </div>
            </div>
        </div>

        <!-- Paid Count -->
        <div class="glass-panel rounded-3xl p-8 relative overflow-hidden group hover:translate-y-[-4px] transition-all duration-500 hover:shadow-2xl hover:shadow-gray-500/10">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-gray-500/5 rounded-full blur-3xl transition-colors"></div>
            <div class="relative z-10 text-right">
                <p class="text-gray-400 text-[10px] font-black uppercase tracking-widest mb-1">فواتير مدفوعة</p>
                <p class="text-3xl font-black text-gray-900 tracking-tighter">{{ number_format($stats['paid_count']) }}</p>
                <p class="mt-4 text-[11px] font-bold text-gray-400 uppercase tracking-tighter">Settled Records</p>
            </div>
        </div>

        <!-- Unpaid Count -->
        <div class="glass-panel rounded-3xl p-8 relative overflow-hidden group hover:translate-y-[-4px] transition-all duration-500 hover:shadow-2xl hover:shadow-indigo-500/10">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-indigo-500/10 rounded-full blur-3xl transition-colors"></div>
            <div class="relative z-10 text-right">
                <p class="text-gray-400 text-[10px] font-black uppercase tracking-widest mb-1">فواتير مستحقة</p>
                <p class="text-3xl font-black text-gray-900 tracking-tighter">{{ number_format($stats['unpaid_count']) }}</p>
                <p class="mt-4 text-[11px] font-bold text-indigo-600 uppercase tracking-tighter">Awaiting Payment</p>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="glass-panel rounded-3xl p-6 mb-12 relative overflow-hidden group">
        <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-indigo-500/5 rounded-full blur-3xl transition-all group-hover:bg-indigo-500/10"></div>
        <form action="{{ route('accounting.invoices.index') }}" method="GET" class="flex flex-col md:flex-row items-center gap-6 relative z-10 w-full">
            <div class="relative flex-1 w-full">
                <select name="status" class="w-full py-3.5 px-6 bg-white/50 backdrop-blur-md border border-white/20 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:bg-white focus:border-indigo-500/30 transition-all font-bold text-gray-700 appearance-none">
                    <option value="all">جميع الحالات - All Statuses</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>مدفوعة - Settled</option>
                    <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>غير مدفوعة - Unpaid</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center px-6 text-gray-400">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                </div>
            </div>
            <button type="submit" class="w-full md:w-auto px-12 py-3.5 bg-gray-900 hover:bg-black text-white font-black rounded-2xl transition-all shadow-xl hover:shadow-gray-900/20 active:scale-95">
                تصفية النتائج
            </button>
        </form>
    </div>

    <!-- Invoices Table -->
    <div class="glass-panel rounded-[2rem] overflow-hidden transition-all duration-500 hover:shadow-2xl hover:shadow-indigo-500/5">
        <div class="overflow-x-auto">
            <table class="w-full text-right">
                <thead>
                    <tr class="bg-white/30 backdrop-blur-lg border-b border-white/20">
                        <th class="px-8 py-5 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">رقم الفاتورة</th>
                        <th class="px-8 py-5 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">العميل</th>
                        <th class="px-8 py-5 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">تاريخ الاستحقاق</th>
                        <th class="px-8 py-5 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">الحالة</th>
                        <th class="px-8 py-5 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">المبلغ</th>
                        <th class="px-8 py-5 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/10">
                    @forelse($invoices as $invoice)
                    <tr class="hover:bg-white/40 transition-all duration-300 group">
                        <td class="px-8 py-6">
                            <span class="font-mono font-black text-xs text-gray-400 tracking-widest group-hover:text-indigo-600 transition-colors">#{{ $invoice->invoice_number }}</span>
                        </td>
                        <td class="px-8 py-6">
                            <div class="font-black text-gray-900 tracking-tight">{{ $invoice->client->name ?? 'عميل محذوف' }}</div>
                            @if(isset($invoice->client->username))
                                <div class="text-[9px] font-black text-gray-400 uppercase tracking-tighter mt-0.5">{{ $invoice->client->username }}</div>
                            @endif
                        </td>
                        <td class="px-8 py-6 text-xs font-black text-gray-600 tracking-tighter">
                            {{ $invoice->due_date->format('Y-m-d') }}
                        </td>
                        <td class="px-8 py-6">
                            @if($invoice->status == 'paid')
                                <span class="inline-flex items-center px-4 py-1.5 rounded-2xl text-[9px] font-black uppercase tracking-widest bg-green-500/5 text-green-600 border border-green-500/10">
                                    <span class="w-1.5 h-1.5 bg-green-600 rounded-full ml-1.5 animate-pulse shadow-[0_0_8px_rgba(34,197,94,0.5)]"></span>
                                    Settled
                                </span>
                            @else
                                <span class="inline-flex items-center px-4 py-1.5 rounded-2xl text-[9px] font-black uppercase tracking-widest bg-rose-500/5 text-rose-600 border border-rose-500/10">
                                    <span class="w-1.5 h-1.5 bg-rose-600 rounded-full ml-1.5 animate-pulse shadow-[0_0_8px_rgba(225,29,72,0.5)]"></span>
                                    Unpaid
                                </span>
                            @endif
                        </td>
                        <td class="px-8 py-6">
                            <span class="text-lg font-black text-gray-900 tracking-tighter">@money($invoice->amount)</span>
                        </td>
                        <td class="px-8 py-6 text-left">
                            <div class="flex items-center justify-end gap-3 translate-x-4 opacity-0 group-hover:opacity-100 group-hover:translate-x-0 transition-all duration-300">
                                <a href="{{ route('accounting.invoices.edit', $invoice) }}" class="w-10 h-10 rounded-xl bg-white/50 backdrop-blur-sm text-indigo-600 flex items-center justify-center border border-white/40 shadow-sm hover:bg-indigo-600 hover:text-white transition-all">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                </a>
                                <a href="#" class="w-10 h-10 rounded-xl bg-white/50 backdrop-blur-sm text-gray-500 flex items-center justify-center border border-white/40 shadow-sm hover:bg-gray-900 hover:text-white transition-all">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 00-2 2h2m2 4h10a2 2 0 002-2v-3a2 2 0 00-2-2H5a2 2 0 00-2 2v3a2 2 0 002 2zm0 0v-8h10v8" /></svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-8 py-20 text-center">
                            <div class="flex flex-col items-center gap-6">
                                <div class="w-20 h-20 bg-white/50 backdrop-blur-md rounded-[2rem] flex items-center justify-center border border-white/30 shadow-lg shadow-gray-200/50">
                                    <svg class="w-10 h-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-black text-gray-900">لا توجد سجلات مالية بعد</h3>
                                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-2 text-center">No financial invoice records found on file</p>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($invoices->hasPages())
        <div class="px-8 py-6 border-t border-white/10 bg-white/20 backdrop-blur-lg">
            {{ $invoices->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
