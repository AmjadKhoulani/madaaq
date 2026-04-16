<script setup>
import { useForm, Head, Link } from '@inertiajs/vue3';
import InstitutionalLayout from '@/Layouts/InstitutionalLayout.vue';

const props = defineProps({
    invoice: Object,
    clients: Array
});

const form = useForm({
    amount: props.invoice.amount,
    due_date: props.invoice.due_date,
    status: props.invoice.status,
});

const submit = () => {
    form.put(route('accounting.invoices.update', props.invoice.id));
};

</script>

<template>
    <InstitutionalLayout :title="'تعديل الوثيقة ' + invoice.invoice_number">
        <Head :title="'الحوكمة المالية: ' + invoice.invoice_number" />

        <div class="max-w-4xl mx-auto pb-24 text-right px-4" dir="rtl">
            <!-- Navigation Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-8 mb-12 flex-row-reverse">
                <div class="flex items-center gap-6 justify-end">
                    <div class="text-right">
                        <h1 class="text-3xl font-black text-primary tracking-tight mb-2">تعديل القيد المالي</h1>
                        <p class="text-slate-500 font-bold text-sm uppercase tracking-wider">تحديث معايير الالتزام المالي للوثيقة: {{ invoice.invoice_number }}</p>
                    </div>
                </div>
                <Link 
                    :href="route('accounting.invoices.index')" 
                    class="w-12 h-12 bg-white border border-outline-variant/10 rounded-xl flex items-center justify-center text-slate-400 hover:text-primary transition-all shadow-sm group"
                >
                    <span class="material-symbols-outlined text-[24px] group-hover:translate-x-1 transition-transform">chevron_right</span>
                </Link>
            </div>

            <form @submit.prevent="submit" class="space-y-10">
                <!-- 1. Subscriber Context -->
                <div class="surface-card p-10 bg-slate-900 text-white relative overflow-hidden rounded-xl shadow-2xl">
                    <div class="absolute -top-10 -right-10 w-64 h-64 bg-white/5 rounded-full blur-3xl"></div>
                    <div class="relative z-10 flex items-center gap-8 flex-row-reverse">
                        <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center border border-white/10 shadow-inner">
                            <span class="material-symbols-outlined text-white text-[32px]">person</span>
                        </div>
                        <div class="text-right">
                             <h4 class="text-xl font-black text-white uppercase tracking-tight">{{ invoice.client?.name || 'مشترك مجهول' }}</h4>
                             <p class="text-[10px] font-black text-white/40 uppercase tracking-widest mt-2">
                                بروتوكول المشترك المعرف • {{ invoice.client?.username }}
                             </p>
                        </div>
                    </div>
                </div>

                <!-- 2. Fiscal Parameters -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Value Extraction -->
                    <div class="surface-card p-10 space-y-10 rounded-xl border border-outline-variant/5 shadow-sm">
                         <div class="flex items-center gap-4 justify-end">
                            <h3 class="text-sm font-black text-primary uppercase tracking-widest">تحديد القيمة المالية</h3>
                            <div class="w-1.5 h-6 bg-emerald-600 rounded-full"></div>
                        </div>

                        <div class="space-y-8">
                            <div class="space-y-4">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mr-2">القيمة الملتزم بها (المبلغ)</label>
                                <div class="relative">
                                    <input v-model="form.amount" type="number" step="0.01" class="form-input-monolith h-16 pr-14 font-black text-lg" required>
                                    <span class="material-symbols-outlined absolute right-5 top-1/2 -translate-y-1/2 text-emerald-600 text-[24px]">payments</span>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mr-2">الأفق الزمني (تاريخ الاستحقاق)</label>
                                <div class="relative">
                                    <input v-model="form.due_date" type="date" class="form-input-monolith h-16 pr-14 font-black" required>
                                    <span class="material-symbols-outlined absolute right-5 top-1/2 -translate-y-1/2 text-slate-400 text-[24px]">calendar_today</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Settlement State -->
                    <div class="surface-card p-10 space-y-10 rounded-xl border border-outline-variant/5 shadow-sm">
                         <div class="flex items-center gap-4 justify-end">
                            <h3 class="text-sm font-black text-primary uppercase tracking-widest">تجاوز حالة التسوية</h3>
                            <div class="w-1.5 h-6 bg-amber-500 rounded-full"></div>
                        </div>

                        <div class="space-y-8">
                            <div class="space-y-4">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mr-2">بروتوكول التسوية الحالي</label>
                                <div class="grid grid-cols-2 gap-4">
                                    <button 
                                        type="button" 
                                        @click="form.status = 'unpaid'"
                                        class="p-6 rounded-2xl border-2 transition-all flex flex-col items-center gap-3"
                                        :class="form.status === 'unpaid' ? 'bg-amber-50 text-amber-700 border-amber-500 shadow-lg' : 'bg-white border-outline-variant/10 text-slate-400 hover:bg-slate-50'"
                                    >
                                        <span class="material-symbols-outlined text-[28px]" :class="form.status === 'unpaid' ? 'text-amber-500' : 'text-slate-300'">pending_actions</span>
                                        <span class="text-[9px] font-black uppercase tracking-widest">غير محصل (Unpaid)</span>
                                    </button>
                                    <button 
                                        type="button" 
                                        @click="form.status = 'paid'"
                                        class="p-6 rounded-2xl border-2 transition-all flex flex-col items-center gap-3"
                                        :class="form.status === 'paid' ? 'bg-emerald-50 text-emerald-700 border-emerald-500 shadow-lg' : 'bg-white border-outline-variant/10 text-slate-400 hover:bg-slate-50'"
                                    >
                                        <span class="material-symbols-outlined text-[28px]" :class="form.status === 'paid' ? 'text-emerald-500' : 'text-slate-300'">verified</span>
                                        <span class="text-[9px] font-black uppercase tracking-widest">تم التحصيل (Paid)</span>
                                    </button>
                                </div>
                            </div>

                            <div class="p-8 bg-slate-900 text-white rounded-2xl relative overflow-hidden group shadow-xl">
                                <div class="absolute -top-10 -right-10 w-40 h-40 bg-white/5 rounded-full blur-3xl transition-all group-hover/card:scale-125"></div>
                                <div class="relative z-10 flex items-center gap-6 justify-end">
                                    <div class="text-right">
                                         <p class="text-[9px] font-black text-white/40 uppercase tracking-widest mb-1.5 leading-none">المعرف المالي</p>
                                         <p class="text-xl font-mono font-black tracking-widest text-secondary">{{ invoice.invoice_number }}</p>
                                    </div>
                                    <span class="material-symbols-outlined text-white/20 text-[32px] rotate-12">receipt_long</span>
                                </div>
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
                                    تحديث المعايير المالية في السجل العام وتاريخ المشترك.
                                 </p>
                            </div>
                            <div class="w-20 h-20 bg-white/5 rounded-2xl flex items-center justify-center border border-white/10 shadow-inner group-hover/final:scale-110 transition-transform">
                                <span class="material-symbols-outlined text-emerald-400 text-[32px]" style="font-variation-settings: 'FILL' 1">shield</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-6 relative z-10 flex-row-reverse">
                            <Link 
                                :href="route('accounting.invoices.index')" 
                                class="px-10 py-5 bg-white/5 text-slate-400 font-black text-[11px] uppercase tracking-widest rounded-lg hover:bg-white/10 transition-all active:scale-95"
                            >
                                تجاهل التعديلات
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

