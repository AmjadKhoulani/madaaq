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
    if (confirm('تأكيد إخراج حساب البرودباند من المصفوفة السيادية؟ سيتم إتلاف كافة بروتوكولات الربط (Secrets) من السيرفر المركزي فوراً.')) {
        router.delete(route('broadband.users.destroy', id));
    }
};

const getStatusDetails = (status) => {
    switch (status) {
        case 'active': return { label: 'النبض مستقر (Live)', class: 'bg-emerald-500/10 text-emerald-600 border-emerald-500/20 shadow-emerald-500/10', icon: 'check_circle' };
        case 'expired': return { label: 'صلاحية منتهية (Expir)', class: 'bg-rose-500/10 text-rose-500 border-rose-500/20 shadow-rose-500/10', icon: 'history' };
        case 'disabled': return { label: 'تـجميد إداري (Locked)', class: 'bg-slate-500/10 text-slate-500 border-slate-500/20', icon: 'block' };
        default: return { label: 'حالة غير معرفة', class: 'bg-amber-500/10 text-amber-600 border-amber-500/20', icon: 'help_center' };
    }
};

</script>

<template>
    <InstitutionalLayout title="مصفوفة هويات البرودباند">
        <Head title="مصفوفة هويات البرودباند (Broadband Identity) - MadaaQ" />

        <div class="max-w-7xl mx-auto pb-24 text-right px-4" dir="rtl">
            <!-- Strategic Intelligence Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-12 mb-16 flex-row-reverse text-right">
                <div class="text-right">
                    <h1 class="text-5xl font-black text-primary tracking-tighter mb-3 uppercase">مصفوفة هويات البرودباند (Subscriber Identity)</h1>
                    <div class="flex items-center gap-6 justify-end">
                        <p class="text-slate-500 font-bold text-sm uppercase tracking-[0.2em]">إدارة أنفاق <span class="text-primary font-black">PPPoE</span>، حوكمة هويات العبور، وتتبع توزيع السعات الطيفية</p>
                        <span class="flex h-4 w-4 relative">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-secondary opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-4 w-4 bg-secondary"></span>
                        </span>
                    </div>
                </div>
                <div class="flex flex-col md:flex-row items-center gap-6">
                    <div class="relative group hidden lg:block">
                        <span class="material-symbols-outlined absolute right-6 top-1/2 -translate-y-1/2 text-primary opacity-40 group-focus-within:opacity-100 transition-opacity text-[28px]">account_circle</span>
                        <input v-model="searchQuery" type="text" placeholder="تدقيق هوية المشترك (SSID/Name)..." class="form-input-monolith pr-16 h-18 w-[400px] text-base font-black tracking-tight rounded-[1.5rem] shadow-inner">
                    </div>
                    <Link 
                        :href="route('broadband.users.create')" 
                        class="px-14 py-6 bg-slate-950 text-white rounded-[1.5rem] font-black text-[11px] uppercase tracking-[0.3em] shadow-2xl hover:bg-primary transition-all active:scale-95 flex items-center gap-5 border border-white/10 group"
                    >
                        <span class="material-symbols-outlined text-[28px] group-hover:rotate-180 transition-transform">person_add_check</span>
                        تفعيل هوية برودباند (Inject Auth)
                    </Link>
                </div>
            </div>

            <!-- Identity Matrix Ledger -->
            <div class="surface-card rounded-[2.5rem] overflow-hidden shadow-2xl border border-outline-variant/10 bg-white relative">
                 <div class="absolute inset-0 bg-grid-slate-50 [mask-image:linear-gradient(0deg,white,rgba(255,255,255,0.6))] pointer-events-none opacity-20"></div>
                
                <div class="overflow-x-auto relative z-10">
                    <table class="w-full text-right border-separate border-spacing-0">
                        <thead>
                            <tr class="bg-slate-950 text-white border-b border-white/5 uppercase">
                                <th class="px-12 py-8 text-[11px] font-black uppercase tracking-[0.3em] text-white/30">هوية المصادقة (PPPoE Secret)</th>
                                <th class="px-10 py-8 text-[11px] font-black uppercase tracking-[0.3em] text-white/30 text-center">بروتوكول الربط الميداني</th>
                                <th class="px-10 py-8 text-[11px] font-black uppercase tracking-[0.3em] text-center text-white/30 border-r border-white/5">مصفوفة السرعة (QoS)</th>
                                <th class="px-10 py-8 text-[11px] font-black uppercase tracking-[0.3em] text-center text-white/30">حالة النبض</th>
                                <th class="px-10 py-8 w-48"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant/5">
                            <tr v-for="user in filteredUsers" :key="user.id" class="group hover:bg-slate-50/80 transition-all duration-500">
                                <td class="px-12 py-10">
                                    <div class="flex items-center gap-10 justify-end text-right">
                                        <div>
                                            <h4 class="text-3xl font-black text-primary leading-tight group-hover:translate-x-3 transition-transform tracking-tighter uppercase font-headline">{{ user.username }}</h4>
                                            <div class="flex items-center gap-3 mt-3 opacity-40 justify-end">
                                                <p class="text-[10px] font-black uppercase tracking-[0.2em] leading-none">{{ user.name || 'هوية مدنية غير معالجة' }}</p>
                                                <span class="material-symbols-outlined text-[18px]">verified_user</span>
                                            </div>
                                        </div>
                                        <div class="w-20 h-20 rounded-[1.5rem] bg-slate-950 text-white flex items-center justify-center shadow-2xl group-hover:bg-primary group-hover:scale-110 group-hover:rotate-6 transition-all duration-700 relative overflow-hidden shrink-0 border border-white/10">
                                             <div class="absolute inset-x-0 bottom-0 h-1/2 bg-gradient-to-t from-black/20 to-transparent"></div>
                                             <span class="material-symbols-outlined text-[40px] relative z-10" style="font-variation-settings: 'FILL' 1; font-weight: 200;">fingerprint</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-10 py-10 text-right">
                                    <div class="flex flex-col gap-4">
                                        <div class="flex items-center gap-4 justify-end">
                                            <span class="text-[11px] font-black text-slate-900 bg-slate-100 px-5 py-2 rounded-xl border border-slate-200 shadow-inner uppercase tracking-[0.1em] group-hover:bg-primary group-hover:text-white transition-all">{{ user.tower?.name || 'عقدة تزويد فرعية' }}</span>
                                            <div class="w-12 h-12 rounded-xl bg-slate-950 text-white flex items-center justify-center border border-white/10 shadow-2xl group-hover:rotate-12 transition-transform">
                                                <span class="material-symbols-outlined text-[24px]">satellite_alt</span>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-4 justify-end opacity-40 group-hover:opacity-100 transition-opacity">
                                            <span class="text-[12px] font-headline font-black text-primary tracking-widest italic">{{ user.ip || 'PROTO_DYNAMIC_POOL' }}</span>
                                            <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center border border-slate-200">
                                                <span class="material-symbols-outlined text-[20px] text-slate-400">lan</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-10 py-10 text-center border-r border-outline-variant/5">
                                    <div class="flex flex-col items-center gap-4">
                                        <div class="px-8 py-4 bg-slate-950 text-white border border-white/10 rounded-[1.5rem] flex items-center gap-8 shadow-[0_20px_40px_rgba(0,0,0,0.2)] group-hover:scale-110 transition-transform">
                                            <div class="flex items-center gap-4">
                                                <span class="material-symbols-outlined text-emerald-500 text-[24px] animate-pulse">download</span>
                                                <span class="text-2xl font-headline font-black tracking-tighter">{{ user.package?.speed_down || 0 }}<span class="text-[10px] opacity-40 ml-2">MB</span></span>
                                            </div>
                                            <div class="w-px h-8 bg-white/10"></div>
                                            <div class="flex items-center gap-4">
                                                <span class="text-2xl font-headline font-black tracking-tighter">{{ user.package?.speed_up || 0 }}<span class="text-[10px] opacity-40 ml-2">MB</span></span>
                                                <span class="material-symbols-outlined text-amber-500 text-[24px]">upload</span>
                                            </div>
                                        </div>
                                        <p class="text-[10px] font-black text-primary uppercase tracking-[0.3em] leading-none">{{ user.package?.name || 'QoS_STANDARD_PROFILE' }}</p>
                                    </div>
                                </td>
                                <td class="px-10 py-10 text-center">
                                    <div :class="[
                                        'inline-flex items-center justify-center gap-4 px-8 py-3.5 rounded-2xl text-[10px] font-black uppercase tracking-[0.3em] border-2 transition-all shadow-2xl group-hover:translate-y-[-4px]',
                                        getStatusDetails(user.status).class
                                    ]">
                                        <span class="relative flex h-3 w-3">
                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-current opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-3 w-3 bg-current"></span>
                                        </span>
                                        {{ getStatusDetails(user.status).label }}
                                    </div>
                                </td>
                                <td class="px-10 py-10">
                                    <div class="flex items-center justify-end gap-3 opacity-0 group-hover:opacity-100 transition-all translate-x-4 group-hover:translate-x-0">
                                         <Link 
                                            :href="route('crm.clients.show', user.id)" 
                                            class="w-14 h-14 bg-white shadow-2xl border border-outline-variant/10 rounded-2xl flex items-center justify-center text-slate-400 hover:text-primary hover:scale-110 active:scale-90 transition-all group/icon"
                                         >
                                            <span class="material-symbols-outlined text-[28px] group-hover/icon:rotate-12 transition-transform" style="font-variation-settings: 'wght' 700">troubleshoot</span>
                                         </Link>
                                         <button 
                                            @click="deleteUser(user.id)"
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

                <!-- Null Auth Protocol (Empty State) -->
                <div v-if="filteredUsers.length === 0" class="py-64 flex flex-col items-center gap-12 group/empty relative z-10">
                    <div class="w-48 h-48 rounded-[3.5rem] bg-slate-950 text-white flex items-center justify-center border-8 border-white shadow-[0_0_80px_rgba(2,6,23,0.15)] group-hover/empty:scale-110 transition-all duration-1000 relative">
                        <div class="absolute inset-0 bg-primary opacity-20 blur-3xl animate-pulse"></div>
                        <span class="material-symbols-outlined text-[80px] relative z-10" style="font-variation-settings: 'wght' 100">vpn_lock</span>
                    </div>
                    <div class="text-center">
                        <h3 class="text-4xl font-black text-primary mb-6 tracking-tighter uppercase leading-none">مصفوفات المشتركين صفرية (Null Auth)</h3>
                        <p class="text-[12px] font-black text-slate-400 uppercase tracking-[0.4em] max-w-sm leading-relaxed italic opacity-70">لم يتم رصد أي هويات مصادقة نشطة ضمن المصفوفة السيادية. ابدأ بروتوكول Inject Auth الأول.</p>
                    </div>
                    <Link :href="route('broadband.users.create')" class="px-16 py-8 bg-primary text-white rounded-3xl font-black text-xs uppercase tracking-[0.4em] shadow-[0_30px_60px_rgba(37,99,235,0.25)] hover:bg-emerald-600 hover:-translate-y-2 transition-all active:scale-95 border border-white/10 flex items-center gap-6">
                        <span class="material-symbols-outlined text-[32px]">person_add_check</span> تفعيل أول حساب برودباند
                    </Link>
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
