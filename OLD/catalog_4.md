# Source Catalog: cat4

## resources/views/broadband\profiles\create.blade.php

```php
@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto" x-data="{
    name: '',
    speed_down: '',
    speed_up: '',
    duration: '',
    limit: '',
    price: '',
    technology_type: 'fiber'
}">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">إضافة باقة برودباند</h2>
            <p class="text-sm text-gray-500">أنشئ باقة PPPoE جديدة</p>
        </div>
        <a href="{{ route('broadband.profiles.index') }}" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition text-sm">
            إلغاء
        </a>
    </div>

    <form action="{{ route('broadband.profiles.store') }}" method="POST">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
            
            <!-- Main Form -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Basic Info & Speed -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                    <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                        تفاصيل السرعة والتكنولوجيا
                    </h3>
                    <div class="mb-6 p-4 bg-blue-50 rounded-xl border border-blue-100">
                        <label class="block text-xs font-bold text-blue-800 uppercase mb-3">فئة تكنولوجيا الاتصال (Technology Selection)</label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            <label class="cursor-pointer group">
                                <input type="radio" name="technology_type" value="fiber" x-model="technology_type" class="peer sr-only">
                                <div class="text-center py-2 border-2 border-gray-100 rounded-lg peer-checked:border-blue-500 peer-checked:bg-white group-hover:border-blue-200 transition bg-white shadow-sm">
                                    <div class="text-xl mb-1">🌐</div>
                                    <div class="text-[10px] font-bold text-gray-700 uppercase">Fiber (FTTH)</div>
                                </div>
                            </label>
                            <label class="cursor-pointer group">
                                <input type="radio" name="technology_type" value="wireless" x-model="technology_type" class="peer sr-only">
                                <div class="text-center py-2 border-2 border-gray-100 rounded-lg peer-checked:border-blue-500 peer-checked:bg-white group-hover:border-blue-200 transition bg-white shadow-sm">
                                    <div class="text-xl mb-1">📡</div>
                                    <div class="text-[10px] font-bold text-gray-700 uppercase">Wireless (PMP)</div>
                                </div>
                            </label>
                            <label class="cursor-pointer group">
                                <input type="radio" name="technology_type" value="dsl" x-model="technology_type" class="peer sr-only">
                                <div class="text-center py-2 border-2 border-gray-100 rounded-lg peer-checked:border-blue-500 peer-checked:bg-white group-hover:border-blue-200 transition bg-white shadow-sm">
                                    <div class="text-xl mb-1">🔌</div>
                                    <div class="text-[10px] font-bold text-gray-700 uppercase">DSL / Copper</div>
                                </div>
                            </label>
                            <label class="cursor-pointer group">
                                <input type="radio" name="technology_type" value="cable" x-model="technology_type" class="peer sr-only">
                                <div class="text-center py-2 border-2 border-gray-100 rounded-lg peer-checked:border-blue-500 peer-checked:bg-white group-hover:border-blue-200 transition bg-white shadow-sm">
                                    <div class="text-xl mb-1">📺</div>
                                    <div class="text-[10px] font-bold text-gray-700 uppercase">Cable TV</div>
                                </div>
                            </label>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">اسم الباقة</label>
                            <input type="text" name="name" x-model="name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 outline-none" placeholder="مثال: الذهبية 100 ميجا">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">تحميل (Download) Mbps</label>
                                <input type="number" name="speed_down" x-model="speed_down" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 outline-none" placeholder="100">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">رفع (Upload) Mbps</label>
                                <input type="number" name="speed_up" x-model="speed_up" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 outline-none" placeholder="50">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Limits & Price -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                    <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        المدة والتكلفة
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">المدة (أيام)</label>
                            <input type="number" name="duration_days" x-model="duration" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-green-500 outline-none" placeholder="30">
                            <p class="text-[10px] text-gray-400 mt-1">اتركه فارغاً للاشتراك الدائم</p>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">حد البيانات (MB)</label>
                            <input type="number" name="data_limit_mb" x-model="limit" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-green-500 outline-none" placeholder="Unlimited">
                             <p class="text-[10px] text-gray-400 mt-1">1000 MB = 1 GB</p>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">السعر</label>
                            <div class="relative">
                                <input type="number" name="price" x-model="price" step="0.01" required class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-green-500 outline-none" placeholder="0.00">
                                <span class="absolute left-3 top-2.5 text-gray-400 text-sm">$</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Targets -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                    <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        الأجهزة المستهدفة (المزامنة)
                    </h3>
                    <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                        <div class="space-y-4">
                            <div>
                                <h4 class="text-xs font-bold text-gray-500 uppercase mb-2 border-b border-gray-200 pb-1">السيرفرات (MikroTik)</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-40 overflow-y-auto custom-scrollbar">
                                    @forelse($servers as $server)
                                        <label class="flex items-center gap-2 hover:bg-white p-2 rounded-lg cursor-pointer transition border border-transparent hover:border-gray-200">
                                            <input type="checkbox" name="targets[]" value="server_{{ $server->id }}" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 bg-white shadow-sm">
                                            <span class="text-sm font-bold text-gray-700">{{ $server->name }}</span>
                                        </label>
                                    @empty
                                        <span class="text-xs text-gray-400">لا يوجد سيرفرات</span>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Sticky Summary -->
            <div class="lg:col-span-1">
                <div class="sticky top-6">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 text-center">
                        <div class="text-gray-500 text-xs font-bold uppercase tracking-wider mb-1">اسم الباقة</div>
                        <div class="text-xl font-bold text-gray-900 mb-4 break-words" x-text="name || '---'"></div>
                        
                        <div class="text-gray-500 text-xs font-bold uppercase tracking-wider mb-1">السعر</div>
                        <div class="text-4xl font-bold font-mono tracking-tight flex items-center justify-center gap-1 text-green-600">
                            <span x-text="price || '0.00'"></span>
                            <span class="text-2xl">$</span>
                        </div>

                        <div class="mt-6 pt-6 border-t border-gray-100 space-y-3">
                            <div class="flex justify-between items-center bg-gray-50 p-2 rounded">
                                <span class="text-xs font-medium text-gray-500">التكنولوجيا</span>
                                <div class="flex items-center gap-1 font-bold text-gray-700 uppercase text-[10px]">
                                    <span x-show="technology_type === 'fiber'">🌐 Fiber</span>
                                    <span x-show="technology_type === 'wireless'">📡 Wireless</span>
                                    <span x-show="technology_type === 'dsl'">🔌 DSL</span>
                                    <span x-show="technology_type === 'cable'">📺 Cable</span>
                                </div>
                            </div>
                            <div class="flex justify-between items-center bg-gray-50 p-2 rounded">
                                <span class="text-xs font-medium text-gray-500">السرعة</span>
                                <div class="text-sm font-bold dir-ltr text-gray-700">
                                    <span x-text="speed_down || 0"></span>M / <span x-text="speed_up || 0"></span>M
                                </div>
                            </div>
                            <div class="flex justify-between items-center bg-gray-50 p-2 rounded">
                                <span class="text-xs font-medium text-gray-500">البيانات</span>
                                <span class="text-sm font-bold text-gray-700" x-text="limit ? (limit/1024).toFixed(1) + ' GB' : 'غير محدود'"></span>
                            </div>
                            <div class="flex justify-between items-center bg-gray-50 p-2 rounded">
                                <span class="text-xs font-medium text-gray-500">المدة</span>
                                <span class="text-sm font-bold text-gray-700" x-text="duration ? duration + ' يوم' : 'دائم'"></span>
                            </div>
                        </div>

                        <button type="submit" class="w-full mt-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg shadow-sm transition transform active:scale-95">
                            حفظ الباقة
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>
@endsection

```

## resources/views/broadband\profiles\edit.blade.php

