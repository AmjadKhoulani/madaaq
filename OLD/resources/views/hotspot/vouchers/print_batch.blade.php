<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طباعة الكروت - مجموعة</title>
    <link href="https://fonts.googleapis.com/css2?family=Readex+Pro:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        @media print {
            .no-print { display: none; }
            body { margin: 0; padding: 0; background: white; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .page-break { page-break-after: always; }
            .card { break-inside: avoid; page-break-inside: avoid; }
        }
        * {
            font-family: 'Readex Pro', 'Tahoma', sans-serif;
        }
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e8eaf6 100%);
            padding: 20px;
            margin: 0;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 16px;
            padding: 3px;
            position: relative;
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
            overflow: hidden;
            transition: transform 0.2s;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
        }
        .card-inner {
            background: white;
            border-radius: 14px;
            padding: 20px;
            text-align: center;
            position: relative;
        }
        .card-header {
            font-weight: 700;
            font-size: 16px;
            background: linear-gradient(90deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            padding-bottom: 10px;
            margin-bottom: 15px;
            border-bottom: 2px solid #e8eaf6;
            letter-spacing: 0.5px;
        }
        .wifi-icon {
            width: 40px;
            height: 40px;
            margin: 0 auto 10px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
        }
        .credentials {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            margin: 15px 0;
        }
        .credential-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 8px 0;
            font-size: 13px;
        }
        .credential-label {
            color: #6b7280;
            font-weight: 600;
            font-size: 11px;
            text-transform: uppercase;
        }
        .credential-value {
            font-family: 'Courier New', monospace;
            font-size: 15px;
            font-weight: bold;
            color: #111827;
            background: white;
            padding: 5px 10px;
            border-radius: 6px;
            border: 1px dashed #667eea;
        }
        .card-footer {
            font-size: 12px;
            color: white;
            background: linear-gradient(90deg, #667eea, #764ba2);
            padding: 8px;
            border-radius: 8px;
            margin-top: 10px;
            font-weight: 600;
        }
        .price-badge {
            display: inline-block;
            background: rgba(255, 255, 255, 0.2);
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 11px;
            margin-right: 5px;
        }
        .header-actions {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        .header-actions h1 {
            margin: 0;
            background: linear-gradient(90deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-size: 28px;
        }
        .header-actions p {
            margin: 5px 0 0 0;
            color: #6b7280;
            font-size: 14px;
        }
        .btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 700;
            text-decoration: none;
            display: inline-block;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
            transition: all 0.3s;
            font-size: 14px;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.5);
        }
        .btn-secondary {
            background: linear-gradient(135deg, #374151 0%, #1f2937 100%);
            margin-right: 10px;
        }
        .decorative-corner {
            position: absolute;
            top: 0;
            right: 0;
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, rgba(255,255,255,0.3) 0%, transparent 100%);
            border-radius: 0 14px 0 100%;
        }
        .decorative-dots {
            position: absolute;
            bottom: 10px;
            left: 10px;
            display: flex;
            gap: 3px;
        }
        .dot {
            width: 4px;
            height: 4px;
            background: #e8eaf6;
            border-radius: 50%;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="header-actions no-print">
            <div>
                <h1>تم توليد {{ count($users) }} كرت بنجاح! 🎉</h1>
                <p>جاهزة للطباعة والبيع - تصميم احترافي</p>
            </div>
            <div>
                <button onclick="window.print()" class="btn">🖨️ طباعة الكل</button>
                <a href="{{ route('hotspot.users.index') }}" class="btn btn-secondary">إنهاء</a>
            </div>
        </div>

        <div class="grid">
            @foreach($users as $user)
            <div class="card">
                <div class="card-inner">
                    <div class="decorative-corner"></div>
                    
                    <div class="wifi-icon">
                        📶
                    </div>
                    
                    <div class="card-header">
                        {{ config('app.name', 'SmartISP') }} WiFi
                    </div>
                    
                    <div class="credentials">
                        <div class="credential-row">
                            <span class="credential-label">اسم المستخدم</span>
                            <span class="credential-value">{{ $user->username }}</span>
                        </div>
                        <div class="credential-row">
                            <span class="credential-label">كلمة المرور</span>
                            <span class="credential-value">{{ $user->password }}</span>
                        </div>
                    </div>
                    
                    <div class="card-footer">
                        {{ $user->package->name ?? 'باقة إنترنت' }}
                        @if($user->package && $user->package->price)
                            <span class="price-badge">{{ number_format($user->package->price) }} ل.س</span>
                        @endif
                    </div>
                    
                    <div class="decorative-dots">
                        <div class="dot"></div>
                        <div class="dot"></div>
                        <div class="dot"></div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

</body>
</html>
