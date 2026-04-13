<script setup>
import { useForm, Head } from '@inertiajs/vue3';
import { ShieldCheck, Lock, Mail, ArrowRight, Activity, Zap } from 'lucide-vue-next';

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <div class="min-h-screen bg-[#f5f5f7] flex items-center justify-center p-6 selection:bg-black selection:text-white relative overflow-hidden">
        <Head title="MadaaQ Intelligence | Entry" />

        <!-- Dynamic Ambient Background -->
        <div class="absolute inset-0 pointer-events-none overflow-hidden">
            <div class="absolute -top-[20%] -left-[10%] w-[60%] h-[70%] bg-indigo-500/10 rounded-full blur-[120px] animate-pulse"></div>
            <div class="absolute -bottom-[20%] -right-[10%] w-[60%] h-[70%] bg-emerald-500/10 rounded-full blur-[120px] animate-pulse duration-[10s]"></div>
        </div>

        <div class="w-full max-w-md relative z-10 animate-in fade-in zoom-in duration-700">
            <!-- Branding Synthesis -->
            <div class="flex flex-col items-center mb-12">
                <div class="w-16 h-16 bg-black text-white rounded-[1.5rem] flex items-center justify-center text-3xl font-black shadow-2xl mb-8 relative group">
                    <div class="absolute inset-0 bg-gradient-to-tr from-white/20 to-transparent rounded-[1.5rem]"></div>
                    M
                </div>
                <h1 class="text-2xl font-bold tracking-tight text-center uppercase">Infrastructure Intelligence</h1>
                <p class="text-[#86868b] font-medium text-center mt-2 flex items-center gap-2">
                    <ShieldCheck class="w-4 h-4 text-emerald-500" /> Authorized Entry Protocol
                </p>
            </div>

            <!-- Login Matrix -->
            <div class="apple-card p-10 bg-white/70 backdrop-blur-3xl border border-white/50 shadow-[0_20px_50px_rgba(0,0,0,0.05)]">
                <form @submit.prevent="submit" class="space-y-8">
                    <div class="space-y-6">
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-1">Identity Protocol (Email)</label>
                            <div class="relative group">
                                <Mail class="absolute left-5 top-1/2 -translate-y-1/2 w-4 h-4 text-[#86868b] group-focus-within:text-black transition-colors" />
                                <input 
                                    v-model="form.email"
                                    type="email" 
                                    class="apple-input h-14 pl-14 font-bold tracking-tight bg-white/50 focus:bg-white transition-all" 
                                    placeholder="operator@madaaq.net"
                                    required
                                    autoowner="email"
                                >
                            </div>
                             <div v-if="form.errors.email" class="text-[10px] font-bold text-rose-500 uppercase tracking-widest mt-2 ml-1">{{ form.errors.email }}</div>
                        </div>

                        <div class="space-y-3">
                            <div class="flex items-center justify-between ml-1">
                                <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest">Secret Protocol (Password)</label>
                                <a href="#" class="text-[9px] font-black text-[#86868b] hover:text-black transition-colors uppercase tracking-widest">Reset?</a>
                            </div>
                            <div class="relative group">
                                <Lock class="absolute left-5 top-1/2 -translate-y-1/2 w-4 h-4 text-[#86868b] group-focus-within:text-black transition-colors" />
                                <input 
                                    v-model="form.password"
                                    type="password" 
                                    class="apple-input h-14 pl-14 font-bold tracking-tight bg-white/50 focus:bg-white transition-all" 
                                    placeholder="••••••••"
                                    required
                                    autoowner="current-password"
                                >
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 ml-1">
                         <input v-model="form.remember" type="checkbox" id="remember" class="w-4 h-4 rounded border-[#d2d2d7] text-black focus:ring-black">
                         <label for="remember" class="text-[11px] font-bold text-[#86868b] uppercase tracking-widest cursor-pointer select-none">Persist Artifact (Remember Me)</label>
                    </div>

                    <button 
                        type="submit" 
                        class="w-full h-14 bg-black text-white rounded-2xl font-bold text-[11px] uppercase tracking-[0.2em] shadow-2xl hover:scale-[1.03] active:scale-95 transition-all flex items-center justify-center gap-3 disabled:opacity-50"
                        :disabled="form.processing"
                    >
                        Initiate Connection
                        <ArrowRight v-if="!form.processing" class="w-4 h-4" />
                        <Activity v-else class="w-4 h-4 animate-spin" />
                    </button>
                </form>
            </div>

            <!-- Footer Compliance -->
            <div class="mt-12 text-center space-y-4">
                <p class="text-[9px] font-black text-[#86868b] uppercase tracking-[0.4em]">MadaaQ Handshake Protocol • v4.0 SPA</p>
                <div class="flex items-center justify-center gap-6 opacity-30 grayscale hover:grayscale-0 hover:opacity-100 transition-all duration-700">
                    <Zap class="w-5 h-5" />
                    <div class="w-px h-4 bg-black/20"></div>
                    <ShieldCheck class="w-5 h-5" />
                </div>
            </div>
        </div>
    </div>
</template>
