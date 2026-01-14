@extends('layouts.app')

@section('content')
<!-- Super WhatsApp Interface -->
<div class="flex-1 flex w-full bg-[#f0f2f5] overflow-hidden relative" dir="rtl">
    
    <!-- Right Sidebar: Contacts List (w-1/4 or fixed width) -->
    <div class="w-[320px] bg-white border-l border-gray-200 flex flex-col h-full z-10 shrink-0">
        <!-- Header -->
        <div class="h-16 bg-[#00a884] px-4 flex items-center justify-between shrink-0">
            <h2 class="text-white font-bold text-lg flex items-center gap-2">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                محادثات العملاء
            </h2>
            <div class="flex gap-2">
                <button class="p-2 text-white/80 hover:text-white transition rounded-full hover:bg-white/10">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                </button>
            </div>
        </div>

        <!-- Search -->
        <div class="p-3 border-b border-gray-100 bg-white">
            <div class="relative">
                <input type="text" placeholder="بحث أو بدء محادثة جديدة" class="w-full pl-4 pr-10 py-2 bg-[#f0f2f5] rounded-lg text-sm border-none focus:ring-0 text-gray-700">
                <div class="absolute inset-y-0 right-0 pl-3 flex items-center pointer-events-none pr-3">
                    <svg class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </div>
        </div>

        <!-- Contact List -->
        <div class="flex-1 overflow-y-auto custom-scrollbar">
            @foreach($clients as $c)
                <a href="{{ route('whatsapp.show', $c->id) }}" class="flex items-center gap-3 p-3 border-b border-gray-50 hover:bg-[#f5f6f6] transition group cursor-pointer {{ isset($client) && $client->id == $c->id ? 'bg-[#f0f2f5]' : '' }}">
                    <div class="relative">
                        <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center text-gray-600 font-bold overflow-hidden">
                            @if(isset($c->profile_photo_url)) 
                                <img src="{{ $c->profile_photo_url }}" class="w-full h-full object-cover">
                            @else
                                {{ substr($c->name ?? $c->username, 0, 1) }}
                            @endif
                        </div>
                        @if($c->status === 'active')
                            <div class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 rounded-full border-2 border-white"></div>
                        @else
                            <div class="absolute bottom-0 right-0 w-3 h-3 bg-red-500 rounded-full border-2 border-white"></div>
                        @endif
                    </div>
                    
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-center mb-1">
                            <h3 class="font-semibold text-gray-900 truncate group-hover:text-black">{{ $c->name ?? $c->username }}</h3>
                            <span class="text-[11px] text-gray-400">12:30 م</span>
                        </div>
                        <p class="text-sm text-gray-500 truncate flex items-center gap-1">
                            <span class="text-gray-400">✓✓</span> 
                            انقر لعرض المحادثة
                        </p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

    <!-- Center: Main Chat Area -->
    <div class="flex-1 flex flex-col h-full relative bg-[#efeae2] before:content-[''] before:absolute before:inset-0 before:opacity-[0.06] before:bg-[url('https://user-images.githubusercontent.com/15075759/28719144-86dc0f70-73b1-11e7-911d-60d70fcded21.png')]">
        
        @if(!$isConfigured)
        <div class="bg-orange-100 border-b border-orange-200 text-orange-800 px-4 py-3 flex items-center justify-between shrink-0 z-30 relative">
            <div class="flex items-center gap-3">
                <svg class="w-6 h-6 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <div>
                    <p class="font-bold text-sm">ميزة الواتساب غير مفعلة</p>
                    <p class="text-xs text-orange-700">لاستخدام هذه الميزة وإرسال الرسائل، يجب إعداد WhatsApp Cloud API أولاً.</p>
                </div>
            </div>
            <a href="{{ route('settings.index') }}" class="text-xs bg-orange-600 text-white px-3 py-1.5 rounded-md hover:bg-orange-700 transition font-bold shadow-sm whitespace-nowrap">
                ⚙️ إعداد الخدمة
            </a>
        </div>
        @endif

        @if(isset($client))
            <!-- Sticky Header -->
            <header class="h-16 bg-[#00a884] px-4 flex items-center justify-between shrink-0 z-20 shadow-sm border-l border-green-700/20">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center text-white font-bold backdrop-blur-sm">
                        {{ substr($client->name ?? $client->username, 0, 1) }}
                    </div>
                    <div>
                        <h3 class="font-bold text-white text-base">{{ $client->name ?? $client->username }}</h3>
                        <p class="text-xs text-green-100 flex items-center gap-1">
                            {{ $client->status == 'active' ? 'مشترك نشط' : 'مشترك غير نشط' }}
                            @if($client->status == 'active') • ينتهي في {{ \Carbon\Carbon::parse($client->expires_at)->format('Y-m-d') }} @endif
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-3 text-white">
                    <button class="p-2 hover:bg-white/10 rounded-full" title="جعل غير مقروء"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg></button>
                    <button class="p-2 hover:bg-white/10 rounded-full"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/></svg></button>
                </div>
            </header>

            <!-- Messages Stream -->
            <div class="flex-1 overflow-y-auto p-4 md:p-8 space-y-6 z-10 custom-scrollbar" id="chat-messages">
                
                <!-- Encryption Notice -->
                <div class="flex justify-center mb-6">
                    <div class="bg-[#FFEECD] text-[#54656f] text-xs px-3 py-1.5 rounded-[8px] shadow-sm flex items-center gap-1.5 max-w-md text-center">
                        <svg class="w-3 h-3 text-[10px]" fill="currentColor" viewBox="0 0 24 24"><path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zM9 6c0-1.66 1.34-3 3-3s3 1.34 3 3v2H9V6zm9 14H6V10h12v10zm-6-3c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2z"/></svg>
                        الرسائل في هذه المحادثة محمية بواسطة واتساب كلاود API.
                    </div>
                </div>

                @forelse($messages as $msg)
                    <div class="flex w-full {{ $msg->direction === 'outbound' ? 'justify-end' : 'justify-start' }}">
                        <div class="relative max-w-[65%] md:max-w-[60%] {{ $msg->direction === 'outbound' ? 'bg-[#d9fdd3] rounded-l-lg rounded-tr-none rounded-br-lg' : 'bg-white rounded-r-lg rounded-tl-none rounded-bl-lg' }} p-2 pl-3 shadow-[0_1px_0.5px_rgba(0,0,0,0.13)]">
                            
                            <!-- Tail SVG for aesthetics -->
                            @if($msg->direction === 'outbound')
                            <span class="absolute -right-[8px] top-0 text-[#d9fdd3]">
                                <svg viewBox="0 0 8 13" height="13" width="8" preserveAspectRatio="none" version="1.1"><path d="M5.188,1H0v11.193l6.467-8.625 C7.526,2.156,6.958,1,5.188,1z" fill="currentColor"></path></svg>
                            </span>
                            @else
                            <span class="absolute -left-[8px] top-0 text-white">
                                <svg viewBox="0 0 8 13" height="13" width="8" preserveAspectRatio="none" version="1.1" transform="scale(-1,1)"><path d="M5.188,1H0v11.193l6.467-8.625 C7.526,2.156,6.958,1,5.188,1z" fill="currentColor"></path></svg>
                            </span>
                            @endif

                            <div class="text-[#111b21] text-[14.2px] leading-[19px] whitespace-pre-wrap">{{ $msg->body }}</div>
                            <div class="flex justify-end items-end gap-1 mt-1 -mb-1 ml-2 select-none">
                                <span class="text-[11px] text-[#667781]">{{ \Carbon\Carbon::parse($msg->created_at)->format('h:i A') }}</span>
                                @if($msg->direction === 'outbound')
                                    <span class="text-[#53bdeb]"><svg class="w-4 h-4" viewBox="0 0 16 11" fill="currentColor"><path d="M11.057 9.993L15.305 4.47 13.91 3.4 10.358 8.016 8.356 6.014 6.96 7.41 9.662 10.111 9.662 10.112 11.057 9.993 11.057 9.993ZM4.673 8.398L7.228 5.842 5.832 4.447 2.578 7.701 0.305 5.429 1.488 4.246 1.488 4.246 1.488 4.246 3.761 6.519 4.673 8.397 4.673 8.398ZM14.161 3.074L12.766 2 8.423 7.647 8.356 7.734 7.027 6.405 7.228 5.842 8.558 7.172 12.016 2.677 12.016 2.677 14.161 3.074 14.161 3.074Z"></path></svg></span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center h-full text-center p-8">
                        <div class="bg-[#f0f2f5] p-6 rounded-full mb-4">
                            <svg class="w-16 h-16 text-[#d1d7db]" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 17h-2v-2h2v2zm2.07-7.75l-.9.92C13.45 12.9 13 13.5 13 15h-2v-.5c0-1.1.45-2.1 1.17-2.83l1.24-1.26c.37-.36.59-.86.59-1.41 0-1.1-.9-2-2-2s-2 .9-2 2H8c0-2.21 1.79-4 4-4s4 1.79 4 4c0 .88-.36 1.68-.93 2.25z"/></svg>
                        </div>
                        <h3 class="text-xl text-[#41525d] font-normal mb-1">لا توجد رسائل بعد</h3>
                        <p class="text-sm text-[#8696a0]">أرسل رسالة لبدء المحادثة وتشغيل الحملة التسويقية.</p>
                    </div>
                @endforelse
            </div>

            <!-- Sticky Footer: Input -->
            <div class="bg-[#f0f2f5] px-4 py-3 flex items-end gap-2 shrink-0 z-20">
                <button class="p-2 text-[#54656f] hover:text-[#41525d] transition">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </button>
                <button class="p-2 text-[#54656f] hover:text-[#41525d] transition">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                </button>
                
                <form action="{{ route('whatsapp.store', $client->id) }}" method="POST" class="flex-1 flex gap-2">
                    @csrf
                    <div class="flex-1 bg-white rounded-lg px-4 py-2 shadow-sm border border-transparent focus-within:border-green-500 focus-within:ring-1 focus-within:ring-green-500 transition flex items-center">
                        <input type="text" name="message" required placeholder="اكتب رسالة" class="w-full bg-transparent border-none text-[#111b21] placeholder-[#8696a0] focus:ring-0 p-0 text-[15px] h-[24px]" autocomplete="off">
                    </div>
                    <button type="submit" class="p-2 bg-[#00a884] text-white rounded-full hover:bg-[#008f6f] shadow-sm transition flex items-center justify-center cursor-pointer">
                        <svg class="w-6 h-6 transform rotate-90 ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"></path></svg>
                    </button>
                </form>
            </div>

        @else
            <!-- Empty State for Main Area -->
            <div class="flex-1 flex flex-col items-center justify-center p-10 text-center bg-[#f0f2f5] border-b-[6px] border-[#25d366]">
                <div class="w-full max-w-md mx-auto">
                     <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/6/6b/WhatsApp.svg/512px-WhatsApp.svg.png" class="w-24 h-24 mx-auto mb-8 opacity-40 grayscale">
                    <h1 class="text-3xl font-light text-[#41525d] mb-4">واتساب الشبكة الذكية</h1>
                    <p class="text-[#667781] text-sm leading-6">
                        أرسل واستقبل الرسائل من المشتركين مباشرة.<br>
                        قم بربط هاتفك للحفاظ على التواصل مع العملاء.<br>
                        استخدم واتساب على ما يصل إلى 4 أجهزة مرتبطة و 1 هاتف في نفس الوقت.
                    </p>
                    <div class="mt-8 flex items-center justify-center gap-2 text-xs text-[#8696a0]">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                        محمي بواسطة التشفير التام بين الطرفين
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Left (Right in RTL): Client Info Panel (w-1/4) -->
    @if(isset($client))
        <div class="w-[320px] bg-white border-r border-gray-200 overflow-y-auto hidden xl:block z-10 shrink-0">
            <!-- Section: Profile -->
            <div class="p-8 flex flex-col items-center border-b border-gray-100 bg-[#f0f2f5]/30">
                <div class="w-32 h-32 bg-gray-200 rounded-full mb-4 flex items-center justify-center text-gray-500 text-3xl font-bold border-4 border-white shadow-sm overflow-hidden">
                    @if(isset($client->profile_photo_url)) 
                        <img src="{{ $c->profile_photo_url }}" class="w-full h-full object-cover">
                    @else
                        {{ substr($client->name ?? $client->username, 0, 1) }}
                    @endif
                </div>
                <h2 class="text-xl font-bold text-gray-800 text-center">{{ $client->name ?? $client->username }}</h2>
                <p class="text-gray-500 text-sm mt-1" dir="ltr">{{ $client->phone ?? 'لا يوجد هاتف' }}</p>
            </div>

            <!-- Section: About -->
            <div class="p-5 border-b border-gray-100">
                <h3 class="text-[#008069] text-sm font-semibold mb-3 uppercase tracking-wider">تفاصيل الاشتراك</h3>
                <div class="space-y-4">
                    <div class="flex items-start gap-3">
                         <div class="mt-0.5 text-gray-400"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg></div>
                         <div>
                             <p class="text-gray-900 font-medium">{{ $client->package->name ?? 'غير محدد' }}</p>
                             <p class="text-xs text-gray-500">الباقة الحالية</p>
                         </div>
                    </div>
                    <div class="flex items-start gap-3">
                         <div class="mt-0.5 text-gray-400"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg></div>
                         <div>
                             <p class="text-gray-900 font-medium">{{ \Carbon\Carbon::parse($client->expires_at)->format('Y-m-d') }}</p>
                             <p class="text-xs text-red-500">انتهاء الاشتراك ({{ \Carbon\Carbon::now()->diffInDays($client->expires_at) }} يوم متبقي)</p>
                         </div>
                    </div>
                </div>
            </div>

            <!-- Section: Technical -->
            <div class="p-5 border-b border-gray-100">
                <h3 class="text-[#008069] text-sm font-semibold mb-3 uppercase tracking-wider">معلومات فنية</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                         <span class="text-sm text-gray-600">IP Address</span>
                         <span class="text-sm font-mono bg-gray-100 px-2 py-1 rounded">{{ $client->ip ?? 'N/A' }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                         <span class="text-sm text-gray-600">البرج</span>
                         <span class="text-sm text-gray-900">{{ $client->tower->name ?? 'غير مرتبط' }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                         <span class="text-sm text-gray-600">الرصيد</span>
                         <span class="text-sm font-bold text-green-600">{{ number_format($client->balance ?? 0, 2) }} {{ $currency }}</span>
                    </div>
                </div>
            </div>

            <!-- Section: Actions -->
            <div class="p-5 space-y-3">
                <button class="w-full py-2.5 bg-[#00a884] text-white rounded-lg hover:bg-[#008f6f] transition font-semibold shadow-sm flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    تجديد الاشتراك
                </button>
                <button class="w-full py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-medium flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    حظر المشترك
                </button>
            </div>
        </div>
    @endif

</div>

<!-- CSS for Scrollbar -->
<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background-color: rgba(0, 0, 0, 0.2);
        border-radius: 20px;
    }
</style>

<script>
    // Auto scroll
    const chatContainer = document.getElementById('chat-messages');
    if(chatContainer) {
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }
</script>
@endsection
