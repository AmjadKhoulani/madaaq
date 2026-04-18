# Source Catalog: cat5

## resources/views/portal\dashboard.blade.php

```php
@extends('layouts.portal')

@section('content')
<div class="max-w-5xl mx-auto space-y-8">
    <!-- Header Card -->
    <div class="bg-gradient-to-r from-blue-900 to-indigo-900 rounded-3xl p-8 text-white shadow-xl relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-full opacity-10">
            <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                <path d="M0 100 C 20 0 50 0 100 100 Z" fill="white" />
            </svg>
        </div>
        <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex items-center gap-6">
                <div class="w-20 h-20 bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center border border-white/20">
                    <span class="text-3xl">👋</span>
                </div>
                <div>
                    <h1 class="text-3xl font-bold mb-1">مرحباً، {{ $client->name ?? $client->username }}</h1>
                    <div class="flex items-center gap-2 text-blue-200">
                        <span class="text-xs uppercase font-bold tracking-wider opacity-75">نوع الخدمة:</span>
                        @if(($client->package->technology_type ?? 'wireless') === 'fiber')
                            <span class="flex items-center gap-1 text-xs font-bold bg-white/20 px-2 py-0.5 rounded">🌐 FIBER (FTTH)</span>
                        @elseif(($client->package->technology_type ?? 'wireless') === 'wireless')
                            <span class="flex items-center gap-1 text-xs font-bold bg-white/20 px-2 py-0.5 rounded">📡 WIRELESS (WISP)</span>
                        @elseif(($client->package->technology_type ?? 'wireless') === 'dsl')
                            <span class="flex items-center gap-1 text-xs font-bold bg-white/20 px-2 py-0.5 rounded">🔌 DSL / COPPER</span>
                        @else
                            <span class="flex items-center gap-1 text-xs font-bold bg-white/20 px-2 py-0.5 rounded">📡 WIRELESS</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="text-center md:text-left">
                <div class="text-sm text-blue-200 mb-1">الحالة</div>
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full {{ $usage['status'] === 'online' ? 'bg-green-500/20 text-green-300 border border-green-500/30' : 'bg-red-500/20 text-red-300 border border-red-500/30' }}">
                    <span class="w-2 h-2 rounded-full {{ $usage['status'] === 'online' ? 'bg-green-400 animate-pulse' : 'bg-red-400' }}"></span>
                    <span class="font-bold">{{ $usage['status'] === 'online' ? 'متصل' : 'غير متصل' }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Package -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center gap-4">

            <div>
                <p class="text-gray-500 text-xs font-semibold uppercase">السرعة الحالية</p>
                <div class="flex items-center gap-2">
                    <span class="text-xl font-bold text-gray-900" title="تحميل">{{ $usage['download_speed'] }} <small class="text-xs text-gray-400">Mbps</small></span>
                    <span class="text-xs text-gray-300">|</span>
                    <span class="text-lg font-bold text-gray-700" title="رفع">{{ $usage['upload_speed'] }} <small class="text-xs text-gray-400">Mbps</small></span>
                </div>
                <p class="text-xs text-purple-600 mt-1">
                    @if($usage['status'] === 'online')
                    مدة الاتصال: {{ $usage['uptime'] }}
                    @else
                    غير متصل حالياً
                    @endif
                </p>
            </div>
        </div>

        <!-- Expiry -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center gap-4">

            <div>
                <p class="text-gray-500 text-xs font-semibold uppercase">نهاية الاشتراك</p>
                <h3 class="text-xl font-bold text-gray-900 font-mono">
                    {{ $client->expires_at ? $client->expires_at->format('Y-m-d') : '--' }}
                </h3>
                <p class="text-xs text-orange-600 mt-1">
                    @if($client->expires_at)
                    سيتوقف بعد {{ $client->expires_at->diffInDays() }} يوم
                    @else
                    تاريخ غير محدد
                    @endif
                </p>
            </div>
        </div>

        <!-- Balance -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center gap-4">

            <div>
                <p class="text-gray-500 text-xs font-semibold uppercase">سعر التجديد</p>
                <h3 class="text-xl font-bold text-gray-900 font-mono">
                    @money($client->price ?? $client->package->price ?? 0)
                </h3>
                <p class="text-xs text-green-600 mt-1">باقة {{ $client->package->name ?? '---' }}</p>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Usage Chart (Mock) -->
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100">
            <h3 class="font-bold text-gray-900 mb-6">استهلاك البيانات (تجريبي)</h3>
            <div class="h-48 flex items-end justify-between px-2 gap-2">
                @foreach([40, 65, 30, 85, 50, 75, 60] as $h)
                <div class="w-full bg-blue-100 rounded-t-lg relative group">
                    <div style="height: {{ $h }}%" class="bg-blue-500 rounded-t-lg absolute bottom-0 w-full transition-all group-hover:bg-blue-600"></div>
                </div>
                @endforeach
            </div>
            <div class="flex justify-between text-xs text-gray-400 mt-2 font-mono">
                <span>السبت</span><span>الأحد</span><span>الاثنين</span><span>الثلاثاء</span><span>الأربعاء</span><span>الخميس</span><span>الجمعة</span>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="space-y-4">
            <h3 class="font-bold text-gray-900 mb-2">إجراءات سريعة</h3>
            
            @php
                $waNumber = \App\Models\Setting::getValue('whatsapp_regular_number', '963999000000');
            @endphp
            <a href="https://wa.me/{{ $waNumber }}?text=مرحباً، أرغب بتجديد اشتراكي رقم {{ $client->id }}" target="_blank" class="flex items-center gap-4 p-5 bg-green-50 rounded-2xl border border-green-100 hover:bg-green-100 transition group">
                <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center shadow-lg group-hover:scale-110 transition">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                </div>
                <div>
                    <h4 class="font-bold text-gray-900">تجديد الاشتراك</h4>
                    <p class="text-xs text-green-700">تواصل عبر واتساب لطلب التجديد</p>
                </div>
            </a>

            <div class="flex items-center gap-4 p-5 bg-white border border-gray-200 rounded-2xl hover:border-gray-300 transition cursor-pointer">
                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center text-gray-500">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636a9 9 0 010 12.728m0 0l-2.829-2.829m2.829 2.829L21 21M15.536 8.464a5 5 0 010 7.072m0 0l-2.829-2.829m-4.243 2.829a4.978 4.978 0 01-1.414-2.83m-1.414 5.658a9 9 0 01-2.167-9.238m7.824 2.167a1 1 0 111.414 1.414m-1.414-1.414L3 3m8.293 8.293l1.414 1.414"/></svg>
                </div>
                <div>
                    <h4 class="font-bold text-gray-900">الإبلاغ عن عطل</h4>
                    <p class="text-xs text-gray-500">لديك مشكلة في الاتصال؟</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

```

## resources/views/portal\invoices\index.blade.php

```php
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
                            <td class="px-6 py-4 font-bold text-gray-900">{{ number_format($invoice->amount, 2) }} {{ \App\Models\Setting::getValue('currency', 'ر.س') }}</td>
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

```

## resources/views/portal\invoices\manual_pay.blade.php

```php
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

```

## resources/views/portal\invoices\pay.blade.php

```php
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

```

## resources/views/portal\login.blade.php

```php
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>بوابة المشتركين | تسجيل الدخول</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Cairo', sans-serif; }
        .font-english { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="bg-gray-900 min-h-screen flex items-center justify-center relative overflow-hidden">
    
    <!-- Background Decor -->
    <div class="absolute inset-0 bg-gradient-to-br from-gray-900 via-blue-900 to-gray-900"></div>
    <div class="absolute top-0 left-0 w-full h-full opacity-10 pointer-events-none">
        <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
            <path d="M0 0 L100 0 L100 100 L0 100 Z" fill="url(#grid)" />
            <defs>
                <pattern id="grid" width="4" height="4" patternUnits="userSpaceOnUse">
                    <rect width="4" height="4" fill="white" />
                    <rect width="3.5" height="3.5" fill="black" />
                </pattern>
            </defs>
        </svg>
    </div>
    <div class="absolute -top-40 -right-40 w-96 h-96 bg-blue-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
    <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>

    <div class="relative w-full max-w-md p-6">
        <div class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-3xl shadow-2xl p-8 md:p-10">
            <div class="text-center mb-10">
                <div class="w-20 h-20 bg-gradient-to-tr from-blue-500 to-purple-600 rounded-2xl mx-auto flex items-center justify-center shadow-lg mb-6 transform rotate-3 hover:rotate-6 transition duration-300">
                    <svg class="w-10 h-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                </div>
                <h1 class="text-3xl font-extrabold text-white mb-2 tracking-wide">أهلاً بك</h1>
                <p class="text-blue-200 text-sm">سجل دخولك لمتابعة استهلاكك وإدارة حسابك</p>
            </div>

            <form method="POST" action="{{ route('portal.login.submit', ['tenant_domain' => request()->getHost()]) }}" class="space-y-6">
                @csrf
                <div class="space-y-2">
                    <label class="text-xs font-bold text-blue-200 mr-1 uppercase opacity-80">اسم المستخدم أو رقم الهاتف</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-blue-300 group-focus-within:text-blue-400 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        </div>
                        <input type="text" name="username" required 
                            class="w-full pl-11 pr-4 py-4 bg-gray-800/50 border border-gray-700 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 transition font-english" 
                            placeholder="username / 09xxxxxxxx">
                    </div>
                    @error('username')
                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <label class="text-xs font-bold text-blue-200 mr-1 uppercase opacity-80">كلمة المرور</label>
                    </div>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-blue-300 group-focus-within:text-blue-400 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                        <input type="password" name="password" required 
                            class="w-full pl-11 pr-4 py-4 bg-gray-800/50 border border-gray-700 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 transition font-english" 
                            placeholder="••••••••">
                    </div>
                </div>

                <button type="submit" class="w-full py-4 px-6 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-blue-600/30 transform hover:-translate-y-0.5 transition duration-200 flex items-center justify-center gap-2">
                    <span>تسجيل الدخول</span>
                    <svg class="w-5 h-5 rtl:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </button>
            </form>
            
            <!-- Activation Links Removed as per request -->
            {{-- 
            <div class="mt-8 text-center space-y-2">
                <a href="{{ route('portal.setup.request') }}" class="block text-sm text-blue-400 font-bold hover:text-blue-300 transition">مشترك جديد / تفعيل الحساب</a>
                <a href="{{ route('portal.setup.request') }}" class="block text-xs text-blue-300/60 hover:text-blue-200 transition">هل نسيت كلمة المرور؟</a>
            </div>
            --}}
        </div>
        
        <div class="text-center mt-6">
            <p class="text-gray-500 text-xs">© {{ date('Y') }} جميع الحقوق محفوظة</p>
        </div>
    </div>
</body>
</html>

```

