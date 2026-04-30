<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إنشاء حساب جديد - مذاق (Madaaq)</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Cairo', sans-serif; }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-2xl shadow-xl">
        <div class="text-center">
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                إنشاء حساب مزود خدمة
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                انضم إلينا وابدأ في إدارة شبكتك اليوم
            </p>
        </div>
        
        @if(session('error'))
            <div class="bg-red-50 text-red-700 p-4 rounded-lg mb-6 border border-red-100">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 text-red-700 p-4 rounded-lg mb-6 border border-red-100 list-disc list-inside">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form class="mt-8 space-y-6" action="{{ route('register') }}" method="POST">
            @csrf
            
            <div class="space-y-4">
                <div>
                    <label for="company_name" class="block text-sm font-medium text-gray-700 mb-1">اسم الشركة / الشبكة</label>
                    <input id="company_name" name="company_name" type="text" required class="appearance-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="مثال: شبكة الأفق">
                </div>

                <div>
                    <label for="domain" class="block text-sm font-medium text-gray-700 mb-1">معرف النطاق (Subdomain)</label>
                    <div class="ltr flex items-center" dir="ltr">
                        <span class="p-3 bg-gray-100 border border-r-0 border-gray-300 rounded-l-lg text-gray-500 text-sm">.madaaq.com</span>
                        <input id="domain" name="domain" type="text" required class="appearance-none block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-r-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-left" placeholder="domain">
                    </div>
                </div>

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">الاسم الكامل</label>
                    <input id="name" name="name" type="text" required class="appearance-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="محمد أحمد">
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">البريد الإلكتروني</label>
                    <input id="email" name="email" type="email" autocomplete="email" required class="appearance-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="name@example.com">
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1 text-right">رقم الهاتف</label>
                    <div class="relative flex" dir="ltr">
                        <select name="country_code" class="appearance-none border border-gray-300 border-r-0 rounded-l-lg px-3 py-3 bg-gray-50 text-gray-700 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm w-32">
                            <option value="+963" selected>🇸🇾 +963</option>
                            <option value="+966">🇸🇦 +966</option>
                            <option value="+971">🇦🇪 +971</option>
                            <option value="+20">🇪🇬 +20</option>
                            <option value="+90">🇹🇷 +90</option>
                        </select>
                        <input id="phone" name="phone" type="tel" required class="appearance-none relative block w-full px-3 py-3 border border-gray-300 rounded-r-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="5xxxxxxxx">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">كلمة المرور</label>
                    <input id="password" name="password" type="password" autocomplete="new-password" required class="appearance-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm">
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">تأكيد كلمة المرور</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required class="appearance-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm">
                </div>

                <div class="flex items-center">
                    <input id="terms" name="terms" type="checkbox" required class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="terms" class="ml-2 block text-sm text-gray-900 mr-2">
                        أوافق على <a href="{{ route('terms') }}" target="_blank" class="text-indigo-600 hover:text-indigo-500 underline">الشروط والأحكام</a> و <a href="{{ route('privacy') }}" target="_blank" class="text-indigo-600 hover:text-indigo-500 underline">سياسة الخصوصية</a>
                    </label>
                </div>
            </div>

            <div>
                <button type="submit" class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-md transition-all transform hover:scale-[1.01]">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-indigo-500 group-hover:text-indigo-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </span>
                    إنشاء الحساب
                </button>
            </div>
            
            <div class="text-center mt-4">
                <p class="text-sm text-gray-600">
                    لديك حساب بالفعل؟
                    <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
                        تسجيل الدخول
                    </a>
                </p>
                <p class="mt-2 text-sm text-gray-500">
                    <a href="/" class="hover:underline">العودة للرئيسية</a>
                </p>
            </div>
        </form>
    </div>
</body>
</html>
