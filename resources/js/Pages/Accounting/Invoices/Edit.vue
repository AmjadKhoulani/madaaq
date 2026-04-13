<script setup>
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
    Save
} from 'lucide-vue-next';

const props = defineProps({
    invoice: Object,
    clients: Array
});

const form = useForm({
    amount: props.invoice.amount,
    due_date: props.invoice.due_date,
    status: props.invoice.status,
});

const submit = () => {
    form.put(route('accounting.invoices.update', props.invoice.id));
};

</script>

<template>
    <AppleLayout :title="'Modify ' + invoice.invoice_number">
        <Head :title="'Fiscal Governance: ' + invoice.invoice_number" />

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
                        <h1 class="text-3xl font-bold tracking-tight mb-1">Adjust Ledger Artifact</h1>
                        <p class="text-[var(--app-secondary)] font-medium">Updating commitment parameters for {{ invoice.invoice_number }}</p>
                    </div>
                </div>
            </div>

            <form @submit.prevent="submit" class="space-y-8">
                <!-- 1. Subscriber Context -->
                <div class="apple-card p-10 bg-black text-white relative overflow-hidden">
                    <div class="absolute -top-10 -right-10 w-64 h-64 bg-white/5 rounded-full blur-3xl"></div>
                    <div class="relative z-10 flex items-center gap-8">
                        <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center text-3xl">
                            👤
                        </div>
                        <div>
                             <h4 class="text-xl font-bold uppercase tracking-tight">{{ invoice.client?.name || 'Anonymous Peer' }}</h4>
                             <p class="text-[10px] font-black text-white/40 uppercase tracking-widest mt-1">
                                Identified Subscriber Protocol • {{ invoice.client?.username }}
                             </p>
                        </div>
                    </div>
                </div>

                <!-- 2. Fiscal Parameters -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Value Extraction -->
                    <div class="apple-card p-10 space-y-10">
                         <div class="flex items-center gap-3">
                            <div class="w-1.5 h-6 bg-emerald-600 rounded-full"></div>
                            <h3 class="text-sm font-bold tracking-tight uppercase">Value Extraction</h3>
                        </div>

                        <div class="space-y-8">
                            <div class="space-y-4">
                                <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Value Committed (Amount)</label>
                                <div class="relative">
                                    <input v-model="form.amount" type="number" step="0.01" class="apple-input h-14 pl-16 font-bold text-lg" required>
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

                    <!-- Settlement State -->
                    <div class="apple-card p-10 space-y-10">
                         <div class="flex items-center gap-3">
                            <div class="w-1.5 h-6 bg-amber-500 rounded-full"></div>
                            <h3 class="text-sm font-bold tracking-tight uppercase">Settlement Override</h3>
                        </div>

                        <div class="space-y-8">
                            <div class="space-y-4">
                                <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Current Settlement Protocol</label>
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
                                         <p class="text-[9px] font-black text-white/40 uppercase tracking-widest mb-1">Fiscal ID</p>
                                         <p class="text-xl font-mono font-bold tracking-widest">{{ invoice.invoice_number }}</p>
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
                             <h4 class="text-lg font-bold uppercase tracking-tight">Sync Maintenance</h4>
                             <p class="text-[9px] font-black text-white/40 uppercase tracking-widest mt-1">
                                Updating fiscal parameters in global ledger and subscriber history.
                             </p>
                        </div>
                    </div>
                    <div class="relative z-10 flex items-center gap-6">
                        <Link 
                            :href="route('accounting.invoices.index')" 
                            class="px-8 py-4 bg-white/10 text-white font-bold text-xs uppercase tracking-widest rounded-2xl hover:bg-white/20 transition-all active:scale-95"
                        >
                            Discard Protocol
                        </Link>
                        <button 
                            type="submit" 
                            class="px-12 py-5 bg-white text-black font-bold text-xs uppercase tracking-[0.2em] rounded-2xl shadow-2xl hover:bg-emerald-500 hover:text-white transition-all hover:scale-105 active:scale-95 disabled:opacity-50 flex items-center gap-2"
                            :disabled="form.processing"
                        >
                            <Save class="w-4 h-4" /> Sync Artifact
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </AppleLayout>
</template>
