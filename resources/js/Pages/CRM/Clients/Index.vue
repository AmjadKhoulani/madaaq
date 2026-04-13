<script setup>
import { ref, watch } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import AppleLayout from '@/Layouts/AppleLayout.vue';
import { 
    Search, 
    UserPlus, 
    Filter, 
    MoreHorizontal, 
    Eye,
    ChevronLeft,
    ChevronRight
} from 'lucide-vue-next';
import debounce from 'lodash/debounce';

const props = defineProps({
    clients: Object,
});

const filters = ref({
    search: usePage().props.auth.query?.search || '',
    status: usePage().props.auth.query?.status || '',
    type: usePage().props.auth.query?.type || '',
});

const search = ref(filters.value.search);

watch(search, debounce((value) => {
    router.get(route('crm.clients.index'), { search: value, status: filters.value.status, type: filters.value.type }, {
        preserveState: true,
        replace: true
    });
}, 300));

const filterData = () => {
    router.get(route('crm.clients.index'), { search: search.value, status: filters.value.status, type: filters.value.type }, {
        preserveState: true,
        replace: true
    });
};

</script>

<template>
    <AppleLayout title="Subscriber Management">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
            <div>
                <h1 class="text-3xl font-bold tracking-tight mb-2">Subscriber Management</h1>
                <p class="text-[var(--app-secondary)] font-medium">Manage and monitor CRM client relationships.</p>
            </div>
            <Link 
                :href="route('crm.clients.create')" 
                class="inline-flex items-center gap-2 px-6 py-3 bg-black text-white rounded-full font-bold shadow-lg hover:bg-gray-800 transition-all active:scale-95"
            >
                <UserPlus class="w-4 h-4" />
                Add Subscriber
            </Link>
        </div>

        <!-- Filters & Search -->
        <div class="apple-card p-4 mb-8 flex flex-col md:flex-row items-center gap-4">
            <div class="flex-1 w-full relative group">
                <Search class="w-4 h-4 absolute left-4 top-1/2 -translate-y-1/2 text-[#86868b]" />
                <input 
                    v-model="search"
                    type="text" 
                    placeholder="Search by name, email, phone..." 
                    class="w-full h-11 bg-black/5 rounded-[12px] pl-10 pr-4 text-sm focus:ring-1 focus:ring-black outline-none border-none transition-all"
                >
            </div>
            
            <div class="flex gap-4 w-full md:w-auto">
                <select 
                    v-model="filters.status"
                    @change="filterData"
                    class="h-11 px-4 bg-black/5 rounded-[12px] text-xs font-bold border-none outline-none focus:ring-1 focus:ring-black"
                >
                    <option value="">All Statuses</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
                
                <select 
                    v-model="filters.type"
                    @change="filterData"
                    class="h-11 px-4 bg-black/5 rounded-[12px] text-xs font-bold border-none outline-none focus:ring-1 focus:ring-black"
                >
                    <option value="">All Types</option>
                    <option value="pppoe">PPPoE</option>
                    <option value="hotspot">Hotspot</option>
                </select>
            </div>
        </div>

        <!-- Table Card -->
        <div class="apple-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-right text-xs">
                    <thead>
                        <tr class="text-[#86868b] border-b border-[var(--app-border)] uppercase tracking-widest font-black">
                            <th class="px-8 py-5 text-right">Subscriber</th>
                            <th class="px-8 py-5 text-right">Network Type</th>
                            <th class="px-8 py-5 text-right">Service Package</th>
                            <th class="px-8 py-5 text-right text-center">Protocol Status</th>
                            <th class="px-8 py-5 text-right">Expiry</th>
                            <th class="px-8 py-5"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[var(--app-border)]">
                        <tr v-for="client in clients.data" :key="client.id" class="hover:bg-black/5 transition-all group">
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-[12px] bg-slate-100 flex items-center justify-center text-slate-900 font-bold border border-black/5">
                                        {{ client.username.substring(0, 2).toUpperCase() }}
                                    </div>
                                    <div class="min-w-0">
                                        <div class="text-[13px] font-bold text-black truncate max-w-[180px]">
                                            {{ client.username }}
                                        </div>
                                        <div class="text-[10px] text-[#86868b] uppercase tracking-tighter">{{ client.name || 'Personal Account' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-5">
                                <span 
                                    :class="[
                                        'px-2.5 py-1 rounded-full text-[9px] font-black uppercase tracking-widest border',
                                        client.type === 'pppoe' ? 'bg-blue-50 text-blue-600 border-blue-100' : 'bg-purple-50 text-purple-600 border-purple-100'
                                    ]"
                                >
                                    {{ client.type }}
                                </span>
                            </td>
                            <td class="px-8 py-5 font-bold text-gray-700">
                                {{ client.package?.name || 'Custom Intelligence' }}
                            </td>
                            <td class="px-8 py-5 text-center">
                                <div v-if="client.status === 'active'" class="inline-flex items-center px-3 py-1 bg-emerald-50 text-emerald-600 rounded-full border border-emerald-100 font-black text-[9px] uppercase tracking-widest">
                                    <span class="w-1.5 h-1.5 bg-emerald-600 rounded-full ml-1.5 animate-pulse"></span>
                                    Operational
                                </div>
                                <div v-else class="inline-flex items-center px-3 py-1 bg-slate-50 text-slate-400 rounded-full border border-slate-100 font-black text-[9px] uppercase tracking-widest">
                                    <span class="w-1.5 h-1.5 bg-slate-300 rounded-full ml-1.5"></span>
                                    Suspended
                                </div>
                            </td>
                            <td class="px-8 py-5 font-mono text-[#86868b] font-medium">
                                {{ client.expires_at ? client.expires_at.split('T')[0] : 'Permanent' }}
                            </td>
                            <td class="px-8 py-5 text-left">
                                <Link 
                                    :href="route('crm.clients.show', client.id)"
                                    class="p-2 hover:bg-white rounded-full transition-all inline-block shadow-sm"
                                >
                                    <Eye class="w-4 h-4 text-[#86868b]" />
                                </Link>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Empty State -->
            <div v-if="clients.data.length === 0" class="py-20 flex flex-col items-center gap-4">
                <div class="w-16 h-16 rounded-[20px] bg-slate-50 flex items-center justify-center text-slate-200">
                    <Search class="w-8 h-8" />
                </div>
                <div class="text-center">
                    <h3 class="font-bold">No subscribers found</h3>
                    <p class="text-[10px] text-[#86868b] uppercase tracking-widest mt-1">Adjust filters or refine your search</p>
                </div>
            </div>

            <!-- Pagination -->
            <div v-if="clients.links.length > 3" class="px-8 py-4 border-t border-[var(--app-border)] flex items-center justify-center gap-2">
                <Link 
                    v-for="(link, k) in clients.links" 
                    :key="k"
                    :href="link.url || '#'"
                    v-html="link.label"
                    class="h-8 px-3 flex items-center justify-center rounded-[8px] text-[10px] font-bold transition-all"
                    :class="[
                        link.active ? 'bg-black text-white shadow-md' : 'hover:bg-black/5 text-[#86868b]',
                        !link.url ? 'opacity-30 cursor-not-allowed' : ''
                    ]"
                />
            </div>
        </div>
    </AppleLayout>
</template>
