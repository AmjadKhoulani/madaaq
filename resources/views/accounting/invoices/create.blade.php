@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto p-6 space-y-6">
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('accounting.invoices.index') }}" class="p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
            <svg class="w-6 h-6 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <h1 class="text-2xl font-bold text-slate-800 dark:text-white">إنشاء فاتورة جديدة</h1>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
        <form action="{{ route('accounting.invoices.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Client Selection -->
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">العميل</label>
                <select name="client_id" required class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                    <option value="">اختر العميل...</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}">{{ $client->name }} ({{ $client->phone }})</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Amount -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">المبلغ ({{ $currency }})</label>
                    <input type="number" step="0.01" name="amount" required class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Due Date -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">تاريخ الاستحقاق</label>
                    <input type="date" name="due_date" value="{{ date('Y-m-d') }}" required class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <!-- Status -->
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">حالة الدفع</label>
                <div class="flex gap-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="status" value="unpaid" checked class="text-blue-600 focus:ring-blue-500">
                        <span class="text-slate-700 dark:text-slate-300">غير مدفوعة</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="status" value="paid" class="text-green-600 focus:ring-green-500">
                        <span class="text-slate-700 dark:text-slate-300">مدفوعة الآن</span>
                    </label>
                </div>
            </div>

            <!-- Description -->
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">ملاحظات / وصف</label>
                <textarea name="description" rows="3" class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-blue-500 focus:border-blue-500"></textarea>
            </div>

            <div class="pt-4 border-t border-slate-200 dark:border-slate-700 flex justify-end">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg font-medium transition-colors shadow-lg shadow-blue-500/30">
                    حفظ الفاتورة
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
