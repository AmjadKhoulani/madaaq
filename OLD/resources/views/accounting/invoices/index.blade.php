@extends('layouts.admin')

@section('title', 'الفواتير والتحصيل المالي | Fiscal Intelligence Hub')

@section('content')
<div class="space-y-12 pb-24">
    
    <!-- Radiant Hub Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-8">
        <div class="space-y-2">
            <div class="flex items-center gap-3">
                <span class="w-12 h-1 bg-accent-flow rounded-full"></span>
                <p class="text-[10px] font-black text-primary uppercase tracking-[0.3em] font-headline">Enterprise Fiscal Governance</p>
            </div>
            <h2 class="text-4xl font-black text-slate-900 tracking-tighter italic uppercase">الفواتير والتحصيل المالي</h2>
            <p class="text-slate-400 font-bold uppercase tracking-widest text-[11px] font-headline opacity-80">Synchronized Revenue Ledger & Monetary Settlement Matrix</p>
        </div>
        
        <a href="{{ route('accounting.invoices.create') }}" class="px-8 py-4 bg-slate-900 text-white font-black rounded-2xl text-[11px] uppercase tracking-[0.2em] shadow-glow-purple hover:scale-[1.05] active:scale-[0.95] transition-all flex items-center gap-4 italic group">
            <span class="material-symbols-outlined text-sm group-hover:rotate-90 transition-transform duration-500">add_card</span>
            إنشاء سجل مالي جديد
        </a>
    </div>

    <!-- Radiant Metric Matrix -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
        <!-- Total Revenue -->
        <div class="glass-card p-8 rounded-[2rem] relative overflow-hidden group border-b-4 border-emerald-500/20">
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-emerald-500/5 rounded-full blur-2xl group-hover:scale-150 transition-transform"></div>
            <div class="flex justify-between items-start mb-6">
                <div class="w-12 h-12 bg-emerald-500/10 rounded-2xl flex items-center justify-center text-emerald-600 shadow-sm border border-emerald-500/10">
                    <span class="material-symbols-outlined">account_balance_wallet</span>
                </div>
                <div class="flex items-center gap-1.5 px-3 py-1 bg-emerald-500/10 text-emerald-600 rounded-full text-[9px] font-black uppercase italic border border-emerald-500/10">
                    <span class="material-symbols-outlined text-[12px]">verified</span>
                    SETTLED
                </div>
            </div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 font-headline italic">إجمالي الإيرادات</p>
            <div class="flex items-baseline gap-2">
                <h3 class="text-3xl font-headline font-black text-slate-900 italic tracking-tighter">@money($stats['total_revenue'])</h3>
                <span class="text-[10px] font-black text-slate-400 italic">SAR</span>
            </div>
        </div>
        
        <!-- Pending Dues -->
        <div class="glass-card p-8 rounded-[2rem] relative overflow-hidden group border-b-4 border-rose-500/20">
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-rose-500/5 rounded-full blur-2xl group-hover:scale-150 transition-transform"></div>
            <div class="flex justify-between items-start mb-6">
                <div class="w-12 h-12 bg-rose-500/10 rounded-2xl flex items-center justify-center text-rose-600 shadow-sm border border-rose-500/10">
                    <span class="material-symbols-outlined">pending_actions</span>
                </div>
                <div class="w-3 h-3 bg-rose-500 rounded-full animate-pulse shadow-glow-purple"></div>
            </div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 font-headline italic">المستحقات المعلقة</p>
            <div class="flex items-baseline gap-2">
                <h3 class="text-3xl font-headline font-black text-slate-900 italic tracking-tighter">@money($stats['unpaid_amount'])</h3>
                <span class="text-[10px] font-black text-slate-400 italic">SAR</span>
            </div>
        </div>

        <!-- Settled Count -->
        <div class="glass-card p-8 rounded-[2rem] relative overflow-hidden group border-b-4 border-primary/20">
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-primary/5 rounded-full blur-2xl group-hover:scale-150 transition-transform"></div>
            <div class="flex justify-between items-start mb-6">
                <div class="w-12 h-12 bg-primary/10 rounded-2xl flex items-center justify-center text-primary shadow-sm border border-primary/10">
                    <span class="material-symbols-outlined">task_alt</span>
                </div>
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest italic tracking-tighter">PROTOCOL INDEX</span>
            </div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 font-headline italic">سجلات مسددة</p>
            <h3 class="text-4xl font-headline font-black text-slate-900 italic tracking-tighter">{{ number_format($stats['paid_count']) }}</h3>
        </div>

        <!-- Awaiting Count -->
        <div class="glass-card p-8 rounded-[2rem] relative overflow-hidden group border-b-4 border-neon-cyan/20">
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-neon-cyan/5 rounded-full blur-2xl group-hover:scale-150 transition-transform"></div>
            <div class="flex justify-between items-start mb-6">
                <div class="w-12 h-12 bg-neon-cyan/10 rounded-2xl flex items-center justify-center text-neon-cyan shadow-sm border border-neon-cyan/10">
                    <span class="material-symbols-outlined">history</span>
                </div>
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest italic tracking-tighter">FUTURE YIELD</span>
            </div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 font-headline italic">سجلات معلقة</p>
            <h3 class="text-4xl font-headline font-black text-slate-900 italic tracking-tighter">{{ number_format($stats['unpaid_count']) }}</h3>
        </div>
    </div>

    <!-- Radiant Search & Filter Node -->
    <div class="glass-panel p-8 rounded-[2.5rem] !bg-white/80 border-slate-100">
        <form action="{{ route('accounting.invoices.index') }}" method="GET" class="flex flex-col lg:flex-row items-center gap-6">
            <div class="relative flex-1 group w-full">
                <select name="status" class="input-radiant !bg-white select-none italic font-black uppercase text-[10px] tracking-widest">
                    <option value="all">Global Ledger Filter - All Statuses</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>SYNCHRONIZED - SETTLED</option>
                    <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>AWAITING HANDSHAKE - PENDING</option>
                </select>
                <span class="material-symbols-outlined absolute left-6 top-4 text-slate-300 pointer-events-none group-focus-within:text-primary">filter_list</span>
            </div>
            <button type="submit" class="w-full lg:w-auto px-12 py-4 bg-primary text-white font-black rounded-2xl text-[11px] uppercase tracking-[0.4em] shadow-glow-purple hover:scale-[1.05] transition-all italic">
                Analyze Records
            </button>
        </form>
    </div>

    <!-- Command Table Hub -->
    <div class="glass-panel rounded-[3rem] overflow-hidden !bg-white/60">
        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse">
                <thead>
                    <tr class="bg-primary/5 text-slate-500 text-[10px] font-black uppercase tracking-[0.2em] border-b border-white/40 italic">
                        <th class="px-10 py-6">Fiscal Token ID</th>
                        <th class="px-10 py-6">Beneficiary Identity</th>
                        <th class="px-10 py-6">Temporal Deadline</th>
                        <th class="px-10 py-6 text-center">Settlement Status</th>
                        <th class="px-10 py-6 text-center">Protocol Density</th>
                        <th class="px-10 py-6 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/20">
                    @forelse($invoices as $invoice)
                    <tr class="group hover:bg-white/60 transition-all duration-300">
                        <td class="px-10 py-7">
                            <span class="font-manrope font-black text-xs text-slate-400 tracking-[0.2em] group-hover:text-primary transition-colors">#{{ $invoice->invoice_number }}</span>
                        </td>
                        <td class="px-10 py-7">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-slate-900 p-0.5">
                                    <div class="w-full h-full bg-slate-800 rounded-lg flex items-center justify-center text-[10px] font-black text-neon-cyan uppercase">
                                        {{ substr($invoice->client->name ?? '?', 0, 1) }}
                                    </div>
                                </div>
                                <div>
                                    <p class="text-xs font-black text-slate-900 uppercase italic tracking-tighter">{{ $invoice->client->name ?? 'DELETED CORE' }}</p>
                                    @if(isset($invoice->client->username))
                                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-0.5 font-manrope italic opacity-60">{{ $invoice->client->username }}</p>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-10 py-7">
                             <div class="flex items-center gap-3 text-slate-600">
                                <span class="material-symbols-outlined text-sm font-light">calendar_today</span>
                                <span class="text-[11px] font-black font-manrope tracking-tighter uppercase italic">{{ $invoice->due_date->format('Y . m . d') }}</span>
                             </div>
                        </td>
                        <td class="px-10 py-7 text-center">
                            <div class="flex justify-center">
                                @if($invoice->status == 'paid')
                                    <span class="px-5 py-1.5 rounded-full text-[9px] font-black bg-emerald-500/10 text-emerald-600 border border-emerald-500/20 uppercase italic shadow-glow-cyan shadow-sm">Verified Settlement</span>
                                @else
                                    <span class="px-5 py-1.5 rounded-full text-[9px] font-black bg-rose-500/10 text-rose-600 border border-rose-500/20 uppercase italic shadow-glow-purple shadow-sm">Protocol Pending</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-10 py-7 text-center">
                            <div class="flex items-baseline justify-center gap-1.5">
                                <span class="text-lg font-manrope font-black text-slate-900 tracking-tighter">@money($invoice->amount)</span>
                                <span class="text-[9px] font-black text-slate-400 uppercase italic">SAR</span>
                            </div>
                        </td>
                        <td class="px-10 py-7">
                            <div class="flex items-center justify-end gap-3 opacity-0 group-hover:opacity-100 group-hover:translate-x-[-10px] transition-all duration-500">
                                <a href="{{ route('accounting.invoices.edit', $invoice) }}" class="w-11 h-11 bg-white border border-slate-100 rounded-xl text-primary flex items-center justify-center shadow-lg hover:bg-primary hover:text-white hover:shadow-glow-purple transition-all italic">
                                    <span class="material-symbols-outlined text-lg">drive_file_rename_outline</span>
                                </a>
                                <a href="#" class="w-11 h-11 bg-white border border-slate-100 rounded-xl text-slate-400 flex items-center justify-center shadow-lg hover:bg-slate-900 hover:text-white transition-all italic">
                                    <span class="material-symbols-outlined text-lg">print</span>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-10 py-32 text-center opacity-30 italic font-black uppercase text-[11px] tracking-[0.3em]">
                            <div class="flex flex-col items-center gap-8">
                                <div class="w-24 h-24 bg-slate-100 rounded-[2rem] flex items-center justify-center text-slate-400 shadow-sm border border-slate-200">
                                    <span class="material-symbols-outlined text-4xl font-light">receipt_long</span>
                                </div>
                                <p>No Fiscal Identity Records Found in Global Registry</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($invoices->hasPages())
        <div class="px-10 py-8 bg-slate-50 border-t border-slate-100">
            {{ $invoices->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
