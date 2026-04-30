@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">إنشاء دور وظيفي جديد</h2>
        <p class="text-gray-500 mt-1">حدد الاسم والصلاحيات للدور الجديد</p>
    </div>

    <form action="{{ route('roles.store') }}" method="POST" class="glass rounded-2xl shadow-lg border border-white/30 p-6 space-y-6" x-data="{ selectedTemplate: '' }">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">اسم الدور (بالإنجليزية) *</label>
                <input type="text" name="name" required placeholder="custom_role" class="w-full px-4 py-3 border-2 border-purple-200 rounded-xl focus:ring-2 focus:ring-purple-500 bg-white/80" value="{{ old('name') }}">
                <p class="text-xs text-gray-500 mt-1">استخدم أحرف إنجليزية صغيرة بدون مسافات</p>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">الاسم المعروض (بالعربية) *</label>
                <input type="text" name="display_name" required placeholder="دور مخصص" class="w-full px-4 py-3 border-2 border-purple-200 rounded-xl focus:ring-2 focus:ring-purple-500 bg-white/80" value="{{ old('display_name') }}">
            </div>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">الوصف</label>
            <textarea name="description" rows="2" placeholder="مثال: مسؤول عن الحسابات والفواتير" class="w-full px-4 py-3 border-2 border-purple-200 rounded-xl focus:ring-2 focus:ring-purple-500 bg-white/80">{{ old('description') }}</textarea>
        </div>

        <div class="border-t border-purple-100 pt-6">
            <h3 class="block text-lg font-bold text-gray-900 mb-2">الأدوار الوظيفية *</h3>
            <p class="text-sm text-gray-600 mb-4">حدد الدور المناسب أو مجموعة أدوار</p>
            
            <!-- Management Roles (Exclusive) -->
            <div class="mb-6">
                <p class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    أدوار إدارية (اختيار واحد فقط)
                </p>
                <div class="space-y-3">
                    @php
                        $adminRoles = \App\Models\Role::where('tenant_id', auth()->user()->tenant_id)
                            ->whereIn('name', ['admin', 'manager'])
                            ->whereNotNull('display_name') // Exclude roles without display_name
                            ->get();
                    @endphp
                    
                    @foreach($adminRoles as $role)
                        <label class="flex items-start gap-3 p-4 border-2 rounded-xl cursor-pointer transition"
                               :class="selectedTemplate === '{{ $role->name }}' ? 'border-indigo-500 bg-indigo-50' : 'border-purple-100 hover:bg-purple-50'">
                            <input type="radio" name="base_role" value="{{ $role->name }}" 
                                   x-model="selectedTemplate"
                                   class="mt-1 w-5 h-5 text-indigo-600 focus:ring-indigo-500">
                            <div class="flex-1">
                                <div class="text-sm font-bold text-gray-900">{{ $role->display_name }}</div>
                                <div class="text-xs text-gray-600 mt-1">{{ $role->description }}</div>
                            </div>
                        </label>
                    @endforeach
                    
                    <label class="flex items-start gap-3 p-4 border-2 rounded-xl cursor-pointer transition"
                           :class="selectedTemplate === 'custom' ? 'border-purple-500 bg-purple-50' : 'border-gray-200 hover:bg-gray-50'">
                        <input type="radio" name="base_role" value="custom" 
                               x-model="selectedTemplate"
                               class="mt-1 w-5 h-5 text-purple-600 focus:ring-purple-500">
                        <div class="flex-1">
                            <div class="text-sm font-bold text-gray-900">دور مخصص</div>
                            <div class="text-xs text-gray-600 mt-1">حدد صلاحيات خاصة من القائمة أدناه</div>
                        </div>
                    </label>
                </div>
            </div>
            
            <!-- Operational Roles (Multiple Selection) -->
            <div class="transition-all duration-300"
                 :class="selectedTemplate !== 'custom' && selectedTemplate !== '' ? 'opacity-75 pointer-events-none' : ''">
                <p class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    أدوار تشغيلية (يتم تحديدها تلقائياً مع الأدوار الإدارية)
                </p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @php
                        $operationalRoles = \App\Models\Role::where('tenant_id', auth()->user()->tenant_id)
                            ->whereIn('name', ['technician', 'accountant', 'marketer', 'subscriptions_followup'])
                            ->whereNotNull('display_name')
                            ->get();
                    @endphp
                    
                    @foreach($operationalRoles as $role)
                        <label class="flex items-start gap-3 p-3 border-2 border-blue-100 rounded-xl transition group"
                               :class="{'hover:bg-blue-50 cursor-pointer': selectedTemplate === 'custom', 'bg-gray-50': selectedTemplate !== 'custom'}">
                            <input type="checkbox" name="additional_roles[]" value="{{ $role->name }}"
                                   class="mt-1 w-5 h-5 text-blue-600 rounded focus:ring-blue-500"
                                   :checked="selectedTemplate === 'admin' || selectedTemplate === 'manager'"
                                   :disabled="selectedTemplate !== 'custom'">
                            <div class="flex-1">
                                <div class="text-sm font-semibold text-gray-900">{{ $role->display_name }}</div>
                                <div class="text-xs text-gray-500 mt-0.5">{{ $role->description }}</div>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="flex gap-3 pt-4">
            <button type="submit" class="flex-1 py-3 bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white font-bold rounded-xl shadow-lg transition">
                إنشاء الدور
            </button>
            <a href="{{ route('roles.index') }}" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition">
                إلغاء
            </a>
        </div>
    </form>
</div>
@endsection
