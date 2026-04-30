@extends('layouts.admin')

@section('title', 'مراقبة كروت الهوت سبوت | Identity Flux Registry')

@section('content')
<div class="space-y-12 pb-24" x-data="{ 
    selectedCount: 0,
    updateCount() { 
        this.selectedCount = document.querySelectorAll('.bulk-item:checked').length; 
    },
    toggleAll(e) {
        document.querySelectorAll('.bulk-item').forEach(el => {
            el.checked = e.target.checked;
        });
        this.updateCount();
    }
}">
    
    <!-- Radiant Hub Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-8">
        <div class="space-y-2">
            <div class="flex items-center gap-3">
                <span class="w-12 h-1 bg-accent-flow rounded-full"></span>
                <p class="text-[10px] font-black text-primary uppercase tracking-[0.3em] font-headline">Identity Emission Hub</p>
            </div>
            <h2 class="text-4xl font-black text-slate-900 tracking-tighter italic uppercase">إدارة كروت الهوت سبوت</h2>
            <p class="text-slate-400 font-bold uppercase tracking-widest text-[11px] font-headline opacity-80">Monitoring Ephemeral Credential Flux & Revenue Points</p>
        </div>
        
        <div class="flex items-center gap-4">
            <form action="{{ route('hotspot.vouchers.reprint_last') }}" method="GET" target="_blank" class="hidden md:block">
                <button type="submit" class="px-6 py-4 bg-white border border-slate-200 text-slate-900 font-black rounded-2xl text-[10px] uppercase tracking-[0.2em] hover:bg-slate-50 transition-all flex items-center gap-3 italic">
                    <span class="material-symbols-outlined text-sm">print</span>
                    آخر دفعة
                </button>
            </form>
            <a href="{{ route('hotspot.vouchers.create') }}" class="px-8 py-4 bg-slate-900 text-white font-black rounded-2xl text-[11px] uppercase tracking-[0.2em] shadow-glow-purple hover:scale-[1.05] active:scale-[0.95] transition-all flex items-center gap-4 italic group">
                <span class="material-symbols-outlined text-sm group-hover:rotate-180 transition-transform duration-500">add_to_photos</span>
                توليد كروت جديدة
            </a>
        </div>
    </div>

    <!-- Radiant Metric Matrix -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Total Vouchers -->
        <div class="glass-card p-8 rounded-[2rem] relative overflow-hidden group border-b-4 border-primary/20">
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-primary/5 rounded-full blur-2xl group-hover:scale-150 transition-transform"></div>
            <div class="flex justify-between items-start mb-6">
                <div class="w-12 h-12 bg-primary/10 rounded-2xl flex items-center justify-center text-primary shadow-sm border border-primary/10">
                    <span class="material-symbols-outlined">confirmation_number</span>
                </div>
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest italic tracking-tighter">GLOBAL REPOSITORY</span>
            </div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 font-headline italic">إجمالي الكروت</p>
            <h3 class="text-4xl font-headline font-black text-slate-900 italic tracking-tighter">{{ number_format($stats['total']) }}</h3>
        </div>

        <!-- Active Vouchers -->
        <div class="glass-card p-8 rounded-[2rem] relative overflow-hidden group border-b-4 border-neon-cyan/20">
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-neon-cyan/5 rounded-full blur-2xl group-hover:scale-150 transition-transform"></div>
            <div class="flex justify-between items-start mb-6">
                <div class="w-12 h-12 bg-neon-cyan/10 rounded-2xl flex items-center justify-center text-neon-cyan shadow-sm border border-neon-cyan/10">
                    <span class="material-symbols-outlined">verified_user</span>
                </div>
                <div class="px-3 py-1 bg-neon-cyan/10 text-neon-cyan rounded-full text-[8px] font-black uppercase italic border border-neon-cyan/20">STABLE FLOW</div>
            </div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 font-headline italic">كروت نشطة</p>
            <h3 class="text-4xl font-headline font-black text-slate-900 italic tracking-tighter text-emerald-600">{{ number_format($stats['active']) }}</h3>
        </div>

        <!-- Inactive Vouchers -->
        <div class="glass-card p-8 rounded-[2rem] relative overflow-hidden group border-b-4 border-vibrant-purple/20">
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-vibrant-purple/5 rounded-full blur-2xl group-hover:scale-150 transition-transform"></div>
            <div class="flex justify-between items-start mb-6">
                <div class="w-12 h-12 bg-vibrant-purple/10 rounded-2xl flex items-center justify-center text-vibrant-purple shadow-sm border border-vibrant-purple/10">
                    <span class="material-symbols-outlined">disabled_by_default</span>
                </div>
                <div class="w-2 h-2 bg-error rounded-full animate-pulse"></div>
            </div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 font-headline italic">كروت معطلة</p>
            <h3 class="text-4xl font-headline font-black text-slate-900 italic tracking-tighter text-rose-500">{{ number_format($stats['inactive']) }}</h3>
        </div>
    </div>

    <!-- Radiant Filter Node -->
    <div class="glass-panel p-10 rounded-[2.5rem] !bg-white/80 border-slate-100">
        <form action="{{ route('hotspot.vouchers.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
            <div class="relative group">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pl-2 mb-2 block italic">بحث عن الهوية</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Username / Token..." class="input-radiant !py-3 text-[11px] font-bold">
            </div>

            <div class="relative group">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pl-2 mb-2 block italic">حالة البروتوكول</label>
                <select name="status" class="input-radiant !py-3 text-[11px] font-bold appearance-none">
                    <option value="">جميع الحالات</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>SYNCHRONIZED - نشط</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>TERMINATED - معطل</option>
                </select>
            </div>

            <div class="relative group">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pl-2 mb-2 block italic">الباقة المركزية</label>
                <select name="package_id" class="input-radiant !py-3 text-[11px] font-bold appearance-none">
                    <option value="">جميع الباقات</option>
                    @foreach($packages as $package)
                        <option value="{{ $package->id }}" {{ request('package_id') == $package->id ? 'selected' : '' }}>{{ $package->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="relative group lg:col-span-2">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pl-2 mb-2 block italic">تحليل نطاق زمني</label>
                <div class="flex items-center gap-2">
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="input-radiant !py-3 text-[11px] font-bold">
                    <span class="text-slate-300 italic">to</span>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="input-radiant !py-3 text-[11px] font-bold">
                </div>
            </div>

            <div class="lg:col-span-5 flex justify-end gap-3 pt-4">
                <a href="{{ route('hotspot.vouchers.index') }}" class="px-8 py-3 bg-slate-100 text-slate-500 font-black rounded-xl text-[10px] uppercase tracking-widest italic transition-all hover:bg-slate-200">Reset Flux</a>
                <button type="submit" class="px-12 py-3 bg-primary text-white font-black rounded-xl text-[11px] uppercase tracking-[0.3em] shadow-glow-purple italic transition-all active:scale-95">Filter Matrix</button>
            </div>
        </form>
    </div>

    <!-- Bulk Action Hub -->
    <div x-show="selectedCount > 0" x-transition class="fixed bottom-12 left-1/2 -translate-x-1/2 z-50 px-10 py-6 glass-panel !bg-slate-900/95 text-white rounded-full shadow-2xl flex items-center gap-10 border-white/10 border backdrop-blur-2xl">
        <div class="flex items-center gap-4">
            <span class="w-3 h-3 bg-neon-cyan rounded-full animate-ping"></span>
            <p class="text-[11px] font-black uppercase tracking-widest italic">
                <span class="text-neon-cyan" x-text="selectedCount"></span> Identities Selected
            </p>
        </div>
        <div class="h-6 w-px bg-white/10"></div>
        <div class="flex gap-4">
            <button form="bulk-form" name="action" value="disable" class="px-6 py-2.5 bg-rose-500 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-rose-600 transition-all italic flex items-center gap-2">
                <span class="material-symbols-outlined text-[16px]">visibility_off</span>
                Terminte
            </button>
            <button form="bulk-form" name="action" value="delete" class="px-6 py-2.5 bg-white/10 border border-white/20 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-white/20 transition-all italic flex items-center gap-2">
                <span class="material-symbols-outlined text-[16px]">delete</span>
                Delete Registry
            </button>
        </div>
    </div>

    <!-- Radiant Command Table Hub -->
    <div class="glass-panel rounded-[3rem] overflow-hidden !bg-white/60">
        <form id="bulk-form" action="{{ route('hotspot.vouchers.bulk_action') }}" method="POST">
            @csrf
            <div class="overflow-x-auto">
                <table class="w-full text-right border-collapse">
                    <thead>
                        <tr class="bg-primary/5 text-slate-500 text-[10px] font-black uppercase tracking-[0.2em] border-b border-white/40 italic">
                            <th class="px-10 py-6 w-20">
                                <label class="flex items-center justify-center cursor-pointer">
                                    <input type="checkbox" @change="toggleAll($event)" class="w-5 h-5 rounded-lg border-slate-300 text-primary transition-all focus:ring-primary shadow-sm">
                                </label>
                            </th>
                            <th class="px-10 py-6">Ephemeral Identity</th>
                            <th class="px-10 py-6">Key Hash</th>
                            <th class="px-10 py-6 text-center">Assigned Spectrum</th>
                            <th class="px-10 py-6 text-center">Host Node</th>
                            <th class="px-10 py-6 text-center">Status</th>
                            <th class="px-10 py-6 text-center">Emission Date</th>
                            <th class="px-10 py-6 text-left">Protocol Hub</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/20">
                        @forelse($vouchers as $voucher)
                        <tr class="group hover:bg-white/60 transition-all duration-300">
                            <td class="px-10 py-7">
                                <label class="flex items-center justify-center cursor-pointer">
                                    <input type="checkbox" name="ids[]" value="{{ $voucher->id }}" @change="updateCount()" class="bulk-item w-5 h-5 rounded-lg border-slate-300 text-primary transition-all focus:ring-primary shadow-sm">
                                </label>
                            </td>
                            <td class="px-10 py-7">
                                <span class="font-manrope font-black text-sm text-primary uppercase italic tracking-tighter group-hover:scale-110 transition-transform origin-right inline-block">{{ $voucher->username }}</span>
                            </td>
                            <td class="px-10 py-7">
                                <div class="px-3 py-1.5 bg-slate-900 text-neon-cyan rounded-lg text-[10px] font-black font-manrope tracking-[0.2em] inline-block shadow-glow-cyan/20 border border-white/5 uppercase">
                                    {{ $voucher->password }}
                                </div>
                            </td>
                            <td class="px-10 py-7 text-center">
                                <div class="space-y-1">
                                    <p class="text-[11px] font-black text-slate-900 uppercase italic tracking-tighter">{{ $voucher->package->name ?? 'VOID' }}</p>
                                    @if($voucher->package)
                                        <p class="text-[9px] font-bold text-primary italic">{{ number_format($voucher->package->price) }} SAR</p>
                                    @endif
                                </div>
                            </td>
                            <td class="px-10 py-7 text-center">
                                <span class="px-3 py-1 bg-slate-100 border border-slate-200 text-slate-500 rounded-lg text-[9px] font-black uppercase tracking-widest italic">{{ $voucher->mikrotikServer->name ?? 'GRID' }}</span>
                            </td>
                            <td class="px-10 py-7 text-center">
                                <div class="flex justify-center">
                                    @if($voucher->status == 'active')
                                        <span class="px-4 py-1.5 bg-emerald-500/10 text-emerald-600 rounded-full text-[9px] font-black uppercase italic border border-emerald-500/20 shadow-glow-cyan shadow-sm flex items-center gap-2">
                                            <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse shadow-glow-cyan"></span>
                                            Synchronized
                                        </span>
                                    @else
                                        <span class="px-4 py-1.5 bg-rose-500/10 text-rose-600 rounded-full text-[9px] font-black uppercase italic border border-rose-500/20 shadow-glow-purple shadow-sm flex items-center gap-2">
                                            <span class="w-2 h-2 bg-rose-500 rounded-full"></span>
                                            Terminated
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-10 py-7 text-center">
                                <div class="text-[11px] font-black text-slate-400 font-manrope italic opacity-60">
                                    {{ $voucher->created_at->format('Y . m . d') }}
                                    <p class="text-[8px] font-bold text-slate-300 uppercase tracking-[0.2em] mt-1">{{ $voucher->created_at->diffForHumans() }}</p>
                                </div>
                            </td>
                            <td class="px-10 py-7 text-left">
                                <div class="flex items-center justify-end gap-3 opacity-0 group-hover:opacity-100 group-hover:translate-x-[-10px] transition-all duration-500">
                                    <a href="{{ route('hotspot.users.print', $voucher->id) }}" target="_blank" class="w-11 h-11 bg-white border border-slate-100 rounded-xl text-primary flex items-center justify-center shadow-lg hover:bg-primary hover:text-white hover:shadow-glow-purple transition-all italic" title="Emission Print">
                                        <span class="material-symbols-outlined text-lg">print</span>
                                    </a>
                                    <a href="{{ route('crm.clients.show', $voucher->id) }}" class="w-11 h-11 bg-white border border-slate-100 rounded-xl text-slate-400 flex items-center justify-center shadow-lg hover:bg-slate-900 hover:text-white transition-all italic" title="Identity Intel">
                                        <span class="material-symbols-outlined text-lg">visibility</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-10 py-40 text-center opacity-30 italic font-black uppercase text-[11px] tracking-[0.3em]">
                                <div class="flex flex-col items-center gap-8">
                                    <div class="w-24 h-24 bg-slate-100 rounded-[2rem] flex items-center justify-center text-slate-400 shadow-sm border border-slate-200 group-hover:scale-110 transition-transform">
                                        <span class="material-symbols-outlined text-5xl font-light">token</span>
                                    </div>
                                    <p>Void Emissions Detected in Registry Matrix</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </form>

        @if($vouchers->hasPages())
        <div class="px-10 py-8 bg-slate-50 border-t border-slate-100">
            {{ $vouchers->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
