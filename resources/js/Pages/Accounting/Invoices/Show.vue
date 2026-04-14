<script setup>
import { Head, Link } from '@inertiajs/vue3';
import InstitutionalLayout from '@/Layouts/InstitutionalLayout.vue';

const props = defineProps({
    invoice: Object
});

const printInvoice = () => {
    window.print();
};

const getStatusDetails = (status) => {
    switch (status) {
        case 'paid': return { label: 'مكتملة الصرف', color: 'text-secondary', bg: 'bg-secondary-container/20 border-secondary-container/30', icon: 'check_circle' };
        case 'unpaid': return { label: 'بانتظار التحصيل', color: 'text-amber-700', bg: 'bg-amber-50 border-amber-100', icon: 'schedule' };
        default: return { label: status, color: 'text-slate-500', bg: 'bg-slate-50 border-slate-100', icon: 'description' };
    }
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('ar-EG', { year: 'numeric', month: 'short', day: 'numeric' });
};
</script>

<template>
    <InstitutionalLayout title="سجل مالي مؤسساتي">
        <Head :title="'فاتورة رقم: ' + invoice.invoice_number" />

        <div class="max-w-4xl mx-auto pb-24">
            <!-- Institutional Header (Hidden on Print) -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-8 mb-12 print:hidden">
                <div class="flex items-center gap-6">
                    <Link 
                        :href="route('accounting.invoices.index')" 
                        class="w-12 h-12 bg-white shadow-sm border border-outline-variant/10 rounded-lg flex items-center justify-center text-slate-400 hover:text-primary transition-all group"
                    >
                        <span class="material-symbols-outlined text-[24px] group-hover:translate-x-1 transition-transform">arrow_forward</span>
                    </Link>
                    <div>
                        <h1 class="text-3xl font-black text-primary tracking-tight mb-1">بيانات السجل المالي</h1>
                        <p class="text-slate-500 font-bold text-sm uppercase tracking-wider">سجل بروتوكول رقم {{ invoice.invoice_number }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                     <button 
                        @click="printInvoice"
                        class="px-8 py-4 bg-primary text-white rounded-lg font-black text-[11px] uppercase tracking-[0.2em] shadow-xl shadow-primary/20 hover:scale-105 active:scale-95 transition-all flex items-center gap-3"
                     >
                        <span class="material-symbols-outlined text-[20px]">print</span>
                        طباعة السجل الفني
                     </button>
                </div>
            </div>

            <!-- Global Fiscal Artifact -->
            <div id="invoice-artifact" class="surface-card bg-white p-12 md:p-20 shadow-2xl relative overflow-hidden rounded-lg print:shadow-none print:p-0 print:border-none border-outline-variant/10 rtl">
                <!-- Authority Watermark -->
                <div class="absolute -top-16 -right-16 w-80 h-80 opacity-[0.03] rotate-12 pointer-events-none">
                    <span class="material-symbols-outlined text-[320px] text-primary">verified_user</span>
                </div>

                <!-- 1. Institutional Identity -->
                <div class="flex flex-col md:flex-row justify-between gap-12 mb-20 border-b border-outline-variant/10 pb-12">
                    <div class="space-y-6 text-right">
                        <div class="flex items-center gap-5">
                            <div class="w-16 h-16 bg-primary rounded-lg flex items-center justify-center text-white text-3xl font-black shadow-2xl shadow-primary/30">M</div>
                            <div class="text-right">
                                <h2 class="text-3xl font-black tracking-tight text-primary uppercase leading-none mb-1">MadaaQ ISP</h2>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">بروتوكول حوكمة البنية التحتية</p>
                            </div>
                        </div>
                        <div class="space-y-1.5 text-slate-500 text-[12px] font-bold leading-relaxed">
                            <p>المقر الرئيسي للعمليات الرقمية</p>
                            <p>ريف دمشق، الجمهورية العربية السورية</p>
                            <p class="font-headline font-black">contact@madaaq.com</p>
                        </div>
                    </div>

                    <div class="text-right md:text-left space-y-6">
                         <div 
                            class="inline-flex items-center gap-3 px-6 py-2.5 rounded border text-[10px] font-black uppercase tracking-widest"
                            :class="getStatusDetails(invoice.status).bg + ' ' + getStatusDetails(invoice.status).color"
                        >
                            <span class="material-symbols-outlined text-[18px]">{{ getStatusDetails(invoice.status).icon }}</span>
                            {{ getStatusDetails(invoice.status).label }}
                        </div>
                        <div class="space-y-2">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">معرف السجل (ID)</p>
                            <p class="text-3xl font-headline font-black tracking-widest text-primary uppercase">{{ invoice.invoice_number }}</p>
                        </div>
                    </div>
                </div>

                <!-- 2. Legal Multi-Matrix (Subscriber & Date) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-16 mb-20">
                    <div class="space-y-6 text-right">
                        <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary text-[18px]">person</span>
                            بيانات الطرف المتعاقد (المشترك)
                        </p>
                        <div class="space-y-3 p-6 bg-surface-container-low rounded-lg border border-outline-variant/5">
                            <p class="text-xl font-black text-primary tracking-tight leading-none">{{ invoice.client?.name || 'مشترك غير معرف' }}</p>
                            <p class="text-[13px] font-headline font-extrabold text-slate-400 tracking-wider">{{ invoice.client?.username }}</p>
                            <p class="text-[12px] font-headline font-extrabold text-primary pt-2 border-t border-outline-variant/10">{{ invoice.client?.phone || 'لا يوجد رقم مسجل' }}</p>
                        </div>
                    </div>
                    <div class="space-y-6 text-right md:text-left">
                        <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest flex items-center md:justify-end gap-3">
                             النطاق الزمني للسجل
                             <span class="material-symbols-outlined text-primary text-[18px]">event_note</span>
                        </p>
                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">تاريخ الإصدار</p>
                                <p class="text-[14px] font-headline font-black text-primary">{{ formatDate(invoice.created_at) }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">تاريخ الاستحقاق</p>
                                <p class="text-[14px] font-headline font-black text-error italic">{{ formatDate(invoice.due_date) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. Technical Service Extraction -->
                <div class="space-y-8 mb-20">
                    <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest border-r-4 border-primary pr-4">تفاصيل الخدمات والمواصفات المخصصّة</p>
                    <div class="overflow-x-auto">
                        <table class="w-full text-right">
                            <thead class="bg-surface-container/30">
                                <tr>
                                    <th class="p-6 text-[11px] font-black text-slate-500 uppercase tracking-widest">توصيف الخدمة (Technical Spec)</th>
                                    <th class="p-6 text-[11px] font-black text-slate-500 uppercase tracking-widest text-center">الوحدة</th>
                                    <th class="p-6 text-[11px] font-black text-slate-500 uppercase tracking-widest text-left">القيمة المستخلصة</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-outline-variant/10">
                                <tr v-if="invoice.client?.package" class="group">
                                    <td class="py-10 px-6">
                                        <div class="flex items-center gap-5">
                                            <div class="w-14 h-14 bg-surface-container-highest rounded-lg flex items-center justify-center text-primary shadow-inner">
                                                <span class="material-symbols-outlined text-[28px]" style="font-variation-settings: 'FILL' 1">bolt</span>
                                            </div>
                                            <div class="text-right">
                                                <p class="font-black text-base text-primary tracking-tight leading-none mb-2">باقة {{ invoice.client.package.name }}</p>
                                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">نطاق التردد المحجوز: {{ invoice.client.package.speed_down }}M / {{ invoice.client.package.speed_up }}M</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-10 px-6 text-center font-black text-sm text-slate-400 font-headline uppercase">1 Cycle</td>
                                    <td class="py-10 px-6 text-left font-black font-headline text-2xl text-primary tracking-tight">{{ invoice.amount.toLocaleString() }} <span class="text-xs font-bold text-slate-400">ر.س</span></td>
                                </tr>
                                <tr v-else>
                                    <td class="py-10 px-6">
                                        <div class="flex items-center gap-5">
                                            <div class="w-14 h-14 bg-surface-container-highest rounded-lg flex items-center justify-center text-amber-600 shadow-inner">
                                                <span class="material-symbols-outlined text-[28px]" style="font-variation-settings: 'FILL' 1">history_edu</span>
                                            </div>
                                            <div class="text-right">
                                                <p class="font-black text-base text-primary tracking-tight leading-none mb-2">تخصيص يدوي للخدمة</p>
                                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic">التزام مخصص خارج الأطر القياسية</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-10 px-6 text-center font-black text-sm text-slate-400 font-headline uppercase">Manual</td>
                                    <td class="py-10 px-6 text-left font-black font-headline text-2xl text-primary tracking-tight">{{ invoice.amount.toLocaleString() }} <span class="text-xs font-bold text-slate-400">ر.س</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- 4. Fiscal Pulse Conclusion -->
                <div class="flex flex-col md:flex-row justify-between items-start gap-12 pt-12 border-t-2 border-primary/10">
                    <div class="max-w-sm space-y-4 text-right">
                        <p class="text-[11px] font-black text-primary uppercase tracking-widest">شروط الالتزام المؤسساتي</p>
                        <p class="text-[12px] font-bold text-slate-400 leading-relaxed">هذا السجل يؤكد تخصيص القدرات التقنية المطلوبة ضمن الإطار الزمني المحدد. كافة القيم تمثل التزاماً رسمياً مسجلاً في أنظمة حوكمة MadaaQ.</p>
                    </div>
                    <div class="w-full md:w-96 space-y-6">
                        <div class="flex items-center justify-between text-slate-400">
                            <span class="text-[11px] font-black uppercase tracking-widest font-headline">قيمة السجل الصافية</span>
                            <span class="font-black font-headline text-base text-primary">{{ invoice.amount.toLocaleString() }} <span class="text-[10px] mr-1">ر.س</span></span>
                        </div>
                        <div class="flex items-center justify-between text-slate-400">
                            <span class="text-[11px] font-black uppercase tracking-widest font-headline">ضرائب الحوكمة (0%)</span>
                            <span class="font-black font-headline text-base">0.00 <span class="text-[10px] mr-1">ر.س</span></span>
                        </div>
                        <div class="flex items-center justify-between pt-8 border-t-2 border-outline-variant/20">
                            <span class="text-[14px] font-black uppercase tracking-[0.2em] text-primary">إجمالي قيمة الاستحقاق</span>
                            <span class="text-4xl font-black font-headline tracking-tighter text-secondary">{{ invoice.amount.toLocaleString() }} <span class="text-sm font-bold opacity-50 mr-2">ر.س</span></span>
                        </div>
                    </div>
                </div>

                <!-- Print Protocol Footer -->
                <div class="hidden print:block mt-32 text-center">
                    <div class="w-24 h-1 bg-primary/10 mx-auto mb-6"></div>
                    <p class="text-[10px] font-black text-slate-300 uppercase tracking-[0.6em]">بروتوكول حوكمة المعاملات المالية • MadaaQ Official Record</p>
                </div>
            </div>
        </div>
    </InstitutionalLayout>
</template>

<style>
@media print {
    body {
        background: white !important;
    }
    .print\:hidden {
        display: none !important;
    }
    #invoice-artifact {
        padding: 0 !important;
        margin: 0 !important;
        border: none !important;
        box-shadow: none !important;
    }
}
</style>
