@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">باقات البرودباند</h2>
            <p class="text-gray-500 mt-1">إدارة باقات PPPoE والاشتراكات</p>
        </div>
        <a href="{{ route('broadband.profiles.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-sm transition">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            إضافة باقة جديدة
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($profiles as $profile)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
            <div class="flex items-start justify-between mb-4">
                <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-sm">
                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('broadband.profiles.edit', $profile) }}" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition" title="تعديل">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </a>
                    <form action="{{ route('broadband.profiles.destroy', $profile) }}" method="POST" onsubmit="return confirm('هل أنت متأكد؟')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition" title="حذف">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </form>
                </div>
            </div>
            
            <h3 class="text-lg font-bold text-gray-900 mb-3">{{ $profile->name }}</h3>
            
            <div class="space-y-2">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">السرعة</span>
                    <span class="font-semibold text-gray-900">{{ $profile->speed_down }}M / {{ $profile->speed_up }}M</span>
                </div>
                
                @if($profile->duration_days)
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">المدة</span>
                    <span class="font-semibold text-gray-900">{{ $profile->duration_days }} يوم</span>
                </div>
                @endif
                
                @if($profile->data_limit_mb)
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">حجم البيانات</span>
                    <span class="font-semibold text-gray-900">{{ number_format($profile->data_limit_mb / 1024, 1) }} GB</span>
                </div>
                @endif
                
                <div class="flex items-center justify-between text-sm pt-2 border-t border-gray-100">
                    <span class="text-gray-500">السعر</span>
                    <span class="font-bold text-blue-600 text-lg">{{ number_format($profile->price, 0) }} {{ $currency }}</span>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                </div>
                <p class="text-gray-500 font-medium mb-3">لا توجد باقات</p>
                <a href="{{ route('broadband.profiles.create') }}" class="text-blue-600 hover:text-blue-700 font-semibold text-sm">أضف باقتك الأولى</a>
            </div>
        </div>
        @endforelse
    </div>
</div>
@endsection
