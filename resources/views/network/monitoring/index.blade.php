@extends('layouts.app')

@section('content')
<div class="space-y-6" x-data="{ 
    autoRefresh: true,
    showLinkModal: false,
    selectedDevice: null,
    openLinkModal(device) {
        this.selectedDevice = device;
        this.showLinkModal = true;
    }
}" x-init="
    if (autoRefresh) {
        setInterval(() => window.location.reload(), 30000); // Refresh every 30s
    }
">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">مراقبة الشبكة</h2>
            <p class="text-gray-500 mt-1">حالة الأجهزة والتنبيهات في الوقت الفعلي</p>
        </div>
        <div class="flex items-center gap-3">
            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" x-model="autoRefresh" class="rounded text-purple-600">
                <span>تحديث تلقائي</span>
            </label>
            <a href="{{ route('network.discovery.index') }}" class="px-4 py-2 bg-blue-100 hover:bg-blue-200 text-blue-700 font-bold rounded-lg transition flex items-center gap-2">
                📡 استكشاف الأجهزة
            </a>
            <button onclick="window.location.reload()" class="px-4 py-2 bg-gray-900 hover:bg-gray-800 text-white font-bold rounded-lg shadow-sm transition">
                🔄 تحديث
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/></svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">إجمالي الأجهزة</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_devices'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-green-50 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">متصل</p>
                    <p class="text-2xl font-bold text-green-600">{{ $stats['online'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-red-50 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">غير متصل</p>
                    <p class="text-2xl font-bold text-red-600">{{ $stats['offline'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-orange-50 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">تنبيهات نشطة</p>
                    <p class="text-2xl font-bold text-orange-600">{{ $stats['active_alerts'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Devices Grid -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">حالة الأجهزة</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($devices as $device)
                <div class="p-4 rounded-lg border transition {{ 
                    $device['status'] === 'online' ? 'bg-green-50 border-green-200' : 
                    ($device['status'] === 'offline' ? 'bg-red-50 border-red-200' : 
                    ($device['status'] === 'warning' ? 'bg-orange-50 border-orange-200' : 'bg-gray-50 border-gray-200'))
                }}">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full {{ 
                                    $device['status'] === 'online' ? 'bg-green-500' : 
                                    ($device['status'] === 'offline' ? 'bg-red-500' : 'bg-orange-500')
                                }} {{ $device['status'] === 'online' ? 'animate-pulse' : '' }}"></span>
                                <h4 class="font-bold text-gray-900">{{ $device['name'] }}</h4>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">
                                {{ $device['type'] === 'router' ? '🌐 راوتر' : ($device['type'] === 'tower' ? '📡 برج' : '💻 سيرفر') }}
                                @if(isset($device['ip'])) • {{ $device['ip'] }}@endif
                            </p>
                        </div>
                        <span class="px-2 py-1 rounded-full text-xs font-bold {{ 
                            $device['status'] === 'online' ? 'bg-green-100 text-green-700' : 
                            ($device['status'] === 'offline' ? 'bg-red-100 text-red-700' : 'bg-orange-100 text-orange-700')
                        }}">
                            {{ $device['status'] === 'online' ? 'متصل' : ($device['status'] === 'offline' ? 'معطل' : 'بطيء') }}
                        </span>
                    </div>
                    <div class="mt-3 flex items-center justify-between text-xs text-gray-600">
                        <span>
                            @if($device['response_time'])
                                ⚡ {{ $device['response_time'] }}ms
                            @else
                                ⚡ --
                            @endif
                        </span>
                        
                        @if($device['status'] !== 'online')
                            <button @click="openLinkModal({{ json_encode($device) }})" class="text-blue-600 hover:text-blue-800 font-bold underline">
                                🔗 ربط الجهاز
                            </button>
                        @else
                            <span>{{ $device['last_check'] ? $device['last_check']->diffForHumans() : '--' }}</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Active Alerts -->
    @if($activeAlerts->count() > 0)
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-900">🚨 التنبيهات النشطة</h3>
            <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-sm font-bold">{{ $activeAlerts->count() }} تنبيه</span>
        </div>
        <div class="space-y-3">
            @foreach($activeAlerts as $alert)
                <div class="p-4 bg-red-50 border-l-4 border-red-500 rounded-lg flex items-center justify-between">
                    <div>
                        <p class="font-semibold text-gray-900">{{ $alert->message }}</p>
                        <p class="text-sm text-gray-600 mt-1">{{ $alert->created_at->diffForHumans() }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        @if($alert->device)
                            <button type="button" @click="openLinkModal({{ json_encode($alert->device) }})" class="px-3 py-2 bg-blue-100 hover:bg-blue-200 text-blue-700 text-sm font-bold rounded-lg transition">
                                🛠️ فحص وإصلاح
                            </button>
                        @endif
                        
                        <form action="{{ route('network.monitoring.alert.resolve', $alert) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="px-3 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-bold rounded-lg transition" onclick="return confirm('هل أنت متأكد من أن المشكلة تم حلها فعلياً؟ هذا الزر يزيل التنبيه فقط.')">
                                ✓ تمت المعالجة
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Alert History -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">📜 سجل التنبيهات (آخر 24 ساعة)</h3>
        <div class="space-y-2">
            @forelse($recentAlerts as $alert)
                <div class="flex items-center justify-between p-3 rounded-lg {{ $alert->is_resolved ? 'bg-gray-50' : 'bg-orange-50' }}">
                    <div class="flex items-center gap-3">
                        @if($alert->is_resolved)
                            <svg class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        @else
                            <svg class="w-5 h-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        @endif
                        <div>
                            <p class="text-sm font-semibold text-gray-900">{{ $alert->message }}</p>
                            <p class="text-xs text-gray-500">
                                {{ $alert->created_at->format('Y-m-d H:i') }}
                                @if($alert->is_resolved)
                                    • تم الحل {{ $alert->resolved_at->diffForHumans() }}
                                @endif
                            </p>
                        </div>
                    </div>
                    <span class="px-2 py-1 rounded-full text-xs font-bold {{ 
                        $alert->severity === 'critical' ? 'bg-red-200 text-red-800' : 
                        ($alert->severity === 'warning' ? 'bg-orange-200 text-orange-800' : 'bg-blue-200 text-blue-800')
                    }}">
                        {{ $alert->severity }}
                    </span>
                </div>
            @empty
                <p class="text-center text-gray-500 py-4">لا توجد تنبيهات في آخر 24 ساعة</p>
            @endforelse
        </div>
    </div>

    <!-- Link Device Modal -->
    <div x-show="showLinkModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showLinkModal" class="fixed inset-0 transition-opacity" @click="showLinkModal = false">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>

            <div x-show="showLinkModal" class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:mr-4 sm:text-right w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                طريقة ربط الجهاز: <span x-text="selectedDevice?.name"></span>
                            </h3>
                            <div class="mt-4 text-sm text-gray-600 space-y-4 text-right">
                                <p class="font-bold text-gray-800">لضمان اتصال الجهاز بالنظام، تأكد من الخطوات التالية:</p>
                                
                                <ul class="list-disc list-inside space-y-2 pr-2">
                                    <li>
                                        <span class="font-semibold text-gray-700">1. عنوان IP صحيح:</span>
                                        تأكد من أن الـ IP المسجل في النظام (<span x-text="selectedDevice?.ip || 'غير محدد'" class="font-mono bg-gray-100 px-1"></span>) مطابق لعنوان الجهاز الفعلي ويمكن الوصول إليه (Pingable).
                                    </li>
                                    <li>
                                        <span class="font-semibold text-gray-700">2. خدمة API مفعلة:</span>
                                        تأكد من تفعيل خدمة API في المايكروتك عبر الذهاب إلى <code class="dir-ltr text-xs">IP -> Services</code> وتفعيل المنفذ 8728.
                                    </li>
                                    <li>
                                        <span class="font-semibold text-gray-700">3. اسم مستخدم وكلمة مرور:</span>
                                        تأكد من أن بيانات الدخول في النظام تطابق بيانات الجهاز ولها صلاحيات كاملة (Full Access).
                                    </li>
                                </ul>

                                <div class="bg-yellow-50 p-3 rounded-lg border border-yellow-200 mt-4 text-xs text-yellow-800">
                                    💡 <strong>ملاحظة:</strong> إذا كان الجهاز خلف NAT أو شبكة معزولة، قد تحتاج لإعداد VPN أو التأكد من إعدادات التوجيه (Routing).
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse justify-between items-center">
                    <div class="flex flex-row-reverse gap-2">
                        <template x-if="selectedDevice?.type === 'router'">
                            <a :href="'/routers/' + selectedDevice?.id + '/edit'" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                                تعديل الإعدادات
                            </a>
                        </template>
                        <template x-if="selectedDevice?.type === 'server'">
                            <a :href="'/servers/' + selectedDevice?.id" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                                إعدادات السيرفر
                            </a>
                        </template>
                    </div>
                    <button type="button" @click="showLinkModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        إغلاق
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
