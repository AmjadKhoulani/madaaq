@extends('layouts.admin')

@section('title', 'مركز اتصالات الواتساب')

@section('content')
<div class="h-[calc(100vh-160px)] flex flex-col space-y-6">
    
    <!-- Command Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 shrink-0">
        <div>
            <h2 class="text-3xl font-black text-primary tracking-tight">قناة التواصل المباشر</h2>
            <p class="text-slate-500 font-medium mt-1 uppercase tracking-widest text-[10px] font-headline">WhatsApp Cloud API Protocol</p>
        </div>
        @if(!$isConfigured)
            <div class="flex items-center gap-4 bg-error-container/20 px-4 py-2 rounded-lg border border-error/20">
                <span class="material-symbols-outlined text-error animate-pulse">cloud_off</span>
                <p class="text-[10px] font-black text-error uppercase tracking-widest leading-none">Service Offline: Configuration Required</p>
                <a href="{{ route('settings.index') }}" class="px-4 py-1.5 bg-error text-white text-[9px] font-black rounded uppercase tracking-widest hover:bg-error-fixed transition-all">Configure API</a>
            </div>
        @endif
    </div>

    <!-- Communication Hub Monolith -->
    <div class="flex-1 bg-surface-container-low rounded-lg border border-outline-variant/10 overflow-hidden flex shadow-sm">
        
        <!-- Sidebar: Subscribers Interface -->
        <div class="w-80 bg-surface-container-low border-l border-outline-variant/10 flex flex-col shrink-0">
            <!-- Search Hub -->
            <div class="p-4 border-b border-outline-variant/10 bg-surface-container-low/50">
                <div class="relative">
                    <input type="text" placeholder="بحث المشتركين..." class="w-full pr-10 pl-4 py-2.5 bg-white border border-outline-variant/20 rounded text-xs font-bold outline-none focus:border-primary">
                    <span class="material-symbols-outlined absolute right-3 top-2.5 text-slate-400 text-sm">search</span>
                </div>
            </div>

            <!-- Subscriber Stream -->
            <div class="flex-1 overflow-y-auto divide-y divide-outline-variant/5 custom-scrollbar">
                @foreach($clients as $c)
                    <a href="{{ route('whatsapp.show', $c->id) }}" class="flex items-center gap-3 p-4 hover:bg-white transition-all group {{ isset($client) && $client->id == $c->id ? 'bg-white border-r-4 border-primary' : '' }}">
                        <div class="relative">
                            <div class="w-11 h-11 bg-surface-container-highest rounded border border-outline-variant/10 flex items-center justify-center font-manrope font-black text-primary overflow-hidden group-hover:scale-105 transition-all">
                                @if(isset($c->profile_photo_url)) 
                                    <img src="{{ $c->profile_photo_url }}" class="w-full h-full object-cover">
                                @else
                                    {{ substr($c->username, 0, 2) }}
                                @endif
                            </div>
                            <div class="absolute -bottom-0.5 -right-0.5 w-3 h-3 {{ $c->status === 'active' ? 'bg-secondary' : 'bg-slate-300' }} rounded-full border-2 border-white shadow-sm"></div>
                        </div>
                        
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-center mb-0.5">
                                <h3 class="text-sm font-bold text-slate-700 truncate capitalize">{{ $c->name ?? $c->username }}</h3>
                                <span class="text-[9px] font-manrope font-bold text-slate-400">12:30</span>
                            </div>
                            <p class="text-[10px] text-slate-400 truncate font-medium">فتح سجل المحادثات لهذا المشترك</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Center: Intelligence Stream -->
        <div class="flex-1 flex flex-col bg-surface-container-lowest relative overflow-hidden">
            @if(isset($client))
                <!-- Subscriber Header -->
                <div class="h-16 bg-white px-6 flex items-center justify-between border-b border-outline-variant/10 z-10">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded bg-primary/5 flex items-center justify-center font-manrope font-black text-primary text-xs">
                            {{ substr($client->username, 0, 2) }}
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-primary italic">{{ $client->name ?? $client->username }}</h3>
                            <div class="flex items-center gap-2 mt-0.5">
                                <span class="w-1.5 h-1.5 rounded-full {{ $client->status == 'active' ? 'bg-secondary animate-pulse' : 'bg-slate-300' }}"></span>
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">
                                    {{ $client->status == 'active' ? 'Operational Node' : 'Terminated' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Messages Pulse -->
                <div class="flex-1 overflow-y-auto p-8 space-y-6 custom-scrollbar" id="chat-messages">
                    <div class="flex justify-center mb-8">
                        <div class="px-4 py-1.5 bg-surface-container-low border border-outline-variant/10 rounded text-[9px] font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
                            <span class="material-symbols-outlined text-xs">encrypted</span>
                            End-to-End Encrypted Session
                        </div>
                    </div>

                    @forelse($messages as $msg)
                        <div class="flex {{ $msg->direction === 'outbound' ? 'justify-start' : 'justify-end' }}">
                            <div class="max-w-[75%] p-4 {{ $msg->direction === 'outbound' ? 'bg-primary text-white rounded-l-lg rounded-tr-lg' : 'bg-surface-container-low text-slate-700 rounded-r-lg rounded-tl-lg' }} shadow-sm">
                                <p class="text-sm font-medium leading-relaxed">{{ $msg->body }}</p>
                                <div class="flex justify-end items-center gap-2 mt-2 opacity-60">
                                    <span class="text-[9px] font-manrope font-bold">{{ \Carbon\Carbon::parse($msg->created_at)->format('H:i') }}</span>
                                    @if($msg->direction === 'outbound')
                                        <span class="material-symbols-outlined text-xs">done_all</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="h-full flex flex-col items-center justify-center p-12 text-center opacity-30">
                            <span class="material-symbols-outlined text-6xl mb-4">forum</span>
                            <p class="text-xs font-black uppercase tracking-[0.2em]">No Active Signals</p>
                        </div>
                    @endforelse
                </div>

                <!-- Command Input -->
                <div class="p-6 bg-white border-t border-outline-variant/10">
                    <form action="{{ route('whatsapp.store', $client->id) }}" method="POST" class="flex gap-4">
                        @csrf
                        <div class="flex-1 relative">
                            <input type="text" name="message" required placeholder="أرسل تعليمات أو استجابة للمشترك..." 
                                   class="w-full px-6 py-4 bg-surface-container-low border border-outline-variant/10 rounded-lg text-sm font-bold text-slate-700 outline-none focus:border-primary transition-all pr-12">
                            <span class="material-symbols-outlined absolute right-4 top-4 text-slate-400">chat</span>
                        </div>
                        <button type="submit" class="px-8 bg-primary text-white rounded-lg flex items-center justify-center hover:scale-105 transition-all shadow-lg shadow-primary/10">
                            <span class="material-symbols-outlined">send</span>
                        </button>
                    </form>
                </div>

            @else
                <!-- Protocol Idle State -->
                <div class="flex-1 flex flex-col items-center justify-center p-12 text-center">
                    <div class="w-24 h-24 bg-surface-container-low rounded-full flex items-center justify-center text-slate-200 mb-8 border border-outline-variant/10">
                        <span class="material-symbols-outlined text-5xl">mark_chat_unread</span>
                    </div>
                    <h2 class="text-2xl font-black text-primary italic mb-2 tracking-tighter">بانتظار مزامنة المحادثات</h2>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest max-w-sm leading-loose">
                        يرجى اختيار مشترك من القائمة الجانبية لعرض السجل الكامل للمراسلات والتحكم في الاتصال.
                    </p>
                </div>
            @endif
        </div>

        <!-- Right Side: Node Intelligence (Sub-Admin Mini Profile) -->
        @if(isset($client))
            <div class="w-80 bg-surface-container-low border-r border-outline-variant/10 overflow-y-auto hidden xl:flex flex-col shrink-0">
                <div class="p-8 flex flex-col items-center border-b border-outline-variant/5 mb-6">
                    <div class="w-20 h-20 bg-white rounded border border-outline-variant/10 mb-4 flex items-center justify-center font-manrope font-black text-primary text-xl shadow-sm overflow-hidden">
                        @if(isset($client->profile_photo_url)) 
                            <img src="{{ $client->profile_photo_url }}" class="w-full h-full object-cover">
                        @else
                            {{ substr($client->username, 0, 2) }}
                        @endif
                    </div>
                    <h3 class="text-base font-black text-primary text-center italic">{{ $client->name ?? $client->username }}</h3>
                    <p class="mt-1 text-[10px] font-manrope font-black text-slate-400">{{ $client->phone }}</p>
                </div>

                <div class="px-6 space-y-4">
                    <div class="bg-surface-container-lowest p-4 rounded border border-outline-variant/5">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2 block">الحالة التشغيلية</label>
                        <div class="flex justify-between items-center">
                            <span class="text-xs font-bold text-slate-700">انتهاء العقد</span>
                            <span class="text-[10px] font-manrope font-black {{ \Carbon\Carbon::now()->diffInDays($client->expires_at, false) < 3 ? 'text-error' : 'text-primary' }}">
                                {{ \Carbon\Carbon::parse($client->expires_at)->format('Y/m/d') }}
                            </span>
                        </div>
                    </div>

                    <div class="bg-surface-container-lowest p-4 rounded border border-outline-variant/5">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2 block">تفاصيل التخصيص</label>
                        <div class="space-y-2">
                            <div class="flex justify-between items-center">
                                <span class="text-[10px] font-bold text-slate-500">الباقة</span>
                                <span class="text-[10px] font-black text-primary">{{ $client->package->name ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-[10px] font-bold text-slate-500">IP Addr</span>
                                <span class="text-[10px] font-manrope font-black text-primary">{{ $client->ip ?? '0.0.0.0' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-auto p-6 space-y-3">
                    <a href="{{ route('crm.clients.renew', $client) }}" class="w-full py-3 bg-secondary text-white text-[10px] font-black uppercase tracking-widest rounded flex items-center justify-center gap-2 shadow-lg shadow-secondary/10 hover:scale-[1.02] transition-all">
                        <span class="material-symbols-outlined text-sm">autorenew</span>
                        تجديد فوري
                    </a>
                    <a href="{{ route('crm.clients.show', $client) }}" class="w-full py-3 bg-white border border-outline-variant/20 text-[10px] font-black text-primary uppercase tracking-widest rounded flex items-center justify-center gap-2 hover:bg-slate-50 transition-all">
                        VIEW FULL PROFILE
                    </a>
                </div>
            </div>
        @endif

    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background-color: rgba(0, 0, 0, 0.05); border-radius: 10px; }
</style>

<script>
    const chatContainer = document.getElementById('chat-messages');
    if(chatContainer) { chatContainer.scrollTop = chatContainer.scrollHeight; }
</script>
@endsection