```php
@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto" x-data="{
    name: '{{ $profile->name }}',
    speed_down: '{{ $profile->speed_down }}',
    speed_up: '{{ $profile->speed_up }}',
    duration: '{{ $profile->duration_days }}',
    limit: '{{ $profile->data_limit_mb }}',
    price: '{{ $profile->price }}',
    technology_type: '{{ $profile->technology_type ?? 'wireless' }}'
}">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">تعديل باقة برودباند</h2>
            <p class="text-sm text-gray-500">تحديث تفاصيل الباقة: <span class="font-bold">{{ $profile->name }}</span></p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('broadband.profiles.index') }}" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition text-sm">
                إلغاء
            </a>
            <form action="{{ route('broadband.profiles.destroy', $profile) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذه الباقة؟ سيتم إزالتها من المايكروتك أيضاً.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-50 text-red-700 border border-red-200 font-medium rounded-lg hover:bg-red-100 transition text-sm">
                    حذف
                </button>
            </form>
        </div>
    </div>

    <form action="{{ route('broadband.profiles.update', $profile) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
            
            <!-- Main Form -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Basic Info & Speed -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                    <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                        تفاصيل السرعة والتكنولوجيا
                    </h3>
                    <div class="mb-6 p-4 bg-indigo-50 rounded-xl border border-indigo-100">
                        <label class="block text-xs font-bold text-indigo-800 uppercase mb-3">فئة تكنولوجيا الاتصال (Technology Selection)</label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            <label class="cursor-pointer group">
                                <input type="radio" name="technology_type" value="fiber" x-model="technology_type" class="peer sr-only">
                                <div class="text-center py-2 border-2 border-gray-100 rounded-lg peer-checked:border-blue-500 peer-checked:bg-white group-hover:border-blue-200 transition bg-white shadow-sm">
                                    <div class="text-xl mb-1">🌐</div>
                                    <div class="text-[10px] font-bold text-gray-700 uppercase">Fiber (FTTH)</div>
                                </div>
                            </label>
                            <label class="cursor-pointer group">
                                <input type="radio" name="technology_type" value="wireless" x-model="technology_type" class="peer sr-only">
                                <div class="text-center py-2 border-2 border-gray-100 rounded-lg peer-checked:border-blue-500 peer-checked:bg-white group-hover:border-blue-200 transition bg-white shadow-sm">
                                    <div class="text-xl mb-1">📡</div>
                                    <div class="text-[10px] font-bold text-gray-700 uppercase">Wireless (PMP)</div>
                                </div>
                            </label>
                            <label class="cursor-pointer group">
                                <input type="radio" name="technology_type" value="dsl" x-model="technology_type" class="peer sr-only">
                                <div class="text-center py-2 border-2 border-gray-100 rounded-lg peer-checked:border-blue-500 peer-checked:bg-white group-hover:border-blue-200 transition bg-white shadow-sm">
                                    <div class="text-xl mb-1">🔌</div>
                                    <div class="text-[10px] font-bold text-gray-700 uppercase">DSL / Copper</div>
                                </div>
                            </label>
                            <label class="cursor-pointer group">
                                <input type="radio" name="technology_type" value="cable" x-model="technology_type" class="peer sr-only">
                                <div class="text-center py-2 border-2 border-gray-100 rounded-lg peer-checked:border-blue-500 peer-checked:bg-white group-hover:border-blue-200 transition bg-white shadow-sm">
                                    <div class="text-xl mb-1">📺</div>
                                    <div class="text-[10px] font-bold text-gray-700 uppercase">Cable TV</div>
                                </div>
                            </label>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">اسم الباقة</label>
                            <input type="text" name="name" x-model="name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 outline-none">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">تحميل (Download) Mbps</label>
                                <input type="number" name="speed_down" x-model="speed_down" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">رفع (Upload) Mbps</label>
                                <input type="number" name="speed_up" x-model="speed_up" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 outline-none">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Limits & Price -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                    <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        المدة والتكلفة
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">المدة (أيام)</label>
                            <input type="number" name="duration_days" x-model="duration" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-green-500 outline-none" placeholder="30">
                            <p class="text-[10px] text-gray-400 mt-1">اتركه فارغاً للاشتراك الدائم</p>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">حد البيانات (MB)</label>
                            <input type="number" name="data_limit_mb" x-model="limit" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-green-500 outline-none" placeholder="Unlimited">
                             <p class="text-[10px] text-gray-400 mt-1">1000 MB = 1 GB</p>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">السعر</label>
                            <div class="relative">
                                <input type="number" name="price" x-model="price" step="0.01" required class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-green-500 outline-none">
                                <span class="absolute left-3 top-2.5 text-gray-400 text-sm">$</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Targets -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                    <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        الأجهزة المستهدفة (المزامنة)
                    </h3>
                    <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                        <div class="space-y-4">
                            <div>
                                <h4 class="text-xs font-bold text-gray-500 uppercase mb-2 border-b border-gray-200 pb-1">السيرفرات (MikroTik)</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-40 overflow-y-auto custom-scrollbar">
                                    @forelse($servers as $server)
                                        <label class="flex items-center gap-2 hover:bg-white p-2 rounded-lg cursor-pointer transition border border-transparent hover:border-gray-200">
                                            <input type="checkbox" name="targets[]" value="server_{{ $server->id }}" 
                                                {{ $profile->mikrotikServers->contains($server->id) ? 'checked' : '' }}
                                                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 bg-white shadow-sm">
                                            <span class="text-sm font-bold text-gray-700">{{ $server->name }}</span>
                                        </label>
                                    @empty
                                        <span class="text-xs text-gray-400">لا يوجد سيرفرات</span>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Sticky Summary -->
            <div class="lg:col-span-1">
                <div class="sticky top-6">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 text-center">
                        <div class="text-gray-500 text-xs font-bold uppercase tracking-wider mb-1">اسم الباقة</div>
                        <div class="text-xl font-bold text-gray-900 mb-4 break-words" x-text="name || '---'"></div>
                        
                        <div class="text-gray-500 text-xs font-bold uppercase tracking-wider mb-1">السعر</div>
                        <div class="text-4xl font-bold font-mono tracking-tight flex items-center justify-center gap-1 text-green-600">
                            <span x-text="price || '0.00'"></span>
                            <span class="text-2xl">$</span>
                        </div>

                        <div class="mt-6 pt-6 border-t border-gray-100 space-y-3">
                             <div class="flex justify-between items-center bg-gray-50 p-2 rounded">
                                <span class="text-xs font-medium text-gray-500">التكنولوجيا</span>
                                <div class="flex items-center gap-1 font-bold text-gray-700 uppercase text-[10px]">
                                    <span x-show="technology_type === 'fiber'">🌐 Fiber</span>
                                    <span x-show="technology_type === 'wireless'">📡 Wireless</span>
                                    <span x-show="technology_type === 'dsl'">🔌 DSL</span>
                                    <span x-show="technology_type === 'cable'">📺 Cable</span>
                                </div>
                            </div>
                            <div class="flex justify-between items-center bg-gray-50 p-2 rounded">
                                <span class="text-xs font-medium text-gray-500">السرعة</span>
                                <div class="text-sm font-bold dir-ltr text-gray-700">
                                    <span x-text="speed_down || 0"></span>M / <span x-text="speed_up || 0"></span>M
                                </div>
                            </div>
                            <div class="flex justify-between items-center bg-gray-50 p-2 rounded">
                                <span class="text-xs font-medium text-gray-500">البيانات</span>
                                <span class="text-sm font-bold text-gray-700" x-text="limit ? (limit/1024).toFixed(1) + ' GB' : 'غير محدود'"></span>
                            </div>
                            <div class="flex justify-between items-center bg-gray-50 p-2 rounded">
                                <span class="text-xs font-medium text-gray-500">المدة</span>
                                <span class="text-sm font-bold text-gray-700" x-text="duration ? duration + ' يوم' : 'دائم'"></span>
                            </div>
                        </div>

                        <button type="submit" class="w-full mt-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg shadow-sm transition transform active:scale-95">
                            تحديث الباقة
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>
@endsection

```

## resources/views/broadband\profiles\index.blade.php

```php
@extends('layouts.app')

@section('content')
<div class="sm:flex sm:items-center sm:justify-between mb-8">
    <div>
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">باقات البرودباند</h2>
        <p class="mt-1 text-sm text-gray-500">إدارة باقات PPPoE والتحكم بالسرعات والأسعار.</p>
    </div>
    <div class="mt-4 sm:ml-4 sm:mt-0 sm:flex-none">
        <a href="{{ route('broadband.profiles.create') }}" class="block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition-colors">
            <span class="flex items-center gap-2">
                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z"/></svg>
                إضافة باقة جديدة
            </span>
        </a>
    </div>
</div>

<div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
    @if($profiles->count() > 0)
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-300">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="py-3.5 pl-4 pr-4 text-right text-sm font-semibold text-gray-900 sm:pr-6">اسم الباقة</th>
                    <th scope="col" class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900">التكنولوجيا</th>
                    <th scope="col" class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900">السرعة (Download/Upload)</th>
                    <th scope="col" class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900">سعة التحميل</th>
                    <th scope="col" class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900">المدة</th>
                    <th scope="col" class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900">السعر</th>
                    <th scope="col" class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900">الأجهزة المستهدفة</th>
                    <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                        <span class="sr-only">إجراءات</span>
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                @foreach($profiles as $profile)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="whitespace-nowrap py-4 pl-4 pr-4 text-sm font-medium text-gray-900 sm:pr-6">
                        <div class="flex items-center gap-3">
                            <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            </div>
                            {{ $profile->name }}
                        </div>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                        <div class="flex items-center gap-1.5 uppercase font-bold text-[10px]">
                            @if($profile->technology_type === 'fiber')
                                <span class="bg-blue-50 text-blue-700 px-2 py-0.5 rounded border border-blue-100">🌐 Fiber</span>
                            @elseif($profile->technology_type === 'wireless')
                                <span class="bg-orange-50 text-orange-700 px-2 py-0.5 rounded border border-orange-100">📡 Wireless</span>
                            @elseif($profile->technology_type === 'dsl')
                                <span class="bg-gray-50 text-gray-700 px-2 py-0.5 rounded border border-gray-100">🔌 DSL</span>
                            @elseif($profile->technology_type === 'cable')
                                <span class="bg-purple-50 text-purple-700 px-2 py-0.5 rounded border border-purple-100">📺 Cable</span>
                            @else
                                <span class="text-gray-400">---</span>
                            @endif
                        </div>
                    </td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500" dir="ltr">
                        <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">{{ $profile->speed_down }}M</span>
                        <span class="text-gray-400 mx-1">/</span>
                        <span class="inline-flex items-center rounded-md bg-yellow-50 px-2 py-1 text-xs font-medium text-yellow-800 ring-1 ring-inset ring-yellow-600/20">{{ $profile->speed_up }}M</span>
                    </td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                        @if($profile->data_limit_mb)
                            {{ number_format($profile->data_limit_mb / 1024, 1) }} GB
                        @else
                            <span class="text-gray-400">غير محدود</span>
                        @endif
                    </td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                        @if($profile->duration_days)
                            {{ $profile->duration_days }} يوم
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm font-bold text-gray-900">
                        {{ number_format($profile->price, 0) }} {{ $currency ?? 'ر.ي' }}
                    </td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                        @php
                            $targetCount = $profile->routers->count() + $profile->mikrotikServers->count();
                        @endphp
                        @if($targetCount > 0)
                            <span class="inline-flex items-center rounded-full bg-indigo-50 px-2 py-1 text-xs font-medium text-indigo-700 ring-1 ring-inset ring-indigo-700/10">
                                {{ $targetCount }} أجهزة
                            </span>
                        @else
                           <span class="text-gray-400 text-xs">غير محدد</span>
                        @endif
                    </td>
                    <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-left text-sm font-medium sm:pr-6">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('broadband.profiles.edit', $profile) }}" class="text-indigo-600 hover:text-indigo-900 p-1 rounded hover:bg-gray-100 transition" title="تعديل">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            <form action="{{ route('broadband.profiles.destroy', $profile) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذه الباقة؟ سيتم إزالتها من المايكروتيك أيضاً.')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 p-1 rounded hover:bg-gray-100 transition" title="حذف">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
            <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
        </svg>
        <h3 class="mt-2 text-sm font-semibold text-gray-900">لا توجد باقات</h3>
        <p class="mt-1 text-sm text-gray-500">ابدأ بإنشاء باقة برودباند جديدة.</p>
        <div class="mt-6">
            <a href="{{ route('broadband.profiles.create') }}" class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                <svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z"/></svg>
                باقة جديدة
            </a>
        </div>
    </div>
    @endif
</div>
@endsection

```

