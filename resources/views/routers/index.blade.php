@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">أجهزة الشبكة</h2>
            <p class="text-gray-500 mt-1">إدارة أجهزة الراوتر ونقاط الوصول</p>
        </div>
        <a href="{{ route('routers.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold rounded-xl shadow-lg transition transform hover:scale-105">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            إضافة جهاز جديد
        </a>
    </div>

    <!-- Success Message -->
    @if(session('success'))
    <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg shadow-sm">
        <div class="flex items-center gap-3">
            <svg class="w-6 h-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <p class="text-green-800 font-bold">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    <!-- Table -->
    <div class="glass shadow-lg rounded-2xl overflow-hidden border border-gray-100">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50/50">
                    <tr>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">الاسم</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">عنوان IP</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">نوع الجهاز</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">الموقع</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="bg-white/60 divide-y divide-gray-200 backdrop-blur-sm">
                    @forelse($routers as $router)
                    <tr class="hover:bg-indigo-50/50 transition duration-200">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-md">
                                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/></svg>
                                </div>
                                <div>
                                    <div class="font-bold text-gray-900">{{ $router->name }}</div>
                                    @if($router->deviceModel)
                                        <div class="text-xs text-gray-500">{{ $router->deviceModel->manufacturer }} {{ $router->deviceModel->model_name }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-mono font-medium text-gray-700 bg-gray-100 px-2 py-1 rounded-md">{{ $router->ip }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($router->device_type === 'router')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-100 text-blue-800 border border-blue-200">راوتر</span>
                            @elseif($router->device_type === 'access_point')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-purple-100 text-purple-800 border border-purple-200">نقطة وصول</span>
                            @elseif($router->device_type === 'server')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-gray-100 text-gray-800 border border-gray-200">سيرفر</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-pink-100 text-pink-800 border border-pink-200">محطة بث</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            @if($router->lat && $router->lng)
                                <span class="inline-flex items-center gap-1 text-emerald-600 font-medium">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    محدد
                                </span>
                            @else
                                <span class="text-gray-400 text-xs italic">غير محدد</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <div class="flex items-center gap-2">
                                @php
                                    $isMikroTik = $router->deviceModel && (stripos($router->deviceModel->manufacturer, 'MikroTik') !== false);
                                @endphp
                                
                                @if($isMikroTik || !$router->deviceModel)
                                    <a href="{{ route('routers.webfig', $router->id) }}" target="_blank" class="text-emerald-500 hover:text-emerald-700 font-bold hover:underline transition flex items-center gap-1" title="وصول آمن (يفتح عن بعد عبر الوكيل)">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                        WebFig (Secure)
                                    </a>
                                @endif

                                @if(isset($router->is_server_model) && $router->is_server_model)
                                    <a href="{{ route('servers.show', $router->id) }}" class="text-blue-600 hover:text-blue-900 font-bold hover:underline transition">
                                        إدارة
                                    </a>
                                    <form action="{{ route('servers.destroy', $router->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا السيرفر؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 font-bold hover:underline transition">حذف</button>
                                    </form>
                                @else
                                    <a href="{{ route('routers.edit', $router->id) }}" class="text-yellow-600 hover:text-yellow-900 font-bold hover:underline transition">
                                        تعديل
                                    </a>
                                    <button onclick="showScript({{ $router->id }})" class="text-indigo-600 hover:text-indigo-900 font-bold hover:underline transition">
                                        سكريبت
                                    </button>
                                    <form action="{{ route('routers.destroy', $router->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا الجهاز؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 font-bold hover:underline transition">حذف</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center border-2 border-dashed border-gray-200">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/></svg>
                                </div>
                                <p class="text-gray-500 font-medium">لا توجد أجهزة مضافة بعد</p>
                                <a href="{{ route('routers.create') }}" class="text-indigo-600 hover:text-indigo-700 font-bold text-sm">أضف جهازك الأول</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Script Modal -->
<div id="scriptModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" onclick="hideScript()">
    <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <h3 class="text-xl font-bold text-gray-900">سكريبت MikroTik</h3>
            <button onclick="hideScript()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="p-6">
            <pre id="scriptContent" class="bg-gray-900 text-green-400 p-4 rounded-lg overflow-x-auto text-xs font-mono" dir="ltr"></pre>
            <button onclick="copyScript()" class="mt-4 w-full px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition">
                نسخ السكريبت
            </button>
        </div>
    </div>
</div>

<script>
function showScript(routerId) {
    fetch(`/routers/${routerId}/script`)
        .then(res => res.text())
        .then(script => {
            document.getElementById('scriptContent').textContent = script;
            document.getElementById('scriptModal').classList.remove('hidden');
        });
}

function hideScript() {
    document.getElementById('scriptModal').classList.add('hidden');
}

function copyScript() {
    const script = document.getElementById('scriptContent').textContent;
    navigator.clipboard.writeText(script).then(() => {
        alert('تم نسخ السكريبت!');
    });
}
</script>
@endsection
