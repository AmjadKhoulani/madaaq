@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">إضافة باقة هوت سبوت</h2>
        <p class="text-gray-500 mt-1">أنشئ باقة نقطة وصول جديدة</p>
    </div>

    <form action="{{ route('hotspot.profiles.store') }}" method="POST" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-5">
        @csrf
        
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">اسم الباقة</label>
            <input type="text" name="name" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition" placeholder="مثال: باقة 50 ميجا">
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">سرعة التحميل (Mbps)</label>
                <input type="number" name="speed_down" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition" placeholder="50">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">سرعة الرفع (Mbps)</label>
                <input type="number" name="speed_up" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition" placeholder="25">
            </div>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">السعر</label>
            <div class="relative">
                <input type="number" name="price" step="0.01" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition" placeholder="75.00">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">{{ $currency }}</span>
            </div>
        </div>

        <div class="flex items-center gap-3 pt-4">
            <button type="submit" class="px-6 py-2.5 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg shadow-sm transition">
                حفظ الباقة
            </button>
            <a href="{{ route('hotspot.profiles.index') }}" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg transition">
                إلغاء
            </a>
        </div>
    </form>
</div>
@endsection