## resources/views/portal\packages\index.blade.php

```php
@extends('layouts.portal')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">الباقات المتاحة</h1>
            <p class="text-gray-500 mt-1">تصفح الباقات واختر الأنسب لاحتياجاتك</p>
        </div>
        <div class="text-sm bg-blue-50 text-blue-700 px-4 py-2 rounded-lg border border-blue-100">
            باقتك الحالية: <span class="font-bold">{{ $client->package->name ?? 'مخصص' }}</span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($packages as $package)
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 flex flex-col relative overflow-hidden transition hover:shadow-md hover:border-blue-100 group">
            
            @if($client->package_id == $package->id)
            <div class="absolute top-0 right-0 bg-green-500 text-white text-xs font-bold px-3 py-1 rounded-bl-xl">
                باقة حالية
            </div>
            @endif

            <div class="mb-4">
                <h3 class="text-xl font-bold text-gray-900">{{ $package->name }}</h3>
                <div class="flex items-baseline mt-2">
                    <span class="text-3xl font-extrabold text-blue-600">{{ number_format($package->price, 0) }}</span>
                    <span class="text-gray-500 mr-1">{{ \App\Models\Setting::getValue('currency', 'ر.س') }} / شهر</span>
                </div>
            </div>

            <ul class="space-y-3 mb-8 flex-1">
                <li class="flex items-center text-sm text-gray-600">
                    <svg class="w-5 h-5 text-green-500 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    سرعة تحميل: <span class="font-bold mx-1">{{ $package->speed_down }}</span> Mbps
                </li>
                <li class="flex items-center text-sm text-gray-600">
                    <svg class="w-5 h-5 text-green-500 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    سرعة رفع: <span class="font-bold mx-1">{{ $package->speed_up }}</span> Mbps
                </li>
                <li class="flex items-center text-sm text-gray-600">
                    <svg class="w-5 h-5 text-green-500 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    سعة التحميل: <span class="font-bold mx-1">{{ $package->data_limit > 0 ? $package->data_limit . ' GB' : 'غير محدود' }}</span>
                </li>
            </ul>

            @if($client->package_id != $package->id)
            <a href="https://wa.me/966000000000?text=مرحباً، أرغب بتغيير باقتي الحالية ({{ $client->username }}) إلى باقة {{ $package->name }}" 
               target="_blank"
               class="w-full py-3 bg-gray-50 hover:bg-green-50 text-gray-900 hover:text-green-700 font-bold rounded-xl border border-gray-200 hover:border-green-200 transition flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                طلب تغيير الباقة
            </a>
            @else
            <button disabled class="w-full py-3 bg-green-50 text-green-600 font-bold rounded-xl cursor-default opacity-60">
                باقة حالية
            </button>
            @endif
        </div>
        @endforeach
    </div>
</div>
@endsection

```

## resources/views/portal\setup\email.blade.php

```php
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>تأكيد البريد الإلكتروني | بوابة المشتركين</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Cairo', sans-serif; } .font-english { font-family: 'Outfit', sans-serif; }</style>
</head>
<body class="bg-gray-900 min-h-screen flex items-center justify-center relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-gray-900 via-blue-900 to-gray-900"></div>
    <div class="relative w-full max-w-md p-6">
        <div class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-3xl shadow-2xl p-8 md:p-10">
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-purple-500/20 rounded-2xl mx-auto flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <h1 class="text-2xl font-bold text-white mb-2">البريد الإلكتروني</h1>
                <p class="text-blue-200 text-sm">سنقوم بإرسال رابط تعيين كلمة المرور إلى هذا البريد.</p>
            </div>

            <form method="POST" action="{{ route('portal.setup.send-link') }}" class="space-y-6">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                
                <div class="space-y-2">
                    <label class="text-xs font-bold text-blue-200 mr-1 uppercase opacity-80">البريد الإلكتروني</label>
                    <input type="email" name="email" required value="{{ old('email', $client->email) }}"
                           class="w-full px-4 py-4 bg-gray-800/50 border border-gray-700 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-purple-500/50 font-english" 
                           placeholder="name@example.com">
                    @error('email') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <button type="submit" class="w-full py-4 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-xl transition shadow-lg">
                    إرسال الرابط
                </button>
            </form>
             <div class="mt-6 text-center">
                <a href="{{ route('portal.login') }}" class="text-sm text-gray-400 hover:text-white transition">إلغاء</a>
            </div>
        </div>
    </div>
</body>
</html>

```

## resources/views/portal\setup\request.blade.php

```php
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>تفعيل الحساب | بوابة المشتركين</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Cairo', sans-serif; } .font-english { font-family: 'Outfit', sans-serif; }</style>
</head>
<body class="bg-gray-900 min-h-screen flex items-center justify-center relative overflow-hidden">
    <!-- Background Decor (Same as Login) -->
    <div class="absolute inset-0 bg-gradient-to-br from-gray-900 via-blue-900 to-gray-900"></div>
    <div class="absolute -top-40 -right-40 w-96 h-96 bg-blue-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20"></div>

    <div class="relative w-full max-w-md p-6">
        <div class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-3xl shadow-2xl p-8 md:p-10">
            <div class="text-center mb-10">
                <div class="w-16 h-16 bg-blue-500/20 rounded-2xl mx-auto flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M12 12h.01M12 6h.01M12 2a10 10 0 0110 10 10 10 0 01-10 10A10 10 0 012 12 10 10 0 0112 2z"/></svg>
                </div>
                <h1 class="text-2xl font-bold text-white mb-2">تفعيل الحساب</h1>
                <p class="text-blue-200 text-sm">أدخل رقم هاتفك للبدء في تفعيل حسابك أو استعادة كلمة المرور</p>
            </div>

            <form method="POST" action="{{ route('portal.setup.check') }}" class="space-y-6">
                @csrf
                <div class="space-y-2">
                    <label class="text-xs font-bold text-blue-200 mr-1 uppercase opacity-80">رقم الهاتف</label>
                    <input type="text" name="phone" required 
                           class="w-full px-4 py-4 bg-gray-800/50 border border-gray-700 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500/50 font-english" 
                           placeholder="09xxxxxxxx">
                    @error('phone') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <button type="submit" class="w-full py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition shadow-lg">
                    متابعة
                </button>
            </form>
            
            <div class="mt-6 text-center">
                <a href="{{ route('portal.login') }}" class="text-sm text-gray-400 hover:text-white transition">العودة لتسجيل الدخول</a>
            </div>
        </div>
    </div>
</body>
</html>

```

## resources/views/portal\setup\reset.blade.php

```php
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>تعيين كلمة المرور | بوابة المشتركين</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Cairo', sans-serif; } .font-english { font-family: 'Outfit', sans-serif; }</style>
</head>
<body class="bg-gray-900 min-h-screen flex items-center justify-center relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-gray-900 via-blue-900 to-gray-900"></div>
    <div class="relative w-full max-w-md p-6">
        <div class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-3xl shadow-2xl p-8 md:p-10">
            <div class="text-center mb-8">
                <h1 class="text-2xl font-bold text-white mb-2">كلمة المرور الجديدة</h1>
                <p class="text-blue-200 text-sm">اختر كلمة مرور قوية لحماية حسابك</p>
            </div>

            <form method="POST" action="{{ route('portal.setup.update') }}" class="space-y-6">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                
                <div class="space-y-2">
                    <label class="text-xs font-bold text-blue-200 mr-1 uppercase opacity-80">كلمة المرور الجديدة</label>
                    <input type="password" name="password" required 
                           class="w-full px-4 py-4 bg-gray-800/50 border border-gray-700 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500/50 font-english" 
                           placeholder="••••••••">
                    @error('password') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-bold text-blue-200 mr-1 uppercase opacity-80">تأكيد كلمة المرور</label>
                    <input type="password" name="password_confirmation" required 
                           class="w-full px-4 py-4 bg-gray-800/50 border border-gray-700 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500/50 font-english" 
                           placeholder="••••••••">
                </div>

                <button type="submit" class="w-full py-4 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl transition shadow-lg">
                    حفظ وتسجيل الدخول
                </button>
            </form>
        </div>
    </div>
</body>
</html>

```

## resources/views/portal\setup\sent.blade.php

