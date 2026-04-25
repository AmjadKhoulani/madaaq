<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import InstitutionalLayout from '@/Layouts/InstitutionalLayout.vue';
import { ref } from 'vue';

const props = defineProps({
    logs: Object,
    users: Array
});

const filters = ref({
    user_id: '',
    model_type: '',
    date_from: '',
    date_to: '',
    search: '',
});

const applyFilters = () => {
    router.get(route('activity-logs.index'), filters.value, {
        preserveState: true,
        preserveScroll: true,
    });
};

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleString('ar-SY', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit'
    });
};

const getEventColor = (description) => {
    if (description.includes('created')) return 'text-emerald-500 border-emerald-500/20 bg-emerald-500/5 shadow-inner';
    if (description.includes('updated')) return 'text-indigo-500 border-indigo-500/20 bg-indigo-500/10 shadow-inner';
    if (description.includes('deleted')) return 'text-rose-600 border-rose-600/20 bg-rose-600/5 shadow-inner';
    return 'text-slate-500 border-slate-500/20 bg-slate-500/5 shadow-inner';
};

const getEventIcon = (description) => {
    if (description.includes('created')) return 'fiber_new';
    if (description.includes('updated')) return 'history_toggle_off';
    if (description.includes('deleted')) return 'dangerous';
    return 'info';
};

</script>

