<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import InstitutionalLayout from '@/Layouts/InstitutionalLayout.vue';

const props = defineProps({
    staff: Object,
});

const deleteStaff = (id) => {
    if (confirm('هل أنت متأكد من رغبتك في سحب الصلاحيات وتوقيف هذا العضو من الكادر الإداري؟ هذا الإجراء سيمنع الوصول الفوري للمنظومة.')) {
        router.delete(route('staff.destroy', id), {
            preserveScroll: true,
        });
    }
};

const getRoleDetails = (roleName) => {
    const roles = {
        'super-admin': { label: 'مدير سيادي (Super Admin)', class: 'text-primary bg-primary/5 border-primary/20', icon: 'security' },
        'admin': { label: 'مدير منظومة (Admin)', class: 'text-indigo-600 bg-indigo-500/5 border-indigo-500/20', icon: 'admin_panel_settings' },
        'operator': { label: 'مشغل فني (Operator)', class: 'text-secondary bg-secondary/5 border-secondary/20', icon: 'settings_accessibility' },
        'support': { label: 'دعم لوجستي (Support)', class: 'text-emerald-600 bg-emerald-500/5 border-emerald-500/20', icon: 'support_agent' },
    };
    return roles[roleName.toLowerCase()] || { label: roleName, class: 'text-slate-500 bg-slate-500/5 border-slate-500/20', icon: 'person' };
};
</script>

