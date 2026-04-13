<script setup>
import { ref, computed } from 'vue';
import { useForm, Head, Link } from '@inertiajs/vue3';
import AppleLayout from '@/Layouts/AppleLayout.vue';
import { 
    ChevronLeft, 
    RefreshCw, 
    Zap, 
    History, 
    DollarSign, 
    Database, 
    Calendar,
    CheckCircle2
} from 'lucide-vue-next';

const props = defineProps({
    client: Object,
    packages: Array,
    currency: String,
});

const form = useForm({
    renew_mode: 'extend',
    package_id: '',
    duration_days: '30',
    data_limit: '',
    price: '',
});

const updateFromPackage = () => {
    if (!form.package_id) return;
    const pkg = props.packages.find(p => p.id == form.package_id);
    if (pkg) {
        form.duration_days = pkg.duration || 30;
        form.price = pkg.price || 0;
        form.data_limit = pkg.data_limit_mb ? (pkg.data_limit_mb / 1024).toFixed(0) : '';
    }
};

const submit = () => {
    form.post(route('crm.clients.renew.post', props.client.id));
};

</script>

<template>
    <AppleLayout title="Subscriber Renewal">
        <Head title="Contract Renewal" />

        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="flex items-center gap-6 mb-10">
                <Link 
                    :href="route('crm.clients.show', client.id)" 
                    class="w-12 h-12 apple-card flex items-center justify-center text-[#86868b] hover:text-black transition-all group"
                >
                    <ChevronLeft class="w-6 h-6 group-hover:-translate-x-1 transition-transform" />
                </Link>
                <div>
                    <h1 class="text-3xl font-bold tracking-tight mb-1">Contract Renewal</h1>
                    <div class="flex items-center gap-3">
                        <span class="text-[10px] font-black uppercase tracking-widest text-[#86868b]">{{ client.username }}</span>
                        <div class="h-1 w-1 bg-[#86868b] rounded-full"></div>
                        <span class="text-[10px] font-black uppercase tracking-widest text-[var(--app-accent)]">{{ client.name || 'Subscriber' }}</span>
                    </div>
                </div>
            </div>

            <!-- Main Portal -->
            <div class="apple-card overflow-hidden">
                <div class="h-1.5 bg-[var(--app-accent)]"></div>
                
                <form @submit.prevent="submit" class="p-10 space-y-10">
                    <!-- Current Network State -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6 bg-black/[0.02] rounded-2xl border border-black/5">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center">
                                <Calendar class="w-5 h-5" />
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-[#86868b] uppercase tracking-widest mb-0.5">Termination Date</p>
                                <p class="text-sm font-bold">{{ client.expires_at ? client.expires_at.split('T')[0] : 'Non-Expiring' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center">
                                <Database class="w-5 h-5" />
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-[#86868b] uppercase tracking-widest mb-0.5">Quota Allocated</p>
                                <p class="text-sm font-bold">{{ client.data_limit ? (client.data_limit / 1024 / 1024 / 1024).toFixed(2) + ' GB' : 'Unlimited' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Renewal Logic Selector -->
                    <div class="space-y-4">
                        <label class="text-[10px] font-black text-[#86868b] uppercase tracking-[0.2em] ml-2">Select Provisioning Mode</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <button 
                                type="button"
                                @click="form.renew_mode = 'extend'"
                                class="flex flex-col p-6 rounded-[20px] transition-all text-right border-2"
                                :class="form.renew_mode === 'extend' ? 'border-[var(--app-accent)] bg-blue-50/50' : 'border-black/5 hover:border-black/10'"
                            >
                                <span class="text-sm font-bold mb-1" :class="form.renew_mode === 'extend' ? 'text-blue-700' : 'text-black'">Synchronized Extension</span>
                                <span class="text-[10px] font-medium text-[#86868b]">Append new cycles to the existing pulse.</span>
                            </button>

                            <button 
                                type="button"
                                @click="form.renew_mode = 'reset'"
                                class="flex flex-col p-6 rounded-[20px] transition-all text-right border-2"
                                :class="form.renew_mode === 'reset' ? 'border-rose-500 bg-rose-50/50' : 'border-black/5 hover:border-black/10'"
                            >
                                <span class="text-sm font-bold mb-1" :class="form.renew_mode === 'reset' ? 'text-rose-700' : 'text-black'">Provisioning Reset</span>
                                <span class="text-[10px] font-medium text-[#86868b]">Ignore existing state and start a fresh cycle.</span>
                            </button>
                        </div>
                    </div>

                    <!-- Logic Layer Selection -->
                    <div class="space-y-4 pt-4">
                        <label class="text-[10px] font-black text-[#86868b] uppercase tracking-[0.2em] ml-2">Service Logic Tier (Package)</label>
                        <div class="relative group">
                            <RefreshCw class="w-4 h-4 absolute left-4 top-1/2 -translate-y-1/2 text-[#86868b] group-focus-within:rotate-180 transition-transform duration-500" />
                            <select 
                                v-model="form.package_id" 
                                @change="updateFromPackage"
                                class="apple-input h-14 pl-12 font-bold text-sm bg-blue-50/20 border-blue-100"
                            >
                                <option value="">Manual Override / No Package</option>
                                <option v-for="pkg in packages" :key="pkg.id" :value="pkg.id">
                                    {{ pkg.name }} — ({{ pkg.price }} {{ currency }})
                                </option>
                            </select>
                        </div>
                    </div>

                    <!-- Parameter Matrix -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-4">
                        <div class="space-y-2">
                             <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-1">Lifecycle Duration (Days)</label>
                             <div class="relative">
                                <Clock class="w-4 h-4 absolute left-4 top-1/2 -translate-y-1/2 text-[#86868b]" />
                                <input v-model="form.duration_days" type="number" class="apple-input h-12 pl-12 font-bold" placeholder="30">
                             </div>
                        </div>

                        <div class="space-y-2">
                             <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-1">Intelligence Quota (GB)</label>
                             <div class="relative">
                                <Database class="w-4 h-4 absolute left-4 top-1/2 -translate-y-1/2 text-[#86868b]" />
                                <input v-model="form.data_limit" type="number" class="apple-input h-12 pl-12 font-bold" placeholder="Unlimited">
                             </div>
                        </div>

                        <div class="md:col-span-2 space-y-2">
                             <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-1">Settlement Evaluation ({{ currency }})</label>
                             <div class="relative">
                                <DollarSign class="w-4 h-4 absolute left-4 top-1/2 -translate-y-1/2 text-[#86868b]" />
                                <input v-model="form.price" type="number" step="0.01" class="apple-input h-12 pl-12 font-mono text-lg font-bold bg-emerald-50/20 border-emerald-100" placeholder="0.00">
                             </div>
                             <p class="text-[8px] font-black text-[#86868b] uppercase tracking-[0.2em] mt-2 ml-2">A paid ledger (invoice) will be generated for this amount instantly.</p>
                        </div>
                    </div>

                    <!-- Execution Trigger -->
                    <div class="pt-10 flex flex-col md:flex-row items-center gap-6 border-t border-black/5">
                        <div class="flex-1">
                             <h4 class="text-xs font-bold mb-1">Authorization Required</h4>
                             <p class="text-[10px] text-[#86868b] font-medium leading-relaxed">By clicking execute, you authorize the network synchronization of this subscriber's lease.</p>
                        </div>
                        <button 
                            @click="submit"
                            :disabled="form.processing"
                            class="w-full md:w-auto px-12 py-4 bg-black text-white rounded-full font-bold shadow-xl shadow-black/10 hover:bg-gray-800 transition-all active:scale-95 flex items-center justify-center gap-2"
                        >
                            <Zap class="w-4 h-4 fill-white" />
                            Execute Renewal Pulse
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppleLayout>
</template>
