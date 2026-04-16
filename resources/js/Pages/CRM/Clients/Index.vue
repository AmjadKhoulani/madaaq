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
    if (!date) return 'وصول دائم (Perpetual)';
    return new Date(date).toLocaleDateString('ar-SY', { year: 'numeric', month: 'long', day: 'numeric' });
};

</script>

<template>
    <InstitutionalLayout title="مركز إدارة المشتركين">
        <Head title="سجل أصول المشتركين (CRM) - MadaaQ" />

        <div class="max-w-7xl mx-auto pb-24 text-right px-4" dir="rtl">
            <!-- Institutional Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-10 mb-16 flex-row-reverse text-right">
                <div class="text-right">
                    <h1 class="text-4xl font-black text-primary tracking-tight mb-2">قاعدة بيانات المشتركين (Legacy CRM)</h1>
                    <div class="flex items-center gap-4 justify-end">
                        <p class="text-slate-500 font-bold text-sm uppercase tracking-wider">إدارة الهوية الرقمية، بروتوكولات الربط، وحوكمة استمرارية الخدمة</p>
                        <span class="material-symbols-outlined text-[24px] text-primary">groups_3</span>
                    </div>
                </div>
                <Link 
                    :href="route('crm.clients.create')" 
                    class="px-12 py-5 bg-primary text-white rounded-xl font-black text-xs uppercase tracking-[0.2em] shadow-2xl shadow-primary/20 hover:bg-emerald-600 transition-all active:scale-95 flex items-center gap-4 border border-white/10"
                >
                    <span class="material-symbols-outlined text-[24px]">person_add</span> تسجيل مشترك جديد
                </Link>
            </div>

            <!-- Analytical Filter Suite -->
            <div class="surface-card p-10 rounded-3xl mb-12 border border-outline-variant/5 shadow-sm bg-white">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 flex-row-reverse">
                    <div class="lg:col-span-2 space-y-4">
                        <label class="text-[11px] font-black text-primary uppercase tracking-widest mr-2 leading-none">محرك البحث المركزي (اسم، رمز، بريد)</label>
                        <div class="relative group">
                            <span class="material-symbols-outlined absolute right-5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary transition-colors text-[24px]">search</span>
                            <input v-model="search" type="text" placeholder="البحث في أصول المشتركين..." class="form-input-monolith h-16 pr-14 text-sm font-bold shadow-inner">
                        </div>
                    </div>
                    <div class="space-y-4">
                        <label class="text-[11px] font-black text-primary uppercase tracking-widest mr-2 leading-none">وضعية الخدمة الإدارية</label>
                        <select v-model="filters.status" @change="filterData" class="form-input-monolith h-16 text-sm font-black pr-12">
                            <option value="">كافة تصنيفات الخدمة</option>
                            <option value="active">حالة نشطة (Active)</option>
                            <option value="inactive">تم إيقاف الخدمة (Inactive)</option>
                        </select>
                    </div>
                    <div class="space-y-4">
                        <label class="text-[11px] font-black text-primary uppercase tracking-widest mr-2 leading-none">بروتوكول تزويد الخدمة</label>
                        <select v-model="filters.type" @change="filterData" class="form-input-monolith h-16 text-sm font-black pr-12">
                            <option value="">كافة البروتوكولات</option>
                            <option value="pppoe">الربط المباشر (PPPoE)</option>
                            <option value="hotspot">بث لاسلكي (Hotspot)</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Institutional Subscriber Ledger -->
            <div class="surface-card rounded-3xl overflow-hidden shadow-2xl border border-outline-variant/5 mb-10 bg-white">
                <div class="overflow-x-auto">
                    <table class="w-full text-right border-separate border-spacing-0">
                        <thead>
                            <tr class="bg-slate-950 text-white border-b border-white/5">
                                <th class="px-10 py-6 text-[11px] font-black uppercase tracking-[0.2em] leading-none text-white/40">هوية المشترك (Identity)</th>
                                <th class="px-10 py-6 text-[11px] font-black uppercase tracking-[0.2em] leading-none text-center text-white/40">بروتوكول الربط</th>
                                <th class="px-10 py-6 text-[11px] font-black uppercase tracking-[0.2em] leading-none text-center text-white/40">أفق الصلاحية</th>
                                <th class="px-10 py-6 text-[11px] font-black uppercase tracking-[0.2em] leading-none text-center text-white/40">وضعية الشبكة</th>
                                <th class="px-10 py-6 w-32"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant/5">
                            <tr v-for="client in clients.data" :key="client.id" class="group hover:bg-surface-container-low/50 transition-all duration-300">
                                <td class="px-10 py-8">
                                    <div class="flex items-center gap-6 justify-end">
                                        <div class="text-right">
                                            <h4 class="text-lg font-black text-primary leading-tight group-hover:translate-x-1 transition-transform font-headline">{{ client.username }}</h4>
                                            <div class="flex items-center gap-2 justify-end mt-2 opacity-50">
                                                <p class="text-[10px] font-black uppercase tracking-[0.15em] leading-none">{{ client.name || 'هوية غير معرفة' }}</p>
                                                <span class="material-symbols-outlined text-[14px]">id_card</span>
                                            </div>
                                        </div>
                                        <div class="w-14 h-14 rounded-2xl bg-surface-container-low border border-outline-variant/5 flex items-center justify-center text-primary shrink-0 group-hover:bg-primary group-hover:text-white shadow-inner transition-all">
                                            <span class="material-symbols-outlined text-[32px]" style="font-variation-settings: 'FILL' 1">account_circle</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-10 py-8 text-center text-right">
                                    <div class="inline-flex flex-col items-center gap-2">
                                        <div :class="[
                                            'px-5 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest border transition-all shadow-sm',
                                            client.type === 'pppoe' ? 'bg-indigo-500/10 text-indigo-600 border-indigo-500/20' : 'bg-purple-500/10 text-purple-600 border-purple-500/20'
                                        ]">
                                            {{ client.type === 'pppoe' ? 'نظام PPPoE' : 'نظام Hotspot' }}
                                        </div>
                                        <span class="text-[11px] font-black text-slate-500 tracking-tight leading-none uppercase">{{ client.package?.name || 'تعرفة يدوية' }}</span>
                                    </div>
                                </td>
                                <td class="px-10 py-8 text-center">
                                    <div class="flex flex-col items-center gap-2">
                                        <span class="text-[13px] font-headline font-black text-primary tracking-tight bg-surface-container-low px-4 py-1.5 rounded-xl border border-outline-variant/5">{{ formatDate(client.expires_at) }}</span>
                                        <p class="text-[9px] font-black text-slate-300 uppercase tracking-widest leading-none">تاريخ انقضاء العقد</p>
                                    </div>
                                </td>
                                <td class="px-10 py-8 text-center">
                                    <div v-if="client.status === 'active'" class="inline-flex items-center gap-3 px-6 py-2 bg-emerald-500/5 text-emerald-600 rounded-2xl text-[10px] font-black uppercase tracking-widest border border-emerald-500/10 shadow-sm transition-all group-hover:scale-105">
                                        <span class="relative flex h-2.5 w-2.5">
                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                                        </span>
                                        بث حي (Active)
                                    </div>
                                    <div v-else class="inline-flex items-center gap-3 px-6 py-2 bg-rose-500/5 text-rose-500 rounded-2xl text-[10px] font-black uppercase tracking-widest border border-rose-500/10 transition-all opacity-60">
                                        <span class="w-2.5 h-2.5 bg-rose-500 rounded-full shadow-inner"></span>
                                        معلق (Inactive)
                                    </div>
                                </td>
                                <td class="px-10 py-8 text-left">
                                    <div class="flex items-center justify-end gap-3 opacity-0 group-hover:opacity-100 transition-all">
                                         <Link 
                                            :href="route('crm.clients.show', client.id)"
                                            class="px-6 py-2.5 bg-white shadow-xl border border-outline-variant/10 rounded-xl text-[10px] font-black uppercase tracking-widest text-slate-500 hover:text-primary transition-all flex items-center gap-3 hover:scale-110 active:scale-90"
                                         >
                                            <span class="material-symbols-outlined text-[20px]">troubleshoot</span> فحص المنظومة
                                         </Link>
                                         <Link 
                                            :href="route('crm.clients.edit', client.id)"
                                            class="w-12 h-12 bg-white shadow-xl border border-outline-variant/10 rounded-xl flex items-center justify-center text-slate-400 hover:text-amber-600 hover:scale-110 active:scale-90 transition-all"
                                         >
                                            <span class="material-symbols-outlined text-[24px]">design_services</span>
                                         </Link>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Empty State Protocol -->
                <div v-if="clients.data.length === 0" class="py-48 flex flex-col items-center gap-10 group/empty">
                    <div class="w-32 h-32 rounded-[2.5rem] bg-surface-container-low flex items-center justify-center text-slate-200 border border-outline-variant/5 shadow-2xl group-hover/empty:scale-110 transition-all duration-1000">
                        <span class="material-symbols-outlined text-[64px]" style="font-variation-settings: 'wght' 100">person_search</span>
                    </div>
                    <div class="text-center">
                        <h3 class="text-2xl font-black text-primary mb-3">قاعدة بيانات المشتركين فارغة</h3>
                        <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.3em] max-w-sm leading-relaxed">لم يتم العثور على أي سجلات مطابقة ضمن نطاق البحث الإداري المحدد.</p>
                    </div>
                </div>

                <!-- Precision Pagination (Command Center Look) -->
                <div v-if="clients.links && clients.links.length > 3" class="px-10 py-8 bg-slate-950 border-t border-white/5 flex justify-between items-center flex-row-reverse">
                    <div class="text-[10px] font-black text-white/30 uppercase tracking-[0.3em] font-headline">Subscriber Registry Discovery Protocol</div>
                    <nav class="flex gap-4">
                        <Link 
                            v-for="link in clients.links" 
                            :key="link.label"
                            :href="link.url || '#'"
                            class="h-12 flex items-center justify-center rounded-xl text-[12px] font-headline font-black uppercase tracking-widest transition-all border px-6"
                            :class="link.active ? 'bg-primary text-white border-primary shadow-2xl shadow-primary/20 scale-110' : 'bg-white/5 text-white/40 border-white/10 hover:text-white hover:bg-white/10 hover:border-white/30'"
                            v-html="link.label"
                        />
                    </nav>
                </div>
            </div>
        </div>
    </InstitutionalLayout>
</template>

<style scoped>
.font-headline {
    font-family: 'Manrope', sans-serif;
}
</style>
