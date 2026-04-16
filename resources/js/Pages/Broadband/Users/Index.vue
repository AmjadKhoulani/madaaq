<script setup>
import { ref, computed } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import InstitutionalLayout from '@/Layouts/InstitutionalLayout.vue';

const props = defineProps({
    users: Array,
});

const searchQuery = ref('');

const filteredUsers = computed(() => {
    if (!searchQuery.value) return props.users;
    const query = searchQuery.value.toLowerCase();
    return props.users.filter(user => 
        user.username.toLowerCase().includes(query) || 
        user.name?.toLowerCase().includes(query) ||
        user.ip?.toLowerCase().includes(query)
    );
});

const deleteUser = (id) => {
    if (confirm('تأكيد إخراج حساب البرودباند من الخدمة؟ سيؤدي هذا الإجراء إلى حذف كافة بيانات الربط (Secrets) من السيرفر المركزي بشكل نهائي.')) {
        router.delete(route('broadband.users.destroy', id));
    }
};

const getStatusDetails = (status) => {
    switch (status) {
        case 'active': return { label: 'بث حي (Active)', class: 'bg-emerald-500/10 text-emerald-600 border-emerald-500/20', icon: 'check_circle' };
        case 'expired': return { label: 'منتهي الصلاحية', class: 'bg-rose-500/10 text-rose-500 border-rose-500/20', icon: 'history' };
        case 'disabled': return { label: 'معطل إدارياً', class: 'bg-slate-500/10 text-slate-500 border-slate-500/20', icon: 'block' };
        default: return { label: 'حالة غير معرفة', class: 'bg-amber-500/10 text-amber-600 border-amber-500/20', icon: 'help_center' };
    }
};

</script>

