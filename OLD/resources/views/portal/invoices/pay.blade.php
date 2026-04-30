@extends('layouts.portal')

@section('content')
<div class="max-w-xl mx-auto py-10">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-2">دفع الفاتورة #{{ $invoice->invoice_number }}</h1>
        <p class="text-gray-500 mb-6">المبلغ المستحق: <span class="text-gray-900 font-bold">{{ $invoice->amount }} {{ \App\Models\Setting::getValue('currency', 'ر.س') }}</span></p>

        @if(session('error'))
            <div class="bg-red-50 text-red-600 p-4 rounded-lg mb-6">
                {{ session('error') }}
            </div>
        @endif

        @if(empty($gateways))
            <div class="text-center py-8 text-gray-500">
                لا توجد وسائل دفع متاحة حالياً. يرجى التواصل مع الإدارة.
            </div>
        @else
            <form action="{{ route('portal.invoices.initiate', ['tenant_domain' => request()->getHost(), 'invoice' => $invoice->id]) }}" method="POST" class="space-y-4">
                @csrf
                @foreach($gateways as $key => $label)
                    <label class="flex items-center justify-between p-4 border rounded-lg cursor-pointer hover:bg-gray-50 transition-colors {{ $loop->first ? 'border-blue-500 bg-blue-50' : 'border-gray-200' }}">
                        <div class="flex items-center gap-3">
                            <input type="radio" name="gateway" value="{{ $key }}" {{ $loop->first ? 'checked' : '' }} class="text-blue-600 focus:ring-blue-500">
                            <span class="font-medium text-gray-900">{{ $label }}</span>
                        </div>
                        @if($key == 'paypal')
                            <span class="text-blue-800 font-bold italic">PayPal</span>
                        @elseif($key == 'stripe')
                            <span class="text-indigo-600 font-bold">Stripe</span>
                        @elseif($key == 'cham_cash')
                             <span class="text-purple-600 font-bold">ChamCash</span>
                        @endif
                    </label>
                @endforeach

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg transition-colors mt-6">
                    المتابعة للدفع
                </button>
            </form>
        @endif
    </div>
</div>
@endsection
