@extends('layouts.portal')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-bold text-gray-900">سجل الفواتير</h1>
        <div class="text-sm text-gray-500">
            ملخص مدفوعاتك واشتراكاتك
        </div>
    </div>

    <!-- Invoices List -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        @if($invoices->isEmpty())
            <div class="p-12 text-center text-gray-500">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <h3 class="text-lg font-medium">لا توجد فواتير حالياً</h3>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-right">
                    <thead class="bg-gray-50 text-gray-500 text-xs uppercase font-semibold">
                        <tr>
                            <th class="px-6 py-4">رقم الفاتورة</th>
                            <th class="px-6 py-4">التاريخ</th>
                            <th class="px-6 py-4">المبلغ</th>
                            <th class="px-6 py-4">الحالة</th>
                            <th class="px-6 py-4">الوصف</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($invoices as $invoice)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-mono text-gray-900 font-bold">#{{ $invoice->id }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $invoice->created_at->format('Y-m-d') }}</td>
                            <td class="px-6 py-4 font-bold text-gray-900">{{ number_format($invoice->amount, 2) }} ر.س</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-bold 
                                    {{ $invoice->status == 'paid' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                    {{ $invoice->status == 'paid' ? 'مدفوعة' : 'غير مدفوعة' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-600 text-sm">
                                {{ $invoice->notes ?? 'تجديد اشتراك' }}
                                @if($invoice->status == 'unpaid')
                                    <a href="{{ route('portal.invoices.pay', ['tenant_domain' => request()->getHost(), 'invoice' => $invoice->id]) }}" class="mr-2 inline-flex items-center px-3 py-1 bg-blue-600 text-white text-xs font-bold rounded-lg hover:bg-blue-700 transition">
                                        دفع الآن
                                    </a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-gray-100">
                {{ $invoices->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
