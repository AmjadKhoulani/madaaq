@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header & Stats -->
    <div class="flex justify-between items-start">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">الفواتير المالية</h1>
            <p class="text-gray-500 mt-1">إدارة فواتير المشتركين والمدفوعات</p>
        </div>
        <a href="{{ route('accounting.invoices.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold rounded-xl shadow-lg transition transform hover:scale-105">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            إنشاء فاتورة جديدة
        </a>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- Total Revenue -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 flex flex-col justify-between">
            <div class="text-gray-500 text-sm font-medium mb-1">إجمالي الإيرادات</div>
            <div class="text-3xl font-bold text-emerald-600 tracking-tight">@money($stats['total_revenue'])</div>
        </div>
        
        <!-- Unpaid Amount -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 flex flex-col justify-between">
            <div class="text-gray-500 text-sm font-medium mb-1">المستحقات (غير مدفوعة)</div>
            <div class="text-3xl font-bold text-rose-600 tracking-tight">@money($stats['unpaid_amount'])</div>
        </div>

        <!-- Paid Count -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 flex flex-col justify-between">
            <div class="text-gray-500 text-sm font-medium mb-1">فواتير مدفوعة</div>
            <div class="text-3xl font-bold text-gray-900">{{ $stats['paid_count'] }}</div>
        </div>

        <!-- Unpaid Count -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 flex flex-col justify-between">
            <div class="text-gray-500 text-sm font-medium mb-1">فواتير مستحقة</div>
            <div class="text-3xl font-bold text-gray-900">{{ $stats['unpaid_count'] }}</div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200">
        <form action="{{ route('accounting.invoices.index') }}" method="GET" class="flex gap-4 w-full">
            <div class="relative flex-1">
                <select name="status" class="w-full pl-4 pr-10 py-2.5 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-gray-700 appearance-none">
                    <option value="all">جميع الحالات</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>مدفوعة</option>
                    <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>غير مدفوعة</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </div>
            </div>
            <button type="submit" class="px-6 py-2.5 bg-gray-900 hover:bg-gray-800 text-white font-bold rounded-lg transition-colors shadow-sm">
                تصفية النتائج
            </button>
        </form>
    </div>

    <!-- Invoices Table -->
    <div class="bg-white shadow-sm rounded-xl overflow-hidden border border-gray-200">
        <div class="overflow-x-auto">
            <table class="w-full text-right">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">رقم الفاتورة</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">العميل</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">المبلغ</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">تاريخ الاستحقاق</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">الحالة</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($invoices as $invoice)
                    <tr class="hover:bg-gray-50 transition duration-200">
                        <td class="px-6 py-4 font-mono font-medium text-gray-600">{{ $invoice->invoice_number }}</td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-900">{{ $invoice->client->name ?? 'عميل محذوف' }}</div>
                        </td>
                        <td class="px-6 py-4 font-bold text-gray-900">@money($invoice->amount)</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $invoice->due_date->format('Y-m-d') }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium {{ $invoice->status == 'paid' ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-red-50 text-red-700 border border-red-200' }}">
                                {{ $invoice->status == 'paid' ? 'مدفوعة' : 'غير مدفوعة' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <a href="{{ route('accounting.invoices.edit', $invoice) }}" class="text-indigo-600 hover:text-indigo-900 font-medium hover:underline">تعديل</a>
                                <span class="text-gray-300">|</span>
                                <a href="#" class="text-gray-500 hover:text-gray-700 font-medium hover:underline">طباعة</a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center text-gray-500">
                            <div class="flex flex-col items-center gap-2">
                                <svg class="w-12 h-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <span class="font-medium">لا توجد فواتير حالياً</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            {{ $invoices->links() }}
        </div>
    </div>
</div>
@endsection
