@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div>
        <a href="{{ route('router-management.index') }}" class="text-blue-600 hover:text-blue-800 font-semibold text-sm mb-4 inline-block">
            ← العودة للقائمة
        </a>
        <h2 class="text-2xl font-bold text-gray-900">سكريبت الربط التلقائي</h2>
        <p class="text-gray-500 mt-1">{{ $router->name }}</p>
    </div>

    <!-- Status -->
    <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <h3 class="text-xl font-bold">السكريبت جاهز!</h3>
                <p class="text-green-100 text-sm mt-1">انسخ والصق في Terminal الخاص بـ MikroTik</p>
            </div>
        </div>
    </div>

    <!-- Script Box -->
    <div class="bg-gray-900 rounded-xl shadow-xl overflow-hidden">
        <div class="bg-gray-800 px-6 py-3 border-b border-gray-700 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                <span class="mr-4 text-gray-400 text-sm font-mono">mikrotik-script.rsc</span>
            </div>
            <button onclick="copyScript()" class="px-4 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded transition">
                📋 نسخ الكود
            </button>
        </div>
        <div class="p-6 overflow-x-auto">
            <pre id="script-content" class="text-sm text-green-400 font-mono leading-relaxed">{{ $script }}</pre>
        </div>
    </div>

    <!-- Instructions -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">📝 خطوات التنفيذ</h3>
            <ol class="space-y-3 text-sm text-gray-700">
                <li class="flex gap-3">
                    <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center font-bold text-xs">1</span>
                    <span>اضغط على "نسخ الكود" أعلاه</span>
                </li>
                <li class="flex gap-3">
                    <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center font-bold text-xs">2</span>
                    <span>افتح WinBox أو SSH Terminal على جهاز MikroTik</span>
                </li>
                <li class="flex gap-3">
                    <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center font-bold text-xs">3</span>
                    <span>الصق السكريبت كاملاً واضغط Enter</span>
                </li>
                <li class="flex gap-3">
                    <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center font-bold text-xs">4</span>
                    <span>انتظر بضع ثواني حتى ينتهي التنفيذ</span>
                </li>
                <li class="flex gap-3">
                    <span class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center font-bold text-xs">✓</span>
                    <span class="font-semibold text-green-600">تم! سيبدأ الجهاز بالمزامنة كل 15 ثانية</span>
                </li>
            </ol>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">⚙️ ما الذي سيحدث؟</h3>
            <ul class="space-y-2 text-sm text-gray-700">
                <li class="flex items-start gap-2">
                    <svg class="w-5 h-5 text-green-600 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>إنشاء WireGuard VPN آمن</span>
                </li>
                <li class="flex items-start gap-2">
                    <svg class="w-5 h-5 text-green-600 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>تفعيل MikroTik API</span>
                </li>
                <li class="flex items-start gap-2">
                    <svg class="w-5 h-5 text-green-600 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>إنشاء مستخدم API خاص</span>
                </li>
                <li class="flex items-start gap-2">
                    <svg class="w-5 h-5 text-green-600 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>مزامنة تلقائية كل 15 ثانية</span>
                </li>
                <li class="flex items-start gap-2">
                    <svg class="w-5 h-5 text-green-600 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>تحديث Users/Packages تلقائياً</span>
                </li>
            </ul>
        </div>
    </div>

    <!-- Download Option -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="font-bold text-gray-900">تنزيل كملف</h3>
                <p class="text-sm text-gray-500 mt-1">يمكنك تنزيل السكريبت ورفعه عبر FTP</p>
            </div>
            <button onclick="downloadScript()" class="px-6 py-2.5 bg-gray-800 hover:bg-gray-900 text-white font-semibold rounded-lg transition">
                💾 تنزيل .rsc
            </button>
        </div>
    </div>
</div>

<script>
function copyScript() {
    const scriptContent = document.getElementById('script-content').textContent;
    navigator.clipboard.writeText(scriptContent).then(() => {
        alert('✅ تم نسخ السكريبت!');
    });
}

function downloadScript() {
    const scriptContent = document.getElementById('script-content').textContent;
    const blob = new Blob([scriptContent], { type: 'text/plain' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'mikrotik-auto-sync-{{ $router->id }}.rsc';
    document.body.appendChild(a);
    a.click();
    window.URL.revokeObjectURL(url);
    document.body.removeChild(a);
}
</script>
@endsection
