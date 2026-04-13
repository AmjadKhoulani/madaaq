<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import AppleLayout from '@/Layouts/AppleLayout.vue';
import { 
    Users, 
    UserPlus, 
    Shield, 
    Mail, 
    Phone, 
    Briefcase, 
    Edit3, 
    Trash2, 
    CheckCircle2, 
    XCircle,
    MoreHorizontal,
    UserCheck,
    Lock
} from 'lucide-vue-next';
import Pagination from '@/Components/Pagination.vue';

const props = defineProps({
    staff: Object,
});

const deleteStaff = (id) => {
    if (confirm('Are you sure you want to decommission this staff member?')) {
        router.delete(route('staff.destroy', id));
    }
};

</script>

<template>
    <AppleLayout title="Administration Roster">
        <Head title="Staff Governance" />

        <div class="max-w-[1400px] mx-auto pb-20">
            <!-- Strategic Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-8 mb-12">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight mb-2">Administration Roster</h1>
                    <p class="text-[var(--app-secondary)] font-medium flex items-center gap-2">
                        <Users class="w-4 h-4" />
                        Managing authority across MadaaQ infrastructure segment
                    </p>
                </div>
                <Link 
                    :href="route('staff.create')" 
                    class="px-8 py-3.5 bg-black text-white rounded-2xl font-bold text-[11px] uppercase tracking-[0.2em] shadow-xl hover:scale-105 active:scale-95 transition-all flex items-center gap-3"
                >
                    <UserPlus class="w-4 h-4" /> Initialize Member
                </Link>
            </div>

            <!-- Roster Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                <div 
                    v-for="member in staff.data" 
                    :key="member.id"
                    class="apple-card p-8 flex flex-col relative group transition-all hover:scale-[1.03] animate-in fade-in duration-500"
                >
                    <!-- Status Pulse -->
                    <div class="absolute top-6 right-6 flex items-center gap-2">
                        <span 
                            class="px-2 py-0.5 rounded-lg text-[8px] font-black uppercase tracking-widest border"
                            :class="member.is_active ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-rose-50 text-rose-600 border-rose-100'"
                        >
                            {{ member.is_active ? 'Active' : 'Deactivated' }}
                        </span>
                    </div>

                    <!-- Member Identity -->
                    <div class="flex flex-col items-center text-center mb-10 pt-4">
                        <div class="w-24 h-24 rounded-[2.5rem] bg-black text-white flex items-center justify-center text-3xl font-black mb-6 shadow-2xl relative group-hover:rotate-3 transition-transform">
                            {{ member.name.substring(0, 1) }}
                            <div v-if="member.is_active" class="absolute -bottom-1 -right-1 w-6 h-6 bg-emerald-500 rounded-full border-4 border-white flex items-center justify-center">
                                <CheckCircle2 class="w-3 h-3 text-white" />
                            </div>
                        </div>
                        <h3 class="text-xl font-bold tracking-tight uppercase line-clamp-1">{{ member.name }}</h3>
                        <p class="text-[10px] font-black text-[#86868b] uppercase tracking-[0.2em] mt-2 flex items-center gap-2">
                            <Briefcase class="w-3 h-3" /> {{ member.position || 'Protocol Operator' }}
                        </p>
                    </div>

                    <!-- Roles Matrix -->
                    <div class="flex flex-wrap items-center justify-center gap-2 mb-10">
                        <span 
                            v-for="role in member.roles" 
                            :key="role.id"
                            class="px-3 py-1.5 bg-black/[0.03] border border-black/5 rounded-xl text-[9px] font-bold text-black uppercase tracking-tight flex items-center gap-1.5"
                        >
                             <Shield class="w-3 h-3 text-indigo-600" /> {{ role.name }}
                        </span>
                        <span v-if="member.roles.length === 0" class="text-[9px] font-black text-rose-500 uppercase tracking-widest bg-rose-50 px-3 py-1.5 rounded-xl border border-rose-100">No Permissions</span>
                    </div>

                    <!-- Contact Protocols -->
                    <div class="space-y-4 pt-8 border-t border-black/[0.03] mt-auto">
                        <div class="flex items-center gap-4 text-[#86868b]">
                            <Mail class="w-3.5 h-3.5" />
                            <span class="text-[11px] font-medium truncate">{{ member.email }}</span>
                        </div>
                        <div class="flex items-center gap-4 text-[#86868b]">
                            <Phone class="w-3.5 h-3.5" />
                            <span class="text-[11px] font-medium">{{ member.phone || 'NO_PHONE_SIGNAL' }}</span>
                        </div>
                    </div>

                    <!-- Action Hover Reveal -->
                    <div class="absolute inset-x-8 bottom-8 flex items-center gap-3 opacity-0 group-hover:opacity-100 transition-all pointer-events-none group-hover:pointer-events-auto">
                         <Link 
                            :href="route('staff.edit', member.id)" 
                            class="flex-1 py-3 bg-black text-white rounded-xl text-[10px] font-black uppercase tracking-widest text-center shadow-xl hover:bg-black/90 transition-all"
                         >
                            Modify
                         </Link>
                         <button 
                            @click="deleteStaff(member.id)"
                            class="w-12 py-3 bg-rose-50 text-rose-600 rounded-xl flex items-center justify-center hover:bg-rose-500 hover:text-white transition-all border border-rose-200"
                         >
                            <Trash2 class="w-4 h-4" />
                         </button>
                    </div>
                </div>
            </div>

            <!-- Strategic Pagination -->
            <div class="mt-20 apple-card p-6 bg-black/[0.01]">
                <Pagination :links="staff.links" />
            </div>
        </div>
    </AppleLayout>
</template>
