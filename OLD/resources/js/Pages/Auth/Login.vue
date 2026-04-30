<script setup>
import { ref, onMounted } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { 
    Zap,
    Mail,
    Lock,
    Eye,
    EyeOff,
    ChevronLeft,
    CheckCircle2,
    Users,
    Activity
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
    <div class="min-h-screen bg-slate-50 flex items-center justify-center p-6 font-tajawal" dir="rtl">
        <Head title="تسجيل الدخول - MadaaQ" />

        <div class="max-w-5xl w-full bg-white rounded-[2.5rem] shadow-2xl overflow-hidden flex min-h-[700px] border border-slate-100">
            <!-- Information Side -->
            <div class="hidden lg:flex lg:w-1/2 bg-primary relative p-16 flex-col justify-between overflow-hidden">
                <!-- Decorative Elements -->
                <div class="absolute -top-24 -right-24 w-96 h-96 bg-white/10 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-black/10 rounded-full blur-2xl"></div>

                <div class="relative z-10">
                    <div class="w-14 h-14 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center text-white mb-10 border border-white/20">
                        <Zap class="w-8 h-8 fill-current" />
                    </div>
                    <h2 class="text-4xl font-black text-white leading-tight mb-6">
                        إدارة شبكتك <br />
                        <span class="text-blue-100">بذكاء ونظافة</span>
                    </h2>
                    <p class="text-blue-50/80 text-lg leading-relaxed font-medium">
                        منصة موحدة لمزودي الخدمة، تجمع بين القوة التقنية وبساطة التجربة في واجهة عصرية واحدة.
                    </p>
                </div>

                <div class="space-y-6 relative z-10">
                    <div class="flex items-center gap-4 text-white/90">
                        <CheckCircle2 class="w-5 h-5 text-blue-200" />
                        <span class="text-sm font-bold">حوكمة مركزية لكامل البنية التحتية</span>
                    </div>
                    <div class="flex items-center gap-4 text-white/90">
                        <Users class="w-5 h-5 text-blue-200" />
                        <span class="text-sm font-bold">إدارة آلاف المشتركين بكل سلاسة</span>
                    </div>
                    <div class="flex items-center gap-4 text-white/90">
                        <Activity class="w-5 h-5 text-blue-200" />
                        <span class="text-sm font-bold">مراقبة حية للأداء والاستقرار</span>
                    </div>
                </div>

                <div class="relative z-10 pt-10 border-t border-white/10">
                    <p class="text-[9px] font-black text-white/40 uppercase tracking-[0.4em] font-inter italic">Network Management Suite v5.0</p>
                </div>
            </div>

            <!-- Login Form Side -->
            <div class="w-full lg:w-1/2 p-12 sm:p-20 flex flex-col justify-center">
                <div class="mb-12">
                    <h1 class="text-3xl font-black text-slate-900 mb-3 tracking-tight italic">تسجيل الدخول</h1>
                    <p class="text-slate-400 font-bold text-sm">أهلاً بك مجدداً في نظام <span class="text-primary">مدى كيو</span></p>
                </div>

                <form @submit.prevent="submit" class="space-y-8">
                    <div class="space-y-3">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest mr-2 block">البريد الإلكتروني</label>
                        <div class="relative group">
                            <Mail class="absolute right-5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-primary transition-colors w-5 h-5" />
                            <input 
                                v-model="form.email"
                                type="email" 
                                required 
                                class="w-full h-16 bg-slate-50 border border-slate-200 rounded-2xl pr-14 pl-6 font-bold text-slate-900 focus:ring-4 focus:ring-primary/5 focus:border-primary outline-none transition-all text-lg font-inter" 
                                placeholder="name@example.com"
                            >
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div class="flex justify-between items-center px-2">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest">كلمة المرور</label>
                            <span class="text-[10px] font-black text-slate-300 uppercase tracking-widest italic">حوكمة الوصول الآمن</span>
                        </div>
                        <div class="relative group">
                            <Lock class="absolute right-5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-primary transition-colors w-5 h-5" />
                            <input 
                                v-model="form.password"
                                :type="showPassword ? 'text' : 'password'" 
                                required 
                                class="w-full h-16 bg-slate-50 border border-slate-200 rounded-2xl pr-14 pl-14 font-bold text-slate-900 focus:ring-4 focus:ring-primary/5 focus:border-primary outline-none transition-all text-lg font-inter tracking-[0.3em]" 
                                placeholder="••••••••"
                            >
                            <button 
                                type="button"
                                @click="showPassword = !showPassword"
                                class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 hover:text-primary transition-colors"
                            >
                                <Eye v-if="!showPassword" class="w-5 h-5" />
                                <EyeOff v-else class="w-5 h-5" />
                            </button>
                        </div>
                    </div>

                    <button 
                        type="submit" 
                        class="w-full py-5 rounded-2xl bg-primary text-white font-black text-sm uppercase tracking-[0.3em] shadow-xl shadow-primary/20 hover:bg-blue-700 transition-all active:scale-95 disabled:opacity-50 flex items-center justify-center gap-3 group"
                        :disabled="form.processing"
                    >
                        <span v-if="!form.processing">دخول النظام</span>
                        <span v-else>جاري التحقق...</span>
                        <ChevronLeft class="w-5 h-5 group-hover:-translate-x-1 transition-transform" />
                    </button>
                </form>

                <div class="mt-16 pt-10 border-t border-slate-100 text-center">
                    <p class="text-xs font-bold text-slate-400">
                        لا تملك حساباً؟
                        <Link :href="route('register')" class="text-primary hover:underline mr-1 font-black">أنشئ حسابك الآن</Link>
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>
