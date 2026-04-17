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
    if (confirm('تأكيد إخراج مصدر التزويد من المنظومة؟ سيؤدي هذا الإجراء إلى انقطاع فوري في تغذية قطاعات الشبكة المرتبطة.')) {
        router.delete(route('network.internet-sources.destroy', id));
    }
};

const techTypes = [
    { value: 'fiber', label: 'الوصل الضوئي المستقر (Fiber)', icon: 'settings_input_fiber' },
    { value: 'microwave', label: 'الربط الراديوي الميداني (MW)', icon: 'cell_tower' },
    { value: 'starlink', label: 'النبض الفضائي السيادي (Starlink)', icon: 'satellite_alt' },
    { value: '4g', label: 'شبكات الخلوي المتاحة (4G/5G)', icon: 'signal_cellular_alt' },
    { value: 'other', label: 'مسار تزويد بديل (Alternative)', icon: 'router' },
];

const getStatusClass = (status) => {
    switch (status) {
        case 'online': return 'text-emerald-500 bg-emerald-500/5 border-emerald-500/20 shadow-emerald-500/10';
        case 'offline': return 'text-rose-500 bg-rose-500/5 border-rose-500/20 shadow-rose-500/10';
        case 'maintenance': return 'text-amber-600 bg-amber-500/5 border-amber-500/20';
        default: return 'text-slate-500 bg-slate-500/5 border-slate-500/20';
    }
};

</script>

