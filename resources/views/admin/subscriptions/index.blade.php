@extends('layouts.admin')

@section('content')
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-2xl font-bold text-white">إدارة الاشتراكات والفواتير</h2>
            <p class="text-gray-400 mt-1">مراجعة المدفوعات وتفعيل الباقات للشركات</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-gray-800 rounded-xl border border-gray-700 p-4 mb-6">
        <form action="{{ route('admin.subscriptions.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Search -->
            <div class="col-span-1">
                <input type="text" name="search" value="{{ request('search') }}" 
                    class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white placeholder-gray-400 focus:outline-none focus:border-indigo-500"
                    placeholder="بحث برقم الفاتورة أو الشركة...">
            </div>

            <!-- Status Filter -->
            <div class="col-span-1">
                <select name="status" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-indigo-500">
                    <option value="all">كل الحالات</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>مدفوع (Paid)</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>قيد الانتظار (Pending)</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>مرفوض (Rejected)</option>
                </select>
            </div>

            <!-- Method Filter -->
            <div class="col-span-1">
                <select name="payment_method" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-indigo-500">
                    <option value="all">كل طرق الدفع</option>
                    @foreach($gateways as $key => $label)
                        <option value="{{ $key }}" {{ request('payment_method') == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Buttons -->
            <div class="col-span-1 flex gap-2">
                <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                    تطبيق
                </button>
                <a href="{{ route('admin.subscriptions.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-500 text-white rounded-lg transition-colors flex items-center justify-center">
                    إلغاء
                </a>
            </div>
        </form>
    </div>

    <!-- Invoices Table -->
    <div class="bg-gray-800 rounded-xl border border-gray-700 overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-700 bg-gray-750 flex justify-between items-center">
            <h3 class="font-bold text-white">سجل الفواتير والطلبات</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-right">
                <thead>
                    <tr class="bg-gray-750 border-b border-gray-700 text-gray-400 text-sm uppercase">
                        <th class="px-6 py-4 font-medium">رقم الفاتورة</th>
                        <th class="px-6 py-4 font-medium">الشركة (Tenant)</th>
                        <th class="px-6 py-4 font-medium">الباقة</th>
                        <th class="px-6 py-4 font-medium">المبلغ</th>
                        <th class="px-6 py-4 font-medium">طريقة الدفع</th>
                        <th class="px-6 py-4 font-medium">الحالة</th>
                        <th class="px-6 py-4 font-medium">التاريخ</th>
                        <th class="px-6 py-4 font-medium">إجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    @forelse($invoices as $invoice)
                    <tr class="hover:bg-gray-700/50 transition-colors group">
                        <td class="px-6 py-4 text-white font-mono text-sm">{{ $invoice->invoice_number }}</td>
                        <td class="px-6 py-4">
                            @if($invoice->tenant)
                                <div class="text-white">{{ $invoice->tenant->name }}</div>
                            @else
                                <span class="text-red-400">محذوف</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($invoice->plan_name == 'basic_annual')
                                <span class="text-gray-300">أساسية</span>
                            @elseif($invoice->plan_name == 'pro_annual')
                                <span class="text-indigo-300 font-bold">احترافية</span>
                            @else
                                {{ $invoice->plan_name }}
                            @endif
                        </td>
                        <td class="px-6 py-4 text-green-400 font-bold">${{ number_format($invoice->amount, 2) }}</td>
                        <td class="px-6 py-4 text-gray-300">
                            {{-- Method Label --}}
                            @if($invoice->payment_method == 'sham_cash')
                                <span class="text-yellow-400 font-bold">شام كاش</span>
                            @elseif($invoice->payment_method == 'syriatel_cash')
                                <span class="text-red-400 font-bold">سيريتيل</span>
                            @elseif($invoice->payment_method == 'turkish_iban')
                                <span class="text-blue-300 font-bold">Turkiye Is Bank</span>
                            @else
                                {{ ucfirst($invoice->payment_method) }}
                            @endif

                            {{-- Transaction Ref --}}
                            @if($invoice->payment_details)
                                @php $details = json_decode($invoice->payment_details); @endphp
                                @if(isset($details->ref) && $details->ref)
                                    <div class="text-xs text-gray-500 mt-1">Ref: {{ $details->ref }}</div>
                                @endif
                            @endif

                            {{-- Receipt Image --}}
                            @if($invoice->receipt_image)
                                <div class="mt-2">
                                    <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($invoice->receipt_image) }}" target="_blank" class="inline-flex items-center gap-1 text-xs text-indigo-400 hover:text-indigo-300 underline bg-indigo-500/10 px-2 py-1 rounded border border-indigo-500/20 transition-colors">
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                        صورة الإشعار (Receipt)
                                    </a>
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($invoice->status == 'paid')
                                <span class="bg-green-500/10 text-green-400 px-2 py-1 rounded-full text-xs font-medium border border-green-500/20">مدفوع</span>
                            @elseif($invoice->status == 'pending')
                                <span class="bg-yellow-500/10 text-yellow-400 px-2 py-1 rounded-full text-xs font-medium border border-yellow-500/20">قيد الانتظار</span>
                            @else
                                <span class="bg-red-500/10 text-red-400 px-2 py-1 rounded-full text-xs font-medium border border-red-500/20">{{ $invoice->status }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-400 text-sm">
                            {{ $invoice->created_at->format('Y-m-d H:i') }}
                        </td>
                        <td class="px-6 py-4">
                            @if($invoice->status == 'pending')
                                <div class="flex items-center gap-2">
                                    <form action="{{ route('admin.subscriptions.approve', $invoice->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من قبول الدفع وتفعيل الاشتراك؟')">
                                        @csrf
                                        <button type="submit" class="px-3 py-1 bg-green-600 hover:bg-green-700 text-white rounded text-xs font-bold transition-colors">
                                            قبول وتفعيل
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.subscriptions.reject', $invoice->id) }}" method="POST" onsubmit="return confirm('رفض الفاتورة؟')">
                                        @csrf
                                        <button type="submit" class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white rounded text-xs font-bold transition-colors">
                                            رفض
                                        </button>
                                    </form>
                                </div>
                            @elseif($invoice->status == 'paid')
                                <span class="text-green-500 text-xs">مكتمل</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                            لا توجد فواتير أو طلبات اشتراك حالياً.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-700 bg-gray-800">
            {{ $invoices->links() }}
        </div>
    </div>
@endsection
