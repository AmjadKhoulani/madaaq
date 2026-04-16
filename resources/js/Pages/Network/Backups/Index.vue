<script setup>
import { Head, Link } from '@inertiajs/vue3';
import InstitutionalLayout from '@/Layouts/InstitutionalLayout.vue';
import Pagination from '@/Components/Pagination.vue';

const props = defineProps({
    backups: Object,
});

const formatBytes = (bytes, decimals = 2) => {
    if (bytes === 0) return '0 بايت';
    const k = 1024;
    const dm = decimals < 0 ? 0 : decimals;
    const sizes = ['بايت', 'کیلوبايت', 'ميجابايت', 'جيجابايت', 'تيرابايت'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
};

const formatDate = (date) => {
    return new Date(date).toLocaleString('ar-SY', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};

</script>

<template>
    <InstitutionalLayout title="ذكاء الاستعادة والنسخ الاحتياطي">
        <Head title="أرشيف البنية التحتية واللقطات - MadaaQ" />

        <div class="max-w-7xl mx-auto pb-24 text-right px-4" dir="rtl">
            <!-- Strategic Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-10 mb-16 flex-row-reverse text-right">
                <div class="text-right">
                    <h1 class="text-4xl font-black text-primary tracking-tight mb-2">منظومة الاستعادة المركزية (Disaster Recovery)</h1>
                    <div class="flex items-center gap-4 justify-end">
                        <p class="text-slate-500 font-bold text-sm uppercase tracking-wider">حوكمة الأرشيف الرقمي، تأمين اللقطات التشغيلية، وضمان استمرارية أصول الشبكة</p>
                        <span class="material-symbols-outlined text-[24px] text-primary">verified_user</span>
                    </div>
                </div>
            </div>

            <!-- Backups Registry Ledger -->
            <div class="surface-card rounded-3xl overflow-hidden shadow-2xl border border-outline-variant/5 bg-white">
                <div class="overflow-x-auto">
                    <table class="w-full text-right border-separate border-spacing-0">
                        <thead>
                            <tr class="bg-slate-950 text-white border-b border-white/5">
                                <th class="px-10 py-6 text-[11px] font-black uppercase tracking-[0.2em] text-white/40">هوية الأرشيف (Artifact)</th>
                                <th class="px-10 py-6 text-[11px] font-black uppercase tracking-[0.2em] text-white/40">المصدر التشغيلي (Origin)</th>
                                <th class="px-10 py-6 text-[11px] font-black uppercase tracking-[0.2em] text-center text-white/40 border-r border-white/5">حجم الحمولة</th>
                                <th class="px-10 py-6 text-[11px] font-black uppercase tracking-[0.2em] text-center text-white/40">النقطة الزمنية لحفظ الحالة</th>
                                <th class="px-10 py-6 w-32"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant/5">
                            <tr v-for="backup in backups.data" :key="backup.id" class="group hover:bg-surface-container-low/50 transition-all duration-300">
                                <td class="px-10 py-8">
                                    <div class="flex items-center gap-6 justify-end text-right">
                                        <div>
                                            <p class="text-base font-headline font-black text-primary leading-tight group-hover:translate-x-1 transition-transform uppercase tracking-tighter">{{ backup.filename }}</p>
                                            <div class="flex items-center gap-3 mt-2 opacity-50 justify-end">
                                                <p class="text-[10px] font-black uppercase tracking-widest leading-none">بروتوكول التأمين: {{ backup.type === 'backup' ? 'لقطة كاملة (Full)' : 'تصدير نصوص البرمجة' }}</p>
                                                <span class="material-symbols-outlined text-[14px]">vibration</span>
                                            </div>
                                        </div>
                                        <div class="w-14 h-14 rounded-2xl border border-outline-variant/5 flex items-center justify-center text-primary shrink-0 group-hover:bg-primary group-hover:text-white shadow-inner transition-all overflow-hidden relative border-none shadow-2xl"
                                             :class="backup.type === 'backup' ? 'bg-primary/5 text-primary' : 'bg-secondary/5 text-secondary'">
                                             <div class="absolute inset-0 bg-current opacity-10 group-hover:opacity-0 transition-opacity"></div>
                                            <span class="material-symbols-outlined text-[28px] relative z-10" style="font-variation-settings: 'FILL' 1">
                                                {{ backup.type === 'backup' ? 'inventory_2' : 'code_blocks' }}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-10 py-8 text-right">
                                    <div class="flex items-center gap-4 justify-end">
                                        <div class="text-right">
                                            <p class="text-[13px] font-black text-primary uppercase tracking-tight">{{ backup.server?.name || 'وحدة التحكم المحلية' }}</p>
                                            <p class="text-[9px] font-black text-slate-300 uppercase tracking-widest mt-1">عقدة المصدر (Origin Node)</p>
                                        </div>
                                        <div class="w-10 h-10 rounded-xl bg-surface-container-low border border-outline-variant/10 flex items-center justify-center text-slate-400">
                                            <span class="material-symbols-outlined text-[20px]">dns</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-10 py-8 text-center border-r border-outline-variant/5">
                                    <div class="inline-flex flex-col items-center gap-1">
                                        <p class="text-base font-headline font-black text-primary leading-none tracking-tight">{{ formatBytes(backup.size) }}</p>
                                        <span class="text-[9px] font-black text-slate-300 uppercase tracking-widest">ثقة البيانات</span>
                                    </div>
                                </td>
                                <td class="px-10 py-8 text-center">
                                    <div class="flex flex-col items-center gap-2">
                                        <span class="text-[12px] font-headline font-black text-primary bg-surface-container-low px-4 py-1.5 rounded-xl border border-outline-variant/5">{{ formatDate(backup.created_at) }}</span>
                                        <div class="flex items-center gap-2 opacity-30">
                                            <p class="text-[9px] font-black uppercase tracking-widest">توقيع أمني زمني</p>
                                            <span class="material-symbols-outlined text-[14px]">history</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-10 py-8">
                                    <div class="flex items-center justify-end gap-3 opacity-0 group-hover:opacity-100 transition-all">
                                         <a 
                                            :href="route('network.backups.download', backup.id)" 
                                            class="w-12 h-12 bg-white shadow-xl border border-outline-variant/10 rounded-xl flex items-center justify-center text-slate-400 hover:text-primary hover:scale-110 active:scale-90 transition-all group/btn"
                                         >
                                            <span class="material-symbols-outlined text-[24px] group-hover/btn:translate-y-1 transition-transform">cloud_download</span>
                                         </a>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Empty State Protocol -->
                <div v-if="backups.data.length === 0" class="py-48 flex flex-col items-center gap-10 group/empty">
                    <div class="w-32 h-32 rounded-[2.5rem] bg-surface-container-low flex items-center justify-center text-slate-200 border border-outline-variant/5 shadow-2xl group-hover/empty:scale-110 transition-all duration-1000">
                        <span class="material-symbols-outlined text-[64px]" style="font-variation-settings: 'wght' 100">database_off</span>
                    </div>
                    <div class="text-center">
                        <h3 class="text-2xl font-black text-primary mb-3">لا توجد أرشيفات محفوظة حالياً</h3>
                        <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.3em] max-w-sm leading-relaxed italic">لم يتم رصد أي نسخ احتياطية مؤمنة في قطاع العمليات. سيتم رصد وتخزين اللقطات آلياً وفقاً لجدول المزامنة.</p>
                    </div>
                </div>

                <!-- Strategic Pagination (Command Center Look) -->
                <div v-if="backups.links && backups.links.length > 3" class="px-10 py-8 bg-slate-950 border-t border-white/5 flex justify-between items-center flex-row-reverse">
                    <div class="text-[10px] font-black text-white/30 uppercase tracking-[0.3em] font-headline">Infrastructure Snapshot Discover Protocol</div>
                    <nav class="flex gap-4">
                        <Link 
                            v-for="(link, k) in backups.links" 
                            :key="k"
                            :href="link.url || '#'"
                            v-html="link.label"
                            class="h-12 flex items-center justify-center rounded-xl text-[12px] font-headline font-black uppercase tracking-widest transition-all border px-6"
                            :class="[
                                link.active ? 'bg-primary text-white border-primary shadow-2xl shadow-primary/20 scale-110' : 'bg-white/5 text-white/40 border-white/10 hover:text-white hover:bg-white/10 hover:border-white/30',
                                !link.url ? 'opacity-20 pointer-events-none' : ''
                            ]"
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