## resources/views/broadband\users\create.blade.php

```php
@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">إضافة مشترك برودباند</h2>
        <p class="text-gray-500 mt-1">إنشاء مستخدم PPPoE جديد مع إمكانية تخصيص الباقة</p>
    </div>

    <form action="{{ route('broadband.users.store') }}" method="POST" class="space-y-6" x-data="userForm()">
        @csrf
        
        <!-- Customer Selection -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6" x-data="{ mode: 'existing', selectedPhone: '' }">
            <h3 class="text-lg font-bold text-gray-900 mb-4">بيانات العميل</h3>
            
            <div class="flex gap-4 mb-6">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="mode" value="existing" x-model="mode" class="text-blue-600 focus:ring-blue-500">
                    <span class="font-semibold text-gray-700">عميل حالي</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="mode" value="new" x-model="mode" class="text-blue-600 focus:ring-blue-500">
                    <span class="font-semibold text-gray-700">عميل جديد</span>
                </label>
            </div>

            <!-- Existing Customer -->
            <div x-show="mode === 'existing'">
                <label class="block text-sm font-semibold text-gray-700 mb-2">اختر العميل</label>
                <select name="customer_id" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 select2" @change="selectedPhone = $event.target.options[$event.target.selectedIndex].dataset.phone || ''">
                    <option value="">-- ابحث عن عميل (الاسم أو الهاتف) --</option>
                    @foreach($customers as $customer)
                    <option value="{{ $customer->id }}" data-phone="{{ $customer->phone }}">{{ $customer->name }} - {{ $customer->phone }}</option>
                    @endforeach
                </select>
                <!-- Read-only Phone Display -->
                <div class="mt-3" x-show="selectedPhone" x-transition>
                    <label class="block text-xs font-semibold text-gray-500 mb-1">رقم الهاتف المسجل:</label>
                    <div class="flex items-center gap-2 text-gray-900 bg-gray-50 px-3 py-2 rounded-lg border border-gray-200 w-fit">
                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        <span x-text="selectedPhone" class="font-mono font-medium"></span>
                    </div>
                </div>
            </div>

            <!-- New Customer -->
            <div x-show="mode === 'new'" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">اسم العميل *</label>
                    <input type="text" name="name" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="الاسم الكامل">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">رقم الهاتف *</label>
                    <input type="text" name="phone" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="05xxxxxxxx">
                </div>
            </div>
        </div>

        <!-- Account Info -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-4">
            <h3 class="text-lg font-bold text-gray-900 mb-4">معلومات الحساب (الاشتراك)</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Tower Selection -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">البرج *</label>
                    <select name="tower_id" x-model="selectedTower" @change="updateSsids()" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                        <option value="">-- اختر البرج --</option>
                        @foreach($towers as $tower)
                        <option value="{{ $tower->id }}" :data-ssids="JSON.stringify({{ $tower->ssids->map(function($ssid) { 
                            return ['id' => $ssid->id, 'name' => $ssid->ssid_name, 'router_id' => $ssid->router_id ?? '']; 
                        }) }})">
                            🗼 {{ $tower->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- SSID Selection -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">شبكة WiFi (SSID) *</label>
                    <select name="ssid_id" x-model="selectedSsid" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required :disabled="!selectedTower">
                        <option value="">-- اختر الشبكة --</option>
                        <template x-for="ssid in availableSsids" :key="ssid.id">
                            <option :value="ssid.id" x-text="ssid.name"></option>
                        </template>
                    </select>
                </div>

                <!-- IP Address -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">IP Address (مخصص)</label>
                    <input type="text" name="ip" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="10.x.x.x (اختياري)">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">اسم المستخدم *</label>
                    <input type="text" name="username" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" placeholder="user123">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">كلمة المرور *</label>
                    <input type="text" name="password" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" value="{{ Str::random(10) }}">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">الباقة الأساسية</label>
                <select name="package_id" x-model="packageId" @change="loadPackageDefaults()" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    <option value="">-- اختر الباقة --</option>
                    @foreach($packages as $package)
                        <option value="{{ $package->id }}" 
                                data-speed-down="{{ $package->speed_down }}"
                                data-speed-up="{{ $package->speed_up }}"
                                data-duration="{{ $package->duration_days }}" 
                                data-limit="{{ $package->data_limit_mb }}"
                                data-price="{{ $package->price }}">
                            {{ $package->name }} ({{ $package->speed_down }}M/{{ $package->speed_up }}M)
                        </option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-500 mt-1">يمكنك تخصيص القيم في الأسفل</p>
            </div>
        </div>

        <!-- Custom Settings -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-4">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900">تخصيص الباقة</h3>
                <span class="text-xs text-gray-500 bg-gray-100 px-3 py-1 rounded-full">اختياري</span>
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">المدة (يوم)</label>
                    <input type="number" name="custom_duration_days" x-model="customDuration" @input="updateExpiry()" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" placeholder="31">
                    <p class="text-xs text-gray-500 mt-1" x-show="packageDuration">
                        افتراضي: <span x-text="packageDuration"></span> يوم
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">حجم البيانات (MB)</label>
                    <input type="number" name="custom_data_limit_mb" x-model="customDataLimit" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" placeholder="1000">
                    <p class="text-xs text-gray-500 mt-1" x-show="packageDataLimit">
                        افتراضي: <span x-text="(packageDataLimit / 1024).toFixed(1)"></span> GB
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">السعر ({{ $currency }})</label>
                    <input type="number" step="0.01" name="custom_price" x-model="customPrice" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" placeholder="150.00">
                    <p class="text-xs text-gray-500 mt-1" x-show="packagePrice">
                        افتراضي: <span x-text="packagePrice"></span>
                    </p>
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">تاريخ الانتهاء</label>
                <input type="date" name="expires_at" x-model="expiryDate" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                <p class="text-xs text-gray-500 mt-1">سيتم حسابه تلقائياً بناءً على المدة</p>
            </div>
        </div>

        <!-- Summary Card -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-200 p-6" x-show="packageId">
            <h4 class="font-bold text-gray-900 mb-3">ملخص الاشتراك</h4>
            <div class="grid grid-cols-2 gap-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">المدة:</span>
                    <span class="font-semibold text-gray-900" x-text="(customDuration || packageDuration || '-') + ' يوم'"></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">حجم البيانات:</span>
                    <span class="font-semibold text-gray-900" x-text="((customDataLimit || packageDataLimit) ? ((customDataLimit || packageDataLimit) / 1024).toFixed(1) + ' GB' : 'غير محدود')"></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">السعر:</span>
                    <span class="font-bold text-blue-600 text-base" x-text="(customPrice || packagePrice || 0) + ' {{ $currency }}'"></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">ينتهي في:</span>
                    <span class="font-semibold text-gray-900" x-text="expiryDate || '-'"></span>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-sm transition">
                حفظ المشترك
            </button>
            <a href="{{ route('broadband.users.index') }}" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg transition">
                إلغاء
            </a>
        </div>
    </form>
</div>

<script>
function userForm() {
    return {
        packageId: '',
        packageDuration: null,
        packageDataLimit: null,
        packagePrice: null,
        customDuration: '',
        customDataLimit: '',
        customPrice: '',
        expiryDate: '',
        
        selectedTower: '',
        selectedSsid: '',
        availableSsids: [],

        updateSsids() {
            const select = document.querySelector('select[name="tower_id"]');
            const option = select.options[select.selectedIndex];
            if (option && option.dataset.ssids) {
                this.availableSsids = JSON.parse(option.dataset.ssids);
            } else {
                this.availableSsids = [];
            }
            this.selectedSsid = '';
        },

        loadPackageDefaults() {
            const select = document.querySelector('select[name="package_id"]');
            const option = select.options[select.selectedIndex];
            
            if (option.value) {
                this.packageDuration = option.dataset.duration || null;
                this.packageDataLimit = option.dataset.limit || null;
                this.packagePrice = option.dataset.price || null;
                
                // Auto-calculate expiry if duration exists
                if (this.packageDuration) {
                    this.updateExpiry();
                }
            }
        },

        updateExpiry() {
            const duration = this.customDuration || this.packageDuration;
            if (duration && duration > 0) {
                const today = new Date();
                today.setDate(today.getDate() + parseInt(duration));
                this.expiryDate = today.toISOString().split('T')[0];
            }
        }
    }
}
</script>
@endsection

```

## resources/views/broadband\users\index.blade.php

