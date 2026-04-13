@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-8 mb-10">
        <div class="flex items-center gap-6">
            <a href="{{ route('crm.clients.index') }}" class="w-12 h-12 rounded-2xl bg-white/40 backdrop-blur-md flex items-center justify-center text-gray-400 hover:text-indigo-600 hover:bg-white border border-white/30 transition-all shadow-sm group">
                <svg class="w-6 h-6 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div class="relative">
                <div class="absolute -top-6 -right-6 w-20 h-20 bg-indigo-500/10 rounded-full blur-2xl -z-10"></div>
                <h2 class="text-4xl font-black text-gray-900 tracking-tight">{{ $client->username }}</h2>
                <p class="text-xs font-bold text-indigo-600 uppercase tracking-widest mt-1 flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-600 animate-pulse"></span>
                    {{ $client->name ?? 'Client Profile' }}
                </p>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-4">
            <a href="{{ route('crm.clients.renew', $client) }}" class="inline-flex items-center gap-3 px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-black rounded-2xl shadow-xl shadow-indigo-200/50 transition-all transform hover:scale-105 active:scale-95 group">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                <span>تجديد الاشتراك</span>
            </a>

            <div class="h-10 w-px bg-gray-900/5 mx-2 hidden md:block"></div>

            <form action="{{ route('crm.clients.toggle-status', $client) }}" method="POST" class="inline-block">
                @csrf
                @method('PATCH')
                @if($client->status === 'active')
                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-rose-50/50 hover:bg-rose-50 text-rose-600 border border-rose-100 rounded-2xl font-black text-[11px] uppercase tracking-widest transition-all hover:shadow-lg hover:shadow-rose-100 active:scale-95" onclick="return confirm('هل أنت متأكد من تعطيل هذا الحساب؟ سيتم فصل الإنترنت عنه فوراً.')">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" /></svg>
                        Deactivate
                    </button>
                @else
                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-emerald-50/50 hover:bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-2xl font-black text-[11px] uppercase tracking-widest transition-all hover:shadow-lg hover:shadow-emerald-100 active:scale-95">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        Activate
                    </button>
                @endif
            </form>

            <a href="{{ route('crm.clients.edit', $client) }}" class="inline-flex items-center justify-center w-12 h-12 bg-white/50 backdrop-blur-md text-gray-400 hover:text-indigo-600 hover:bg-white rounded-2xl border border-white/40 transition-all shadow-sm">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
            </a>
        </div>
    </div>

    <!-- Stats Orbs -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-10">
        <!-- Invoices -->
        <div class="glass-panel rounded-3xl p-8 relative overflow-hidden group hover:translate-y-[-4px] transition-all duration-500 hover:shadow-2xl hover:shadow-blue-500/10">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-blue-500/10 rounded-full blur-3xl group-hover:bg-blue-500/20 transition-colors"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4 text-blue-600">
                    <div class="w-12 h-12 rounded-2xl bg-blue-600/10 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                </div>
                <p class="text-gray-400 text-[10px] font-black uppercase tracking-widest mb-1">إجمالي الفواتير</p>
                <p class="text-3xl font-black text-gray-900 tracking-tighter">{{ number_format($stats['total_invoices']) }}</p>
                <p class="mt-4 text-[11px] font-bold text-blue-500 uppercase tracking-tighter">Billing Records</p>
            </div>
        </div>

        <!-- Paid -->
        <div class="glass-panel rounded-3xl p-8 relative overflow-hidden group hover:translate-y-[-4px] transition-all duration-500 hover:shadow-2xl hover:shadow-emerald-500/10">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-emerald-500/10 rounded-full blur-3xl group-hover:bg-emerald-500/20 transition-colors"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4 text-emerald-600">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-600/10 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <p class="text-gray-400 text-[10px] font-black uppercase tracking-widest mb-1">المدفوعات</p>
                <p class="text-3xl font-black text-emerald-600 tracking-tighter">@money($stats['total_paid'])</p>
                <p class="mt-4 text-[11px] font-bold text-emerald-500 uppercase tracking-tighter">Total Collected</p>
            </div>
        </div>

        <!-- Pending -->
        <div class="glass-panel rounded-3xl p-8 relative overflow-hidden group hover:translate-y-[-4px] transition-all duration-500 hover:shadow-2xl hover:shadow-orange-500/10">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-orange-500/10 rounded-full blur-3xl group-hover:bg-orange-500/20 transition-colors"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4 text-orange-600">
                    <div class="w-12 h-12 rounded-2xl bg-orange-600/10 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <p class="text-gray-400 text-[10px] font-black uppercase tracking-widest mb-1">المبلغ المعلق</p>
                <p class="text-3xl font-black text-orange-600 tracking-tighter">@money($stats['pending_amount'])</p>
                <p class="mt-4 text-[11px] font-bold text-orange-600 uppercase tracking-tighter">Awaiting Settlement</p>
            </div>
        </div>

        <!-- Expiry -->
        <div class="glass-panel rounded-3xl p-8 relative overflow-hidden group hover:translate-y-[-4px] transition-all duration-500 hover:shadow-2xl hover:shadow-purple-500/10">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-purple-500/10 rounded-full blur-3xl group-hover:bg-purple-500/20 transition-colors"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4 text-purple-600">
                    <div class="w-12 h-12 rounded-2xl bg-purple-600/10 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                </div>
                <p class="text-gray-400 text-[10px] font-black uppercase tracking-widest mb-1">تاريخ الانتهاء</p>
                <p class="text-2xl font-black text-gray-900 tracking-tight">{{ $client->expires_at ? $client->expires_at->format('Y-m-d') : 'Permanent' }}</p>
                <p class="mt-4 text-[11px] font-bold text-purple-500 uppercase tracking-tighter">Contract Duration</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Client Info Cards -->
            <div class="glass-panel rounded-[2rem] p-8 relative overflow-hidden group">
                <div class="absolute -top-10 -right-10 w-32 h-32 bg-indigo-500/5 rounded-full blur-3xl transition-all"></div>
                <h3 class="text-xl font-black text-gray-900 mb-8 flex items-center gap-3">
                    <span class="w-2 h-8 bg-indigo-600 rounded-full"></span>
                    Identity & Connection
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="flex flex-col p-5 bg-white/40 backdrop-blur-sm rounded-2xl border border-white/40 transition-all hover:bg-white/60">
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Username Account</span>
                        <span class="font-black text-gray-900 tracking-tight">{{ $client->username }}</span>
                    </div>
                    @if($client->name)
                    <div class="flex flex-col p-5 bg-white/40 backdrop-blur-sm rounded-2xl border border-white/40 transition-all hover:bg-white/60">
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Full Legal Name</span>
                        <span class="font-black text-gray-900 tracking-tight">{{ $client->name }}</span>
                    </div>
                    @endif
                    @if($client->email)
                    <div class="flex flex-col p-5 bg-white/40 backdrop-blur-sm rounded-2xl border border-white/40 transition-all hover:bg-white/60">
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">E-mail Address</span>
                        <span class="font-black text-gray-900 tracking-tight">{{ $client->email }}</span>
                    </div>
                    @endif
                    @if($client->phone)
                    <div class="flex flex-col p-5 bg-white/40 backdrop-blur-sm rounded-2xl border border-white/40 transition-all hover:bg-white/60">
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Mobile Phone</span>
                        <span class="font-black text-indigo-600 tracking-tight dir-ltr text-right">{{ $client->phone }}</span>
                    </div>
                    @endif
                    <div class="flex flex-col p-5 bg-white/40 backdrop-blur-sm rounded-2xl border border-white/40 transition-all hover:bg-white/60">
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Service Type</span>
                        <span class="font-black text-gray-900 tracking-tight uppercase">{{ $client->type === 'pppoe' ? 'Broadband (PPPoE)' : 'Hotspot (Portal)' }}</span>
                    </div>
                    <div class="flex flex-col p-5 bg-white/40 backdrop-blur-sm rounded-2xl border border-white/40 transition-all hover:bg-white/60">
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Assigned Package</span>
                        <span class="px-4 py-1.5 bg-indigo-600 text-white rounded-xl text-xs font-black inline-block w-fit tracking-tight shadow-md">{{ $client->package->name ?? 'Custom Plan' }}</span>
                    </div>
                </div>
            </div>

            <!-- Network & CPE Access (Premium) -->
            <div class="glass-panel rounded-[2rem] p-8 relative overflow-hidden group border border-indigo-500/10 shadow-2xl shadow-indigo-500/5">
                <div class="absolute -top-10 -right-10 w-32 h-32 bg-indigo-500/10 rounded-full blur-3xl transition-all group-hover:bg-indigo-500/20"></div>
                
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-xl font-black text-gray-900 flex items-center gap-3">
                        <span class="w-1.5 h-8 bg-indigo-600 rounded-full"></span>
                        Equipment Intelligence & Remote Access
                    </h3>
                    @if($client->mikrotik_server_id)
                        <span class="px-3 py-1 bg-emerald-500/10 text-emerald-600 rounded-lg text-[9px] font-black uppercase tracking-widest border border-emerald-500/10">Edge Node Connected</span>
                    @else
                        <span class="px-3 py-1 bg-gray-500/10 text-gray-400 rounded-lg text-[9px] font-black uppercase tracking-widest border border-gray-500/10">No Management Node</span>
                    @endif
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- 1. Interior Router (CPE) -->
                    <div class="space-y-6">
                        <div class="flex items-center gap-3 mb-2">
                            <span class="p-2 bg-indigo-600/10 text-indigo-600 rounded-lg uppercase text-[10px] font-black tracking-widest">Device 1: Interior Router</span>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-4 bg-gray-900/5 rounded-2xl border border-gray-900/5">
                                <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest block mb-1">Model / Vendor</span>
                                <span class="text-sm font-black text-gray-900">{{ $client->cpe_model ?? 'Generic CPE' }}</span>
                            </div>
                            <div class="p-4 bg-gray-900/5 rounded-2xl border border-gray-900/5">
                                <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest block mb-1">Network IP</span>
                                <span class="text-sm font-black text-indigo-600 font-mono">{{ $client->cpe_ip ?? 'Not Set' }}</span>
                            </div>
                        </div>

                        @if($client->cpe_ip && $client->mikrotik_server_id)
                        <div class="p-4 bg-indigo-600/5 rounded-2xl border border-indigo-600/10 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center text-white">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                </div>
                                <div>
                                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">{{ $client->cpe_username ?? 'admin' }}</p>
                                    <p class="text-[10px] font-bold text-gray-900">Encrypted Auth</p>
                                </div>
                            </div>
                            <a href="{{ route('crm.clients.cpe-proxy', ['client' => $client, 'type' => 'cpe']) }}" target="_blank" class="px-5 py-2 bg-indigo-600 text-white text-[9px] font-black uppercase tracking-widest rounded-xl shadow-lg hover:bg-indigo-700 transition-all">
                                Open Webfig
                            </a>
                        </div>
                        @endif
                    </div>

                    <!-- 2. Exterior Receiver (Radio) -->
                    <div class="space-y-6">
                        <div class="flex items-center gap-3 mb-2">
                            <span class="p-2 bg-emerald-600/10 text-emerald-600 rounded-lg uppercase text-[10px] font-black tracking-widest">Device 2: Outdoor Radio</span>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-4 bg-gray-900/5 rounded-2xl border border-gray-900/5">
                                <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest block mb-1">Radio Model</span>
                                <span class="text-sm font-black text-gray-900">{{ $client->receiver_model ?? 'Generic Radio' }}</span>
                            </div>
                            <div class="p-4 bg-gray-900/5 rounded-2xl border border-gray-900/5">
                                <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest block mb-1">Radio IP</span>
                                <span class="text-sm font-black text-emerald-600 font-mono">{{ $client->receiver_ip ?? 'Not Set' }}</span>
                            </div>
                        </div>

                        @if($client->receiver_ip && $client->mikrotik_server_id)
                        <div class="p-4 bg-emerald-600/5 rounded-2xl border border-emerald-600/10 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-emerald-600 flex items-center justify-center text-white">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                </div>
                                <div>
                                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">{{ $client->receiver_username ?? 'admin' }}</p>
                                    <p class="text-[10px] font-bold text-gray-900">Encrypted Auth</p>
                                </div>
                            </div>
                            <a href="{{ route('crm.clients.cpe-proxy', ['client' => $client, 'type' => 'receiver']) }}" target="_blank" class="px-5 py-2 bg-emerald-600 text-white text-[9px] font-black uppercase tracking-widest rounded-xl shadow-lg hover:bg-emerald-700 transition-all">
                                Open Dashboard
                            </a>
                        </div>
                        @else
                        <div class="p-4 bg-gray-50 rounded-2xl border border-dashed border-gray-200 text-center">
                            <p class="text-[10px] font-bold text-gray-400 italic">No Outdoor Equipment Registered</p>
                        </div>
                        @endif
                    </div>
                </div>

                <p class="mt-8 text-[9px] text-gray-400 font-bold px-2 italic text-left">
                    Network nodes managed via: <span class="text-indigo-600">{{ $client->mikrotikServer->name ?? 'Primary Infrastructure' }}</span>
                </p>
            </div>

            <!-- Client Portal Access -->
            @if(auth()->user()->tenant->is_subdomain_enabled)
            <!-- Client Portal Access -->
            @if(auth()->user()->tenant->is_subdomain_enabled)
            <div class="glass-panel rounded-[2rem] p-8 relative overflow-hidden group">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-xl font-black text-gray-900 flex items-center gap-3">
                        <span class="w-2 h-8 bg-emerald-500 rounded-full"></span>
                        Subscriber Portal Access
                    </h3>
                    @if(empty($client->password))
                        <span class="px-4 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest bg-rose-500/10 text-rose-600 border border-rose-500/10 flex items-center gap-2">
                             <span class="w-1.5 h-1.5 bg-rose-600 rounded-full"></span>
                             Inactive
                        </span>
                    @else
                        <span class="px-4 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest bg-emerald-500/10 text-emerald-600 border border-emerald-500/10 flex items-center gap-2">
                             <span class="w-1.5 h-1.5 bg-emerald-600 rounded-full animate-pulse"></span>
                             Portal Ready
                        </span>
                    @endif
                </div>

                <div class="space-y-6">
                    <div class="bg-gray-900/5 backdrop-blur-md rounded-2xl p-6 border border-gray-900/5 space-y-4">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-2 border-b border-gray-900/5 pb-4">
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest leading-none">Authentication URL</span>
                            <span class="font-mono text-xs font-black text-indigo-600 dir-ltr text-right select-all truncate max-w-[300px]">
                                {{ 'http://' . (str_contains(auth()->user()->tenant->domain, '.') ? auth()->user()->tenant->domain : auth()->user()->tenant->domain . '.' . str_replace('www.', '', parse_url(config('app.url'), PHP_URL_HOST))) }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest leading-none">Login Identity</span>
                            <span class="font-black text-sm text-gray-900 select-all">{{ $client->username }}</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <button onclick="copyCredentials()" class="flex items-center justify-center gap-3 px-6 py-3.5 bg-white/50 hover:bg-white text-gray-700 rounded-2xl border border-white/40 shadow-sm transition-all font-black text-[11px] uppercase tracking-widest group">
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-indigo-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            Copy Payload
                        </button>
                        
                        <form action="{{ route('crm.clients.send-credentials', $client) }}" method="POST" class="w-full">
                            @csrf
                            <button type="submit" class="flex items-center justify-center gap-3 px-6 py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl shadow-xl shadow-emerald-200/50 transition-all font-black text-[11px] uppercase tracking-widest active:scale-95 w-full">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                                Send via WhatsApp
                            </button>
                        </form>
                    </div>

                    <!-- Update Password -->
                    <form action="{{ route('crm.clients.update-password', $client) }}" method="POST" class="pt-8 border-t border-gray-900/5">
                        @csrf
                        @method('PUT')
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Renew Portal Access Key</label>
                        <div class="flex gap-3">
                            <input type="text" name="password" placeholder="New high-security password..." class="flex-1 px-6 py-3.5 bg-white/50 backdrop-blur-md border border-white/20 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:bg-white focus:border-indigo-500/30 transition-all font-bold text-gray-900 placeholder-gray-400 text-sm" minlength="6" required>
                            <button type="submit" class="px-8 py-3.5 bg-gray-900 hover:bg-black text-white font-black text-[11px] uppercase tracking-widest rounded-2xl transition-all shadow-xl hover:shadow-gray-900/20 active:scale-95">
                                Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @endif
            @endif

            <!-- Activity Timeline -->
            <div class="glass-panel rounded-[2rem] p-8 relative overflow-hidden group h-fit">
                <h3 class="text-xl font-black text-gray-900 mb-8 flex items-center gap-3">
                    <span class="w-2 h-8 bg-blue-500 rounded-full"></span>
                    Activity Pulse
                </h3>
                <div class="space-y-8 relative before:absolute before:right-1 before:top-2 before:bottom-0 before:w-0.5 before:bg-gray-900/5">
                    @forelse($client->activities as $activity)
                    <div class="flex gap-6 pr-6 relative">
                        <div class="absolute right-0 top-1.5 w-2 h-2 rounded-full bg-blue-500 ring-4 ring-blue-500/10"></div>
                        <div class="flex-1">
                            <p class="text-sm font-black text-gray-900 leading-tight">{{ $activity->description }}</p>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1.5">{{ $activity->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @empty
                    <div class="flex flex-col items-center justify-center py-12 text-center">
                        <div class="w-12 h-12 bg-gray-900/5 rounded-2xl flex items-center justify-center text-gray-300 mb-4">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Silence is golden. No activity.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar Actions & Notes -->
        <div class="space-y-8">
            <!-- Add Note -->
            <div class="glass-panel rounded-[2rem] p-8 relative overflow-hidden group">
                <h3 class="text-xl font-black text-gray-900 mb-8 flex items-center gap-3">
                    <span class="w-2 h-8 bg-gray-900 rounded-full"></span>
                    Observer Log
                </h3>
                <form action="{{ route('crm.clients.notes.store', $client) }}" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <select name="type" required class="w-full py-3.5 px-6 bg-white/50 backdrop-blur-md border border-white/20 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:bg-white focus:border-indigo-500/30 transition-all font-bold text-gray-700 appearance-none text-sm">
                            <option value="general">General Narrative</option>
                            <option value="technical">Technical Report</option>
                            <option value="billing">Financial Log</option>
                        </select>
                    </div>
                    <div>
                        <textarea name="content" required rows="4" class="w-full px-6 py-4 bg-white/50 backdrop-blur-md border border-white/20 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:bg-white focus:border-indigo-500/30 transition-all font-bold text-gray-900 placeholder-gray-400 text-sm resize-none" placeholder="Record your observations..."></textarea>
                    </div>
                    <button type="submit" class="w-full py-4 bg-gray-900 hover:bg-black text-white font-black text-[11px] uppercase tracking-widest rounded-2xl shadow-xl hover:shadow-gray-900/20 transition-all active:scale-95">
                        Commit Observation
                    </button>
                </form>
            </div>

            <!-- Notes List -->
            <div class="glass-panel rounded-[2rem] p-8 relative overflow-hidden group">
                <h3 class="text-xl font-black text-gray-900 mb-8 flex items-center gap-3">
                    <span class="w-2 h-8 bg-gray-400/20 rounded-full"></span>
                    Log Records
                </h3>
                <div class="space-y-4">
                    @forelse($client->clientNotes as $note)
                    <div class="p-6 bg-white/40 backdrop-blur-sm rounded-2xl border-r-4 {{ $note->type === 'technical' ? 'border-purple-500' : ($note->type === 'billing' ? 'border-emerald-500' : 'border-blue-500') }} transition-all hover:bg-white/60">
                        <p class="text-xs font-bold text-gray-900 leading-relaxed">{{ $note->content }}</p>
                        <div class="flex items-center justify-between mt-4 text-[9px] font-black uppercase tracking-widest text-gray-400">
                            <span>{{ $note->type }}</span>
                            <span>{{ $note->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">No manual logs present</p>
                    </div>
                    @endforelse
                </div>
            </div>
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
        alert('تم نسخ بيانات الدخول!');
    }).catch(err => {
        console.error('فشل النسخ', err);
    });
}
</script>
@endsection
