@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">النقل من MikroTik</h2>
        <p class="text-gray-500 mt-1">استيراد البيانات من جهاز MikroTik الخاص بك</p>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
        {{ session('error') }}
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Import via API -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">الاتصال المباشر</h3>
                    <p class="text-xs text-gray-500">استيراد مباشر من جهاز MikroTik</p>
                </div>
            </div>

            <form action="{{ route('tools.migration.import-mikrotik') }}" method="POST" class="space-y-4">
                @csrf
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">عنوان IP الجهاز</label>
                    <input type="text" name="mikrotik_host" required placeholder="192.168.88.1" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">اسم المستخدم</label>
                        <input type="text" name="mikrotik_username" required placeholder="admin" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">كلمة المرور</label>
                        <input type="password" name="mikrotik_password" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">المنفذ (Port)</label>
                    <input type="number" name="mikrotik_port" value="8728" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">نوع الاستيراد</label>
                    <select name="import_type" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="pppoe">PPPoE فقط</option>
                        <option value="hotspot">Hotspot فقط</option>
                        <option value="both">الكل</option>
                    </select>
                </div>

                <button type="submit" class="w-full px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg shadow-sm transition">
                    بدء الاستيراد
                </button>
            </form>
        </div>

        <!-- Import from File -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">من ملف CSV</h3>
                    <p class="text-xs text-gray-500">رفع ملف يحتوي على البيانات</p>
                </div>
            </div>

            <form action="{{ route('tools.migration.import-file') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">نوع البيانات</label>
                    <select name="file_type" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                        <option value="pppoe">PPPoE</option>
                        <option value="hotspot">Hotspot</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">ملف CSV</label>
                    <input type="file" name="import_file" accept=".csv,.txt" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                    <p class="text-xs text-gray-500 mt-2">صيغة الملف: username,password,package,profile</p>
                </div>

                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <p class="text-sm font-semibold text-blue-900 mb-2">📋 مثال CSV:</p>
                    <pre class="text-xs text-blue-800 font-mono">username,password,package,profile
user1,pass123,Gold,profile1
user2,pass456,Silver,profile2</pre>
                </div>

                <button type="submit" class="w-full px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-lg shadow-sm transition">
                    رفع واستيراد
                </button>
            </form>
        </div>
    </div>

    <!-- Instructions -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">📖 تعليمات الاستخدام</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h4 class="font-semibold text-gray-900 mb-2">الاتصال المباشر:</h4>
                <ul class="space-y-2 text-sm text-gray-600">
                    <li class="flex items-start gap-2">
                        <span class="text-blue-600">•</span>
                        <span>تأكد من تفعيل API على جهاز MikroTik</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-blue-600">•</span>
                        <span>المنفذ الافتراضي للـ API هو 8728</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-blue-600">•</span>
                        <span>تحتاج صلاحيات admin للوصول</span>
                    </li>
                </ul>
            </div>

            <div>
                <h4 class="font-semibold text-gray-900 mb-2">استيراد من ملف:</h4>
                <ul class="space-y-2 text-sm text-gray-600">
                    <li class="flex items-start gap-2">
                        <span class="text-green-600">•</span>
                        <span>استخدم ملف CSV بصيغة UTF-8</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-green-600">•</span>
                        <span>السطر الأول يجب أن يكون رؤوس الأعمدة</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-green-600">•</span>
                        <span>يمكنك تصدير البيانات من MikroTik ثم رفعها</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
