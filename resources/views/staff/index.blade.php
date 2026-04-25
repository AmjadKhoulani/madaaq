@extends('layouts.admin')

@section('title', 'إدارة الموظفين | Personnel Architecture Registry')

@section('content')
<div class="space-y-12 pb-24">
    
    <!-- Radiant Hub Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-8">
        <div class="space-y-2">
            <div class="flex items-center gap-3">
                <span class="w-12 h-1 bg-vibrant-purple rounded-full shadow-glow-purple"></span>
                <p class="text-[10px] font-black text-primary uppercase tracking-[0.3em] font-headline">Internal Governance Protocols</p>
            </div>
            <h2 class="text-4xl font-black text-slate-900 tracking-tighter italic uppercase">إدارة الطاقم الإداري</h2>
            <p class="text-slate-400 font-bold uppercase tracking-widest text-[11px] font-headline opacity-80">Managing Human Capital Architecture & Role-Based Access Sovereignty</p>
        </div>
        
        <a href="{{ route('staff.create') }}" class="px-8 py-4 bg-slate-900 text-white font-black rounded-2xl text-[11px] uppercase tracking-[0.2em] shadow-glow-purple hover:scale-[1.05] active:scale-[0.95] transition-all flex items-center gap-4 italic group">
            <span class="material-symbols-outlined text-sm group-hover:rotate-180 transition-transform duration-500">person_add</span>
            تعيين موظف جديد
        </a>
    </div>

    <!-- Radiant Command Table Hub -->
    <div class="glass-panel rounded-[3rem] overflow-hidden !bg-white/60">
        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse">
                <thead>
                    <tr class="bg-primary/5 text-slate-500 text-[10px] font-black uppercase tracking-[0.2em] border-b border-white/40 italic">
                        <th class="px-10 py-6">Human Identity</th>
                        <th class="px-10 py-6">Communication Index</th>
                        <th class="px-10 py-6 text-center">Governance Roles</th>
                        <th class="px-10 py-6 text-center">Status Protocol</th>
                        <th class="px-10 py-6 text-left">Protocol Hub</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/20">
                    @forelse($staff as $user)
                    <tr class="group hover:bg-white/40 transition-all duration-300">
                        <td class="px-10 py-7">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-primary to-vibrant-purple p-0.5 shadow-sm group-hover:shadow-glow-purple transition-all">
                                    <div class="w-full h-full bg-slate-900 rounded-lg flex items-center justify-center text-white text-[10px] font-black font-manrope italic">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                </div>
                                <div class="space-y-0.5">
                                    <span class="text-xs font-black text-slate-900 uppercase italic tracking-tighter block">{{ $user->name }}</span>
                                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">{{ $user->created_at->format('Y . m . d') }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-10 py-7 text-sm font-bold text-slate-600 font-manrope">
                            {{ $user->email }}
                            @if($user->phone)
                                <div class="text-[9px] font-black text-primary uppercase mt-1 italic tracking-widest">{{ $user->phone }}</div>
                            @endif
                        </td>
                        <td class="px-10 py-7 text-center">
                            <div class="flex flex-wrap justify-center gap-2">
                                @forelse($user->roles as $role)
                                    <span class="px-3 py-1 bg-slate-900 text-white text-[8px] font-black uppercase rounded-lg border border-white/10 italic shadow-glow-purple/20">
                                        {{ $role->display_name ?? $role->name }}
                                    </span>
                                @empty
                                    <span class="text-[9px] font-bold text-slate-300 italic uppercase">Unaligned Identity</span>
                                @endforelse
                            </div>
                        </td>
                        <td class="px-10 py-7 text-center">
                            <div class="flex justify-center">
                                @if($user->is_active)
                                    <span class="px-4 py-1.5 bg-neon-cyan/10 text-neon-cyan rounded-full text-[9px] font-black uppercase italic border border-neon-cyan/20 shadow-glow-cyan shadow-sm flex items-center gap-2">
                                        <span class="w-2 h-2 bg-neon-cyan rounded-full animate-pulse shadow-glow-cyan"></span>
                                        Synchronized
                                    </span>
                                @else
                                    <span class="px-4 py-1.5 bg-slate-100 text-slate-400 rounded-full text-[9px] font-black uppercase italic border border-slate-200 shadow-sm flex items-center gap-2 grayscale">
                                        <span class="w-2 h-2 bg-slate-300 rounded-full"></span>
                                        Deactivated
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-10 py-7">
                            <div class="flex items-center justify-end gap-3 opacity-0 group-hover:opacity-100 group-hover:translate-x-[-10px] transition-all duration-500">
                                <a href="{{ route('staff.edit', $user) }}" class="w-11 h-11 bg-white border border-slate-100 rounded-xl text-primary flex items-center justify-center shadow-lg hover:bg-primary hover:text-white hover:shadow-glow-purple transition-all italic">
                                    <span class="material-symbols-outlined text-lg">edit_note</span>
                                </a>
                                <form action="{{ route('staff.destroy', $user) }}" method="POST" onsubmit="return confirm('IDENTITY PROTOCOL: Deleting this personnel node will revoke all governance access. Proceed?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-11 h-11 bg-white border border-slate-100 rounded-xl text-rose-500 flex items-center justify-center shadow-lg hover:bg-rose-500 hover:text-white transition-all italic">
                                        <span class="material-symbols-outlined text-lg">person_remove</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-10 py-32 text-center">
                             <div class="flex flex-col items-center justify-center gap-8 italic group">
                                <div class="w-24 h-24 bg-slate-50 rounded-[2rem] flex items-center justify-center text-slate-300 border border-slate-100 shadow-inner group-hover:scale-110 transition-transform">
                                    <span class="material-symbols-outlined text-5xl font-light">group_off</span>
                                </div>
                                <div class="text-center">
                                    <h3 class="text-lg font-black text-slate-900 uppercase italic tracking-tighter">Personnel Grid Empty</h3>
                                    <p class="text-[11px] text-slate-400 font-bold uppercase tracking-[0.3em] mt-3">No active governance identities detected in the departmental matrix.</p>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($staff->hasPages())
        <div class="px-10 py-8 bg-slate-50 border-t border-slate-100">
            {{ $staff->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
