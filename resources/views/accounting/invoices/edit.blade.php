@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto p-6 space-y-6">
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('accounting.invoices.index') }}" class="p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
            <svg class="w-6 h-6 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">تعديل الفاتورة #{{ $invoice->invoice_number }}</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1">العميل: {{ $invoice->client->name }}</p>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
        <form action="{{ route('accounting.invoices.update', $invoice) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Amount -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">المبلغ ({{ $currency }})</label>
                    <input type="number" step="0.01" name="amount" value="{{ $invoice->amount }}" required class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Due Date -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">تاريخ الاستحقاق</label>
                    <input type="date" name="due_date" value="{{ $invoice->due_date->format('Y-m-d') }}" required class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <!-- Status -->
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">حالة الدفع</label>
                <div class="flex gap-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="status" value="unpaid" {{ $invoice->status == 'unpaid' ? 'checked' : '' }} class="text-blue-600 focus:ring-blue-500">
                        <span class="text-slate-700 dark:text-slate-300">غير مدفوعة</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="status" value="paid" {{ $invoice->status == 'paid' ? 'checked' : '' }} class="text-green-600 focus:ring-green-500">
                        <span class="text-slate-700 dark:text-slate-300">مدفوعة</span>
                    </label>
                </div>
            </div>

            <!-- Description -->
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">ملاحظات / وصف</label>
                <textarea name="description" rows="3" class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-blue-500 focus:border-blue-500">{{ $invoice->description ?? '' }}</textarea>
            </div>

            <div class="pt-4 border-t border-slate-200 dark:border-slate-700 flex justify-between items-center">
                <!-- Delete Button -->
                <button type="button" onclick="if(confirm('هل أنت متأكد من حذف هذه الفاتورة؟')) document.getElementById('delete-form').submit()" class="text-red-600 hover:text-red-800 text-sm font-medium">
                    حذف الفاتورة
                </button>

                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg font-medium transition-colors shadow-lg shadow-blue-500/30">
                    تحديث الفاتورة
                </button>
            </div>
        </form>
    </div>
</div>

<form id="delete-form" action="{{ route('accounting.invoices.destroy', $invoice) }}" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>
@endsection