<template>
    <InstitutionalLayout title="سجل نزاهة المنظومة">
        <Head title="سجل التتبع والنزاهة السيادي - MadaaQ" />

        <div class="max-w-7xl mx-auto pb-24 text-right px-4" dir="rtl">
            <!-- Strategic Intelligence Header -->
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-10 mb-20 flex-row-reverse text-right">
                <div class="text-right">
                    <h1 class="text-4xl font-black text-primary tracking-tight mb-2 uppercase">سجل تتبع النزاهة والعمليات (Sovereign Audit Log)</h1>
                    <div class="flex items-center gap-4 justify-end">
                        <p class="text-slate-500 font-bold text-sm uppercase tracking-wider">الأرشفة اللحظية للحوكمة الرقمية، تتبع التغييرات البنيوية، وتوثيق هويات المشغلين</p>
                        <span class="material-symbols-outlined text-primary text-[24px]">verified_user</span>
                    </div>
                </div>
            </div>

            <!-- Strategic Intelligence Filter Suite -->
            <div class="surface-card p-12 rounded-[2.5rem] mb-16 border border-outline-variant/10 shadow-2xl bg-white relative overflow-hidden">
                <div class="absolute -top-32 -left-32 w-80 h-80 bg-primary/5 rounded-full blur-[100px]"></div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10 flex-row-reverse relative z-10">
                    <div class="space-y-4">
                        <label class="text-[11px] font-black text-primary uppercase tracking-[0.2em] mr-3">المشغل المسؤول (Operator)</label>
                        <div class="relative group">
                            <span class="material-symbols-outlined absolute right-5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-primary transition-colors">shield_person</span>
                            <select v-model="filters.user_id" @change="applyFilters" class="form-input-monolith h-16 text-sm font-black pr-14 appearance-none">
                                <option value="">كافة الهويات القيادية</option>
                                <option v-for="user in users" :key="user.id" :value="user.id">{{ user.name }}</option>
                            </select>
                            <span class="material-symbols-outlined absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 pointer-events-none group-focus-within:rotate-180 transition-transform">expand_more</span>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <label class="text-[11px] font-black text-primary uppercase tracking-[0.2em] mr-3">من تاريخ (Range Start)</label>
                        <div class="relative">
                            <input v-model="filters.date_from" type="date" @change="applyFilters" class="form-input-monolith h-16 font-headline font-black text-lg px-8">
                        </div>
                    </div>
                    <div class="space-y-4">
                        <label class="text-[11px] font-black text-primary uppercase tracking-[0.2em] mr-3">إلى تاريخ (Range End)</label>
                        <div class="relative">
                            <input v-model="filters.date_to" type="date" @change="applyFilters" class="form-input-monolith h-16 font-headline font-black text-lg px-8">
                        </div>
                    </div>
                    <div class="lg:col-span-2 space-y-4">
                        <label class="text-[11px] font-black text-primary uppercase tracking-[0.2em] mr-3">بـروب البحث الشامل (Global Trace)</label>
                        <div class="relative group">
                            <span class="material-symbols-outlined absolute right-6 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-primary transition-all text-[24px]">manage_search</span>
                            <input v-model="filters.search" @input="applyFilters" type="text" placeholder="اسم الكيان، الرقم المرجعي، أو وصف الإجراء السيادي..." class="form-input-monolith h-16 pr-16 text-sm font-black tracking-tight border-2 focus:ring-primary/20 shadow-inner">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Absolute Ledger (High-Density Integrity Table) -->
            <div class="surface-card rounded-[3rem] overflow-hidden shadow-[0_45px_70px_-15px_rgba(0,0,0,0.15)] bg-white border border-outline-variant/5">
                <div class="overflow-x-auto px-6 pb-6">
                    <table class="w-full text-right border-separate border-spacing-y-4">
                        <thead>
                            <tr class="text-slate-400">
                                <th class="px-10 py-8 text-[11px] font-black uppercase tracking-[0.3em] leading-none">توصيف التفاعل البرمجي (Action Trace)</th>
                                <th class="px-10 py-8 text-[11px] font-black uppercase tracking-[0.3em] leading-none text-center">بنية الكيان (Target)</th>
                                <th class="px-10 py-8 text-[11px] font-black uppercase tracking-[0.3em] leading-none">مُفعـل البروتوكول (Initiator)</th>
                                <th class="px-10 py-8 text-[11px] font-black uppercase tracking-[0.3em] leading-none text-center">التصنيف</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="log in logs.data" :key="log.id" class="group hover:bg-slate-50 transition-all duration-500 rounded-3xl">
                                <td class="px-10 py-8 bg-white border-y first:rounded-r-[2.5rem] first:border-r border-slate-100/50 group-hover:bg-slate-50 transition-all">
                                    <div class="flex flex-col gap-3">
                                        <div class="flex items-center gap-4 justify-end mb-1">
                                            <span class="text-lg font-black text-primary tracking-tight leading-none group-hover:translate-x-[-4px] transition-transform">{{ log.description }}</span>
                                            <span class="w-1.5 h-6 bg-primary/20 rounded-full"></span>
                                        </div>
                                        <div class="flex items-center gap-3 justify-end opacity-40">
                                            <span class="text-[10px] font-headline font-black uppercase tracking-[0.2em]">{{ formatDate(log.created_at) }}</span>
                                            <span class="material-symbols-outlined text-[16px]">schedule</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-10 py-8 bg-white border-y border-slate-100/50 group-hover:bg-slate-50 text-center">
                                    <div class="inline-flex items-center gap-5 px-6 py-3 bg-slate-950 text-white rounded-[1.5rem] shadow-2xl group-hover:scale-105 transition-transform border border-white/10 group-hover:rotate-1">
                                        <span class="text-[11px] font-headline font-black tracking-widest uppercase opacity-80">{{ log.subject_type?.split('\\').pop() }} <span class="text-primary mx-1">/</span> #{{ log.subject_id }}</span>
                                        <span class="material-symbols-outlined text-primary text-[20px]" style="font-variation-settings: 'FILL' 1">terminal</span>
                                    </div>
                                </td>
                                <td class="px-10 py-8 bg-white border-y border-slate-100/50 group-hover:bg-slate-50">
                                    <div class="flex items-center gap-6 justify-end">
                                        <div class="text-right">
                                            <h4 class="text-base font-black text-primary leading-none mb-2 tracking-tight">{{ log.causer?.name || 'النظام المركزي' }}</h4>
                                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.3em] flex items-center gap-3 justify-end">
                                                <span>{{ log.causer? log.causer.role : 'AUTO_PROTOCOL_NODE' }}</span>
                                                <div class="flex h-1.5 w-1.5 relative">
                                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-500 opacity-75"></span>
                                                    <span class="inline-flex rounded-full h-1.5 w-1.5 bg-emerald-500"></span>
                                                </div>
                                            </p>
                                        </div>
                                        <div class="w-14 h-14 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-400 group-hover:bg-primary group-hover:text-white transition-all shadow-inner border border-slate-100">
                                            <span class="material-symbols-outlined text-[32px]">shield_person</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-10 py-8 bg-white border-y last:rounded-l-[2.5rem] last:border-l border-slate-100/50 group-hover:bg-slate-50 text-center">
                                    <div class="inline-flex items-center gap-4 px-6 py-2.5 rounded-2xl border-2 text-[10px] font-black uppercase tracking-[0.2em] transition-all shadow-sm group-hover:scale-110"
                                         :class="getEventColor(log.description)">
                                         <span class="material-symbols-outlined text-[20px]">{{ getEventIcon(log.description) }}</span>
                                        {{ log.description.includes('created') ? 'إنشاء (Creation)' : (log.description.includes('updated') ? 'تعديل (Modification)' : (log.description.includes('deleted') ? 'إلغاء (De-provision)' : 'بروتوكول')) }}
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Empty State (No Trace) -->
                <div v-if="logs.data.length === 0" class="py-48 flex flex-col items-center gap-10 opacity-30 group/empty">
                    <div class="w-32 h-32 rounded-[3.5rem] bg-slate-50 flex items-center justify-center text-slate-200 border-4 border-dashed border-outline-variant/10 shadow-inner group-hover/empty:scale-110 group-hover/empty:rotate-12 transition-all duration-1000">
                        <span class="material-symbols-outlined text-[64px]" style="font-variation-settings: 'wght' 100">history_toggle_off</span>
                    </div>
                    <div class="text-center">
                        <h3 class="text-3xl font-black text-primary mb-4">سجل النزاهة في وضع السكون</h3>
                        <p class="text-[12px] font-black text-slate-400 uppercase tracking-[0.4em] max-w-lg leading-relaxed italic">لم يتم رصد أي عمليات تداخل برمجية أو تفاعلات إدارية سيادية ضمن النطاق الزمني والمعايير المحددة.</p>
                    </div>
                </div>

                <!-- Strategic Pagination (Terminal Theme) -->
                <div v-if="logs.links && logs.links.length > 3" class="px-16 py-12 bg-slate-950 border-t border-white/5 flex justify-between items-center flex-row-reverse text-right rounded-b-[3rem] shadow-[0_-20px_50px_rgba(0,0,0,0.5)]">
                    <div class="text-[11px] font-black text-white/20 uppercase tracking-[0.4em] leading-none">تصفح السجلات التتبعية (Pagination Cluster)</div>
                    <nav class="flex gap-4">
                        <Link 
                            v-for="link in logs.links" 
                            :key="link.label"
                            :href="link.url || '#'"
                            class="h-14 min-w-[3.5rem] flex items-center justify-center rounded-2xl text-[12px] font-headline font-black uppercase tracking-[0.2em] transition-all border px-6"
                            :class="link.active ? 'bg-primary text-white border-primary shadow-2xl shadow-primary/30 scale-110 z-10' : (link.url ? 'bg-white/5 text-white/40 border-white/10 hover:text-white hover:bg-white/10 hover:border-white/30' : 'bg-transparent text-white/10 border-transparent cursor-not-allowed')"
                            v-html="link.label"
                        />
                    </nav>
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

