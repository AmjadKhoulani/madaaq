<script setup>
import { useForm, Head } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import { 
    ShieldCheck, 
    Mail, 
    Lock, 
    Eye, 
    EyeOff, 
    ChevronLeft, 
    Fingerprint,
    Cpu,
    Globe
} from 'lucide-vue-next';

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
    <div class="min-h-screen bg-white selection:bg-vendor selection:text-white relative overflow-hidden font-sans flex items-center justify-center p-6 sm:p-12" dir="rtl">
        <Head title="دخول بوابة الاستخبارات الإدارية - MadaaQ" />

        <!-- Strategic Background Mesh -->
        <div class="fixed inset-0 pointer-events-none z-0">
            <div class="absolute inset-0 bg-[radial-gradient(at_0%_0%,rgba(99,102,241,0.1)_0px,transparent_50%),radial-gradient(at_100%_100%,rgba(165,180,252,0.1)_0px,transparent_50%)]"></div>
            <div class="absolute top-[10%] left-[15%] w-64 h-64 bg-vendor/5 rounded-full blur-[100px] animate-pulse"></div>
            <div class="absolute bottom-[10%] right-[15%] w-96 h-96 bg-indigo-500/5 rounded-full blur-[120px] animate-pulse"></div>
        </div>

        <div 
            class="max-w-md w-full relative z-10 transition-all duration-1000 transform"
            :class="isVisible ? 'translate-y-0 opacity-100 scale-100' : 'translate-y-12 opacity-0 scale-95'"
        >
            <!-- Brand Unit -->
            <div class="text-center mb-12">
                <div class="inline-flex items-center justify-center w-24 h-24 rounded-[2.5rem] bg-slate-900 shadow-radiant mb-8 relative group transition-transform hover:rotate-6 duration-500">
                    <div class="absolute inset-0 bg-vendor/20 opacity-0 group-hover:opacity-100 blur-2xl transition-opacity duration-1000"></div>
                    <ShieldCheck class="w-10 h-10 text-vendor relative z-10 stroke-[2.5]" />
                </div>
                <h1 class="text-4xl font-black text-slate-900 tracking-tighter italic uppercase mb-2 font-inter">Madaa<span class="text-vendor">Q</span></h1>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.4em] italic font-inter opacity-70">Administrative Intelligence Gateway</p>
            </div>

            <!-- Login Matrix -->
            <div class="glass-card p-10 sm:p-12 rounded-[3rem] shadow-glass relative overflow-hidden bg-white/40 border border-white/60 backdrop-blur-2xl">
                <div class="absolute top-0 right-0 w-32 h-32 bg-vendor/5 rounded-full blur-3xl -mr-16 -mt-16 pointer-events-none"></div>
                
                <div class="relative z-10">
                    <div class="mb-10 text-right">
                        <h2 class="text-2xl font-black text-slate-900 italic tracking-tighter mb-1">تسجيل الدخول</h2>
                        <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest italic opacity-80">بروتوكول التحقق من الهوية السيادية</p>
                    </div>

                    <form @submit.prevent="submit" class="space-y-8">
                        <!-- Identity Input -->
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mr-4 block text-right">البريد الإلكتروني (Personnel ID)</label>
                            <div class="relative group">
                                <div class="absolute right-6 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-vendor transition-colors">
                                    <Mail class="w-5 h-5 stroke-[2.5]" />
                                </div>
                                <input 
                                    v-model="form.email"
                                    type="email" 
                                    required 
                                    class="w-full bg-white/50 border border-white/60 rounded-2xl px-6 pr-16 h-16 font-black text-slate-900 transition-all duration-300 focus:ring-4 focus:ring-vendor/5 focus:border-vendor outline-none focus:bg-white font-inter" 
                                    placeholder="id@madaaq.net"
                                >
                            </div>
                            <p v-if="form.errors.email" class="text-rose-500 text-[10px] font-black mr-4 uppercase tracking-widest mt-2 italic">{{ form.errors.email }}</p>
                        </div>

                        <!-- Secret Input -->
                        <div class="space-y-3">
                            <div class="flex justify-between items-center px-4 flex-row-reverse">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">كلمة المرور (Secret Key)</label>
                                <Link :href="route('password.request')" class="text-[10px] font-black text-vendor uppercase tracking-widest hover:underline italic">نسيت الشيفرة؟</Link>
                            </div>
                            <div class="relative group">
                                <div class="absolute right-6 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-vendor transition-colors">
                                    <Lock class="w-5 h-5 stroke-[2.5]" />
                                </div>
                                <input 
                                    v-model="form.password"
                                    :type="showPassword ? 'text' : 'password'" 
                                    required 
                                    class="w-full bg-white/50 border border-white/60 rounded-2xl px-6 pr-16 h-16 font-black text-slate-900 transition-all duration-300 focus:ring-4 focus:ring-vendor/5 focus:border-vendor outline-none focus:bg-white font-inter tracking-[0.5em]" 
                                    placeholder="••••••••"
                                >
                                <button 
                                    type="button"
                                    @click="showPassword = !showPassword"
                                    class="absolute left-6 top-1/2 -translate-y-1/2 text-slate-300 hover:text-vendor transition-colors"
                                >
                                    <Eye v-if="!showPassword" class="w-5 h-5" />
                                    <EyeOff v-else class="w-5 h-5" />
                                </button>
                            </div>
                            <p v-if="form.errors.password" class="text-rose-500 text-[10px] font-black mr-4 uppercase tracking-widest mt-2 italic">{{ form.errors.password }}</p>
                        </div>

                        <!-- Tactical Options -->
                        <div class="flex items-center justify-end px-4">
                            <label class="flex items-center gap-3 cursor-pointer group select-none">
                                <span class="text-[11px] font-black text-slate-500 uppercase tracking-widest italic group-hover:text-vendor transition-colors">تذكر الهوية في هذه المحطة</span>
                                <div class="relative">
                                    <input v-model="form.remember" type="checkbox" class="sr-only peer">
                                    <div class="w-5 h-5 bg-white/50 border-white/60 border rounded-lg peer-checked:bg-vendor peer-checked:border-vendor transition-all flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" class="opacity-0 peer-checked:opacity-100 transition-opacity"><polyline points="20 6 9 17 4 12"/></svg>
                                    </div>
                                </div>
                            </label>
                        </div>

                        <!-- Commit Trigger -->
                        <button 
                            type="submit" 
                            class="w-full py-5 rounded-2xl text-white font-black text-xs uppercase tracking-[0.4em] shadow-radiant flex items-center justify-center gap-4 group relative overflow-hidden bg-gradient-to-br from-vendor to-vendor-dark transition-all duration-500 active:scale-95 disabled:opacity-50"
                            :disabled="form.processing"
                        >
                            <span v-if="!form.processing">دخول النظام</span>
                            <span v-else>جاري التحقق...</span>
                            <ChevronLeft v-if="!form.processing" class="w-5 h-5 group-hover:-translate-x-1 transition-transform stroke-[3]" />
                        </button>
                    </form>

                    <!-- Footer Protocol -->
                    <div class="mt-12 pt-8 border-t border-white/40 text-center">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic mb-4">
                            لا تملك هوية وصول؟
                            <Link :href="route('register')" class="text-vendor hover:underline ml-1">بدء بروتوكول التسجيل</Link>
                        </p>
                        <div class="flex items-center justify-center gap-6 opacity-20 group-hover:opacity-100 transition-opacity">
                            <Fingerprint class="w-4 h-4 text-slate-400" />
                            <Cpu class="w-4 h-4 text-slate-400" />
                            <Globe class="w-4 h-4 text-slate-400" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Compliance Notice -->
            <p class="text-center mt-12 text-[8px] font-black text-slate-400 uppercase tracking-[0.4em] opacity-40 italic">
                SECURE ACCESS PROTOCOL V5.2.0 | ENCRYPTED BY MADAQA CORE
            </p>
        </div>
    </div>
</template>

<style scoped>
.glass-card {
    @apply border border-white/40 shadow-glass transition-all duration-500;
}
</style>
