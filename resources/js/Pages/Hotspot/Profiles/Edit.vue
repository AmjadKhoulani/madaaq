<script setup>
import { onMounted } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
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
    technology_type: props.profile.technology_type || 'wireless',
    targets: [],
});

onMounted(() => {
    // Populate targets from relations
    const serverTargets = props.profile.mikrotik_servers?.map(s => `server_${s.id}`) || [];
    const routerTargets = props.profile.routers?.map(r => `router_${r.id}`) || [];
    form.targets = [...serverTargets, ...routerTargets];
});

const techTypes = [
    { value: 'fiber', label: 'وصلة ضوئية (Fiber)', icon: 'settings_input_fiber' },
    { value: 'wireless', label: 'ربط لاسلكي (Microwave)', icon: 'cell_tower' },
    { value: 'dsl', label: 'خط سلكي DSL', icon: 'hub' },
    { value: 'cable', label: 'ربط سلكي (Gigabit)', icon: 'settings_ethernet' },
];

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

const submit = () => {
    form.put(route('hotspot.profiles.update', props.profile.id));
};

</script>

<template>
    <InstitutionalLayout :title="'تعديل فئة: ' + profile.name">
        <Head :title="'تعديل باقة ' + profile.name + ' - MadaaQ'" />

        <div class="max-w-5xl mx-auto pb-24 text-right px-4" dir="rtl">
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-8 mb-16 flex-row-reverse">
                <div class="flex items-center gap-6 flex-row-reverse text-right">
                    <Link 
                        :href="route('hotspot.profiles.index')" 
                        class="w-14 h-14 bg-white shadow-sm border border-outline-variant/10 rounded-xl flex items-center justify-center text-slate-400 hover:text-primary transition-all group"
                    >
                        <span class="material-symbols-outlined text-[28px] group-hover:translate-x-2 transition-transform">arrow_forward</span>
                    </Link>
                    <div class="text-right">
                        <h1 class="text-4xl font-black text-primary tracking-tight mb-2">تعديل معايير الباقة</h1>
                        <p class="text-slate-500 font-bold text-sm uppercase tracking-wider flex items-center gap-3 justify-end">
                            <span>صيانة بروتوكول السرعة والتحكم للفئة: {{ profile.name }}</span>
                            <span class="material-symbols-outlined text-primary text-[20px]">tune</span>
                        </p>
                    </div>
                </div>
            </div>

            <form @submit.prevent="submit" class="space-y-10">
                <!-- 1. Tier Identity -->
                <div class="surface-card p-12 rounded-2xl relative overflow-hidden border border-outline-variant/5 shadow-sm">
                    <div class="flex items-center gap-3 mb-10 justify-end">
                        <h3 class="text-xs font-black text-primary uppercase tracking-[0.2em]">هوية الفئة وبروتوكول النقل</h3>
                        <span class="material-symbols-outlined text-secondary">fingerprint</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10 text-right">
                        <div class="space-y-4 md:col-span-2">
                            <label class="text-[11px] font-black text-primary uppercase tracking-widest mr-2">مُعرف الفئة (Unique Profile Name)</label>
                            <input v-model="form.name" type="text" class="form-input-monolith text-2xl font-black tracking-tight h-16" required>
                        </div>

                        <div class="space-y-6 md:col-span-2">
                            <label class="text-[11px] font-black text-primary uppercase tracking-widest mr-2">معيار النقل الفيزيائي (Physical Medium)</label>
                            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 flex-row-reverse">
                                <button 
                                    v-for="tech in techTypes" 
                                    :key="tech.value"
                                    type="button"
                                    @click="form.technology_type = tech.value"
                                    class="p-8 rounded-2xl border transition-all flex flex-col items-center gap-5 group"
                                    :class="form.technology_type === tech.value ? 'bg-primary text-white border-primary shadow-2xl scale-105' : 'bg-surface-container-low border-outline-variant/10 text-slate-400 hover:bg-white hover:border-primary/30'"
                                >
                                    <span class="material-symbols-outlined text-[36px] group-hover:rotate-6 transition-transform">{{ tech.icon }}</span>
                                    <span class="text-[10px] font-black uppercase tracking-widest text-center leading-relaxed">{{ tech.label }}</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Performance & Pricing -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                    <!-- Velocity Matrix -->
                    <div class="surface-card p-12 space-y-10 rounded-2xl text-right border border-outline-variant/5 shadow-sm">
                         <div class="flex items-center gap-3 justify-end">
                            <h3 class="text-xs font-black text-primary uppercase tracking-[0.2em]">نبض السرعة (Velocity Control)</h3>
                            <span class="material-symbols-outlined text-emerald-500">speed</span>
                        </div>

                        <div class="space-y-8">
                            <div class="space-y-4">
                                <label class="text-[11px] font-black text-primary uppercase tracking-widest mr-2">معدل التنزيل (Download / Mbps)</label>
                                <div class="relative">
                                    <input v-model="form.speed_down" type="number" class="form-input-monolith h-16 pr-16 font-headline font-black text-2xl">
                                    <span class="material-symbols-outlined absolute right-6 top-1/2 -translate-y-1/2 text-emerald-500 text-[28px]">download_for_offline</span>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <label class="text-[11px] font-black text-primary uppercase tracking-widest mr-2">معدل الرفع (Upload / Mbps)</label>
                                <div class="relative">
                                    <input v-model="form.speed_up" type="number" class="form-input-monolith h-16 pr-16 font-headline font-black text-2xl">
                                    <span class="material-symbols-outlined absolute right-6 top-1/2 -translate-y-1/2 text-indigo-500 text-[28px]">upload_file</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Commercial Policy -->
                    <div class="surface-card p-12 space-y-10 rounded-2xl text-right border border-outline-variant/5 shadow-sm">
                         <div class="flex items-center gap-3 justify-end">
                            <h3 class="text-xs font-black text-primary uppercase tracking-[0.2em]">السياسة المالية (Financial Policy)</h3>
                            <span class="material-symbols-outlined text-rose-500">payments</span>
                        </div>

                        <div class="space-y-8">
                            <div class="space-y-4">
                                <label class="text-[11px] font-black text-primary uppercase tracking-widest mr-2">القيمة الاسمية للقسيمة (S.P)</label>
                                <div class="relative">
                                    <input v-model="form.price" type="number" step="0.01" class="form-input-monolith h-16 pr-16 font-headline font-black text-2xl">
                                    <span class="material-symbols-outlined absolute right-6 top-1/2 -translate-y-1/2 text-emerald-600 text-[28px]">currency_exchange</span>
                                </div>
                            </div>

                            <div class="p-8 bg-slate-950 text-white rounded-2xl relative overflow-hidden group shadow-2xl border border-white/5">
                                <div class="absolute -top-10 -right-10 w-40 h-40 bg-primary/10 rounded-full blur-3xl group-hover:scale-125 transition-all"></div>
                                <div class="relative z-10 flex items-center gap-6 flex-row-reverse text-right">
                                     <span class="material-symbols-outlined text-white/20 text-[56px] rotate-12">confirmation_number</span>
                                     <div>
                                         <p class="text-[9px] font-black text-primary uppercase tracking-widest mb-1">تحديث الحالة</p>
                                         <p class="text-sm font-bold opacity-80 leading-relaxed italic">يتم مزامنة التعديلات البرمجية مع العقد الطرفية فور اعتماد البروتوكول المحدث.</p>
                                     </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. Deployment Topology -->
                <div class="surface-card p-12 rounded-2xl border border-outline-variant/5 shadow-sm">
                    <div class="flex items-center gap-3 mb-12 justify-end">
                        <h3 class="text-xs font-black text-primary uppercase tracking-[0.2em]">طوبوغرافيا النشر (Static Distribution)</h3>
                        <span class="material-symbols-outlined text-primary">hub</span>
                    </div>

                    <div class="space-y-16 text-right">
                        <!-- Server Targets -->
                        <div class="space-y-8">
                            <div class="flex items-center gap-4 justify-end">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">بوابات النفاذ الأساسية (Mikrotik Gateways)</span>
                                <div class="h-px bg-outline-variant/10 flex-1"></div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 flex-row-reverse">
                                <button 
                                    v-for="server in servers" 
                                    :key="server.id"
                                    type="button"
                                    @click="toggleTarget('server', server.id)"
                                    class="p-8 rounded-2xl border-2 transition-all flex items-center gap-5 group text-right flex-row-reverse shadow-sm"
                                    :class="isTargetSelected('server', server.id) ? 'bg-primary text-white border-primary shadow-xl scale-[1.02]' : 'bg-surface-container-low border-outline-variant/10 text-slate-400 hover:bg-white hover:border-primary/20'"
                                >
                                    <div class="w-14 h-14 rounded-xl flex items-center justify-center transition-all bg-white/10 group-hover:rotate-12 shrink-0 border border-white/5" :class="isTargetSelected('server', server.id) ? 'text-white' : 'text-primary'">
                                        <span class="material-symbols-outlined text-[32px]">dns</span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-base font-black tracking-tight truncate">{{ server.name }}</p>
                                        <p class="text-[10px] font-headline font-black opacity-60 tracking-wider">{{ server.ip }}</p>
                                    </div>
                                    <div v-if="isTargetSelected('server', server.id)" class="w-8 h-8 bg-white rounded-full flex items-center justify-center text-primary shrink-0 shadow-lg">
                                        <span class="material-symbols-outlined text-[20px] font-black">done_all</span>
                                    </div>
                                </button>
                            </div>
                        </div>

                        <!-- Router Targets -->
                        <div class="space-y-8">
                             <div class="flex items-center gap-4 justify-end">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">محطات البث والربط (Edge Nodes)</span>
                                <div class="h-px bg-outline-variant/10 flex-1"></div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 flex-row-reverse">
                                <button 
                                    v-for="router in routers" 
                                    :key="router.id"
                                    type="button"
                                    @click="toggleTarget('router', router.id)"
                                    class="p-8 rounded-2xl border-2 transition-all flex items-center gap-5 group text-right flex-row-reverse shadow-sm"
                                    :class="isTargetSelected('router', router.id) ? 'bg-indigo-600 text-white border-indigo-600 shadow-xl scale-[1.02]' : 'bg-surface-container-low border-outline-variant/10 text-slate-400 hover:bg-white hover:border-indigo-600/20'"
                                >
                                    <div class="w-14 h-14 rounded-xl flex items-center justify-center transition-all bg-white/10 group-hover:rotate-12 shrink-0 border border-white/5" :class="isTargetSelected('router', router.id) ? 'text-white' : 'text-indigo-600'">
                                        <span class="material-symbols-outlined text-[32px]">router</span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-base font-black tracking-tight truncate">{{ router.name }}</p>
                                        <p class="text-[10px] font-headline font-black opacity-60 tracking-wider">{{ router.ip }}</p>
                                    </div>
                                    <div v-if="isTargetSelected('router', router.id)" class="w-8 h-8 bg-white rounded-full flex items-center justify-center text-indigo-600 shrink-0 shadow-lg">
                                        <span class="material-symbols-outlined text-[20px] font-black">done_all</span>
                                    </div>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Governance Commitment -->
                <div class="flex flex-col md:flex-row items-center justify-between gap-10 surface-card p-12 bg-slate-950 text-white rounded-2xl relative overflow-hidden flex-row-reverse shadow-2xl border border-white/5">
                    <div class="absolute -bottom-20 -right-20 w-80 h-80 bg-primary/10 rounded-full blur-3xl"></div>
                    <div class="relative z-10 flex items-center gap-8 flex-row-reverse text-right">
                        <div class="w-20 h-20 bg-white/10 rounded-2xl flex items-center justify-center text-3xl shrink-0 border border-white/5">
                            <span class="material-symbols-outlined text-amber-500 text-[40px]">sync_saved_locally</span>
                        </div>
                        <div>
                             <h4 class="text-2xl font-black uppercase tracking-tight mb-2">تأكيد المزامنة والمطابقة</h4>
                             <p class="text-[11px] font-black text-white/40 uppercase tracking-widest leading-relaxed">
                                تحديث معايير الفئة في كافة العقد الطرفية ومطابقة قواعد المرور الحالية.
                             </p>
                        </div>
                    </div>
                    <div class="relative z-10 flex items-center gap-6 flex-row-reverse">
                         <button 
                            type="submit" 
                            class="px-14 py-5 bg-primary text-white font-black text-xs uppercase tracking-[0.2em] rounded-xl shadow-2xl shadow-primary/30 hover:bg-primary/90 transition-all hover:scale-105 active:scale-95 disabled:opacity-50 flex items-center gap-4 border border-white/10"
                            :disabled="form.processing"
                        >
                            <span class="material-symbols-outlined text-[24px]">cloud_sync</span> تثبيت المزامنة
                        </button>
                        <Link 
                            :href="route('hotspot.profiles.index')" 
                            class="px-10 py-5 bg-white/5 text-white/70 font-black text-xs uppercase tracking-widest rounded-xl hover:bg-white/10 hover:text-white transition-all active:scale-95 border border-white/5"
                        >
                            إلغاء التعديلات
                        </Link>
                    </div>
                </div>
            </form>
        </div>
    </InstitutionalLayout>
</template>

<style scoped>
.font-headline {
    font-family: 'Manrope', sans-serif;
}
</style>
