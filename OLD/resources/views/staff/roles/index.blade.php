@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">إدارة الأدوار الوظيفية</h2>
            <p class="text-gray-500 mt-1">إنشاء وتعديل الأدوار وصلاحياتها</p>
        </div>
        <a href="{{ route('roles.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white font-semibold rounded-lg shadow-lg transition">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            دور وظيفي جديد
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($roles as $role)
        <div class="glass rounded-2xl shadow-lg border border-white/30 p-6 hover:shadow-xl transition">
            <div class="flex items-start justify-between mb-4">
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-gray-900">{{ $role->display_name }}</h3>
                    @if($role->description)
                        <p class="text-sm text-gray-500 mt-1">{{ $role->description }}</p>
                    @endif
                </div>
                <span class="px-3 py-1 bg-gradient-to-r from-purple-100 to-blue-100 text-purple-700 rounded-full text-xs font-bold">
                    {{ $role->users_count ?? 0 }} موظف
                </span>
            </div>

            @if($role->permissions->count() > 0)
                <div class="mb-4">
                    <div class="text-xs font-semibold text-gray-500 mb-2">الصلاحيات ({{ $role->permissions->count() }}):</div>
                    <div class="flex flex-wrap gap-1">
                        @foreach($role->permissions->take(3) as $permission)
                            <span class="px-2 py-1 bg-purple-50 text-purple-600 rounded text-xs">{{ $permission->display_name }}</span>
                        @endforeach
                        @if($role->permissions->count() > 3)
                            <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded text-xs">+{{ $role->permissions->count() - 3 }}</span>
                        @endif
                    </div>
                </div>
            @endif

            <div class="flex items-center gap-2 pt-4 border-t border-purple-100">
                <a href="{{ route('roles.edit', $role) }}" class="flex-1 text-center py-2 bg-purple-50 hover:bg-purple-100 text-purple-700 font-semibold rounded-lg transition text-sm">
                    تعديل
                </a>
                <form action="{{ route('roles.destroy', $role) }}" method="POST" onsubmit="return confirm('هل أنت متأكد؟ هذا سيزيل الدور من جميع الموظفين المرتبطين به.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="py-2 px-4 bg-red-50 hover:bg-red-100 text-red-700 font-semibold rounded-lg transition text-sm">
                        حذف
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="col-span-full glass rounded-2xl p-12 text-center">
            <svg class="w-16 h-16 text-purple-300 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            <h3 class="text-lg font-bold text-gray-700 mb-2">لا توجد أدوار وظيفية</h3>
            <p class="text-gray-500 mb-4">ابدأ بإنشاء دور وظيفي جديد وتعيين الصلاحيات</p>
            <a href="{{ route('roles.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white font-bold rounded-xl shadow-lg transition">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                إنشاء الدور الأول
            </a>
        </div>
        @endforelse
    </div>
</div>
@endsection
