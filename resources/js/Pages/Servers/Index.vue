<script setup>
import { ref } from 'vue';
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
</script>

<template>
    <InstitutionalLayout title="حوكمة العقد المركزية">
        <Head title="إدارة العقد السيادية (Core Nodes) - MadaaQ" />

        <div class="max-w-7xl mx-auto pb-24 text-right px-4" dir="rtl">
            <!-- Strategic Header Intel -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-10 mb-16 flex-row-reverse text-right">
                <div class="text-right">
                    <h1 class="text-4xl font-black text-primary tracking-tight mb-2 uppercase">مصفوفة التحكم المركزية (Cluster Inventory)</h1>
                    <div class="flex items-center gap-4 justify-end">
                        <p class="text-slate-500 font-bold text-sm uppercase tracking-wider">حوكمة العقد التشغيلية، بروتوكولات المزامنة القطبية، وتأمين المسارات السيادية</p>
                        <span class="flex h-3 w-3 relative">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-secondary opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-secondary"></span>
                        </span>
                    </div>
                </div>
                <Link 
                    :href="route('servers.create')" 
                    class="px-12 py-5 bg-slate-950 text-white rounded-2xl font-black text-[11px] uppercase tracking-[0.3em] shadow-2xl hover:bg-primary transition-all active:scale-95 flex items-center gap-4 border border-white/10 group"
                >
                    <span class="material-symbols-outlined text-[24px] group-hover:rotate-180 transition-transform">rebase</span>
                    حقن عقدة جديدة (Inject Node)
                </Link>
            </div>

            <!-- Fleet Registry Ledger -->
            <div class="surface-card rounded-[2.5rem] overflow-hidden shadow-2xl border border-outline-variant/10 bg-white relative">
                <div class="absolute inset-0 bg-grid-slate-50 [mask-image:linear-gradient(0deg,white,rgba(255,255,255,0.6))] pointer-events-none opacity-20"></div>
                
                <div class="overflow-x-auto relative z-10">
                    <table class="w-full text-right border-separate border-spacing-0">
                        <thead>
                            <tr class="bg-slate-950 text-white border-b border-white/5">
                                <th class="px-10 py-8 text-[11px] font-black uppercase tracking-[0.2em] text-white/30">هوية العقدة (Edge Hub)</th>
                                <th class="px-10 py-8 text-[11px] font-black uppercase tracking-[0.2em] text-white/30 text-center">بروتوكول الواجهة (Interface)</th>
                                <th class="px-10 py-8 text-[11px] font-black uppercase tracking-[0.2em] text-center text-white/30 border-r border-white/5">التوضع الجغرافي</th>
                                <th class="px-10 py-8 text-[11px] font-black uppercase tracking-[0.2em] text-center text-white/30">نبض المزامنة (Pulse)</th>
                                <th class="px-10 py-8 w-48"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant/5">
                            <tr v-for="server in servers" :key="server.id" class="group hover:bg-slate-50/80 transition-all duration-500">
                                <td class="px-10 py-10">
                                    <div class="flex items-center gap-10 justify-end text-right">
                                        <div>
                                            <h4 class="text-2xl font-black text-primary leading-tight group-hover:translate-x-2 transition-transform tracking-tight uppercase">{{ server.name }}</h4>
                                            <div class="flex items-center gap-3 mt-3 opacity-40 justify-end">
                                                <p class="text-[10px] font-black uppercase tracking-[0.2em] leading-none">{{ server.device_model?.model_name || 'MikroTik Infrastructure Core' }}</p>
                                                <span class="material-symbols-outlined text-[16px]">memory</span>
                                            </div>
                                        </div>
                                        <div class="w-20 h-20 rounded-3xl bg-slate-950 text-white flex items-center justify-center shadow-2xl group-hover:bg-primary group-hover:scale-110 group-hover:rotate-6 transition-all duration-700 relative overflow-hidden shrink-0 border border-white/10">
                                             <div class="absolute inset-x-0 bottom-0 h-1/2 bg-gradient-to-t from-black/20 to-transparent"></div>
                                             <span class="material-symbols-outlined text-[36px] relative z-10" style="font-variation-settings: 'FILL' 1; font-weight: 700;">dns</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-10 py-10 text-center">
                                    <div class="inline-flex flex-col items-center gap-3">
                                        <span class="text-xl font-headline font-black text-primary tracking-widest bg-white shadow-xl px-6 py-2.5 rounded-2xl border border-outline-variant/10 group-hover:bg-slate-900 group-hover:text-white transition-all">{{ server.ip }}</span>
                                        <div class="flex items-center gap-3 opacity-30">
                                            <span class="text-[9px] font-black uppercase tracking-[0.3em]">API_PORT: <span class="font-headline text-primary">{{ server.api_port }}</span></span>
                                            <span class="w-1.5 h-1.5 bg-current rounded-full animate-pulse"></span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-10 py-10 text-center border-r border-outline-variant/5">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="flex items-center gap-3 bg-surface-container-low px-5 py-2.5 rounded-2xl border border-outline-variant/10 shadow-inner group-hover:shadow-none transition-all">
                                            <span class="material-symbols-outlined text-primary text-[20px]" style="font-variation-settings: 'FILL' 1">location_on</span>
                                            <span class="text-[14px] font-black text-slate-700 tracking-tight">{{ server.location || 'نقطة الربط السيادية' }}</span>
                                        </div>
                                        <p class="text-[9px] font-black text-slate-300 uppercase tracking-[0.3em]">CORE_TOPOLOGY_COORDINATE</p>
                                    </div>
                                </td>
                                <td class="px-10 py-10 text-center">
                                    <button 
                                        @click="testConnection(server.id)"
                                        :disabled="testingStatus[server.id] === 'testing'"
                                        class="inline-flex items-center gap-4 px-8 py-4 rounded-2xl text-[11px] font-black uppercase tracking-[0.2em] transition-all border-2 group-hover:shadow-xl relative overflow-hidden group/btn"
                                        :class="{
                                            'bg-white text-slate-400 border-outline-variant/10 hover:border-primary hover:text-primary': !testingStatus[server.id],
                                            'bg-primary/5 text-primary border-primary/20 animate-pulse': testingStatus[server.id] === 'testing',
                                            'bg-rose-500/5 text-rose-500 border-rose-500/20': testingStatus[server.id] === 'error',
                                            'bg-emerald-500/5 text-emerald-600 border-emerald-500/20 shadow-xl shadow-emerald-500/10': testingStatus[server.id] === 'success'
                                        }"
                                    >
                                        <span class="material-symbols-outlined text-[20px]" :class="{'animate-spin': testingStatus[server.id] === 'testing'}">
                                            {{ testingStatus[server.id] === 'testing' ? 'progress_activity' : (testingStatus[server.id] === 'error' ? 'report_problem' : (testingStatus[server.id] === 'success' ? 'verified' : 'pulse')) }}
                                        </span>
                                        {{ testingStatus[server.id] === 'testing' ? 'جاري الفحص البرمجي...' : (testingStatus[server.id] === 'error' ? 'فشل المسار' : (testingStatus[server.id] === 'success' ? 'العقدة مستقرة' : 'اختبار زمن الاستجابة')) }}
                                    </button>
                                </td>
                                <td class="px-10 py-10">
                                    <div class="flex items-center justify-end gap-3 opacity-0 group-hover:opacity-100 transition-all translate-x-4 group-hover:translate-x-0">
                                         <Link 
                                            :href="route('servers.show', server.id)"
                                            class="w-14 h-14 bg-white shadow-2xl border border-outline-variant/10 rounded-2xl flex items-center justify-center text-slate-400 hover:text-primary hover:scale-110 active:scale-95 transition-all group/icon"
                                         >
                                            <span class="material-symbols-outlined text-[28px] group-hover/icon:rotate-12 transition-transform" style="font-variation-settings: 'wght' 700">terminal</span>
                                         </Link>
                                         <Link 
                                            :href="route('servers.edit', server.id)"
                                            class="w-14 h-14 bg-white shadow-2xl border border-outline-variant/10 rounded-2xl flex items-center justify-center text-slate-400 hover:text-amber-600 hover:scale-110 active:scale-95 transition-all group/icon"
                                         >
                                            <span class="material-symbols-outlined text-[28px] group-hover/icon:-rotate-12 transition-transform" style="font-variation-settings: 'wght' 700">architecture</span>
                                         </Link>
                                         <button 
                                            @click="deleteServer(server.id)"
                                            class="w-14 h-14 bg-white shadow-2xl border border-outline-variant/10 rounded-2xl flex items-center justify-center text-slate-400 hover:text-rose-600 hover:scale-110 active:scale-95 transition-all group/icon"
                                         >
                                            <span class="material-symbols-outlined text-[28px] group-hover/icon:scale-75 transition-transform" style="font-variation-settings: 'wght' 700">power_settings_new</span>
                                         </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Empty Fleet Protocol -->
                <div v-if="servers.length === 0" class="py-64 flex flex-col items-center gap-12 group/empty relative z-10">
                    <div class="w-40 h-40 rounded-[3rem] bg-slate-950 text-white flex items-center justify-center border-4 border-white/10 shadow-[0_0_50px_rgba(2,6,23,0.1)] group-hover/empty:scale-110 group-hover/empty:rotate-12 transition-all duration-1000 relative">
                        <div class="absolute inset-0 bg-primary opacity-20 blur-2xl animate-pulse"></div>
                        <span class="material-symbols-outlined text-[80px] relative z-10" style="font-variation-settings: 'wght' 100">dns</span>
                    </div>
                    <div class="text-center">
                        <h3 class="text-3xl font-black text-primary mb-4 tracking-tight uppercase">مصفوفة العقد غير مكتملة (Null Fleet)</h3>
                        <p class="text-[12px] font-black text-slate-400 uppercase tracking-[0.4em] max-w-sm leading-relaxed italic opacity-60">لم يتم حقن أي بيانات لعقد MikroTik المركزية ضمن هذا القطاع. يُرجى تفعيل بروتوكول الربط الأولي.</p>
                    </div>
                    <Link :href="route('servers.create')" class="px-14 py-6 bg-primary text-white rounded-2xl font-black text-xs uppercase tracking-[0.3em] shadow-[0_20px_40px_rgba(37,99,235,0.2)] hover:bg-emerald-600 hover:-translate-y-1 transition-all active:scale-95 border border-white/10 flex items-center gap-5">
                        <span class="material-symbols-outlined text-[28px]">rebase</span> تأسيس النواة الأولى
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
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32' width='32' height='32' fill='none' stroke='rgb(15 23 42 / 0.04)'%3E%3Cpath d='M0 .5H31.5V32'/%3E%3C/svg%3E");
}
</style>
