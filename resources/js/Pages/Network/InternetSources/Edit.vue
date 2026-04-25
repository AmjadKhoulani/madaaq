<script setup>
import { useForm, Head, Link } from '@inertiajs/vue3';
import InstitutionalLayout from '@/Layouts/InstitutionalLayout.vue';

const props = defineProps({
    internetSource: Object,
});

const form = useForm({
    name: props.internetSource.name,
    type: props.internetSource.type,
    capacity: props.internetSource.capacity,
    status: props.internetSource.status,
    ip_gateway: props.internetSource.ip_gateway,
    connection_type: props.internetSource.connection_type || '',
});

const techTypes = [
    { value: 'fiber', label: 'وصلة ألياف ضوئية (Fiber)', icon: 'settings_input_fiber' },
    { value: 'microwave', label: 'تردد لاسلكي (Microwave)', icon: 'settings_input_antenna' },
    { value: 'starlink', label: 'اتصال أقمار صناعية (Starlink)', icon: 'rocket_launch' },
    { value: '4g', label: 'بيانات خلوية (4G/5G)', icon: 'signal_cellular_alt' },
    { value: 'other', label: 'تغذية بديلة', icon: 'alt_route' },
];

const submit = () => {
    form.put(route('network.internet-sources.update', props.internetSource.id));
};

</script>

<template>
    <InstitutionalLayout :title="'تعديل ' + internetSource.name">
        <Head :title="'حوكمة مصادر التزويد: ' + internetSource.name" />

        <div class="max-w-4xl mx-auto pb-24 text-right px-4" dir="rtl">
            <!-- Navigation Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-8 mb-12 flex-row-reverse">
                <div class="flex items-center gap-6 justify-end">
                    <div class="text-right">
                        <h1 class="text-3xl font-black text-primary tracking-tight mb-2">تعديل بروتوكول التزويد</h1>
                        <p class="text-slate-500 font-bold text-sm uppercase tracking-wider">ضبط معايير الاستخراج لمصدر: {{ internetSource.name }}</p>
                    </div>
                </div>
                <Link 
                    :href="route('network.internet-sources.index')" 
                    class="w-12 h-12 bg-white border border-outline-variant/10 rounded-xl flex items-center justify-center text-slate-400 hover:text-primary transition-all shadow-sm group"
                >
                    <span class="material-symbols-outlined text-[24px] group-hover:translate-x-1 transition-transform">chevron_right</span>
                </Link>
            </div>

            <form @submit.prevent="submit" class="space-y-10">
                <!-- 1. Feed Identity -->
                <div class="surface-card p-10 rounded-xl border border-outline-variant/5 shadow-sm">
                    <div class="flex items-center gap-4 mb-12 justify-end">
                        <h3 class="text-sm font-black text-primary uppercase tracking-widest">هوية البروتوكول</h3>
                        <div class="w-1.5 h-6 bg-primary rounded-full"></div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        <div class="space-y-4 md:col-span-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mr-2">مُعرف التزويد (الاسم)</label>
                            <input v-model="form.name" type="text" class="form-input-monolith h-16 font-black uppercase tracking-tight" required>
                        </div>

                        <div class="space-y-4">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mr-2">معيار الإرسال (Standard)</label>
                            <select v-model="form.type" class="form-input-monolith h-16 font-black uppercase tracking-tight" required>
                                <option v-for="tech in techTypes" :key="tech.value" :value="tech.value">{{ tech.label }}</option>
                            </select>
                        </div>

                        <div class="space-y-4">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mr-2">بروتوكول الحالة الحالي</label>
                            <select v-model="form.status" class="form-input-monolith h-16 font-black uppercase tracking-tight" required>
                                <option value="online">نشط (Online)</option>
                                <option value="offline">متوقف (Offline)</option>
                                <option value="maintenance">صيانة (Maintenance)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- 2. Performance Specs -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="surface-card p-10 space-y-8 rounded-xl border border-outline-variant/5 shadow-sm">
                        <div class="flex items-center gap-4 mb-4 justify-end">
                            <h3 class="text-sm font-black text-primary uppercase tracking-widest">المواصفات الأدائية</h3>
                            <div class="w-1.5 h-6 bg-indigo-600 rounded-full"></div>
                        </div>
                        <div class="space-y-4">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mr-2">سعة الاستخراج (Mbps)</label>
                            <input v-model="form.capacity" type="text" class="form-input-monolith h-16 font-black" placeholder="1000">
                        </div>
                        <div class="space-y-4">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mr-2">عنوان البوابة (Gateway IP)</label>
                            <input v-model="form.ip_gateway" type="text" class="form-input-monolith h-16 font-mono font-black" placeholder="10.0.0.1">
                        </div>
                    </div>

                    <div class="surface-card p-10 bg-slate-900 text-white relative overflow-hidden group rounded-xl shadow-2xl">
                         <div class="absolute -top-10 -right-10 w-40 h-40 bg-white/5 rounded-full blur-3xl group-hover/card:scale-150 transition-transform duration-1000"></div>
                         <div class="relative z-10 space-y-6">
                            <div class="w-14 h-14 rounded-2xl bg-white/10 flex items-center justify-center text-emerald-400 border border-white/10 shadow-inner">
                                <span class="material-symbols-outlined text-[28px]">verified_user</span>
                            </div>
                            <div class="text-right">
                                <h4 class="text-lg font-black text-white uppercase tracking-tight">نزاهة المزامنة</h4>
                                <p class="text-[9px] font-black text-white/40 uppercase tracking-widest mt-2 leading-relaxed">تعديلات بروتوكول التزويد يجب أن تتطابق مع الحالة المادية للمزود قبل التوثيق النهائي.</p>
                            </div>
                         </div>
                    </div>
                </div>

                <!-- 3. Commitment Confirmation -->
                <div class="surface-card p-12 bg-slate-900 rounded-xl shadow-2xl overflow-hidden relative group/final">
                    <div class="absolute -bottom-20 -right-20 w-80 h-80 bg-primary/5 rounded-full blur-3xl group-hover/final:scale-150 transition-transform duration-1000"></div>
                    <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-10 flex-row-reverse">
                        <div class="flex items-center gap-8 flex-1 justify-end">
                            <div class="text-right">
                                 <h4 class="text-xl font-black text-white tracking-tight">صيانة المزامنة</h4>
                                 <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mt-2">
                                    تحديث معايير التزويد وإخطار عقد البنية التحتية المتأثرة.
                                 </p>
                            </div>
                            <div class="w-20 h-20 bg-white/5 rounded-2xl flex items-center justify-center border border-white/10 shadow-inner group-hover/final:scale-110 transition-transform">
                                <span class="material-symbols-outlined text-white text-[32px]" style="font-variation-settings: 'FILL' 1">sync_alt</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-6 relative z-10 flex-row-reverse">
                            <Link 
                                :href="route('network.internet-sources.index')" 
                                class="px-10 py-5 bg-white/5 text-slate-400 font-black text-[11px] uppercase tracking-widest rounded-lg hover:bg-white/10 transition-all active:scale-95"
                            >
                                تجاهل التغييرات
                            </Link>
                            <button 
                                type="submit" 
                                class="px-14 py-5 bg-primary text-white font-black text-[11px] uppercase tracking-[0.2em] rounded-lg shadow-2xl shadow-primary/20 hover:bg-emerald-600 transition-all hover:scale-[1.05] active:scale-95 flex items-center gap-3"
                                :disabled="form.processing"
                            >
                                <span class="material-symbols-outlined text-[20px]">sync</span>
                                حفظ التغييرات
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </InstitutionalLayout>
</template>


