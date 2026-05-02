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
