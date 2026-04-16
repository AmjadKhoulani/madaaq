<script setup>
import { useForm, Head, router } from '@inertiajs/vue3';
import InstitutionalLayout from '@/Layouts/InstitutionalLayout.vue';

const props = defineProps({
    sources: Array,
});

const form = useForm({
    name: '',
    type: 'fiber',
    capacity: '',
    status: 'online',
    ip_gateway: '',
});

const submit = () => {
    form.post(route('network.internet-sources.store'), {
        onSuccess: () => form.reset()
    });
};

const deleteSource = (id) => {
    if (confirm('هل أنت متأكد من إخراج هذا المصدر من الخدمة؟ سيؤدي ذلك إلى تعطيل التغذية المرتبطة به.')) {
        router.delete(route('network.internet-sources.destroy', id));
    }
};

const techTypes = [
    { value: 'fiber', label: 'وصلة ضوئية (Fiber)', icon: 'settings_input_fiber' },
    { value: 'microwave', label: 'ربط لاسلكي (Microwave)', icon: 'cell_tower' },
    { value: 'starlink', label: 'ربط فضائي (Starlink)', icon: 'satellite_alt' },
    { value: '4g', label: 'ربط خلوي (4G/5G)', icon: 'signal_cellular_alt' },
    { value: 'other', label: 'مصدر بديل (Other)', icon: 'router' },
];

const getStatusClass = (status) => {
    switch (status) {
        case 'online': return 'text-emerald-500 bg-emerald-500/5 border-emerald-500/20';
        case 'offline': return 'text-rose-500 bg-rose-500/5 border-rose-500/20';
        case 'maintenance': return 'text-amber-600 bg-amber-500/5 border-amber-500/20';
        default: return 'text-slate-500 bg-slate-500/5 border-slate-500/20';
    }
};

</script>

