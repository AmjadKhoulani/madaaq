@extends('layouts.app')

@push('styles')
<style>
    .glass-panel { background: rgba(255, 255, 255, 0.4); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); }
    [x-cloak] { display: none !important; }
    .status-pulse { position: relative; display: flex; align-items: center; justify-content: center; }
    .status-pulse::after { content: ''; position: absolute; width: 100%; height: 100%; border-radius: 50%; opacity: 0; animation: pulse 2s infinite; }
    @keyframes pulse { 0% { transform: scale(1); opacity: 0.5; } 100% { transform: scale(2.5); opacity: 0; } }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto space-y-10 pb-20">
    <!-- Infrastructure Fleet Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-10 glass-panel border border-white/40 rounded-[3rem] p-10 md:p-12 shadow-2xl relative overflow-hidden">
        <div class="absolute -top-24 -right-24 w-64 h-64 bg-indigo-600/5 rounded-full blur-3xl"></div>
        <div class="relative z-10">
            <h1 class="text-4xl font-black text-gray-900 tracking-tighter uppercase">Core Node Fleet</h1>
            <p class="text-xs font-bold text-indigo-600 uppercase tracking-[0.3em] mt-2 flex items-center gap-3">
                <span class="w-2 h-2 rounded-full bg-indigo-600 status-pulse shadow-[0_0_12px_rgba(79,70,229,0.5)]"></span>
                Primary Network Infrastructure Management
            </p>
        </div>
        <div class="flex items-center gap-6 relative z-10">
            <a href="{{ route('servers.create') }}" class="px-10 py-5 bg-gray-900 text-white font-black text-[11px] uppercase tracking-[0.2em] rounded-2xl shadow-2xl hover:bg-indigo-600 transition-all hover:scale-105 active:scale-95 group flex items-center gap-3 leading-none">
                <svg class="w-5 h-5 group-hover:rotate-90 transition-transform duration-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v12m8-8H4"/></svg>
                Deploy New Node
            </a>
        </div>
    </div>

    <!-- Active Node Matrix -->
    @if($servers->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10" x-data="serverGrid()">
            @foreach($servers as $server)
            <div class="glass-panel border border-white/40 rounded-[3rem] p-4 group hover:shadow-[0_32px_64px_-12px_rgba(79,70,229,0.15)] transition-all duration-700 hover:-translate-y-3 relative overflow-hidden flex flex-col min-h-[480px]">
                <div class="absolute -top-20 -right-20 w-48 h-48 bg-indigo-500/5 rounded-full blur-[100px] group-hover:bg-indigo-500/10 transition-all duration-700"></div>
                
                <div class="p-8 pb-4 flex-grow relative z-10">
                    <!-- Identity Shield -->
                    <div class="flex items-start justify-between mb-10">
                        <div class="flex items-center gap-6">
                            <div class="w-24 h-24 bg-white/60 backdrop-blur-md rounded-[2rem] border border-white/80 shadow-2xl p-4 flex items-center justify-center group-hover:scale-110 group-hover:-rotate-3 transition-all duration-700 overflow-hidden relative">
                                <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/5 to-transparent"></div>
                                <img src="{{ $server->deviceModel->image_url ?? '/images/devices/mikrotik_node.png' }}" 
                                     class="max-w-full max-h-full object-contain filter drop-shadow-md brightness-[0.95] group-hover:brightness-100 transition-all"
                                     onerror="this.src='https://placehold.co/150x100?text=Node'">
                            </div>
                            <div class="min-w-0">
                                <h3 class="text-2xl font-black text-gray-900 tracking-tighter truncate uppercase mb-2">{{ $server->name }}</h3>
                                <div class="bg-gray-900/5 px-4 py-2 rounded-xl inline-flex items-center gap-2 border border-white/40 shadow-inner">
                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                                    <p class="text-[10px] font-black font-mono text-gray-600 leading-none uppercase tracking-widest">{{ $server->ip }}:{{ $server->api_port }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Real-time Status Orb -->
                        <div id="status-orb-{{ $server->id }}" class="shrink-0">
                            @if($server->connection_status === 'connected')
                                <div class="w-12 h-12 bg-emerald-500/10 rounded-2xl flex items-center justify-center text-emerald-600 border border-emerald-500/20 shadow-[0_0_20px_rgba(16,185,129,0.1)]">
                                    <svg class="w-6 h-6 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                </div>
                            @elseif($server->connection_status === 'error')
                                <div class="w-12 h-12 bg-rose-500/10 rounded-2xl flex items-center justify-center text-rose-500 border border-rose-500/20 shadow-[0_0_20px_rgba(244,63,94,0.1)]">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                </div>
                            @else
                                <div class="w-12 h-12 bg-gray-500/10 rounded-2xl flex items-center justify-center text-gray-400 border border-gray-500/20">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Telemetry Summary -->
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div class="p-6 bg-white/40 backdrop-blur-md rounded-[2rem] border border-white/60 group-hover:bg-white/70 transition-all duration-500 shadow-sm relative overflow-hidden">
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1 relative z-10">Uplink Access</p>
                            <p class="text-xs font-black text-gray-800 tracking-tight truncate relative z-10">{{ $server->username }}</p>
                            <div class="absolute bottom-0 right-0 w-16 h-16 bg-indigo-500/5 rounded-full blur-xl -mb-8 -mr-8"></div>
                        </div>
                        <div class="p-6 bg-white/40 backdrop-blur-md rounded-[2rem] border border-white/60 group-hover:bg-white/70 transition-all duration-500 shadow-sm relative overflow-hidden">
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1 relative z-10">Site Registry</p>
                            <p class="text-xs font-black text-indigo-600 tracking-tight truncate relative z-10">📍 {{ $server->location ?? 'Independent Node' }}</p>
                            <div class="absolute bottom-0 right-0 w-16 h-16 bg-rose-500/5 rounded-full blur-xl -mb-8 -mr-8"></div>
                        </div>
                    </div>

                    <!-- Connection Protocol Diagnostics -->
                    <div class="flex items-center gap-3 mt-4">
                        <button @click="testConnection({{ $server->id }})" class="flex-1 py-4 bg-white/60 hover:bg-white text-indigo-600 font-extrabold text-[10px] uppercase tracking-[0.2em] rounded-2xl border border-white/80 shadow-sm transition-all active:scale-95 flex items-center justify-center gap-2 group/btn">
                            <svg class="w-4 h-4 group-hover/btn:rotate-180 transition-transform duration-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            Test Handshake
                        </button>
                    </div>
                </div>

                <!-- Command Interface -->
                <div class="p-8 pt-4 bg-white/20 backdrop-blur-xl border-t border-white/20 grid grid-cols-2 gap-4 relative z-10">
                    <a href="{{ route('servers.show', $server) }}" class="px-4 py-5 bg-gray-900 hover:bg-black text-white text-center font-black text-[11px] uppercase tracking-[0.3em] rounded-2xl shadow-2xl hover:shadow-gray-900/40 transition-all active:scale-95 leading-none">
                        Network Console
                    </a>
                    <a href="{{ route('servers.edit', $server) }}" class="px-4 py-5 bg-white/80 hover:bg-white text-gray-500 hover:text-indigo-600 text-center font-black text-[11px] uppercase tracking-[0.3em] rounded-2xl border border-white/60 transition-all active:scale-95 leading-none shadow-sm">
                        Edit Node
                    </a>
                    <form action="{{ route('servers.destroy', $server) }}" method="POST" onsubmit="return confirm('Initiate site decommissioning protocol?');" class="col-span-2">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full py-4 bg-rose-500/5 hover:bg-rose-500 hover:text-white text-rose-500 font-black text-[9px] uppercase tracking-[0.3em] rounded-2xl border border-rose-500/20 transition-all active:scale-95 leading-none group/delete mt-2">
                            Decommission Site Registry
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
            
            <!-- Strategic Expansion Slot -->
            <a href="{{ route('servers.create') }}" class="glass-panel border-4 border-dashed border-white/40 rounded-[3rem] p-12 flex flex-col items-center justify-center text-gray-400 hover:border-indigo-500/40 hover:text-indigo-600 hover:bg-indigo-50/20 transition-all duration-700 group min-h-[480px]">
                <div class="w-24 h-24 rounded-[2rem] bg-white/60 backdrop-blur-md flex items-center justify-center border border-white/80 shadow-2xl group-hover:scale-110 group-hover:rotate-12 transition-all duration-700 mb-8 relative">
                    <div class="absolute inset-0 bg-indigo-500/5 rounded-[2rem] animate-pulse"></div>
                    <svg class="w-12 h-12 relative z-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                </div>
                <span class="font-black text-sm uppercase tracking-[0.4em] text-center">Deploy New Site Registry Node</span>
            </a>
        </div>
    @else
        <!-- Vacant Fleet State -->
        <div class="glass-panel border border-white/40 rounded-[4rem] p-32 text-center relative overflow-hidden group">
            <div class="absolute -top-10 -right-10 w-64 h-64 bg-indigo-500/5 rounded-full blur-[120px] group-hover:bg-indigo-500/10 transition-all duration-1000"></div>
            <div class="w-32 h-32 bg-white/60 backdrop-blur-md rounded-[3rem] flex items-center justify-center mx-auto mb-12 border border-white/80 shadow-2xl group-hover:scale-110 transition-transform duration-700">
                <svg class="w-16 h-16 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/>
                </svg>
            </div>
            <h3 class="text-4xl font-black text-gray-900 tracking-tighter uppercase">Fleet Registry Empty</h3>
            <p class="text-[11px] text-gray-400 font-bold uppercase tracking-[0.3em] mt-4 mb-12 max-w-md mx-auto">No primary core nodes found. Register your first MikroTik gateway to initialize network governance.</p>
            <a href="{{ route('servers.create') }}" class="inline-flex items-center gap-4 px-14 py-6 bg-gray-900 text-white font-black text-[11px] uppercase tracking-[0.3em] rounded-[2rem] shadow-2xl hover:shadow-indigo-500/30 hover:bg-black transition-all hover:scale-105 active:scale-95 leading-none">
                Deploy Core Interface
            </a>
        </div>
    @endif
</div>

<script>
function serverGrid() {
    return {
        async testConnection(serverId) {
            const btn = event.currentTarget;
            const orb = document.getElementById(`status-orb-${serverId}`);
            const originalContent = btn.innerHTML;
            
            btn.disabled = true;
            btn.innerHTML = '<svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Initializing...';
            
            try {
                const response = await fetch(`/api/servers/${serverId}/test-connection`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                });
                const result = await response.json();
                
                if (result.success) {
                    orb.innerHTML = '<div class="w-12 h-12 bg-emerald-500/10 rounded-2xl flex items-center justify-center text-emerald-600 border border-emerald-500/20 shadow-[0_0_20px_rgba(16,185,129,0.2)] animate-in zoom-in duration-500"><svg class="w-6 h-6 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg></div>';
                    btn.innerHTML = '✅ Handshake Success';
                    btn.classList.remove('text-indigo-600');
                    btn.classList.add('text-emerald-600', 'border-emerald-500/40', 'bg-emerald-50/30');
                } else {
                    orb.innerHTML = '<div class="w-12 h-12 bg-rose-500/10 rounded-2xl flex items-center justify-center text-rose-500 border border-rose-500/20 shadow-[0_0_20px_rgba(244,63,94,0.2)] animate-in zoom-in duration-500"><svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg></div>';
                    btn.innerHTML = '❌ Link Failure';
                    btn.classList.remove('text-indigo-600');
                    btn.classList.add('text-rose-600', 'border-rose-500/40', 'bg-rose-50/30');
                    alert(result.message);
                }
            } catch (error) {
                console.error(error);
                btn.innerHTML = '⚠️ Engine Error';
            } finally {
                setTimeout(() => {
                    btn.disabled = false;
                    if (!btn.classList.contains('text-emerald-600') && !btn.classList.contains('text-rose-600')) {
                        btn.innerHTML = originalContent;
                    }
                }, 3000);
            }
        }
    }
}
</script>
@endsection
