@extends('layouts.admin')

@section('title', 'سجل النشاطات | Temporal Event Registry')

@section('content')
<div class="space-y-12 pb-24">
    
    <!-- Radiant Hub Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-8">
        <div class="space-y-2">
            <div class="flex items-center gap-3">
                <span class="w-12 h-1 bg-accent-flow rounded-full"></span>
                <p class="text-[10px] font-black text-primary uppercase tracking-[0.3em] font-headline">Operations Audit Trail</p>
            </div>
            <h2 class="text-4xl font-black text-slate-900 tracking-tighter italic uppercase">سجل النشاطات المركزية</h2>
            <p class="text-slate-400 font-bold uppercase tracking-widest text-[11px] font-headline opacity-80">Monitoring System Mutation Events & Temporal Governance Shards</p>
        </div>
    </div>

    <!-- Radiant Filter Node -->
    <div class="glass-panel p-10 rounded-[2.5rem] !bg-white/80 border-slate-100 shadow-sm">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="space-y-3">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pr-4 italic">بحث عن حدث (Event Search)</label>
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Action type, target..." class="input-radiant !py-3.5 pr-12 text-[11px] font-bold">
                    <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-slate-300">search</span>
                </div>
            </div>
            
            <div class="space-y-3">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pr-4 italic">موظف العمليات (Operator)</label>
                <div class="relative group">
                    <select name="user_id" class="input-radiant !py-3.5 pr-12 text-[11px] font-black uppercase italic appearance-none">
                        <option value="">جميع المصادر (All Sources)</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 pointer-events-none group-focus-within:text-primary">expand_more</span>
                </div>
            </div>

            <div class="space-y-3 lg:col-span-2">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pr-4 italic">نطاق السجل الزمني (Temporal Range)</label>
                <div class="flex items-center gap-4">
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="input-radiant !py-3.5 text-[11px] font-bold">
                    <span class="text-slate-300 italic text-[10px] font-black uppercase">To</span>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="input-radiant !py-3.5 text-[11px] font-bold">
                </div>
            </div>

            <div class="lg:col-span-4 flex justify-end gap-3 pt-4">
                <a href="{{ route('activity-logs.index') }}" class="px-8 py-3 bg-slate-100 text-slate-500 font-black rounded-xl text-[10px] uppercase tracking-widest italic transition-all hover:bg-slate-200">Reset Scopes</a>
                <button type="submit" class="px-12 py-3 bg-slate-900 text-white font-black rounded-xl text-[11px] uppercase tracking-[0.3em] shadow-glow-purple italic transition-all active:scale-95">Filter Timeline</button>
            </div>
        </form>
    </div>

    <!-- Temporal Registry Matrix -->
    <div class="space-y-6">
        @forelse($logs as $log)
            <div class="glass-panel p-8 rounded-[2.5rem] !bg-white/70 border-white/40 flex flex-col md:flex-row gap-8 group hover:bg-white/90 transition-all duration-500 hover:translate-x-[-10px]">
                <!-- Event Identity Shard -->
                <div class="flex-shrink-0 flex items-start">
                    <div class="w-16 h-16 rounded-[1.5rem] flex items-center justify-center shadow-lg transition-all duration-500 group-hover:scale-110
                        @if(str_contains($log->description, 'إنشاء')) bg-emerald-500/10 text-emerald-600 border border-emerald-500/20 shadow-emerald-500/10
                        @elseif(str_contains($log->description, 'تعديل')) bg-blue-500/10 text-blue-600 border border-blue-500/20 shadow-blue-500/10
                        @elseif(str_contains($log->description, 'حذف')) bg-rose-500/10 text-rose-600 border border-rose-500/20 shadow-rose-500/10
                        @else bg-slate-900/10 text-slate-900 border border-slate-900/20 @endif">
                        <span class="material-symbols-outlined text-3xl">
                            @if(str_contains($log->description, 'إنشاء')) add_circle
                            @elseif(str_contains($log->description, 'تعديل')) contract_edit
                            @elseif(str_contains($log->description, 'حذف')) delete_forever
                            @else history @endif
                        </span>
                    </div>
                </div>

                <!-- Event Content Matrix -->
                <div class="flex-1 space-y-4">
                    <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
                        <div class="space-y-1">
                            <h4 class="text-sm font-black text-slate-900 uppercase italic tracking-tighter">{{ $log->description }}</h4>
                            <div class="flex items-center gap-3">
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest italic">Executed By:</span>
                                <div class="flex items-center gap-2">
                                    <div class="w-5 h-5 bg-primary/10 rounded-full flex items-center justify-center text-primary text-[6px] font-black">
                                         {{ strtoupper(substr($log->causer->name ?? 'SYS', 0, 2)) }}
                                    </div>
                                    <span class="text-[10px] font-black text-primary uppercase italic">{{ $log->causer->name ?? 'Central System' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-col items-end gap-1">
                            <span class="text-[11px] font-black text-slate-900 font-manrope italic tracking-tighter">{{ $log->created_at->format('H : i : s') }}</span>
                            <span class="text-[8px] font-bold text-slate-400 uppercase tracking-widest opacity-60">{{ $log->created_at->format('Y . m . d') }}</span>
                            <span class="text-[7px] font-black text-slate-300 uppercase tracking-widest italic mt-1">{{ $log->created_at->diffForHumans() }}</span>
                        </div>
                    </div>

                    @if($log->properties && (isset($log->properties['changes']) || isset($log->properties['attributes'])))
                        <div class="mt-4 pt-4 border-t border-white/40">
                            <details class="group/details">
                                <summary class="flex items-center gap-3 text-[9px] font-black text-primary uppercase tracking-[0.2em] italic cursor-pointer list-none hover:text-vibrant-purple transition-all">
                                    <span class="material-symbols-outlined text-sm group-open/details:rotate-180 transition-transform">database</span>
                                    Inspection Registry Data
                                    <span class="w-20 h-px bg-primary/20 flex-1"></span>
                                </summary>
                                <div class="mt-4 p-6 bg-slate-900 rounded-[1.5rem] border border-white/5 space-y-3 shadow-inner">
                                    @php 
                                        $changes = $log->properties['changes'] ?? $log->properties['attributes'] ?? [];
                                        $old = $log->properties['old'] ?? [];
                                    @endphp
                                    @foreach($changes as $key => $value)
                                        @if($key !== 'updated_at' && $key !== 'created_at')
                                        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
                                            <span class="md:col-span-3 text-[9px] font-black text-slate-500 uppercase italic tracking-widest">{{ str_replace('_', ' ', $key) }}</span>
                                            <div class="md:col-span-9 flex items-center gap-4 text-[10px] font-manrope">
                                                <span class="px-2 py-1 bg-white/5 text-slate-400 rounded-md border border-white/5 line-through italic opacity-50">{{ is_array($old[$key] ?? '--') ? 'ARRAY' : ($old[$key] ?? '--') }}</span>
                                                <span class="material-symbols-outlined text-[12px] text-neon-cyan animate-pulse">keyboard_double_arrow_left</span>
                                                <span class="px-2 py-1 bg-neon-cyan/10 text-neon-cyan rounded-md border border-neon-cyan/20 font-black italic">{{ is_array($value) ? 'ARRAY' : $value }}</span>
                                            </div>
                                        </div>
                                        @endif
                                    @endforeach
                                </div>
                            </details>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="py-40 flex flex-col items-center justify-center gap-8 glass-panel !bg-white/60 rounded-[3rem] italic opacity-40">
                <span class="material-symbols-outlined text-6xl font-light">history_toggle_off</span>
                <p class="text-[11px] font-black uppercase tracking-[0.3em]">No Temporal Events Detected in the Registry Scope</p>
            </div>
        @endforelse
    </div>

    <!-- Radiant Pagination -->
    @if($logs->hasPages())
    <div class="glass-panel rounded-[2rem] p-8 !bg-white/60 border-white/40">
        {{ $logs->links() }}
    </div>
    @endif
</div>
@endsection
