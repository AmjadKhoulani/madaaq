<script setup>
import { ref, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AppleLayout from '@/Layouts/AppleLayout.vue';
import { 
    Plus, 
    MapPin, 
    Activity, 
    TowerControl as Tower, 
    Building2, 
    Box, 
    Home, 
    Navigation,
    Sun,
    Battery,
    Zap,
    ChevronRight,
    Search,
    Filter,
    X,
    Server,
    DollarSign,
    MoreHorizontal,
    Trash2,
    Settings
} from 'lucide-vue-next';

const props = defineProps({
    towers: Array,
    cities: Array,
    stats: Object,
    filters: Object,
});

const filter = ref({
    city: props.filters.city || 'all',
    status: props.filters.status || 'all',
    type: props.filters.type || 'all',
});

const applyFilters = () => {
    router.get(route('network.towers.index'), filter.value, {
        preserveState: true,
        replace: true,
    });
};

watch(filter, applyFilters, { deep: true });

const getSiteTypeInfo = (type) => {
    const map = {
        'tower': { label: 'Tower Site', icon: Tower, color: 'text-blue-600', bg: 'bg-blue-50' },
        'building': { label: 'Building', icon: Building2, color: 'text-purple-600', bg: 'bg-purple-50' },
        'cabinet': { label: 'Pop / Cabinet', icon: Box, color: 'text-emerald-600', bg: 'bg-emerald-50' },
        'office': { label: 'HQ Office', icon: Home, color: 'text-indigo-600', bg: 'bg-indigo-50' },
        'pole': { label: 'Mast / Pole', icon: Navigation, color: 'text-slate-600', bg: 'bg-slate-50' },
    };
    return map[type] || map['pole'];
};

const deleteTower = (id) => {
    if (confirm('Initiate site decommissioning protocol? This will remove all linked equipment registries.')) {
        router.delete(route('network.towers.destroy', id));
    }
};

</script>

<template>
    <AppleLayout title="Network Site Registry">
        <Head title="Infrastructure Governance" />

        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-8 mb-12">
            <div>
                <h1 class="text-4xl font-bold tracking-tight mb-2">Ground Fleet Governance</h1>
                <p class="text-[var(--app-secondary)] font-medium flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-600 animate-pulse"></span>
                    Infrastructure Management Hub
                </p>
            </div>
            <Link 
                :href="route('network.towers.create')" 
                class="px-8 py-4 bg-black text-white rounded-full font-bold text-sm shadow-2xl hover:bg-gray-800 transition-all active:scale-95 flex items-center gap-2"
            >
                <Plus class="w-5 h-5" />
                Initialize New Site
            </Link>
        </div>

        <!-- Global Telemetry -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
            <div class="apple-card p-8 group">
                <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center mb-6">
                    <Tower class="w-6 h-6" />
                </div>
                <p class="text-[10px] font-black text-[#86868b] uppercase tracking-widest mb-1">Global Site Matrix</p>
                <h3 class="text-3xl font-bold tracking-tight">{{ stats.totalTowers }}</h3>
                <p class="text-[9px] font-black text-indigo-600 uppercase tracking-widest mt-4">Active Deployments</p>
            </div>

            <div class="apple-card p-8 group">
                <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center mb-6">
                    <Activity class="w-6 h-6" />
                </div>
                <p class="text-[10px] font-black text-[#86868b] uppercase tracking-widest mb-1">Performance Index</p>
                <h3 class="text-3xl font-bold tracking-tight">{{ stats.activeTowers }}</h3>
                <div class="mt-4">
                    <span class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-full text-[9px] font-black uppercase tracking-widest border border-emerald-100">
                        {{ stats.totalTowers > 0 ? Math.round((stats.activeTowers / stats.totalTowers) * 100) : 0 }}% Efficiency
                    </span>
                </div>
            </div>

            <div class="apple-card p-8 group">
                <div class="w-12 h-12 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center mb-6">
                    <Settings class="w-6 h-6" />
                </div>
                <p class="text-[10px] font-black text-[#86868b] uppercase tracking-widest mb-1">Protocol Maintenance</p>
                <h3 class="text-3xl font-bold tracking-tight">{{ stats.maintenanceTowers }}</h3>
                <p class="text-[9px] font-black text-rose-600 uppercase tracking-widest mt-4">Action Pipeline</p>
            </div>

            <div class="apple-card p-8 group">
                <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-2xl flex items-center justify-center mb-6">
                    <Server class="w-6 h-6" />
                </div>
                <p class="text-[10px] font-black text-[#86868b] uppercase tracking-widest mb-1">Linked Infrastructure</p>
                <h3 class="text-3xl font-bold tracking-tight">{{ stats.totalRouters }}</h3>
                <p class="text-[9px] font-black text-purple-600 uppercase tracking-widest mt-4">Hardware Nodes</p>
            </div>
        </div>

        <!-- Filter Array -->
        <div class="apple-card p-6 mb-10 bg-black/[0.01]">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="space-y-2">
                    <label class="text-[9px] font-black text-[#86868b] uppercase tracking-widest ml-1">Geospatial District</label>
                    <select v-model="filter.city" class="apple-input h-12 bg-white font-bold text-xs uppercase">
                        <option value="all">Global (All Districts)</option>
                        <option v-for="city in cities" :key="city" :value="city">{{ city }}</option>
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="text-[9px] font-black text-[#86868b] uppercase tracking-widest ml-1">Governance Status</label>
                    <select v-model="filter.status" class="apple-input h-12 bg-white font-bold text-xs uppercase">
                        <option value="all">All States</option>
                        <option value="active">Online / Operational</option>
                        <option value="maintenance">Maintenance Pulse</option>
                        <option value="inactive">Decommissioned</option>
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="text-[9px] font-black text-[#86868b] uppercase tracking-widest ml-1">Site Classification</label>
                    <select v-model="filter.type" class="apple-input h-12 bg-white font-bold text-xs uppercase">
                        <option value="all">All Topologies</option>
                        <option value="tower">Primary Tower</option>
                        <option value="building">Urban Building</option>
                        <option value="cabinet">Logical Cabinet (Pop)</option>
                        <option value="office">HQ / Office</option>
                    </select>
                </div>

                <div class="flex items-end">
                    <button 
                        @click="filter = { city: 'all', status: 'all', type: 'all' }"
                        class="w-full h-12 bg-black/[0.05] hover:bg-black/10 text-black font-bold text-[10px] uppercase tracking-widest rounded-xl transition-all active:scale-95"
                    >
                        Reset Matrix
                    </button>
                </div>
            </div>
        </div>

        <!-- Ground Fleet Grid -->
        <div v-if="towers.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div 
                v-for="tower in towers" 
                :key="tower.id" 
                class="apple-card hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 overflow-hidden flex flex-col group"
            >
                <div class="p-8 pb-6 border-b border-black/5">
                    <div class="flex items-start justify-between mb-6">
                        <div class="flex items-center gap-5">
                            <div class="w-16 h-16 rounded-3xl bg-black text-white flex items-center justify-center group-hover:rotate-6 transition-transform">
                                <component :is="getSiteTypeInfo(tower.type).icon" class="w-8 h-8" />
                            </div>
                            <div class="min-w-0">
                                <h3 class="text-xl font-bold tracking-tight truncate max-w-[150px]">{{ tower.name }}</h3>
                                <p v-if="tower.city" class="text-[9px] font-black text-indigo-600 uppercase tracking-widest mt-1">📍 {{ tower.city }}</p>
                            </div>
                        </div>
                        <div 
                            class="px-3 py-1 rounded-full text-[8px] font-black uppercase tracking-widest border"
                            :class="{
                                'bg-emerald-50 text-emerald-600 border-emerald-100': tower.status === 'active',
                                'bg-amber-50 text-amber-600 border-amber-100': tower.status === 'maintenance',
                                'bg-rose-50 text-rose-600 border-rose-100': tower.status === 'inactive'
                            }"
                        >
                            {{ tower.status }}
                        </div>
                    </div>

                    <p v-if="tower.location" class="text-[11px] font-medium text-[#86868b] line-clamp-2 min-h-[32px]">{{ tower.location }}</p>

                    <div class="mt-4 flex flex-wrap gap-2">
                        <span class="px-3 py-1 bg-black/5 rounded-full text-[8px] font-black uppercase tracking-widest text-[#86868b]">
                            {{ getSiteTypeInfo(tower.type).label }}
                        </span>
                    </div>
                </div>

                <div class="p-8 space-y-4 flex-grow">
                     <div class="flex items-center justify-between p-4 bg-black/[0.02] border border-black/5 rounded-2xl group-hover:bg-white transition-all">
                        <div class="flex items-center gap-3">
                            <Server class="w-4 h-4 text-[#86868b]" />
                            <span class="text-[9px] font-black uppercase tracking-widest text-[#86868b]">Active Nodes</span>
                        </div>
                        <span class="font-bold text-sm">{{ tower.routers_count || 0 }}</span>
                     </div>

                     <div class="grid grid-cols-2 gap-2">
                        <div v-if="tower.has_solar" class="px-3 py-2 bg-yellow-50 text-yellow-700 rounded-xl border border-yellow-100 text-[8px] font-black uppercase tracking-widest flex items-center justify-center gap-1.5">
                            <Sun class="w-3 h-3" /> Solar
                        </div>
                        <div v-if="tower.battery_count > 0" class="px-3 py-2 bg-emerald-50 text-emerald-700 rounded-xl border border-emerald-100 text-[8px] font-black uppercase tracking-widest flex items-center justify-center gap-1.5">
                            <Battery class="w-3.5 h-3.5" /> {{ tower.battery_count }} Cells
                        </div>
                        <div v-if="tower.has_generator" class="px-3 py-2 bg-orange-50 text-orange-700 rounded-xl border border-orange-100 text-[8px] font-black uppercase tracking-widest flex items-center justify-center gap-1.5">
                            <Zap class="w-3 h-3" /> Gen-Set
                        </div>
                     </div>
                </div>

                <div class="px-8 py-5 bg-black/[0.01] border-t border-black/5 flex items-center gap-4">
                     <Link :href="route('network.towers.show', tower.id)" class="flex-1 text-center py-4 bg-black text-white rounded-2xl font-bold text-[10px] uppercase tracking-widest hover:bg-gray-800 transition-all active:scale-95 shadow-lg">
                        View Site Registry
                     </Link>
                     <div class="flex gap-2">
                        <Link :href="route('network.towers.edit', tower.id)" class="w-12 h-12 flex items-center justify-center bg-white border border-black/5 rounded-2xl text-[#86868b] hover:text-black hover:bg-black/5 transition-all">
                            <Settings class="w-5 h-5" />
                        </Link>
                        <button @click="deleteTower(tower.id)" class="w-12 h-12 flex items-center justify-center bg-white border border-black/5 rounded-2xl text-[#86868b] hover:text-rose-500 hover:bg-rose-50 transition-all">
                            <Trash2 class="w-5 h-5" />
                        </button>
                     </div>
                </div>
            </div>
        </div>

        <!-- Empty State -->
        <div v-else class="apple-card py-32 text-center">
            <Tower class="w-16 h-16 text-black/10 mx-auto mb-8" />
            <h2 class="text-3xl font-bold tracking-tight mb-2 uppercase">Fleet Registry Vacant</h2>
            <p class="text-[var(--app-secondary)] font-medium max-w-sm mx-auto mb-10 text-sm">
                No infrastructure sites detected within the active district matrix.
            </p>
            <Link :href="route('network.towers.index')" class="px-10 py-5 bg-black text-white rounded-full font-bold text-sm uppercase tracking-widest shadow-xl hover:bg-gray-800 transition-all">
                Reset Global Matrix
            </Link>
        </div>
    </AppleLayout>
</template>
