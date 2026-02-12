@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">تعديل مصدر الإنترنت</h1>
            <p class="text-gray-500 mt-1">تعديل بيانات المصدر: {{ $internetSource->name }}</p>
        </div>
        <a href="{{ route('network.internet-sources.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-bold transition">
            رجوع
        </a>
    </div>

    <form action="{{ route('network.internet-sources.update', $internetSource->id) }}" method="POST" class="glass rounded-2xl shadow-sm border border-gray-100 p-8">
        @csrf
        @method('PUT')

        <div class="space-y-6">
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">اسم المصدر</label>
                <input type="text" name="name" value="{{ $internetSource->name }}" required class="w-full rounded-xl border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 font-bold">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">النوع</label>
                    <select name="type" required class="w-full rounded-xl border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 bg-gray-50">
                        <option value="fiber" {{ $internetSource->type == 'fiber' ? 'selected' : '' }}>Fiber Optic</option>
                        <option value="starlink" {{ $internetSource->type == 'starlink' ? 'selected' : '' }}>Starlink</option>
                        <option value="microwave" {{ $internetSource->type == 'microwave' ? 'selected' : '' }}>Microwave Link</option>
                        <option value="4g" {{ $internetSource->type == '4g' ? 'selected' : '' }}>4G / LTE</option>
                        <option value="other" {{ $internetSource->type == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">نوع الوصل (Connection)</label>
                    <select name="connection_type" class="w-full rounded-xl border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 bg-gray-50">
                        <option value="">غير محدد</option>
                        <option value="fiber" {{ $internetSource->connection_type == 'fiber' ? 'selected' : '' }}>فايبر (Fiber)</option>
                        <option value="cable" {{ $internetSource->connection_type == 'cable' ? 'selected' : '' }}>كابل (Cable)</option>
                        <option value="wireless" {{ $internetSource->connection_type == 'wireless' ? 'selected' : '' }}>لاسلكي (Wireless)</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">الحالة</label>
                <select name="status" required class="w-full rounded-xl border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 bg-gray-50">
                    <option value="online" {{ $internetSource->status == 'online' ? 'selected' : '' }}>متصل</option>
                    <option value="offline" {{ $internetSource->status == 'offline' ? 'selected' : '' }}>منقطع</option>
                    <option value="maintenance" {{ $internetSource->status == 'maintenance' ? 'selected' : '' }}>صيانة</option>
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">السعة (Capacity)</label>
                    <input type="text" name="capacity" value="{{ $internetSource->capacity }}" class="w-full rounded-xl border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 dir-ltr text-right" placeholder="100 Mbps">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Gateway IP</label>
                    <input type="text" name="ip_gateway" value="{{ $internetSource->ip_gateway }}" placeholder="192.168.1.1" class="w-full rounded-xl border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 font-mono text-sm dir-ltr">
                </div>
            </div>
        </div>

        <div class="mt-8 flex justify-end gap-3 pt-4 border-t border-gray-100">
            <a href="{{ route('network.internet-sources.index') }}" class="px-6 py-2.5 text-gray-700 hover:bg-gray-100 rounded-xl font-bold transition">إلغاء</a>
            <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold shadow-lg transition transform hover:scale-105">حفظ التعديلات</button>
        </div>
    </form>
</div>
@endsection
