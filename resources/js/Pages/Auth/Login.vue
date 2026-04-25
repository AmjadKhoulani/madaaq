<script setup>
import { ref, onMounted } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { 
    Zap,
    CheckCircle2,
    Users,
    Activity,
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
    <div class="min-h-screen bg-slate-50 flex overflow-hidden font-tajawal selection:bg-indigo-600 selection:text-white" dir="rtl">
        <Head title="تسجيل الدخول - MadaaQ" />

        <!-- 1. Strategic Visual Side (Left in RTL, but logically the 'Info' side) -->
        <div class="hidden lg:flex lg:w-1/2 bg-slate-900 relative items-center justify-center overflow-hidden">
            <!-- Animated Background Mesh -->
            <div class="absolute inset-0 z-0">
                <div class="absolute top-0 right-0 w-[800px] h-[800px] bg-indigo-600/20 rounded-full blur-[120px] animate-pulse"></div>
                <div class="absolute bottom-0 left-0 w-[600px] h-[600px] bg-purple-600/10 rounded-full blur-[100px] animate-pulse" style="animation-delay: 2s"></div>
                <!-- Grid Pattern Overlay -->
                <div class="absolute inset-0 opacity-10 bg-[radial-gradient(circle_at_center,#ffffff_1px,transparent_1px)] [background-size:40px_40px]"></div>
            </div>

            <div class="relative z-10 p-20 text-right">
                <div class="mb-12">
                    <div class="w-16 h-16 bg-indigo-600 rounded-2xl flex items-center justify-center text-white shadow-2xl mb-8 group hover:rotate-12 transition-transform duration-500">
                        <Zap class="w-8 h-8 fill-current" />
                    </div>
                    <h2 class="text-5xl font-black text-white leading-tight mb-6">
                        بوابتك نحو إدارة <br />
                        <span class="text-indigo-400">شبكات المستقبل</span>
                    </h2>
                    <p class="text-slate-400 text-xl max-w-md leading-relaxed font-medium">
                        استمتع بتجربة إدارية سيادية تجمع بين القوة والجمال في منصة واحدة متكاملة.
                    </p>
                </div>

                <div class="space-y-6">
                    <div class="flex items-center gap-4 text-white/80 group">
                        <div class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center group-hover:bg-indigo-600/20 transition-colors">
                            <CheckCircle2 class="w-5 h-5 text-indigo-400" />
                        </div>
                        <span class="text-sm font-bold tracking-wide">أمان فائق وتشفير كامل للبيانات</span>
                    </div>
                    <div class="flex items-center gap-4 text-white/80 group">
                        <div class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center group-hover:bg-indigo-600/20 transition-colors">
                            <Users class="w-5 h-5 text-indigo-400" />
                        </div>
                        <span class="text-sm font-bold tracking-wide">إدارة مركزية لآلاف المشتركين</span>
                    </div>
                    <div class="flex items-center gap-4 text-white/80 group">
                        <div class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center group-hover:bg-indigo-600/20 transition-colors">
                            <Activity class="w-5 h-5 text-indigo-400" />
                        </div>
                        <span class="text-sm font-bold tracking-wide">مراقبة حية للأبراج والسيرفرات</span>
                    </div>
                </div>

                <div class="mt-20 pt-12 border-t border-white/5">
                    <p class="text-[10px] font-black text-white/20 uppercase tracking-[0.4em] italic font-inter">Infrastructure Management Node v5.2</p>
                </div>
            </div>
        </div>

        <!-- 2. Interactive Login Side (Right in RTL) -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 sm:p-20 relative bg-white lg:rounded-r-[4rem] shadow-[-50px_0_100px_rgba(0,0,0,0.05)] z-20">
            
            <div 
                class="max-w-md w-full transition-all duration-1000 transform"
                :class="isVisible ? 'translate-y-0 opacity-100' : 'translate-y-12 opacity-0'"
            >
                <!-- Brand Unit (Mobile Only or Minimal) -->
                <div class="lg:hidden text-center mb-10">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-indigo-600 shadow-xl mb-6">
                        <Zap class="w-10 h-10 text-white fill-current" />
                    </div>
                </div>

                <div class="mb-12">
                    <h3 class="text-3xl font-black text-slate-900 mb-3 tracking-tight italic">تسجيل الدخول</h3>
                    <p class="text-slate-400 font-bold text-sm">أهلاً بك مجدداً في نظام <span class="text-indigo-600">مدى كيو</span></p>
                </div>

                <form @submit.prevent="submit" class="space-y-8">
                    <!-- Identity Input -->
                    <div class="space-y-3">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest mr-4 block">البريد الإلكتروني</label>
                        <div class="relative group">
                            <div class="absolute right-6 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-indigo-600 transition-colors">
                                <Mail class="w-5 h-5 stroke-[2.5]" />
                            </div>
                            <input 
                                v-model="form.email"
                                type="email" 
                                required 
                                class="w-full h-16 bg-slate-50 border border-slate-200 rounded-2xl px-6 pr-16 font-bold text-slate-900 transition-all duration-300 focus:ring-4 focus:ring-indigo-600/5 focus:border-indigo-600 outline-none focus:bg-white text-lg font-inter" 
                                placeholder="name@example.com"
                            >
                        </div>
                        <p v-if="form.errors.email" class="text-rose-500 text-[10px] font-black mr-4 uppercase tracking-widest mt-2 italic">{{ form.errors.email }}</p>
                    </div>

                    <!-- Secret Input -->
                    <div class="space-y-3">
                        <div class="flex justify-between items-center px-4">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest">كلمة المرور</label>
                            <Link :href="route('password.request')" class="text-[10px] font-black text-indigo-600 uppercase tracking-widest hover:underline italic">نسيت كلمة المرور؟</Link>
                        </div>
                        <div class="relative group">
                            <div class="absolute right-6 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-indigo-600 transition-colors">
                                <Lock class="w-5 h-5 stroke-[2.5]" />
                            </div>
                            <input 
                                v-model="form.password"
                                :type="showPassword ? 'text' : 'password'" 
                                required 
                                class="w-full h-16 bg-slate-50 border border-slate-200 rounded-2xl px-6 pr-16 h-16 font-bold text-slate-900 transition-all duration-300 focus:ring-4 focus:ring-indigo-600/5 focus:border-indigo-600 outline-none focus:bg-white text-lg font-inter tracking-[0.5em]" 
                                placeholder="••••••••"
                            >
                            <button 
                                type="button"
                                @click="showPassword = !showPassword"
                                class="absolute left-6 top-1/2 -translate-y-1/2 text-slate-300 hover:text-indigo-600 transition-colors"
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
                            <span class="text-[11px] font-black text-slate-500 uppercase tracking-widest italic group-hover:text-indigo-600 transition-colors">تذكر الهوية في هذه المحطة</span>
                            <div class="relative">
                                <input v-model="form.remember" type="checkbox" class="sr-only peer">
                                <div class="w-6 h-6 bg-slate-100 border-slate-200 border-2 rounded-lg peer-checked:bg-indigo-600 peer-checked:border-indigo-600 transition-all flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" class="opacity-0 peer-checked:opacity-100 transition-opacity"><polyline points="20 6 9 17 4 12"/></svg>
                                </div>
                            </div>
                        </label>
                    </div>

                    <!-- Commit Trigger -->
                    <button 
                        type="submit" 
                        class="w-full py-6 rounded-2xl text-white font-black text-sm uppercase tracking-[0.3em] shadow-xl shadow-indigo-600/20 flex items-center justify-center gap-4 group relative overflow-hidden bg-slate-900 hover:bg-indigo-700 transition-all duration-500 active:scale-95 disabled:opacity-50"
                        :disabled="form.processing"
                    >
                        <span v-if="!form.processing">دخول النظام</span>
                        <span v-else>جاري التحقق...</span>
                        <ChevronLeft v-if="!form.processing" class="w-5 h-5 group-hover:-translate-x-1 transition-transform stroke-[3]" />
                    </button>
                </form>

                <!-- Footer Protocol -->
                <div class="mt-16 pt-10 border-t border-slate-100 text-center">
                    <p class="text-xs font-bold text-slate-400 mb-6">
                        لا تملك حساباً؟
                        <Link :href="route('register')" class="text-indigo-600 hover:underline mr-1 font-black">أنشئ حسابك الآن</Link>
                    </p>
                    <div class="flex items-center justify-center gap-8 opacity-20">
                        <Fingerprint class="w-5 h-5 text-slate-400" />
                        <Cpu class="w-5 h-5 text-slate-400" />
                        <Globe class="w-5 h-5 text-slate-400" />
                    </div>
                </div>
            </div>

            <!-- Floating Label -->
            <div class="absolute bottom-10 right-10 pointer-events-none opacity-[0.03] select-none">
                <h1 class="text-[120px] font-black italic tracking-tighter leading-none">MadaaQ</h1>
            </div>
        </div>
    </div>
</template>


