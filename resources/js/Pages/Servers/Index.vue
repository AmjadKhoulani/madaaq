<script setup>
import { ref, computed } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import InstitutionalLayout from '@/Layouts/InstitutionalLayout.vue';
import axios from 'axios';

const props = defineProps({
    servers: Array,
});

const testingStatus = ref({});

const testConnection = async (serverId) => {
    testingStatus.value[serverId] = 'testing';
    
    try {
        const response = await axios.post(route('servers.test-connection', serverId));
        if (response.data.success) {
            testingStatus.value[serverId] = 'success';
        } else {
            testingStatus.value[serverId] = 'error';
        }
    } catch (error) {
        testingStatus.value[serverId] = 'error';
    } finally {
        setTimeout(() => {
            delete testingStatus.value[serverId];
        }, 5000);
    }
};

const deleteServer = (serverId) => {
    if (confirm('تأكيد إخراج وحدة المعالجة المركزية (Core) من الخدمة؟ سيؤدي هذا الإجراء إلى انقطاع التنسيق البرمجي الفوري مع كافة عقد الشبكة المرتبطة.')) {
        router.delete(route('servers.destroy', serverId), {
            preserveScroll: true,
        });
    }
};

const formatNumber = (num) => {
    return new Intl.NumberFormat('ar-SY').format(num);
};
</script>

