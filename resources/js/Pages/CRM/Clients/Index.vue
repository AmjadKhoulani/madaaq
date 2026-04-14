<script setup>
import { ref, watch } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
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
    if (!date) return 'مفتوح';
    return new Date(date).toLocaleDateString('ar-EG', { year: 'numeric', month: 'short', day: 'numeric' });
};
</script>

<template>
    <InstitutionalLayout title="إدارة المشتركين">
        <!-- Header Monolith -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
            <div>
                <h1 class="text-3xl font-black text-primary tracking-tight mb-2">إدارة المشتركين</h1>
                <p class="text-slate-500 font-bold text-sm">مراقبة وإدارة قاعدة بيانات عملاء الشبكة.</p>
            </div>
            <Link 
                :href="route('crm.clients.create')" 
                class="inline-flex items-center gap-3 px-8 py-3.5 bg-primary text-white rounded-lg font-bold shadow-xl shadow-primary/20 hover:bg-primary-container transition-all active:scale-95"
            >
                <span class="material-symbols-outlined text-[20px]">person_add</span>
                <span class="text-sm">إضافة مشترك جديد</span>
            </Link>
        </div>

        <!-- Analytical Filters Block -->
        <div class="surface-card p-6 mb-8 flex flex-col md:flex-row items-center gap-6 rounded-lg">
            <div class="flex-1 w-full relative">
                <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-[20px]">search</span>
                <input 
                    v-model="search"
                    type="text" 
                    placeholder="ابحث عن مشترك بالاسم، البريد، أو الهاتف..." 
                    class="w-full h-12 bg-surface-container-low border-none rounded-lg pr-12 pl-4 text-sm focus:ring-2 focus:ring-primary transition-all placeholder:text-slate-400"
                >
            </div>
            
            <div class="flex gap-4 w-full md:w-auto">
                <div class="relative min-w-[160px]">
                    <select 
                        v-model="filters.status"
                        @change="filterData"
                        class="w-full h-12 px-4 bg-surface-container-low rounded-lg text-xs font-bold border-none appearance-none focus:ring-2 focus:ring-primary text-slate-700"
                    >
                        <option value="">كافة الحالات</option>
                        <option value="active">نشط</option>
                        <option value="inactive">غير نشط</option>
                    </select>
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-[18px]">expand_more</span>
                </div>
                
                <div class="relative min-w-[160px]">
                    <select 
                        v-model="filters.type"
                        @change="filterData"
                        class="w-full h-12 px-4 bg-surface-container-low rounded-lg text-xs font-bold border-none appearance-none focus:ring-2 focus:ring-primary text-slate-700"
                    >
                        <option value="">كافة الأنواع</option>
                        <option value="pppoe">الاشتراك المباشر (PPPoE)</option>
                        <option value="hotspot">بث لاسلكي (Hotspot)</option>
                    </select>
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-[18px]">expand_more</span>
                </div>
            </div>
        </div>

        <!-- Ledger Table Layer -->
        <div class="surface-card rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-right border-collapse">
                    <thead>
                        <tr class="bg-surface-container/30 border-b border-outline-variant/10">
                            <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-widest leading-none">بيانات المشترك</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-widest leading-none">نوع البروتوكول</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-widest leading-none">الباقة الحالية</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-widest leading-none text-center">حالة الخدمة</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-widest leading-none text-center">تاريخ الانتهاء</th>
                            <th class="px-8 py-5"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/5">
                        <tr v-for="client in clients.data" :key="client.id" class="hover:bg-surface-container-low transition-all group">
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-5">
                                    <div class="w-11 h-11 rounded-lg bg-primary-fixed/30 flex items-center justify-center text-primary font-black text-xs shadow-inner uppercase tracking-tighter">
                                        {{ client.username.substring(0, 2) }}
                                    </div>
                                    <div class="min-w-0">
                                        <h4 class="text-[14px] font-black text-primary truncate max-w-[200px] leading-tight">{{ client.username }}</h4>
                                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">{{ client.name || 'بدون اسم' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div :class="[
                                    'inline-flex items-center px-3 py-1 rounded text-[10px] font-black uppercase tracking-widest border',
                                    client.type === 'pppoe' ? 'bg-indigo-50 text-indigo-700 border-indigo-100' : 'bg-purple-50 text-purple-700 border-purple-100'
                                ]">
                                    {{ client.type }}
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <span class="text-[13px] font-black text-primary">{{ client.package?.name || 'مخصص' }}</span>
                            </td>
                            <td class="px-8 py-6 text-center">
                                <div v-if="client.status === 'active'" class="inline-flex items-center px-4 py-1.5 bg-secondary-container/20 text-on-secondary-container rounded text-[10px] font-black uppercase tracking-widest border border-secondary-container/30">
                                    <span class="w-2 h-2 bg-secondary rounded-full ml-2 animate-pulse shadow-sm shadow-secondary/50"></span>
                                    متصل حالياً
                                </div>
                                <div v-else class="inline-flex items-center px-4 py-1.5 bg-error-container/10 text-error rounded text-[10px] font-black uppercase tracking-widest border border-error-container/20">
                                    <span class="w-2 h-2 bg-error rounded-full ml-2"></span>
                                    متوقف
                                </div>
                            </td>
                            <td class="px-8 py-6 text-center">
                                <span class="text-[12px] font-headline font-extrabold text-primary tracking-tight">
                                    {{ formatDate(client.expires_at) }}
                                </span>
                            </td>
                            <td class="px-8 py-6 text-left">
                                <Link 
                                    :href="route('crm.clients.show', client.id)"
                                    class="w-10 h-10 bg-white shadow-sm border border-outline-variant/10 rounded-lg flex items-center justify-center text-slate-400 hover:text-primary hover:border-primary transition-all group-hover:scale-110"
                                >
                                    <span class="material-symbols-outlined text-[20px]">visibility</span>
                                </Link>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Monolith Empty State -->
            <div v-if="clients.data.length === 0" class="py-24 flex flex-col items-center gap-6">
                <div class="w-20 h-20 rounded-full bg-surface-container-low flex items-center justify-center text-slate-200">
                    <span class="material-symbols-outlined text-[40px]">person_search</span>
                </div>
                <div class="text-center">
                    <h3 class="text-lg font-black text-primary">لم يتم العثور على مشتركين</h3>
                    <p class="text-[11px] text-slate-400 font-bold uppercase tracking-[0.2em] mt-2">جرب تعديل معايير البحث أو الفرز</p>
                </div>
            </div>

            <!-- Precision Pagination -->
            <div v-if="clients.links.length > 3" class="px-8 py-6 border-t border-outline-variant/10 flex items-center justify-center gap-2 bg-surface-container/10">
                <Link 
                    v-for="(link, k) in clients.links" 
                    :key="k"
                    :href="link.url || '#'"
                    v-html="link.label"
                    class="h-10 px-4 flex items-center justify-center rounded-lg text-[11px] font-black transition-all font-headline"
                    :class="[
                        link.active ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'hover:bg-primary-fixed/20 text-slate-500',
                        !link.url ? 'opacity-30 cursor-not-allowed' : ''
                    ]"
                />
            </div>
        </div>
    </InstitutionalLayout>
</template>

<style scoped>
/* Data density refinements */
table {
    border-spacing: 0;
}
</style>
