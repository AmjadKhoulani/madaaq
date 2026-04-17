<script setup>
import { ref, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import InstitutionalLayout from '@/Layouts/InstitutionalLayout.vue';
import debounce from 'lodash/debounce';

const props = defineProps({
    clients: Object,
});

const filters = ref({
    search: new URLSearchParams(window.location.search).get('search') || '',
    status: new URLSearchParams(window.location.search).get('status') || '',
    type: new URLSearchParams(window.location.search).get('type') || '',
});

const search = ref(filters.value.search);

watch(search, debounce((value) => {
    router.get(route('crm.clients.index'), { search: value, status: filters.value.status, type: filters.value.type }, {
        preserveState: true,
        replace: true
    });
}, 300));

const filterData = () => {
    router.get(route('crm.clients.index'), { search: search.value, status: filters.value.status, type: filters.value.type }, {
        preserveState: true,
        replace: true
    });
};

const formatDate = (date) => {
    if (!date) return 'وصول سيادي دائم';
    return new Date(date).toLocaleDateString('ar-SY', { year: 'numeric', month: 'long', day: 'numeric' });
};

</script>

<template>
    <InstitutionalLayout title="سجل الهوية الرقمية (CRM)">
        <Head title="سجل الهوية الرقمية (Subscriber Master) - MadaaQ" />

        <div class="max-w-7xl mx-auto pb-24 text-right px-4" dir="rtl">
            <!-- Strategic Intelligence Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-12 mb-16 flex-row-reverse text-right">
                <div class="text-right">
                    <h1 class="text-5xl font-black text-primary tracking-tighter mb-3 uppercase">سجل الهوية الرقمية (Subscriber Master)</h1>
                    <div class="flex items-center gap-6 justify-end">
                        <p class="text-slate-500 font-bold text-sm uppercase tracking-[0.2em]">حوكمة الأصول البشرية، تتبع استمرارية النبض، وإدارة حزم الامتيازات</p>
                        <span class="flex h-4 w-4 relative">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-secondary opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-4 w-4 bg-secondary"></span>
                        </span>
                    </div>
                </div>
                <Link 
                    :href="route('crm.clients.create')" 
                    class="px-14 py-6 bg-slate-950 text-white rounded-[1.5rem] font-black text-[11px] uppercase tracking-[0.3em] shadow-2xl hover:bg-primary transition-all active:scale-95 flex items-center gap-5 border border-white/10 group"
                >
                    <span class="material-symbols-outlined text-[28px] group-hover:rotate-180 transition-transform">person_add_alt_1</span>
                    تسجيل هوية (New Identity)
                </Link>
            </div>

            <!-- Analytical Strategic Filters -->
            <div class="surface-card p-12 rounded-[3rem] mb-12 border border-outline-variant/10 shadow-2xl bg-white relative overflow-hidden">
                <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-l from-primary via-secondary to-primary opacity-20"></div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 flex-row-reverse">
                    <div class="lg:col-span-2 space-y-4">
                        <label class="text-[10px] font-black text-primary uppercase tracking-[0.3em] block mr-3 italic">محرك التدقيق المركزي (Identity Search)</label>
                        <div class="relative group">
                            <span class="material-symbols-outlined absolute right-6 top-1/2 -translate-y-1/2 text-primary opacity-40 group-focus-within:opacity-100 transition-opacity text-[28px]">account_circle</span>
                            <input v-model="search" type="text" placeholder="البحث برقم SSID، اسم المشترك، أو النطاق الرقمي..." class="form-input-monolith h-18 pr-16 text-base font-black tracking-tight rounded-2xl shadow-inner uppercase">
                        </div>
                    </div>
                    <div class="space-y-4">
                        <label class="text-[10px] font-black text-primary uppercase tracking-[0.3em] block mr-3 italic">وضعية الخدمة</label>
                        <div class="relative group">
                            <select v-model="filters.status" @change="filterData" class="form-input-monolith h-18 pr-14 text-sm font-black appearance-none rounded-2xl border-slate-200">
                                <option value="">كافة التصنيفات الإدارية</option>
                                <option value="active">مستوى النبض النشط (Active)</option>
                                <option value="inactive">تجميد مؤقت للخدمة (Inactive)</option>
                            </select>
                             <span class="material-symbols-outlined absolute left-6 top-1/2 -translate-y-1/2 text-slate-300 pointer-events-none">expand_content</span>
                             <span class="material-symbols-outlined absolute right-6 top-1/2 -translate-y-1/2 text-primary opacity-40 text-[24px]">verified</span>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <label class="text-[10px] font-black text-primary uppercase tracking-[0.3em] block mr-3 italic">بروتوكول التزويد</label>
                        <div class="relative group">
                             <select v-model="filters.type" @change="filterData" class="form-input-monolith h-18 pr-14 text-sm font-black appearance-none rounded-2xl border-slate-200">
                                <option value="">كافة أنظمة التحقق</option>
                                <option value="pppoe">المصادقة النفَقية (PPPoE)</option>
                                <option value="hotspot">قسائم الوصول (Hotspot)</option>
                            </select>
                             <span class="material-symbols-outlined absolute left-6 top-1/2 -translate-y-1/2 text-slate-300 pointer-events-none">expand_content</span>
                             <span class="material-symbols-outlined absolute right-6 top-1/2 -translate-y-1/2 text-primary opacity-40 text-[24px]">sensors</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Master Identity Ledger Table -->
            <div class="surface-card rounded-[3rem] overflow-hidden shadow-2xl border border-outline-variant/10 bg-white relative">
                 <div class="absolute inset-0 bg-grid-slate-50 [mask-image:linear-gradient(0deg,white,rgba(255,255,255,0.6))] pointer-events-none opacity-20"></div>
                
                <div class="overflow-x-auto relative z-10">
                    <table class="w-full text-right border-separate border-spacing-0">
                        <thead>
                            <tr class="bg-slate-950 text-white border-b border-white/5 uppercase">
                                <th class="px-12 py-8 text-[11px] font-black uppercase tracking-[0.3em] text-white/30">الهوية الرقمية (Service Identity)</th>
                                <th class="px-10 py-8 text-[11px] font-black uppercase tracking-[0.3em] text-center text-white/30">نمط الربط والحزمة</th>
                                <th class="px-10 py-8 text-[11px] font-black uppercase tracking-[0.3em] text-center text-white/30 border-r border-white/5">أفق الصلاحية</th>
                                <th class="px-10 py-8 text-[11px] font-black uppercase tracking-[0.3em] text-center text-white/30">وضع النبض</th>
                                <th class="px-10 py-8 w-48"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant/5">
                            <tr v-for="client in clients.data" :key="client.id" class="group hover:bg-slate-50/80 transition-all duration-500">
                                <td class="px-12 py-10">
                                    <div class="flex items-center gap-10 justify-end text-right">
                                        <div>
                                            <h4 class="text-3xl font-black text-primary leading-tight group-hover:translate-x-3 transition-transform tracking-tighter uppercase font-headline">{{ client.username }}</h4>
                                            <div class="flex items-center gap-4 mt-3 opacity-40 justify-end">
                                                <p class="text-[10px] font-black uppercase tracking-[0.2em] leading-none">{{ client.name || 'هوية مدنية غير مؤكدة' }}</p>
                                                <span class="material-symbols-outlined text-[18px]">verified_user</span>
                                            </div>
                                        </div>
                                        <div class="w-20 h-20 rounded-[2rem] bg-slate-950 text-white flex items-center justify-center shadow-2xl group-hover:bg-primary group-hover:scale-110 group-hover:rotate-6 transition-all duration-700 relative overflow-hidden shrink-0 border border-white/10">
                                             <div class="absolute inset-x-0 bottom-0 h-1/2 bg-gradient-to-t from-black/20 to-transparent"></div>
                                             <span class="material-symbols-outlined text-[40px] relative z-10" style="font-variation-settings: 'FILL' 1; font-weight: 200;">account_circle</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-10 py-10 text-center">
                                    <div class="flex flex-col items-center gap-4">
                                        <div :class="[
                                            'px-6 py-3 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] border-2 transition-all shadow-xl group-hover:scale-105',
                                            client.type === 'pppoe' ? 'bg-indigo-500/10 text-indigo-600 border-indigo-500/20' : 'bg-purple-500/10 text-purple-600 border-purple-500/20'
                                        ]">
                                            {{ client.type === 'pppoe' ? 'Tunnel: PPPoE' : 'Tunnel: Hotspot' }}
                                        </div>
                                        <span class="text-[11px] font-black text-slate-500 tracking-[0.2em] leading-none uppercase italic group-hover:text-primary transition-colors">{{ client.package?.name || 'CUSTOM_QOS' }}</span>
                                    </div>
                                </td>
                                <td class="px-10 py-10 text-center border-r border-outline-variant/5">
                                    <div class="flex flex-col items-center gap-4">
                                        <span class="text-[12px] font-headline font-black text-white bg-slate-950 px-6 py-2.5 rounded-xl shadow-xl group-hover:bg-primary transition-colors italic whitespace-nowrap">{{ formatDate(client.expires_at) }}</span>
                                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.3em] leading-none italic mt-2">نهاية النبض الفعلي</p>
                                    </div>
                                </td>
                                <td class="px-10 py-10 text-center">
                                    <div v-if="client.status === 'active'" class="inline-flex items-center justify-center gap-4 px-8 py-3.5 rounded-2xl text-[10px] font-black uppercase tracking-[0.3em] border-2 transition-all shadow-2xl bg-emerald-500/10 text-emerald-600 border-emerald-500/20 group-hover:translate-y-[-4px]">
                                        <span class="relative flex h-3 w-3">
                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                                        </span>
                                        النبض مستقر (Active)
                                    </div>
                                    <div v-else class="inline-flex items-center justify-center gap-4 px-8 py-3.5 rounded-2xl text-[10px] font-black uppercase tracking-[0.3em] border-2 transition-all shadow-2xl bg-rose-500/10 text-rose-500 border-rose-500/20 opacity-60">
                                        <span class="w-3 h-3 bg-rose-500 rounded-full shadow-inner"></span>
                                        تجميد سيادي (Inactive)
                                    </div>
                                </td>
                                <td class="px-10 py-10">
                                    <div class="flex items-center justify-end gap-3 opacity-0 group-hover:opacity-100 transition-all translate-x-4 group-hover:translate-x-0">
                                         <Link 
                                            :href="route('crm.clients.show', client.id)"
                                            class="px-8 py-4 bg-white shadow-2xl border border-outline-variant/10 rounded-[1.5rem] text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 hover:text-primary transition-all flex items-center gap-4 hover:scale-105 active:scale-95 group/icon"
                                         >
                                            <span class="material-symbols-outlined text-[24px] group-hover/icon:rotate-90 transition-transform">troubleshoot</span> فحص المنظومة
                                         </Link>
                                         <Link 
                                            :href="route('crm.clients.edit', client.id)"
                                            class="w-14 h-14 bg-white shadow-2xl border border-outline-variant/10 rounded-2xl flex items-center justify-center text-slate-400 hover:text-amber-600 hover:scale-110 active:scale-90 transition-all group/icon"
                                         >
                                            <span class="material-symbols-outlined text-[28px] group-hover/icon:-rotate-12 transition-transform">edit_square</span>
                                         </Link>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Empty Master State -->
                <div v-if="clients.data.length === 0" class="py-64 flex flex-col items-center gap-12 group/empty relative z-10">
                    <div class="w-48 h-48 rounded-[3.5rem] bg-slate-950 text-white flex items-center justify-center border-8 border-white shadow-[0_0_80px_rgba(2,6,23,0.15)] group-hover/empty:scale-110 transition-all duration-1000 relative">
                        <div class="absolute inset-0 bg-primary opacity-20 blur-3xl animate-pulse"></div>
                        <span class="material-symbols-outlined text-[80px] relative z-10" style="font-variation-settings: 'wght' 100">person_search</span>
                    </div>
                    <div class="text-center">
                        <h3 class="text-4xl font-black text-primary mb-6 tracking-tighter uppercase leading-none">مصفوفة الهويات صفرية (Null Identity)</h3>
                        <p class="text-[12px] font-black text-slate-400 uppercase tracking-[0.4em] max-w-sm leading-relaxed italic opacity-70">لم يتم رصد أي سجلات ضمن المصفوفة السيادية للمشتركين. ابدأ بروتوكول Inject Identity الأول.</p>
                    </div>
                </div>

                <!-- Strategic Pagination Oversight -->
                <div v-if="clients.links && clients.links.length > 3" class="px-12 py-10 bg-slate-950 border-t border-white/5 flex justify-between items-center flex-row-reverse relative z-10 overflow-hidden">
                    <div class="absolute inset-0 bg-grid-slate-50 opacity-5 pointer-events-none"></div>
                    <div class="text-[10px] font-black text-white/20 uppercase tracking-[0.5em] font-headline relative z-10">SUBSCRIBER_REGISTRY_DISCOVERY_PROTOCOL_v8.4</div>
                    <nav class="flex gap-5 relative z-10">
                        <Link 
                            v-for="link in clients.links" 
                            :key="link.label"
                            :href="link.url || '#'"
                            class="h-14 flex items-center justify-center rounded-2xl text-[12px] font-headline font-black uppercase tracking-[0.2em] transition-all border px-8"
                            :class="link.active ? 'bg-primary text-white border-primary shadow-[0_15px_30px_rgba(37,99,235,0.3)] scale-110 z-10' : 'bg-white/5 text-white/40 border-white/10 hover:text-white hover:bg-white/10 hover:border-white/30'"
                            v-html="link.label"
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
.form-input-monolith {
    @apply w-full bg-slate-50 border-slate-200 text-slate-900 rounded-[1.5rem] pr-14 focus:ring-8 focus:ring-primary/5 focus:border-primary transition-all font-black text-base;
}
</style>
