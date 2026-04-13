<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import AppleLayout from '@/Layouts/AppleLayout.vue';
import { 
    Plus, 
    Ticket, 
    Zap, 
    ArrowDownCircle, 
    ArrowUpCircle, 
    Settings2, 
    Trash2, 
    Globe, 
    Wifi, 
    Activity, 
    HardDrive,
    Search,
    ChevronRight,
    Users
} from 'lucide-vue-next';

const props = defineProps({
    profiles: Array
});

const deleteProfile = (id) => {
    if (confirm('Decommission this hotspot profile? All synced edge nodes will purge the locally cached definition.')) {
        router.delete(route('hotspot.profiles.destroy', id));
    }
};

const getTechIcon = (type) => {
    switch (type) {
        case 'fiber': return { icon: Globe, color: 'text-emerald-500', bg: 'bg-emerald-50' };
        case 'wireless': return { icon: Wifi, color: 'text-indigo-500', bg: 'bg-indigo-50' };
        case 'dsl': return { icon: Activity, color: 'text-amber-500', bg: 'bg-amber-50' };
        default: return { icon: HardDrive, color: 'text-gray-500', bg: 'bg-gray-50' };
    }
};

</script>

<template>
    <AppleLayout title="Guest Velocity Tiers">
        <Head title="Hotspot Profiles" />

        <div class="max-w-[1400px] mx-auto pb-20">
            <!-- Strategic Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-8 mb-12">
                <div>
                     <h1 class="text-3xl font-bold tracking-tight mb-2">Guest Access Intelligence</h1>
                     <p class="text-[var(--app-secondary)] font-medium flex items-center gap-2">
                        <span class="w-1.5 h-1.5 bg-amber-500 rounded-full shadow-[0_0_8px_rgba(245,158,11,0.5)]"></span>
                        {{ profiles.length }} Active Hotspot Tier Protocols Defined
                     </p>
                </div>
                <div class="flex items-center gap-4">
                     <Link 
                        :href="route('hotspot.profiles.create')" 
                        class="px-8 py-3.5 bg-black text-white rounded-2xl font-bold text-[11px] uppercase tracking-[0.2em] shadow-xl hover:scale-105 active:scale-95 transition-all flex items-center gap-3"
                     >
                        <Plus class="w-4 h-4" /> Initialize Hub Profile
                     </Link>
                </div>
            </div>

            <!-- Profiles Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
                <div 
                    v-for="profile in profiles" 
                    :key="profile.id"
                    class="apple-card group hover:scale-[1.02] transition-all flex flex-col bg-white"
                >
                    <!-- Visual Identity -->
                    <div class="p-8 pb-4 relative overflow-hidden">
                        <div class="absolute -top-10 -right-10 w-32 h-32 bg-amber-500/[0.03] rounded-full blur-3xl group-hover:bg-amber-500/[0.06] transition-all"></div>
                        <div class="flex items-start justify-between mb-8 relative z-10">
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 rounded-2xl flex items-center justify-center transition-all shadow-inner" :class="getTechIcon(profile.technology_type).bg">
                                    <Ticket class="w-7 h-7" :class="getTechIcon(profile.technology_type).color" />
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold tracking-tight group-hover:text-amber-600 transition-colors uppercase">{{ profile.name }}</h3>
                                    <p class="text-[9px] font-black text-[#86868b] uppercase tracking-widest mt-1">Hotspot Edge Protocol</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-[9px] font-black text-[#86868b] uppercase tracking-widest">Voucher Price</p>
                                <p class="text-2xl font-black tracking-tight text-amber-600">${{ profile.price }}</p>
                            </div>
                        </div>

                        <!-- Velocity Matrix -->
                        <div class="grid grid-cols-2 gap-4 relative z-10">
                            <div class="p-4 bg-black/[0.01] border border-black/5 rounded-2xl group/speed">
                                <div class="flex items-center gap-2 mb-1">
                                    <ArrowDownCircle class="w-3.5 h-3.5 text-emerald-500" />
                                    <span class="text-[9px] font-black text-[#86868b] uppercase tracking-widest">Downlink</span>
                                </div>
                                <p class="text-xl font-bold tracking-tighter">{{ profile.speed_down }} <span class="text-[10px] text-[#86868b]">MBPS</span></p>
                            </div>
                            <div class="p-4 bg-black/[0.01] border border-black/5 rounded-2xl group/speed">
                                <div class="flex items-center gap-2 mb-1">
                                    <ArrowUpCircle class="w-3.5 h-3.5 text-indigo-500" />
                                    <span class="text-[9px] font-black text-[#86868b] uppercase tracking-widest">Uplink</span>
                                </div>
                                <p class="text-xl font-bold tracking-tighter">{{ profile.speed_up }} <span class="text-[10px] text-[#86868b]">MBPS</span></p>
                            </div>
                        </div>
                    </div>

                    <!-- Topology Sync -->
                    <div class="px-8 py-5 border-t border-black/5 bg-black/[0.01]">
                         <div class="flex items-center justify-between">
                             <p class="text-[9px] font-black text-[#86868b] uppercase tracking-widest">Synchronized Edge Clusters</p>
                             <div class="flex -space-x-2">
                                 <div v-for="i in Math.min(3, (profile.routers?.length || 0) + (profile.mikrotik_servers?.length || 0))" :key="i" class="w-6 h-6 rounded-lg bg-white border border-black/5 flex items-center justify-center text-[8px] font-black text-amber-600 shadow-sm">
                                     {{ i }}
                                 </div>
                                 <div v-if="((profile.routers?.length || 0) + (profile.mikrotik_servers?.length || 0)) > 3" class="w-6 h-6 rounded-lg bg-amber-100 border border-amber-200 flex items-center justify-center text-[8px] font-black text-amber-600 shadow-sm">
                                     +{{ (profile.routers?.length || 0) + (profile.mikrotik_servers?.length || 0) - 3 }}
                                 </div>
                             </div>
                         </div>
                    </div>

                    <!-- Commit Actions -->
                    <div class="p-4 bg-white mt-auto flex items-center justify-between border-t border-black/[0.02]">
                        <div class="flex items-center gap-2">
                            <Link 
                                :href="route('hotspot.profiles.edit', profile.id)" 
                                class="w-10 h-10 flex items-center justify-center text-[#86868b] hover:text-amber-600 hover:bg-amber-50 rounded-xl transition-all"
                            >
                                <Settings2 class="w-5 h-5" />
                            </Link>
                            <button 
                                @click="deleteProfile(profile.id)"
                                class="w-10 h-10 flex items-center justify-center text-[#86868b] hover:text-rose-500 hover:bg-rose-50 rounded-xl transition-all"
                            >
                                <Trash2 class="w-5 h-5" />
                            </button>
                        </div>
                        <Link 
                            :href="route('hotspot.vouchers.index', { profile_id: profile.id })" 
                            class="px-5 py-2.5 text-[10px] font-black uppercase tracking-widest text-amber-600 hover:bg-amber-600 hover:text-white rounded-xl transition-all flex items-center gap-2 group/btn"
                        >
                            <Users class="w-3.5 h-3.5" />
                            Lease History
                            <ChevronRight class="w-3.5 h-3.5 group-hover/btn:translate-x-1 transition-transform" />
                        </Link>
                    </div>
                </div>

                <!-- Add Placeholder -->
                <Link 
                    :href="route('hotspot.profiles.create')" 
                    class="apple-card border-dashed border-2 border-black/5 bg-transparent flex flex-col items-center justify-center p-12 group hover:border-black/10 transition-all hover:bg-black/[0.01]"
                >
                    <div class="w-16 h-16 bg-black/5 rounded-3xl flex items-center justify-center text-[#86868b] group-hover:scale-110 group-hover:bg-amber-500 group-hover:text-white transition-all mb-4">
                        <Plus class="w-8 h-8 font-black" />
                    </div>
                    <p class="text-[11px] font-black uppercase tracking-[0.2em] text-[#86868b]">Define Hotspot Cluster Profile</p>
                </Link>
            </div>
        </div>
    </AppleLayout>
</template>
