@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto pb-12" x-data="serverDashboard()">
    <!-- Premium Header -->
    <div class="bg-gradient-to-br from-white to-slate-50 rounded-3xl shadow-md border border-slate-200 border-b-4 border-b-indigo-500 p-8 mb-8 relative overflow-hidden">
        <!-- Background Gradient Decor -->
        <div class="absolute top-0 right-0 w-64 h-64 bg-blue-50 rounded-full -mr-32 -mt-32 opacity-50 blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-indigo-50 rounded-full -ml-32 -mb-32 opacity-50 blur-3xl"></div>

        <div class="relative flex flex-col md:flex-row items-center gap-8">
            <!-- Device Image & Status -->
            <div class="relative group">
                <div class="w-48 h-48 bg-gray-50 rounded-3xl border border-gray-100 p-4 flex items-center justify-center shadow-inner group-hover:shadow-md transition-all duration-500">
                    <img src="{{ $server->deviceModel->image_url ?? 'https://placehold.co/200x150?text=MikroTik' }}" 
                         alt="{{ $server->name }}" 
                         class="max-w-full max-h-full object-contain transition-transform duration-500 group-hover:scale-105">
                </div>
                <div class="absolute -bottom-2 -right-2 transform transition-transform group-hover:scale-110">
                    {!! $server->status_badge !!}
                </div>
            </div>

            <!-- Server Title & Quick Info -->
            <div class="flex-1 text-center md:text-right">
                <div class="flex flex-col md:flex-row md:items-center gap-3 mb-2 justify-center md:justify-start">
                    <h1 class="text-3xl font-black text-gray-900 tracking-tight">{{ $server->name }}</h1>
                    <span class="px-3 py-1 bg-gray-100 text-gray-500 rounded-full text-xs font-bold uppercase tracking-widest border border-gray-200">
                        {{ $server->deviceModel->model_name ?? 'MadaaQ Server' }}
                    </span>
                </div>
                
                <div class="flex flex-wrap justify-center md:justify-start gap-6 text-sm">
                    <div class="flex items-center gap-2 text-gray-600 bg-white/50 px-3 py-1.5 rounded-xl border border-gray-50 shadow-sm">
                        <span class="text-lg opacity-70">🌐</span>
                        <span class="font-mono font-bold">{{ $server->ip }}</span>
                    </div>
                    <div class="flex items-center gap-2 text-gray-600 bg-white/50 px-3 py-1.5 rounded-xl border border-gray-50 shadow-sm">
                        <span class="text-lg opacity-70">🔌</span>
                        <span class="font-mono font-bold">{{ $server->api_port }}</span>
                    </div>
                    <div class="flex items-center gap-2 text-gray-600 bg-white/50 px-3 py-1.5 rounded-xl border border-gray-50 shadow-sm">
                        <span class="text-lg opacity-70">📍</span>
                        <span class="font-bold">{{ $server->location ?? 'الموقع غير محدد' }}</span>
                    </div>
                </div>
            </div>

            <!-- Edit Action -->
            <div class="shrink-0">
                <a href="{{ route('servers.edit', $server) }}" class="flex items-center gap-2 px-6 py-3 bg-white border border-gray-200 text-gray-700 rounded-2xl font-bold hover:bg-gray-50 hover:shadow-md transition-all">
                    <span>⚙️</span>
                    <span>تعديل الإعدادات</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Live Performance Dashboard -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-8" x-show="isConnected">
        <!-- CPU Card -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 border-r-4 border-r-blue-500 transition-all hover:shadow-md">
            <div class="flex items-center justify-between mb-4">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">تحميل المعالج</p>
                <span class="text-blue-500 p-2 bg-blue-50 rounded-xl">📟</span>
            </div>
            <div class="flex items-end gap-2">
                <h3 class="text-3xl font-black text-gray-900" id="cpu-val">0%</h3>
            </div>
            <div class="w-full bg-gray-100 h-1.5 rounded-full mt-4 overflow-hidden">
                <div id="cpu-bar" class="bg-blue-500 h-full transition-all duration-1000" style="width: 0%"></div>
            </div>
        </div>

        <!-- RAM Card -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 border-r-4 border-r-indigo-500 transition-all hover:shadow-md">
            <div class="flex items-center justify-between mb-4">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">الذاكرة المتاحة</p>
                <span class="text-indigo-500 p-2 bg-indigo-50 rounded-xl">🧠</span>
            </div>
            <div class="flex flex-col">
                <h3 class="text-xl font-black text-gray-900" id="mem-val">-- / --</h3>
                <p class="text-[10px] text-gray-400 font-bold mt-1">ميغابايت</p>
            </div>
        </div>

        <!-- PPPoE Card -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 border-r-4 border-r-green-500 transition-all hover:shadow-md">
            <div class="flex items-center justify-between mb-4">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">نشط PPPoE</p>
                <span class="text-green-500 p-2 bg-green-50 rounded-xl">📡</span>
            </div>
            <h3 class="text-3xl font-black text-gray-900" id="active-pppoe-val">0</h3>
            <p class="text-[10px] text-green-600 font-bold mt-1">متصل الآن</p>
        </div>

        <!-- Hotspot Card -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 border-r-4 border-r-orange-500 transition-all hover:shadow-md">
            <div class="flex items-center justify-between mb-4">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">نشط Hotspot</p>
                <span class="text-orange-500 p-2 bg-orange-50 rounded-xl">📶</span>
            </div>
            <h3 class="text-3xl font-black text-gray-900" id="active-hotspot-val">0</h3>
            <p class="text-[10px] text-orange-600 font-bold mt-1">متصل الآن</p>
        </div>
    </div>

    <!-- Main Content Tabs -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden min-h-[500px]">
        <!-- Tabs Navigation -->
        <div class="flex border-b border-gray-200 bg-slate-100 p-2 gap-2">
            <template x-for="tab in tabs" :key="tab.id">
                <button @click="activeTab = tab.id" 
                        class="px-6 py-3 rounded-2xl font-bold text-sm transition-all flex items-center gap-2"
                        :class="activeTab === tab.id ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-600 hover:text-indigo-700 hover:bg-white border border-transparent'">
                    <span x-text="tab.icon"></span>
                    <span x-text="tab.name"></span>
                </button>
            </template>
        </div>

        <!-- Tab Content Wrapper -->
        <div class="p-8">
            <!-- General Tab -->
            <div x-show="activeTab === 'general'" x-transition:enter="duration-500 ease-out" class="space-y-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Info Table -->
                    <div class="space-y-6">
                        <div class="bg-blue-50/50 p-4 rounded-2xl border border-blue-100 flex items-center gap-3">
                            <span class="p-2 bg-blue-100 text-blue-600 rounded-xl">🖥️</span>
                            <h4 class="text-lg font-black text-gray-900">تفاصيل النظام</h4>
                        </div>
                        <div class="grid grid-cols-2 gap-6 bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                            <div class="space-y-1">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">الإصدار (RouterOS)</p>
                                <p class="font-bold text-gray-900 font-mono" id="version-val">--</p>
                            </div>
                            <div class="space-y-1">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">مدة التشغيل (Uptime)</p>
                                <p class="font-bold text-gray-900 font-mono" id="uptime-val">--</p>
                            </div>
                            <div class="space-y-1">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">اسم المستخدم</p>
                                <p class="font-bold text-gray-900">{{ $server->username }}</p>
                            </div>
                            <div class="space-y-1">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">آخر مزامنة</p>
                                <p class="font-bold text-gray-900 text-xs">
                                    {{ $server->last_sync_at ? $server->last_sync_at->diffForHumans() : 'لم تتم بعد' }}
                                </p>
                            </div>
                        </div>
                        
                        <!-- Top DNS Sites -->
                        <div id="top-sites-card-new" class="hidden">
                            <div class="bg-indigo-50/50 p-4 rounded-2xl border border-indigo-100 flex items-center gap-3 mb-4">
                                <span class="p-2 bg-indigo-100 text-indigo-600 rounded-xl">🌐</span>
                                <h4 class="text-lg font-black text-gray-900">المواقع الأكثر طلباً (DNS)</h4>
                            </div>
                            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm space-y-4" id="top-sites-list-new">
                                <!-- JS content -->
                            </div>
                        </div>
                    </div>

                    <!-- Map Location -->
                    <div class="space-y-6">
                        <div class="bg-green-50/50 p-4 rounded-2xl border border-green-100 flex items-center gap-3">
                            <span class="p-2 bg-green-100 text-green-600 rounded-xl">📍</span>
                            <h4 class="text-lg font-black text-gray-900">موقع السيرفر</h4>
                        </div>
                        <div class="aspect-video w-full rounded-3xl overflow-hidden border border-gray-200 shadow-md relative group">
                            @if($server->lat && $server->lng)
                                <div id="map" class="w-full h-full z-0"></div>
                            @else
                                <div class="w-full h-full bg-gray-100 flex flex-col items-center justify-center text-gray-400 gap-2">
                                    <span class="text-4xl">🗺️</span>
                                    <p class="font-bold text-sm">الموقع الجغرافي غير محدد</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Users Tab -->
            <div x-show="activeTab === 'users'" x-cloak x-transition:enter="duration-500 ease-out">
                <div id="sessions-card-new" class="space-y-6">
                    <div class="flex items-center justify-between bg-teal-50/50 p-4 rounded-2xl border border-teal-100">
                        <div class="flex items-center gap-3">
                            <span class="p-2 bg-teal-100 text-teal-600 rounded-xl">👥</span>
                            <h4 class="text-lg font-black text-gray-900">الجلسات المتصلة حالياً</h4>
                        </div>
                        <div class="flex gap-2">
                            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-xl text-[10px] font-black uppercase tracking-widest">PPPoE: <span id="pppoe-count-label-new">0</span></span>
                            <span class="px-3 py-1 bg-orange-100 text-orange-700 rounded-xl text-[10px] font-black uppercase tracking-widest">Hotspot: <span id="hotspot-count-label-new">0</span></span>
                        </div>
                    </div>

                    <div class="overflow-hidden border border-gray-100 rounded-3xl">
                        <table class="w-full text-sm text-right">
                            <thead class="bg-gray-50 border-b border-gray-100 text-gray-400 text-[10px] font-black uppercase">
                                <tr>
                                    <th class="px-6 py-4 font-black tracking-widest text-right">المستخدم</th>
                                    <th class="px-6 py-4 font-black tracking-widest text-right">عنوان الـ IP</th>
                                    <th class="px-6 py-4 font-black tracking-widest text-right">النوع</th>
                                    <th class="px-6 py-4 font-black tracking-widest text-right">مدة الاتصال</th>
                                </tr>
                            </thead>
                            <tbody id="sessions-list-new" class="divide-y divide-gray-50">
                                <!-- JS content -->
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-gray-400 font-bold italic">لا توجد جلسات نشطة حالياً.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Tools Tab -->
            <div x-show="activeTab === 'tools'" x-cloak x-transition:enter="duration-500 ease-out" class="space-y-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Connectivity -->
                    <div class="bg-gray-50 p-8 rounded-3xl border border-gray-100 shadow-sm space-y-6">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-12 h-12 bg-green-100 text-green-600 rounded-2xl flex items-center justify-center text-2xl shadow-sm border border-green-200">🔌</div>
                            <h4 class="text-lg font-black text-gray-900">اختبار الاتصال</h4>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 leading-relaxed mb-6">التأكد من أن لوحة التحكم قادرة على الوصول إلى الـ API الخاص بالمايكروتيك عبر WireGuard.</p>
                        </div>
                        <button onclick="testConnection()" class="w-full py-4 bg-green-600 hover:bg-green-700 text-white rounded-2xl font-black shadow-md transition-all active:scale-95">
                            اختبار الآن
                        </button>
                        <div id="connection-status-new" class="hidden text-center p-4 rounded-2xl bg-white shadow-sm border border-gray-100 font-bold text-sm"></div>
                    </div>

                    <!-- Sync Imports -->
                    <div class="bg-gradient-to-br from-indigo-600 to-indigo-700 p-8 rounded-3xl shadow-md border border-indigo-500 text-white space-y-6 relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16 blur-xl"></div>
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-12 h-12 bg-white/20 text-white rounded-2xl flex items-center justify-center text-2xl border border-white/10 shadow-sm">📥</div>
                            <h4 class="text-lg font-black text-white">استيراد البيانات والمزامنة</h4>
                        </div>
                        <div>
                            <p class="text-sm text-indigo-100 leading-relaxed mb-6">جلب المستخدمين والبروفايلات الموجودة مسبقاً في السيرفر إلى قاعدة بيانات MadaaQ.</p>
                        </div>
                        <div class="grid grid-cols-1 gap-3">
                            <button onclick="importData('pppoe')" class="flex items-center justify-between px-4 py-3 bg-white/10 hover:bg-white/20 border border-white/20 rounded-xl font-bold transition">
                                <span>استيراد مستخدمي PPPoE</span>
                                <span>📡</span>
                            </button>
                            <button onclick="importData('hotspot')" class="flex items-center justify-between px-4 py-3 bg-white/10 hover:bg-white/20 border border-white/20 rounded-xl font-bold transition">
                                <span>استيراد مستخدمي Hotspot</span>
                                <span>📶</span>
                            </button>
                            <button onclick="importData('pppoe-profiles')" class="flex items-center justify-between px-4 py-3 bg-white/10 hover:bg-white/20 border border-white/20 rounded-xl font-bold transition">
                                <span>مزامنة بروفايلات PPPoE</span>
                                <span>📋</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Config Tab -->
            <div x-show="activeTab === 'config'" x-cloak x-transition:enter="duration-500 ease-out" class="space-y-6">
                <div class="flex items-center justify-between bg-amber-50/50 p-4 rounded-2xl border border-amber-100">
                    <div class="flex items-center gap-3">
                        <span class="p-2 bg-amber-100 text-amber-600 rounded-xl">📜</span>
                        <h4 class="text-lg font-black text-gray-900">كود الإعداد الأولي (Terminal)</h4>
                    </div>
                    <button @click="copyScript()" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold shadow-md transition-all flex items-center gap-2">
                        <span x-show="!scriptCopied">📋 نسخ الكود</span>
                        <span x-show="scriptCopied" class="flex items-center gap-1">✅ تم النسخ</span>
                    </button>
                </div>

                <div class="rounded-3xl border border-gray-200 bg-white shadow-sm overflow-hidden text-left" dir="ltr">
                    <div class="bg-gray-50 border-b border-gray-100 px-6 py-3 flex items-center justify-between">
                        <div class="flex gap-2">
                            <div class="w-2.5 h-2.5 rounded-full bg-gray-300"></div>
                            <div class="w-2.5 h-2.5 rounded-full bg-gray-300"></div>
                            <div class="w-2.5 h-2.5 rounded-full bg-gray-300"></div>
                        </div>
                        <span class="text-[10px] font-mono text-gray-400 font-bold uppercase tracking-widest">madaaq-config.rsc</span>
                    </div>
                    <div class="p-8 overflow-x-auto bg-gray-50 max-h-[400px] overflow-y-auto custom-scrollbar-light scroll-smooth">
                        <pre class="text-sm font-mono text-blue-700 leading-relaxed whitespace-pre"><code id="setup-code-new"># Loading setup script...</code></pre>
                    </div>
                </div>

                <div class="bg-blue-50 border border-blue-100 rounded-3xl p-6 flex items-start gap-4">
                    <span class="text-2xl">💡</span>
                    <div class="text-sm text-blue-800 leading-relaxed">
                        <p class="font-black mb-2 text-base">كيف أقوم بالإعداد؟</p>
                        <p>1. قم بنسخ الكود أعلاه.</p>
                        <p>2. افتح تطبيق <strong>Winbox</strong> وادخل إلى السيرفر الخاص بك.</p>
                        <p>3. افتح <strong>New Terminal</strong> من القائمة اليسرى.</p>
                        <p>4. قم بلصق الكود (Right Click -> Paste) ثم اضغط <strong>Enter</strong>.</p>
                    </div>
                </div>
            </div>

            <!-- Backups Tab -->
            <div x-show="activeTab === 'backups'" x-cloak x-transition:enter="duration-500 ease-out" class="space-y-6">
                <div class="bg-purple-50/50 p-4 rounded-2xl border border-purple-100 flex items-center gap-3">
                    <span class="p-2 bg-purple-100 text-purple-600 rounded-xl">💾</span>
                    <h4 class="text-lg font-black text-gray-900">سجل النسخ الاحتياطية</h4>
                </div>
                
                @if($server->backups->count() > 0)
                    <div class="overflow-hidden border border-gray-100 rounded-3xl">
                        <table class="w-full text-sm text-right">
                            <thead class="bg-gray-50 border-b border-gray-100 text-gray-400 text-[10px] font-black uppercase tracking-widest">
                                <tr>
                                    <th class="px-6 py-4 text-right">اسم الملف</th>
                                    <th class="px-6 py-4 text-right">النوع</th>
                                    <th class="px-6 py-4 text-right">التاريخ</th>
                                    <th class="px-6 py-4 text-right">الحجم</th>
                                    <th class="px-6 py-4 text-center">الإجراء</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($server->backups()->latest()->take(10)->get() as $backup)
                                <tr class="hover:bg-gray-50/80 transition-colors">
                                    <td class="px-6 py-4 font-bold text-gray-900">{{ $backup->filename }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-wide {{ $backup->type == 'backup' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' }}">
                                            {{ $backup->type == 'backup' ? '📦 BACKUP' : 'TXT EXPORT' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-gray-500 font-mono text-xs" dir="ltr">{{ $backup->created_at->format('Y-m-d H:i') }}</td>
                                    <td class="px-6 py-4 text-gray-500 font-bold" dir="ltr">{{ round($backup->size / 1024 / 1024, 2) }} MB</td>
                                    <td class="px-6 py-4 text-center">
                                        <a href="{{ route('servers.backups.download', $backup) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-600 rounded-xl font-bold hover:bg-blue-600 hover:text-white transition-all shadow-sm">
                                            <span>⬇️</span>
                                            <span>تحميل</span>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-20 bg-gray-50 rounded-3xl border border-gray-100">
                        <div class="text-5xl mb-4">📭</div>
                        <h5 class="text-lg font-black text-gray-900 mb-2">لا توجد نسخ احتياطية</h5>
                        <p class="text-sm text-gray-500">سيقوم النظام بأخذ نسخة احتياطية لسيرفرك يومياً بشكل تلقائي.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Extra Styles for progress and scrollbars -->
<style>
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    
    .custom-scrollbar-dark::-webkit-scrollbar { width: 8px; }
    .custom-scrollbar-dark::-webkit-scrollbar-track { background: #0f172a; }
    .custom-scrollbar-dark::-webkit-scrollbar-thumb { background: #334155; border-radius: 10px; border: 2px solid #0f172a; }

    [x-cloak] { display: none !important; }

    /* Map Styling */
    #map {
        filter: saturate(1.2) contrast(1.1);
    }
</style>

<!-- Leaflet for Map (only if coords exist) -->
@if($server->lat && $server->lng)
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
@endif

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('serverDashboard', () => ({
        activeTab: 'general',
        showScript: false,
        scriptCopied: false,
        isConnected: {{ $server->is_connected ? 'true' : 'false' }},
        tabs: [
            { id: 'general', name: 'العامة', icon: '🏠' },
            { id: 'users', name: 'المستخدمين', icon: '👥' },
            { id: 'tools', name: 'الأدوات', icon: '🛠️' },
            { id: 'config', name: 'الإعداد', icon: '📜' },
            { id: 'backups', name: 'النسخ الاحتياطي', icon: '💾' },
        ],

        init() {
            if (this.activeTab === 'general') {
                this.initMap();
                this.updateStatus();
            }
            
            // Auto update every 30s
            setInterval(() => this.updateStatus(), 30000);
            
            this.$watch('activeTab', value => {
                if (value === 'general') setTimeout(() => this.initMap(), 100);
                if (value === 'config') this.fetchConfig();
            });
        },

        initMap() {
            @if($server->lat && $server->lng)
            if (document.getElementById('map') && !this.mapLoaded) {
                const map = L.map('map').setView([{{ $server->lat }}, {{ $server->lng }}], 14);
                L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
                    maxZoom: 19
                }).addTo(map);
                
                const customIcon = L.divIcon({
                    className: 'custom-div-icon',
                    html: "<div style='background-color:#3b82f6; width:16px; height:16px; border-radius:50%; border:3px solid white; shadow:0 5px 15px rgba(0,0,0,0.2)'></div>",
                    iconSize: [20, 20],
                    iconAnchor: [10, 10]
                });

                L.marker([{{ $server->lat }}, {{ $server->lng }}], {icon: customIcon}).addTo(map);
                this.mapLoaded = true;
            }
            @endif
        },

        updateStatus() {
            fetch('{{ route('servers.status', $server) }}')
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        const info = data.data;
                        this.isConnected = true;
                        
                        // Header info
                        if(document.getElementById('version-val')) document.getElementById('version-val').textContent = info.version;
                        if(document.getElementById('uptime-val')) document.getElementById('uptime-val').textContent = info.uptime;
                        
                        // Cards
                        if(document.getElementById('cpu-val')) document.getElementById('cpu-val').textContent = info.cpu_load + '%';
                        if(document.getElementById('cpu-bar')) document.getElementById('cpu-bar').style.width = info.cpu_load + '%';
                        
                        if(document.getElementById('mem-val')) {
                            document.getElementById('mem-val').textContent = 
                                Math.round(info.free_memory / 1024 / 1024) + ' / ' + Math.round(info.total_memory / 1024 / 1024);
                        }

                        if(document.getElementById('active-pppoe-val')) document.getElementById('active-pppoe-val').textContent = info.active_pppoe;
                        if(document.getElementById('active-hotspot-val')) document.getElementById('active-hotspot-val').textContent = info.active_hotspot;
                        if(document.getElementById('pppoe-count-label-new')) document.getElementById('pppoe-count-label-new').textContent = info.active_pppoe;
                        if(document.getElementById('hotspot-count-label-new')) document.getElementById('hotspot-count-label-new').textContent = info.active_hotspot;

                        // Sessions List
                        const list = document.getElementById('sessions-list-new');
                        if (list && (info.pppoe_users.length > 0 || info.hotspot_users.length > 0)) {
                            list.innerHTML = '';
                            info.pppoe_users.forEach(user => {
                                list.innerHTML += this.renderSessionRow(user, 'PPPoE', 'green');
                            });
                            info.hotspot_users.forEach(user => {
                                list.innerHTML += this.renderSessionRow(user, 'Hotspot', 'orange');
                            });
                        }

                        // Top Sites
                        const sitesList = document.getElementById('top-sites-list-new');
                        if (sitesList && info.top_sites && info.top_sites.length > 0) {
                            document.getElementById('top-sites-card-new').classList.remove('hidden');
                            sitesList.innerHTML = '';
                            const maxHits = info.top_sites[0].hits;
                            info.top_sites.forEach(site => {
                                const percent = (site.hits / maxHits) * 100;
                                sitesList.innerHTML += `
                                    <div class="space-y-1.5">
                                        <div class="flex justify-between text-xs font-bold">
                                            <span class="text-gray-700 font-mono">${site.domain}</span>
                                            <span class="text-indigo-600">${site.hits} طلب</span>
                                        </div>
                                        <div class="w-full bg-white h-2 rounded-full border border-gray-100 overflow-hidden">
                                            <div class="bg-indigo-500 h-full rounded-full transition-all duration-1000" style="width: ${percent}%"></div>
                                        </div>
                                    </div>
                                `;
                            });
                        }
                    } else {
                        this.isConnected = false;
                    }
                })
                .catch(() => this.isConnected = false);
        },

        renderSessionRow(user, type, color) {
            return `
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-6 py-4 font-black text-gray-900">${user.name || user.user || 'N/A'}</td>
                    <td class="px-6 py-4 font-mono text-gray-500">${user.address || '--'}</td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 bg-${color}-50 text-${color}-700 rounded-lg text-[10px] font-black uppercase tracking-widest">${type}</span>
                    </td>
                    <td class="px-6 py-4 text-gray-500 font-mono text-xs">${user.uptime || '--'}</td>
                </tr>
            `;
        },

        fetchConfig() {
            const codeBlock = document.getElementById('setup-code-new');
            if (codeBlock && codeBlock.textContent.includes('Loading')) {
                fetch('{{ route('servers.setup-script', $server) }}')
                    .then(res => res.json())
                    .then(data => {
                        codeBlock.textContent = data.script;
                    })
                    .catch(() => {
                        codeBlock.textContent = '# خطأ في تحميل السكريبت';
                    });
            }
        },

        copyScript() {
            fetch('{{ route('servers.setup-script', $server) }}')
                .then(res => res.json())
                .then(data => {
                    navigator.clipboard.writeText(data.script);
                    this.scriptCopied = true;
                    setTimeout(() => this.scriptCopied = false, 3000);
                });
        }
    }));
});

