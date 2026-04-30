<script setup>
import { Head } from '@inertiajs/vue3';
import InstitutionalLayout from '@/Layouts/InstitutionalLayout.vue';
import { ref } from 'vue';

const props = defineProps({
    routers: Array,
    clients: Array,
    towers: Array
});

const isFullscreen = ref(false);
const activeFilter = ref('all');
const selectedNode = ref(null);

</script>

<template>
    <InstitutionalLayout title="توزيع الشبكة الجغرافي">
        <Head title="خارطة الطوبولوجيا والبنية التحتية" />

        <div class="max-w-7xl mx-auto pb-24 text-right px-4">
            <!-- Institutional Header -->
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-8 mb-12">
                <div>
                     <h1 class="text-3xl font-black text-primary tracking-tight mb-2">التوزيع الجغرافي للمواقع</h1>
                     <div class="flex items-center gap-3 justify-end">
                        <span class="material-symbols-outlined text-[20px] text-indigo-500">travel_explore</span>
                        <p class="text-slate-500 font-bold text-sm uppercase tracking-wider">تحليل انتشار البنية التحتية وقطاعات البث لاسلكي</p>
                     </div>
                </div>
                <div class="flex items-center gap-4">
                     <div class="flex bg-surface-container-low p-1 rounded-lg border border-outline-variant/10">
                          <button @click="activeFilter = 'all'" class="px-6 py-2 rounded text-[10px] font-black uppercase tracking-widest transition-all" :class="activeFilter === 'all' ? 'bg-white text-primary shadow-sm' : 'text-slate-400 hover:text-slate-600'">كافة الطبقات</button>
                          <button @click="activeFilter = 'towers'" class="px-6 py-2 rounded text-[10px] font-black uppercase tracking-widest transition-all" :class="activeFilter === 'towers' ? 'bg-white text-primary shadow-sm' : 'text-slate-400 hover:text-slate-600'">المواقع</button>
                          <button @click="activeFilter = 'clients'" class="px-6 py-2 rounded text-[10px] font-black uppercase tracking-widest transition-all" :class="activeFilter === 'clients' ? 'bg-white text-primary shadow-sm' : 'text-slate-400 hover:text-slate-600'">المشتركين</button>
                     </div>
                </div>
            </div>

            <!-- Monolith Strategic Map Artifact -->
            <div class="relative surface-card overflow-hidden bg-slate-900 shadow-2xl transition-all group/map border-none" 
                 :class="isFullscreen ? 'fixed inset-4 z-[99] rounded-2xl ring-1 ring-white/10' : 'h-[750px] rounded-2xl'">
                 
                 <!-- Simulated Digital Sandbox Placeholder -->
                 <div class="absolute inset-0 bg-[#0a111a] flex items-center justify-center">
                      <div class="absolute inset-0 opacity-[0.05] pointer-events-none" style="background-image: radial-gradient(circle at 1px 1px, white 1px, transparent 0); background-size: 50px 50px;"></div>
                      
                      <!-- Visual Grid Pulse -->
                      <div class="relative flex flex-col items-center gap-8 opacity-20 group-hover/map:opacity-40 transition-all duration-1000">
                           <span class="material-symbols-outlined text-[120px] text-primary italic animate-pulse" style="font-variation-settings: 'FILL' 0, 'wght' 100">language</span>
                           <h3 class="text-base font-black text-white uppercase tracking-[1em] mr-[1em]">جاري استخراج البيانات الجغرافية...</h3>
                      </div>

                      <!-- Glass Control Pods - Top Left -->
                      <div class="absolute top-8 left-8 flex flex-col gap-4">
                           <button class="w-12 h-12 bg-white/10 backdrop-blur-3xl border border-white/10 rounded-lg flex items-center justify-center text-white shadow-2xl hover:bg-white/20 hover:scale-110 active:scale-95 transition-all">
                               <span class="material-symbols-outlined text-[24px]">layers</span>
                           </button>
                           <button @click="isFullscreen = !isFullscreen" class="w-12 h-12 bg-white/10 backdrop-blur-3xl border border-white/10 rounded-lg flex items-center justify-center text-white shadow-2xl hover:bg-white/20 hover:scale-110 active:scale-95 transition-all">
                               <span class="material-symbols-outlined text-[24px]">{{ isFullscreen ? 'fullscreen_exit' : 'fullscreen' }}</span>
                           </button>
                      </div>

                      <!-- Tactical Telemetry Deck - Bottom -->
                      <div class="absolute bottom-8 left-8 right-8 flex items-end justify-between pointer-events-none">
                           <div class="flex gap-4 pointer-events-auto">
                                <div class="bg-slate-900/80 backdrop-blur-3xl border border-white/5 p-6 rounded-xl flex flex-col gap-1 pr-12 min-w-[160px]">
                                    <p class="text-[9px] font-black text-white/40 uppercase tracking-widest text-right">إجمالي المواقع</p>
                                    <h4 class="text-3xl font-black font-headline text-white text-right leading-none">{{ towers.length }}</h4>
                                </div>
                                <div class="bg-slate-900/80 backdrop-blur-3xl border border-white/5 p-6 rounded-xl flex flex-col gap-1 pr-12 min-w-[160px]">
                                    <p class="text-[9px] font-black text-white/40 uppercase tracking-widest text-right">محطات المشتركين</p>
                                    <h4 class="text-3xl font-black font-headline text-white text-right leading-none">{{ clients.length }}</h4>
                                </div>
                                <div class="bg-slate-900/80 backdrop-blur-3xl border border-white/5 p-6 rounded-xl flex flex-col gap-1 pr-12 min-w-[160px]">
                                    <p class="text-[9px] font-black text-white/40 uppercase tracking-widest text-right">عقد الراديو</p>
                                    <h4 class="text-3xl font-black font-headline text-white text-right leading-none">{{ routers.length }}</h4>
                                </div>
                           </div>
                           
                           <!-- Signal Pulse Legend -->
                           <div class="bg-slate-900/90 backdrop-blur-3xl border border-white/5 p-6 rounded-xl flex items-center gap-8 pointer-events-auto shadow-2xl">
                                <div class="flex items-center gap-3">
                                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 shadow-[0_0_15px_rgba(16,185,129,0.5)]"></span>
                                    <span class="text-[10px] font-black text-white uppercase tracking-widest">تشغيلي</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="w-2.5 h-2.5 rounded-full bg-indigo-500"></span>
                                    <span class="text-[10px] font-black text-white uppercase tracking-widest">أبراج رئيسية</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                                    <span class="text-[10px] font-black text-white uppercase tracking-widest">أعطال نشطة</span>
                                </div>
                           </div>
                      </div>

                      <!-- Node Identity Reveal - Top Right -->
                      <div class="absolute top-8 right-8 w-80 bg-slate-900/90 backdrop-blur-3xl border border-white/5 p-8 rounded-xl shadow-2xl animate-in slide-in-from-right duration-500">
                           <h3 class="text-xs font-black text-white uppercase tracking-widest mb-6 flex items-center gap-3 justify-end">
                                <span class="text-emerald-500 material-symbols-outlined text-[20px]">BarChart3</span> بيانات العقدة
                           </h3>
                           <div class="flex flex-col items-center text-center py-10 opacity-20 grayscale">
                                <span class="material-symbols-outlined text-[64px] text-white" style="font-variation-settings: 'wght' 100">sensors</span>
                                <p class="text-[10px] font-black text-white uppercase tracking-widest mt-4">لم يتم تحديد عقدة</p>
                           </div>
                            <div class="mt-4 pt-8 border-t border-white/5 text-right">
                                <p class="text-[10px] font-black text-white/30 uppercase tracking-widest">تعليمات التشغيل</p>
                                <p class="text-[11px] font-bold text-white/60 leading-relaxed mt-2 italic">اختر نقطة انتشار مادية لبدء استخراج بيانات التشغيل والربط.</p>
                            </div>
                      </div>
                 </div>

                 <!-- Tactical Overlay Instructions -->
                 <div class="absolute inset-0 pointer-events-none group-hover/map:bg-white/[0.02] transition-all duration-700"></div>
            </div>

            <!-- Secondary BarChart3 Matrix -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mt-12">
                 <div class="surface-card p-8 flex items-center gap-6 group hover:scale-[1.02] transition-all rounded-xl">
                      <div class="w-16 h-16 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center transition-all group-hover:scale-110 shadow-inner border border-indigo-100/50">
                           <span class="material-symbols-outlined text-[32px]">signal_cellular_alt</span>
                      </div>
                      <div class="text-right">
                           <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 leading-none">كثافة الانتشار</p>
                           <h4 class="text-xl font-black text-primary leading-tight">تجميع عالي الكثافة</h4>
                      </div>
                 </div>
                 
                 <div class="surface-card p-8 flex items-center gap-6 group hover:scale-[1.02] transition-all rounded-xl border-r-4 border-secondary">
                      <div class="w-16 h-16 rounded-2xl bg-secondary-container/10 text-secondary flex items-center justify-center transition-all group-hover:scale-110 shadow-inner border border-secondary-container/20">
                           <span class="material-symbols-outlined text-[32px]">bolt</span>
                      </div>
                      <div class="text-right">
                           <p class="text-[10px] font-black text-secondary uppercase tracking-widest mb-1.5 leading-none">استقرار مزامنة الشبكة</p>
                           <h4 class="text-xl font-black text-primary leading-tight">99.98% استقرار تام</h4>
                      </div>
                 </div>

                 <div class="bg-primary text-white p-8 flex items-center justify-between group hover:scale-[1.02] transition-all overflow-hidden relative rounded-xl shadow-xl shadow-primary/20">
                      <div class="absolute -right-8 -top-8 w-24 h-24 bg-white/5 rounded-full blur-3xl group-hover:bg-white/10 transition-all"></div>
                      <div class="flex items-center gap-6 relative z-10">
                           <div class="w-16 h-16 rounded-2xl bg-white/10 flex items-center justify-center border border-white/10 shadow-inner">
                                <span class="material-symbols-outlined text-[32px]">Users</span>
                           </div>
                           <div class="text-right">
                                <p class="text-[10px] font-black text-white/40 uppercase tracking-widest mb-1.5 leading-none">تغطية المشتركين</p>
                                <h4 class="text-xl font-black text-white leading-tight font-headline tracking-tight">{{ clients.length }} محطة مسجلة</h4>
                           </div>
                      </div>
                      <span class="material-symbols-outlined text-white/20 group-hover:text-white transition-all transform group-hover:translate-x-[-8px]">arrow_back</span>
                 </div>
            </div>
        </div>
    </InstitutionalLayout>
</template>



