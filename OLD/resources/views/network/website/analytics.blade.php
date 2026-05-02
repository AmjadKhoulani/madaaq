@extends('layouts.app')

@section('content')
<style>
    .gradient-text {
        background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
</style>
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-bold gradient-text">أكثر المواقع تصفحاً 🌐</h2>
            <p class="text-gray-500 mt-1">إحصائيات المواقع الأكثر زيارة على الشبكة (Top 100)</p>
        </div>
        
        <div class="flex gap-2">
            <a href="?period=7d" class="px-4 py-2 {{ $period === '7d' ? 'bg-purple-600 text-white' : 'bg-gray-100 text-gray-700' }} rounded-lg font-semibold transition">
                7 أيام
            </a>
            <a href="?period=30d" class="px-4 py-2 {{ $period === '30d' ? 'bg-purple-600 text-white' : 'bg-gray-100 text-gray-700' }} rounded-lg font-semibold transition">
                30 يوم
            </a>
        </div>
    </div>

    <!-- Top Websites Table -->
    <div class="glass rounded-2xl shadow-lg border border-white/30 overflow-hidden">
        <table class="w-full">
            <thead class="bg-gradient-to-r from-purple-600 to-blue-600 text-white">
                <tr>
                    <th class="px-6 py-4 text-right text-sm font-bold">#</th>
                    <th class="px-6 py-4 text-right text-sm font-bold">الموقع</th>
                    <th class="px-6 py-4 text-right text-sm font-bold">عدد طلبات DNS</th>
                    {{-- <th class="px-6 py-4 text-right text-sm font-bold">الاستهلاك</th> --}}
                    <th class="px-6 py-4 text-right text-sm font-bold">إجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-purple-100">
                @forelse($topWebsites as $index => $website)
                <tr class="hover:bg-purple-50 transition">
                    <td class="px-6 py-4 text-gray-900 font-semibold">
                        @if($index == 0) 🥇
                        @elseif($index == 1) 🥈
                        @elseif($index == 2) 🥉
                        @else {{ $index + 1 }}
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 {{ $index < 3 ? 'bg-amber-100 text-amber-600 ring-2 ring-amber-200' : 'bg-purple-100 text-purple-600' }} rounded-full flex items-center justify-center font-bold">
                                {{ strtoupper(substr($website->domain, 0, 1)) }}
                            </div>
                            <span class="font-semibold {{ $index < 3 ? 'text-indigo-900 text-lg' : 'text-gray-900' }}">{{ $website->domain }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-700 font-medium">{{ number_format($website->total_hits) }} طلب DNS</td>
                    {{-- <td class="px-6 py-4 text-gray-700 font-medium">{{ number_format($website->total_bytes / 1024 / 1024, 2) }} MB</td> --}}
                    <td class="px-6 py-4">
                        <form action="{{ route('network.website.block') }}" method="POST" class="inline">
                            @csrf
                            <input type="hidden" name="domain" value="{{ $website->domain }}">
                            <input type="hidden" name="type" value="domain">
                            <input type="hidden" name="reason" value="محظور من قائمة الأكثر تصفحاً">
                            <button type="submit" class="px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-sm font-bold rounded-lg transition">
                                🚫 حظر
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                        لا توجد بيانات. سيتم جمعها تلقائياً من سجلات DNS الخاصة بـ MikroTik.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Info Note -->
    <div class="glass rounded-xl p-4 border border-blue-200 bg-blue-50/50">
        <p class="text-sm text-blue-800">
            <strong>💡 ملاحظة هامة:</strong> البيانات المعروضة مبنية على سجلات DNS Cache من MikroTik وتعكس عدد طلبات DNS فقط (وليس حجم الاستهلاك الفعلي). 
            للحصول على بيانات أدقق عن الاستهلاك، استخدم صفحة "استهلاك البيانات" التي تعتمد على Accounting أو Torch.
        </p>
    </div>
</div>
@endsection