```php
@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">مشتركي البرودباند</h2>
            <p class="text-gray-500 mt-1">إدارة مستخدمي PPPoE</p>
        </div>
        <a href="{{ route('broadband.users.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-sm transition">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            إضافة مشترك جديد
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-600 uppercase">اسم المستخدم</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-600 uppercase">الباقة</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-600 uppercase">الحالة</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-600 uppercase">تاريخ الانتهاء</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-600 uppercase">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                    <span class="text-blue-600 font-bold text-sm">{{ substr($user->username, 0, 2) }}</span>
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900">{{ $user->username }}</div>
                                    @if($user->name)
                                        <div class="text-xs text-gray-500">{{ $user->name }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                {{ $user->package->name ?? 'لا يوجد' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($user->status === 'active')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full ml-1.5"></span>
                                    نشط
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    <span class="w-1.5 h-1.5 bg-gray-500 rounded-full ml-1.5"></span>
                                    غير نشط
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $user->expires_at ? $user->expires_at->format('Y-m-d') : 'غير محدد' }}
                        </td>
                        <td class="px-6 py-4">
                            <form action="{{ route('broadband.users.destroy', $user) }}" method="POST" onsubmit="return confirm('هل أنت متأكد؟')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 font-medium text-sm">حذف</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                </div>
                                <p class="text-gray-500 font-medium">لا يوجد مشتركين</p>
                                <a href="{{ route('broadband.users.create') }}" class="text-blue-600 hover:text-blue-700 font-semibold text-sm">أضف مشتركك الأول</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

```

## resources/views/hotspot\profiles\create.blade.php

```php
@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto" x-data="{
    name: '',
    speed_down: '',
    speed_up: '',
    price: '',
    technology_type: 'wireless'
}">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">إضافة باقة هوت سبوت</h2>
            <p class="text-sm text-gray-500">أنشئ باقة نقطة وصول جديدة</p>
        </div>
        <a href="{{ route('hotspot.profiles.index') }}" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition text-sm">
            إلغاء
        </a>
    </div>

    <form action="{{ route('hotspot.profiles.store') }}" method="POST">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
            
            <!-- Main Form -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Basic Info & Speed -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                    <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                        تفاصيل الباقة والتكنولوجيا
                    </h3>
                    <div class="mb-6 p-4 bg-purple-50 rounded-xl border border-purple-100">
                        <label class="block text-xs font-bold text-purple-800 uppercase mb-3">فئة تكنولوجيا الاتصال (Technology Selection)</label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            <label class="cursor-pointer group">
                                <input type="radio" name="technology_type" value="fiber" x-model="technology_type" class="peer sr-only">
                                <div class="text-center py-2 border-2 border-gray-100 rounded-lg peer-checked:border-purple-500 peer-checked:bg-white group-hover:border-purple-200 transition bg-white shadow-sm">
                                    <div class="text-xl mb-1">🌐</div>
                                    <div class="text-[10px] font-bold text-gray-700 uppercase">Fiber (FTTH)</div>
                                </div>
                            </label>
                            <label class="cursor-pointer group">
                                <input type="radio" name="technology_type" value="wireless" x-model="technology_type" class="peer sr-only" checked>
                                <div class="text-center py-2 border-2 border-gray-100 rounded-lg peer-checked:border-purple-500 peer-checked:bg-white group-hover:border-purple-200 transition bg-white shadow-sm">
                                    <div class="text-xl mb-1">📡</div>
                                    <div class="text-[10px] font-bold text-gray-700 uppercase">Wireless (PMP)</div>
                                </div>
                            </label>
                            <label class="cursor-pointer group">
                                <input type="radio" name="technology_type" value="dsl" x-model="technology_type" class="peer sr-only">
                                <div class="text-center py-2 border-2 border-gray-100 rounded-lg peer-checked:border-purple-500 peer-checked:bg-white group-hover:border-blue-200 transition bg-white shadow-sm">
                                    <div class="text-xl mb-1">🔌</div>
                                    <div class="text-[10px] font-bold text-gray-700 uppercase">DSL / Copper</div>
                                </div>
                            </label>
                            <label class="cursor-pointer group">
                                <input type="radio" name="technology_type" value="cable" x-model="technology_type" class="peer sr-only">
                                <div class="text-center py-2 border-2 border-gray-100 rounded-lg peer-checked:border-purple-500 peer-checked:bg-white group-hover:border-purple-200 transition bg-white shadow-sm">
                                    <div class="text-xl mb-1">📺</div>
                                    <div class="text-[10px] font-bold text-gray-700 uppercase">Cable TV</div>
                                </div>
                            </label>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">اسم الباقة</label>
                            <input type="text" name="name" x-model="name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-purple-500 outline-none" placeholder="مثال: باقة 50 ميجا">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">تحميل (Download) Mbps</label>
                                <input type="number" name="speed_down" x-model="speed_down" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-purple-500 outline-none" placeholder="50">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">رفع (Upload) Mbps</label>
                                <input type="number" name="speed_up" x-model="speed_up" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-purple-500 outline-none" placeholder="25">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Price -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                    <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        التكلفة
                    </h3>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">السعر</label>
                        <div class="relative">
                            <input type="number" name="price" x-model="price" step="0.01" required class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-green-500 outline-none" placeholder="75.00">
                            <span class="absolute left-3 top-2.5 text-gray-400 text-sm">$</span>
                        </div>
                    </div>
                </div>

                <!-- Targets -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                    <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        الأجهزة المستهدفة (المزامنة)
                    </h3>
                    <div class="bg-purple-50 rounded-lg border border-purple-200 p-4">
                        <div class="space-y-4">
                            <div>
                                <h4 class="text-xs font-bold text-gray-500 uppercase mb-2 border-b border-purple-200 pb-1">السيرفرات (MikroTik)</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-40 overflow-y-auto custom-scrollbar">
                                    @forelse($servers as $server)
                                        <label class="flex items-center gap-2 hover:bg-white p-2 rounded-lg cursor-pointer transition border border-transparent hover:border-purple-100">
                                            <input type="checkbox" name="targets[]" value="server_{{ $server->id }}" class="rounded border-gray-300 text-purple-600 focus:ring-purple-500 bg-white shadow-sm">
                                            <span class="text-sm font-bold text-gray-700">{{ $server->name }}</span>
                                        </label>
                                    @empty
                                        <span class="text-xs text-gray-400">لا يوجد سيرفرات</span>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Sticky Summary -->
            <div class="lg:col-span-1">
                <div class="sticky top-6">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 text-center">
                        <div class="text-gray-500 text-xs font-bold uppercase tracking-wider mb-1">اسم الباقة</div>
                        <div class="text-xl font-bold text-gray-900 mb-4 break-words" x-text="name || '---'"></div>
                        
                        <div class="text-gray-500 text-xs font-bold uppercase tracking-wider mb-1">السعر</div>
                        <div class="text-4xl font-bold font-mono tracking-tight flex items-center justify-center gap-1 text-green-600">
                            <span x-text="price || '0.00'"></span>
                            <span class="text-2xl">$</span>
                        </div>

                        <div class="mt-6 pt-6 border-t border-gray-100 space-y-3">
                            <div class="flex justify-between items-center bg-gray-50 p-2 rounded">
                                <span class="text-xs font-medium text-gray-500">التكنولوجيا</span>
                                <div class="flex items-center gap-1 font-bold text-gray-700 uppercase text-[10px]">
                                    <span x-show="technology_type === 'fiber'">🌐 Fiber</span>
                                    <span x-show="technology_type === 'wireless'">📡 Wireless</span>
                                    <span x-show="technology_type === 'dsl'">🔌 DSL</span>
                                    <span x-show="technology_type === 'cable'">📺 Cable</span>
                                </div>
                            </div>
                            <div class="flex justify-between items-center bg-gray-50 p-2 rounded">
                                <span class="text-xs font-medium text-gray-500">السرعة</span>
                                <div class="text-sm font-bold dir-ltr text-gray-700">
                                    <span x-text="speed_down || 0"></span>M / <span x-text="speed_up || 0"></span>M
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="w-full mt-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-lg shadow-sm transition transform active:scale-95">
                            حفظ الباقة
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>
@endsection

```

## resources/views/hotspot\profiles\edit.blade.php

