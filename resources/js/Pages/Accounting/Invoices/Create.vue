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
    Gavel,
    Receipt
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
        <Head title="سك وثيقة مالية جديدة" />

        <div class="max-w-5xl mx-auto pb-24 text-right">
            <!-- Strategic Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-8 mb-12">
                <div class="flex items-center gap-6">
                    <Link 
                        :href="route('accounting.invoices.index')" 
                        class="w-12 h-12 bg-white shadow-sm border border-white/60 rounded-xl flex items-center justify-center text-slate-400 hover:text-vendor transition-all group"
                    >
                        <ArrowRight class="w-6 h-6 group-hover:translate-x-1 transition-transform" />
                    </Link>
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                             <span class="w-8 h-1 bg-vendor rounded-full"></span>
                             <p class="text-[10px] font-black text-vendor uppercase tracking-[0.3em] font-inter">Financial Ledger Generation</p>
                        </div>
                        <h1 class="text-3xl font-black text-slate-900 tracking-tight italic">سك وثيقة مالية <span class="text-vendor">(Ledger Artifact)</span></h1>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest leading-none mt-2 italic">بدء بروتوكول الالتزام المالي الموثق للشركاء</p>
                    </div>
                </div>
            </div>

            <form @submit.prevent="submit" class="space-y-8">
                <!-- 1. Subscriber Identification Matrix -->
                <div class="glass-card p-10 bg-white/60">
                    <h3 class="text-xs font-black text-vendor uppercase tracking-[0.3em] mb-10 flex items-center gap-3 italic font-inter">
                        <UserSearch class="w-5 h-5" />
                        تحديد هوية الطرف المستهدف
                    </h3>

                    <div class="space-y-8">
                        <div class="space-y-4">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest block mr-2 italic">اختيار الكيان من سجلات الحوكمة</label>
                            <div class="relative group">
                                <Building2 class="absolute right-6 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-vendor transition-colors w-6 h-6" />
                                <select v-model="form.client_id" class="w-full bg-white/50 border-white/60 rounded-2xl pr-16 h-16 font-black text-lg text-slate-900 focus:ring-4 focus:ring-vendor/5 transition-all appearance-none" required>
                                    <option value="">ابحث في قاعدة بيانات المشتركين السيادية...</option>
                                    <option v-for="c in clients" :key="c.id" :value="c.id">{{ c.name }} ({{ c.username }})</option>
                                </select>
                            </div>
                        </div>

                        <!-- Subscriber Validation Pulse -->
                        <div v-if="selectedClient" class="p-8 bg-slate-900 text-white rounded-[2rem] flex items-center justify-between animate-in slide-in-from-top-4 duration-500 relative overflow-hidden group">
                             <div class="absolute inset-0 bg-vendor/10 opacity-20 animate-pulse pointer-events-none"></div>
                             <div class="flex items-center gap-6 relative z-10">
                                 <div class="w-16 h-16 rounded-2xl bg-white/10 text-vendor flex items-center justify-center border border-white/10 shadow-inner group-hover:scale-110 transition-transform">
                                     <ShieldCheck class="w-10 h-10 stroke-[2.5]" />
                                 </div>
                                 <div class="space-y-2 text-right">
                                     <p class="text-xl font-black text-white italic tracking-tighter uppercase font-inter">{{ selectedClient.name }}</p>
                                     <p class="text-[10px] font-black text-white/40 uppercase tracking-widest leading-none italic">البروتوكول المعين: {{ selectedClient.type?.toUpperCase() || 'BROADBAND_CORE' }}</p>
                                 </div>
                             </div>
                             <div class="flex items-center gap-4 bg-white/10 px-6 py-2.5 rounded-2xl border border-white/5 relative z-10">
                                 <div class="w-2.5 h-2.5 bg-emerald-500 rounded-full animate-ping"></div>
                                 <span class="text-[10px] font-black text-white uppercase tracking-widest font-inter">Verified Node</span>
                             </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Fiscal Parameters & Status Layer -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Commercial Extraction Card -->
                    <div class="glass-card p-10 space-y-10 bg-white/60">
                         <h3 class="text-xs font-black text-vendor uppercase tracking-[0.3em] flex items-center gap-3 italic font-inter">
                            <CreditCard class="w-5 h-5" />
                            الاستخراج المالي (Commercial Yield)
                        </h3>

                        <div class="space-y-8 text-right">
                            <div class="space-y-4">
                                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest block mr-2 italic">القيمة الملتزم بها (Yield Amount)</label>
                                <div class="relative group">
                                    <input v-model="form.amount" type="number" step="0.01" class="w-full bg-white/50 border-white/60 rounded-2xl pr-16 h-16 font-black text-3xl text-slate-900 focus:ring-4 focus:ring-vendor/5 transition-all font-inter" placeholder="0.00" required />
                                    <Receipt class="absolute right-6 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-vendor transition-colors w-8 h-8" />
                                    <span class="absolute left-6 top-1/2 -translate-y-1/2 text-[10px] font-black text-slate-300 uppercase tracking-widest font-inter">SAR</span>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest block mr-2 italic">أفق الاستحقاق (Fiscal Horizon)</label>
                                <div class="relative group">
                                    <input v-model="form.due_date" type="date" class="w-full bg-white/50 border-white/60 rounded-2xl pr-16 h-16 font-black text-xl text-slate-900 focus:ring-4 focus:ring-vendor/5 transition-all font-inter" required />
                                    <Calendar class="absolute right-6 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-vendor transition-colors w-8 h-8" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Status Protocol Control -->
                    <div class="glass-card p-10 space-y-10 bg-white/60">
                         <h3 class="text-xs font-black text-vendor uppercase tracking-[0.3em] flex items-center gap-3 italic font-inter">
                            <Clock class="w-5 h-5" />
                            بروتوكول حالة التسوية اللحظية
                        </h3>

                        <div class="space-y-8">
                            <div class="space-y-4">
                                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest block mr-2 italic">حالة التسوية الابتدائية</label>
                                <div class="grid grid-cols-2 gap-6">
                                    <button 
                                        type="button" 
                                        @click="form.status = 'unpaid'"
                                        class="p-8 rounded-[2rem] border transition-all flex flex-col items-center gap-5 group"
                                        :class="form.status === 'unpaid' ? 'bg-slate-900 text-white border-slate-900 shadow-radiant' : 'bg-white/40 border-white/60 text-slate-400 hover:bg-white/60'"
                                    >
                                        <Clock class="w-10 h-10 transition-transform group-hover:rotate-12" />
                                        <span class="text-[10px] font-black uppercase tracking-widest font-inter italic">Awaiting Protocol</span>
                                    </button>
                                    <button 
                                        type="button" 
                                        @click="form.status = 'paid'"
                                        class="p-8 rounded-[2rem] border transition-all flex flex-col items-center gap-5 group"
                                        :class="form.status === 'paid' ? 'bg-vendor text-white border-vendor shadow-radiant' : 'bg-white/40 border-white/60 text-slate-400 hover:bg-white/60'"
                                    >
                                        <CheckCircle2 class="w-10 h-10 transition-transform group-hover:scale-110" />
                                        <span class="text-[10px] font-black uppercase tracking-widest font-inter italic">Verified Yield</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Artifact Identifier Card -->
                            <div class="p-10 bg-slate-900 text-white rounded-[2rem] relative overflow-hidden group shadow-radiant border border-white/5">
                                <div class="absolute -top-10 -right-10 w-48 h-48 bg-vendor/20 rounded-full blur-3xl opacity-40 group-hover:scale-125 transition-transform duration-1000"></div>
                                <div class="relative z-10 flex items-center gap-10 justify-end">
                                    <div class="text-right">
                                         <p class="text-[9px] font-black text-white/30 uppercase tracking-[0.3em] mb-2 font-inter italic">Artifact Identity Code</p>
                                         <p class="text-3xl font-black tracking-widest text-vendor font-inter italic">{{ nextInvoiceNumber }}</p>
                                    </div>
                                    <div class="w-16 h-16 rounded-2xl bg-white/5 flex items-center justify-center border border-white/5 text-white/20">
                                        <Receipt class="w-10 h-10" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. Final Strategic Commitment -->
                <div class="glass-card p-12 bg-slate-900 text-white border-none shadow-radiant relative overflow-hidden flex flex-col md:flex-row items-center justify-between gap-12 group/final">
                    <div class="absolute inset-0 bg-vendor/10 animate-pulse pointer-events-none opacity-20"></div>
                    <div class="relative z-10 flex items-center gap-10 text-right w-full lg:w-auto">
                        <div class="w-20 h-20 bg-white/10 rounded-2xl flex items-center justify-center text-vendor border border-white/5 shadow-inner rotate-3 transition-transform group-hover/final:rotate-0">
                            <Gavel class="w-12 h-12 stroke-[2.5]" />
                        </div>
                        <div>
                             <h4 class="text-2xl font-black text-white uppercase tracking-tight italic font-inter leading-none mb-3">اعتماد القيد المالي (Commit)</h4>
                             <p class="text-[11px] font-black text-white/30 uppercase tracking-[0.3em] leading-none italic">
                                ترحيل الوثيقة المالية إلى السجل العالمي وتحديث مصفوفة الشريك.
                             </p>
                        </div>
                    </div>
                    <div class="relative z-10 flex items-center gap-6 w-full lg:w-auto shrink-0">
                        <Link 
                            :href="route('accounting.invoices.index')" 
                            class="flex-1 lg:flex-none px-10 py-6 bg-white/5 text-white/40 font-black text-[11px] uppercase tracking-widest rounded-2xl border border-white/5 hover:bg-white/10 hover:text-white transition-all text-center italic"
                        >
                            إلغاء العملية
                        </Link>
                        <button 
                            type="submit" 
                            class="flex-1 lg:flex-none px-16 py-6 bg-white text-slate-900 font-black text-[12px] uppercase tracking-[0.3em] rounded-2xl shadow-2xl hover:bg-vendor hover:text-white transition-all hover:scale-105 active:scale-95 disabled:opacity-50 flex items-center justify-center gap-5 border border-white/10 font-inter"
                            :disabled="form.processing || !form.client_id || !form.amount"
                        >
                            <CloudUpload class="w-6 h-6 group-hover:translate-y-[-2px] transition-transform" />
                            سك واعتماد الوثيقة
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </InstitutionalLayout>
</template>
