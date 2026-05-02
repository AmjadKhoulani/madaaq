<script setup>
import { routerHead } from '@inertiajs/vue3';
import { ref } from 'vue';
import {  } from 'lucide-vue-next';;
import InstitutionalLayout from '@/Layouts/InstitutionalLayout.vue';
import axios from 'axios';

const props = defineProps({
    servers: Array,
});

const selectedServer = ref('');
const command = ref('');
const isExecuting = ref(false);
const history = ref([]);

const executeCommand = async () => {
    if (!selectedServer.value) return;
    
    isExecuting.value = true;
    const currentCommand = command.value;
    
    try {
        const response = await axios.post(route('network.commands.execute'), {
            server_id: selectedServer.value,
            command: currentCommand
        });
        
        history.value.unshift({
            id: Date.now(),
            server: response.data.server || 'CORE_NODE',
            command: currentCommand,
            output: response.data.output,
            success: response.data.success,
            message: response.data.message,
            timestamp: new Date().toLocaleTimeString('ar-SY', { hour12: false })
        });
        
        if (response.data.success) {
            command.value = '';
        }
    } catch (error) {
        history.value.unshift({
            id: Date.now(),
            server: 'ERR_PROTOCOL',
            command: currentCommand,
            output: 'خطأ في اصطدام البروتوكول (Network Conflict): ' + (error.response?.data?.message || error.message),
            success: false,
            timestamp: new Date().toLocaleTimeString('ar-SY', { hour12: false })
        });
    } finally {
        isExecuting.value = false;
    }
};

const clearHistory = () => {
    history.value = [];
};

</script>

