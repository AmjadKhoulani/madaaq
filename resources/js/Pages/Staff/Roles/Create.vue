<script setup>
import { ref, computed } from 'vue';
import { useForm, Head, Link } from '@inertiajs/vue3';
import AppleLayout from '@/Layouts/AppleLayout.vue';
import { 
    ChevronLeft, 
    ShieldPlus, 
    ShieldCheck, 
    Lock, 
    Zap, 
    Layers, 
    Settings, 
    Save, 
    Info, 
    CheckCircle2,
    Users
} from 'lucide-vue-next';

const props = defineProps({
    permissions: Object,
    baseRoles: Array
});

const form = useForm({
    name: '',
    display_name: '',
    description: '',
    base_role: 'operator',
    additional_roles: [],
});

const submit = () => {
    form.post(route('roles.store'));
};

const toggleAdditionalRole = (roleName) => {
    const index = form.additional_roles.indexOf(roleName);
    if (index === -1) {
        form.additional_roles.push(roleName);
    } else {
        form.additional_roles.splice(index, 1);
    }
};

const isRoleSelected = (roleName) => {
    return form.additional_roles.includes(roleName);
};

</script>

<template>
    <AppleLayout title="Synthesize Authority">
        <Head title="Role Synthesis Protocol" />

        <div class="max-w-4xl mx-auto pb-24">
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
                        <h1 class="text-3xl font-bold tracking-tight mb-1">Synthesize Authority</h1>
                        <p class="text-[var(--app-secondary)] font-medium">Configuring a new permission boundary protocol</p>
                    </div>
                </div>
            </div>

            <form @submit.prevent="submit" class="space-y-8">
                <!-- 1. Identity & Purpose -->
                <div class="apple-card p-10">
                    <div class="flex items-center gap-3 mb-10">
                        <div class="w-1.5 h-6 bg-black rounded-full"></div>
                        <h3 class="text-sm font-bold tracking-tight uppercase">Authority Identity</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        <div class="space-y-4">
                            <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Internal Protocol ID (Unique Name)</label>
                            <input v-model="form.name" type="text" class="apple-input h-14 font-mono font-bold uppercase tracking-tight" placeholder="ADMIN_SUPPORT" required>
                        </div>
                        <div class="space-y-4">
                            <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Display Label (Human Readable)</label>
                            <input v-model="form.display_name" type="text" class="apple-input h-14 font-bold text-lg" placeholder="Support Manager" required>
                        </div>
                        <div class="space-y-4 md:col-span-2">
                            <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Protocol Description</label>
                            <textarea v-model="form.description" class="apple-input py-6 min-h-[120px] font-medium leading-relaxed" placeholder="Briefly describe the authority boundaries of this role..."></textarea>
                        </div>
                    </div>
                </div>

                <!-- 2. Synthesis Logic -->
                <div class="apple-card p-10">
                    <div class="flex items-center gap-3 mb-10">
                        <div class="w-1.5 h-6 bg-indigo-600 rounded-full"></div>
                        <h3 class="text-sm font-bold tracking-tight uppercase">Permission Synthesis Logic</h3>
                    </div>

                    <div class="space-y-12">
                        <!-- Base Template Selection -->
                        <div class="space-y-6">
                            <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Foundational Authority Template</label>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <button 
                                    v-for="base in ['admin', 'operator', 'custom']" 
                                    :key="base"
                                    type="button"
                                    @click="form.base_role = base"
                                    class="p-6 rounded-[2rem] border-2 transition-all flex flex-col items-center gap-3 group"
                                    :class="form.base_role === base ? 'bg-black text-white border-black shadow-xl scale-105' : 'bg-white border-black/5 text-[#86868b] hover:bg-black/5'"
                                >
                                    <component :is="base === 'admin' ? ShieldPlus : (base === 'operator' ? Layers : Settings)" class="w-8 h-8 group-hover:rotate-6 transition-transform" />
                                    <span class="text-[9px] font-black uppercase tracking-widest">{{ base }} Template</span>
                                </button>
                            </div>
                        </div>

                        <!-- Additional Role Layering (Only for Custom) -->
                        <div v-if="form.base_role === 'custom'" class="space-y-6 animate-in slide-in-from-top duration-500">
                             <div class="flex items-center justify-between">
                                <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Inheritance Layers (Merge Roles)</label>
                                <span class="text-[8px] font-black text-indigo-500 uppercase tracking-widest">Additive Protocol</span>
                             </div>
                             <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                <button 
                                    v-for="role in baseRoles" 
                                    :key="role.id"
                                    type="button"
                                    @click="toggleAdditionalRole(role.name)"
                                    class="p-5 rounded-2xl border transition-all flex items-center justify-between group"
                                    :class="isRoleSelected(role.name) ? 'bg-indigo-50 text-indigo-700 border-indigo-200' : 'bg-white border-black/5 text-[#86868b] hover:border-black/10'"
                                >
                                    <span class="text-[10px] font-bold uppercase tracking-tight">{{ role.display_name }}</span>
                                    <CheckCircle2 v-if="isRoleSelected(role.name)" class="w-4 h-4 text-indigo-600" />
                                    <Plus v-else class="w-4 h-4 text-black/10 group-hover:text-black/20" />
                                </button>
                             </div>
                        </div>

                        <!-- Insight Card -->
                        <div class="p-8 bg-black text-white rounded-[2.5rem] relative overflow-hidden group">
                            <div class="absolute -top-10 -right-10 w-40 h-40 bg-white/10 rounded-full blur-3xl transition-all group-hover:scale-125"></div>
                            <div class="relative z-10 flex items-center gap-8">
                                <Info class="w-12 h-12 text-white/40" />
                                <div>
                                     <h4 class="text-sm font-bold uppercase tracking-tight">Handshake Integrity</h4>
                                     <p class="text-[10px] opacity-60 mt-1 leading-relaxed">Synthesis automatically merges all underlying permissions from selected templates. Use 'Custom' to layer multiple existing authorities.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. Commitment Protocol -->
                <div class="flex flex-col md:flex-row items-center justify-between gap-10 apple-card p-12 bg-black text-white relative overflow-hidden">
                    <div class="absolute -bottom-20 -left-20 w-64 h-64 bg-white/5 rounded-full blur-3xl"></div>
                    <div class="relative z-10 flex items-center gap-8 text-left">
                        <div class="w-16 h-16 bg-white/10 rounded-[1.5rem] flex items-center justify-center text-3xl">
                            🛡️
                        </div>
                        <div>
                             <h4 class="text-lg font-bold uppercase tracking-tight">Authority Commitment</h4>
                             <p class="text-[9px] font-black text-white/40 uppercase tracking-widest mt-1 leading-relaxed">
                                Committing synthetic role protocol to the global authority matrix.
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
                            :disabled="form.processing || !form.name || !form.display_name"
                        >
                            <Save class="w-4 h-4" /> Commit Protocol
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </AppleLayout>
</template>
