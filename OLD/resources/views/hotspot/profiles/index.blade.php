@extends('layouts.admin')

@section('title', 'باقات الهوت سبوت | Identity Gateway Registry')

@section('content')
<div class="space-y-12 pb-24">
    
    <!-- Radiant Hub Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-8">
        <div class="space-y-2">
            <div class="flex items-center gap-3">
                <span class="w-12 h-1 bg-vibrant-purple rounded-full shadow-glow-purple"></span>
                <p class="text-[10px] font-black text-primary uppercase tracking-[0.3em] font-headline">Portal Access Governance</p>
            </div>
            <h2 class="text-4xl font-black text-slate-900 tracking-tighter italic uppercase">باقات الهوت سبوت</h2>
            <p class="text-slate-400 font-bold uppercase tracking-widest text-[11px] font-headline opacity-80">Managing Ephemeral Identity Gates & Spectral Access Profiles</p>
        </div>
        
        <a href="{{ route('hotspot.profiles.create') }}" class="px-8 py-4 bg-slate-900 text-white font-black rounded-2xl text-[11px] uppercase tracking-[0.2em] shadow-glow-purple hover:scale-[1.05] active:scale-[0.95] transition-all flex items-center gap-4 italic group">
            <span class="material-symbols-outlined text-sm group-hover:rotate-180 transition-transform duration-500">wifi_tethering</span>
            إضافة باقة هوت سبوت جديدة
        </a>
    </div>

    <!-- Radiant Command Table Hub -->
    <div class="glass-panel rounded-[3rem] overflow-hidden !bg-white/60">
        @if($profiles->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse">
                <thead>
                    <tr class="bg-primary/5 text-slate-500 text-[10px] font-black uppercase tracking-[0.2em] border-b border-white/40 italic">
                        <th class="px-10 py-6">Identity Profile</th>
                        <th class="px-10 py-6">Access Technology</th>
                        <th class="px-10 py-6 text-center">Burst Capability (D/U)</th>
                        <th class="px-10 py-6 text-center">Data Allocation</th>
                        <th class="px-10 py-6 text-center">Lease Duration</th>
                        <th class="px-10 py-6 text-center text-primary">Unit Price</th>
                        <th class="px-10 py-6 text-center">Host Nodes</th>
                        <th class="px-10 py-6 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/20">
                    @foreach($profiles as $profile)
                    <tr class="group hover:bg-white/40 transition-all duration-300">
                        <td class="px-10 py-7">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl bg-vibrant-purple p-0.5 shadow-sm group-hover:shadow-glow-purple transition-all">
                                    <div class="w-full h-full bg-slate-900 rounded-lg flex items-center justify-center text-white">
                                        <span class="material-symbols-outlined text-lg">key</span>
                                    </div>
                                </div>
                                <span class="text-xs font-black text-slate-900 uppercase italic tracking-tighter">{{ $profile->name }}</span>
                            </div>
                        </td>
                        <td class="px-10 py-7">
                             <div class="flex items-center gap-2">
                                @if($profile->technology_type === 'fiber')
                                    <span class="px-3 py-1 bg-neon-cyan/10 text-neon-cyan text-[8px] font-black uppercase rounded-lg border border-neon-cyan/20">🌐 Optical Fiber</span>
                                @elseif($profile->technology_type === 'wireless' || !$profile->technology_type)
                                    <span class="px-3 py-1 bg-amber-500/10 text-amber-600 text-[8px] font-black uppercase rounded-lg border border-amber-500/20 animate-pulse-soft">📡 RF Wireless</span>
                                @elseif($profile->technology_type === 'dsl')
                                    <span class="px-3 py-1 bg-slate-100 text-slate-500 text-[8px] font-black uppercase rounded-lg border border-slate-200">🔌 Copper DSL</span>
                                @else
                                    <span class="px-3 py-1 bg-vibrant-purple/10 text-vibrant-purple text-[8px] font-black uppercase rounded-lg border border-vibrant-purple/20">📺 Cable Link</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-10 py-7 text-center">
                            <div class="flex items-center justify-center gap-1.5 font-manrope font-black">
                                <span class="px-3 py-1 bg-slate-900 text-neon-cyan text-[10px] rounded-lg shadow-glow-cyan">{{ $profile->speed_down }}M</span>
                                <span class="text-slate-300 italic opacity-40">↓↑</span>
                                <span class="px-3 py-1 bg-slate-900 text-vibrant-purple text-[10px] rounded-lg shadow-glow-purple">{{ $profile->speed_up }}M</span>
                            </div>
                        </td>
                        <td class="px-10 py-7 text-center">
                            <span class="text-[11px] font-black text-slate-600 uppercase italic tracking-tighter">
                                @if($profile->data_limit_mb)
                                    {{ number_format($profile->data_limit_mb / 1024, 1) }} GB
                                @else
                                    <span class="text-slate-300 font-bold opacity-50">UNLIMITED</span>
                                @endif
                            </span>
                        </td>
                        <td class="px-10 py-7 text-center">
                            <div class="flex items-center justify-center gap-2 text-slate-500">
                                <span class="material-symbols-outlined text-[14px]">timer</span>
                                <span class="text-[10px] font-black uppercase italic">{{ $profile->duration_days ?? '∞' }} DAYS</span>
                            </div>
                        </td>
                        <td class="px-10 py-7 text-center">
                            <div class="flex items-baseline justify-center gap-1">
                                <span class="text-lg font-black text-primary italic tracking-tighter">{{ number_format($profile->price, 0) }}</span>
                                <span class="text-[8px] font-black text-slate-400 uppercase italic">SAR</span>
                            </div>
                        </td>
                        <td class="px-10 py-7 text-center">
                             @php $targetCount = $profile->routers->count() + $profile->mikrotikServers->count(); @endphp
                             @if($targetCount > 0)
                                <span class="px-3 py-1 bg-slate-100 text-primary border border-slate-200 rounded-lg text-[9px] font-black uppercase tracking-widest">
                                    {{ $targetCount }} NODES
                                </span>
                             @else
                                <span class="text-[9px] font-bold text-slate-300 italic uppercase">Unaligned</span>
                             @endif
                        </td>
                        <td class="px-10 py-7">
                            <div class="flex items-center justify-end gap-3 opacity-0 group-hover:opacity-100 group-hover:translate-x-[-10px] transition-all duration-500">
                                <a href="{{ route('hotspot.profiles.edit', $profile) }}" class="w-11 h-11 bg-white border border-slate-100 rounded-xl text-primary flex items-center justify-center shadow-lg hover:bg-primary hover:text-white hover:shadow-glow-purple transition-all italic">
                                    <span class="material-symbols-outlined text-lg">drive_file_rename_outline</span>
                                </a>
                                <form action="{{ route('hotspot.profiles.destroy', $profile) }}" method="POST" onsubmit="return confirm('IDENTITY PROTOCOL: Deleting this profile will remove access rules from all host nodes. Proceed?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-11 h-11 bg-white border border-slate-100 rounded-xl text-rose-500 flex items-center justify-center shadow-lg hover:bg-rose-500 hover:text-white transition-all italic">
                                        <span class="material-symbols-outlined text-lg">delete_sweep</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="py-32 flex flex-col items-center justify-center gap-8 glass-card rounded-[2.5rem] border-dashed italic group">
            <div class="w-24 h-24 bg-slate-50 rounded-[2rem] flex items-center justify-center text-slate-300 border border-slate-100 shadow-inner group-hover:scale-110 transition-transform">
                <span class="material-symbols-outlined text-5xl font-light">wifi_off</span>
            </div>
            <div class="text-center">
                <h3 class="text-lg font-black text-slate-900 uppercase italic tracking-tighter">Hotspot Matrix Locked</h3>
                <p class="text-[11px] text-slate-400 font-bold uppercase tracking-[0.3em] mt-3">No active gateway profiles detected in the regional cluster.</p>
            </div>
            <a href="{{ route('hotspot.profiles.create') }}" class="mt-6 px-10 py-4 bg-vibrant-purple text-white font-black rounded-2xl text-[11px] uppercase tracking-[0.3em] shadow-glow-purple flex items-center gap-4 italic transition-all active:scale-95">
                <span class="material-symbols-outlined text-sm">vpn_key</span>
                Provision New Gate
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
