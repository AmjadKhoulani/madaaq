@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">👥 الجلسات النشطة (Active Sessions)</h2>
            <p class="text-gray-500 mt-1">عرض وإدارة جميع الاتصالات الحالية</p>
        </div>
        <div class="px-4 py-2 bg-green-100 text-green-700 rounded-lg font-bold">
            {{ count($allSessions) }} جلسة نشطة
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gradient-to-r from-purple-50 to-purple-100 border-b border-purple-200">
                    <tr>
                        <th class="px-4 py-3 text-right font-bold text-gray-700">المستخدم</th>
                        <th class="px-4 py-3 text-right font-bold text-gray-700">النوع</th>
                        <th class="px-4 py-3 text-right font-bold text-gray-700">IP Address</th>
                        <th class="px-4 py-3 text-right font-bold text-gray-700">Uptime</th>
                        <th class="px-4 py-3 text-right font-bold text-gray-700">الراوتر</th>
                        <th class="px-4 py-3 text-right font-bold text-gray-700">إجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($allSessions as $session)
                    <tr class="hover:bg-purple-50 transition">
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $session['name'] ?? $session['user'] ?? 'N/A' }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 {{ $session['type'] === 'pppoe' ? 'bg-blue-100 text-blue-700' : 'bg-orange-100 text-orange-700' }} rounded text-xs font-medium uppercase">
                                {{ $session['type'] }}
                            </span>
                        </td>
                        <td class="px-4 py-3 font-mono text-xs text-gray-600">{{ $session['address'] ?? 'N/A' }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $session['uptime'] ?? '0' }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $session['router_name'] }}</td>
                        <td class="px-4 py-3">
                            <form action="{{ route('network.sessions.disconnect') }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="router_id" value="{{ $session['router_id'] }}">
                                <input type="hidden" name="router_type" value="{{ $session['router_type'] }}">
                                <input type="hidden" name="session_id" value="{{ $session['.id'] }}">
                                <input type="hidden" name="type" value="{{ $session['type'] }}">
                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium" 
                                        onclick="return confirm('هل أنت متأكد من قطع الاتصال؟')">
                                    قطع الاتصال
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                            لا توجد جلسات نشطة حالياً
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
