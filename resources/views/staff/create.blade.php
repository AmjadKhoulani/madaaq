@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">إضافة موظف جديد</h2>
        <p class="text-gray-500 mt-1">إنشاء حساب موظف وتعيين الصلاحيات</p>
    </div>

    <form action="{{ route('staff.store') }}" method="POST" class="glass rounded-2xl shadow-lg border border-white/30 p-6 space-y-6">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">الاسم الكامل *</label>
                <input type="text" name="name" required class="w-full px-4 py-3 border-2 border-purple-200 rounded-xl focus:ring-2 focus:ring-purple-500 bg-white/80" value="{{ old('name') }}">
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">البريد الإلكتروني *</label>
                <input type="email" name="email" required class="w-full px-4 py-3 border-2 border-purple-200 rounded-xl focus:ring-2 focus:ring-purple-500 bg-white/80" value="{{ old('email') }}">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">رقم الهاتف</label>
                <input type="text" name="phone" class="w-full px-4 py-3 border-2 border-purple-200 rounded-xl focus:ring-2 focus:ring-purple-500 bg-white/80" value="{{ old('phone') }}">
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">المنصب</label>
                <input type="text" name="position" placeholder="مثال: موظف دعم فني" class="w-full px-4 py-3 border-2 border-purple-200 rounded-xl focus:ring-2 focus:ring-purple-500 bg-white/80" value="{{ old('position') }}">
            </div>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">كلمة المرور *</label>
            <input type="password" name="password" required class="w-full px-4 py-3 border-2 border-purple-200 rounded-xl focus:ring-2 focus:ring-purple-500 bg-white/80" minlength="6">
            <p class="text-xs text-gray-500 mt-1">6 أحرف على الأقل</p>
        </div>

        <div class="border-t border-purple-100 pt-6">
            <label class="block text-sm font-semibold text-gray-700 mb-3">الأدوار الوظيفية *</label>
            <div class="space-y-2">
                @foreach($roles as $role)
                    <label class="flex items-center gap-3 p-3 border-2 border-purple-100 rounded-xl hover:bg-purple-50 cursor-pointer transition">
                        <input type="checkbox" name="roles[]" value="{{ $role->id }}" class="w-5 h-5 text-purple-600 rounded focus:ring-purple-500">
                        <div>
                            <div class="font-semibold text-gray-900">{{ $role->display_name }}</div>
                            @if($role->description)
                                <div class="text-xs text-gray-500">{{ $role->description }}</div>
                            @endif
                        </div>
                    </label>
                @endforeach
                @if($roles->isEmpty())
                    <p class="text-gray-500 text-sm">لا توجد أدوار وظيفية. <a href="{{ route('roles.create') }}" class="text-purple-600 font-bold">أنشئ دوراً الآن</a></p>
                @endif
            </div>
        </div>

        <div class="flex gap-3 pt-4">
            <button type="submit" class="flex-1 py-3 bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white font-bold rounded-xl shadow-lg transition">
                إضافة الموظف
            </button>
            <a href="{{ route('staff.index') }}" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition">
                إلغاء
            </a>
        </div>
    </form>
</div>
@endsection
