@extends('layouts.admin')

@section('content')
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-2xl font-bold text-white">التقارير المالية</h2>
            <p class="text-gray-400 mt-1">سجل كامل للمدفوعات والفواتير</p>
        </div>
        <div>
             <div class="bg-gray-800 px-4 py-2 border border-gray-700 rounded-lg flex items-center gap-3">
                <span class="text-gray-400 text-sm">إجمالي الإيرادات (المدفوعة):</span>
                <span class="text-green-400 font-bold text-lg">${{ number_format($total_revenue, 2) }}</span>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-gray-800 rounded-xl border border-gray-700 p-4 mb-6">
        <form action="{{ route('admin.reports.financial') }}" method="GET" class="flex flex-wrap items-end gap-4">
            
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs text-gray-400 mb-1">تاريخ البدء</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full bg-gray-900 border border-gray-700 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-indigo-500">
            </div>

            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs text-gray-400 mb-1">تاريخ الانتهاء</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full bg-gray-900 border border-gray-700 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-indigo-500">
            </div>

            <div class="flex-1 min-w-[150px]">
                <label class="block text-xs text-gray-400 mb-1">الحالة</label>
                <select name="status" class="w-full bg-gray-900 border border-gray-700 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-indigo-500">
                    <option value="">الكل</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>مدفوع</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>معلق</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>ملغي</option>
                </select>
            </div>

            <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition-colors">
                تصفية
            </button>
            
            @if(request()->hasAny(['start_date', 'end_date', 'status']))
                <a href="{{ route('admin.reports.financial') }}" class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-gray-300 rounded-lg font-medium transition-colors">
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
                        <th class="px-6 py-4 font-medium">رقم الفاتورة</th>
                        <th class="px-6 py-4 font-medium">الشركة</th>
                        <th class="px-6 py-4 font-medium">المبلغ</th>
                        <th class="px-6 py-4 font-medium">طريقة الدفع</th>
                        <th class="px-6 py-4 font-medium">الحالة</th>
                        <th class="px-6 py-4 font-medium">تاريخ الإنشاء</th>
                        <th class="px-6 py-4 font-medium">تاريخ الدفع</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    @forelse($invoices as $invoice)
                    <tr class="hover:bg-gray-700/50 transition-colors">
                        <td class="px-6 py-4 text-white font-mono text-sm">{{ $invoice->invoice_number }}</td>
                        <td class="px-6 py-4">
                            @if($invoice->tenant)
                                <div class="text-white text-sm">{{ $invoice->tenant->name }}</div>
                            @else
                                <span class="text-red-400 text-xs">Deleted Tenant</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-green-400 font-bold">${{ number_format($invoice->amount, 2) }}</td>
                        <td class="px-6 py-4 text-gray-300 text-sm">
                             @if($invoice->payment_method == 'sham_cash')
                                <span class="text-yellow-400">شام كاش</span>
                            @else
                                {{ ucfirst($invoice->payment_method) }}
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($invoice->status == 'paid')
                                <span class="bg-green-500/10 text-green-400 px-2 py-1 rounded-full text-xs font-medium border border-green-500/20">مدفوع</span>
                            @elseif($invoice->status == 'pending')
                                <span class="bg-yellow-500/10 text-yellow-400 px-2 py-1 rounded-full text-xs font-medium border border-yellow-500/20">معلق</span>
                            @else
                                <span class="bg-red-500/10 text-red-400 px-2 py-1 rounded-full text-xs font-medium border border-red-500/20">{{ $invoice->status }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-400 text-sm font-mono">
                            {{ $invoice->created_at->format('Y-m-d') }}
                        </td>
                        <td class="px-6 py-4 text-gray-400 text-sm font-mono">
                            {{ $invoice->paid_at ? $invoice->paid_at->format('Y-m-d H:i') : '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                            لا توجد بيانات مطابقة للفلاتر الحالية.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-700 bg-gray-800">
            {{ $invoices->withQueryString()->links() }}
        </div>
    </div>
@endsection
