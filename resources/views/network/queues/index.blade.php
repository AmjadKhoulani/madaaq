@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">⚡ إدارة السرعات (Queue Management)</h2>
            <p class="text-gray-500 mt-1">التحكم بسرعات المستخدمين عبر جميع الراوترات</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gradient-to-r from-blue-50 to-blue-100 border-b border-blue-200">
                    <tr>
                        <th class="px-4 py-3 text-right font-bold text-gray-700">الاسم</th>
                        <th class="px-4 py-3 text-right font-bold text-gray-700">الراوتر</th>
                        <th class="px-4 py-3 text-right font-bold text-gray-700">السرعة الحالية</th>
                        <th class="px-4 py-3 text-right font-bold text-gray-700">Burst</th>
                        <th class="px-4 py-3 text-right font-bold text-gray-700">إجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($allQueues as $queue)
                    <tr class="hover:bg-blue-50 transition">
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $queue['name'] ?? 'N/A' }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $queue['router_name'] }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs font-medium">
                                {{ $queue['max-limit'] ?? 'N/A' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 bg-purple-100 text-purple-700 rounded text-xs font-medium">
                                {{ $queue['burst-limit'] ?? 'N/A' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <form action="{{ route('network.queues.set-speed') }}" method="POST" class="flex items-center gap-2">
                                @csrf
                                <input type="hidden" name="router_id" value="{{ $queue['router_id'] }}">
                                <input type="hidden" name="router_type" value="{{ $queue['router_type'] }}">
                                <input type="hidden" name="username" value="{{ $queue['name'] ?? '' }}">
                                
                                <input type="text" name="download_speed" placeholder="DL" class="w-16 px-2 py-1 text-xs border rounded" value="{{ explode('/', $queue['max-limit'] ?? '/')[1] ?? '' }}">
                                <input type="text" name="upload_speed" placeholder="UL" class="w-16 px-2 py-1 text-xs border rounded" value="{{ explode('/', $queue['max-limit'] ?? '/')[0] ?? '' }}">
                                
                                <button type="submit" class="p-1 text-blue-600 hover:bg-blue-100 rounded transition" title="تحديث السرعة">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                            لا توجد queues محددة حالياً
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
