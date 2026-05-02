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
