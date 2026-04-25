<script setup>
import { ref, Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import { useForm } from 'lucide-vue-next';;
import InstitutionalLayout from '@/Layouts/InstitutionalLayout.vue';

const props = defineProps({
    clients: Array,
    nextInvoiceNumber: String
});

const form = useForm({
    client_id: '',
    amount: '',
    due_date: new Date(Date.now() + 30 * 24 * 60 * 60 * 1000).toISOString().split('T')[0],
    description: '',
    status: 'unpaid',
});

const selectedClient = computed(() => {
    return props.clients.find(c => c.id === form.client_id);
});

const submit = () => {
    form.post(route('accounting.invoices.store'), {
        preserveScroll: true,
    });
};
</script>

<template>
    <InstitutionalLayout title="إصدار فاتورة جديدة">
        <Head title="سك وثيقة مالية جديدة" />

        <div class="max-w-5xl mx-auto pb-24 text-right">
            <!-- Strategic Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-8 mb-12">
                <div class="flex items-center gap-6">
                    <Link 
                        :href="route('accounting.invoices.index')" 
                        class="w-12 h-12 bg-white shadow-sm border border-outline-variant/10 rounded-lg flex items-center justify-center text-slate-400 hover:text-primary transition-all group"
                    >
                        <span class="material-symbols-outlined text-[24px] group-hover:translate-x-1 transition-transform">arrow_forward</span>
                    </Link>
                    <div>
                        <h1 class="text-3xl font-black text-primary tracking-tight mb-2">سك وثيقة مالية (Ledger Artifact)</h1>
                        <p class="text-[12px] font-bold text-slate-400 uppercase tracking-widest leading-none">بدء بروتوكول الالتزام المالي الجديد</p>
                    </div>
                </div>
            </div>

            <form @submit.prevent="submit" class="space-y-8">
                <!-- 1. Subscriber Identification Matrix -->
                <div class="surface-card p-10 rounded-lg">
                    <h3 class="text-sm font-black text-primary uppercase tracking-[0.2em] mb-10 flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary text-[20px]">person_search</span>
                        اكتشاف وتحديد المشترك المستهدف
                    </h3>

                    <div class="space-y-8">
                        <div class="space-y-4">
                            <label class="text-[11px] font-black text-primary uppercase tracking-widest block mr-2">اختيار المشترك من سجلات المركز</label>
                            <div class="relative group">
                                <span class="material-symbols-outlined absolute right-6 top-1/2 -translate-y-1/2 text-primary">group</span>
                                <select v-model="form.client_id" class="form-select-monolith h-16 pr-16 font-black text-lg text-primary tracking-tight" required>
                                    <option value="">ابحث في قاعدة بيانات المشتركين...</option>
                                    <option v-for="c in clients" :key="c.id" :value="c.id">{{ c.name }} ({{ c.username }})</option>
                                </select>
                            </div>
                        </div>

                        <!-- Subscriber Validation Pulse -->
                        <div v-if="selectedClient" class="p-8 bg-surface-container-low border border-outline-variant/10 rounded-lg flex items-center justify-between animate-in slide-in-from-top duration-500">
                             <div class="flex items-center gap-6">
                                 <div class="w-14 h-14 rounded-lg bg-primary text-white flex items-center justify-center shadow-lg shadow-primary/20">
                                     <span class="material-symbols-outlined text-[32px]">person</span>
                                 </div>
                                 <div class="space-y-1 text-right">
                                     <p class="text-base font-black text-primary tracking-tight uppercase">{{ selectedClient.name }}</p>
                                     <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">البروتوكول المعين: {{ selectedClient.type === 'pppoe' ? 'Broadband (PPPoE)' : 'Hotspot' }}</p>
                                 </div>
                             </div>
                             <div class="flex items-center gap-3 bg-secondary-container/10 px-4 py-2 rounded-full border border-secondary-container/20">
                                 <div class="w-2 h-2 bg-secondary rounded-full animate-pulse shadow-[0_0_8px_rgba(var(--secondary-rgb),0.5)]"></div>
                                 <span class="text-[10px] font-black text-secondary uppercase tracking-widest">المشترك موثق</span>
                             </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Fiscal Parameters & Status Layer -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Commercial Extraction Card -->
                    <div class="surface-card p-10 space-y-10 rounded-lg">
                         <h3 class="text-sm font-black text-primary uppercase tracking-[0.2em] flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary text-[20px]">payments</span>
                            الاستخراج المالي (Commercial)
                        </h3>

                        <div class="space-y-8 text-right">
                            <div class="space-y-4">
                                <label class="text-[11px] font-black text-primary uppercase tracking-widest block mr-2">القيمة الملتزم بها (Amount)</label>
                                <div class="relative">
                                    <input v-model="form.amount" type="number" step="0.01" class="form-input-monolith h-16 pr-16 font-headline font-black text-2xl text-secondary" placeholder="0.00" required />
                                    <span class="material-symbols-outlined absolute right-6 top-1/2 -translate-y-1/2 text-secondary text-[28px]">sell</span>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <label class="text-[11px] font-black text-primary uppercase tracking-widest block mr-2">الأفق الزمني للتسوية (Due Date)</label>
                                <div class="relative">
                                    <input v-model="form.due_date" type="date" class="form-input-monolith h-16 pr-16 font-headline font-bold text-lg text-primary" required />
                                    <span class="material-symbols-outlined absolute right-6 top-1/2 -translate-y-1/2 text-slate-400 text-[28px]">calendar_today</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Status Protocol Control -->
                    <div class="surface-card p-10 space-y-10 rounded-lg">
                         <h3 class="text-sm font-black text-primary uppercase tracking-[0.2em] flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary text-[20px]">rule</span>
                            بروتوكول حالة التسوية
                        </h3>

                        <div class="space-y-8">
                            <div class="space-y-4">
                                <label class="text-[11px] font-black text-primary uppercase tracking-widest block mr-2">حالة التسوية الابتدائية</label>
                                <div class="grid grid-cols-2 gap-4">
                                    <button 
                                        type="button" 
                                        @click="form.status = 'unpaid'"
                                        class="p-6 rounded-lg border-2 transition-all flex flex-col items-center gap-4 group"
                                        :class="form.status === 'unpaid' ? 'bg-amber-50 text-amber-700 border-amber-500 shadow-xl' : 'bg-white border-outline-variant/10 text-slate-400 hover:bg-slate-50'"
                                    >
                                        <span class="material-symbols-outlined text-[32px]" :style="form.status === 'unpaid' ? { 'font-variation-settings': '\'FILL\' 1' } : {}">pending_actions</span>
                                        <span class="text-[10px] font-black uppercase tracking-widest">قيد الانتظار</span>
                                    </button>
                                    <button 
                                        type="button" 
                                        @click="form.status = 'paid'"
                                        class="p-6 rounded-lg border-2 transition-all flex flex-col items-center gap-4 group"
                                        :class="form.status === 'paid' ? 'bg-secondary-container/10 text-secondary-container border-secondary shadow-xl' : 'bg-white border-outline-variant/10 text-slate-400 hover:bg-slate-50'"
                                    >
                                        <span class="material-symbols-outlined text-[32px]" :style="form.status === 'paid' ? { 'font-variation-settings': '\'FILL\' 1' } : {}">check_circle</span>
                                        <span class="text-[10px] font-black uppercase tracking-widest">تمت التسوية</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Artifact Identifier Card -->
                            <div class="p-10 bg-primary text-white rounded-lg relative overflow-hidden group shadow-xl shadow-primary/20">
                                <div class="absolute -top-10 -right-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
                                <div class="relative z-10 flex items-center gap-8 justify-end">
                                    <div class="text-right">
                                         <p class="text-[10px] font-black text-white/50 uppercase tracking-[0.2em] mb-2">هوية الوثيقة (Identity)</p>
                                         <p class="text-2xl font-headline font-black tracking-widest">{{ nextInvoiceNumber }}</p>
                                    </div>
                                    <span class="material-symbols-outlined text-white/30 text-[48px] rotate-12">description</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. Final Strategic Commitment -->
                <div class="surface-card p-12 bg-primary text-white rounded-lg shadow-2xl shadow-primary/30 relative overflow-hidden flex flex-col md:flex-row items-center justify-between gap-10">
                    <div class="absolute -bottom-20 -right-20 w-64 h-64 bg-white/5 rounded-full blur-3xl"></div>
                    <div class="relative z-10 flex items-center gap-8 text-right w-full">
                        <div class="w-20 h-20 bg-white/10 rounded-lg flex items-center justify-center border border-white/20 shrink-0">
                            <span class="material-symbols-outlined text-white/80 text-[40px]" style="font-variation-settings: 'FILL' 1">gavel</span>
                        </div>
                        <div>
                             <h4 class="text-xl font-black uppercase tracking-tight">الالتزام بالقيد المالي</h4>
                             <p class="text-[10px] font-black text-white/40 uppercase tracking-[0.3em] mt-2">
                                ترحيل الوثيقة المالية إلى السجل العالمي وتاريخ المشترك.
                             </p>
                        </div>
                    </div>
                    <div class="relative z-10 flex items-center gap-6 w-full md:w-auto shrink-0">
                        <Link 
                            :href="route('accounting.invoices.index')" 
                            class="px-8 py-5 bg-white/10 text-white font-black text-[11px] uppercase tracking-widest rounded-lg hover:bg-white/20 transition-all active:scale-95 text-center"
                        >
                            إجهاض السك
                        </Link>
                        <button 
                            type="submit" 
                            class="px-14 py-5 bg-white text-primary font-black text-[12.5px] uppercase tracking-[0.2em] rounded-lg shadow-2xl hover:bg-slate-50 transition-all hover:scale-105 active:scale-95 disabled:opacity-50 flex items-center justify-center gap-4 w-full"
                            :disabled="form.processing || !form.client_id || !form.amount"
                        >
                            <span class="material-symbols-outlined text-[20px] fill-current" style="font-variation-settings: 'FILL' 1">cloud_upload</span>
                            سك الوثيقة المالية
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </InstitutionalLayout>
</template>


