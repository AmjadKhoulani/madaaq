@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">إدارة الموظفين</h2>
            <p class="text-gray-500 mt-1">إضافة وتعديل صلاحيات الموظفين</p>
        </div>
        <a href="{{ route('staff.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white font-semibold rounded-lg shadow-lg transition">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            موظف جديد
        </a>
    </div>

    <!-- Staff Table -->
    <div class="glass shadow-lg rounded-2xl overflow-hidden border border-white/30">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-purple-100">
                <thead class="bg-gradient-to-r from-purple-50 to-blue-50">
                    <tr>
                        <th class="px-6 py-4 text-right text-xs font-bold text-purple-700 uppercase">الموظف</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-purple-700 uppercase">البريد</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-purple-700 uppercase">الدور الوظيفي</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-purple-700 uppercase">الحالة</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-purple-700 uppercase">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="bg-white/80 divide-y divide-purple-50">
                    @forelse($staff as $user)
                    <tr class="hover:bg-purple-50/50 transition-all">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-500 to-blue-500 flex items-center justify-center text-white font-bold shadow-lg">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900">{{ $user->name }}</div>
                                    @if($user->phone)
                                        <div class="text-xs text-purple-600">{{ $user->phone }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $user->email }}</td>
                        <td class="px-6 py-4">
                            @foreach($user->roles as $role)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gradient-to-r from-indigo-400 to-purple-500 text-white shadow-md mr-1">
                                    {{ $role->display_name ?? $role->name }}
                                </span>
                            @endforeach
                            @if($user->roles->isEmpty())
                                <span class="text-gray-400 text-sm">بدون دور</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($user->is_active)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gradient-to-r from-green-400 to-emerald-500 text-white shadow-md">
                                    <span class="w-1.5 h-1.5 bg-white rounded-full ml-1.5 animate-pulse"></span>
                                    نشط
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gradient-to-r from-gray-400 to-gray-600 text-white shadow-md">
                                    معطل
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('staff.edit', $user) }}" class="text-purple-600 hover:text-purple-800 font-bold text-sm">
                                    تعديل
                                </a>
                                <form action="{{ route('staff.destroy', $user) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا الموظف؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 font-bold text-sm">
                                        حذف
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center gap-3">
                                <svg class="w-16 h-16 text-purple-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                <span class="text-lg font-medium">لا يوجد موظفين</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 border-t border-purple-100 bg-white/50">
            {{ $staff->links() }}
        </div>
    </div>
</div>
@endsection
