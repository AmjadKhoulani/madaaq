<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { 
    ChevronLeft, 
    Printer, 
    User, 
    Zap, 
    Wifi, 
    Clock, 
    ShieldCheck, 
    Globe,
    CreditCard
} from 'lucide-vue-next';

const props = defineProps({
    user: Object
});

const printArtifact = () => {
    window.print();
};

</script>

<template>
    <div class="min-h-screen bg-[#f5f5f7] print:bg-white text-black font-sans selection:bg-black selection:text-white">
        <Head title="Guest Artifact Print" />

        <!-- Navigation Header (Hidden on Print) -->
        <div class="max-w-xl mx-auto px-6 py-12 flex items-center justify-between print:hidden">
            <Link 
                :href="route('hotspot.users.index')" 
                class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-[#86868b] hover:text-black transition-all shadow-sm"
            >
                <ChevronLeft class="w-6 h-6" />
            </Link>
            <button 
                @click="printArtifact"
                class="px-8 h-12 bg-black text-white rounded-2xl font-bold text-xs uppercase tracking-[0.2em] shadow-2xl hover:scale-105 active:scale-95 transition-all flex items-center gap-3"
            >
                <Printer class="w-4 h-4" /> Print Protocol
            </button>
        </div>

        <!-- Print Artifact Container -->
        <div class="max-w-xl mx-auto px-6 pb-24 print:p-0 print:m-0">
            <div class="apple-card bg-white p-12 relative overflow-hidden shadow-2xl print:border print:border-black/10 print:rounded-[2.5rem] print:shadow-none print:break-inside-avoid">
                <!-- Branding Pulse -->
                <div class="flex items-center justify-between mb-16">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-black rounded-2xl flex items-center justify-center text-white text-2xl font-black shadow-xl">M</div>
                        <div>
                            <h2 class="text-xl font-bold tracking-tighter uppercase leading-none mb-1">MadaaQ Guest</h2>
                            <p class="text-[9px] font-black text-[#86868b] uppercase tracking-widest">Access Protocol Artifact</p>
                        </div>
                    </div>
                    <Wifi class="w-8 h-8 text-black/20" />
                </div>

                <!-- Identity Discovery -->
                <div class="space-y-12 mb-16">
                    <div class="space-y-4">
                        <div class="flex items-center gap-2 text-[10px] uppercase font-black tracking-widest text-[#86868b]">
                            <User class="w-4 h-4" /> Participant Identity
                        </div>
                        <p class="text-3xl font-bold tracking-tight uppercase border-b-2 border-black/5 pb-4">{{ user.name }}</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                        <div class="space-y-3">
                            <p class="text-[9px] font-black text-[#86868b] uppercase tracking-widest">Secret Secret (Username)</p>
                            <div class="p-6 bg-black text-white rounded-3xl text-center shadow-xl">
                                <p class="text-2xl font-mono font-bold tracking-[0.2em] uppercase">{{ user.username }}</p>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <p class="text-[9px] font-black text-[#86868b] uppercase tracking-widest">Access Protocol (Password)</p>
                            <div class="p-6 bg-white border-2 border-black rounded-3xl text-center shadow-sm">
                                <p class="text-2xl font-mono font-bold tracking-[0.2em] uppercase">{{ user.password }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Performance Matrix -->
                <div class="pt-12 border-t border-black/[0.03] space-y-10">
                    <div class="flex items-center justify-between">
                         <div class="flex items-center gap-4">
                             <div class="w-10 h-10 rounded-xl bg-amber-500/10 flex items-center justify-center text-amber-600">
                                <Zap class="w-5 h-5" />
                             </div>
                             <div>
                                 <p class="text-[9px] font-black text-[#86868b] uppercase tracking-widest">Velocity Commitment</p>
                                 <p class="text-sm font-bold uppercase tracking-tight">{{ user.package?.name || 'GENERIC_TIER' }}</p>
                             </div>
                         </div>
                         <div class="text-right">
                             <p class="text-[9px] font-black text-[#86868b] uppercase tracking-widest mb-1">Throughput</p>
                             <p class="text-sm font-bold">{{ user.package?.speed_down }}M / {{ user.package?.speed_up }}M</p>
                         </div>
                    </div>

                    <div class="flex items-center justify-between">
                         <div class="flex items-center gap-4">
                             <div class="w-10 h-10 rounded-xl bg-indigo-500/10 flex items-center justify-center text-indigo-600">
                                <Clock class="w-5 h-5" />
                             </div>
                             <div>
                                 <p class="text-[9px] font-black text-[#86868b] uppercase tracking-widest">Temporal Horizon</p>
                                 <p class="text-sm font-bold uppercase tracking-tight">{{ user.expires_at ? new Date(user.expires_at).toLocaleDateString() : 'Permanent Authority' }}</p>
                             </div>
                         </div>
                         <ShieldCheck class="w-6 h-6 text-emerald-500" />
                    </div>
                </div>

                <!-- Instructions Protocol -->
                <div class="mt-16 p-8 bg-black/[0.02] border border-dashed border-black/10 rounded-[2.5rem] space-y-4">
                    <div class="flex items-center gap-3">
                        <Globe class="w-4 h-4 opacity-40" />
                        <p class="text-[10px] font-black uppercase tracking-widest text-[#86868b]">Handshake Instructions</p>
                    </div>
                    <ol class="space-y-2 text-[11px] font-medium leading-relaxed opacity-70 list-decimal pl-4">
                        <li>Initiate connection with "MadaaQ_Infrastructure" SSID.</li>
                        <li>Dispatch provided secret and access protocol credentials.</li>
                        <li>Commence high-integrity network extraction.</li>
                    </ol>
                </div>

                <!-- Print Footer Protocol -->
                <div class="hidden print:block mt-20 text-center">
                    <div class="w-16 h-1 bg-black/10 mx-auto mb-4 rounded-full"></div>
                    <p class="text-[8px] font-black text-[#86868b] uppercase tracking-[0.4em]">MadaaQ Handshake Protocol • Integrity Artifact</p>
                </div>

                <!-- Background Element -->
                <div class="absolute -bottom-20 -left-20 w-64 h-64 bg-black/[0.01] rounded-full blur-3xl pointer-events-none"></div>
            </div>
        </div>
    </div>
</template>

<style>
@media print {
    body {
        background: white !important;
    }
    .print\:hidden {
        display: none !important;
    }
}
</style>
