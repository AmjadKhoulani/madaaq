@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">سجل النشاطات</h2>
            <p class="text-gray-500 mt-1">تتبع جميع العمليات التي تمت على النظام</p>
        </div>
    </div>

    <!-- Filters -->
    <form method="GET" class="glass rounded-2xl shadow-lg border border-white/30 p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">بحث</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="ابحث..." class="w-full px-4 py-2 border-2 border-purple-200 rounded-xl focus:ring-2 focus:ring-purple-500 bg-white/80">
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">الموظف</label>
                <select name="user_id" class="w-full px-4 py-2 border-2 border-purple-200 rounded-xl focus:ring-2 focus:ring-purple-500 bg-white/80">
                    <option value="">الكل</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">من تاريخ</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full px-4 py-2 border-2 border-purple-200 rounded-xl focus:ring-2 focus:ring-purple-500 bg-white/80">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">إلى تاريخ</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full px-4 py-2 border-2 border-purple-200 rounded-xl focus:ring-2 focus:ring-purple-500 bg-white/80">
            </div>
        </div>

        <div class="flex gap-3 mt-4">
            <button type="submit" class="px-6 py-2 bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white font-bold rounded-xl shadow-lg transition">
                تطبيق الفلاتر
            </button>
            <a href="{{ route('activity-logs.index') }}" class="px-6 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition">
                إعادة تعيين
            </a>
        </div>
    </form>

    <!-- Activity Timeline -->
    <div class="glass rounded-2xl shadow-lg border border-white/30 p-6">
        <div class="space-y-4">
            @forelse($logs as $log)
                <div class="flex gap-4 p-4 bg-white/50 rounded-xl border border-purple-100 hover:border-purple-300 transition">
                    <!-- Icon -->
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-500 to-blue-500 flex items-center justify-center text-white shadow-lg">
                            @if(str_contains($log->description, 'إنشاء'))
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                            @elseif(str_contains($log->description, 'تعديل'))
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            @elseif(str_contains($log->description, 'حذف'))
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            @else
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            @endif
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="flex-1">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="font-semibold text-gray-900">{{ $log->description }}</p>
                                <p class="text-sm text-gray-600 mt-1">
                                    بواسطة: <span class="font-medium text-purple-600">{{ $log->causer->name ?? 'نظام' }}</span>
                                </p>
                            </div>
                            <span class="text-xs text-gray-500">{{ $log->created_at->diffForHumans() }}</span>
                        </div>

                        @if($log->properties && isset($log->properties['changes']))
                            <details class="mt-2">
                                <summary class="text-xs text-blue-600 cursor-pointer hover:text-blue-800">عرض التفاصيل</summary>
                                <div class="mt-2 p-3 bg-blue-50 rounded-lg text-xs space-y-1">
                                    @foreach($log->properties['changes'] as $key => $value)
                                        <div class="flex gap-2">
                                            <span class="font-semibold text-gray-700">{{ $key }}:</span>
                                            <span class="text-gray-500">{{ $log->properties['old'][$key] ?? '--' }}</span>
                                            <span class="text-gray-400">→</span>
                                            <span class="text-blue-600 font-medium">{{ $value }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </details>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-purple-300 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    <p class="text-gray-500">لا توجد نشاطات محفوظة</p>
                </div>
            @endforelse
        </div>

        <div class="mt-6 pt-6 border-t border-purple-100">
            {{ $logs->links() }}
        </div>
    </div>
</div>
@endsection
