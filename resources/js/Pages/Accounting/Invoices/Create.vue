<script setup>
import { ref, computed } from 'vue';
import { useForm, Head, Link } from '@inertiajs/vue3';
import AppleLayout from '@/Layouts/AppleLayout.vue';
import { 
    ChevronLeft, 
    FileText, 
    User, 
    DollarSign, 
    Calendar, 
    Shield, 
    CheckCircle2,
    Clock,
    Search,
    Wifi,
    Zap,
    Users
} from 'lucide-vue-next';

const props = defineProps({
    clients: Array,
    nextInvoiceNumber: String
});

const form = useForm({
    client_id: '',
    amount: '',
    due_date: new Date(Date.now() + 30 * 24 * 60 * 60 * 1000).toISOString().split('T')[0],
    description: '',
    status: 'unpaid',
});

const selectedClient = computed(() => {
    return props.clients.find(c => c.id === form.client_id);
});

const submit = () => {
    form.post(route('accounting.invoices.store'));
};

</script>

<template>
    <AppleLayout title="Initialize Ledger">
        <Head title="Mint New Invoice" />

        <div class="max-w-4xl mx-auto pb-24">
            <!-- Navigation Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-8 mb-12">
                <div class="flex items-center gap-6">
                    <Link 
                        :href="route('accounting.invoices.index')" 
                        class="w-12 h-12 apple-card flex items-center justify-center text-[#86868b] hover:text-black transition-all group"
                    >
                        <ChevronLeft class="w-6 h-6 group-hover:-translate-x-1 transition-transform" />
                    </Link>
                    <div>
                        <h1 class="text-3xl font-bold tracking-tight mb-1">Mint Ledger Artifact</h1>
                        <p class="text-[var(--app-secondary)] font-medium">Initializing a new fiscal commitment record</p>
                    </div>
                </div>
            </div>

            <form @submit.prevent="submit" class="space-y-8">
                <!-- 1. Subscriber Intelligence -->
                <div class="apple-card p-10">
                    <div class="flex items-center gap-3 mb-10">
                        <div class="w-1.5 h-6 bg-indigo-600 rounded-full"></div>
                        <h3 class="text-sm font-bold tracking-tight uppercase">Subscriber Discovery</h3>
                    </div>

                    <div class="space-y-6">
                        <div class="space-y-4">
                            <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Select Targeted Subscriber</label>
                            <div class="relative group">
                                <Users class="absolute left-6 top-1/2 -translate-y-1/2 w-5 h-5 text-indigo-500" />
                                <select v-model="form.client_id" class="apple-input h-14 pl-16 font-bold text-lg uppercase tracking-tight" required>
                                    <option value="">Search Subscriber Registry...</option>
                                    <option v-for="c in clients" :key="c.id" :value="c.id">{{ c.name }} ({{ c.username }})</option>
                                </select>
                            </div>
                        </div>

                        <div v-if="selectedClient" class="p-8 bg-black/[0.02] border border-black/5 rounded-[2.5rem] flex items-center justify-between animate-in slide-in-from-top duration-500">
                             <div class="flex items-center gap-6">
                                 <div class="w-12 h-12 rounded-2xl bg-black text-white flex items-center justify-center shadow-lg">
                                     <User class="w-6 h-6" />
                                 </div>
                                 <div class="space-y-1">
                                     <p class="text-sm font-bold tracking-tight uppercase">{{ selectedClient.name }}</p>
                                     <p class="text-[9px] font-black text-[#86868b] uppercase tracking-widest">{{ selectedClient.type }} Protocol Peer</p>
                                 </div>
                             </div>
                             <div class="flex items-center gap-2">
                                 <div class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></div>
                                 <span class="text-[9px] font-black text-[#86868b] uppercase tracking-widest">Subscriber Validated</span>
                             </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Fiscal Parameters -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Commercial Extraction -->
                    <div class="apple-card p-10 space-y-10">
                         <div class="flex items-center gap-3">
                            <div class="w-1.5 h-6 bg-emerald-600 rounded-full"></div>
                            <h3 class="text-sm font-bold tracking-tight uppercase">Commercial Extraction</h3>
                        </div>

                        <div class="space-y-8">
                            <div class="space-y-4">
                                <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Value Committed (Amount)</label>
                                <div class="relative">
                                    <input v-model="form.amount" type="number" step="0.01" class="apple-input h-14 pl-16 font-bold text-lg" placeholder="45.00" required>
                                    <DollarSign class="absolute left-6 top-1/2 -translate-y-1/2 w-6 h-6 text-emerald-600" />
                                </div>
                            </div>
                            <div class="space-y-4">
                                <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Temporal Horizon (Due Date)</label>
                                <div class="relative">
                                    <input v-model="form.due_date" type="date" class="apple-input h-14 pl-16 font-bold text-lg" required>
                                    <Calendar class="absolute left-6 top-1/2 -translate-y-1/2 w-6 h-6 text-[#86868b]" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Status Protocol -->
                    <div class="apple-card p-10 space-y-10">
                         <div class="flex items-center gap-3">
                            <div class="w-1.5 h-6 bg-amber-500 rounded-full"></div>
                            <h3 class="text-sm font-bold tracking-tight uppercase">Status Protocol</h3>
                        </div>

                        <div class="space-y-8">
                            <div class="space-y-4">
                                <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Initial Settlement State</label>
                                <div class="grid grid-cols-2 gap-4">
                                    <button 
                                        type="button" 
                                        @click="form.status = 'unpaid'"
                                        class="p-6 rounded-3xl border-2 transition-all flex flex-col items-center gap-3 group"
                                        :class="form.status === 'unpaid' ? 'bg-amber-50 text-amber-700 border-amber-500 shadow-xl' : 'bg-white border-black/5 text-[#86868b] hover:bg-black/5'"
                                    >
                                        <Clock class="w-7 h-7" :class="form.status === 'unpaid' ? 'text-amber-500' : 'text-[#86868b]'" />
                                        <span class="text-[9px] font-black uppercase tracking-widest">Outstanding</span>
                                    </button>
                                    <button 
                                        type="button" 
                                        @click="form.status = 'paid'"
                                        class="p-6 rounded-3xl border-2 transition-all flex flex-col items-center gap-3 group"
                                        :class="form.status === 'paid' ? 'bg-emerald-50 text-emerald-700 border-emerald-500 shadow-xl' : 'bg-white border-black/5 text-[#86868b] hover:bg-black/5'"
                                    >
                                        <CheckCircle2 class="w-7 h-7" :class="form.status === 'paid' ? 'text-emerald-500' : 'text-[#86868b]'" />
                                        <span class="text-[9px] font-black uppercase tracking-widest">Settled</span>
                                    </button>
                                </div>
                            </div>

                            <div class="p-8 bg-black text-white rounded-[2.5rem] relative overflow-hidden group">
                                <div class="absolute -top-10 -right-10 w-40 h-40 bg-white/10 rounded-full blur-3xl transition-all group-hover:scale-125"></div>
                                <div class="relative z-10 flex items-center gap-6">
                                    <FileText class="w-12 h-12 text-white/40 rotate-12" />
                                    <div>
                                         <p class="text-[9px] font-black text-white/40 uppercase tracking-widest mb-1">Artifact Identity</p>
                                         <p class="text-xl font-mono font-bold tracking-widest">{{ nextInvoiceNumber }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. Commitment Confirmation -->
                <div class="flex flex-col md:flex-row items-center justify-between gap-10 apple-card p-12 bg-black text-white relative overflow-hidden">
                    <div class="absolute -bottom-20 -left-20 w-64 h-64 bg-white/5 rounded-full blur-3xl"></div>
                    <div class="relative z-10 flex items-center gap-8">
                        <div class="w-16 h-16 bg-white/10 rounded-[1.5rem] flex items-center justify-center text-3xl shrink-0">
                            <Shield class="w-8 h-8 text-emerald-400" />
                        </div>
                        <div>
                             <h4 class="text-lg font-bold uppercase tracking-tight">Ledger Commitment</h4>
                             <p class="text-[9px] font-black text-white/40 uppercase tracking-widest mt-1">
                                Committing fiscal artifact to the global ledger and subscriber history.
                             </p>
                        </div>
                    </div>
                    <div class="relative z-10 flex items-center gap-6">
                        <Link 
                            :href="route('accounting.invoices.index')" 
                            class="px-8 py-4 bg-white/10 text-white font-bold text-xs uppercase tracking-widest rounded-2xl hover:bg-white/20 transition-all active:scale-95"
                        >
                            Abort Minting
                        </Link>
                        <button 
                            type="submit" 
                            class="px-12 py-5 bg-white text-black font-bold text-xs uppercase tracking-[0.2em] rounded-2xl shadow-2xl hover:bg-emerald-500 hover:text-white transition-all hover:scale-105 active:scale-95 disabled:opacity-50"
                            :disabled="form.processing || !form.client_id || !form.amount"
                        >
                            Commit Artifact
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </AppleLayout>
</template>
