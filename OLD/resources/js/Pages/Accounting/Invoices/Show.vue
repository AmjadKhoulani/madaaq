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
        case 'paid': return { label: 'مكتملة الصرف (Paid)', color: 'text-emerald-500', bg: 'bg-emerald-500/10 border-emerald-500/20', icon: 'verified' };
        case 'unpaid': return { label: 'بانتظار التحصيل (Pending)', color: 'text-amber-600', bg: 'bg-amber-500/10 border-amber-500/20', icon: 'pending_actions' };
        default: return { label: status, color: 'text-slate-500', bg: 'bg-slate-500/10 border-slate-500/20', icon: 'description' };
    }
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('ar-EG', { year: 'numeric', month: 'short', day: 'numeric' });
};

</script>

<template>
    <InstitutionalLayout title="سجل مالي مفصل">
        <Head :title="'بيانات الفاتورة: ' + invoice.invoice_number" />

        <div class="max-w-5xl mx-auto pb-24 text-right px-4">
            <!-- Institutional Navigation (Hidden on Print) -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-8 mb-12 print:hidden flex-row-reverse">
                <div class="flex items-center gap-6 flex-row-reverse">
                    <div class="text-right">
                        <h1 class="text-3xl font-black text-primary tracking-tighter mb-2 uppercase">وثيقة الفاتورة الرسمية (Fiscal Record)</h1>
                        <div class="flex items-center gap-3 justify-end">
                            <span class="material-symbols-outlined text-[20px] text-secondary">verified_user</span>
                            <p class="text-slate-500 font-bold text-sm uppercase tracking-wider">سجل مالي معتمد - إدارة التحصيل المركزي</p>
                        </div>
                    </div>
                    <Link 
                        :href="route('accounting.invoices.index')" 
                        class="w-12 h-12 bg-white border border-outline-variant/10 rounded-lg flex items-center justify-center text-slate-400 hover:text-primary transition-all shadow-sm group"
                    >
                        <span class="material-symbols-outlined text-[24px] group-hover:translate-x-1 transition-transform">chevron_right</span>
                    </Link>
                </div>
                <div class="flex items-center gap-4">
                     <button 
                        @click="printInvoice"
                        class="px-10 py-5 bg-slate-900 text-white rounded-lg font-black text-[11px] uppercase tracking-[0.2em] shadow-2xl hover:bg-black transition-all flex items-center gap-3"
                     >
                        <span class="material-symbols-outlined text-[20px]">print</span>
                        استخراج السجل (PDF)
                     </button>
                </div>
            </div>

            <!-- Premium Fiscal Artifact -->
            <div id="invoice-artifact" class="surface-card bg-white p-12 md:p-24 rounded-xl shadow-2xl relative overflow-hidden print:p-0 print:shadow-none border border-outline-variant/5">
                <!-- Authority Surface Detail -->
                <div class="absolute top-0 right-0 w-[400px] h-[400px] bg-primary/5 rounded-full -translate-y-1/2 translate-x-1/2 blur-3xl opacity-30 pointer-events-none"></div>

                <!-- 1. Institutional Identity Header -->
                <div class="flex flex-col md:flex-row justify-between gap-12 mb-24 border-b-2 border-primary/10 pb-16 flex-row-reverse">
                    <div class="space-y-8 text-right">
                        <div class="flex items-center gap-6 justify-end">
                            <div class="text-right">
                                <h2 class="text-4xl font-black tracking-tighter text-primary leading-none mb-2">MADAAQ ISP</h2>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em]">المركز الإداري لإدارة الشبكات</p>
                            </div>
                            <div class="w-20 h-20 bg-primary rounded-xl flex items-center justify-center text-white text-4xl font-black shadow-2xl shadow-primary/30 group-hover:rotate-6 transition-transform">M</div>
                        </div>
                        <div class="space-y-2 text-slate-500 text-[13px] font-bold leading-relaxed">
                            <p>البنية التحتية والمقرات الرقمية</p>
                            <p>ريف دمشق، الجمهورية العربية السورية</p>
                            <p class="font-headline font-black text-primary border-t border-outline-variant/10 pt-2 mt-4">support@madaaq.com</p>
                        </div>
                    </div>

                    <div class="text-right md:text-left space-y-10">
                         <div 
                            class="inline-flex items-center gap-3 px-8 py-3 rounded-lg border text-[11px] font-black uppercase tracking-widest"
                            :class="getStatusDetails(invoice.status).bg + ' ' + getStatusDetails(invoice.status).color"
                        >
                            <span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1">{{ getStatusDetails(invoice.status).icon }}</span>
                            {{ getStatusDetails(invoice.status).label }}
                        </div>
                        <div class="space-y-4">
                            <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">بيانات جهة الإصدار (المزود)</h3>
                            <div class="space-y-4 text-right">
                                <h4 class="text-xl font-black text-primary leading-none">{{ invoice.tenant?.company_name || 'شبكة مدى كيو' }}</h4>
                                <div class="space-y-2 text-[11px] font-bold text-slate-500 uppercase tracking-wide">
                                    <p>مركز العمليات الرئيسي</p>
                                    <p>سجل الفواتير الضريبي الموحد</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Strategic Meta-Matrix (Counterparty & Lifecycle) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-20 mb-24">
                    <div class="space-y-8 text-right order-2 md:order-1">
                        <p class="text-[12px] font-black text-slate-400 uppercase tracking-widest flex items-center justify-end gap-4 border-r-4 border-secondary pr-4">
                             بيانات الطرف الأول (المشترك)
                             <span class="material-symbols-outlined text-secondary text-[22px]">contact_page</span>
                        </p>
                        <div class="space-y-4 p-10 bg-surface-container-low rounded-xl border border-outline-variant/10 shadow-inner">
                            <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">بيانات المستلم (العميل)</h3>
                            <div class="space-y-4 text-right">
                                <h4 class="text-xl font-black text-primary leading-none">{{ invoice.client?.name || invoice.client?.username }}</h4>
                                <div class="space-y-2 text-[11px] font-bold text-slate-500 uppercase tracking-wide">
                                    <p>معرف العميل الرقمي: <span class="text-primary font-headline">{{ invoice.client?.id }}</span></p>
                                    <p>عنوان الاتصال المسجل: {{ invoice.client?.phone || 'غير محدد' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="space-y-8 text-right order-1 md:order-2">
                        <p class="text-[12px] font-black text-slate-400 uppercase tracking-widest flex items-center justify-end gap-4 border-r-4 border-primary pr-4">
                             النطاق الزمني والتوقيت
                             <span class="material-symbols-outlined text-primary text-[22px]">history_toggle_off</span>
                        </p>
                        <div class="grid grid-cols-2 gap-10">
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2.5">تاريخ التوثيق</p>
                                <p class="text-[16px] font-headline font-black text-primary">{{ formatDate(invoice.created_at) }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2.5">تاريخ الاستحقاق</p>
                                <p class="text-[16px] font-headline font-black text-error italic shadow-sm shadow-error/10 bg-error/5 px-3 py-1 rounded inline-block">{{ formatDate(invoice.due_date) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. Technical Value Extraction Table -->
                <div class="space-y-12 mb-24">
                    <p class="text-[12px] font-black text-slate-400 uppercase tracking-[0.2em] border-r-4 border-primary pr-4">ملخص الرسوم والخدمات (Services Matrix)</p>
                    <div class="overflow-x-auto">
                        <table class="w-full text-right border-collapse">
                            <thead class="bg-surface-container-low/50">
                                <tr>
                                    <th class="pb-6 px-4 text-right">وصف الخدمة / الباقة</th>
                                    <th class="pb-6 px-4 text-center">القيمة (ر.س)</th>
                                    <th class="pb-6 px-4 text-left">الإجمالي</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-outline-variant/10 border-b border-outline-variant/10">
                                <tr v-if="invoice.client?.package" class="group">
                                    <td class="py-12 px-8">
                                        <div class="flex items-center gap-6 flex-row-reverse">
                                            <div class="w-16 h-16 bg-primary/5 rounded-xl border border-primary/10 flex items-center justify-center text-primary shadow-inner">
                                                <span class="material-symbols-outlined text-[32px]" style="font-variation-settings: 'FILL' 1">speed</span>
                                            </div>
                                            <div class="text-right">
                                                <p class="font-black text-xl text-primary tracking-tight leading-none mb-3">حزمة خدمة: {{ invoice.client.package.name }}</p>
                                                <div class="flex items-center gap-3 justify-end text-slate-400">
                                                    <span class="text-[10px] font-black uppercase tracking-widest font-headline">Down: {{ invoice.client.package.speed_down }}M</span>
                                                    <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                                                    <span class="text-[10px] font-black uppercase tracking-widest font-headline">Up: {{ invoice.client.package.speed_up }}M</span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-12 px-8 text-center font-black text-xs text-slate-400 font-headline uppercase tracking-[0.2em]">1 Cycle Protocol</td>
                                    <td class="py-12 px-8 text-left font-black font-headline text-3xl text-primary tracking-tighter">{{ invoice.amount.toLocaleString() }} <span class="text-[10px] font-bold text-slate-300 mr-2 uppercase">ر.س</span></td>
                                </tr>
                                <tr v-else>
                                    <td class="py-12 px-8">
                                        <div class="flex items-center gap-6 flex-row-reverse">
                                            <div class="w-16 h-16 bg-amber-500/5 rounded-xl border border-amber-500/10 flex items-center justify-center text-amber-600 shadow-inner">
                                                <span class="material-symbols-outlined text-[32px]" style="font-variation-settings: 'FILL' 1">architecture</span>
                                            </div>
                                            <div class="text-right">
                                                <p class="font-black text-xl text-primary tracking-tight leading-none mb-3">تخصيص هيكلي مخصص</p>
                                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic">التزام استثنائي خارج القواعد المعيارية</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-12 px-8 text-center font-black text-xs text-slate-400 font-headline uppercase tracking-[0.2em]">Manual Entry</td>
                                    <td class="py-12 px-8 text-left font-black font-headline text-3xl text-primary tracking-tighter">{{ invoice.amount.toLocaleString() }} <span class="text-[10px] font-bold text-slate-300 mr-2 uppercase">ر.س</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- 4. Fiscal Conclusion & Authority Sign-off -->
                <div class="flex flex-col lg:flex-row justify-between items-start gap-16 pt-16 border-t-4 border-slate-900 flex-row-reverse">
                    <div class="w-full lg:w-[400px] space-y-10">
                        <div class="flex items-center justify-between text-slate-400 border-b border-outline-variant/10 pb-4 flex-row-reverse">
                            <span class="text-[11px] font-black uppercase tracking-widest font-headline">قيمة التسوية الصافية</span>
                            <span class="font-black font-headline text-lg text-primary">{{ invoice.amount.toLocaleString() }} <span class="text-[10px] mr-2">ر.س</span></span>
                        </div>
                        <div class="flex items-center justify-between text-slate-400 border-b border-outline-variant/10 pb-4 flex-row-reverse">
                            <span class="text-[11px] font-black uppercase tracking-widest font-headline">ضرائب الحوكمة الهيكلية (0%)</span>
                            <span class="font-black font-headline text-lg italic">0.00 <span class="text-[10px] mr-2">ر.س</span></span>
                        </div>
                        <div class="flex items-center justify-between pt-10 flex-row-reverse">
                            <span class="text-[16px] font-black uppercase tracking-[0.2em] text-primary">إجمالي المطالبة (Total)</span>
                            <div class="flex items-baseline gap-2">
                                <span class="text-5xl font-black font-headline tracking-tighter text-secondary">{{ invoice.amount.toLocaleString() }}</span>
                                <span class="text-sm font-black text-secondary/40 uppercase">ر.س</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="max-w-md space-y-8 text-right bg-slate-50 p-8 border border-outline-variant/10 rounded-xl shadow-inner">
                        <div class="flex items-center gap-3 justify-end text-primary mb-2">
                             <p class="text-[12px] font-black uppercase tracking-widest">ميثاق الالتزام المالي</p>
                             <span class="material-symbols-outlined text-[20px]">verified</span>
                        </div>
                        <p class="text-[10px] font-bold text-slate-500 leading-relaxed text-right opacity-70">نظام مدى كيو - بروتوكول الفوترة المؤسساتي الموحد</p>
                        <p class="text-[13px] font-bold text-slate-500 leading-relaxed italic">يُعد هذا السجل وثيقة رسمية تؤكد تخصيص الموارد التقنية والقانونية للخدمة المذكورة. أي مطالبات أو اعتراضات يجب أن تتم ضمن نافذة زمنية قدرها 7 أيام من تاريخ التوثيق.</p>
                        <div class="pt-8 flex justify-end">
                            <div class="text-center opacity-30">
                                <div class="w-32 h-1 bg-slate-300 mx-auto mb-3"></div>
                                <p class="text-[10px] font-black uppercase tracking-[0.2em]">ختم السلطة المركزية</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Print Context Overlay -->
                <div class="hidden print:block mt-40 text-center">
                    <p class="text-[10px] font-black text-slate-300 uppercase tracking-[0.8em] border-t-2 border-slate-100 pt-10 px-20">MADAAQ INFRASTRUCTURE GOVERNANCE PROTOCOL • OFFICIAL RECORD</p>
                </div>
            </div>
        </div>
    </InstitutionalLayout>
</template>