```php
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>تم الإرسال | بوابة المشتركين</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Cairo', sans-serif; }</style>
</head>
<body class="bg-gray-900 min-h-screen flex items-center justify-center relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-gray-900 via-blue-900 to-gray-900"></div>
    <div class="relative w-full max-w-md p-6">
        <div class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-3xl shadow-2xl p-8 md:p-10 text-center">
            <div class="w-20 h-20 bg-green-500/20 rounded-full mx-auto flex items-center justify-center mb-6 animate-bounce">
                <svg class="w-10 h-10 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
            
            <h1 class="text-2xl font-bold text-white mb-4">تم إرسال الرابط!</h1>
            <p class="text-blue-200 text-sm mb-8 leading-relaxed">
                تم إرسال رابط تعيين كلمة المرور إلى بريدك الإلكتروني:<br>
                <span class="font-bold text-white font-english mt-2 block">{{ $client->email }}</span>
            </p>

            <a href="{{ route('portal.login') }}" class="inline-block w-full py-4 bg-gray-700 hover:bg-gray-600 text-white font-bold rounded-xl transition">
                العودة لتسجيل الدخول
            </a>
            
            <p class="mt-6 text-xs text-gray-500">ملاحظة: بما أن النظام تجريبي، يرجى مراجعة ملف السيرفر `laravel.log` للحصول على الرابط.</p>
        </div>
    </div>
</body>
</html>

```

## resources/views/subscription\index.blade.php

