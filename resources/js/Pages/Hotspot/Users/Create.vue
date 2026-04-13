<script setup>
import { ref, computed } from 'vue';
import { useForm, Head, Link } from '@inertiajs/vue3';
import AppleLayout from '@/Layouts/AppleLayout.vue';
import { 
    ChevronLeft, 
    UserPlus, 
    Users, 
    Zap, 
    Cpu, 
    Shield, 
    CheckCircle2, 
    Lock, 
    Phone, 
    User,
    Calendar,
    Save
} from 'lucide-vue-next';

const props = defineProps({
    servers: Array,
    packages: Array,
    customers: Array
});

const form = useForm({
    mode: 'new', // new or existing
    customer_id: '',
    name: '',
    phone: '',
    server_id: '',
    package_id: '',
    username: '',
    password: Math.random().toString(36).slice(-6).toUpperCase(),
    expires_at: '',
});

const selectedPackage = computed(() => {
    return props.packages.find(p => p.id === form.package_id);
});

const selectedCustomer = computed(() => {
    return props.customers.find(c => c.id === form.customer_id);
});

const submit = () => {
    form.post(route('hotspot.users.store'));
};

const generatePassword = () => {
    form.password = Math.random().toString(36).slice(-6).toUpperCase();
};

</script>

<template>
    <AppleLayout title="Provision Guest Member">
        <Head title="Guest Provisioning Protocol" />

        <div class="max-w-4xl mx-auto pb-24">
            <!-- Navigation Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-8 mb-12">
                <div class="flex items-center gap-6">
                    <Link 
                        :href="route('hotspot.users.index')" 
                        class="w-12 h-12 apple-card flex items-center justify-center text-[#86868b] hover:text-black transition-all group"
                    >
                        <ChevronLeft class="w-6 h-6 group-hover:-translate-x-1 transition-transform" />
                    </Link>
                    <div>
                        <h1 class="text-3xl font-bold tracking-tight mb-1">Provision Guest Member</h1>
                        <p class="text-[var(--app-secondary)] font-medium">Initializing high-accountability guest access</p>
                    </div>
                </div>
            </div>

            <form @submit.prevent="submit" class="space-y-8">
                <!-- 1. Customer Context -->
                <div class="apple-card p-10">
                    <div class="flex items-center gap-3 mb-10">
                        <div class="w-1.5 h-6 bg-black rounded-full"></div>
                        <h3 class="text-sm font-bold tracking-tight uppercase">Guest Identity Context</h3>
                    </div>

                    <div class="space-y-8">
                        <div class="flex bg-black/[0.03] p-1.5 rounded-2xl w-fit">
                             <button type="button" @click="form.mode = 'new'" class="px-8 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all" :class="form.mode === 'new' ? 'bg-white text-black shadow-lg' : 'text-[#86868b]'">New Participant</button>
                             <button type="button" @click="form.mode = 'existing'" class="px-8 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all" :class="form.mode === 'existing' ? 'bg-white text-black shadow-lg' : 'text-[#86868b]'">Existing Customer</button>
                        </div>

                        <div v-if="form.mode === 'new'" class="grid grid-cols-1 md:grid-cols-2 gap-8 animate-in slide-in-from-top duration-500">
                            <div class="space-y-4 text-left">
                                <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Full Legal Identity</label>
                                <div class="relative group">
                                    <User class="absolute left-6 top-1/2 -translate-y-1/2 w-5 h-5 text-black/20 group-focus-within:text-black transition-colors" />
                                    <input v-model="form.name" type="text" class="apple-input h-14 pl-16 font-bold" placeholder="E.g. Amjad Khoulani" required>
                                </div>
                            </div>
                            <div class="space-y-4 text-left">
                                <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Secure Phone Protocol</label>
                                <div class="relative group">
                                    <div class="absolute left-6 top-1/2 -translate-y-1/2 w-5 h-5 flex items-center justify-center">
                                       <Phone class="w-5 h-5 text-black/20 group-focus-within:text-black transition-colors" />
                                    </div>
                                    <input v-model="form.phone" type="text" class="apple-input h-14 pl-16 font-bold" placeholder="+963..." required>
                                </div>
                            </div>
                        </div>

                        <div v-else class="space-y-4 animate-in slide-in-from-top duration-500 text-left">
                            <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Global Customer Registry</label>
                            <div class="relative group">
                                <Users class="absolute left-6 top-1/2 -translate-y-1/2 w-5 h-5 text-black/20 group-focus-within:text-black transition-colors" />
                                <select v-model="form.customer_id" class="apple-input h-14 pl-16 font-bold uppercase tracking-tight" required>
                                    <option value="">Select Target Identity...</option>
                                    <option v-for="c in customers" :key="c.id" :value="c.id">{{ c.name }} ({{ c.phone }})</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Infrastructure Commitment -->
                <div class="apple-card p-10">
                    <div class="flex items-center gap-3 mb-10">
                        <div class="w-1.5 h-6 bg-indigo-600 rounded-full"></div>
                        <h3 class="text-sm font-bold tracking-tight uppercase">Infrastructure Alignment</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                         <div class="space-y-4 text-left">
                            <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Deployment Edge Cluster</label>
                            <div class="relative group">
                                <Cpu class="absolute left-6 top-1/2 -translate-y-1/2 w-5 h-5 text-indigo-500" />
                                <select v-model="form.server_id" class="apple-input h-14 pl-16 font-bold uppercase tracking-tight" required>
                                    <option value="">Choose Node...</option>
                                    <option v-for="s in servers" :key="s.id" :value="s.id">{{ s.name }} ({{ s.ip }})</option>
                                </select>
                            </div>
                        </div>
                        <div class="space-y-4 text-left">
                            <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Velocity Tier (Profile)</label>
                            <div class="relative group">
                                <Zap class="absolute left-6 top-1/2 -translate-y-1/2 w-5 h-5 text-amber-500" />
                                <select v-model="form.package_id" class="apple-input h-14 pl-16 font-bold uppercase tracking-tight" required>
                                    <option value="">Choose Velocity Profile...</option>
                                    <option v-for="p in packages" :key="p.id" :value="p.id">{{ p.name }} (${{ p.price }})</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. Credential Protocol -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Identity Credentials -->
                    <div class="apple-card p-10 space-y-10">
                        <div class="flex items-center gap-3">
                            <div class="w-1.5 h-6 bg-emerald-500 rounded-full"></div>
                            <h3 class="text-sm font-bold tracking-tight uppercase">Identity Protocol</h3>
                        </div>

                        <div class="space-y-8 text-left">
                            <div class="space-y-4">
                                <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Access Secret (Username)</label>
                                <div class="relative">
                                    <input v-model="form.username" type="text" class="apple-input h-14 pl-16 font-mono font-bold text-lg" placeholder="USR-2024" required>
                                    <User class="absolute left-6 top-1/2 -translate-y-1/2 w-5 h-5 text-black/20" />
                                </div>
                            </div>
                            <div class="space-y-4">
                                <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2 flex items-center justify-between">
                                    Access Protocol (Password)
                                    <button type="button" @click="generatePassword" class="text-[9px] hover:text-black transition-colors">Generate Protocol</button>
                                </label>
                                <div class="relative">
                                    <input v-model="form.password" type="text" class="apple-input h-14 pl-16 font-mono font-bold text-lg" required>
                                    <Lock class="absolute left-6 top-1/2 -translate-y-1/2 w-5 h-5 text-black/20" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Temporal Horizon -->
                    <div class="apple-card p-10 space-y-10">
                         <div class="flex items-center gap-3">
                            <div class="w-1.5 h-6 bg-rose-500 rounded-full"></div>
                            <h3 class="text-sm font-bold tracking-tight uppercase">Temporal Horizon</h3>
                        </div>

                        <div class="space-y-8 text-left">
                            <div class="space-y-4">
                                <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Expiration Protocol (Lease End)</label>
                                <div class="relative">
                                    <input v-model="form.expires_at" type="date" class="apple-input h-14 pl-16 font-bold text-lg">
                                    <Calendar class="absolute left-6 top-1/2 -translate-y-1/2 w-5 h-5 text-black/20" />
                                </div>
                                <p class="text-[8px] font-black text-rose-500/60 uppercase tracking-widest ml-2">Leave empty for semi-permanent provisioning</p>
                            </div>

                            <div v-if="selectedPackage" class="p-8 bg-black text-white rounded-[2.5rem] relative overflow-hidden group">
                                <div class="absolute -top-10 -right-10 w-40 h-40 bg-white/10 rounded-full blur-3xl transition-all group-hover:scale-125"></div>
                                <div class="relative z-10 flex items-center gap-6">
                                    <Zap class="w-12 h-12 text-amber-500 shadow-amber-500/20 shadow-2xl" />
                                    <div>
                                         <p class="text-[9px] font-black text-white/40 uppercase tracking-widest mb-1">Velocity Commitment</p>
                                         <p class="text-xl font-bold tracking-tighter">{{ selectedPackage.speed_down }}M / {{ selectedPackage.speed_up }}M</p>
                                         <p class="text-[10px] opacity-60 mt-1">${{ selectedPackage.price }} Protocol Artifact Value</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 4. Governance Commitment -->
                <div class="flex flex-col md:flex-row items-center justify-between gap-10 apple-card p-12 bg-black text-white relative overflow-hidden">
                    <div class="absolute -bottom-20 -left-20 w-64 h-64 bg-white/5 rounded-full blur-3xl"></div>
                    <div class="relative z-10 flex items-center gap-8 text-left">
                        <div class="w-16 h-16 bg-white/10 rounded-[1.5rem] flex items-center justify-center text-3xl shrink-0">
                            <Shield class="w-8 h-8 text-amber-400" />
                        </div>
                        <div>
                             <h4 class="text-lg font-bold uppercase tracking-tight">Provisioning Commitment</h4>
                             <p class="text-[9px] font-black text-white/40 uppercase tracking-widest mt-1 leading-relaxed">
                                Initiating individual guest handshake protocol and edge controller synchronization.
                             </p>
                        </div>
                    </div>
                    <div class="relative z-10 flex items-center gap-6">
                        <Link 
                            :href="route('hotspot.users.index')" 
                            class="px-8 py-4 bg-white/10 text-white font-bold text-xs uppercase tracking-widest rounded-2xl hover:bg-white/20 transition-all active:scale-95"
                        >
                            Abort Changes
                        </Link>
                        <button 
                            type="submit" 
                            class="px-12 py-5 bg-white text-black font-bold text-xs uppercase tracking-[0.2em] rounded-2xl shadow-2xl hover:bg-emerald-500 hover:text-white transition-all hover:scale-105 active:scale-95 disabled:opacity-50 flex items-center gap-2"
                            :disabled="form.processing"
                        >
                            <Save class="w-4 h-4" /> Commit Protocol
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </AppleLayout>
</template>
