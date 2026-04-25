<script setup>
import { onMounted } from 'vue';
import { useForm, Head, Link } from '@inertiajs/vue3';
import InstitutionalLayout from '@/Layouts/InstitutionalLayout.vue';

const props = defineProps({
    profile: Object,
    servers: Array,
    routers: Array
});

const form = useForm({
    name: props.profile.name,
    speed_down: props.profile.speed_down,
    speed_up: props.profile.speed_up,
    price: props.profile.price,
    duration_days: props.profile.duration_days || 30,
    data_limit_mb: props.profile.data_limit_mb || '',
    technology_type: props.profile.technology_type || 'fiber',
    targets: [],
});

onMounted(() => {
    const serverTargets = props.profile.mikrotik_servers?.map(s => `server_${s.id}`) || [];
    const routerTargets = props.profile.routers?.map(r => `router_${r.id}`) || [];
    form.targets = [...serverTargets, ...routerTargets];
});

const techTypes = [
    { value: 'fiber', label: 'ألياف ضوئية (Fiber)', icon: 'fiber_smart_record' },
    { value: 'wireless', label: 'لاسلكي (Wireless)', icon: 'settings_input_antenna' },
    { value: 'dsl', label: 'خط سلكي DSL', icon: 'GitBranch' },
    { value: 'cable', label: 'كابل Gigabit', icon: 'settings_ethernet' },
];

const submit = () => {
    form.put(route('broadband.profiles.update', props.profile.id));
};

const toggleTarget = (type, id) => {
    const targetString = `${type}_${id}`;
    const index = form.targets.indexOf(targetString);
    if (index === -1) {
        form.targets.push(targetString);
    } else {
        form.targets.splice(index, 1);
    }
};

const isTargetSelected = (type, id) => {
    return form.targets.includes(`${type}_${id}`);
};

</script>