```php
@extends('layouts.app')

@push('styles')
<style>
    .payment-option.selected {
        border-color: #6366f1;
        background-color: #f5f3ff;
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8" x-data="{ 
    selectedPlan: null, 
    showModal: false,
    selectedMethod: '{{ array_key_first($gateways) ?? "" }}',
    managePlans: {{ auth()->user()->hasAccess() ? 'false' : 'true' }},
    
    openModal(plan) {
        this.selectedPlan = plan;
        this.showModal = true;
    },
    
    get amount() {
        return this.selectedPlan === 'basic_annual' ? 50 : 100;
    }
}">
    <div class="max-w-4xl mx-auto text-center mb-12">
        <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
            إدارة اشتراكك وباقاتك 🚀
        </h2>
        <p class="mt-4 text-lg text-gray-600">
            خطط سنوية مصممة لتلبية احتياجات جميع مزودي الخدمة
        </p>
    </div>

    <!-- Current Subscription Status -->
    <div class="max-w-3xl mx-auto mb-16">
        <div class="bg-white rounded-2xl shadow-lg border border-indigo-100 overflow-hidden relative">
            <div class="absolute top-0 right-0 w-2 h-full bg-indigo-500"></div>
            <div class="p-6 md:p-8 flex flex-col md:flex-row items-center justify-between gap-6">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">حالة الاشتراك الحالية</h3>
                    <div class="mt-2 flex items-center gap-2">
                        @if(auth()->user()->subscription_status === 'active')
                            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-bold flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                نشط (Active)
                            </span>
                        @elseif(auth()->user()->onTrial())
                            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-bold flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                فترة تجريبية (Trial)
                            </span>
                        @else
                            <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-sm font-bold flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                                غير نشط / منتهي
                            </span>
                        @endif
                        <span class="text-gray-500 text-sm">|</span>
                        <span class="font-semibold text-gray-700">الباقة: {{ auth()->user()->plan_name == 'pro_annual' ? 'الاحترافية (Pro)' : (auth()->user()->plan_name == 'basic_annual' ? 'الأساسية (Basic)' : 'مجانية / غير محددة') }}</span>
                    </div>
                </div>
                
                <div class="text-center md:text-left flex flex-col items-end gap-2">
                    @if(auth()->user()->subscription_ends_at)
                        <div>
                            <p class="text-sm text-gray-500 mb-1">تاريخ الانتهاء</p>
                            <p class="text-xl font-bold text-gray-900">{{ \Carbon\Carbon::parse(auth()->user()->subscription_ends_at)->format('Y-m-d') }}</p>
                            <p class="text-xs text-indigo-600 mt-1">({{ \Carbon\Carbon::parse(auth()->user()->subscription_ends_at)->diffForHumans() }})</p>
                        </div>
                    @elseif(auth()->user()->trial_ends_at)
                         <div>
                            <p class="text-sm text-gray-500 mb-1">تاريخ انتهاء التجربة</p>
                            <p class="text-xl font-bold text-gray-900">{{ \Carbon\Carbon::parse(auth()->user()->trial_ends_at)->format('Y-m-d') }}</p>
                            <p class="text-xs text-indigo-600 mt-1">({{ \Carbon\Carbon::parse(auth()->user()->trial_ends_at)->diffForHumans() }})</p>
                        </div>
                    @else
                        <p class="text-sm text-gray-500">لا يوجد تاريخ انتهاء</p>
                    @endif

                    @if(auth()->user()->hasAccess())
                        <button @click="managePlans = !managePlans" class="text-sm text-indigo-600 hover:text-indigo-800 font-semibold underline mt-2">
                            <span x-text="managePlans ? 'إخفاء الباقات' : 'تغيير الباقة / ترقية (Change Plan)'"></span>
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Pricing Cards -->
    <div x-show="managePlans" x-collapse class="max-w-5xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Basic Plan -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden hover:shadow-xl transition-shadow relative">
            <div class="p-8">
                <h3 class="text-xl font-semibold text-gray-900">الباقة الأساسية</h3>
                <p class="mt-4 text-gray-500">للبدء في إدارة شبكتك بكفاءة</p>
                <div class="mt-6 flex items-baseline justify-center">
                    <span class="text-5xl font-extrabold text-gray-900">$50</span>
                    <span class="text-xl font-medium text-gray-500">/ سنوياً</span>
                </div>
                
                <ul class="mt-8 space-y-4 text-right">
                    <li class="flex items-center"><svg class="flex-shrink-0 h-5 w-5 text-green-500 ml-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg><span class="text-gray-600">لوحة تحكم أساسية (Dashboard)</span></li>
                    <li class="flex items-center"><svg class="flex-shrink-0 h-5 w-5 text-green-500 ml-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg><span class="text-gray-600">إدارة المشتركين (Clients)</span></li>
                    <li class="flex items-center"><svg class="flex-shrink-0 h-5 w-5 text-green-500 ml-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg><span class="text-gray-600">مراقبة أساسية (Basic Monitoring)</span></li>
                </ul>
            </div>
            <div class="p-8 bg-gray-50 border-t border-gray-100 space-y-3">
                @if(auth()->user()->plan_name == 'basic_annual' && auth()->user()->hasAccess())
                    <div class="w-full bg-green-100 text-green-700 font-bold py-3 px-4 rounded-xl text-center cursor-default flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        باقتك الحالية
                    </div>
                @else
                    <button @click="openModal('basic_annual')" class="w-full bg-indigo-50 text-indigo-700 hover:bg-indigo-100 font-bold py-3 px-4 rounded-xl transition-colors">
                        {{ auth()->user()->plan_name == 'pro_annual' ? 'تخفيض (Downgrade)' : 'اشتراك / تحويل' }}
                    </button>
                    
                    @if(!auth()->user()->onTrial() && !auth()->user()->hasActiveSubscription())
                        <form action="{{ route('subscription.trial') }}" method="POST" class="w-full">
                            @csrf
                            <input type="hidden" name="plan" value="basic_annual">
                            <button type="submit" class="w-full bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 font-semibold py-3 px-4 rounded-xl transition-colors text-sm">
                                تجربة مجانية (5 أيام)
                            </button>
                        </form>
                    @endif
                @endif
            </div>
        </div>

        <!-- Pro Plan -->
        <div class="bg-gray-900 rounded-2xl shadow-2xl border border-indigo-500 overflow-hidden transform scale-105 relative">
            <div class="absolute top-0 right-0 bg-yellow-400 text-xs font-bold px-3 py-1 rounded-bl-lg">الأكثر مبيعاً</div>
            <div class="p-8">
                <h3 class="text-xl font-semibold text-white">الباقة الاحترافية (Pro)</h3>
                <p class="mt-4 text-indigo-200">للشركات التي تبحث عن التحكم الكامل</p>
                <div class="mt-6 flex items-baseline justify-center">
                    <span class="text-5xl font-extrabold text-white">$100</span>
                    <span class="text-xl font-medium text-indigo-200">/ سنوياً</span>
                </div>
                
                <ul class="mt-8 space-y-4 text-right">
                    <li class="flex items-center"><svg class="flex-shrink-0 h-5 w-5 text-green-400 ml-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg><span class="text-gray-300">جميع ميزات الباقة الأساسية</span></li>
                    <li class="flex items-center"><svg class="flex-shrink-0 h-5 w-5 text-green-400 ml-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg><span class="text-gray-300 font-bold">تقارير مالية ومحاسبة</span></li>
                    <li class="flex items-center"><svg class="flex-shrink-0 h-5 w-5 text-green-400 ml-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg><span class="text-gray-300 font-bold">خريطة الشبكة (NOC Topology)</span></li>
                    <li class="flex items-center"><svg class="flex-shrink-0 h-5 w-5 text-green-400 ml-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg><span class="text-gray-300 font-bold">نظام الفواتير (Invoices)</span></li>
                </ul>
            </div>
            <div class="p-8 bg-gray-800 border-t border-gray-700 space-y-3">
                @if(auth()->user()->plan_name == 'pro_annual' && auth()->user()->hasAccess())
                    <div class="w-full bg-green-600/20 text-green-400 font-bold py-3 px-4 rounded-xl text-center cursor-default flex items-center justify-center gap-2 border border-green-500/30">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        باقتك الحالية
                    </div>
                @else
                    <button @click="openModal('pro_annual')" class="w-full bg-gradient-to-r from-purple-600 to-indigo-600 text-white hover:from-purple-700 hover:to-indigo-700 font-bold py-3 px-4 rounded-xl shadow-lg transition-all transform hover:scale-105">
                        {{ auth()->user()->plan_name == 'basic_annual' ? 'ترقية الآن (Upgrade)' : 'اشتراك / تحويل' }}
                    </button>

                    @if(!auth()->user()->onTrial() && !auth()->user()->hasActiveSubscription())
                        <form action="{{ route('subscription.trial') }}" method="POST" class="w-full">
                            @csrf
                            <input type="hidden" name="plan" value="pro_annual">
                            <button type="submit" class="w-full bg-gray-700 border border-gray-600 text-gray-300 hover:bg-gray-600 font-semibold py-3 px-4 rounded-xl transition-colors text-sm">
                                تجربة مجانية (5 أيام)
                            </button>
                        </form>
                    @endif
                @endif
            </div>
        </div>
    </div>

    <!-- Payment Modal -->
    <div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showModal" @click="showModal = false" class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <div x-show="showModal" class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4 text-center">
                    إتمام الاشتراك (<span x-text="selectedPlan == 'basic_annual' ? 'Basic' : 'Pro'"></span>)
                </h3>

                <form action="{{ route('subscription.subscribe') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="plan" :value="selectedPlan">

                    <!-- Amount -->
                    <div class="mb-6 text-center">
                        <span class="text-3xl font-bold text-gray-900">$<span x-text="amount"></span></span>
                        <span class="text-gray-500">/ سنوياً</span>
                    </div>

                    <!-- Payment Methods -->
                    <div class="grid grid-cols-1 gap-4 mb-6">
                        @if(isset($gateways['stripe']))
                        <!-- Stripe -->
                        <div @click="selectedMethod = 'stripe'" 
                             :class="{'selected ring-2 ring-indigo-500': selectedMethod === 'stripe'}"
                             class="payment-option cursor-pointer border rounded-lg p-4 flex items-center justify-between hover:border-indigo-300 transition-all">
                            <div class="flex items-center">
                                <span class="ml-3 font-medium text-gray-900">Stripe (بطاقة ائتمان)</span>
                            </div>
                            <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                        </div>
                        @endif
                        
                        @if(isset($gateways['paypal']))
                        <!-- PayPal -->
                        <div @click="selectedMethod = 'paypal'" 
                             :class="{'selected ring-2 ring-indigo-500': selectedMethod === 'paypal'}"
                             class="payment-option cursor-pointer border rounded-lg p-4 flex items-center justify-between hover:border-indigo-300 transition-all">
                            <div class="flex items-center">
                                <span class="ml-3 font-medium text-gray-900">PayPal</span>
                            </div>
                            <!-- PayPal Icon -->
                             <span class="font-bold text-blue-700 italic">Pay<span class="text-blue-500">Pal</span></span>
                        </div>
                        @endif

                        @if(isset($gateways['sham_cash']))
                        <!-- Sham Cash -->
                        <div @click="selectedMethod = 'sham_cash'"
                             :class="{'selected ring-2 ring-indigo-500': selectedMethod === 'sham_cash'}"
                             class="payment-option cursor-pointer border rounded-lg p-4 flex items-center justify-between hover:border-indigo-300 transition-all">
                            <div class="flex items-center">
                                <span class="ml-3 font-medium text-gray-900">شام كاش (Sham Cash)</span>
                                <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded">محلي</span>
                            </div>
                            <svg class="h-6 w-6 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                        </div>
                        @endif

                        @if(isset($gateways['syriatel_cash']))
                        <!-- Syriatel Cash -->
                        <div @click="selectedMethod = 'syriatel_cash'"
                             :class="{'selected ring-2 ring-red-500': selectedMethod === 'syriatel_cash'}"
                             class="payment-option cursor-pointer border rounded-lg p-4 flex items-center justify-between hover:border-indigo-300 transition-all">
                            <div class="flex items-center">
                                <span class="ml-3 font-medium text-gray-900">Syriatel Cash (سيريتيل)</span>
                                <span class="bg-red-100 text-red-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded">محلي</span>
                            </div>
                            <svg class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                        </div>
                        @endif
 
                        @if(isset($gateways['turkish_iban']))
                        <!-- Turkish IBAN -->
                        <div @click="selectedMethod = 'turkish_iban'"
                             :class="{'selected ring-2 ring-red-500': selectedMethod === 'turkish_iban'}"
                             class="payment-option cursor-pointer border rounded-lg p-4 flex items-center justify-between hover:border-indigo-300 transition-all">
                            <div class="flex items-center">
                                <span class="ml-3 font-medium text-gray-900">تحويل بنكي (تركيا)</span>
                                <span class="bg-gray-100 text-gray-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded">IBAN</span>
                            </div>
                            <svg class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                        </div>
                        @endif
                        
                        @if(empty($gateways))
                            <div class="p-4 bg-red-50 text-red-600 rounded-lg text-center">
                                لا توجد وسائل دفع متاحة حالياً.
                            </div>
                        @endif
                    </div>

                    <input type="hidden" name="payment_method" :value="selectedMethod">

                    <!-- Sham Cash Details -->
                    <div x-show="selectedMethod === 'sham_cash'" class="mb-6 bg-yellow-50 p-4 rounded-lg border border-yellow-200">
                        <p class="text-sm text-yellow-800 mb-2 font-bold">تعليمات الدفع:</p>
                        
                        @php
                            $shamQr = $settings['sham_cash_qr'] ?? null;
                        @endphp
                         @if($shamQr)
                            <div class="mb-4 text-center bg-white p-4 rounded-xl border border-yellow-100 shadow-sm inline-block w-full">
                                <p class="text-xs text-gray-500 mb-2 font-bold">امسح الكود (QR Code)</p>
                                <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($shamQr) }}" alt="QR Code" class="h-48 w-auto mx-auto rounded-lg object-contain">
                            </div>
                        @endif

                        <div class="text-sm text-gray-700 mb-4 whitespace-pre-line">{{ $settings['sham_cash_instructions'] ?? 'يرجى التواصل مع الإدارة' }}</div>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">رقم العملية / الإشعار</label>
                                <input type="text" name="sham_cash_ref" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="أدخل رقم عملية التحويل">
                            </div>
                        </div>
                    </div>

                    <!-- Syriatel Cash Details -->
                    <div x-show="selectedMethod === 'syriatel_cash'" class="mb-6 bg-red-50 p-4 rounded-lg border border-red-200">
                        <p class="text-sm text-red-800 mb-2 font-bold">تعليمات الدفع (سيريتيل كاش):</p>
                        <div class="text-sm text-gray-700 mb-4 whitespace-pre-line">{{ $settings['syriatel_cash_instructions'] ?? 'يرجى التواصل مع الإدارة' }}</div>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">رقم العملية / الإشعار</label>
                                <input type="text" name="syriatel_ref" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="أدخل رقم عملية التحويل">
                            </div>
                        </div>
                    </div>

                    <!-- Turkish IBAN Details -->
                    <div x-show="selectedMethod === 'turkish_iban'" class="mb-6 bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <p class="text-sm text-gray-800 mb-2 font-bold">بيانات الحساب البنكي:</p>
                        <div class="text-sm text-gray-700 mb-4 whitespace-pre-line font-mono bg-white p-3 rounded border">{{ $settings['turkish_bank_details'] ?? 'يرجى التواصل مع الإدارة' }}</div>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">رقم العملية / اسم المرسل</label>
                                <input type="text" name="turkish_ref" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="أدخل مرجع التحويل">
                            </div>
                        </div>
                    </div>

                    <!-- Common Receipt Upload (Visible for all manual methods) -->
                    <div x-show="['sham_cash', 'syriatel_cash', 'turkish_iban'].includes(selectedMethod)" class="mb-6">
                         <label class="block text-sm font-medium text-gray-700 mb-1">صورة الإشعار (مطلوب)</label>
                         <input type="file" name="receipt_image" accept="image/*" class="block w-full text-sm text-gray-500
                           file:mr-4 file:py-2 file:px-4
                           file:rounded-full file:border-0
                           file:text-sm file:font-semibold
                           file:bg-indigo-50 file:text-indigo-700
                           hover:file:bg-indigo-100
                         "/>
                    </div>

                    <button type="submit" class="w-full bg-indigo-600 border border-transparent rounded-md shadow-sm py-3 px-4 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        إتمام وإرسال
                    </button>
                    
                    <button type="button" @click="showModal = false" class="mt-3 w-full bg-white border border-gray-300 rounded-md shadow-sm py-3 px-4 text-base font-medium text-gray-700 hover:bg-gray-50">
                        إلغاء
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection

```

## resources/views/support\contact.blade.php