```php
@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto" x-data="{
    name: '{{ $profile->name }}',
    speed_down: '{{ $profile->speed_down }}',
    speed_up: '{{ $profile->speed_up }}',
    price: '{{ $profile->price }}',
    technology_type: '{{ $profile->technology_type ?? 'wireless' }}'
}">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">تعديل باقة هوت سبوت</h2>
            <p class="text-sm text-gray-500">تحديث تفاصيل الباقة: <span class="font-bold">{{ $profile->name }}</span></p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('hotspot.profiles.index') }}" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition text-sm">
                إلغاء
            </a>
            <form action="{{ route('hotspot.profiles.destroy', $profile) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذه الباقة؟ سيتم إزالتها من المايكروتك أيضاً.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-50 text-red-700 border border-red-200 font-medium rounded-lg hover:bg-red-100 transition text-sm">
                    حذف
                </button>
            </form>
        </div>
    </div>

    <form action="{{ route('hotspot.profiles.update', $profile) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
            
            <!-- Main Form -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Basic Info & Speed -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                    <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                        تفاصيل الباقة والتكنولوجيا
                    </h3>
                    <div class="mb-6 p-4 bg-purple-50 rounded-xl border border-purple-100">
                        <label class="block text-xs font-bold text-purple-800 uppercase mb-3">فئة تكنولوجيا الاتصال (Technology Selection)</label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            <label class="cursor-pointer group">
                                <input type="radio" name="technology_type" value="fiber" x-model="technology_type" class="peer sr-only">
                                <div class="text-center py-2 border-2 border-gray-100 rounded-lg peer-checked:border-purple-500 peer-checked:bg-white group-hover:border-purple-200 transition bg-white shadow-sm">
                                    <div class="text-xl mb-1">🌐</div>
                                    <div class="text-[10px] font-bold text-gray-700 uppercase">Fiber (FTTH)</div>
                                </div>
                            </label>
                            <label class="cursor-pointer group">
                                <input type="radio" name="technology_type" value="wireless" x-model="technology_type" class="peer sr-only">
                                <div class="text-center py-2 border-2 border-gray-100 rounded-lg peer-checked:border-purple-500 peer-checked:bg-white group-hover:border-purple-200 transition bg-white shadow-sm">
                                    <div class="text-xl mb-1">📡</div>
                                    <div class="text-[10px] font-bold text-gray-700 uppercase">Wireless (PMP)</div>
                                </div>
                            </label>
                            <label class="cursor-pointer group">
                                <input type="radio" name="technology_type" value="dsl" x-model="technology_type" class="peer sr-only">
                                <div class="text-center py-2 border-2 border-gray-100 rounded-lg peer-checked:border-purple-500 peer-checked:bg-white group-hover:border-blue-200 transition bg-white shadow-sm">
                                    <div class="text-xl mb-1">🔌</div>
                                    <div class="text-[10px] font-bold text-gray-700 uppercase">DSL / Copper</div>
                                </div>
                            </label>
                            <label class="cursor-pointer group">
                                <input type="radio" name="technology_type" value="cable" x-model="technology_type" class="peer sr-only">
                                <div class="text-center py-2 border-2 border-gray-100 rounded-lg peer-checked:border-purple-500 peer-checked:bg-white group-hover:border-purple-200 transition bg-white shadow-sm">
                                    <div class="text-xl mb-1">📺</div>
                                    <div class="text-[10px] font-bold text-gray-700 uppercase">Cable TV</div>
                                </div>
                            </label>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">اسم الباقة</label>
                            <input type="text" name="name" x-model="name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-purple-500 outline-none">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">تحميل (Download) Mbps</label>
                                <input type="number" name="speed_down" x-model="speed_down" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-purple-500 outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">رفع (Upload) Mbps</label>
                                <input type="number" name="speed_up" x-model="speed_up" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-purple-500 outline-none">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Price -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                    <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        التكلفة
                    </h3>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">السعر</label>
                        <div class="relative">
                            <input type="number" name="price" x-model="price" step="0.01" required class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-green-500 outline-none">
                            <span class="absolute left-3 top-2.5 text-gray-400 text-sm">$</span>
                        </div>
                    </div>
                </div>

                <!-- Targets -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                    <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        الأجهزة المستهدفة (المزامنة)
                    </h3>
                    <div class="bg-purple-50 rounded-lg border border-purple-200 p-4">
                        <div class="space-y-4">
                            <div>
                                <h4 class="text-xs font-bold text-gray-500 uppercase mb-2 border-b border-purple-200 pb-1">السيرفرات (MikroTik)</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-40 overflow-y-auto custom-scrollbar">
                                    @forelse($servers as $server)
                                        <label class="flex items-center gap-2 hover:bg-white p-2 rounded-lg cursor-pointer transition border border-transparent hover:border-purple-100">
                                            <input type="checkbox" name="targets[]" value="server_{{ $server->id }}" 
                                                {{ $profile->mikrotikServers->contains($server->id) ? 'checked' : '' }}
                                                class="rounded border-gray-300 text-purple-600 focus:ring-purple-500 bg-white shadow-sm">
                                            <span class="text-sm font-bold text-gray-700">{{ $server->name }}</span>
                                        </label>
                                    @empty
                                        <span class="text-xs text-gray-400">لا يوجد سيرفرات</span>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Sticky Summary -->
            <div class="lg:col-span-1">
                <div class="sticky top-6">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 text-center">
                        <div class="text-gray-500 text-xs font-bold uppercase tracking-wider mb-1">اسم الباقة</div>
                        <div class="text-xl font-bold text-gray-900 mb-4 break-words" x-text="name || '---'"></div>
                        
                        <div class="text-gray-500 text-xs font-bold uppercase tracking-wider mb-1">السعر</div>
                        <div class="text-4xl font-bold font-mono tracking-tight flex items-center justify-center gap-1 text-green-600">
                            <span x-text="price || '0.00'"></span>
                            <span class="text-2xl">$</span>
                        </div>

                        <div class="mt-6 pt-6 border-t border-gray-100 space-y-3">
                            <div class="flex justify-between items-center bg-gray-50 p-2 rounded">
                                <span class="text-xs font-medium text-gray-500">التكنولوجيا</span>
                                <div class="flex items-center gap-1 font-bold text-gray-700 uppercase text-[10px]">
                                    <span x-show="technology_type === 'fiber'">🌐 Fiber</span>
                                    <span x-show="technology_type === 'wireless'">📡 Wireless</span>
                                    <span x-show="technology_type === 'dsl'">🔌 DSL</span>
                                    <span x-show="technology_type === 'cable'">📺 Cable</span>
                                </div>
                            </div>
                            <div class="flex justify-between items-center bg-gray-50 p-2 rounded">
                                <span class="text-xs font-medium text-gray-500">السرعة</span>
                                <div class="text-sm font-bold dir-ltr text-gray-700">
                                    <span x-text="speed_down || 0"></span>M / <span x-text="speed_up || 0"></span>M
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="w-full mt-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-lg shadow-sm transition transform active:scale-95">
                            تحديث الباقة
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>
@endsection

```

## resources/views/hotspot\profiles\index.blade.php

```php
@extends('layouts.app')

@section('content')
<div class="sm:flex sm:items-center sm:justify-between mb-8">
    <div>
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">باقات الهوت سبوت</h2>
        <p class="mt-1 text-sm text-gray-500">إدارة باقات نقاط الوصول اللاسلكية والتحكم بالسرعات.</p>
    </div>
    <div class="mt-4 sm:ml-4 sm:mt-0 sm:flex-none">
        <a href="{{ route('hotspot.profiles.create') }}" class="block rounded-md bg-purple-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-purple-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-purple-600 transition-colors">
            <span class="flex items-center gap-2">
                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z"/></svg>
                إضافة باقة جديدة
            </span>
        </a>
    </div>
</div>

<div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
    @if($profiles->count() > 0)
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-300">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="py-3.5 pl-4 pr-4 text-right text-sm font-semibold text-gray-900 sm:pr-6">اسم الباقة</th>
                    <th scope="col" class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900">التكنولوجيا</th>
                    <th scope="col" class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900">السرعة (Download/Upload)</th>
                    <th scope="col" class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900">سعة التحميل</th>
                     <th scope="col" class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900">المدة</th>
                    <th scope="col" class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900">السعر</th>
                    <th scope="col" class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900">الأجهزة المستهدفة</th>
                    <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                        <span class="sr-only">إجراءات</span>
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                @foreach($profiles as $profile)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="whitespace-nowrap py-4 pl-4 pr-4 text-sm font-medium text-gray-900 sm:pr-6">
                        <div class="flex items-center gap-3">
                            <div class="h-8 w-8 rounded-full bg-purple-100 flex items-center justify-center text-purple-600">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/></svg>
                            </div>
                            {{ $profile->name }}
                        </div>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                        <div class="flex items-center gap-1.5 uppercase font-bold text-[10px]">
                            @if($profile->technology_type === 'fiber')
                                <span class="bg-blue-50 text-blue-700 px-2 py-0.5 rounded border border-blue-100">🌐 Fiber</span>
                            @elseif($profile->technology_type === 'wireless')
                                <span class="bg-orange-50 text-orange-700 px-2 py-0.5 rounded border border-orange-100">📡 Wireless</span>
                            @elseif($profile->technology_type === 'dsl')
                                <span class="bg-gray-50 text-gray-700 px-2 py-0.5 rounded border border-gray-100">🔌 DSL</span>
                            @elseif($profile->technology_type === 'cable')
                                <span class="bg-purple-50 text-purple-700 px-2 py-0.5 rounded border border-purple-100">📺 Cable</span>
                            @else
                                <span class="bg-orange-50 text-orange-700 px-2 py-0.5 rounded border border-orange-100">📡 Wireless</span>
                            @endif
                        </div>
                    </td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500" dir="ltr">
                        <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">{{ $profile->speed_down }}M</span>
                        <span class="text-gray-400 mx-1">/</span>
                        <span class="inline-flex items-center rounded-md bg-yellow-50 px-2 py-1 text-xs font-medium text-yellow-800 ring-1 ring-inset ring-yellow-600/20">{{ $profile->speed_up }}M</span>
                    </td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                        @if($profile->data_limit_mb)
                            {{ number_format($profile->data_limit_mb / 1024, 1) }} GB
                        @else
                            <span class="text-gray-400">غير محدود</span>
                        @endif
                    </td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                        @if($profile->duration_days)
                            {{ $profile->duration_days }} يوم
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm font-bold text-gray-900">
                        {{ number_format($profile->price, 0) }} {{ $currency ?? 'ر.ي' }}
                    </td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                         @php
                            $targetCount = $profile->routers->count() + $profile->mikrotikServers->count();
                        @endphp
                        @if($targetCount > 0)
                            <span class="inline-flex items-center rounded-full bg-indigo-50 px-2 py-1 text-xs font-medium text-indigo-700 ring-1 ring-inset ring-indigo-700/10">
                                {{ $targetCount }} أجهزة
                            </span>
                        @else
                           <span class="text-gray-400 text-xs">غير محدد</span>
                        @endif
                    </td>
                    <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-left text-sm font-medium sm:pr-6">
                        <div class="flex items-center justify-end gap-2">
                             <a href="{{ route('hotspot.profiles.edit', $profile) }}" class="text-indigo-600 hover:text-indigo-900 p-1 rounded hover:bg-gray-100 transition" title="تعديل">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            <form action="{{ route('hotspot.profiles.destroy', $profile) }}" method="POST" onsubmit="return confirm('هل أنت متأكد؟')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 p-1 rounded hover:bg-gray-100 transition" title="حذف">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
            <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/>
        </svg>
        <h3 class="mt-2 text-sm font-semibold text-gray-900">لا توجد باقات</h3>
        <p class="mt-1 text-sm text-gray-500">ابدأ بإنشاء باقة هوت سبوت جديدة.</p>
        <div class="mt-6">
            <a href="{{ route('hotspot.profiles.create') }}" class="inline-flex items-center rounded-md bg-purple-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-purple-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-purple-600">
                <svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z"/></svg>
                باقة جديدة
            </a>
        </div>
    </div>
    @endif
</div>
@endsection

```