<template>
    <InstitutionalLayout :title="'تعديل باقة ' + profile.name">
        <Head :title="'تعديل فئة الخدمة: ' + profile.name" />

        <div class="max-w-5xl mx-auto pb-24 text-right px-4">
            <!-- Institutional Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-8 mb-12 flex-row-reverse">
                <div class="text-right">
                    <h1 class="text-3xl font-black text-primary tracking-tight mb-2">تعديل بروتوكول الباقة</h1>
                    <p class="text-slate-500 font-bold text-sm uppercase tracking-wider">ضبط معايير الحوكمة لفئة: {{ profile.name }}</p>
                </div>
                <Link 
                    :href="route('broadband.profiles.index')" 
                    class="w-12 h-12 bg-white border border-outline-variant/10 rounded-lg flex items-center justify-center text-slate-400 hover:text-primary transition-all shadow-sm group"
                >
                    <span class="material-symbols-outlined text-[24px] group-hover:translate-x-1 transition-transform">chevron_right</span>
                </Link>
            </div>

            <form @submit.prevent="submit" class="space-y-10">
                <!-- 1. Identity & Technology Architecture -->
                <div class="surface-card p-10 rounded-xl border border-outline-variant/5 shadow-sm">
                    <div class="flex items-center gap-4 mb-10 justify-end">
                        <h3 class="text-sm font-black text-primary uppercase tracking-widest">هوية الفئة ومعيار النقل (Architecture)</h3>
                        <div class="w-1.5 h-6 bg-primary rounded-full"></div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        <div class="space-y-4 md:col-span-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mr-2">مُعرف الفئة (اسم الباقة)</label>
                            <input v-model="form.name" type="text" class="form-input-monolith h-16 font-black text-lg uppercase tracking-tight" required>
                        </div>

                        <div class="space-y-6 md:col-span-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mr-2">معيار بروتوكول النقل</label>
                            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 flex-row-reverse">
                                <button 
                                    v-for="tech in techTypes" 
                                    :key="tech.value"
                                    type="button"
                                    @click="form.technology_type = tech.value"
                                    class="p-6 rounded-xl border transition-all flex flex-col items-center gap-4 group/btn"
                                    :class="form.technology_type === tech.value 
                                        ? 'bg-primary text-white border-primary shadow-xl shadow-primary/20 scale-105' 
                                        : 'bg-white border-outline-variant/10 text-slate-400 hover:bg-surface-container-low'"
                                >
                                    <span class="material-symbols-outlined text-[32px] group-hover/btn:rotate-6 transition-transform">
                                        {{ tech.icon }}
                                    </span>
                                    <span class="text-[10px] font-black uppercase tracking-widest text-center">{{ tech.label }}</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Performance & Fiscal Matrix -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 flex-row-reverse">
                    <!-- Performance Matrix -->
                    <div class="surface-card p-10 rounded-xl border border-outline-variant/5 shadow-sm space-y-10">
                         <div class="flex items-center gap-4 justify-end">
                            <h3 class="text-sm font-black text-primary uppercase tracking-widest">إعدادات الأداء والسرعة (Performance)</h3>
                            <div class="w-1.5 h-6 bg-emerald-500 rounded-full"></div>
                        </div>

                        <div class="space-y-8">
                            <div class="space-y-4">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mr-2">سرعة التحميل (MBPS - Download)</label>
                                <div class="relative">
                                    <input v-model="form.speed_down" type="number" class="form-input-monolith h-16 pr-14 font-black text-lg">
                                    <span class="material-symbols-outlined absolute right-5 top-1/2 -translate-y-1/2 text-emerald-500 text-[24px]">download_for_offline</span>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mr-2">سرعة الرفع (MBPS - Upload)</label>
                                <div class="relative">
                                    <input v-model="form.speed_up" type="number" class="form-input-monolith h-16 pr-14 font-black text-lg">
                                    <span class="material-symbols-outlined absolute right-5 top-1/2 -translate-y-1/2 text-indigo-500 text-[24px]">upload_file</span>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mr-2">سعة البيانات (Quota MB)</label>
                                <div class="relative">
                                    <input v-model="form.data_limit_mb" type="number" class="form-input-monolith h-16 pr-14 font-black text-lg">
                                    <span class="material-symbols-outlined absolute right-5 top-1/2 -translate-y-1/2 text-amber-500 text-[24px]">database_set</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Fiscal Strategy -->
                    <div class="surface-card p-10 rounded-xl border border-outline-variant/5 shadow-sm space-y-10">
                         <div class="flex items-center gap-4 justify-end">
                            <h3 class="text-sm font-black text-primary uppercase tracking-widest">البيانات المالية (Pricing)</h3>
                            <div class="w-1.5 h-6 bg-amber-500 rounded-full"></div>
                        </div>

                        <div class="space-y-8">
                            <div class="space-y-4">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mr-2">سعر الباقة (ر.س)</label>
                                <div class="relative">
                                    <input v-model="form.price" type="number" step="0.01" class="form-input-monolith h-16 pr-14 font-black text-lg">
                                    <span class="material-symbols-outlined absolute right-5 top-1/2 -translate-y-1/2 text-emerald-600 text-[24px]">payments</span>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mr-2">مدة الاشتراك (أيام)</label>
                                <div class="relative">
                                    <input v-model="form.duration_days" type="number" class="form-input-monolith h-16 pr-14 font-black text-lg">
                                    <span class="material-symbols-outlined absolute right-5 top-1/2 -translate-y-1/2 text-slate-400 text-[24px]">history_toggle_off</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. Deployment Topology Selection -->
                <div class="surface-card p-10 rounded-xl border border-outline-variant/5 shadow-sm border-r-4 border-secondary">
                    <div class="flex items-center gap-4 mb-12 justify-end">
                        <h3 class="text-sm font-black text-primary uppercase tracking-widest">التوزع الجغرافي والربط (Deployment)</h3>
                        <div class="w-1.5 h-6 bg-secondary rounded-full"></div>
                    </div>

                    <div class="space-y-12">
                        <!-- Server Targets -->
                        <div class="space-y-6">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mr-2">السيرفرات المرتبطة (Masters)</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 flex-row-reverse">
                                <button 
                                    v-for="server in servers" 
                                    :key="server.id"
                                    type="button"
                                    @click="toggleTarget('server', server.id)"
                                    class="p-6 rounded-xl border-2 transition-all flex items-center justify-between group/target text-right"
                                    :class="isTargetSelected('server', server.id) 
                                        ? 'bg-primary/5 border-primary shadow-lg scale-[1.02]' 
                                        : 'bg-white border-outline-variant/10 text-slate-400 hover:bg-surface-container-low'"
                                >
                                    <div v-if="isTargetSelected('server', server.id)" class="w-6 h-6 bg-primary rounded-full flex items-center justify-center text-white">
                                        <span class="material-symbols-outlined text-[16px]">check</span>
                                    </div>
                                    <div v-else class="w-6 h-6 border-2 border-outline-variant/20 rounded-full"></div>
                                    
                                    <div class="flex-1 mr-4 min-w-0">
                                        <p class="text-sm font-black tracking-tight truncate" :class="isTargetSelected('server', server.id) ? 'text-primary' : ''">{{ server.name }}</p>
                                        <p class="text-[10px] font-mono opacity-60 mt-1">{{ server.ip }}</p>
                                    </div>
                                    <div class="w-12 h-12 rounded-lg flex items-center justify-center transition-all bg-surface-container-low" 
                                         :class="isTargetSelected('server', server.id) ? 'text-primary' : 'text-slate-300'">
                                        <span class="material-symbols-outlined text-[24px]">dns</span>
                                    </div>
                                </button>
                            </div>
                        </div>

                        <!-- Router Targets -->
                        <div class="space-y-6">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mr-2">أجهزة التوجيه الطرفية النشطة (Service Routers)</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 flex-row-reverse">
                                <button 
                                    v-for="router in routers" 
                                    :key="router.id"
                                    type="button"
                                    @click="toggleTarget('router', router.id)"
                                    class="p-6 rounded-xl border-2 transition-all flex items-center justify-between group/target text-right"
                                    :class="isTargetSelected('router', router.id) 
                                        ? 'bg-secondary/5 border-secondary shadow-lg scale-[1.02]' 
                                        : 'bg-white border-outline-variant/10 text-slate-400 hover:bg-surface-container-low'"
                                >
                                    <div v-if="isTargetSelected('router', router.id)" class="w-6 h-6 bg-secondary rounded-full flex items-center justify-center text-white">
                                        <span class="material-symbols-outlined text-[16px]">check</span>
                                    </div>
                                    <div v-else class="w-6 h-6 border-2 border-outline-variant/20 rounded-full"></div>

                                    <div class="flex-1 mr-4 min-w-0">
                                        <p class="text-sm font-black tracking-tight truncate" :class="isTargetSelected('router', router.id) ? 'text-secondary' : ''">{{ router.name }}</p>
                                        <p class="text-[10px] font-mono opacity-60 mt-1">{{ router.ip }}</p>
                                    </div>
                                    <div class="w-12 h-12 rounded-lg flex items-center justify-center transition-all bg-surface-container-low" 
                                         :class="isTargetSelected('router', router.id) ? 'text-secondary' : 'text-slate-300'">
                                        <span class="material-symbols-outlined text-[24px]">router</span>
                                    </div>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Strategic Update Submission -->
                <div class="surface-card p-12 bg-slate-900 text-white rounded-xl shadow-2xl relative overflow-hidden group/final">
                    <div class="absolute -bottom-20 -right-20 w-80 h-80 bg-primary/5 rounded-full blur-3xl group-hover/final:scale-150 transition-transform duration-1000"></div>
                    <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-10 flex-row-reverse">
                        <div class="flex items-center gap-8 flex-1 justify-end">
                            <div class="text-right">
                                 <h4 class="text-lg font-black text-primary tracking-tight">حفظ وتحديث الباقة (Update Commit)</h4>
                                 <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1.5">سيتم تحديث إعدادات الباقة في كافة السيرفرات والأجهزة المرتبطة</p>
                            </div>
                            <div class="w-20 h-20 bg-white/5 rounded-2xl flex items-center justify-center border border-white/10 shadow-inner group-hover/final:scale-110 transition-transform duration-700">
                                <span class="material-symbols-outlined text-white text-[32px]" style="font-variation-settings: 'FILL' 1">sync</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-6 relative z-10 flex-row-reverse">
                            <Link 
                                :href="route('broadband.profiles.index')" 
                                class="px-10 py-5 bg-white/5 text-slate-400 font-black text-[11px] uppercase tracking-widest rounded-lg hover:bg-white/10 transition-all active:scale-95"
                            >
                                إلغاء التعديلات
                            </Link>
                            <button 
                                type="submit" 
                                class="px-14 py-5 bg-primary text-white font-black text-[11px] uppercase tracking-[0.2em] rounded-lg shadow-2xl shadow-primary/20 hover:bg-emerald-600 transition-all hover:scale-[1.05] active:scale-95 flex items-center gap-3"
                                :disabled="form.processing"
                            >
                                <span class="material-symbols-outlined text-[20px]">save</span>
                                تأكيد وحفظ التعديلات
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </InstitutionalLayout>
</template>

