@extends('layouts.admin')

@section('title', 'أسطول العقد المركزية | Network Nodes')

@section('content')
<div class="max-w-7xl mx-auto space-y-8" x-data="serverGrid()">
    
    <!-- Infrastructure Fleet Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h2 class="text-3xl font-black text-primary tracking-tight">إدارة أسطول العقد المركزية</h2>
            <p class="text-slate-500 font-medium mt-1 uppercase tracking-widest text-[10px] font-headline">MikroTik Core Infrastructure Control</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('servers.create') }}" class="px-8 py-2.5 bg-primary text-white font-bold rounded-lg text-sm shadow-lg shadow-primary/10 hover:scale-[1.02] transition-all flex items-center gap-2">
                <span class="material-symbols-outlined text-sm">add</span>
                نشر عقدة جديدة
            </a>
        </div>
    </div>

    <!-- Stats Snapshot -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-surface-container-low p-5 rounded-lg border border-outline-variant/10 flex items-center gap-4">
            <div class="w-10 h-10 rounded bg-primary/5 flex items-center justify-center text-primary">
                <span class="material-symbols-outlined">dns</span>
            </div>
            <div>
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">إجمالي العقد</p>
                <p class="text-xl font-manrope font-black text-primary">{{ $servers->count() }}</p>
            </div>
        </div>
        <div class="bg-surface-container-low p-5 rounded-lg border border-outline-variant/10 flex items-center gap-4">
            <div class="w-10 h-10 rounded bg-secondary/10 flex items-center justify-center text-secondary">
                <span class="material-symbols-outlined">check_circle</span>
            </div>
            <div>
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">عقد متصلة</p>
                <p class="text-xl font-manrope font-black text-secondary">{{ $servers->where('connection_status', 'connected')->count() }}</p>
            </div>
        </div>
        <div class="bg-surface-container-low p-5 rounded-lg border border-outline-variant/10 flex items-center gap-4">
            <div class="w-10 h-10 rounded bg-error/10 flex items-center justify-center text-error">
                <span class="material-symbols-outlined">report</span>
            </div>
            <div>
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">أعطال الاتصال</p>
                <p class="text-xl font-manrope font-black text-error">{{ $servers->where('connection_status', 'error')->count() }}</p>
            </div>
        </div>
        <div class="bg-surface-container-low p-5 rounded-lg border border-outline-variant/10 flex items-center gap-4">
            <div class="w-10 h-10 rounded bg-surface-container-highest flex items-center justify-center text-slate-500">
                <span class="material-symbols-outlined">router</span>
            </div>
            <div>
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">إجمالي المشتركين</p>
                <p class="text-xl font-manrope font-black text-primary">--</p>
            </div>
        </div>
    </div>

    <!-- Active Node Matrix -->
    @if($servers->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($servers as $server)
            <div class="bg-surface-container-lowest rounded-lg border border-outline-variant/10 overflow-hidden flex flex-col group hover:shadow-xl hover:shadow-primary/5 transition-all duration-500">
                <!-- Node Identity Header -->
                <div class="p-6 border-b border-outline-variant/10 bg-surface-container-low/30 relative">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 bg-white rounded border border-outline-variant/10 p-2 flex items-center justify-center shadow-sm">
                                <img src="{{ $server->deviceModel->image_url ?? '/images/devices/mikrotik_node.png' }}" 
                                     class="max-w-full max-h-full object-contain grayscale group-hover:grayscale-0 transition-all duration-500"
                                     onerror="this.src='https://placehold.co/150x100?text=Node'">
                            </div>
                            <div class="min-w-0">
                                <h3 class="text-lg font-black text-primary tracking-tight truncate uppercase italic">{{ $server->name }}</h3>
                                <p class="text-[10px] font-manrope font-bold text-slate-400 flex items-center gap-2 mt-0.5">
                                    <span class="material-symbols-outlined text-[12px]">podcasts</span>
                                    {{ $server->ip }}
                                </p>
                            </div>
                        </div>
                        
                        <!-- Status Indicator -->
                        <div id="status-orb-{{ $server->id }}">
                            @if($server->connection_status === 'connected')
                                <div class="w-8 h-8 bg-secondary/10 rounded-lg flex items-center justify-center text-secondary border border-secondary/20 shadow-sm animate-pulse">
                                    <span class="material-symbols-outlined text-sm">check_circle</span>
                                </div>
                            @elseif($server->connection_status === 'error')
                                <div class="w-8 h-8 bg-error/10 rounded-lg flex items-center justify-center text-error border border-error/20 shadow-sm animate-bounce">
                                    <span class="material-symbols-outlined text-sm">warning</span>
                                </div>
                            @else
                                <div class="w-8 h-8 bg-surface-container-highest rounded-lg flex items-center justify-center text-slate-400 border border-outline-variant/10 shadow-sm">
                                    <span class="material-symbols-outlined text-sm">link_off</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Telemetry Metrics -->
                <div class="p-6 flex-1 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-3 bg-surface-container-low rounded border border-outline-variant/10">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Site Location</p>
                            <p class="text-xs font-bold text-primary truncate">📍 {{ $server->location ?? 'Headquarters' }}</p>
                        </div>
                        <div class="p-3 bg-surface-container-low rounded border border-outline-variant/10">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Uplink Account</p>
                            <p class="text-xs font-manrope font-bold text-primary truncate">{{ $server->username }}</p>
                        </div>
                    </div>

                    <!-- Actions Bar -->
                    <div class="flex items-center gap-3 pt-2">
                        <button @click="testConnection({{ $server->id }}, $el)" class="flex-1 py-2.5 bg-surface-container-highest/20 hover:bg-primary/5 text-primary font-black text-[10px] uppercase tracking-widest rounded transition-all flex items-center justify-center gap-2 border border-outline-variant/5">
                            <span class="material-symbols-outlined text-[16px]">sensors</span>
                            Test Handshake
                        </button>
                    </div>
                </div>

                <!-- Command Control Console -->
                <div class="p-4 bg-surface-container-low/50 border-t border-outline-variant/10 grid grid-cols-2 gap-3 items-center">
                    <a href="{{ route('servers.show', $server) }}" class="px-3 py-3 bg-primary text-white text-center font-black text-[10px] uppercase tracking-widest rounded shadow-lg shadow-primary/10 hover:scale-[1.02] active:scale-95 transition-all">
                        Node Console
                    </a>
                    <div class="flex gap-2">
                        <a href="{{ route('servers.edit', $server) }}" class="flex-1 px-3 py-3 bg-white border border-outline-variant/20 text-slate-500 text-center font-black text-[10px] uppercase tracking-widest rounded hover:bg-slate-50 transition-all">
                            Config
                        </a>
                        <form action="{{ route('servers.destroy', $server) }}" method="POST" onsubmit="return confirm('تأكيد تفكيك العقدة من النظام؟');" class="flex-1">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full px-3 py-3 bg-error-container text-on-error-container font-black text-[10px] uppercase tracking-widest rounded border border-outline-variant/10 hover:bg-error hover:text-white transition-all">
                                <span class="material-symbols-outlined text-[14px]">delete</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach

            <!-- Deploy Slot -->
            <a href="{{ route('servers.create') }}" class="group bg-surface-container-low border-2 border-dashed border-outline-variant/20 rounded-lg p-8 flex flex-col items-center justify-center text-slate-400 hover:border-primary/50 hover:bg-primary/5 transition-all min-h-[380px]">
                <div class="w-16 h-16 rounded-lg bg-white border border-outline-variant/10 flex items-center justify-center text-primary mb-6 shadow-sm group-hover:scale-110 transition-all">
                    <span class="material-symbols-outlined text-4xl">add_circle</span>
                </div>
                <span class="text-sm font-black text-primary uppercase tracking-[0.2em] italic">Deploy Cluster Node</span>
                <p class="text-[10px] font-bold text-slate-400 mt-2 uppercase tracking-widest">Register Hardware Instance</p>
            </a>
        </div>
    @else
        <!-- Fleet Vacant Message -->
        <div class="bg-surface-container-low p-20 rounded-lg border border-outline-variant/10 text-center">
            <div class="w-24 h-24 bg-white rounded-lg flex items-center justify-center mx-auto mb-8 border border-outline-variant/10 text-slate-200">
                <span class="material-symbols-outlined text-6xl">grid_view</span>
            </div>
            <h3 class="text-2xl font-black text-primary italic uppercase tracking-tighter">Fleet Registry Empty</h3>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-4 mb-8 max-w-sm mx-auto leading-relaxed">لم يتم العثور على عقد شبكة مركزية. ابدأ بربط أول راوتر ميكروتيك لتفعيل لوحة التحكم.</p>
            <a href="{{ route('servers.create') }}" class="inline-block px-10 py-3 bg-primary text-white font-black text-xs uppercase tracking-[0.2em] rounded shadow-xl shadow-primary/10">
                Deploy Primary Interface
            </a>
        </div>
    @endif
</div>

<script>
function serverGrid() {
    return {
        async testConnection(serverId, btn) {
            const orb = document.getElementById(`status-orb-${serverId}`);
            const originalContent = btn.innerHTML;
            
            btn.disabled = true;
            btn.innerHTML = '<span class="material-symbols-outlined animate-spin text-sm">progress_activity</span> Syncing...';
            
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
                    orb.innerHTML = '<div class="w-8 h-8 bg-secondary/20 rounded-lg flex items-center justify-center text-secondary border border-secondary/30"><span class="material-symbols-outlined text-sm">check_circle</span></div>';
                    btn.innerHTML = '<span class="material-symbols-outlined text-sm">task_alt</span> Handshake Ready';
                    btn.classList.add('bg-secondary/10', 'text-secondary');
                } else {
                    orb.innerHTML = '<div class="w-8 h-8 bg-error/20 rounded-lg flex items-center justify-center text-error border border-error/30"><span class="material-symbols-outlined text-sm">warning</span></div>';
                    btn.innerHTML = '<span class="material-symbols-outlined text-sm">signal_disconnected</span> Link Loss';
                    btn.classList.add('bg-error/10', 'text-error');
                    alert(result.message);
                }
            } catch (error) {
                btn.innerHTML = '⚠️ Engine Failure';
            } finally {
                setTimeout(() => {
                    btn.disabled = false;
                    btn.innerHTML = originalContent;
                    btn.classList.remove('bg-secondary/10', 'text-secondary', 'bg-error/10', 'text-error');
                }, 4000);
            }
        }
    }
}
</script>
@endsection
