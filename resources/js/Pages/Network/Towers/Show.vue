<script setup>
import { ref, computed } from 'vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import AppleLayout from '@/Layouts/AppleLayout.vue';
import { 
    ChevronLeft, 
    Settings, 
    LayoutDashboard as Overview, 
    Router, 
    Zap, 
    DollarSign, 
    Wifi, 
    Activity, 
    Users, 
    HardDrive,
    TowerControl as TowerIcon,
    Radio,
    Battery,
    Sun,
    Server,
    Trash2,
    Plus,
    ExternalLink,
    CheckCircle2,
    Monitor,
    Shield,
    Box
} from 'lucide-vue-next';

const props = defineProps({
    tower: Object,
    currency: String,
});

const activeTab = ref('overview');

const tabs = [
    { id: 'overview', name: 'Overview', icon: Overview },
    { id: 'equipment', name: 'Infrastructure', icon: HardDrive },
    { id: 'power', name: 'Energy', icon: Zap },
    { id: 'costs', name: 'Ledger', icon: DollarSign },
    { id: 'connection', name: 'Uplink', icon: Wifi },
];

const stats = computed(() => [
    { name: 'Hardware Units', value: props.tower.routers?.length || 0, label: 'Routers & APs', icon: Router, color: 'text-blue-600', bg: 'bg-blue-50' },
    { name: 'Wireless SSIDs', value: props.tower.ssids?.length || 0, label: 'Interfaces', icon: Radio, color: 'text-purple-600', bg: 'bg-purple-50' },
    { name: 'Subscribers', value: props.tower.clients?.length || 0, label: 'Active Leases', icon: Users, color: 'text-emerald-600', bg: 'bg-emerald-50' },
    { name: 'Operating Burn', value: props.tower.monthly_rent || 0, label: 'Monthly Fixed', icon: DollarSign, color: 'text-amber-600', bg: 'bg-amber-50' },
]);

const deleteDevice = (id) => {
    if (confirm('Decommission this hardware element? All associated mappings will be purged.')) {
        router.delete(route('network.towers.devices.destroy', [props.tower.id, id]));
    }
};

</script>

