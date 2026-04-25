<script setup>
import { ref, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import InstitutionalLayout from '@/Layouts/InstitutionalLayout.vue';
import { pickBy, throttle } from 'lodash';

const props = defineProps({
    vouchers: Object,
    packages: Array,
    stats: Object,
    filters: Object
});

const selectedIds = ref([]);
const showFilters = ref(false);

const form = ref({
    search: props.filters.search || '',
    status: props.filters.status || '',
    package_id: props.filters.package_id || '',
    date_from: props.filters.date_from || '',
    date_to: props.filters.date_to || ''
});

watch(form, throttle(() => {
    router.get(route('hotspot.vouchers.index'), pickBy(form.value), {
        preserveState: true,
        replace: true
    });
}, 300), { deep: true });

const toggleSelectAll = (e) => {
    if (e.target.checked) {
        selectedIds.value = props.vouchers.data.map(v => v.id);
    } else {
        selectedIds.value = [];
    }
};

const handleBulkAction = (action) => {
    if (!selectedIds.value.length) return;
    
    let actionLabel = action === 'delete' ? 'إتلاف نهائي' : 'تجميد فوري';
    if (confirm(`تأكيد تنفيذ بروتوكول (${actionLabel}) على عدد (${selectedIds.value.length}) قسيمة ائتمانية؟`)) {
        router.post(route('hotspot.vouchers.bulk_action'), {
            ids: selectedIds.value,
            action: action
        }, {
            onSuccess: () => selectedIds.value = []
        });
    }
};

const getStatusClass = (status) => {
    switch (status) {
        case 'active': return 'bg-emerald-500/10 text-emerald-600 border-emerald-500/20 shadow-emerald-500/10';
        case 'inactive': return 'bg-rose-500/10 text-rose-500 border-rose-500/20 shadow-rose-500/10';
        default: return 'bg-slate-500/10 text-slate-500 border-slate-500/20';
    }
};

</script>

<template>
    <InstitutionalLayout title="مصفوفة القسائم الائتمانية">
        <Head title="مصفوفة القسائم الائتمانية (Hotspot Tokens) - MadaaQ" />

        <div class="max-w-7xl mx-auto pb-24 text-right px-4" dir="rtl">
            <!-- Institutional Command Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-12 mb-16 flex-row-reverse text-right">
                <div class="text-right">
                    <h1 class="text-5xl font-black text-primary tracking-tighter mb-3 uppercase">مصفوفة القسائم الائتمانية (Token Matrix)</h1>
                    <div class="flex items-center gap-6 justify-end">
                        <p class="text-slate-500 font-bold text-sm uppercase tracking-[0.2em]">توليد الأذونات المؤقتة، حوكمة أرصدة الهوت سبوت، وتتبع التوزيع الميداني</p>
                        <span class="flex h-4 w-4 relative">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-secondary opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-4 w-4 bg-secondary"></span>
                        </span>
                    </div>
                </div>
                <div class="flex flex-col md:flex-row items-center gap-6">
                     <Link 
                        :href="route('hotspot.vouchers.reprint_last')" 
                        class="px-10 h-18 surface-card flex items-center justify-center gap-4 text-[11px] font-black uppercase tracking-[0.3em] text-slate-600 hover:text-primary transition-all rounded-[1.5rem] border border-outline-variant/10 shadow-xl group/print"
                     >
                        <span class="material-symbols-outlined text-[28px] group-hover/print:rotate-12 transition-transform">print</span>
                        إعادة طباعة (Batch)
                     </Link>
                     <Link 
                        :href="route('hotspot.vouchers.create')" 
                        class="px-14 h-18 bg-slate-950 text-white rounded-[1.5rem] font-black text-[11px] uppercase tracking-[0.3em] shadow-2xl hover:bg-primary transition-all active:scale-95 flex items-center gap-5 border border-white/10 group"
                     >
                        <span class="material-symbols-outlined text-[28px] group-hover:rotate-180 transition-transform">confirmation_number</span>
                        توليد مصفوفة (Generate)
                     </Link>
                </div>
            </div>

            <!-- Strategic Token Metrics -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10 mb-16">
                <!-- Total Emission -->
                <div class="surface-card p-10 bg-slate-950 text-white relative overflow-hidden group rounded-[2.5rem] shadow-2xl border border-white/5">
                    <div class="absolute inset-0 bg-grid-slate-50 opacity-5 pointer-events-none"></div>
                    <div class="absolute -top-20 -right-20 w-80 h-80 bg-primary/10 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-1000"></div>
                    <div class="relative z-10 flex flex-col items-start gap-8">
                        <p class="text-[10px] font-black text-white/40 uppercase tracking-[0.3em] font-headline relative z-10 italic">إجمالي الانبعاث (Total Emission)</p>
                        <div class="flex items-center gap-10 flex-row-reverse relative z-10">
                            <h3 class="text-6xl font-black font-headline tracking-tighter leading-none">{{ stats.total.toLocaleString() }}</h3>
                            <div class="w-16 h-16 bg-white/5 rounded-2xl flex items-center justify-center border border-white/10 shadow-2xl group-hover:rotate-12 transition-transform">
                                <span class="material-symbols-outlined text-primary text-[36px]" style="font-variation-settings: 'FILL' 1">stacked_bar_chart</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Active Pulse Pool -->
                <div class="surface-card p-10 bg-white group rounded-[2.5rem] shadow-2xl border border-outline-variant/10 relative overflow-hidden">
                    <div class="absolute inset-x-0 bottom-0 h-1.5 bg-secondary opacity-20 group-hover:opacity-100 transition-opacity"></div>
                    <div class="relative z-10 flex flex-col items-start gap-8">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] font-headline italic">النبض التشغيلي (Live Pool)</p>
                        <div class="flex items-center gap-10 flex-row-reverse">
                            <h3 class="text-6xl font-black font-headline text-secondary tracking-tighter leading-none">{{ stats.active.toLocaleString() }}</h3>
                            <div class="w-16 h-16 bg-secondary/5 rounded-2xl flex items-center justify-center border border-secondary/10 shadow-inner group-hover:scale-110 transition-transform">
                                <span class="material-symbols-outlined text-secondary text-[36px]" style="font-variation-settings: 'FILL' 1">bolt</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Nullified Tokens Registry -->
                <div class="surface-card p-10 bg-white group rounded-[2.5rem] shadow-2xl border border-outline-variant/10 relative overflow-hidden">
                    <div class="absolute inset-x-0 bottom-0 h-1.5 bg-rose-500 opacity-20 group-hover:opacity-100 transition-opacity"></div>
                    <div class="relative z-10 flex flex-col items-start gap-8">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] font-headline italic">السجلات الملغاة (Nullified)</p>
                        <div class="flex items-center gap-10 flex-row-reverse">
                            <h3 class="text-6xl font-black font-headline text-rose-500 tracking-tighter leading-none">{{ stats.inactive.toLocaleString() }}</h3>
                            <div class="w-16 h-16 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center border border-rose-100 shadow-inner group-hover:animate-pulse">
                                <span class="material-symbols-outlined text-rose-500 text-[36px]" style="font-variation-settings: 'FILL' 1">do_not_disturb_on</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Precision Analytical Controls -->
            <div class="surface-card p-12 rounded-[3rem] mb-12 border border-outline-variant/10 shadow-2xl bg-white relative overflow-hidden">
                <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-l from-primary via-secondary to-primary opacity-20"></div>
                
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-10">
                    <div class="flex flex-col md:flex-row items-center gap-8 flex-1">
                        <!-- Advanced Tactical Search -->
                        <div class="relative group flex-1 max-w-xl">
                            <span class="material-symbols-outlined absolute right-6 top-1/2 -translate-y-1/2 text-primary opacity-40 group-focus-within:opacity-100 transition-opacity text-[28px]">search_check</span>
                            <input v-model="form.search" type="text" placeholder="تدقيق رمز القسيمة (Auth Token)..." class="form-input-monolith pr-16 h-18 w-full text-base font-black tracking-tight rounded-2xl shadow-inner uppercase">
                        </div>

                        <!-- Bulk Protocol Execution -->
                        <div v-if="selectedIds.length > 0" class="flex items-center gap-5 animate-in slide-in-from-right-10 duration-500">
                            <div class="h-12 w-px bg-slate-200 mx-4"></div>
                            <button @click="handleBulkAction('disable')" class="px-10 py-5 bg-slate-900 text-white rounded-2xl text-[10px] font-black uppercase tracking-[0.3em] hover:bg-primary transition-all shadow-2xl flex items-center gap-4">
                                <span class="material-symbols-outlined text-[18px]">lock</span> تجميد ({{ selectedIds.length }})
                            </button>
                            <button @click="handleBulkAction('delete')" class="px-10 py-5 bg-rose-500 text-white rounded-2xl text-[10px] font-black uppercase tracking-[0.3em] hover:bg-rose-700 transition-all shadow-2xl flex items-center gap-4">
                                <span class="material-symbols-outlined text-[18px]">delete_forever</span> إتلاف
                            </button>
                        </div>

                        <button 
                            @click="showFilters = !showFilters"
                            class="w-18 h-18 surface-card flex items-center justify-center text-slate-400 hover:text-primary transition-all rounded-[1.5rem] border border-outline-variant/10 shadow-xl group/filter"
                            :class="showFilters ? 'bg-primary text-white border-primary rotate-180' : ''"
                        >
                            <span class="material-symbols-outlined text-[32px] group-hover/filter:scale-125 transition-transform">tune</span>
                        </button>
                    </div>
                </div>

                <!-- Deep Filtering Matrix -->
                <div v-if="showFilters" class="mt-12 pt-12 border-t border-slate-100 grid grid-cols-1 md:grid-cols-4 gap-10 animate-in slide-in-from-top duration-500">
                    <div class="space-y-4 text-right">
                        <label class="text-[10px] font-black text-primary uppercase tracking-[0.3em] block mr-3 italic">الوضع التشغيلي</label>
                        <select v-model="form.status" class="form-input-monolith h-16 text-sm bg-slate-50 rounded-2xl border-slate-200">
                            <option value="">كافة الأكواد السيادية</option>
                            <option value="active">القسائم قيد الاستخدام</option>
                            <option value="inactive">القسائم الملغاة/المنتهية</option>
                        </select>
                    </div>
                    <div class="space-y-4 text-right">
                        <label class="text-[10px] font-black text-primary uppercase tracking-[0.3em] block mr-3 italic">بروفايل الباقة</label>
                        <select v-model="form.package_id" class="form-input-monolith h-16 text-sm bg-slate-50 rounded-2xl border-slate-200">
                            <option value="">كافة السيرفرات والباقات</option>
                            <option v-for="p in packages" :key="p.id" :value="p.id">{{ p.name }}</option>
                        </select>
                    </div>
                    <div class="space-y-4 text-right">
                        <label class="text-[10px] font-black text-primary uppercase tracking-[0.3em] block mr-3 italic">من تاريخ الإصدار</label>
                        <input v-model="form.date_from" type="date" class="form-input-monolith h-16 text-sm bg-slate-50 font-headline rounded-2xl border-slate-200">
                    </div>
                    <div class="space-y-4 text-right">
                        <label class="text-[10px] font-black text-primary uppercase tracking-[0.3em] block mr-3 italic">إلى تاريخ الإصدار</label>
                        <input v-model="form.date_to" type="date" class="form-input-monolith h-16 text-sm bg-slate-50 font-headline rounded-2xl border-slate-200">
                    </div>
                </div>
            </div>

            <!-- Token Matrix Registry -->
            <div class="surface-card rounded-[3rem] overflow-hidden shadow-2xl border border-outline-variant/10 bg-white relative">
                 <div class="absolute inset-0 bg-grid-slate-50 [mask-image:linear-gradient(0deg,white,rgba(255,255,255,0.6))] pointer-events-none opacity-20"></div>
                
                <div class="overflow-x-auto relative z-10">
                    <table class="w-full text-right border-separate border-spacing-0">
                        <thead>
                            <tr class="bg-slate-950 text-white border-b border-white/5 uppercase">
                                <th class="px-12 py-8 w-20 text-center">
                                    <input type="checkbox" @change="toggleSelectAll" class="w-6 h-6 rounded-lg border-white/10 bg-white/5 text-primary focus:ring-primary/20 transition-all cursor-pointer">
                                </th>
                                <th class="px-10 py-8 text-[11px] font-black uppercase tracking-[0.3em] text-white/30">كود المصادقة (Auth Token)</th>
                                <th class="px-10 py-8 text-[11px] font-black uppercase tracking-[0.3em] text-white/30 text-center">مركز التدفق (Gateway GitBranch)</th>
                                <th class="px-10 py-8 text-[11px] font-black uppercase tracking-[0.3em] text-center text-white/30 border-r border-white/5">التسعير والسرعة المخصصة</th>
                                <th class="px-10 py-8 text-[11px] font-black uppercase tracking-[0.3em] text-center text-white/30">وضعية الرمز الراهنة</th>
                                <th class="px-10 py-8 w-48"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant/5">
                            <tr v-for="v in vouchers.data" :key="v.id" class="group hover:bg-slate-50/80 transition-all duration-500" :class="selectedIds.includes(v.id) ? 'bg-primary/5' : ''">
                                <td class="px-12 py-10 text-center">
                                    <input type="checkbox" v-model="selectedIds" :value="v.id" class="w-6 h-6 rounded-lg border-slate-200 text-primary focus:ring-primary/20 transition-all cursor-pointer">
                                </td>
                                <td class="px-10 py-10">
                                    <div class="flex items-center gap-10 justify-end text-right">
                                        <div>
                                            <h4 class="text-3xl font-black text-primary leading-tight group-hover:translate-x-3 transition-transform tracking-widest uppercase font-headline">{{ v.username }}</h4>
                                            <div class="flex items-center gap-4 mt-3 opacity-40 justify-end">
                                                <p class="text-[10px] font-black uppercase tracking-[0.3em] leading-none">إصدار: <span class="font-headline font-black text-slate-900">{{ new Date(v.created_at).toLocaleDateString('ar-SY') }}</span></p>
                                                <span class="material-symbols-outlined text-[18px]">history_edu</span>
                                            </div>
                                        </div>
                                        <div class="w-20 h-20 rounded-[1.5rem] bg-slate-950 text-white flex items-center justify-center shadow-2xl group-hover:bg-primary group-hover:scale-110 group-hover:rotate-12 transition-all duration-700 relative overflow-hidden shrink-0 border border-white/10">
                                             <div class="absolute inset-x-0 bottom-0 h-1/2 bg-gradient-to-t from-black/20 to-transparent"></div>
                                             <span class="material-symbols-outlined text-[40px] relative z-10" style="font-variation-settings: 'FILL' 1; font-weight: 200;">confirmation_number</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-10 py-10 text-center">
                                    <div class="flex flex-col items-center gap-4">
                                        <div class="flex items-center gap-4 justify-center">
                                            <span class="text-[11px] font-black text-primary bg-slate-100 px-6 py-2.5 rounded-xl border border-slate-200 shadow-inner uppercase tracking-[0.2em] group-hover:bg-primary group-hover:text-white transition-all">{{ v.mikrotik_server?.name || 'خادم مركزي' }}</span>
                                            <div class="w-12 h-12 rounded-xl bg-slate-950 text-white flex items-center justify-center border border-white/10 shadow-2xl group-hover:rotate-12 transition-transform">
                                                <span class="material-symbols-outlined text-[24px]">dns</span>
                                            </div>
                                        </div>
                                        <p class="text-[12px] font-headline font-black text-slate-400 tracking-widest italic opacity-40">{{ v.mikrotik_server?.ip || 'SECURED_LINK' }}</p>
                                    </div>
                                </td>
                                <td class="px-10 py-10 text-center border-r border-outline-variant/5">
                                    <div class="px-8 py-5 bg-slate-950 text-white border border-white/10 rounded-[2rem] flex flex-col items-center shadow-[0_20px_40px_rgba(0,0,0,0.2)] group-hover:scale-110 transition-transform">
                                         <p class="text-[11px] font-black tracking-[0.3em] text-primary uppercase leading-none mb-3 italic">{{ v.package?.name || 'CUSTOM_QOS_PROFILE' }}</p>
                                         <div class="flex items-baseline gap-3">
                                            <p class="text-3xl font-black font-headline text-white tracking-tighter leading-none">{{ (v.package?.price || 0).toLocaleString() }}</p>
                                            <span class="text-[9px] font-black text-white/30 uppercase tracking-[0.2em]">SYP</span>
                                         </div>
                                    </div>
                                </td>
                                <td class="px-10 py-10 text-center">
                                    <div :class="[
                                        'inline-flex items-center justify-center gap-4 px-8 py-3.5 rounded-2xl text-[10px] font-black uppercase tracking-[0.3em] border-2 transition-all shadow-2xl group-hover:translate-y-[-4px]',
                                        getStatusClass(v.status)
                                    ]">
                                        <span v-if="v.status === 'active'" class="relative flex h-3 w-3">
                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-current opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-3 w-3 bg-current"></span>
                                        </span>
                                        {{ v.status === 'active' ? 'نبض نشط (Active)' : 'منتهي سيادياً (Null)' }}
                                    </div>
                                </td>
                                <td class="px-10 py-10">
                                    <div class="flex items-center justify-end gap-3 opacity-0 group-hover:opacity-100 transition-all translate-x-4 group-hover:translate-x-0">
                                         <button class="w-14 h-14 bg-white shadow-2xl border border-outline-variant/10 rounded-2xl flex items-center justify-center text-slate-400 hover:text-primary hover:scale-110 active:scale-90 transition-all group/icon">
                                            <span class="material-symbols-outlined text-[28px] group-hover/icon:rotate-12 transition-transform" style="font-variation-settings: 'wght' 700">query_stats</span>
                                         </button>
                                         <button class="w-14 h-14 bg-white shadow-2xl border border-outline-variant/10 rounded-2xl flex items-center justify-center text-slate-400 hover:text-secondary hover:scale-110 active:scale-90 transition-all group/icon">
                                            <span class="material-symbols-outlined text-[28px] group-hover/icon:-rotate-12 transition-transform" style="font-variation-settings: 'wght' 700">print</span>
                                         </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Null Token Protocol (Empty State) -->
                <div v-if="vouchers.data.length === 0" class="py-64 flex flex-col items-center gap-12 group/empty relative z-10">
                    <div class="w-48 h-48 rounded-[3.5rem] bg-slate-950 text-white flex items-center justify-center border-8 border-white shadow-[0_0_80px_rgba(2,6,23,0.15)] group-hover/empty:scale-110 transition-all duration-1000 relative">
                        <div class="absolute inset-0 bg-primary opacity-20 blur-3xl animate-pulse"></div>
                        <span class="material-symbols-outlined text-[80px] relative z-10" style="font-variation-settings: 'wght' 100">confirmation_number</span>
                    </div>
                    <div class="text-center">
                        <h3 class="text-4xl font-black text-primary mb-6 tracking-tighter uppercase leading-none">مصفوفة الأكواد صفرية (Null Token)</h3>
                        <p class="text-[12px] font-black text-slate-400 uppercase tracking-[0.4em] max-w-sm leading-relaxed italic opacity-70">لم يتم رصد أي قسائم ائتمانية ضمن هذا القطاع. يُرجى تفعيل بروتوكول Generate الأول.</p>
                    </div>
                </div>

                <!-- Tactical Token Pagination -->
                <div class="px-12 py-10 bg-slate-950 border-t border-white/5 flex justify-between items-center flex-row-reverse relative z-10 overflow-hidden">
                    <div class="absolute inset-0 bg-grid-slate-50 opacity-5 pointer-events-none"></div>
                    <div class="text-[10px] font-black text-white/20 uppercase tracking-[0.5em] font-headline relative z-10">TOKEN_REGISTRY_OVERSIGHT_PROTOCOL_v5.2</div>
                    <nav class="flex gap-5 relative z-10">
                        <Link 
                            v-for="(link, k) in vouchers.links" 
                            :key="k"
                            :href="link.url || '#'"
                            v-html="link.label"
                            class="h-14 flex items-center justify-center rounded-2xl text-[12px] font-headline font-black uppercase tracking-[0.2em] transition-all border px-8"
                            :class="[
                                link.active ? 'bg-primary text-white border-primary shadow-[0_15px_30px_rgba(37,99,235,0.3)] scale-110 z-10' : 'bg-white/5 text-white/40 border-white/10 hover:text-white hover:bg-white/10 hover:border-white/30',
                                !link.url ? 'opacity-20 pointer-events-none' : ''
                            ]"
                        />
                    </nav>
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
</style>

