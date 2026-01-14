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
