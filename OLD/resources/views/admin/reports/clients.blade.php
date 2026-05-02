@extends('layouts.admin')

@section('content')
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-2xl font-bold text-white">تقارير المستخدمين النهائيين (Clients)</h2>
            <p class="text-gray-400 mt-1">إحصائيات المشتركين على مستوى كامل النظام</p>
        </div>
        <div class="flex gap-4">
            <div class="bg-gray-800 px-4 py-2 border border-gray-700 rounded-lg">
                <span class="text-gray-400 text-sm block">الإجمالي</span>
                <span class="text-white font-bold text-lg">{{ number_format($total_clients) }}</span>
            </div>
            <div class="bg-gray-800 px-4 py-2 border border-gray-700 rounded-lg">
                <span class="text-gray-400 text-sm block">النشطين</span>
                <span class="text-green-400 font-bold text-lg">{{ number_format($active_clients) }}</span>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-gray-800 rounded-xl border border-gray-700 p-4 mb-6">
        <form action="{{ route('admin.reports.clients') }}" method="GET" class="flex flex-wrap items-end gap-4">
            
            <div class="flex-1 min-w-[150px]">
                <label class="block text-xs text-gray-400 mb-1">الحالة</label>
                <select name="status" class="w-full bg-gray-900 border border-gray-700 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-indigo-500">
                    <option value="">الكل</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                     <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>منتهي</option>
                </select>
            </div>

            <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition-colors">
                تصفية
            </button>
             @if(request()->has('status'))
                <a href="{{ route('admin.reports.clients') }}" class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-gray-300 rounded-lg font-medium transition-colors">
                    إلغاء
                </a>
            @endif
        </form>
    </div>

    <!-- Table -->
    <div class="bg-gray-800 rounded-xl border border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-right">
                <thead>
                    <tr class="bg-gray-750 border-b border-gray-700 text-gray-400 text-sm uppercase">
                        <th class="px-6 py-4 font-medium">اسم العميل</th>
                        <th class="px-6 py-4 font-medium">رقم الهاتف</th>
                        <th class="px-6 py-4 font-medium">الشركة المزودة</th>
                        <th class="px-6 py-4 font-medium">الحالة</th>
                        <th class="px-6 py-4 font-medium">تاريخ الإضافة</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    @forelse($clients as $client)
                    <tr class="hover:bg-gray-700/50 transition-colors">
                        <td class="px-6 py-4 text-white font-medium">{{ $client->name }}</td>
                        <td class="px-6 py-4 text-gray-300 font-mono text-sm">{{ $client->phone ?? '-' }}</td>
                        <td class="px-6 py-4 text-gray-300">
                            @if($client->tenant)
                                {{ $client->tenant->name }}
                            @else
                                <span class="text-red-400">Unknown</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($client->status == 'active')
                                <span class="bg-green-500/10 text-green-400 px-2 py-1 rounded-full text-xs font-medium border border-green-500/20">نشط</span>
                            @elseif($client->status == 'expired')
                                <span class="bg-orange-500/10 text-orange-400 px-2 py-1 rounded-full text-xs font-medium border border-orange-500/20">منتهي</span>
                            @else
                                <span class="bg-red-500/10 text-red-400 px-2 py-1 rounded-full text-xs font-medium border border-red-500/20">{{ $client->status }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-400 text-sm font-mono">
                            {{ $client->created_at->format('Y-m-d') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                            لا توجد بيانات.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-700 bg-gray-800">
            {{ $clients->withQueryString()->links() }}
        </div>
    </div>
@endsection
