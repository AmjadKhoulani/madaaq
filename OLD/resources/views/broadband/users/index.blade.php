@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">مشتركي البرودباند</h2>
            <p class="text-gray-500 mt-1">إدارة مستخدمي PPPoE</p>
        </div>
        <a href="{{ route('broadband.users.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-sm transition">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            إضافة مشترك جديد
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-600 uppercase">اسم المستخدم</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-600 uppercase">الباقة</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-600 uppercase">الحالة</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-600 uppercase">تاريخ الانتهاء</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-600 uppercase">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                    <span class="text-blue-600 font-bold text-sm">{{ substr($user->username, 0, 2) }}</span>
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900">{{ $user->username }}</div>
                                    @if($user->name)
                                        <div class="text-xs text-gray-500">{{ $user->name }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                {{ $user->package->name ?? 'لا يوجد' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($user->status === 'active')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full ml-1.5"></span>
                                    نشط
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    <span class="w-1.5 h-1.5 bg-gray-500 rounded-full ml-1.5"></span>
                                    غير نشط
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $user->expires_at ? $user->expires_at->format('Y-m-d') : 'غير محدد' }}
                        </td>
                        <td class="px-6 py-4">
                            <form action="{{ route('broadband.users.destroy', $user) }}" method="POST" onsubmit="return confirm('هل أنت متأكد؟')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 font-medium text-sm">حذف</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                </div>
                                <p class="text-gray-500 font-medium">لا يوجد مشتركين</p>
                                <a href="{{ route('broadband.users.create') }}" class="text-blue-600 hover:text-blue-700 font-semibold text-sm">أضف مشتركك الأول</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
