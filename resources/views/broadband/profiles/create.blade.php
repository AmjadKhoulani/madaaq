@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">إضافة باقة برودباند</h2>
        <p class="text-gray-500 mt-1">أنشئ باقة PPPoE جديدة مع المدة وحجم البيانات</p>
    </div>

    <form action="{{ route('broadband.profiles.store') }}" method="POST" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-5">
        @csrf
        
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">اسم الباقة</label>
            <input type="text" name="name" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" placeholder="مثال: باقة 100 ميجا">
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">سرعة التحميل (Mbps)</label>
                <input type="number" name="speed_down" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" placeholder="100">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">سرعة الرفع (Mbps)</label>
                <input type="number" name="speed_up" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" placeholder="50">
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">مدة الباقة (يوم)</label>
                <input type="number" name="duration_days" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" placeholder="31">
                <p class="text-xs text-gray-500 mt-1">اتركه فارغاً للباقات الدائمة</p>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">حجم البيانات (ميجابايت)</label>
                <input type="number" name="data_limit_mb" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" placeholder="1000">
                <p class="text-xs text-gray-500 mt-1">اتركه فارغاً لبيانات غير محدودة</p>
            </div>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">السعر</label>
            <div class="relative">
                <input type="number" name="price" step="0.01" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" placeholder="150.00">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">{{ $currency }}</span>
            </div>
        </div>

        <div class="flex items-center gap-3 pt-4">
            <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-sm transition">
                حفظ الباقة
            </button>
            <a href="{{ route('broadband.profiles.index') }}" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg transition">
                إلغاء
            </a>
        </div>
    </form>
</div>
@endsection
