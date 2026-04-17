<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import InstitutionalLayout from '@/Layouts/InstitutionalLayout.vue';

const props = defineProps({
    staff: Object,
});

const deleteStaff = (id) => {
    if (confirm('تأكيد سحب كافة صلاحيات العبور وتجميد الهوية السيادية لهذا العضو؟ سيتم الإغلاق الفوري لكافة منافذ الحوكمة المرتبطة.')) {
        router.delete(route('staff.destroy', id), {
            preserveScroll: true,
        });
    }
};

const getRoleDetails = (roleName) => {
    const roles = {
        'super-admin': { label: 'المدير السيادي المطلق (Super)', class: 'text-primary bg-primary/5 border-primary/20 shadow-primary/10', icon: 'security' },
        'admin': { label: 'مدير العمليات المركزية (Admin)', class: 'text-indigo-600 bg-indigo-500/5 border-indigo-500/20 shadow-indigo-500/10', icon: 'admin_panel_settings' },
        'operator': { label: 'مشغل المصفوفة الفنية (Operator)', class: 'text-emerald-600 bg-emerald-500/5 border-emerald-500/20 shadow-emerald-500/10', icon: 'settings_accessibility' },
        'support': { label: 'وحدة الدعم اللوجستي (Support)', class: 'text-amber-600 bg-amber-500/5 border-amber-500/20 shadow-amber-500/10', icon: 'support_agent' },
    };
    return roles[roleName.toLowerCase()] || { label: roleName, class: 'text-slate-500 bg-slate-500/5 border-slate-500/20', icon: 'person' };
};
</script>

