<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';
import InstitutionalLayout from '@/Layouts/InstitutionalLayout.vue';
import { 
    ArrowRight,
    UserSearch,
    CreditCard,
    Calendar,
    Clock,
    CheckCircle2,
    Building2,
    ShieldCheck,
    CloudUpload,
    Receipt,
    Plus,
    User,
    Wallet,
    Info
} from 'lucide-vue-next';

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
        <div class="space-y-8 pb-20">
            
            <!-- Page Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-black text-slate-900">إصدار فاتورة جديدة</h1>
                    <p class="text-slate-400 font-bold text-xs mt-1">قم بتحديد المشترك والقيمة المالية لإصدار قيد مالي جديد</p>
                </div>
                <Link :href="route('accounting.invoices.index')" class="btn-outline flex items-center gap-2 px-6 py-2.5">
                    <ArrowRight class="w-4 h-4" />
                    العودة للسجل المالي
                </Link>
            </div>

            <form @submit.prevent="submit" class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                
                <!-- Main Form Area -->
                <div class="lg:col-span-2 space-y-8">
                    
                    <!-- Client Identification -->
                    <div class="clean-card p-10">
                        <div class="flex items-center gap-4 mb-10 border-b border-slate-50 pb-6">
                            <div class="w-10 h-10 bg-primary-soft text-primary rounded-xl flex items-center justify-center">
                                <UserSearch class="w-6 h-6" />
                            </div>
                            <div>
                                <h3 class="text-lg font-black text-slate-900">تحديد المشترك</h3>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Select Target Beneficiary</p>
                            </div>
                        </div>

                        <div class="space-y-8">
                            <div class="space-y-2">
                                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest mr-2 block">البحث عن مشترك</label>
                                <select v-model="form.client_id" class="w-full bg-slate-50 border-slate-200 rounded-xl px-5 py-4 text-sm font-bold focus:border-primary focus:ring-0 appearance-none cursor-pointer" required>
                                    <option value="">اختر المشترك من القائمة...</option>
                                    <option v-for="c in clients" :key="c.id" :value="c.id">{{ c.name }} ({{ c.username }})</option>
                                </select>
                            </div>

                            <!-- Selected Client Preview -->
                            <div v-if="selectedClient" class="p-8 bg-slate-900 text-white rounded-2xl flex items-center justify-between animate-in slide-in-from-top-2 duration-300 relative overflow-hidden">
                                 <div class="absolute inset-0 bg-primary/10 opacity-20 pointer-events-none"></div>
                                 <div class="flex items-center gap-6 relative z-10">
                                     <div class="w-14 h-14 rounded-xl bg-white/10 text-primary flex items-center justify-center border border-white/5">
                                         <User class="w-8 h-8" />
                                     </div>
                                     <div>
                                         <h4 class="text-lg font-black">{{ selectedClient.name }}</h4>
                                         <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">معرف المشترك: {{ selectedClient.username }}</p>
                                     </div>
                                 </div>
                                 <div class="flex items-center gap-2 bg-white/10 px-4 py-2 rounded-xl border border-white/5 relative z-10">
                                     <div class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></div>
                                     <span class="text-[9px] font-black uppercase tracking-widest">حساب موثق</span>
                                 </div>
                            </div>
                        </div>
                    </div>

                    <!-- Financial Details -->
                    <div class="clean-card p-10">
                        <div class="flex items-center gap-4 mb-10 border-b border-slate-50 pb-6">
                            <div class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center border border-emerald-100">
                                <Wallet class="w-6 h-6" />
                            </div>
                            <div>
                                <h3 class="text-lg font-black text-slate-900">تفاصيل الفاتورة</h3>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Fiscal Parameters & Status</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-2">
                                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest mr-2 block">القيمة المالية (ل.س)</label>
                                <input v-model="form.amount" type="number" step="0.01" placeholder="0.00" class="w-full bg-slate-50 border-slate-200 rounded-xl px-5 py-4 text-xl font-black text-slate-900 focus:border-primary focus:ring-0 transition-all" required />
                            </div>
                            <div class="space-y-2">
                                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest mr-2 block">تاريخ الاستحقاق</label>
                                <input v-model="form.due_date" type="date" class="w-full bg-slate-50 border-slate-200 rounded-xl px-5 py-4 text-sm font-bold focus:border-primary focus:ring-0 transition-all font-inter" required />
                            </div>
                        </div>

                        <div class="mt-10 pt-10 border-t border-slate-50">
                             <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest mr-2 block mb-4">وضعية الفاتورة الابتدائية</label>
                             <div class="grid grid-cols-2 gap-6">
                                 <button type="button" @click="form.status = 'unpaid'" class="flex flex-col items-center gap-4 p-8 rounded-3xl border transition-all" :class="form.status === 'unpaid' ? 'bg-slate-900 text-white border-slate-900 shadow-xl' : 'bg-slate-50 border-slate-200 text-slate-400 hover:bg-slate-100'">
                                     <Clock class="w-8 h-8" />
                                     <span class="text-[10px] font-black uppercase tracking-widest">بانتظار التحصيل</span>
                                 </button>
                                 <button type="button" @click="form.status = 'paid'" class="flex flex-col items-center gap-4 p-8 rounded-3xl border transition-all" :class="form.status === 'paid' ? 'bg-primary text-white border-primary shadow-xl' : 'bg-slate-50 border-slate-200 text-slate-400 hover:bg-slate-100'">
                                     <CheckCircle2 class="w-8 h-8" />
                                     <span class="text-[10px] font-black uppercase tracking-widest">مدفوعة ومحصلة</span>
                                 </button>
                             </div>
                        </div>
                    </div>
                </div>

                <!-- Summary Sidebar -->
                <div class="space-y-8 sticky top-24">
                    
                    <!-- Invoice ID Card -->
                    <div class="clean-card p-10 bg-slate-900 text-white border-none shadow-2xl shadow-slate-900/20 relative overflow-hidden">
                        <div class="absolute -top-10 -right-10 w-32 h-32 bg-primary/20 rounded-full blur-2xl"></div>
                        <div class="relative z-10">
                            <p class="text-[10px] font-black uppercase tracking-widest opacity-40 mb-2">رقم الوثيقة المالية</p>
                            <h3 class="text-4xl font-black italic tracking-tighter text-primary mb-10">{{ nextInvoiceNumber }}</h3>

                            <div class="space-y-4 mb-10 pt-10 border-t border-white/5">
                                <div class="flex justify-between items-center text-[10px] font-black uppercase tracking-widest">
                                    <span class="opacity-40">الحالة المختارة</span>
                                    <span>{{ form.status === 'paid' ? 'مدفوعة' : 'غير مدفوعة' }}</span>
                                </div>
                                <div class="flex justify-between items-center text-[10px] font-black uppercase tracking-widest">
                                    <span class="opacity-40">قيمة القيد</span>
                                    <span>{{ form.amount || '0.00' }} SAR</span>
                                </div>
                            </div>

                            <button 
                                type="submit" 
                                class="w-full py-5 bg-white text-slate-900 rounded-2xl font-black text-sm uppercase tracking-widest shadow-xl hover:bg-primary hover:text-white transition-all active:scale-95 flex items-center justify-center gap-3 disabled:opacity-50"
                                :disabled="form.processing || !form.client_id || !form.amount"
                            >
                                <CloudUpload class="w-5 h-5" />
                                سك واعتماد الفاتورة
                            </button>
                        </div>
                    </div>

                    <!-- Tech Tips -->
                    <div class="p-6 bg-amber-50 border border-amber-100 rounded-2xl">
                        <div class="flex gap-4">
                            <Info class="w-5 h-5 text-amber-600 shrink-0" />
                            <p class="text-[11px] font-bold text-amber-900 leading-relaxed">
                                سيتم إرسال إشعار تلقائي للمشترك عبر لوحة التحكم الخاصة به فور اعتماد هذه الفاتورة. يرجى التأكد من المبالغ المدخلة.
                            </p>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </InstitutionalLayout>
</template>
