<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import InstitutionalLayout from '@/Layouts/InstitutionalLayout.vue';

const props = defineProps({
    profiles: Array
});

const deleteProfile = (id) => {
    if (confirm('تأكيد إخراج فئة الخدمة هذه من الدليل؟ سيؤدي ذلك إلى تعطيل إمكانية الاشتراك الجديد وإخطار كافة أجهزة التحكم الطرفية بالشبكة.')) {
        router.delete(route('broadband.profiles.destroy', id), {
            preserveScroll: true,
        });
    }
};

const getTechDetails = (type) => {
    switch (type) {
        case 'fiber': return { label: 'ألياف ضوئية (Fiber)', icon: 'fiber_smart_record', color: 'text-emerald-500 bg-emerald-500/5' };
        case 'wireless': return { label: 'ربط لاسلكي (Wireless)', icon: 'settings_input_antenna', color: 'text-indigo-500 bg-indigo-500/5' };
        case 'dsl': return { label: 'خط سلكي (DSL)', icon: 'GitBranch', color: 'text-amber-500 bg-amber-500/5' };
        default: return { label: 'بروتوكول افتراضي', icon: 'router', color: 'text-slate-500 bg-slate-500/5' };
    }
};

</script>

<template>
    <InstitutionalLayout title="كتالوج الخدمات">
        <Head title="باقات البرودباند (PPPoE) - MadaaQ" />

        <div class="max-w-7xl mx-auto pb-24 text-right px-4" dir="rtl">
            <!-- Institutional Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-10 mb-16 flex-row-reverse text-right">
                <div class="text-right">
                    <h1 class="text-4xl font-black text-primary tracking-tight mb-2">تعريف باقات النطاق العريض (Broadband Tiers)</h1>
                    <div class="flex items-center gap-4 justify-end">
                        <p class="text-slate-500 font-bold text-sm uppercase tracking-wider">حوكمة مستويات الخدمة، ضبط السعات القصوى، وتحديد التعرفات المالية لبروتوكول <span class="text-primary">PPPoE</span></p>
                        <span class="material-symbols-outlined text-[24px] text-primary">inventory_2</span>
                    </div>
                </div>
                <Link 
                    :href="route('broadband.profiles.create')" 
                    class="px-12 py-5 bg-primary text-white rounded-xl font-black text-xs uppercase tracking-[0.2em] shadow-2xl shadow-primary/20 hover:bg-emerald-600 transition-all active:scale-95 flex items-center gap-4 border border-white/10"
                >
                    <span class="material-symbols-outlined text-[24px]">add_box</span> تأسيس فئة خدمة جديدة
                </Link>
            </div>

            <!-- Service Tier Ledger Table -->
            <div class="surface-card rounded-3xl overflow-hidden shadow-2xl border border-outline-variant/5 bg-white">
                <div class="overflow-x-auto">
                    <table class="w-full text-right border-separate border-spacing-0">
                        <thead>
                            <tr class="bg-slate-950 text-white border-b border-white/5">
                                <th class="px-10 py-6 text-[11px] font-black uppercase tracking-[0.2em] text-white/40">فئة الخدمة (Tier Identity)</th>
                                <th class="px-10 py-6 text-[11px] font-black uppercase tracking-[0.2em] text-center text-white/40">إعدادات السرعة المتزامنة</th>
                                <th class="px-10 py-6 text-[11px] font-black uppercase tracking-[0.2em] text-center text-white/40 border-r border-white/5">التعرفة الاستحقاقية</th>
                                <th class="px-10 py-6 text-[11px] font-black uppercase tracking-[0.2em] text-center text-white/40">سعة البيانات (Quota)</th>
                                <th class="px-10 py-6 w-48"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant/5">
                            <tr v-for="profile in profiles" :key="profile.id" class="group hover:bg-surface-container-low/50 transition-all duration-300">
                                <td class="px-10 py-8">
                                    <div class="flex items-center gap-8 justify-end text-right">
                                        <div>
                                            <h4 class="text-xl font-black text-primary leading-tight group-hover:translate-x-1 transition-transform">{{ profile.name }}</h4>
                                            <div class="flex items-center gap-3 mt-2 opacity-50 justify-end">
                                                <p class="text-[10px] font-black uppercase tracking-widest leading-none">{{ getTechDetails(profile.technology_type).label }}</p>
                                                <span class="material-symbols-outlined text-[16px]">{{ getTechDetails(profile.technology_type).icon }}</span>
                                            </div>
                                        </div>
                                        <div class="w-16 h-16 rounded-2xl border border-outline-variant/10 flex items-center justify-center text-primary shrink-0 group-hover:bg-primary group-hover:text-white shadow-inner transition-all overflow-hidden relative border-none shadow-2xl"
                                             :class="getTechDetails(profile.technology_type).color">
                                             <div class="absolute inset-0 bg-current opacity-10 group-hover:opacity-0 transition-opacity"></div>
                                            <span class="material-symbols-outlined text-[32px] relative z-10" style="font-variation-settings: 'FILL' 1">
                                                {{ getTechDetails(profile.technology_type).icon }}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-10 py-8">
                                    <div class="flex items-center justify-center gap-8 font-headline font-black">
                                        <div class="flex flex-col items-center gap-2">
                                            <div class="flex items-center gap-3 bg-emerald-500/5 px-4 py-1.5 rounded-xl border border-emerald-500/20">
                                                <span class="text-xl text-emerald-600 tracking-tighter">{{ profile.speed_down }}</span>
                                                <span class="material-symbols-outlined text-emerald-500 text-[20px]">download_for_offline</span>
                                            </div>
                                            <span class="text-[8px] text-slate-400 uppercase tracking-widest leading-none">تنزيل (Down)</span>
                                        </div>
                                        <div class="w-px h-10 bg-slate-100"></div>
                                        <div class="flex flex-col items-center gap-2">
                                            <div class="flex items-center gap-3 bg-indigo-500/5 px-4 py-1.5 rounded-xl border border-indigo-500/20">
                                                <span class="text-xl text-indigo-600 tracking-tighter">{{ profile.speed_up }}</span>
                                                <span class="material-symbols-outlined text-indigo-500 text-[20px]">upload_file</span>
                                            </div>
                                            <span class="text-[8px] text-slate-400 uppercase tracking-widest leading-none">رفع (Up)</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-10 py-8 text-center border-r border-outline-variant/5">
                                    <div class="inline-flex flex-col items-center gap-2">
                                        <div class="flex items-baseline gap-2">
                                            <span class="text-2xl font-black font-headline text-primary leading-none">{{ profile.price.toLocaleString() }}</span>
                                            <span class="text-[10px] text-slate-300 font-black uppercase">ل.س</span>
                                        </div>
                                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] leading-none">دورة: {{ profile.duration_days || 30 }} يوم</span>
                                    </div>
                                </td>
                                <td class="px-10 py-8 text-center">
                                    <div class="flex flex-col items-center gap-2">
                                        <div class="inline-flex items-center gap-3 px-4 py-1.5 bg-surface-container-low rounded-xl border border-outline-variant/5">
                                            <span class="text-[14px] font-headline font-black text-slate-700 uppercase tracking-tight">
                                                {{ profile.data_limit_mb ? (profile.data_limit_mb/1024).toFixed(0) + ' GB' : 'غير محدود' }}
                                            </span>
                                            <span class="material-symbols-outlined text-slate-400 text-[18px]">database</span>
                                        </div>
                                        <p class="text-[9px] font-black text-slate-300 uppercase tracking-widest leading-none">سعة الحزمة (Quota)</p>
                                    </div>
                                </td>
                                <td class="px-10 py-8">
                                    <div class="flex items-center justify-end gap-3 opacity-0 group-hover:opacity-100 transition-all">
                                         <Link 
                                            :href="route('broadband.users.index', { package_id: profile.id })"
                                            class="px-6 py-2.5 bg-white shadow-xl border border-outline-variant/10 rounded-xl text-[10px] font-black uppercase tracking-widest text-slate-500 hover:text-primary transition-all flex items-center gap-3 hover:scale-110 active:scale-90"
                                         >
                                            <span class="material-symbols-outlined text-[20px]">Users</span> سجل المشتركين
                                         </Link>
                                         <Link 
                                            :href="route('broadband.profiles.edit', profile.id)"
                                            class="w-12 h-12 bg-white shadow-xl border border-outline-variant/10 rounded-xl flex items-center justify-center text-slate-400 hover:text-amber-600 hover:scale-110 active:scale-90 transition-all"
                                         >
                                            <span class="material-symbols-outlined text-[24px]">design_services</span>
                                         </Link>
                                         <button 
                                            @click="deleteProfile(profile.id)"
                                            class="w-12 h-12 bg-white shadow-xl border border-outline-variant/10 rounded-xl flex items-center justify-center text-slate-400 hover:text-rose-600 hover:scale-110 active:scale-90 transition-all"
                                         >
                                            <span class="material-symbols-outlined text-[24px]">delete_sweep</span>
                                         </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Empty State Protocol -->
                <div v-if="profiles.length === 0" class="py-48 flex flex-col items-center gap-10 group/empty">
                    <div class="w-32 h-32 rounded-[2.5rem] bg-surface-container-low flex items-center justify-center text-slate-200 border border-outline-variant/5 shadow-2xl group-hover/empty:scale-110 transition-all duration-1000">
                        <span class="material-symbols-outlined text-[64px]" style="font-variation-settings: 'wght' 100">category</span>
                    </div>
                    <div class="text-center">
                        <h3 class="text-2xl font-black text-primary mb-3">دليل باقات البرودباند فارغ</h3>
                        <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.3em] max-w-sm leading-relaxed italic">لم يتم تعريف أي مستويات خدمة برمجية حالياً. يُسجى البدء بتأسيس الباقة الأولى للمنظومة.</p>
                    </div>
                    <Link :href="route('broadband.profiles.create')" class="px-12 py-5 bg-primary text-white rounded-xl font-black text-xs uppercase tracking-[0.2em] shadow-2xl hover:bg-emerald-600 transition-all active:scale-95 border border-white/10 flex items-center gap-4">
                        <span class="material-symbols-outlined text-[24px]">add_box</span> تأسيس أول فئة خدمة
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