```php
@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto pb-12">
    <!-- Header Protocol -->
    <div class="glass-panel border border-white/40 rounded-[3rem] p-10 md:p-12 shadow-2xl relative overflow-hidden mb-12">
        <div class="absolute -top-24 -right-24 w-64 h-64 bg-indigo-600/5 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-emerald-600/5 rounded-full blur-3xl"></div>

        <div class="relative flex flex-col md:flex-row items-center gap-10">
            <div class="w-24 h-24 bg-indigo-600 rounded-[2rem] flex items-center justify-center text-white shadow-2xl shadow-indigo-200">
                <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
            <div class="text-center md:text-right">
                <h1 class="text-4xl font-black text-gray-900 tracking-tighter mb-2">بروتوكول الدعم الفني</h1>
                <p class="text-lg text-gray-500 font-medium">نحن هنا لضمان استقرار عملياتك وتوسيع نطاق شبكتك. تواصل معنا عبر القنوات المعتمدة.</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Contact Cards -->
        <div class="lg:col-span-1 space-y-6">
            <h4 class="text-xs font-black text-indigo-600 uppercase tracking-[0.2em] px-4 mb-4">قنوات الاتصال المباشر</h4>
            
            <!-- WhatsApp Card -->
            <a href="https://wa.me/963951555555" target="_blank" class="glass-panel border border-white/60 p-6 rounded-[2.5rem] flex items-center gap-6 group hover:scale-[1.02] transition-all shadow-xl block">
                <div class="w-16 h-16 bg-emerald-500/10 rounded-2xl flex items-center justify-center text-emerald-600 border border-emerald-500/20 group-hover:bg-emerald-500 group-hover:text-white transition-all">
                    <span class="text-3xl">💬</span>
                </div>
                <div>
                    <h5 class="text-sm font-black text-gray-900 uppercase">WhatsApp</h5>
                    <p class="text-[10px] font-bold text-emerald-600 uppercase tracking-widest mt-0.5">الاستجابة الفورية</p>
                </div>
                <div class="mr-auto opacity-0 group-hover:opacity-100 transition-all">
                    <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </div>
            </a>

            <!-- Email Card -->
            <a href="mailto:support@madaaq.com" class="glass-panel border border-white/60 p-6 rounded-[2.5rem] flex items-center gap-6 group hover:scale-[1.02] transition-all shadow-xl block">
                <div class="w-16 h-16 bg-blue-500/10 rounded-2xl flex items-center justify-center text-blue-600 border border-blue-500/20 group-hover:bg-blue-500 group-hover:text-white transition-all">
                    <span class="text-3xl">📧</span>
                </div>
                <div>
                    <h5 class="text-sm font-black text-gray-900 uppercase">Email Protocol</h5>
                    <p class="text-[10px] font-bold text-blue-600 uppercase tracking-widest mt-0.5">المراسلات الرسمية</p>
                </div>
                <div class="mr-auto opacity-0 group-hover:opacity-100 transition-all">
                    <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </div>
            </a>

            <!-- Telegram Card -->
            <div class="glass-panel border border-white/60 p-6 rounded-[2.5rem] flex items-center gap-6 group hover:scale-[1.02] transition-all shadow-xl">
                <div class="w-16 h-16 bg-sky-500/10 rounded-2xl flex items-center justify-center text-sky-600 border border-sky-500/20 group-hover:bg-sky-500 group-hover:text-white transition-all">
                    <span class="text-3xl">✈️</span>
                </div>
                <div>
                    <h5 class="text-sm font-black text-gray-900 uppercase">Telegram Hub</h5>
                    <p class="text-[10px] font-bold text-sky-600 uppercase tracking-widest mt-0.5">قناة التحديثات</p>
                </div>
            </div>
        </div>

        <!-- Support Inquiry Form -->
        <div class="lg:col-span-2">
            <div class="glass-panel border border-white/40 rounded-[3rem] p-8 md:p-12 shadow-2xl relative overflow-hidden h-full">
                <div class="relative z-10">
                    <div class="flex items-center gap-4 mb-10">
                        <div class="w-12 h-12 bg-indigo-600/10 rounded-2xl flex items-center justify-center text-indigo-600">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                        </div>
                        <h3 class="text-2xl font-black text-gray-900 tracking-tight uppercase">إرسال طلب مساعدة</h3>
                    </div>

                    <form action="{{ route('support.send') }}" method="POST" class="space-y-8">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mr-4">نوع الطلب</label>
                                <select name="subject" class="w-full bg-white/50 backdrop-blur-md border border-white/80 rounded-2xl px-6 py-4 outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all font-bold text-gray-900">
                                    <option value="technical">مشكلة تقنية في السيرفر</option>
                                    <option value="accounting">استفسار مالي أو فاتورة</option>
                                    <option value="feature">اقتراح ميزة جديدة</option>
                                    <option value="other">أخرى</option>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mr-4">رقم الموبايل للتواصل</label>
                                <input type="text" value="{{ auth()->user()->phone ?? '' }}" class="w-full bg-white/50 backdrop-blur-md border border-white/80 rounded-2xl px-6 py-4 outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all font-mono font-bold text-gray-900" placeholder="+963 ...">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mr-4">تفاصيل الاستفسار</label>
                            <textarea name="message" rows="5" class="w-full bg-white/50 backdrop-blur-md border border-white/80 rounded-3xl px-6 py-4 outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all font-medium text-gray-900" placeholder="كيف يمكننا مساعدتك اليوم؟"></textarea>
                        </div>

                        <div class="pt-4">
                            <button type="submit" class="w-full py-5 bg-indigo-600 text-white rounded-[2rem] font-black text-xs uppercase tracking-[0.2em] shadow-2xl shadow-indigo-200 hover:scale-[1.02] active:scale-95 transition-all flex items-center justify-center gap-4">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                إرسال إلى مركز العمليات
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- Decorative Element -->
                <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-indigo-600/5 rounded-full blur-3xl"></div>
            </div>
        </div>
    </div>
</div>
@endsection

```

## resources/views/accounting\invoices\create.blade.php

```php
@extends('layouts.app')

@push('styles')
<style>
    .glass-panel { background: rgba(255, 255, 255, 0.4); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); }
    [x-cloak] { display: none !important; }
</style>
@endpush

@section('content')
<div class="max-w-4xl mx-auto space-y-10 pb-20">
    <!-- Transaction Header -->
    <div class="glass-panel border border-white/40 rounded-[3rem] p-10 md:p-12 shadow-2xl relative overflow-hidden">
        <div class="absolute -top-24 -right-24 w-64 h-64 bg-emerald-600/5 rounded-full blur-3xl"></div>
        <div class="flex flex-col md:flex-row items-center justify-between gap-8 relative z-10">
            <div class="flex items-center gap-8">
                <div class="w-20 h-20 bg-emerald-600 rounded-[2rem] flex items-center justify-center text-white shadow-xl shadow-emerald-100 shrink-0">
                    <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div class="text-center md:text-left">
                    <h2 class="text-3xl font-black text-gray-900 tracking-tighter uppercase">Initialize Fiscal Node</h2>
                    <p class="text-gray-500 font-bold text-xs uppercase tracking-widest mt-1">Generating new revenue stream protocol</p>
                </div>
            </div>
            <a href="{{ route('accounting.invoices.index') }}" class="px-8 py-4 bg-white/60 text-gray-500 font-black text-[11px] uppercase tracking-widest rounded-2xl hover:bg-white hover:text-gray-900 transition-all border border-white/60 shadow-sm flex items-center gap-3">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Ledger View
            </a>
        </div>
    </div>

    <form action="{{ route('accounting.invoices.store') }}" method="POST" class="space-y-10">
        @csrf

        <!-- Registry Configuration -->
        <div class="glass-panel border border-white/40 rounded-[3rem] p-10 md:p-12 shadow-2xl relative overflow-hidden">
            <div class="flex items-center gap-4 mb-10">
                <div class="w-12 h-12 bg-indigo-600/10 rounded-2xl flex items-center justify-center text-indigo-600 font-bold">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <h3 class="text-2xl font-black text-gray-900 tracking-tight uppercase">Counterparty Assignment</h3>
            </div>
            
            <!-- Client Discovery -->
            <div class="space-y-4">
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-2">Assign Legal Entity <span class="text-rose-500">*</span></label>
                <div class="relative">
                    <select name="client_id" required class="w-full pl-6 pr-12 py-5 bg-white/40 backdrop-blur-md border border-white/60 rounded-3xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500/40 font-black text-gray-900 appearance-none transition-all shadow-inner">
                        <option value="">Select Counterparty Identity...</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}">{{ $client->name }} ({{ $client->phone }})</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-6 flex items-center pointer-events-none text-gray-400">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Fiscal Parameters -->
        <div class="glass-panel border border-white/40 rounded-[3rem] p-10 md:p-12 shadow-2xl relative overflow-hidden">
            <div class="flex items-center gap-4 mb-10">
                <div class="w-12 h-12 bg-emerald-600/10 rounded-2xl flex items-center justify-center text-emerald-600 font-bold">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="text-2xl font-black text-gray-900 tracking-tight uppercase">Economic Parameters</h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                <!-- Amount Protocol -->
                <div class="space-y-4">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-2">Monetary Sum ({{ $currency }}) <span class="text-rose-500">*</span></label>
                    <div class="relative group">
                        <input type="number" step="0.01" name="amount" required class="w-full pl-6 pr-12 py-5 bg-white/40 backdrop-blur-md border border-white/60 rounded-3xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500/40 font-mono font-black text-gray-900 transition-all shadow-inner" placeholder="0.00">
                        <div class="absolute inset-y-0 right-0 pr-6 flex items-center pointer-events-none text-emerald-600 font-black text-xs opacity-40 group-focus-within:opacity-100 transition-opacity">
                            {{ $currency }}
                        </div>
                    </div>
                </div>

                <!-- Temporal Protocol -->
                <div class="space-y-4">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-2">Maturity Horizon <span class="text-rose-500">*</span></label>
                    <input type="date" name="due_date" value="{{ date('Y-m-d') }}" required class="w-full px-6 py-5 bg-white/40 backdrop-blur-md border border-white/60 rounded-3xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500/40 font-black text-gray-900 transition-all shadow-inner">
                </div>
            </div>

            <!-- Settlement Status -->
            <div class="mt-12 space-y-4">
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-2">Settlement Protocol</label>
                <div class="flex flex-wrap gap-6">
                    <label class="flex-1 min-w-[200px] cursor-pointer group">
                        <input type="radio" name="status" value="unpaid" checked class="hidden peer">
                        <div class="p-6 bg-white/40 backdrop-blur-md border border-white/60 rounded-3xl transition-all peer-checked:bg-rose-500/5 peer-checked:border-rose-500/40 peer-checked:ring-4 peer-checked:ring-rose-500/10 group-hover:scale-[1.02] flex items-center gap-4">
                            <div class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center text-gray-400 peer-checked:bg-rose-500 peer-checked:text-white transition-colors">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest peer-checked:text-rose-600 transition-colors">Protocol: Pending</p>
                                <p class="text-sm font-black text-gray-900 uppercase tracking-tight">Unpaid Archive</p>
                            </div>
                        </div>
                    </label>

                    <label class="flex-1 min-w-[200px] cursor-pointer group">
                        <input type="radio" name="status" value="paid" class="hidden peer">
                        <div class="p-6 bg-white/40 backdrop-blur-md border border-white/60 rounded-3xl transition-all peer-checked:bg-emerald-500/5 peer-checked:border-emerald-500/40 peer-checked:ring-4 peer-checked:ring-emerald-500/10 group-hover:scale-[1.02] flex items-center gap-4">
                            <div class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center text-gray-400 peer-checked:bg-emerald-500 peer-checked:text-white transition-colors">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest peer-checked:text-emerald-600 transition-colors">Protocol: Verified</p>
                                <p class="text-sm font-black text-gray-900 uppercase tracking-tight">Instant Settlement</p>
                            </div>
                        </div>
                    </label>
                </div>
            </div>
        </div>

        <!-- Narrative Context -->
        <div class="glass-panel border border-white/40 rounded-[3rem] p-10 md:p-12 shadow-2xl relative overflow-hidden">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-12 h-12 bg-gray-600/10 rounded-2xl flex items-center justify-center text-gray-600 font-bold">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h7"/></svg>
                </div>
                <h3 class="text-2xl font-black text-gray-900 tracking-tight uppercase">Narrative Context</h3>
            </div>
            
            <div class="space-y-4">
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-2">Registry Memo / Descriptive Index</label>
                <textarea name="description" rows="4" class="w-full px-6 py-5 bg-white/40 backdrop-blur-md border border-white/60 rounded-3xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500/40 font-black text-gray-900 transition-all shadow-inner" placeholder="Provide strategic narrative for this fiscal node..."></textarea>
            </div>
        </div>

        <!-- Protocol Commitment -->
        <div class="flex flex-col md:flex-row items-center justify-between gap-10 glass-panel border border-white/40 p-12 rounded-[3.5rem] shadow-2xl relative overflow-hidden">
            <div class="absolute bottom-0 right-0 w-64 h-64 bg-emerald-600/5 rounded-full blur-3xl -mb-32 -mr-32"></div>
            <div class="flex items-center gap-8 relative z-10">
                <div class="w-16 h-16 bg-emerald-600/10 text-emerald-600 rounded-[1.5rem] flex items-center justify-center text-3xl shrink-0">🏛️</div>
                <div class="space-y-1">
                    <h5 class="text-lg font-black text-gray-900 uppercase tracking-tight">Fiscal Commitment</h5>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Formalizing financial entry in the immutable ledger.</p>
                </div>
            </div>
            <div class="flex items-center gap-6 relative z-10">
                <a href="{{ route('accounting.invoices.index') }}" class="px-10 py-5 bg-white/60 text-gray-500 font-black text-[11px] uppercase tracking-[0.2em] rounded-2xl hover:bg-white hover:text-gray-900 transition-all">
                    Discard Entry
                </a>
                <button type="submit" class="px-12 py-5 bg-gray-900 text-white font-black text-[11px] uppercase tracking-[0.3em] rounded-2xl shadow-2xl shadow-indigo-200 hover:bg-emerald-600 transition-all hover:scale-105 active:scale-95 leading-none">
                    Initialize Ledger commit
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

```

