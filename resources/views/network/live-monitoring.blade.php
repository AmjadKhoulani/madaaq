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
