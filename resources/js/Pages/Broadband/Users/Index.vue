<script setup>
import { ref, computed } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import InstitutionalLayout from '@/Layouts/InstitutionalLayout.vue';
import { 
    Wifi, 
    UserPlus, 
    Search, 
    Fingerprint, 
    Download, 
    Upload, 
    CheckCircle2, 
    Clock, 
    ShieldAlert, 
    Activity, 
    MoreVertical,
    Eye,
    UserX,
    Radio,
    Network,
    Zap
} from 'lucide-vue-next';

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
        case 'active': return { label: 'النبض مستقر (Live)', class: 'bg-emerald-500/10 text-emerald-600 border-emerald-500/20 shadow-emerald-500/10', icon: CheckCircle2 };
        case 'expired': return { label: 'صلاحية منتهية (Expir)', class: 'bg-rose-500/10 text-rose-500 border-rose-500/20 shadow-rose-500/10', icon: Clock };
        case 'disabled': return { label: 'تـجميد إداري (Locked)', class: 'bg-slate-500/10 text-slate-500 border-slate-500/20', icon: ShieldAlert };
        default: return { label: 'حالة غير معرفة', class: 'bg-amber-500/10 text-amber-600 border-amber-500/20', icon: Activity };
    }
};

</script>

<template>
    <InstitutionalLayout title="مصفوفة هويات البرودباند">
        <div class="space-y-10 pb-20 animate-in fade-in slide-in-from-bottom-4 duration-700">
            
            <!-- Strategic Header -->
            <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-8">
                <div>
                    <div class="flex items-center gap-3 mb-3">
                        <span class="w-12 h-1 bg-vendor rounded-full"></span>
                        <p class="text-[10px] font-black text-vendor uppercase tracking-[0.3em] font-inter">Broadband Identity Matrix</p>
                    </div>
                    <h1 class="text-4xl font-black text-slate-900 tracking-tighter italic">مصفوفة هويات <span class="text-vendor">البرودباند</span></h1>
                    <p class="text-slate-400 font-bold mt-2 uppercase tracking-widest text-[11px] opacity-80 italic">إدارة أنفاق PPPoE وحوكمة سعات الربط الميداني</p>
                </div>
                
                <div class="flex items-center gap-6">
                    <div class="relative group hidden lg:block">
                        <input v-model="searchQuery" type="text" placeholder="تدقيق SSID أو الاسم..." class="w-full bg-white/50 border-white/60 rounded-2xl pr-12 py-3.5 text-sm font-bold focus:ring-4 focus:ring-vendor/5 transition-all w-[350px]">
                        <Search class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-300 w-5 h-5 group-focus-within:text-vendor transition-colors stroke-[3]" />
                    </div>
                    <Link :href="route('broadband.users.create')" class="btn-radiant btn-vendor px-8 py-4 text-xs font-black uppercase tracking-[0.2em] flex items-center gap-3">
                        <UserPlus class="w-5 h-5 stroke-[3]" />
                        تفعيل هوية برودباند
                    </Link>
                </div>
            </div>

            <!-- Identity Matrix Ledger -->
            <div class="glass-card overflow-hidden bg-white/40 border-white/60">
                <div class="overflow-x-auto">
                    <table class="w-full text-right border-collapse">
                        <thead>
                            <tr class="bg-slate-900 text-white/40 text-[10px] font-black uppercase tracking-[0.3em] italic font-inter">
                                <th class="px-10 py-8">هوية المصادقة (PPPoE Secret)</th>
                                <th class="px-8 py-8 text-center">بروتوكول الربط الميداني</th>
                                <th class="px-8 py-8 text-center">مصفوفة السرعة (QoS)</th>
                                <th class="px-8 py-8 text-center">حالة النبض</th>
                                <th class="px-10 py-8"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/20">
                            <tr v-for="user in filteredUsers" :key="user.id" class="group hover:bg-white/60 transition-all duration-500">
                                <td class="px-10 py-8">
                                    <div class="flex items-center gap-5 justify-end">
                                        <div class="text-right">
                                            <h4 class="text-lg font-black text-slate-900 italic tracking-tighter group-hover:translate-x-2 transition-transform uppercase font-inter">{{ user.username }}</h4>
                                            <div class="flex items-center gap-2 mt-1 opacity-40 justify-end">
                                                <span class="text-[9px] font-black uppercase tracking-widest">{{ user.name || 'Civil Identity Unprocessed' }}</span>
                                                <Fingerprint class="w-3 h-3" />
                                            </div>
                                        </div>
                                        <div class="w-14 h-14 rounded-2xl bg-slate-900 text-vendor flex items-center justify-center shadow-xl group-hover:scale-110 group-hover:rotate-6 transition-all duration-500 shrink-0 border border-white/10">
                                            <Wifi class="w-7 h-7 stroke-[2]" />
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-8 text-right">
                                    <div class="flex flex-col gap-3 items-end">
                                        <div class="flex items-center gap-2">
                                            <span class="text-[10px] font-black text-slate-900 bg-white/50 px-4 py-1.5 rounded-xl border border-white/60 shadow-sm uppercase tracking-widest group-hover:bg-vendor group-hover:text-white transition-all">{{ user.tower?.name || 'Sub-Node Entity' }}</span>
                                            <Radio class="w-4 h-4 text-vendor" />
                                        </div>
                                        <div class="flex items-center gap-2 opacity-40 group-hover:opacity-100 transition-opacity">
                                            <span class="text-[11px] font-black text-slate-500 font-inter tracking-widest italic">{{ user.ip || 'PROTO_DYNAMIC_POOL' }}</span>
                                            <Network class="w-3.5 h-3.5" />
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-8 text-center">
                                    <div class="flex flex-col items-center gap-2">
                                        <div class="px-6 py-3 bg-slate-900 text-white rounded-[1.5rem] flex items-center gap-6 shadow-xl group-hover:scale-105 transition-transform border border-white/10">
                                            <div class="flex items-center gap-2">
                                                <Download class="w-4 h-4 text-emerald-500 animate-pulse" />
                                                <span class="text-xl font-black font-inter tracking-tighter">{{ user.package?.speed_down || 0 }}<span class="text-[9px] opacity-40 ml-1">MB</span></span>
                                            </div>
                                            <div class="w-px h-6 bg-white/10"></div>
                                            <div class="flex items-center gap-2">
                                                <span class="text-xl font-black font-inter tracking-tighter">{{ user.package?.speed_up || 0 }}<span class="text-[9px] opacity-40 ml-1">MB</span></span>
                                                <Upload class="w-4 h-4 text-amber-500" />
                                            </div>
                                        </div>
                                        <span class="text-[9px] font-black text-vendor uppercase tracking-widest font-inter">{{ user.package?.name || 'QoS_STANDARD_PROFILE' }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-8 text-center">
                                    <div :class="[
                                        'inline-flex items-center justify-center gap-3 px-6 py-3 rounded-xl text-[9px] font-black uppercase tracking-widest border transition-all shadow-sm group-hover:translate-y-[-2px]',
                                        getStatusDetails(user.status).class
                                    ]">
                                        <component :is="getStatusDetails(user.status).icon" class="w-3.5 h-3.5" />
                                        {{ getStatusDetails(user.status).label }}
                                    </div>
                                </td>
                                <td class="px-10 py-8">
                                    <div class="flex items-center justify-end gap-3 opacity-0 group-hover:opacity-100 transition-all translate-x-4 group-hover:translate-x-0">
                                         <Link :href="route('crm.clients.show', user.id)" class="w-12 h-12 bg-white shadow-xl border border-white/60 rounded-xl flex items-center justify-center text-slate-400 hover:text-vendor hover:scale-110 active:scale-90 transition-all">
                                            <Eye class="w-5 h-5" />
                                         </Link>
                                         <button @click="deleteUser(user.id)" class="w-12 h-12 bg-white shadow-xl border border-white/60 rounded-xl flex items-center justify-center text-slate-400 hover:text-rose-600 hover:scale-110 active:scale-90 transition-all">
                                            <UserX class="w-5 h-5" />
                                         </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Empty State Protocol -->
                <div v-if="filteredUsers.length === 0" class="py-40 flex flex-col items-center gap-8 text-center">
                    <div class="w-32 h-32 rounded-[2.5rem] bg-slate-900 text-vendor flex items-center justify-center border-8 border-white/20 shadow-2xl relative group">
                        <div class="absolute inset-0 bg-vendor/20 opacity-20 blur-2xl animate-pulse"></div>
                        <Zap class="w-12 h-12 relative z-10" />
                    </div>
                    <div>
                        <h3 class="text-3xl font-black text-slate-900 italic tracking-tighter uppercase">مصفوفات المشتركين صفرية (Null Auth)</h3>
                        <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.3em] max-w-sm mt-3 opacity-70 italic font-inter">No active broadband identities detected in the current sovereign domain.</p>
                    </div>
                    <Link :href="route('broadband.users.create')" class="btn-radiant btn-vendor px-12 py-5 text-xs font-black uppercase tracking-[0.3em]">
                        بدء بروتوكول التفعيل
                    </Link>
                </div>
            </div>
        </div>
    </InstitutionalLayout>
</template>

<style scoped>
.glass-card {
    @apply border border-white/40 shadow-glass rounded-[2.5rem] transition-all duration-500;
}
.glass-card:hover {
    @apply border-white/60 shadow-radiant -translate-y-1;
}
</style>
