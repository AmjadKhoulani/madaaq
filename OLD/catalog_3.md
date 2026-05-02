# Source Catalog: cat3

## resources/views/network\backups\index.blade.php

```php
@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">💾 النسخ الاحتياطية</h2>
            <p class="text-gray-500 mt-1">أرشيف النسخ الاحتياطية لجميع السيرفرات (آخر 10 أيام)</p>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg">
        <p class="text-green-800 font-semibold">{{ session('success') }}</p>
    </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-right">
                <thead class="bg-gray-50 text-gray-500 text-sm uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-3 font-medium">السيرفر</th>
                        <th class="px-6 py-3 font-medium">اسم الملف</th>
                        <th class="px-6 py-3 font-medium">النوع</th>
                        <th class="px-6 py-3 font-medium">التاريخ</th>
                        <th class="px-6 py-3 font-medium">الحجم</th>
                        <th class="px-6 py-3 font-medium">تحميل</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($backups as $backup)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900">{{ $backup->server->name ?? 'Deleted Server' }}</div>
                            <div class="text-xs text-gray-500">{{ $backup->server->ip ?? '' }}</div>
                        </td>
                        <td class="px-6 py-4 text-gray-700 font-mono text-sm">{{ $backup->filename }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded text-xs font-semibold {{ $backup->type == 'backup' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                {{ strtoupper($backup->type) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-500 text-sm" dir="ltr">
                            {{ $backup->created_at->format('Y-m-d H:i') }}
                            <div class="text-xs text-gray-400">{{ $backup->created_at->diffForHumans() }}</div>
                        </td>
                        <td class="px-6 py-4 text-gray-500 text-sm" dir="ltr">{{ round($backup->size / 1024 / 1024, 2) }} MB</td>
                        <td class="px-6 py-4">
                            <a href="{{ route('servers.backups.download', $backup) }}" class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-800 font-medium text-sm">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                تحميل
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                            <p>لا توجد نسخ احتياطية متاحة حتى الآن.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($backups->hasPages())
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
            {{ $backups->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

```

## resources/views/network\commands\index.blade.php

```php
@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold gradient-text">الأوامر الديناميكية (Terminal)</h1>
            <p class="text-gray-500 mt-1">إدارة وتحكم مباشر في أجهزة الشبكة باحترافية</p>
        </div>
        <div class="flex items-center gap-3">
             <button onclick="clearConsole()" class="px-5 py-2.5 text-gray-700 bg-white shadow-sm border border-gray-100 hover:bg-gray-50 rounded-xl transition font-medium flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                مسح الشاشة
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Control Panel -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Server Selection -->
            <div class="glass p-6 rounded-2xl shadow-lg border border-white/30">
                <label class="block text-sm font-bold text-gray-700 mb-3 flex items-center gap-2">
                    <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/></svg>
                    اختر الجهاز (MikroTik)
                </label>
                <select id="server_id" class="w-full rounded-xl border-gray-200 focus:ring-indigo-500 focus:border-indigo-500 bg-white/50 backdrop-blur">
                    <option value="" disabled selected>-- اختر جهاز --</option>
                    @foreach($servers as $server)
                        <option value="{{ $server->id }}">{{ $server->name }} ({{ $server->ip }})</option>
                    @endforeach
                </select>
            </div>

            <!-- Quick Commands -->
            <div class="glass p-6 rounded-2xl shadow-lg border border-white/30">
                <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    أوامر سريعة
                </h3>
                <div class="grid grid-cols-2 gap-3">
                    <button onclick="setCommand('/system/resource/print')" class="p-4 bg-gradient-to-br from-blue-50 to-indigo-50 hover:from-blue-100 hover:to-indigo-100 text-blue-700 rounded-xl text-xs font-bold transition text-right shadow-sm border border-blue-100">
                        Resources 📊
                    </button>
                    <button onclick="setCommand('/ip/address/print')" class="p-4 bg-gradient-to-br from-purple-50 to-pink-50 hover:from-purple-100 hover:to-pink-100 text-purple-700 rounded-xl text-xs font-bold transition text-right shadow-sm border border-purple-100">
                        IP Addresses 🌐
                    </button>
                    <button onclick="setCommand('/interface/print')" class="p-4 bg-gradient-to-br from-emerald-50 to-teal-50 hover:from-emerald-100 hover:to-teal-100 text-emerald-700 rounded-xl text-xs font-bold transition text-right shadow-sm border border-emerald-100">
                        Interfaces 📡
                    </button>
                    <button onclick="setCommand('/system/identity/print')" class="p-4 bg-gradient-to-br from-orange-50 to-yellow-50 hover:from-orange-100 hover:to-yellow-100 text-orange-700 rounded-xl text-xs font-bold transition text-right shadow-sm border border-orange-100">
                        Identity 🎯
                    </button>
                </div>
                <div class="mt-4 p-3 bg-indigo-50/50 border border-indigo-100 rounded-xl text-[10px] text-indigo-800 leading-relaxed">
                    <strong>💡 ملاحظة:</strong> الأوامر تتبع صيغة MikroTik API القياسية باستخدام المسارات الكاملة.
                </div>
            </div>
        </div>

        <!-- Terminal Output -->
        <div class="lg:col-span-2 flex flex-col h-[650px] glass rounded-2xl shadow-2xl border border-white/30 overflow-hidden">
            <!-- Terminal Header -->
            <div class="px-4 py-3 bg-gray-900/90 backdrop-blur text-gray-400 text-[10px] font-mono flex items-center gap-2 border-b border-white/10">
                <div class="flex gap-1.5">
                    <div class="w-3 h-3 rounded-full bg-red-500 shadow-lg shadow-red-500/20"></div>
                    <div class="w-3 h-3 rounded-full bg-yellow-500 shadow-lg shadow-yellow-500/20"></div>
                    <div class="w-3 h-3 rounded-full bg-green-500 shadow-lg shadow-green-500/20"></div>
                </div>
                <span class="ml-2 font-bold tracking-wider opacity-80" id="terminal-title">TERMINAL CONSOLE</span>
            </div>

            <!-- Terminal Body -->
            <div id="terminal-output" class="flex-1 bg-gradient-to-br from-gray-900 to-gray-800 p-6 font-mono text-sm text-green-400 overflow-y-auto space-y-1">
                <div class="opacity-60 text-cyan-400">═══════════════════════════════════════════════════</div>
                <div class="opacity-80 text-white font-bold">🚀 MikroTik Terminal Console v1.0</div>
                <div class="opacity-60 text-cyan-400">═══════════════════════════════════════════════════</div>
                <div class="opacity-70 text-yellow-300 mt-2">ℹ️  اختر جهازاً من القائمة لبدء التشغيل...</div>
            </div>

            <!-- Input Area -->
            <div class="p-4 bg-gray-900/95 border-t border-white/10 backdrop-blur-xl">
                <div class="flex gap-4 items-center">
                    <span class="text-indigo-500 font-bold font-mono text-lg animate-pulse">></span>
                    <input type="text" id="command-input" placeholder="Type command (e.g. /system/resource/print)..." class="flex-1 bg-transparent border-none text-white focus:ring-0 font-mono text-sm placeholder:opacity-30" onkeypress="handleEnter(event)">
                    <button onclick="executeCommand()" class="px-8 py-2.5 bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white font-bold rounded-xl transition shadow-lg shadow-indigo-500/20 flex items-center gap-2" id="exec-btn">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        EXECUTE
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function setCommand(cmd) {
    const input = document.getElementById('command-input');
    input.value = cmd;
    input.focus();
}

function clearConsole() {
    document.getElementById('terminal-output').innerHTML = '<div class="opacity-50">Console cleared.</div>';
}

function handleEnter(e) {
    if (e.key === 'Enter') {
        executeCommand();
    }
}

function appendLog(text, type = 'info') {
    const term = document.getElementById('terminal-output');
    const div = document.createElement('div');
    
    // Format timestamp
    const time = new Date().toLocaleTimeString('en-US', { hour12: false });
    
    if (type === 'command') {
        div.className = 'text-white font-bold border-l-2 border-indigo-500 pl-2 mt-4';
        div.innerText = `[${time}] > ${text}`;
    } else if (type === 'error') {
        div.className = 'text-red-400 pl-2 whitespace-pre-wrap';
        div.innerText = text;
    } else {
        div.className = 'text-green-400 pl-2 whitespace-pre-wrap';
        div.innerText = text;
    }
    
    term.appendChild(div);
    term.scrollTop = term.scrollHeight;
}

function executeCommand() {
    const serverId = document.getElementById('server_id').value;
    const command = document.getElementById('command-input').value;
    const btn = document.getElementById('exec-btn');

    if (!serverId) {
        alert('يرجى اختيار سيرفر أولاً');
        return;
    }
    if (!command) return;

    // UI Updates
    appendLog(command, 'command');
    document.getElementById('command-input').value = '';
    btn.disabled = true;
    btn.innerText = 'Run...';
    btn.classList.add('opacity-75');

    // API Call
    fetch('{{ route("network.commands.execute") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            server_id: serverId,
            command: command
        })
    })
    .then(response => {
        console.log('Response status:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            appendLog(data.output || 'Command executed successfully');
        } else {
            appendLog('❌ Error: ' + (data.message || 'Unknown error'), 'error');
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
        appendLog('❌ Connection Error: ' + error.message, 'error');
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = `
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            EXECUTE
        `;
        btn.classList.remove('opacity-75');
        // Re-focus input
        setTimeout(() => {
            document.getElementById('command-input').focus();
        }, 100);
    });
}
</script>
@endsection

```

## resources/views/network\discovery\index.blade.php

```php
@extends('layouts.app')

@section('content')
<div class="space-y-6" x-data="{
    selectedServer: '',
    isScanning: false,
    neighbors: [],
    error: null,
    async scan() {
        if (!this.selectedServer) return;
        this.isScanning = true;
        this.error = null;
        this.neighbors = [];
        
        try {
            const response = await fetch(`/network/discovery/scan/${this.selectedServer}`);
            const data = await response.json();
            if (response.ok) {
                this.neighbors = data;
            } else {
                this.error = data.error || 'فشل الفحص';
            }
        } catch (e) {
            this.error = 'حدث خطأ أثناء الاتصال بالخادم';
        } finally {
            this.isScanning = false;
        }
    },
    async link(neighbor) {
        if (!confirm('هل تريد ربط هذا الجهاز وتحديث عنوان IP الخاص به؟')) return;
        
        try {
            const response = await fetch('/network/discovery/link', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    device_id: neighbor.device_id,
                    neighbor_ip: neighbor.address || neighbor.address4,
                    device_type: neighbor.device_type
                })
            });
            
            if (response.ok) {
                alert('تم الربط وتحديث البيانات بنجاح!');
                this.scan(); // Refresh
            }
        } catch (e) {
            alert('فشل عملية الربط');
        }
    }
}">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">استكشاف الشبكة (Discovery)</h2>
            <p class="text-gray-500 mt-1">اكتشاف الأجهزة المحيطة وربطها آلياً عبر بروتوكول MNDP</p>
        </div>
    </div>

    <div class="glass rounded-2xl p-6 shadow-lg border border-white/30">
        <div class="flex items-end gap-4">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-2">اختر السيرفر للفحص من خلاله</label>
                <select x-model="selectedServer" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                    <option value="">-- اختر سيرفر --</option>
                    @foreach($servers as $server)
                        <option value="{{ $server->id }}">{{ $server->name }} ({{ $server->ip }})</option>
                    @endforeach
                </select>
            </div>
            <button @click="scan()" :disabled="!selectedServer || isScanning" class="px-6 py-2 bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white font-bold rounded-xl shadow-lg transition disabled:opacity-50">
                <span x-show="!isScanning">🔍 ابدأ الفحص</span>
                <span x-show="isScanning">⏳ جاري الفحص...</span>
            </button>
        </div>
    </div>

    <div x-show="error" class="p-4 bg-red-50 border-r-4 border-red-500 text-red-700 rounded-lg" style="display: none;">
        <p x-text="error"></p>
    </div>

    <div class="glass rounded-2xl shadow-lg border border-white/30 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الجهاز</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">IP Address</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">MAC Address</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الموديل / الإصدار</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الحالة في النظام</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الإجراء</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <template x-for="neighbor in neighbors" :key="neighbor['mac-address']">
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-900" x-text="neighbor.identity || 'بدون اسم'"></div>
                            <div class="text-xs text-blue-500 font-bold" x-text="neighbor.source"></div>
                            <div class="text-xs text-gray-400" x-text="neighbor.interface"></div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-mono bg-gray-100 px-2 py-1 rounded text-sm" x-text="neighbor.address || '--'"></span>
                            <template x-if="neighbor.address">
                                <a :href="'/network/discovery/webfig/' + neighbor.server_id + '/' + neighbor.address" target="_blank" class="ml-2 inline-flex items-center text-purple-600 hover:text-purple-800" title="دخول Webfig عن بُعد">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                    <span class="text-xs font-bold mr-1">Webfig</span>
                                </a>
                            </template>
                        </td>
                        <td class="px-6 py-4 text-xs text-gray-600 font-mono" x-text="neighbor['mac-address']"></td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900" x-text="neighbor.platform || '--'"></div>
                            <template x-if="neighbor.age">
                                <div class="text-xs text-gray-400" x-text="'العمر: ' + neighbor.age"></div>
                            </template>
                        </td>
                        <td class="px-6 py-4">
                            <template x-if="neighbor.is_linked">
                                <div class="flex flex-col gap-1">
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-[10px] font-bold text-center w-fit">
                                        مرتبط: <span x-text="neighbor.db_name"></span>
                                    </span>
                                    <template x-if="neighbor.db_ip !== neighbor.address">
                                        <button @click="link(neighbor)" class="text-blue-600 hover:text-blue-800 text-[10px] font-bold underline flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                            تحديث الـ IP في النظام
                                        </button>
                                    </template>
                                </div>
                            </template>
                            <template x-if="!neighbor.is_linked">
                                <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-[10px] font-bold">غير مسجل</span>
                            </template>
                        </td>
                        <td class="px-6 py-4">
                             <template x-if="!neighbor.is_linked">
                                <span class="text-gray-400 text-xs italic">يتطلب تسجيله كراوتر</span>
                            </template>
                        </td>
                    </tr>
                </template>
                <template x-if="neighbors.length === 0 && !isScanning">
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                            لا توجد نتائج. اضغط على زر الفحص للبدء.
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>
</div>
@endsection

```

## resources/views/network\internet_sources\edit.blade.php

```php
@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight" style="font-family: 'Cairo', sans-serif;">تعديل مصدر الإنترنت</h1>
            <p class="text-sm text-gray-500 mt-1" style="font-family: 'Cairo', sans-serif;">أنت الآن تعدل بيانات: <span class="font-bold text-indigo-600">{{ $internetSource->name }}</span></p>
        </div>
        <a href="{{ route('network.internet-sources.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors shadow-sm">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            العودة للقائمة
        </a>
    </div>

    <!-- Main Card -->
    <div class="bg-white rounded-lg border border-gray-300 shadow-sm">
        <form action="{{ route('network.internet-sources.update', $internetSource->id) }}" method="POST" class="p-6 md:p-8 space-y-8">
            @csrf
            @method('PUT')

            <!-- Section: Primary Identity -->
            <div class="space-y-4">
                <h2 class="text-base font-bold text-gray-900 border-b border-gray-100 pb-2 flex items-center gap-2" style="font-family: 'Cairo', sans-serif;">
                    <span class="w-1 h-5 bg-indigo-500 rounded-full"></span>
                    الهوية والتعريف
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-1">
                        <label class="block text-sm font-bold text-gray-700">اسم المصدر</label>
                        <input type="text" name="name" value="{{ $internetSource->name }}" required 
                            class="block w-full px-4 py-2.5 rounded-lg border border-gray-300 bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors text-sm font-bold text-gray-900 placeholder-gray-400 shadow-sm"
                            placeholder="مثال: الخط الرئيسي 1">
                    </div>
                </div>
            </div>

            <!-- Section: Technical Config -->
            <div class="space-y-4">
                <h2 class="text-base font-bold text-gray-900 border-b border-gray-100 pb-2 flex items-center gap-2" style="font-family: 'Cairo', sans-serif;">
                    <span class="w-1 h-5 bg-indigo-500 rounded-full"></span>
                    الإعدادات التقنية
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Type -->
                    <div class="space-y-1">
                        <label class="block text-sm font-bold text-gray-700">النوع</label>
                        <div class="relative">
                            <select name="type" required class="block w-full px-4 py-2.5 rounded-lg border border-gray-300 bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors text-sm font-medium text-gray-900 shadow-sm appearance-none">
                                <option value="fiber" {{ $internetSource->type == 'fiber' ? 'selected' : '' }}>Fiber Optic (فايبر)</option>
                                <option value="starlink" {{ $internetSource->type == 'starlink' ? 'selected' : '' }}>Starlink (ستارلينك)</option>
                                <option value="microwave" {{ $internetSource->type == 'microwave' ? 'selected' : '' }}>Microwave Link (مايكروويف)</option>
                                <option value="4g" {{ $internetSource->type == '4g' ? 'selected' : '' }}>4G / LTE (شبكة خلوية)</option>
                                <option value="other" {{ $internetSource->type == 'other' ? 'selected' : '' }}>Other (آخر)</option>
                            </select>
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Connection Type -->
                    <div class="space-y-1">
                        <label class="block text-sm font-bold text-gray-700">نوع الوصل (Connection)</label>
                        <div class="relative">
                            <select name="connection_type" class="block w-full px-4 py-2.5 rounded-lg border border-gray-300 bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors text-sm font-medium text-gray-900 shadow-sm appearance-none">
                                <option value="">غير محدد</option>
                                <option value="fiber" {{ $internetSource->connection_type == 'fiber' ? 'selected' : '' }}>فايبر (Fiber)</option>
                                <option value="cable" {{ $internetSource->connection_type == 'cable' ? 'selected' : '' }}>كابل (Cable)</option>
                                <option value="wireless" {{ $internetSource->connection_type == 'wireless' ? 'selected' : '' }}>لاسلكي (Wireless)</option>
                            </select>
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Capacity -->
                    <div class="space-y-1">
                        <label class="block text-sm font-bold text-gray-700">السعة (Capacity)</label>
                        <input type="text" name="capacity" value="{{ $internetSource->capacity }}" 
                            class="block w-full px-4 py-2.5 rounded-lg border border-gray-300 bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors text-sm font-medium text-gray-900 dir-ltr text-right shadow-sm"
                            placeholder="100 Mbps">
                    </div>

                    <!-- Gateway IP -->
                    <div class="space-y-1">
                        <label class="block text-sm font-bold text-gray-700">Gateway IP</label>
                        <input type="text" name="ip_gateway" value="{{ $internetSource->ip_gateway }}" 
                            class="block w-full px-4 py-2.5 rounded-lg border border-gray-300 bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors text-sm font-mono font-medium text-gray-900 dir-ltr shadow-sm"
                            placeholder="192.168.1.1">
                    </div>
                </div>
            </div>

            <!-- Section: Lifecycle -->
            <div class="space-y-4">
                <h2 class="text-base font-bold text-gray-900 border-b border-gray-100 pb-2 flex items-center gap-2" style="font-family: 'Cairo', sans-serif;">
                    <span class="w-1 h-5 bg-indigo-500 rounded-full"></span>
                    الحالة والتشغيل
                </h2>

                <div class="space-y-2">
                    <label class="block text-sm font-bold text-gray-700">الحالة التشغيلية</label>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Online Option -->
                        <label class="relative flex items-center p-3 cursor-pointer rounded-lg border transition-all {{ $internetSource->status == 'online' ? 'bg-emerald-50 border-emerald-500 ring-1 ring-emerald-500' : 'bg-white border-gray-300 hover:border-gray-400' }}">
                            <input type="radio" name="status" value="online" class="hidden" {{ $internetSource->status == 'online' ? 'checked' : '' }}>
                            <div class="flex items-center gap-3">
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                                <span class="font-bold text-sm text-gray-900" style="font-family: 'Cairo', sans-serif;">متصل (Online)</span>
                            </div>
                        </label>

                        <!-- Offline Option -->
                        <label class="relative flex items-center p-3 cursor-pointer rounded-lg border transition-all {{ $internetSource->status == 'offline' ? 'bg-rose-50 border-rose-500 ring-1 ring-rose-500' : 'bg-white border-gray-300 hover:border-gray-400' }}">
                            <input type="radio" name="status" value="offline" class="hidden" {{ $internetSource->status == 'offline' ? 'checked' : '' }}>
                            <div class="flex items-center gap-3">
                                <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                                <span class="font-bold text-sm text-gray-900" style="font-family: 'Cairo', sans-serif;">منقطع (Offline)</span>
                            </div>
                        </label>

                        <!-- Maintenance Option -->
                        <label class="relative flex items-center p-3 cursor-pointer rounded-lg border transition-all {{ $internetSource->status == 'maintenance' ? 'bg-orange-50 border-orange-500 ring-1 ring-orange-500' : 'bg-white border-gray-300 hover:border-gray-400' }}">
                            <input type="radio" name="status" value="maintenance" class="hidden" {{ $internetSource->status == 'maintenance' ? 'checked' : '' }}>
                            <div class="flex items-center gap-3">
                                <span class="w-2.5 h-2.5 rounded-full bg-orange-500"></span>
                                <span class="font-bold text-sm text-gray-900" style="font-family: 'Cairo', sans-serif;">صيانة (Maintenance)</span>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-8 flex items-center justify-end gap-3 pt-6 border-t border-gray-100">
                <button type="button" @click="window.history.back()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                    إلغاء
                </button>
                <button type="submit" class="px-6 py-2 text-sm font-bold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-sm transition-colors">
                    حفظ التعديلات
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Simple logic to handle radio visual feedback
    document.querySelectorAll('input[name="status"]').forEach(radio => {
        radio.addEventListener('change', function() {
            document.querySelectorAll('input[name="status"]').forEach(r => {
                const label = r.closest('label');
                // Remove active classes
                label.classList.remove('bg-emerald-50', 'border-emerald-500', 'ring-1', 'ring-emerald-500');
                label.classList.remove('bg-rose-50', 'border-rose-500', 'ring-1', 'ring-rose-500');
                label.classList.remove('bg-orange-50', 'border-orange-500', 'ring-1', 'ring-orange-500');
                
                // Add default classes
                label.classList.add('bg-white', 'border-gray-200');
            });
            
            const label = this.closest('label');
            label.classList.remove('bg-white', 'border-gray-200');
            
            if (this.value === 'online') label.classList.add('bg-emerald-50', 'border-emerald-500', 'ring-1', 'ring-emerald-500');
            if (this.value === 'offline') label.classList.add('bg-rose-50', 'border-rose-500', 'ring-1', 'ring-rose-500');
            if (this.value === 'maintenance') label.classList.add('bg-orange-50', 'border-orange-500', 'ring-1', 'ring-orange-500');
        });
    });
</script>
@endsection

```

## resources/views/network\internet_sources\index.blade.php

```php
@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight" style="font-family: 'Cairo', sans-serif;">مصادر الإنترنت</h1>
            <p class="text-sm text-gray-500 mt-1" style="font-family: 'Cairo', sans-serif;">إدارة خطوط الإنترنت المغذية للشبكة (Fiber, Starlink)</p>
        </div>
        <button @click="$dispatch('open-modal', 'create-source-modal')" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-lg shadow-sm transition-all focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2" style="font-family: 'Cairo', sans-serif;">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            إضافة مصدر
        </button>
    </div>

    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg flex items-center gap-3" role="alert">
        <svg class="w-5 h-5 flex-shrink-0 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span class="font-medium text-sm">{{ session('success') }}</span>
    </div>
    @endif

    <!-- Sources Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($sources as $source)
        <div class="group bg-white rounded-lg border border-gray-300 shadow-sm hover:border-indigo-400 hover:shadow-md transition-all duration-200">
            <div class="p-5">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex items-center gap-3">
                        <div class="flex-shrink-0 p-2 rounded-md border border-gray-100 {{ $source->status === 'online' ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }}">
                            @if(strtolower($source->type) === 'starlink')
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg>
                            @elseif(strtolower($source->type) === 'fiber')
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg> 
                            @else
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            @endif
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-gray-900 leading-tight" style="font-family: 'Cairo', sans-serif;">{{ $source->name }}</h3>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-xs font-bold text-gray-600 bg-gray-100 border border-gray-200 px-2 py-0.5 rounded">{{ $source->type }}</span>
                                @if($source->connection_type)
                                    <span class="text-xs font-bold text-gray-600 bg-gray-100 border border-gray-200 px-2 py-0.5 rounded">{{ $source->connection_type }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                     <!-- Status Badge -->
                    @if($source->status === 'online')
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            متصل
                        </span>
                    @elseif($source->status === 'maintenance')
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-orange-50 text-orange-700 border border-orange-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span>
                            صيانة
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                            منقطع
                        </span>
                    @endif
                </div>

                <div class="grid grid-cols-2 gap-4 mt-4 py-3 border-t border-gray-100">
                    <div>
                        <span class="block text-xs font-bold text-gray-500 mb-0.5">السعة</span>
                        <span class="font-mono font-bold text-gray-800 text-sm">{{ $source->capacity ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-bold text-gray-500 mb-0.5">Gateway IP</span>
                        <span class="font-mono font-bold text-gray-800 dir-ltr text-sm">{{ $source->ip_gateway ?? '-' }}</span>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="mt-4 flex items-center justify-end gap-2">
                    <a href="{{ route('network.internet-sources.edit', $source->id) }}" class="text-gray-700 hover:text-indigo-700 text-sm font-bold px-3 py-1.5 rounded-md hover:bg-gray-100 transition-colors border border-transparent hover:border-gray-200">
                        تعديل
                    </a>
                    <form action="{{ route('network.internet-sources.destroy', $source->id) }}" method="POST" class="inline-block" onsubmit="return confirm('هل أنت متأكد من حذف هذا المصدر؟');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-rose-600 hover:text-rose-700 text-sm font-bold px-3 py-1.5 rounded-md hover:bg-rose-50 transition-colors border border-transparent hover:border-rose-100">
                            حذف
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach

        @if($sources->isEmpty())
        <div class="col-span-full">
            <div class="bg-white rounded-lg border border-gray-300 border-dashed p-12 text-center">
                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-gray-200">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-1">لا توجد مصادر إنترنت</h3>
                <p class="text-gray-500 text-sm mb-6">قم بإضافة مصدر الإنترنت الرئيسي للبدء.</p>
                <button @click="$dispatch('open-modal', 'create-source-modal')" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg shadow-sm transition-colors ring-2 ring-indigo-600 ring-offset-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    إضافة مصدر
                </button>
            </div>
        </div>
        @endif
    </div>

    <!-- Create Modal -->
    <x-modal name="create-source-modal" title="إضافة مصدر إنترنت جديد">
        <form action="{{ route('network.internet-sources.store') }}" method="POST" class="p-6">
            @csrf
            
            <div class="space-y-5">
                <!-- Source Name -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">اسم المصدر</label>
                    <input type="text" name="name" 
                           placeholder="مثال: الخط الرئيسي Fiber 1" 
                           required 
                           class="w-full px-4 py-2.5 rounded-lg border border-gray-300 bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors text-sm font-medium placeholder-gray-400 shadow-sm">
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Type Selection -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">النوع</label>
                        <div class="relative">
                            <select name="type" required class="w-full px-4 py-2.5 rounded-lg border border-gray-300 bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors text-sm font-medium text-gray-700 shadow-sm appearance-none">
                                <option value="fiber">🌐 Fiber Optic</option>
                                <option value="starlink">📡 Starlink</option>
                                <option value="microwave">📶 Microwave Link</option>
                                <option value="4g">📱 4G / LTE</option>
                                <option value="other">🔌 Other</option>
                            </select>
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Connection Type Selection -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">نوع الوصل</label>
                        <div class="relative">
                            <select name="connection_type" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors text-sm font-medium text-gray-700 shadow-sm appearance-none">
                                <option value="">غير محدد</option>
                                <option value="fiber">فايبر (Fiber)</option>
                                <option value="cable">كابل (Cable)</option>
                                <option value="wireless">لاسلكي (Wireless)</option>
                            </select>
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Status Selection -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">الحالة</label>
                        <div class="relative">
                            <select name="status" required class="w-full px-4 py-2.5 rounded-lg border border-gray-300 bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors text-sm font-medium text-gray-700 shadow-sm appearance-none">
                                <option value="online">✅ متصل (Online)</option>
                                <option value="offline">❌ منقطع (Offline)</option>
                                <option value="maintenance">🛠️ صيانة (Maintenance)</option>
                            </select>
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Capacity -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">السعة (Capacity)</label>
                        <input type="text" name="capacity" placeholder="100 Mbps" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors text-sm font-medium dir-ltr shadow-sm">
                    </div>
                </div>

                <!-- Gateway IP -->
                <div>
                   <label class="block text-sm font-bold text-gray-700 mb-1">Gateway IP</label>
                   <input type="text" name="ip_gateway" placeholder="192.168.1.1" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors text-sm font-mono font-medium dir-ltr shadow-sm">
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-8 flex justify-end gap-3 pt-5 border-t border-gray-100">
                <button type="button" @click="$dispatch('close-modal', 'create-source-modal')" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                    إلغاء
                </button>
                <button type="submit" class="px-4 py-2 text-sm font-bold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-sm transition-colors">
                    حفظ المصدر
                </button>
            </div>
        </form>
    </x-modal>

</div>
@endsection

```

## resources/views/network\live-monitoring.blade.php

```php
@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="glass-header rounded-2xl p-6 flex flex-col md:flex-row md:items-center justify-between gap-4 border border-indigo-100/50 shadow-sm relative overflow-hidden">
        
        <!-- Background Decor -->
        <div class="absolute top-0 right-0 w-64 h-64 bg-indigo-50/50 rounded-full blur-3xl -z-10 transform translate-x-1/2 -translate-y-1/2"></div>

        <div>
            <h2 class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-indigo-700 to-blue-700">المراقبة الحية (Live Monitoring)</h2>
            <p class="text-gray-500 mt-1">مراقبة أداء الشبكة وأجهزة التوجيه في الوقت الفعلي</p>
        </div>
        <div class="flex items-center gap-2 px-4 py-2 bg-emerald-50 text-emerald-700 rounded-xl border border-emerald-100 shadow-sm">
            <span class="relative flex h-3 w-3">
              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
              <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
            </span>
            <span class="text-sm font-bold">تحديث تلقائي</span>
        </div>
    </div>

    <!-- Live Stats Grid -->
    <div id="live-stats" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <!-- Stats will be loaded here via JavaScript -->
        
        <!-- Loading Skeleton (Initial State) -->
        <div class="col-span-full py-20 flex flex-col items-center justify-center text-gray-400 space-y-4">
            <div class="relative">
                <div class="w-16 h-16 border-4 border-indigo-100 border-t-indigo-500 rounded-full animate-spin"></div>
                <div class="absolute inset-0 flex items-center justify-center">
                    <svg class="w-6 h-6 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
            </div>
            <p class="font-medium text-gray-500 animate-pulse">جاري الاتصال بالأجهزة...</p>
        </div>
    </div>
</div>

<script>
function loadStats() {
    fetch('{{ route("api.network.realtime") }}')
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('live-stats');
            
            if(data.length === 0) {
                 container.innerHTML = `
                    <div class="col-span-full py-12 text-center bg-white/50 rounded-3xl border border-dashed border-gray-200">
                        <div class="inline-flex w-20 h-20 bg-indigo-50 rounded-full items-center justify-center mb-4 shadow-inner">
                            <svg class="w-10 h-10 text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/></svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800">لا توجد أجهزة متصلة</h3>
                        <p class="text-gray-500">تأكد من إعدادات الـ API في الراوترات</p>
                    </div>
                `;
                return;
            }

            container.innerHTML = '';
            
            data.forEach(router => {
                // Determine CSS based on Load
                let cpuColor = 'bg-blue-600';
                if(router.cpu_load > 80) cpuColor = 'bg-rose-500 shadow-[0_0_10px_rgba(244,63,94,0.4)]';
                else if(router.cpu_load > 50) cpuColor = 'bg-amber-500';

                let memColor = 'bg-emerald-500';
                if(router.memory_usage > 80) memColor = 'bg-rose-500 shadow-[0_0_10px_rgba(244,63,94,0.4)]';
                
                const card = `
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-indigo-50 hover:shadow-xl hover:border-indigo-100 transition-all duration-300 hover:-translate-y-1 group relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-indigo-50 to-transparent rounded-bl-full -z-10 transition-transform group-hover:scale-150 duration-500"></div>

                        <div class="flex items-start justify-between mb-6">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-500 to-blue-600 flex items-center justify-center text-white shadow-lg shadow-indigo-200 group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/></svg>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-900 leading-tight group-hover:text-indigo-700 transition-colors">${router.router_name}</h3>
                                    <p class="text-xs text-slate-500 font-mono mt-0.5 dir-ltr text-right bg-slate-100 px-2 py-0.5 rounded inline-block">${router.ip_address || '192.168.x.x'}</p>
                                </div>
                            </div>
                            <span class="inline-flex items-center px-2 py-1 rounded-lg ${router.cpu_load > 80 ? 'bg-rose-50 text-rose-700 border-rose-100' : 'bg-emerald-50 text-emerald-700 border-emerald-100'} text-xs font-bold border">
                                ${router.cpu_load}% CPU
                            </span>
                        </div>

                        <div class="space-y-5">
                            <!-- CPU -->
                            <div>
                                <div class="flex justify-between text-xs mb-1.5 font-bold text-gray-500">
                                    <span>المعالج (CPU)</span>
                                    <span class="${router.cpu_load > 80 ? 'text-rose-600' : 'text-gray-700'}">${router.cpu_load}%</span>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden shadow-inner">
                                    <div class="${cpuColor} h-2.5 rounded-full transition-all duration-1000 ease-out relative overflow-hidden" style="width: ${router.cpu_load}%">
                                         <div class="absolute top-0 left-0 bottom-0 right-0 bg-white/30 animate-pulse"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Memory -->
                            <div>
                                <div class="flex justify-between text-xs mb-1.5 font-bold text-gray-500">
                                    <span>الذاكرة (RAM)</span>
                                    <span class="${router.memory_usage > 80 ? 'text-rose-600' : 'text-gray-700'}">${router.memory_usage}%</span>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden shadow-inner">
                                     <div class="${memColor} h-2.5 rounded-full transition-all duration-1000 ease-out relative" style="width: ${router.memory_usage}%"></div>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-3 pt-2">
                                <div class="bg-indigo-50/50 rounded-xl p-3 border border-indigo-100 text-center group-hover:bg-indigo-50 transition-colors">
                                    <span class="block text-xs text-indigo-400 mb-1 font-semibold">النشطة</span>
                                    <span class="font-bold text-indigo-900 text-lg">${router.active_sessions}</span>
                                </div>
                                <div class="bg-slate-50 rounded-xl p-3 border border-slate-100 text-center">
                                    <span class="block text-xs text-slate-400 mb-1 font-semibold">وقت التشغيل</span>
                                    <span class="font-bold text-slate-800 dir-ltr text-xs text-nowrap font-mono">${router.uptime}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                container.innerHTML += card;
            });
        })
        .catch(error => {
            console.error('Error loading stats:', error);
        });
}

// Load stats immediately then every 5 seconds
loadStats();
setInterval(loadStats, 5000);
</script>
@endsection

