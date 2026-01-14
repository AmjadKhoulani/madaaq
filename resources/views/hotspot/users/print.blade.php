<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طباعة كرت المشترك - {{ $user->username }}</title>
    <style>
        @media print {
            .no-print { display: none; }
            body { margin: 0; padding: 0; background: white; }
            .card { border: 1px solid #000; box-shadow: none !important; page-break-inside: avoid; }
        }
        body {
            font-family: 'Tahoma', 'Segoe UI', sans-serif;
            background: #f3f4f6;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .card {
            width: 300px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            text-align: center;
            border: 2px dashed #e5e7eb;
            margin-bottom: 20px;
        }
        .logo {
            font-size: 20px;
            font-weight: bold;
            color: #4f46e5;
            margin-bottom: 15px;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 10px;
        }
        .info-row {
            margin: 10px 0;
            display: flex;
            justify-content: space-between;
            font-size: 14px;
        }
        .label { color: #6b7280; font-weight: bold; }
        .value { color: #111827; font-weight: bold; font-family: monospace; font-size: 16px; }
        .price {
            background: #4f46e5;
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
            margin-top: 15px;
            display: inline-block;
        }
        .footer {
            margin-top: 15px;
            font-size: 10px;
            color: #9ca3af;
            border-top: 1px solid #e5e7eb;
            padding-top: 10px;
        }
        .btn {
            background: #4f46e5;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            text-decoration: none;
        }
        .btn:hover { background: #4338ca; }
    </style>
</head>
<body>

    <div class="no-print" style="margin-bottom: 20px;">
        <button onclick="window.print()" class="btn">🖨️ طباعة الكرت</button>
        <a href="{{ route('hotspot.users.index') }}" class="btn" style="background: #6b7280; margin-right: 10px;">رجوع</a>
    </div>

    <div class="card">
        <div class="logo">
            {{ config('app.name', 'ISP System') }} WiFi
        </div>
        
        <div class="info-row">
            <span class="label">اسم المستخدم:</span>
            <span class="value">{{ $user->username }}</span>
        </div>
        
        <div class="info-row">
            <span class="label">كلمة المرور:</span>
            <span class="value">{{ $user->password }}</span>
        </div>

        @if($user->package)
        <div class="info-row">
            <span class="label">الباقة:</span>
            <span class="value">{{ $user->package->name }}</span>
        </div>
        @endif

        @if($user->expires_at)
        <div class="info-row">
            <span class="label">الانتهاء:</span>
            <span class="value">{{ $user->expires_at->format('Y-m-d') }}</span>
        </div>
        @endif
        
        @if($user->package && $user->package->price > 0)
        <div class="price">
            السعر: {{ number_format($user->package->price) }}
        </div>
        @endif

        <div class="footer">
            خدمة الإنترنت السريع والموثوق
            <br>
            دعم فني: {{ auth()->user()->phone ?? '00000000' }}
        </div>
    </div>

    <script>
        // Auto print on load if desired, usually better to let user click
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>
