<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تأكيد البريد الإلكتروني - مذاق (Madaaq)</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Cairo', sans-serif; }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-2xl shadow-xl text-center">
        <div>
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                تأكيد البريد الإلكتروني
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                شكراً لتسجيلك! يرجى التحقق من بريدك الإلكتروني والنقر على الرابط المرسل إليك لتفعيل حسابك.
            </p>
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="bg-green-50 text-green-700 p-4 rounded-lg mb-6 border border-green-100 text-sm">
                تم إرسال رابط تحقق جديد إلى بريدك الإلكتروني.
            </div>
        @endif

        <div class="mt-8 space-y-4">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-md">
                    إعادة إرسال بريد التحقق
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-sm font-medium text-gray-500 hover:text-gray-700 underline">
                    تسجيل الخروج
                </button>
            </form>
        </div>
    </div>
</body>
</html>
