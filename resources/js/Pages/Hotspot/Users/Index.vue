<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import AppleLayout from '@/Layouts/AppleLayout.vue';
import { 
    Users, 
    UserPlus, 
    TowerControl as Tower, 
    Wifi, 
    Zap, 
    ShieldCheck, 
    Clock, 
    Printer, 
    Trash2, 
    Activity,
    MoreHorizontal,
    Search,
    ShieldAlert
} from 'lucide-vue-next';

const props = defineProps({
    users: Array,
});

const deleteUser = (id) => {
    if (confirm('Decommission this individual guest access? This will purge the record from the edge node.')) {
        router.delete(route('hotspot.users.destroy', id));
    }
};

const getStatusDetails = (status) => {
    switch (status) {
        case 'active': return { label: 'Operational', color: 'text-emerald-600 bg-emerald-50 border-emerald-100' };
        case 'inactive': return { label: 'Suspended', color: 'text-rose-600 bg-rose-50 border-rose-100' };
        default: return { label: status, color: 'text-gray-600 bg-gray-50 border-gray-100' };
    }
};

</script>

<template>
    <AppleLayout title="Guest Intelligence">
        <Head title="Individual Guest Management" />

        <div class="max-w-[1400px] mx-auto pb-20">
            <!-- Strategic Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-8 mb-12">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight mb-2">Individual Guest Intelligence</h1>
                    <p class="text-[var(--app-secondary)] font-medium flex items-center gap-2">
                        <Users class="w-4 h-4" />
                        Managing high-accountability guest network participants
                    </p>
                </div>
                <div class="flex items-center gap-4">
                     <div class="relative group hidden md:block">
                        <Search class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-[#86868b] group-focus-within:text-black transition-colors" />
                        <input type="text" placeholder="Probe identity or secret..." class="apple-input pl-11 h-12 w-64 bg-black/[0.02] border-transparent focus:bg-white focus:border-black/5">
                     </div>
                     <Link 
                        :href="route('hotspot.users.create')" 
                        class="px-8 py-3.5 bg-black text-white rounded-2xl font-bold text-[11px] uppercase tracking-[0.2em] shadow-xl hover:scale-105 active:scale-95 transition-all flex items-center gap-3"
                     >
                        <UserPlus class="w-4 h-4" /> Provision Member
                     </Link>
                </div>
            </div>

            <!-- Users Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
                <div 
                    v-for="user in users" 
                    :key="user.id"
                    class="apple-card p-8 flex flex-col relative group transition-all hover:scale-[1.03] animate-in fade-in duration-500"
                >
                    <!-- Status Pulse -->
                    <div class="absolute top-6 right-6">
                        <span 
                            class="px-2 py-0.5 rounded-lg text-[8px] font-black uppercase tracking-widest border"
                            :class="getStatusDetails(user.status).color"
                        >
                            {{ getStatusDetails(user.status).label }}
                        </span>
                    </div>

                    <!-- User Identity -->
                    <div class="flex items-center gap-5 mb-10">
                        <div class="w-16 h-16 rounded-2xl bg-black text-white flex items-center justify-center text-xl font-black shadow-xl group-hover:rotate-3 transition-transform">
                            {{ user.name.substring(0, 1) }}
                        </div>
                        <div>
                             <h3 class="text-lg font-bold tracking-tight uppercase line-clamp-1">{{ user.name }}</h3>
                             <p class="text-[10px] font-black text-[#86868b] uppercase tracking-[0.2em] mt-1 flex items-center gap-2">
                                <Zap class="w-3 h-3 text-amber-500" /> {{ user.package?.name || 'GENERIC_TIER' }}
                             </p>
                        </div>
                    </div>

                    <!-- Credentials Artifact -->
                    <div class="p-6 bg-black/[0.02] border border-black/5 rounded-2xl space-y-4 mb-8">
                         <div class="flex justify-between items-center text-[10px] uppercase font-black tracking-widest text-[#86868b]">
                             <span>Secret Key</span>
                             <span class="text-black font-mono tracking-normal">{{ user.username }}</span>
                         </div>
                         <div class="flex justify-between items-center text-[10px] uppercase font-black tracking-widest text-[#86868b]">
                             <span>Access Protocol</span>
                             <span class="text-black font-mono tracking-normal">{{ user.password }}</span>
                         </div>
                    </div>

                    <!-- Edge Node & Expiry -->
                    <div class="grid grid-cols-2 gap-6 pt-6 border-t border-black/[0.03] mt-auto">
                        <div class="space-y-1">
                            <p class="text-[8px] font-black text-[#86868b] uppercase tracking-widest">Edge Cluster</p>
                            <p class="text-xs font-bold truncate flex items-center gap-1.5"><Tower class="w-3 h-3" /> {{ user.mikrotik_server?.name || 'Local Node' }}</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-[8px] font-black text-[#86868b] uppercase tracking-widest">Temporal Horizon</p>
                            <p class="text-xs font-bold flex items-center gap-1.5" :class="user.expires_at ? 'text-amber-600' : 'text-emerald-600'"><Clock class="w-3 h-3" /> {{ user.expires_at ? new Date(user.expires_at).toLocaleDateString() : 'Permanent' }}</p>
                        </div>
                    </div>

                    <!-- Action Hover Reveal -->
                    <div class="absolute inset-x-8 bottom-8 flex items-center gap-3 opacity-0 group-hover:opacity-100 transition-all pointer-events-none group-hover:pointer-events-auto">
                         <Link 
                            :href="route('hotspot.users.print', user.id)" 
                            class="flex-1 py-3 bg-black text-white rounded-xl text-[10px] font-black uppercase tracking-widest text-center shadow-xl hover:bg-black/90 transition-all flex items-center justify-center gap-2"
                         >
                            <Printer class="w-3.5 h-3.5" /> Artifact
                         </Link>
                         <button 
                            @click="deleteUser(user.id)"
                            class="w-12 py-3 bg-rose-50 text-rose-600 rounded-xl flex items-center justify-center hover:bg-rose-500 hover:text-white transition-all border border-rose-200"
                         >
                            <Trash2 class="w-4 h-4" />
                         </button>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-if="users.length === 0" class="col-span-full py-24 apple-card flex flex-col items-center justify-center text-center opacity-50 border-dashed border-2">
                     <div class="w-20 h-20 rounded-[2.5rem] bg-black/5 flex items-center justify-center mb-6">
                        <Users class="w-10 h-10" />
                     </div>
                     <h4 class="text-lg font-bold uppercase tracking-tight">Governance Void</h4>
                     <p class="text-xs font-medium max-w-xs mx-auto mt-2">No individual guest access records detected. Provision a new member to begin tracking.</p>
                </div>
            </div>
        </div>
    </AppleLayout>
</template>
