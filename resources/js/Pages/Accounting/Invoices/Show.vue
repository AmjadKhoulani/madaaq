<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AppleLayout from '@/Layouts/AppleLayout.vue';
import { 
    ChevronLeft, 
    Printer, 
    Download, 
    FileText, 
    Calendar, 
    User, 
    Zap, 
    ShieldCheck,
    CreditCard,
    Globe,
    CheckCircle2,
    Clock
} from 'lucide-vue-next';

const props = defineProps({
    invoice: Object
});

const printInvoice = () => {
    window.print();
};

const getStatusDetails = (status) => {
    switch (status) {
        case 'paid': return { label: 'Settled', color: 'text-emerald-600', bg: 'bg-emerald-50', icon: CheckCircle2 };
        case 'unpaid': return { label: 'Outstanding', color: 'text-amber-600', bg: 'bg-amber-50', icon: Clock };
        default: return { label: status, color: 'text-gray-600', bg: 'bg-gray-50', icon: FileText };
    }
};

</script>

<template>
    <AppleLayout title="Fiscal Artifact">
        <Head :title="'Invoice: ' + invoice.invoice_number" />

        <div class="max-w-4xl mx-auto pb-24">
            <!-- Navigation Header (Hidden on Print) -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-8 mb-12 print:hidden">
                <div class="flex items-center gap-6">
                    <Link 
                        :href="route('accounting.invoices.index')" 
                        class="w-12 h-12 apple-card flex items-center justify-center text-[#86868b] hover:text-black transition-all group"
                    >
                        <ChevronLeft class="w-6 h-6 group-hover:-translate-x-1 transition-transform" />
                    </Link>
                    <div>
                        <h1 class="text-3xl font-bold tracking-tight mb-1">Fiscal Artifact</h1>
                        <p class="text-[var(--app-secondary)] font-medium">Protocol record {{ invoice.invoice_number }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                     <button 
                        @click="printInvoice"
                        class="px-8 py-3.5 bg-black text-white rounded-2xl font-bold text-[11px] uppercase tracking-[0.2em] shadow-xl hover:scale-105 active:scale-95 transition-all flex items-center gap-3"
                     >
                        <Printer class="w-4 h-4" /> Print Protocol
                     </button>
                </div>
            </div>

            <!-- Invoice Artifact -->
            <div id="invoice-artifact" class="apple-card bg-white p-12 md:p-20 shadow-2xl relative overflow-hidden print:shadow-none print:p-0 print:border-none">
                <!-- Status Watermark (Hidden on Print usually, or subtle) -->
                <div class="absolute -top-10 -right-10 w-64 h-64 opacity-[0.03] rotate-12 pointer-events-none">
                    <ShieldCheck class="w-full h-full text-black" />
                </div>

                <!-- 1. Header Protocol -->
                <div class="flex flex-col md:flex-row justify-between gap-12 mb-20">
                    <div class="space-y-6">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 bg-black rounded-2xl flex items-center justify-center text-white text-2xl font-black shadow-xl">M</div>
                            <div>
                                <h2 class="text-2xl font-black tracking-tighter uppercase leading-none mb-1">MadaaQ</h2>
                                <p class="text-[10px] font-black text-[#86868b] uppercase tracking-widest">Infrastructure Protocol</p>
                            </div>
                        </div>
                        <div class="space-y-1 text-[#86868b] text-[11px] font-medium leading-relaxed">
                            <p>Edge Operations HQ</p>
                            <p>Damascus Rural, Syria</p>
                            <p>contact@madaaq.net</p>
                        </div>
                    </div>

                    <div class="text-left md:text-right space-y-4">
                         <div 
                            class="inline-flex items-center gap-3 px-6 py-2 rounded-2xl border text-[10px] font-black uppercase tracking-widest"
                            :class="getStatusDetails(invoice.status).bg + ' ' + getStatusDetails(invoice.status).color"
                        >
                            <component :is="getStatusDetails(invoice.status).icon" class="w-4 h-4" />
                            {{ getStatusDetails(invoice.status).label }}
                        </div>
                        <div class="space-y-1">
                            <p class="text-[9px] font-black text-[#86868b] uppercase tracking-widest">Artifact Identity</p>
                            <p class="text-2xl font-mono font-bold tracking-widest uppercase">{{ invoice.invoice_number }}</p>
                        </div>
                    </div>
                </div>

                <!-- 2. Parties Matrix -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-16 mb-20 py-12 border-y border-black/[0.03]">
                    <div class="space-y-6">
                        <p class="text-[10px] font-black text-[#86868b] uppercase tracking-widest flex items-center gap-2">
                            <User class="w-4 h-4" /> Subscriber Protocol
                        </p>
                        <div class="space-y-2">
                            <p class="text-xl font-bold tracking-tight uppercase">{{ invoice.client?.name || 'Anonymous Peer' }}</p>
                            <p class="text-[11px] font-mono text-[#86868b] tracking-wider">{{ invoice.client?.username }}</p>
                            <p class="text-[11px] font-medium text-[#86868b]">{{ invoice.client?.phone || 'No phone record' }}</p>
                        </div>
                    </div>
                    <div class="space-y-6 md:text-right">
                        <p class="text-[10px] font-black text-[#86868b] uppercase tracking-widest flex items-center justify-end gap-2">
                             Temporal Pulse <Calendar class="w-4 h-4" />
                        </p>
                        <div class="space-y-4">
                            <div>
                                <p class="text-[9px] font-black text-[#86868b] uppercase tracking-widest">Genesis (Date)</p>
                                <p class="text-sm font-bold">{{ new Date(invoice.created_at).toLocaleDateString() }}</p>
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-[#86868b] uppercase tracking-widest">Horizon (Due)</p>
                                <p class="text-sm font-bold text-rose-600">{{ new Date(invoice.due_date).toLocaleDateString() }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. Service Extraction (Items) -->
                <div class="space-y-8 mb-20">
                    <p class="text-[10px] font-black text-[#86868b] uppercase tracking-widest">Extracted Service Components</p>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="border-b-2 border-black/5">
                                <tr>
                                    <th class="pb-6 text-[10px] font-black uppercase tracking-widest">Velocity Spec</th>
                                    <th class="pb-6 text-[10px] font-black uppercase tracking-widest text-center">Protocol Unit</th>
                                    <th class="pb-6 text-[10px] font-black uppercase tracking-widest text-right">Extracted Value</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-black/[0.01]">
                                <tr v-if="invoice.client?.package" class="group">
                                    <td class="py-10">
                                        <div class="flex items-center gap-4">
                                            <div class="w-12 h-12 bg-black/5 rounded-2xl flex items-center justify-center text-indigo-600">
                                                <Zap class="w-6 h-6" />
                                            </div>
                                            <div>
                                                <p class="font-bold text-sm uppercase tracking-tight">{{ invoice.client.package.name }}</p>
                                                <p class="text-[10px] font-medium text-[#86868b] uppercase tracking-widest mt-0.5">Throughput Lease: {{ invoice.client.package.speed_down }}M/{{ invoice.client.package.speed_up }}M</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-10 text-center font-bold text-sm tracking-tight text-[#86868b]">1 Cycle</td>
                                    <td class="py-10 text-right font-black text-xl tracking-tight">${{ invoice.amount.toLocaleString() }}</td>
                                </tr>
                                <tr v-else>
                                    <td class="py-10">
                                        <div class="flex items-center gap-4">
                                            <div class="w-12 h-12 bg-black/5 rounded-2xl flex items-center justify-center text-amber-600">
                                                <FileText class="w-6 h-6" />
                                            </div>
                                            <div>
                                                <p class="font-bold text-sm uppercase tracking-tight">Manual Provisioning</p>
                                                <p class="text-[10px] font-medium text-[#86868b] uppercase tracking-widest mt-0.5">Ad-hoc service commitment</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-10 text-center font-bold text-sm tracking-tight text-[#86868b]">1 Artifact</td>
                                    <td class="py-10 text-right font-black text-xl tracking-tight">${{ invoice.amount.toLocaleString() }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- 4. Fiscal Pulse (Total) -->
                <div class="flex flex-col md:flex-row justify-between items-start gap-12 pt-12 border-t border-black/5">
                    <div class="max-w-xs space-y-4">
                        <p class="text-[10px] font-black text-[#86868b] uppercase tracking-widest">Commitment Conditions</p>
                        <p class="text-[11px] font-medium text-[#86868b] leading-relaxed">This artifact confirms the allocation of network throughput within the specified horizon. All values represent extracted technical commitment.</p>
                    </div>
                    <div class="w-full md:w-80 space-y-6">
                        <div class="flex items-center justify-between text-[#86868b]">
                            <span class="text-[10px] font-black uppercase tracking-widest">Gross Value</span>
                            <span class="font-bold text-sm">${{ invoice.amount.toLocaleString() }}</span>
                        </div>
                        <div class="flex items-center justify-between text-[#86868b]">
                            <span class="text-[10px] font-black uppercase tracking-widest">Governance Tax (0%)</span>
                            <span class="font-bold text-sm">$0.00</span>
                        </div>
                        <div class="flex items-center justify-between pt-6 border-t border-black/10">
                            <span class="text-[12px] font-black uppercase tracking-[0.2em] text-black">Net Protocol Artifact</span>
                            <span class="text-3xl font-black tracking-tight text-emerald-600">${{ invoice.amount.toLocaleString() }}</span>
                        </div>
                    </div>
                </div>

                <!-- Print Footer Protocol -->
                <div class="hidden print:block mt-32 text-center">
                    <div class="w-20 h-1 bg-black/10 mx-auto mb-6"></div>
                    <p class="text-[9px] font-black text-[#86868b] uppercase tracking-[0.4em]">MadaaQ Fiscal Handshake Protocol • Integrity First</p>
                </div>
            </div>
        </div>
    </AppleLayout>
</template>

<style>
@media print {
    body {
        background: white !important;
    }
    .print\:hidden {
        display: none !important;
    }
    #invoice-artifact {
        padding: 0 !important;
        margin: 0 !important;
    }
}
</style>
