@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-12">
        <div class="relative">
            <div class="absolute -top-4 -right-4 w-24 h-24 bg-indigo-500/10 rounded-full blur-2xl -z-10"></div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">إدارة مراكز الاتصال (Core Nodes)</h1>
            <p class="text-xs font-bold text-indigo-600 uppercase tracking-widest mt-1 flex items-center gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-indigo-600 animate-pulse shadow-[0_0_8px_rgba(79,70,229,0.5)]"></span>
                Primary Network Servers Management
            </p>
        </div>
        @if($servers->count() > 0)
        <a href="{{ route('servers.create') }}" class="inline-flex items-center gap-3 px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-black rounded-2xl shadow-xl shadow-indigo-200/50 transition-all transform hover:scale-105 active:scale-95 group">
            <svg class="w-5 h-5 group-hover:rotate-90 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            <span>إضافة سيرفر جديد</span>
        </a>
        @endif
    </div>

    @if(session('success'))
    <div class="glass-panel border-green-500/20 bg-green-50/10 backdrop-blur-xl p-5 rounded-2xl mb-8 flex items-center gap-4 transition-all animate-fade-in-up">
        <div class="w-10 h-10 rounded-xl bg-green-500/20 flex items-center justify-center text-green-600">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <p class="text-sm font-black text-green-800 tracking-tight">{{ session('success') }}</p>
    </div>
    @endif

    @if(session('info'))
    <div class="glass-panel border-indigo-500/20 bg-indigo-50/10 backdrop-blur-xl p-5 rounded-2xl mb-8 flex items-center gap-4 transition-all animate-fade-in-up">
        <div class="w-10 h-10 rounded-xl bg-indigo-500/20 flex items-center justify-center text-indigo-600">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <p class="text-sm font-black text-indigo-800 tracking-tight">{{ session('info') }}</p>
    </div>
    @endif

    @if($servers->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
            @foreach($servers as $server)
            <div class="glass-panel rounded-[2rem] hover:shadow-2xl hover:shadow-indigo-500/10 transition-all duration-500 hover:translate-y-[-8px] group relative overflow-hidden flex flex-col">
                <div class="absolute -top-20 -right-20 w-40 h-40 bg-indigo-500/5 rounded-full blur-[80px]"></div>
                
                <div class="p-8 pb-6 border-b border-white/10 flex-grow">
                    <div class="flex items-start justify-between mb-8">
                        <div class="flex items-center gap-5">
                            <div class="w-20 h-20 bg-white/50 backdrop-blur-md rounded-[1.75rem] border border-white/40 shadow-lg p-3 flex items-center justify-center group-hover:scale-110 group-hover:-rotate-3 transition-all duration-500">
                                <img src="/images/devices/mikrotik_ccr1009.png" alt="Server" class="max-w-full max-h-full object-contain filter grayscale group-hover:grayscale-0 transition-all"
                                     onerror="this.src='https://cdn-icons-png.flaticon.com/512/9637/9637409.png'">
                            </div>
                            <div class="min-w-0">
                                <h3 class="text-xl font-black text-gray-900 tracking-tight line-clamp-1 mb-1">{{ $server->name }}</h3>
                                <div class="bg-gray-900/5 px-2 py-1 rounded-lg inline-block border border-gray-900/5">
                                    <p class="text-[10px] font-black font-mono text-gray-500 leading-none uppercase">{{ $server->ip }}:{{ $server->api_port }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="relative">
                           {!! $server->status_badge !!}
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 bg-white/40 backdrop-blur-sm rounded-2xl border border-white/40 group-hover:bg-white/60 transition-colors">
                            <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Username Access</span>
                            <span class="text-xs font-black text-gray-800 tracking-tight">{{ $server->username }}</span>
                        </div>
                        <div class="flex items-center justify-between p-4 bg-white/40 backdrop-blur-sm rounded-2xl border border-white/40 group-hover:bg-white/60 transition-colors">
                            <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Physical Location</span>
                            <span class="text-xs font-black text-indigo-600 tracking-tight">📍 {{ $server->location ?? 'Cloud DC' }}</span>
                        </div>
                    </div>
                </div>

                <div class="p-8 pt-6 grid grid-cols-2 gap-4 bg-white/20 backdrop-blur-xl border-t border-white/10">
                    <a href="{{ route('servers.show', $server) }}" class="px-4 py-3 bg-gray-900 hover:bg-black text-white text-center font-black text-[11px] uppercase tracking-widest rounded-2xl shadow-xl hover:shadow-gray-900/20 transition-all active:scale-95">
                        Network Console
                    </a>
                    <a href="{{ route('servers.edit', $server) }}" class="px-4 py-3 bg-white/50 hover:bg-white text-indigo-600 text-center font-black text-[11px] uppercase tracking-widest rounded-2xl border border-white/50 transition-all active:scale-95">
                        Edit node
                    </a>
                    <form action="{{ route('servers.destroy', $server) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا السيرفر؟');" class="col-span-2 mt-2">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full py-3 bg-red-50/50 hover:bg-red-50 text-red-500 font-extrabold text-[10px] uppercase tracking-[0.2em] rounded-xl transition-all border border-red-100/50">
                            Decommission Site
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
            
            <!-- Add New Card -->
            <a href="{{ route('servers.create') }}" class="glass-panel border-2 border-dashed border-white/30 rounded-[2rem] p-12 flex flex-col items-center justify-center text-gray-400 hover:border-indigo-500/50 hover:text-indigo-600 hover:bg-indigo-50/20 transition-all duration-500 group min-h-[400px]">
                <div class="w-20 h-20 rounded-[1.75rem] bg-white/50 backdrop-blur-md flex items-center justify-center border border-white/30 shadow-lg group-hover:scale-110 group-hover:rotate-12 transition-all duration-500 mb-6">
                    <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                </div>
                <span class="font-black text-sm uppercase tracking-[0.3em]">Deploy New Node</span>
            </a>
        </div>
    @else
        <!-- No Servers State -->
        <div class="glass-panel rounded-[3rem] p-24 text-center relative overflow-hidden group">
            <div class="absolute -top-10 -right-10 w-48 h-48 bg-indigo-500/5 rounded-full blur-3xl group-hover:bg-indigo-500/10 transition-all duration-700"></div>
            <div class="w-24 h-24 bg-white/50 backdrop-blur-md rounded-[2.5rem] flex items-center justify-center mx-auto mb-10 border border-white/30 shadow-xl group-hover:scale-110 transition-transform duration-500">
                <svg class="w-12 h-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/>
                </svg>
            </div>
            <h3 class="text-3xl font-black text-gray-900 tracking-tight">No Management Servers found</h3>
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-[0.2em] mt-3 mb-10">Add your primary MikroTik gateway to begin managing radius clients</p>
            <a href="{{ route('servers.create') }}" class="inline-flex items-center gap-3 px-12 py-4 bg-gray-900 hover:bg-black text-white font-black text-[11px] uppercase tracking-widest rounded-2xl shadow-xl hover:shadow-gray-900/30 transition-all active:scale-95">
                Deploy First Node
            </a>
        </div>
    @endif

</div>

<script>
function testConnection() {
    // Will be implemented
    alert('سيتم تنفيذ هذه الميزة قريباً');
}
</script>
@endsection
