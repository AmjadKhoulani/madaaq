<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AppleLayout from '@/Layouts/AppleLayout.vue';
import { 
    HardDrive, 
    Download, 
    ArrowUpCircle, 
    ArrowDownCircle, 
    Cpu, 
    ShieldCheck, 
    Clock, 
    FileArchive, 
    FileCode, 
    CheckCircle2,
    Calendar,
    ArrowRight
} from 'lucide-vue-next';
import Pagination from '@/Components/Pagination.vue';

const props = defineProps({
    backups: Object,
});

const formatBytes = (bytes, decimals = 2) => {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const dm = decimals < 0 ? 0 : decimals;
    const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
};

</script>

<template>
    <AppleLayout title="Recovery Intelligence">
        <Head title="Infrastructure Backup Stream" />

        <div class="max-w-[1400px] mx-auto pb-20">
            <!-- Strategic Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-8 mb-12">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight mb-2">Recovery Intelligence</h1>
                    <p class="text-[var(--app-secondary)] font-medium flex items-center gap-2">
                        <HardDrive class="w-4 h-4" />
                        Managing automated infrastructure state snapshots from edge controllers
                    </p>
                </div>
            </div>

            <!-- Backups Grid/Table -->
            <div class="apple-card overflow-hidden bg-white/50 backdrop-blur-xl border border-black/5">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-black/[0.02] bg-black/[0.01]">
                                <th class="px-8 py-5 text-[10px] font-black text-[#86868b] uppercase tracking-widest">Artifact Identity</th>
                                <th class="px-8 py-5 text-[10px] font-black text-[#86868b] uppercase tracking-widest">Edge Origin</th>
                                <th class="px-8 py-5 text-[10px] font-black text-[#86868b] uppercase tracking-widest">Payload Spec</th>
                                <th class="px-8 py-5 text-[10px] font-black text-[#86868b] uppercase tracking-widest">Temporal Point</th>
                                <th class="px-8 py-5 text-[10px] font-black text-[#86868b] uppercase tracking-widest text-right">Extraction</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-black/[0.01]">
                            <tr v-for="backup in backups.data" :key="backup.id" class="group hover:bg-black/[0.01] transition-all">
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-5">
                                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center shadow-inner group-hover:scale-110 transition-transform" :class="backup.type === 'backup' ? 'bg-black text-white' : 'bg-indigo-600 text-white'">
                                            <component :is="backup.type === 'backup' ? FileArchive : FileCode" class="w-6 h-6" />
                                        </div>
                                        <div>
                                            <p class="font-bold text-sm tracking-tight group-hover:text-black transition-colors">{{ backup.filename }}</p>
                                            <p class="text-[9px] font-bold text-[#86868b] uppercase tracking-widest mt-0.5">{{ backup.type }} protocol</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-black/[0.03] flex items-center justify-center text-black/40">
                                            <Cpu class="w-4 h-4" />
                                        </div>
                                        <p class="text-xs font-bold uppercase tracking-tight text-black">{{ backup.server?.name || 'Local Controller' }}</p>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <p class="text-xs font-mono font-bold text-black/60">{{ formatBytes(backup.size) }}</p>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-3 text-[#86868b]">
                                        <Calendar class="w-3.5 h-3.5" />
                                        <span class="text-[11px] font-medium">{{ new Date(backup.created_at).toLocaleString() }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <a 
                                        :href="route('network.backups.download', backup.id)" 
                                        class="inline-flex items-center justify-center w-10 h-10 apple-card text-[#86868b] hover:text-black hover:bg-white transition-all shadow-sm group/btn"
                                    >
                                        <Download class="w-5 h-5 group-hover/btn:translate-y-0.5 transition-transform" />
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Empty State -->
                <div v-if="backups.data.length === 0" class="py-24 text-center">
                    <div class="w-20 h-20 bg-black/5 rounded-[2.5rem] flex items-center justify-center mx-auto mb-6 text-[#86868b]">
                        <HardDrive class="w-10 h-10" />
                    </div>
                    <h3 class="text-lg font-bold tracking-tight text-black mb-1">Matrix Void Detecting</h3>
                    <p class="text-sm text-[#86868b] max-w-xs mx-auto">No recovery artifacts detected in NOC segment. Infrastructure snapshots will appear here automatically.</p>
                </div>

                <!-- Strategic Pagination -->
                <div class="px-8 py-6 border-t border-black/[0.02] bg-black/[0.01]">
                    <Pagination :links="backups.links" />
                </div>
            </div>
        </div>
    </AppleLayout>
</template>