<template>
    <InstitutionalLayout title="حوكمة العقد المركزية">
        <Head title="إدارة العقد السيادية (Core Nodes) - MadaaQ" />

        <div class="max-w-[1600px] mx-auto pb-24 text-right px-4" dir="rtl">
            <!-- Strategic Command Header -->
            <div class="relative mb-20 p-12 bg-slate-950 text-white rounded-[3rem] overflow-hidden shadow-2xl border border-white/5">
                <div class="absolute inset-0 bg-grid-slate-50 opacity-5 pointer-events-none"></div>
                <div class="absolute -top-32 -left-32 w-96 h-96 bg-primary/20 rounded-full blur-[100px] animate-pulse"></div>
                
                <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-12 flex-row-reverse text-right">
                    <div class="text-right">
                        <div class="flex items-center gap-4 justify-end mb-4">
                            <span class="px-4 py-1.5 bg-white/10 text-white/60 rounded-full text-[10px] font-black uppercase tracking-[0.4em] border border-white/5">Operational_Node_Array</span>
                            <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse shadow-[0_0_15px_rgba(16,185,129,0.5)]"></div>
                        </div>
                        <h1 class="text-5xl lg:text-6xl font-black tracking-tighter mb-4 leading-none">مصفوفة التحكم المركزية</h1>
                        <p class="text-white/40 font-bold text-base max-w-2xl ml-auto">
                            حوكمة العقد التشغيلية (MikroTik Cores)، إدارة بروتوكولات المزامنة القطبية، وتأمين المسارات السيادية لتدفق البيانات عبر الشبكة.
                        </p>
                    </div>
                    
                    <div class="flex flex-wrap items-center gap-6">
                        <div class="bg-white/5 border border-white/10 p-6 rounded-3xl flex items-center gap-8 flex-row-reverse shadow-inner">
                            <div class="text-right">
                                <p class="text-[10px] font-black text-white/30 uppercase tracking-[0.3em] mb-1">إجمالي العقد</p>
                                <p class="text-3xl font-headline font-black text-white leading-none">{{ formatNumber(servers.length) }}</p>
                            </div>
                            <div class="w-12 h-12 bg-primary/20 rounded-2xl flex items-center justify-center text-primary group-hover:scale-110 transition-transform">
                                <span class="material-symbols-outlined text-[28px]">hub</span>
                            </div>
                        </div>
                        
                        <Link 
                            :href="route('servers.create')" 
                            class="px-14 py-7 bg-white text-slate-950 rounded-3xl font-black text-xs uppercase tracking-[0.4em] shadow-[0_20px_60px_rgba(255,255,255,0.1)] hover:bg-emerald-500 hover:text-white transition-all transform hover:-translate-y-2 active:scale-95 flex items-center gap-6 group"
                        >
                            <span class="material-symbols-outlined text-[32px] group-hover:rotate-180 transition-all duration-700">dns</span>
                            حقن عقدة سيادية (Inject)
                        </Link>
                    </div>
                </div>
            </div>

            <!-- The Node Matrix Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-10">
                <div 
                    v-for="server in servers" 
                    :key="server.id" 
                    class="surface-card group relative p-10 rounded-[3rem] bg-white border border-outline-variant/10 shadow-2xl overflow-hidden transition-all duration-500 hover:scale-[1.03] hover:shadow-primary/10"
                >
                    <!-- Background Grid Motif -->
                    <div class="absolute inset-x-0 bottom-0 h-48 bg-grid-slate-50 opacity-[0.03] pointer-events-none group-hover:opacity-[0.07] transition-opacity"></div>
                    
                    <!-- Tactical Status Bar -->
                    <div class="flex items-center justify-between mb-12 flex-row-reverse">
                        <div class="flex items-center gap-4">
                            <span class="text-[9px] font-black text-slate-400 font-headline tracking-[0.3em] uppercase">STATUS_ACTIVE</span>
                            <div class="w-3 h-3 rounded-full bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.4)] animate-pulse"></div>
                        </div>
                        <div class="w-16 h-16 rounded-[1.5rem] bg-slate-950 text-white flex items-center justify-center shadow-2xl group-hover:bg-primary group-hover:rotate-6 transition-all duration-700 relative overflow-hidden shrink-0 border border-white/10">
                             <div class="absolute inset-x-0 bottom-0 h-1/2 bg-gradient-to-t from-black/20 to-transparent"></div>
                             <span class="material-symbols-outlined text-[32px] relative z-10" style="font-variation-settings: 'FILL' 1; font-weight: 200;">memory</span>
                        </div>
                    </div>

                    <!-- Core Identification -->
                    <div class="text-right mb-12">
                        <h3 class="text-3xl font-black text-primary tracking-tighter mb-2 truncate group-hover:translate-x-2 transition-transform">{{ server.name }}</h3>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.4em] leading-relaxed italic opacity-70">
                            {{ server.device_model?.model_name || 'MikroTik Infrastructure Core' }}
                        </p>
                    </div>

                    <!-- Technical Metrics Layer -->
                    <div class="grid grid-cols-2 gap-8 mb-12 p-8 bg-slate-50 rounded-[2.5rem] border border-slate-100 shadow-inner group-hover:bg-slate-900 group-hover:border-slate-800 transition-all duration-500 relative z-10">
                        <div class="text-right">
                            <p class="text-[9px] font-black text-slate-400 group-hover:text-white/30 uppercase tracking-[0.2em] mb-2 leading-none">PROTOCOL_IP</p>
                            <p class="text-xl font-headline font-black text-primary group-hover:text-white tracking-widest">{{ server.ip }}</p>
                        </div>
                        <div class="text-right border-r border-slate-200 group-hover:border-white/10 pr-6">
                            <p class="text-[9px] font-black text-slate-400 group-hover:text-white/30 uppercase tracking-[0.2em] mb-2 leading-none">API_INTERFACE</p>
                            <p class="text-xl font-headline font-black text-secondary group-hover:text-amber-400 tracking-widest">{{ server.api_port }}</p>
                        </div>
                    </div>

                    <!-- Geographic Anchoring -->
                    <div class="flex items-center gap-4 justify-end mb-14 text-right flex-row-reverse px-2">
                        <div class="w-10 h-10 bg-slate-50 group-hover:bg-white/5 rounded-xl flex items-center justify-center border border-slate-100 group-hover:border-white/10 shadow-sm transition-all">
                            <span class="material-symbols-outlined text-primary text-[20px]" style="font-variation-settings: 'FILL' 1">location_on</span>
                        </div>
                        <div>
                             <p class="text-[14px] font-black text-slate-700 group-hover:text-white transition-colors leading-none mb-1">{{ server.location || 'نقطة الربط السيادية' }}</p>
                             <p class="text-[8px] font-black text-slate-300 group-hover:text-white/20 uppercase tracking-[0.3em] font-headline italic">CORE_TOPOLOGY_COORDINATE</p>
                        </div>
                    </div>

                    <!-- Interactive Diagnostic Protocol -->
                    <div class="flex flex-col gap-4 relative z-10">
                        <button 
                            @click="testConnection(server.id)"
                            :disabled="testingStatus[server.id] === 'testing'"
                            class="w-full h-16 rounded-2xl text-[11px] font-black uppercase tracking-[0.3em] transition-all border-2 flex items-center justify-center gap-4 shadow-xl active:scale-95 group/btn"
                            :class="{
                                'bg-white text-slate-400 border-slate-100 hover:border-primary hover:text-primary hover:bg-slate-50': !testingStatus[server.id],
                                'bg-primary/5 text-primary border-primary/20 animate-pulse': testingStatus[server.id] === 'testing',
                                'bg-rose-500/5 text-rose-500 border-rose-500/20': testingStatus[server.id] === 'error',
                                'bg-emerald-500/5 text-emerald-600 border-emerald-500/20': testingStatus[server.id] === 'success'
                            }"
                        >
                            <span class="material-symbols-outlined text-[24px]" :class="{'animate-spin': testingStatus[server.id] === 'testing'}">
                                {{ testingStatus[server.id] === 'testing' ? 'progress_activity' : (testingStatus[server.id] === 'error' ? 'report_problem' : (testingStatus[server.id] === 'success' ? 'verified' : 'pulse')) }}
                            </span>
                            {{ testingStatus[server.id] === 'testing' ? 'جاري الفحص...' : (testingStatus[server.id] === 'error' ? 'فشل المسار' : (testingStatus[server.id] === 'success' ? 'العقدة مستقرة' : 'اختبار النبض (Pulse Test)')) }}
                        </button>

                        <div class="flex items-center gap-3">
                             <Link 
                                :href="route('servers.show', server.id)"
                                class="flex-1 h-16 bg-slate-50 border border-slate-100 rounded-2xl flex items-center justify-center text-slate-500 hover:bg-slate-950 hover:text-white hover:border-slate-950 transition-all active:scale-95 group/icon"
                             >
                                <span class="material-symbols-outlined text-[26px] group-hover/icon:rotate-90 transition-transform">terminal</span>
                                <span class="mr-3 text-[9px] font-black uppercase tracking-widest">Console</span>
                             </Link>
                             <Link 
                                :href="route('servers.edit', server.id)"
                                class="w-16 h-16 bg-slate-100/50 border border-slate-100 rounded-2xl flex items-center justify-center text-slate-400 hover:text-amber-600 hover:bg-white transition-all active:scale-95"
                             >
                                <span class="material-symbols-outlined text-[24px]">architecture</span>
                             </Link>
                             <button 
                                @click="deleteServer(server.id)"
                                class="w-16 h-16 bg-slate-100/50 border border-slate-100 rounded-2xl flex items-center justify-center text-slate-400 hover:text-rose-600 hover:bg-white transition-all active:scale-95"
                             >
                                <span class="material-symbols-outlined text-[24px]">power_settings_new</span>
                             </button>
                        </div>
                    </div>
                </div>

                <!-- Empty Fleet Protocol -->
                <div v-if="servers.length === 0" class="col-span-full py-64 flex flex-col items-center gap-12 group/empty relative z-10">
                    <div class="w-64 h-64 rounded-[4rem] bg-slate-950 text-white flex items-center justify-center border-8 border-white shadow-[0_0_80px_rgba(2,6,23,0.1)] group-hover/empty:scale-110 group-hover/empty:rotate-12 transition-all duration-1000 relative">
                        <div class="absolute inset-0 bg-primary opacity-20 blur-3xl animate-pulse"></div>
                        <span class="material-symbols-outlined text-[100px] relative z-10" style="font-variation-settings: 'wght' 100">dns</span>
                    </div>
                    <div class="text-center">
                        <h3 class="text-5xl font-black text-primary mb-6 tracking-tight uppercase">مصفوفة العقد صفرية (Null Fleet)</h3>
                        <p class="text-lg font-black text-slate-400 uppercase tracking-[0.4em] max-w-lg leading-relaxed italic opacity-60">لم يتم رصد أي عقد MikroTik سيادية ضمن هذا القطاع. يُرجى تفعيل بروتوكول Inject Core الأولي.</p>
                    </div>
                    <Link :href="route('servers.create')" class="px-20 py-8 bg-primary text-white rounded-3xl font-black text-sm uppercase tracking-[0.4em] shadow-[0_30px_60px_rgba(37,99,235,0.2)] hover:bg-emerald-600 hover:-translate-y-2 transition-all active:scale-95 border border-white/10 flex items-center gap-8">
                        <span class="material-symbols-outlined text-[36px]">dns</span> تأسيس النواة الأولى
                    </Link>
                </div>
            </div>
        </div>
    </InstitutionalLayout>
</template>

<style scoped>
.font-headline {
    font-family: 'Manrope', sans-serif;
}
.bg-grid-slate-50 {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32' width='32' height='32' fill='none' stroke='white'%3E%3Cpath d='M0 .5H31.5V32'/%3E%3C/svg%3E");
}
</style>
