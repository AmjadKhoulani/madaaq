<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import InstitutionalLayout from '@/Layouts/InstitutionalLayout.vue';

const props = defineProps({
    roles: Object,
});

const deleteRole = (id) => {
    if (confirm('تأكيد إبطال مصفوفة الصلاحيات هذه؟ سيؤدي هذا الإجراء إلى تجميد وصول كافة الأعضاء المرتبطين بهذا الدور برمجياً.')) {
        router.delete(route('roles.destroy', id));
    }
};

</script>

<template>
    <InstitutionalLayout title="مصفوفة حوكمة الأدوار">
        <Head title="مصفوفة حوكمة الصلاحيات (Authority Matrix) - MadaaQ" />

        <div class="max-w-7xl mx-auto pb-24 text-right px-4" dir="rtl">
            <!-- Strategic Intelligence Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-12 mb-16 flex-row-reverse text-right">
                <div class="text-right">
                    <h1 class="text-5xl font-black text-primary tracking-tighter mb-3 uppercase">مصفوفة حوكمة الأدوار (Authority Matrix)</h1>
                    <div class="flex items-center gap-6 justify-end">
                        <p class="text-slate-500 font-bold text-sm uppercase tracking-[0.2em]">تحديد مستويات النفاذ، إدارة بروتوكولات الأذونات، وحوكمة الامتيازات السيادية</p>
                        <span class="flex h-4 w-4 relative">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-secondary opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-4 w-4 bg-secondary"></span>
                        </span>
                    </div>
                </div>
                <Link 
                    :href="route('roles.create')" 
                    class="px-14 py-6 bg-slate-950 text-white rounded-[1.5rem] font-black text-[11px] uppercase tracking-[0.3em] shadow-2xl hover:bg-primary transition-all active:scale-95 flex items-center gap-5 border border-white/10 group"
                >
                    <span class="material-symbols-outlined text-[28px] group-hover:rotate-180 transition-transform">add_moderator</span>
                    إنشاء دور سيادي (New Role)
                </Link>
            </div>

            <!-- Role Matrix Ledger -->
            <div class="surface-card rounded-[2.5rem] overflow-hidden shadow-2xl border border-outline-variant/10 bg-white relative">
                 <div class="absolute inset-0 bg-grid-slate-50 [mask-image:linear-gradient(0deg,white,rgba(255,255,255,0.6))] pointer-events-none opacity-20"></div>
                
                <div class="overflow-x-auto relative z-10">
                    <table class="w-full text-right border-separate border-spacing-0">
                        <thead>
                            <tr class="bg-slate-950 text-white border-b border-white/5 uppercase">
                                <th class="px-12 py-8 text-[11px] font-black uppercase tracking-[0.3em] text-white/30">تصنيف الدور (Role Taxonomy)</th>
                                <th class="px-10 py-8 text-[11px] font-black uppercase tracking-[0.3em] text-white/30">المُعرف البرمجي</th>
                                <th class="px-10 py-8 text-[11px] font-black uppercase tracking-[0.3em] text-center text-white/30 border-r border-white/5">الأعضاء النشطون</th>
                                <th class="px-10 py-8 w-48"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant/5">
                            <tr v-for="role in roles.data" :key="role.id" class="group hover:bg-slate-50/80 transition-all duration-500">
                                <td class="px-12 py-10">
                                    <div class="flex items-center gap-10 justify-end text-right">
                                        <div>
                                            <h4 class="text-2xl font-black text-primary leading-tight group-hover:translate-x-3 transition-transform tracking-tight uppercase">{{ role.display_name }}</h4>
                                            <p class="text-[10px] font-black text-slate-400 mt-3 uppercase tracking-[0.2em] opacity-60 leading-relaxed max-w-sm truncate">{{ role.description || 'بروتوكول أذونات عام ضمن مصفوفة مداء المركزية' }}</p>
                                        </div>
                                        <div class="w-20 h-20 rounded-[1.5rem] bg-slate-950 text-white flex items-center justify-center shadow-2xl group-hover:bg-primary group-hover:scale-110 group-hover:rotate-6 transition-all duration-700 relative overflow-hidden shrink-0 border border-white/10">
                                             <div class="absolute inset-x-0 bottom-0 h-1/2 bg-gradient-to-t from-black/20 to-transparent"></div>
                                             <span class="material-symbols-outlined text-[36px] relative z-10" style="font-variation-settings: 'FILL' 1; font-weight: 200;">shield_person</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-10 py-10">
                                    <span class="px-6 py-3 bg-slate-100 border border-slate-200 rounded-2xl text-[10px] font-black font-headline text-primary uppercase tracking-[0.2em] shadow-inner group-hover:bg-slate-950 group-hover:text-white transition-all">
                                        {{ role.name }}
                                    </span>
                                </td>
                                <td class="px-10 py-10 text-center border-r border-outline-variant/5">
                                    <div class="inline-flex items-center gap-4 px-8 py-4 bg-white border-2 border-slate-200 rounded-[1.5rem] shadow-xl group-hover:border-primary transition-all">
                                        <span class="material-symbols-outlined text-primary text-[24px]">groups</span>
                                        <span class="text-2xl font-headline font-black text-primary leading-none">{{ role.users_count }}</span>
                                    </div>
                                </td>
                                <td class="px-10 py-10">
                                    <div class="flex items-center justify-end gap-3 opacity-0 group-hover:opacity-100 transition-all translate-x-4 group-hover:translate-x-0">
                                         <Link 
                                            :href="route('roles.edit', role.id)" 
                                            class="w-14 h-14 bg-white shadow-2xl border border-outline-variant/10 rounded-2xl flex items-center justify-center text-slate-400 hover:text-primary hover:scale-110 active:scale-90 transition-all group/icon"
                                         >
                                            <span class="material-symbols-outlined text-[28px] group-hover/icon:rotate-12 transition-transform" style="font-variation-settings: 'wght' 700">rule_settings</span>
                                         </Link>
                                         <button 
                                            @click="deleteRole(role.id)"
                                            class="w-14 h-14 bg-white shadow-2xl border border-outline-variant/10 rounded-2xl flex items-center justify-center text-slate-400 hover:text-rose-600 hover:scale-110 active:scale-90 transition-all group/icon"
                                         >
                                            <span class="material-symbols-outlined text-[28px] group-hover/icon:scale-75 transition-transform" style="font-variation-settings: 'wght' 700">key_off</span>
                                         </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Empty Matrix Protocol -->
                <div v-if="roles.data.length === 0" class="py-64 flex flex-col items-center gap-12 group/empty relative z-10">
                    <div class="w-48 h-48 rounded-[3.5rem] bg-slate-950 text-white flex items-center justify-center border-8 border-white shadow-[0_0_80px_rgba(2,6,23,0.15)] group-hover/empty:scale-110 transition-all duration-1000 relative">
                        <div class="absolute inset-0 bg-primary opacity-20 blur-3xl animate-pulse"></div>
                        <span class="material-symbols-outlined text-[80px] relative z-10" style="font-variation-settings: 'wght' 100">lock_open</span>
                    </div>
                    <div class="text-center">
                        <h3 class="text-4xl font-black text-primary mb-6 tracking-tighter uppercase leading-none">مصفوفة الصلاحيات فارغة (Null Matrix)</h3>
                        <p class="text-[12px] font-black text-slate-400 uppercase tracking-[0.4em] max-w-sm leading-relaxed italic opacity-70">لم يتم تعريف أي مستويات نفاذ ضمن مصفوفة الحوكمة. ابدأ بروتوكول Inject Role الأول.</p>
                    </div>
                    <Link :href="route('roles.create')" class="px-16 py-8 bg-primary text-white rounded-3xl font-black text-xs uppercase tracking-[0.4em] shadow-[0_30px_60px_rgba(37,99,235,0.25)] hover:bg-emerald-600 hover:-translate-y-2 transition-all active:scale-95 border border-white/10 flex items-center gap-6">
                        <span class="material-symbols-outlined text-[32px]">security</span> تعريف أول دور
                    </Link>
                </div>

                <!-- Strategic Pagination Oversight -->
                <div v-if="roles.links.length > 3" class="px-12 py-10 bg-slate-950 border-t border-white/5 flex justify-between items-center flex-row-reverse relative z-10 overflow-hidden">
                    <div class="absolute inset-0 bg-grid-slate-50 opacity-5 pointer-events-none"></div>
                    <div class="text-[10px] font-black text-white/20 uppercase tracking-[0.5em] font-headline relative z-10">PRIVILEGE_DISTRIBUTION_MAP_LEGACY_01</div>
                    <nav class="flex gap-5 relative z-10">
                        <Link 
                            v-for="(link, k) in roles.links" 
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
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/xml' viewBox='0 0 32 32' width='32' height='32' fill='none' stroke='rgb(15 23 42 / 0.05)'%3E%3Cpath d='M0 .5H31.5V32'/%3E%3C/svg%3E");
}
.no-scrollbar::-webkit-scrollbar { display: none; }
.no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
