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
    <InstitutionalLayout title="حوكمة السيرفرات">
        <Head title="إدارة عقد MikroTik المركزية - MadaaQ" />

        <div class="max-w-7xl mx-auto pb-24 text-right px-4" dir="rtl">
            <!-- Institutional Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-10 mb-16 flex-row-reverse text-right">
                <div class="text-right">
                    <h1 class="text-4xl font-black text-primary tracking-tight mb-2">منظومة السيرفرات المركزية (Core Nodes)</h1>
                    <div class="flex items-center gap-4 justify-end">
                        <p class="text-slate-500 font-bold text-sm uppercase tracking-wider">إدارة العقد التشغيلية، بروتوكولات الربط البرمجي، وحوكمة المزامنة المركزية</p>
                        <span class="flex h-3 w-3 relative">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-secondary opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-secondary"></span>
                        </span>
                    </div>
                </div>
                <Link 
                    :href="route('servers.create')" 
                    class="px-12 py-5 bg-primary text-white rounded-xl font-black text-xs uppercase tracking-[0.2em] shadow-2xl shadow-primary/20 hover:bg-emerald-600 transition-all active:scale-95 flex items-center gap-4 border border-white/10"
                >
                    <span class="material-symbols-outlined text-[24px]">dns</span> ربط وحدة معالجة جديدة
                </Link>
            </div>

            <!-- Fleet Registry Ledger -->
            <div class="surface-card rounded-3xl overflow-hidden shadow-2xl border border-outline-variant/5 bg-white">
                <div class="overflow-x-auto">
                    <table class="w-full text-right border-separate border-spacing-0">
                        <thead>
                            <tr class="bg-slate-950 text-white border-b border-white/5">
                                <th class="px-10 py-6 text-[11px] font-black uppercase tracking-[0.2em] text-white/40">هوية العقدة (Edge Hub)</th>
                                <th class="px-10 py-6 text-[11px] font-black uppercase tracking-[0.2em] text-white/40">بروتوكول العنوان والمنفذ</th>
                                <th class="px-10 py-6 text-[11px] font-black uppercase tracking-[0.2em] text-center text-white/40 border-r border-white/5">الموقع البروتوكولي</th>
                                <th class="px-10 py-6 text-[11px] font-black uppercase tracking-[0.2em] text-center text-white/40">نبض الاتصال</th>
                                <th class="px-10 py-6 w-48"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant/5">
                            <tr v-for="server in servers" :key="server.id" class="group hover:bg-surface-container-low/50 transition-all duration-300">
                                <td class="px-10 py-8">
                                    <div class="flex items-center gap-8 justify-end text-right">
                                        <div>
                                            <h4 class="text-xl font-black text-primary leading-tight group-hover:translate-x-1 transition-transform">{{ server.name }}</h4>
                                            <div class="flex items-center gap-3 mt-2 opacity-50 justify-end">
                                                <p class="text-[10px] font-black uppercase tracking-widest leading-none">{{ server.device_model?.model_name || 'MikroTik Infrastructure Core' }}</p>
                                                <span class="material-symbols-outlined text-[16px]">memory</span>
                                            </div>
                                        </div>
                                        <div class="w-16 h-16 rounded-2xl bg-surface-container-low border border-outline-variant/10 flex items-center justify-center text-primary shrink-0 group-hover:bg-primary group-hover:text-white shadow-inner transition-all overflow-hidden relative border-none shadow-2xl">
                                             <div class="absolute inset-0 bg-primary opacity-5 group-hover:opacity-0 transition-opacity"></div>
                                            <span class="material-symbols-outlined text-[32px] relative z-10" style="font-variation-settings: 'FILL' 1">terminal</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-10 py-8 text-right">
                                    <div class="inline-flex flex-col gap-2">
                                        <span class="text-lg font-headline font-black text-primary tracking-widest bg-slate-50 px-4 py-1.5 rounded-xl border border-slate-200">{{ server.ip }}</span>
                                        <div class="flex items-center gap-3 justify-end opacity-40">
                                            <span class="text-[10px] font-black uppercase tracking-[0.2em]">بروتوكول الصيانة: <span class="font-headline">{{ server.api_port }}</span></span>
                                            <span class="material-symbols-outlined text-[14px]">bolt</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-10 py-8 text-center border-r border-outline-variant/5">
                                    <div class="flex flex-col items-center gap-2">
                                        <div class="flex items-center gap-2 bg-surface-container-low px-4 py-1.5 rounded-xl border border-outline-variant/5">
                                            <span class="material-symbols-outlined text-primary text-[18px]">location_on</span>
                                            <span class="text-[12px] font-black text-slate-600">{{ server.location || 'العقدة الرئيسية' }}</span>
                                        </div>
                                        <p class="text-[9px] font-black text-slate-300 uppercase tracking-widest">تغطية النطاق المركزية</p>
                                    </div>
                                </td>
                                <td class="px-10 py-8 text-center">
                                    <button 
                                        @click="testConnection(server.id)"
                                        :disabled="testingStatus[server.id] === 'testing'"
                                        class="inline-flex items-center gap-3 px-6 py-3 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] transition-all border-2 group-hover:scale-105"
                                        :class="{
                                            'bg-slate-50 text-slate-400 border-slate-100 hover:border-primary hover:text-primary shadow-sm': !testingStatus[server.id],
                                            'bg-blue-500/10 text-blue-600 border-blue-500/20 animate-pulse': testingStatus[server.id] === 'testing',
                                            'bg-rose-500/10 text-rose-500 border-rose-500/20': testingStatus[server.id] === 'error',
                                            'bg-emerald-500/10 text-emerald-600 border-emerald-500/20': testingStatus[server.id] === 'success'
                                        }"
                                    >
                                        <span class="material-symbols-outlined text-[20px]" :class="{'animate-spin': testingStatus[server.id] === 'testing'}">
                                            {{ testingStatus[server.id] === 'testing' ? 'rebase' : (testingStatus[server.id] === 'error' ? 'wifi_off' : (testingStatus[server.id] === 'success' ? 'verified' : 'network_check')) }}
                                        </span>
                                        {{ testingStatus[server.id] === 'testing' ? 'مزامنة فحص النبض' : (testingStatus[server.id] === 'error' ? 'فشل الوصلة' : (testingStatus[server.id] === 'success' ? 'تمت الاستجابة' : 'اختبار زمن الاستجابة')) }}
                                    </button>
                                </td>
                                <td class="px-10 py-8">
                                    <div class="flex items-center justify-end gap-3 opacity-0 group-hover:opacity-100 transition-all">
                                         <Link 
                                            :href="route('servers.show', server.id)"
                                            class="w-12 h-12 bg-white shadow-xl border border-outline-variant/10 rounded-xl flex items-center justify-center text-slate-400 hover:text-primary hover:scale-110 active:scale-90 transition-all"
                                         >
                                            <span class="material-symbols-outlined text-[24px]">terminal</span>
                                         </Link>
                                         <Link 
                                            :href="route('servers.edit', server.id)"
                                            class="w-12 h-12 bg-white shadow-xl border border-outline-variant/10 rounded-xl flex items-center justify-center text-slate-400 hover:text-amber-600 hover:scale-110 active:scale-90 transition-all"
                                         >
                                            <span class="material-symbols-outlined text-[24px]">settings_suggest</span>
                                         </Link>
                                         <button 
                                            @click="deleteServer(server.id)"
                                            class="w-12 h-12 bg-white shadow-xl border border-outline-variant/10 rounded-xl flex items-center justify-center text-slate-400 hover:text-rose-600 hover:scale-110 active:scale-90 transition-all"
                                         >
                                            <span class="material-symbols-outlined text-[24px]">power_settings_new</span>
                                         </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Empty Fleet Protocol -->
                <div v-if="servers.length === 0" class="py-48 flex flex-col items-center gap-10 group/empty">
                    <div class="w-32 h-32 rounded-[2.5rem] bg-surface-container-low flex items-center justify-center text-slate-200 border border-outline-variant/5 shadow-2xl group-hover/empty:scale-110 transition-all duration-1000">
                        <span class="material-symbols-outlined text-[64px]" style="font-variation-settings: 'wght' 100">dns</span>
                    </div>
                    <div class="text-center">
                        <h3 class="text-2xl font-black text-primary mb-3">أسطول السيرفرات غير مكتمل</h3>
                        <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.3em] max-w-sm leading-relaxed italic">لم يتم تعريف أي عقد MikroTik مركزية حالياً. يُرجى الربط مع البنية التحتية لتفعيل المنظومة.</p>
                    </div>
                    <Link :href="route('servers.create')" class="px-12 py-5 bg-primary text-white rounded-xl font-black text-xs uppercase tracking-[0.2em] shadow-2xl hover:bg-emerald-600 transition-all active:scale-95 border border-white/10 flex items-center gap-4">
                        <span class="material-symbols-outlined text-[24px]">add_moderator</span> تعريف العقدة الأولى
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
</style>