```

## resources/views/network\monitoring\bandwidth.blade.php

```php
@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">استهلاك Bandwidth</h2>
            <p class="text-gray-500 mt-1">رسوم بيانية لاستهلاك الشبكة عبر الوقت</p>
        </div>
        
        <!-- Selector Area -->
        <div class="flex items-center gap-4">
            <!-- Interface Selector -->
            <form action="" method="GET" class="flex items-center gap-2">
                <input type="hidden" name="period" value="{{ $period }}">
                <select name="interface" onchange="this.form.submit()" class="bg-white border border-gray-300 text-gray-700 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block p-2.5">
                    <option value="">جميع الواجهات</option>
                    @foreach($availableInterfaces as $iface)
                        <option value="{{ $iface }}" {{ $interface === $iface ? 'selected' : '' }}>{{ $iface }}</option>
                    @endforeach
                </select>
            </form>

            <!-- Period Selector -->
            <div class="flex gap-2">
                <a href="?period=24h&interface={{ $interface }}" class="px-4 py-2 {{ $period === '24h' ? 'bg-purple-600 text-white' : 'bg-gray-100 text-gray-700' }} rounded-lg font-semibold transition hover:opacity-80">
                    24 ساعة
                </a>
                <a href="?period=7d&interface={{ $interface }}" class="px-4 py-2 {{ $period === '7d' ? 'bg-purple-600 text-white' : 'bg-gray-100 text-gray-700' }} rounded-lg font-semibold transition hover:opacity-80">
                    7 أيام
                </a>
                <a href="?period=30d&interface={{ $interface }}" class="px-4 py-2 {{ $period === '30d' ? 'bg-purple-600 text-white' : 'bg-gray-100 text-gray-700' }} rounded-lg font-semibold transition hover:opacity-80">
                    30 يوم
                </a>
            </div>
        </div>
    </div>

    <!--Chart -->
    <div class="glass rounded-2xl shadow-lg border border-white/30 p-6 relative">
        @if(empty($chartData) || count($chartData) == 0)
            <div class="absolute inset-0 flex items-center justify-center bg-white/50 z-10 rounded-2xl">
                <div class="text-center">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    <p class="text-gray-500 font-medium">لا توجد بيانات كافية لعرض الرسم البياني حالياً.</p>
                    <p class="text-xs text-gray-400 mt-1">يتم جمع البيانات كل 5 دقائق تلقائياً.</p>
                </div>
            </div>
        @endif
        <div style="height: 400px; width: 100%;">
            <canvas id="bandwidthChart"></canvas>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="glass rounded-xl p-6 shadow-lg border border-white/30">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/></svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">إجمالي Download</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format(collect($chartData)->sum('rx'), 2) }} MB</p>
                </div>
            </div>
        </div>

        <div class="glass rounded-xl p-6 shadow-lg border border-white/30">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">إجمالي Upload</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format(collect($chartData)->sum('tx'), 2) }} MB</p>
                </div>
            </div>
        </div>

        <div class="glass rounded-xl p-6 shadow-lg border border-white/30">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">إجمالي الاستهلاك</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format(collect($chartData)->sum('rx') + collect($chartData)->sum('tx'), 2) }} MB</p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('bandwidthChart');
    if (!ctx) return;

    const dataFromPHP = @json($chartData);
    const labels = Object.keys(dataFromPHP);
    const rxData = Object.values(dataFromPHP).map(d => d.rx);
    const txData = Object.values(dataFromPHP).map(d => d.tx);

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'تحميل (Download - MB)',
                    data: rxData,
                    borderColor: '#0ea5e9',
                    backgroundColor: 'rgba(14, 165, 233, 0.1)',
                    borderWidth: 3,
                    pointRadius: 4,
                    pointBackgroundColor: '#0ea5e9',
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'رفع (Upload - MB)',
                    data: txData,
                    borderColor: '#8b5cf6',
                    backgroundColor: 'rgba(139, 92, 246, 0.1)',
                    borderWidth: 3,
                    pointRadius: 4,
                    pointBackgroundColor: '#8b5cf6',
                    tension: 0.4,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                intersect: false,
                mode: 'index',
            },
            plugins: {
                legend: {
                    position: 'top',
                    rtl: true,
                    labels: {
                        font: {
                            family: 'Rubik'
                        }
                    }
                },
                tooltip: {
                    rtl: true,
                    titleFont: { family: 'Rubik' },
                    bodyFont: { family: 'Rubik' }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    },
                    ticks: {
                        font: { family: 'Rubik' },
                        callback: function(value) {
                            return value + ' MB';
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: { family: 'Rubik' }
                    }
                }
            }
        }
    });
});
</script>
@endpush
@endsection

```

## resources/views/network\monitoring\index.blade.php

```php
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

```

## resources/views/network\queues\index.blade.php

```php
@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">⚡ إدارة السرعات (Queue Management)</h2>
            <p class="text-gray-500 mt-1">التحكم بسرعات المستخدمين عبر جميع الراوترات</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gradient-to-r from-blue-50 to-blue-100 border-b border-blue-200">
                    <tr>
                        <th class="px-4 py-3 text-right font-bold text-gray-700">الاسم</th>
                        <th class="px-4 py-3 text-right font-bold text-gray-700">الراوتر</th>
                        <th class="px-4 py-3 text-right font-bold text-gray-700">السرعة الحالية</th>
                        <th class="px-4 py-3 text-right font-bold text-gray-700">Burst</th>
                        <th class="px-4 py-3 text-right font-bold text-gray-700">إجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($allQueues as $queue)
                    <tr class="hover:bg-blue-50 transition">
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $queue['name'] ?? 'N/A' }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $queue['router_name'] }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs font-medium">
                                {{ $queue['max-limit'] ?? 'N/A' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 bg-purple-100 text-purple-700 rounded text-xs font-medium">
                                {{ $queue['burst-limit'] ?? 'N/A' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <form action="{{ route('network.queues.set-speed') }}" method="POST" class="flex items-center gap-2">
                                @csrf
                                <input type="hidden" name="router_id" value="{{ $queue['router_id'] }}">
                                <input type="hidden" name="router_type" value="{{ $queue['router_type'] }}">
                                <input type="hidden" name="username" value="{{ $queue['name'] ?? '' }}">
                                
                                <input type="text" name="download_speed" placeholder="DL" class="w-16 px-2 py-1 text-xs border rounded" value="{{ explode('/', $queue['max-limit'] ?? '/')[1] ?? '' }}">
                                <input type="text" name="upload_speed" placeholder="UL" class="w-16 px-2 py-1 text-xs border rounded" value="{{ explode('/', $queue['max-limit'] ?? '/')[0] ?? '' }}">
                                
                                <button type="submit" class="p-1 text-blue-600 hover:bg-blue-100 rounded transition" title="تحديث السرعة">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                            لا توجد queues محددة حالياً
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

```

## resources/views/network\sessions\index.blade.php

```php
@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">👥 الجلسات النشطة (Active Sessions)</h2>
            <p class="text-gray-500 mt-1">عرض وإدارة جميع الاتصالات الحالية</p>
        </div>
        <div class="px-4 py-2 bg-green-100 text-green-700 rounded-lg font-bold">
            {{ count($allSessions) }} جلسة نشطة
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gradient-to-r from-purple-50 to-purple-100 border-b border-purple-200">
                    <tr>
                        <th class="px-4 py-3 text-right font-bold text-gray-700">المستخدم</th>
                        <th class="px-4 py-3 text-right font-bold text-gray-700">النوع</th>
                        <th class="px-4 py-3 text-right font-bold text-gray-700">IP Address</th>
                        <th class="px-4 py-3 text-right font-bold text-gray-700">Uptime</th>
                        <th class="px-4 py-3 text-right font-bold text-gray-700">الراوتر</th>
                        <th class="px-4 py-3 text-right font-bold text-gray-700">إجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($allSessions as $session)
                    <tr class="hover:bg-purple-50 transition">
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $session['name'] ?? $session['user'] ?? 'N/A' }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 {{ $session['type'] === 'pppoe' ? 'bg-blue-100 text-blue-700' : 'bg-orange-100 text-orange-700' }} rounded text-xs font-medium uppercase">
                                {{ $session['type'] }}
                            </span>
                        </td>
                        <td class="px-4 py-3 font-mono text-xs text-gray-600">{{ $session['address'] ?? 'N/A' }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $session['uptime'] ?? '0' }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $session['router_name'] }}</td>
                        <td class="px-4 py-3">
                            <form action="{{ route('network.sessions.disconnect') }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="router_id" value="{{ $session['router_id'] }}">
                                <input type="hidden" name="router_type" value="{{ $session['router_type'] }}">
                                <input type="hidden" name="session_id" value="{{ $session['.id'] }}">
                                <input type="hidden" name="type" value="{{ $session['type'] }}">
                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium" 
                                        onclick="return confirm('هل أنت متأكد من قطع الاتصال؟')">
                                    قطع الاتصال
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                            لا توجد جلسات نشطة حالياً
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

```

## resources/views/network\topology\index.blade.php

```php
@extends('layouts.app')

@section('content')
<div class="space-y-6" 
     x-data="{ 
        selectedDevice: null
     }"
     @device-selected.window="selectedDevice = $event.detail">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">خريطة الشبكة التفاعلية</h2>
            <p class="text-gray-500 mt-1">عرض تفاعلي لبنية الشبكة والأجهزة المتصلة</p>
        </div>
    </div>

    <!-- Legend -->
    <div class="glass rounded-xl p-4 shadow-lg border border-white/30 flex gap-6 items-center">
        <span class="text-sm font-semibold text-gray-700">الحالة:</span>
        <div class="flex gap-4">
            <div class="flex items-center gap-2">
                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                <span class="text-sm text-gray-600">متصل</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                <span class="text-sm text-gray-600">معطل</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-3 h-3 bg-orange-500 rounded-full"></div>
                <span class="text-sm text-gray-600">بطيء</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-3 h-3 bg-gray-400 rounded-full"></div>
                <span class="text-sm text-gray-600">غير محدد</span>
            </div>
        </div>
        
        <span class="text-sm font-semibold text-gray-700 mr-6">الأجهزة:</span>
        <div class="flex gap-4">
            <div class="flex items-center gap-2">
                <div class="w-6 h-6 bg-blue-100 rounded flex items-center justify-center">
                    <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path d="M2 5a2 2 0 012-2h12a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2V5zm3.293 1.293a1 1 0 011.414 0l3 3a1 1 0 010 1.414l-3 3a1 1 0 01-1.414-1.414L7.586 10 5.293 7.707a1 1 0 010-1.414zM11 12a1 1 0 100 2h3a1 1 0 100-2h-3z"/></svg>
                </div>
                <span class="text-sm text-gray-600">سيرفر</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-6 h-6 bg-purple-100 rounded flex items-center justify-center">
                    <svg class="w-4 h-4 text-purple-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7 2a2 2 0 00-2 2v12a2 2 0 002 2h6a2 2 0 002-2V4a2 2 0 00-2-2H7zm3 14a1 1 0 100-2 1 1 0 000 2z"/></svg>
                </div>
                <span class="text-sm text-gray-600">راوتر</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-6 h-6 bg-green-100 rounded flex items-center justify-center">
                    <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a.75.75 0 01.75.75v1.5a.75.75 0 01-1.5 0v-1.5A.75.75 0 0110 2zM10 15a.75.75 0 01.75.75v1.5a.75.75 0 01-1.5 0v-1.5A.75.75 0 0110 15zM10 7a3 3 0 100 6 3 3 0 000-6zM15.657 5.404a.75.75 0 10-1.06-1.06l-1.061 1.06a.75.75 0 001.06 1.06l1.06-1.06zM6.464 14.596a.75.75 0 10-1.06-1.06l-1.06 1.06a.75.75 0 001.06 1.06l1.06-1.06zM18 10a.75.75 0 01-.75.75h-1.5a.75.75 0 010-1.5h1.5A.75.75 0 0118 10zM5 10a.75.75 0 01-.75.75h-1.5a.75.75 0 010-1.5h1.5A.75.75 0 015 10zM14.596 15.657a.75.75 0 001.06-1.06l-1.06-1.061a.75.75 0 10-1.06 1.06l1.06 1.06zM5.404 6.464a.75.75 0 001.06-1.06l-1.06-1.06a.75.75 0 10-1.06 1.06l1.06 1.06z"/></svg>
                </div>
                <span class="text-sm text-gray-600">برج</span>
            </div>
        </div>
    </div>

    <!-- Topology Map Container -->
    <div class="rounded-2xl shadow-xl border border-gray-200 overflow-hidden relative" style="height: 800px; background: #f8fafc;">
        <!-- Light Gradient Overlay -->
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,_transparent_0%,_#f1f5f9_100%)] pointer-events-none"></div>

        <!-- NOC Header Overlay -->
        <div class="absolute top-0 left-0 right-0 p-4 z-10 flex justify-between items-start pointer-events-none">
            <div>
                <h3 class="text-gray-500 font-mono text-xs tracking-widest uppercase opacity-70">Network Operations Center</h3>
                <h1 class="text-gray-800 font-bold text-lg tracking-tight">Live Topology Map</h1>
            </div>
            <div class="flex flex-col items-end">
                <div class="flex items-center gap-2 mb-1">
                    <span class="relative flex h-2 w-2">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                    </span>
                    <span class="text-green-600 font-mono text-xs">SYSTEM ONLINE</span>
                </div>
                <span class="text-gray-400 font-mono text-[10px]">{{ now()->format('H:i:s UTC') }}</span>
            </div>
        </div>

        <!-- Toolbar (White Glass) -->
        <div class="absolute bottom-6 right-6 z-10 flex flex-col gap-2 bg-white/90 backdrop-blur-md rounded-lg shadow-xl border border-gray-200 p-2">
            <button onclick="network.fit()" class="p-2 hover:bg-gray-100 rounded text-gray-500 hover:text-blue-600 transition-colors" title="Fit All">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
            </button>
            <button onclick="network.moveTo({scale: 1.5})" class="p-2 hover:bg-gray-100 rounded text-gray-500 hover:text-blue-600 transition-colors" title="Zoom In">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            </button>
            <button onclick="network.moveTo({scale: 0.5})" class="p-2 hover:bg-gray-100 rounded text-gray-500 hover:text-blue-600 transition-colors" title="Zoom Out">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
            </button>
        </div>

        <div id="mynetwork" class="w-full h-full z-0"></div>
        
        <!-- Loading Indicator -->
        <div id="loading" class="absolute inset-0 flex items-center justify-center bg-slate-50 z-50">
            <div class="flex flex-col items-center">
                <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500 mb-4 shadow-lg"></div>
                <span class="text-blue-600 font-mono text-sm tracking-widest animate-pulse">INITIALIZING VISUALIZATION...</span>
            </div>
        </div>
    </div>

    <!-- Details Modal (Alpine) -->
    <div x-show="selectedDevice" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/20 backdrop-blur-sm" @click.self="selectedDevice = null">
        <div class="bg-white rounded-2xl p-6 max-w-sm w-full mx-4 shadow-2xl border border-gray-100 transform transition-all"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">
            
            <div class="text-center">
                <div class="w-24 h-24 mx-auto rounded-lg bg-gray-50 border border-gray-200 flex items-center justify-center mb-4 p-2">
                    <img :src="selectedDevice?.image" class="max-w-full max-h-full object-contain" alt="Device">
                </div>
                <h3 class="text-xl font-bold text-gray-900" x-text="selectedDevice?.label"></h3>
                <p class="text-sm text-gray-500 mb-6" x-text="selectedDevice?.group === 'server' ? 'سيرفر رئيسي' : (selectedDevice?.group === 'router' ? 'راوتر توزيع' : 'برج تغطية')"></p>
                
                <div class="bg-gray-50 rounded-xl p-4 mb-6 text-right">
                    <div class="flex justify-between mb-2">
                        <span class="text-gray-500 text-sm">الحالة</span>
                        <span class="font-bold text-sm" 
                              :class="selectedDevice?.status === 'online' ? 'text-green-600' : 'text-red-600'" 
                              x-text="selectedDevice?.status === 'online' ? 'متصل ✅' : 'منقطع ❌'"></span>
                    </div>
                </div>

                <button @click="selectedDevice = null" class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-semibold shadow-md transition">
                    إغلاق
                </button>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/vis-network/9.1.2/dist/dist/vis-network.min.css" integrity="sha512-WgxfT5LWjfszlPHXRmBWHkV2PHnB7QYxuTqpD2n33d3FE6+Q5yQ/+mrqbPY+UdDIDyvUb8UTU1Esn31rIwx+EQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<style>
    #mynetwork { outline: none; }
</style>
@endpush

@push('scripts')
<!-- Switched to unpkg standalone build for better compatibility -->
<script type="text/javascript" src="https://unpkg.com/vis-network/standalone/umd/vis-network.min.js"></script>
<script>
    var network;

    // Failsafe: Force hide loading after 5 seconds
    setTimeout(function() {
        var loader = document.getElementById('loading');
        if(loader) loader.style.display = 'none';
    }, 5000);

    // Fallback Icon Generator (if no image)
    const getFallbackIcon = (type, color) => {
         const svgs = {
            cloud: `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="${color}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.5 19c0-1.7-1.3-3-3-3h-1.1c-.8-3.4-3.9-6-7.6-6-4.1 0-7.4 3.4-7.4 7.5s3.3 7.5 7.5 7.5c1.4 0 2.7-.4 3.9-1.1"/><path d="M12 12v4"/><path d="M12 12l2.3 2.3"/><path d="M12 12l-2.3 2.3"/></svg>`,
            tower: `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="${color}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2L2 22h20L12 2z"/><path d="M12 6l-3.5 7h7L12 6z"/><path d="M12 16a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"/></svg>`
        };
        const svg = svgs[type] || svgs.cloud;
        return 'data:image/svg+xml;charset=utf-8,' + encodeURIComponent(svg);
    };

    document.addEventListener('DOMContentLoaded', function() {
        try {
            var nodes = [];
            var edges = [];
            
            // Defines
            // Placeholder images if actual images are missing
            const IMG_ROUTER_DEFAULT = 'https://cdn-icons-png.flaticon.com/512/9637/9637409.png';
            const IMG_TOWER = 'https://cdn-icons-png.flaticon.com/512/2942/2942544.png'; // Tower Icon
            const IMG_SERVER = '/images/devices/mikrotik_ccr1009.png';
            const IMG_CLOUD = 'https://cdn-icons-png.flaticon.com/512/4138/4138124.png'; // Internet

            // 1. Internet Sources
            var internetSources = @json($internetSources) || [];
            if (internetSources.length === 0) {
                 nodes.push({ 
                    id: 'internet', 
                    label: 'Internet', 
                    group: 'cloud', 
                    image: IMG_CLOUD, 
                    shape: 'image', 
                    size: 40
                });
            } else {
                internetSources.forEach(function(source) {
                    nodes.push({ 
                        id: 'is_' + source.id, 
                        label: source.name, 
                        group: 'cloud', 
                        image: IMG_CLOUD,
                        shape: 'image', 
                        size: 40
                    });
                });
            }

            // 2. Servers
            var servers = @json($servers) || [];
            servers.forEach(function(server) {
                nodes.push({ 
                    id: 's_' + server.id, 
                    label: server.name, 
                    group: 'server', 
                    image: IMG_SERVER,
                    shape: 'image',
                    size: 45,
                    font: { color: '#334155', face: 'Rubik', size: 12, strokeWidth: 3, strokeColor: '#ffffff' }
                });
                
                // Edges
                if (internetSources.length > 0) {
                     internetSources.forEach(function(source) {
                        edges.push({ from: 'is_' + source.id, to: 's_' + server.id, dashes: [5, 5], color: { color: '#94a3b8' } });
                     });
                } else {
                     edges.push({ from: 'internet', to: 's_' + server.id, dashes: [5, 5], color: { color: '#ef4444' } });
                }
            });

            // 3. Routers
            var routers = @json($routers) || [];
            routers.forEach(function(router, index) {
                // Use backend image_url or fallback
                let img = router.image_url ? router.image_url : IMG_ROUTER_DEFAULT;
                
                nodes.push({ 
                    id: 'r_' + router.id, 
                    label: router.name, 
                    group: 'router', 
                    image: img,
                    shape: 'image',
                    size: 35,
                    font: { color: '#475569', face: 'Rubik', size: 11, strokeWidth: 3, strokeColor: '#ffffff' }
                });
                
                if (servers.length > 0) {
                    var serverId = servers[index % servers.length].id;
                    edges.push({ from: 's_' + serverId, to: 'r_' + router.id, color: { color: '#cbd5e1' } });
                }
            });

            // 4. Towers
            var towers = @json($towers) || [];
            towers.forEach(function(tower) {
                nodes.push({ 
                    id: 't_' + tower.id, 
                    label: tower.name, 
                    group: 'tower', 
                    image: IMG_TOWER,
                    shape: 'image',
                    size: 30,
                    font: { color: '#64748b', face: 'Rubik', size: 10, strokeWidth: 3, strokeColor: '#ffffff' }
                });

                if (tower.router_id) {
                    edges.push({ from: 'r_' + tower.router_id, to: 't_' + tower.id, color: { color: '#cbd5e1' } });
                } else if (routers.length > 0) {
                    edges.push({ from: 'r_' + routers[0].id, to: 't_' + tower.id, dashes: [2, 2], color: { color: '#94a3b8' } });
                }
            });

            // Config for Light Mode
            var container = document.getElementById('mynetwork');
            var data = { nodes: nodes, edges: edges };
            var options = {
                nodes: {
                    borderWidth: 0,
                    shadow: {
                        enabled: true,
                        color: 'rgba(0,0,0,0.1)',
                        size: 10,
                        x: 2,
                        y: 2
                    }
                },
                edges: {
                    width: 1,
                    shadow: false,
                    smooth: {
                        type: 'cubicBezier',
                        forceDirection: 'vertical',
                        roundness: 0.5
                    },
                    color: {
                        color: '#cbd5e1',
                        highlight: '#3b82f6',
                        opacity: 1.0
                    }
                },
                layout: {
                    hierarchical: {
                        enabled: true,
                        direction: 'UD',
                        sortMethod: 'directed',
                        nodeSpacing: 200,
                        levelSeparation: 200,
                        blockShifting: true,
                        edgeMinimization: true
                    }
                },
                physics: {
                     hierarchicalRepulsion: {
                        centralGravity: 0.0,
                        springLength: 250,
                        springConstant: 0.01,
                        nodeDistance: 250,
                        damping: 0.09
                    },
                    solver: 'hierarchicalRepulsion'
                },
                interaction: {
                    hover: true,
                    tooltipDelay: 100,
                    zoomView: true,
                    dragView: true
                }
            };

            network = new vis.Network(container, data, options);

            // Hide loading
            network.on("stabilizationIterationsDone", function() {
                 document.getElementById('loading').style.display = 'none';
            });
            network.on("afterDrawing", function() {
                 document.getElementById('loading').style.display = 'none';
                 network.off("afterDrawing");
            });

            // Click Events
            network.on("click", function(params) {
                if (params.nodes.length > 0) {
                    var nodeId = params.nodes[0];
                    var node = nodes.find(n => n.id === nodeId);
                    if (node && node.id !== 'internet') {
                        // Safe way to update Alpine state
                        var el = document.querySelector('[x-data]');
                        if (el && el.__x_data_stack__) {
                            // High-level access if possible, otherwise we use standard dispatch
                            window.dispatchEvent(new CustomEvent('device-selected', { detail: node }));
                        } else {
                            // Fallback for older Alpine or custom implementations
                            var root = document.querySelector('[x-data]');
                            if (root && root.__x) {
                                root.__x.$data.selectedDevice = node;
                            }
                        }
                    }
                }
            });

        } catch (error) {
             document.getElementById('loading').innerHTML = '<div class="text-red-500 p-4 font-bold text-center">خطأ في التحميل<br><span class="text-sm font-normal text-gray-500">' + error.message + '</span></div>';
        }
    });
</script>
@endpush
@endsection

```

## resources/views/network\towers\create.blade.php

```php
@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #map { height: 450px; width: 100%; z-index: 1; filter: grayscale(0.2) contrast(1.1); }
    .glass-map-frame { position: relative; border-radius: 2.5rem; overflow: hidden; border: 1px solid rgba(255, 255, 255, 0.4); box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15); }
    .coordinates-badge { position: absolute; bottom: 2rem; right: 2rem; z-index: 1000; background: rgba(255, 255, 255, 0.8); backdrop-blur: 12px; border: 1px solid rgba(255, 255, 255, 0.5); border-radius: 1.5rem; padding: 1rem 1.5rem; display: flex; gap: 1.5rem; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1); }
</style>
@endpush

