<script setup>
import { ref } from 'vue';
import { Link, Head, useForm } from '@inertiajs/vue3';
import AppleLayout from '@/Layouts/AppleLayout.vue';
import { 
    ChevronLeft, 
    RefreshCw, 
    ShieldAlert, 
    ShieldCheck, 
    Edit3,
    Tally5,
    DollarSign,
    Calendar,
    Wifi,
    Box,
    Globe,
    ExternalLink,
    Activity,
    StickyNote,
    CheckCircle2,
    Copy,
    MessageSquare
} from 'lucide-vue-next';

const props = defineProps({
    client: Object,
    stats: Object,
    usage: Object,
    activeIp: String,
});

const noteForm = useForm({
    content: '',
    type: 'general',
});

const submitNote = () => {
    noteForm.post(route('crm.clients.notes.store', props.client.id), {
        onSuccess: () => noteForm.reset('content'),
        preserveScroll: true,
    });
};

const copyToClipboard = () => {
    const portalUrl = 'http://' + props.client.tenant?.domain;
    const text = `Portal URL: ${portalUrl}\nUsername: ${props.client.username}`;
    
    navigator.clipboard.writeText(text).then(() => {
        alert('Credentials copied to clipboard!');
    });
};

const getStatusColor = (status) => {
    switch (status) {
        case 'active': return 'emerald';
        case 'suspended': return 'rose';
        default: return 'slate';
    }
};

</script>