## resources/views/hotspot\users\create.blade.php

```php
@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">إضافة مشترك هوت سبوت</h2>
        <p class="text-gray-500 mt-1">إنشاء مستخدم نقطة وصول جديد</p>
    </div>

    <form action="{{ route('hotspot.users.store') }}" method="POST" class=" space-y-5" x-data="{ mode: 'existing', selectedPhone: '' }">
        @csrf
        
        <!-- Customer Selection -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-4">
            <h3 class="text-lg font-bold text-gray-900 mb-4">بيانات العميل</h3>
            
            <div class="flex gap-4 mb-6">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="mode" value="existing" x-model="mode" class="text-purple-600 focus:ring-purple-500">
                    <span class="font-semibold text-gray-700">عميل حالي</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="mode" value="new" x-model="mode" class="text-purple-600 focus:ring-purple-500">
                    <span class="font-semibold text-gray-700">عميل جديد</span>
                </label>
            </div>

            <!-- Existing Customer -->
            <div x-show="mode === 'existing'">
                <label class="block text-sm font-semibold text-gray-700 mb-2">اختر العميل</label>
                <select name="customer_id" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500" @change="selectedPhone = $event.target.options[$event.target.selectedIndex].dataset.phone || ''">
                    <option value="">-- ابحث عن عميل (الاسم أو الهاتف) --</option>
                    @foreach($customers as $customer)
                    <option value="{{ $customer->id }}" data-phone="{{ $customer->phone }}">{{ $customer->name }} - {{ $customer->phone }}</option>
                    @endforeach
                </select>
                <!-- Read-only Phone Display -->
                <div class="mt-3" x-show="selectedPhone" x-transition>
                     <label class="block text-xs font-semibold text-gray-500 mb-1">رقم الهاتف المسجل:</label>
                    <div class="flex items-center gap-2 text-gray-900 bg-gray-50 px-3 py-2 rounded-lg border border-gray-200 w-fit">
                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        <span x-text="selectedPhone" class="font-mono font-medium"></span>
                    </div>
                </div>
            </div>

            <!-- New Customer -->
            <div x-show="mode === 'new'" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">اسم العميل *</label>
                    <input type="text" name="name" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500" placeholder="الاسم الكامل">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">رقم الهاتف *</label>
                    <input type="text" name="phone" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500" placeholder="05xxxxxxxx">
                </div>
            </div>
        </div>

        <!-- Account Info -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-4">
            <h3 class="text-lg font-bold text-gray-900 mb-4">معلومات الحساب (Hotspot)</h3>

            <!-- Server Selection -->
            <div>
                 <label class="block text-sm font-semibold text-gray-700 mb-2">سيرفر التحكم (Core) *</label>
                 <select name="server_id" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                    <option value="">-- اختر السيرفر --</option>
                    @foreach($servers as $server)
                    <option value="{{ $server->id }}">{{ $server->name }} ({{ $server->ip }})</option>
                    @endforeach
                 </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">اسم المستخدم *</label>
                    <input type="text" name="username" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500" placeholder="user123">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">كلمة المرور *</label>
                    <input type="text" name="password" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500" value="{{ Str::random(8) }}">
                </div>
            </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">الباقة</label>
            <select name="package_id" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                <option value="">-- اختر الباقة --</option>
                @foreach(\App\Models\Package::where('type', 'hotspot')->get() as $package)
                    <option value="{{ $package->id }}">{{ $package->name }} ({{ $package->speed_down }}M/{{ $package->speed_up }}M)</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">تاريخ الانتهاء</label>
            <input type="date" name="expires_at" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
        </div>

        </div> <!-- End Account Info -->

        <div class="flex items-center gap-3 pt-4">
            <button type="submit" class="px-6 py-2.5 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg shadow-sm transition">
                حفظ المشترك
            </button>
            <a href="{{ route('hotspot.users.index') }}" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg transition">
                إلغاء
            </a>
        </div>
    </form>
</div>
@endsection

```

## resources/views/hotspot\users\index.blade.php

```php
@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">مشتركي الهوت سبوت</h2>
            <p class="text-gray-500 mt-1">إدارة مستخدمي نقاط الوصول</p>
        </div>
        <a href="{{ route('hotspot.vouchers.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg shadow-sm transition ml-2">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-2v2m0 0v2m0-2h2m-2 0H5"/></svg>
            توليد كروت (جملة)
        </a>
        <a href="{{ route('hotspot.users.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-sm transition">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            إضافة مشترك
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-600 uppercase">اسم المستخدم</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-600 uppercase">الباقة</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-600 uppercase">الحالة</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-600 uppercase">تاريخ الانتهاء</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-600 uppercase">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center">
                                    <span class="text-purple-600 font-bold text-sm">{{ substr($user->username, 0, 2) }}</span>
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900">{{ $user->username }}</div>
                                    @if($user->name)
                                        <div class="text-xs text-gray-500">{{ $user->name }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-pink-100 text-pink-800">
                                {{ $user->package->name ?? 'لا يوجد' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($user->status === 'active')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full ml-1.5"></span>
                                    نشط
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    <span class="w-1.5 h-1.5 bg-gray-500 rounded-full ml-1.5"></span>
                                    غير نشط
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $user->expires_at ? $user->expires_at->format('Y-m-d') : 'غير محدد' }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('hotspot.users.print', $user) }}" target="_blank" class="text-blue-600 hover:text-blue-800 font-medium text-sm border border-blue-200 px-2 py-1 rounded bg-blue-50 hover:bg-blue-100 transition">
                                    🖨️ طباعة
                                </a>
                                <form action="{{ route('hotspot.users.destroy', $user) }}" method="POST" onsubmit="return confirm('هل أنت متأكد؟')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 font-medium text-sm px-2 py-1">حذف</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                </div>
                                <p class="text-gray-500 font-medium">لا يوجد مشتركين</p>
                                <a href="{{ route('hotspot.users.create') }}" class="text-blue-600 hover:text-blue-700 font-semibold text-sm">أضف مشتركك الأول</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

```

## resources/views/hotspot\users\print.blade.php

```php
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طباعة كرت المشترك - {{ $user->username }}</title>
    <style>
        @media print {
            .no-print { display: none; }
            body { margin: 0; padding: 0; background: white; }
            .card { border: 1px solid #000; box-shadow: none !important; page-break-inside: avoid; }
        }
        body {
            font-family: 'Tahoma', 'Segoe UI', sans-serif;
            background: #f3f4f6;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .card {
            width: 300px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            text-align: center;
            border: 2px dashed #e5e7eb;
            margin-bottom: 20px;
        }
        .logo {
            font-size: 20px;
            font-weight: bold;
            color: #4f46e5;
            margin-bottom: 15px;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 10px;
        }
        .info-row {
            margin: 10px 0;
            display: flex;
            justify-content: space-between;
            font-size: 14px;
        }
        .label { color: #6b7280; font-weight: bold; }
        .value { color: #111827; font-weight: bold; font-family: monospace; font-size: 16px; }
        .price {
            background: #4f46e5;
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
            margin-top: 15px;
            display: inline-block;
        }
        .footer {
            margin-top: 15px;
            font-size: 10px;
            color: #9ca3af;
            border-top: 1px solid #e5e7eb;
            padding-top: 10px;
        }
        .btn {
            background: #4f46e5;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            text-decoration: none;
        }
        .btn:hover { background: #4338ca; }
    </style>
</head>
<body>

    <div class="no-print" style="margin-bottom: 20px;">
        <button onclick="window.print()" class="btn">🖨️ طباعة الكرت</button>
        <a href="{{ route('hotspot.users.index') }}" class="btn" style="background: #6b7280; margin-right: 10px;">رجوع</a>
    </div>

    <div class="card">
        <div class="logo">
            {{ config('app.name', 'ISP System') }} WiFi
        </div>
        
        <div class="info-row">
            <span class="label">اسم المستخدم:</span>
            <span class="value">{{ $user->username }}</span>
        </div>
        
        <div class="info-row">
            <span class="label">كلمة المرور:</span>
            <span class="value">{{ $user->password }}</span>
        </div>

        @if($user->package)
        <div class="info-row">
            <span class="label">الباقة:</span>
            <span class="value">{{ $user->package->name }}</span>
        </div>
        @endif

        @if($user->expires_at)
        <div class="info-row">
            <span class="label">الانتهاء:</span>
            <span class="value">{{ $user->expires_at->format('Y-m-d') }}</span>
        </div>
        @endif
        
        @if($user->package && $user->package->price > 0)
        <div class="price">
            السعر: {{ number_format($user->package->price) }}
        </div>
        @endif

        <div class="footer">
            خدمة الإنترنت السريع والموثوق
            <br>
            دعم فني: {{ auth()->user()->phone ?? '00000000' }}
        </div>
    </div>

    <script>
        // Auto print on load if desired, usually better to let user click
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>

```

## resources/views/hotspot\vouchers\create.blade.php

