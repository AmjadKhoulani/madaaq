@extends('layouts.admin')

@section('title', 'ملف المشترك | ' . $client->username)

@section('content')
<div class="space-y-8">
    <!-- Header Administrative Monolith -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
        <div class="flex items-center gap-6">
            <a href="{{ route('crm.clients.index') }}" class="w-11 h-11 rounded-lg bg-surface-container-low flex items-center justify-center text-primary hover:bg-primary hover:text-white transition-all border border-outline-variant/10 shadow-sm">
                <span class="material-symbols-outlined">arrow_forward</span>
            </a>
            <div>
                <div class="flex items-center gap-3">
                    <h2 class="text-3xl font-black text-primary tracking-tight">{{ $client->username }}</h2>
                    @if($client->status === 'active')
                        <span class="px-3 py-0.5 rounded text-[10px] font-black bg-secondary-container text-on-secondary-container uppercase tracking-widest flex items-center gap-2 border border-secondary/10">
                            <span class="w-1.5 h-1.5 rounded-full bg-secondary animate-pulse"></span>
                            Active Node
                        </span>
                    @else
                        <span class="px-3 py-0.5 rounded text-[10px] font-black bg-error-container text-on-error-container uppercase tracking-widest flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-error"></span>
                            Terminated
                        </span>
                    @endif
                </div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mt-1 font-headline italic">
                    {{ $client->name ?? 'Identity Protocol' }} | ID: {{ $client->id }}
                </p>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('crm.clients.renew', $client) }}" class="px-6 py-2.5 bg-primary text-white font-bold rounded-lg text-sm transition-all hover:scale-[1.02] flex items-center gap-2 shadow-lg shadow-primary/10">
                <span class="material-symbols-outlined text-lg">autorenew</span>
                تجديد الفاتورة
            </a>

            <div class="h-8 w-px bg-outline-variant/20 mx-1 hidden lg:block"></div>

            <form action="{{ route('crm.clients.toggle-status', $client) }}" method="POST" class="inline-block">
                @csrf
                @method('PATCH')
                <button type="submit" class="px-5 py-2.5 {{ $client->status === 'active' ? 'bg-error-container text-on-error-container' : 'bg-secondary-container text-on-secondary-container' }} font-black text-[10px] uppercase tracking-widest rounded-lg transition-all border border-outline-variant/10 flex items-center gap-2" 
                        onclick="return confirm('تأكيد تعديل حالة الاتصال لهذا المشترك؟')">
                    <span class="material-symbols-outlined text-lg">{{ $client->status === 'active' ? 'block' : 'check_circle' }}</span>
                    {{ $client->status === 'active' ? 'Deactivate' : 'Activate' }}
                </button>
            </form>

            <a href="{{ route('crm.clients.edit', $client) }}" class="p-2.5 bg-surface-container-low text-slate-500 hover:text-primary rounded-lg border border-outline-variant/10 transition-all">
                <span class="material-symbols-outlined text-xl">edit_square</span>
            </a>
        </div>
    </div>

    <!-- Tonal Metrics Dashboard -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-surface-container-low p-6 rounded-lg border border-outline-variant/10">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 font-headline">إجمالي المديونية</p>
            <div class="flex items-baseline gap-1">
                <h4 class="text-2xl font-manrope font-black text-primary">{{ number_format($stats['total_invoices']) }}</h4>
                <span class="text-[10px] font-bold text-slate-400">DOCS</span>
            </div>
        </div>
        <div class="bg-surface-container-low p-6 rounded-lg border border-outline-variant/10">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 font-headline">إجمالي التحصيل</p>
            <div class="flex items-baseline gap-1">
                <h4 class="text-2xl font-manrope font-black text-secondary">{{ number_format($stats['total_paid']) }}</h4>
                <span class="text-[10px] font-bold text-slate-400">SAR</span>
            </div>
        </div>
        <div class="bg-surface-container-low p-6 rounded-lg border border-outline-variant/10">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 font-headline">الرصيد المعلق</p>
            <div class="flex items-baseline gap-1">
                <h4 class="text-2xl font-manrope font-black text-error">{{ number_format($stats['pending_amount']) }}</h4>
                <span class="text-[10px] font-bold text-slate-400">SAR</span>
            </div>
        </div>
        <div class="bg-surface-container-low p-6 rounded-lg border border-outline-variant/10">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 font-headline">نهاية التعاقد</p>
            <h4 class="text-xl font-manrope font-black text-primary">{{ $client->expires_at ? $client->expires_at->format('Y-m-d') : 'دائم' }}</h4>
        </div>
    </div>

    <div class="grid grid-cols-12 gap-8">
        <!-- Main Structure -->
        <div class="col-span-12 lg:col-span-8 space-y-8">
            
            <!-- Identity Section -->
            <div class="bg-surface-container-lowest p-8 rounded-lg shadow-sm border border-outline-variant/10 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-primary/5 rounded-full -mr-16 -mt-16 blur-3xl"></div>
                <h3 class="text-xl font-black text-primary mb-8 flex items-center gap-3 italic">
                    <span class="w-1 h-6 bg-primary rounded-full"></span>
                    Identity & Protocol
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-8">
                    <div class="space-y-1">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest font-headline">Login Identity</label>
                        <p class="text-[15px] font-bold text-slate-700">{{ $client->username }}</p>
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest font-headline">Active Service</label>
                        <p class="text-[15px] font-bold text-slate-700 uppercase">{{ $client->type === 'pppoe' ? 'Broadband (PPPoE)' : 'Hotspot (Mobile)' }}</p>
                    </div>
                    @if($client->phone)
                    <div class="space-y-1">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest font-headline">Mobile Payload</label>
                        <p class="text-[15px] font-manrope font-bold text-primary">{{ $client->phone }}</p>
                    </div>
                    @endif
                    <div class="space-y-1">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest font-headline">Tier Level</label>
                        <span class="inline-block px-4 py-1.5 bg-primary text-white text-[11px] font-black rounded shadow-md uppercase tracking-tight">{{ $client->package->name ?? 'Custom' }}</span>
                    </div>
                </div>
            </div>

            <!-- Network Topology -->
            <div class="bg-surface-container-lowest p-8 rounded-lg shadow-sm border border-outline-variant/10 group">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-xl font-black text-primary italic flex items-center gap-3">
                        <span class="w-1 h-6 bg-secondary rounded-full"></span>
                        Infrastructure Intelligence
                    </h3>
                    <div class="px-3 py-1 bg-surface-container rounded text-[9px] font-black text-slate-400 uppercase tracking-widest border border-outline-variant/10">
                        Node: {{ $client->mikrotikServer->name ?? 'Primary' }}
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <!-- Router (CPE) -->
                    <div class="space-y-6">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-slate-400">router</span>
                            <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest font-headline">Terminal CPE</span>
                        </div>
                        <div class="bg-surface-container-low p-5 rounded border border-outline-variant/10 space-y-4">
                            <div class="flex justify-between items-center group/item cursor-help">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tight">Model</span>
                                <span class="text-sm font-bold text-slate-700">{{ $client->cpe_model ?? 'Standard Router' }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tight">Access IP</span>
                                <span class="text-sm font-manrope font-black text-primary">{{ $client->cpe_ip ?? '0.0.0.0' }}</span>
                            </div>
                            @if($client->cpe_ip && $client->mikrotik_server_id)
                                <a href="{{ route('crm.clients.cpe-proxy', ['client' => $client, 'type' => 'cpe']) }}" target="_blank" class="w-full mt-4 py-2.5 bg-white border border-outline-variant/20 rounded flex items-center justify-center gap-2 text-[10px] font-black text-primary hover:bg-primary hover:text-white transition-all shadow-sm">
                                    <span class="material-symbols-outlined text-sm">terminal</span>
                                    REMOTE WEBFIG
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Station (Radio) -->
                    <div class="space-y-6">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-slate-400">settings_input_antenna</span>
                            <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest font-headline">Sector Radio</span>
                        </div>
                        <div class="bg-surface-container-low p-5 rounded border border-outline-variant/10 space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tight">Device</span>
                                <span class="text-sm font-bold text-slate-700">{{ $client->receiver_model ?? 'Standard Radio' }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tight">Link IP</span>
                                <span class="text-sm font-manrope font-black text-secondary">{{ $client->receiver_ip ?? '0.0.0.0' }}</span>
                            </div>
                            @if($client->receiver_ip && $client->mikrotik_server_id)
                                <a href="{{ route('crm.clients.cpe-proxy', ['client' => $client, 'type' => 'receiver']) }}" target="_blank" class="w-full mt-4 py-2.5 bg-white border border-outline-variant/20 rounded flex items-center justify-center gap-2 text-[10px] font-black text-secondary hover:bg-secondary hover:text-white transition-all shadow-sm">
                                    <span class="material-symbols-outlined text-sm">analytics</span>
                                    RADIO DASHBOARD
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Portal & Communication -->
            @if(auth()->user()->tenant->is_subdomain_enabled)
                <div class="bg-surface-container-lowest p-8 rounded-lg shadow-sm border border-outline-variant/10">
                    <div class="flex items-center justify-between mb-8">
                        <h3 class="text-xl font-black text-primary italic flex items-center gap-3">
                            <span class="w-1 h-6 bg-emerald-500 rounded-full"></span>
                            Subscriber Connect
                        </h3>
                        @if(!empty($client->password))
                            <span class="px-3 py-1 bg-secondary/10 text-secondary text-[9px] font-black uppercase tracking-widest rounded">Ready for Remote Login</span>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-6">
                            <div class="bg-surface-container-low p-6 rounded border border-outline-variant/10 space-y-3">
                                <div class="flex justify-between items-center pb-3 border-b border-outline-variant/5">
                                    <span class="text-[10px] font-bold text-slate-400 uppercase">Portal URL</span>
                                    <span class="text-[11px] font-manrope font-bold text-primary select-all">
                                        {{ 'http://' . (str_contains(auth()->user()->tenant->domain, '.') ? auth()->user()->tenant->domain : auth()->user()->tenant->domain . '.' . str_replace('www.', '', parse_url(config('app.url'), PHP_URL_HOST))) }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-[10px] font-bold text-slate-400 uppercase">Identity</span>
                                    <span class="text-[13px] font-bold text-slate-700 select-all">{{ $client->username }}</span>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <button onclick="copyCredentials()" class="flex-1 py-3 bg-white border border-outline-variant/20 text-[10px] font-black text-slate-600 rounded flex items-center justify-center gap-2 hover:bg-surface-container transition-all">
                                    <span class="material-symbols-outlined text-sm">content_copy</span>
                                    COPY PAYLOAD
                                </button>
                                <form action="{{ route('crm.clients.send-credentials', $client) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit" class="w-full py-3 bg-secondary text-white text-[10px] font-black rounded flex items-center justify-center gap-2 hover:scale-[1.02] transition-all shadow-lg shadow-secondary/10">
                                        <span class="material-symbols-outlined text-sm">send</span>
                                        WHATSAPP LINK
                                    </button>
                                </form>
                            </div>
                        </div>

                        <form action="{{ route('crm.clients.update-password', $client) }}" method="POST" class="bg-surface-container-low p-6 rounded border border-outline-variant/10">
                            @csrf
                            @method('PUT')
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Reset Access Key</label>
                            <div class="flex flex-col gap-3">
                                <input type="text" name="password" placeholder="New encrypted key..." class="w-full px-4 py-2.5 bg-white border border-outline-variant/20 rounded text-sm font-bold outline-none focus:border-primary" required>
                                <button type="submit" class="w-full py-2.5 bg-primary text-white text-[10px] font-black uppercase tracking-widest rounded hover:bg-primary-fixed transition-all">
                                    CONFIRM UPDATE
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        </div>

        <!-- Lateral Sidebar -->
        <div class="col-span-12 lg:col-span-4 space-y-8">
            
            <!-- Pulse Timeline -->
            <div class="bg-surface-container-lowest p-8 rounded-lg shadow-sm border border-outline-variant/10">
                <h3 class="text-xl font-black text-primary mb-8 flex items-center gap-3 italic">
                    <span class="w-1 h-6 bg-primary/20 rounded-full"></span>
                    Activity Pulse
                </h3>
                <div class="space-y-6 relative before:absolute before:right-1 before:top-2 before:bottom-0 before:w-px before:bg-outline-variant/10">
                    @forelse($client->activities as $activity)
                        <div class="flex gap-4 pr-6 relative">
                            <div class="absolute right-0 top-2 w-2 h-2 rounded-full bg-primary ring-4 ring-primary/5"></div>
                            <div>
                                <p class="text-[13px] font-bold text-slate-700 leading-tight">{{ $activity->description }}</p>
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mt-1.5">{{ $activity->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 italic text-center py-4">No recent signals detected.</p>
                    @endforelse
                </div>
            </div>

            <!-- Notes Monolith -->
            <div class="bg-surface-container-lowest p-8 rounded-lg shadow-sm border border-outline-variant/10">
                <h3 class="text-xl font-black text-primary mb-8 flex items-center gap-3 italic">
                    <span class="w-1 h-6 bg-slate-900 rounded-full"></span>
                    Observation Deck
                </h3>
                <form action="{{ route('crm.clients.notes.store', $client) }}" method="POST" class="space-y-4">
                    @csrf
                    <select name="type" required class="w-full px-4 py-2.5 bg-surface-container-low border border-outline-variant/20 rounded text-xs font-bold text-slate-600 outline-none">
                        <option value="general">General Logging</option>
                        <option value="technical">Technical Audit</option>
                        <option value="billing">Financial Tracking</option>
                    </select>
                    <textarea name="content" required rows="3" class="w-full px-4 py-3 bg-surface-container-low border border-outline-variant/20 rounded text-sm font-bold text-slate-700 outline-none resize-none placeholder:text-slate-300" placeholder="Capture incident details..."></textarea>
                    <button type="submit" class="w-full py-3 bg-primary text-white text-[10px] font-black uppercase tracking-widest rounded shadow-md hover:bg-primary-fixed transition-all">
                        COMMIT LOG
                    </button>
                </form>

                <div class="mt-8 space-y-4">
                    @foreach($client->clientNotes as $note)
                        <div class="p-4 bg-surface-container-low rounded border-r-4 {{ $note->type === 'technical' ? 'border-primary' : ($note->type === 'billing' ? 'border-secondary' : 'border-slate-300') }}">
                            <p class="text-xs font-bold text-slate-600 leading-relaxed">{{ $note->content }}</p>
                            <div class="flex justify-between mt-3 text-[8px] font-black uppercase tracking-widest text-slate-400">
                                <span>{{ $note->type }}</span>
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
    const text = `رابط البوابة: ${link}\nاسم المستخدم: ${username}`;
    
    navigator.clipboard.writeText(text).then(() => {
        alert('تم نسخ البيانات بنجاح في قاعدة الذاكرة.');
    }).catch(err => {
        console.error('فشل في الوصول لذاكرة النظام', err);
    });
}
</script>
@endsection