function testConnection() {
    const statusDiv = document.getElementById('connection-status-new');
    statusDiv.innerHTML = '<span class="flex items-center justify-center gap-2"><span class="animate-spin text-blue-500">🔄</span> جاري الاختبار...</span>';
    statusDiv.classList.remove('hidden');

    fetch('{{ route('servers.test-connection', $server) }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' }
    })
    .then(res => res.json())
    .then(data => {
        statusDiv.innerHTML = data.success ? 
            `<span class="text-green-600">✅ ${data.message}</span>` : 
            `<span class="text-red-600">❌ ${data.message}</span>`;
            
        if (data.success) {
            setTimeout(() => window.location.reload(), 2000);
        }
    });
}

function importData(type) {
    const btn = event.currentTarget;
    const originalContent = btn.innerHTML;
    btn.innerHTML = '<span>جاري الاستيراد...</span><span class="animate-spin">⌛</span>';
    btn.disabled = true;

    let route = '';
    if (type === 'pppoe') route = '{{ route('servers.import-pppoe', $server) }}';
    if (type === 'hotspot') route = '{{ route('servers.import-hotspot', $server) }}';
    if (type === 'pppoe-profiles') route = '{{ route('servers.import-pppoe-profiles', $server) }}';
    if (type === 'hotspot-profiles') route = '{{ route('servers.import-hotspot-profiles', $server) }}';

    fetch(route, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        btn.innerHTML = originalContent;
        btn.disabled = false;
        if (data.success) {
            alert('✅ ' + data.message);
            window.location.reload();
        } else {
            alert('❌ ' + data.message);
        }
    })
    .catch(() => {
        btn.innerHTML = originalContent;
        btn.disabled = false;
        alert('❌ حدث خطأ غير متوقع');
    });
}
</script>
@endsection