## resources/views/accounting\invoices\edit.blade.php

```php
@extends('layouts.app')

@push('styles')
<style>
    .glass-panel { background: rgba(255, 255, 255, 0.4); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); }
    [x-cloak] { display: none !important; }
</style>
@endpush

@section('content')
<div class="max-w-4xl mx-auto space-y-10 pb-20">
    <!-- Modification Header -->
    <div class="glass-panel border border-white/40 rounded-[3rem] p-10 md:p-12 shadow-2xl relative overflow-hidden">
        <div class="absolute -top-24 -right-24 w-64 h-64 bg-indigo-600/5 rounded-full blur-3xl"></div>
        <div class="flex flex-col md:flex-row items-center justify-between gap-8 relative z-10">
            <div class="flex items-center gap-8">
                <div class="w-20 h-20 bg-indigo-600 rounded-[2rem] flex items-center justify-center text-white shadow-xl shadow-indigo-100 shrink-0">
                    <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </div>
                <div class="text-center md:text-left">
                    <h2 class="text-3xl font-black text-gray-900 tracking-tighter uppercase">Modify Fiscal Node #{{ $invoice->invoice_number }}</h2>
                    <p class="text-gray-500 font-bold text-xs uppercase tracking-widest mt-1">Adjusting protocol for {{ $invoice->client->name }}</p>
                </div>
            </div>
            <a href="{{ route('accounting.invoices.index') }}" class="px-8 py-4 bg-white/60 text-gray-500 font-black text-[11px] uppercase tracking-widest rounded-2xl hover:bg-white hover:text-gray-900 transition-all border border-white/60 shadow-sm flex items-center gap-3">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Ledger View
            </a>
        </div>
    </div>

    <form action="{{ route('accounting.invoices.update', $invoice) }}" method="POST" class="space-y-10">
        @csrf
        @method('PUT')

        <!-- Fiscal Parameters -->
        <div class="glass-panel border border-white/40 rounded-[3rem] p-10 md:p-12 shadow-2xl relative overflow-hidden">
            <div class="flex items-center gap-4 mb-10">
                <div class="w-12 h-12 bg-emerald-600/10 rounded-2xl flex items-center justify-center text-emerald-600 font-bold">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="text-2xl font-black text-gray-900 tracking-tight uppercase">Adjust Economic Parameters</h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                <!-- Amount Protocol -->
                <div class="space-y-4">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-2">Monetary Sum ({{ $currency }}) <span class="text-rose-500">*</span></label>
                    <div class="relative group">
                        <input type="number" step="0.01" name="amount" value="{{ $invoice->amount }}" required class="w-full pl-6 pr-12 py-5 bg-white/40 backdrop-blur-md border border-white/60 rounded-3xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500/40 font-mono font-black text-gray-900 transition-all shadow-inner" placeholder="0.00">
                        <div class="absolute inset-y-0 right-0 pr-6 flex items-center pointer-events-none text-emerald-600 font-black text-xs opacity-40 group-focus-within:opacity-100 transition-opacity">
                            {{ $currency }}
                        </div>
                    </div>
                </div>

                <!-- Temporal Protocol -->
                <div class="space-y-4">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-2">Maturity Horizon <span class="text-rose-500">*</span></label>
                    <input type="date" name="due_date" value="{{ $invoice->due_date->format('Y-m-d') }}" required class="w-full px-6 py-5 bg-white/40 backdrop-blur-md border border-white/60 rounded-3xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500/40 font-black text-gray-900 transition-all shadow-inner">
                </div>
            </div>

            <!-- Settlement Status -->
            <div class="mt-12 space-y-4">
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-2">Settlement Protocol</label>
                <div class="flex flex-wrap gap-6">
                    <label class="flex-1 min-w-[200px] cursor-pointer group">
                        <input type="radio" name="status" value="unpaid" {{ $invoice->status == 'unpaid' ? 'checked' : '' }} class="hidden peer">
                        <div class="p-6 bg-white/40 backdrop-blur-md border border-white/60 rounded-3xl transition-all peer-checked:bg-rose-500/5 peer-checked:border-rose-500/40 peer-checked:ring-4 peer-checked:ring-rose-500/10 group-hover:scale-[1.02] flex items-center gap-4">
                            <div class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center text-gray-400 peer-checked:bg-rose-500 peer-checked:text-white transition-colors">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest peer-checked:text-rose-600 transition-colors">Protocol: Pending</p>
                                <p class="text-sm font-black text-gray-900 uppercase tracking-tight">Unpaid Archive</p>
                            </div>
                        </div>
                    </label>

                    <label class="flex-1 min-w-[200px] cursor-pointer group">
                        <input type="radio" name="status" value="paid" {{ $invoice->status == 'paid' ? 'checked' : '' }} class="hidden peer">
                        <div class="p-6 bg-white/40 backdrop-blur-md border border-white/60 rounded-3xl transition-all peer-checked:bg-emerald-500/5 peer-checked:border-emerald-500/40 peer-checked:ring-4 peer-checked:ring-emerald-500/10 group-hover:scale-[1.02] flex items-center gap-4">
                            <div class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center text-gray-400 peer-checked:bg-emerald-500 peer-checked:text-white transition-colors">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest peer-checked:text-emerald-600 transition-colors">Protocol: Verified</p>
                                <p class="text-sm font-black text-gray-900 uppercase tracking-tight">Confirmed Settlement</p>
                            </div>
                        </div>
                    </label>
                </div>
            </div>
        </div>

        <!-- Narrative Context -->
        <div class="glass-panel border border-white/40 rounded-[3rem] p-10 md:p-12 shadow-2xl relative overflow-hidden">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-12 h-12 bg-gray-600/10 rounded-2xl flex items-center justify-center text-gray-600 font-bold">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h7"/></svg>
                </div>
                <h3 class="text-2xl font-black text-gray-900 tracking-tight uppercase">Narrative Context</h3>
            </div>
            
            <div class="space-y-4">
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-2">Registry Memo / Descriptive Index</label>
                <textarea name="description" rows="4" class="w-full px-6 py-5 bg-white/40 backdrop-blur-md border border-white/60 rounded-3xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500/40 font-black text-gray-900 transition-all shadow-inner" placeholder="Provide strategic narrative for this fiscal node...">{{ $invoice->description ?? '' }}</textarea>
            </div>
        </div>

        <!-- Commitment & Removal Controllers -->
        <div class="flex flex-col md:flex-row items-center justify-between gap-10 glass-panel border border-white/40 p-12 rounded-[3.5rem] shadow-2xl relative overflow-hidden">
            <div class="absolute bottom-0 left-0 w-64 h-64 bg-rose-600/5 rounded-full blur-3xl -mb-32 -ml-32"></div>
            <div class="flex items-center gap-8 relative z-10">
                <button type="button" onclick="if(confirm('Initiate removal protocol for this fiscal node?')) document.getElementById('delete-form').submit()" class="w-16 h-16 bg-rose-500/10 text-rose-500 rounded-[1.5rem] flex items-center justify-center text-2xl shrink-0 hover:bg-rose-500 hover:text-white transition-all shadow-lg hover:shadow-rose-100 group">
                    <svg class="w-8 h-8 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
                <div class="space-y-1">
                    <h5 class="text-lg font-black text-gray-900 uppercase tracking-tight">Fiscal Commitment</h5>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Adjusting the immutable ledger state.</p>
                </div>
            </div>
            <div class="flex items-center gap-6 relative z-10">
                <a href="{{ route('accounting.invoices.index') }}" class="px-10 py-5 bg-white/60 text-gray-500 font-black text-[11px] uppercase tracking-[0.2em] rounded-2xl hover:bg-white hover:text-gray-900 transition-all">
                    Discard Changes
                </a>
                <button type="submit" class="px-12 py-5 bg-gray-900 text-white font-black text-[11px] uppercase tracking-[0.3em] rounded-2xl shadow-2xl shadow-indigo-200 hover:bg-emerald-600 transition-all hover:scale-105 active:scale-95 leading-none">
                    Sync Ledger commit
                </button>
            </div>
        </div>
    </form>
</div>

<form id="delete-form" action="{{ route('accounting.invoices.destroy', $invoice) }}" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>
@endsection

```

