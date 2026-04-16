<script setup>
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    user: Object
});

const printArtifact = () => {
    window.print();
};

</script>

<template>
    <div class="min-h-screen bg-[#f8fafc] print:bg-white text-slate-900 font-sans selection:bg-primary selection:text-white" dir="rtl">
        <Head title="طباعة بطاقة الوصول - MadaaQ" />

        <!-- Navigation Header (Hidden on Print) -->
        <div class="max-w-xl mx-auto px-6 py-12 flex items-center justify-between print:hidden gap-6">
            <Link 
                :href="route('hotspot.users.index')" 
                class="w-14 h-14 bg-white border border-outline-variant/10 rounded-2xl flex items-center justify-center text-slate-400 hover:text-primary transition-all shadow-sm group"
            >
                <span class="material-symbols-outlined text-[28px] group-hover:translate-x-2 transition-transform">arrow_forward</span>
            </Link>
            <button 
                @click="printArtifact"
                class="px-12 h-14 bg-primary text-white rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-2xl shadow-primary/20 hover:scale-105 active:scale-95 transition-all flex items-center gap-4 border border-white/10"
            >
                <span class="material-symbols-outlined text-[24px]">print</span>
                بدء بروتوكول الطباعة
            </button>
        </div>

        <!-- Print Artifact Container -->
        <div class="max-w-xl mx-auto px-6 pb-24 print:p-0 print:m-0">
            <div class="surface-card bg-white p-12 relative overflow-hidden shadow-2xl rounded-[3rem] border border-outline-variant/5 print:border print:border-slate-950/10 print:rounded-3xl print:shadow-none print:break-inside-avoid text-right">
                <!-- Branding Pulse -->
                <div class="flex items-center justify-between mb-16 flex-row-reverse text-right">
                    <div class="flex items-center gap-5 flex-row-reverse">
                        <div class="w-16 h-16 bg-slate-900 rounded-[1.5rem] flex items-center justify-center text-white text-3xl font-black shadow-2xl border border-white/10">M</div>
                        <div>
                            <h2 class="text-2xl font-black text-primary tracking-tighter leading-none mb-2">ضيف مداك (Access)</h2>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">وثيقة بروتوكول الوصول الفني</p>
                        </div>
                    </div>
                    <span class="material-symbols-outlined text-state-200 text-[40px] opacity-10">verified_user</span>
                </div>

                <!-- Identity Discovery -->
                <div class="space-y-12 mb-16">
                    <div class="space-y-4">
                        <div class="flex items-center gap-3 text-[11px] uppercase font-black tracking-widest text-slate-400 justify-end">
                            مُعرف المشترك المعتمد
                            <span class="material-symbols-outlined text-[20px]">person</span>
                        </div>
                        <p class="text-4xl font-black text-primary tracking-tight border-b-4 border-primary/5 pb-6 text-right leading-none">{{ user.name }}</p>
                    </div>

                    <div class="grid grid-cols-1 gap-10">
                        <div class="space-y-4">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">اسم المستخدم (Access Username)</p>
                            <div class="p-8 bg-slate-950 text-white rounded-[2rem] text-center shadow-2xl border border-white/5">
                                <p class="text-3xl font-headline font-black tracking-[0.2em] uppercase">{{ user.username }}</p>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">رمز المرور (Access Secret)</p>
                            <div class="p-8 bg-white border-4 border-dashed border-primary/20 rounded-[2rem] text-center shadow-inner">
                                <p class="text-3xl font-headline font-black tracking-[0.2em] uppercase text-primary">{{ user.password }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Performance Matrix -->
                <div class="pt-12 border-t border-slate-50 space-y-12">
                    <div class="flex items-center justify-between flex-row-reverse text-right">
                         <div class="flex items-center gap-5 flex-row-reverse">
                             <div class="w-14 h-14 rounded-2xl bg-amber-500/5 flex items-center justify-center text-amber-500 border border-amber-500/10 shadow-sm">
                                <span class="material-symbols-outlined text-[28px]">speed</span>
                             </div>
                             <div>
                                 <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 leading-none">فئة السرعة المقررة</p>
                                 <p class="text-lg font-black text-primary tracking-tight uppercase leading-none">{{ user.package?.name || 'فئة عامة' }}</p>
                             </div>
                         </div>
                         <div class="text-left">
                             <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 leading-none">النطاق الترددي</p>
                             <p class="text-xl font-headline font-black text-primary leading-none">{{ user.package?.speed_down }} / {{ user.package?.speed_up }} <span class="text-[10px] opacity-40 uppercase mr-1">Mbps</span></p>
                         </div>
                    </div>

                    <div class="flex items-center justify-between flex-row-reverse text-right">
                         <div class="flex items-center gap-5 flex-row-reverse">
                             <div class="w-14 h-14 rounded-2xl bg-indigo-500/5 flex items-center justify-center text-indigo-500 border border-indigo-500/10 shadow-sm">
                                <span class="material-symbols-outlined text-[28px]">schedule</span>
                             </div>
                             <div>
                                 <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 leading-none">صلاحية الوصول (Expiration)</p>
                                 <p class="text-lg font-black text-primary tracking-tight leading-none">{{ user.expires_at ? new Date(user.expires_at).toLocaleDateString('ar-SY') : 'وصول دائم معتمد (Permanent)' }}</p>
                             </div>
                         </div>
                         <span class="material-symbols-outlined text-emerald-500 text-[32px]">verified</span>
                    </div>
                </div>

                <!-- Instructions Protocol -->
                <div class="mt-16 p-10 bg-slate-50 border border-dashed border-outline-variant/20 rounded-[2.5rem] space-y-6 text-right">
                    <div class="flex items-center gap-4 justify-end">
                        <p class="text-[11px] font-black uppercase tracking-widest text-slate-400">بروتوكول المصافحة الموحد (Standard Protocol)</p>
                        <span class="material-symbols-outlined text-primary text-[24px]">contactless</span>
                    </div>
                    <ul class="space-y-4 text-[13px] font-bold leading-relaxed text-slate-600 list-none text-right">
                        <li class="flex items-center gap-4 flex-row-reverse">
                            <span class="w-6 h-6 rounded-lg bg-primary/10 text-primary flex items-center justify-center text-[11px] font-black shrink-0 font-headline">01</span>
                            قم بالاتصال بشبكة البث المتاحة في موقعك (WiFi SSID).
                        </li>
                        <li class="flex items-center gap-4 flex-row-reverse">
                            <span class="w-6 h-6 rounded-lg bg-primary/10 text-primary flex items-center justify-center text-[11px] font-black shrink-0 font-headline">02</span>
                            استخدم مُعرف الوصول ورمز المرور الموضح في هذه البطاقة.
                        </li>
                        <li class="flex items-center gap-4 flex-row-reverse">
                            <span class="w-6 h-6 rounded-lg bg-primary/10 text-primary flex items-center justify-center text-[11px] font-black shrink-0 font-headline">03</span>
                            في حال واجهت أي عوائق تقنية، يرجى التواصل مع الدعم الفني فوراً.
                        </li>
                    </ul>
                </div>

                <!-- Print Footer Protocol -->
                <div class="hidden print:block mt-24 text-center">
                    <div class="w-20 h-1.5 bg-slate-100 mx-auto mb-6 rounded-full"></div>
                    <p class="text-[9px] font-black text-slate-300 uppercase tracking-[0.5em]">MadaaQ Handshake Protocol • Integrity Artifact • MADAQAQ.NET</p>
                </div>

                <!-- Background Element -->
                <div class="absolute -bottom-24 -left-24 w-80 h-80 bg-primary/5 rounded-full blur-3xl pointer-events-none opacity-50"></div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.font-headline {
    font-family: 'Manrope', sans-serif;
}
@media print {
    body {
        margin: 0 !important;
        padding: 0 !important;
        background: white !important;
        -webkit-print-color-adjust: exact;
    }
    .print\:hidden {
        display: none !important;
    }
}
</style>