<template>
    <InstitutionalLayout title="حوكمة مصادر التزويد (Backbone)">
        <Head title="بوابة التزويد المركزية (Upstream Backbone) - MadaaQ" />

        <div class="max-w-7xl mx-auto pb-24 text-right px-4" dir="rtl">
            <!-- Strategic Intelligence Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-12 mb-16 flex-row-reverse text-right">
                <div class="text-right">
                    <h1 class="text-5xl font-black text-primary tracking-tighter mb-3 uppercase">بوابة التزويد المركزية (Upstream Backbone)</h1>
                    <div class="flex items-center gap-6 justify-end">
                        <p class="text-slate-500 font-bold text-sm uppercase tracking-[0.2em]">إدارة خطوط التغذية السيادية، حوكمة بوابات العبور، وتتبع السعات الإجمالية</p>
                        <span class="flex h-4 w-4 relative">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-secondary opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-4 w-4 bg-secondary"></span>
                        </span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-16 flex-row-reverse text-right">
                <!-- 1. Synthesis Matrix (Provisioning Form) -->
                <div class="lg:col-span-1 space-y-12 lg:order-2">
                    <div class="surface-card p-12 rounded-[2.5rem] border border-outline-variant/10 shadow-2xl bg-white relative overflow-hidden group">
                        <div class="absolute inset-x-0 top-0 h-1.5 bg-gradient-to-l from-primary to-secondary"></div>
                        
                        <div class="flex items-center gap-5 mb-12 justify-end">
                            <h3 class="text-xs font-black text-primary uppercase tracking-[0.3em]">تفعيل بروتوكول تزويد (Inject Feed)</h3>
                            <div class="w-12 h-12 bg-slate-950 text-white rounded-xl flex items-center justify-center shadow-2xl group-hover:rotate-12 transition-transform">
                                <span class="material-symbols-outlined text-[24px]">power_input</span>
                            </div>
                        </div>

                        <form @submit.prevent="submit" class="space-y-8">
                            <div class="space-y-4 text-right">
                                <label class="text-[10px] font-black text-primary uppercase tracking-[0.3em] block mr-3 italic">مُعرف المصدر (Backbone Label)</label>
                                <input v-model="form.name" type="text" class="form-input-monolith h-18 text-base font-black tracking-tight" placeholder="مثال: البوابة الضوئية الدولية" required>
                            </div>

                            <div class="space-y-4 text-right">
                                <label class="text-[10px] font-black text-primary uppercase tracking-[0.3em] block mr-3 italic">تكنولوجيا الربط (Layer 1 Type)</label>
                                <div class="relative group">
                                    <select v-model="form.type" class="form-input-monolith h-18 pr-16 text-sm font-black appearance-none" required>
                                        <option v-for="tech in techTypes" :key="tech.value" :value="tech.value">{{ tech.label }}</option>
                                    </select>
                                    <span class="material-symbols-outlined absolute right-6 top-1/2 -translate-y-1/2 text-primary text-[28px] opacity-40 group-hover:opacity-100 transition-opacity">settings_ethernet</span>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-8">
                                <div class="space-y-4 text-right">
                                    <label class="text-[10px] font-black text-primary uppercase tracking-[0.3em] block mr-3 italic">السعة التعاقدية</label>
                                    <div class="relative group">
                                        <input v-model="form.capacity" type="text" class="form-input-monolith h-18 font-headline font-black text-xl pr-6 text-center" placeholder="1000">
                                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[9px] font-black text-slate-300 uppercase tracking-widest">MBPS</span>
                                    </div>
                                </div>
                                <div class="space-y-4 text-right">
                                    <label class="text-[10px] font-black text-primary uppercase tracking-[0.3em] block mr-3 italic">نمط العمل</label>
                                    <select v-model="form.status" class="form-input-monolith h-18 text-sm font-black text-center">
                                        <option value="online">متزامن (Live)</option>
                                        <option value="offline">معطل (Down)</option>
                                        <option value="maintenance">تحت المعاينة</option>
                                    </select>
                                </div>
                            </div>

                            <div class="space-y-4 text-right">
                                <label class="text-[10px] font-black text-primary uppercase tracking-[0.3em] block mr-3 italic">بوابة العبور (IP Gateway Hub)</label>
                                <div class="relative group">
                                     <input v-model="form.ip_gateway" type="text" class="form-input-monolith h-18 font-headline font-black text-lg pr-16 tracking-[0.2em]" placeholder="10.0.0.1">
                                     <span class="material-symbols-outlined absolute right-6 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary transition-colors text-[28px]">router</span>
                                </div>
                            </div>

                            <button 
                                type="submit" 
                                class="w-full py-6 bg-primary text-white rounded-[1.5rem] font-black text-xs uppercase tracking-[0.4em] shadow-[0_25px_50px_rgba(37,99,235,0.25)] hover:bg-slate-950 transition-all active:scale-95 disabled:opacity-50 flex items-center justify-center gap-5 border border-white/10 group/btn"
                                :disabled="form.processing"
                            >
                                <span class="material-symbols-outlined text-[28px] group-hover:animate-pulse">bolt</span>
                                اعتماد وتثبيت المصدر
                            </button>
                        </form>
                    </div>

                    <!-- Aggregate Backbone Statistics -->
                    <div class="surface-card p-12 bg-slate-950 text-white relative overflow-hidden group rounded-[2.5rem] shadow-2xl border border-white/5">
                        <div class="absolute inset-0 bg-grid-slate-50 opacity-5 pointer-events-none"></div>
                        <div class="absolute -top-20 -right-20 w-80 h-80 bg-primary/20 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-1000"></div>
                        <div class="relative z-10 flex flex-col items-start gap-8">
                            <p class="text-[10px] font-black text-white/40 uppercase tracking-[0.3em] mb-2 text-right">إجمالي السعة التزويدية النشطة (Aggr. Link)</p>
                            <div class="flex items-center gap-10 flex-row-reverse">
                                <h3 class="text-7xl font-black font-headline tracking-tighter leading-none">{{ sources.reduce((acc, s) => acc + (parseFloat(s.capacity) || 0), 0).toLocaleString() }}</h3>
                                <div class="flex flex-col items-center">
                                     <span class="text-2xl font-black text-primary italic">Mbps</span>
                                     <div class="w-1.5 h-16 bg-primary rounded-full mt-4 opacity-50"></div>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 px-6 py-2 bg-white/5 rounded-xl border border-white/10 mt-4">
                                <span class="material-symbols-outlined text-primary text-[20px] animate-pulse">flowsheet</span>
                                <span class="text-[10px] font-black uppercase tracking-[0.2em]">Backbone Registry Active</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Feed Infrastructure Registry -->
                <div class="lg:col-span-2 space-y-12 lg:order-1">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-10 flex-row-reverse border-b border-outline-variant/10 pb-10">
                         <div class="flex items-center gap-6">
                            <h2 class="text-4xl font-black text-primary tracking-tighter leading-none">مصفوفة خطوط التغذية (Backbone Registry)</h2>
                            <div class="w-2 h-10 bg-primary rounded-full"></div>
                         </div>
                         <div class="flex items-center gap-4">
                            <span class="px-8 py-3 bg-slate-100 text-slate-500 rounded-[1.5rem] text-[10px] font-black uppercase tracking-[0.2em] border border-slate-200 shadow-inner">
                                تعداد المداخل: <span class="text-primary">{{ sources.length.toLocaleString() }}</span> ACTIVE_FEEDS
                            </span>
                         </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                        <div 
                            v-for="source in sources" 
                            :key="source.id"
                            class="surface-card p-12 flex flex-col relative group transition-all hover:scale-[1.02] rounded-[3rem] border border-outline-variant/10 shadow-2xl bg-white overflow-hidden"
                        >
                            <div class="absolute inset-0 bg-grid-slate-50 [mask-image:linear-gradient(0deg,white,rgba(255,255,255,0.4))] pointer-events-none opacity-20"></div>
                            
                            <!-- Tactical Status Indicator -->
                            <div class="absolute top-0 right-0 w-4 h-full opacity-40 group-hover:opacity-100 transition-opacity" :class="source.status === 'online' ? 'bg-emerald-500' : 'bg-rose-500'"></div>
                            
                            <div class="flex items-center justify-between mb-16 flex-row-reverse">
                                <div class="w-20 h-20 rounded-[2rem] bg-slate-950 text-white flex items-center justify-center shadow-2xl group-hover:bg-primary group-hover:rotate-12 transition-all duration-700 border border-white/10 relative overflow-hidden">
                                     <div class="absolute inset-x-0 bottom-0 h-1/2 bg-gradient-to-t from-black/20 to-transparent"></div>
                                    <span class="material-symbols-outlined text-[40px] relative z-10" style="font-variation-settings: 'wght' 200">
                                        {{ techTypes.find(t => t.value === source.type)?.icon || 'hub' }}
                                    </span>
                                </div>
                                <div 
                                    class="px-6 py-3 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] border-2 flex items-center gap-4 flex-row-reverse shadow-xl group-hover:scale-105 transition-transform"
                                    :class="getStatusClass(source.status)"
                                >
                                    <span v-if="source.status === 'online'" class="relative flex h-3 w-3">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-current opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-3 w-3 bg-current"></span>
                                    </span>
                                    {{ source.status === 'online' ? 'بث نشط (Live)' : (source.status === 'offline' ? 'منقطع سيادياً' : 'قيد المعاينة') }}
                                </div>
                            </div>

                            <div class="space-y-4 mb-16 text-right">
                                <h4 class="text-3xl font-black text-primary tracking-tighter truncate uppercase group-hover:translate-x-3 transition-transform">{{ source.name }}</h4>
                                <div class="flex items-center gap-4 justify-end opacity-40">
                                    <p class="text-[11px] font-black uppercase tracking-[0.2em]">{{ techTypes.find(t => t.value === source.type)?.label }}</p>
                                    <span class="material-symbols-outlined text-[18px]">verified_user</span>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-10 pt-10 border-t border-slate-50 mt-auto flex-row-reverse text-right relative z-10">
                                <div>
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 leading-none italic">سعة النطاق</p>
                                    <p class="text-4xl font-headline font-black text-primary tracking-tighter">{{ source.capacity || '0' }} <span class="text-[11px] opacity-40 uppercase ml-2 tracking-widest font-sans">Mbps</span></p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 leading-none italic">بوابة العبور</p>
                                    <p class="text-2xl font-headline font-black text-secondary tracking-[0.2em] truncate group-hover:text-primary transition-colors">{{ source.ip_gateway || 'DYNAMIC' }}</p>
                                </div>
                            </div>

                            <!-- Tactical Context Actions -->
                            <div class="absolute top-10 left-10 flex items-center gap-4 opacity-0 group-hover:opacity-100 transition-all flex-row-reverse translate-x-[-10px] group-hover:translate-x-0">
                                <button @click="router.get(route('network.internet-sources.edit', source.id))" class="w-14 h-14 rounded-2xl bg-white shadow-2xl flex items-center justify-center hover:bg-primary hover:text-white transition-all text-slate-400 border border-outline-variant/10 active:scale-90 group/act">
                                    <span class="material-symbols-outlined text-[28px] group-hover/act:rotate-90 transition-transform">design_services</span>
                                </button>
                                <button @click="deleteSource(source.id)" class="w-14 h-14 rounded-2xl bg-white shadow-2xl flex items-center justify-center hover:bg-rose-600 hover:text-white transition-all text-rose-500 border border-outline-variant/10 active:scale-90 group/act">
                                    <span class="material-symbols-outlined text-[28px] group-hover/act:scale-75 transition-transform">delete_forever</span>
                                </button>
                            </div>
                        </div>

                        <!-- Null Flow Protocol (Empty State) -->
                        <div v-if="sources.length === 0" class="col-span-full py-64 surface-card flex flex-col items-center justify-center text-center rounded-[4rem] border-4 border-dashed border-outline-variant/10 bg-slate-50 shadow-inner group/empty">
                             <div class="w-40 h-40 rounded-[3rem] bg-white flex items-center justify-center mb-12 text-slate-200 shadow-2xl border border-outline-variant/5 group-hover/empty:scale-110 group-hover/empty:rotate-12 transition-all duration-1000">
                                <span class="material-symbols-outlined text-[80px]" style="font-variation-settings: 'wght' 100">cloud_sync</span>
                             </div>
                             <h4 class="text-4xl font-black text-primary tracking-tighter mb-6 uppercase">تدفق البيانات منخفض (Null Flow)</h4>
                             <p class="text-[13px] font-black text-slate-400 uppercase tracking-[0.4em] max-w-sm mx-auto leading-relaxed italic opacity-70">لم يتم تعريف أي بوابات تزويد سيادية ضمن هذا القطاع. يُرجى تفعيل بروتوكول Inject Feed الأول.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </InstitutionalLayout>
</template>

<style scoped>
.font-headline { font-family: 'Manrope', sans-serif; }
.bg-grid-slate-50 {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32' width='32' height='32' fill='none' stroke='rgb(15 23 42 / 0.05)'%3E%3Cpath d='M0 .5H31.5V32'/%3E%3C/svg%3E");
}
.form-input-monolith {
    @apply w-full bg-slate-50 border-slate-200 text-slate-900 rounded-[1.5rem] pr-14 focus:ring-8 focus:ring-primary/5 focus:border-primary transition-all font-black text-base;
}
.no-scrollbar::-webkit-scrollbar { display: none; }
.no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
