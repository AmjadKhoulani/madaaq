<script setup>
import { ref, Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import { useForm } from 'lucide-vue-next';;
import InstitutionalLayout from '@/Layouts/InstitutionalLayout.vue';

const props = defineProps({
    servers: Array,
    packages: Array
});

const form = useForm({
    server_id: '',
    package_id: '',
    quantity: 10,
    prefix: '',
    length: 6,
});

const selectedPackage = computed(() => {
    return props.packages.find(p => p.id === form.package_id);
});

const selectedServer = computed(() => {
    return props.servers.find(s => s.id === form.server_id);
});

const submit = () => {
    form.post(route('hotspot.vouchers.store'));
};

</script>

<template>
    <InstitutionalLayout title="توليد قسائم جديدة">
        <Head title="إصدار قسائم الهوت سبوت - MadaaQ" />

        <div class="max-w-4xl mx-auto pb-24 text-right px-4" dir="rtl">
            <!-- Navigation Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-8 mb-16 flex-row-reverse">
                <div class="flex items-center gap-6 justify-end">
                    <div class="text-right">
                        <h1 class="text-4xl font-black text-primary tracking-tight mb-2">منظومة توليد القسائم (Minting)</h1>
                        <p class="text-slate-500 font-bold text-sm uppercase tracking-wider flex items-center gap-3 justify-end">
                            <span>إصدار دفعات الرموز الائتمانية عبر بروتوكول API</span>
                            <span class="material-symbols-outlined text-primary text-[20px]">confirmation_number</span>
                        </p>
                    </div>
                    <Link 
                        :href="route('hotspot.vouchers.index')" 
                        class="w-14 h-14 bg-white shadow-sm border border-outline-variant/10 rounded-xl flex items-center justify-center text-slate-400 hover:text-primary transition-all group"
                    >
                        <span class="material-symbols-outlined text-[28px] group-hover:translate-x-2 transition-transform">arrow_forward</span>
                    </Link>
                </div>
            </div>

            <form @submit.prevent="submit" class="space-y-10">
                <!-- 1. Deployment Host -->
                <div class="surface-card p-12 rounded-2xl relative overflow-hidden border border-outline-variant/5 shadow-sm">
                    <div class="flex items-center gap-3 mb-10 justify-end">
                        <h3 class="text-xs font-black text-primary uppercase tracking-[0.2em]">خادم الربط ونطاق الخدمة</h3>
                        <span class="material-symbols-outlined text-secondary">lan</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                         <div class="space-y-4 text-right">
                            <label class="text-[11px] font-black text-primary uppercase tracking-widest mr-2">خادم الميكروتيك المستهدف (Gateway)</label>
                            <select v-model="form.server_id" class="form-input-monolith h-14 font-black text-sm pr-12" required>
                                <option value="" disabled>اختر بوابة التزويد...</option>
                                <option v-for="s in servers" :key="s.id" :value="s.id">{{ s.name }} — {{ s.ip }}</option>
                            </select>
                        </div>
                        <div class="space-y-4 text-right">
                            <label class="text-[11px] font-black text-primary uppercase tracking-widest mr-2">باقة الاشتراك (Standard Profile)</label>
                            <select v-model="form.package_id" class="form-input-monolith h-14 font-black text-sm pr-12" required>
                                <option value="" disabled>اختر باقة الخدمة الفنية...</option>
                                <option v-for="p in packages" :key="p.id" :value="p.id">{{ p.name }} ({{ p.price }} ل.س)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- 2. Synthesis Parameters -->
                <div class="surface-card p-12 rounded-2xl relative overflow-hidden border border-outline-variant/5 shadow-sm">
                    <div class="flex items-center gap-3 mb-10 justify-end">
                        <h3 class="text-xs font-black text-primary uppercase tracking-[0.2em]">معايير توليد الرموز (Synthesis)</h3>
                        <span class="material-symbols-outlined text-emerald-500">settings_suggest</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                        <div class="space-y-4 text-right">
                            <label class="text-[11px] font-black text-primary uppercase tracking-widest mr-2">حجم الدفعة (Quantity)</label>
                            <div class="relative">
                                <input v-model="form.quantity" type="number" min="1" max="500" class="form-input-monolith h-14 pr-14 font-headline font-black text-xl">
                                <span class="material-symbols-outlined absolute right-5 top-4 text-emerald-500">layers</span>
                            </div>
                        </div>
                        <div class="space-y-4 text-right">
                            <label class="text-[11px] font-black text-primary uppercase tracking-widest mr-2">بادئة الرمز (Prefix)</label>
                            <div class="relative">
                                <input v-model="form.prefix" type="text" maxlength="5" class="form-input-monolith h-14 pr-14 font-headline font-black text-xl" placeholder="GT-">
                                <span class="material-symbols-outlined absolute right-5 top-4 text-primary">text_fields</span>
                            </div>
                        </div>
                        <div class="space-y-4 text-right">
                            <label class="text-[11px] font-black text-primary uppercase tracking-widest mr-2">طول الرمز (Length)</label>
                            <div class="relative">
                                <input v-model="form.length" type="number" min="4" max="12" class="form-input-monolith h-14 pr-14 font-headline font-black text-xl">
                                <span class="material-symbols-outlined absolute right-5 top-4 text-secondary">pin</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. Minting Preview -->
                <div v-if="selectedPackage && selectedServer" class="surface-card p-12 bg-slate-950 text-white relative overflow-hidden animate-in slide-in-from-top duration-700 rounded-2xl shadow-2xl">
                    <div class="absolute -top-16 -right-16 w-64 h-64 bg-primary/20 rounded-full blur-3xl"></div>
                    <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-12 flex-row-reverse">
                        <div class="flex items-center gap-8 flex-row-reverse text-right">
                            <div class="w-20 h-20 bg-white/10 rounded-2xl flex items-center justify-center border border-white/5 shadow-2xl">
                                <span class="material-symbols-outlined text-[40px] text-primary">precision_manufacturing</span>
                            </div>
                            <div>
                                <h4 class="text-2xl font-black tracking-tight leading-none mb-2">ملخص البروتوكول</h4>
                                <p class="text-[10px] font-black text-white/40 uppercase tracking-widest">جاهز للحقن البرمجي في خادم الميكروتيك</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-16 text-right">
                            <div>
                                <p class="text-[10px] font-black text-white/40 uppercase tracking-widest mb-2">القيمة الائتمانية</p>
                                <p class="text-4xl font-black text-secondary font-headline">{{ (selectedPackage.price * form.quantity).toLocaleString() }} <span class="text-xs opacity-50 uppercase mr-1">S.P</span></p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-white/40 uppercase tracking-widest mb-2">خادم المزامنة</p>
                                <p class="text-2xl font-black text-primary truncate max-w-[150px]">{{ selectedServer.name }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-12 p-8 bg-white/5 rounded-2xl border border-white/10 flex items-center justify-between group flex-row-reverse shadow-inner">
                        <div class="flex items-center gap-6 flex-row-reverse">
                            <div class="px-8 py-3 rounded-xl bg-white/10 flex items-center justify-center font-headline font-black text-2xl text-amber-500 tracking-[0.2em] border border-white/5">
                                {{ form.prefix || '' }}{{ 'X'.repeat(form.length) }}
                            </div>
                            <p class="text-xs font-black text-white/30 uppercase tracking-widest">نموذج الرمز المولد (Artifact)</p>
                        </div>
                        <span class="material-symbols-outlined text-emerald-500 group-hover:scale-125 transition-all text-[36px]">verified</span>
                    </div>
                </div>

                <!-- 4. Governance Commitment -->
                <div class="flex flex-col md:flex-row items-center justify-between gap-10 surface-card p-12 bg-slate-900 text-white rounded-2xl relative overflow-hidden shadow-2xl border border-white/5">
                    <div class="absolute -bottom-32 -left-32 w-80 h-80 bg-primary/10 rounded-full blur-3xl"></div>
                    <div class="relative z-10 flex items-center gap-8 text-right flex-row-reverse">
                        <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center shrink-0 border border-white/5">
                            <span class="material-symbols-outlined text-[32px] text-amber-500">verified_user</span>
                        </div>
                        <div>
                             <h4 class="text-xl font-black uppercase tracking-tight">إقرار المزامنة الطرفية</h4>
                             <p class="text-[10px] font-black text-white/40 uppercase tracking-widest mt-2 leading-relaxed">
                                سيتم حقن رموز الوصول في قاعدة بيانات خادم الميكروتيك فوراً عبر بروتوكول API الموحد.
                             </p>
                        </div>
                    </div>
                    <div class="relative z-10 flex items-center gap-6 flex-row-reverse">
                         <button 
                            type="submit" 
                            class="px-14 py-5 bg-primary text-white font-black text-xs uppercase tracking-[0.2em] rounded-xl shadow-2xl shadow-primary/30 hover:bg-primary/90 transition-all hover:scale-105 active:scale-95 disabled:opacity-30 flex items-center gap-4 border border-white/10"
                            :disabled="form.processing || !form.server_id || !form.package_id"
                        >
                            <span class="material-symbols-outlined text-[24px]">cloud_sync</span> تنفيذ عملية التوليد
                        </button>
                        <Link 
                            :href="route('hotspot.vouchers.index')" 
                            class="px-10 py-5 bg-white/5 text-white/70 font-black text-xs uppercase tracking-widest rounded-xl hover:bg-white/10 hover:text-white transition-all active:scale-95 border border-white/5"
                        >
                            إلغاء الأمر
                        </Link>
                    </div>
                </div>
            </form>
        </div>
    </InstitutionalLayout>
</template>

<style scoped>
.font-headline {
    font-family: 'Manrope', sans-serif;
}
</style>


