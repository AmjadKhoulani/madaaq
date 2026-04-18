@extends('layouts.admin')

@section('title', 'سكريبت المزامنة التلقائية | Auto-Sync Protocol')

@section('content')
<div class="max-w-5xl mx-auto space-y-8 pb-20">
    
    <!-- Strategic Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h2 class="text-3xl font-black text-primary tracking-tight italic uppercase">بروتوكول الربط الذاتي</h2>
            <p class="text-slate-500 font-medium mt-1 uppercase tracking-widest text-[10px] font-headline">Autonomous Synchronization Layer for Asset: <span class="text-secondary">{{ $router->name }}</span></p>
        </div>
        <a href="{{ route('router-management.index') }}" class="px-6 py-2 border border-outline-variant/20 rounded text-slate-500 font-bold text-xs uppercase tracking-widest hover:bg-slate-50 transition-all flex items-center gap-2">
            <span class="material-symbols-outlined text-sm text-slate-400">arrow_back</span>
            العودة للقائمة
        </a>
    </div>

    <!-- Status Shield -->
    <div class="bg-primary p-8 rounded-lg shadow-xl shadow-primary/10 relative overflow-hidden group">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full blur-3xl -mr-32 -mt-32"></div>
        <div class="relative z-10 flex items-center gap-6">
            <div class="w-16 h-16 bg-white/10 rounded border border-white/20 flex items-center justify-center text-secondary shadow-inner">
                <span class="material-symbols-outlined text-3xl">verified_user</span>
            </div>
            <div>
                <h3 class="text-xl font-black text-white uppercase italic tracking-widest italic">البروتوكول جاهز للتنفيذ</h3>
                <p class="text-[10px] font-bold text-white/60 uppercase tracking-widest mt-1 italic">Ready for terminal injection into the MikroTik RouterOS stack</p>
            </div>
        </div>
    </div>

    <!-- Terminal Environment -->
    <div class="bg-slate-900 rounded-lg shadow-2xl overflow-hidden border border-white/5">
        <div class="bg-slate-800 px-6 py-3 border-b border-white/5 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="flex gap-1.5">
                    <div class="w-2.5 h-2.5 bg-[#ff5f56] rounded-full shadow-sm"></div>
                    <div class="w-2.5 h-2.5 bg-[#ffbd2e] rounded-full shadow-sm"></div>
                    <div class="w-2.5 h-2.5 bg-[#27c93f] rounded-full shadow-sm"></div>
                </div>
                <span class="font-manrope text-[10px] font-black text-slate-400 uppercase tracking-widest">mikrotik-provisioning.rsc</span>
            </div>
            <button onclick="copyScript()" class="px-6 py-1.5 bg-primary text-white text-[10px] font-black uppercase tracking-widest rounded shadow-xl shadow-primary/20 hover:scale-[1.05] active:scale-[0.98] transition-all italic">
                📋 نسخ البروتوكول
            </button>
        </div>
        <div class="p-8 overflow-x-auto custom-scrollbar">
            <pre id="script-content" class="text-xs font-manrope font-black text-emerald-400/90 leading-relaxed whitespace-pre" dir="ltr">{{ $script }}</pre>
        </div>
    </div>

    <!-- Intelligence Matrix -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Execution Blueprint -->
        <div class="bg-surface-container-low border border-outline-variant/10 rounded-lg p-8 space-y-6">
            <div class="flex items-center gap-4">
                <span class="material-symbols-outlined text-primary">analytics</span>
                <h3 class="text-sm font-black text-primary uppercase tracking-widest italic">خريطة تنفيذ البروتوكول</h3>
            </div>
            <div class="space-y-4">
                <div class="flex gap-4 group">
                    <span class="w-7 h-7 bg-primary rounded flex items-center justify-center text-[10px] font-black text-white shadow-lg shrink-0">01</span>
                    <p class="text-[10px] font-bold text-slate-500 uppercase leading-snug tracking-tight italic pt-1">Execute the "Copy" command to cache the protocol logic.</p>
                </div>
                <div class="flex gap-4 group">
                    <span class="w-7 h-7 bg-primary rounded flex items-center justify-center text-[10px] font-black text-white shadow-lg shrink-0">02</span>
                    <p class="text-[10px] font-bold text-slate-500 uppercase leading-snug tracking-tight italic pt-1">Access the WinBox / SSH terminal of the target hardware asset.</p>
                </div>
                <div class="flex gap-4 group">
                    <span class="w-7 h-7 bg-primary rounded flex items-center justify-center text-[10px] font-black text-white shadow-lg shrink-0">03</span>
                    <p class="text-[10px] font-bold text-slate-500 uppercase leading-snug tracking-tight italic pt-1">Inject the protocol into the "New Terminal" environment.</p>
                </div>
                <div class="flex gap-4 group">
                    <span class="w-7 h-7 bg-secondary rounded flex items-center justify-center text-[10px] font-black text-white shadow-lg shrink-0">✓</span>
                    <p class="text-[10px] font-black text-secondary uppercase leading-snug tracking-tight italic pt-1">The asset will initialize live synchronization every 15 seconds.</p>
                </div>
            </div>
        </div>

        <!-- Logic Breakdown -->
        <div class="bg-surface-container-low border border-outline-variant/10 rounded-lg p-8 space-y-6">
            <div class="flex items-center gap-4">
                <span class="material-symbols-outlined text-secondary">terminal</span>
                <h3 class="text-sm font-black text-secondary uppercase tracking-widest italic">مواصفات طبقة الربط</h3>
            </div>
            <div class="space-y-3">
                <div class="flex items-center gap-3 p-3 bg-white border border-outline-variant/10 rounded hover:shadow-sm transition-all">
                    <span class="material-symbols-outlined text-primary text-lg">vpn_lock</span>
                    <span class="text-[9px] font-black text-slate-600 uppercase tracking-widest">WireGuard VPN Layer</span>
                </div>
                <div class="flex items-center gap-3 p-3 bg-white border border-outline-variant/10 rounded hover:shadow-sm transition-all">
                    <span class="material-symbols-outlined text-primary text-lg">api</span>
                    <span class="text-[9px] font-black text-slate-600 uppercase tracking-widest">RouterOS REST API activation</span>
                </div>
                <div class="flex items-center gap-3 p-3 bg-white border border-outline-variant/10 rounded hover:shadow-sm transition-all">
                    <span class="material-symbols-outlined text-primary text-lg">admin_panel_settings</span>
                    <span class="text-[9px] font-black text-slate-600 uppercase tracking-widest">Restricted Management Identity</span>
                </div>
                <div class="flex items-center gap-3 p-3 bg-white border border-outline-variant/10 rounded hover:shadow-sm transition-all">
                    <span class="material-symbols-outlined text-primary text-lg">sync_alt</span>
                    <span class="text-[9px] font-black text-slate-600 uppercase tracking-widest">Autonomous Sync Scheduling</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Download Block -->
    <div class="bg-surface-container-low border border-outline-variant/10 rounded-lg p-8 flex flex-col md:flex-row items-center justify-between gap-6 shadow-sm">
        <div class="flex items-center gap-6">
            <div class="w-12 h-12 bg-slate-900 text-white rounded flex items-center justify-center shadow-lg italic font-black text-xl">F</div>
            <div>
                <h5 class="text-sm font-black text-primary uppercase italic">Physical Distribution</h5>
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Download the .RSC script for manual FTP deployment</p>
            </div>
        </div>
        <button onclick="downloadScript()" class="px-10 py-3.5 bg-slate-900 text-white font-black text-[10px] uppercase tracking-[0.2em] rounded shadow-xl hover:bg-black transition-all italic flex items-center gap-2">
            <span class="material-symbols-outlined text-sm">download</span>
            تنزيل الكود (.rsc)
        </button>
    </div>
</div>

<script>
function copyScript() {
    const scriptContent = document.getElementById('script-content').textContent;
    navigator.clipboard.writeText(scriptContent).then(() => {
        alert('Protocol Copied Successfully');
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
