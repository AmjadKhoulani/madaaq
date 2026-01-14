@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">تعديل دور وظيفي</h2>
        <p class="text-gray-500 mt-1">تحديث الصلاحيات والتفاصيل</p>
    </div>

    <form action="{{ route('roles.update', $role) }}" method="POST" class="glass rounded-2xl shadow-lg border border-white/30 p-6 space-y-6">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">اسم الدور (بالإنجليزية) *</label>
                <input type="text" name="name" required class="w-full px-4 py-3 border-2 border-purple-200 rounded-xl focus:ring-2 focus:ring-purple-500 bg-white/80" value="{{ old('name', $role->name) }}">
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">الاسم المعروض (بالعربية) *</label>
                <input type="text" name="display_name" required class="w-full px-4 py-3 border-2 border-purple-200 rounded-xl focus:ring-2 focus:ring-purple-500 bg-white/80" value="{{ old('display_name', $role->display_name) }}">
            </div>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">الوصف</label>
            <textarea name="description" rows="2" class="w-full px-4 py-3 border-2 border-purple-200 rounded-xl focus:ring-2 focus:ring-purple-500 bg-white/80">{{ old('description', $role->description) }}</textarea>
        </div>

        <div class="border-t border-purple-100 pt-6">
            <label class="block text-sm font-semibold text-gray-700 mb-4">الصلاحيات</label>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                @foreach($permissions as $permission)
                    <label class="flex items-center gap-3 p-3 border-2 border-purple-100 rounded-xl hover:bg-purple-50 cursor-pointer transition">
                        <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" {{ $role->permissions->contains($permission->id) ? 'checked' : '' }} class="w-5 h-5 text-purple-600 rounded focus:ring-purple-500">
                        <div class="text-sm font-semibold text-gray-900">{{ $permission->display_name }}</div>
                    </label>
                @endforeach
            </div>
        </div>

        <div class="bg-blue-50 border-l-4 border-blue-500 rounded-lg p-4 flex gap-3">
            <span class="text-2xl">ℹ️</span>
            <div>
                <h4 class="font-bold text-blue-900 text-sm">ملاحظة</h4>
                <p class="text-blue-700 text-xs mt-1">التغييرات ستؤثر على جميع الموظفين المرتبطين بهذا الدور ({{ $role->users_count ?? 0 }} موظف)</p>
            </div>
        </div>

        <div class="flex gap-3 pt-4">
            <button type="submit" class="flex-1 py-3 bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white font-bold rounded-xl shadow-lg transition">
                حفظ التغييرات
            </button>
            <a href="{{ route('roles.index') }}" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition">
                إلغاء
            </a>
        </div>
    </form>
</div>
@endsection
