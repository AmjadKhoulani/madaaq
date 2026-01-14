@extends('layouts.portal')

@section('content')
<div class="max-w-xl mx-auto py-10">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 text-center">
        <h1 class="text-2xl font-bold text-gray-900 mb-4">الدفع اليدوي ({{ $gateway == 'cham_cash_personal' ? 'شام كاش' : 'يدوي' }})</h1>
        
        <div class="bg-gradient-to-b from-gray-50 to-white p-8 rounded-2xl border border-gray-200 mb-8 shadow-sm">
            @if(!empty($result['qr_image']))
                <div class="mb-6 relative inline-block group">
                    <div class="absolute -inset-1 bg-gradient-to-r from-purple-600 to-blue-600 rounded-xl blur opacity-25 group-hover:opacity-50 transition duration-1000"></div>
                    <img src="{{ $result['qr_image'] }}" alt="QR Code" class="w-32 h-32 mx-auto rounded-lg shadow-sm">
                    <p class="text-xs text-gray-400 mt-2">امسح الكود للدفع</p>
                </div>
            @endif
            
            <div class="bg-gray-100 rounded-lg p-4 inline-block min-w-[250px] border border-gray-200">
                <p class="text-xs text-gray-500 mb-1 uppercase tracking-wider font-semibold">رقم المحفظة</p>
                <p class="text-xl font-mono font-bold text-gray-900 tracking-widest select-all cursor-pointer" onclick="navigator.clipboard.writeText(this.innerText); alert('تم نسخ الرقم')">{{ $result['wallet_number'] }}</p>
            </div>
        </div>

        <div class="text-right bg-blue-50 p-4 rounded-lg text-blue-800 text-sm mb-6">
            <h3 class="font-bold mb-2">تعليمات الدفع:</h3>
            <p>{{ $result['instructions'] }}</p>
        </div>

        <!-- In a real scenario, here we'd have a form to upload the receipt -->
        <form action="#" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-4 text-right">
                <label class="block text-sm font-semibold text-gray-700 mb-2">إرفاق إشعار الدفع</label>
                <input type="file" name="receipt" class="w-full border rounded p-2">
            </div>
            
            <button type="button" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded-lg" onclick="alert('سيتم تفعيل هذه الميزة قريباً!')">
                تأكيد الدفع
            </button>
        </form>
    </div>
</div>
@endsection