<template>
    <AppleLayout :title="tower.name">
        <Head :title="tower.name" />

        <!-- Protocol Header -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-8 mb-10">
            <div class="flex items-center gap-6">
                <Link 
                    :href="route('network.towers.index')" 
                    class="w-12 h-12 apple-card flex items-center justify-center text-[#86868b] hover:text-black transition-all group"
                >
                    <ChevronLeft class="w-6 h-6 group-hover:-translate-x-1 transition-transform" />
                </Link>
                <div class="relative">
                    <div class="flex items-center gap-4 mb-1">
                        <h1 class="text-3xl font-bold tracking-tight">{{ tower.name }}</h1>
                        <span 
                            class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest border flex items-center gap-2"
                            :class="{
                                'bg-emerald-50 text-emerald-600 border-emerald-100': tower.status === 'active',
                                'bg-amber-50 text-amber-600 border-amber-100': tower.status === 'maintenance',
                                'bg-rose-50 text-rose-600 border-rose-100': tower.status === 'inactive'
                            }"
                        >
                             <span v-if="tower.status === 'active'" class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse shadow-[0_0_8px_rgba(16,185,129,0.5)]"></span>
                             {{ tower.status === 'active' ? 'Operational' : tower.status }}
                        </span>
                    </div>
                    <p class="text-[10px] font-black text-indigo-600 uppercase tracking-widest opacity-80 flex items-center gap-2">
                         <MapPin class="w-3 h-3" />
                         Infrastructure Unit: {{ tower.location || tower.city || 'Edge Node' }}
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-4">
                 <Link 
                    :href="route('network.towers.edit', tower.id)" 
                    class="px-6 py-3.5 apple-card text-[#86868b] hover:text-black font-bold text-[11px] uppercase tracking-widest flex items-center gap-3 transition-all"
                 >
                    <Settings class="w-4 h-4" />
                    Modify Site Parameters
                 </Link>
                 <div class="h-10 w-px bg-black/5 mx-2"></div>
                 <div class="flex -space-x-3">
                     <div v-for="i in 3" :key="i" class="w-10 h-10 rounded-2xl bg-black/5 border-2 border-white flex items-center justify-center text-[10px] font-bold text-[#86868b]">
                         {{ i === 3 ? '+' : 'A' }}
                     </div>
                 </div>
            </div>
        </div>

        <!-- Telemetry Orbs -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
            <div 
                v-for="stat in stats" 
                :key="stat.name" 
                class="apple-card p-6 relative overflow-hidden group hover:scale-[1.02] transition-all"
            >
                <div class="absolute -top-10 -right-10 w-24 h-24 rounded-full blur-3xl opacity-20 group-hover:scale-150 transition-all" :class="stat.bg"></div>
                <div class="relative flex items-center justify-between">
                    <div>
                        <p class="text-[9px] font-black text-[#86868b] uppercase tracking-widest mb-1">{{ stat.name }}</p>
                        <h3 class="text-3xl font-bold tracking-tighter">{{ stat.value }}</h3>
                        <p class="text-[8px] font-black border-t border-black/5 pt-1.5 mt-2 uppercase tracking-widest" :class="stat.color">{{ stat.label }}</p>
                    </div>
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center transition-all shadow-inner" :class="stat.bg + ' ' + stat.color">
                        <component :is="stat.icon" class="w-6 h-6" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Strategic Registry Navigator -->
        <div class="apple-card overflow-hidden min-h-[600px] mb-20 bg-white">
            <div class="border-b border-black/5 bg-black/[0.01] p-2">
                <nav class="flex overflow-x-auto no-scrollbar gap-1">
                    <button 
                        v-for="tab in tabs" 
                        :key="tab.id"
                        @click="activeTab = tab.id"
                        class="px-8 py-4 rounded-2xl font-bold text-[10px] uppercase tracking-widest transition-all flex items-center gap-3 whitespace-nowrap"
                        :class="activeTab === tab.id ? 'bg-black text-white shadow-lg' : 'text-[#86868b] hover:bg-black/5 hover:text-black'"
                    >
                        <component :is="tab.icon" class="w-4 h-4" />
                        {{ tab.name }}
                    </button>
                </nav>
            </div>

            <div class="p-8 md:p-12">
                 <!-- 1. Overview Tab -->
                 <div v-if="activeTab === 'overview'" class="space-y-12 animate-in fade-in duration-500">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                        <!-- Site Physics -->
                        <div class="space-y-8">
                            <div class="flex items-center gap-4">
                                <div class="w-1.5 h-6 bg-black rounded-full"></div>
                                <h4 class="text-sm font-bold tracking-tight uppercase">Site Physics</h4>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="p-6 bg-black/[0.02] rounded-3xl border border-black/5">
                                    <p class="text-[9px] font-black text-[#86868b] uppercase tracking-widest mb-1">Architecture</p>
                                    <p class="font-bold text-sm uppercase">
                                        {{ tower.type === 'tower' ? '🗼 Radio Mast' : tower.type === 'building' ? '🏢 Building Structural' : '📍 Pole Support' }}
                                    </p>
                                </div>
                                <div class="p-6 bg-black/[0.02] rounded-3xl border border-black/5">
                                    <p class="text-[9px] font-black text-[#86868b] uppercase tracking-widest mb-1">Elevation</p>
                                    <p class="font-bold text-sm">{{ tower.height || '0' }}m AGL</p>
                                </div>
                                <div class="p-6 bg-black/[0.02] rounded-3xl border border-black/5 col-span-2">
                                    <p class="text-[9px] font-black text-[#86868b] uppercase tracking-widest mb-1">GIS Coordinates</p>
                                    <p class="font-mono font-bold text-sm text-indigo-600 select-all">{{ tower.lat }}, {{ tower.lng }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Uplink Gateway -->
                        <div class="space-y-8">
                            <div class="flex items-center gap-4">
                                <div class="w-1.5 h-6 bg-indigo-600 rounded-full"></div>
                                <h4 class="text-sm font-bold tracking-tight uppercase">Uplink Gateway</h4>
                            </div>
                            <div v-if="tower.mikrotik_server" class="p-8 bg-indigo-600 text-white rounded-[2.5rem] shadow-2xl relative overflow-hidden group">
                                <div class="absolute -bottom-20 -left-20 w-48 h-48 bg-white/10 rounded-full blur-3xl group-hover:scale-125 transition-all"></div>
                                <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                                    <div>
                                        <p class="text-[9px] font-black text-indigo-200 uppercase tracking-widest mb-1">Active Controller</p>
                                        <h5 class="text-2xl font-bold tracking-tight">{{ tower.mikrotik_server.name }}</h5>
                                        <p class="text-xs font-mono font-bold opacity-60 mt-1">{{ tower.mikrotik_server.ip }}</p>
                                    </div>
                                    <Link :href="route('servers.show', tower.mikrotik_server.id)" class="px-6 py-3 bg-white text-indigo-600 rounded-xl font-bold text-[10px] uppercase tracking-widest hover:bg-white/90 transition-all flex items-center gap-2">
                                        Logic Base <ExternalLink class="w-3.5 h-3.5" />
                                    </Link>
                                </div>
                            </div>
                            <div v-else class="p-12 bg-black/[0.02] border border-dashed border-black/10 rounded-[2.5rem] text-center">
                                <p class="text-[10px] font-black text-[#86868b] uppercase tracking-widest">No Logical Gateway Linked</p>
                            </div>
                        </div>
                    </div>

                    <!-- Observer Logs -->
                    <div v-if="tower.notes" class="p-10 bg-black text-white rounded-[2.5rem] relative overflow-hidden">
                        <div class="absolute -top-20 -right-20 w-64 h-64 bg-white/5 rounded-full blur-3xl"></div>
                        <h4 class="text-[9px] font-black text-white/40 uppercase tracking-widest mb-4 flex items-center gap-2">
                            <Activity class="w-4 h-4 text-indigo-400" />
                            Observer Log Integrity
                        </h4>
                        <p class="text-lg font-medium leading-relaxed opacity-90 whitespace-pre-wrap">{{ tower.notes }}</p>
                    </div>
                 </div>

                 <!-- 2. Infrastructure Tab -->
                 <div v-if="activeTab === 'equipment'" class="space-y-12 animate-in fade-in duration-500">
                    <!-- Wireless Sector -->
                    <div class="space-y-8">
                         <div class="flex items-center justify-between">
                             <div class="flex items-center gap-4">
                                <div class="w-1.5 h-6 bg-indigo-600 rounded-full"></div>
                                <h4 class="text-sm font-bold tracking-tight uppercase">Radio Access Units (RF Array)</h4>
                                <span class="px-3 py-1 bg-indigo-50 text-indigo-600 rounded-lg text-[9px] font-black uppercase tracking-widest">{{ tower.wireless_devices?.length || 0 }} Unit</span>
                             </div>
                             <button class="px-6 py-3.5 bg-black text-white rounded-2xl font-bold text-[10px] uppercase tracking-widest flex items-center gap-2 hover:scale-105 active:scale-95 transition-all">
                                <Plus class="w-4 h-4" /> Initialize Radio
                             </button>
                         </div>

                         <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div v-for="device in tower.wireless_devices" :key="device.id" class="p-6 bg-white border border-black/5 rounded-[2.5rem] shadow-sm hover:shadow-xl transition-all group">
                                <div class="flex items-start justify-between mb-8">
                                    <div class="flex items-center gap-5">
                                        <div class="w-14 h-14 bg-indigo-600 rounded-2xl flex items-center justify-center text-white shadow-lg group-hover:rotate-6 transition-transform">
                                            <Radio class="w-7 h-7" />
                                        </div>
                                        <div>
                                            <h5 class="text-lg font-bold tracking-tight">{{ device.name }}</h5>
                                            <div class="flex items-center gap-2 mt-1">
                                                <span class="px-2 py-0.5 bg-indigo-50 text-indigo-600 rounded-lg text-[8px] font-black uppercase tracking-widest">{{ device.mode }}</span>
                                                <span class="text-[10px] font-mono text-[#86868b] font-bold">{{ device.ip }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <button @click="deleteDevice(device.id)" class="w-10 h-10 flex items-center justify-center text-[#86868b] hover:text-rose-500 hover:bg-rose-50 rounded-xl transition-all">
                                        <Trash2 class="w-5 h-5" />
                                    </button>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                     <div class="p-4 bg-black/[0.02] rounded-2xl border border-black/5">
                                        <p class="text-[8px] font-black text-[#86868b] uppercase tracking-widest mb-1">Freq Profile</p>
                                        <p class="font-bold text-sm">{{ device.frequency || '5 GHz' }}</p>
                                     </div>
                                     <div class="p-4 bg-black/[0.02] rounded-2xl border border-black/5">
                                        <p class="text-[8px] font-black text-[#86868b] uppercase tracking-widest mb-1">Subscribers</p>
                                        <p class="font-bold text-sm text-indigo-600">{{ device.clients_count || 0 }} Active</p>
                                     </div>
                                </div>
                            </div>
                         </div>
                    </div>

                    <!-- Backend Switches -->
                     <div class="space-y-8">
                         <div class="flex items-center justify-between">
                             <div class="flex items-center gap-4">
                                <div class="w-1.5 h-6 bg-emerald-600 rounded-full"></div>
                                <h4 class="text-sm font-bold tracking-tight uppercase">Network Commutation (Switches)</h4>
                                <span class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-lg text-[9px] font-black uppercase tracking-widest">{{ tower.switch_devices?.length || 0 }} Unit</span>
                             </div>
                             <button class="px-6 py-3.5 bg-black text-white rounded-2xl font-bold text-[10px] uppercase tracking-widest flex items-center gap-2 hover:scale-105 active:scale-95 transition-all">
                                <Plus class="w-4 h-4" /> Provision Switch
                             </button>
                         </div>

                         <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div v-for="sw in tower.switch_devices" :key="sw.id" class="p-6 bg-white border border-black/5 rounded-[2.5rem] shadow-sm hover:shadow-xl transition-all group">
                                <div class="flex items-center gap-5">
                                    <div class="w-14 h-14 bg-emerald-500 rounded-2xl flex items-center justify-center text-white shadow-lg group-hover:scale-110 transition-transform">
                                        <Box class="w-7 h-7" />
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between">
                                            <h5 class="text-lg font-bold tracking-tight">{{ sw.name }}</h5>
                                            <button @click="deleteDevice(sw.id)" class="text-[#86868b] hover:text-rose-500 transition-colors">
                                                <Trash2 class="w-5 h-5" />
                                            </button>
                                        </div>
                                        <div class="flex items-center gap-3 mt-1">
                                             <span class="text-[9px] font-black uppercase tracking-widest text-[#86868b]">{{ sw.ports_count || 24 }} Port Array</span>
                                             <span class="text-[10px] font-mono text-emerald-600 font-bold">{{ sw.ip || 'INTERNAL_SITE_NODE' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                         </div>
                    </div>
                 </div>

                 <!-- 3. Energy Tab -->
                 <div v-if="activeTab === 'power'" class="space-y-12 animate-in fade-in duration-500">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Batteries -->
                        <div v-if="tower.battery_count > 0" class="p-8 bg-white border border-black/5 rounded-[2.5rem] relative overflow-hidden group shadow-xl">
                            <div class="absolute -top-10 -right-10 w-32 h-32 bg-indigo-500/5 rounded-full blur-3xl group-hover:scale-150 transition-all"></div>
                            <div class="flex items-center gap-4 mb-8">
                                <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center">
                                    <Battery class="w-6 h-6" />
                                </div>
                                <h4 class="text-lg font-bold tracking-tight uppercase">Chemical Energy Cluster</h4>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="p-5 bg-black/[0.02] rounded-2xl border border-black/5">
                                    <p class="text-[9px] font-black text-[#86868b] uppercase tracking-widest mb-1">Cell Bank</p>
                                    <p class="text-xl font-bold tracking-tight">{{ tower.battery_count }} Units</p>
                                </div>
                                <div class="p-5 bg-black/[0.02] rounded-2xl border border-black/5">
                                    <p class="text-[9px] font-black text-[#86868b] uppercase tracking-widest mb-1">Chemistry Profile</p>
                                    <p class="text-sm font-bold font-mono">{{ tower.battery_type }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Solar -->
                        <div v-if="tower.has_solar" class="p-8 bg-white border border-black/5 rounded-[2.5rem] relative overflow-hidden group shadow-xl">
                             <div class="absolute -top-10 -right-10 w-32 h-32 bg-amber-500/5 rounded-full blur-3xl group-hover:scale-150 transition-all"></div>
                            <div class="flex items-center gap-4 mb-8">
                                <div class="w-12 h-12 bg-amber-50 text-amber-500 rounded-2xl flex items-center justify-center">
                                    <Sun class="w-6 h-6" />
                                </div>
                                <h4 class="text-lg font-bold tracking-tight uppercase">Photovoltaic Intake</h4>
                            </div>
                            <div class="grid grid-cols-3 gap-4">
                                <div class="p-4 bg-black/[0.02] rounded-2xl border border-black/5">
                                    <p class="text-[8px] font-black text-[#86868b] uppercase tracking-widest mb-1">Panels</p>
                                    <p class="font-bold text-sm">{{ tower.solar_panels_count }}</p>
                                </div>
                                <div class="p-4 bg-black/[0.02] rounded-2xl border border-black/5">
                                    <p class="text-[8px] font-black text-[#86868b] uppercase tracking-widest mb-1">Unit Pulse</p>
                                    <p class="font-bold text-sm">{{ tower.solar_panel_wattage }}W</p>
                                </div>
                                <div class="p-4 bg-black text-white rounded-2xl shadow-lg">
                                    <p class="text-[8px] font-black text-white/40 uppercase tracking-widest mb-1">Global Load</p>
                                    <p class="font-bold text-sm">{{ tower.total_solar_capacity || (tower.solar_panels_count * tower.solar_panel_wattage) }}W</p>
                                </div>
                            </div>
                        </div>

                        <!-- Generator -->
                        <div v-if="tower.has_generator" class="p-8 bg-white border border-black/5 rounded-[2.5rem] group shadow-xl">
                            <div class="flex items-center gap-4 mb-8">
                                <div class="w-12 h-12 bg-orange-50 text-orange-600 rounded-2xl flex items-center justify-center">
                                    <Zap class="w-6 h-6" />
                                </div>
                                <h4 class="text-lg font-bold tracking-tight uppercase">Combustion Backup</h4>
                            </div>
                            <div class="p-6 bg-black/[0.02] rounded-3xl border border-black/5">
                                <p class="text-[9px] font-black text-[#86868b] uppercase tracking-widest mb-1">Rating / Capacity Protocol</p>
                                <p class="text-lg font-bold tracking-tight">{{ tower.generator_capacity }}</p>
                            </div>
                        </div>
                    </div>
                 </div>

                 <!-- 4. Ledger Tab -->
                 <div v-if="activeTab === 'costs'" class="space-y-12 animate-in fade-in duration-500">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        <!-- Operational Burn -->
                         <div class="p-10 bg-white border border-black/5 rounded-[3rem] shadow-xl relative overflow-hidden group">
                            <div class="absolute -top-10 -right-10 w-40 h-40 bg-indigo-600/[0.03] rounded-full blur-3xl transition-all group-hover:scale-125"></div>
                             <h4 class="text-xl font-bold tracking-tight uppercase mb-10 flex items-center gap-4">
                                <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center">
                                    <DollarSign class="w-6 h-6" />
                                </div>
                                Operational Burn
                             </h4>
                             <div class="space-y-4">
                                <div class="flex items-center justify-between p-5 bg-black/[0.02] rounded-2xl border border-black/5">
                                    <span class="text-[10px] font-black text-[#86868b] uppercase tracking-widest">Site Lease Protocol</span>
                                    <span class="font-bold text-sm">{{ tower.monthly_rent }} {{ currency }}</span>
                                </div>
                                <div class="flex items-center justify-between p-5 bg-black/[0.02] rounded-2xl border border-black/5">
                                    <span class="text-[10px] font-black text-[#86868b] uppercase tracking-widest">Support Maintenance</span>
                                    <span class="font-bold text-sm">{{ tower.monthly_maintenance || 0 }} {{ currency }}</span>
                                </div>
                                <div class="p-8 bg-black text-white rounded-[2rem] shadow-2xl mt-8">
                                    <p class="text-[9px] font-black text-white/40 uppercase tracking-widest mb-1">Consolidated Liability</p>
                                    <h5 class="text-3xl font-bold tracking-tighter">{{ (parseFloat(tower.monthly_rent) || 0) + (parseFloat(tower.monthly_maintenance) || 0) }} {{ currency }}</h5>
                                </div>
                             </div>
                         </div>

                         <!-- CAPEX Investment -->
                         <div class="p-10 bg-white border border-black/5 rounded-[3rem] shadow-xl relative overflow-hidden group">
                             <div class="absolute -top-10 -right-10 w-40 h-40 bg-emerald-600/[0.03] rounded-full blur-3xl transition-all group-hover:scale-125"></div>
                             <h4 class="text-xl font-bold tracking-tight uppercase mb-10 flex items-center gap-4">
                                <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center">
                                    <Activity class="w-6 h-6" />
                                </div>
                                Capital Expenditure
                             </h4>
                             <div class="space-y-4">
                                <div class="flex items-center justify-between p-5 bg-black/[0.02] rounded-2xl border border-black/5">
                                    <span class="text-[10px] font-black text-[#86868b] uppercase tracking-widest">Structural Build CAPEX</span>
                                    <span class="font-bold text-sm">{{ tower.structure_cost || 0 }} {{ currency }}</span>
                                </div>
                                <div class="p-8 bg-emerald-600 text-white rounded-[2rem] shadow-2xl shadow-emerald-100/50 mt-8 relative overflow-hidden">
                                     <div class="absolute -bottom-10 -right-10 w-32 h-32 bg-white/10 rounded-full blur-3xl"></div>
                                     <p class="text-[9px] font-black text-emerald-100 uppercase tracking-widest mb-1">Audit Ledger Maturity</p>
                                     <h5 class="text-3xl font-bold tracking-tighter">{{ tower.total_equipment_cost || tower.structure_cost || 0 }} {{ currency }}</h5>
                                </div>
                             </div>
                         </div>
                    </div>
                 </div>

                 <!-- 5. Connection Tab -->
                 <div v-if="activeTab === 'connection'" class="space-y-12 animate-in fade-in duration-500">
                    <div class="max-w-4xl mx-auto space-y-12">
                        <!-- Primary Gateway -->
                        <div v-if="tower.mikrotik_server" class="p-10 bg-black text-white rounded-[3rem] shadow-2xl relative overflow-hidden group">
                            <div class="absolute -bottom-20 -right-20 w-64 h-64 bg-indigo-600/20 rounded-full blur-3xl group-hover:scale-110 transition-all"></div>
                            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-8">
                                <div class="flex items-center gap-6">
                                    <div class="w-16 h-16 bg-white/10 rounded-3xl flex items-center justify-center border border-white/10">
                                        <Server class="w-8 h-8 text-indigo-400" />
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-black text-white/40 uppercase tracking-widest mb-1">Master Controller (Gateway)</p>
                                        <h5 class="text-2xl font-bold tracking-tight">{{ tower.mikrotik_server.name }}</h5>
                                        <p class="text-xs font-mono font-bold opacity-60 mt-1">{{ tower.mikrotik_server.ip }}</p>
                                    </div>
                                </div>
                                <Link :href="route('servers.show', tower.mikrotik_server.id)" class="px-8 py-4 bg-white text-black rounded-2xl font-bold text-xs uppercase tracking-widest hover:bg-gray-100 transition-all shadow-xl active:scale-95">
                                    Initialize Control
                                </Link>
                            </div>
                        </div>

                        <!-- Backhaul P2P -->
                        <div v-if="tower.connection_type === 'wireless'" class="space-y-8">
                             <div class="flex items-center gap-4">
                                <div class="h-px flex-1 bg-black/5"></div>
                                <span class="text-[10px] font-black text-[#86868b] uppercase tracking-[0.2em]">Point-to-Point Analysis</span>
                                <div class="h-px flex-1 bg-black/5"></div>
                             </div>

                             <div class="grid grid-cols-1 md:grid-cols-2 gap-8 relative">
                                <!-- Signal Path Visual -->
                                <div class="hidden md:block absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-10">
                                    <div class="w-16 h-16 bg-white border border-black/5 rounded-full flex items-center justify-center shadow-xl">
                                        <Wifi class="w-6 h-6 text-indigo-600 animate-pulse" />
                                    </div>
                                </div>

                                <!-- Transmitter -->
                                <div class="p-8 bg-black/[0.02] border border-black/5 rounded-[2.5rem] group hover:bg-white transition-all">
                                    <p class="text-[9px] font-black text-[#86868b] uppercase tracking-widest mb-6">Radio Source (PTP-TX)</p>
                                    <div v-if="tower.transmitter_router" class="flex gap-5">
                                         <div class="w-14 h-14 bg-black text-white rounded-2xl flex items-center justify-center shadow-lg group-hover:rotate-6 transition-transform">
                                            <Radio class="w-7 h-7" />
                                         </div>
                                         <div class="min-w-0">
                                            <h6 class="font-bold text-lg leading-none mb-2">{{ tower.transmitter_router.name }}</h6>
                                            <p class="text-[10px] font-mono text-[#86868b] font-black">{{ tower.transmitter_router.ip }}</p>
                                            <div v-if="tower.transmitter_router.tower" class="mt-4 pt-4 border-t border-black/5">
                                                <p class="text-[8px] font-black text-[#86868b] uppercase mb-1">Located at Site Matrix:</p>
                                                <Link :href="route('network.towers.show', tower.transmitter_router.tower_id)" class="text-xs font-bold text-indigo-600 flex items-center gap-2">
                                                    <span class="w-1 h-3 bg-indigo-600 rounded-full"></span>
                                                    {{ tower.transmitter_router.tower.name }}
                                                </Link>
                                            </div>
                                         </div>
                                    </div>
                                </div>

                                <!-- Receiver -->
                                <div class="p-8 bg-indigo-600 text-white rounded-[2.5rem] shadow-2xl relative overflow-hidden group">
                                     <div class="absolute -bottom-10 -right-10 w-32 h-32 bg-white/10 rounded-full blur-3xl group-hover:scale-125 transition-all"></div>
                                     <p class="text-[9px] font-black text-indigo-200 uppercase tracking-widest mb-6 relative z-10">Terminal Link (PTP-RX)</p>
                                     <div class="flex gap-5 relative z-10">
                                         <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center border border-white/10 group-hover:-rotate-6 transition-transform">
                                            <Wifi class="w-7 h-7" />
                                         </div>
                                         <div>
                                            <h6 class="font-bold text-lg leading-none mb-2">{{ tower.name }} Interface</h6>
                                            <p class="text-[10px] font-mono text-indigo-200 font-bold">{{ tower.receiver_ip || 'PENDING_SIGNAL' }}</p>
                                            <span class="mt-4 inline-block px-3 py-1 bg-white/10 rounded-lg text-[8px] font-black uppercase tracking-widest">{{ tower.receiver_model?.model_name || 'Radio Pulse Unit' }}</span>
                                         </div>
                                     </div>
                                </div>
                             </div>
                        </div>
                    </div>
                 </div>
            </div>
        </div>
    </AppleLayout>
</template>
