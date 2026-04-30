@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">مشتركي الهوت سبوت</h2>
            <p class="text-gray-500 mt-1">إدارة مستخدمي نقاط الوصول</p>
        </div>
        <a href="{{ route('hotspot.vouchers.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg shadow-sm transition ml-2">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-2v2m0 0v2m0-2h2m-2 0H5"/></svg>
            توليد كروت (جملة)
        </a>
        <a href="{{ route('hotspot.users.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-sm transition">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            إضافة مشترك
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
                                <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center">
                                    <span class="text-purple-600 font-bold text-sm">{{ substr($user->username, 0, 2) }}</span>
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
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-pink-100 text-pink-800">
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
                            <div class="flex items-center gap-2">
                                <a href="{{ route('hotspot.users.print', $user) }}" target="_blank" class="text-blue-600 hover:text-blue-800 font-medium text-sm border border-blue-200 px-2 py-1 rounded bg-blue-50 hover:bg-blue-100 transition">
                                    🖨️ طباعة
                                </a>
                                <form action="{{ route('hotspot.users.destroy', $user) }}" method="POST" onsubmit="return confirm('هل أنت متأكد؟')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 font-medium text-sm px-2 py-1">حذف</button>
                                </form>
                            </div>
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
                                <a href="{{ route('hotspot.users.create') }}" class="text-blue-600 hover:text-blue-700 font-semibold text-sm">أضف مشتركك الأول</a>
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
