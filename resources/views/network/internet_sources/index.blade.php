@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">مصادر الإنترنت</h1>
            <p class="text-gray-500 mt-1">إدارة خطوط الإنترنت المغذية للشبكة (Fiber, Starlink)</p>
        </div>
        <button @click="$dispatch('open-modal', 'create-source-modal')" class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold rounded-xl shadow-lg transition transform hover:scale-105">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            إضافة مصدر جديد
        </button>
    </div>

    @if(session('success'))
    <div class="bg-emerald-50 border-r-4 border-emerald-500 text-emerald-700 p-4 rounded-lg shadow-sm flex items-center gap-3">
        <svg class="w-6 h-6 flex-shrink-0 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span class="font-bold">{{ session('success') }}</span>
    </div>
    @endif

    <!-- Sources Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($sources as $source)
        <div class="glass relative group rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-xl transition-all duration-300 hover:-translate-y-1 overflow-hidden">
            <!-- Background Decoration -->
            <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-bl from-gray-50 to-transparent rounded-bl-3xl -mr-2 -mt-2"></div>

            <div class="relative z-10">
                <div class="flex justify-between items-start mb-6">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-2xl flex items-center justify-center shadow-inner {{ $source->status === 'online' ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }}">
                            @if(strtolower($source->type) === 'starlink')
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg>
                            @elseif(strtolower($source->type) === 'fiber')
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg> 
                            @else
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            @endif
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 leading-tight">{{ $source->name }}</h3>
                            <div class="flex gap-1">
                                <span class="text-xs font-bold text-indigo-500 bg-indigo-50 px-2 py-0.5 rounded uppercase tracking-wider">{{ $source->type }}</span>
                                @if($source->connection_type)
                                    <span class="text-xs font-bold text-blue-500 bg-blue-50 px-2 py-0.5 rounded uppercase tracking-wider">{{ $source->connection_type }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="flex justify-between items-center p-3 bg-gray-50/50 rounded-xl">
                        <span class="text-gray-500 font-medium text-sm">الحالة</span>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold {{ $source->status === 'online' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                            <span class="w-2 h-2 rounded-full ml-1.5 {{ $source->status === 'online' ? 'bg-emerald-500 animate-pulse' : 'bg-rose-500' }}"></span>
                            {{ $source->status === 'online' ? 'متصل' : 'منقطع' }}
                        </span>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div class="p-3 rounded-xl border border-gray-100 bg-white shadow-sm">
                            <span class="block text-xs text-gray-400 mb-1">السعة (Capacity)</span>
                            <span class="font-mono font-bold text-gray-800 text-lg">{{ $source->capacity ?? '-' }}</span>
                        </div>
                        <div class="p-3 rounded-xl border border-gray-100 bg-white shadow-sm">
                            <span class="block text-xs text-gray-400 mb-1">Gateway IP</span>
                            <span class="font-mono font-bold text-gray-800 dir-ltr text-lg">{{ $source->ip_gateway ?? '-' }}</span>
                        </div>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="mt-6 flex justify-end gap-3 pt-4 border-t border-gray-50">
                    <a href="{{ route('network.internet-sources.edit', $source->id) }}" class="text-indigo-600 hover:text-indigo-800 font-bold text-sm bg-indigo-50 hover:bg-indigo-100 px-4 py-2 rounded-lg transition">
                        تعديل
                    </a>
                    <form action="{{ route('network.internet-sources.destroy', $source->id) }}" method="POST" class="inline-block bg-rose-50 hover:bg-rose-100 rounded-lg px-2" onsubmit="return confirm('هل أنت متأكد من حذف هذا المصدر؟');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-2 text-rose-500 hover:text-rose-700 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </form>
                </div>
            </div>
            
        </div>
        @endforeach

        @if($sources->isEmpty())
        <div class="col-span-full">
            <div class="glass rounded-2xl shadow-sm border border-gray-200 p-12 text-center">
                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6 border-2 border-dashed border-gray-200">
                    <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">لا توجد مصادر إنترنت</h3>
                <p class="text-gray-500 mb-6">قم بإضافة مصدر الإنترنت الرئيسي (Fiber, Starlink) للبدء في المراقبة.</p>
                <button @click="$dispatch('open-modal', 'create-source-modal')" class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    إضافة مصدر جديد
                </button>
            </div>
        </div>
        @endif
    </div>

    <!-- Create Modal -->
    <x-modal name="create-source-modal" title="إضافة مصدر إنترنت جديد">
        <form action="{{ route('network.internet-sources.store') }}" method="POST" class="p-8">
            @csrf
            
            <div class="space-y-6">
                <!-- Source Name -->
                <div class="space-y-2">
                    <label class="block text-sm font-bold text-gray-700">اسم المصدر</label>
                    <div class="relative">
                        <input type="text" name="name" 
                               placeholder="مثال: الخط الرئيسي Fiber 1" 
                               required 
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all font-bold placeholder-gray-400 bg-gray-50/50 focus:bg-white text-gray-800">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 2a8 8 0 100 16 8 8 0 000-16zM5 10a1 1 0 112 0 1 1 0 01-2 0zm4 0a1 1 0 112 0 1 1 0 01-2 0zm4 0a1 1 0 112 0 1 1 0 01-2 0z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Type Selection -->
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-gray-700">النوع</label>
                        <div class="relative">
                            <select name="type" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 bg-gray-50/50 focus:bg-white transition-all appearance-none font-semibold text-gray-700">
                                <option value="fiber">🌐 Fiber Optic</option>
                                <option value="starlink">📡 Starlink</option>
                                <option value="microwave">📶 Microwave Link</option>
                                <option value="4g">📱 4G / LTE</option>
                                <option value="other">🔌 Other</option>
                            </select>
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                        </div>
                    </div>

                    <!-- Connection Type Selection -->
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-gray-700">نوع الوصل (Connection)</label>
                        <div class="relative">
                            <select name="connection_type" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 bg-gray-50/50 focus:bg-white transition-all appearance-none font-semibold text-gray-700">
                                <option value="">غير محدد</option>
                                <option value="fiber">فايبر (Fiber)</option>
                                <option value="cable">كابل (Cable)</option>
                                <option value="wireless">لاسلكي (Wireless)</option>
                            </select>
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                        </div>
                    </div>

                    <!-- Status Selection -->
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-gray-700">الحالة</label>
                        <div class="relative">
                            <select name="status" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 bg-gray-50/50 focus:bg-white transition-all appearance-none font-semibold text-gray-700">
                                <option value="online">✅ متصل (Online)</option>
                                <option value="offline">❌ منقطع (Offline)</option>
                                <option value="maintenance">🛠️ صيانة (Maintenance)</option>
                            </select>
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Capacity -->
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-gray-700">السعة (Capacity)</label>
                        <div class="relative">
                            <input type="text" name="capacity" placeholder="100 Mbps" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all font-mono text-sm dir-ltr text-right bg-gray-50/50 focus:bg-white">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400 font-bold text-xs">Mbps</div>
                        </div>
                    </div>

                    <!-- Gateway IP -->
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-gray-700">Gateway IP</label>
                        <input type="text" name="ip_gateway" placeholder="192.168.1.1" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all font-mono text-sm dir-ltr bg-gray-50/50 focus:bg-white">
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-8 flex justify-end gap-3 pt-6 border-t border-gray-100">
                <button type="button" @click="$dispatch('close-modal', 'create-source-modal')" class="px-6 py-2.5 text-gray-600 hover:bg-gray-100 hover:text-gray-900 rounded-xl font-bold transition duration-200">
                    إلغاء
                </button>
                <button type="submit" class="px-8 py-2.5 bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white rounded-xl font-bold shadow-lg shadow-indigo-200 hover:shadow-xl hover:-translate-y-0.5 transition-all duration-200 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    إضافة المصدر
                </button>
            </div>
        </form>
    </x-modal>

</div>
@endsection
