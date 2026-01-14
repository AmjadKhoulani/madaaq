@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto space-y-6" x-data="serverPage({{ $server->setup_script_generated ? 'true' : 'false' }})">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">🖥️ {{ $server->name }}</h2>
            <p class="text-gray-500 mt-1">{{ $server->ip }}:{{ $server->api_port }}</p>
        </div>
        {!! $server->status_badge !!}
    </div>

    @if(session('success'))
    <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg">
        <div class="flex items-center gap-3">
            <svg class="w-6 h-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <p class="text-green-800 font-semibold">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Server Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Details Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="font-bold text-lg text-gray-900 mb-4">معلومات السيرفر</h3>
                <dl class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <dt class="text-sm text-gray-500">اسم المستخدم</dt>
                        <dd class="font-semibold text-gray-900">{{ $server->username }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">الموقع</dt>
                        <dd class="font-semibold text-gray-900">{{ $server->location ?? 'غير محدد' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">آخر مزامنة</dt>
                        <dd class="font-semibold text-gray-900 text-xs">
                            {{ $server->last_sync_at ? $server->last_sync_at->diffForHumans() : 'لم يتم بعد' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">الإصدار</dt>
                        <dd class="font-semibold text-gray-900" id="version-val">--</dd>
                    </div>
                </dl>
            </div>

            <!-- Monitoring Card -->
            <div id="monitoring-card" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hidden">
                <h3 class="font-bold text-lg text-gray-900 mb-4">📊 مراقبة النظام</h3>
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 text-center">
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <p class="text-xs text-gray-500 mb-1">CPU Load</p>
                        <p class="text-lg font-bold text-blue-600" id="cpu-val">0%</p>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <p class="text-xs text-gray-500 mb-1">Free RAM</p>
                        <p class="text-sm font-bold text-blue-600" id="mem-val">--</p>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <p class="text-xs text-gray-500 mb-1">PPPoE Active</p>
                        <p class="text-lg font-bold text-green-600" id="active-pppoe-val">0</p>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <p class="text-xs text-gray-500 mb-1">Hotspot Active</p>
                        <p class="text-lg font-bold text-orange-600" id="active-hotspot-val">0</p>
                    </div>
                </div>
                <div class="mt-4 p-2 bg-blue-50 rounded border border-blue-100">
                    <p class="text-xs text-blue-800 text-center">Uptime: <span id="uptime-val" class="font-mono">--</span></p>
                </div>
            </div>


            <!-- Active Sessions Card -->
            <div id="sessions-card" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hidden">
                <!-- Existing Sessions Content -->
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-bold text-lg text-gray-900">👥 الجلسات النشطة</h3>
                    <div class="flex gap-2 text-xs">
                        <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full">PPPoE: <span id="pppoe-count-label">0</span></span>
                        <span class="px-2 py-1 bg-orange-100 text-orange-700 rounded-full">Hotspot: <span id="hotspot-count-label">0</span></span>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-right">
                        <thead class="bg-gray-50 text-gray-500">
                            <tr>
                                <th class="px-4 py-2 font-medium">المستخدم</th>
                                <th class="px-4 py-2 font-medium">العنوان (IP)</th>
                                <th class="px-4 py-2 font-medium">النوع</th>
                                <th class="px-4 py-2 font-medium">Uptime</th>
                            </tr>
                        </thead>
                        <tbody id="sessions-list" class="divide-y divide-gray-100">
                            <!-- Sessions will be loaded here -->
                        </tbody>
                    </table>
                </div>
            </div>

            </div>

            <!-- Backups Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="font-bold text-lg text-gray-900 mb-4">💾 النسخ الاحتياطية</h3>
                
                @if($server->backups->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-right">
                        <thead class="bg-gray-50 text-gray-500">
                            <tr>
                                <th class="px-4 py-2 font-medium">الملف</th>
                                <th class="px-4 py-2 font-medium">النوع</th>
                                <th class="px-4 py-2 font-medium">التاريخ</th>
                                <th class="px-4 py-2 font-medium">الحجم</th>
                                <th class="px-4 py-2 font-medium">تحميل</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($server->backups()->latest()->take(5)->get() as $backup)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-2 text-gray-900">{{ $backup->filename }}</td>
                                <td class="px-4 py-2">
                                    <span class="px-2 py-0.5 rounded text-xs {{ $backup->type == 'backup' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' }}">
                                        {{ ucfirst($backup->type) }}
                                    </span>
                                </td>
                                <td class="px-4 py-2 text-gray-500" dir="ltr">{{ $backup->created_at->format('Y-m-d H:i') }}</td>
                                <td class="px-4 py-2 text-gray-500" dir="ltr">{{ round($backup->size / 1024 / 1024, 2) }} MB</td>
                                <td class="px-4 py-2">
                                    <a href="{{ route('servers.backups.download', $backup) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                        ⬇️ تحميل
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-6 text-gray-500">
                    <p>لا توجد نسخ احتياطية متاحة حالياً.</p>
                    <p class="text-xs mt-1">يتم أخذ النسخ تلقائياً كل يوم الساعة 2:00 صباحاً</p>
                </div>
                @endif
            </div>

            <!-- Top Visited Sites Card -->
            <div id="top-sites-card" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hidden">
                <h3 class="font-bold text-lg text-gray-900 mb-4">🌐 المواقع الأكثر تصفحاً</h3>
                <div class="space-y-4" id="top-sites-list">
                    <!-- Progress bars for sites will be here -->
                </div>
                <p class="text-[10px] text-gray-400 mt-4 text-center italic">* يتم استنتاجها من سجل DNS السريع في المايكروتك</p>
            </div>

            <!-- Setup Script -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6" id="setup-script">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-bold text-lg text-gray-900">📜 كود إعداد السيرفر</h3>
                    <button @click="showScript = !showScript" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                        <span x-text="showScript ? 'إخفاء' : 'عرض'"></span>
                    </button>
                </div>

                <div x-show="!showScript" class="text-center py-8">
                    <p class="text-gray-500 mb-4">انقر على "عرض" لرؤية كود الإعداد</p>
                    <button @click="showScript = true" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        عرض الكود
                    </button>
                </div>

                <div x-show="showScript" x-cloak class="space-y-4">
                    <!-- Modern Terminal Window -->
                    <div class="rounded-xl border border-gray-300 bg-white shadow-sm overflow-hidden text-left" dir="ltr">
                        <!-- Window Header -->
                        <div class="bg-gray-100 border-b border-gray-200 px-4 py-2 flex items-center justify-between">
                            <div class="flex gap-1.5">
                                <div class="w-3 h-3 rounded-full bg-red-400"></div>
                                <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
                                <div class="w-3 h-3 rounded-full bg-green-400"></div>
                            </div>
                            <span class="text-xs font-mono text-gray-500">router-setup.rsc</span>
                            <div></div> <!-- Spacer -->
                        </div>
                        
                        <!-- Code Content -->
                        <div class="p-4 overflow-x-auto bg-slate-50 max-h-96 overflow-y-auto custom-scrollbar" id="script-content">
                            <pre class="text-sm font-mono text-slate-800 leading-relaxed whitespace-pre" style="tab-size: 4;"><code id="setup-code"># Loading setup script...</code></pre>
                        </div>
                    </div>
                    
                    <div class="flex gap-3">
                        <button @click="copyScript()" class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow-md hover:shadow-lg transition transform hover:-translate-y-0.5 font-bold flex items-center justify-center gap-2 group">
                            <span x-show="!scriptCopied" class="group-hover:scale-110 transition">📋</span>
                            <span x-show="!scriptCopied">نسخ الكود بالكامل</span>
                            
                            <span x-show="scriptCopied" class="text-white flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                تم النسخ بنجاح!
                            </span>
                        </button>
                    </div>

                    <!-- WireGuard Sync Notice -->
                    <div x-show="scriptCopied" x-transition class="bg-amber-50 border-l-4 border-amber-500 rounded-lg p-4 flex items-start gap-3">
                        <svg class="w-6 h-6 text-amber-600 mt-0.5 shrink-0 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <div class="text-sm text-amber-900 flex-1">
                            <strong class="block mb-1">⏳ انتظر دقيقة واحدة للربط التلقائي</strong>
                            <p class="mb-2">بعد تطبيق الكود على المايكروتيك، سيقوم النظام تلقائياً بربط السيرفر خلال <span x-text="countdown" class="font-mono font-bold text-amber-700"></span> ثانية.</p>
                            <div class="w-full bg-amber-200 rounded-full h-2 overflow-hidden">
                                <div class="bg-amber-600 h-full transition-all duration-1000" :style="'width: ' + ((60 - countdown) / 60 * 100) + '%'"></div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-blue-50 border border-blue-100 rounded-lg p-4 flex items-start gap-3">
                        <svg class="w-5 h-5 text-blue-600 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <div class="text-sm text-blue-800">
                            <strong>تعليمات:</strong> هذا الكود يقوم بضبط إعدادات WireGuard، إنشاء المستخدمين، وإعداد جدول المزامنة تلقائياً. ألصقه في Terminal المايكروتيك واضغط Enter.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="space-y-6">
            <!-- Connection Test -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="font-bold text-lg text-gray-900 mb-4">🔌 اختبار الاتصال</h3>
                <button onclick="testConnection()" class="w-full px-4 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg transition font-semibold">
                    اختبار الآن
                </button>
                <div id="connection-status" class="mt-4 hidden">
                    <!-- Will show result here -->
                </div>
            </div>

            <!-- Import Data -->
            <div class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl shadow-lg p-6 text-white">
                <h3 class="font-bold text-lg mb-4">📥 استيراد البيانات</h3>
                <div class="space-y-2">
                    <button onclick="importData('pppoe')" class="w-full px-4 py-2 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-lg transition text-left">
                        📡 PPPoE Users
                    </button>
                    <button onclick="importData('hotspot')" class="w-full px-4 py-2 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-lg transition text-left">
                        📶 Hotspot Users
                    </button>
                    <button onclick="importData('pppoe-profiles')" class="w-full px-4 py-2 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-lg transition text-left">
                        📋 PPPoE Profiles
                    </button>
                    <button onclick="importData('hotspot-profiles')" class="w-full px-4 py-2 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-lg transition text-left">
                        📋 Hotspot Profiles
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyScript() {
    fetch('{{ route('servers.setup-script', $server) }}')
        .then(res => res.json())
        .then(data => {
            navigator.clipboard.writeText(data.script);
            // Trigger Alpine component state
            const event = new CustomEvent('script-copied');
            window.dispatchEvent(event);
        });
}

// Load script when shown
document.addEventListener('alpine:init', () => {
    Alpine.data('serverPage', (initialShowScript) => ({
        showScript: initialShowScript,
        scriptCopied: false,
        countdown: 60,
        countdownInterval: null,
        init() {
            // Fetch immediately if shown by default
            if (this.showScript) {
                this.fetchConfig();
            }

            this.$watch('showScript', value => {
                if (value) {
                    this.fetchConfig();
                }
            });

            // Listen for copy event
            window.addEventListener('script-copied', () => {
                this.scriptCopied = true;
                this.startCountdown();
            });

            // Start countdown when script is copied
            this.$watch('scriptCopied', value => {
                if (value) {
                    this.startCountdown();
                }
            });
        },
        fetchConfig() {
            const codeBlock = document.getElementById('setup-code');
            if (codeBlock.textContent.includes('Loading')) {
                 fetch('{{ route('servers.setup-script', $server) }}')
                    .then(res => res.json())
                    .then(data => {
                        codeBlock.textContent = data.script;
                    })
                    .catch(err => {
                        codeBlock.textContent = '# Error loading script';
                    });
            }
        },
        startCountdown() {
            this.countdown = 60;
            if (this.countdownInterval) {
                clearInterval(this.countdownInterval);
            }
            this.countdownInterval = setInterval(() => {
                this.countdown--;
                if (this.countdown <= 0) {
                    clearInterval(this.countdownInterval);
                    this.countdown = 0;
                }
            }, 1000);
        }
    }));
});

function testConnection() {
    const statusDiv = document.getElementById('connection-status');
    statusDiv.innerHTML = '<p class="text-sm text-gray-600">جاري الاختبار...</p>';
    statusDiv.classList.remove('hidden');

    fetch('{{ route('servers.test-connection', $server) }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            statusDiv.innerHTML = '<p class="text-sm text-green-600 font-semibold">✅ ' + data.message + '</p>';
        } else {
            statusDiv.innerHTML = '<p class="text-sm text-red-600 font-semibold">❌ ' + data.message + '</p>';
        }
    });
}

function importData(type) {
    const btn = event.target;
    const originalText = btn.innerText;
    btn.innerText = '⌛ جاري الاستيراد...';
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
        btn.innerText = originalText;
        btn.disabled = false;
        if (data.success) {
            alert('✅ ' + data.message);
            window.location.reload();
        } else {
            alert('❌ ' + data.message);
        }
    })
    .catch(err => {
        btn.innerText = originalText;
        btn.disabled = false;
        alert('❌ حدث خطأ غير متوقع');
    });
}

function updateServerStatus() {
    fetch('{{ route('servers.status', $server) }}')
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const info = data.data;
                document.getElementById('version-val').textContent = info.version;
                document.getElementById('uptime-val').textContent = info.uptime;
                document.getElementById('cpu-val').textContent = info.cpu_load + '%';
                document.getElementById('mem-val').textContent = Math.round(info.free_memory / 1024 / 1024) + ' / ' + Math.round(info.total_memory / 1024 / 1024) + ' MB';
                document.getElementById('active-pppoe-val').textContent = info.active_pppoe;
                document.getElementById('active-hotspot-val').textContent = info.active_hotspot;
                document.getElementById('pppoe-count-label').textContent = info.active_pppoe;
                document.getElementById('hotspot-count-label').textContent = info.active_hotspot;

                // Update Sessions List
                const list = document.getElementById('sessions-list');
                list.innerHTML = '';
                
                info.pppoe_users.forEach(user => {
                    list.innerHTML += `<tr class="hover:bg-gray-50">
                        <td class="px-4 py-2 font-medium text-gray-900">${user.name || user.user || 'N/A'}</td>
                        <td class="px-4 py-2 text-gray-500">${user.address || '--'}</td>
                        <td class="px-4 py-2"><span class="px-2 py-0.5 bg-green-50 text-green-700 rounded text-xs">PPPoE</span></td>
                        <td class="px-4 py-2 text-gray-500">${user.uptime || '--'}</td>
                    </tr>`;
                });

                info.hotspot_users.forEach(user => {
                    list.innerHTML += `<tr class="hover:bg-gray-50">
                        <td class="px-4 py-2 font-medium text-gray-900">${user.user || user.name || 'N/A'}</td>
                        <td class="px-4 py-2 text-gray-500">${user.address || '--'}</td>
                        <td class="px-4 py-2"><span class="px-2 py-0.5 bg-orange-50 text-orange-700 rounded text-xs">Hotspot</span></td>
                        <td class="px-4 py-2 text-gray-500">${user.uptime || '--'}</td>
                    </tr>`;
                });

                if (info.pppoe_users.length > 0 || info.hotspot_users.length > 0) {
                    document.getElementById('sessions-card').classList.remove('hidden');
                } else {
                    document.getElementById('sessions-card').classList.add('hidden');
                }

                // Update Top Sites
                const sitesList = document.getElementById('top-sites-list');
                sitesList.innerHTML = '';
                if (info.top_sites && info.top_sites.length > 0) {
                    const maxHits = info.top_sites[0].hits;
                    info.top_sites.forEach(site => {
                        const percent = (site.hits / maxHits) * 100;
                        sitesList.innerHTML += `
                            <div class="space-y-1">
                                <div class="flex justify-between text-xs">
                                    <span class="font-medium text-gray-700">${site.domain}</span>
                                    <span class="text-gray-500">${site.hits} reqs</span>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-1.5">
                                    <div class="bg-blue-500 h-1.5 rounded-full" style="width: ${percent}%"></div>
                                </div>
                            </div>
                        `;
                    });
                    document.getElementById('top-sites-card').classList.remove('hidden');
                } else {
                    document.getElementById('top-sites-card').classList.add('hidden');
                }

                document.getElementById('monitoring-card').classList.remove('hidden');
            }
        });
}

// Update status every 30 seconds
setInterval(updateServerStatus, 30000);
document.addEventListener('DOMContentLoaded', updateServerStatus);
</script>
@endsection
