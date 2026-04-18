@extends('layouts.admin')

@section('title', 'أجهزة الشبكة | Network Fleet')

@section('content')
<div class="space-y-8">
    <!-- Infrastructure Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h2 class="text-3xl font-black text-primary tracking-tight italic uppercase text-right">أجهزة الشبكة العالمية</h2>
            <p class="text-slate-500 font-medium mt-1 uppercase tracking-widest text-[10px] font-headline text-right">Global Registry of Active Routing Nodes & Access Points</p>
        </div>
        <a href="{{ route('routers.create') }}" class="px-8 py-2.5 bg-primary text-white font-bold rounded text-sm shadow-lg shadow-primary/10 hover:scale-[1.05] transition-all flex items-center gap-2 italic">
            <span class="material-symbols-outlined text-sm">add_circle</span>
            إضافة جهاز جديد
        </a>
    </div>

    <!-- Protocol Status -->
    @if(session('success'))
    <div class="bg-secondary/10 border border-secondary/20 p-5 rounded-lg flex items-center gap-4 animate-in fade-in slide-in-from-top-4">
        <div class="w-8 h-8 bg-secondary rounded flex items-center justify-center text-white shadow-lg">
            <span class="material-symbols-outlined text-sm">check_circle</span>
        </div>
        <p class="text-secondary font-black text-xs uppercase italic">{{ session('success') }}</p>
    </div>
    @endif

    <!-- Core Governance Nodes (Servers) -->
    @if($servers->count() > 0)
    <div class="space-y-6">
        <h3 class="text-sm font-black text-primary uppercase tracking-widest italic flex items-center gap-2">
            <span class="material-symbols-outlined text-lg">dns</span>
            سيرفرات الإدارة المركزية (Management Cores)
        </h3>
        <div class="bg-surface-container-low border border-outline-variant/10 rounded-lg overflow-hidden shadow-sm">
            <table class="w-full text-right">
                <thead class="bg-primary/5 text-primary border-b border-outline-variant/10">
                    <tr>
                        <th class="px-6 py-4 text-[9px] font-black uppercase tracking-widest">Node Identity</th>
                        <th class="px-6 py-4 text-[9px] font-black uppercase tracking-widest">Network IP</th>
                        <th class="px-6 py-4 text-[9px] font-black uppercase tracking-widest text-center">Protocol State</th>
                        <th class="px-6 py-4 text-[9px] font-black uppercase tracking-widest text-left">Control Handshake</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/5">
                    @foreach($servers as $server)
                    <tr class="hover:bg-white/40 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-surface-container-highest/20 rounded-lg flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-all">
                                    <span class="material-symbols-outlined text-lg">memory</span>
                                </div>
                                <div>
                                    <div class="font-black text-primary text-xs uppercase italic">{{ $server->name }}</div>
                                    <div class="text-[9px] font-bold text-slate-400 font-manrope">v{{ $server->version ?? 'OPERATIONAL' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-manrope font-black text-slate-500 text-[10px] tracking-tight">{{ $server->ip }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-3 py-1 bg-secondary/5 text-secondary border border-secondary/10 rounded text-[9px] font-black uppercase tracking-widest italic">
                                Active Sync
                            </span>
                        </td>
                        <td class="px-6 py-4 text-left">
                            <div class="flex items-center justify-end gap-4">
                                <a href="{{ route('servers.show', $server->id) }}" class="text-primary hover:text-secondary font-black text-[10px] uppercase tracking-widest italic transition-all underline decoration-primary/20 hover:decoration-secondary">Manage Hub</a>
                                <form action="{{ route('servers.destroy', $server->id) }}" method="POST" onsubmit="return confirm('Confirm Core Decommissioning?')" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-slate-300 hover:text-error transition-colors">
                                        <span class="material-symbols-outlined text-sm">delete_forever</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Peripheral Device Matrix (Routers & APs) -->
    <div class="space-y-6">
        <h3 class="text-sm font-black text-primary uppercase tracking-widest italic flex items-center gap-2">
            <span class="material-symbols-outlined text-lg">router</span>
            مصفوفة أجهزة التوزيع (Distribution Matrix)
        </h3>
        <div class="bg-surface-container-low border border-outline-variant/10 rounded-lg overflow-hidden shadow-sm">
            <table class="w-full text-right">
                <thead class="bg-slate-900 text-white">
                    <tr>
                        <th class="px-6 py-4 text-[9px] font-black uppercase tracking-widest">Peripheral Identity</th>
                        <th class="px-6 py-4 text-[9px] font-black uppercase tracking-widest">Network IP</th>
                        <th class="px-6 py-4 text-[9px] font-black uppercase tracking-widest">Category</th>
                        <th class="px-6 py-4 text-[9px] font-black uppercase tracking-widest text-center">Topology</th>
                        <th class="px-6 py-4 text-[9px] font-black uppercase tracking-widest text-left">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/5">
                    @forelse($routers as $router)
                    <tr class="hover:bg-white/40 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-primary/5 rounded-lg flex items-center justify-center text-primary border border-primary/5 shadow-sm">
                                    <span class="material-symbols-outlined text-lg">sensors</span>
                                </div>
                                <div>
                                    <div class="font-black text-primary text-xs uppercase italic">{{ $router->name }}</div>
                                    @if($router->deviceModel)
                                        <div class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">{{ $router->deviceModel->manufacturer }} {{ $router->deviceModel->model_name }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-manrope font-black text-slate-500 text-[10px] tracking-tight bg-slate-100/50 px-2 py-1 rounded">{{ $router->ip }}</span>
                        </td>
                        <td class="px-6 py-4">
                            @if($router->device_type === 'router')
                                <span class="px-2 py-0.5 bg-primary/5 text-primary border border-primary/10 rounded text-[8px] font-black uppercase italic">Gateway</span>
                            @elseif($router->device_type === 'access_point')
                                <span class="px-2 py-0.5 bg-secondary/5 text-secondary border border-secondary/10 rounded text-[8px] font-black uppercase italic">Access Pt</span>
                            @else
                                <span class="px-2 py-0.5 bg-slate-100 text-slate-500 border border-slate-200 rounded text-[8px] font-black uppercase italic">Station</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($router->lat && $router->lng)
                                <span class="material-symbols-outlined text-secondary text-sm">location_on</span>
                            @else
                                <span class="material-symbols-outlined text-slate-200 text-sm">location_off</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-left">
                            <div class="flex items-center justify-end gap-3">
                                @php
                                    $isMikroTik = $router->deviceModel && (stripos($router->deviceModel->manufacturer, 'MikroTik') !== false);
                                @endphp
                                
                                @if($isMikroTik || !$router->deviceModel)
                                    <a href="{{ route('routers.webfig', $router->id) }}" target="_blank" class="px-3 py-1 bg-emerald-500 text-white font-black text-[9px] uppercase tracking-widest rounded shadow-sm hover:bg-emerald-600 transition-all italic">WebFig</a>
                                @endif

                                <a href="{{ route('routers.edit', $router->id) }}" class="text-primary hover:text-secondary p-1 transition-colors">
                                    <span class="material-symbols-outlined text-sm">edit</span>
                                </a>
                                <button onclick="showScript({{ $router->id }})" class="text-slate-400 hover:text-primary p-1 transition-colors">
                                    <span class="material-symbols-outlined text-sm">terminal</span>
                                </button>
                                <form action="{{ route('routers.destroy', $router->id) }}" method="POST" onsubmit="return confirm('Confirms Device Removal Index?')" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-slate-200 hover:text-error transition-colors p-1">
                                        <span class="material-symbols-outlined text-sm">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-24 text-center">
                            <div class="flex flex-col items-center gap-4 opacity-30">
                                <span class="material-symbols-outlined text-5xl font-light">router</span>
                                <p class="text-[10px] font-black uppercase tracking-widest italic">Registry Void: No Distributions Active</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Terminal Script Overlay -->
<div id="scriptModal" class="hidden fixed inset-0 bg-slate-900/80 backdrop-blur-sm z-[100] flex items-center justify-center p-6" onclick="hideScript()">
    <div class="bg-white rounded-lg shadow-2xl max-w-2xl w-full overflow-hidden border border-outline-variant/20" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between p-6 bg-slate-50 border-b border-outline-variant/10">
            <h3 class="text-sm font-black text-primary uppercase tracking-widest italic">MikroTik Sovereign Script</h3>
            <button onclick="hideScript()" class="text-slate-400 hover:text-primary transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div class="p-8 space-y-6">
            <div class="bg-slate-900 rounded p-6 font-manrope text-xs text-emerald-400/90 leading-relaxed overflow-x-auto border border-white/5" dir="ltr">
                <pre id="scriptContent" class="whitespace-pre-wrap"></pre>
            </div>
            <button onclick="copyScript()" class="w-full py-4 bg-primary text-white font-black text-xs uppercase tracking-[0.3em] rounded shadow-xl shadow-primary/10 hover:scale-[1.01] active:scale-[0.98] transition-all">
                Copy Protocol to Clipboard
            </button>
        </div>
    </div>
</div>

<script>
function showScript(routerId) {
    fetch(`/routers/${routerId}/script`)
        .then(res => res.text())
        .then(script => {
            document.getElementById('scriptContent').textContent = script;
            document.getElementById('scriptModal').classList.remove('hidden');
        });
}

function hideScript() {
    document.getElementById('scriptModal').classList.add('hidden');
}

function copyScript() {
    const script = document.getElementById('scriptContent').textContent;
    navigator.clipboard.writeText(script).then(() => {
        alert('Protocol Copied Successfully');
    });
}
</script>
@endsection