<template>
    <InstitutionalLayout title="حوكمة مصادر الإنترنت">
        <Head title="إدارة مصادر التزويد (Upstream) - MadaaQ" />

        <div class="max-w-7xl mx-auto pb-24 text-right px-4" dir="rtl">
            <!-- Institutional Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-10 mb-16 flex-row-reverse text-right">
                <div class="text-right">
                    <h1 class="text-4xl font-black text-primary tracking-tight mb-2">حوكمة مصادر التزويد المركزية (Upstream Sources)</h1>
                    <div class="flex items-center gap-4 justify-end">
                        <p class="text-slate-500 font-bold text-sm uppercase tracking-wider">إدارة خطوط التغذية الرئيسية، تتبع السعات التجميعية، وحوكمة بوابات الخروج</p>
                        <span class="material-symbols-outlined text-[24px] text-primary">hub</span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 flex-row-reverse text-right">
                <!-- 1. Synthesis (Create Source) -->
                <div class="lg:col-span-1 space-y-10 lg:order-2">
                    <div class="surface-card p-10 rounded-3xl border border-outline-variant/5 shadow-2xl bg-white relative overflow-hidden">
                        <div class="flex items-center gap-4 mb-10 justify-end">
                            <h3 class="text-xs font-black text-primary uppercase tracking-[0.2em]">تفعيل بروتوكول تزويد جديد</h3>
                            <span class="material-symbols-outlined text-secondary">add_link</span>
                        </div>

                        <form @submit.prevent="submit" class="space-y-8">
                            <div class="space-y-4 text-right">
                                <label class="text-[11px] font-black text-primary uppercase tracking-widest mr-2 leading-none">مُعرف المصدر (Label)</label>
                                <input v-model="form.name" type="text" class="form-input-monolith h-16 font-bold" placeholder="مثال: المصدر الرئيسي للألياف الضوئية" required>
                            </div>

                            <div class="space-y-4 text-right">
                                <label class="text-[11px] font-black text-primary uppercase tracking-widest mr-2 leading-none">تكنولوجيا النقل (Interface Type)</label>
                                <select v-model="form.type" class="form-input-monolith h-16 text-sm font-black pr-12" required>
                                    <option v-for="tech in techTypes" :key="tech.value" :value="tech.value">{{ tech.label }}</option>
                                </select>
                            </div>

                            <div class="grid grid-cols-2 gap-8">
                                <div class="space-y-4 text-right">
                                    <label class="text-[11px] font-black text-primary uppercase tracking-widest mr-2 leading-none">السعة التعاقدية</label>
                                    <div class="relative">
                                        <input v-model="form.capacity" type="text" class="form-input-monolith h-16 font-headline font-black text-lg pr-12" placeholder="1000">
                                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[10px] font-black text-slate-300 uppercase tracking-widest">Mbps</span>
                                    </div>
                                </div>
                                <div class="space-y-4 text-right">
                                    <label class="text-[11px] font-black text-primary uppercase tracking-widest mr-2 leading-none">النظام التشغيلي</label>
                                    <select v-model="form.status" class="form-input-monolith h-16 text-sm font-black">
                                        <option value="online">متصل (Online)</option>
                                        <option value="offline">معطل (Offline)</option>
                                        <option value="maintenance">صيانة فنية</option>
                                    </select>
                                </div>
                            </div>

                            <div class="space-y-4 text-right">
                                <label class="text-[11px] font-black text-primary uppercase tracking-widest mr-2 leading-none">عنوان بوابة العبور (IP Gateway)</label>
                                <div class="relative group">
                                     <input v-model="form.ip_gateway" type="text" class="form-input-monolith h-16 font-headline font-black text-lg pr-14 tracking-widest" placeholder="10.0.0.1">
                                     <span class="material-symbols-outlined absolute right-5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary transition-colors">router</span>
                                </div>
                            </div>

                            <button 
                                type="submit" 
                                class="w-full py-5 bg-primary text-white rounded-xl font-black text-xs uppercase tracking-[0.2em] shadow-2xl shadow-primary/20 hover:bg-emerald-600 transition-all active:scale-95 disabled:opacity-50 flex items-center justify-center gap-4 border border-white/10"
                                :disabled="form.processing"
                            >
                                <span class="material-symbols-outlined text-[24px]">cloud_sync</span>
                                تثبيت واعتماد المصدر
                            </button>
                        </form>
                    </div>

                    <!-- Statistics Matrix Card -->
                    <div class="surface-card p-10 bg-slate-950 text-white relative overflow-hidden group rounded-3xl shadow-2xl border border-white/5">
                        <div class="absolute -top-16 -right-16 w-64 h-64 bg-primary/10 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-1000"></div>
                        <div class="relative z-10 flex flex-col items-end gap-6">
                            <p class="text-[11px] font-black text-white/40 uppercase tracking-[0.2em] mb-2 text-right">السعة الإجمالية النشطة (Aggregate Capacity)</p>
                            <div class="flex items-center gap-8 flex-row-reverse">
                                <h3 class="text-6xl font-black font-headline tracking-tighter leading-none">{{ sources.reduce((acc, s) => acc + (parseFloat(s.capacity) || 0), 0).toLocaleString() }}</h3>
                                <div class="flex flex-col items-end">
                                     <span class="text-lg font-black text-primary">Mbps</span>
                                     <span class="material-symbols-outlined text-white/10 text-[48px] leading-none">dynamic_feed</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Feed Registry (The List) -->
                <div class="lg:col-span-2 space-y-10 lg:order-1">
                    <div class="flex items-center justify-between flex-row-reverse border-b border-outline-variant/5 pb-6">
                         <div class="flex items-center gap-4">
                            <h2 class="text-3xl font-black text-primary tracking-tight leading-none">سجل مصادرات التغذية النشطة</h2>
                            <div class="w-1.5 h-8 bg-primary rounded-full"></div>
                         </div>
                         <div class="flex items-center gap-3">
                            <span class="px-6 py-2 bg-slate-100 text-slate-500 rounded-xl text-[10px] font-black uppercase tracking-widest border border-slate-200 shadow-sm">
                                المنظومة تشمل عدد ({{ sources.length.toLocaleString() }}) مصادر تغذية
                            </span>
                         </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        <div 
                            v-for="source in sources" 
                            :key="source.id"
                            class="surface-card p-10 flex flex-col relative group transition-all hover:scale-[1.03] rounded-[2.5rem] border border-outline-variant/5 shadow-2xl bg-white overflow-hidden"
                        >
                            <!-- Progress Status Indicator -->
                            <div class="absolute top-0 right-0 w-3 h-full opacity-60" :class="source.status === 'online' ? 'bg-emerald-500' : 'bg-rose-500'"></div>
                            
                            <div class="flex items-center justify-between mb-12 flex-row-reverse">
                                <div class="w-16 h-16 rounded-[1.5rem] bg-slate-950 text-white flex items-center justify-center shadow-2xl group-hover:bg-primary group-hover:rotate-12 transition-all duration-500 border border-white/10">
                                    <span class="material-symbols-outlined text-[32px]">
                                        {{ techTypes.find(t => t.value === source.type)?.icon || 'router' }}
                                    </span>
                                </div>
                                <div 
                                    class="px-5 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest border-2 flex items-center gap-3 flex-row-reverse shadow-sm"
                                    :class="getStatusClass(source.status)"
                                >
                                    <span v-if="source.status === 'online'" class="w-2.5 h-2.5 rounded-full bg-current animate-pulse"></span>
                                    {{ source.status === 'online' ? 'بث نشط (Live)' : (source.status === 'offline' ? 'منقطع (Down)' : 'قيد الصيانة') }}
                                </div>
                            </div>

                            <div class="space-y-3 mb-12 text-right">
                                <h4 class="text-2xl font-black text-primary tracking-tight truncate">{{ source.name }}</h4>
                                <div class="flex items-center gap-3 justify-end opacity-40">
                                    <p class="text-[11px] font-black uppercase tracking-widest">{{ techTypes.find(t => t.value === source.type)?.label || 'بروتوكول مخصص فائق' }}</p>
                                    <span class="material-symbols-outlined text-[16px]">verified</span>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-8 pt-8 border-t border-slate-50 mt-auto flex-row-reverse text-right">
                                <div>
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 leading-none">سعة النطاق</p>
                                    <p class="text-2xl font-headline font-black text-primary tracking-tighter">{{ source.capacity || '0' }} <span class="text-[10px] opacity-40 uppercase ml-1">Mbps</span></p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 leading-none">بوابة الخروج</p>
                                    <p class="text-xl font-headline font-black text-secondary tracking-widest truncate">{{ source.ip_gateway || 'DYNAMIC_LINK' }}</p>
                                </div>
                            </div>

                            <!-- Context Actions -->
                            <div class="absolute top-8 left-8 flex items-center gap-3 opacity-0 group-hover:opacity-100 transition-all flex-row-reverse">
                                <button @click="router.get(route('network.internet-sources.edit', source.id))" class="w-12 h-12 rounded-xl bg-white shadow-2xl flex items-center justify-center hover:bg-primary hover:text-white transition-all text-slate-400 border border-outline-variant/10 active:scale-90">
                                    <span class="material-symbols-outlined text-[24px]">design_services</span>
                                </button>
                                <button @click="deleteSource(source.id)" class="w-12 h-12 rounded-xl bg-white shadow-2xl flex items-center justify-center hover:bg-rose-500 hover:text-white transition-all text-rose-500 border border-outline-variant/10 active:scale-90">
                                    <span class="material-symbols-outlined text-[24px]">delete_sweep</span>
                                </button>
                            </div>
                        </div>

                        <!-- Empty State Protocol -->
                        <div v-if="sources.length === 0" class="col-span-full py-48 surface-card flex flex-col items-center justify-center text-center rounded-[3rem] border-4 border-dashed border-outline-variant/10 bg-slate-50 shadow-inner">
                             <div class="w-32 h-32 rounded-[2.5rem] bg-white flex items-center justify-center mb-10 text-slate-200 shadow-2xl border border-outline-variant/5">
                                <span class="material-symbols-outlined text-[64px]" style="font-variation-settings: 'wght' 100">cloud_off</span>
                             </div>
                             <h4 class="text-3xl font-black text-primary tracking-tight mb-4">لا توجد مصادر تزويد نشطة</h4>
                             <p class="text-[12px] font-black text-slate-400 uppercase tracking-[0.3em] max-w-sm mx-auto leading-relaxed italic">لم يتم رصد أي خطوط تغذية مسجلة ضمن مركز البيانات الحالي. يُرجى تعريف المصدر الأول للمنظومة.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </InstitutionalLayout>
</template>

<style scoped>
.font-headline {
    font-family: 'Manrope', sans-serif;
}
.custom-scrollbar::-webkit-scrollbar {
    width: 3px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: rgba(0, 0, 0, 0.05);
    border-radius: 10px;
}
</style>
