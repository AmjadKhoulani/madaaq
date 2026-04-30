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
