<script setup>
import { useForm, Head, Link } from '@inertiajs/vue3';
import AppleLayout from '@/Layouts/AppleLayout.vue';
import { 
    ChevronLeft, 
    Shield, 
    ShieldCheck, 
    Lock, 
    Zap, 
    Save, 
    Settings, 
    Key, 
    Activity, 
    Globe, 
    Users,
    CheckCircle2
} from 'lucide-vue-next';

const props = defineProps({
    role: Object,
    permissions: Array
});

const form = useForm({
    name: props.role.name,
    display_name: props.role.display_name,
    description: props.role.description,
    permissions: props.role.permissions.map(p => p.id),
});

const submit = () => {
    form.put(route('roles.update', props.role.id));
};

const togglePermission = (id) => {
    const index = form.permissions.indexOf(id);
    if (index === -1) {
        form.permissions.push(id);
    } else {
        form.permissions.splice(index, 1);
    }
};

const isPermissionSelected = (id) => {
    return form.permissions.includes(id);
};

// Group permissions by category (assuming naming convention like 'clients.index' or similar)
const groupedPermissions = computed(() => {
    const groups = {};
    props.permissions.forEach(p => {
        const parts = p.name.split('.');
        const category = parts.length > 1 ? parts[0] : 'general';
        if (!groups[category]) groups[category] = [];
        groups[category].push(p);
    });
    return groups;
});

import { computed } from 'vue';

</script>

<template>
    <AppleLayout :title="'Modify ' + role.display_name">
        <Head :title="'Authority Governance: ' + role.display_name" />

        <div class="max-w-5xl mx-auto pb-24">
            <!-- Navigation Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-8 mb-12">
                <div class="flex items-center gap-6">
                    <Link 
                        :href="route('roles.index')" 
                        class="w-12 h-12 apple-card flex items-center justify-center text-[#86868b] hover:text-black transition-all group"
                    >
                        <ChevronLeft class="w-6 h-6 group-hover:-translate-x-1 transition-transform" />
                    </Link>
                    <div>
                        <h1 class="text-3xl font-bold tracking-tight mb-1">Modify Authority Boundary</h1>
                        <p class="text-[var(--app-secondary)] font-medium">Updating protocol limits for {{ role.display_name }}</p>
                    </div>
                </div>
            </div>

            <form @submit.prevent="submit" class="space-y-8">
                <!-- 1. Identity & Context -->
                <div class="apple-card p-10">
                    <div class="flex items-center gap-3 mb-10">
                        <div class="w-1.5 h-6 bg-black rounded-full"></div>
                        <h3 class="text-sm font-bold tracking-tight uppercase">Protocol Identity</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10 text-left">
                        <div class="space-y-4">
                            <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Internal Identity Artifact (Name)</label>
                            <input v-model="form.name" type="text" class="apple-input h-14 font-mono font-bold uppercase tracking-tight" required>
                        </div>
                        <div class="space-y-4">
                            <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Display Label (Human Readable)</label>
                            <input v-model="form.display_name" type="text" class="apple-input h-14 font-bold text-lg" required>
                        </div>
                        <div class="space-y-4 md:col-span-2">
                            <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Authority Boundary Description</label>
                            <textarea v-model="form.description" class="apple-input py-6 min-h-[100px] font-medium leading-relaxed"></textarea>
                        </div>
                    </div>
                </div>

                <!-- 2. Atomic Permissions Matrix -->
                <div class="apple-card p-10">
                    <div class="flex items-center gap-3 mb-10">
                        <div class="w-1.5 h-6 bg-indigo-600 rounded-full"></div>
                        <h3 class="text-sm font-bold tracking-tight uppercase">Atomic Permission Matrix</h3>
                    </div>

                    <div class="space-y-16">
                        <div v-for="(perms, category) in groupedPermissions" :key="category" class="space-y-8">
                             <div class="flex items-center gap-4">
                                <h4 class="text-xs font-black uppercase tracking-widest text-[#86868b]">{{ category }} Protocol</h4>
                                <div class="flex-1 h-px bg-black/[0.03]"></div>
                             </div>

                             <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                <button 
                                    v-for="perm in perms" 
                                    :key="perm.id"
                                    type="button"
                                    @click="togglePermission(perm.id)"
                                    class="p-6 rounded-[2rem] border transition-all flex items-center justify-between group text-left relative overflow-hidden"
                                    :class="isPermissionSelected(perm.id) ? 'bg-black text-white border-black shadow-lg scale-[1.02]' : 'bg-white border-black/5 text-[#86868b] hover:bg-black/[0.02]'"
                                >
                                    <div class="flex items-center gap-4 relative z-10 min-w-0">
                                        <div class="w-8 h-8 rounded-xl flex items-center justify-center transition-all bg-black/[0.03]" :class="isPermissionSelected(perm.id) ? 'bg-white/10' : ''">
                                            <Key class="w-4 h-4" />
                                        </div>
                                        <span class="text-[10px] font-bold uppercase tracking-tight truncate">{{ perm.name.split('.').slice(1).join(' ') || perm.name }}</span>
                                    </div>
                                    <CheckCircle2 v-if="isPermissionSelected(perm.id)" class="w-5 h-5 text-emerald-400 relative z-10" />
                                </button>
                             </div>
                        </div>
                    </div>
                </div>

                <!-- 3. Commitment Protocol -->
                <div class="flex flex-col md:flex-row items-center justify-between gap-10 apple-card p-12 bg-black text-white relative overflow-hidden">
                    <div class="absolute -bottom-20 -left-20 w-64 h-64 bg-white/5 rounded-full blur-3xl"></div>
                    <div class="relative z-10 flex items-center gap-8 text-left">
                        <div class="w-16 h-16 bg-white/10 rounded-[1.5rem] flex items-center justify-center text-3xl shrink-0">
                            <ShieldCheck class="w-8 h-8 text-amber-400" />
                        </div>
                        <div>
                             <h4 class="text-lg font-bold uppercase tracking-tight">Handshake Commitment</h4>
                             <p class="text-[9px] font-black text-white/40 uppercase tracking-widest mt-1 leading-relaxed">
                                Committing updated authority boundaries to the global matrix artifacts.
                             </p>
                        </div>
                    </div>
                    <div class="relative z-10 flex items-center gap-6">
                        <Link 
                            :href="route('roles.index')" 
                            class="px-8 py-4 bg-white/10 text-white font-bold text-xs uppercase tracking-widest rounded-2xl hover:bg-white/20 transition-all active:scale-95"
                        >
                            Abort Changes
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
