@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto" x-data="{
    name: '',
    speed_down: '',
    speed_up: '',
    price: ''
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
                        <svg class="w-5 h-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        تفاصيل الباقة والسرعة
                    </h3>
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