## resources/views/accounting\invoices\index.blade.php

```php
@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header & Stats -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-12">
        <div class="relative">
            <div class="absolute -top-4 -right-4 w-24 h-24 bg-emerald-500/10 rounded-full blur-2xl -z-10"></div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">الفواتير والتحصيل المالي</h1>
            <p class="text-xs font-bold text-emerald-600 uppercase tracking-widest mt-1 flex items-center gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-600 animate-pulse shadow-[0_0_8px_rgba(16,185,129,0.5)]"></span>
                Financial Ledger & Revenue Hub
            </p>
        </div>
        <a href="{{ route('accounting.invoices.create') }}" class="inline-flex items-center gap-3 px-8 py-3 bg-gray-900 hover:bg-black text-white font-black rounded-2xl shadow-xl hover:shadow-gray-900/20 transition-all transform hover:scale-105 active:scale-95 group">
            <svg class="w-5 h-5 group-hover:rotate-90 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            <span>إنشاء فاتورة جديدة</span>
        </a>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
        <!-- Total Revenue -->
        <div class="glass-panel rounded-3xl p-8 relative overflow-hidden group hover:translate-y-[-4px] transition-all duration-500 hover:shadow-2xl hover:shadow-emerald-500/10">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-emerald-500/10 rounded-full blur-3xl group-hover:bg-emerald-500/20 transition-colors"></div>
            <div class="relative z-10 text-right">
                <p class="text-gray-400 text-[10px] font-black uppercase tracking-widest mb-1">إجمالي الإيرادات</p>
                <p class="text-3xl font-black text-emerald-600 tracking-tighter">@money($stats['total_revenue'])</p>
                <div class="mt-4 flex items-center text-[10px] font-black text-emerald-600 uppercase tracking-widest">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-600 ml-2 animate-pulse"></span>
                    Total Collected
                </div>
            </div>
        </div>
        
        <!-- Unpaid Amount -->
        <div class="glass-panel rounded-3xl p-8 relative overflow-hidden group hover:translate-y-[-4px] transition-all duration-500 hover:shadow-2xl hover:shadow-rose-500/10">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-rose-500/10 rounded-full blur-3xl group-hover:bg-rose-500/20 transition-colors"></div>
            <div class="relative z-10 text-right">
                <p class="text-gray-400 text-[10px] font-black uppercase tracking-widest mb-1">المستحقات المعلقة</p>
                <p class="text-3xl font-black text-rose-600 tracking-tighter">@money($stats['unpaid_amount'])</p>
                <div class="mt-4 flex items-center text-[10px] font-black text-rose-600 uppercase tracking-widest">
                    <span class="w-1.5 h-1.5 rounded-full bg-rose-600 ml-2 animate-pulse"></span>
                    Pending Dues
                </div>
            </div>
        </div>

        <!-- Paid Count -->
        <div class="glass-panel rounded-3xl p-8 relative overflow-hidden group hover:translate-y-[-4px] transition-all duration-500 hover:shadow-2xl hover:shadow-gray-500/10">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-gray-500/5 rounded-full blur-3xl transition-colors"></div>
            <div class="relative z-10 text-right">
                <p class="text-gray-400 text-[10px] font-black uppercase tracking-widest mb-1">فواتير مدفوعة</p>
                <p class="text-3xl font-black text-gray-900 tracking-tighter">{{ number_format($stats['paid_count']) }}</p>
                <p class="mt-4 text-[11px] font-bold text-gray-400 uppercase tracking-tighter">Settled Records</p>
            </div>
        </div>

        <!-- Unpaid Count -->
        <div class="glass-panel rounded-3xl p-8 relative overflow-hidden group hover:translate-y-[-4px] transition-all duration-500 hover:shadow-2xl hover:shadow-indigo-500/10">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-indigo-500/10 rounded-full blur-3xl transition-colors"></div>
            <div class="relative z-10 text-right">
                <p class="text-gray-400 text-[10px] font-black uppercase tracking-widest mb-1">فواتير مستحقة</p>
                <p class="text-3xl font-black text-gray-900 tracking-tighter">{{ number_format($stats['unpaid_count']) }}</p>
                <p class="mt-4 text-[11px] font-bold text-indigo-600 uppercase tracking-tighter">Awaiting Payment</p>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="glass-panel rounded-3xl p-6 mb-12 relative overflow-hidden group">
        <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-indigo-500/5 rounded-full blur-3xl transition-all group-hover:bg-indigo-500/10"></div>
        <form action="{{ route('accounting.invoices.index') }}" method="GET" class="flex flex-col md:flex-row items-center gap-6 relative z-10 w-full">
            <div class="relative flex-1 w-full">
                <select name="status" class="w-full py-3.5 px-6 bg-white/50 backdrop-blur-md border border-white/20 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:bg-white focus:border-indigo-500/30 transition-all font-bold text-gray-700 appearance-none">
                    <option value="all">جميع الحالات - All Statuses</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>مدفوعة - Settled</option>
                    <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>غير مدفوعة - Unpaid</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center px-6 text-gray-400">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                </div>
            </div>
            <button type="submit" class="w-full md:w-auto px-12 py-3.5 bg-gray-900 hover:bg-black text-white font-black rounded-2xl transition-all shadow-xl hover:shadow-gray-900/20 active:scale-95">
                تصفية النتائج
            </button>
        </form>
    </div>

    <!-- Invoices Table -->
    <div class="glass-panel rounded-[2rem] overflow-hidden transition-all duration-500 hover:shadow-2xl hover:shadow-indigo-500/5">
        <div class="overflow-x-auto">
            <table class="w-full text-right">
                <thead>
                    <tr class="bg-white/30 backdrop-blur-lg border-b border-white/20">
                        <th class="px-8 py-5 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">رقم الفاتورة</th>
                        <th class="px-8 py-5 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">العميل</th>
                        <th class="px-8 py-5 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">تاريخ الاستحقاق</th>
                        <th class="px-8 py-5 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">الحالة</th>
                        <th class="px-8 py-5 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">المبلغ</th>
                        <th class="px-8 py-5 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/10">
                    @forelse($invoices as $invoice)
                    <tr class="hover:bg-white/40 transition-all duration-300 group">
                        <td class="px-8 py-6">
                            <span class="font-mono font-black text-xs text-gray-400 tracking-widest group-hover:text-indigo-600 transition-colors">#{{ $invoice->invoice_number }}</span>
                        </td>
                        <td class="px-8 py-6">
                            <div class="font-black text-gray-900 tracking-tight">{{ $invoice->client->name ?? 'عميل محذوف' }}</div>
                            @if(isset($invoice->client->username))
                                <div class="text-[9px] font-black text-gray-400 uppercase tracking-tighter mt-0.5">{{ $invoice->client->username }}</div>
                            @endif
                        </td>
                        <td class="px-8 py-6 text-xs font-black text-gray-600 tracking-tighter">
                            {{ $invoice->due_date->format('Y-m-d') }}
                        </td>
                        <td class="px-8 py-6">
                            @if($invoice->status == 'paid')
                                <span class="inline-flex items-center px-4 py-1.5 rounded-2xl text-[9px] font-black uppercase tracking-widest bg-green-500/5 text-green-600 border border-green-500/10">
                                    <span class="w-1.5 h-1.5 bg-green-600 rounded-full ml-1.5 animate-pulse shadow-[0_0_8px_rgba(34,197,94,0.5)]"></span>
                                    Settled
                                </span>
                            @else
                                <span class="inline-flex items-center px-4 py-1.5 rounded-2xl text-[9px] font-black uppercase tracking-widest bg-rose-500/5 text-rose-600 border border-rose-500/10">
                                    <span class="w-1.5 h-1.5 bg-rose-600 rounded-full ml-1.5 animate-pulse shadow-[0_0_8px_rgba(225,29,72,0.5)]"></span>
                                    Unpaid
                                </span>
                            @endif
                        </td>
                        <td class="px-8 py-6">
                            <span class="text-lg font-black text-gray-900 tracking-tighter">@money($invoice->amount)</span>
                        </td>
                        <td class="px-8 py-6 text-left">
                            <div class="flex items-center justify-end gap-3 translate-x-4 opacity-0 group-hover:opacity-100 group-hover:translate-x-0 transition-all duration-300">
                                <a href="{{ route('accounting.invoices.edit', $invoice) }}" class="w-10 h-10 rounded-xl bg-white/50 backdrop-blur-sm text-indigo-600 flex items-center justify-center border border-white/40 shadow-sm hover:bg-indigo-600 hover:text-white transition-all">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                </a>
                                <a href="#" class="w-10 h-10 rounded-xl bg-white/50 backdrop-blur-sm text-gray-500 flex items-center justify-center border border-white/40 shadow-sm hover:bg-gray-900 hover:text-white transition-all">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 00-2 2h2m2 4h10a2 2 0 002-2v-3a2 2 0 00-2-2H5a2 2 0 00-2 2v3a2 2 0 002 2zm0 0v-8h10v8" /></svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-8 py-20 text-center">
                            <div class="flex flex-col items-center gap-6">
                                <div class="w-20 h-20 bg-white/50 backdrop-blur-md rounded-[2rem] flex items-center justify-center border border-white/30 shadow-lg shadow-gray-200/50">
                                    <svg class="w-10 h-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-black text-gray-900">لا توجد سجلات مالية بعد</h3>
                                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-2 text-center">No financial invoice records found on file</p>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($invoices->hasPages())
        <div class="px-8 py-6 border-t border-white/10 bg-white/20 backdrop-blur-lg">
            {{ $invoices->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

```

