<script setup>
import { Head, Link } from '@inertiajs/vue3';
import InstitutionalLayout from '@/Layouts/InstitutionalLayout.vue';

const props = defineProps({
    backups: Object,
});

const formatBytes = (bytes, decimals = 2) => {
    if (bytes === 0) return '0.000 KB';
    const k = 1024;
    const dm = decimals < 0 ? 0 : decimals;
    const sizes = ['B', 'KB', 'MB', 'GB', 'TB'];
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
    <InstitutionalLayout title="مصفوفة الاستعادة والنسخ الاحتياطي">
        <Head title="مصفوفة الاستعادة والنسخ الاحتياطي (DR Matrix) - MadaaQ" />

        <div class="max-w-7xl mx-auto pb-24 text-right px-4" dir="rtl">
            <!-- Strategic Intelligence Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-12 mb-16 flex-row-reverse text-right">
                <div class="text-right">
                    <h1 class="text-5xl font-black text-primary tracking-tighter mb-3 uppercase">مصفوفة الاستعادة والنسخ الاحتياطي (DR Matrix)</h1>
                    <div class="flex items-center gap-6 justify-end">
                        <p class="text-slate-500 font-bold text-sm uppercase tracking-[0.2em]">حوكمة الأرشيف الرقمي، تأمين اللقطات التشغيلية، وضمان استمرارية أصول البنية التحتية</p>
                        <span class="flex h-4 w-4 relative">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-secondary opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-4 w-4 bg-secondary"></span>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Archival Intelligence Registry -->
            <div class="surface-card rounded-[2.5rem] overflow-hidden shadow-2xl border border-outline-variant/10 bg-white relative">
                 <div class="absolute inset-0 bg-grid-slate-50 [mask-image:linear-gradient(0deg,white,rgba(255,255,255,0.6))] pointer-events-none opacity-20"></div>
                
                <div class="overflow-x-auto relative z-10">
                    <table class="w-full text-right border-separate border-spacing-0">
                        <thead>
                            <tr class="bg-slate-950 text-white border-b border-white/5 uppercase">
                                <th class="px-12 py-8 text-[11px] font-black uppercase tracking-[0.3em] text-white/30">هوية الأرشيف (Artifact ID)</th>
                                <th class="px-10 py-8 text-[11px] font-black uppercase tracking-[0.3em] text-white/30">الموضع العملياتي (Node Origin)</th>
                                <th class="px-10 py-8 text-[11px] font-black uppercase tracking-[0.3em] text-center text-white/30 border-r border-white/5">كتلة البيانات (Payload)</th>
                                <th class="px-10 py-8 text-[11px] font-black uppercase tracking-[0.3em] text-center text-white/30 border-r border-white/5">طابع التوقيع الزمني</th>
                                <th class="px-10 py-8 w-48"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant/5">
                            <tr v-for="backup in backups.data" :key="backup.id" class="group hover:bg-slate-50/80 transition-all duration-500">
                                <td class="px-12 py-10">
                                    <div class="flex items-center gap-10 justify-end text-right">
                                        <div>
                                            <h4 class="text-2xl font-black text-primary leading-tight group-hover:translate-x-3 transition-transform tracking-tighter uppercase font-headline">{{ backup.filename }}</h4>
                                            <div class="flex items-center gap-4 mt-3 opacity-40 justify-end">
                                                <p class="text-[10px] font-black uppercase tracking-[0.2em] leading-none">بروتوكول التأمين: <span class="font-headline font-black text-slate-900">{{ backup.type === 'backup' ? 'FULL_SNAP' : 'CONFIG_SCRIPT' }}</span></p>
                                                <span class="material-symbols-outlined text-[18px]">verified_user</span>
                                            </div>
                                        </div>
                                        <div class="w-20 h-20 rounded-[1.5rem] bg-slate-950 text-white flex items-center justify-center shadow-2xl group-hover:bg-primary group-hover:scale-110 group-hover:rotate-6 transition-all duration-700 relative overflow-hidden shrink-0 border border-white/10">
                                             <div class="absolute inset-y-0 left-0 w-1 bg-primary transform -translate-x-full group-hover:translate-x-0 transition-transform"></div>
                                             <span class="material-symbols-outlined text-[40px] relative z-10" style="font-variation-settings: 'FILL' 1; font-weight: 200;">
                                                {{ backup.type === 'backup' ? 'inventory_2' : 'code_blocks' }}
                                             </span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-10 py-10 text-right">
                                    <div class="flex items-center gap-6 justify-end">
                                        <div class="text-right">
                                            <p class="text-xl font-black text-primary uppercase tracking-tight group-hover:text-primary transition-colors">{{ backup.server?.name || 'ROOT_CONTROLLER' }}</p>
                                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mt-2 italic opacity-40">عقدة المصدر (Origin Node)</p>
                                        </div>
                                        <div class="w-14 h-14 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-center text-slate-400 shadow-inner group-hover:bg-slate-950 group-hover:text-white transition-all">
                                            <span class="material-symbols-outlined text-[28px]">dns</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-10 py-10 text-center border-r border-outline-variant/5">
                                    <div class="flex flex-col items-center gap-3">
                                        <p class="text-2xl font-headline font-black tracking-tighter text-primary leading-none group-hover:scale-110 transition-transform">{{ formatBytes(backup.size) }}</p>
                                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] leading-none">نزاهة الكتلة</span>
                                    </div>
                                </td>
                                <td class="px-10 py-10 text-center border-r border-outline-variant/5">
                                    <div class="flex flex-col items-center gap-3">
                                        <span class="text-[12px] font-headline font-black text-white bg-slate-950 px-6 py-2.5 rounded-xl shadow-xl group-hover:bg-primary transition-colors italic whitespace-nowrap">{{ formatDate(backup.created_at) }}</span>
                                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.3em] leading-none mt-2">طابع استبقاء الحالة</p>
                                    </div>
                                </td>
                                <td class="px-10 py-10 text-left">
                                    <div class="flex items-center justify-end gap-3 opacity-0 group-hover:opacity-100 transition-all translate-x-4 group-hover:translate-x-0">
                                         <a 
                                            :href="route('network.backups.download', backup.id)" 
                                            class="w-16 h-16 bg-white shadow-2xl border border-outline-variant/10 rounded-2xl flex items-center justify-center text-slate-400 hover:text-primary hover:scale-110 active:scale-95 transition-all group/btn"
                                         >
                                            <span class="material-symbols-outlined text-[32px] group-hover/btn:translate-y-2 transition-transform" style="font-variation-settings: 'wght' 700">cloud_download</span>
                                         </a>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Null Artifact Protocol (Empty State) -->
                <div v-if="backups.data.length === 0" class="py-64 flex flex-col items-center gap-12 group/empty relative z-10">
                    <div class="w-48 h-48 rounded-[3.5rem] bg-slate-950 text-white flex items-center justify-center border-8 border-white shadow-[0_0_80px_rgba(2,6,23,0.15)] group-hover/empty:scale-110 transition-all duration-1000 relative">
                        <div class="absolute inset-0 bg-primary opacity-20 blur-3xl animate-pulse"></div>
                        <span class="material-symbols-outlined text-[80px] relative z-10" style="font-variation-settings: 'wght' 100">database_off</span>
                    </div>
                    <div class="text-center">
                        <h3 class="text-4xl font-black text-primary mb-6 tracking-tighter uppercase leading-none">مصفوفات الأرشيف صفرية (Null Artifact)</h3>
                        <p class="text-[12px] font-black text-slate-400 uppercase tracking-[0.4em] max-w-sm leading-relaxed italic opacity-70">لم يتم رصد أي نسخ استعادة لقطوف الشبكة حالياً. سيتم رصد وتخزين اللقطات آلياً وفقاً لبروتوكول المزامنة.</p>
                    </div>
                </div>

                <!-- Strategic Snapshot Pagination -->
                <div v-if="backups.links && backups.links.length > 3" class="px-12 py-10 bg-slate-950 border-t border-white/5 flex justify-between items-center flex-row-reverse relative z-10 overflow-hidden">
                    <div class="absolute inset-0 bg-grid-slate-50 opacity-5 pointer-events-none"></div>
                    <div class="text-[10px] font-black text-white/20 uppercase tracking-[0.5em] font-headline relative z-10">SNAPSHOT_DISCOVERY_PROTOCOL_v4.2</div>
                    <nav class="flex gap-5 relative z-10">
                        <Link 
                            v-for="(link, k) in backups.links" 
                            :key="k"
                            :href="link.url || '#'"
                            v-html="link.label"
                            class="h-14 flex items-center justify-center rounded-2xl text-[12px] font-headline font-black uppercase tracking-[0.2em] transition-all border px-8"
                            :class="[
                                link.active ? 'bg-primary text-white border-primary shadow-[0_15px_30px_rgba(37,99,235,0.3)] scale-110 z-10' : 'bg-white/5 text-white/40 border-white/10 hover:text-white hover:bg-white/10 hover:border-white/30',
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
.font-headline { font-family: 'Manrope', sans-serif; }
.bg-grid-slate-50 {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32' width='32' height='32' fill='none' stroke='rgb(15 23 42 / 0.05)'%3E%3Cpath d='M0 .5H31.5V32'/%3E%3C/svg%3E");
}
</style>