<template>
    <InstitutionalLayout title="وحدة التحكم السيادية">
        <Head title="منصة حقن البروتوكولات (Terminal) - MadaaQ" />

        <div class="max-w-7xl mx-auto pb-24 text-right px-4" dir="rtl">
            <!-- Strategic Header Intel -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-10 mb-16 flex-row-reverse text-right">
                <div class="text-right">
                    <h1 class="text-4xl font-black text-primary tracking-tight mb-2 uppercase">منصة حقن واستخراج البروتوكولات (Core Console)</h1>
                    <div class="flex items-center gap-4 justify-end">
                        <p class="text-slate-500 font-bold text-sm uppercase tracking-wider">الاستخراج اللحظي للبيانات، إدارة العقد عبر الـ <span class="text-primary">API</span>، وحوكمة الأوامر السيادية</p>
                        <span class="flex h-3 w-3 relative">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-secondary opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-secondary"></span>
                        </span>
                    </div>
                </div>
                <button 
                    @click="clearHistory"
                    v-if="history.length > 0"
                    class="px-10 py-5 bg-white text-slate-400 border border-outline-variant/10 rounded-xl font-black text-[11px] uppercase tracking-[0.2em] shadow-2xl hover:text-rose-600 hover:border-rose-200 transition-all flex items-center gap-4 active:scale-95"
                >
                    <span class="material-symbols-outlined text-[20px]">terminal_off</span> تصفير سجل الجلسة
                </button>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 text-right">
                <!-- 1. Extraction Control Panel -->
                <div class="lg:col-span-1 space-y-10">
                    <div class="surface-card p-10 rounded-3xl border border-outline-variant/5 shadow-2xl bg-white relative overflow-hidden">
                        <div class="flex items-center gap-4 mb-12 justify-end">
                            <h3 class="text-xs font-black text-primary uppercase tracking-[0.3em] leading-none">مدخلات التحكم (Input Console)</h3>
                            <div class="w-1.5 h-6 bg-primary rounded-full"></div>
                        </div>

                        <div class="space-y-10">
                            <div class="space-y-4">
                                <label class="text-[11px] font-black text-primary uppercase tracking-widest mr-2 leading-none">العقدة الطرفية المستهدفة (Target Node)</label>
                                <div class="relative group">
                                    <span class="material-symbols-outlined absolute right-6 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-primary transition-colors text-[24px]">dns</span>
                                    <select v-model="selectedServer" class="form-input-monolith h-16 pr-16 font-black text-lg tracking-tight appearance-none cursor-pointer">
                                        <option value="" disabled>تحديد العقدة النشطة...</option>
                                        <option v-for="s in servers" :key="s.id" :value="s.id">{{ s.name }}</option>
                                    </select>
                                    <span class="material-symbols-outlined absolute left-6 top-1/2 -translate-y-1/2 text-slate-300 pointer-events-none group-focus-within:rotate-180 transition-transform">expand_more</span>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <label class="text-[11px] font-black text-primary uppercase tracking-widest mr-2 leading-none">توجيه بروتوكول API (Routing Command)</label>
                                <div class="relative group">
                                     <textarea 
                                        v-model="command"
                                        @keydown.enter.ctrl.prevent="executeCommand"
                                        class="form-input-monolith py-8 h-40 font-headline font-black text-lg tracking-widest leading-relaxed text-left border-2 focus:ring-primary/20 shadow-inner" 
                                        placeholder="/system/identity/print"
                                        dir="ltr"
                                     ></textarea>
                                     <div class="absolute bottom-4 right-6 flex items-center gap-2 opacity-30 select-none">
                                         <span class="text-[9px] font-black uppercase tracking-widest">PULSE_READY</span>
                                         <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                                     </div>
                                </div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mr-2 italic">نظام الاختصار: <span class="text-primary">CTRL + ENTER</span> لحقن البروتوكول الفوري</p>
                            </div>

                            <button 
                                @click="executeCommand"
                                class="w-full py-6 bg-primary text-white rounded-2xl font-black text-[12px] uppercase tracking-[0.3em] shadow-2xl shadow-primary/30 hover:bg-emerald-600 transition-all active:scale-95 disabled:opacity-50 flex items-center justify-center gap-5 border border-white/10 group"
                                :disabled="isExecuting || !selectedServer || !command"
                            >
                                <span v-if="!isExecuting" class="material-symbols-outlined text-[24px] group-hover:translate-x-[-4px] transition-transform">send_money</span>
                                <span v-else class="material-symbols-outlined text-[24px] animate-spin">rebase</span>
                                {{ isExecuting ? 'جاري تنفيذ المسار البرمجي...' : 'حقـن بروتوكول الأوامر' }}
                            </button>
                        </div>
                    </div>

                    <!-- Terminal Resilience Stats -->
                    <div class="surface-card p-10 bg-slate-950 text-white rounded-[2rem] relative overflow-hidden group shadow-2xl border border-white/5">
                        <div class="absolute -top-16 -left-16 w-64 h-64 bg-primary/10 rounded-full blur-3xl group-hover:scale-150 transition-all duration-1000"></div>
                        <div class="relative z-10 flex flex-col gap-8 text-right">
                            <div class="w-16 h-16 rounded-2xl bg-white/5 flex items-center justify-center border border-white/10 shadow-inner">
                                <span class="material-symbols-outlined text-[32px] text-amber-500" style="font-variation-settings: 'FILL' 1">Shield</span>
                            </div>
                            <div>
                                <h4 class="text-lg font-black uppercase tracking-tight leading-none mb-3">نزاهة الـ API القطبية</h4>
                                <p class="text-[11px] font-black text-white/30 uppercase tracking-[0.3em] leading-relaxed">تعتمد وحدة التحكم على بروتوكول RouterOS API المباشر. تأكد من بناء الأوامر بدقة لضمان استقرار العقد (Nodes).</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Extraction Stream Ledger (Output Cluster) -->
                <div class="lg:col-span-2 space-y-10">
                    <div v-for="entry in history" :key="entry.id" class="surface-card rounded-[2rem] overflow-hidden transition-all animate-in slide-in-from-top duration-700 bg-white shadow-2xl border border-outline-variant/5 group">
                        <!-- Cluster Header -->
                        <div class="px-10 py-6 border-b border-outline-variant/5 flex items-center justify-between flex-row-reverse" :class="entry.success ? 'bg-emerald-500/5' : 'bg-rose-500/5'">
                            <div class="flex items-center gap-6 flex-row-reverse">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white shadow-2xl group-hover:scale-110 transition-transform" :class="entry.success ? 'bg-emerald-500' : 'bg-rose-500'">
                                    <span class="material-symbols-outlined text-[24px]">{{ entry.success ? 'verified_user' : 'report' }}</span>
                                </div>
                                <div class="text-right">
                                    <p class="text-[12px] font-black text-primary uppercase tracking-widest flex items-center gap-3 flex-row-reverse">
                                        {{ entry.server }} <span class="block w-1 h-4 bg-outline-variant opacity-20"></span> <span class="font-headline font-black text-slate-400 bg-slate-100 px-3 py-1 rounded-lg border border-slate-200">{{ entry.command }}</span>
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 text-left">
                                <span class="text-[10px] font-headline font-black text-slate-400 tracking-widest">{{ entry.timestamp }}</span>
                                <span class="material-symbols-outlined text-[16px] text-slate-300">schedule</span>
                            </div>
                        </div>
                        <!-- High-Density Terminal Output -->
                        <div class="p-10 bg-slate-950 text-emerald-400 font-headline font-black text-[14px] leading-loose overflow-x-auto whitespace-pre custom-scrollbar max-h-[500px] text-left border-t-4 border-slate-900 shadow-inner" dir="ltr">
                            <span class="text-white/20 select-none mr-4">$</span>{{ entry.message || entry.output }}
                        </div>
                        <!-- Audit Footer -->
                        <div class="px-10 py-4 bg-slate-900 flex justify-between items-center text-[9px] font-black uppercase tracking-[0.3em] flex-row-reverse">
                             <div class="flex items-center gap-2 text-primary">
                                 <span class="w-1.5 h-1.5 bg-current rounded-full"></span>
                                 <span>PROTOCOL_EXTRACTED_SUCCESSFULLY</span>
                             </div>
                             <div class="text-white/20">MadaaQ Command Cluster v2.0</div>
                        </div>
                    </div>

                    <!-- Operational Standby Protocol (Empty State) -->
                    <div v-if="history.length === 0" class="surface-card py-48 flex flex-col items-center justify-center text-center rounded-[3rem] border-4 border-dashed border-outline-variant/10 bg-slate-50 shadow-inner opacity-80 group/empty">
                        <div class="w-32 h-32 rounded-[2.5rem] bg-white flex items-center justify-center mb-10 text-slate-200 shadow-2xl border border-outline-variant/5 group-hover/empty:scale-110 transition-all duration-1000">
                            <span class="material-symbols-outlined text-[72px]" style="font-variation-settings: 'wght' 100">terminal</span>
                        </div>
                        <h4 class="text-3xl font-black text-primary uppercase tracking-tight mb-4">وحدة التحكم في وضع الاستعداد</h4>
                        <p class="text-[12px] font-black text-slate-400 uppercase tracking-[0.3em] max-w-sm mx-auto leading-relaxed italic">بوابة حقن البروتوكولات جاهزة للعمليات. قم بتوجيه أمر (API) لعرض مخرجات الاستجابة اللحظية للعقد.</p>
                    </div>
                </div>
            </div>
        </div>
    </InstitutionalLayout>
</template>