```php
@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto space-y-6" x-data="voucherPreview()">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">توليد كروت هوت سبوت (بالجملة)</h2>
        <p class="text-gray-500 mt-1">إنشاء وتوليد مجموعة من الكروت للطباعة والبيع</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Form -->
        <form action="{{ route('hotspot.vouchers.store') }}" method="POST" class="glass rounded-2xl shadow-lg border border-white/30 p-6 space-y-6">
            @csrf
            
            <!-- Server & Package -->
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">سيرفر مايكروتك *</label>
                    <select name="server_id" required class="w-full px-4 py-3 border-2 border-purple-200 rounded-xl focus:ring-2 focus:ring-purple-500 bg-white/80">
                        <option value="">-- اختر السيرفر --</option>
                        @foreach($servers as $server)
                        <option value="{{ $server->id }}">{{ $server->name }} ({{ $server->ip }})</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">الباقة *</label>
                    <select name="package_id" required x-model="selectedPackage" class="w-full px-4 py-3 border-2 border-purple-200 rounded-xl focus:ring-2 focus:ring-purple-500 bg-white/80">
                        <option value="">-- اختر الباقة --</option>
                        @foreach($packages as $package)
                        <option value="{{ $package->id }}" data-name="{{ $package->name }}" data-price="{{ $package->price }}">
                            {{ $package->name }} - {{ number_format($package->price) }} ل.س
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="border-t border-purple-100 pt-6"></div>

            <!-- Generation Settings -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">عدد الكروت *</label>
                    <input type="number" name="quantity" min="1" max="100" value="10" x-model="quantity" required class="w-full px-4 py-3 border-2 border-purple-200 rounded-xl focus:ring-2 focus:ring-purple-500 text-center text-lg font-bold bg-white/80">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">بادئة الاسم</label>
                    <input type="text" name="prefix" x-model="prefix" placeholder="WiFi-" maxlength="5" class="w-full px-4 py-3 border-2 border-purple-200 rounded-xl focus:ring-2 focus:ring-purple-500 bg-white/80">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">طول الرمز</label>
                    <input type="number" name="length" min="4" max="10" value="6" x-model="length" required class="w-full px-4 py-3 border-2 border-purple-200 rounded-xl focus:ring-2 focus:ring-purple-500 bg-white/80">
                </div>
            </div>

            <div class="bg-blue-50 border-l-4 border-blue-500 rounded-lg p-4 flex gap-3">
                <span class="text-2xl">⚡</span>
                <div>
                    <h4 class="font-bold text-blue-900 text-sm">ملاحظة هامة</h4>
                    <p class="text-blue-700 text-xs mt-1">عند الضغط على "توليد"، سيقوم النظام بإنشاء المستخدمين فوراً على الراوتر المحدد وحفظهم في قاعدة البيانات.</p>
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full py-3 bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white font-bold rounded-xl shadow-lg transform transition hover:scale-105 active:scale-95 flex items-center justify-center gap-2">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    توليد <span x-text="quantity"></span> كرت ومزامنتها
                </button>
            </div>
        </form>

        <!-- Live Preview -->
        <div class="glass rounded-2xl shadow-lg border border-white/30 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-gray-900">معاينة مباشرة 👁️</h3>
                <span class="text-xs text-gray-500 bg-purple-50 px-3 py-1 rounded-full">Live Preview</span>
            </div>

            <!-- Preview Card -->
            <div class="relative">
                <div class="card-preview">
                    <div class="card-preview-inner">
                        <div class="decorative-corner"></div>
                        
                        <div class="wifi-icon">
                            📶
                        </div>
                        
                        <div class="card-header">
                            {{ config('app.name', 'SmartISP') }} WiFi
                        </div>
                        
                        <div class="credentials">
                            <div class="credential-row">
                                <span class="credential-label">اسم المستخدم</span>
                                <span class="credential-value" x-text="previewUsername"></span>
                            </div>
                            <div class="credential-row">
                                <span class="credential-label">كلمة المرور</span>
                                <span class="credential-value" x-text="previewPassword"></span>
                            </div>
                        </div>
                        
                        <div class="card-footer">
                            <span x-text="packageName"></span>
                            <span class="price-badge" x-show="packagePrice" x-text="packagePrice + ' ل.س'"></span>
                        </div>
                        
                        <div class="decorative-dots">
                            <div class="dot"></div>
                            <div class="dot"></div>
                            <div class="dot"></div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 text-center text-sm text-gray-500">
                    سيتم توليد <strong x-text="quantity"></strong> كرت بهذا التصميم
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card-preview {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 16px;
        padding: 3px;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
        transition: transform 0.3s;
    }
    .card-preview:hover {
        transform: translateY(-5px);
    }
    .card-preview-inner {
        background: white;
        border-radius: 14px;
        padding: 20px;
        text-align: center;
        position: relative;
    }
    .wifi-icon {
        width: 50px;
        height: 50px;
        margin: 0 auto 15px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }
    .card-header {
        font-weight: 700;
        font-size: 16px;
        background: linear-gradient(90deg, #667eea, #764ba2);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        padding-bottom: 10px;
        margin-bottom: 15px;
        border-bottom: 2px solid #e8eaf6;
    }
    .credentials {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 15px;
        margin: 15px 0;
    }
    .credential-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin: 8px 0;
    }
    .credential-label {
        color: #6b7280;
        font-weight: 600;
        font-size: 11px;
        text-transform: uppercase;
    }
    .credential-value {
        font-family: 'Courier New', monospace;
        font-size: 14px;
        font-weight: bold;
        color: #111827;
        background: white;
        padding: 5px 10px;
        border-radius: 6px;
        border: 1px dashed #667eea;
    }
    .card-footer {
        font-size: 12px;
        color: white;
        background: linear-gradient(90deg, #667eea, #764ba2);
        padding: 8px;
        border-radius: 8px;
        margin-top: 10px;
        font-weight: 600;
    }
    .price-badge {
        display: inline-block;
        background: rgba(255, 255, 255, 0.2);
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 10px;
        margin-right: 5px;
    }
    .decorative-corner {
        position: absolute;
        top: 0;
        right: 0;
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, rgba(255,255,255,0.3) 0%, transparent 100%);
        border-radius: 0 14px 0 100%;
    }
    .decorative-dots {
        position: absolute;
        bottom: 10px;
        left: 10px;
        display: flex;
        gap: 3px;
    }
    .dot {
        width: 4px;
        height: 4px;
        background: #e8eaf6;
        border-radius: 50%;
    }
</style>

<script>
function voucherPreview() {
    return {
        prefix: 'WiFi-',
        length: 6,
        quantity: 10,
        selectedPackage: '',
        packageName: 'باقة إنترنت',
        packagePrice: '',
        
        get previewUsername() {
            return this.prefix + 'XXXX'.substring(0, this.length);
        },
        
        get previewPassword() {
            return 'X'.repeat(this.length);
        },
        
        init() {
            this.$watch('selectedPackage', value => {
                const select = document.querySelector('select[name="package_id"]');
                const option = select.options[select.selectedIndex];
                if (option && option.dataset.name) {
                    this.packageName = option.dataset.name;
                    this.packagePrice = new Intl.NumberFormat('ar-SY').format(option.dataset.price);
                }
            });
        }
    }
}
</script>
@endsection

```

## resources/views/hotspot\vouchers\index.blade.php

