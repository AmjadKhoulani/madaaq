<script setup>
import { ref } from 'vue';
import { Link, Head, useForm } from '@inertiajs/vue3';
import InstitutionalLayout from '@/Layouts/InstitutionalLayout.vue';

const props = defineProps({
    client: Object,
    stats: Object,
    usage: Object,
    activeIp: String,
});

const noteForm = useForm({
    content: '',
    type: 'general',
});

const submitNote = () => {
    noteForm.post(route('crm.clients.notes.store', props.client.id), {
        onSuccess: () => noteForm.reset('content'),
        preserveScroll: true,
    });
};

const formatDate = (date) => {
    if (!date) return 'وصول سيادي لا نهائي (Perpetual)';
    return new Date(date).toLocaleDateString('ar-SY', { year: 'numeric', month: 'long', day: 'numeric' });
};

const formatBytes = (bytes) => {
    if (!bytes || bytes === 0) return '0.000 GB';
    const gb = bytes / (1024 * 1024 * 1024);
    return gb.toFixed(3) + ' GB';
};
</script>

<template>
    <InstitutionalLayout :title="'هوية المشترك: ' + client.username">
        <Head :title="'الملف الاستخباري للمشترك: ' + client.username + ' - MadaaQ'" />

        <div class="max-w-7xl mx-auto pb-24 text-right px-4" dir="rtl">
            <!-- Institutional Profile Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-12 mb-16 flex-row-reverse text-right">
                <div class="flex items-center gap-10 flex-row-reverse">
                    <div class="text-right">
                        <div class="flex items-center gap-4 justify-end mb-2">
                            <span class="text-[11px] font-black uppercase tracking-[0.4em] text-slate-400 italic">Civilian Identity Verified</span>
                            <span class="flex h-3 w-3 relative">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-secondary opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-secondary"></span>
                            </span>
                        </div>
                        <h1 class="text-6xl font-black text-primary tracking-tighter leading-none mb-6 uppercase font-headline">{{ client.username }}</h1>
                        <div class="flex items-center gap-4 justify-end">
                            <div class="flex items-center gap-3 px-5 py-2 bg-slate-950 text-white rounded-xl text-[10px] font-black uppercase tracking-[0.2em] shadow-2xl border border-white/10">
                                <span class="material-symbols-outlined text-[18px]">dns</span>
                                {{ client.mikrotik_server?.name || 'ROOT_SERVER' }}
                            </div>
                            <div class="flex items-center gap-3 px-5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] shadow-inner">
                                <span class="material-symbols-outlined text-[18px]">fingerprint</span>
                                CID: {{ client.id }}
                            </div>
                        </div>
                    </div>
                    <div class="w-28 h-28 rounded-[2rem] bg-slate-950 text-white flex items-center justify-center border-4 border-white shadow-[0_20px_60px_rgba(2,6,23,0.15)] shrink-0 group hover:rotate-6 transition-all duration-700 relative overflow-hidden">
                         <div class="absolute inset-x-0 bottom-0 h-1/2 bg-gradient-to-t from-black/40 to-transparent"></div>
                         <span class="material-symbols-outlined text-[56px] relative z-10" style="font-variation-settings: 'FILL' 1; font-weight: 200;">account_circle</span>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-5 justify-end">
                    <Link 
                        :href="route('crm.clients.renew', client.id)" 
                        class="px-12 py-6 bg-primary text-white rounded-2xl font-black text-[11px] uppercase tracking-[0.3em] shadow-2xl hover:bg-emerald-600 hover:-translate-y-1 transition-all active:scale-95 flex items-center gap-4 border border-white/10"
                    >
                        <span class="material-symbols-outlined text-[24px]">sync_alt</span>
                        تجديد العقد (Inject Credit)
                    </Link>

                    <Link 
                        :href="route('crm.clients.toggle-status', client.id)" 
                        method="patch" 
                        as="button"
                        class="px-8 py-6 rounded-2xl font-black text-[11px] uppercase tracking-[0.3em] transition-all active:scale-95 border-2 flex items-center gap-4 shadow-xl"
                        :class="client.status === 'active' 
                            ? 'bg-rose-50 text-rose-600 border-rose-100 hover:bg-rose-500 hover:text-white hover:border-rose-500' 
                            : 'bg-emerald-50 text-emerald-600 border-emerald-100 hover:bg-emerald-500 hover:text-white hover:border-emerald-500'"
                    >
                        <span class="material-symbols-outlined text-[24px]">{{ client.status === 'active' ? 'lock' : 'verified_user' }}</span>
                        {{ client.status === 'active' ? 'تجميد السيادة' : 'تنشيط النبض' }}
                    </Link>

                    <Link 
                        :href="route('crm.clients.edit', client.id)" 
                        class="w-18 h-18 bg-white shadow-2xl border border-outline-variant/10 rounded-2xl flex items-center justify-center text-slate-400 hover:text-primary transition-all hover:scale-110 active:scale-90"
                    >
                        <span class="material-symbols-outlined text-[32px]">design_services</span>
                    </Link>
                </div>
            </div>

            <!-- Strategic Status Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-16">
                <!-- Fiscal Documents -->
                <div class="surface-card p-10 rounded-[2.5rem] border border-outline-variant/10 shadow-2xl bg-white group relative overflow-hidden">
                    <div class="relative z-10 flex flex-col gap-8">
                        <div class="flex items-center justify-between flex-row-reverse">
                             <div class="w-16 h-16 rounded-2xl bg-primary text-white flex items-center justify-center shadow-2xl group-hover:rotate-12 transition-transform">
                                <span class="material-symbols-outlined text-[36px]" style="font-variation-settings: 'FILL' 1">receipt_long</span>
                             </div>
                             <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] font-headline italic">INVOICE_POOL</p>
                        </div>
                        <div class="flex flex-col items-start gap-1">
                            <h3 class="text-5xl font-black font-headline text-primary tracking-tighter leading-none">{{ (stats.total_invoices || 0).toLocaleString() }}</h3>
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.4em] mt-3">إجمالي السندات المالية الصادرة</p>
                        </div>
                    </div>
                </div>

                <!-- Total Settled -->
                <div class="surface-card p-10 rounded-[2.5rem] border border-outline-variant/10 shadow-2xl bg-white group overflow-hidden relative">
                    <div class="relative z-10 flex flex-col gap-8">
                        <div class="flex items-center justify-between flex-row-reverse">
                             <div class="w-16 h-16 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-100 shadow-inner group-hover:scale-110 transition-transform">
                                <span class="material-symbols-outlined text-[36px]" style="font-variation-settings: 'FILL' 1">payments</span>
                             </div>
                             <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] font-headline italic">SETTLED_YTD</p>
                        </div>
                        <div class="flex flex-col items-start gap-1">
                            <div class="flex items-baseline gap-4">
                                <h3 class="text-5xl font-black font-headline text-emerald-600 tracking-tighter leading-none">{{ (stats.total_paid || 0).toLocaleString() }}</h3>
                                <span class="text-[11px] font-black text-slate-300 uppercase tracking-[0.2em] italic">SYP</span>
                            </div>
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.4em] mt-3 italic">القيمة المحصلة تراكمياً</p>
                        </div>
                    </div>
                </div>

                <!-- Outstanding Debt -->
                <div class="surface-card p-10 rounded-[2.5rem] border border-outline-variant/10 shadow-2xl bg-white group overflow-hidden relative border-b-8 border-rose-500">
                    <div class="relative z-10 flex flex-col gap-8">
                        <div class="flex items-center justify-between flex-row-reverse">
                             <div class="w-16 h-16 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center border border-rose-100 shadow-inner group-hover:animate-pulse">
                                <span class="material-symbols-outlined text-[36px]" style="font-variation-settings: 'FILL' 1">account_balance_wallet</span>
                             </div>
                             <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] font-headline italic text-rose-500">LIQUIDITY_GAP</p>
                        </div>
                        <div class="flex flex-col items-start gap-1">
                            <div class="flex items-baseline gap-4">
                                <h3 class="text-5xl font-black font-headline text-rose-600 tracking-tighter leading-none">{{ (stats.pending_amount || 0).toLocaleString() }}</h3>
                                <span class="text-[11px] font-black text-rose-200 uppercase tracking-[0.2em] italic">SYP</span>
                            </div>
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.4em] mt-3 italic">الذمة المالية المعلقة (Debt)</p>
                        </div>
                    </div>
                </div>

                <!-- Expiry Protocol -->
                <div class="surface-card p-10 rounded-[2.5rem] bg-slate-950 text-white shadow-2xl shadow-primary/20 relative overflow-hidden group">
                    <div class="absolute -top-10 -left-10 w-40 h-40 bg-white/10 rounded-full blur-3xl group-hover:scale-150 transition-all duration-1000"></div>
                    <div class="relative z-10 flex flex-col gap-8">
                        <div class="flex items-center justify-between flex-row-reverse">
                             <div class="w-16 h-16 rounded-2xl bg-white/10 flex items-center justify-center border border-white/20 shadow-2xl group-hover:rotate-[-12deg] transition-transform">
                                <span class="material-symbols-outlined text-primary text-[36px]" style="font-variation-settings: 'FILL' 1">auto_schedule</span>
                             </div>
                             <p class="text-[10px] font-black text-white/30 uppercase tracking-[0.4em] font-headline italic">EXPIRY_SYNC</p>
                        </div>
                        <div class="flex flex-col items-start gap-2">
                            <h3 class="text-2xl font-black font-headline text-white tracking-tight leading-tight uppercase">{{ formatDate(client.expires_at) }}</h3>
                            <p class="text-[9px] font-black text-white/20 uppercase tracking-[0.4em] mt-3">نهاية النافذة التشغيلية</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                <!-- Master Intelligence Column -->
                <div class="lg:col-span-2 space-y-12">
                    <!-- Identity DNA Profile -->
                    <div class="surface-card p-12 rounded-[3rem] shadow-2xl border border-outline-variant/10 bg-white relative overflow-hidden">
                        <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-l from-primary via-secondary to-primary opacity-20"></div>
                        <h3 class="text-[11px] font-black text-primary uppercase tracking-[0.5em] mb-12 flex items-center justify-end gap-5 italic text-right">
                            جوهر الهوية والربط السيادي
                            <span class="w-8 h-1 bg-primary rounded-full"></span>
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                            <div class="p-10 bg-slate-50 rounded-2xl border border-slate-200 text-right group hover:bg-slate-950 hover:text-white transition-all duration-500 shadow-inner">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-3 font-headline group-hover:text-primary transition-colors">IDENTITY_AUTH</p>
                                <p class="text-3xl font-black font-headline tracking-tighter leading-none group-hover:translate-x-3 transition-transform uppercase">{{ client.username }}</p>
                            </div>
                            <div class="p-10 bg-slate-50 rounded-2xl border border-slate-200 text-right group hover:bg-slate-950 hover:text-white transition-all duration-500 shadow-inner">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-3 font-headline group-hover:text-secondary transition-colors">SERVICE_PROTOCOL</p>
                                <div class="flex items-center gap-4 justify-end mt-1">
                                    <span class="px-5 py-1.5 bg-primary text-white rounded-xl text-[10px] font-black uppercase tracking-[0.2em] shadow-2xl">{{ client.type }}</span>
                                    <p class="text-2xl font-black uppercase text-slate-500 group-hover:text-white transition-colors leading-none italic">{{ client.type === 'pppoe' ? 'Tunnel' : 'Voucher' }}</p>
                                </div>
                            </div>
                            <div class="p-10 bg-slate-50 rounded-2xl border border-slate-200 text-right group hover:bg-slate-950 hover:text-white transition-all duration-500 shadow-inner">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-3 font-headline">QoS_PROFILE</p>
                                <p class="text-2xl font-black text-primary group-hover:text-white transition-colors leading-none uppercase">{{ client.package?.name || 'CUSTOM_POLICY' }}</p>
                            </div>
                            <div class="p-10 bg-slate-50 rounded-2xl border border-slate-200 text-right group hover:bg-slate-950 hover:text-white transition-all duration-500 shadow-inner">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-3 font-headline">CONTACT_GATEWAY</p>
                                <p class="text-2xl font-black font-headline tracking-widest text-primary group-hover:text-white transition-colors leading-none">{{ client.phone || 'NO_RECORD' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Live Pulse Matrix (Telemetry) -->
                    <div class="surface-card p-12 rounded-[3.5rem] bg-slate-950 border-4 border-primary/20 text-white relative overflow-hidden group">
                         <div class="absolute inset-0 bg-grid-slate-50 [mask-image:radial-gradient(ellipse_at_center,black,transparent)] opacity-10 pointer-events-none"></div>
                         <div class="flex items-center justify-between mb-16 flex-row-reverse relative z-10">
                            <h3 class="text-[11px] font-black text-white/40 uppercase tracking-[0.5em] flex items-center justify-end gap-5 italic text-right">
                                مصفوفة النبض الحي (Live Telemetry)
                                <span class="w-12 h-1 bg-secondary rounded-full shadow-[0_0_15px_rgba(244,63,94,0.5)]"></span>
                            </h3>
                            <div class="flex items-center gap-5 px-6 py-3 rounded-2xl bg-white/5 border border-white/10 shadow-2xl backdrop-blur-xl">
                                 <span class="relative flex h-3.5 w-3.5">
                                     <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-secondary opacity-75"></span>
                                     <span class="relative inline-flex rounded-full h-3.5 w-3.5" :class="usage?.status === 'online' ? 'bg-secondary' : 'bg-rose-500 opacity-50'"></span>
                                 </span>
                                 <span class="text-[11px] font-black uppercase tracking-[0.3em] text-white italic">{{ usage?.status === 'online' ? 'Tunnel: Established' : 'Tunnel: Null' }}</span>
                            </div>
                         </div>
                         
                         <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 flex-row-reverse relative z-10">
                             <div class="bg-white/5 p-8 rounded-[2rem] border border-white/10 group/item hover:bg-white/10 hover:scale-105 transition-all duration-500 text-right shadow-2xl">
                                 <p class="text-[9px] font-black text-white/30 uppercase tracking-[0.3em] mb-4 font-headline">DHCP_LEASE</p>
                                 <p class="text-2xl font-black font-headline text-white tracking-widest leading-none">{{ activeIp || 'Detached' }}</p>
                             </div>
                             <div class="bg-white/5 p-8 rounded-[2rem] border border-white/10 group/item hover:bg-white/10 hover:scale-105 transition-all duration-500 text-right shadow-2xl">
                                 <p class="text-[9px] font-black text-white/30 uppercase tracking-[0.3em] mb-4 font-headline">SESSION_UPTIME</p>
                                 <p class="text-2xl font-black font-headline text-secondary tracking-widest leading-none uppercase">{{ usage?.uptime || '00:00:00' }}</p>
                             </div>
                             <div class="bg-white/5 p-8 rounded-[2rem] border border-white/10 group/item hover:bg-white/10 hover:scale-105 transition-all duration-500 text-right shadow-2xl border-b-4 border-emerald-500">
                                 <p class="text-[9px] font-black text-white/30 uppercase tracking-[0.3em] mb-4 font-headline">INGRESS_FLOOD</p>
                                 <div class="flex items-baseline gap-2 justify-end">
                                     <p class="text-3xl font-black font-headline text-emerald-400 tracking-tighter leading-none">{{ usage?.download_speed || 0 }}</p>
                                     <span class="text-[10px] font-black text-white/20 uppercase">MBPS</span>
                                 </div>
                             </div>
                             <div class="bg-white/5 p-8 rounded-[2rem] border border-white/10 group/item hover:bg-white/10 hover:scale-105 transition-all duration-500 text-right shadow-2xl border-b-4 border-indigo-500">
                                 <p class="text-[9px] font-black text-white/30 uppercase tracking-[0.3em] mb-4 font-headline">EGRESS_SYNC</p>
                                 <div class="flex items-baseline gap-2 justify-end">
                                     <p class="text-3xl font-black font-headline text-indigo-400 tracking-tighter leading-none">{{ usage?.upload_speed || 0 }}</p>
                                     <span class="text-[10px] font-black text-white/20 uppercase">MBPS</span>
                                 </div>
                             </div>
                         </div>

                         <div v-if="usage?.status === 'online'" class="mt-16 pt-10 border-t border-white/10 flex flex-col md:flex-row justify-between items-center gap-10 flex-row-reverse relative z-10">
                             <div class="text-right">
                                 <p class="text-[10px] font-black uppercase tracking-[0.4em] text-white/30 mb-4 italic">تراكم البيانات (Registry Session Flow)</p>
                                 <div class="flex items-center gap-8 justify-end">
                                     <div class="flex flex-col items-end gap-1">
                                         <span class="text-[12px] font-black text-emerald-400 font-headline tracking-widest leading-none">{{ formatBytes(usage?.bytes_out) }}</span>
                                         <span class="text-[8px] text-white/20 uppercase font-black tracking-widest leading-none">Aggr_In</span>
                                     </div>
                                     <div class="w-px h-10 bg-white/10 mx-2"></div>
                                     <div class="flex flex-col items-end gap-1">
                                         <span class="text-[12px] font-black text-indigo-400 font-headline tracking-widest leading-none">{{ formatBytes(usage?.bytes_in) }}</span>
                                         <span class="text-[8px] text-white/20 uppercase font-black tracking-widest leading-none">Aggr_Out</span>
                                     </div>
                                 </div>
                             </div>
                             <a v-if="client.type === 'pppoe' && activeIp" :href="route('crm.clients.cpe-proxy', { client: client.id, type: 'pppoe' })" target="_blank" class="px-12 py-6 bg-white text-slate-950 text-[11px] font-black uppercase tracking-[0.4em] rounded-2xl transition-all flex items-center gap-5 hover:bg-primary hover:text-white hover:scale-105 active:scale-95 shadow-[0_20px_50px_rgba(0,0,0,0.5)] border border-white/10 group/con">
                                <span class="material-symbols-outlined text-[28px] group-hover/con:rotate-12 transition-transform">terminal</span>
                                حقن بروتوكول المصادقة (Console)
                             </a>
                         </div>
                    </div>

                    <!-- Gateway Topology Nodes -->
                    <div class="surface-card p-12 rounded-[3.5rem] shadow-2xl border border-outline-variant/10 bg-white overflow-hidden relative">
                        <div class="absolute inset-x-0 bottom-0 h-1 bg-gradient-to-r from-primary via-secondary to-primary opacity-20"></div>
                        <h3 class="text-[11px] font-black text-slate-400 uppercase tracking-[0.5em] mb-16 flex items-center justify-end gap-5 italic text-right">
                            طوبولوجيا العقد الميدانية (Field Nodes)
                            <span class="w-16 h-1 bg-slate-950 rounded-full"></span>
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 flex-row-reverse">
                            <!-- Internal Node -->
                            <div class="space-y-8 text-right">
                                <div class="flex items-center gap-4 justify-end text-[12px] font-black text-primary uppercase tracking-[0.3em] font-headline">
                                    بوابة العميل الداخلية (CPE)
                                    <div class="w-14 h-14 bg-slate-50 rounded-2xl flex items-center justify-center border border-slate-200 shadow-inner group-hover:rotate-12 transition-transform">
                                        <span class="material-symbols-outlined text-primary text-[28px]">router</span>
                                    </div>
                                </div>
                                <div class="p-10 bg-slate-950 text-white rounded-[2.5rem] border border-white/10 group hover:bg-primary transition-all duration-700 shadow-2xl relative overflow-hidden">
                                    <div class="absolute inset-0 bg-grid-slate-50 opacity-5 pointer-events-none"></div>
                                    <p class="text-[9px] font-black text-white/40 uppercase tracking-[0.4em] mb-3 italic">Internal_Legacy_IP</p>
                                    <p class="text-4xl font-black font-headline tracking-tighter group-hover:scale-105 transition-transform">{{ client.cpe_ip || 'NULL_NODE' }}</p>
                                    
                                    <div v-if="client.cpe_ip" class="mt-12 pt-8 border-t border-white/10 flex items-center justify-between flex-row-reverse relative z-10">
                                        <div class="text-right">
                                            <p class="text-[12px] font-black text-white tracking-widest">{{ client.cpe_username || 'ADMIN' }}</p>
                                            <p class="text-[8px] font-black text-white/30 tracking-[0.4em] mt-2 uppercase italic leading-none">Mikrotik Auth Shield</p>
                                        </div>
                                        <a :href="route('crm.clients.cpe-proxy', { client: client.id, type: 'cpe' })" target="_blank" class="px-6 py-3 bg-white/10 border border-white/20 text-white text-[10px] font-black uppercase tracking-[0.3em] rounded-xl hover:bg-white hover:text-slate-950 transition-all shadow-2xl">
                                             Webfig Access
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Edge Node -->
                            <div class="space-y-8 text-right">
                                <div class="flex items-center gap-4 justify-end text-[12px] font-black text-secondary uppercase tracking-[0.3em] font-headline">
                                    راديو الاستقبال الطرفي (RECEIVER)
                                    <div class="w-14 h-14 bg-slate-50 rounded-2xl flex items-center justify-center border border-slate-200 shadow-inner group-hover:rotate-12 transition-transform">
                                        <span class="material-symbols-outlined text-secondary text-[28px]">sensors</span>
                                    </div>
                                </div>
                                <div class="p-10 bg-slate-950 text-white rounded-[2.5rem] border border-white/10 group hover:bg-secondary transition-all duration-700 shadow-2xl relative overflow-hidden">
                                    <div class="absolute inset-0 bg-grid-slate-50 opacity-5 pointer-events-none"></div>
                                    <p class="text-[9px] font-black text-white/40 uppercase tracking-[0.4em] mb-3 italic">Edge_Radio_IP</p>
                                    <p class="text-4xl font-black font-headline tracking-tighter group-hover:scale-105 transition-transform">{{ client.receiver_ip || 'VOID_RECV' }}</p>

                                    <div v-if="client.receiver_ip" class="mt-12 pt-8 border-t border-white/10 flex items-center justify-between flex-row-reverse relative z-10">
                                        <div class="text-right">
                                            <p class="text-[12px] font-black text-white tracking-widest">{{ client.receiver_username || 'UBTI_USER' }}</p>
                                            <p class="text-[8px] font-black text-white/30 tracking-[0.4em] mt-2 uppercase italic leading-none">Radio Link Credentials</p>
                                        </div>
                                        <a :href="route('crm.clients.cpe-proxy', { client: client.id, type: 'receiver' })" target="_blank" class="px-6 py-3 bg-white/10 border border-white/20 text-white text-[10px] font-black uppercase tracking-[0.3em] rounded-xl hover:bg-white hover:text-slate-950 transition-all shadow-2xl">
                                             Panel Access
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Strategic Intelligence Sidebar -->
                <div class="space-y-12">
                    <!-- Event Horizon (Activity Log) -->
                    <div class="surface-card p-10 rounded-[3rem] border border-outline-variant/10 bg-white shadow-2xl relative overflow-hidden">
                        <div class="absolute inset-y-0 right-0 w-1 bg-primary opacity-20"></div>
                        <h3 class="text-[11px] font-black text-primary uppercase tracking-[0.5em] mb-12 flex items-center justify-end gap-4 italic text-right">
                            أفق الأحداث اللحظي (Event Horizon)
                            <span class="material-symbols-outlined text-primary text-[24px]">stream</span>
                        </h3>
                        <div class="space-y-12 relative before:absolute before:right-[7px] before:top-2 before:bottom-0 before:w-px before:bg-slate-100 pr-10">
                            <div v-for="activity in client.activities" :key="activity.id" class="relative group">
                                <div class="absolute -right-[37px] top-1.5 w-3 h-3 rounded-full border-2 border-white bg-primary shadow-2xl z-10 transition-all group-hover:scale-150 group-hover:shadow-primary/50"></div>
                                <p class="text-[14px] font-black text-slate-900 leading-tight mb-3 text-right group-hover:text-primary transition-colors">{{ activity.description }}</p>
                                <div class="flex items-center justify-end gap-3 text-[10px] font-black uppercase tracking-widest text-slate-400 font-headline italic">
                                    {{ activity.created_at_human || 'Active_Stream' }}
                                    <span class="material-symbols-outlined text-[16px]">schedule</span>
                                </div>
                            </div>

                             <div v-if="!client.activities?.length" class="text-center py-20 opacity-20 group/empty">
                                <span class="material-symbols-outlined text-slate-900 text-[80px] mb-6 group-hover/empty:scale-110 transition-transform" style="font-variation-settings: 'wght' 100">history_toggle_off</span>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.4em] leading-relaxed italic">نظام الأحداث خامل حالياً (NULL_HORIZON)</p>
                            </div>
                        </div>
                    </div>

                    <!-- Strategic Intelligence Logs (Notes) -->
                    <div class="surface-card p-10 rounded-[3rem] border-t-8 border-slate-950 shadow-2xl bg-white relative overflow-hidden">
                         <div class="absolute inset-0 bg-slate-50/50 pointer-events-none opacity-50"></div>
                        <h3 class="text-[11px] font-black text-primary uppercase tracking-[0.5em] mb-10 flex items-center justify-end gap-4 italic text-right relative z-10">
                            سجل التعليقات الاستخبارية
                            <span class="material-symbols-outlined text-slate-950 text-[24px]">edit_note</span>
                        </h3>
                        
                        <form @submit.prevent="submitNote" class="space-y-6 relative z-10">
                            <div class="relative group">
                                <select v-model="noteForm.type" class="form-input-monolith h-16 pr-14 pl-6 bg-slate-100 border-slate-200 rounded-2xl text-[10px] font-black uppercase tracking-[0.3em] appearance-none shadow-inner">
                                    <option value="general">بلاغ عام (GENERAL_LOG)</option>
                                    <option value="technical">إنذار فني (TECH_ALERT)</option>
                                    <option value="billing">مسار مالي (BILLING_SYNC)</option>
                                </select>
                                <span class="material-symbols-outlined absolute right-6 top-1/2 -translate-y-1/2 text-primary opacity-40 text-[24px] pointer-events-none">sell</span>
                                <span class="material-symbols-outlined absolute left-6 top-1/2 -translate-y-1/2 text-slate-300 pointer-events-none">expand_more</span>
                            </div>
                            <textarea v-model="noteForm.content" placeholder="حقن بيانات الملاحظة الجديدة في مصفوفة الهوية..." rows="4" class="form-input-monolith p-8 bg-slate-100 border-slate-200 rounded-2xl text-base font-black tracking-tight shadow-inner resize-none text-right focus:h-48"></textarea>
                            <button type="submit" :disabled="noteForm.processing || !noteForm.content" class="w-full h-18 bg-slate-950 text-white rounded-2xl font-black text-[11px] uppercase tracking-[0.4em] shadow-2xl hover:bg-primary transition-all active:scale-95 disabled:opacity-20 flex items-center justify-center gap-5 group">
                                <span class="material-symbols-outlined text-[28px] group-hover:rotate-12 transition-all">save_as</span>
                                حفظ الملاحظة (Sync)
                            </button>
                        </form>

                        <div class="mt-14 space-y-8 relative z-10">
                            <div v-for="note in client.client_notes" :key="note.id" class="p-10 bg-slate-50 rounded-[2rem] border border-slate-200 shadow-inner group hover:bg-slate-950 hover:text-white transition-all duration-700 relative overflow-hidden text-right">
                                <div class="absolute inset-y-0 right-0 w-2 bg-primary transform translate-x-full group-hover:translate-x-0 transition-transform duration-700"></div>
                                <p class="text-[15px] font-black leading-relaxed mb-6 group-hover:text-white transition-colors">{{ note.content }}</p>
                                <div class="flex items-center justify-between text-[10px] font-black uppercase tracking-[0.2em] border-t border-slate-200/50 pt-6 group-hover:border-white/10">
                                    <span class="font-headline font-black text-slate-400 opacity-60 group-hover:opacity-100 transition-opacity">{{ note.created_at_human || 'TIME_STAMP_VOID' }}</span>
                                    <span :class="note.type === 'technical' ? 'bg-indigo-500 text-white shadow-indigo-500/20' : (note.type === 'billing' ? 'bg-amber-500 text-white shadow-amber-500/20' : 'bg-slate-300 text-slate-700')" class="px-4 py-1.5 rounded-lg font-black uppercase tracking-widest text-[9px] shadow-2xl">
                                        {{ note.type }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </InstitutionalLayout>
</template>

<style scoped>
.font-headline { font-family: 'Manrope', sans-serif; }
.bg-grid-slate-50 {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32' width='32' height='32' fill='none' stroke='rgb(15 23 42 / 0.05)'%3E%3Cpath d='M0 .5H31.5V32'/%3E%3C/svg%3E");
}
.form-input-monolith {
    @apply w-full border-none focus:ring-8 focus:ring-primary/5 transition-all text-slate-900;
}
</style>
