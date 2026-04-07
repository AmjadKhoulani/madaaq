@extends('layouts.app')

@push('styles')
<style>
    .glass-panel { background: rgba(255, 255, 255, 0.4); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); }
    [x-cloak] { display: none !important; }
</style>
@endpush

@section('content')
<div class="max-w-4xl mx-auto space-y-10 pb-20">
    <!-- Modification Header -->
    <div class="glass-panel border border-white/40 rounded-[3rem] p-10 md:p-12 shadow-2xl relative overflow-hidden">
        <div class="absolute -top-24 -right-24 w-64 h-64 bg-indigo-600/5 rounded-full blur-3xl"></div>
        <div class="flex flex-col md:flex-row items-center justify-between gap-8 relative z-10">
            <div class="flex items-center gap-8">
                <div class="w-20 h-20 bg-indigo-600 rounded-[2rem] flex items-center justify-center text-white shadow-xl shadow-indigo-100 shrink-0">
                    <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </div>
                <div class="text-center md:text-left">
                    <h2 class="text-3xl font-black text-gray-900 tracking-tighter uppercase">Modify Fiscal Node #{{ $invoice->invoice_number }}</h2>
                    <p class="text-gray-500 font-bold text-xs uppercase tracking-widest mt-1">Adjusting protocol for {{ $invoice->client->name }}</p>
                </div>
            </div>
            <a href="{{ route('accounting.invoices.index') }}" class="px-8 py-4 bg-white/60 text-gray-500 font-black text-[11px] uppercase tracking-widest rounded-2xl hover:bg-white hover:text-gray-900 transition-all border border-white/60 shadow-sm flex items-center gap-3">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Ledger View
            </a>
        </div>
    </div>

    <form action="{{ route('accounting.invoices.update', $invoice) }}" method="POST" class="space-y-10">
        @csrf
        @method('PUT')

        <!-- Fiscal Parameters -->
        <div class="glass-panel border border-white/40 rounded-[3rem] p-10 md:p-12 shadow-2xl relative overflow-hidden">
            <div class="flex items-center gap-4 mb-10">
                <div class="w-12 h-12 bg-emerald-600/10 rounded-2xl flex items-center justify-center text-emerald-600 font-bold">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="text-2xl font-black text-gray-900 tracking-tight uppercase">Adjust Economic Parameters</h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                <!-- Amount Protocol -->
                <div class="space-y-4">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-2">Monetary Sum ({{ $currency }}) <span class="text-rose-500">*</span></label>
                    <div class="relative group">
                        <input type="number" step="0.01" name="amount" value="{{ $invoice->amount }}" required class="w-full pl-6 pr-12 py-5 bg-white/40 backdrop-blur-md border border-white/60 rounded-3xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500/40 font-mono font-black text-gray-900 transition-all shadow-inner" placeholder="0.00">
                        <div class="absolute inset-y-0 right-0 pr-6 flex items-center pointer-events-none text-emerald-600 font-black text-xs opacity-40 group-focus-within:opacity-100 transition-opacity">
                            {{ $currency }}
                        </div>
                    </div>
                </div>

                <!-- Temporal Protocol -->
                <div class="space-y-4">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-2">Maturity Horizon <span class="text-rose-500">*</span></label>
                    <input type="date" name="due_date" value="{{ $invoice->due_date->format('Y-m-d') }}" required class="w-full px-6 py-5 bg-white/40 backdrop-blur-md border border-white/60 rounded-3xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500/40 font-black text-gray-900 transition-all shadow-inner">
                </div>
            </div>

            <!-- Settlement Status -->
            <div class="mt-12 space-y-4">
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-2">Settlement Protocol</label>
                <div class="flex flex-wrap gap-6">
                    <label class="flex-1 min-w-[200px] cursor-pointer group">
                        <input type="radio" name="status" value="unpaid" {{ $invoice->status == 'unpaid' ? 'checked' : '' }} class="hidden peer">
                        <div class="p-6 bg-white/40 backdrop-blur-md border border-white/60 rounded-3xl transition-all peer-checked:bg-rose-500/5 peer-checked:border-rose-500/40 peer-checked:ring-4 peer-checked:ring-rose-500/10 group-hover:scale-[1.02] flex items-center gap-4">
                            <div class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center text-gray-400 peer-checked:bg-rose-500 peer-checked:text-white transition-colors">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest peer-checked:text-rose-600 transition-colors">Protocol: Pending</p>
                                <p class="text-sm font-black text-gray-900 uppercase tracking-tight">Unpaid Archive</p>
                            </div>
                        </div>
                    </label>

                    <label class="flex-1 min-w-[200px] cursor-pointer group">
                        <input type="radio" name="status" value="paid" {{ $invoice->status == 'paid' ? 'checked' : '' }} class="hidden peer">
                        <div class="p-6 bg-white/40 backdrop-blur-md border border-white/60 rounded-3xl transition-all peer-checked:bg-emerald-500/5 peer-checked:border-emerald-500/40 peer-checked:ring-4 peer-checked:ring-emerald-500/10 group-hover:scale-[1.02] flex items-center gap-4">
                            <div class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center text-gray-400 peer-checked:bg-emerald-500 peer-checked:text-white transition-colors">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest peer-checked:text-emerald-600 transition-colors">Protocol: Verified</p>
                                <p class="text-sm font-black text-gray-900 uppercase tracking-tight">Confirmed Settlement</p>
                            </div>
                        </div>
                    </label>
                </div>
            </div>
        </div>

        <!-- Narrative Context -->
        <div class="glass-panel border border-white/40 rounded-[3rem] p-10 md:p-12 shadow-2xl relative overflow-hidden">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-12 h-12 bg-gray-600/10 rounded-2xl flex items-center justify-center text-gray-600 font-bold">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h7"/></svg>
                </div>
                <h3 class="text-2xl font-black text-gray-900 tracking-tight uppercase">Narrative Context</h3>
            </div>
            
            <div class="space-y-4">
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-2">Registry Memo / Descriptive Index</label>
                <textarea name="description" rows="4" class="w-full px-6 py-5 bg-white/40 backdrop-blur-md border border-white/60 rounded-3xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500/40 font-black text-gray-900 transition-all shadow-inner" placeholder="Provide strategic narrative for this fiscal node...">{{ $invoice->description ?? '' }}</textarea>
            </div>
        </div>

        <!-- Commitment & Removal Controllers -->
        <div class="flex flex-col md:flex-row items-center justify-between gap-10 glass-panel border border-white/40 p-12 rounded-[3.5rem] shadow-2xl relative overflow-hidden">
            <div class="absolute bottom-0 left-0 w-64 h-64 bg-rose-600/5 rounded-full blur-3xl -mb-32 -ml-32"></div>
            <div class="flex items-center gap-8 relative z-10">
                <button type="button" onclick="if(confirm('Initiate removal protocol for this fiscal node?')) document.getElementById('delete-form').submit()" class="w-16 h-16 bg-rose-500/10 text-rose-500 rounded-[1.5rem] flex items-center justify-center text-2xl shrink-0 hover:bg-rose-500 hover:text-white transition-all shadow-lg hover:shadow-rose-100 group">
                    <svg class="w-8 h-8 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
                <div class="space-y-1">
                    <h5 class="text-lg font-black text-gray-900 uppercase tracking-tight">Fiscal Commitment</h5>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Adjusting the immutable ledger state.</p>
                </div>
            </div>
            <div class="flex items-center gap-6 relative z-10">
                <a href="{{ route('accounting.invoices.index') }}" class="px-10 py-5 bg-white/60 text-gray-500 font-black text-[11px] uppercase tracking-[0.2em] rounded-2xl hover:bg-white hover:text-gray-900 transition-all">
                    Discard Changes
                </a>
                <button type="submit" class="px-12 py-5 bg-gray-900 text-white font-black text-[11px] uppercase tracking-[0.3em] rounded-2xl shadow-2xl shadow-indigo-200 hover:bg-emerald-600 transition-all hover:scale-105 active:scale-95 leading-none">
                    Sync Ledger commit
                </button>
            </div>
        </div>
    </form>
</div>

<form id="delete-form" action="{{ route('accounting.invoices.destroy', $invoice) }}" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>
@endsection