<template>
    <InstitutionalLayout title="حوكمة الكادر">
        <Head title="إدارة الكادر الإداري - MadaaQ" />

        <div class="max-w-7xl mx-auto pb-24 text-right px-4" dir="rtl">
            <!-- Institutional Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-10 mb-16 flex-row-reverse text-right">
                <div class="text-right">
                    <h1 class="text-4xl font-black text-primary tracking-tight mb-2">سجلات الكادر الإداري (Staff Ledger)</h1>
                    <div class="flex items-center gap-4 justify-end">
                        <p class="text-slate-500 font-bold text-sm uppercase tracking-wider">إدارة هويات الوصول، حوكمة الصلاحيات، ومراقبة النشاط السيادي</p>
                        <span class="material-symbols-outlined text-[24px] text-primary">admin_panel_settings</span>
                    </div>
                </div>
                <Link 
                    :href="route('staff.create')" 
                    class="px-12 py-5 bg-primary text-white rounded-xl font-black text-xs uppercase tracking-[0.2em] shadow-2xl shadow-primary/20 hover:bg-emerald-600 transition-all active:scale-95 flex items-center gap-4 border border-white/10"
                >
                    <span class="material-symbols-outlined text-[24px]">person_add</span> تعيين عضو إداري جديد
                </Link>
            </div>

            <!-- Institutional Register (The Table) -->
            <div class="surface-card rounded-3xl overflow-hidden shadow-2xl border border-outline-variant/5 bg-white">
                <div class="overflow-x-auto">
                    <table class="w-full text-right border-separate border-spacing-0">
                        <thead>
                            <tr class="bg-slate-950 text-white border-b border-white/5">
                                <th class="px-10 py-6 text-[11px] font-black uppercase tracking-[0.2em] text-white/40">هوية العضو (Identity)</th>
                                <th class="px-10 py-6 text-[11px] font-black uppercase tracking-[0.2em] text-white/40">بروتوكول الصلاحيات</th>
                                <th class="px-10 py-6 text-[11px] font-black uppercase tracking-[0.2em] text-center text-white/40">بيانات التواصل</th>
                                <th class="px-10 py-6 text-[11px] font-black uppercase tracking-[0.2em] text-center text-white/40">وضعية الوصول</th>
                                <th class="px-10 py-6 w-48"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant/5">
                            <tr v-for="member in staff.data" :key="member.id" class="group hover:bg-surface-container-low/50 transition-all duration-300">
                                <td class="px-10 py-8">
                                    <div class="flex items-center gap-8 justify-end text-right">
                                        <div>
                                            <h4 class="text-lg font-black text-primary leading-tight group-hover:translate-x-1 transition-transform">{{ member.name }}</h4>
                                            <div class="flex items-center gap-2 mt-2 opacity-50 justify-end">
                                                <p class="text-[10px] font-black uppercase tracking-widest leading-none">{{ member.position || 'موظف عمليات مركزية' }}</p>
                                                <span class="material-symbols-outlined text-[14px]">shield_person</span>
                                            </div>
                                        </div>
                                        <div class="w-16 h-16 rounded-2xl bg-surface-container-low border border-outline-variant/10 flex items-center justify-center text-primary shrink-0 group-hover:bg-primary group-hover:text-white shadow-inner transition-all font-black text-2xl overflow-hidden relative">
                                            <div class="absolute inset-0 bg-primary opacity-5 group-hover:opacity-0 transition-opacity"></div>
                                            <span class="relative z-10">{{ member.name.substring(0, 1) }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-10 py-8">
                                    <div class="flex flex-wrap gap-2 justify-end">
                                        <span 
                                            v-for="role in member.roles" 
                                            :key="role.id"
                                            :class="['px-5 py-2 border-2 rounded-xl text-[10px] font-black uppercase tracking-widest flex items-center gap-3 shadow-sm transition-all group-hover:scale-105', getRoleDetails(role.name).class]"
                                        >
                                             <span class="material-symbols-outlined text-[18px]">{{ getRoleDetails(role.name).icon }}</span> 
                                             {{ getRoleDetails(role.name).label }}
                                        </span>
                                        <span v-if="member.roles.length === 0" class="text-[10px] font-black text-rose-500 uppercase tracking-widest bg-rose-500/5 px-5 py-2 rounded-xl border-2 border-rose-500/20 flex items-center gap-2">
                                            <span class="material-symbols-outlined text-[18px]">no_accounts</span> غير مصرح
                                        </span>
                                    </div>
                                </td>
                                <td class="px-10 py-8 text-center text-right">
                                    <div class="flex flex-col items-center gap-2">
                                        <span class="text-[12px] font-headline font-black text-primary tracking-tight bg-surface-container-low px-4 py-1 rounded-lg border border-outline-variant/5">{{ member.email }}</span>
                                        <div class="flex items-center gap-2 opacity-40">
                                            <span class="text-[10px] font-headline font-black tracking-widest">{{ member.phone || 'GHOST_ID' }}</span>
                                            <span class="material-symbols-outlined text-[14px]">call</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-10 py-8 text-center">
                                    <div :class="[
                                        'inline-flex items-center justify-center gap-3 px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-[0.2em] border-2 transition-all shadow-sm group-hover:scale-105',
                                        member.is_active ? 'bg-emerald-500/10 text-emerald-600 border-emerald-500/20' : 'bg-rose-500/10 text-rose-500 border-rose-500/20'
                                    ]">
                                        <span class="relative flex h-2.5 w-2.5">
                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-current opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-current"></span>
                                        </span>
                                        {{ member.is_active ? 'نشط (Online)' : 'معطل (Deactivated)' }}
                                    </div>
                                </td>
                                <td class="px-10 py-8 text-left">
                                    <div class="flex items-center justify-end gap-3 opacity-0 group-hover:opacity-100 transition-all">
                                         <Link 
                                            :href="route('staff.edit', member.id)"
                                            class="w-12 h-12 bg-white shadow-xl border border-outline-variant/10 rounded-xl flex items-center justify-center text-slate-400 hover:text-primary hover:scale-110 active:scale-90 transition-all"
                                         >
                                            <span class="material-symbols-outlined text-[24px]">design_services</span>
                                         </Link>
                                         <button 
                                            @click="deleteStaff(member.id)"
                                            class="w-12 h-12 bg-white shadow-xl border border-outline-variant/10 rounded-xl flex items-center justify-center text-slate-400 hover:text-rose-600 hover:scale-110 active:scale-90 transition-all"
                                         >
                                            <span class="material-symbols-outlined text-[24px]">person_remove</span>
                                         </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Empty State Protocol -->
                <div v-if="staff.data.length === 0" class="py-48 flex flex-col items-center gap-10 group/empty">
                    <div class="w-32 h-32 rounded-[2.5rem] bg-surface-container-low flex items-center justify-center text-slate-200 border border-outline-variant/5 shadow-2xl group-hover/empty:scale-110 transition-all duration-1000">
                        <span class="material-symbols-outlined text-[64px]" style="font-variation-settings: 'wght' 100">group_off</span>
                    </div>
                    <div class="text-center">
                        <h3 class="text-2xl font-black text-primary mb-3">سجل الكادر فارغ حالياً</h3>
                        <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.3em] max-w-sm leading-relaxed italic">لم يتم تعيين أي أعضاء إداريين في المنظومة. يُرجى البدء بتعريف الكادر الإداري.</p>
                    </div>
                    <Link :href="route('staff.create')" class="px-12 py-5 bg-primary text-white rounded-xl font-black text-xs uppercase tracking-[0.2em] shadow-2xl hover:bg-emerald-600 transition-all active:scale-95 border border-white/10 flex items-center gap-4">
                        <span class="material-symbols-outlined text-[24px]">person_add</span> تعيين أول مـدير
                    </Link>
                </div>

                <!-- Pagination (Command Center Look) -->
                <div v-if="staff.links.length > 3" class="px-10 py-8 bg-slate-950 border-t border-white/5 flex justify-between items-center flex-row-reverse">
                    <div class="text-[10px] font-black text-white/30 uppercase tracking-[0.3em] font-headline">Administrative Access Control Protocol</div>
                    <nav class="flex gap-4">
                        <Link 
                            v-for="(link, k) in staff.links" 
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
