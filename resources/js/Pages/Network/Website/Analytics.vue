<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppleLayout from '@/Layouts/AppleLayout.vue';
import { 
    Globe, 
    Activity, 
    ShieldBan,
    Clock,
    Search,
    TrendingUp,
    Filter,
    Table as TableIcon
} from 'lucide-vue-next';

const props = defineProps({
    topWebsites: Array,
    period: String
});

const form = useForm({
    domain: '',
    type: 'domain',
    reason: 'Restricted from Top Analytics'
});

const blockDomain = (domain) => {
    form.domain = domain;
    if(confirm(`Are you sure you want to deploy a block protocol for ${domain} across all edge nodes?`)) {
        form.post(route('network.website.block'), {
            preserveScroll: true,
            onSuccess: () => alert('Domain blocking protocol engaged.')
        });
    }
};

const formatNumber = (num) => {
    return new Intl.NumberFormat().format(num);
};

</script>

<template>
    <AppleLayout title="Global Web Analytics">
        <Head title="Global Extraction Trends" />

        <div class="max-w-[1400px] mx-auto pb-24">
            <!-- Strategic Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-8 mb-12">
                <div>
                     <h1 class="text-4xl font-bold tracking-tight mb-2">Global Web Analytics</h1>
                     <p class="text-[var(--app-secondary)] font-medium flex items-center gap-3">
                        <TrendingUp class="w-4 h-4 text-indigo-500" />
                        Cumulative Top 100 DNS Hit Intelligence
                     </p>
                </div>
                
                <div class="flex items-center gap-2 p-1.5 bg-black/[0.03] backdrop-blur-3xl rounded-[16px] border border-black/5">
                    <Link :href="route('network.website.analytics', { period: '7d' })" 
                          class="px-6 py-2.5 rounded-[12px] text-xs font-bold transition-all text-center"
                          :class="period === '7d' ? 'bg-white text-black shadow-sm' : 'text-[#86868b] hover:text-black hover:bg-black/5'">
                        Past 7 Days
                    </Link>
                    <Link :href="route('network.website.analytics', { period: '30d' })" 
                          class="px-6 py-2.5 rounded-[12px] text-xs font-bold transition-all text-center"
                          :class="period === '30d' ? 'bg-white text-black shadow-sm' : 'text-[#86868b] hover:text-black hover:bg-black/5'">
                        Past 30 Days
                    </Link>
                </div>
            </div>

            <!-- Global Analytics Table -->
            <div class="apple-card overflow-hidden">
                <div class="p-6 border-b border-black/[0.03] bg-black/[0.02] flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-1.5 h-6 bg-indigo-500 rounded-full"></div>
                        <h3 class="text-sm font-bold tracking-tight">Cumulative Extraction Ranking</h3>
                    </div>
                    <div class="text-[10px] font-black text-[#86868b] uppercase tracking-widest flex items-center gap-2">
                        <TableIcon class="w-4 h-4" />
                        Top 100 Domains
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-black/[0.01] border-b border-black/[0.05]">
                                <th class="p-4 text-[10px] font-black text-[#86868b] uppercase tracking-widest select-none text-center w-16">Rank</th>
                                <th class="p-4 text-[10px] font-black text-[#86868b] uppercase tracking-widest select-none">Domain Identity</th>
                                <th class="p-4 text-[10px] font-black text-[#86868b] uppercase tracking-widest select-none">DNS Resolution Density</th>
                                <th class="p-4 text-[10px] font-black text-[#86868b] uppercase tracking-widest select-none text-right">Sanction Protocol</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-black/[0.03]">
                            <tr v-for="(website, index) in topWebsites" :key="website.domain" class="hover:bg-black/[0.01] transition-colors group">
                                <td class="p-4 text-center">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center mx-auto text-[11px] font-black"
                                         :class="{
                                             'bg-amber-100 text-amber-600': index === 0,
                                             'bg-slate-200 text-slate-600': index === 1,
                                             'bg-orange-100 text-orange-600': index === 2,
                                             'bg-black/[0.03] text-[#86868b] group-hover:text-black transition-colors': index > 2
                                         }">
                                         {{ index + 1 }}
                                    </div>
                                </td>
                                <td class="p-4">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-[12px] flex items-center justify-center font-bold text-lg"
                                             :class="index < 3 ? 'bg-indigo-50 text-indigo-600' : 'bg-black/[0.03] text-black/50'">
                                            {{ website.domain.substring(0, 1).toUpperCase() }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-sm text-black group-hover:text-indigo-600 transition-colors">{{ website.domain }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4">
                                    <div class="flex items-center gap-3">
                                        <Activity class="w-4 h-4 text-[#86868b]" />
                                        <span class="font-mono text-xs font-bold">{{ formatNumber(website.total_hits) }} Hits</span>
                                    </div>
                                </td>
                                <td class="p-4 text-right">
                                    <button @click="blockDomain(website.domain)" class="px-4 py-2 border-2 border-rose-500/20 text-rose-600 bg-white hover:bg-rose-50 text-[10px] font-black uppercase tracking-widest rounded-full transition-all active:scale-95 inline-flex items-center gap-2">
                                        <ShieldBan class="w-3.5 h-3.5" />
                                        Block
                                    </button>
                                </td>
                            </tr>
                            <tr v-if="topWebsites.length === 0">
                                <td colspan="4" class="p-16 text-center text-[#86868b]">
                                    <Activity class="w-8 h-8 mx-auto mb-4 opacity-30" />
                                    <p class="text-[10px] font-black uppercase tracking-widest">No analytic payload detected for this period</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-8 flex items-center gap-4 p-4 rounded-2xl bg-blue-50/50 border border-blue-100">
                <div class="w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center shrink-0">
                    <Activity class="w-4 h-4" />
                </div>
                <p class="text-xs font-bold text-blue-900">
                    <span class="text-blue-800 font-black">Intelligence Note:</span> Data is derived from global MikroTik DNS cache snapshots. It represents DNS resolution requests, not absolute traffic volume.
                </p>
            </div>
        </div>
    </AppleLayout>
</template>
