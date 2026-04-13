<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import AppleLayout from '@/Layouts/AppleLayout.vue';
import { 
    Plus, 
    Zap, 
    ArrowDownCircle, 
    ArrowUpCircle, 
    Users, 
    Settings2, 
    Wifi, 
    Globe, 
    Database,
    Trash2,
    Calendar,
    ChevronRight,
    Search,
    Filter,
    Activity,
    HardDrive
} from 'lucide-vue-next';

const props = defineProps({
    profiles: Array
});

const deleteProfile = (id) => {
    if (confirm('Decommission this speed profile? This will notify all edge controllers but won\'t disconnect currently active users.')) {
        router.delete(route('broadband.profiles.destroy', id));
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
    <AppleLayout title="Service Catalog">
        <Head title="Broadband Profiles (PPPoE)" />

        <div class="max-w-[1400px] mx-auto pb-20">
            <!-- Header Section -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-8 mb-12">
                <div>
                     <h1 class="text-3xl font-bold tracking-tight mb-2">Broadband Intelligence</h1>
                     <p class="text-[var(--app-secondary)] font-medium flex items-center gap-2">
                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                        {{ profiles.length }} Active Service Tiers Registered
                     </p>
                </div>
                <div class="flex items-center gap-4">
                     <div class="relative group hidden lg:block">
                        <Search class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-[#86868b] group-focus-within:text-black transition-colors" />
                        <input type="text" placeholder="Search profiles..." class="apple-input pl-11 h-12 w-64 bg-black/[0.02] border-transparent focus:bg-white focus:border-black/5">
                     </div>
                     <Link 
                        :href="route('broadband.profiles.create')" 
                        class="px-8 py-3.5 bg-black text-white rounded-2xl font-bold text-[11px] uppercase tracking-[0.2em] shadow-xl hover:scale-105 active:scale-95 transition-all flex items-center gap-3"
                     >
                        <Plus class="w-4 h-4" /> Initialize Tier
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
                    <!-- Visual Header -->
                    <div class="p-8 pb-4 relative overflow-hidden">
                        <div class="absolute -top-10 -right-10 w-32 h-32 bg-black/[0.01] rounded-full blur-3xl group-hover:bg-black/[0.03] transition-all"></div>
                        <div class="flex items-start justify-between mb-8 relative z-10">
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 rounded-2xl flex items-center justify-center transition-all shadow-inner" :class="getTechIcon(profile.technology_type).bg">
                                    <component :is="getTechIcon(profile.technology_type).icon" class="w-7 h-7" :class="getTechIcon(profile.technology_type).color" />
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold tracking-tight group-hover:text-indigo-600 transition-colors uppercase">{{ profile.name }}</h3>
                                    <p class="text-[9px] font-black text-[#86868b] uppercase tracking-widest mt-1">{{ profile.technology_type }} Core Protocol</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-[9px] font-black text-[#86868b] uppercase tracking-widest">Monthly Rate</p>
                                <p class="text-2xl font-black tracking-tight">${{ profile.price }}</p>
                            </div>
                        </div>

                        <!-- Speed Matrix -->
                        <div class="grid grid-cols-2 gap-4 relative z-10">
                            <div class="p-4 bg-black/[0.01] border border-black/5 rounded-2xl group/speed">
                                <div class="flex items-center gap-2 mb-1">
                                    <ArrowDownCircle class="w-3.5 h-3.5 text-emerald-500" />
                                    <span class="text-[9px] font-black text-[#86868b] uppercase tracking-widest">Download</span>
                                </div>
                                <p class="text-xl font-bold tracking-tighter group-hover/speed:translate-y-[-2px] transition-transform">{{ profile.speed_down }} <span class="text-[10px] text-[#86868b]">MBPS</span></p>
                            </div>
                            <div class="p-4 bg-black/[0.01] border border-black/5 rounded-2xl group/speed">
                                <div class="flex items-center gap-2 mb-1">
                                    <ArrowUpCircle class="w-3.5 h-3.5 text-indigo-500" />
                                    <span class="text-[9px] font-black text-[#86868b] uppercase tracking-widest">Upload</span>
                                </div>
                                <p class="text-xl font-bold tracking-tighter group-hover/speed:translate-y-[-2px] transition-transform">{{ profile.speed_up }} <span class="text-[10px] text-[#86868b]">MBPS</span></p>
                            </div>
                        </div>
                    </div>

                    <!-- Meta Stripe -->
                    <div class="px-8 py-6 border-y border-black/5 bg-black/[0.01] grid grid-cols-3 gap-4">
                        <div class="text-center">
                            <p class="text-[8px] font-black text-[#86868b] uppercase tracking-[0.1em] mb-1">Cycle</p>
                            <p class="text-[10px] font-bold">{{ profile.duration_days || 30 }} Days</p>
                        </div>
                        <div class="text-center">
                            <p class="text-[8px] font-black text-[#86868b] uppercase tracking-[0.1em] mb-1">Quota</p>
                            <p class="text-[10px] font-bold">{{ profile.data_limit_mb ? (profile.data_limit_mb/1024).toFixed(1) + ' GB' : 'Unlimited' }}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-[8px] font-black text-[#86868b] uppercase tracking-[0.1em] mb-1">Edge Nodes</p>
                            <p class="text-[10px] font-bold">{{ (profile.routers?.length || 0) + (profile.mikrotik_servers?.length || 0) }}</p>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="p-4 bg-white mt-auto flex items-center justify-between border-t border-black/[0.02]">
                        <div class="flex items-center gap-2">
                            <Link 
                                :href="route('broadband.profiles.edit', profile.id)" 
                                class="w-10 h-10 flex items-center justify-center text-[#86868b] hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all"
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
                            :href="route('broadband.users.index', { package_id: profile.id })" 
                            class="px-5 py-2.5 text-[10px] font-black uppercase tracking-widest text-indigo-600 hover:bg-indigo-600 hover:text-white rounded-xl transition-all flex items-center gap-2 group/btn"
                        >
                            <Users class="w-3.5 h-3.5" />
                            Active Leases
                            <ChevronRight class="w-3.5 h-3.5 group-hover/btn:translate-x-1 transition-transform" />
                        </Link>
                    </div>
                </div>

                <!-- Initialization Placeholder -->
                <Link 
                    :href="route('broadband.profiles.create')" 
                    class="apple-card border-dashed border-2 border-black/5 bg-transparent flex flex-col items-center justify-center p-12 group hover:border-black/10 transition-all hover:bg-black/[0.01]"
                >
                    <div class="w-16 h-16 bg-black/5 rounded-3xl flex items-center justify-center text-[#86868b] group-hover:scale-110 group-hover:bg-black group-hover:text-white transition-all mb-4">
                        <Plus class="w-8 h-8 font-black" />
                    </div>
                    <p class="text-[11px] font-black uppercase tracking-[0.2em] text-[#86868b]">Define New Service Tier</p>
                    <p class="text-[9px] font-medium text-[#86868b]/60 mt-2 max-w-[200px] text-center">Provision a high-integrity speed profile across the edge network.</p>
                </Link>
            </div>
        </div>
    </AppleLayout>
</template>
