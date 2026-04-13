<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { 
    ChevronLeft, 
    Printer, 
    Ticket, 
    Wifi, 
    Globe, 
    Zap, 
    ShieldCheck,
    Download
} from 'lucide-vue-next';

const props = defineProps({
    users: Array,
});

const printVouchers = () => {
    window.print();
};

</script>

<template>
    <div class="min-h-screen bg-[#f5f5f7] print:bg-white text-black font-sans selection:bg-black selection:text-white">
        <Head title="Voucher Printing Protocol" />

        <!-- UI Header (Hidden on Print) -->
        <div class="max-w-[1200px] mx-auto px-10 py-12 flex items-center justify-between print:hidden">
            <div class="flex items-center gap-6">
                <Link 
                    :href="route('hotspot.vouchers.index')" 
                    class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-[#86868b] hover:text-black transition-all shadow-sm group"
                >
                    <ChevronLeft class="w-6 h-6 group-hover:-translate-x-1 transition-transform" />
                </Link>
                <div>
                    <h1 class="text-3xl font-bold tracking-tight">Print Preparation</h1>
                    <p class="text-[var(--app-secondary)] font-medium">Preparing {{ users.length }} vouchers for physical distribution</p>
                </div>
            </div>
            <div class="flex items-center gap-4">
                 <button 
                    @click="printVouchers" 
                    class="px-10 py-4 bg-black text-white rounded-2xl font-bold text-xs uppercase tracking-[0.2em] shadow-2xl hover:scale-105 active:scale-95 transition-all flex items-center gap-3"
                 >
                    <Printer class="w-4 h-4" /> Initialize Print
                 </button>
            </div>
        </div>

        <!-- Print Grid -->
        <div class="max-w-[1200px] mx-auto px-10 pb-20 print:p-0 print:m-0">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 print:grid-cols-3 print:gap-4 print:block">
                <div 
                    v-for="user in users" 
                    :key="user.id"
                    class="bg-white rounded-[2rem] border border-black/5 p-8 flex flex-col relative overflow-hidden print:border print:border-black/10 print:rounded-2xl print:mb-4 print:break-inside-avoid print:w-[30%] print:inline-block print:align-top print:mr-[3%]"
                >
                    <!-- Brand Top -->
                    <div class="flex items-center justify-between mb-8">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 bg-black rounded-lg flex items-center justify-center text-[10px] text-white font-black">M</div>
                            <span class="text-[9px] font-black uppercase tracking-widest">MadaaQ Guest</span>
                        </div>
                        <Ticket class="w-4 h-4 text-black/20" />
                    </div>

                    <!-- Identity -->
                    <div class="space-y-6 mb-8">
                        <div class="space-y-1">
                            <p class="text-[8px] font-black text-[#86868b] uppercase tracking-[0.15em]">Access Secret</p>
                            <p class="text-2xl font-mono font-bold tracking-widest border-b-2 border-dashed border-black/10 pb-2 uppercase">{{ user.username }}</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-[8px] font-black text-[#86868b] uppercase tracking-[0.15em]">Password Protocol</p>
                            <p class="text-xl font-mono font-bold tracking-widest uppercase">{{ user.password }}</p>
                        </div>
                    </div>

                    <!-- Specs -->
                    <div class="mt-auto pt-6 border-t border-black/[0.03] space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <Zap class="w-3.5 h-3.5 text-amber-500" />
                                <span class="text-[10px] font-bold uppercase tracking-tight">{{ user.package?.name || 'GENERIC' }}</span>
                            </div>
                            <span class="text-[10px] font-black tracking-tight text-emerald-600">${{ user.package?.price || '0.00' }}</span>
                        </div>
                        <div class="flex items-center gap-2 text-[#86868b]">
                            <Wifi class="w-3.5 h-3.5" />
                            <span class="text-[8px] font-black uppercase tracking-widest">Connect to "MadaaQ_Guest"</span>
                        </div>
                    </div>

                    <!-- Subtle Pattern -->
                    <div class="absolute -bottom-10 -right-10 w-24 h-24 bg-black/[0.02] rounded-full blur-2xl"></div>
                </div>
            </div>
        </div>

        <!-- Footer Protocol (Print Only) -->
        <div class="hidden print:block fixed bottom-10 left-0 right-0 text-center">
            <p class="text-[8px] font-black text-black/20 uppercase tracking-[0.3em]">Generated by MadaaQ Infrastructure Protocol • High Integrity Access</p>
        </div>
    </div>
</template>

<style>
@media print {
    body {
        margin: 0;
        padding: 0;
        -webkit-print-color-adjust: exact;
    }
    .print\:hidden {
        display: none !important;
    }
}
</style>