## resources/views/accounting\reports\index.blade.php

```php
@extends('layouts.app')

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('financialCharts', () => ({
            init() {
                this.renderRevenueChart();
                this.renderSourceChart();
                this.renderExpensesChart();
            },
            
            renderRevenueChart() {
                const ctx = document.getElementById('revenueChart').getContext('2d');
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: @json($months),
                        datasets: [
                            {
                                label: 'الإيرادات',
                                data: @json($revenueData),
                                borderColor: '#10B981', // Emerald 500
                                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                                tension: 0.4,
                                fill: true
                            },
                            {
                                label: 'المصاريف',
                                data: @json($expensesData),
                                borderColor: '#EF4444', // Red 500
                                backgroundColor: 'rgba(239, 68, 68, 0.1)',
                                tension: 0.4,
                                fill: true
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { position: 'bottom' }
                        },
                        scales: {
                            y: { beginAtZero: true }
                        }
                    }
                });
            },

            renderSourceChart() {
                const ctx = document.getElementById('sourceChart').getContext('2d');
                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: ['هوت سبوت', 'برودباند'],
                        datasets: [{
                            data: [@json($revenuePieData['hotspot']), @json($revenuePieData['broadband'])],
                            backgroundColor: ['#8B5CF6', '#3B82F6'], // Purple, Blue
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { position: 'bottom' }
                        }
                    }
                });
            },

            renderExpensesChart() {
                const ctx = document.getElementById('expensesChart').getContext('2d');
                const breakdown = @json($expenseBreakdown);
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ['الكهرباء', 'الوقود', 'الصيانة', 'الآجار'],
                        datasets: [{
                            label: 'المصاريف',
                            data: [breakdown.electricity, breakdown.fuel, breakdown.maintenance, breakdown.rent],
                            backgroundColor: ['#F59E0B', '#EF4444', '#6366F1', '#10B981'],
                            borderRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { display: false }
                        },
                        scales: {
                            y: { beginAtZero: true }
                        }
                    }
                });
            }
        }));
    });
</script>
@endpush

@section('content')
<div class="space-y-6" x-data="financialCharts">
    <style>
        .gradient-text {
            background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .dark .gradient-text {
            background: linear-gradient(135deg, #818cf8 0%, #60a5fa 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
    <!-- Header -->
    <div class="flex justify-between items-start">
        <div>
            <h1 class="text-3xl font-bold gradient-text">التقارير المالية 📊</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1">نظرة شاملة على أداء الشبكة المالي باحترافية</p>
        </div>
        <div class="text-sm text-slate-500 bg-slate-100 px-3 py-1 rounded-full dark:bg-slate-700 dark:text-slate-300">
            السنة المالية: {{ date('Y') }}
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- Revenue -->
        <div class="bg-white shadow-sm border border-gray-100 p-5 rounded-2xl border-l-4 border-green-500">
            <div class="text-slate-500 dark:text-slate-400 text-sm mb-1">إيرادات السنة الحالية</div>
            <div class="text-2xl font-bold text-slate-800 dark:text-white">@money($thisYearRevenue)</div>
            <div class="text-xs text-green-600 mt-2 font-medium">
                +@money($thisMonthRevenue) هذا الشهر
            </div>
        </div>

        <!-- Profit -->
        <div class="bg-white shadow-sm border border-gray-100 p-5 rounded-2xl border-l-4 border-blue-500">
            <div class="text-slate-500 dark:text-slate-400 text-sm mb-1">صافي الربح (التقريبي)</div>
            <div class="text-2xl font-bold text-blue-600">@money($thisYearProfit)</div>
            <div class="text-xs text-slate-400 mt-2">الإيرادات - مصاريف الأبراج</div>
        </div>

        <!-- Expenses -->
        <div class="bg-white shadow-sm border border-gray-100 p-5 rounded-2xl border-l-4 border-red-500">
            <div class="text-slate-500 dark:text-slate-400 text-sm mb-1">إجمالي المصاريف</div>
            <div class="text-2xl font-bold text-red-600">@money($thisYearExpenses)</div>
            <div class="text-xs text-red-400 mt-2 font-medium">
                مصاريف تشغيلية للأبراج
            </div>
        </div>

        <!-- Debts -->
        <div class="bg-white shadow-sm border border-gray-100 p-5 rounded-2xl border-l-4 border-orange-500">
            <div class="text-slate-500 dark:text-slate-400 text-sm mb-1">الديون المستحقة</div>
            <div class="text-2xl font-bold text-orange-600">@money($totalDebts)</div>
            <div class="text-xs text-orange-400 mt-2">فواتير لم يتم سدادها</div>
        </div>
    </div>

    <!-- Capital Assets Section -->
    <h2 class="text-lg font-bold text-gray-800 mt-8 mb-4 flex items-center gap-2">
        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
        الأصول الرأسمالية (Capital Assets)
    </h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Structure Costs -->
        <div class="bg-white shadow-sm border border-gray-100 p-5 rounded-2xl border-l-4 border-indigo-500 relative overflow-hidden group">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-indigo-500/10 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
            <div class="relative">
                <div class="text-slate-500 dark:text-slate-400 text-sm mb-1 font-medium">قيمة البنية التحتية (الهيكل)</div>
                <div class="text-3xl font-bold text-indigo-700 mt-1">@money($totalStructureCost)</div>
                <div class="text-xs text-indigo-500 mt-2 font-medium flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    استثمار ثابت في الأبراج
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row 1 -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Revenue Chart -->
        <div class="lg:col-span-2 bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <h3 class="text-xl font-bold gradient-text mb-4">📈 نمو الإيرادات والمصاريف (شهرياً)</h3>
            <canvas id="revenueChart" height="250" class="w-full"></canvas>
        </div>

        <!-- Source Breakdown -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <h3 class="text-xl font-bold gradient-text mb-4">📊 توزيع الإيرادات (المصدر)</h3>
            <div class="h-64 flex items-center justify-center">
                <canvas id="sourceChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Charts Row 2 -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Expenses Breakdown -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <h3 class="text-xl font-bold gradient-text mb-4">💸 تحليل المصاريف</h3>
            <canvas id="expensesChart" height="200"></canvas>
        </div>

        <!-- Top Towers Table -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 overflow-hidden">
            <h3 class="text-xl font-bold gradient-text mb-4">🏆 أعلى الأبراج دخلاً</h3>
            <table class="w-full text-sm text-right">
                <thead class="bg-slate-50 dark:bg-slate-700/50 text-slate-500">
                    <tr>
                        <th class="px-4 py-3 rounded-r-lg">البرج</th>
                        <th class="px-4 py-3 rounded-l-lg">الإجمالي</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($topTowers as $tower)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition">
                        <td class="px-4 py-3 font-medium text-slate-700 dark:text-slate-200">{{ $tower->name }}</td>
                        <td class="px-4 py-3 font-bold text-green-600">@money($tower->total)</td>
                    </tr>
                    @empty
                    <tr><td colspan="2" class="px-4 py-4 text-center text-slate-400">لا توجد بيانات كافية</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

```

