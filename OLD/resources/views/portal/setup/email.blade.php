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
