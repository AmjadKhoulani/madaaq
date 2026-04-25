<script setup>
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    users: Array,
});

const printVouchers = () => {
    window.print();
};

</script>

<template>
    <div class="min-h-screen bg-[#f8fafc] print:bg-white text-slate-900 font-sans selection:bg-primary selection:text-white" dir="rtl">
        <Head title="بروتوكول طباعة القسائم - MadaaQ" />

        <!-- UI Header (Hidden on Print) -->
        <div class="max-w-[1200px] mx-auto px-6 py-12 flex items-center justify-between print:hidden border-b border-outline-variant/5 mb-10">
            <div class="flex items-center gap-6">
                <Link 
                    :href="route('hotspot.vouchers.index')" 
                    class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center text-slate-400 hover:text-primary transition-all shadow-sm group border border-outline-variant/10"
                >
                    <span class="material-symbols-outlined text-[28px] group-hover:translate-x-2 transition-transform">arrow_forward</span>
                </Link>
                <div class="text-right">
                    <h1 class="text-3xl font-black text-primary tracking-tight mb-1">تجهيز أمور الطباعة</h1>
                    <p class="text-slate-500 font-bold text-sm uppercase tracking-wider">جاري تحضير عدد ({{ users.length.toLocaleString() }}) قسيمة للتوزيع الفيزيائي</p>
                </div>
            </div>
            <div class="flex items-center gap-4">
                 <button 
                    @click="printVouchers" 
                    class="px-12 py-5 bg-primary text-white rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-2xl hover:scale-105 active:scale-95 transition-all flex items-center gap-4 border border-white/10"
                 >
                    <span class="material-symbols-outlined">print</span> بدء بروتوكول الطباعة
                 </button>
            </div>
        </div>

        <!-- Print Grid -->
        <div class="max-w-[1100px] mx-auto px-6 pb-20 print:p-0 print:m-0">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 print:grid-cols-3 print:gap-2 print:block">
                <div 
                    v-for="user in users" 
                    :key="user.id"
                    class="bg-white rounded-3xl border border-outline-variant/10 p-10 flex flex-col relative overflow-hidden shadow-sm print:border print:border-slate-200 print:rounded-2xl print:p-6 print:mb-2 print:break-inside-avoid print:w-[32%] print:inline-block print:align-top print:ml-[1%] print:mr-0 print:rtl"
                >
                    <!-- Brand Top -->
                    <div class="flex items-center justify-between mb-8 flex-row-reverse">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-slate-900 rounded-xl flex items-center justify-center text-[12px] text-white font-black border border-white/10 shadow-lg">M</div>
                            <span class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-900">MadaaQ <span class="text-primary italic">Access</span></span>
                        </div>
                        <span class="material-symbols-outlined text-slate-200 text-[24px]">confirmation_number</span>
                    </div>

                    <!-- Identity -->
                    <div class="space-y-6 mb-10 text-right">
                        <div class="space-y-2">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none">مُعرف الوصول (Secret)</p>
                            <p class="text-3xl font-headline font-black tracking-[0.1em] border-b-4 border-primary/10 pb-2 uppercase text-slate-900">{{ user.username }}</p>
                        </div>
                        <div class="space-y-2">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none">رمز المرور (Access Code)</p>
                            <p class="text-2xl font-headline font-black tracking-[0.15em] uppercase text-primary">{{ user.password }}</p>
                        </div>
                    </div>

                    <!-- Specs -->
                    <div class="mt-auto pt-8 border-t border-slate-50 space-y-5 text-right">
                        <div class="flex items-center justify-between flex-row-reverse">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-amber-500 text-[18px]">bolt</span>
                                <span class="text-[11px] font-black uppercase tracking-tight text-slate-900">{{ user.package?.name || 'V-ULTRA' }}</span>
                            </div>
                            <span class="text-[11px] font-headline font-black tracking-tight text-emerald-600">{{ user.package?.price.toLocaleString() }} <span class="text-[9px] opacity-50 mr-1 uppercase">S.P</span></span>
                        </div>
                        <div class="flex items-center gap-3 text-slate-400 flex-row-reverse">
                            <span class="material-symbols-outlined text-[18px]">wifi_password</span>
                            <span class="text-[9px] font-black uppercase tracking-widest">الاتصال عبر شبكة: <span class="text-slate-900">MadaaQ_Access</span></span>
                        </div>
                    </div>

                    <!-- Subtle Shield Mark -->
                    <div class="absolute -bottom-12 -left-12 w-32 h-32 bg-primary/5 rounded-full blur-3xl opacity-50"></div>
                </div>
            </div>
        </div>

        <!-- Footer Protocol (Print Only) -->
        <div class="hidden print:block fixed bottom-8 left-0 right-0 text-center">
            <p class="text-[8px] font-black text-slate-300 uppercase tracking-[0.4em]">إصدار بروتوكول مدى إنفراستركتشر • نظام التوزيع الموحد • MADAQAQ.NET</p>
        </div>
    </div>
</template>

<style scoped>
.font-headline {
    font-family: 'Manrope', sans-serif;
}
@media print {
    body {
        margin: 0;
        padding: 0;
        -webkit-print-color-adjust: exact;
    }
    .print\:hidden {
        display: none !important;
    }
}
</style>


