<script setup>
import { ref, computed } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AppleLayout from '@/Layouts/AppleLayout.vue';
import { 
    Users, 
    Search, 
    Plus, 
    Wifi, 
    MoreHorizontal, 
    Eye, 
    Trash2, 
    RotateCcw, 
    Globe, 
    ArrowUpCircle, 
    ArrowDownCircle,
    User,
    CheckCircle2,
    XCircle,
    Activity,
    HardDrive,
    TowerControl as Tower
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
    if (confirm('Permanently de-provision this account? This will purge the secret from the respective edge controller.')) {
        router.delete(route('broadband.users.destroy', id));
    }
};

const getStatusClass = (status) => {
    switch (status) {
        case 'active': return 'bg-emerald-50 text-emerald-600 border-emerald-100';
        case 'expired': return 'bg-rose-50 text-rose-600 border-rose-100';
        case 'disabled': return 'bg-gray-50 text-gray-600 border-gray-100';
        default: return 'bg-amber-50 text-amber-600 border-amber-100';
    }
};

</script>

<template>
    <AppleLayout title="Broadband Registry">
        <Head title="Broadband Users (PPPoE)" />

        <div class="max-w-[1400px] mx-auto pb-20">
            <!-- Strategic Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-8 mb-12">
                <div>
                     <h1 class="text-3xl font-bold tracking-tight mb-2">Broadband Portfolio</h1>
                     <p class="text-[var(--app-secondary)] font-medium flex items-center gap-2">
                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full shadow-[0_0_8px_rgba(16,185,129,0.5)]"></span>
                        {{ users.length }} Active Leases Tracked Across Edge Network
                     </p>
                </div>
                <div class="flex items-center gap-4">
                     <div class="relative group">
                        <Search class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-[#86868b] group-focus-within:text-black transition-colors" />
                        <input v-model="searchQuery" type="text" placeholder="Search accounts, IPs, names..." class="apple-input pl-11 h-12 w-64 lg:w-80 bg-black/[0.02] border-transparent focus:bg-white focus:border-black/5">
                     </div>
                     <Link 
                        :href="route('broadband.users.create')" 
                        class="px-8 py-3.5 bg-black text-white rounded-2xl font-bold text-[11px] uppercase tracking-[0.2em] shadow-xl hover:scale-105 active:scale-95 transition-all flex items-center gap-3"
                     >
                        <Plus class="w-4 h-4" /> Provision Account
                     </Link>
                </div>
            </div>

            <!-- Registry Table -->
            <div class="apple-card overflow-hidden bg-white/50 backdrop-blur-xl border border-black/5">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-black/[0.02] bg-black/[0.01]">
                                <th class="px-8 py-5 text-[10px] font-black text-[#86868b] uppercase tracking-widest">Subscriber Intelligence</th>
                                <th class="px-8 py-5 text-[10px] font-black text-[#86868b] uppercase tracking-widest">Network Topology</th>
                                <th class="px-8 py-5 text-[10px] font-black text-[#86868b] uppercase tracking-widest text-center">Velocity Tier</th>
                                <th class="px-8 py-5 text-[10px] font-black text-[#86868b] uppercase tracking-widest">Status</th>
                                <th class="px-8 py-5 text-[10px] font-black text-[#86868b] uppercase tracking-widest text-right">Commitment</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-black/[0.01]">
                            <tr v-for="user in filteredUsers" :key="user.id" class="group hover:bg-black/[0.01] transition-all">
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-2xl bg-black shadow-lg flex items-center justify-center text-white shrink-0 group-hover:scale-110 transition-transform">
                                            <User class="w-5 h-5" />
                                        </div>
                                        <div>
                                            <p class="font-bold text-sm tracking-tight group-hover:text-indigo-600 transition-colors uppercase">{{ user.username }}</p>
                                            <p class="text-[9px] font-black text-[#86868b] uppercase tracking-widest mt-1">{{ user.name || 'Anonymous Peer' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="space-y-1.5">
                                        <div class="flex items-center gap-2">
                                            <Tower class="w-3.5 h-3.5 text-[#86868b]" />
                                            <span class="text-[10px] font-bold text-black uppercase">{{ user.tower?.name || 'Local Site' }}</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <HardDrive class="w-3.5 h-3.5 text-[#86868b]" />
                                            <span class="text-[9px] font-mono text-[#86868b] font-bold">{{ user.ip || 'DHCP_LEASE' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex flex-col items-center">
                                        <div class="px-3 py-1.5 bg-black/[0.03] border border-black/5 rounded-xl flex items-center gap-3">
                                            <div class="flex items-center gap-1.5">
                                                <ArrowDownCircle class="w-3.5 h-3.5 text-emerald-500" />
                                                <span class="text-xs font-black tracking-tighter">{{ user.package?.speed_down || 0 }}M</span>
                                            </div>
                                            <div class="w-px h-3 bg-black/10"></div>
                                            <div class="flex items-center gap-1.5">
                                                <ArrowUpCircle class="w-3.5 h-3.5 text-indigo-500" />
                                                <span class="text-xs font-black tracking-tighter">{{ user.package?.speed_up || 0 }}M</span>
                                            </div>
                                        </div>
                                        <p class="text-[8px] font-black text-[#86868b] uppercase tracking-widest mt-2">{{ user.package?.name }}</p>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <span 
                                        class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest border flex items-center justify-center gap-2 w-fit mx-auto"
                                        :class="getStatusClass(user.status)"
                                    >
                                        <span v-if="user.status === 'active'" class="w-1 h-1 bg-emerald-500 rounded-full animate-pulse"></span>
                                        {{ user.status }}
                                    </span>
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <div class="flex items-center justify-end gap-3">
                                         <Link 
                                            :href="route('crm.clients.show', user.id)" 
                                            class="w-10 h-10 apple-card flex items-center justify-center text-[#86868b] hover:text-indigo-600 hover:bg-white transition-all shadow-sm group/btn"
                                         >
                                            <Eye class="w-4 h-4 group-hover/btn:scale-110 transition-transform" />
                                         </Link>
                                         <button 
                                            @click="deleteUser(user.id)"
                                            class="w-10 h-10 apple-card flex items-center justify-center text-[#86868b] hover:text-rose-600 hover:bg-white transition-all shadow-sm"
                                         >
                                            <Trash2 class="w-4 h-4" />
                                         </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Empty State -->
                <div v-if="filteredUsers.length === 0" class="py-24 text-center">
                    <div class="w-20 h-20 bg-black/5 rounded-[2rem] flex items-center justify-center mx-auto mb-6 text-[#86868b]">
                        <Activity class="w-10 h-10" />
                    </div>
                    <h3 class="text-lg font-bold tracking-tight text-black mb-2">No Subscribers Matching Search</h3>
                    <p class="text-sm text-[#86868b] max-w-xs mx-auto">Adjust the search parameters or provision a new high-speed account below.</p>
                </div>
            </div>
        </div>
    </AppleLayout>
</template>