@section('content')
<div class="max-w-6xl mx-auto pb-24" x-data="towerForm">
    
    <!-- Navigation Header -->
    <div class="glass-panel border border-white/40 rounded-[2.5rem] p-6 mb-12 flex items-center justify-between shadow-xl relative overflow-hidden group">
        <div class="absolute -top-10 -right-10 w-32 h-32 bg-indigo-600/5 rounded-full blur-3xl group-hover:scale-150 transition-all duration-700"></div>
        <div class="flex items-center gap-6 relative">
            <div class="w-14 h-14 bg-indigo-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-indigo-100 group-hover:rotate-[-5deg] transition-all">
                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            </div>
            <div>
                <h2 class="text-3xl font-black text-gray-900 tracking-tight uppercase">Deploy <span class="text-indigo-600">Hub</span></h2>
                <div class="flex items-center gap-2 mt-1">
                    <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Ready for initialization</p>
                </div>
            </div>
        </div>
        <div class="flex gap-4 relative">
            <a href="{{ route('network.towers.index') }}" class="px-8 py-3.5 bg-white border border-gray-100 text-gray-500 font-black text-[11px] uppercase tracking-widest rounded-2xl hover:bg-gray-50 transition-all shadow-sm">
                Abort Mission
            </a>
        </div>
    </div>

    <!-- Error Messages -->
    <!-- Alert System -->
    @if($errors->any())
    <div class="glass-panel border border-rose-500/20 bg-rose-500/5 backdrop-blur-xl p-8 rounded-[2.5rem] mb-12 shadow-xl animate-shake">
        <div class="flex items-start gap-6">
            <div class="w-14 h-14 bg-rose-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-rose-200 shrink-0">
                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <div>
                <h4 class="text-lg font-black text-rose-900 uppercase tracking-tight mb-2">Protocol Disruption</h4>
                <ul class="space-y-1.5">
                    @foreach($errors->all() as $error)
                    <li class="text-sm font-bold text-rose-700/80 flex items-center gap-2">
                        <span class="w-1.5 h-1.5 bg-rose-500 rounded-full"></span>
                        {{ $error }}
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    <form action="{{ route('network.towers.store') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Basic Informatics -->
        <div class="glass-panel border border-white/40 rounded-[3rem] p-10 md:p-12 shadow-2xl relative overflow-hidden">
            <div class="absolute -top-24 -right-24 w-64 h-64 bg-indigo-600/5 rounded-full blur-3xl"></div>
            
            <div class="flex items-center gap-4 mb-12">
                <div class="w-14 h-14 bg-indigo-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-indigo-100">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div>
                    <h3 class="text-2xl font-black text-gray-900 tracking-tight uppercase">Hub Identity</h3>
                    <p class="text-[10px] font-black text-indigo-600 uppercase tracking-widest mt-1">Core site registration and classification</p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <!-- Name -->
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4 ml-1">Site Label / SSID Host</label>
                    <input type="text" name="name" required value="{{ old('name') }}"
                        class="w-full px-8 py-5 bg-white border border-gray-100 rounded-[2rem] focus:ring-4 focus:ring-indigo-100 focus:border-indigo-600 outline-none transition-all font-black text-gray-900 text-lg shadow-sm"
                        placeholder="e.g. TOWER_NORTH_CORE">
                </div>

                <!-- Type Selection -->
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-6 ml-1">Infrastructure Classification</label>
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                        @php
                            $types = [
                                'tower' => ['Tower', '🗼'],
                                'building' => ['Building', '🏢'],
                                'cabinet' => ['Cabinet', '🗄️'],
                                'pole' => ['Pole', '📍'],
                                'office' => ['Office', '🏛️']
                            ];
                        @endphp
                        @foreach($types as $value => $meta)
                        <label class="cursor-pointer group">
                            <input type="radio" name="type" value="{{ $value }}" x-model="siteType" class="peer sr-only">
                            <div class="text-center py-6 bg-white border border-gray-100 rounded-3xl peer-checked:border-indigo-600 peer-checked:bg-indigo-600 peer-checked:text-white transition-all shadow-sm group-hover:scale-105">
                                <div class="text-3xl mb-3">{{ $meta[1] }}</div>
                                <div class="text-[9px] font-black uppercase tracking-widest">{{ $meta[0] }}</div>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>

                <!-- Location Details -->
                <div class="space-y-10">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4 ml-1">City / Region</label>
                        <input type="text" name="city" value="{{ old('city') }}" class="w-full px-6 py-4 bg-white border border-gray-100 rounded-2xl focus:ring-4 focus:ring-indigo-100 focus:border-indigo-600 outline-none transition-all font-bold text-gray-900" placeholder="Dubai">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4 ml-1">Sub-District</label>
                        <input type="text" name="district" value="{{ old('district') }}" class="w-full px-6 py-4 bg-white border border-gray-100 rounded-2xl focus:ring-4 focus:ring-indigo-100 focus:border-indigo-600 outline-none transition-all font-bold text-gray-900">
                    </div>
                </div>
                
                <div class="space-y-10">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4 ml-1">Structural Height (m)</label>
                        <input type="number" name="height" value="{{ old('height') }}" class="w-full px-6 py-4 bg-white border border-gray-100 rounded-2xl focus:ring-4 focus:ring-indigo-100 focus:border-indigo-600 outline-none transition-all font-bold text-gray-900">
                    </div>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4 ml-1">Physical Access Protocol / Address</label>
                    <textarea name="location" rows="3" class="w-full px-6 py-4 bg-white border border-gray-100 rounded-[2rem] focus:ring-4 focus:ring-indigo-100 focus:border-indigo-600 outline-none transition-all font-bold text-gray-900 resize-none" placeholder="Precise directions or site entry requirements...">{{ old('location') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Geospatial Grid -->
        <div class="glass-panel border border-white/40 rounded-[3rem] p-10 md:p-12 shadow-2xl relative overflow-hidden">
            <div class="flex items-center gap-4 mb-10">
                <div class="w-14 h-14 bg-indigo-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-indigo-100">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <div>
                    <h3 class="text-2xl font-black text-gray-900 tracking-tight uppercase">Geospatial Plot</h3>
                    <p class="text-[10px] font-black text-indigo-600 uppercase tracking-widest mt-1">Precise GIS coordinates for network mapping</p>
                </div>
            </div>
            
            <div class="glass-map-frame">
                <div id="map"></div>
                <!-- Coordinates Badge -->
                <div class="coordinates-badge">
                    <div>
                        <span class="block text-[8px] font-black text-gray-400 uppercase tracking-widest mb-1">Latitude</span>
                        <input type="text" name="lat" x-model="lat" readonly class="bg-transparent font-mono font-black text-gray-900 border-none p-0 focus:ring-0 text-sm select-all">
                    </div>
                    <div class="w-px bg-gray-200 my-1"></div>
                    <div>
                        <span class="block text-[8px] font-black text-gray-400 uppercase tracking-widest mb-1">Longitude</span>
                        <input type="text" name="lng" x-model="lng" readonly class="bg-transparent font-mono font-black text-gray-900 border-none p-0 focus:ring-0 text-sm select-all">
                    </div>
                </div>
            </div>
            <div class="mt-8 flex items-center gap-3 bg-indigo-50/50 p-4 rounded-2xl border border-indigo-100">
                <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="text-[10px] font-black text-indigo-600 uppercase tracking-widest">Interactive Plot: Tap map to update telemetry</p>
            </div>
        </div>

        <!-- Energy & Financial Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Power Configuration -->
            <div class="glass-panel border border-white/40 rounded-[3rem] p-10 shadow-2xl relative overflow-hidden">
                <div class="flex items-center gap-4 mb-10">
                    <div class="w-14 h-14 bg-amber-500 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-amber-100">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-2xl font-black text-gray-900 tracking-tight uppercase">Energy Grid</h3>
                        <p class="text-[10px] font-black text-amber-600 uppercase tracking-widest mt-1">Autonomous power systems verification</p>
                    </div>
                </div>

                <div class="space-y-6">
                    <!-- Solar -->
                    <div class="bg-white/40 backdrop-blur-md rounded-3xl p-6 border border-white/60 group transition-all" :class="hasSolar ? 'ring-2 ring-indigo-600 border-transparent shadow-lg bg-white/80' : ''">
                        <label class="flex items-center justify-between cursor-pointer mb-6">
                            <span class="text-xs font-black text-gray-900 uppercase tracking-widest flex items-center gap-3">
                                <span class="text-xl">☀️</span> Solar Array
                            </span>
                            <div class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="has_solar" value="1" x-model="hasSolar" class="sr-only peer">
                                <div class="w-12 h-6 bg-gray-200 rounded-full peer peer-checked:bg-indigo-600 transition-all after:content-[''] after:absolute after:top-[4px] after:left-[4px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-6"></div>
                            </div>
                        </label>
                        <div x-show="hasSolar" x-collapse>
                            <div class="grid grid-cols-2 gap-4">
                                <input type="number" name="solar_panels_count" placeholder="Panel Count" class="w-full px-5 py-3 bg-white border border-gray-100 rounded-xl focus:ring-4 focus:ring-indigo-50 focus:border-indigo-600 outline-none transition-all font-bold text-gray-900">
                                <input type="number" name="solar_panel_wattage" placeholder="Wattage (W)" class="w-full px-5 py-3 bg-white border border-gray-100 rounded-xl focus:ring-4 focus:ring-indigo-50 focus:border-indigo-600 outline-none transition-all font-bold text-gray-900">
                            </div>
                        </div>
                    </div>

                    <!-- Generator -->
                    <div class="bg-white/40 backdrop-blur-md rounded-3xl p-6 border border-white/60 group transition-all" :class="hasGenerator ? 'ring-2 ring-indigo-600 border-transparent shadow-lg bg-white/80' : ''">
                        <label class="flex items-center justify-between cursor-pointer mb-4">
                            <span class="text-xs font-black text-gray-900 uppercase tracking-widest flex items-center gap-3">
                                <span class="text-xl">🔌</span> Combustion Unit
                            </span>
                            <div class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="has_generator" value="1" x-model="hasGenerator" class="sr-only peer">
                                <div class="w-12 h-6 bg-gray-200 rounded-full peer peer-checked:bg-indigo-600 transition-all after:content-[''] after:absolute after:top-[4px] after:left-[4px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-6"></div>
                            </div>
                        </label>
                        <div x-show="hasGenerator" x-collapse>
                            <input type="text" name="generator_capacity" placeholder="e.g. 15 KVA / Diesel" class="w-full px-5 py-3 bg-white border border-gray-100 rounded-xl focus:ring-4 focus:ring-indigo-50 focus:border-indigo-600 outline-none transition-all font-bold text-gray-900">
                        </div>
                    </div>

                    <!-- Gov Electricity -->
                    <div class="bg-white/40 backdrop-blur-md rounded-3xl p-6 border border-white/60 group transition-all" :class="hasGovElec ? 'ring-2 ring-emerald-600 border-transparent shadow-lg bg-white/80' : ''">
                        <label class="flex items-center justify-between cursor-pointer">
                            <span class="text-xs font-black text-gray-900 uppercase tracking-widest flex items-center gap-3">
                                <span class="text-xl">🏛️</span> Grid Electricity
                            </span>
                            <div class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="has_government_electricity" value="1" x-model="hasGovElec" class="sr-only peer">
                                <div class="w-12 h-6 bg-gray-200 rounded-full peer peer-checked:bg-emerald-600 transition-all after:content-[''] after:absolute after:top-[4px] after:left-[4px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-6"></div>
                            </div>
                        </label>
                    </div>

                    <!-- Batteries -->
                    <div class="bg-gray-900 p-8 rounded-[2rem] shadow-xl relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -translate-x-1/2 translate-y-1/2 blur-2xl"></div>
                        <label class="text-[10px] font-black text-indigo-300 uppercase tracking-widest mb-6 block">Chemical Energy Storage</label>
                        <div class="grid grid-cols-2 gap-4 relative z-10">
                            <input type="number" name="battery_count" placeholder="Units" class="bg-white/10 border border-white/10 rounded-xl px-5 py-3 text-white font-bold outline-none focus:bg-white/20 transition-all">
                            <input type="text" name="battery_type" placeholder="e.g. 12V 200AH" class="bg-white/10 border border-white/10 rounded-xl px-5 py-3 text-white font-bold outline-none focus:bg-white/20 transition-all">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Financial Ledger -->
            <div class="glass-panel border border-white/40 rounded-[3rem] p-10 shadow-2xl relative overflow-hidden">
                <div class="flex items-center gap-4 mb-10">
                    <div class="w-14 h-14 bg-emerald-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-emerald-100">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-2xl font-black text-gray-900 tracking-tight uppercase">Capital Ledger</h3>
                        <p class="text-[10px] font-black text-emerald-600 uppercase tracking-widest mt-1">Operational and construction burn rates</p>
                    </div>
                </div>
                
                <div class="space-y-10">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4 ml-1">Structural Build CAPEX</label>
                        <div class="relative">
                            <input type="number" step="0.01" name="structure_cost" value="{{ old('structure_cost') }}" class="w-full pl-16 pr-8 py-5 bg-white border border-gray-100 rounded-[2rem] focus:ring-4 focus:ring-emerald-50 focus:border-emerald-600 outline-none transition-all font-black text-gray-900 text-lg shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none">
                                <span class="text-xs font-black text-emerald-600 uppercase">{{ $currency }}</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4 ml-1">Node Monthly Lease</label>
                        <div class="relative">
                            <input type="number" step="0.01" name="monthly_rent" value="{{ old('monthly_rent') }}" class="w-full pl-16 pr-8 py-5 bg-white border border-gray-100 rounded-[2rem] focus:ring-4 focus:ring-indigo-50 focus:border-indigo-600 outline-none transition-all font-black text-gray-900 text-lg shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none">
                                <span class="text-xs font-black text-indigo-600 uppercase">{{ $currency }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-emerald-600 p-8 rounded-[2.5rem] shadow-2xl relative overflow-hidden group/burn">
                        <div class="absolute -top-12 -right-12 w-48 h-48 bg-white/10 rounded-full blur-3xl group-hover/burn:scale-125 transition-all"></div>
                        <p class="text-[10px] font-black text-emerald-100 uppercase tracking-widest mb-2 relative z-10">Financial Health Disclosure</p>
                        <p class="text-sm font-bold text-white/80 leading-relaxed mb-6 relative z-10">Ensure all costs are audited for tax and operational compliance.</p>
                        <div class="flex items-center gap-4 relative z-10">
                            <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center text-white">💰</div>
                            <div>
                                <p class="text-[9px] font-black text-emerald-200 uppercase tracking-widest">Initialization Burn Rate</p>
                                <p class="text-xl font-black text-white tracking-widest">Awaiting Input...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Network Configuration -->
        <div class="glass-panel border border-white/40 rounded-[3rem] p-10 md:p-12 shadow-2xl relative overflow-hidden mt-12">
            <div class="absolute -top-24 -right-24 w-64 h-64 bg-indigo-600/5 rounded-full blur-3xl"></div>
            
            <div class="flex items-center gap-4 mb-12">
                <div class="w-14 h-14 bg-emerald-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-emerald-100">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                </div>
                <div>
                    <h3 class="text-2xl font-black text-gray-900 tracking-tight uppercase">Network Uplink</h3>
                    <p class="text-[10px] font-black text-indigo-600 uppercase tracking-widest mt-1">Routing and upstream topology definitions</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                 <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4 ml-1">Gateway Controller</label>
                    <select name="mikrotik_server_id" x-model="serverId" class="w-full px-8 py-5 bg-white border border-gray-100 rounded-[2rem] focus:ring-4 focus:ring-indigo-100 focus:border-indigo-600 outline-none transition-all font-black text-gray-900 appearance-none shadow-sm">
                        <option value="">-- Standalone Node --</option>
                        @foreach($servers as $server)
                            <option value="{{ $server->id }}" {{ old('mikrotik_server_id') == $server->id ? 'selected' : '' }}>{{ $server->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4 ml-1">Transport Standard</label>
                    <select name="connection_type" x-model="connectionType" class="w-full px-8 py-5 bg-white border border-gray-100 rounded-[2rem] focus:ring-4 focus:ring-indigo-100 focus:border-indigo-600 outline-none transition-all font-black text-gray-900 appearance-none shadow-sm">
                        <option value="">-- Choose Protocol --</option>
                        <option value="wireless">Wireless (PTP Array)</option>
                        <option value="fiber">Optical Fiber (FTTH)</option>
                        <option value="cable">Copper (Gigabit Ethernet)</option>
                    </select>
                </div>

                <!-- Wireless Grid (PTP) -->
                <div class="md:col-span-2 bg-indigo-600 p-10 rounded-[3rem] shadow-2xl relative overflow-hidden" x-show="connectionType === 'wireless'" x-transition>
                    <div class="absolute -bottom-24 -right-24 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
                    <h4 class="text-[10px] font-black text-indigo-200 uppercase tracking-[0.3em] mb-10 pb-4 border-b border-white/10">Point-to-Point Configuration</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                        <!-- Transmitter (TX) -->
                        <div class="space-y-8">
                            <label class="block text-[10px] font-black text-white uppercase tracking-widest opacity-60">Source Link (TX Array)</label>
                            
                             <div class="bg-white/10 p-2 rounded-2xl flex gap-2">
                                <button type="button" @click="transmitterType = 'existing'" :class="transmitterType === 'existing' ? 'bg-white text-indigo-600 shadow-sm' : 'text-indigo-200'" class="flex-1 py-3 rounded-xl font-black text-[10px] uppercase tracking-widest transition-all">Stored</button>
                                <button type="button" @click="transmitterType = 'new'" :class="transmitterType === 'new' ? 'bg-white text-indigo-600 shadow-sm' : 'text-indigo-200'" class="flex-1 py-3 rounded-xl font-black text-[10px] uppercase tracking-widest transition-all">New</button>
                            </div>

                            <div x-show="transmitterType === 'existing'">
                                <select name="transmitter_router_id" class="w-full px-6 py-4 bg-white/10 border border-white/10 rounded-2xl text-white font-bold outline-none focus:bg-white/20">
                                    <option value="" class="text-gray-900">Choose Hardware...</option>
                                    @foreach($activeRouters as $router)
                                    <option value="{{ $router->id }}" class="text-gray-900">{{ $router->name }} ({{ $router->ip }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <div x-show="transmitterType === 'new'" class="space-y-4">
                                <input type="text" name="transmitter_name" placeholder="Radio Name" class="w-full px-6 py-4 bg-white/10 border border-white/10 rounded-2xl text-white font-black placeholder-white/30 outline-none">
                                <input type="text" name="transmitter_ip" placeholder="Target IP" class="w-full px-6 py-4 bg-white/10 border border-white/10 rounded-2xl text-white font-mono placeholder-white/30 outline-none" dir="ltr">
                                <select name="transmitter_model_id" class="w-full px-6 py-4 bg-white/10 border border-white/10 rounded-2xl text-white font-bold outline-none">
                                    <option value="" class="text-gray-900">Model Selection...</option>
                                    @foreach($deviceModels as $m)
                                    <option value="{{ $m->id }}" class="text-gray-900">{{ $m->model_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Receiver (RX) -->
                         <div class="space-y-8">
                            <label class="block text-[10px] font-black text-white uppercase tracking-widest opacity-60">Terminal Link (RX Array)</label>
                            <input type="text" name="receiver_name" placeholder="Terminal Handle" class="w-full px-6 py-4 bg-white/10 border border-white/10 rounded-2xl text-white font-black placeholder-white/30 outline-none">
                            <input type="text" name="receiver_ip" placeholder="Control IP" class="w-full px-6 py-4 bg-white/10 border border-white/10 rounded-2xl text-white font-mono placeholder-white/30 outline-none" dir="ltr">
                            <select name="receiver_model_id" class="w-full px-6 py-4 bg-white/10 border border-white/10 rounded-2xl text-white font-bold outline-none">
                                <option value="" class="text-gray-900">Radio Profile...</option>
                                @foreach($deviceModels as $m)
                                <option value="{{ $m->id }}" class="text-gray-900">{{ $m->model_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Governance -->
        <div class="glass-panel border border-white/40 rounded-[2.5rem] p-8 flex items-center justify-between shadow-xl mt-12 bg-white/80 backdrop-blur-md">
             <a href="{{ route('network.towers.index') }}" class="text-[11px] font-black text-gray-500 uppercase tracking-widest hover:text-gray-900 transition-colors">Abort Mission</a>
             <div class="flex gap-4">
                 <button type="reset" class="px-8 py-3.5 bg-gray-100 text-gray-600 font-black text-[11px] uppercase tracking-widest rounded-2xl hover:bg-gray-200 transition-all">Clear Form</button>
                 <button type="submit" class="px-10 py-4 bg-indigo-600 text-white rounded-[2rem] font-black text-[11px] uppercase tracking-widest shadow-xl shadow-indigo-100 hover:scale-105 transition-all">
                    Initialize Deployment
                 </button>
             </div>
        </div>
    </form>
</div>

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('towerForm', () => ({
            siteType: '{{ old('type', 'tower') }}',
            lat: '{{ old('lat') }}',
            lng: '{{ old('lng') }}',
            hasSolar: {{ old('has_solar') ? 'true' : 'false' }},
            hasGenerator: {{ old('has_generator') ? 'true' : 'false' }},
            hasGovElec: {{ old('has_government_electricity') ? 'true' : 'false' }},
            hasSubscription: {{ old('has_ampere_subscription') ? 'true' : 'false' }},
            serverId: '{{ old('mikrotik_server_id') }}',
            connectionType: '{{ old('connection_type') }}',
            transmitterType: 'existing',
            map: null,
            marker: null,

            init() {
                this.$nextTick(() => {
                    this.initMap();
                });
            },

            initMap() {
                const initialLat = this.lat || 33.5138;
                const initialLng = this.lng || 36.2765;

                this.map = L.map('map').setView([initialLat, initialLng], 13);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(this.map);

                if (this.lat && this.lng) {
                    this.marker = L.marker([this.lat, this.lng]).addTo(this.map);
                }

                this.map.on('click', (e) => {
                    this.lat = e.latlng.lat.toFixed(6);
                    this.lng = e.latlng.lng.toFixed(6);
                    
                    if (this.marker) this.marker.setLatLng(e.latlng);
                    else this.marker = L.marker(e.latlng).addTo(this.map);
                });
            }
        }));
    });
</script>
@endpush
@endsection

```

## resources/views/network\towers\create_enterprise.blade.php

```php
@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    /* Compact Map for Enterprise View */
    #map { height: 250px; width: 100%; border-radius: 0.5rem; }
    
    /* Dense Form Styling */
    .form-dense label { font-size: 0.75rem; font-weight: 600; color: #4b5563; margin-bottom: 0.25rem; display: block; }
    .form-dense input, .form-dense select, .form-dense textarea {
        font-size: 0.875rem; padding: 0.4rem 0.6rem; border-radius: 0.375rem;
    }
    
    /* Section Card */
    .enterprise-card {
        background: white; border: 1px solid #e5e7eb; border-radius: 0.5rem;
        height: 100%; display: flex; flex-direction: column;
    }
    .enterprise-card-header {
        padding: 0.75rem 1rem; border-bottom: 1px solid #f3f4f6;
        display: flex; align-items: center; gap: 0.5rem;
        background: #f9fafb; border-radius: 0.5rem 0.5rem 0 0;
    }
    .enterprise-card-body { padding: 1rem; flex: 1; }
</style>
@endpush

@section('content')
<div class="max-w-[1600px] mx-auto space-y-4" x-data="towerEnterprise">
    
    {{-- Top Bar --}}
    <div class="flex items-center justify-between bg-white px-6 py-3 rounded-lg border border-gray-200 shadow-sm">
        <div class="flex items-center gap-4">
            <h2 class="text-xl font-bold text-gray-800">إضافة برج جديد (Enterprise)</h2>
            <span class="bg-indigo-100 text-indigo-700 text-xs px-2 py-0.5 rounded border border-indigo-200">وضع المحترفين</span>
        </div>
        <div class="flex items-center gap-3">
            <div class="h-4 w-px bg-gray-300"></div>
            <button @click="submitForm" class="px-6 py-2 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                حفظ البرج
            </button>
        </div>
    </div>

    {{-- Validation Errors --}}
    @if($errors->any())
    <div class="bg-red-50 border-l-4 border-red-500 p-3 rounded shadow-sm text-sm">
        <p class="font-bold text-red-700">يرجى التحقق من البيانات:</p>
        <ul class="list-disc list-inside text-red-600 mt-1">
            @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('network.towers.store') }}" method="POST" id="tower-form" class="form-dense">
        @csrf

        {{-- Main Grid --}}
        <div class="grid grid-cols-12 gap-4">
            
            {{-- Column 1: Basic Info & Location (Width 4) --}}
            <div class="col-span-12 lg:col-span-4 space-y-4">
                {{-- Basic Info --}}
                <div class="enterprise-card">
                    <div class="enterprise-card-header">
                        <span class="text-blue-600">📝</span> <h3 class="font-bold text-gray-700 text-sm">البيانات الأساسية</h3>
                    </div>
                    <div class="enterprise-card-body space-y-3">
                        <div>
                            <label>اسم البرج <span class="text-red-500">*</span></label>
                            <input type="text" name="name" required value="{{ old('name') }}" class="w-full border-gray-300 focus:ring-blue-500" placeholder="اسم النقطة التعريفية">
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label>نوع الموقع</label>
                                <select name="type" class="w-full border-gray-300 focus:ring-blue-500">
                                    <option value="tower" {{ old('type') == 'tower' ? 'selected' : '' }}>🗼 برج</option>
                                    <option value="building" {{ old('type') == 'building' ? 'selected' : '' }}>🏢 مبنى</option>
                                    <option value="pole" {{ old('type') == 'pole' ? 'selected' : '' }}>📍 عمود</option>
                                </select>
                            </div>
                            <div>
                                <label>الحالة</label>
                                <select name="status" class="w-full border-gray-300 focus:ring-blue-500">
                                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label>المحافظة</label>
                                <select name="city" x-model="city" class="w-full border-gray-300 focus:ring-blue-500">
                                    <option value="">اختر...</option>
                                    <option value="Damascus">دمشق</option>
                                    <option value="Aleppo">حلب</option>
                                    {{-- Add others --}}
                                </select>
                            </div>
                            <div>
                                <label>المنطقة / الحي</label>
                                <input type="text" name="district" value="{{ old('district') }}" class="w-full border-gray-300 focus:ring-blue-500">
                            </div>
                        </div>

                         <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label>الارتفاع (م)</label>
                                <input type="number" name="height" value="{{ old('height') }}" class="w-full border-gray-300 focus:ring-blue-500">
                            </div>
                            <div>
                                <label>الطوابق</label>
                                <input type="number" name="number_of_floors" value="{{ old('number_of_floors') }}" class="w-full border-gray-300 focus:ring-blue-500">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Location Map --}}
                 <div class="enterprise-card">
                    <div class="enterprise-card-header">
                        <span class="text-green-600">📍</span> <h3 class="font-bold text-gray-700 text-sm">الإحداثيات</h3>
                    </div>
                    <div class="enterprise-card-body p-0 relative">
                        <div id="map"></div>
                         <div class="grid grid-cols-2 gap-0 border-t border-gray-200">
                            <input type="text" name="lat" x-model="lat" readonly class="border-0 bg-gray-50 text-xs text-center border-r border-gray-200 py-1" placeholder="Lat">
                            <input type="text" name="lng" x-model="lng" readonly class="border-0 bg-gray-50 text-xs text-center py-1" placeholder="Lng">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Column 2: Network & Connectivity (Width 4) --}}
            <div class="col-span-12 lg:col-span-4 space-y-4">
                <div class="enterprise-card">
                    <div class="enterprise-card-header">
                        <span class="text-indigo-600">🔗</span> <h3 class="font-bold text-gray-700 text-sm">الربط الشبكي</h3>
                    </div>
                    <div class="enterprise-card-body space-y-4">
                        <div>
                            <label>السيرفر المغذي</label>
                            <select name="mikrotik_server_id" class="w-full border-gray-300 focus:ring-blue-500">
                                <option value="">-- مباشر من المقسم --</option>
                                @foreach($servers as $server)
                                    <option value="{{ $server->id }}" {{ old('mikrotik_server_id') == $server->id ? 'selected' : '' }}>
                                        {{ $server->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label>نوع الربط</label>
                                <select name="connection_type" x-model="connectionType" class="w-full border-gray-300 focus:ring-blue-500">
                                    <option value="">نوع الوسيط...</option>
                                    <option value="wireless">لاسلكي (Wireless)</option>
                                    <option value="fiber">ألياف (Fiber)</option>
                                    <option value="cable">كبل (Cable)</option>
                                </select>
                            </div>
                            <div>
                                <label>المنفذ (Port)</label>
                                <input type="text" name="connection_port" value="{{ old('connection_port') }}" class="w-full border-gray-300 focus:ring-blue-500" placeholder="ether1">
                            </div>
                        </div>

                        {{-- Wireless Fields --}}
                        <div x-show="connectionType === 'wireless'" class="bg-gray-50 p-3 rounded border border-gray-200 space-y-3">
                             <div>
                                <label class="text-indigo-800">جهاز الإرسال (AP)</label>
                                <select name="transmitter_router_id" @change="updateTransmitter" class="w-full border-gray-300 focus:ring-indigo-500 text-xs">
                                    <option value="">اختر المرسل...</option>
                                    @foreach($activeRouters as $router)
                                    <option value="{{ $router->id }}" data-ip="{{ $router->ip }}" {{ old('transmitter_router_id') == $router->id ? 'selected' : '' }}>
                                        {{ $router->name }} ({{ $router->ip }})
                                    </option>
                                    @endforeach
                                </select>
                                <div class="flex justify-between items-center mt-1">
                                    <span x-text="transmitterIP" class="text-xs font-mono text-gray-500"></span>
                                    <a href="#" @click.prevent="alert('Available in Wizard Mode')" class="text-xs text-blue-600 hover:underline">+ جديد</a>
                                </div>
                            </div>

                            <hr class="border-gray-200">

                            <div>
                                <label class="text-indigo-800">جهاز الاستقبال (CPE)</label>
                                <div class="grid grid-cols-2 gap-2 mb-2">
                                     <select name="receiver_model_id" class="w-full border-gray-300 focus:ring-indigo-500 text-xs col-span-2">
                                        <option value="">موديل الجهاز...</option>
                                        @foreach($deviceModels as $model)
                                            <option value="{{ $model->id }}">{{ $model->manufacturer }} {{ $model->model_name }}</option>
                                        @endforeach
                                    </select>
                                    <input type="text" name="receiver_ip" value="{{ old('receiver_ip') }}" placeholder="IP" class="form-input text-xs">
                                    <input type="text" name="receiver_ssid" value="{{ old('receiver_ssid') }}" placeholder="SSID" class="form-input text-xs">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Column 3: Power & Costs (Width 4) --}}
            <div class="col-span-12 lg:col-span-4 space-y-4">
                 <div class="enterprise-card">
                    <div class="enterprise-card-header">
                        <span class="text-yellow-600">⚡</span> <h3 class="font-bold text-gray-700 text-sm">الطاقة</h3>
                    </div>
                    <div class="enterprise-card-body space-y-3">
                        <div class="flex items-center justify-between border-b pb-2 border-gray-100">
                             <label class="mb-0">🔌 كهرباء الدولة</label>
                             <input type="checkbox" name="has_government_electricity" value="1" {{ old('has_government_electricity') ? 'checked' : '' }}>
                        </div>

                        <div class="flex items-center justify-between">
                             <label class="mb-0">☀️ طاقة شمسية</label>
                             <input type="checkbox" name="has_solar" value="1" x-model="hasSolar">
                        </div>
                        <div x-show="hasSolar" class="grid grid-cols-2 gap-2 bg-yellow-50 p-2 rounded">
                            <input type="number" name="solar_panels_count" placeholder="عدد الألواح" class="text-xs">
                            <input type="number" name="solar_panel_wattage" placeholder="القدرة (واط)" class="text-xs">
                        </div>

                        <div class="flex items-center justify-between">
                             <label class="mb-0">🔋 بطاريات</label>
                             <div class="flex items-center gap-2">
                                <input type="number" name="battery_count" placeholder="العدد" class="w-16 text-center h-8">
                             </div>
                        </div>
                    </div>
                </div>

                 <div class="enterprise-card">
                    <div class="enterprise-card-header">
                        <span class="text-gray-600">💰</span> <h3 class="font-bold text-gray-700 text-sm">التكاليف</h3>
                    </div>
                    <div class="enterprise-card-body grid grid-cols-2 gap-3">
                         <div>
                            <label>آجار شهري</label>
                            <input type="number" name="monthly_rent" class="w-full">
                        </div>
                        <div>
                            <label>صيانة شهرية</label>
                            <input type="number" name="monthly_maintenance" class="w-full">
                        </div>
                        <div>
                            <label>كلفة الهيكل</label>
                            <input type="number" name="structure_cost" class="w-full">
                        </div>
                        <div>
                            <label>كلفة الطاقة</label>
                            <input type="number" name="solar_installation_cost" class="w-full">
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('towerEnterprise', () => ({
            city: '{{ old("city") }}',
            lat: '{{ old("lat") }}',
            lng: '{{ old("lng") }}',
            connectionType: '{{ old("connection_type") }}',
            hasSolar: {{ old("has_solar") ? 'true' : 'false' }},
            transmitterIP: '',
            map: null,
            marker: null,

            init() {
                this.initMap();
            },

            submitForm() {
                document.getElementById('tower-form').submit();
            },

            initMap() {
                setTimeout(() => {
                    const startLat = this.lat || 33.5138;
                    const startLng = this.lng || 36.2765;
                    this.map = L.map('map').setView([startLat, startLng], 12);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(this.map);
                    
                    this.map.on('click', (e) => {
                        this.lat = e.latlng.lat.toFixed(6);
                        this.lng = e.latlng.lng.toFixed(6);
                        if(this.marker) this.marker.setLatLng(e.latlng);
                        else this.marker = L.marker(e.latlng).addTo(this.map);
                    });
                }, 200);
            },

            updateTransmitter(e) {
                const opt = e.target.selectedOptions[0];
                this.transmitterIP = opt.dataset.ip || '';
            }
        }));
    });
</script>
@endsection

```

## resources/views/network\towers\edit.blade.php

```php
@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #map { height: 450px; width: 100%; z-index: 1; filter: grayscale(0.2) contrast(1.1); }
    .glass-map-frame { position: relative; border-radius: 2.5rem; overflow: hidden; border: 1px solid rgba(255, 255, 255, 0.4); box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15); }
    .coordinates-badge { position: absolute; bottom: 2rem; right: 2rem; z-index: 1000; background: rgba(255, 255, 255, 0.8); backdrop-blur: 12px; border: 1px solid rgba(255, 255, 255, 0.5); border-radius: 1.5rem; padding: 1rem 1.5rem; display: flex; gap: 1.5rem; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1); }
    .form-toggle:checked + .toggle-track { background-color: #4f46e5; }
    .form-toggle:checked + .toggle-track::after { transform: translateX(100%); background-color: white; }
</style>
@endpush

@section('content')
<div class="max-w-6xl mx-auto pb-24" x-data="towerForm">
    
    <!-- Navigation Header -->
    <div class="glass-panel border border-white/40 rounded-[2.5rem] p-6 mb-12 flex items-center justify-between shadow-xl relative overflow-hidden group">
        <div class="absolute -top-10 -right-10 w-32 h-32 bg-indigo-600/5 rounded-full blur-3xl group-hover:scale-150 transition-all duration-700"></div>
        <div class="flex items-center gap-6 relative">
            <a href="{{ route('network.towers.show', $tower) }}" class="w-14 h-14 bg-white/50 backdrop-blur-md border border-white/80 rounded-2xl flex items-center justify-center text-indigo-600 hover:bg-indigo-600 hover:text-white transition-all shadow-sm group-hover:rotate-[-5deg]">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <h2 class="text-3xl font-black text-gray-900 tracking-tight uppercase">Edit Hub <span class="text-indigo-600">Specs</span></h2>
                <div class="flex items-center gap-2 mt-1">
                    <span class="w-2 h-2 bg-indigo-600 rounded-full animate-pulse"></span>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] select-all">{{ $tower->name }}</p>
                </div>
            </div>
        </div>
        <div class="flex gap-4 relative">
             <a href="{{ route('network.towers.show', $tower) }}" class="px-8 py-3.5 bg-white border border-indigo-100 text-indigo-600 font-black text-[11px] uppercase tracking-widest rounded-2xl hover:bg-indigo-50 transition-all shadow-sm">
                View Instance
            </a>
            <a href="{{ route('network.towers.index') }}" class="px-8 py-3.5 bg-gray-900 text-white font-black text-[11px] uppercase tracking-widest rounded-2xl hover:scale-105 transition-all shadow-lg shadow-indigo-100">
                Cancel Update
            </a>
        </div>
    </div>

    <!-- Error Messages -->
    <!-- Alert System -->
    @if($errors->any())
    <div class="glass-panel border border-rose-500/20 bg-rose-500/5 backdrop-blur-xl p-8 rounded-[2.5rem] mb-12 shadow-xl animate-shake">
        <div class="flex items-start gap-6">
            <div class="w-14 h-14 bg-rose-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-rose-200 shrink-0">
                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <div>
                <h4 class="text-lg font-black text-rose-900 uppercase tracking-tight mb-2">Protocol Disruption</h4>
                <ul class="space-y-1.5">
                    @foreach($errors->all() as $error)
                    <li class="text-sm font-bold text-rose-700/80 flex items-center gap-2">
                        <span class="w-1.5 h-1.5 bg-rose-500 rounded-full"></span>
                        {{ $error }}
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    <form action="{{ route('network.towers.update', $tower) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Basic Informatics -->
        <div class="glass-panel border border-white/40 rounded-[3rem] p-10 md:p-12 shadow-2xl relative overflow-hidden">
            <div class="absolute -top-24 -right-24 w-64 h-64 bg-indigo-600/5 rounded-full blur-3xl"></div>
            
            <div class="flex items-center gap-4 mb-12">
                <div class="w-14 h-14 bg-indigo-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-indigo-100">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div>
                    <h3 class="text-2xl font-black text-gray-900 tracking-tight uppercase">Hub Identity</h3>
                    <p class="text-[10px] font-black text-indigo-600 uppercase tracking-widest mt-1">Core site registration and classification</p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <!-- Name -->
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4 ml-1">Site Label / SSID Host</label>
                    <input type="text" name="name" required value="{{ old('name', $tower->name) }}"
                        class="w-full px-8 py-5 bg-white border border-gray-100 rounded-[2rem] focus:ring-4 focus:ring-indigo-100 focus:border-indigo-600 outline-none transition-all font-black text-gray-900 text-lg shadow-sm"
                        placeholder="e.g. TOWER_NORTH_CORE">
                </div>

                <!-- Type Selection -->
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-6 ml-1">Infrastructure Classification</label>
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                        @php
                            $types = [
                                'tower' => ['Tower', '🗼'],
                                'building' => ['Building', '🏢'],
                                'cabinet' => ['Cabinet', '🗄️'],
                                'pole' => ['Pole', '📍'],
                                'office' => ['Office', '🏛️']
                            ];
                        @endphp
                        @foreach($types as $value => $meta)
                        <label class="cursor-pointer group">
                            <input type="radio" name="type" value="{{ $value }}" x-model="siteType" class="peer sr-only">
                            <div class="text-center py-6 bg-white border border-gray-100 rounded-3xl peer-checked:border-indigo-600 peer-checked:bg-indigo-600 peer-checked:text-white transition-all shadow-sm group-hover:scale-105">
                                <div class="text-3xl mb-3">{{ $meta[1] }}</div>
                                <div class="text-[9px] font-black uppercase tracking-widest">{{ $meta[0] }}</div>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>

                <!-- Location Details -->
                <div class="space-y-10">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4 ml-1">City / Region</label>
                        <input type="text" name="city" value="{{ old('city', $tower->city) }}" class="w-full px-6 py-4 bg-white border border-gray-100 rounded-2xl focus:ring-4 focus:ring-indigo-100 focus:border-indigo-600 outline-none transition-all font-bold text-gray-900" placeholder="Dubai">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4 ml-1">Sub-District</label>
                        <input type="text" name="district" value="{{ old('district', $tower->district) }}" class="w-full px-6 py-4 bg-white border border-gray-100 rounded-2xl focus:ring-4 focus:ring-indigo-100 focus:border-indigo-600 outline-none transition-all font-bold text-gray-900">
                    </div>
                </div>
                
                <div class="space-y-10">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4 ml-1">Structural Height (m)</label>
                        <input type="number" name="height" value="{{ old('height', $tower->height) }}" class="w-full px-6 py-4 bg-white border border-gray-100 rounded-2xl focus:ring-4 focus:ring-indigo-100 focus:border-indigo-600 outline-none transition-all font-bold text-gray-900">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4 ml-1">Operational State</label>
                        <select name="status" class="w-full px-6 py-4 bg-white border border-gray-100 rounded-2xl focus:ring-4 focus:ring-indigo-100 focus:border-indigo-600 outline-none transition-all font-bold text-gray-900 appearance-none">
                            <option value="active" {{ old('status', $tower->status) == 'active' ? 'selected' : '' }}>Operational (Live)</option>
                            <option value="maintenance" {{ old('status', $tower->status) == 'maintenance' ? 'selected' : '' }}>Maintenance Mode</option>
                            <option value="inactive" {{ old('status', $tower->status) == 'inactive' ? 'selected' : '' }}>Offline / Decommissioned</option>
                        </select>
                    </div>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4 ml-1">Physical Access Protocol / Address</label>
                    <textarea name="location" rows="3" class="w-full px-6 py-4 bg-white border border-gray-100 rounded-[2rem] focus:ring-4 focus:ring-indigo-100 focus:border-indigo-600 outline-none transition-all font-bold text-gray-900 resize-none" placeholder="Precise directions or site entry requirements...">{{ old('location', $tower->location) }}</textarea>
                </div>
            </div>
        </div>

        <!-- Geospatial Grid -->
        <div class="glass-panel border border-white/40 rounded-[3rem] p-10 md:p-12 shadow-2xl relative overflow-hidden">
            <div class="flex items-center gap-4 mb-10">
                <div class="w-14 h-14 bg-indigo-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-indigo-100">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <div>
                    <h3 class="text-2xl font-black text-gray-900 tracking-tight uppercase">Geospatial Plot</h3>
                    <p class="text-[10px] font-black text-indigo-600 uppercase tracking-widest mt-1">Precise GIS coordinates for network mapping</p>
                </div>
            </div>
            
            <div class="glass-map-frame">
                <div id="map"></div>
                <!-- Coordinates Badge -->
                <div class="coordinates-badge">
                    <div>
                        <span class="block text-[8px] font-black text-gray-400 uppercase tracking-widest mb-1">Latitude</span>
                        <input type="text" name="lat" x-model="lat" readonly class="bg-transparent font-mono font-black text-gray-900 border-none p-0 focus:ring-0 text-sm select-all">
                    </div>
                    <div class="w-px bg-gray-200 my-1"></div>
                    <div>
                        <span class="block text-[8px] font-black text-gray-400 uppercase tracking-widest mb-1">Longitude</span>
                        <input type="text" name="lng" x-model="lng" readonly class="bg-transparent font-mono font-black text-gray-900 border-none p-0 focus:ring-0 text-sm select-all">
                    </div>
                </div>
            </div>
            <div class="mt-8 flex items-center gap-3 bg-indigo-50/50 p-4 rounded-2xl border border-indigo-100">
                <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="text-[10px] font-black text-indigo-600 uppercase tracking-widest">Interactive Plot: Tap map to update telemetry</p>
            </div>
        </div>

        <!-- Energy & Financial Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Power Configuration -->
            <div class="glass-panel border border-white/40 rounded-[3rem] p-10 shadow-2xl relative overflow-hidden">
                <div class="flex items-center gap-4 mb-10">
                    <div class="w-14 h-14 bg-amber-500 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-amber-100">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-2xl font-black text-gray-900 tracking-tight uppercase">Energy Grid</h3>
                        <p class="text-[10px] font-black text-amber-600 uppercase tracking-widest mt-1">Autonomous power systems verification</p>
                    </div>
                </div>

                <div class="space-y-6">
                    <!-- Solar -->
                    <div class="bg-white/40 backdrop-blur-md rounded-3xl p-6 border border-white/60 group transition-all" :class="hasSolar ? 'ring-2 ring-indigo-600 border-transparent shadow-lg bg-white/80' : ''">
                        <label class="flex items-center justify-between cursor-pointer mb-6">
                            <span class="text-xs font-black text-gray-900 uppercase tracking-widest flex items-center gap-3">
                                <span class="text-xl">☀️</span> Solar Array
                            </span>
                            <div class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="has_solar" value="1" x-model="hasSolar" class="sr-only peer">
                                <div class="w-12 h-6 bg-gray-200 rounded-full peer peer-checked:bg-indigo-600 transition-all after:content-[''] after:absolute after:top-[4px] after:left-[4px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-6"></div>
                            </div>
                        </label>
                        <div x-show="hasSolar" x-collapse>
                            <div class="grid grid-cols-2 gap-4">
                                <input type="number" name="solar_panels_count" value="{{ old('solar_panels_count', $tower->solar_panels_count) }}" placeholder="Panel Count" class="w-full px-5 py-3 bg-white border border-gray-100 rounded-xl focus:ring-4 focus:ring-indigo-50 focus:border-indigo-600 outline-none transition-all font-bold text-gray-900">
                                <input type="number" name="solar_panel_wattage" value="{{ old('solar_panel_wattage', $tower->solar_panel_wattage) }}" placeholder="Wattage (W)" class="w-full px-5 py-3 bg-white border border-gray-100 rounded-xl focus:ring-4 focus:ring-indigo-50 focus:border-indigo-600 outline-none transition-all font-bold text-gray-900">
                            </div>
                        </div>
                    </div>

                    <!-- Generator -->
                    <div class="bg-white/40 backdrop-blur-md rounded-3xl p-6 border border-white/60 group transition-all" :class="hasGenerator ? 'ring-2 ring-indigo-600 border-transparent shadow-lg bg-white/80' : ''">
                        <label class="flex items-center justify-between cursor-pointer mb-4">
                            <span class="text-xs font-black text-gray-900 uppercase tracking-widest flex items-center gap-3">
                                <span class="text-xl">🔌</span> Combustion Unit
                            </span>
                            <div class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="has_generator" value="1" x-model="hasGenerator" class="sr-only peer">
                                <div class="w-12 h-6 bg-gray-200 rounded-full peer peer-checked:bg-indigo-600 transition-all after:content-[''] after:absolute after:top-[4px] after:left-[4px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-6"></div>
                            </div>
                        </label>
                        <div x-show="hasGenerator" x-collapse>
                            <input type="text" name="generator_capacity" value="{{ old('generator_capacity', $tower->generator_capacity) }}" placeholder="e.g. 15 KVA / Diesel" class="w-full px-5 py-3 bg-white border border-gray-100 rounded-xl focus:ring-4 focus:ring-indigo-50 focus:border-indigo-600 outline-none transition-all font-bold text-gray-900">
                        </div>
                    </div>

                    <!-- Gov Electricity -->
                    <div class="bg-white/40 backdrop-blur-md rounded-3xl p-6 border border-white/60 group transition-all" :class="hasGovElec ? 'ring-2 ring-emerald-600 border-transparent shadow-lg bg-white/80' : ''">
                        <label class="flex items-center justify-between cursor-pointer">
                            <span class="text-xs font-black text-gray-900 uppercase tracking-widest flex items-center gap-3">
                                <span class="text-xl">🏛️</span> Grid Electricity
                            </span>
                            <div class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="has_government_electricity" value="1" x-model="hasGovElec" class="sr-only peer">
                                <div class="w-12 h-6 bg-gray-200 rounded-full peer peer-checked:bg-emerald-600 transition-all after:content-[''] after:absolute after:top-[4px] after:left-[4px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-6"></div>
                            </div>
                        </label>
                    </div>

                    <!-- Batteries -->
                    <div class="bg-gray-900 p-8 rounded-[2rem] shadow-xl relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -translate-x-1/2 translate-y-1/2 blur-2xl"></div>
                        <label class="text-[10px] font-black text-indigo-300 uppercase tracking-widest mb-6 block">Chemical Energy Storage</label>
                        <div class="grid grid-cols-2 gap-4 relative z-10">
                            <input type="number" name="battery_count" value="{{ old('battery_count', $tower->battery_count) }}" placeholder="Units" class="bg-white/10 border border-white/10 rounded-xl px-5 py-3 text-white font-bold outline-none focus:bg-white/20 transition-all">
                            <input type="text" name="battery_type" value="{{ old('battery_type', $tower->battery_type) }}" placeholder="e.g. 12V 200AH" class="bg-white/10 border border-white/10 rounded-xl px-5 py-3 text-white font-bold outline-none focus:bg-white/20 transition-all">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Financial Ledger -->
            <div class="glass-panel border border-white/40 rounded-[3rem] p-10 shadow-2xl relative overflow-hidden">
                <div class="flex items-center gap-4 mb-10">
                    <div class="w-14 h-14 bg-emerald-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-emerald-100">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-2xl font-black text-gray-900 tracking-tight uppercase">Capital Ledger</h3>
                        <p class="text-[10px] font-black text-emerald-600 uppercase tracking-widest mt-1">Operational and construction burn rates</p>
                    </div>
                </div>
                
                <div class="space-y-10">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4 ml-1">Structural Build CAPEX</label>
                        <div class="relative">
                            <input type="number" step="0.01" name="structure_cost" value="{{ old('structure_cost', $tower->structure_cost) }}" class="w-full pl-16 pr-8 py-5 bg-white border border-gray-100 rounded-[2rem] focus:ring-4 focus:ring-emerald-50 focus:border-emerald-600 outline-none transition-all font-black text-gray-900 text-lg shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none">
                                <span class="text-xs font-black text-emerald-600 uppercase">{{ $currency }}</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4 ml-1">Node Monthly Lease</label>
                        <div class="relative">
                            <input type="number" step="0.01" name="monthly_rent" value="{{ old('monthly_rent', $tower->monthly_rent) }}" class="w-full pl-16 pr-8 py-5 bg-white border border-gray-100 rounded-[2rem] focus:ring-4 focus:ring-indigo-50 focus:border-indigo-600 outline-none transition-all font-black text-gray-900 text-lg shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none">
                                <span class="text-xs font-black text-indigo-600 uppercase">{{ $currency }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-emerald-600 p-8 rounded-[2.5rem] shadow-2xl relative overflow-hidden group/burn">
                        <div class="absolute -top-12 -right-12 w-48 h-48 bg-white/10 rounded-full blur-3xl group-hover/burn:scale-125 transition-all"></div>
                        <p class="text-[10px] font-black text-emerald-100 uppercase tracking-widest mb-2 relative z-10">Financial Health Disclosure</p>
                        <p class="text-sm font-bold text-white/80 leading-relaxed mb-6 relative z-10">Ensure all costs are audited for tax and operational compliance.</p>
                        <div class="flex items-center gap-4 relative z-10">
                            <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center text-white">💰</div>
                            <div>
                                <p class="text-[9px] font-black text-emerald-200 uppercase tracking-widest">Global Asset Cost</p>
                                <p class="text-xl font-black text-white tracking-widest">{{ number_format(($tower->structure_cost ?? 0) + ($tower->solar_installation_cost ?? 0), 0) }} {{ $currency }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Network Configuration -->
        <div class="glass-panel border border-white/40 rounded-[3rem] p-10 md:p-12 shadow-2xl relative overflow-hidden mt-12">
            <div class="absolute -top-24 -right-24 w-64 h-64 bg-indigo-600/5 rounded-full blur-3xl"></div>
            
            <div class="flex items-center gap-4 mb-12">
                <div class="w-14 h-14 bg-emerald-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-emerald-100">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                </div>
                <div>
                    <h3 class="text-2xl font-black text-gray-900 tracking-tight uppercase">Network Uplink</h3>
                    <p class="text-[10px] font-black text-indigo-600 uppercase tracking-widest mt-1">Routing and upstream topology definitions</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                 <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4 ml-1">Gateway Controller</label>
                    <select name="mikrotik_server_id" x-model="serverId" class="w-full px-8 py-5 bg-white border border-gray-100 rounded-[2rem] focus:ring-4 focus:ring-indigo-100 focus:border-indigo-600 outline-none transition-all font-black text-gray-900 appearance-none shadow-sm">
                        <option value="">-- Standalone Node --</option>
                        @foreach($servers as $server)
                            <option value="{{ $server->id }}" {{ old('mikrotik_server_id', $tower->mikrotik_server_id) == $server->id ? 'selected' : '' }}>{{ $server->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4 ml-1">Transport Standard</label>
                    <select name="connection_type" x-model="connectionType" class="w-full px-8 py-5 bg-white border border-gray-100 rounded-[2rem] focus:ring-4 focus:ring-indigo-100 focus:border-indigo-600 outline-none transition-all font-black text-gray-900 appearance-none shadow-sm">
                        <option value="">-- Choose Protocol --</option>
                        <option value="wireless">Wireless (PTP Array)</option>
                        <option value="fiber">Optical Fiber (FTTH)</option>
                        <option value="cable">Copper (Gigabit Ethernet)</option>
                    </select>
                </div>

                <!-- Wireless Grid (PTP) -->
                <div class="md:col-span-2 bg-indigo-600 p-10 rounded-[3rem] shadow-2xl relative overflow-hidden" x-show="connectionType === 'wireless'" x-transition>
                    <div class="absolute -bottom-24 -right-24 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
                    <h4 class="text-[10px] font-black text-indigo-200 uppercase tracking-[0.3em] mb-10 pb-4 border-b border-white/10">Point-to-Point Configuration</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                        <!-- Transmitter (TX) -->
                        <div class="space-y-8">
                            <label class="block text-[10px] font-black text-white uppercase tracking-widest opacity-60">Source Link (TX Array)</label>
                            
                             <div class="bg-white/10 p-2 rounded-2xl flex gap-2">
                                <button type="button" @click="transmitterType = 'existing'" :class="transmitterType === 'existing' ? 'bg-white text-indigo-600 shadow-sm' : 'text-indigo-200'" class="flex-1 py-3 rounded-xl font-black text-[10px] uppercase tracking-widest transition-all">Stored</button>
                                <button type="button" @click="transmitterType = 'new'" :class="transmitterType === 'new' ? 'bg-white text-indigo-600 shadow-sm' : 'text-indigo-200'" class="flex-1 py-3 rounded-xl font-black text-[10px] uppercase tracking-widest transition-all">New</button>
                            </div>

                            <div x-show="transmitterType === 'existing'">
                                <select name="transmitter_router_id" class="w-full px-6 py-4 bg-white/10 border border-white/10 rounded-2xl text-white font-bold outline-none focus:bg-white/20">
                                    <option value="" class="text-gray-900">Choose Hardware...</option>
                                    @foreach($activeRouters as $router)
                                    <option value="{{ $router->id }}" class="text-gray-900" {{ old('transmitter_router_id', $tower->transmitter_router_id) == $router->id ? 'selected' : '' }}>{{ $router->name }} ({{ $router->ip }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <div x-show="transmitterType === 'new'" class="space-y-4">
                                <input type="text" name="transmitter_name" placeholder="Radio Name" class="w-full px-6 py-4 bg-white/10 border border-white/10 rounded-2xl text-white font-black placeholder-white/30 outline-none">
                                <input type="text" name="transmitter_ip" placeholder="Target IP" class="w-full px-6 py-4 bg-white/10 border border-white/10 rounded-2xl text-white font-mono placeholder-white/30 outline-none" dir="ltr">
                                <select name="transmitter_model_id" class="w-full px-6 py-4 bg-white/10 border border-white/10 rounded-2xl text-white font-bold outline-none">
                                    <option value="" class="text-gray-900">Model Selection...</option>
                                    @foreach($deviceModels as $m)
                                    <option value="{{ $m->id }}" class="text-gray-900">{{ $m->model_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Receiver (RX) -->
                         <div class="space-y-8">
                            <label class="block text-[10px] font-black text-white uppercase tracking-widest opacity-60">Terminal Link (RX Array)</label>
                            <input type="text" name="receiver_name" value="{{ old('receiver_name', $tower->receiver_name) }}" placeholder="Terminal Handle" class="w-full px-6 py-4 bg-white/10 border border-white/10 rounded-2xl text-white font-black placeholder-white/30 outline-none">
                            <input type="text" name="receiver_ip" value="{{ old('receiver_ip', $tower->receiver_ip) }}" placeholder="Control IP" class="w-full px-6 py-4 bg-white/10 border border-white/10 rounded-2xl text-white font-mono placeholder-white/30 outline-none" dir="ltr">
                            <select name="receiver_model_id" class="w-full px-6 py-4 bg-white/10 border border-white/10 rounded-2xl text-white font-bold outline-none">
                                <option value="" class="text-gray-900">Radio Profile...</option>
                                @foreach($deviceModels as $m)
                                <option value="{{ $m->id }}" class="text-gray-900" {{ old('receiver_model_id', $tower->receiver_model_id) == $m->id ? 'selected' : '' }}>{{ $m->model_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Governance -->
        <div class="glass-panel border border-white/40 rounded-[2.5rem] p-8 flex items-center justify-between shadow-xl mt-12 bg-white/80 backdrop-blur-md">
             <a href="{{ route('network.towers.index') }}" class="text-[11px] font-black text-gray-500 uppercase tracking-widest hover:text-gray-900 transition-colors">Discard Sequence</a>
             <div class="flex gap-4">
                 <button type="reset" class="px-8 py-3.5 bg-gray-100 text-gray-600 font-black text-[11px] uppercase tracking-widest rounded-2xl hover:bg-gray-200 transition-all">Reset Form</button>
                 <button type="submit" class="px-10 py-4 bg-indigo-600 text-white rounded-[2rem] font-black text-[11px] uppercase tracking-widest shadow-xl shadow-indigo-100 hover:scale-105 transition-all">
                    Commit Changes
                 </button>
             </div>
        </div>
    </form>
</div>

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('towerForm', () => ({
            siteType: '{{ old('type', $tower->type) }}',
            lat: '{{ old('lat', $tower->lat) }}',
            lng: '{{ old('lng', $tower->lng) }}',
            hasSolar: {{ old('has_solar', $tower->has_solar) ? 'true' : 'false' }},
            hasGenerator: {{ old('has_generator', $tower->has_generator) ? 'true' : 'false' }},
            hasGovElec: {{ old('has_government_electricity', $tower->has_government_electricity) ? 'true' : 'false' }},
            hasSubscription: {{ old('has_ampere_subscription', $tower->has_ampere_subscription) ? 'true' : 'false' }},
            serverId: '{{ old('mikrotik_server_id', $tower->mikrotik_server_id) }}',
            connectionType: '{{ old('connection_type', $tower->connection_type) }}',
            transmitterType: 'existing',
            map: null,
            marker: null,

            init() {
                this.$nextTick(() => {
                    this.initMap();
                });
            },

            initMap() {
                // Default to Damascus if no coordinates
                const initialLat = this.lat ? parseFloat(this.lat) : 33.5138;
                const initialLng = this.lng ? parseFloat(this.lng) : 36.2765;

                this.map = L.map('map').setView([initialLat, initialLng], this.lat ? 15 : 13);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(this.map);

                if (this.lat && this.lng) {
                    this.marker = L.marker([initialLat, initialLng]).addTo(this.map);
                }

                this.map.on('click', (e) => {
                    this.lat = e.latlng.lat.toFixed(6);
                    this.lng = e.latlng.lng.toFixed(6);
                    
                    if (this.marker) this.marker.setLatLng(e.latlng);
                    else this.marker = L.marker(e.latlng).addTo(this.map);
                });
            }
        }));
    });
</script>
@endpush
@endsection

```

## resources/views/network\towers\index.blade.php

```php
@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
        <div class="relative">
            <div class="absolute -top-4 -right-4 w-24 h-24 bg-indigo-500/10 rounded-full blur-2xl -z-10"></div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">إدارة مواقع الشبكة (Sites)</h1>
            <p class="text-xs font-bold text-indigo-600 uppercase tracking-widest mt-1 flex items-center gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-indigo-600 animate-pulse shadow-[0_0_8px_rgba(79,70,229,0.5)]"></span>
                Infrasturture Management Center
            </p>
        </div>
        <a href="{{ route('network.towers.create') }}" class="inline-flex items-center gap-3 px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-black rounded-2xl shadow-xl shadow-indigo-200/50 transition-all transform hover:scale-105 active:scale-95 group">
            <svg class="w-5 h-5 group-hover:rotate-90 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            <span>إضافة موقع جديد</span>
        </a>
    </div>

    <!-- Statistics Orbs -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-10">
        <!-- Total Towers -->
        <div class="glass-panel rounded-3xl p-8 relative overflow-hidden group hover:translate-y-[-4px] transition-all duration-500 hover:shadow-2xl hover:shadow-indigo-500/10">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-indigo-500/10 rounded-full blur-3xl group-hover:bg-indigo-500/20 transition-colors"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-600/10 flex items-center justify-center text-indigo-600">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                </div>
                <p class="text-gray-400 text-[10px] font-black uppercase tracking-widest mb-1">إجمالي المواقع</p>
                <p class="text-3xl font-black text-gray-900">{{ number_format($stats['totalTowers']) }}</p>
                <p class="mt-4 text-[11px] font-bold text-indigo-500 uppercase tracking-tighter">Sites Worldwide</p>
            </div>
        </div>

        <!-- Active -->
        <div class="glass-panel rounded-3xl p-8 relative overflow-hidden group hover:translate-y-[-4px] transition-all duration-500 hover:shadow-2xl hover:shadow-emerald-500/10">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-emerald-500/10 rounded-full blur-3xl group-hover:bg-emerald-500/20 transition-colors"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-600/10 flex items-center justify-center text-emerald-600">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <p class="text-gray-400 text-[10px] font-black uppercase tracking-widest mb-1">المواقع النشطة</p>
                <p class="text-3xl font-black text-gray-900">{{ number_format($stats['activeTowers']) }}</p>
                <div class="mt-4 flex items-center text-[11px] font-bold">
                    <span class="text-green-600 bg-green-50 px-2 py-0.5 rounded-full border border-green-100 flex items-center gap-1">
                        {{ $stats['totalTowers'] > 0 ? round(($stats['activeTowers'] / $stats['totalTowers']) * 100) : 0 }}% Efficiency
                    </span>
                </div>
            </div>
        </div>

        <!-- Maintenance -->
        <div class="glass-panel rounded-3xl p-8 relative overflow-hidden group hover:translate-y-[-4px] transition-all duration-500 hover:shadow-2xl hover:shadow-amber-500/10">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-amber-500/10 rounded-full blur-3xl group-hover:bg-amber-500/20 transition-colors"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-2xl bg-amber-600/10 flex items-center justify-center text-amber-600">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                </div>
                <p class="text-gray-400 text-[10px] font-black uppercase tracking-widest mb-1">في الصيانة</p>
                <p class="text-3xl font-black text-gray-900">{{ number_format($stats['maintenanceTowers']) }}</p>
                <p class="mt-4 text-[11px] font-bold text-amber-600 uppercase tracking-tighter">Requires Action</p>
            </div>
        </div>

        <!-- Equipment -->
        <div class="glass-panel rounded-3xl p-8 relative overflow-hidden group hover:translate-y-[-4px] transition-all duration-500 hover:shadow-2xl hover:shadow-purple-500/10">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-purple-500/10 rounded-full blur-3xl group-hover:bg-purple-500/20 transition-colors"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-2xl bg-purple-600/10 flex items-center justify-center text-purple-600">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/></svg>
                    </div>
                </div>
                <p class="text-gray-400 text-[10px] font-black uppercase tracking-widest mb-1">إجمالي الأجهزة</p>
                <p class="text-3xl font-black text-gray-900">{{ number_format($stats['totalRouters']) }}</p>
                <p class="mt-4 text-[11px] font-bold text-purple-600 uppercase tracking-tighter">Active Nodes</p>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="glass-panel rounded-3xl p-6 mb-10 relative overflow-hidden group">
        <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-indigo-500/5 rounded-full blur-3xl transition-all group-hover:bg-indigo-500/10"></div>
        <form method="GET" action="{{ route('network.towers.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-6 relative z-10">
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 mr-1">المحافظة</label>
                <select name="city" onchange="this.form.submit()" class="w-full py-3 px-6 bg-white/50 backdrop-blur-md border border-white/20 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:bg-white focus:border-indigo-500/30 transition-all font-bold text-gray-700 appearance-none">
                    <option value="all">كل المحافظات</option>
                    @foreach($cities as $city)
                        <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>{{ $city }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 mr-1">الحالة</label>
                <select name="status" onchange="this.form.submit()" class="w-full py-3 px-6 bg-white/50 backdrop-blur-md border border-white/20 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:bg-white focus:border-indigo-500/30 transition-all font-bold text-gray-700 appearance-none">
                    <option value="all">كل الحالات</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
                    <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>صيانة</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                </select>
            </div>

            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 mr-1">النوع</label>
                <select name="type" onchange="this.form.submit()" class="w-full py-3 px-6 bg-white/50 backdrop-blur-md border border-white/20 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:bg-white focus:border-indigo-500/30 transition-all font-bold text-gray-700 appearance-none">
                    <option value="all">كل التصنيفات</option>
                    <option value="tower" {{ request('type') == 'tower' ? 'selected' : '' }}>برج (Tower)</option>
                    <option value="building" {{ request('type') == 'building' ? 'selected' : '' }}>بناء (Building)</option>
                    <option value="cabinet" {{ request('type') == 'cabinet' ? 'selected' : '' }}>كبينة (Cabinet)</option>
                    <option value="office" {{ request('type') == 'office' ? 'selected' : '' }}>مركز (Office)</option>
                    <option value="pole" {{ request('type') == 'pole' ? 'selected' : '' }}>عمود (Pole)</option>
                </select>
            </div>

            <div class="flex items-end">
                <a href="{{ route('network.towers.index') }}" class="w-full py-3 bg-gray-100 hover:bg-gray-200 text-gray-600 font-black text-[11px] uppercase tracking-widest rounded-2xl transition-all text-center border border-transparent active:scale-95">
                    Reset Filters
                </a>
            </div>
        </form>
    </div>

    <!-- Towers Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
        @forelse($towers as $tower)
        <div class="glass-panel rounded-[2rem] hover:shadow-2xl hover:shadow-indigo-500/10 transition-all duration-500 hover:translate-y-[-8px] group relative overflow-hidden">
            <div class="absolute -top-20 -right-20 w-40 h-40 bg-indigo-500/5 rounded-full blur-[80px]"></div>
            
            <!-- Card Header -->
            <div class="p-8 pb-6 border-b border-white/10">
                <div class="flex items-start justify-between mb-6">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-[1.5rem] bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-xl group-hover:rotate-6 transition-transform duration-500">
                            <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <h3 class="font-black text-gray-900 text-xl truncate tracking-tight">{{ $tower->name }}</h3>
                            @if($tower->city)
                                <p class="text-[10px] text-indigo-600 font-black uppercase tracking-widest mt-1">📍 {{ $tower->city }}</p>
                            @endif
                        </div>
                    </div>
                    @if($tower->status === 'active')
                        <span class="inline-flex items-center px-3 py-1 rounded-xl text-[9px] font-black uppercase tracking-widest bg-green-500/10 text-green-600 border border-green-500/10">
                            <span class="w-1.5 h-1.5 bg-green-600 rounded-full ml-1.5 animate-pulse shadow-[0_0_8px_rgba(34,197,94,0.5)]"></span>
                            Online
                        </span>
                    @elseif($tower->status === 'maintenance')
                        <span class="inline-flex items-center px-3 py-1 rounded-xl text-[9px] font-black uppercase tracking-widest bg-amber-500/10 text-amber-600 border border-amber-500/10">
                             Maintenance
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-xl text-[9px] font-black uppercase tracking-widest bg-gray-500/10 text-gray-500 border border-gray-500/10">
                             Offline
                        </span>
                    @endif
                </div>
                
                @if($tower->location)
                    <p class="text-[11px] text-gray-500 leading-relaxed font-medium mb-4">{{ Str::limit($tower->location, 80) }}</p>
                @endif

                <div class="flex flex-wrap gap-2">
                    @php
                        $typeMap = [
                            'tower' => ['label' => 'Tower Site', 'icon' => '🗼', 'bg' => 'blue'],
                            'building' => ['label' => 'Building', 'icon' => '🏢', 'bg' => 'purple'],
                            'cabinet' => ['label' => 'Pop / Cabinet', 'icon' => '🗄️', 'bg' => 'emerald'],
                            'office' => ['label' => 'HQ Office', 'icon' => '🏛️', 'bg' => 'indigo'],
                            'pole' => ['label' => 'Mast / Pole', 'icon' => '📍', 'bg' => 'gray']
                        ];
                        $type = $typeMap[$tower->type] ?? $typeMap['pole'];
                    @endphp
                    <span class="px-3 py-1 bg-{{ $type['bg'] }}-500/5 text-{{ $type['bg'] }}-600 rounded-xl text-[9px] font-black uppercase tracking-widest border border-{{ $type['bg'] }}-500/10">
                        {{ $type['icon'] }} {{ $type['label'] }}
                    </span>
                </div>
            </div>

            <!-- Stats & Details -->
            <div class="p-8 space-y-5">
                <div class="flex items-center justify-between p-4 bg-white/50 backdrop-blur-md rounded-2xl border border-white/50 shadow-sm transition-all group-hover:bg-white group-hover:shadow-md">
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest flex items-center gap-3">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/></svg>
                        Devices Linked
                    </span>
                    <span class="font-black text-gray-900 text-lg">{{ $tower->routers_count }}</span>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    @if($tower->has_solar)
                        <div class="flex items-center gap-2 p-3 bg-yellow-500/5 border border-yellow-500/10 rounded-2xl transition-all group-hover:bg-yellow-50">
                            <span class="text-[9px] font-black text-yellow-700 uppercase tracking-tighter">☀️ Solar Power</span>
                        </div>
                    @endif
                    @if($tower->battery_count > 0)
                        <div class="flex items-center gap-2 p-3 bg-green-500/5 border border-green-500/10 rounded-2xl transition-all group-hover:bg-green-50">
                            <span class="text-[9px] font-black text-green-700 uppercase tracking-tighter">🔋 {{ $tower->battery_count }} Batteries</span>
                        </div>
                    @endif
                    @if($tower->has_generator)
                        <div class="flex items-center gap-2 p-3 bg-orange-500/5 border border-orange-500/10 rounded-2xl transition-all group-hover:bg-orange-50">
                            <span class="text-[9px] font-black text-orange-700 uppercase tracking-tighter">⚡ Generator</span>
                        </div>
                    @endif
                </div>

                @if($tower->monthly_rent || $tower->monthly_maintenance)
                    <div class="pt-4 border-t border-white/20">
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Monthly Overheads</p>
                        <p class="font-black text-gray-900 text-lg">
                            @money(($tower->monthly_rent ?? 0) + ($tower->monthly_maintenance ?? 0))
                        </p>
                    </div>
                @endif
            </div>

            <!-- Action Buttons -->
            <div class="px-8 py-5 bg-white/20 backdrop-blur-xl border-t border-white/10 flex items-center gap-4">
                <a href="{{ route('network.towers.show', $tower) }}" class="flex-1 text-center py-3 bg-gray-900 hover:bg-black text-white font-black text-[11px] uppercase tracking-widest rounded-2xl shadow-xl hover:shadow-gray-900/20 transition-all active:scale-95">
                    View Site
                </a>
                <div class="flex items-center gap-2">
                    <a href="{{ route('network.towers.edit', $tower) }}" class="w-11 h-11 flex items-center justify-center bg-white/50 backdrop-blur-md text-gray-400 hover:text-indigo-600 hover:bg-white rounded-2xl border border-white/50 transition-all">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </a>
                    <form action="{{ route('network.towers.destroy', $tower) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا البرج؟')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-11 h-11 flex items-center justify-center bg-white/50 backdrop-blur-md text-gray-300 hover:text-red-500 hover:bg-white rounded-2xl border border-white/50 transition-all">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full font-inter">
            <div class="glass-panel rounded-[3rem] p-24 text-center relative overflow-hidden group">
                <div class="absolute -top-10 -right-10 w-48 h-48 bg-indigo-500/5 rounded-full blur-3xl group-hover:bg-indigo-500/10 transition-all duration-700"></div>
                <div class="w-24 h-24 bg-white/50 backdrop-blur-md rounded-[2rem] flex items-center justify-center mx-auto mb-10 border border-white/30 shadow-xl group-hover:scale-110 transition-transform duration-500">
                    <svg class="w-12 h-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <h3 class="text-3xl font-black text-gray-900">No Infrastructure Sites Found</h3>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-[0.2em] mt-3 mb-10">We couldn't find any network locations matching your current filters</p>
                <a href="{{ route('network.towers.index') }}" class="inline-flex items-center gap-3 px-10 py-4 bg-gray-900 hover:bg-black text-white font-black text-[11px] uppercase tracking-widest rounded-2xl transition-all shadow-xl hover:shadow-gray-900/30 active:scale-95">
                    Clear Active Filters
                </a>
            </div>
        </div>
        @endforelse
    </div>
</div>
@endsection

```

## resources/views/network\towers\show.blade.php

```php
@extends('layouts.app')

@section('content')
<div class="space-y-6" x-data="{ activeTab: '{{ request('tab', 'overview') }}' }">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-8 mb-10">
        <div class="flex items-center gap-6">
            <a href="{{ route('network.towers.index') }}" class="w-12 h-12 rounded-2xl bg-white/40 backdrop-blur-md flex items-center justify-center text-gray-400 hover:text-indigo-600 hover:bg-white border border-white/30 transition-all shadow-sm group">
                <svg class="w-6 h-6 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div class="relative">
                <div class="absolute -top-6 -right-6 w-20 h-20 bg-indigo-500/10 rounded-full blur-2xl -z-10"></div>
                <div class="flex items-center gap-4 mb-1">
                    <h2 class="text-3xl font-black text-gray-900 tracking-tight">{{ $tower->name }}</h2>
                    @if($tower->status === 'active')
                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-emerald-500/10 text-emerald-600 border border-emerald-500/20 flex items-center gap-2">
                             <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                             Operational
                        </span>
                    @elseif($tower->status === 'maintenance')
                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-amber-500/10 text-amber-600 border border-amber-500/20 flex items-center gap-2">
                             <span class="w-1.5 h-1.5 bg-amber-500 rounded-full"></span>
                             Maintenance
                        </span>
                    @else
                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-gray-500/10 text-gray-600 border border-gray-500/20 flex items-center gap-2">
                             <span class="w-1.5 h-1.5 bg-gray-400 rounded-full"></span>
                             Offline
                        </span>
                    @endif
                </div>
                <p class="text-xs font-bold text-indigo-600 uppercase tracking-[0.2em] opacity-70">
                    Infrastructure Unit: <span class="text-gray-900">{{ $tower->location ?? 'Global Node' }}</span>
                </p>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <a href="{{ route('network.towers.edit', $tower) }}" class="px-6 py-3 bg-white/50 backdrop-blur-md text-gray-700 font-black text-[11px] uppercase tracking-widest rounded-2xl border border-white/40 hover:bg-white transition-all shadow-sm flex items-center gap-3">
                <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                Modify Node
            </a>
            <div class="h-8 w-px bg-gray-900/10 mx-2"></div>
            <div class="flex -space-x-3 overflow-hidden">
                <div class="w-10 h-10 rounded-2xl bg-indigo-100 flex items-center justify-center border-2 border-white text-indigo-600 font-black text-xs">A</div>
                <div class="w-10 h-10 rounded-2xl bg-emerald-100 flex items-center justify-center border-2 border-white text-emerald-600 font-black text-xs">M</div>
                <div class="w-10 h-10 rounded-2xl bg-blue-100 flex items-center justify-center border-2 border-white text-blue-600 font-black text-xs shadow-lg">+</div>
            </div>
        </div>
    </div>

    {{-- Quick Stats / Metric Orbs --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        {{-- Equipment Orb --}}
        <div class="glass-panel rounded-[2rem] p-6 relative overflow-hidden group hover:scale-[1.02] transition-all cursor-default">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-blue-500/10 rounded-full blur-3xl group-hover:bg-blue-500/20 transition-all"></div>
            <div class="relative flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Hardware Units</p>
                    <h3 class="text-4xl font-black text-gray-900 tracking-tighter">{{ $tower->routers->count() }}</h3>
                    <p class="text-[9px] font-bold text-blue-600 uppercase mt-1">Routers & Access Points</p>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-blue-500/10 flex items-center justify-center text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-all shadow-inner">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/></svg>
                </div>
            </div>
        </div>

        {{-- SSIDs Orb --}}
        <div class="glass-panel rounded-[2rem] p-6 relative overflow-hidden group hover:scale-[1.02] transition-all cursor-default">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-purple-500/10 rounded-full blur-3xl group-hover:bg-purple-500/20 transition-all"></div>
            <div class="relative flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Wireless SSIDs</p>
                    <h3 class="text-4xl font-black text-gray-900 tracking-tighter">{{ $tower->ssids->count() }}</h3>
                    <p class="text-[9px] font-bold text-purple-600 uppercase mt-1">Broadcasting Interfaces</p>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-purple-500/10 flex items-center justify-center text-purple-600 group-hover:bg-purple-600 group-hover:text-white transition-all shadow-inner">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-15.857 21.213 0"/></svg>
                </div>
            </div>
        </div>

        {{-- Clients Orb --}}
        <div class="glass-panel rounded-[2rem] p-6 relative overflow-hidden group hover:scale-[1.02] transition-all cursor-default">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-emerald-500/10 rounded-full blur-3xl group-hover:bg-emerald-500/20 transition-all"></div>
            <div class="relative flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Active Subscribers</p>
                    <h3 class="text-4xl font-black text-gray-900 tracking-tighter">{{ $tower->clients->count() }}</h3>
                    <p class="text-[9px] font-bold text-emerald-600 uppercase mt-1">Connected Customers</p>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-emerald-500/10 flex items-center justify-center text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-all shadow-inner">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
            </div>
        </div>

        {{-- Costs Orb --}}
        <div class="glass-panel rounded-[2rem] p-6 relative overflow-hidden group hover:scale-[1.02] transition-all cursor-default">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-amber-500/10 rounded-full blur-3xl group-hover:bg-amber-500/20 transition-all"></div>
            <div class="relative flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Operating Burn</p>
                    <h3 class="text-4xl font-black text-gray-900 tracking-tighter">{{ number_format($tower->monthly_fixed_costs ?? 0, 0) }}</h3>
                    <p class="text-[9px] font-bold text-amber-600 uppercase mt-1">Monthly Fixed Overhead</p>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-amber-500/10 flex items-center justify-center text-amber-600 group-hover:bg-amber-600 group-hover:text-white transition-all shadow-inner">
                    <span class="text-xl font-black">{{ $currency }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabs Navigation --}}
    <div class="glass-panel rounded-[2.5rem] overflow-hidden mb-10 border border-white/40 shadow-xl">
        <div class="border-b border-gray-900/5 bg-white/30 backdrop-blur-md">
            <nav class="flex overflow-x-auto no-scrollbar p-2 gap-2">
                <button @click="activeTab = 'overview'" :class="activeTab === 'overview' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-200' : 'text-gray-500 hover:bg-white/50 hover:text-gray-900'" class="px-8 py-4 rounded-2xl font-black text-[11px] uppercase tracking-widest transition-all whitespace-nowrap flex items-center gap-3">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    Overview
                </button>
                <button @click="activeTab = 'equipment'" :class="activeTab === 'equipment' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-200' : 'text-gray-500 hover:bg-white/50 hover:text-gray-900'" class="px-8 py-4 rounded-2xl font-black text-[11px] uppercase tracking-widest transition-all whitespace-nowrap flex items-center gap-3">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h2M4 8h16"/></svg>
                    Infrastructure
                </button>
                <button @click="activeTab = 'power'" :class="activeTab === 'power' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-200' : 'text-gray-500 hover:bg-white/50 hover:text-gray-900'" class="px-8 py-4 rounded-2xl font-black text-[11px] uppercase tracking-widest transition-all whitespace-nowrap flex items-center gap-3">
                   <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                   Energy
                </button>
                <button @click="activeTab = 'costs'" :class="activeTab === 'costs' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-200' : 'text-gray-500 hover:bg-white/50 hover:text-gray-900'" class="px-8 py-4 rounded-2xl font-black text-[11px] uppercase tracking-widest transition-all whitespace-nowrap flex items-center gap-3">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Ledger
                </button>
                <button @click="activeTab = 'connection'" :class="activeTab === 'connection' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-200' : 'text-gray-500 hover:bg-white/50 hover:text-gray-900'" class="px-8 py-4 rounded-2xl font-black text-[11px] uppercase tracking-widest transition-all whitespace-nowrap flex items-center gap-3">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                    Uplink
                </button>
            </nav>
        </div>

        {{-- Tab Content --}}
        <div class="p-6">
            {{-- Overview Tab --}}
            <div x-show="activeTab === 'overview'" x-transition class="space-y-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    {{-- Basic Info --}}
                    <div class="bg-gray-900/5 rounded-3xl p-8 border border-white/50 relative overflow-hidden group">
                        <div class="absolute -top-10 -right-10 w-24 h-24 bg-gray-900/5 rounded-full blur-2xl"></div>
                        <div class="flex items-center justify-between mb-8">
                            <h3 class="text-lg font-black text-gray-900 flex items-center gap-3 uppercase tracking-tight">
                                <span class="w-1.5 h-6 bg-gray-900 rounded-full"></span>
                                Primary Intelligence
                            </h3>
                            <a href="{{ route('network.towers.edit', [$tower, 'tab' => 'basic']) }}" class="w-8 h-8 rounded-xl bg-white/50 flex items-center justify-center text-gray-400 hover:text-indigo-600 hover:bg-white transition-all shadow-sm">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            </a>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-white/40 backdrop-blur-md p-4 rounded-2xl border border-white/60">
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Architecture</p>
                                <p class="text-sm font-black text-gray-900">
                                    @if($tower->type === 'tower') 🗼 Radio Tower
                                    @elseif($tower->type === 'building') 🏢 Structural Roof
                                    @else 📍 Support Pole
                                    @endif
                                </p>
                            </div>
                            <div class="bg-white/40 backdrop-blur-md p-4 rounded-2xl border border-white/60">
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Altitude</p>
                                <p class="text-sm font-black text-gray-900">{{ $tower->height ?? '0' }}m AGL</p>
                            </div>
                            <div class="bg-white/40 backdrop-blur-md p-4 rounded-2xl border border-white/60 col-span-2">
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Coordinates</p>
                                <p class="text-sm font-mono font-black text-indigo-600 select-all">{{ round($tower->lat, 6) }}, {{ round($tower->lng, 6) }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Server Info --}}
                    <div class="bg-indigo-900 rounded-3xl p-8 relative overflow-hidden group shadow-2xl">
                        <div class="absolute -bottom-20 -left-20 w-48 h-48 bg-indigo-500/20 rounded-full blur-3xl group-hover:scale-125 transition-all"></div>
                        <div class="relative z-10">
                            <h3 class="text-lg font-black text-white flex items-center gap-3 uppercase tracking-tight mb-8">
                                <span class="w-1.5 h-6 bg-indigo-400 rounded-full"></span>
                                Uplink Controller
                            </h3>
                            @if($tower->mikrotikServer)
                            <div class="bg-white/10 backdrop-blur-xl rounded-2xl p-6 border border-white/10">
                                <div class="flex items-center justify-between mb-4">
                                    <div>
                                        <p class="text-[10px] font-black text-indigo-300 uppercase tracking-widest mb-1">Active Gateway</p>
                                        <p class="text-xl font-black text-white tracking-tight">{{ $tower->mikrotikServer->name }}</p>
                                    </div>
                                    <div class="w-12 h-12 rounded-2xl bg-indigo-500/20 flex items-center justify-center text-indigo-300 border border-white/5">
                                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/></svg>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-mono text-indigo-200 select-all">{{ $tower->mikrotikServer->ip }}</span>
                                    <a href="{{ route('servers.show', $tower->mikrotikServer) }}" class="text-[10px] font-black text-white uppercase tracking-widest bg-indigo-500 hover:bg-white hover:text-indigo-900 px-4 py-2 rounded-xl transition-all">
                                        View Logic
                                    </a>
                                </div>
                            </div>
                            @else
                            <div class="bg-rose-500/20 backdrop-blur-md rounded-2xl p-8 border border-white/10 text-center">
                                <p class="text-xs font-black text-rose-200 uppercase tracking-widest">No Controller Sync</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Power Summary --}}
                <div class="glass-panel rounded-3xl p-8 relative overflow-hidden group">
                    <div class="absolute -top-10 -right-10 w-32 h-32 bg-amber-500/5 rounded-full blur-3xl"></div>
                    <h3 class="text-lg font-black text-gray-900 mb-8 flex items-center gap-3 uppercase tracking-tight">
                        <span class="w-1.5 h-6 bg-amber-500 rounded-full"></span>
                        Energy Infrastructure
                    </h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="bg-white/40 backdrop-blur-md p-6 rounded-3xl border border-white/60 text-center group/item hover:bg-white transition-all cursor-default shadow-sm">
                            <div class="w-12 h-12 bg-amber-500/10 rounded-2xl flex items-center justify-center text-2xl mx-auto mb-4 group-hover/item:scale-110 transition-transform">🔋</div>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Batteries</p>
                            <p class="text-xl font-black text-gray-900 tracking-tight">{{ $tower->battery_count ?? '0' }}x Units</p>
                        </div>
                        <div class="bg-white/40 backdrop-blur-md p-6 rounded-3xl border border-white/60 text-center group/item hover:bg-white transition-all cursor-default shadow-sm">
                            <div class="w-12 h-12 bg-amber-500/10 rounded-2xl flex items-center justify-center text-2xl mx-auto mb-4 group-hover/item:scale-110 transition-transform">☀️</div>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Solar Intake</p>
                            <p class="text-xl font-black text-gray-900 tracking-tight">{{ $tower->has_solar ? ($tower->solar_panels_count ?? '✓') : 'Inactive' }}</p>
                        </div>
                        <div class="bg-white/40 backdrop-blur-md p-6 rounded-3xl border border-white/60 text-center group/item hover:bg-white transition-all cursor-default shadow-sm">
                            <div class="w-12 h-12 bg-amber-500/10 rounded-2xl flex items-center justify-center text-2xl mx-auto mb-4 group-hover/item:scale-110 transition-transform">⚡</div>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Generator</p>
                            <p class="text-xl font-black text-gray-900 tracking-tight">{{ $tower->has_generator ? $tower->generator_capacity : 'None' }}</p>
                        </div>
                        <div class="bg-white/40 backdrop-blur-md p-6 rounded-3xl border border-white/60 text-center group/item hover:bg-white transition-all cursor-default shadow-sm">
                            <div class="w-12 h-12 bg-amber-500/10 rounded-2xl flex items-center justify-center text-2xl mx-auto mb-4 group-hover/item:scale-110 transition-transform">🔌</div>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Inverter</p>
                            <p class="text-xl font-black text-gray-900 tracking-tight">{{ $tower->has_inverter ? $tower->inverter_capacity : 'None' }}</p>
                        </div>
                    </div>
                </div>

                @if($tower->notes)
                <div class="bg-indigo-600 rounded-[2.5rem] p-8 text-white relative overflow-hidden shadow-2xl">
                    <div class="absolute -top-24 -right-24 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
                    <h4 class="text-[10px] font-black text-indigo-200 uppercase tracking-[0.2em] mb-4 flex items-center gap-3">
                        <span class="w-2 h-2 bg-white rounded-full animate-pulse"></span>
                        Observer Logs
                    </h4>
                    <p class="text-lg font-bold leading-relaxed opacity-90 whitespace-pre-line">{{ $tower->notes }}</p>
                </div>
                @endif
            </div>

            {{-- Equipment & SSIDs Tab --}}
            {{-- Equipment & SSIDs Tab --}}
            <div x-show="activeTab === 'equipment'" x-transition class="space-y-12">
                {{-- Wireless Devices (Sectors/Antennas) --}}
                <div>
                     <div class="flex items-center justify-between mb-8">
                        <div class="flex items-center gap-4">
                            <h3 class="text-2xl font-black text-gray-900 tracking-tight uppercase">Radio Access Units</h3>
                            <span class="bg-indigo-600 text-white px-3 py-1 rounded-xl text-[10px] font-black shadow-lg shadow-indigo-100">{{ $tower->wireless_devices->count() }}</span>
                        </div>
                        <button @click="$dispatch('open-device-modal', { type: 'wireless' })" type="button" class="px-6 py-3 bg-white hover:bg-indigo-600 hover:text-white text-indigo-600 font-black text-[11px] uppercase tracking-widest rounded-2xl border border-indigo-100 transition-all shadow-sm flex items-center gap-2 group">
                            <svg class="w-4 h-4 group-hover:rotate-90 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                            Add Radio Unit
                        </button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @forelse($tower->wireless_devices as $device)
                        <div class="glass-panel border border-white/40 rounded-[2.5rem] overflow-hidden group hover:scale-[1.02] transition-all shadow-xl">
                            <!-- Device Header -->
                            <div class="p-6 flex items-start justify-between bg-white/40 backdrop-blur-md border-b border-white/60">
                                <div class="flex items-center gap-4">
                                    <div class="w-14 h-14 bg-indigo-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-indigo-100 relative group-hover:scale-110 transition-transform">
                                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-15.857 21.213 0"/></svg>
                                        <div class="absolute -top-1 -right-1 w-4 h-4 bg-emerald-500 border-2 border-white rounded-full"></div>
                                    </div>
                                    <div>
                                        <p class="font-black text-gray-900 text-lg tracking-tight">{{ $device->name }}</p>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span class="text-[9px] font-black text-indigo-600 uppercase tracking-widest bg-indigo-50 px-2 py-0.5 rounded-lg border border-indigo-100">{{ $device->mode }}</span>
                                            <span class="text-[9px] font-mono font-bold text-gray-400 select-all">{{ $device->ip }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <form action="{{ route('network.towers.devices.destroy', [$tower, $device]) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا الجهاز؟')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-10 h-10 flex items-center justify-center text-gray-300 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-all">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <!-- Device Stats/Meta -->
                            <div class="p-6 grid grid-cols-2 gap-4">
                                <div class="bg-white/50 backdrop-blur-md px-4 py-3 rounded-2xl border border-white/80">
                                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Frequency</p>
                                    <p class="text-sm font-black text-gray-900 tracking-tight">{{ $device->frequency ?? '5GHz' }}</p>
                                </div>
                                <div class="bg-white/50 backdrop-blur-md px-4 py-3 rounded-2xl border border-white/80">
                                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Main SSID</p>
                                    <p class="text-sm font-black text-indigo-600 tracking-tight">{{ $device->ssid ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="md:col-span-2 py-16 text-center glass-panel border border-dashed border-gray-300 rounded-[2.5rem]">
                            <p class="text-[11px] font-black text-gray-400 uppercase tracking-[0.2em]">No Wireless Infrastructure</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                {{-- Network Switches Section --}}
                <div>
                     <div class="flex items-center justify-between mb-8">
                        <div class="flex items-center gap-4">
                            <h3 class="text-2xl font-black text-gray-900 tracking-tight uppercase">Network Switches</h3>
                            <span class="bg-emerald-600 text-white px-3 py-1 rounded-xl text-[10px] font-black shadow-lg shadow-emerald-100">{{ $tower->switch_devices->count() }}</span>
                        </div>
                        <button @click="$dispatch('open-device-modal', { type: 'switch' })" type="button" class="px-6 py-3 bg-white hover:bg-emerald-600 hover:text-white text-emerald-600 font-black text-[11px] uppercase tracking-widest rounded-2xl border border-emerald-100 transition-all shadow-sm flex items-center gap-2 group">
                            <svg class="w-4 h-4 group-hover:rotate-90 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                            Add Switch
                        </button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @forelse($tower->switch_devices as $switch)
                        <div class="glass-panel border border-white/40 rounded-[2.5rem] overflow-hidden group hover:scale-[1.02] transition-all shadow-xl">
                            <div class="p-6 flex items-start justify-between bg-white/40 backdrop-blur-md border-b border-white/60">
                                <div class="flex items-center gap-4">
                                    <div class="w-14 h-14 bg-emerald-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-emerald-100 relative group-hover:scale-110 transition-transform">
                                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/></svg>
                                        <div class="absolute -top-1 -right-1 w-4 h-4 bg-emerald-500 border-2 border-white rounded-full animate-pulse"></div>
                                    </div>
                                    <div>
                                        <p class="font-black text-gray-900 text-lg tracking-tight">{{ $switch->name }}</p>
                                        <p class="text-[9px] font-mono font-bold text-gray-400 select-all mt-1">{{ $switch->ip ?? 'OFFLINE_NODE' }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="px-3 py-1 bg-emerald-500/10 text-emerald-600 rounded-xl text-[10px] font-black uppercase tracking-widest border border-emerald-500/20">{{ $switch->ports_count ?? '?' }} Port</span>
                                    <form action="{{ route('network.towers.devices.destroy', [$tower, $switch]) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا السويتش؟')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-10 h-10 flex items-center justify-center text-gray-300 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-all">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @if($switch->deviceModel)
                            <div class="px-6 py-4 bg-white/30 backdrop-blur-md border-t border-white/40 flex justify-between items-center">
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Hardware Profile</p>
                                <p class="text-xs font-black text-gray-700 tracking-tight">{{ $switch->deviceModel->manufacturer }} {{ $switch->deviceModel->model_name }}</p>
                            </div>
                            @endif
                        </div>
                        @empty
                        <div class="md:col-span-2 py-16 text-center glass-panel border border-dashed border-gray-300 rounded-[2.5rem]">
                            <p class="text-[11px] font-black text-gray-400 uppercase tracking-[0.2em]">No Switching Capacity Detected</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                {{-- Unassigned SSIDs --}}
                @php
                    $unassignedSsids = $tower->ssids->whereNull('tower_device_id');
                @endphp
                @if($unassignedSsids->count() > 0)
                <div class="pt-12 border-t border-gray-900/5">
                    <div class="flex items-center justify-between mb-8">
                        <div class="flex items-center gap-4">
                            <h3 class="text-xl font-black text-gray-900 tracking-tight uppercase">Orphaned Interfaces</h3>
                            <span class="bg-gray-100 text-gray-500 px-3 py-1 rounded-xl text-[10px] font-black">{{ $unassignedSsids->count() }}</span>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($unassignedSsids as $ssid)
                        <div class="glass-panel border border-white/40 rounded-[2.5rem] p-6 opacity-60 grayscale hover:grayscale-0 hover:opacity-100 transition-all group">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-12 h-12 bg-gray-200 rounded-2xl flex items-center justify-center text-gray-400 group-hover:bg-indigo-600 group-hover:text-white transition-all shadow-inner">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-15.857 21.213 0"/></svg>
                                </div>
                                <div>
                                    <p class="font-black text-gray-900 tracking-tight">{{ $ssid->ssid_name }}</p>
                                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mt-1">{{ $ssid->frequency }}</p>
                                </div>
                            </div>
                            <div class="flex items-center justify-between mt-6">
                                <span class="text-[8px] font-black text-rose-600 uppercase tracking-[0.2em] bg-rose-50 px-3 py-1 rounded-full border border-rose-100">Unassigned Hook</span>
                                <button @click="$dispatch('open-edit-ssid-modal', { ssidId: '{{ $ssid->id }}', ssidName: '{{ $ssid->ssid_name }}', frequency: '{{ $ssid->frequency }}', isActive: {{ $ssid->is_active ? 'true' : 'false' }}, notes: '{{ $ssid->notes }}' })" class="text-[9px] font-black text-indigo-600 uppercase tracking-widest hover:underline">Link Now</button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            {{-- Power Tab --}}
            <div x-show="activeTab === 'power'" x-transition class="space-y-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    @if($tower->battery_count > 0)
                    <div class="glass-panel rounded-[2.5rem] p-8 border border-white/40 relative overflow-hidden group">
                        <div class="absolute -top-10 -right-10 w-24 h-24 bg-blue-500/5 rounded-full blur-2xl"></div>
                        <h4 class="text-lg font-black text-gray-900 mb-8 flex items-center gap-4 uppercase tracking-tight">
                            <div class="w-12 h-12 bg-blue-500/10 rounded-2xl flex items-center justify-center text-2xl">🔋</div>
                            Energy Storage
                        </h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-white/40 backdrop-blur-md p-4 rounded-2xl border border-white/60">
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Cell Count</p>
                                <p class="text-sm font-black text-gray-900">{{ $tower->battery_count }} Units</p>
                            </div>
                            <div class="bg-white/40 backdrop-blur-md p-4 rounded-2xl border border-white/60">
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Chemistry</p>
                                <p class="text-sm font-black text-gray-900 font-mono tracking-tighter">{{ $tower->battery_type }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($tower->has_solar)
                    <div class="glass-panel rounded-[2.5rem] p-8 border border-white/40 relative overflow-hidden group">
                        <div class="absolute -top-10 -right-10 w-24 h-24 bg-amber-500/5 rounded-full blur-2xl"></div>
                        <h4 class="text-lg font-black text-gray-900 mb-8 flex items-center gap-4 uppercase tracking-tight">
                            <div class="w-12 h-12 bg-amber-500/10 rounded-2xl flex items-center justify-center text-2xl">☀️</div>
                            Photovoltaic Array
                        </h4>
                        <div class="grid grid-cols-3 gap-4">
                            <div class="bg-white/40 backdrop-blur-md p-4 rounded-2xl border border-white/60">
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Panels</p>
                                <p class="text-sm font-black text-gray-900">{{ $tower->solar_panels_count ?? '0' }}</p>
                            </div>
                            <div class="bg-white/40 backdrop-blur-md p-4 rounded-2xl border border-white/60">
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Unit Wattage</p>
                                <p class="text-sm font-black text-gray-900">{{ $tower->solar_panel_wattage }}W</p>
                            </div>
                            <div class="bg-indigo-600 p-4 rounded-2xl shadow-lg shadow-indigo-100">
                                <p class="text-[9px] font-black text-indigo-200 uppercase tracking-widest mb-1">Total</p>
                                <p class="text-sm font-black text-white">{{ $tower->total_solar_capacity }}W</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($tower->has_generator)
                    <div class="glass-panel rounded-[2.5rem] p-8 border border-white/40 relative overflow-hidden group">
                        <div class="absolute -top-10 -right-10 w-24 h-24 bg-orange-500/5 rounded-full blur-2xl"></div>
                        <h4 class="text-lg font-black text-gray-900 mb-8 flex items-center gap-4 uppercase tracking-tight">
                            <div class="w-12 h-12 bg-orange-500/10 rounded-2xl flex items-center justify-center text-2xl">⚡</div>
                            Internal Combustion
                        </h4>
                        <div class="bg-white/40 backdrop-blur-md p-4 rounded-2xl border border-white/60">
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Fuel Capacity / Rating</p>
                            <p class="text-sm font-black text-gray-900 font-mono">{{ $tower->generator_capacity }}</p>
                        </div>
                    </div>
                    @endif

                    @if($tower->has_inverter)
                    <div class="glass-panel rounded-[2.5rem] p-8 border border-white/40 relative overflow-hidden group">
                        <div class="absolute -top-10 -right-10 w-24 h-24 bg-emerald-500/5 rounded-full blur-2xl"></div>
                        <h4 class="text-lg font-black text-gray-900 mb-8 flex items-center gap-4 uppercase tracking-tight">
                            <div class="w-12 h-12 bg-emerald-500/10 rounded-2xl flex items-center justify-center text-2xl">🔌</div>
                                Conversion Hub
                        </h4>
                        <div class="bg-white/40 backdrop-blur-md p-4 rounded-2xl border border-white/60">
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Output Load Rating</p>
                            <p class="text-sm font-black text-gray-900 font-mono">{{ $tower->inverter_capacity }}</p>
                        </div>
                    </div>
                    @endif
                </div>

                @if(!$tower->battery_count && !$tower->has_solar && !$tower->has_generator && !$tower->has_inverter)
                <div class="py-24 text-center glass-panel rounded-[3rem] border border-dashed border-gray-300">
                    <p class="text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4">Energy Signature: Undetected</p>
                    <a href="{{ route('network.towers.edit', [$tower, 'tab' => 'power']) }}" class="inline-flex px-8 py-4 bg-indigo-600 text-white rounded-[2rem] font-black text-[11px] uppercase tracking-widest shadow-xl shadow-indigo-100 hover:scale-105 transition-all">Synchronize Specs</a>
                </div>
                @endif
            </div>

            {{-- Costs Tab --}}
            <div x-show="activeTab === 'costs'" x-transition class="space-y-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    {{-- Monthly Fixed Costs --}}
                    <div class="glass-panel rounded-[2.5rem] p-8 border border-white/40 shadow-xl overflow-hidden group">
                        <div class="absolute -top-12 -right-12 w-32 h-32 bg-indigo-600/5 rounded-full blur-3xl group-hover:scale-150 transition-all"></div>
                        <h4 class="text-lg font-black text-gray-900 mb-8 flex items-center gap-3 uppercase tracking-tight relative">
                            <span class="w-1.5 h-6 bg-indigo-600 rounded-full"></span>
                            Operational Burn
                        </h4>
                        <div class="space-y-4 relative">
                            @if($tower->monthly_rent)
                            <div class="bg-white/40 backdrop-blur-md p-5 rounded-2xl border border-white/60 flex items-center justify-between">
                                <div>
                                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Node Lease</p>
                                    <p class="text-sm font-black text-gray-900">{{ number_format($tower->monthly_rent, 0) }} {{ $currency }}</p>
                                </div>
                                <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-400">🏢</div>
                            </div>
                            @endif
                            @if($tower->monthly_maintenance)
                            <div class="bg-white/40 backdrop-blur-md p-5 rounded-2xl border border-white/60 flex items-center justify-between">
                                <div>
                                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Field Support</p>
                                    <p class="text-sm font-black text-gray-900">{{ number_format($tower->monthly_maintenance, 0) }} {{ $currency }}</p>
                                </div>
                                <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-400">🔧</div>
                            </div>
                            @endif
                            <div class="bg-gray-900 p-6 rounded-3xl shadow-2xl relative overflow-hidden">
                                <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -translate-x-1/2 translate-y-1/2 blur-2xl"></div>
                                <p class="text-[10px] font-black text-indigo-300 uppercase tracking-[0.2em] mb-1">Total Monthly Liability</p>
                                <p class="text-3xl font-black text-white tracking-tighter">{{ number_format($tower->monthly_fixed_costs ?? 0, 0) }} <span class="text-lg opacity-50">{{ $currency }}</span></p>
                            </div>
                        </div>
                    </div>

                    {{-- Equipment Costs --}}
                    <div class="glass-panel rounded-[2.5rem] p-8 border border-white/40 shadow-xl overflow-hidden group">
                        <div class="absolute -top-12 -right-12 w-32 h-32 bg-emerald-600/5 rounded-full blur-3xl group-hover:scale-150 transition-all"></div>
                        <h4 class="text-lg font-black text-gray-900 mb-8 flex items-center gap-3 uppercase tracking-tight relative">
                            <span class="w-1.5 h-6 bg-emerald-500 rounded-full"></span>
                            Capital Investment
                        </h4>
                        <div class="space-y-4 relative">
                            @if($tower->structure_cost)
                            <div class="bg-white/40 backdrop-blur-md p-5 rounded-2xl border border-white/60 flex items-center justify-between">
                                <div>
                                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Structural Build</p>
                                    <p class="text-sm font-black text-gray-900">{{ number_format($tower->structure_cost, 0) }} {{ $currency }}</p>
                                </div>
                                <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-400">🗼</div>
                            </div>
                            @endif
                            @if($tower->solar_installation_cost)
                            <div class="bg-white/40 backdrop-blur-md p-5 rounded-2xl border border-white/60 flex items-center justify-between">
                                <div>
                                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Energy Installation</p>
                                    <p class="text-sm font-black text-gray-900">{{ number_format($tower->solar_installation_cost, 0) }} {{ $currency }}</p>
                                </div>
                                <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-400">☀️</div>
                            </div>
                            @endif
                            <div class="bg-white p-6 rounded-3xl border border-emerald-500/20 shadow-lg relative overflow-hidden group/burn">
                                <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-500/5 rounded-full -translate-x-1/2 translate-y-1/2 blur-2xl group-hover/burn:scale-150 transition-all"></div>
                                <p class="text-[10px] font-black text-emerald-600 uppercase tracking-[0.2em] mb-1">Total CAPEX Evaluation</p>
                                <p class="text-3xl font-black text-gray-900 tracking-tighter">{{ number_format($tower->total_equipment_cost ?? 0, 0) }} <span class="text-lg text-gray-300 font-normal">{{ $currency }}</span></p>
                            </div>
                        </div>
                    </div>
                </div>

                @if($tower->monthly_notes)
                <div class="bg-amber-600 rounded-[2.5rem] p-8 text-white relative overflow-hidden shadow-2xl">
                    <div class="absolute -top-24 -right-24 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
                    <h4 class="text-[10px] font-black text-amber-200 uppercase tracking-[0.2em] mb-4 flex items-center gap-3">
                        <span class="w-2 h-2 bg-white rounded-full animate-pulse"></span>
                        Ledger Verification
                    </h4>
                    <p class="text-lg font-bold leading-relaxed opacity-90">{{ $tower->monthly_notes }}</p>
                </div>
                @endif
            </div>

            {{-- Connection Tab --}}
            <div x-show="activeTab === 'connection'" x-transition class="space-y-8">
                <div class="glass-panel rounded-[2.5rem] p-8 border border-white/40 shadow-xl overflow-hidden group">
                    <div class="absolute -top-12 -right-12 w-32 h-32 bg-indigo-600/5 rounded-full blur-3xl group-hover:scale-150 transition-all"></div>
                    <h4 class="text-lg font-black text-gray-900 mb-8 flex items-center gap-3 uppercase tracking-tight relative">
                        <span class="w-1.5 h-6 bg-indigo-600 rounded-full"></span>
                        Uplink Topology
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 relative">
                        <div class="col-span-1 md:col-span-2">
                             @if($tower->mikrotikServer)
                                <div class="bg-gray-900 p-8 rounded-[2rem] shadow-2xl relative overflow-hidden group/server">
                                    <div class="absolute top-0 right-0 w-48 h-48 bg-indigo-500/10 rounded-full -translate-x-1/2 translate-y-1/2 blur-3xl group-hover/server:scale-110 transition-all"></div>
                                    <div class="relative flex items-center justify-between">
                                        <div>
                                            <p class="text-[10px] font-black text-indigo-300 uppercase tracking-[0.2em] mb-2">Master Controller (Gateway)</p>
                                            <p class="text-2xl font-black text-white tracking-widest">{{ $tower->mikrotikServer->name }}</p>
                                            <p class="text-xs text-indigo-200 mt-1 font-mono opacity-60">{{ $tower->mikrotikServer->ip_address }}</p>
                                        </div>
                                        <a href="{{ route('servers.show', $tower->mikrotikServer) }}" class="w-14 h-14 bg-white/10 backdrop-blur-xl border border-white/10 rounded-2xl flex items-center justify-center text-white hover:bg-white hover:text-indigo-900 transition-all">
                                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>

                        @if($tower->connection_type)
                        <div class="bg-white/40 backdrop-blur-md p-6 rounded-2xl border border-white/60">
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Link Protocol</p>
                            <p class="text-sm font-black text-gray-900 uppercase tracking-tight">{{ $tower->connection_type }}</p>
                        </div>
                        @endif
                        @if($tower->connection_port)
                        <div class="bg-white/40 backdrop-blur-md p-6 rounded-2xl border border-white/60">
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Target Port</p>
                            <p class="text-sm font-black text-indigo-600 font-mono tracking-tighter">{{ $tower->connection_port }}</p>
                        </div>
                        @endif
                        
                        @if($tower->connection_type === 'wireless')
                            {{-- Transmitter Details --}}
                            <div class="md:col-span-2 mt-4 space-y-6">
                                <div class="flex items-center gap-4">
                                    <div class="h-px flex-1 bg-gradient-to-r from-transparent via-gray-200 to-transparent"></div>
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.3em]">Point-to-Point Analysis</p>
                                    <div class="h-px flex-1 bg-gradient-to-r from-transparent via-gray-200 to-transparent"></div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    {{-- Source / Transmitter --}}
                                    <div class="bg-white/30 backdrop-blur-sm p-6 rounded-[2rem] border border-white/50">
                                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-4">Radio Source (PTP-TX)</p>
                                        @if($tower->transmitterRouter)
                                            <div class="flex items-start gap-4">
                                                <div class="w-14 h-14 bg-indigo-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-indigo-100">
                                                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-15.857 21.213 0"/></svg>
                                                </div>
                                                <div>
                                                    <a href="{{ route('routers.show', $tower->transmitterRouter) }}" class="text-lg font-black text-indigo-600 hover:text-indigo-900 transition-colors">
                                                        {{ $tower->transmitterRouter->name }}
                                                    </a>
                                                    <p class="text-xs text-gray-500 font-mono mt-1 opacity-70">{{ $tower->transmitterRouter->ip }}</p>
                                                    @if($tower->transmitterRouter->tower)
                                                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mt-4">Located at:</p>
                                                        <a href="{{ route('network.towers.show', $tower->transmitterRouter->tower) }}" class="text-xs font-black text-gray-900 hover:text-indigo-600 flex items-center gap-2 mt-1">
                                                            <span class="w-1 h-1 bg-indigo-600 rounded-full"></span>
                                                            {{ $tower->transmitterRouter->tower->name }}
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Destination / Receiver --}}
                                    <div class="bg-indigo-600 p-6 rounded-[2rem] text-white shadow-2xl relative overflow-hidden group/rx">
                                        <div class="absolute -bottom-10 -right-10 w-32 h-32 bg-white/10 rounded-full blur-2xl group-hover/rx:scale-125 transition-all"></div>
                                        <p class="text-[9px] font-black text-indigo-200 uppercase tracking-widest mb-4 relative z-10">Local Terminal (PTP-RX)</p>
                                        <div class="flex items-start gap-4 relative z-10">
                                            <div class="w-14 h-14 bg-white/20 backdrop-blur-xl border border-white/10 rounded-2xl flex items-center justify-center text-white">
                                                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                                            </div>
                                            <div>
                                                <p class="text-lg font-black tracking-tight">{{ $tower->name }} Station</p>
                                                <p class="text-xs text-indigo-200 font-mono mt-1 opacity-70">{{ $tower->receiver_ip }}</p>
                                                <div class="mt-4 inline-flex px-3 py-1 bg-white/10 rounded-lg border border-white/10">
                                                    <p class="text-[9px] font-black uppercase tracking-widest text-white">{{ $tower->receiverModel->model_name ?? 'Radio Unit' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Device Creation Modal --}}
    <div x-data="{ open: false, deviceType: 'wireless', deviceMode: 'existing' }" 
         @open-device-modal.window="open = true; deviceType = $event.detail.type || 'wireless'; deviceMode = 'existing'" 
         @keydown.escape.window="open = false"
         x-show="open" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto" 
         style="display: none;">
         
        <div x-show="open" x-transition.opacity class="fixed inset-0 bg-gray-900/40 backdrop-blur-md" aria-hidden="true"></div>

        <div x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="relative z-10 glass-panel border border-white/40 rounded-[2.5rem] shadow-2xl w-full max-w-2xl bg-white/80 overflow-hidden">
            <form action="{{ route('network.towers.devices.store', $tower) }}" method="POST">
                @csrf
                <div class="p-8">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h3 class="text-2xl font-black text-gray-900 tracking-tight" x-text="deviceType === 'wireless' ? 'Radio Unit Registration' : 'Switch Integration'"></h3>
                            <p class="text-[10px] font-black text-indigo-600 uppercase tracking-widest mt-1" x-text="deviceType === 'wireless' ? 'Sector, Omni, or AP Deployment' : 'Ethernet / Backend Switching'"></p>
                        </div>
                        <button type="button" @click="open = false" class="w-10 h-10 flex items-center justify-center text-gray-400 hover:text-gray-900 hover:bg-gray-100 rounded-full transition-all">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <input type="hidden" name="device_type" :value="deviceType">

                    {{-- Toggle --}}
                    <div class="bg-gray-100 p-1.5 rounded-2xl flex gap-2 mb-8">
                        <button type="button" @click="deviceMode = 'existing'" :class="deviceMode === 'existing' ? 'bg-white text-indigo-600 shadow-sm' : 'text-gray-500'" class="flex-1 py-3 rounded-xl font-black text-[11px] uppercase tracking-widest transition-all">
                            Stored Asset
                        </button>
                        <button type="button" @click="deviceMode = 'new'" :class="deviceMode === 'new' ? 'bg-white text-indigo-600 shadow-sm' : 'text-gray-500'" class="flex-1 py-3 rounded-xl font-black text-[11px] uppercase tracking-widest transition-all">
                            Provision New
                        </button>
                    </div>

                    {{-- Existing Device Selection --}}
                    <div x-show="deviceMode === 'existing'" x-transition class="space-y-6">
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Select Active Fragment</label>
                            <select name="existing_device_id" class="w-full px-6 py-4 bg-white border border-gray-200 rounded-2xl focus:ring-4 focus:ring-indigo-100 focus:border-indigo-600 outline-none transition-all font-bold text-gray-900">
                                <option value="">-- Choose Hardware --</option>
                                @foreach(\App\Models\TowerDevice::whereNull('tower_id')->get() as $device)
                                    <option value="{{ $device->id }}" x-show="deviceType === '{{ $device->device_type }}'">
                                        {{ $device->name }} ({{ $device->ip ?? 'No IP' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- New Device Form --}}
                    <div x-show="deviceMode === 'new'" x-transition class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Target Name</label>
                                <input type="text" name="name" :required="deviceMode === 'new'" class="w-full px-6 py-4 bg-white border border-gray-200 rounded-2xl focus:ring-4 focus:ring-indigo-100 focus:border-indigo-600 outline-none transition-all font-bold text-gray-900" placeholder="e.g. CORE-SW-01">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Hardware Model</label>
                                <select name="device_model_id" class="w-full px-6 py-4 bg-white border border-gray-200 rounded-2xl focus:ring-4 focus:ring-indigo-100 focus:border-indigo-600 outline-none transition-all font-bold text-gray-900">
                                    <option value="">Choose Profile...</option>
                                    @foreach(\App\Models\DeviceModel::all() as $model)
                                        <option value="{{ $model->id }}">{{ $model->manufacturer }} {{ $model->model_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Control IP</label>
                                <input type="text" name="ip" class="w-full px-6 py-4 bg-white border border-gray-200 rounded-2xl focus:ring-4 focus:ring-indigo-100 focus:border-indigo-600 outline-none transition-all font-mono font-bold text-gray-900" dir="ltr" placeholder="10.0.x.x">
                            </div>
                            <div x-show="deviceType === 'switch'">
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Interface Count</label>
                                <input type="number" name="ports_count" class="w-full px-6 py-4 bg-white border border-gray-200 rounded-2xl focus:ring-4 focus:ring-indigo-100 focus:border-indigo-600 outline-none transition-all font-bold text-gray-900" placeholder="24">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-6" x-show="deviceType === 'wireless'">
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Operational Mode</label>
                                <select name="mode" class="w-full px-6 py-4 bg-white border border-gray-200 rounded-2xl focus:ring-4 focus:ring-indigo-100 focus:border-indigo-600 outline-none transition-all font-bold text-gray-900">
                                    <option value="ap">Access Point (AP)</option>
                                    <option value="station">Station</option>
                                    <option value="bridge">Bridge</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="device_mode" x-bind:value="deviceMode">
                <div class="bg-gray-900 p-8 flex items-center justify-between">
                    <button type="button" @click="open = false" class="text-[11px] font-black text-indigo-300 uppercase tracking-widest hover:text-white transition-colors">Abort Procedure</button>
                    <button type="submit" class="px-10 py-4 bg-indigo-600 text-white rounded-[2rem] font-black text-[11px] uppercase tracking-widest shadow-xl shadow-indigo-100 hover:scale-105 transition-all">
                        Commit Integration
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- SSID Creation Modal --}}
    <div x-data="{ open: false, deviceId: null, deviceName: '', deviceType: '' }" 
         @open-ssid-modal.window="open = true; deviceId = $event.detail.deviceId; deviceName = $event.detail.deviceName; deviceType = $event.detail.deviceType" 
         @keydown.escape.window="open = false"
         x-show="open" 
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="open" x-transition.opacity class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-50"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="open" x-transition.scale class="relative z-10 inline-block align-bottom bg-white rounded-lg text-right overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                <form action="{{ route('network.towers.ssids.store', $tower) }}" method="POST">
                    @csrf
                    <input type="hidden" name="tower_device_id" x-bind:value="deviceId">
                    <input type="hidden" name="device_type" x-bind:value="deviceType">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="mb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">إضافة شبكة (SSID) جديدة</h3>
                            <p class="text-sm text-gray-500 mt-1">للجهاز: <span class="font-semibold" x-text="deviceName"></span></p>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">اسم الشبكة (SSID) *</label>
                                <input type="text" name="ssid_name" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" placeholder="My-WiFi">
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">التردد *</label>
                                    <select name="frequency" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                        <option value="5GHz">5 GHz</option>
                                        <option value="2.4GHz">2.4 GHz</option>
                                        <option value="Both">Both</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">الحالة</label>
                                    <select name="is_active" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                        <option value="1">فعال</option>
                                        <option value="0">غير فعال</option>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">ملاحظات</label>
                                <textarea name="notes" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-purple-600 text-base font-medium text-white hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 sm:ml-3 sm:w-auto sm:text-sm">
                            حفظ SSID
                        </button>
                        <button type="button" @click="open = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            إلغاء
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Edit SSID Modal --}}
    <div x-data="{ open: false, ssidId: null, ssidName: '', frequency: '', isActive: true, notes: '' }"
         @open-edit-ssid-modal.window="open = true; ssidId = $event.detail.ssidId; ssidName = $event.detail.ssidName; frequency = $event.detail.frequency; isActive = $event.detail.isActive; notes = $event.detail.notes"
         @keydown.escape.window="open = false"
         x-show="open"
         class="fixed inset-0 z-50 overflow-y-auto"
         style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="open" x-transition.opacity class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-50"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="open" x-transition.scale class="relative z-10 inline-block align-bottom bg-white rounded-lg text-right overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                <form :action="'{{ url('MadaaQ/public/network/towers/' . $tower->id . '/ssids') }}/' + ssidId" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="mb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">تعديل شبكة (SSID)</h3>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">اسم الشبكة (SSID) *</label>
                                <input type="text" name="ssid_name" required x-model="ssidName" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">التردد *</label>
                                    <select name="frequency" required x-model="frequency" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                        <option value="5GHz">5 GHz</option>
                                        <option value="2.4GHz">2.4 GHz</option>
                                        <option value="Both">Both</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">الحالة</label>
                                    <select name="is_active" x-model="isActive" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                        <option value="1">فعال</option>
                                        <option value="0">غير فعال</option>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">ملاحظات</label>
                                <textarea name="notes" rows="2" x-model="notes" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                            حفظ التعديلات
                        </button>
                        <button type="button" @click="open = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            إلغاء
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection

```

## resources/views/network\website\analytics.blade.php

```php
@extends('layouts.app')

@section('content')
<style>
    .gradient-text {
        background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
</style>
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-bold gradient-text">أكثر المواقع تصفحاً 🌐</h2>
            <p class="text-gray-500 mt-1">إحصائيات المواقع الأكثر زيارة على الشبكة (Top 100)</p>
        </div>
        
        <div class="flex gap-2">
            <a href="?period=7d" class="px-4 py-2 {{ $period === '7d' ? 'bg-purple-600 text-white' : 'bg-gray-100 text-gray-700' }} rounded-lg font-semibold transition">
                7 أيام
            </a>
            <a href="?period=30d" class="px-4 py-2 {{ $period === '30d' ? 'bg-purple-600 text-white' : 'bg-gray-100 text-gray-700' }} rounded-lg font-semibold transition">
                30 يوم
            </a>
        </div>
    </div>

    <!-- Top Websites Table -->
    <div class="glass rounded-2xl shadow-lg border border-white/30 overflow-hidden">
        <table class="w-full">
            <thead class="bg-gradient-to-r from-purple-600 to-blue-600 text-white">
                <tr>
                    <th class="px-6 py-4 text-right text-sm font-bold">#</th>
                    <th class="px-6 py-4 text-right text-sm font-bold">الموقع</th>
                    <th class="px-6 py-4 text-right text-sm font-bold">عدد طلبات DNS</th>
                    {{-- <th class="px-6 py-4 text-right text-sm font-bold">الاستهلاك</th> --}}
                    <th class="px-6 py-4 text-right text-sm font-bold">إجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-purple-100">
                @forelse($topWebsites as $index => $website)
                <tr class="hover:bg-purple-50 transition">
                    <td class="px-6 py-4 text-gray-900 font-semibold">
                        @if($index == 0) 🥇
                        @elseif($index == 1) 🥈
                        @elseif($index == 2) 🥉
                        @else {{ $index + 1 }}
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 {{ $index < 3 ? 'bg-amber-100 text-amber-600 ring-2 ring-amber-200' : 'bg-purple-100 text-purple-600' }} rounded-full flex items-center justify-center font-bold">
                                {{ strtoupper(substr($website->domain, 0, 1)) }}
                            </div>
                            <span class="font-semibold {{ $index < 3 ? 'text-indigo-900 text-lg' : 'text-gray-900' }}">{{ $website->domain }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-700 font-medium">{{ number_format($website->total_hits) }} طلب DNS</td>
                    {{-- <td class="px-6 py-4 text-gray-700 font-medium">{{ number_format($website->total_bytes / 1024 / 1024, 2) }} MB</td> --}}
                    <td class="px-6 py-4">
                        <form action="{{ route('network.website.block') }}" method="POST" class="inline">
                            @csrf
                            <input type="hidden" name="domain" value="{{ $website->domain }}">
                            <input type="hidden" name="type" value="domain">
                            <input type="hidden" name="reason" value="محظور من قائمة الأكثر تصفحاً">
                            <button type="submit" class="px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-sm font-bold rounded-lg transition">
                                🚫 حظر
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                        لا توجد بيانات. سيتم جمعها تلقائياً من سجلات DNS الخاصة بـ MikroTik.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Info Note -->
    <div class="glass rounded-xl p-4 border border-blue-200 bg-blue-50/50">
        <p class="text-sm text-blue-800">
            <strong>💡 ملاحظة هامة:</strong> البيانات المعروضة مبنية على سجلات DNS Cache من MikroTik وتعكس عدد طلبات DNS فقط (وليس حجم الاستهلاك الفعلي). 
            للحصول على بيانات أدقق عن الاستهلاك، استخدم صفحة "استهلاك البيانات" التي تعتمد على Accounting أو Torch.
        </p>
    </div>
</div>
@endsection

```

## resources/views/network\website\blocked.blade.php

```php
@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">المواقع المحظورة</h2>
            <p class="text-gray-500 mt-1">إدارة قائمة المواقع والكلمات المفتاحية المحظورة</p>
        </div>
        
        <button onclick="document.getElementById('addModal').classList.remove('hidden')" class="px-4 py-2 bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white font-bold rounded-xl shadow-lg transition">
            + حظر موقع جديد
        </button>
    </div>

    <!-- Blocked List -->
    <div class="glass rounded-2xl shadow-lg border border-white/30 overflow-hidden">
        <table class="w-full">
            <thead class="bg-gradient-to-r from-red-600 to-orange-600 text-white">
                <tr>
                    <th class="px-6 py-4 text-right text-sm font-bold">النطاق/الكلمة</th>
                    <th class="px-6 py-4 text-right text-sm font-bold">النوع</th>
                    <th class="px-6 py-4 text-right text-sm font-bold">السبب</th>
                    <th class="px-6 py-4 text-right text-sm font-bold">الحالة</th>
                    <th class="px-6 py-4 text-right text-sm font-bold">إجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-red-100">
                @forelse($blocked as $item)
                <tr class="hover:bg-red-50 transition">
                    <td class="px-6 py-4 font-semibold text-gray-900">{{ $item->domain }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 bg-purple-100 text-purple-700 rounded-full text-xs font-bold">
                            {{ $item->type === 'domain' ? 'نطاق' : 'كلمة مفتاحية' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-gray-600 text-sm">{{ $item->reason ?? '--' }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded-full text-xs font-bold {{ $item->is_active ? 'bg-red-200 text-red-800' : 'bg-gray-200 text-gray-600' }}">
                            {{ $item->is_active ? 'فعّال' : 'معطل' }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex gap-2">
                            <form action="{{ route('network.website.toggle', $item) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-lg transition">
                                    {{ $item->is_active ? 'تعطيل' : 'تفعيل' }}
                                </button>
                            </form>
                            
                            <form action="{{ route('network.website.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('متأكد من الحذف؟')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-sm font-bold rounded-lg transition">
                                    حذف
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                        لا توجد مواقع محظورة. أضف موقع جديد للحظر.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="p-4 border-t border-red-100">
            {{ $blocked->links() }}
        </div>
    </div>
</div>

<!-- Add Modal -->
<div id="addModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm" onclick="if(event.target === this) this.classList.add('hidden')">
    <div class="glass rounded-2xl p-6 max-w-md w-full mx-4 shadow-2xl border border-white/30">
        <h3 class="text-xl font-bold text-gray-900 mb-4">حظر موقع جديد</h3>
        
        <form action="{{ route('network.website.block') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">النطاق أو الكلمة المفتاحية</label>
                <input type="text" name="domain" required placeholder="example.com أو كلمة" class="w-full px-4 py-2 border-2 border-purple-200 rounded-xl focus:ring-2 focus:ring-purple-500">
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">النوع</label>
                <select name="type" class="w-full px-4 py-2 border-2 border-purple-200 rounded-xl focus:ring-2 focus:ring-purple-500">
                    <option value="domain">نطاق كامل</option>
                    <option value="keyword">كلمة مفتاحية</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">السبب (اختياري)</label>
                <textarea name="reason" rows="2" placeholder="سبب الحظر..." class="w-full px-4 py-2 border-2 border-purple-200 rounded-xl focus:ring-2 focus:ring-purple-500"></textarea>
            </div>
            
            <div class="flex gap-3">
                <button type="submit" class="flex-1 px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-bold rounded-lg transition">
                    🚫 حظر
                </button>
                <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg transition">
                    إلغاء
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

```

## resources/views/routers\create.blade.php

```php
@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #locationMap { height: 400px; width: 100%; border-radius: 0.75rem; z-index: 1; }
    [x-cloak] { display: none !important; }
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { bg: #f1f1f1; }
    .custom-scrollbar::-webkit-scrollbar-thumb { bg: #c1c1c1; border-radius: 10px; }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto" x-data="routerForm()">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">إضافة جهاز جديد</h2>
            <p class="text-sm text-gray-500">أضف راوتر أو نقطة وصول أو محطة بث جديد</p>
        </div>
        <a href="{{ route('routers.index') }}" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition text-sm">
            إلغاء
        </a>
    </div>

    <!-- Error Messages -->
    @if($errors->any())
    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg mb-6">
        <div class="flex items-start gap-3">
            <svg class="w-6 h-6 text-red-500 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div>
                <p class="text-red-800 font-semibold mb-2">يرجى تصحيح الأخطاء التالية:</p>
                <ul class="list-disc list-inside text-red-700 text-sm space-y-1">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    <form action="{{ route('routers.store') }}" method="POST">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
            
            <!-- RIGHT COLUMN: Main Form -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- 1. Device Selection & Identity -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/></svg>
                        بيانات الجهاز
                    </h3>
                    
                    <!-- Search -->
                    <div class="mb-6 relative">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">موديل الجهاز</label>
                        <input type="text" x-model="deviceQuery" @input.debounce.300ms="searchDevices()" @click.away="showDevices = false"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-indigo-500 outline-none placeholder-gray-400" 
                               placeholder="ابحث... (مثال: NanoStation, hEX)">
                        <input type="hidden" name="model_id" :value="selectedDevice ? selectedDevice.id : ''">
                        
                        <!-- Dropdown -->
                        <div x-show="showDevices && devices.length > 0" x-cloak class="absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-xl max-h-60 overflow-y-auto">
                            <template x-for="device in devices" :key="device.id">
                                <div @click="selectDevice(device)" class="flex items-center p-3 hover:bg-gray-50 cursor-pointer border-b border-gray-100 last:border-0 gap-3">
                                    <img :src="device.image_url || 'https://via.placeholder.com/40'" class="w-10 h-10 object-contain p-1 border rounded bg-white">
                                    <div>
                                        <p class="font-bold text-sm text-gray-900" x-text="device.model_name" dir="ltr"></p>
                                        <p class="text-xs text-gray-500" x-text="device.manufacturer"></p>
                                    </div>
                                    <span class="mr-auto text-[10px] px-2 py-0.5 rounded-full bg-gray-100 text-gray-600" x-text="device.device_type"></span>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">اسم الجهاز <span class="text-red-500">*</span></label>
                            <input type="text" name="name" x-model="routerName" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-indigo-500 outline-none" required dir="ltr">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">نوع الجهاز <span class="text-red-500">*</span></label>
                            <select name="device_type" x-model="deviceType" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-indigo-500 outline-none">
                                <option value="router">راوتر (Router)</option>
                                <option value="switch">سويتش (Switch)</option>
                                <option value="access_point">نقطة وصول (Access Point)</option>
                                <option value="base_station">محطة بث (Base Station)</option>
                            </select>
                        </div>
                    </div>

                    <!-- Antenna Options -->
                    <div x-show="['access_point', 'base_station'].includes(deviceType)" x-transition class="mt-4 p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                        <label class="block text-xs font-bold text-yellow-800 uppercase mb-2">نوع الأنتينا</label>
                        <div class="flex gap-4">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="antenna_type" value="sector" class="text-indigo-600 focus:ring-indigo-500">
                                <span class="text-sm text-gray-700">Sector</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="antenna_type" value="omni" class="text-indigo-600 focus:ring-indigo-500">
                                <span class="text-sm text-gray-700">Omni</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="antenna_type" value="dish" class="text-indigo-600 focus:ring-indigo-500">
                                <span class="text-sm text-gray-700">Dish</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- 2. Connection Details -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-1.015 1.09-2.132 1.335-3.354M10 9l5 5m0 0l-5 5m5-5H5"/></svg>
                        الاتصال والدخول
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">عنوان IP <span class="text-red-500">*</span></label>
                            <input type="text" name="ip" x-model="ip" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-green-500 outline-none font-mono" placeholder="192.168.88.1" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">منفذ API</label>
                            <input type="number" name="api_port" value="8728" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-green-500 outline-none font-mono">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">اسم المستخدم</label>
                            <input type="text" name="username" value="admin" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-green-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">كلمة المرور</label>
                            <input type="password" name="password" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-green-500 outline-none" required>
                        </div>
                    </div>
                </div>

                <!-- 3. Location (Collapsible Map) -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <button type="button" @click="showMap = !showMap" class="w-full p-4 flex items-center justify-between hover:bg-gray-50 transition">
                         <h3 class="font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            الموقع والتغطية
                        </h3>
                        <svg class="w-5 h-5 text-gray-400 transform transition-transform" :class="showMap ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    
                    <div x-show="showMap" x-collapse>
                        <div class="p-5 pt-0">
                            <!-- Helper Options -->
                            <div class="mb-4">
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">نسخ إحداثيات برج</label>
                                <select @change="selectTower($event)" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-red-500 outline-none text-sm">
                                    <option value="">-- اختر البرج --</option>
                                    @foreach($towers as $tower)
                                    <option value="{{ $tower->id }}" data-lat="{{ $tower->lat }}" data-lng="{{ $tower->lng }}">
                                        {{ $tower->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div id="locationMap" class="mb-4 bg-gray-100"></div>
                            
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Latitude</label>
                                    <input type="text" name="lat" x-model="lat" class="w-full px-3 py-2 bg-gray-50 border rounded-lg text-xs" readonly>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Longitude</label>
                                    <input type="text" name="lng" x-model="lng" class="w-full px-3 py-2 bg-gray-50 border rounded-lg text-xs" readonly>
                                </div>
                            </div>
                            
                            <!-- Coverage Inputs -->
                            <div class="grid grid-cols-3 gap-4 border-t pt-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">المدى (متر)</label>
                                    <input type="number" name="coverage_radius" x-model="coverageRadius" @input="updateCoverageCircle()" class="w-full px-3 py-2 border border-gray-300 rounded-lg outline-none text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">الاتجاه (0-360)</label>
                                    <input type="number" name="azimuth" x-model="azimuth" @input="updateCoverageCircle()" class="w-full px-3 py-2 border border-gray-300 rounded-lg outline-none text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">العرض (درجة)</label>
                                    <input type="number" name="beam_width" x-model="beamWidth" @input="updateCoverageCircle()" class="w-full px-3 py-2 border border-gray-300 rounded-lg outline-none text-sm">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- LEFT COLUMN: Sticky Summary -->
            <div class="lg:col-span-1">
                <div class="sticky top-6">
                    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                        <!-- Preview Image -->
                        <div class="h-32 bg-gray-50 flex items-center justify-center p-4 border-b border-gray-100">
                             <img :src="selectedDevice ? selectedDevice.image_url : 'https://via.placeholder.com/150?text=Device'" 
                                  class="max-h-full max-w-full object-contain" :class="!selectedDevice ? 'opacity-30' : ''">
                        </div>
                        
                        <div class="p-5">
                            <div class="text-center mb-4">
                                <h3 class="font-bold text-gray-900 text-lg mb-1" x-text="routerName || 'جهاز جديد'"></h3>
                                <div class="flex items-center justify-center gap-2">
                                    <span class="px-2 py-0.5 rounded text-xs font-mono bg-gray-100" x-text="ip || 'Pending IP'"></span>
                                    <span class="px-2 py-0.5 rounded text-xs font-bold uppercase text-white" 
                                          :class="{
                                              'bg-blue-500': deviceType === 'router',
                                              'bg-green-500': deviceType === 'access_point',
                                              'bg-teal-500': deviceType === 'switch',
                                              'bg-purple-500': deviceType === 'base_station'
                                          }" 
                                          x-text="deviceType"></span>
                                </div>
                            </div>
                            
                            <dl class="space-y-2 text-sm border-t border-gray-100 pt-3">
                                <div class="flex justify-between">
                                    <dt class="text-gray-500">الموديل</dt>
                                    <dd class="font-medium text-gray-900" x-text="selectedDevice ? selectedDevice.model_name : '---'"></dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-gray-500">التردد</dt>
                                    <dd class="font-medium text-gray-900" x-text="selectedDevice ? selectedDevice.frequency : '---'"></dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-gray-500">الإحداثيات</dt>
                                    <dd class="font-medium text-gray-900" x-text="lat ? 'تم التحديد' : '---'"></dd>
                                </div>
                            </dl>
                            
                            <button type="submit" class="w-full mt-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg shadow transition transform active:scale-95 flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                حفظ الجهاز
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('routerForm', () => ({
        routerName: '',
        deviceType: 'router',
        ip: '',
        
        lat: '',
        lng: '',
        coverageRadius: '',
        azimuth: '',
        beamWidth: '',
        
        showMap: true,
        map: null,
        marker: null,
        circle: null,
        
        deviceQuery: '',
        selectedDevice: null,
        devices: [],
        showDevices: false,

        async searchDevices() {
            if (this.deviceQuery.length < 2) { this.devices = []; return; }
            const res = await fetch(`{{ route('api.devices.search') }}?q=${this.deviceQuery}`);
            this.devices = await res.json();
            this.showDevices = true;
        },

        selectDevice(device) {
            this.selectedDevice = device;
            this.deviceQuery = device.model_name;
            this.deviceType = device.device_type;
            if (device.default_coverage_radius) this.coverageRadius = device.default_coverage_radius;
            this.showDevices = false;
            this.updateCoverageCircle();
        },

        selectTower(e) {
            const opt = e.target.options[e.target.selectedIndex];
            if (opt.dataset.lat) {
                this.lat = opt.dataset.lat;
                this.lng = opt.dataset.lng;
                this.updateMap();
            }
        },

        init() {
            this.initMap();
        },

        initMap() {
            this.map = L.map('locationMap').setView([24.7136, 46.6753], 6);
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(this.map);
            this.map.on('click', (e) => {
                this.lat = e.latlng.lat.toFixed(6);
                this.lng = e.latlng.lng.toFixed(6);
                this.updateMap();
            });
        },

        updateMap() {
            const latlng = [this.lat, this.lng];
            this.map.setView(latlng, 15);
            if(this.marker) this.marker.setLatLng(latlng);
            else this.marker = L.marker(latlng).addTo(this.map);
            this.updateCoverageCircle();
        },

        updateCoverageCircle() {
             if (this.circle) {
                this.map.removeLayer(this.circle);
                this.circle = null;
            }
            if (this.marker && this.coverageRadius > 0) {
                 const latlng = this.marker.getLatLng();
                 const color = this.deviceType === 'access_point' ? '#8b5cf6' : '#3b82f6';
                 
                 // Sector Logic
                 if (this.azimuth && this.beamWidth && this.beamWidth < 360) {
                    this.circle = this.drawSector(latlng, this.coverageRadius, this.azimuth, this.beamWidth, color);
                 } else {
                    this.circle = L.circle(latlng, { radius: this.coverageRadius, color: color }).addTo(this.map);
                 }
            }
        },

        drawSector(center, radius, azimuth, beamWidth, color) {
            // Sector drawing logic (omitted for brevity, same as before)
             const startAngle = parseFloat(azimuth) - (parseFloat(beamWidth) / 2);
            const endAngle = parseFloat(azimuth) + (parseFloat(beamWidth) / 2);
            const points = [center];
            for (let angle = startAngle; angle <= endAngle; angle += 2) {
                const rad = (angle * Math.PI) / 180;
                const latOffset = (radius / 111320) * Math.cos(rad);
                const lngOffset = (radius / (111320 * Math.cos(center.lat * Math.PI / 180))) * Math.sin(rad);
                points.push(L.latLng(center.lat + latOffset, center.lng + lngOffset));
            }
            points.push(center);
            return L.polygon(points, { color: color, fillColor: color, fillOpacity: 0.2 }).addTo(this.map);
        }
    }));
});
</script>
@endpush
@endsection

```

## resources/views/routers\edit.blade.php

```php
@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #locationMap { height: 400px; width: 100%; border-radius: 0.75rem; z-index: 1; }
    [x-cloak] { display: none !important; }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto" x-data="routerEditForm({
    router: {{ Js::from($router) }},
    device: {{ Js::from($router->deviceModel) }}
})">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">تعديل بيانات الجهاز</h2>
            <p class="text-sm text-gray-500">تحديث: <span x-text="router.name"></span></p>
        </div>
        <div class="flex gap-2">
             <a href="{{ route('routers.index') }}" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition text-sm">
                إلغاء
            </a>
            <form action="{{ route('routers.destroy', $router->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد؟')">
                @csrf @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-50 text-red-700 border border-red-200 font-medium rounded-lg hover:bg-red-100 transition text-sm">حذف</button>
            </form>
        </div>
    </div>

    <form action="{{ route('routers.update', $router->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
            
            <!-- RIGHT COLUMN: Main Form -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- 1. Device Selection & Identity -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/></svg>
                        بيانات الجهاز
                    </h3>
                    
                    <!-- Search -->
                    <div class="mb-6 relative">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">موديل الجهاز</label>
                        <input type="text" x-model="deviceQuery" @input.debounce.300ms="searchDevices()" @click.away="showDevices = false"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-indigo-500 outline-none placeholder-gray-400" 
                               placeholder="ابحث لتغيير الموديل...">
                        <input type="hidden" name="model_id" :value="selectedDevice ? selectedDevice.id : ''">
                        
                        <!-- Dropdown -->
                        <div x-show="showDevices && devices.length > 0" x-cloak class="absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-xl max-h-60 overflow-y-auto">
                            <template x-for="device in devices" :key="device.id">
                                <div @click="selectDevice(device)" class="flex items-center p-3 hover:bg-gray-50 cursor-pointer border-b border-gray-100 last:border-0 gap-3">
                                    <img :src="device.image_url || 'https://via.placeholder.com/40'" class="w-10 h-10 object-contain p-1 border rounded bg-white">
                                    <div>
                                        <p class="font-bold text-sm text-gray-900" x-text="device.model_name" dir="ltr"></p>
                                        <p class="text-xs text-gray-500" x-text="device.manufacturer"></p>
                                    </div>
                                    <span class="mr-auto text-[10px] px-2 py-0.5 rounded-full bg-gray-100 text-gray-600" x-text="device.device_type"></span>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">اسم الجهاز <span class="text-red-500">*</span></label>
                            <input type="text" name="name" x-model="routerName" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-indigo-500 outline-none" required dir="ltr">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">نوع الجهاز <span class="text-red-500">*</span></label>
                             <select name="device_type" x-model="deviceType" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-indigo-500 outline-none">
                                <option value="router">راوتر (Router)</option>
                                <option value="switch">سويتش (Switch)</option>
                                <option value="access_point">نقطة وصول (Access Point)</option>
                                <option value="base_station">محطة بث (Base Station)</option>
                            </select>
                        </div>
                    </div>

                     <!-- Antenna Options -->
                    <div x-show="['access_point', 'base_station'].includes(deviceType)" x-transition class="mt-4 p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                        <label class="block text-xs font-bold text-yellow-800 uppercase mb-2">نوع الأنتينا</label>
                        <div class="flex gap-4">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="antenna_type" value="sector" x-model="antennaType" class="text-indigo-600 focus:ring-indigo-500">
                                <span class="text-sm text-gray-700">Sector</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="antenna_type" value="omni" x-model="antennaType" class="text-indigo-600 focus:ring-indigo-500">
                                <span class="text-sm text-gray-700">Omni</span>
                            </label>
                             <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="antenna_type" value="dish" x-model="antennaType" class="text-indigo-600 focus:ring-indigo-500">
                                <span class="text-sm text-gray-700">Dish</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- 2. Connection Details -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-1.015 1.09-2.132 1.335-3.354M10 9l5 5m0 0l-5 5m5-5H5"/></svg>
                        الاتصال والدخول
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">عنوان IP <span class="text-red-500">*</span></label>
                            <input type="text" name="ip" x-model="ip" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-green-500 outline-none font-mono" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">منفذ API</label>
                            <input type="number" name="api_port" x-model="apiPort" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-green-500 outline-none font-mono">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">اسم المستخدم</label>
                            <input type="text" name="username" x-model="username" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-green-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">كلمة المرور</label>
                            <input type="password" name="password" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-green-500 outline-none" placeholder="اترك فارغاً لعدم التغيير">
                        </div>
                    </div>
                </div>

                <!-- 3. Location (Collapsible Map) -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <button type="button" @click="showMap = !showMap" class="w-full p-4 flex items-center justify-between hover:bg-gray-50 transition">
                         <h3 class="font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            الموقع والتغطية
                        </h3>
                        <svg class="w-5 h-5 text-gray-400 transform transition-transform" :class="showMap ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    
                    <div x-show="showMap" x-collapse>
                        <div class="p-5 pt-0">
                            <!-- Helper Options -->
                            <div class="mb-4">
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">نسخ إحداثيات برج</label>
                                <select @change="selectTower($event)" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-red-500 outline-none text-sm">
                                    <option value="">-- اختر البرج --</option>
                                    @foreach($towers as $tower)
                                    <option value="{{ $tower->id }}" data-lat="{{ $tower->lat }}" data-lng="{{ $tower->lng }}" {{ $router->tower_id == $tower->id ? 'selected' : '' }}>
                                        {{ $tower->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div id="locationMap" class="mb-4 bg-gray-100"></div>
                            
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Latitude</label>
                                    <input type="text" name="lat" x-model="lat" class="w-full px-3 py-2 bg-gray-50 border rounded-lg text-xs" readonly>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Longitude</label>
                                    <input type="text" name="lng" x-model="lng" class="w-full px-3 py-2 bg-gray-50 border rounded-lg text-xs" readonly>
                                </div>
                            </div>
                            
                            <!-- Coverage Inputs -->
                            <div class="grid grid-cols-3 gap-4 border-t pt-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">المدى (متر)</label>
                                    <input type="number" name="coverage_radius" x-model="coverageRadius" @input="updateCoverageCircle()" class="w-full px-3 py-2 border border-gray-300 rounded-lg outline-none text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">الاتجاه (0-360)</label>
                                    <input type="number" name="azimuth" x-model="azimuth" @input="updateCoverageCircle()" class="w-full px-3 py-2 border border-gray-300 rounded-lg outline-none text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">العرض (درجة)</label>
                                    <input type="number" name="beam_width" x-model="beamWidth" @input="updateCoverageCircle()" class="w-full px-3 py-2 border border-gray-300 rounded-lg outline-none text-sm">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- LEFT COLUMN: Sticky Summary -->
            <div class="lg:col-span-1">
                <div class="sticky top-6">
                    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                        <!-- Preview Image -->
                        <div class="h-32 bg-gray-50 flex items-center justify-center p-4 border-b border-gray-100">
                             <img :src="selectedDevice ? selectedDevice.image_url : 'https://via.placeholder.com/150?text=Device'" 
                                  class="max-h-full max-w-full object-contain" :class="!selectedDevice ? 'opacity-30' : ''">
                        </div>
                        
                        <div class="p-5">
                            <div class="text-center mb-4">
                                <h3 class="font-bold text-gray-900 text-lg mb-1" x-text="routerName || 'جهاز جديد'"></h3>
                                <div class="flex items-center justify-center gap-2">
                                    <span class="px-2 py-0.5 rounded text-xs font-mono bg-gray-100" x-text="ip || 'Pending IP'"></span>
                                    <span class="px-2 py-0.5 rounded text-xs font-bold uppercase text-white" 
                                          :class="{
                                              'bg-blue-500': deviceType === 'router',
                                              'bg-green-500': deviceType === 'access_point',
                                              'bg-teal-500': deviceType === 'switch',
                                              'bg-purple-500': deviceType === 'base_station'
                                          }" 
                                          x-text="deviceType"></span>
                                </div>
                            </div>
                            
                            <dl class="space-y-2 text-sm border-t border-gray-100 pt-3">
                                <div class="flex justify-between">
                                    <dt class="text-gray-500">الموديل</dt>
                                    <dd class="font-medium text-gray-900" x-text="selectedDevice ? selectedDevice.model_name : '---'"></dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-gray-500">التردد</dt>
                                    <dd class="font-medium text-gray-900" x-text="selectedDevice ? selectedDevice.frequency : '---'"></dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-gray-500">الإحداثيات</dt>
                                    <dd class="font-medium text-gray-900" x-text="lat ? 'تم التحديد' : '---'"></dd>
                                </div>
                            </dl>
                            
                            <button type="submit" class="w-full mt-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg shadow transition transform active:scale-95 flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                تحديث البيانات
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('routerEditForm', (data) => ({
        router: data.router,
        
        routerName: data.router.name,
        deviceType: data.router.device_type,
        ip: data.router.ip,
        username: data.router.username || 'admin',
        apiPort: data.router.api_port || 8728,
        antennaType: data.router.antenna_type || 'sector',
        
        lat: data.router.lat,
        lng: data.router.lng,
        coverageRadius: data.router.coverage_radius,
        azimuth: data.router.azimuth,
        beamWidth: data.router.beam_width,
        
        showMap: false,
        map: null,
        marker: null,
        circle: null,
        
        deviceQuery: '',
        selectedDevice: data.device,
        devices: [],
        showDevices: false,

        async searchDevices() {
            if (this.deviceQuery.length < 2) { this.devices = []; return; }
            const res = await fetch(`{{ route('api.devices.search') }}?q=${this.deviceQuery}`);
            this.devices = await res.json();
            this.showDevices = true;
        },

        selectDevice(device) {
            this.selectedDevice = device;
            this.deviceQuery = device.model_name;
            this.deviceType = device.device_type;
            if (device.default_coverage_radius) this.coverageRadius = device.default_coverage_radius;
            this.showDevices = false;
        },

        selectTower(e) {
            const opt = e.target.options[e.target.selectedIndex];
            if (opt.dataset.lat) {
                this.lat = opt.dataset.lat;
                this.lng = opt.dataset.lng;
                this.updateMap();
            }
        },

        init() {
             this.$watch('showMap', (value) => {
                if (value && !this.map) {
                    setTimeout(() => this.initMap(), 100);
                }
            });
            // Update device query to current name if exists
            if (this.selectedDevice) this.deviceQuery = this.selectedDevice.model_name;
        },

        initMap() {
            const initialLat = this.lat || 24.7136;
            const initialLng = this.lng || 46.6753;
            
            this.map = L.map('locationMap').setView([initialLat, initialLng], this.lat ? 15 : 6);
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(this.map);
            
            if (this.lat && this.lng) {
                 this.marker = L.marker([this.lat, this.lng]).addTo(this.map);
                 this.updateCoverageCircle();
            }

            this.map.on('click', (e) => {
                this.lat = e.latlng.lat.toFixed(6);
                this.lng = e.latlng.lng.toFixed(6);
                this.updateMap();
            });
        },

        updateMap() {
            if (!this.map) return;
            const latlng = [this.lat, this.lng];
            this.map.setView(latlng, 15);
            if(this.marker) this.marker.setLatLng(latlng);
            else this.marker = L.marker(latlng).addTo(this.map);
            this.updateCoverageCircle();
        },

        updateCoverageCircle() {
             if (this.circle) {
                this.map.removeLayer(this.circle);
                this.circle = null;
            }
            if (this.marker && this.coverageRadius > 0) {
                 const latlng = this.marker.getLatLng();
                 const color = this.deviceType === 'access_point' ? '#8b5cf6' : '#3b82f6';
                 
                 if (this.azimuth && this.beamWidth && this.beamWidth < 360) {
                    this.circle = this.drawSector(latlng, this.coverageRadius, this.azimuth, this.beamWidth, color);
                 } else {
                    this.circle = L.circle(latlng, { radius: this.coverageRadius, color: color }).addTo(this.map);
                 }
            }
        },

        drawSector(center, radius, azimuth, beamWidth, color) {
             const startAngle = parseFloat(azimuth) - (parseFloat(beamWidth) / 2);
            const endAngle = parseFloat(azimuth) + (parseFloat(beamWidth) / 2);
            const points = [center];
            for (let angle = startAngle; angle <= endAngle; angle += 2) {
                const rad = (angle * Math.PI) / 180;
                const latOffset = (radius / 111320) * Math.cos(rad);
                const lngOffset = (radius / (111320 * Math.cos(center.lat * Math.PI / 180))) * Math.sin(rad);
                points.push(L.latLng(center.lat + latOffset, center.lng + lngOffset));
            }
            points.push(center);
            return L.polygon(points, { color: color, fillColor: color, fillOpacity: 0.2 }).addTo(this.map);
        }
    }));
});
</script>
@endpush
@endsection

```

## resources/views/routers\index.blade.php

```php
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

    <!-- Servers Section -->
    @if($servers->count() > 0)
    <div class="mb-8">
        <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
            <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/></svg>
            سيرفرات الإدارة (MikroTik Servers)
        </h3>
        <div class="glass shadow-lg rounded-2xl overflow-hidden border border-indigo-100">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-indigo-50/50">
                        <tr>
                            <th class="px-6 py-4 text-right text-xs font-bold text-indigo-800 uppercase tracking-wider">اسم السيرفر</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-indigo-800 uppercase tracking-wider">عنوان IP</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-indigo-800 uppercase tracking-wider">الحالة</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-indigo-800 uppercase tracking-wider">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white/60 divide-y divide-gray-200 backdrop-blur-sm">
                        @foreach($servers as $server)
                        <tr class="hover:bg-indigo-50/50 transition duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center text-indigo-600">
                                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                    </div>
                                    <div>
                                        <div class="font-bold text-gray-900">{{ $server->name }}</div>
                                        <div class="text-xs text-indigo-500">v{{ $server->version ?? 'Unknown' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="font-mono text-sm bg-gray-100 px-2 py-1 rounded text-gray-700">{{ $server->ip }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-bold rounded-full bg-green-100 text-green-700">متصل ✅</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('servers.show', $server->id) }}" class="text-indigo-600 hover:text-indigo-900 font-bold hover:underline">لوحة التحكم</a>
                                    <form action="{{ route('servers.destroy', $server->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد؟')" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700">حذف</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- Routers List -->
    <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
        <svg class="w-6 h-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/></svg>
        أجهزة الشبكة (Routers & APs)
    </h3>
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
                                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/></svg>
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
                                    <a href="{{ route('routers.webfig', $router->id) }}" target="_blank" class="text-emerald-500 hover:text-emerald-700 font-bold hover:underline transition flex items-center gap-1" title="وصول آمن">
                                        WebFig
                                    </a>
                                @endif

                                <a href="{{ route('routers.edit', $router->id) }}" class="text-yellow-600 hover:text-yellow-900 font-bold hover:underline transition">تعديل</a>
                                <button onclick="showScript({{ $router->id }})" class="text-indigo-600 hover:text-indigo-900 font-bold hover:underline transition">سكريبت</button>
                                <form action="{{ route('routers.destroy', $router->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا الجهاز؟')" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 font-bold hover:underline transition">حذف</button>
                                </form>
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

```

## resources/views/router-management\index.blade.php

```php
@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">إدارة الراوترات المتصلة</h2>
            <p class="text-gray-500 mt-1">ربط وإدارة أجهزة MikroTik تلقائياً</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        @forelse($routers as $router)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition">
            <!-- Status Badge -->
            <div class="p-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900">{{ $router->name }}</h3>
                            <p class="text-xs text-gray-500">{{ $router->host }}</p>
                        </div>
                    </div>
                    @if($router->is_connected)
                        <span class="flex items-center gap-1 px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">
                            <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                            متصل
                        </span>
                    @else
                        <span class="px-2 py-1 bg-gray-100 text-gray-800 text-xs font-medium rounded-full">غير متصل</span>
                    @endif
                </div>
            </div>

            <!-- Info -->
            <div class="p-4 space-y-3">
                @if($router->vpn_ip)
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">VPN IP:</span>
                    <span class="font-mono font-semibold text-gray-900">{{ $router->vpn_ip }}</span>
                </div>
                @endif

                @if($router->tower)
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">البرج:</span>
                    <span class="font-semibold text-gray-900">{{ $router->tower->name }}</span>
                </div>
                @endif

                @if($router->last_sync)
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">آخر مزامنة:</span>
                    <span class="text-gray-700">{{ $router->last_sync->diffForHumans() }}</span>
                </div>
                @endif
            </div>

            <!-- Actions -->
            <div class="p-4 bg-gray-50 border-t border-gray-200">
                <a href="{{ route('router-management.script', $router) }}" class="block w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-center font-semibold rounded-lg transition">
                    {{ $router->is_connected ? '🔄 تحديث السكريبت' : '🔗 توليد سكريبت الربط' }}
                </a>
            </div>
        </div>
        @empty
        <div class="col-span-full bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/></svg>
            </div>
            <p class="text-gray-500 font-medium mb-3">لا توجد أجهزة</p>
            <a href="{{ route('routers.create') }}" class="text-blue-600 hover:text-blue-700 font-semibold text-sm">أضف جهاز أولاً</a>
        </div>
        @endforelse
    </div>

    <!-- Instructions -->
    <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-xl p-6">
        <h3 class="text-lg font-bold text-blue-900 mb-3">📖 كيفية الربط</h3>
        <div class="space-y-2 text-sm text-blue-800">
            <p class="flex items-start gap-2">
                <span class="font-bold">1.</span>
                <span>اضغط على "توليد سكريبت الربط" للجهاز المطلوب</span>
            </p>
            <p class="flex items-start gap-2">
                <span class="font-bold">2.</span>
                <span>انسخ السكريبت الكامل</span>
            </p>
            <p class="flex items-start gap-2">
                <span class="font-bold">3.</span>
                <span>افتح Terminal على جهاز MikroTik والصق السكريبت</span>
            </p>
            <p class="flex items-start gap-2">
                <span class="font-bold">4.</span>
                <span>سيتم الربط تلقائياً والمزامنة كل 15 ثانية!</span>
            </p>
        </div>
    </div>
</div>
@endsection

```

## resources/views/router-management\script.blade.php

```php
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

```

## resources/views/towers\show.blade.php

```php
@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto space-y-6" x-data="{ activeTab: 'info' }">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">🗼 {{ $tower->name }}</h2>
            <p class="text-gray-500 mt-1">{{ $tower->location }}</p>
        </div>
        <a href="{{ route('network.towers.edit', $tower) }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
            ✏️ تعديل
        </a>
    </div>

    <!-- Tabs Navigation -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="border-b border-gray-200">
            <nav class="flex -mb-px">
                <button @click="activeTab = 'info'" :class="activeTab === 'info' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700'" class="px-6 py-3 border-b-2 font-medium text-sm transition">
                    ℹ️ معلومات
                </button>
                <button @click="activeTab = 'equipment'" :class="activeTab === 'equipment' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700'" class="px-6 py-3 border-b-2 font-medium text-sm transition">
                    📦 المعدات
                </button>
                <button @click="activeTab = 'power'" :class="activeTab === 'power' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700'" class="px-6 py-3 border-b-2 font-medium text-sm transition">
                    ⚡ الطاقة
                </button>
                <button @click="activeTab = 'ssids'" :class="activeTab === 'ssids' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700'" class="px-6 py-3 border-b-2 font-medium text-sm transition">
                    📶 SSIDs
                </button>
                <button @click="activeTab = 'costs'" :class="activeTab === 'costs' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700'" class="px-6 py-3 border-b-2 font-medium text-sm transition">
                    💰 التكاليف
                </button>
            </nav>
        </div>

        <!-- Tab: Info -->
        <div x-show="activeTab === 'info'" class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="font-semibold text-gray-900 mb-4">موقع البرج</h3>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm text-gray-500">الموقع</dt>
                            <dd class="text-gray-900">{{ $tower->location }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">الإحداثيات</dt>
                            <dd class="text-gray-900">{{ $tower->lat }}, {{ $tower->lng }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">الارتفاع</dt>
                            <dd class="text-gray-900">{{ $tower->height }} متر</dd>
                        </div>
                    </dl>
                </div>

                <div>
                    <h3 class="font-semibold text-gray-900 mb-4">المعدات المثبتة</h3>
                    <div class="space-y-2">
                        <p class="text-sm text-gray-600">📡 {{ $tower->routers->count() }} معدات شبكة</p>
                        <p class="text-sm text-gray-600">👥 {{ $tower->clients->count() }} عميل</p>
                        <p class="text-sm text-gray-600">📶 {{ $tower->ssids->count() }} SSID</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab: Equipment -->
        <div x-show="activeTab === 'equipment'" class="p-6">
            <div class="space-y-6">
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-6 border border-blue-200">
                    <h3 class="font-bold text-xl text-gray-900 mb-2">💰 تكلفة الأجهزة</h3>
                    <div class="flex items-center justify-center mt-4">
                        <div>
                            <p class="text-sm text-gray-600 text-center">إجمالي تكلفة الأجهزة</p>
                            <p class="text-3xl font-bold text-blue-600">${{ number_format($tower->routers->sum('price'), 2) }}</p>
                        </div>
                    </div>
                    @if($tower->equipment_notes)
                    <p class="text-sm text-gray-600 mt-4 pt-4 border-t border-blue-200">{{ $tower->equipment_notes }}</p>
                    @endif
                </div>

                <div>
                    <h3 class="font-semibold text-gray-900 mb-4">المعدات المثبتة</h3>
                    <div class="bg-white border rounded-lg overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-right text-xs font-bold text-gray-600">الجهاز</th>
                                    <th class="px-4 py-3 text-right text-xs font-bold text-gray-600">النوع</th>
                                    <th class="px-4 py-3 text-right text-xs font-bold text-gray-600">IP</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($tower->routers as $router)
                                <tr>
                                    <td class="px-4 py-3 text-sm">{{ $router->name }}</td>
                                    <td class="px-4 py-3 text-sm">
                                        @if($router->device_type === 'router') 📡 راوتر
                                        @elseif($router->device_type === 'access_point') 📶 نقطة وصول
                                        @else 🗼 محطة بث
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-sm font-mono">{{ $router->ip }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-8 text-center text-gray-500">
                                        لا توجد معدات مثبتة بعد
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab: Power -->
        <div x-show="activeTab === 'power'" class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Ampere -->
                <div class="bg-white border rounded-xl p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <span class="text-2xl">⚡</span>
                        </div>
                        <h3 class="font-bold text-lg">كهرباء أمبير</h3>
                    </div>

                    @if($tower->has_ampere)
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-gray-500">الاشتراك الشهري</p>
                            <p class="text-xl font-bold text-gray-900">${{ number_format($tower->ampere_subscription_monthly, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">سعر الكيلو واط</p>
                            <p class="text-lg font-semibold text-gray-900">${{ number_format($tower->kwh_price, 2) }}/kWh</p>
                        </div>
                    </div>
                    @else
                    <p class="text-gray-500">⚪ غير متوفر</p>
                    @endif
                </div>

                <!-- Solar -->
                <div class="bg-white border rounded-xl p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                            <span class="text-2xl">☀️</span>
                        </div>
                        <h3 class="font-bold text-lg">الطاقة الشمسية</h3>
                    </div>

                    @if($tower->has_solar)
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-gray-500">عدد الألواح</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $tower->solar_panels_count }} لوح</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">استطاعة اللوح الواحد</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $tower->solar_panel_wattage }}W</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">الاستطاعة الكلية</p>
                            <p class="text-xl font-bold text-green-600">{{ $tower->total_solar_capacity }}W</p>
                        </div>
                        <div class="pt-3 border-t">
                            <p class="text-sm text-gray-500">تكلفة التركيب</p>
                            <p class="text-lg font-semibold text-gray-900">${{ number_format($tower->solar_installation_cost, 2) }}</p>
                        </div>
                    </div>
                    @else
                    <p class="text-gray-500">⚪ غير متوفر</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Tab: SSIDs -->
        <div x-show="activeTab === 'ssids'" class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-semibold text-gray-900">📶 شبكات WiFi على البرج</h3>
                <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm">
                    + إضافة SSID
                </button>
            </div>

            <div class="bg-white border rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-right text-xs font-bold text-gray-600">SSID</th>
                            <th class="px-4 py-3 text-right text-xs font-bold text-gray-600">التردد</th>
                            <th class="px-4 py-3 text-right text-xs font-bold text-gray-600">الجهاز</th>
                            <th class="px-4 py-3 text-right text-xs font-bold text-gray-600">الحالة</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($tower->ssids as $ssid)
                        <tr>
                            <td class="px-4 py-3 text-sm font-medium">{{ $ssid->ssid_name }}</td>
                            <td class="px-4 py-3 text-sm">{!! $ssid->frequency_badge !!}</td>
                            <td class="px-4 py-3 text-sm">{{ $ssid->router?->name ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm">{!! $ssid->status_badge !!}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                                لم يتم إضافة SSIDs بعد
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tab: Costs -->
        <div x-show="activeTab === 'costs'" class="p-6">
            <div class="space-y-6">
                <!-- Setup Costs -->
                <div class="bg-purple-50 border border-purple-200 rounded-xl p-6">
                    <h3 class="font-bold text-lg text-gray-900 mb-4">🛠️ تكاليف التجهيز الأولية</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">تكلفة الهيكل</p>
                            <p class="text-2xl font-bold text-gray-800">${{ number_format($tower->structure_cost, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">تكلفة الأجهزة</p>
                            <p class="text-2xl font-bold text-gray-800">${{ number_format($tower->routers->sum('price'), 2) }}</p>
                        </div>
                        <div class="border-r border-purple-200 pr-4">
                            <p class="text-sm text-purple-800 font-semibold">الإجمالي الكلي</p>
                            <p class="text-3xl font-bold text-purple-600">${{ number_format($tower->total_equipment_cost, 2) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Fixed Costs -->
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
                    <h3 class="font-bold text-lg text-gray-900 mb-4">💵 التكاليف الثابتة الشهرية</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">صيانة</p>
                            <p class="text-lg font-bold">${{ number_format($tower->monthly_maintenance, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">إيجار</p>
                            <p class="text-lg font-bold">${{ number_format($tower->monthly_rent, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">أخرى</p>
                            <p class="text-lg font-bold">${{ number_format($tower->monthly_other_costs, 2) }}</p>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-blue-300">
                        <p class="text-sm text-gray-600">الإجمالي الثابت</p>
                        <p class="text-2xl font-bold text-blue-600">${{ number_format($tower->monthly_fixed_costs, 2) }}/شهر</p>
                    </div>
                </div>

                <!-- Current Month -->
                <div class="bg-green-50 border border-green-200 rounded-xl p-6">
                    <h3 class="font-bold text-lg text-gray-900 mb-2">📅 تكلفة الشهر الحالي</h3>
                    <p class="text-3xl font-bold text-green-600">${{ number_format($tower->getCurrentMonthCost(), 2) }}</p>
                    <p class="text-sm text-gray-600 mt-1">{{ now()->format('F Y') }}</p>
                </div>

                <!-- Monthly Costs History -->
                <div>
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-semibold text-gray-900">📊 سجل التكاليف الشهرية</h3>
                        <button class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-sm">
                            + إضافة فاتورة
                        </button>
                    </div>

                    <div class="bg-white border rounded-lg overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-right text-xs font-bold text-gray-600">الشهر</th>
                                    <th class="px-4 py-3 text-right text-xs font-bold text-gray-600">فاتورة أمبير</th>
                                    <th class="px-4 py-3 text-right text-xs font-bold text-gray-600">مازوت (ديزل)</th>
                                    <th class="px-4 py-3 text-right text-xs font-bold text-gray-600">صيانة</th>
                                    <th class="px-4 py-3 text-right text-xs font-bold text-gray-600">أخرى</th>
                                    <th class="px-4 py-3 text-right text-xs font-bold text-gray-600">الإجمالي</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($tower->monthlyCosts()->orderBy('month', 'desc')->take(6)->get() as $cost)
                                <tr>
                                    <td class="px-4 py-3 text-sm font-medium">{{ \Carbon\Carbon::parse($cost->month . '-01')->format('F Y') }}</td>
                                    <td class="px-4 py-3 text-sm">${{ number_format($cost->ampere_bill, 2) }}</td>
                                    <td class="px-4 py-3 text-sm">${{ number_format($cost->diesel_cost, 2) }}</td>
                                    <td class="px-4 py-3 text-sm">${{ number_format($cost->maintenance_cost, 2) }}</td>
                                    <td class="px-4 py-3 text-sm">${{ number_format($cost->other_costs, 2) }}</td>
                                    <td class="px-4 py-3 text-sm font-bold text-green-600">${{ number_format($cost->total_cost, 2) }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                        لم يتم إضافة فواتير بعد
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

```

## resources/views/servers\create.blade.php

```php
@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<style>
    #map { height: 450px; width: 100%; border-radius: 2.5rem; z-index: 1; overflow: hidden; border: 4px solid white; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.1); }
    .glass-panel { background: rgba(255, 255, 255, 0.4); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); }
    [x-cloak] { display: none !important; }
</style>
@endpush

@section('content')
<div class="max-w-5xl mx-auto space-y-10 pb-20">
    <!-- Provisioning Header -->
    <div class="glass-panel border border-white/40 rounded-[3rem] p-10 md:p-12 shadow-2xl relative overflow-hidden">
        <div class="absolute -top-24 -right-24 w-64 h-64 bg-emerald-600/5 rounded-full blur-3xl"></div>
        <div class="flex flex-col md:flex-row items-center gap-8 relative z-10">
            <div class="w-20 h-20 bg-emerald-600 rounded-[2rem] flex items-center justify-center text-white shadow-xl shadow-emerald-100 shrink-0">
                <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 12h14M5 12l4-4m-4 4l4 4"/></svg>
            </div>
            <div class="text-center md:text-left">
                <h2 class="text-3xl font-black text-gray-900 tracking-tighter uppercase">Initialize MikroTik Node</h2>
                <p class="text-gray-500 font-bold text-xs uppercase tracking-widest mt-1">Registering main infrastructure core for user governance</p>
            </div>
        </div>
    </div>

    <!-- Notification Layer -->
    @if(session('error'))
    <div class="bg-rose-500/10 border border-rose-500/20 p-6 rounded-[2rem] animate-in fade-in zoom-in duration-500">
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 bg-rose-500 text-white rounded-xl flex items-center justify-center shadow-lg shadow-rose-100">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <p class="text-rose-900 font-black text-xs uppercase tracking-widest">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    @if($errors->any())
    <div class="bg-rose-500/10 border border-rose-500/20 p-8 rounded-[2rem] animate-in fade-in zoom-in duration-500">
        <div class="flex items-start gap-5">
            <div class="w-12 h-12 bg-rose-500 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-rose-100 shrink-0">
                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-rose-900 font-black text-sm uppercase tracking-tight mb-3">Validation Protocol Failed</p>
                <ul class="space-y-1">
                    @foreach($errors->all() as $error)
                    <li class="text-rose-800/80 font-bold text-[11px] uppercase tracking-wide flex items-center gap-2">
                        <span class="w-1.5 h-1.5 bg-rose-500 rounded-full"></span>
                        {{ $error }}
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    <form action="{{ route('servers.store') }}" method="POST" class="space-y-10">
        @csrf
        
        <!-- Hardware Architecture & Catalog -->
        <div class="glass-panel border border-white/40 rounded-[3rem] p-10 md:p-12 shadow-2xl relative overflow-hidden" x-data="modelSelector({{ $products->map(fn($p) => ['id' => $p->id, 'model_name' => $p->model_name, 'image_url' => $p->image_url, 'price' => $p->max_throughput, 'description' => $p->description, 'manufacturer' => $p->manufacturer, 'device_type' => $p->device_type])->toJson() }}, {{ old('model_id') ?? 'null' }})">
            <div class="flex items-center gap-4 mb-10">
                <div class="w-12 h-12 bg-indigo-600/10 rounded-2xl flex items-center justify-center text-indigo-600 font-bold">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/></svg>
                </div>
                <h3 class="text-2xl font-black text-gray-900 tracking-tight uppercase">Hardware Intelligence Core</h3>
            </div>
            
            <!-- Global Device Discovery Matrix -->
            <div class="mb-12 relative group">
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4 ml-2">Smart Discovery Search</label>
                <div class="relative">
                    <input type="text" 
                           x-model="deviceQuery"
                           @input.debounce.300ms="searchDevices()"
                           @click.away="showDevices = false"
                           class="w-full pl-14 pr-6 py-5 bg-white/40 backdrop-blur-md border border-white/60 rounded-3xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500/40 font-bold text-gray-900 transition-all shadow-inner" 
                           placeholder="Type to search global MikroTik matrix... (e.g. CCR2004)"
                           autocomplete="off">
                    <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-gray-400 group-focus-within:text-indigo-600 transition-colors">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                </div>

                <!-- Live Results Hologram -->
                <div x-show="showDevices && devices.length > 0" 
                     x-cloak
                     x-transition:enter="duration-300 ease-out"
                     class="absolute z-50 w-full mt-4 bg-white/95 backdrop-blur-xl border border-white/60 rounded-[2.5rem] shadow-2xl max-h-96 overflow-y-auto p-4 space-y-2">
                    <template x-for="device in devices" :key="device.id">
                        <div @click="selectDevice(device)" 
                             class="flex items-center gap-4 p-4 hover:bg-indigo-600 hover:text-white rounded-2xl cursor-pointer transition-all">
                            <div class="w-16 h-16 bg-white rounded-xl border border-gray-100 p-2 flex items-center justify-center shrink-0">
                                <img :src="device.image_url" class="max-w-full max-h-full object-contain" onerror="this.src='https://placehold.co/150x100?text=Node'">
                            </div>
                            <div>
                                <p class="font-black text-sm tracking-tight" x-text="device.manufacturer + ' ' + device.model_name" dir="ltr"></p>
                                <p class="text-[9px] font-black uppercase tracking-widest opacity-60" x-text="device.device_type"></p>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Curated Inventory Registry -->
            <div x-show="!deviceQuery || devices.length === 0" class="mb-12 animate-in fade-in duration-700">
                <p class="text-[10px] font-black text-indigo-600 uppercase tracking-[0.3em] mb-10 text-center bg-indigo-50/50 py-2.5 rounded-full border border-indigo-100/50 shadow-sm">Distributed Infrastructure Catalog</p>
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
                    <template x-for="product in products" :key="product.id">
                        <div @click="selectProduct(product)" 
                             class="cursor-pointer glass-panel border bg-white/40 backdrop-blur-md rounded-[2rem] p-6 transition-all duration-500 hover:scale-[1.05] hover:shadow-2xl text-center group relative overflow-hidden flex flex-col items-center border-white/60"
                             :class="selectedModelId === product.id ? 'ring-4 ring-emerald-500/20 border-emerald-500/40 bg-emerald-500/5' : 'hover:border-indigo-600/40'">
                            
                            <!-- Selection Check -->
                            <div x-show="selectedModelId === product.id" class="absolute top-4 right-4 text-emerald-500 animate-in zoom-in duration-300">
                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            </div>

                            <div class="h-32 w-full flex items-center justify-center mb-6">
                                <img :src="product.image_url" :alt="product.model_name" class="max-h-full max-w-full object-contain filter drop-shadow-lg group-hover:scale-110 transition-transform duration-700" onerror="this.src='https://placehold.co/150x100?text=Hardware'">
                            </div>

                            <div class="w-full">
                                <h4 class="text-[11px] font-black text-gray-900 group-hover:text-indigo-600 transition-colors uppercase tracking-tight line-clamp-2" x-text="product.model_name" dir="ltr"></h4>
                                <p class="text-[8px] font-black text-gray-400 uppercase tracking-widest mt-2" x-text="product.manufacturer"></p>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Identity Core -->
            <input type="hidden" name="model_id" :value="selectedModelId">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-end">
                <div class="space-y-4">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-2">Node Specification Name <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" x-model="serverName" class="w-full px-6 py-5 bg-white/40 backdrop-blur-md border border-white/60 rounded-3xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500/40 font-black text-gray-900 transition-all shadow-inner" placeholder="E.g. Main-Gateway-X1" required dir="ltr">
                </div>

                <!-- Live Preview Metadata -->
                <div class="bg-indigo-600 p-8 rounded-[2.5rem] flex items-center gap-8 shadow-xl shadow-indigo-100 transition-all duration-700 animate-in fade-in slide-in-from-right-8" x-show="previewImage" x-transition>
                    <div class="w-24 h-24 shrink-0 bg-white/20 backdrop-blur-md rounded-2xl border border-white/20 p-3 flex items-center justify-center shadow-lg">
                        <img :src="previewImage" class="max-w-full max-h-full object-contain filter drop-shadow-md">
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-[9px] font-black text-indigo-200 uppercase tracking-[0.2em] mb-1">Provisioning Preview</p>
                        <p class="text-sm font-black text-white truncate uppercase tracking-tight" x-text="serverName.replace('MikroTik ', '')" dir="ltr"></p>
                    </div>
                </div>
            </div>
        </div>

        <script>
        function modelSelector(initialProducts, initialModelId = null) {
            return {
                selectedModelId: initialModelId,
                serverName: '{{ old('name') }}',
                deviceQuery: '',
                previewImage: '',
                devices: [],
                showDevices: false,
                products: initialProducts || [],

                init() {
                    if (this.selectedModelId) {
                        const product = this.products.find(p => p.id == this.selectedModelId);
                        if (product) {
                            this.previewImage = product.image_url;
                        }
                    }
                },

                async searchDevices() {
                    if (this.deviceQuery.length < 2) {
                        this.devices = [];
                        this.showDevices = false;
                        return;
                    }
                    try {
                        const response = await fetch(`{{ route('api.devices.search') }}?q=${this.deviceQuery}`);
                        this.devices = await response.json();
                        this.showDevices = true;
                    } catch (e) {
                        console.error("Search failed", e);
                    }
                },

                selectDevice(device) {
                    this.serverName = 'MikroTik ' + device.model_name;
                    this.previewImage = device.image_url;
                    this.deviceQuery = device.model_name;
                    this.selectedModelId = device.id;
                    this.showDevices = false;
                },

                selectProduct(product) {
                    this.selectedModelId = product.id;
                    this.serverName = product.manufacturer + ' ' + product.model_name;
                    this.previewImage = product.image_url;
                    this.deviceQuery = '';
                }
            }
        }
        </script>

        <!-- Geospatial Registry Shield -->
        <div class="glass-panel border border-white/40 rounded-[3rem] p-10 md:p-12 shadow-2xl relative overflow-hidden">
            <div class="flex items-center gap-4 mb-12">
                <div class="w-12 h-12 bg-rose-600/10 rounded-2xl flex items-center justify-center text-rose-600 font-bold">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <h3 class="text-2xl font-black text-gray-900 tracking-tight uppercase">Geospatial Distribution Point</h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                <div class="space-y-4">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1 ml-2">Backbone Connectivity Source</label>
                    <div class="relative">
                        <select name="internet_source_id" class="w-full pl-6 pr-12 py-5 bg-white/40 backdrop-blur-md border border-white/60 rounded-3xl focus:ring-4 focus:ring-rose-500/10 focus:border-rose-500/40 font-black text-gray-900 appearance-none transition-all shadow-inner">
                            <option value="">Autonomous Gateway (Standalone)</option>
                            @foreach($internetSources ?? [] as $source)
                            <option value="{{ $source->id }}" {{ old('internet_source_id') == $source->id ? 'selected' : '' }}>
                                🌐 {{ $source->name }} ({{ $source->type }})
                            </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-6 flex items-center pointer-events-none text-gray-400">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1 ml-2">Structural Assembly (Tower)</label>
                    <div class="relative">
                        <select name="location_tower_id" class="w-full pl-6 pr-12 py-5 bg-white/40 backdrop-blur-md border border-white/60 rounded-3xl focus:ring-4 focus:ring-rose-500/10 focus:border-rose-500/40 font-black text-gray-900 appearance-none transition-all shadow-inner">
                            <option value="">Virtual Infrastructure (Independent)</option>
                            @foreach($towers ?? [] as $tower)
                            <option value="{{ $tower->id }}" {{ old('location_tower_id') == $tower->id ? 'selected' : '' }}>
                                🗼 {{ $tower->name }}
                            </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-6 flex items-center pointer-events-none text-gray-400">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>
                </div>

                <div class="md:col-span-2 space-y-4">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1 ml-2">Physical Location Intelligence</label>
                    <input type="text" name="location" value="{{ old('location') }}" class="w-full px-6 py-5 bg-white/40 backdrop-blur-md border border-white/60 rounded-3xl focus:ring-4 focus:ring-rose-500/10 focus:border-rose-500/40 font-black text-gray-900 transition-all shadow-inner" placeholder="E.g. Main Distribution Frame - Terminal 4C">
                </div>

                <!-- Geospatial Plotting Control -->
                <div class="md:col-span-2 space-y-8" x-data="serverMap()" x-init="init()">
                    <div class="flex items-center justify-between">
                        <label class="block text-[10px] font-black text-rose-600 uppercase tracking-[0.3em] ml-2">Coordinate Synchronization Shield</label>
                        <div class="flex gap-4">
                            <div class="bg-rose-50 px-6 py-2 rounded-2xl border border-rose-100 flex items-center gap-3">
                                <span class="text-[9px] font-black text-rose-400 uppercase tracking-widest">N-Lat</span>
                                <input type="text" name="lat" x-model="lat" class="bg-transparent border-none p-0 text-xs font-mono font-black text-rose-900 w-24 focus:ring-0" readonly>
                            </div>
                            <div class="bg-emerald-50 px-6 py-2 rounded-2xl border border-emerald-100 flex items-center gap-3">
                                <span class="text-[9px] font-black text-emerald-400 uppercase tracking-widest">E-Lng</span>
                                <input type="text" name="lng" x-model="lng" class="bg-transparent border-none p-0 text-xs font-mono font-black text-emerald-900 w-24 focus:ring-0" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="aspect-video w-full rounded-[3.5rem] overflow-hidden border border-white/80 shadow-2xl relative group">
                        <div id="map" class="w-full h-full z-0 grayscale-[0.4] contrast-[1.1] brightness-[0.95]"></div>
                        <div class="absolute inset-0 bg-rose-500/5 pointer-events-none group-focus-within:bg-transparent transition-all"></div>
                        <div class="absolute top-8 left-8 z-[10] bg-white/60 backdrop-blur-md px-6 py-3 rounded-2xl border border-white/60 shadow-xl opacity-0 group-hover:opacity-100 transition-all duration-500">
                            <p class="text-[9px] font-black text-gray-900 uppercase tracking-widest">Global Mapping Engine v2.4</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
        function serverMap() {
            return {
                lat: '{{ old('lat') }}',
                lng: '{{ old('lng') }}',
                map: null,
                marker: null,
                init() {
                    setTimeout(() => {
                        this.map = L.map('map', {zoomControl: false}).setView([33.5138, 36.2765], 13);
                        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
                            maxZoom: 19
                        }).addTo(this.map);
                        
                        L.control.zoom({position: 'bottomleft'}).addTo(this.map);

                        const customIcon = L.divIcon({
                            className: 'custom-div-icon',
                            html: "<div style='background-color:#e11d48; width:16px; height:16px; border-radius:50%; border:3px solid white; shadow:0 5px 15px rgba(225,29,72,0.4)'></div>",
                            iconSize: [20, 20],
                            iconAnchor: [10, 10]
                        });

                        if (this.lat && this.lng) {
                            const initialLatLng = [parseFloat(this.lat), parseFloat(this.lng)];
                            this.marker = L.marker(initialLatLng, {icon: customIcon}).addTo(this.map);
                            this.map.setView(initialLatLng, 15);
                        }

                        this.map.on('click', (e) => {
                            this.lat = e.latlng.lat.toFixed(7);
                            this.lng = e.latlng.lng.toFixed(7);
                            if (this.marker) {
                                this.marker.setLatLng(e.latlng);
                            } else {
                                this.marker = L.marker(e.latlng, {icon: customIcon}).addTo(this.map);
                            }
                        });
                    }, 200);
                }
            }
        }
        </script>

        <!-- Infrastructure Commitment -->
        <div class="flex flex-col md:flex-row items-center justify-between gap-10 glass-panel border border-white/40 p-12 rounded-[3.5rem] shadow-2xl relative overflow-hidden">
            <div class="absolute bottom-0 right-0 w-64 h-64 bg-emerald-600/5 rounded-full blur-3xl -mb-32 -mr-32"></div>
            <div class="flex items-center gap-8 relative z-10">
                <div class="w-16 h-16 bg-indigo-600/10 text-indigo-600 rounded-[1.5rem] flex items-center justify-center text-3xl shrink-0">💡</div>
                <div class="space-y-1">
                    <h5 class="text-lg font-black text-gray-900 uppercase tracking-tight">Provisioning Intelligence</h5>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Post-registration terminal logic will be generated for the RouterOS stack.</p>
                </div>
            </div>
            <div class="flex items-center gap-6 relative z-10">
                <a href="{{ route('servers.index') }}" class="px-10 py-5 bg-white/60 text-gray-500 font-black text-[11px] uppercase tracking-[0.2em] rounded-2xl hover:bg-white hover:text-gray-900 transition-all">
                    Discard Archive
                </a>
                <button type="submit" class="px-12 py-5 bg-gray-900 text-white font-black text-[11px] uppercase tracking-[0.3em] rounded-2xl shadow-2xl shadow-indigo-200 hover:bg-emerald-600 transition-all hover:scale-105 active:scale-95 leading-none">
                    Initialize Infrastructure commit
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
@endpush

```

## resources/views/servers\edit.blade.php

```php
@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<style>
    #map { height: 450px; width: 100%; border-radius: 2.5rem; z-index: 1; overflow: hidden; border: 4px solid white; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.1); }
    .glass-panel { background: rgba(255, 255, 255, 0.4); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); }
    [x-cloak] { display: none !important; }
</style>
@endpush

@section('content')
<div class="max-w-5xl mx-auto space-y-10 pb-20">
    <!-- Command Center Header -->
    <div class="glass-panel border border-white/40 rounded-[3rem] p-10 md:p-12 shadow-2xl relative overflow-hidden">
        <div class="absolute -top-24 -right-24 w-64 h-64 bg-indigo-600/5 rounded-full blur-3xl"></div>
        <div class="relative flex flex-col md:flex-row items-center justify-between gap-8 z-10">
            <div class="flex flex-col md:flex-row items-center gap-8">
                <div class="w-20 h-20 bg-indigo-600 rounded-[2rem] flex items-center justify-center text-white shadow-xl shadow-indigo-100 shrink-0">
                    <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </div>
                <div class="text-center md:text-left">
                    <h1 class="text-3xl font-black text-gray-900 tracking-tighter uppercase">Modify Node Protocol</h1>
                    <p class="text-gray-500 font-bold text-xs uppercase tracking-widest mt-1">Adjusting governance parameters for {{ $server->name }}</p>
                </div>
            </div>
            <a href="{{ route('servers.show', $server) }}" class="px-8 py-4 bg-white/60 text-gray-500 font-black text-[11px] uppercase tracking-widest rounded-2xl hover:bg-white hover:text-gray-900 transition-all border border-white/60 shadow-sm flex items-center gap-3">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 17l-5-5m0 0l5-5m-5 5h12"/></svg>
                Identity Overview
            </a>
        </div>
    </div>

    <form action="{{ route('servers.update', $server) }}" method="POST" class="space-y-10">
        @csrf
        @method('PUT')

        <!-- Hardware Blueprint (Read Onlyish) -->
        <div class="glass-panel border border-white/40 rounded-[3rem] p-10 md:p-12 shadow-2xl relative overflow-hidden">
            <div class="flex items-center gap-4 mb-10">
                <div class="w-12 h-12 bg-indigo-600/10 rounded-2xl flex items-center justify-center text-indigo-600 font-bold">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/></svg>
                </div>
                <h3 class="text-2xl font-black text-gray-900 tracking-tight uppercase">Infrastructure Blueprint</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                <div class="space-y-6">
                    <div class="space-y-3">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-2">Node Identity Name <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $server->name) }}" class="w-full px-6 py-5 bg-white/40 backdrop-blur-md border border-white/60 rounded-3xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500/40 font-black text-gray-900 transition-all shadow-inner" placeholder="E.g. Core-BGP-01" required dir="ltr">
                    </div>
                </div>

                <!-- Hardware Visual -->
                <div class="bg-indigo-600 p-8 rounded-[2.5rem] flex items-center gap-8 shadow-xl shadow-indigo-100 relative group">
                    <div class="absolute -top-12 -right-12 w-32 h-32 bg-white/10 rounded-full blur-3xl"></div>
                    <div class="w-24 h-24 shrink-0 bg-white/20 backdrop-blur-md rounded-2xl border border-white/20 p-3 flex items-center justify-center shadow-lg relative z-10">
                        <img src="{{ $server->deviceModel->image_url ?? 'https://placehold.co/150x100?text=Hardware' }}" class="max-w-full max-h-full object-contain filter drop-shadow-md">
                    </div>
                    <div class="flex-1 min-w-0 relative z-10">
                        <p class="text-[9px] font-black text-indigo-200 uppercase tracking-[0.2em] mb-1">Assigned Hardware</p>
                        <p class="text-sm font-black text-white truncate uppercase tracking-tight" dir="ltr">{{ $server->deviceModel->model_name ?? 'MikroTik Infrastructure' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Geospatial Registry Shield -->
        <div class="glass-panel border border-white/40 rounded-[3rem] p-10 md:p-12 shadow-2xl relative overflow-hidden">
            <div class="flex items-center gap-4 mb-12">
                <div class="w-12 h-12 bg-rose-600/10 rounded-2xl flex items-center justify-center text-rose-600 font-bold">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <h3 class="text-2xl font-black text-gray-900 tracking-tight uppercase">Geospatial Distribution Point</h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                <div class="space-y-4">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1 ml-2">Backbone Connectivity Source</label>
                    <div class="relative">
                        <select name="internet_source_id" class="w-full pl-6 pr-12 py-5 bg-white/40 backdrop-blur-md border border-white/60 rounded-3xl focus:ring-4 focus:ring-rose-500/10 focus:border-rose-500/40 font-black text-gray-900 appearance-none transition-all shadow-inner">
                            <option value="">Autonomous Gateway (Standalone)</option>
                            @foreach($internetSources ?? [] as $source)
                            <option value="{{ $source->id }}" {{ old('internet_source_id', $server->internet_source_id) == $source->id ? 'selected' : '' }}>
                                🌐 {{ $source->name }} ({{ $source->type }})
                            </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-6 flex items-center pointer-events-none text-gray-400">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1 ml-2">Structural Assembly (Tower)</label>
                    <div class="relative">
                        <select name="location_tower_id" class="w-full pl-6 pr-12 py-5 bg-white/40 backdrop-blur-md border border-white/60 rounded-3xl focus:ring-4 focus:ring-rose-500/10 focus:border-rose-500/40 font-black text-gray-900 appearance-none transition-all shadow-inner">
                            <option value="">Virtual Infrastructure (Independent)</option>
                            @foreach($towers ?? [] as $tower)
                            <option value="{{ $tower->id }}" {{ old('location_tower_id', $server->location_tower_id) == $tower->id ? 'selected' : '' }}>
                                🗼 {{ $tower->name }}
                            </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-6 flex items-center pointer-events-none text-gray-400">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>
                </div>

                <div class="md:col-span-2 space-y-4">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1 ml-2">Physical Location Intelligence</label>
                    <input type="text" name="location" value="{{ old('location', $server->location) }}" class="w-full px-6 py-5 bg-white/40 backdrop-blur-md border border-white/60 rounded-3xl focus:ring-4 focus:ring-rose-500/10 focus:border-rose-500/40 font-black text-gray-900 transition-all shadow-inner" placeholder="E.g. Main Distribution Frame - Terminal 4C">
                </div>

                <!-- Geospatial Plotting Control -->
                <div class="md:col-span-2 space-y-8" x-data="serverMap()" x-init="init()">
                    <div class="flex items-center justify-between">
                        <label class="block text-[10px] font-black text-rose-600 uppercase tracking-[0.3em] ml-2">Coordinate Synchronization Shield</label>
                        <div class="flex gap-4">
                            <div class="bg-rose-50 px-6 py-2 rounded-2xl border border-rose-100 flex items-center gap-3">
                                <span class="text-[9px] font-black text-rose-400 uppercase tracking-widest">N-Lat</span>
                                <input type="text" name="lat" x-model="lat" class="bg-transparent border-none p-0 text-xs font-mono font-black text-rose-900 w-24 focus:ring-0" readonly>
                            </div>
                            <div class="bg-emerald-50 px-6 py-2 rounded-2xl border border-emerald-100 flex items-center gap-3">
                                <span class="text-[9px] font-black text-emerald-400 uppercase tracking-widest">E-Lng</span>
                                <input type="text" name="lng" x-model="lng" class="bg-transparent border-none p-0 text-xs font-mono font-black text-emerald-900 w-24 focus:ring-0" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="aspect-video w-full rounded-[3.5rem] overflow-hidden border border-white/80 shadow-2xl relative group">
                        <div id="map" class="w-full h-full z-0 grayscale-[0.4] contrast-[1.1] brightness-[0.95]"></div>
                        <div class="absolute inset-0 bg-rose-500/5 pointer-events-none group-focus-within:bg-transparent transition-all"></div>
                    </div>
                </div>
            </div>
        </div>

        <script>
        function serverMap() {
            return {
                lat: '{{ old('lat', $server->lat) }}',
                lng: '{{ old('lng', $server->lng) }}',
                map: null,
                marker: null,
                init() {
                    setTimeout(() => {
                        const startLat = this.lat || 33.5138;
                        const startLng = this.lng || 36.2765;
                        this.map = L.map('map', {zoomControl: false}).setView([startLat, startLng], 13);
                        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
                            maxZoom: 19
                        }).addTo(this.map);
                        
                        L.control.zoom({position: 'bottomleft'}).addTo(this.map);

                        const customIcon = L.divIcon({
                            className: 'custom-div-icon',
                            html: "<div style='background-color:#e11d48; width:16px; height:16px; border-radius:50%; border:3px solid white; shadow:0 5px 15px rgba(225,29,72,0.4)'></div>",
                            iconSize: [20, 20],
                            iconAnchor: [10, 10]
                        });

                        if (this.lat && this.lng) {
                            const initialLatLng = [parseFloat(this.lat), parseFloat(this.lng)];
                            this.marker = L.marker(initialLatLng, {icon: customIcon}).addTo(this.map);
                            this.map.setView(initialLatLng, 15);
                        }

                        this.map.on('click', (e) => {
                            this.lat = e.latlng.lat.toFixed(7);
                            this.lng = e.latlng.lng.toFixed(7);
                            if (this.marker) {
                                this.marker.setLatLng(e.latlng);
                            } else {
                                this.marker = L.marker(e.latlng, {icon: customIcon}).addTo(this.map);
                            }
                        });
                    }, 200);
                }
            }
        }
        </script>

        <!-- Connectivity Credentials Stack -->
        <div class="glass-panel border border-white/40 rounded-[3rem] p-10 md:p-12 shadow-2xl relative overflow-hidden">
            <div class="flex items-center gap-4 mb-12">
                <div class="w-12 h-12 bg-emerald-600/10 rounded-2xl flex items-center justify-center text-emerald-600 font-bold">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <h3 class="text-2xl font-black text-gray-900 tracking-tight uppercase">Connection Governance Stack</h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                <div class="space-y-4">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1 ml-2">Encrypted API Gateway (IP)</label>
                    <input type="text" name="ip" value="{{ old('ip', $server->ip) }}" class="w-full px-6 py-5 bg-white/40 backdrop-blur-md border border-white/60 rounded-3xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500/40 font-mono font-black text-gray-900 transition-all shadow-inner" dir="ltr">
                </div>
                <div class="space-y-4">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1 ml-2">Protocol Port Index</label>
                    <input type="number" name="api_port" value="{{ old('api_port', $server->api_port) }}" class="w-full px-6 py-5 bg-white/40 backdrop-blur-md border border-white/60 rounded-3xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500/40 font-mono font-black text-gray-900 transition-all shadow-inner" dir="ltr">
                </div>
                <div class="space-y-4">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1 ml-2">Governance User ID</label>
                    <input type="text" name="username" value="{{ old('username', $server->username) }}" class="w-full px-6 py-5 bg-white/40 backdrop-blur-md border border-white/60 rounded-3xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500/40 font-black text-gray-900 transition-all shadow-inner" dir="ltr">
                </div>
                <div class="space-y-4">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1 ml-2">Secure Passcode Index (Unchanged if blank)</label>
                    <input type="password" name="password" autocomplete="new-password" class="w-full px-6 py-5 bg-white/40 backdrop-blur-md border border-white/60 rounded-3xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500/40 font-black text-gray-900 transition-all shadow-inner" placeholder="••••••••••••" dir="ltr">
                </div>
            </div>
        </div>

        <!-- Infrastructure Commitment -->
        <div class="flex flex-col md:flex-row items-center justify-between gap-10 glass-panel border border-white/40 p-12 rounded-[3.5rem] shadow-2xl relative overflow-hidden">
            <div class="absolute bottom-0 right-0 w-64 h-64 bg-emerald-600/5 rounded-full blur-3xl -mb-32 -mr-32"></div>
            <div class="flex items-center gap-8 relative z-10">
                <div class="w-16 h-16 bg-indigo-600/10 text-indigo-600 rounded-[1.5rem] flex items-center justify-center text-3xl shrink-0">💡</div>
                <div class="space-y-1">
                    <h5 class="text-lg font-black text-gray-900 uppercase tracking-tight">Governance Commitment</h5>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Protocol synchronization will re-initialize upon commitment.</p>
                </div>
            </div>
            <div class="flex items-center gap-6 relative z-10">
                <a href="{{ route('servers.show', $server) }}" class="px-10 py-5 bg-white/60 text-gray-500 font-black text-[11px] uppercase tracking-[0.2em] rounded-2xl hover:bg-white hover:text-gray-900 transition-all">
                    Discard Changes
                </a>
                <button type="submit" class="px-12 py-5 bg-gray-900 text-white font-black text-[11px] uppercase tracking-[0.3em] rounded-2xl shadow-2xl shadow-indigo-200 hover:bg-emerald-600 transition-all hover:scale-105 active:scale-95 leading-none">
                    Sync Infrastructure Commit
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
@endpush

```

## resources/views/servers\index.blade.php

```php
@extends('layouts.app')

@push('styles')
<style>
    .glass-panel { background: rgba(255, 255, 255, 0.4); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); }
    [x-cloak] { display: none !important; }
    .status-pulse { position: relative; display: flex; align-items: center; justify-content: center; }
    .status-pulse::after { content: ''; position: absolute; width: 100%; height: 100%; border-radius: 50%; opacity: 0; animation: pulse 2s infinite; }
    @keyframes pulse { 0% { transform: scale(1); opacity: 0.5; } 100% { transform: scale(2.5); opacity: 0; } }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto space-y-10 pb-20">
    <!-- Infrastructure Fleet Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-10 glass-panel border border-white/40 rounded-[3rem] p-10 md:p-12 shadow-2xl relative overflow-hidden">
        <div class="absolute -top-24 -right-24 w-64 h-64 bg-indigo-600/5 rounded-full blur-3xl"></div>
        <div class="relative z-10">
            <h1 class="text-4xl font-black text-gray-900 tracking-tighter uppercase">Core Node Fleet</h1>
            <p class="text-xs font-bold text-indigo-600 uppercase tracking-[0.3em] mt-2 flex items-center gap-3">
                <span class="w-2 h-2 rounded-full bg-indigo-600 status-pulse shadow-[0_0_12px_rgba(79,70,229,0.5)]"></span>
                Primary Network Infrastructure Management
            </p>
        </div>
        <div class="flex items-center gap-6 relative z-10">
            <a href="{{ route('servers.create') }}" class="px-10 py-5 bg-gray-900 text-white font-black text-[11px] uppercase tracking-[0.2em] rounded-2xl shadow-2xl hover:bg-indigo-600 transition-all hover:scale-105 active:scale-95 group flex items-center gap-3 leading-none">
                <svg class="w-5 h-5 group-hover:rotate-90 transition-transform duration-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v12m8-8H4"/></svg>
                Deploy New Node
            </a>
        </div>
    </div>

    <!-- Active Node Matrix -->
    @if($servers->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10" x-data="serverGrid()">
            @foreach($servers as $server)
            <div class="glass-panel border border-white/40 rounded-[3rem] p-4 group hover:shadow-[0_32px_64px_-12px_rgba(79,70,229,0.15)] transition-all duration-700 hover:-translate-y-3 relative overflow-hidden flex flex-col min-h-[480px]">
                <div class="absolute -top-20 -right-20 w-48 h-48 bg-indigo-500/5 rounded-full blur-[100px] group-hover:bg-indigo-500/10 transition-all duration-700"></div>
                
                <div class="p-8 pb-4 flex-grow relative z-10">
                    <!-- Identity Shield -->
                    <div class="flex items-start justify-between mb-10">
                        <div class="flex items-center gap-6">
                            <div class="w-24 h-24 bg-white/60 backdrop-blur-md rounded-[2rem] border border-white/80 shadow-2xl p-4 flex items-center justify-center group-hover:scale-110 group-hover:-rotate-3 transition-all duration-700 overflow-hidden relative">
                                <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/5 to-transparent"></div>
                                <img src="{{ $server->deviceModel->image_url ?? '/images/devices/mikrotik_node.png' }}" 
                                     class="max-w-full max-h-full object-contain filter drop-shadow-md brightness-[0.95] group-hover:brightness-100 transition-all"
                                     onerror="this.src='https://placehold.co/150x100?text=Node'">
                            </div>
                            <div class="min-w-0">
                                <h3 class="text-2xl font-black text-gray-900 tracking-tighter truncate uppercase mb-2">{{ $server->name }}</h3>
                                <div class="bg-gray-900/5 px-4 py-2 rounded-xl inline-flex items-center gap-2 border border-white/40 shadow-inner">
                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                                    <p class="text-[10px] font-black font-mono text-gray-600 leading-none uppercase tracking-widest">{{ $server->ip }}:{{ $server->api_port }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Real-time Status Orb -->
                        <div id="status-orb-{{ $server->id }}" class="shrink-0">
                            @if($server->connection_status === 'connected')
                                <div class="w-12 h-12 bg-emerald-500/10 rounded-2xl flex items-center justify-center text-emerald-600 border border-emerald-500/20 shadow-[0_0_20px_rgba(16,185,129,0.1)]">
                                    <svg class="w-6 h-6 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                </div>
                            @elseif($server->connection_status === 'error')
                                <div class="w-12 h-12 bg-rose-500/10 rounded-2xl flex items-center justify-center text-rose-500 border border-rose-500/20 shadow-[0_0_20px_rgba(244,63,94,0.1)]">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                </div>
                            @else
                                <div class="w-12 h-12 bg-gray-500/10 rounded-2xl flex items-center justify-center text-gray-400 border border-gray-500/20">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Telemetry Summary -->
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div class="p-6 bg-white/40 backdrop-blur-md rounded-[2rem] border border-white/60 group-hover:bg-white/70 transition-all duration-500 shadow-sm relative overflow-hidden">
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1 relative z-10">Uplink Access</p>
                            <p class="text-xs font-black text-gray-800 tracking-tight truncate relative z-10">{{ $server->username }}</p>
                            <div class="absolute bottom-0 right-0 w-16 h-16 bg-indigo-500/5 rounded-full blur-xl -mb-8 -mr-8"></div>
                        </div>
                        <div class="p-6 bg-white/40 backdrop-blur-md rounded-[2rem] border border-white/60 group-hover:bg-white/70 transition-all duration-500 shadow-sm relative overflow-hidden">
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1 relative z-10">Site Registry</p>
                            <p class="text-xs font-black text-indigo-600 tracking-tight truncate relative z-10">📍 {{ $server->location ?? 'Independent Node' }}</p>
                            <div class="absolute bottom-0 right-0 w-16 h-16 bg-rose-500/5 rounded-full blur-xl -mb-8 -mr-8"></div>
                        </div>
                    </div>

                    <!-- Connection Protocol Diagnostics -->
                    <div class="flex items-center gap-3 mt-4">
                        <button @click="testConnection({{ $server->id }})" class="flex-1 py-4 bg-white/60 hover:bg-white text-indigo-600 font-extrabold text-[10px] uppercase tracking-[0.2em] rounded-2xl border border-white/80 shadow-sm transition-all active:scale-95 flex items-center justify-center gap-2 group/btn">
                            <svg class="w-4 h-4 group-hover/btn:rotate-180 transition-transform duration-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            Test Handshake
                        </button>
                    </div>
                </div>

                <!-- Command Interface -->
                <div class="p-8 pt-4 bg-white/20 backdrop-blur-xl border-t border-white/20 grid grid-cols-2 gap-4 relative z-10">
                    <a href="{{ route('servers.show', $server) }}" class="px-4 py-5 bg-gray-900 hover:bg-black text-white text-center font-black text-[11px] uppercase tracking-[0.3em] rounded-2xl shadow-2xl hover:shadow-gray-900/40 transition-all active:scale-95 leading-none">
                        Network Console
                    </a>
                    <a href="{{ route('servers.edit', $server) }}" class="px-4 py-5 bg-white/80 hover:bg-white text-gray-500 hover:text-indigo-600 text-center font-black text-[11px] uppercase tracking-[0.3em] rounded-2xl border border-white/60 transition-all active:scale-95 leading-none shadow-sm">
                        Edit Node
                    </a>
                    <form action="{{ route('servers.destroy', $server) }}" method="POST" onsubmit="return confirm('Initiate site decommissioning protocol?');" class="col-span-2">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full py-4 bg-rose-500/5 hover:bg-rose-500 hover:text-white text-rose-500 font-black text-[9px] uppercase tracking-[0.3em] rounded-2xl border border-rose-500/20 transition-all active:scale-95 leading-none group/delete mt-2">
                            Decommission Site Registry
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
            
            <!-- Strategic Expansion Slot -->
            <a href="{{ route('servers.create') }}" class="glass-panel border-4 border-dashed border-white/40 rounded-[3rem] p-12 flex flex-col items-center justify-center text-gray-400 hover:border-indigo-500/40 hover:text-indigo-600 hover:bg-indigo-50/20 transition-all duration-700 group min-h-[480px]">
                <div class="w-24 h-24 rounded-[2rem] bg-white/60 backdrop-blur-md flex items-center justify-center border border-white/80 shadow-2xl group-hover:scale-110 group-hover:rotate-12 transition-all duration-700 mb-8 relative">
                    <div class="absolute inset-0 bg-indigo-500/5 rounded-[2rem] animate-pulse"></div>
                    <svg class="w-12 h-12 relative z-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                </div>
                <span class="font-black text-sm uppercase tracking-[0.4em] text-center">Deploy New Site Registry Node</span>
            </a>
        </div>
    @else
        <!-- Vacant Fleet State -->
        <div class="glass-panel border border-white/40 rounded-[4rem] p-32 text-center relative overflow-hidden group">
            <div class="absolute -top-10 -right-10 w-64 h-64 bg-indigo-500/5 rounded-full blur-[120px] group-hover:bg-indigo-500/10 transition-all duration-1000"></div>
            <div class="w-32 h-32 bg-white/60 backdrop-blur-md rounded-[3rem] flex items-center justify-center mx-auto mb-12 border border-white/80 shadow-2xl group-hover:scale-110 transition-transform duration-700">
                <svg class="w-16 h-16 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/>
                </svg>
            </div>
            <h3 class="text-4xl font-black text-gray-900 tracking-tighter uppercase">Fleet Registry Empty</h3>
            <p class="text-[11px] text-gray-400 font-bold uppercase tracking-[0.3em] mt-4 mb-12 max-w-md mx-auto">No primary core nodes found. Register your first MikroTik gateway to initialize network governance.</p>
            <a href="{{ route('servers.create') }}" class="inline-flex items-center gap-4 px-14 py-6 bg-gray-900 text-white font-black text-[11px] uppercase tracking-[0.3em] rounded-[2rem] shadow-2xl hover:shadow-indigo-500/30 hover:bg-black transition-all hover:scale-105 active:scale-95 leading-none">
                Deploy Core Interface
            </a>
        </div>
    @endif
</div>

<script>
function serverGrid() {
    return {
        async testConnection(serverId) {
            const btn = event.currentTarget;
            const orb = document.getElementById(`status-orb-${serverId}`);
            const originalContent = btn.innerHTML;
            
            btn.disabled = true;
            btn.innerHTML = '<svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Initializing...';
            
            try {
                const response = await fetch(`/api/servers/${serverId}/test-connection`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                });
                const result = await response.json();
                
                if (result.success) {
                    orb.innerHTML = '<div class="w-12 h-12 bg-emerald-500/10 rounded-2xl flex items-center justify-center text-emerald-600 border border-emerald-500/20 shadow-[0_0_20px_rgba(16,185,129,0.2)] animate-in zoom-in duration-500"><svg class="w-6 h-6 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg></div>';
                    btn.innerHTML = '✅ Handshake Success';
                    btn.classList.remove('text-indigo-600');
                    btn.classList.add('text-emerald-600', 'border-emerald-500/40', 'bg-emerald-50/30');
                } else {
                    orb.innerHTML = '<div class="w-12 h-12 bg-rose-500/10 rounded-2xl flex items-center justify-center text-rose-500 border border-rose-500/20 shadow-[0_0_20px_rgba(244,63,94,0.2)] animate-in zoom-in duration-500"><svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg></div>';
                    btn.innerHTML = '❌ Link Failure';
                    btn.classList.remove('text-indigo-600');
                    btn.classList.add('text-rose-600', 'border-rose-500/40', 'bg-rose-50/30');
                    alert(result.message);
                }
            } catch (error) {
                console.error(error);
                btn.innerHTML = '⚠️ Engine Error';
            } finally {
                setTimeout(() => {
                    btn.disabled = false;
                    if (!btn.classList.contains('text-emerald-600') && !btn.classList.contains('text-rose-600')) {
                        btn.innerHTML = originalContent;
                    }
                }, 3000);
            }
        }
    }
}
</script>
@endsection

```

## resources/views/servers\show.blade.php

```php
@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto pb-12" x-data="serverDashboard()">
    <!-- Command Center Header -->
    <div class="glass-panel border border-white/40 rounded-[3rem] p-10 md:p-12 shadow-2xl relative overflow-hidden mb-10">
        <div class="absolute -top-24 -right-24 w-64 h-64 bg-indigo-600/5 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-emerald-600/5 rounded-full blur-3xl"></div>

        <div class="relative flex flex-col lg:flex-row items-center gap-12">
            <!-- Hardware Hologram -->
            <div class="relative group">
                <div class="w-56 h-56 bg-white/40 backdrop-blur-md rounded-[2.5rem] border border-white/60 p-6 flex items-center justify-center shadow-xl group-hover:scale-105 transition-all duration-700">
                    <img src="{{ $server->deviceModel->image_url ?? 'https://placehold.co/200x150?text=MikroTik' }}" 
                         alt="{{ $server->name }}" 
                         class="max-w-full max-h-full object-contain filter drop-shadow-2xl">
                </div>
                <div class="absolute -bottom-3 -right-3 transform transition-transform group-hover:scale-110">
                    @if($server->is_connected)
                        <div class="px-5 py-2 rounded-2xl bg-emerald-500 text-white font-black text-[10px] uppercase tracking-widest shadow-lg shadow-emerald-200 flex items-center gap-2 border-2 border-white">
                            <span class="w-2 h-2 bg-white rounded-full animate-pulse"></span>
                            Synchronized
                        </div>
                    @else
                        <div class="px-5 py-2 rounded-2xl bg-rose-500 text-white font-black text-[10px] uppercase tracking-widest shadow-lg shadow-rose-200 flex items-center gap-2 border-2 border-white">
                            <span class="w-2 h-2 bg-white rounded-full"></span>
                            Disconnected
                        </div>
                    @endif
                </div>
            </div>

            <!-- Identity Core -->
            <div class="flex-1 text-center lg:text-left">
                <div class="flex flex-col lg:flex-row lg:items-center gap-4 mb-6 justify-center lg:justify-start">
                    <h1 class="text-4xl font-black text-gray-900 tracking-tighter">{{ $server->name }}</h1>
                    <span class="px-4 py-1.5 bg-indigo-600/10 text-indigo-600 rounded-xl text-[10px] font-black uppercase tracking-[0.2em] border border-indigo-600/20">
                        {{ $server->deviceModel->model_name ?? 'MadaaQ Infrastructure' }}
                    </span>
                </div>
                
                <div class="flex flex-wrap justify-center lg:justify-start gap-4">
                    <div class="px-6 py-3 bg-white/60 backdrop-blur-md rounded-2xl border border-white/80 shadow-sm flex items-center gap-3 group/chip transition-all hover:bg-white">
                        <span class="text-lg grayscale group-hover/chip:grayscale-0 transition-all">🌐</span>
                        <span class="font-mono font-black text-gray-900 text-sm tracking-tight">{{ $server->ip }}</span>
                    </div>
                    <div class="px-6 py-3 bg-white/60 backdrop-blur-md rounded-2xl border border-white/80 shadow-sm flex items-center gap-3 group/chip transition-all hover:bg-white">
                        <span class="text-lg grayscale group-hover/chip:grayscale-0 transition-all">🔌</span>
                        <span class="font-mono font-black text-gray-900 text-sm tracking-tight">API:{{ $server->api_port }}</span>
                    </div>
                    <div class="px-6 py-3 bg-white/60 backdrop-blur-md rounded-2xl border border-white/80 shadow-sm flex items-center gap-3 group/chip transition-all hover:bg-white">
                        <span class="text-lg grayscale group-hover/chip:grayscale-0 transition-all">📍</span>
                        <span class="font-black text-gray-900 text-[11px] uppercase tracking-widest">{{ $server->location ?? 'Edge Node' }}</span>
                    </div>
                </div>
            </div>

            <!-- Global Actions -->
            <div class="shrink-0">
                <a href="{{ route('servers.edit', $server) }}" class="px-8 py-4 bg-indigo-600 text-white rounded-[2rem] font-black text-[11px] uppercase tracking-widest shadow-xl shadow-indigo-100 hover:scale-105 transition-all flex items-center gap-3">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    System Protocol
                </a>
            </div>
        </div>
    </div>

    <!-- Live Telemetry Orbs -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10" x-show="isConnected">
        <!-- CPU Orb -->
        <div class="glass-panel border border-white/40 rounded-[2.5rem] p-8 shadow-xl relative overflow-hidden group hover:scale-[1.02] transition-all">
            <div class="absolute -top-12 -right-12 w-32 h-32 bg-indigo-600/5 rounded-full blur-3xl group-hover:scale-150 transition-all"></div>
            <div class="relative flex items-center justify-between mb-8">
                <div>
                    <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Compute Load</h4>
                    <h3 class="text-4xl font-black text-gray-900 tracking-tighter" id="cpu-val">0%</h3>
                </div>
                <div class="w-14 h-14 bg-indigo-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-indigo-100">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/></svg>
                </div>
            </div>
            <div class="w-full bg-gray-100 h-2 rounded-full overflow-hidden border border-gray-50">
                <div id="cpu-bar" class="bg-indigo-600 h-full transition-all duration-1000 shadow-sm" style="width: 0%"></div>
            </div>
        </div>

        <!-- RAM Orb -->
        <div class="glass-panel border border-white/40 rounded-[2.5rem] p-8 shadow-xl relative overflow-hidden group hover:scale-[1.02] transition-all">
            <div class="absolute -top-12 -right-12 w-32 h-32 bg-emerald-600/5 rounded-full blur-3xl group-hover:scale-150 transition-all"></div>
            <div class="relative flex items-center justify-between">
                <div>
                    <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Active Memory</h4>
                    <h3 class="text-2xl font-black text-gray-900 tracking-tighter" id="mem-val">-- / --</h3>
                    <p class="text-[9px] font-black text-emerald-600 uppercase tracking-widest mt-1">Megabytes Available</p>
                </div>
                <div class="w-14 h-14 bg-emerald-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-emerald-100">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </div>

        <!-- PPPoE Orb -->
        <div class="glass-panel border border-white/40 rounded-[2.5rem] p-8 shadow-xl relative overflow-hidden group hover:scale-[1.02] transition-all">
            <div class="absolute -top-12 -right-12 w-32 h-32 bg-blue-600/5 rounded-full blur-3xl group-hover:scale-150 transition-all"></div>
            <div class="relative flex items-center justify-between">
                <div>
                    <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">PPPoE Registry</h4>
                    <h3 class="text-4xl font-black text-gray-900 tracking-tighter" id="active-pppoe-val">0</h3>
                    <p class="text-[9px] font-black text-blue-600 uppercase tracking-widest mt-1">Synchronized Sessions</p>
                </div>
                <div class="w-14 h-14 bg-blue-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-blue-100">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-15.857 21.213 0"/></svg>
                </div>
            </div>
        </div>

        <!-- Hotspot Orb -->
        <div class="glass-panel border border-white/40 rounded-[2.5rem] p-8 shadow-xl relative overflow-hidden group hover:scale-[1.02] transition-all">
            <div class="absolute -top-12 -right-12 w-32 h-32 bg-amber-600/5 rounded-full blur-3xl group-hover:scale-150 transition-all"></div>
            <div class="relative flex items-center justify-between">
                <div>
                    <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Hotspot Guard</h4>
                    <h3 class="text-4xl font-black text-gray-900 tracking-tighter" id="active-hotspot-val">0</h3>
                    <p class="text-[9px] font-black text-amber-600 uppercase tracking-widest mt-1">Voucher Connections</p>
                </div>
                <div class="w-14 h-14 bg-amber-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-amber-100">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
            </div>
        </div>
    </div>

    <!-- System Intelligence Registry -->
    <div class="glass-panel border border-white/40 rounded-[3rem] shadow-2xl overflow-hidden min-h-[600px] mb-12">
        <!-- Modern Navigator -->
        <div class="border-b border-gray-900/5 bg-white/30 backdrop-blur-md p-3">
            <nav class="flex overflow-x-auto no-scrollbar gap-2">
                <template x-for="tab in tabs" :key="tab.id">
                    <button @click="activeTab = tab.id" 
                            class="px-8 py-4 rounded-2xl font-black text-[11px] uppercase tracking-widest transition-all flex items-center gap-3 whitespace-nowrap"
                            :class="activeTab === tab.id ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100' : 'text-gray-500 hover:bg-white hover:text-gray-900'">
                        <span x-text="tab.icon"></span>
                        <span x-text="tab.name"></span>
                    </button>
                </template>
            </nav>
        </div>

        <!-- Intelligence Hub -->
        <div class="p-8 md:p-12">
            <!-- Global Overview -->
            <div x-show="activeTab === 'general'" x-transition:enter="duration-500 ease-out" class="space-y-12">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                    <!-- Unit Specifications -->
                    <div class="space-y-8">
                        <div class="flex items-center gap-4 mb-2">
                            <div class="w-12 h-12 bg-indigo-600/10 rounded-2xl flex items-center justify-center text-indigo-600">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            </div>
                            <h4 class="text-xl font-black text-gray-900 tracking-tight uppercase">Hardware Intelligence</h4>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-white/40 backdrop-blur-md p-6 rounded-3xl border border-white/60">
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Operating System</p>
                                <p class="font-bold text-gray-900 font-mono text-sm" id="version-val">--</p>
                            </div>
                            <div class="bg-white/40 backdrop-blur-md p-6 rounded-3xl border border-white/60">
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Up-time Period</p>
                                <p class="font-bold text-gray-900 font-mono text-sm tracking-tighter" id="uptime-val">--</p>
                            </div>
                            <div class="bg-white/40 backdrop-blur-md p-6 rounded-3xl border border-white/60">
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Governance Access</p>
                                <p class="font-black text-gray-900 text-sm tracking-tight">{{ $server->username }}</p>
                            </div>
                            <div class="bg-white/40 backdrop-blur-md p-6 rounded-3xl border border-white/60">
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Sync Telemetry</p>
                                <p class="font-black text-indigo-600 text-[10px] uppercase">
                                    {{ $server->last_sync_at ? $server->last_sync_at->diffForHumans() : 'Standby Mode' }}
                                </p>
                            </div>
                        </div>
                    </div>
                        
                        <!-- Connectivity Spectrum (DNS) -->
                        <div id="top-sites-card-new" class="hidden animate-in fade-in slide-in-from-bottom-4 duration-700">
                            <div class="flex items-center gap-4 mb-6">
                                <div class="w-12 h-12 bg-emerald-600/10 rounded-2xl flex items-center justify-center text-emerald-600">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                                </div>
                                <h4 class="text-xl font-black text-gray-900 tracking-tight uppercase">DNS Demand Matrix</h4>
                            </div>
                            <div class="glass-panel border border-white/60 p-8 rounded-[2.5rem] space-y-6" id="top-sites-list-new">
                                <!-- Synchronized via JS -->
                            </div>
                        </div>
                    </div>

                    <!-- Geospatial Node Plot -->
                    <div class="space-y-8">
                        <div class="flex items-center gap-4 mb-2">
                            <div class="w-12 h-12 bg-rose-600/10 rounded-2xl flex items-center justify-center text-rose-600">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <h4 class="text-xl font-black text-gray-900 tracking-tight uppercase">Network Topology Point</h4>
                        </div>
                        <div class="aspect-video w-full rounded-[2.5rem] overflow-hidden border border-white/80 shadow-2xl relative group">
                            @if($server->lat && $server->lng)
                                <div id="map" class="w-full h-full z-0 grayscale-[0.5] contrast-[1.1]"></div>
                                <div class="absolute bottom-6 right-6 z-[10] bg-white/40 backdrop-blur-md px-6 py-3 rounded-2xl border border-white/60 shadow-xl">
                                    <p class="text-[9px] font-black text-rose-600 uppercase tracking-widest mb-1">Coordinates Verified</p>
                                    <p class="font-mono text-xs font-bold text-gray-900">{{ $server->lat }}, {{ $server->lng }}</p>
                                </div>
                            @else
                                <div class="w-full h-full bg-slate-50 flex flex-col items-center justify-center text-gray-400 gap-4">
                                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center text-3xl">🗺️</div>
                                    <p class="font-black text-xs uppercase tracking-widest">Geospatial Data Missing</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Services Registry (Users) -->
            <div x-show="activeTab === 'users'" x-cloak x-transition:enter="duration-500 ease-out" class="space-y-8">
                <div id="sessions-card-new" class="space-y-8">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-emerald-600/10 rounded-2xl flex items-center justify-center text-emerald-600">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            </div>
                            <h4 class="text-xl font-black text-gray-900 tracking-tight uppercase">Live Session Registry</h4>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="px-6 py-2 bg-emerald-600/5 text-emerald-700 rounded-xl border border-emerald-600/10 text-[10px] font-black uppercase tracking-widest">
                                PPPoE Array: <span id="pppoe-count-label-new" class="ml-1">0</span>
                            </div>
                            <div class="px-6 py-2 bg-amber-600/5 text-amber-700 rounded-xl border border-amber-600/10 text-[10px] font-black uppercase tracking-widest">
                                Proxy Hub: <span id="hotspot-count-label-new" class="ml-1">0</span>
                            </div>
                        </div>
                    </div>

                    <div class="glass-panel border border-white/60 rounded-[2.5rem] overflow-hidden shadow-xl">
                        <table class="w-full text-right border-collapse">
                            <thead>
                                <tr class="bg-gray-900/5 border-b border-gray-900/5">
                                    <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">End-User Identity</th>
                                    <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Network Interface (IP)</th>
                                    <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Protocol Type</th>
                                    <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Signal Uptime</th>
                                </tr>
                            </thead>
                            <tbody id="sessions-list-new" class="divide-y divide-gray-900/5">
                                <tr>
                                    <td colspan="4" class="px-8 py-20 text-center">
                                        <div class="flex flex-col items-center gap-4 text-gray-400">
                                            <svg class="w-12 h-12 opacity-20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                            <p class="font-black text-xs uppercase tracking-widest">Awaiting Active Packets...</p>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Utility Array (Tools) -->
            <div x-show="activeTab === 'tools'" x-cloak x-transition:enter="duration-500 ease-out" class="space-y-12">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                    <!-- Protocol Handshake -->
                    <div class="glass-panel border border-white/60 p-10 rounded-[3rem] shadow-xl space-y-8 group hover:scale-[1.01] transition-all">
                        <div class="flex items-center gap-5">
                            <div class="w-14 h-14 bg-emerald-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-emerald-100">
                                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            </div>
                            <div>
                                <h4 class="text-xl font-black text-gray-900 tracking-tight uppercase">Connection Pulse</h4>
                                <p class="text-[10px] font-black text-emerald-600 uppercase tracking-widest mt-1">WireGuard API Verification</p>
                            </div>
                        </div>
                        <p class="text-sm text-gray-500 leading-relaxed">Execute a diagnostic handshake between MadaaQ Central and the RouterOS API layer to verify encrypted tunneling stability.</p>
                        <button onclick="testConnection()" class="w-full py-5 bg-gray-900 text-white rounded-2xl font-black text-[11px] uppercase tracking-[0.2em] shadow-xl hover:bg-emerald-600 transition-all active:scale-95">
                            Verify Infrastructure
                        </button>
                        <div id="connection-status-new" class="hidden animate-in zoom-in-95 duration-300 text-center p-5 rounded-2xl bg-slate-50 border border-gray-100 font-black text-xs uppercase tracking-widest"></div>
                    </div>

                    <!-- Database Mirroring -->
                    <div class="bg-gray-900 p-10 rounded-[3rem] shadow-2xl relative overflow-hidden space-y-8 group">
                        <div class="absolute -top-12 -right-12 w-48 h-48 bg-white/5 rounded-full blur-3xl"></div>
                        <div class="flex items-center gap-5 relative z-10">
                            <div class="w-14 h-14 bg-white/10 text-white rounded-2xl flex items-center justify-center text-3xl border border-white/10">📥</div>
                            <div>
                                <h4 class="text-xl font-black text-white tracking-tight uppercase">Registry Assimilation</h4>
                                <p class="text-[10px] font-black text-indigo-400 uppercase tracking-widest mt-1">Local Resource Import</p>
                            </div>
                        </div>
                        <p class="text-sm text-gray-400 leading-relaxed relative z-10">Synchronize existing RouterOS configuration profiles into the MadaaQ distributed database for administrative oversight.</p>
                        <div class="grid grid-cols-1 gap-4 relative z-10">
                            <button onclick="importData('pppoe')" class="flex items-center justify-between px-6 py-4 bg-white/5 hover:bg-white/10 border border-white/10 rounded-2xl text-white font-black text-[10px] uppercase tracking-widest transition-all">
                                <span>Migrate PPPoE Matrix</span>
                                <span class="bg-white/10 p-2 rounded-lg">📡</span>
                            </button>
                            <button onclick="importData('hotspot')" class="flex items-center justify-between px-6 py-4 bg-white/5 hover:bg-white/10 border border-white/10 rounded-2xl text-white font-black text-[10px] uppercase tracking-widest transition-all">
                                <span>Mirror Hotspot Stack</span>
                                <span class="bg-white/10 p-2 rounded-lg">📶</span>
                            </button>
                            <button onclick="importData('pppoe-profiles')" class="flex items-center justify-between px-6 py-4 bg-white/5 hover:bg-white/10 border border-white/10 rounded-2xl text-white font-black text-[10px] uppercase tracking-widest transition-all">
                                <span>Sync Policy Profiles</span>
                                <span class="bg-white/10 p-2 rounded-lg">📋</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Terminal Protocol (Config) -->
            <div x-show="activeTab === 'config'" x-cloak x-transition:enter="duration-500 ease-out" class="space-y-10">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-amber-600/10 rounded-2xl flex items-center justify-center text-amber-600">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <h4 class="text-xl font-black text-gray-900 tracking-tight uppercase">Initialization Terminal</h4>
                    </div>
                    <button @click="copyScript()" class="px-10 py-4 bg-indigo-600 text-white rounded-2xl font-black text-[11px] uppercase tracking-[0.2em] shadow-xl shadow-indigo-100 hover:scale-105 transition-all flex items-center gap-3">
                        <span x-show="!scriptCopied" class="flex items-center gap-3">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                            Copy Command Stack
                        </span>
                        <span x-show="scriptCopied" class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            Stack Copied
                        </span>
                    </button>
                </div>

                <div class="rounded-[2.5rem] border border-gray-900/10 bg-gray-950 p-1 shadow-2xl relative group overflow-hidden" dir="ltr">
                    <div class="bg-gray-900/50 backdrop-blur-md px-8 py-4 flex items-center justify-between border-b border-white/5">
                        <div class="flex gap-2.5">
                            <div class="w-3 h-3 rounded-full bg-rose-500/80 shadow-sm"></div>
                            <div class="w-3 h-3 rounded-full bg-amber-500/80 shadow-sm"></div>
                            <div class="w-3 h-3 rounded-full bg-emerald-500/80 shadow-sm"></div>
                        </div>
                        <span class="text-[9px] font-black text-gray-500 uppercase tracking-[0.3em]">Infrastructure Provisioning</span>
                    </div>
                    <div class="p-10 max-h-[500px] overflow-y-auto custom-scrollbar-dark scroll-smooth">
                        <pre class="text-sm font-mono text-indigo-400 leading-relaxed whitespace-pre-wrap"><code id="setup-code-new" class="block">Generating optimized protocol script...</code></pre>
                    </div>
                </div>

                <div class="bg-indigo-600 p-10 rounded-[3rem] shadow-2xl relative overflow-hidden group">
                    <div class="absolute -top-12 -right-12 w-48 h-48 bg-white/10 rounded-full blur-3xl"></div>
                    <div class="flex items-start gap-6 relative z-10">
                        <div class="w-14 h-14 bg-white/20 text-white rounded-2xl flex items-center justify-center text-3xl border border-white/10 shrink-0">💡</div>
                        <div class="space-y-4">
                            <h5 class="text-xl font-black text-white uppercase tracking-tight">Provisioning Protocol</h5>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-indigo-100 text-sm">
                                <div class="space-y-3">
                                    <div class="flex items-center gap-3">
                                        <span class="w-6 h-6 bg-white/10 rounded-lg flex items-center justify-center text-[10px] font-black">01</span>
                                        <p>Capture the command stack using the <b>Copy</b> button.</p>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="w-6 h-6 bg-white/10 rounded-lg flex items-center justify-center text-[10px] font-black">02</span>
                                        <p>Initialize a secure session via <b>Winbox</b>.</p>
                                    </div>
                                </div>
                                <div class="space-y-3">
                                    <div class="flex items-center gap-3">
                                        <span class="w-6 h-6 bg-white/10 rounded-lg flex items-center justify-center text-[10px] font-black">03</span>
                                        <p>Inject commands into the <b>New Terminal</b> interface.</p>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="w-6 h-6 bg-white/10 rounded-lg flex items-center justify-center text-[10px] font-black">04</span>
                                        <p>Allow <b>60 Seconds</b> for secure tunnel registration.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Version Stability (Backups) -->
            <div x-show="activeTab === 'backups'" x-cloak x-transition:enter="duration-500 ease-out" class="space-y-10">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-purple-600/10 rounded-2xl flex items-center justify-center text-purple-600">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h4 class="text-xl font-black text-gray-900 tracking-tight uppercase">Infrastructure Recovery Log</h4>
                </div>
                
                @if($server->backups->count() > 0)
                    <div class="glass-panel border border-white/60 rounded-[2.5rem] overflow-hidden shadow-xl">
                        <table class="w-full text-right border-collapse">
                            <thead>
                                <tr class="bg-gray-900/5 border-b border-gray-900/5">
                                    <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Snapshot Identity</th>
                                    <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Protocol Type</th>
                                    <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Timestamp</th>
                                    <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Payload Size</th>
                                    <th class="px-8 py-5 text-center text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Deployment</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-900/5">
                                @foreach($server->backups()->latest()->take(10)->get() as $backup)
                                <tr class="hover:bg-gray-900/5 transition-all">
                                    <td class="px-8 py-6 font-black text-gray-900">{{ $backup->filename }}</td>
                                    <td class="px-8 py-6">
                                        @if($backup->type == 'backup')
                                            <span class="px-4 py-1.5 bg-blue-600/5 text-blue-700 rounded-xl border border-blue-600/10 text-[9px] font-black tracking-widest uppercase">Binary Stack</span>
                                        @else
                                            <span class="px-4 py-1.5 bg-purple-600/5 text-purple-700 rounded-xl border border-purple-600/10 text-[9px] font-black tracking-widest uppercase">Script Export</span>
                                        @endif
                                    </td>
                                    <td class="px-8 py-6 text-gray-500 font-mono text-[10px] tracking-widest" dir="ltr">{{ $backup->created_at->format('Y-m-d H:i') }}</td>
                                    <td class="px-8 py-6 text-indigo-600 font-black text-xs" dir="ltr">{{ round($backup->size / 1024 / 1024, 2) }} MB</td>
                                    <td class="px-8 py-6 text-center">
                                        <a href="{{ route('servers.backups.download', $backup) }}" class="inline-flex items-center gap-3 px-6 py-2.5 bg-white text-gray-900 border border-gray-100 rounded-xl font-black text-[10px] uppercase shadow-sm hover:bg-gray-900 hover:text-white transition-all">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                            Retrieve
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="glass-panel border-2 border-dashed border-gray-200 py-32 rounded-[3.5rem] flex flex-col items-center justify-center text-gray-400 gap-6">
                        <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center text-5xl grayscale opacity-40">💾</div>
                        <div class="text-center">
                            <h5 class="text-xl font-black text-gray-900 tracking-tight uppercase mb-2">No Snapshots Detected</h5>
                            <p class="text-xs font-bold uppercase tracking-widest">Automated nightly backups are initialized.</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
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

```

