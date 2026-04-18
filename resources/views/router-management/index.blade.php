@extends('layouts.admin')

@section('title', 'إدارة العقد المترابطة | Connected Asset Governance')

@section('content')
<div class="space-y-8">
    <!-- Governance Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h2 class="text-3xl font-black text-primary tracking-tight italic uppercase">إدارة العقد المترابطة</h2>
            <p class="text-slate-500 font-medium mt-1 uppercase tracking-widest text-[10px] font-headline">Automated Synchronization & Tunnel Governance for MikroTik Nodes</p>
        </div>
    </div>

    <!-- Active Registry Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($routers as $router)
        <div class="bg-surface-container-low border border-outline-variant/10 rounded-lg overflow-hidden shadow-sm flex flex-col group">
            
            <!-- Connection Identity -->
            <div class="p-6 border-b border-outline-variant/10 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded bg-primary text-white flex items-center justify-center shadow-lg shadow-primary/10 italic font-black text-xl">
                        {{ substr($router->name, 0, 1) }}
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-primary uppercase italic truncate max-w-[120px]">{{ $router->name }}</h3>
                        <p class="text-[9px] font-manrope font-black text-slate-400 uppercase tracking-tighter">{{ $router->host }}</p>
                    </div>
                </div>
                
                @if($router->is_connected)
                    <div class="px-3 py-1 bg-secondary/5 text-secondary border border-secondary/10 rounded text-[9px] font-black uppercase tracking-widest flex items-center gap-2 italic">
                        <span class="w-1.5 h-1.5 bg-secondary rounded-full animate-pulse"></span>
                        Active Sync
                    </div>
                @else
                    <div class="px-3 py-1 bg-slate-100 text-slate-400 border border-slate-200 rounded text-[9px] font-black uppercase tracking-widest italic">
                        Offline State
                    </div>
                @endif
            </div>

            <!-- Handshake Metrics -->
            <div class="p-6 space-y-4 flex-1">
                @if($router->vpn_ip)
                <div class="flex items-center justify-between">
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">VPN Architecture IP</span>
                    <span class="font-manrope font-black text-primary text-[10px] italic">{{ $router->vpn_ip }}</span>
                </div>
                @endif

                @if($router->tower)
                <div class="flex items-center justify-between">
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Structural Tower Port</span>
                    <span class="font-black text-primary text-[10px] italic uppercase">{{ $router->tower->name }}</span>
                </div>
                @endif

                <div class="flex items-center justify-between">
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Last Protocol Sync</span>
                    <span class="font-black text-slate-600 text-[9px] uppercase italic">
                        {{ $router->last_sync ? $router->last_sync->diffForHumans() : 'INIT PENDING' }}
                    </span>
                </div>
            </div>

            <!-- Authority Trigger -->
            <div class="p-5 bg-white/40 border-t border-outline-variant/5">
                <a href="{{ route('router-management.script', $router) }}" class="w-full py-3.5 bg-primary text-white text-center font-black text-[10px] uppercase tracking-[0.2em] rounded shadow-xl shadow-primary/10 hover:scale-[1.02] active:scale-[0.98] transition-all block">
                    {{ $router->is_connected ? '🔄 Update Protocol Script' : '🔗 Generate Handshake Script' }}
                </a>
            </div>
        </div>
        @empty
        <div class="col-span-full py-24 flex flex-col items-center justify-center gap-6 bg-surface-container-low border border-dashed border-outline-variant/20 rounded-lg opacity-40">
            <span class="material-symbols-outlined text-5xl font-light">account_tree</span>
            <div class="text-center">
                <p class="text-[10px] font-black uppercase tracking-widest italic">No Interconnected Nodes Found</p>
                <a href="{{ route('routers.create') }}" class="text-[9px] font-bold text-primary uppercase underline mt-2 block">Initialize your first node registry</a>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Sovereign Instruction Blueprint -->
    <div class="bg-slate-900 text-white rounded-lg p-10 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-primary/20 rounded-full blur-3xl -mr-32 -mt-32"></div>
        
        <div class="relative z-10 space-y-8">
            <div class="flex items-center gap-4">
                <span class="material-symbols-outlined text-secondary text-2xl">terminal</span>
                <h3 class="text-lg font-black uppercase italic tracking-widest">Node Interconnection Protocol</h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-16">
                <div class="space-y-6">
                    <div class="flex items-start gap-5">
                        <span class="w-6 h-6 rounded bg-white/10 flex items-center justify-center text-[10px] font-black">01</span>
                        <p class="text-[11px] font-bold text-slate-300 leading-relaxed uppercase tracking-tight italic">Generate the unique "Handshake Script" for your target hardware using the dashboard controls above.</p>
                    </div>
                    <div class="flex items-start gap-5">
                        <span class="w-6 h-6 rounded bg-white/10 flex items-center justify-center text-[10px] font-black">02</span>
                        <p class="text-[11px] font-bold text-slate-300 leading-relaxed uppercase tracking-tight italic">Securely copy the generated protocol block to your authorized administrative terminal.</p>
                    </div>
                </div>
                <div class="space-y-6">
                    <div class="flex items-start gap-5">
                        <span class="w-6 h-6 rounded bg-white/10 flex items-center justify-center text-[10px] font-black">03</span>
                        <p class="text-[11px] font-bold text-slate-300 leading-relaxed uppercase tracking-tight italic">Inject the script into the MikroTik "New Terminal" environment to establish the encrypted tunnel.</p>
                    </div>
                    <div class="flex items-start gap-5">
                        <span class="w-6 h-6 rounded bg-white/10 flex items-center justify-center text-[10px] font-black">04</span>
                        <p class="text-[11px] font-bold text-slate-300 leading-relaxed uppercase tracking-tight italic">Autonomous synchronization will initialize instantly, polling telemetry data every 15 seconds.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
