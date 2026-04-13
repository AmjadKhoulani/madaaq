<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import AppleLayout from '@/Layouts/AppleLayout.vue';
import { 
    Shield, 
    ShieldPlus, 
    Users, 
    Lock, 
    ShieldCheck, 
    Trash2, 
    Edit3, 
    MoreHorizontal,
    Search,
    ChevronRight,
    ArrowRight
} from 'lucide-vue-next';
import Pagination from '@/Components/Pagination.vue';

const props = defineProps({
    roles: Object,
});

const deleteRole = (id) => {
    if (confirm('Decommission this authority role? This will disrupt all associated subscribers.')) {
        router.delete(route('roles.destroy', id));
    }
};

</script>

<template>
    <AppleLayout title="Authority Matrix">
        <Head title="Staff Roles Governance" />

        <div class="max-w-[1400px] mx-auto pb-20">
            <!-- Strategic Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-8 mb-12">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight mb-2">Authority Matrix</h1>
                    <p class="text-[var(--app-secondary)] font-medium flex items-center gap-2">
                        <Lock class="w-4 h-4" />
                        Configuring permission boundaries across MadaaQ infrastructure
                    </p>
                </div>
                <Link 
                    :href="route('roles.create')" 
                    class="px-8 py-3.5 bg-black text-white rounded-2xl font-bold text-[11px] uppercase tracking-[0.2em] shadow-xl hover:scale-105 active:scale-95 transition-all flex items-center gap-3"
                >
                    <ShieldPlus class="w-4 h-4" /> Synthesize Role
                </Link>
            </div>

            <!-- Role Registry Table -->
            <div class="apple-card overflow-hidden bg-white/50 backdrop-blur-xl border border-black/5">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-black/[0.02] bg-black/[0.01]">
                                <th class="px-8 py-5 text-[10px] font-black text-[#86868b] uppercase tracking-widest">Authority ID (Role)</th>
                                <th class="px-8 py-5 text-[10px] font-black text-[#86868b] uppercase tracking-widest">Internal Protocol</th>
                                <th class="px-8 py-5 text-[10px] font-black text-[#86868b] uppercase tracking-widest text-center">Active Members</th>
                                <th class="px-8 py-5 text-[10px] font-black text-[#86868b] uppercase tracking-widest text-right">Commitment</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-black/[0.01]">
                            <tr v-for="role in roles.data" :key="role.id" class="group hover:bg-black/[0.01] transition-all">
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-5">
                                        <div class="w-12 h-12 rounded-2xl bg-black shadow-inner flex items-center justify-center text-white shrink-0 group-hover:scale-110 transition-transform">
                                            <Shield class="w-6 h-6" />
                                        </div>
                                        <div>
                                            <p class="font-bold text-base tracking-tight group-hover:text-indigo-600 transition-colors capitalize">{{ role.display_name }}</p>
                                            <p class="text-[9px] font-bold text-[#86868b] uppercase tracking-widest mt-0.5 truncate max-w-[300px]">{{ role.description || 'Global administrative authority' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <span class="px-3 py-1 bg-black/[0.03] border border-black/5 rounded-xl text-[10px] font-bold font-mono text-black uppercase">{{ role.name }}</span>
                                </td>
                                <td class="px-8 py-6 text-center">
                                    <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-emerald-50 text-emerald-700 rounded-full border border-emerald-100">
                                        <Users class="w-3.5 h-3.5" />
                                        <span class="text-sm font-black">{{ role.users_count }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                         <Link 
                                            :href="route('roles.edit', role.id)" 
                                            class="w-10 h-10 apple-card flex items-center justify-center text-[#86868b] hover:text-black hover:bg-white transition-all shadow-sm"
                                         >
                                            <Edit3 class="w-4 h-4" />
                                         </Link>
                                         <button 
                                            @click="deleteRole(role.id)"
                                            class="w-10 h-10 apple-card flex items-center justify-center text-[#86868b] hover:text-rose-500 hover:bg-white transition-all shadow-sm"
                                         >
                                            <Trash2 class="w-4 h-4" />
                                         </button>
                                         <div class="h-8 w-px bg-black/[0.03] mx-2"></div>
                                         <ChevronRight class="w-4 h-4 text-black/10 group-hover:text-black group-hover:translate-x-1 transition-all" />
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Empty State -->
                <div v-if="roles.data.length === 0" class="py-24 text-center">
                    <div class="w-20 h-20 bg-black/5 rounded-[2.5rem] flex items-center justify-center mx-auto mb-6 text-[#86868b]">
                        <Shield class="w-10 h-10" />
                    </div>
                    <h3 class="text-lg font-bold tracking-tight text-black mb-1">Matrix Void Detecting</h3>
                    <p class="text-sm text-[#86868b] max-w-xs mx-auto">Initialize a new authority role to begin configuring permission boundaries.</p>
                </div>

                <!-- Strategic Pagination -->
                <div class="px-8 py-6 border-t border-black/[0.02] bg-black/[0.01]">
                    <Pagination :links="roles.links" />
                </div>
            </div>
        </div>
    </AppleLayout>
</template>
