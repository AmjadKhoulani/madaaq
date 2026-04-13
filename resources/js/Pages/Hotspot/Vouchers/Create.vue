<script setup>
import { ref, computed } from 'vue';
import { useForm, Head, Link } from '@inertiajs/vue3';
import AppleLayout from '@/Layouts/AppleLayout.vue';
import { 
    ChevronLeft, 
    Ticket, 
    Zap, 
    Cpu, 
    Hash, 
    Shield, 
    CheckCircle2, 
    ArrowRight,
    Printer,
    DollarSign,
    Box
} from 'lucide-vue-next';

const props = defineProps({
    servers: Array,
    packages: Array
});

const form = useForm({
    server_id: '',
    package_id: '',
    quantity: 10,
    prefix: '',
    length: 6,
});

const selectedPackage = computed(() => {
    return props.packages.find(p => p.id === form.package_id);
});

const selectedServer = computed(() => {
    return props.servers.find(s => s.id === form.server_id);
});

const submit = () => {
    form.post(route('hotspot.vouchers.store'));
};

</script>

<template>
    <AppleLayout title="Mint Vouchers">
        <Head title="Bulk Voucher Minting" />

        <div class="max-w-4xl mx-auto pb-24">
            <!-- Navigation Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-8 mb-12">
                <div class="flex items-center gap-6">
                    <Link 
                        :href="route('hotspot.vouchers.index')" 
                        class="w-12 h-12 apple-card flex items-center justify-center text-[#86868b] hover:text-black transition-all group"
                    >
                        <ChevronLeft class="w-6 h-6 group-hover:-translate-x-1 transition-transform" />
                    </Link>
                    <div>
                        <h1 class="text-3xl font-bold tracking-tight mb-1">Voucher Minting Protocol</h1>
                        <p class="text-[var(--app-secondary)] font-medium">Bulk initialization of guest access secrets</p>
                    </div>
                </div>
            </div>

            <form @submit.prevent="submit" class="space-y-8">
                <!-- 1. Deployment Host -->
                <div class="apple-card p-10">
                    <div class="flex items-center gap-3 mb-10">
                        <div class="w-1.5 h-6 bg-indigo-600 rounded-full"></div>
                        <h3 class="text-sm font-bold tracking-tight uppercase">Deployment Target Cluster</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                         <div class="space-y-4">
                            <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Primary Edge Controller</label>
                            <select v-model="form.server_id" class="apple-input h-14 font-bold uppercase tracking-tight" required>
                                <option value="">Select Target Node...</option>
                                <option v-for="s in servers" :key="s.id" :value="s.id">🖥️ {{ s.name }} ({{ s.ip }})</option>
                            </select>
                        </div>
                        <div class="space-y-4">
                            <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Surface Velocity Tier</label>
                            <select v-model="form.package_id" class="apple-input h-14 font-bold uppercase tracking-tight" required>
                                <option value="">Choose Service Profile...</option>
                                <option v-for="p in packages" :key="p.id" :value="p.id">⚡ {{ p.name }} (${{ p.price }})</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- 2. Synthesis Parameters -->
                <div class="apple-card p-10">
                    <div class="flex items-center gap-3 mb-10">
                        <div class="w-1.5 h-6 bg-amber-500 rounded-full"></div>
                        <h3 class="text-sm font-bold tracking-tight uppercase">Synthesis Configuration</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="space-y-4">
                            <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Batch Quantity</label>
                            <div class="relative">
                                <input v-model="form.quantity" type="number" min="1" max="100" class="apple-input h-14 pl-14 font-bold text-lg">
                                <Hash class="absolute left-6 top-1/2 -translate-y-1/2 w-5 h-5 text-amber-500" />
                            </div>
                            <p class="text-[8px] font-bold text-[#86868b] uppercase tracking-widest ml-2">Max 100 units per cycle</p>
                        </div>
                        <div class="space-y-4">
                            <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Identity Prefix</label>
                            <div class="relative">
                                <input v-model="form.prefix" type="text" maxlength="5" class="apple-input h-14 pl-14 font-mono font-bold text-lg" placeholder="GT-">
                                <Zap class="absolute left-6 top-1/2 -translate-y-1/2 w-5 h-5 text-indigo-500" />
                            </div>
                        </div>
                        <div class="space-y-4">
                            <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Secret Entropy (Length)</label>
                            <div class="relative">
                                <input v-model="form.length" type="number" min="4" max="10" class="apple-input h-14 pl-14 font-bold text-lg">
                                <Shield class="absolute left-6 top-1/2 -translate-y-1/2 w-5 h-5 text-emerald-500" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. Minting Preview -->
                <div v-if="selectedPackage && selectedServer" class="apple-card p-12 bg-black text-white relative overflow-hidden animate-in slide-in-from-top duration-700">
                    <div class="absolute -top-10 -right-10 w-64 h-64 bg-indigo-600/20 rounded-full blur-3xl"></div>
                    <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-10">
                        <div class="flex items-center gap-8">
                            <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center text-3xl">
                                🏭
                            </div>
                            <div>
                                <h4 class="text-xl font-bold tracking-tight">Synthesis Summary</h4>
                                <p class="text-[10px] font-black text-white/40 uppercase tracking-widest mt-1">Ready to transmit batch payload</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-8 text-right">
                            <div>
                                <p class="text-[9px] font-black text-white/40 uppercase tracking-widest mb-1">Fiscal Impact</p>
                                <p class="text-2xl font-black">${{ (selectedPackage.price * form.quantity).toFixed(2) }}</p>
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-white/40 uppercase tracking-widest mb-1">Sync Horizon</p>
                                <p class="text-2xl font-black">{{ selectedServer.name }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-12 p-8 bg-white/5 rounded-[2rem] border border-white/10 flex items-center justify-between group">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center font-mono font-black text-amber-500">
                                {{ form.prefix || '?' }}{{ 'X'.repeat(form.length) }}
                            </div>
                            <p class="text-sm font-medium opacity-60">Example voucher identity structure</p>
                        </div>
                        <CheckCircle2 class="text-emerald-500 group-hover:scale-125 transition-all" />
                    </div>
                </div>

                <!-- 4. Governance Commitment -->
                <div class="flex flex-col md:flex-row items-center justify-between gap-10 apple-card p-12 bg-black text-white relative overflow-hidden">
                    <div class="absolute -bottom-20 -left-20 w-64 h-64 bg-white/5 rounded-full blur-3xl"></div>
                    <div class="relative z-10 flex items-center gap-8">
                        <div class="w-16 h-16 bg-white/10 rounded-[1.5rem] flex items-center justify-center text-3xl shrink-0">
                            <Box class="w-8 h-8 text-amber-400" />
                        </div>
                        <div>
                             <h4 class="text-lg font-bold uppercase tracking-tight">Minting Commitment</h4>
                             <p class="text-[9px] font-black text-white/40 uppercase tracking-widest mt-1">
                                Initiating bulk synthesis and edge API handshake protocol.
                             </p>
                        </div>
                    </div>
                    <div class="relative z-10 flex items-center gap-6">
                        <Link 
                            :href="route('hotspot.vouchers.index')" 
                            class="px-8 py-4 bg-white/10 text-white font-bold text-xs uppercase tracking-widest rounded-2xl hover:bg-white/20 transition-all active:scale-95"
                        >
                            Abort Minting
                        </Link>
                        <button 
                            type="submit" 
                            class="px-12 py-5 bg-white text-black font-bold text-xs uppercase tracking-[0.2em] rounded-2xl shadow-2xl hover:bg-amber-500 hover:text-white transition-all hover:scale-105 active:scale-95 disabled:opacity-50"
                            :disabled="form.processing || !form.server_id || !form.package_id"
                        >
                            Commence Synthesis
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </AppleLayout>
</template>