<template>
    <InstitutionalLayout title="سجل اشتراكات البرودباند">
        <Head title="إدارة حسابات البرودباند (PPPoE) - MadaaQ" />

        <div class="max-w-7xl mx-auto pb-24 text-right px-4" dir="rtl">
            <!-- Institutional Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-10 mb-16 flex-row-reverse text-right">
                <div class="text-right">
                    <h1 class="text-4xl font-black text-primary tracking-tight mb-2">إدارة اشتراكات النطاق العريض (Broadband CRM)</h1>
                    <div class="flex items-center gap-4 justify-end">
                        <p class="text-slate-500 font-bold text-sm uppercase tracking-wider">حوكمة الربط عبر بروتوكول <span class="text-primary">PPPoE</span>، تتبع الجلسات التشغيلية، وإدارة السعات المخصصة</p>
                        <span class="flex h-3 w-3 relative">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-secondary opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-secondary"></span>
                        </span>
                    </div>
                </div>
                <div class="flex items-center gap-6">
                    <div class="relative group hidden lg:block">
                        <span class="material-symbols-outlined absolute right-5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary transition-colors text-[24px]">search</span>
                        <input v-model="searchQuery" type="text" placeholder="البحث في هوية المشتركين..." class="form-input-monolith pr-14 h-16 w-96 text-sm font-bold">
                    </div>
                    <Link 
                        :href="route('broadband.users.create')" 
                        class="px-12 py-5 bg-primary text-white rounded-xl font-black text-xs uppercase tracking-[0.2em] shadow-2xl shadow-primary/20 hover:bg-emerald-600 transition-all active:scale-95 flex items-center gap-4 border border-white/10"
                    >
                        <span class="material-symbols-outlined text-[24px]">person_add</span> تفعيل اشتراك PPPoE
                    </Link>
                </div>
            </div>

            <!-- Registry Ledger Table -->
            <div class="surface-card rounded-3xl overflow-hidden shadow-2xl border border-outline-variant/5 bg-white">
                <div class="overflow-x-auto">
                    <table class="w-full text-right border-separate border-spacing-0">
                        <thead>
                            <tr class="bg-slate-950 text-white border-b border-white/5">
                                <th class="px-10 py-6 text-[11px] font-black uppercase tracking-[0.2em] text-white/40">هوية المشترك (PPPoE Auth)</th>
                                <th class="px-10 py-6 text-[11px] font-black uppercase tracking-[0.2em] text-white/40">بروتوكول الربط الفني</th>
                                <th class="px-10 py-6 text-[11px] font-black uppercase tracking-[0.2em] text-center text-white/40 border-r border-white/5">خطة السرعة المخططة</th>
                                <th class="px-10 py-6 text-[11px] font-black uppercase tracking-[0.2em] text-center text-white/40">وضعية الشبكة</th>
                                <th class="px-10 py-6 w-48"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant/5">
                            <tr v-for="user in filteredUsers" :key="user.id" class="group hover:bg-surface-container-low/50 transition-all duration-300">
                                <td class="px-10 py-8">
                                    <div class="flex items-center gap-8 justify-end text-right">
                                        <div>
                                            <h4 class="text-xl font-black text-primary leading-tight group-hover:translate-x-1 transition-transform font-headline">{{ user.username }}</h4>
                                            <div class="flex items-center gap-2 mt-2 opacity-50 justify-end">
                                                <p class="text-[10px] font-black uppercase tracking-widest leading-none">{{ user.name || 'هوية غير معرفة حضرياً' }}</p>
                                                <span class="material-symbols-outlined text-[16px]">account_circle</span>
                                            </div>
                                        </div>
                                        <div class="w-16 h-16 rounded-2xl bg-surface-container-low border border-outline-variant/10 flex items-center justify-center text-primary shrink-0 group-hover:bg-primary group-hover:text-white shadow-inner transition-all overflow-hidden relative border-none shadow-2xl">
                                             <div class="absolute inset-0 bg-primary opacity-5 group-hover:opacity-0 transition-opacity"></div>
                                            <span class="material-symbols-outlined text-[32px] relative z-10" style="font-variation-settings: 'FILL' 1">vpn_lock</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-10 py-8 text-right">
                                    <div class="flex flex-col gap-3">
                                        <div class="flex items-center gap-3 justify-end">
                                            <span class="text-[11px] font-black text-primary uppercase tracking-[0.1em]">{{ user.tower?.name || 'موقع توزيع محلي' }}</span>
                                            <div class="w-8 h-8 rounded-lg bg-surface-container-low flex items-center justify-center border border-outline-variant/5">
                                                <span class="material-symbols-outlined text-slate-400 text-[18px]">cell_tower</span>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-3 justify-end">
                                            <span class="text-[11px] font-headline font-black text-slate-400 bg-slate-50 px-3 py-1 rounded-lg border border-slate-200">{{ user.ip || 'PROTO_DYNAMIC' }}</span>
                                            <div class="w-8 h-8 rounded-lg bg-surface-container-low flex items-center justify-center border border-outline-variant/5">
                                                <span class="material-symbols-outlined text-slate-400 text-[18px]">lan</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-10 py-8 text-center border-r border-outline-variant/5">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="px-6 py-2.5 bg-slate-950 text-white border border-white/10 rounded-2xl flex items-center gap-6 shadow-2xl group-hover:rotate-1 transition-transform">
                                            <div class="flex items-center gap-3">
                                                <span class="material-symbols-outlined text-emerald-500 text-[20px]">download_for_offline</span>
                                                <span class="text-lg font-headline font-black tracking-tighter">{{ user.package?.speed_down || 0 }} <span class="text-[8px] opacity-40 uppercase">Mbit</span></span>
                                            </div>
                                            <div class="w-px h-6 bg-white/10"></div>
                                            <div class="flex items-center gap-3">
                                                <span class="text-lg font-headline font-black tracking-tighter">{{ user.package?.speed_up || 0 }} <span class="text-[8px] opacity-40 uppercase">Mbit</span></span>
                                                <span class="material-symbols-outlined text-amber-500 text-[20px]">upload_file</span>
                                            </div>
                                        </div>
                                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] leading-none">{{ user.package?.name || 'سياسة مخصصة' }}</p>
                                    </div>
                                </td>
                                <td class="px-10 py-8 text-center text-right">
                                    <div :class="[
                                        'inline-flex items-center justify-center gap-3 px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-[0.2em] border-2 transition-all shadow-sm group-hover:scale-105',
                                        getStatusDetails(user.status).class
                                    ]">
                                        <span class="relative flex h-2.5 w-2.5">
                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-current opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-current"></span>
                                        </span>
                                        {{ getStatusDetails(user.status).label }}
                                    </div>
                                </td>
                                <td class="px-10 py-8">
                                    <div class="flex items-center justify-end gap-3 opacity-0 group-hover:opacity-100 transition-all">
                                         <Link 
                                            :href="route('crm.clients.show', user.id)" 
                                            class="w-12 h-12 bg-white shadow-xl border border-outline-variant/10 rounded-xl flex items-center justify-center text-slate-400 hover:text-primary hover:scale-110 active:scale-90 transition-all"
                                         >
                                            <span class="material-symbols-outlined text-[24px]">troubleshoot</span>
                                         </Link>
                                         <button 
                                            @click="deleteUser(user.id)"
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
                <div v-if="filteredUsers.length === 0" class="py-48 flex flex-col items-center gap-10 group/empty">
                    <div class="w-32 h-32 rounded-[2.5rem] bg-surface-container-low flex items-center justify-center text-slate-200 border border-outline-variant/5 shadow-2xl group-hover/empty:scale-110 transition-all duration-1000">
                        <span class="material-symbols-outlined text-[64px]" style="font-variation-settings: 'wght' 100">person_off</span>
                    </div>
                    <div class="text-center">
                        <h3 class="text-2xl font-black text-primary mb-3">لم يتم رصد أي اشتراكات برودباند</h3>
                        <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.3em] max-w-sm leading-relaxed italic">لا توجد حسابات (Secrets) مطابقة لمعايير البحث البرمجية الحالية في قاعدة البيانات.</p>
                    </div>
                    <Link :href="route('broadband.users.create')" class="px-12 py-5 bg-primary text-white rounded-xl font-black text-xs uppercase tracking-[0.2em] shadow-2xl hover:bg-emerald-600 transition-all active:scale-95 border border-white/10 flex items-center gap-4">
                        <span class="material-symbols-outlined text-[24px]">add_moderator</span> تفعيل أول حساب PPPoE
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
