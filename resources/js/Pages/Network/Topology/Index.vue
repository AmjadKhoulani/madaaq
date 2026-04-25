<script setup>
import { Head } from '@inertiajs/vue3';
import InstitutionalLayout from '@/Layouts/InstitutionalLayout.vue';
import { ref, computed } from 'vue';

const props = defineProps({
    routers: Array,
    towers: Array,
    servers: Array,
    internetSources: Array
});

const getStatusColor = (status) => {
    switch (status) {
        case 'online': return 'text-emerald-600 bg-emerald-500/5 border-emerald-500/20';
        case 'offline': return 'text-rose-600 bg-rose-500/5 border-rose-500/20';
        default: return 'text-amber-600 bg-amber-500/5 border-amber-500/20';
    }
};

</script>

<template>
    <InstitutionalLayout title="ذكاء الطوبولوجيا">
        <Head title="هيكلية البنية التحتية المنطقية - MadaaQ" />

        <div class="max-w-7xl mx-auto pb-24 text-right px-4" dir="rtl">
            <!-- Strategic Header Intel -->
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-10 mb-20 flex-row-reverse text-right">
                <div class="text-right">
                     <h1 class="text-4xl font-black text-primary tracking-tight mb-2 uppercase">مخطط التموضع المنطقي (Network Topology Matrix)</h1>
                     <div class="flex items-center gap-4 justify-end">
                        <p class="text-slate-500 font-bold text-sm uppercase tracking-wider">مزامنة مسارات تدفق البيانات، رصد العقد متعددة المستويات، وحوكمة الارتباطات البنيوية</p>
                        <span class="material-symbols-outlined text-primary text-[24px]">GitBranch</span>
                     </div>
                </div>
            </div>

            <!-- Multi-Tier Topology Cluster -->
            <div class="space-y-24 pb-24 relative">
                
                <!-- Advanced Connection Guiding Architecture -->
                <div class="absolute inset-y-0 right-1/2 w-0.5 bg-gradient-to-b from-slate-200 via-primary/20 to-slate-200 translate-x-1/2 hidden lg:block opacity-30"></div>

                <!-- Tier 1: Core Upstream Ingress -->
                <section class="relative z-10">
                    <div class="flex items-center gap-6 mb-12 flex-row-reverse text-right">
                         <div class="w-1.5 h-10 bg-slate-950 rounded-full shadow-2xl"></div>
                        <div class="px-8 py-3 bg-slate-950 text-white rounded-2xl text-[11px] font-black uppercase tracking-[0.2em] shadow-2xl border border-white/5">المستوى 01: بوابات النواة المركزية (Core Ingress)</div>
                        <div class="flex-1 h-px bg-slate-200"></div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                        <div v-for="source in internetSources" :key="source.id" class="surface-card p-10 rounded-3xl border border-outline-variant/5 shadow-2xl hover:scale-[1.05] transition-all bg-white group overflow-hidden">
                             <div class="absolute -top-10 -right-10 w-32 h-32 bg-indigo-500/5 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-1000"></div>
                             <div class="flex items-center gap-6 mb-10 flex-row-reverse text-right relative z-10">
                                <div class="w-16 h-16 rounded-2xl bg-indigo-500/10 text-indigo-600 flex items-center justify-center shadow-inner border border-indigo-500/10 group-hover:bg-indigo-600 group-hover:text-white transition-all">
                                    <span class="material-symbols-outlined text-[32px]">public</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-lg font-black text-primary truncate tracking-tight leading-none group-hover:translate-x-[-4px] transition-transform">{{ source.name }}</h4>
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mt-2 opacity-60">بروتوكول تزويد: {{ source.type }}</p>
                                </div>
                             </div>
                             <div class="flex items-center justify-between pt-8 border-t border-slate-50 flex-row-reverse relative z-10">
                                <div class="flex flex-col items-center gap-1">
                                    <span class="font-headline text-lg font-black text-primary tracking-tighter">{{ source.capacity }} <span class="text-[8px] opacity-40 uppercase">Mbps</span></span>
                                    <span class="text-[7px] font-black text-slate-300 uppercase tracking-widest">سعة النطاق</span>
                                </div>
                                <div class="flex items-center gap-3 flex-row-reverse">
                                    <div class="flex flex-col items-end">
                                        <span class="text-[9px] font-black uppercase tracking-widest leading-none" :class="source.status === 'online' ? 'text-emerald-500' : 'text-rose-500'">{{ source.status === 'online' ? 'بث نشط (LIVE)' : 'منقطع (DOWN)' }}</span>
                                    </div>
                                    <div class="relative flex h-3 w-3">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75" :class="source.status === 'online' ? 'bg-emerald-500' : 'bg-rose-500'"></span>
                                        <span class="relative inline-flex rounded-full h-3 w-3" :class="source.status === 'online' ? 'bg-emerald-500' : 'bg-rose-500'"></span>
                                    </div>
                                </div>
                             </div>
                        </div>
                    </div>
                </section>

                <!-- Pulse Transition Connector -->
                <div class="flex justify-center py-6 text-slate-200 relative z-10">
                    <div class="w-16 h-16 rounded-full bg-white shadow-2xl flex items-center justify-center border border-slate-100 group hover:scale-110 transition-all cursor-default">
                        <span class="material-symbols-outlined text-[32px] animate-bounce text-primary">expand_more</span>
                    </div>
                </div>

                <!-- Tier 2: Extraction GitBranchs (Servers) -->
                <section class="relative z-10">
                    <div class="flex items-center gap-6 mb-12 flex-row-reverse text-right">
                        <div class="w-1.5 h-10 bg-primary rounded-full shadow-2xl"></div>
                        <div class="px-8 py-3 bg-primary text-white rounded-2xl text-[11px] font-black uppercase tracking-[0.2em] shadow-2xl border border-white/10">المستوى 02: عقد المعالجة والتحكم (Extraction GitBranchs)</div>
                        <div class="flex-1 h-px bg-slate-200"></div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12">
                        <div v-for="server in servers" :key="server.id" class="surface-card p-12 rounded-[2.5rem] border border-outline-variant/5 shadow-2xl hover:translate-y-[-8px] transition-all group relative overflow-hidden bg-white">
                             <div class="absolute -top-32 -left-32 w-80 h-80 bg-primary/5 rounded-full blur-[100px] group-hover:scale-150 transition-transform duration-1000"></div>
                             <div class="flex items-center gap-8 mb-12 flex-row-reverse text-right relative z-10">
                                <div class="w-24 h-24 rounded-[2rem] bg-slate-950 text-white flex items-center justify-center transition-all group-hover:rotate-12 shadow-[0_20px_40px_rgba(0,0,0,0.2)] border border-white/10 shrink-0">
                                    <span class="material-symbols-outlined text-[48px]" style="font-variation-settings: 'FILL' 1">terminal</span>
                                </div>
                                <div>
                                    <h4 class="text-2xl font-black text-primary tracking-tight uppercase leading-none mb-3 group-hover:translate-x-[-4px] transition-transform">{{ server.name }}</h4>
                                    <div class="flex items-center gap-3 justify-end opacity-40">
                                        <p class="font-headline text-[11px] font-black tracking-widest">{{ server.ip }}</p>
                                        <span class="material-symbols-outlined text-[16px]">lan</span>
                                    </div>
                                </div>
                             </div>
                             <div class="p-8 bg-slate-50 rounded-3xl border border-slate-100 flex items-center justify-between flex-row-reverse shadow-inner relative z-10 overflow-hidden">
                                <div class="absolute inset-0 bg-primary/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                <div class="flex items-center gap-4 flex-row-reverse relative">
                                    <span class="material-symbols-outlined text-primary text-[24px]">vital_signs</span>
                                    <div class="text-right">
                                        <p class="text-[10px] font-black text-primary uppercase tracking-[0.2em] leading-none">وضعية العقدة</p>
                                        <p class="text-[8px] font-black text-slate-300 uppercase tracking-widest mt-1">OPERATIONAL_PROTOCOL</p>
                                    </div>
                                </div>
                                <div class="px-6 py-2 rounded-xl text-[10px] font-black uppercase tracking-[0.2em] border-2 shadow-sm transition-all" :class="getStatusColor(server.status)">
                                    {{ server.status === 'online' ? 'مستقر (STABLE)' : 'حرج (CRITICAL)' }}
                                </div>
                             </div>
                        </div>
                    </div>
                </section>

                <!-- Pulse Transition Connector -->
                <div class="flex justify-center py-6 text-slate-200 relative z-10">
                    <div class="w-16 h-16 rounded-full bg-white shadow-2xl flex items-center justify-center border border-slate-100 group hover:scale-110 transition-all cursor-default">
                        <span class="material-symbols-outlined text-[32px] animate-bounce text-primary">expand_more</span>
                    </div>
                </div>

                <!-- Tier 3: Distribution Mesh -->
                <section class="relative z-10">
                    <div class="flex items-center gap-6 mb-12 flex-row-reverse text-right">
                         <div class="w-1.5 h-10 bg-slate-950 rounded-full shadow-2xl"></div>
                        <div class="px-8 py-3 bg-slate-950 text-white rounded-2xl text-[11px] font-black uppercase tracking-[0.2em] shadow-2xl border border-white/5">المستوى 03: شبكة النشر الطرفية (Distribution Mesh)</div>
                        <div class="flex-1 h-px bg-slate-200"></div>
                    </div>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-20">
                         <!-- Sites Registry -->
                         <div class="space-y-10 text-right">
                            <div class="flex items-center gap-4 justify-end">
                                <h5 class="text-[11px] font-black text-slate-400 uppercase tracking-[0.3em] leading-none">مواقع البث الرئيسية (Base Sites)</h5>
                                <div class="w-1.5 h-5 bg-slate-300 rounded-full"></div>
                            </div>
                            <div class="space-y-6">
                                <div v-for="tower in towers" :key="tower.id" class="surface-card p-10 border border-outline-variant/5 rounded-3xl flex items-center justify-between hover:translate-x-[-8px] transition-all group flex-row-reverse bg-white shadow-xl">
                                    <div class="flex items-center gap-8 flex-row-reverse text-right">
                                        <div class="w-20 h-20 rounded-[1.5rem] bg-surface-container-low border border-outline-variant/5 text-primary flex items-center justify-center shadow-inner group-hover:bg-primary group-hover:text-white transition-all duration-500 overflow-hidden relative">
                                             <div class="absolute inset-0 bg-primary opacity-5 group-hover:opacity-0 transition-opacity"></div>
                                            <span class="material-symbols-outlined text-[40px] relative z-10">cell_tower</span>
                                        </div>
                                        <div>
                                            <h6 class="text-xl font-black text-primary uppercase tracking-tight mb-3 group-hover:translate-x-[-4px] transition-transform">{{ tower.name }}</h6>
                                            <div class="flex items-center gap-3 justify-end flex-row-reverse">
                                                <span class="text-[10px] font-black text-emerald-500 uppercase tracking-[0.2em] leading-none">بث الإشارة الحية (LIVE)</span>
                                                <div class="relative flex h-2 w-2">
                                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-500 opacity-75"></span>
                                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="material-symbols-outlined text-slate-200 group-hover:translate-x-[-4px] group-hover:text-primary transition-all text-[28px]">arrow_back_ios</span>
                                </div>
                            </div>
                         </div>

                         <!-- Radios Portfolio -->
                         <div class="space-y-10 text-right">
                            <div class="flex items-center gap-4 justify-end">
                                <h5 class="text-[11px] font-black text-slate-400 uppercase tracking-[0.3em] leading-none">عقد النشر الطرفية (Access Radios)</h5>
                                <div class="w-1.5 h-5 bg-slate-300 rounded-full"></div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div v-for="router in routers" :key="router.id" class="surface-card p-8 border border-outline-variant/10 bg-white/50 backdrop-blur-3xl rounded-[2rem] flex flex-col gap-10 group hover:shadow-2xl transition-all border-b-4" :class="router.status === 'online' ? 'border-emerald-500' : 'border-rose-500'">
                                     <div class="flex items-center justify-between flex-row-reverse">
                                          <div class="w-14 h-14 rounded-2xl bg-indigo-500/5 text-indigo-600 flex items-center justify-center shadow-inner group-hover:scale-110 group-hover:rotate-12 transition-all border border-indigo-500/10">
                                               <span class="material-symbols-outlined text-[28px]" style="font-variation-settings: 'FILL' 1">router</span>
                                          </div>
                                          <div class="text-left flex flex-col items-start opacity-40">
                                               <p class="text-[9px] font-black text-primary uppercase tracking-widest leading-none">{{ router.status === 'online' ? 'بث نشط' : 'وضع السكون' }}</p>
                                               <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mt-1 leading-none text-left">PROT_STATE</p>
                                          </div>
                                     </div>
                                     <div class="text-right">
                                         <h6 class="text-base font-black text-primary uppercase tracking-tight truncate mb-3 group-hover:translate-x-[-4px] transition-transform">{{ router.name }}</h6>
                                         <div class="flex items-center gap-2 justify-end opacity-40">
                                            <span class="font-headline text-[10px] font-black tracking-widest">{{ router.ip }}</span>
                                            <span class="material-symbols-outlined text-[14px]">sensors</span>
                                         </div>
                                     </div>
                                </div>
                            </div>
                         </div>
                    </div>
                </section>
            </div>

            <!-- Empty Matrix Awareness -->
            <div v-if="!internetSources.length && !servers.length && !towers.length" class="py-48 flex flex-col items-center justify-center text-center opacity-40 italic">
                <span class="material-symbols-outlined text-[72px] mb-8">topology</span>
                <p class="text-[11px] font-black uppercase tracking-[0.4em]">لا تتوفر مخططات تموضع نشطة حالياً ضمن هذا النطاق السيادي</p>
            </div>
        </div>
    </InstitutionalLayout>
</template>



