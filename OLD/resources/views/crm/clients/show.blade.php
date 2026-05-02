@extends('layouts.admin')

@section('title', 'ملف المشترك | ' . $client->username)

@section('content')
<div class="space-y-12 pb-24">
    <!-- Radiant Command Header -->
    <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-8 animate-enter">
        <div class="flex items-center gap-8">
            <a href="{{ route('crm.clients.index') }}" class="w-14 h-14 rounded-2xl bg-white border border-slate-100 flex items-center justify-center text-slate-400 hover:bg-slate-900 hover:text-white transition-all shadow-sm hover:translate-x-[-5px]">
                <span class="material-symbols-outlined text-2xl">arrow_right_alt</span>
            </a>
            <div class="space-y-2">
                <div class="flex items-center gap-4">
                    <h2 class="text-4xl font-black text-slate-900 tracking-tighter italic uppercase">{{ $client->username }}</h2>
                    @if($client->status === 'active')
                        <span class="px-4 py-1.5 rounded-full text-[9px] font-black bg-emerald-500/10 text-emerald-600 uppercase tracking-widest border border-emerald-500/20 shadow-glow-cyan flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse shadow-glow-cyan"></span>
                            Synchronized Node
                        </span>
                    @else
                        <span class="px-4 py-1.5 rounded-full text-[9px] font-black bg-rose-500/10 text-rose-600 uppercase tracking-widest border border-rose-500/20 shadow-glow-purple flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-rose-500 shadow-glow-purple"></span>
                            Terminated Signal
                        </span>
                    @endif
                </div>
                <p class="text-slate-400 font-bold uppercase tracking-widest text-[11px] font-headline opacity-80 italic">
                    {{ $client->name ?? 'Identity Protocol Alpha' }} | MATRIX ID: #{{ $client->id }}
                </p>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-4">
            <a href="{{ route('crm.clients.renew', $client) }}" class="px-8 py-4 bg-slate-900 text-white font-black rounded-2xl text-[11px] uppercase tracking-[0.2em] shadow-glow-purple hover:scale-[1.05] active:scale-[0.95] transition-all flex items-center gap-4 italic group">
                <span class="material-symbols-outlined text-sm group-hover:rotate-180 transition-transform duration-500">autorenew</span>
                بروتوكول تجديد الفاتورة
            </a>

            <div class="h-10 w-px bg-slate-200 mx-2 hidden lg:block"></div>

            <form action="{{ route('crm.clients.toggle-status', $client) }}" method="POST" class="inline-block">
                @csrf
                @method('PATCH')
                <button type="submit" class="px-6 py-4 {{ $client->status === 'active' ? 'bg-rose-500/10 text-rose-600 border-rose-500/20' : 'bg-emerald-500/10 text-emerald-600 border-emerald-500/20' }} font-black text-[10px] uppercase tracking-widest rounded-2xl transition-all border flex items-center gap-3 italic hover:scale-105" 
                        onclick="return confirm('IDENTITY PROTOCOL: Confirming status mutation for this node. Proceed?')">
                    <span class="material-symbols-outlined text-lg">{{ $client->status === 'active' ? 'block' : 'task_alt' }}</span>
                    {{ $client->status === 'active' ? 'Disable Registry' : 'Activate Registry' }}
                </button>
            </form>

            <a href="{{ route('crm.clients.edit', $client) }}" class="w-14 h-14 bg-white border border-slate-100 rounded-2xl text-slate-400 hover:text-primary flex items-center justify-center shadow-sm hover:bg-slate-50 transition-all italic">
                <span class="material-symbols-outlined text-2xl">edit_square</span>
            </a>
        </div>
    </div>

    <!-- Radiant Metric Dashboard -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
        <!-- Fiscal Documents -->
        <div class="glass-card p-8 rounded-[2rem] relative overflow-hidden group border-b-4 border-slate-900/20">
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-slate-900/5 rounded-full blur-2xl group-hover:scale-150 transition-transform"></div>
            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-3 font-headline italic">إجمالي الوثائق المالية</p>
            <div class="flex items-baseline gap-2">
                <h4 class="text-4xl font-manrope font-black text-slate-900 italic tracking-tighter">{{ number_format($stats['total_invoices']) }}</h4>
                <span class="text-[10px] font-black text-slate-300 uppercase italic">DOCS</span>
            </div>
        </div>

        <!-- Total Emissions -->
        <div class="glass-card p-8 rounded-[2rem] relative overflow-hidden group border-b-4 border-emerald-500/20">
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-emerald-500/5 rounded-full blur-2xl group-hover:scale-150 transition-transform"></div>
            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-3 font-headline italic">إجمالي المبالغ المسددة</p>
            <div class="flex items-baseline gap-2">
                <h4 class="text-4xl font-manrope font-black text-emerald-600 italic tracking-tighter">{{ number_format($stats['total_paid']) }}</h4>
                <span class="text-[10px] font-black text-slate-300 uppercase italic">SAR</span>
            </div>
        </div>

        <!-- Pending Burden -->
        <div class="glass-card p-8 rounded-[2rem] relative overflow-hidden group border-b-4 border-rose-500/20">
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-rose-500/5 rounded-full blur-2xl group-hover:scale-150 transition-transform"></div>
            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-3 font-headline italic">الرصيد المعلق (Pending)</p>
            <div class="flex items-baseline gap-2">
                <h4 class="text-4xl font-manrope font-black text-rose-500 italic tracking-tighter">{{ number_format($stats['pending_amount']) }}</h4>
                <span class="text-[10px] font-black text-slate-300 uppercase italic">SAR</span>
            </div>
        </div>

        <!-- Termination Threshold -->
        <div class="glass-card p-8 rounded-[2rem] relative overflow-hidden group border-b-4 border-primary/20">
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-primary/5 rounded-full blur-2xl group-hover:scale-150 transition-transform"></div>
            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-3 font-headline italic">نهاية التعاقد الحالي</p>
            <h4 class="text-xl font-manrope font-black text-slate-900 italic tracking-tighter uppercase">
                {{ $client->expires_at ? $client->expires_at->format('Y . m . d') : 'PERMANENT SIGNAL' }}
            </h4>
        </div>
    </div>

    <!-- Main Command Hub Matrix -->
    <div class="grid grid-cols-12 gap-10">
        <!-- Center Information Grid -->
        <div class="col-span-12 lg:col-span-8 space-y-10">
            
            <!-- Identity Shard Matrix -->
            <div class="glass-panel p-10 rounded-[3rem] !bg-white/80 border-slate-100 relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-64 h-64 bg-primary/5 rounded-full -mr-32 -mt-32 blur-3xl group-hover:scale-150 transition-transform duration-1000"></div>
                
                <div class="flex items-center gap-6 mb-12">
                    <div class="w-14 h-14 bg-primary/10 rounded-2xl flex items-center justify-center text-primary shadow-inner border border-primary/20">
                        <span class="material-symbols-outlined text-3xl">fingerprint</span>
                    </div>
                    <div class="space-y-1">
                        <h3 class="text-xl font-black text-slate-900 uppercase italic tracking-tighter">Identity Registry Matrix</h3>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] font-headline italic">Primary Subscriber Authentication Protocols</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-10">
                    <div class="space-y-2">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest italic pr-4">Network Username Identity</label>
                        <div class="flex items-center gap-4">
                            <span class="text-[17px] font-manrope font-black text-slate-900 tracking-tighter italic uppercase underline decoration-primary/30 decoration-4 underline-offset-8">{{ $client->username }}</span>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest italic pr-4">Service Access Method</label>
                        <div class="flex items-center gap-3">
                            <span class="px-4 py-1.5 bg-slate-900 text-neon-cyan border border-white/5 rounded-xl text-[10px] font-black uppercase italic shadow-glow-cyan/20">
                                {{ $client->type === 'pppoe' ? 'Broadband (PPPoE)' : 'Hotspot Portal' }}
                            </span>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest italic pr-4">Assigned Service Tier</label>
                        <div class="flex items-center gap-3">
                            <span class="px-5 py-2 bg-accent-flow text-white text-[11px] font-black rounded-xl shadow-glow-purple uppercase italic tracking-tighter">
                                {{ $client->package->name ?? 'VOID PACKAGE' }}
                            </span>
                        </div>
                    </div>
                    @if($client->phone)
                    <div class="space-y-2">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest italic pr-4">Communication Handshake</label>
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary text-xl">contact_phone</span>
                            <span class="text-[15px] font-manrope font-black text-slate-700 tracking-[0.1em]">{{ $client->phone }}</span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Infrastructure Matrix Engine -->
            <div class="glass-panel p-10 rounded-[3rem] !bg-white/80 border-slate-100 group">
                <div class="flex items-center justify-between mb-12">
                    <div class="flex items-center gap-6">
                        <div class="w-14 h-14 bg-emerald-500/10 rounded-2xl flex items-center justify-center text-emerald-600 shadow-inner border border-emerald-500/20">
                            <span class="material-symbols-outlined text-3xl">router</span>
                        </div>
                        <div class="space-y-1">
                            <h3 class="text-xl font-black text-slate-900 uppercase italic tracking-tighter">Infrastructure Topology</h3>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] font-headline italic">Terminal CPE & RF Access Distribution Information</p>
                        </div>
                    </div>
                    <div class="px-5 py-2 bg-slate-900 rounded-xl text-[9px] font-black text-neon-cyan animate-pulse uppercase tracking-widest italic border border-white/5 shadow-glow-cyan/10">
                        Host Node: {{ $client->mikrotikServer->name ?? 'GRID-UNKNOWN' }}
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <!-- Terminal CPE Card -->
                    <div class="glass-card p-8 rounded-[2.5rem] !bg-white space-y-8 border-slate-100 group/cpe hover:border-primary/20 transition-all">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-slate-300 group-hover/cpe:text-primary transition-colors">modem</span>
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] italic">Terminal CPE Node</span>
                            </div>
                            <span class="w-2 h-2 bg-primary rounded-full animate-ping shadow-glow-purple"></span>
                        </div>
                        <div class="space-y-6">
                            <div class="flex justify-between items-center bg-slate-50 p-4 rounded-2xl border border-slate-100">
                                <span class="text-[9px] font-black text-slate-400 uppercase italic">Device Identity</span>
                                <span class="text-xs font-black text-slate-900 uppercase italic tracking-tighter">{{ $client->cpe_model ?? 'STANDARD UNIT' }}</span>
                            </div>
                            <div class="flex justify-between items-center bg-slate-900 p-4 rounded-2xl shadow-glow-cyan/5">
                                <span class="text-[9px] font-black text-slate-500 uppercase italic">Static Registry IP</span>
                                <span class="text-sm font-manrope font-black text-neon-cyan tracking-[0.1em]">{{ $client->cpe_ip ?? '0.0.0.0' }}</span>
                            </div>
                            @if($client->cpe_ip && $client->mikrotik_server_id)
                                <a href="{{ route('crm.clients.cpe-proxy', ['client' => $client, 'type' => 'cpe']) }}" target="_blank" class="w-full py-4 bg-primary text-white text-[10px] font-black rounded-2xl flex items-center justify-center gap-4 uppercase tracking-[0.2em] italic shadow-glow-purple hover:scale-[1.03] active:scale-[0.97] transition-all">
                                    <span class="material-symbols-outlined text-lg">terminal</span>
                                    WebFig Remote Access
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- RF Access Unit Card -->
                    <div class="glass-card p-8 rounded-[2.5rem] !bg-white space-y-8 border-slate-100 group/rf hover:border-emerald-500/20 transition-all">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-slate-300 group-hover/rf:text-emerald-500 transition-colors">wifi_tethering</span>
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] italic">RF Access Distribution</span>
                            </div>
                            <span class="w-2 h-2 bg-emerald-500 rounded-full animate-ping shadow-glow-cyan"></span>
                        </div>
                        <div class="space-y-6">
                            <div class="flex justify-between items-center bg-slate-50 p-4 rounded-2xl border border-slate-100">
                                <span class="text-[9px] font-black text-slate-400 uppercase italic">Receiver Architecture</span>
                                <span class="text-xs font-black text-slate-900 uppercase italic tracking-tighter">{{ $client->receiver_model ?? 'LEGACY RF UNIT' }}</span>
                            </div>
                            <div class="flex justify-between items-center bg-slate-900 p-4 rounded-2xl shadow-glow-cyan/5">
                                <span class="text-[9px] font-black text-slate-500 uppercase italic">Backhaul Link IP</span>
                                <span class="text-sm font-manrope font-black text-emerald-400 tracking-[0.1em]">{{ $client->receiver_ip ?? '0.0.0.0' }}</span>
                            </div>
                            @if($client->receiver_ip && $client->mikrotik_server_id)
                                <a href="{{ route('crm.clients.cpe-proxy', ['client' => $client, 'type' => 'receiver']) }}" target="_blank" class="w-full py-4 bg-emerald-600 text-white text-[10px] font-black rounded-2xl flex items-center justify-center gap-4 uppercase tracking-[0.2em] italic shadow-glow-cyan hover:scale-[1.03] active:scale-[0.97] transition-all">
                                    <span class="material-symbols-outlined text-lg">sensors</span>
                                    Signal Topology Engine
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Subscriber Handshake Hub -->
            @if(auth()->user()->tenant->is_subdomain_enabled)
                <div class="glass-panel p-10 rounded-[3rem] !bg-white/80 border-slate-100 group">
                    <div class="flex items-center justify-between mb-12">
                        <div class="flex items-center gap-6">
                            <div class="w-14 h-14 bg-indigo-600/10 rounded-2xl flex items-center justify-center text-indigo-600 shadow-inner border border-indigo-600/20">
                                <span class="material-symbols-outlined text-3xl">hub</span>
                            </div>
                            <div class="space-y-1">
                                <h3 class="text-xl font-black text-slate-900 uppercase italic tracking-tighter">Subscriber Connect Portal</h3>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] font-headline italic">Public Interface Access Protocols & Credentials</p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        <!-- Portal Access Registry -->
                        <div class="space-y-8">
                            <div class="p-8 bg-slate-900 rounded-[2.5rem] space-y-6 shadow-glow-purple/10 border border-white/5 relative overflow-hidden">
                                <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-600/10 rounded-full -mr-16 -mt-16 blur-3xl animate-pulse"></div>
                                
                                <div class="space-y-2">
                                    <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest italic pr-2">Identity Endpoint URL</p>
                                    <div class="px-6 py-4 bg-white/5 rounded-2xl border border-white/10 text-[11px] font-manrope font-black text-indigo-400 select-all tracking-widest italic underline underline-offset-4">
                                        {{ 'http://' . (str_contains(auth()->user()->tenant->domain, '.') ? auth()->user()->tenant->domain : auth()->user()->tenant->domain . '.' . str_replace('www.', '', parse_url(config('app.url'), PHP_URL_HOST))) }}
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest italic pr-2">Identity Hub Username</p>
                                    <div class="px-6 py-4 bg-white/5 rounded-2xl border border-white/10 text-sm font-black text-white italic tracking-tighter uppercase select-all">
                                        {{ $client->username }}
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-col sm:flex-row gap-4">
                                <button onclick="copyCredentials()" class="flex-1 py-4 bg-white border border-slate-200 text-slate-600 rounded-2xl text-[10px] font-black uppercase tracking-widest italic flex items-center justify-center gap-3 transition-all hover:bg-slate-50 active:scale-95 shadow-sm">
                                    <span class="material-symbols-outlined text-lg">content_copy</span>
                                    Copy Packet
                                </button>
                                <form action="{{ route('crm.clients.send-credentials', $client) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit" class="w-full py-4 bg-emerald-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest italic flex items-center justify-center gap-3 transition-all hover:scale-[1.03] active:scale-95 shadow-glow-cyan/20">
                                        <span class="material-symbols-outlined text-lg">chat</span>
                                        WhatsApp Push
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Credential Mutation Engine -->
                        <div class="glass-card p-8 rounded-[2.5rem] !bg-white border-slate-100 flex flex-col justify-center">
                            <form action="{{ route('crm.clients.update-password', $client) }}" method="POST" class="space-y-6">
                                @csrf
                                @method('PUT')
                                <div class="space-y-3">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pr-4 italic">Reset Access Credentials</label>
                                    <div class="relative">
                                        <input type="text" name="password" placeholder="Protocol Hash Protocol..." class="input-radiant !py-4 pr-12 text-[12px] font-black placeholder:text-slate-200 uppercase" required>
                                        <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-slate-300">vpn_key</span>
                                    </div>
                                </div>
                                <button type="submit" class="w-full py-4 bg-slate-900 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest italic transition-all hover:bg-black active:scale-[0.98] shadow-lg">
                                    CONFIRM SECURITY MUTATION
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar Activity Column -->
        <div class="col-span-12 lg:col-span-4 space-y-10">
            
            <!-- Temporal Activity Pulse -->
            <div class="glass-panel p-10 rounded-[3rem] !bg-white/80 border-slate-100 group">
                <div class="flex items-center gap-5 mb-10">
                    <div class="w-12 h-12 bg-primary/10 rounded-2xl flex items-center justify-center text-primary group-hover:scale-110 transition-transform shadow-inner">
                        <span class="material-symbols-outlined text-2xl">insights</span>
                    </div>
                    <h3 class="text-xl font-black text-slate-900 uppercase italic tracking-tighter">Activity Pulse</h3>
                </div>

                <div class="space-y-8 relative pr-6 before:absolute before:right-1.5 before:top-2 before:bottom-0 before:w-px before:bg-slate-100">
                    @forelse($client->activities as $activity)
                        <div class="relative">
                            <div class="absolute right-[-22px] top-1.5 w-3 h-3 rounded-full bg-white border-2 border-primary shadow-glow-purple z-10 group-hover:scale-125 transition-transform"></div>
                            <div class="space-y-1">
                                <p class="text-[13px] font-bold text-slate-700 leading-tight italic">{{ $activity->description }}</p>
                                <p class="text-[8px] font-black text-slate-400 uppercase tracking-[0.2em] opacity-60">{{ $activity->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="py-12 text-center opacity-30 italic font-black uppercase text-[10px] tracking-widest">
                            No temporal signals emitted.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Observation Deck (Notes Engine) -->
            <div class="glass-panel p-10 rounded-[3rem] !bg-white/80 border-slate-100 group overflow-hidden relative">
                <div class="absolute bottom-0 left-0 w-32 h-32 bg-slate-900/5 rounded-full -ml-16 -mb-16 blur-2xl group-hover:scale-150 transition-transform"></div>
                
                <div class="flex items-center gap-5 mb-10 relative z-10">
                    <div class="w-12 h-12 bg-slate-900 rounded-2xl flex items-center justify-center text-white shadow-lg">
                        <span class="material-symbols-outlined text-2xl group-hover:rotate-12 transition-transform">description</span>
                    </div>
                    <h3 class="text-xl font-black text-slate-900 uppercase italic tracking-tighter">Observation Deck</h3>
                </div>

                <form action="{{ route('crm.clients.notes.store', $client) }}" method="POST" class="space-y-6 relative z-10">
                    @csrf
                    <div class="space-y-3">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest pr-4 italic">Registry Classification</label>
                        <select name="type" required class="input-radiant !py-3 text-[10px] font-black uppercase italic appearance-none !bg-white">
                            <option value="general">Global Operational Log</option>
                            <option value="technical">Technical Infrastructure Audit</option>
                            <option value="billing">Fiscal Ledger Tracking</option>
                        </select>
                    </div>
                    
                    <div class="space-y-3">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest pr-4 italic">Incident Payload Description</label>
                        <textarea name="content" required rows="4" class="input-radiant !p-6 text-[12px] font-bold text-slate-700 leading-relaxed placeholder:text-slate-200 !bg-white focus:ring-slate-900/10" placeholder="Capture specific incident shards..."></textarea>
                    </div>

                    <button type="submit" class="w-full py-4 bg-slate-900 text-white text-[11px] font-black rounded-2xl uppercase tracking-[0.3em] italic shadow-glow-purple hover:bg-black transition-all active:scale-95">
                        Commit Signal to Registry
                    </button>
                </form>

                <div class="mt-12 space-y-6 relative z-10">
                    @foreach($client->clientNotes as $note)
                        <div class="glass-card p-5 rounded-2xl !bg-white border-r-8 {{ $note->type === 'technical' ? 'border-primary' : ($note->type === 'billing' ? 'border-emerald-500' : 'border-slate-400 shadow-sm') }}">
                            <p class="text-[12px] font-bold text-slate-700 leading-relaxed italic">{{ $note->content }}</p>
                            <div class="flex justify-between mt-4 text-[8px] font-black uppercase tracking-[0.3em] text-slate-300">
                                <span class="bg-slate-50 px-2 py-0.5 rounded border border-slate-100">{{ $note->type }} Registry</span>
                                <span>{{ $note->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyCredentials() {
    const link = "{{ 'http://' . (str_contains(auth()->user()->tenant->domain, '.') ? auth()->user()->tenant->domain : auth()->user()->tenant->domain . '.' . str_replace('www.', '', parse_url(config('app.url'), PHP_URL_HOST))) }}";
    const username = "{{ $client->username }}";
    const text = `Handshake Node Link: ${link}\nIdentity Token: ${username}`;
    
    navigator.clipboard.writeText(text).then(() => {
        alert('IDENTITY PROTOCOL: Credential payload successfully cached to system memory.');
    }).catch(err => {
        console.error('CRITICAL ERROR: Matrix clipboard access failed.', err);
    });
}
</script>
@endsection
