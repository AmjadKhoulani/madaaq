@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight" style="font-family: 'Cairo', sans-serif;">مصادر الإنترنت</h1>
            <p class="text-sm text-gray-500 mt-1" style="font-family: 'Cairo', sans-serif;">إدارة خطوط الإنترنت المغذية للشبكة (Fiber, Starlink)</p>
        </div>
        <button @click="$dispatch('open-modal', 'create-source-modal')" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-lg shadow-sm transition-all focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2" style="font-family: 'Cairo', sans-serif;">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            إضافة مصدر
        </button>
    </div>

    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg flex items-center gap-3" role="alert">
        <svg class="w-5 h-5 flex-shrink-0 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span class="font-medium text-sm">{{ session('success') }}</span>
    </div>
    @endif

    <!-- Sources Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($sources as $source)
        <div class="group bg-white rounded-lg border border-gray-300 shadow-sm hover:border-indigo-400 hover:shadow-md transition-all duration-200">
            <div class="p-5">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex items-center gap-3">
                        <div class="flex-shrink-0 p-2 rounded-md border border-gray-100 {{ $source->status === 'online' ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }}">
                            @if(strtolower($source->type) === 'starlink')
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg>
                            @elseif(strtolower($source->type) === 'fiber')
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg> 
                            @else
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            @endif
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-gray-900 leading-tight" style="font-family: 'Cairo', sans-serif;">{{ $source->name }}</h3>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-xs font-bold text-gray-600 bg-gray-100 border border-gray-200 px-2 py-0.5 rounded">{{ $source->type }}</span>
                                @if($source->connection_type)
                                    <span class="text-xs font-bold text-gray-600 bg-gray-100 border border-gray-200 px-2 py-0.5 rounded">{{ $source->connection_type }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                     <!-- Status Badge -->
                    @if($source->status === 'online')
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            متصل
                        </span>
                    @elseif($source->status === 'maintenance')
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-orange-50 text-orange-700 border border-orange-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span>
                            صيانة
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                            منقطع
                        </span>
                    @endif
                </div>

                <div class="grid grid-cols-2 gap-4 mt-4 py-3 border-t border-gray-100">
                    <div>
                        <span class="block text-xs font-bold text-gray-500 mb-0.5">السعة</span>
                        <span class="font-mono font-bold text-gray-800 text-sm">{{ $source->capacity ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-bold text-gray-500 mb-0.5">Gateway IP</span>
                        <span class="font-mono font-bold text-gray-800 dir-ltr text-sm">{{ $source->ip_gateway ?? '-' }}</span>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="mt-4 flex items-center justify-end gap-2">
                    <a href="{{ route('network.internet-sources.edit', $source->id) }}" class="text-gray-700 hover:text-indigo-700 text-sm font-bold px-3 py-1.5 rounded-md hover:bg-gray-100 transition-colors border border-transparent hover:border-gray-200">
                        تعديل
                    </a>
                    <form action="{{ route('network.internet-sources.destroy', $source->id) }}" method="POST" class="inline-block" onsubmit="return confirm('هل أنت متأكد من حذف هذا المصدر؟');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-rose-600 hover:text-rose-700 text-sm font-bold px-3 py-1.5 rounded-md hover:bg-rose-50 transition-colors border border-transparent hover:border-rose-100">
                            حذف
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach

        @if($sources->isEmpty())
        <div class="col-span-full">
            <div class="bg-white rounded-lg border border-gray-300 border-dashed p-12 text-center">
                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-gray-200">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-1">لا توجد مصادر إنترنت</h3>
                <p class="text-gray-500 text-sm mb-6">قم بإضافة مصدر الإنترنت الرئيسي للبدء.</p>
                <button @click="$dispatch('open-modal', 'create-source-modal')" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg shadow-sm transition-colors ring-2 ring-indigo-600 ring-offset-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    إضافة مصدر
                </button>
            </div>
        </div>
        @endif
    </div>

    <!-- Create Modal -->
    <x-modal name="create-source-modal" title="إضافة مصدر إنترنت جديد">
        <form action="{{ route('network.internet-sources.store') }}" method="POST" class="p-6">
            @csrf
            
            <div class="space-y-5">
                <!-- Source Name -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">اسم المصدر</label>
                    <input type="text" name="name" 
                           placeholder="مثال: الخط الرئيسي Fiber 1" 
                           required 
                           class="w-full px-4 py-2.5 rounded-lg border border-gray-300 bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors text-sm font-medium placeholder-gray-400 shadow-sm">
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Type Selection -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">النوع</label>
                        <div class="relative">
                            <select name="type" required class="w-full px-4 py-2.5 rounded-lg border border-gray-300 bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors text-sm font-medium text-gray-700 shadow-sm appearance-none">
                                <option value="fiber">🌐 Fiber Optic</option>
                                <option value="starlink">📡 Starlink</option>
                                <option value="microwave">📶 Microwave Link</option>
                                <option value="4g">📱 4G / LTE</option>
                                <option value="other">🔌 Other</option>
                            </select>
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Connection Type Selection -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">نوع الوصل</label>
                        <div class="relative">
                            <select name="connection_type" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors text-sm font-medium text-gray-700 shadow-sm appearance-none">
                                <option value="">غير محدد</option>
                                <option value="fiber">فايبر (Fiber)</option>
                                <option value="cable">كابل (Cable)</option>
                                <option value="wireless">لاسلكي (Wireless)</option>
                            </select>
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Status Selection -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">الحالة</label>
                        <div class="relative">
                            <select name="status" required class="w-full px-4 py-2.5 rounded-lg border border-gray-300 bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors text-sm font-medium text-gray-700 shadow-sm appearance-none">
                                <option value="online">✅ متصل (Online)</option>
                                <option value="offline">❌ منقطع (Offline)</option>
                                <option value="maintenance">🛠️ صيانة (Maintenance)</option>
                            </select>
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Capacity -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">السعة (Capacity)</label>
                        <input type="text" name="capacity" placeholder="100 Mbps" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors text-sm font-medium dir-ltr shadow-sm">
                    </div>
                </div>

                <!-- Gateway IP -->
                <div>
                   <label class="block text-sm font-bold text-gray-700 mb-1">Gateway IP</label>
                   <input type="text" name="ip_gateway" placeholder="192.168.1.1" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors text-sm font-mono font-medium dir-ltr shadow-sm">
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-8 flex justify-end gap-3 pt-5 border-t border-gray-100">
                <button type="button" @click="$dispatch('close-modal', 'create-source-modal')" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                    إلغاء
                </button>
                <button type="submit" class="px-4 py-2 text-sm font-bold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-sm transition-colors">
                    حفظ المصدر
                </button>
            </div>
        </form>
    </x-modal>

</div>
@endsection
