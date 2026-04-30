<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { computedref } from 'vue';

import InstitutionalLayout from '@/Layouts/InstitutionalLayout.vue';

const props = defineProps({
    client: Object,
    packages: Array,
    currency: String,
});

const form = useForm({
    renew_mode: 'extend',
    package_id: '',
    duration_days: '30',
    data_limit: '',
    price: '',
});

const updateFromPackage = () => {
    if (!form.package_id) return;
    const pkg = props.packages.find(p => p.id == form.package_id);
    if (pkg) {
        form.duration_days = pkg.duration || 30;
        form.price = pkg.price || 0;
        form.data_limit = pkg.data_limit_mb ? (pkg.data_limit_mb / 1024).toFixed(0) : '';
    }
};

const submit = () => {
    form.post(route('crm.clients.renew.post', props.client.id));
};

</script>

<template>
    <InstitutionalLayout title="تجديد الاشتراك">
        <Head title="تجديد عقد الخدمة" />

        <div class="max-w-4xl mx-auto pb-24 text-right px-4" dir="rtl">
            <!-- Institutional Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-8 mb-12 flex-row-reverse">
                <div class="flex items-center gap-6 justify-end">
                    <div class="text-right">
                        <h1 class="text-3xl font-black text-primary tracking-tight mb-2">تجديد عقد الخدمة</h1>
                        <div class="flex items-center gap-3 justify-end">
                            <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">{{ client.username }}</span>
                            <div class="h-1.5 w-1.5 bg-secondary rounded-full"></div>
                            <span class="text-[10px] font-black uppercase tracking-widest text-primary">{{ client.name || 'مشترك' }}</span>
                        </div>
                    </div>
                </div>
                <Link 
                    :href="route('crm.clients.show', client.id)" 
                    class="w-12 h-12 bg-white border border-outline-variant/10 rounded-xl flex items-center justify-center text-slate-400 hover:text-primary transition-all shadow-sm group"
                >
                    <span class="material-symbols-outlined text-[24px] group-hover:translate-x-1 transition-transform">chevron_right</span>
                </Link>
            </div>

            <!-- Main Renewal Portal -->
            <div class="surface-card rounded-xl overflow-hidden shadow-sm border border-outline-variant/5">
                <div class="h-1.5 bg-primary"></div>
                
                <form @submit.prevent="submit" class="p-10 space-y-12">
                    <!-- Current Network State -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 p-8 bg-surface-container-low/50 rounded-2xl border border-outline-variant/10 shadow-inner">
                        <div class="flex items-center gap-5 flex-row-reverse">
                            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center border border-indigo-100/50">
                                <span class="material-symbols-outlined">event_busy</span>
                            </div>
                            <div class="text-right">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 leading-none">تاريخ الانتهاء الحالي</p>
                                <p class="text-[15px] font-black text-primary tracking-tight">{{ client.expires_at ? new Date(client.expires_at).toLocaleDateString('ar-SY') : 'وصول دائم غير منتهي' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-5 flex-row-reverse">
                            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-100/50">
                                <span class="material-symbols-outlined">data_usage</span>
                            </div>
                            <div class="text-right">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 leading-none">الحصة المخصصة (Quota)</p>
                                <p class="text-[15px] font-black text-primary tracking-tight">{{ client.data_limit ? (client.data_limit / 1024 / 1024 / 1024).toFixed(2) + ' جيجا بايت' : 'غير محدود' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Renewal Logic Selector -->
                    <div class="space-y-6">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mr-2">تحديد نمط التزويد (Provisioning Mode)</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <button 
                                type="button"
                                @click="form.renew_mode = 'extend'"
                                class="flex flex-col p-8 rounded-2xl transition-all text-right border-2 relative overflow-hidden group"
                                :class="form.renew_mode === 'extend' ? 'border-primary bg-indigo-50/10 shadow-lg' : 'border-outline-variant/10 hover:border-primary/30'"
                            >
                                <span class="text-sm font-black mb-2" :class="form.renew_mode === 'extend' ? 'text-primary' : 'text-slate-600'">تمديد متزامن (Extend)</span>
                                <span class="text-[10px] font-bold text-slate-400 leading-relaxed">إضافة دورات اشتراك جديدة إلى الرصيد الزمني الحالي للمشترك.</span>
                                <span v-if="form.renew_mode === 'extend'" class="material-symbols-outlined absolute -bottom-4 -left-4 text-primary/10 text-[64px]">sync</span>
                            </button>

                            <button 
                                type="button"
                                @click="form.renew_mode = 'reset'"
                                class="flex flex-col p-8 rounded-2xl transition-all text-right border-2 relative overflow-hidden group"
                                :class="form.renew_mode === 'reset' ? 'border-secondary bg-blue-50/10 shadow-lg' : 'border-outline-variant/10 hover:border-secondary/30'"
                            >
                                <span class="text-sm font-black mb-2" :class="form.renew_mode === 'reset' ? 'text-secondary' : 'text-slate-600'">إعادة تعيين التزويد (Reset)</span>
                                <span class="text-[10px] font-bold text-slate-400 leading-relaxed">تجاوز الحالة الزمنية الحالية والبدء بدورة اشتراك جديدة كلياً.</span>
                                <span v-if="form.renew_mode === 'reset'" class="material-symbols-outlined absolute -bottom-4 -left-4 text-secondary/10 text-[64px]">restart_alt</span>
                            </button>
                        </div>
                    </div>

                    <!-- Logic Layer Selection -->
                    <div class="space-y-4">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mr-2">فئة منطق الخدمة (الباقة المعتمدة)</label>
                        <div class="relative group">
                            <span class="material-symbols-outlined absolute right-5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:rotate-180 transition-all duration-700 text-[20px]">refresh</span>
                            <select 
                                v-model="form.package_id" 
                                @change="updateFromPackage"
                                class="form-input-monolith h-16 pr-14 font-black text-[13px] uppercase"
                            >
                                <option value="">تجاوز يدوي / بدون باقة محددة</option>
                                <option v-for="pkg in packages" :key="pkg.id" :value="pkg.id">
                                    {{ pkg.name }} — ({{ pkg.price }} {{ currency }})
                                </option>
                            </select>
                        </div>
                    </div>

                    <!-- Parameter Matrix -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        <div class="space-y-4">
                             <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mr-2">مدة دورة الحياة (بالأيام)</label>
                             <div class="relative">
                                <span class="material-symbols-outlined absolute right-5 top-1/2 -translate-y-1/2 text-slate-400 text-[20px]">history</span>
                                <input v-model="form.duration_days" type="number" class="form-input-monolith h-14 pr-14 font-black" placeholder="30">
                             </div>
                        </div>

                        <div class="space-y-4">
                             <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mr-2">حصة البيانات (جيجا بايت)</label>
                             <div class="relative">
                                <span class="material-symbols-outlined absolute right-5 top-1/2 -translate-y-1/2 text-slate-400 text-[20px]">database</span>
                                <input v-model="form.data_limit" type="number" class="form-input-monolith h-14 pr-14 font-black" placeholder="غير محدود">
                             </div>
                        </div>

                        <div class="md:col-span-2 space-y-4">
                             <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mr-2">تقييم التسوية المالية ({{ currency }})</label>
                             <div class="relative">
                                <span class="material-symbols-outlined absolute right-5 top-1/2 -translate-y-1/2 text-emerald-600 text-[20px]">payments</span>
                                <input v-model="form.price" type="number" step="0.01" class="form-input-monolith h-14 pr-14 font-headline font-black text-lg bg-emerald-50/10 border-emerald-100/20" placeholder="0.00">
                             </div>
                             <p class="text-[9px] font-black text-emerald-600 uppercase tracking-[0.2em] mt-3 mr-2">سيتم إنشاء قيد مالي (فاتورة) محصلة بهذا المبلغ فوراً وإضافتها لسجل المشترك.</p>
                        </div>
                    </div>

                    <!-- Execution Trigger -->
                    <div class="pt-10 flex flex-col md:flex-row items-center gap-8 border-t border-outline-variant/10 flex-row-reverse">
                        <div class="flex-1 text-right">
                             <h4 class="text-sm font-black text-primary mb-1 uppercase">يتطلب تفويضاً إدارياً</h4>
                             <p class="text-[10px] text-slate-400 font-bold leading-relaxed">بالضغط على تنفيذ، أنت تؤكد مزامنة عقد المشترك برمجياً مع البنية التحتية للشبكة.</p>
                        </div>
                        <button 
                            @click="submit"
                            :disabled="form.processing"
                            class="w-full md:w-auto px-14 py-5 bg-slate-900 text-white rounded-xl font-black shadow-2xl shadow-slate-900/20 hover:bg-primary transition-all active:scale-95 flex items-center justify-center gap-4 group"
                        >
                            <span class="material-symbols-outlined text-[20px] group-hover:rotate-180 transition-transform duration-700">bolt</span>
                            تنفيذ نبضة التجديد
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </InstitutionalLayout>
</template>





