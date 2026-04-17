<script setup>
import { useForm, Head } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const isVisible = ref(false);
const showPassword = ref(false);

onMounted(() => {
    isVisible.value = true;
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <div class="min-h-screen bg-[#020617] flex items-center justify-center p-6 selection:bg-emerald-500 selection:text-white relative overflow-hidden font-sans" dir="rtl">
        <Head title="Sovereign Entry Hub - MadaaQ" />

        <!-- 1. SOVEREIGN BACKGROUND ECOSYSTEM -->
        <div class="absolute inset-0 pointer-events-none overflow-hidden">
            <!-- Data Grid Overlay -->
            <div class="absolute inset-0 opacity-[0.15] bg-[radial-gradient(circle_at_center,#334155_1px,transparent_1px)] [background-size:40px_40px]"></div>
            
            <!-- Strategic Light Orbs -->
            <div class="absolute -top-[20%] -left-[10%] w-[70%] h-[70%] bg-emerald-500/10 rounded-full blur-[120px] animate-pulse duration-[10s]"></div>
            <div class="absolute -bottom-[20%] -right-[10%] w-[70%] h-[70%] bg-blue-600/10 rounded-full blur-[120px] animate-pulse duration-[15s]"></div>
            
            <!-- Circuit Pulse Lines (SVG Architecture) -->
            <svg class="absolute inset-0 w-full h-full opacity-20" xmlns="http://www.w3.org/2000/svg">
                <path d="M-100 100 L500 100 L550 150 L1200 150" fill="none" stroke="currentColor" stroke-width="0.5" class="text-emerald-500/30" />
                <path d="M1200 800 L800 800 L750 750 L-100 750" fill="none" stroke="currentColor" stroke-width="0.5" class="text-blue-500/30" />
                <circle cx="550" cy="150" r="2" class="fill-emerald-500 animate-ping" />
                <circle cx="750" cy="750" r="2" class="fill-blue-500 animate-ping" style="animation-delay: 2s" />
            </svg>
        </div>

        <!-- 2. ADVANCED GLASSMORPHISM LOGIN PANEL -->
        <div 
            class="w-full max-w-[540px] relative z-20 transition-all duration-1000 transform"
            :class="isVisible ? 'translate-y-0 opacity-100 scale-100' : 'translate-y-12 opacity-0 scale-95'"
        >
            <div class="relative group">
                <!-- Panel Glow Backdrop -->
                <div class="absolute -inset-1 bg-gradient-to-tr from-emerald-500/20 to-blue-600/20 rounded-[3.5rem] blur-2xl opacity-50 group-hover:opacity-100 transition-opacity duration-700"></div>
                
                <div class="relative surface-card p-12 lg:p-16 bg-white/[0.02] backdrop-blur-[60px] border border-white/10 rounded-[3.5rem] shadow-[0_50px_100px_-20px_rgba(0,0,0,0.8)] overflow-hidden">
                    
                    <!-- Internal Glass Accent -->
                    <div class="absolute top-0 right-0 w-full h-1 bg-gradient-to-l from-transparent via-emerald-500/40 to-transparent"></div>

                    <!-- 3. BRANDING SYNTHESIS -->
                    <div class="flex flex-col items-center mb-16 text-center">
                        <div class="w-24 h-24 bg-white text-slate-950 rounded-[2rem] flex items-center justify-center text-5xl font-black shadow-[0_20px_50px_rgba(0,0,0,0.3)] mb-8 relative border-4 border-white/5 overflow-hidden transition-transform hover:rotate-6 duration-500">
                            <div class="absolute inset-0 bg-gradient-to-tr from-emerald-500/10 to-transparent"></div>
                            <span class="font-headline tracking-tighter">M</span>
                            <div class="absolute -bottom-1 -right-1 w-7 h-7 bg-emerald-500 rounded-full border-4 border-slate-900 shadow-lg"></div>
                        </div>
                        
                        <div class="space-y-2">
                             <h1 class="text-4xl font-black text-white tracking-tight flex items-center gap-4 justify-center">
                                <span class="bg-gradient-to-l from-white via-white to-white/60 bg-clip-text text-transparent">MadaaQ</span>
                                <span class="text-white">مدى كيو</span>
                             </h1>
                             <div class="flex flex-col items-center">
                                <p class="text-emerald-400 font-black text-[10px] uppercase tracking-[0.4em] mb-1">Sovereign Entry Hub</p>
                                <p class="text-white/30 font-bold text-[11px] uppercase tracking-wider">محور الدخول السيادي للمنظومة</p>
                             </div>
                        </div>
                    </div>

                    <!-- 4. AUTH MATRIX FORM -->
                    <form @submit.prevent="submit" class="space-y-10">
                        <div class="space-y-8">
                            <!-- Email / Identity -->
                            <div class="space-y-4">
                                <div class="flex items-center justify-between px-2">
                                    <label class="text-[10px] font-black text-white/40 uppercase tracking-[0.2em]">Username / Email</label>
                                    <label class="text-[10px] font-black text-white/40 uppercase tracking-[0.2em]">اسم المستخدم / البريد</label>
                                </div>
                                <div class="relative group/input">
                                    <span class="material-symbols-outlined absolute right-6 top-1/2 -translate-y-1/2 text-white/10 group-focus-within/input:text-emerald-400 transition-all text-[24px]">account_circle</span>
                                    <input 
                                        v-model="form.email"
                                        type="email" 
                                        class="w-full h-18 bg-white/[0.03] border-2 border-white/5 rounded-3xl pr-16 pl-8 text-white font-headline font-black text-lg focus:border-emerald-500/40 focus:ring-0 focus:bg-white/[0.05] transition-all placeholder:text-white/5 shadow-inner" 
                                        placeholder="operator@madaaq.net"
                                        required
                                        dir="ltr"
                                    >
                                    <div class="absolute inset-0 rounded-3xl border border-emerald-500/0 group-focus-within/input:border-emerald-500/20 pointer-events-none transition-all"></div>
                                </div>
                                <div v-if="form.errors.email" class="text-[10px] font-black text-rose-500 mt-2 px-2 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-[14px]">report</span> {{ form.errors.email }}
                                </div>
                            </div>

                            <!-- Password / Secret -->
                            <div class="space-y-4">
                                <div class="flex items-center justify-between px-2">
                                    <label class="text-[10px] font-black text-white/40 uppercase tracking-[0.2em]">Secret Protocol</label>
                                    <label class="text-[10px] font-black text-white/40 uppercase tracking-[0.2em]">كلمة المرور / الشيفرة</label>
                                </div>
                                <div class="relative group/input">
                                    <span class="material-symbols-outlined absolute right-6 top-1/2 -translate-y-1/2 text-white/10 group-focus-within/input:text-emerald-400 transition-all text-[24px]">vpn_key</span>
                                    <input 
                                        v-model="form.password"
                                        :type="showPassword ? 'text' : 'password'" 
                                        class="w-full h-18 bg-white/[0.03] border-2 border-white/5 rounded-3xl pr-16 pl-16 text-white font-headline font-black text-lg focus:border-emerald-500/40 focus:ring-0 focus:bg-white/[0.05] transition-all placeholder:text-white/5 shadow-inner tracking-[0.5em]" 
                                        placeholder="••••••••"
                                        required
                                        dir="ltr"
                                    >
                                    <button 
                                        type="button"
                                        @click="showPassword = !showPassword"
                                        class="absolute left-6 top-1/2 -translate-y-1/2 text-white/20 hover:text-emerald-400 transition-colors"
                                    >
                                        <span class="material-symbols-outlined text-[20px]">{{ showPassword ? 'visibility_off' : 'visibility' }}</span>
                                    </button>
                                </div>
                                <div class="flex justify-start px-2">
                                    <a href="#" class="text-[10px] font-black text-emerald-500 hover:text-emerald-400 transition-colors uppercase tracking-widest">Forgot Password? / نسيت كلمة المرور؟</a>
                                </div>
                            </div>
                        </div>

                        <!-- 5. INTERACTIVE PROTOCOLS -->
                        <div class="space-y-8 pt-4">
                            <div class="flex items-center gap-4 px-2">
                                <div class="relative flex items-center group/check">
                                    <input 
                                        v-model="form.remember" 
                                        type="checkbox" 
                                        id="remember" 
                                        class="w-6 h-6 rounded-lg border-white/10 bg-white/5 text-emerald-500 focus:ring-emerald-500/50 focus:ring-offset-0 transition-all cursor-pointer"
                                    >
                                </div>
                                <label for="remember" class="text-[11px] font-black text-white/30 uppercase tracking-widest cursor-pointer select-none flex items-center justify-between flex-1">
                                    <span>Persist Session Protocol</span>
                                    <span class="text-[10px]">تثبيت الجلسة</span>
                                </label>
                            </div>

                            <div class="grid grid-cols-1 gap-4">
                                <!-- Primary Action -->
                                <button 
                                    type="submit" 
                                    class="w-full h-20 bg-emerald-500 text-white rounded-[1.5rem] font-black text-[13px] uppercase tracking-[0.3em] shadow-[0_20px_40px_rgba(16,185,129,0.3)] hover:bg-emerald-400 hover:scale-[1.02] active:scale-95 transition-all flex items-center justify-between px-10 group/btn overflow-hidden relative"
                                    :disabled="form.processing"
                                >
                                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover/btn:translate-x-full transition-transform duration-1000"></div>
                                    <div class="flex items-center gap-4 relative z-10">
                                        <span v-if="!form.processing">تسجيل الدخول / Sign In</span>
                                        <span v-else>جاري المعالجة...</span>
                                    </div>
                                    <span class="material-symbols-outlined text-[28px] group-hover/btn:translate-x-[-8px] transition-transform">login</span>
                                </button>

                                <!-- Secondary SSO Aesthetic -->
                                <button 
                                    type="button" 
                                    class="w-full h-16 bg-white/[0.02] text-white/40 rounded-[1.25rem] border border-white/5 font-black text-[10px] uppercase tracking-[0.3em] hover:bg-white/[0.05] hover:text-white transition-all flex items-center justify-center gap-4 group/sso"
                                >
                                    <span class="material-symbols-outlined text-[20px] group-hover/sso:rotate-12 transition-all">hub</span>
                                    Single Sign-On (SSO) / تسجيل الدخول الموحد
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- 6. COMPLIANCE FOOTER -->
                    <div class="mt-12 text-center pt-8 border-t border-white/5">
                        <p class="text-[9px] font-black text-white/10 uppercase tracking-[0.5em] mb-6">MadaaQ Handshake Protocol v5.0 • Infrastructure Matrix</p>
                        <div class="flex items-center justify-center gap-8 opacity-5">
                            <span class="material-symbols-outlined text-[20px]">fingerprint</span>
                            <span class="material-symbols-outlined text-[20px]">security</span>
                            <span class="material-symbols-outlined text-[20px]">lan</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Decorative UI Fragments -->
        <div class="absolute bottom-10 left-10 text-white/5 font-headline text-[120px] font-black select-none pointer-events-none uppercase tracking-tighter opacity-10">CORE</div>
        <div class="absolute top-10 right-10 text-white/5 font-headline text-[120px] font-black select-none pointer-events-none rotate-90 uppercase tracking-tighter opacity-10">AUTH</div>
    </div>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Manrope:wght@800&display=swap');

.font-headline {
    font-family: 'Manrope', sans-serif;
}

input:-webkit-autofill,
input:-webkit-autofill:hover, 
input:-webkit-autofill:focus {
    -webkit-text-fill-color: white !important;
    -webkit-box-shadow: 0 0 0px 1000px #0f172a inset !important;
    transition: background-color 5000s ease-in-out 0s;
}

.surface-card {
    transition: box-shadow 0.7s cubic-bezier(0.4, 0, 0.2, 1);
}
</style>