```php
@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-bold text-gray-900">مراقبة كروت الهوت سبوت 🎫</h2>
            <p class="text-gray-500 mt-1">عرض وإدارة جميع الكروت المطبوعة</p>
        </div>
        <a href="{{ route('hotspot.vouchers.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white font-bold rounded-xl shadow-lg transform transition hover:scale-105">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            إنشاء كروت جديدة
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="glass rounded-2xl shadow-lg border border-white/30 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">إجمالي الكروت</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($stats['total']) }}</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl flex items-center justify-center text-white text-2xl">
                    🎫
                </div>
            </div>
        </div>

        <div class="glass rounded-2xl shadow-lg border border-white/30 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">كروت نشطة</p>
                    <p class="text-3xl font-bold text-green-600 mt-2">{{ number_format($stats['active']) }}</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-green-400 to-green-600 rounded-xl flex items-center justify-center text-white text-2xl">
                    ✅
                </div>
            </div>
        </div>

        <div class="glass rounded-2xl shadow-lg border border-white/30 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">كروت معطلة</p>
                    <p class="text-3xl font-bold text-red-600 mt-2">{{ number_format($stats['inactive']) }}</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-red-400 to-red-600 rounded-xl flex items-center justify-center text-white text-2xl">
                    ⛔
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="glass rounded-2xl shadow-lg border border-white/30 p-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <!-- Search -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">بحث</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="اسم المستخدم..." class="w-full px-4 py-2.5 border-2 border-purple-200 rounded-xl focus:ring-2 focus:ring-purple-500 bg-white/80">
            </div>

            <!-- Status Filter -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">الحالة</label>
                <select name="status" class="w-full px-4 py-2.5 border-2 border-purple-200 rounded-xl focus:ring-2 focus:ring-purple-500 bg-white/80">
                    <option value="">الكل</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>معطل</option>
                </select>
            </div>

            <!-- Package Filter -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">الباقة</label>
                <select name="package_id" class="w-full px-4 py-2.5 border-2 border-purple-200 rounded-xl focus:ring-2 focus:ring-purple-500 bg-white/80">
                    <option value="">الكل</option>
                    @foreach($packages as $package)
                        <option value="{{ $package->id }}" {{ request('package_id') == $package->id ? 'selected' : '' }}>{{ $package->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Date From -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">من تاريخ</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full px-4 py-2.5 border-2 border-purple-200 rounded-xl focus:ring-2 focus:ring-purple-500 bg-white/80">
            </div>

            <!-- Date To -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">إلى تاريخ</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full px-4 py-2.5 border-2 border-purple-200 rounded-xl focus:ring-2 focus:ring-purple-500 bg-white/80">
            </div>

            <div class="md:col-span-5 flex gap-3">
                <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white font-bold rounded-xl shadow-lg transition">
                    🔍 بحث وتصفية
                </button>
                <a href="{{ route('hotspot.vouchers.index') }}" class="px-6 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold rounded-xl transition">
                    إعادة تعيين
                </a>
            </div>
        </form>
    </div>

    <!-- Actions and Bulk Controls -->
    <div class="flex justify-between items-center mb-4 px-2" x-data="{ count: 0, 
        updateCount() { 
            this.count = document.querySelectorAll('.bulk-item:checked').length; 
        },
        toggleAll(e) {
            document.querySelectorAll('.bulk-item').forEach(el => {
                el.checked = e.target.checked;
            });
            this.updateCount();
        }
    }">
        <div class="flex gap-3">
             <form action="{{ route('hotspot.vouchers.reprint_last') }}" method="GET" target="_blank">
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg shadow transition">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    طباعة آخر دفعة
                </button>
             </form>
        </div>
        
        <div x-show="count > 0" style="display: none;" class="flex gap-2 items-center bg-yellow-50 px-4 py-2 rounded-lg border border-yellow-200">
            <span class="text-sm font-bold text-yellow-800" x-text="count + ' عنصر محدد'"></span>
            <button form="bulk-form" name="action" value="disable" class="text-xs px-3 py-1.5 bg-red-600 text-white rounded hover:bg-red-700 font-bold" onclick="return confirm('هل أنت متأكد من تعطيل الكروت المحددة؟')">
                تعطيل المحدد
            </button>
            <button form="bulk-form" name="action" value="delete" class="text-xs px-3 py-1.5 bg-gray-800 text-white rounded hover:bg-gray-900 font-bold" onclick="return confirm('هل أنت متأكد من حذف الكروت المحددة نهائياً؟')">
                حذف المحدد
            </button>
        </div>
    </div>

    <!-- Vouchers Table -->
    <div class="glass rounded-2xl shadow-lg border border-white/30 overflow-hidden">
        <form id="bulk-form" action="{{ route('hotspot.vouchers.bulk_action') }}" method="POST">
            @csrf
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-purple-600 to-blue-600 text-white">
                    <tr>
                        <th class="px-6 py-4 text-right">
                            <input type="checkbox" @change="toggleAll($event)" class="w-4 h-4 rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                        </th>
                        <th class="px-6 py-4 text-right text-sm font-bold">#</th>
                        <th class="px-6 py-4 text-right text-sm font-bold">اسم المستخدم</th>
                        <th class="px-6 py-4 text-right text-sm font-bold">كلمة المرور</th>
                        <th class="px-6 py-4 text-right text-sm font-bold">الباقة</th>
                        <th class="px-6 py-4 text-right text-sm font-bold">السيرفر</th>
                        <th class="px-6 py-4 text-right text-sm font-bold">الحالة</th>
                        <th class="px-6 py-4 text-right text-sm font-bold">تاريخ الإنشاء</th>
                        <th class="px-6 py-4 text-center text-sm font-bold">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($vouchers as $voucher)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <input type="checkbox" name="ids[]" value="{{ $voucher->id }}" @change="updateCount()" class="bulk-item w-4 h-4 rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $loop->iteration + ($vouchers->currentPage() - 1) * $vouchers->perPage() }}</td>
                        <td class="px-6 py-4">
                            <span class="font-mono font-bold text-purple-600">{{ $voucher->username }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-mono text-sm bg-gray-100 px-3 py-1 rounded-lg">{{ $voucher->password }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">
                            {{ $voucher->package->name ?? 'غير محدد' }}
                            @if($voucher->package)
                                <span class="text-xs text-gray-500">({{ number_format($voucher->package->price) }} ل.س)</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $voucher->mikrotikServer->name ?? 'غير محدد' }}</td>
                        <td class="px-6 py-4">
                            @if($voucher->status == 'active')
                                <span class="inline-flex items-center gap-1 px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold">
                                    <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                    نشط
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-bold">
                                    <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                                    معطل
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $voucher->created_at->format('Y-m-d H:i') }}
                            <span class="text-xs text-gray-400">({{ $voucher->created_at->diffForHumans() }})</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('hotspot.users.print', $voucher->id) }}" target="_blank" class="p-2 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-lg transition" title="طباعة الكرت">
                                    🖨️
                                </a>
                                <a href="{{ route('crm.clients.show', $voucher->id) }}" class="p-2 bg-purple-100 hover:bg-purple-200 text-purple-700 rounded-lg transition" title="عرض التفاصيل">
                                    👁️
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center gap-4">
                                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center text-4xl">
                                    🎫
                                </div>
                                <div>
                                    <p class="text-gray-500 font-medium">لا توجد كروت بعد</p>
                                    <a href="{{ route('hotspot.vouchers.create') }}" class="text-blue-600 hover:text-blue-700 font-semibold text-sm mt-2 inline-block">
                                        ابدأ بإنشاء كروت جديدة
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        </form>

        <!-- Pagination -->
        @if($vouchers->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $vouchers->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

```

## resources/views/hotspot\vouchers\print_batch.blade.php

```php
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طباعة الكروت - مجموعة</title>
    <link href="https://fonts.googleapis.com/css2?family=Readex+Pro:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        @media print {
            .no-print { display: none; }
            body { margin: 0; padding: 0; background: white; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .page-break { page-break-after: always; }
            .card { break-inside: avoid; page-break-inside: avoid; }
        }
        * {
            font-family: 'Readex Pro', 'Tahoma', sans-serif;
        }
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e8eaf6 100%);
            padding: 20px;
            margin: 0;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 16px;
            padding: 3px;
            position: relative;
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
            overflow: hidden;
            transition: transform 0.2s;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
        }
        .card-inner {
            background: white;
            border-radius: 14px;
            padding: 20px;
            text-align: center;
            position: relative;
        }
        .card-header {
            font-weight: 700;
            font-size: 16px;
            background: linear-gradient(90deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            padding-bottom: 10px;
            margin-bottom: 15px;
            border-bottom: 2px solid #e8eaf6;
            letter-spacing: 0.5px;
        }
        .wifi-icon {
            width: 40px;
            height: 40px;
            margin: 0 auto 10px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
        }
        .credentials {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            margin: 15px 0;
        }
        .credential-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 8px 0;
            font-size: 13px;
        }
        .credential-label {
            color: #6b7280;
            font-weight: 600;
            font-size: 11px;
            text-transform: uppercase;
        }
        .credential-value {
            font-family: 'Courier New', monospace;
            font-size: 15px;
            font-weight: bold;
            color: #111827;
            background: white;
            padding: 5px 10px;
            border-radius: 6px;
            border: 1px dashed #667eea;
        }
        .card-footer {
            font-size: 12px;
            color: white;
            background: linear-gradient(90deg, #667eea, #764ba2);
            padding: 8px;
            border-radius: 8px;
            margin-top: 10px;
            font-weight: 600;
        }
        .price-badge {
            display: inline-block;
            background: rgba(255, 255, 255, 0.2);
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 11px;
            margin-right: 5px;
        }
        .header-actions {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        .header-actions h1 {
            margin: 0;
            background: linear-gradient(90deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-size: 28px;
        }
        .header-actions p {
            margin: 5px 0 0 0;
            color: #6b7280;
            font-size: 14px;
        }
        .btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 700;
            text-decoration: none;
            display: inline-block;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
            transition: all 0.3s;
            font-size: 14px;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.5);
        }
        .btn-secondary {
            background: linear-gradient(135deg, #374151 0%, #1f2937 100%);
            margin-right: 10px;
        }
        .decorative-corner {
            position: absolute;
            top: 0;
            right: 0;
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, rgba(255,255,255,0.3) 0%, transparent 100%);
            border-radius: 0 14px 0 100%;
        }
        .decorative-dots {
            position: absolute;
            bottom: 10px;
            left: 10px;
            display: flex;
            gap: 3px;
        }
        .dot {
            width: 4px;
            height: 4px;
            background: #e8eaf6;
            border-radius: 50%;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="header-actions no-print">
            <div>
                <h1>تم توليد {{ count($users) }} كرت بنجاح! 🎉</h1>
                <p>جاهزة للطباعة والبيع - تصميم احترافي</p>
            </div>
            <div>
                <button onclick="window.print()" class="btn">🖨️ طباعة الكل</button>
                <a href="{{ route('hotspot.users.index') }}" class="btn btn-secondary">إنهاء</a>
            </div>
        </div>

        <div class="grid">
            @foreach($users as $user)
            <div class="card">
                <div class="card-inner">
                    <div class="decorative-corner"></div>
                    
                    <div class="wifi-icon">
                        📶
                    </div>
                    
                    <div class="card-header">
                        {{ config('app.name', 'SmartISP') }} WiFi
                    </div>
                    
                    <div class="credentials">
                        <div class="credential-row">
                            <span class="credential-label">اسم المستخدم</span>
                            <span class="credential-value">{{ $user->username }}</span>
                        </div>
                        <div class="credential-row">
                            <span class="credential-label">كلمة المرور</span>
                            <span class="credential-value">{{ $user->password }}</span>
                        </div>
                    </div>
                    
                    <div class="card-footer">
                        {{ $user->package->name ?? 'باقة إنترنت' }}
                        @if($user->package && $user->package->price)
                            <span class="price-badge">{{ number_format($user->package->price) }} ل.س</span>
                        @endif
                    </div>
                    
                    <div class="decorative-dots">
                        <div class="dot"></div>
                        <div class="dot"></div>
                        <div class="dot"></div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

</body>
</html>

```