<template>
    <AppleLayout :title="client.username">
        <Head :title="client.username + ' Profile'" />

        <!-- Profile Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-8 mb-10">
            <div class="flex items-center gap-6">
                <Link 
                    :href="route('crm.clients.index')" 
                    class="w-12 h-12 apple-card flex items-center justify-center text-[#86868b] hover:text-black transition-all group"
                >
                    <ChevronLeft class="w-6 h-6 group-hover:-translate-x-1 transition-transform" />
                </Link>
                <div>
                    <h1 class="text-4xl font-bold tracking-tight mb-1">{{ client.username }}</h1>
                    <div class="flex items-center gap-3">
                        <span class="text-[10px] font-black uppercase tracking-widest text-[#86868b]">{{ client.name || 'Personal Account' }}</span>
                        <div class="h-1 w-1 bg-[#86868b] rounded-full"></div>
                        <span class="text-[10px] font-black uppercase tracking-widest text-[var(--app-accent)]">{{ client.mikrotik_server?.name || 'Edge Node' }}</span>
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-4">
                <Link 
                    :href="route('crm.clients.renew', client.id)" 
                    class="inline-flex items-center gap-2 px-6 py-3 bg-[var(--app-accent)] text-white rounded-full font-bold shadow-lg shadow-blue-200/50 hover:bg-blue-600 transition-all active:scale-95"
                >
                    <RefreshCw class="w-4 h-4" />
                    Renew Contract
                </Link>

                <div class="h-10 w-px bg-black/5 mx-2 hidden md:block"></div>

                <Link 
                    :href="route('crm.clients.toggle-status', client.id)" 
                    method="patch" 
                    as="button"
                    class="px-6 py-3 rounded-full font-bold text-[11px] uppercase tracking-widest transition-all active:scale-95 border"
                    :class="client.status === 'active' 
                        ? 'bg-rose-50 text-rose-600 border-rose-100 hover:bg-rose-100' 
                        : 'bg-emerald-50 text-emerald-600 border-emerald-100 hover:bg-emerald-100'"
                >
                    <component :is="client.status === 'active' ? ShieldAlert : ShieldCheck" class="w-4 h-4 inline-block ml-2" />
                    {{ client.status === 'active' ? 'Deactivate' : 'Activate' }}
                </Link>

                <Link 
                    :href="route('crm.clients.edit', client.id)" 
                    class="w-12 h-12 apple-card flex items-center justify-center text-[#86868b] hover:text-black transition-all"
                >
                    <Edit3 class="w-5 h-5" />
                </Link>
            </div>
        </div>

        <!-- Metric Orbs -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
            <div class="apple-card p-6">
                <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center mb-4">
                    <Tally5 class="w-5 h-5" />
                </div>
                <p class="text-[10px] font-bold text-[#86868b] uppercase tracking-widest mb-1">Total Ledgers</p>
                <div class="text-2xl font-bold tracking-tight">{{ stats.total_invoices }}</div>
            </div>

            <div class="apple-card p-6">
                <div class="w-10 h-10 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mb-4">
                    <DollarSign class="w-5 h-5" />
                </div>
                <p class="text-[10px] font-bold text-[#86868b] uppercase tracking-widest mb-1">Collected</p>
                <div class="text-2xl font-bold tracking-tight text-emerald-600">${{ stats.total_paid }}</div>
            </div>

            <div class="apple-card p-6">
                <div class="w-10 h-10 rounded-full bg-orange-50 text-orange-600 flex items-center justify-center mb-4">
                    <ShieldAlert class="w-5 h-5" />
                </div>
                <p class="text-[10px] font-bold text-[#86868b] uppercase tracking-widest mb-1">Exposure</p>
                <div class="text-2xl font-bold tracking-tight text-orange-600">${{ stats.pending_amount }}</div>
            </div>

            <div class="apple-card p-6">
                <div class="w-10 h-10 rounded-full bg-purple-50 text-purple-600 flex items-center justify-center mb-4">
                    <Calendar class="w-5 h-5" />
                </div>
                <p class="text-[10px] font-bold text-[#86868b] uppercase tracking-widest mb-1">Termination</p>
                <div class="text-[15px] font-bold tracking-tight">{{ client.expires_at ? client.expires_at.split('T')[0] : 'Permanent' }}</div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Data Column -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Core Info Section -->
                <div class="apple-card p-8 relative overflow-hidden">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-1.5 h-6 bg-black rounded-full"></div>
                        <h3 class="text-sm font-bold tracking-tight">Technical Identity</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="p-4 bg-black/5 rounded-[12px] border border-black/5">
                            <p class="text-[9px] font-black text-[#86868b] uppercase tracking-widest mb-1">Username</p>
                            <p class="text-sm font-bold">{{ client.username }}</p>
                        </div>
                        <div class="p-4 bg-black/5 rounded-[12px] border border-black/5">
                            <p class="text-[9px] font-black text-[#86868b] uppercase tracking-widest mb-1">Service Type</p>
                            <p class="text-sm font-bold uppercase">{{ client.type }}</p>
                        </div>
                        <div class="p-4 bg-black/5 rounded-[12px] border border-black/5">
                            <p class="text-[9px] font-black text-[#86868b] uppercase tracking-widest mb-1">Assigned Package</p>
                            <p class="text-sm font-bold text-[var(--app-accent)]">{{ client.package?.name || 'Custom Intelligence' }}</p>
                        </div>
                        <div class="p-4 bg-black/5 rounded-[12px] border border-black/5">
                            <p class="text-[9px] font-black text-[#86868b] uppercase tracking-widest mb-1">Mobile Interface</p>
                            <p class="text-sm font-bold font-mono">{{ client.phone || 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Live Telemetry -->
                <div class="apple-card p-8 border-2 border-[var(--app-accent)]/10 bg-[var(--app-accent)]/5">
                     <div class="flex items-center justify-between mb-8">
                        <div class="flex items-center gap-3">
                            <div class="w-1.5 h-6 bg-[var(--app-accent)] rounded-full"></div>
                            <h3 class="text-sm font-bold tracking-tight">Active Source Telemetry</h3>
                        </div>
                        <div class="flex items-center gap-2">
                             <div class="h-2 w-2 rounded-full" :class="usage?.status === 'online' ? 'bg-emerald-500 animate-pulse' : 'bg-rose-500'"></div>
                             <span class="text-[10px] font-black uppercase tracking-widest text-[#86868b]">{{ usage?.status === 'online' ? 'Active Emission' : 'Offline' }}</span>
                        </div>
                     </div>
                     <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                         <div class="apple-card bg-white p-4">
                             <p class="text-[9px] font-black text-[#86868b] uppercase tracking-[0.2em] mb-1">Dynamic Target IP</p>
                             <p class="text-xs font-bold font-mono text-[var(--app-accent)]">{{ activeIp || 'Detached' }}</p>
                         </div>
                         <div class="apple-card bg-white p-4">
                             <p class="text-[9px] font-black text-[#86868b] uppercase tracking-[0.2em] mb-1">Session Uptime</p>
                             <p class="text-xs font-bold font-mono">{{ usage?.uptime || '00:00:00' }}</p>
                         </div>
                         <div class="apple-card bg-white p-4">
                             <p class="text-[9px] font-black text-[#86868b] uppercase tracking-[0.2em] mb-1">Live Ingress</p>
                             <p class="text-xs font-bold font-mono text-emerald-600">{{ usage?.download_speed || 0 }} Mbps</p>
                         </div>
                         <div class="apple-card bg-white p-4">
                             <p class="text-[9px] font-black text-[#86868b] uppercase tracking-[0.2em] mb-1">Live Egress</p>
                             <p class="text-xs font-bold font-mono text-blue-600">{{ usage?.upload_speed || 0 }} Mbps</p>
                         </div>
                     </div>
                     <div v-if="usage?.status === 'online'" class="mt-8 pt-6 border-t border-black/5 flex flex-col sm:flex-row justify-between items-center gap-4">
                         <p class="text-[9px] font-black uppercase tracking-[0.2em] text-[#86868b]">
                             Session Volume: <span class="text-black">{{ (usage?.bytes_out / 1024 / 1024 / 1024).toFixed(3) }} GB</span> Dwn / <span class="text-black">{{ (usage?.bytes_in / 1024 / 1024 / 1024).toFixed(3) }} GB</span> Up
                         </p>
                         <a v-if="client.type === 'pppoe' && activeIp" :href="route('crm.clients.cpe-proxy', { client: client.id, type: 'pppoe' })" target="_blank" class="px-5 py-2.5 bg-black text-white text-[9px] font-black uppercase tracking-widest rounded-full hover:bg-gray-800 transition-all flex items-center gap-2">
                             PPPoE Edge Injection <ExternalLink class="w-3.5 h-3.5" />
                         </a>
                     </div>
                </div>

                <!-- Equipment Intelligence Card (Dual Mode) -->
                <div class="apple-card p-8 border-2 border-black/5">
                    <div class="flex items-center justify-between mb-8">
                         <div class="flex items-center gap-3">
                            <div class="w-1.5 h-6 bg-[var(--app-accent)] rounded-full"></div>
                            <h3 class="text-sm font-bold tracking-tight">Active Nodes & Proxies</h3>
                        </div>
                        <div class="flex items-center gap-2">
                             <div class="h-2 w-2 rounded-full" :class="client.status === 'active' ? 'bg-emerald-500 animate-pulse' : 'bg-slate-300'"></div>
                             <span class="text-[10px] font-black uppercase tracking-widest text-[#86868b]">{{ client.status === 'active' ? 'Live' : 'Offline' }}</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- CPE: Internal -->
                        <div class="space-y-4">
                            <div class="flex items-center gap-2 text-[10px] font-black text-[#86868b] uppercase tracking-widest mb-2">
                                <Box class="w-3.5 h-3.5" />
                                CPE: Gateway
                            </div>
                            <div class="apple-card bg-black/[0.02] p-4">
                                <p class="text-[9px] font-black text-[#86868b] uppercase tracking-widest mb-1">Hardware IP</p>
                                <p class="text-sm font-bold font-mono">{{ client.cpe_ip || 'Node Detached' }}</p>
                                
                                <div v-if="client.cpe_ip" class="mt-4 pt-4 border-t border-black/5 flex items-center justify-between">
                                    <div class="text-[10px] font-bold text-[#86868b]">
                                        <p class="uppercase">{{ client.cpe_username || 'admin' }}</p>
                                        <p class="text-[8px] opacity-70">Secured Access</p>
                                    </div>
                                    <a 
                                        :href="route('crm.clients.cpe-proxy', { client: client.id, type: 'cpe' })" 
                                        target="_blank"
                                        class="px-4 py-2 bg-black text-white text-[9px] font-black uppercase tracking-widest rounded-full hover:bg-gray-800 transition-all flex items-center gap-2"
                                    >
                                        Webfig <ExternalLink class="w-3 h-3" />
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Receiver: Outdoor -->
                        <div class="space-y-4">
                            <div class="flex items-center gap-2 text-[10px] font-black text-[#86868b] uppercase tracking-widest mb-2">
                                <Wifi class="w-3.5 h-3.5" />
                                Radio: External
                            </div>
                            <div class="apple-card bg-white p-4">
                                <p class="text-[9px] font-black text-[#86868b] uppercase tracking-widest mb-1">Subscriber Target IP</p>
                                <p class="text-sm font-bold font-mono text-emerald-600">{{ client.receiver_ip || 'Passive Connection' }}</p>

                                <div v-if="client.receiver_ip" class="mt-4 pt-4 border-t border-black/5 flex items-center justify-between">
                                    <div class="text-[10px] font-bold text-[#86868b]">
                                        <p class="uppercase">{{ client.receiver_username || 'admin' }}</p>
                                        <p class="text-[8px] opacity-70">External Node</p>
                                    </div>
                                    <a 
                                        :href="route('crm.clients.cpe-proxy', { client: client.id, type: 'receiver' })" 
                                        target="_blank"
                                        class="px-4 py-2 border-2 border-black/5 text-black text-[9px] font-black uppercase tracking-widest rounded-full hover:bg-black hover:text-white transition-all flex items-center gap-2"
                                    >
                                        SXT Control <ExternalLink class="w-3 h-3" />
                                    </a>
                                </div>
                                <div v-else class="mt-4 py-4 text-center border-2 border-dashed border-black/5 rounded-xl">
                                    <p class="text-[9px] font-bold text-[#86868b] italic">No Outdoor Equipment</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Portal Access -->
                <div v-if="client.tenant?.is_subdomain_enabled" class="apple-card p-8">
                     <div class="flex items-center justify-between mb-8">
                        <div class="flex items-center gap-3">
                            <div class="w-1.5 h-6 bg-emerald-500 rounded-full"></div>
                            <h3 class="text-sm font-bold tracking-tight">Subscriber Portal</h3>
                        </div>
                        <div class="flex items-center gap-3">
                            <button @click="copyToClipboard" class="p-2 hover:bg-black/5 rounded-full transition-colors">
                                <Copy class="w-4 h-4 text-[#86868b]" />
                            </button>
                            <Link 
                                :href="route('crm.clients.send-credentials', client.id)" 
                                method="post" 
                                as="button"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white rounded-full text-[9px] font-black uppercase tracking-widest shadow-lg shadow-emerald-500/20 active:scale-95"
                            >
                                <MessageSquare class="w-3.5 h-3.5" />
                                Send Auth
                            </Link>
                        </div>
                    </div>

                    <div class="bg-black/[0.02] p-6 rounded-[14px] flex flex-col md:flex-row items-center justify-between gap-4">
                        <div class="flex-1 min-w-0">
                            <p class="text-[8px] font-black text-[#86868b] uppercase tracking-[0.2em] mb-1">Access URL</p>
                            <p class="text-xs font-bold font-mono text-[#86868b] truncate">http://{{ client.tenant?.domain }}</p>
                        </div>
                        <div class="h-8 w-px bg-black/5 hidden md:block"></div>
                        <div class="flex-1 min-w-0">
                            <p class="text-[8px] font-black text-[#86868b] uppercase tracking-[0.2em] mb-1">Identity Key</p>
                            <p class="text-xs font-black text-black select-all">{{ client.username }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Detail Sidebar -->
            <div class="space-y-6">
                <!-- Activity Pulse -->
                <div class="apple-card p-8">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-1.5 h-6 bg-blue-500 rounded-full"></div>
                        <h3 class="text-sm font-bold tracking-tight">Activity Pulse</h3>
                    </div>
                    <div class="space-y-6 relative before:absolute before:right-0.5 before:top-2 before:bottom-0 before:w-px before:bg-black/5 pr-6">
                        <div v-for="activity in client.activities" :key="activity.id" class="relative group">
                            <div class="absolute -right-7 top-1.5 w-2 h-2 rounded-full border-2 border-[var(--app-bg)] bg-blue-500 shadow-sm z-10 transition-transform group-hover:scale-125"></div>
                            <p class="text-xs font-bold leading-relaxed mb-1">{{ activity.description }}</p>
                            <p class="text-[9px] font-black uppercase tracking-widest text-[#86868b]">{{ activity.created_at_human || 'Just now' }}</p>
                        </div>

                         <div v-if="!client.activities?.length" class="text-center py-10">
                            <Activity class="w-8 h-8 text-black/5 mx-auto mb-4" />
                            <p class="text-[10px] font-black text-[#86868b] uppercase tracking-[0.2em]">System Static</p>
                        </div>
                    </div>
                </div>

                <!-- Observer Log -->
                <div class="apple-card p-8">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-1.5 h-6 bg-black rounded-full"></div>
                        <h3 class="text-sm font-bold tracking-tight">Intelligence Log</h3>
                    </div>
                    
                    <form @submit.prevent="submitNote" class="space-y-4">
                        <select 
                            v-model="noteForm.type"
                            class="w-full h-10 px-4 bg-black/5 rounded-[12px] text-xs font-bold border-none outline-none focus:ring-1 focus:ring-black"
                        >
                            <option value="general">General</option>
                            <option value="technical">Technical</option>
                            <option value="billing">Billing</option>
                        </select>
                        <textarea 
                            v-model="noteForm.content"
                            placeholder="Add classified observation..."
                            rows="4"
                            class="w-full p-4 bg-black/5 rounded-[16px] text-xs font-medium border-none outline-none focus:ring-1 focus:ring-black resize-none"
                        ></textarea>
                        <button 
                            type="submit" 
                            :disabled="noteForm.processing || !noteForm.content"
                            class="w-full h-11 bg-black text-white rounded-full font-bold text-[10px] uppercase tracking-widest disabled:opacity-50 transition-all active:scale-95"
                        >
                            Commit Observation
                        </button>
                    </form>

                    <div class="mt-8 space-y-4">
                        <div v-for="note in client.client_notes" :key="note.id" class="p-4 bg-black/[0.02] rounded-[14px] border border-black/5">
                            <p class="text-xs font-medium leading-relaxed mb-3">{{ note.content }}</p>
                            <div class="flex items-center justify-between text-[8px] font-black uppercase tracking-[0.2em] text-[#86868b]">
                                <span :class="note.type === 'technical' ? 'text-purple-600' : (note.type === 'billing' ? 'text-emerald-600' : '')">{{ note.type }}</span>
                                <span>{{ note.created_at_human || 'New' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppleLayout>
</template>

<style scoped>
.dir-ltr { direction: ltr; }
</style>