<template>
    <InstitutionalLayout title="حوكمة الكادر السيادي">
        <Head title="سجلات الكادر السيادي (Personnel Registry) - MadaaQ" />

        <div class="max-w-7xl mx-auto pb-24 text-right px-4" dir="rtl">
            <!-- Institutional Command Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-12 mb-16 flex-row-reverse text-right">
                <div class="text-right">
                    <h1 class="text-5xl font-black text-primary tracking-tighter mb-3 uppercase">سجلات الكادر السيادي (Personnel Registry)</h1>
                    <div class="flex items-center gap-6 justify-end">
                        <p class="text-slate-500 font-bold text-sm uppercase tracking-[0.2em]">إدارة هويات العبور، حوكمة مستويات الصلاحية، ومراقبة النشاط الإداري</p>
                        <span class="flex h-4 w-4 relative">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-secondary opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-4 w-4 bg-secondary"></span>
                        </span>
                    </div>
                </div>
                <Link 
                    :href="route('staff.create')" 
                    class="px-14 py-6 bg-slate-950 text-white rounded-[1.5rem] font-black text-[11px] uppercase tracking-[0.3em] shadow-2xl hover:bg-primary transition-all active:scale-95 flex items-center gap-5 border border-white/10 group"
                >
                    <span class="material-symbols-outlined text-[28px] group-hover:rotate-180 transition-transform">person_add</span>
                    تعيين هوية جديدة (Inject Personnel)
                </Link>
            </div>

            <!-- Global Identity Register Ledger -->
            <div class="surface-card rounded-[2.5rem] overflow-hidden shadow-2xl border border-outline-variant/10 bg-white relative">
                 <div class="absolute inset-0 bg-grid-slate-50 [mask-image:linear-gradient(0deg,white,rgba(255,255,255,0.6))] pointer-events-none opacity-20"></div>
                
                <div class="overflow-x-auto relative z-10">
                    <table class="w-full text-right border-separate border-spacing-0">
                        <thead>
                            <tr class="bg-slate-950 text-white border-b border-white/5 uppercase">
                                <th class="px-12 py-8 text-[11px] font-black uppercase tracking-[0.3em] text-white/30">هوية العضو (Personnel Identity)</th>
                                <th class="px-10 py-8 text-[11px] font-black uppercase tracking-[0.3em] text-white/30 text-center">أذونات المصفوفة</th>
                                <th class="px-10 py-8 text-[11px] font-black uppercase tracking-[0.3em] text-center text-white/30 border-r border-white/5">قنوات التواصل السيادية</th>
                                <th class="px-10 py-8 text-[11px] font-black uppercase tracking-[0.3em] text-center text-white/30">وضعية العبور</th>
                                <th class="px-10 py-8 w-48"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant/5">
                            <tr v-for="member in staff.data" :key="member.id" class="group hover:bg-slate-50/80 transition-all duration-500">
                                <td class="px-12 py-10">
                                    <div class="flex items-center gap-10 justify-end text-right">
                                        <div>
                                            <h4 class="text-2xl font-black text-primary leading-tight group-hover:translate-x-3 transition-transform tracking-tight uppercase">{{ member.name }}</h4>
                                            <div class="flex items-center gap-3 mt-3 opacity-40 justify-end">
                                                <p class="text-[10px] font-black uppercase tracking-[0.2em] leading-none">{{ member.position || 'عامل في المصفوفة المركزية' }}</p>
                                                <span class="material-symbols-outlined text-[18px]">verified_user</span>
                                            </div>
                                        </div>
                                        <div class="w-20 h-20 rounded-[1.5rem] bg-slate-950 text-white flex items-center justify-center shadow-2xl group-hover:bg-primary group-hover:scale-110 group-hover:rotate-6 transition-all duration-700 relative overflow-hidden shrink-0 border border-white/10 font-black text-3xl">
                                             <div class="absolute inset-x-0 bottom-0 h-1/2 bg-gradient-to-t from-black/20 to-transparent"></div>
                                             <span class="relative z-10 font-headline">{{ member.name.substring(0, 1) }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-10 py-10">
                                    <div class="flex flex-wrap gap-4 justify-center">
                                        <span 
                                            v-for="role in member.roles" 
                                            :key="role.id"
                                            :class="['px-6 py-3 border-2 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] flex items-center gap-4 shadow-xl transition-all group-hover:scale-105', getRoleDetails(role.name).class]"
                                        >
                                             <span class="material-symbols-outlined text-[20px]">{{ getRoleDetails(role.name).icon }}</span> 
                                             {{ getRoleDetails(role.name).label }}
                                        </span>
                                        <span v-if="member.roles.length === 0" class="text-[10px] font-black text-rose-500 uppercase tracking-[0.2em] bg-rose-500/5 px-6 py-3 rounded-2xl border-2 border-rose-500/20 flex items-center gap-4">
                                            <span class="material-symbols-outlined text-[20px]">block</span> هويـة غير مصرحة
                                        </span>
                                    </div>
                                </td>
                                <td class="px-10 py-10 text-center border-r border-outline-variant/5">
                                    <div class="flex flex-col items-center gap-4">
                                        <span class="text-[14px] font-headline font-black text-primary tracking-widest bg-slate-100 px-6 py-2 rounded-xl group-hover:bg-slate-950 group-hover:text-white transition-all shadow-inner">{{ member.email }}</span>
                                        <div class="flex items-center gap-3 opacity-30">
                                            <span class="text-[10px] font-headline font-black tracking-[0.3em]">{{ member.phone || 'HIDDEN_CONTACT' }}</span>
                                            <span class="material-symbols-outlined text-[16px]">contact_phone</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-10 py-10 text-center">
                                    <div :class="[
                                        'inline-flex items-center justify-center gap-4 px-8 py-3.5 rounded-2xl text-[10px] font-black uppercase tracking-[0.3em] border-2 transition-all shadow-2xl group-hover:translate-y-[-4px]',
                                        member.is_active ? 'bg-emerald-500/10 text-emerald-600 border-emerald-500/20 shadow-emerald-500/10' : 'bg-rose-500/10 text-rose-500 border-rose-500/20 shadow-rose-500/10'
                                    ]">
                                        <span class="relative flex h-3 w-3">
                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-current opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-3 w-3 bg-current"></span>
                                        </span>
                                        {{ member.is_active ? 'صلاحيات نشطة (Active)' : 'وصول مجمد (Locked)' }}
                                    </div>
                                </td>
                                <td class="px-10 py-10">
                                    <div class="flex items-center justify-end gap-3 opacity-0 group-hover:opacity-100 transition-all translate-x-4 group-hover:translate-x-0">
                                         <Link 
                                            :href="route('staff.edit', member.id)"
                                            class="w-14 h-14 bg-white shadow-2xl border border-outline-variant/10 rounded-2xl flex items-center justify-center text-slate-400 hover:text-primary hover:scale-110 active:scale-90 transition-all group/icon"
                                         >
                                            <span class="material-symbols-outlined text-[28px] group-hover/icon:rotate-12 transition-transform" style="font-variation-settings: 'wght' 700">edit_note</span>
                                         </Link>
                                         <button 
                                            @click="deleteStaff(member.id)"
                                            class="w-14 h-14 bg-white shadow-2xl border border-outline-variant/10 rounded-2xl flex items-center justify-center text-slate-400 hover:text-rose-600 hover:scale-110 active:scale-90 transition-all group/icon"
                                         >
                                            <span class="material-symbols-outlined text-[28px] group-hover/icon:scale-75 transition-transform" style="font-variation-settings: 'wght' 700">person_off</span>
                                         </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Empty Personnel Protocol -->
                <div v-if="staff.data.length === 0" class="py-64 flex flex-col items-center gap-12 group/empty relative z-10">
                    <div class="w-48 h-48 rounded-[3.5rem] bg-slate-950 text-white flex items-center justify-center border-8 border-white shadow-[0_0_80px_rgba(2,6,23,0.15)] group-hover/empty:scale-110 transition-all duration-1000 relative">
                        <div class="absolute inset-0 bg-primary opacity-20 blur-3xl animate-pulse"></div>
                        <span class="material-symbols-outlined text-[80px] relative z-10" style="font-variation-settings: 'wght' 100">group_off</span>
                    </div>
                    <div class="text-center">
                        <h3 class="text-4xl font-black text-primary mb-6 tracking-tighter uppercase leading-none">مصفوفة الكادر فارغة (Null Personnel)</h3>
                        <p class="text-[12px] font-black text-slate-400 uppercase tracking-[0.4em] max-w-sm leading-relaxed italic opacity-70">لم يتم تعيين أي هويات إدارية ضمن المصفوفة السيادية. ابدأ بروتوكول Inject Personnel الأول.</p>
                    </div>
                    <Link :href="route('staff.create')" class="px-16 py-8 bg-primary text-white rounded-3xl font-black text-xs uppercase tracking-[0.4em] shadow-[0_30px_60px_rgba(37,99,235,0.25)] hover:bg-emerald-600 hover:-translate-y-2 transition-all active:scale-95 border border-white/10 flex items-center gap-6">
                        <span class="material-symbols-outlined text-[32px]">person_add</span> تعيين أول هويـة
                    </Link>
                </div>

                <!-- Tactical Pagination Oversight -->
                <div v-if="staff.links.length > 3" class="px-12 py-10 bg-slate-950 border-t border-white/5 flex justify-between items-center flex-row-reverse relative z-10 overflow-hidden">
                    <div class="absolute inset-0 bg-grid-slate-50 opacity-5 pointer-events-none"></div>
                    <div class="text-[10px] font-black text-white/20 uppercase tracking-[0.5em] font-headline relative z-10">Administrative Access Protocol Overload Registry</div>
                    <nav class="flex gap-5 relative z-10">
                        <Link 
                            v-for="(link, k) in staff.links" 
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
.no-scrollbar::-webkit-scrollbar { display: none; }
.no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
