<script setup>
import { ref } from 'vue';
import { Link, Head, useForm } from '@inertiajs/vue3';
import InstitutionalLayout from '@/Layouts/InstitutionalLayout.vue';

const props = defineProps({
    client: Object,
    stats: Object,
    usage: Object,
    activeIp: String,
});

const noteForm = useForm({
    content: '',
    type: 'general',
});

const submitNote = () => {
    noteForm.post(route('crm.clients.notes.store', props.client.id), {
        onSuccess: () => noteForm.reset('content'),
        preserveScroll: true,
    });
};

const formatDate = (date) => {
    if (!date) return 'مفتوح (Unlimited)';
    return new Date(date).toLocaleDateString('ar-EG', { year: 'numeric', month: 'short', day: 'numeric' });
};

const formatBytes = (bytes) => {
    if (!bytes || bytes === 0) return '0 GB';
    const gb = bytes / (1024 * 1024 * 1024);
    return gb.toFixed(3) + ' GB';
};
</script>

<template>
    <InstitutionalLayout :title="client.username">
        <Head :title="'الملف الفني للمشترك: ' + client.username" />

        <div class="max-w-7xl mx-auto pb-24 text-right px-4">
            <!-- Institutional Profile Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-8 mb-12 flex-row-reverse text-right">
                <div class="flex items-center gap-8 flex-row-reverse">
                    <div class="text-right">
                        <div class="flex items-center gap-3 justify-end mb-1">
                            <span class="text-[11px] font-black uppercase tracking-[0.3em] text-slate-400">{{ client.name || 'هوية غير معرفة' }}</span>
                            <div class="w-1.5 h-1.5 bg-secondary rounded-full"></div>
                        </div>
                        <h1 class="text-5xl font-black text-primary tracking-tighter leading-none mb-4 uppercase">{{ client.username }}</h1>
                        <div class="flex items-center gap-4 justify-end">
                            <div class="flex items-center gap-2 px-3 py-1 bg-surface-container-low border border-outline-variant/10 rounded text-[10px] font-black text-primary uppercase tracking-widest whitespace-nowrap">
                                <span class="material-symbols-outlined text-[16px]">tower</span>
                                {{ client.mikrotik_server?.name || 'سيرفر الحافة' }}
                            </div>
                            <div class="flex items-center gap-2 px-3 py-1 bg-surface-container-low border border-outline-variant/10 rounded text-[10px] font-black text-primary uppercase tracking-widest whitespace-nowrap">
                                <span class="material-symbols-outlined text-[16px]">shield_person</span>
                                مُعرف العميل: {{ client.id }}
                            </div>
                        </div>
                    </div>
                    <div class="w-24 h-24 rounded-2xl bg-slate-900 flex items-center justify-center text-white border-4 border-white shadow-2xl shrink-0 group hover:rotate-6 transition-transform">
                         <span class="material-symbols-outlined text-[48px]" style="font-variation-settings: 'FILL' 1">person</span>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-4 justify-end">
                    <Link 
                        :href="route('crm.clients.renew', client.id)" 
                        class="px-10 py-5 bg-primary text-white rounded-xl font-black text-[11px] uppercase tracking-[0.2em] shadow-2xl shadow-primary/20 hover:scale-105 active:scale-95 transition-all flex items-center gap-3"
                    >
                        <span class="material-symbols-outlined text-[20px]">refresh</span>
                        تجديد العقد (Renew)
                    </Link>

                    <Link 
                        :href="route('crm.clients.toggle-status', client.id)" 
                        method="patch" 
                        as="button"
                        class="px-6 py-5 rounded-xl font-black text-[11px] uppercase tracking-widest transition-all active:scale-95 border flex items-center gap-3"
                        :class="client.status === 'active' 
                            ? 'bg-rose-50 text-rose-600 border-rose-100 hover:bg-rose-100' 
                            : 'bg-emerald-50 text-emerald-600 border-emerald-100 hover:bg-emerald-100'"
                    >
                        <span class="material-symbols-outlined text-[20px]">{{ client.status === 'active' ? 'block' : 'verified_user' }}</span>
                        {{ client.status === 'active' ? 'تجميد الخدمة' : 'تنشيط الخدمة' }}
                    </Link>

                    <Link 
                        :href="route('crm.clients.edit', client.id)" 
                        class="w-14 h-14 bg-white shadow-sm border border-outline-variant/10 rounded-xl flex items-center justify-center text-slate-400 hover:text-primary transition-all hover:shadow-xl"
                    >
                        <span class="material-symbols-outlined text-[28px]">edit_square</span>
                    </Link>
                </div>
            </div>

            <!-- Strategic Metric Bento -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
                <div class="surface-card p-10 rounded-xl border border-outline-variant/5 shadow-sm">
                    <div class="w-12 h-12 rounded-xl bg-surface-container-low flex items-center justify-center text-primary mb-6 shadow-inner border border-outline-variant/10">
                        <span class="material-symbols-outlined text-[24px]">receipt_long</span>
                    </div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">إجمالي الفواتير الصادرة</p>
                    <div class="text-4xl font-black font-headline tracking-tighter text-primary leading-none">{{ stats.total_invoices }}</div>
                </div>

                <div class="surface-card p-10 rounded-xl border-t-4 border-emerald-500 shadow-sm">
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-6 border border-emerald-100 shadow-sm">
                        <span class="material-symbols-outlined text-[24px]">payments</span>
                    </div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">القيمة المحصلة YTD</p>
                    <div class="flex items-baseline gap-2">
                        <div class="text-4xl font-black font-headline tracking-tighter text-primary leading-none">{{ stats.total_paid.toLocaleString() }}</div>
                        <span class="text-xs font-bold text-slate-300 uppercase font-headline">ر.س</span>
                    </div>
                </div>

                <div class="surface-card p-10 rounded-xl border-t-4 border-rose-500 shadow-sm">
                    <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center mb-6 border border-rose-100 shadow-sm">
                        <span class="material-symbols-outlined text-[24px]">account_balance_wallet</span>
                    </div>
                    <p class="text-[10px] font-black text-rose-400 uppercase tracking-[0.2em] mb-2">المبالغ المعلقة (Debt)</p>
                    <div class="flex items-baseline gap-2">
                        <div class="text-4xl font-black font-headline tracking-tighter text-rose-600 leading-none">{{ stats.pending_amount.toLocaleString() }}</div>
                        <span class="text-xs font-bold text-rose-200 uppercase font-headline">ر.س</span>
                    </div>
                </div>

                <div class="surface-card p-10 rounded-xl bg-primary text-white shadow-2xl shadow-primary/20 relative overflow-hidden group">
                    <div class="absolute -top-10 -left-10 w-32 h-32 bg-white/10 rounded-full blur-3xl group-hover:scale-125 transition-all"></div>
                    <div class="w-12 h-12 rounded-xl bg-white/10 flex items-center justify-center text-white mb-6 border border-white/10 relative z-10 shadow-sm">
                        <span class="material-symbols-outlined text-[24px]">calendar_month</span>
                    </div>
                    <p class="text-[10px] font-black text-white/50 uppercase tracking-[0.2em] mb-2 relative z-10">تاريخ انتهاء الخدمة الوشيك</p>
                    <div class="text-[20px] font-black font-headline tracking-tight text-white relative z-10">{{ formatDate(client.expires_at) }}</div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                <!-- Main Intelligence Suite -->
                <div class="lg:col-span-2 space-y-10">
                    <!-- Data Cluster: Technical DNA -->
                    <div class="surface-card p-10 rounded-xl shadow-sm border border-outline-variant/5">
                        <div class="flex items-center gap-4 mb-10 justify-end">
                            <h3 class="text-xs font-black text-primary uppercase tracking-[0.2em]">البيانات الفنية والربط</h3>
                            <div class="w-1.5 h-6 bg-primary rounded-full"></div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 flex-row-reverse">
                            <div class="p-8 bg-surface-container-low rounded-xl border border-outline-variant/10 text-right group hover:bg-white transition-all">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">معرف الدخول (Username)</p>
                                <p class="text-xl font-black font-headline tracking-tight text-primary leading-none">{{ client.username }}</p>
                            </div>
                            <div class="p-8 bg-surface-container-low rounded-xl border border-outline-variant/10 text-right group hover:bg-white transition-all">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">بروتوكول الخدمة الفعال</p>
                                <div class="flex items-center gap-3 justify-end mt-1">
                                    <span class="px-2.5 py-0.5 bg-primary/10 text-primary rounded text-[10px] font-black uppercase tracking-widest border border-primary/20">{{ client.type }}</span>
                                    <p class="text-[16px] font-black uppercase text-slate-500 leading-none">{{ client.type === 'pppoe' ? 'Direct Access' : 'Wireless Mesh' }}</p>
                                </div>
                            </div>
                            <div class="p-8 bg-surface-container-low rounded-xl border border-outline-variant/10 text-right group hover:bg-white transition-all">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">الباقة الرقمية الحالية</p>
                                <p class="text-[18px] font-black text-primary leading-none">{{ client.package?.name || 'تعرفة مخصصة (Custom)' }}</p>
                            </div>
                            <div class="p-8 bg-surface-container-low rounded-xl border border-outline-variant/10 text-right group hover:bg-white transition-all">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">قناة الاتصال المسجلة</p>
                                <p class="text-[18px] font-black font-headline tracking-widest text-primary leading-none">{{ client.phone || 'إدخال مفقود' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Live Telemetry Monitor -->
                    <div class="surface-card p-10 rounded-xl bg-slate-900 border-2 border-primary/20 text-white relative overflow-hidden group">
                         <div class="absolute inset-0 bg-primary/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                         <div class="flex items-center justify-between mb-12 flex-row-reverse">
                            <div class="flex items-center gap-4 justify-end">
                                <h3 class="text-xs font-black text-white/60 uppercase tracking-[0.2em]">مراقب الاستهلاك اللحظي (Live Status)</h3>
                                <div class="w-1.5 h-6 bg-secondary rounded-full"></div>
                            </div>
                            <div class="flex items-center gap-3 px-4 py-2 rounded-lg bg-white/5 border border-white/10 shadow-inner">
                                 <div class="h-2.5 w-2.5 rounded-full shadow-[0_0_12px_rgba(34,197,94,0.5)]" :class="usage?.status === 'online' ? 'bg-secondary animate-pulse' : 'bg-rose-500'"></div>
                                 <span class="text-[10px] font-black uppercase tracking-widest text-white/80">{{ usage?.status === 'online' ? 'ارتباط نشط (Online)' : 'ارتباط مقطوع' }}</span>
                            </div>
                         </div>
                         
                         <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 flex-row-reverse">
                             <div class="bg-white/5 p-6 rounded-xl border border-white/10 group/item hover:bg-white/10 transition-all text-right">
                                 <p class="text-[9px] font-black text-white/30 uppercase tracking-[0.2em] mb-2">عنوان IP الممنوح</p>
                                 <p class="text-xl font-black font-headline text-white tracking-widest">{{ activeIp || 'Detached' }}</p>
                             </div>
                             <div class="bg-white/5 p-6 rounded-xl border border-white/10 group/item hover:bg-white/10 transition-all text-right">
                                 <p class="text-[9px] font-black text-white/30 uppercase tracking-[0.2em] mb-2">زمن التشغيل (Uptime)</p>
                                 <p class="text-xl font-black font-headline text-secondary tracking-widest leading-none">{{ usage?.uptime || '00:00:00' }}</p>
                             </div>
                             <div class="bg-white/5 p-6 rounded-xl border border-white/10 group/item hover:bg-white/10 transition-all text-right">
                                 <p class="text-[9px] font-black text-white/30 uppercase tracking-[0.2em] mb-2">معدل التحميل (IN)</p>
                                 <p class="text-xl font-black font-headline text-emerald-400 tracking-tight leading-none">{{ usage?.download_speed || 0 }} <span class="text-xs opacity-40">Mb</span></p>
                             </div>
                             <div class="bg-white/5 p-6 rounded-xl border border-white/10 group/item hover:bg-white/10 transition-all text-right">
                                 <p class="text-[9px] font-black text-white/30 uppercase tracking-[0.2em] mb-2">معدل الرفع (OUT)</p>
                                 <p class="text-xl font-black font-headline text-indigo-400 tracking-tight leading-none">{{ usage?.upload_speed || 0 }} <span class="text-xs opacity-40">Mb</span></p>
                             </div>
                         </div>

                         <div v-if="usage?.status === 'online'" class="mt-12 pt-8 border-t border-white/10 flex flex-col md:flex-row justify-between items-center gap-8 flex-row-reverse">
                             <div class="text-right">
                                 <p class="text-[10px] font-black uppercase tracking-[0.2em] text-white/40">استهلاك البيانات المجمع (Session)</p>
                                 <div class="flex items-center gap-4 justify-end mt-2">
                                     <div class="text-sm font-black text-white leading-none">
                                         <span class="text-emerald-400 font-headline ml-1">{{ formatBytes(usage?.bytes_out) }}</span>
                                         <span class="text-[9px] text-white/30 uppercase font-bold">IN</span>
                                     </div>
                                     <div class="w-px h-4 bg-white/10"></div>
                                     <div class="text-sm font-black text-white leading-none">
                                         <span class="text-indigo-400 font-headline ml-1">{{ formatBytes(usage?.bytes_in) }}</span>
                                         <span class="text-[9px] text-white/30 uppercase font-bold">OUT</span>
                                     </div>
                                 </div>
                             </div>
                             <a v-if="client.type === 'pppoe' && activeIp" :href="route('crm.clients.cpe-proxy', { client: client.id, type: 'pppoe' })" target="_blank" class="px-8 py-4 bg-white text-slate-900 text-[10px] font-black uppercase tracking-[0.2em] rounded-lg transition-all flex items-center gap-3 hover:scale-105 active:scale-95 shadow-2xl">
                                <span class="material-symbols-outlined text-[20px]">terminal</span>
                                حقن بروتوكول PPPoE (Console)
                             </a>
                         </div>
                    </div>

                    <!-- Topology Cluster: Client Hardware -->
                    <div class="surface-card p-10 rounded-xl shadow-sm border border-outline-variant/10">
                        <div class="flex items-center gap-4 mb-12 justify-end">
                            <h3 class="text-xs font-black text-primary uppercase tracking-[0.2em]">توصيف العقد والعتاد (Edge Nodes)</h3>
                            <div class="w-1.5 h-6 bg-slate-900 rounded-full"></div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 flex-row-reverse">
                            <!-- Internal Gateway (CPE) -->
                            <div class="space-y-6 text-right">
                                <div class="flex items-center gap-3 justify-end text-[11px] font-black text-slate-400 uppercase tracking-widest">
                                    البوابة الداخلية (Internal CPE)
                                    <span class="material-symbols-outlined text-primary text-[20px]">router</span>
                                </div>
                                <div class="p-8 bg-surface-container-low rounded-xl border border-outline-variant/10 group hover:bg-white transition-all shadow-inner">
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2">عنوان الرابط المحلي (Local IP)</p>
                                    <p class="text-2xl font-black font-headline text-primary tracking-widest">{{ client.cpe_ip || 'Detached Node' }}</p>
                                    
                                    <div v-if="client.cpe_ip" class="mt-8 pt-6 border-t border-outline-variant/10 flex items-center justify-between flex-row-reverse">
                                        <div class="text-right">
                                            <p class="text-[11px] font-black text-slate-700 uppercase leading-none">{{ client.cpe_username || 'ADMIN_AUTH' }}</p>
                                            <p class="text-[9px] font-black text-slate-400 tracking-[0.2em] mt-1.5 uppercase">بروتوكول تحكم ميكروتيك</p>
                                        </div>
                                        <a 
                                            :href="route('crm.clients.cpe-proxy', { client: client.id, type: 'cpe' })" 
                                            target="_blank"
                                            class="px-5 py-2.5 bg-white border border-outline-variant/10 text-primary text-[10px] font-black uppercase tracking-widest rounded-lg hover:shadow-lg transition-all flex items-center gap-3"
                                        >
                                            <span class="material-symbols-outlined text-[18px]">settings_accessibility</span>
                                            Webfig Console
                                        </a>
                                    </div>
                                    <div v-else class="mt-8 py-6 text-center border-2 border-dashed border-outline-variant/20 rounded-xl bg-surface-container-low/50">
                                        <p class="text-[10px] font-black text-slate-300 uppercase tracking-widest italic leading-relaxed">لا يوجد ارتباط بوابة داخلي مسجل</p>
                                    </div>
                                </div>
                            </div>

                            <!-- External Antenna (Radio) -->
                            <div class="space-y-6 text-right">
                                <div class="flex items-center gap-3 justify-end text-[11px] font-black text-slate-400 uppercase tracking-widest">
                                    راديو الاستقبال (Edge Radio)
                                    <span class="material-symbols-outlined text-secondary text-[20px]">sensors</span>
                                </div>
                                <div class="p-8 bg-surface-container-low rounded-xl border border-outline-variant/10 group hover:bg-white transition-all shadow-inner">
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2">عنوان الارتباط الهوائي (Air IP)</p>
                                    <p class="text-2xl font-black font-headline text-secondary tracking-widest">{{ client.receiver_ip || 'Offline Link' }}</p>

                                    <div v-if="client.receiver_ip" class="mt-8 pt-6 border-t border-outline-variant/10 flex items-center justify-between flex-row-reverse">
                                        <div class="text-right">
                                            <p class="text-[11px] font-black text-slate-700 uppercase leading-none">{{ client.receiver_username || 'RADIO_AUTH' }}</p>
                                            <p class="text-[9px] font-black text-slate-400 tracking-[0.2em] mt-1.5 uppercase">عقدة استقبال SXT/LHG</p>
                                        </div>
                                        <a 
                                            :href="route('crm.clients.cpe-proxy', { client: client.id, type: 'receiver' })" 
                                            target="_blank"
                                            class="px-5 py-2.5 bg-secondary text-white text-[10px] font-black uppercase tracking-widest rounded-lg hover:bg-secondary-container transition-all flex items-center gap-3 shadow-xl shadow-secondary/20"
                                        >
                                            <span class="material-symbols-outlined text-[18px]">cell_tower</span>
                                             Radio Pulse View
                                        </a>
                                    </div>
                                    <div v-else class="mt-8 py-6 text-center border-2 border-dashed border-outline-variant/20 rounded-xl bg-surface-container-low/50">
                                        <p class="text-[10px] font-black text-slate-300 uppercase tracking-widest italic leading-relaxed">لم يتم رصد معدات استقبال خارجية</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Side Technical Intelligence -->
                <div class="space-y-10">
                    <!-- Activity Ledger (Live Stream) -->
                    <div class="surface-card p-8 rounded-xl border border-outline-variant/5 shadow-sm overflow-hidden">
                        <h3 class="text-xs font-black text-primary uppercase tracking-[0.2em] mb-10 flex items-center justify-end gap-3">
                            تاريخ النشاط اللحظي (Vitals)
                            <span class="material-symbols-outlined text-primary text-[20px]">dynamic_feed</span>
                        </h3>
                        <div class="space-y-10 relative before:absolute before:right-[5.5px] before:top-2 before:bottom-0 before:w-px before:bg-outline-variant/15 pr-8">
                            <div v-for="activity in client.activities" :key="activity.id" class="relative group">
                                <div class="absolute -right-[32px] top-1.5 w-2.5 h-2.5 rounded-full border-2 border-white bg-primary shadow-sm z-10 transition-transform group-hover:scale-150"></div>
                                <p class="text-[13px] font-bold text-slate-700 leading-relaxed mb-2 text-right">{{ activity.description }}</p>
                                <div class="flex items-center justify-end gap-2 text-[9px] font-black uppercase tracking-widest text-slate-400 font-headline">
                                    {{ activity.created_at_human || 'Just now' }}
                                    <span class="material-symbols-outlined text-[12px]">schedule</span>
                                </div>
                            </div>

                             <div v-if="!client.activities?.length" class="text-center py-16 opacity-30">
                                <span class="material-symbols-outlined text-slate-200 text-[64px] mb-4">history_toggle_off</span>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em]">لم يتم العثور على أي بيانات تطابق معايير البحث المحددة.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Intelligence Logs (Admin Comments) -->
                    <div class="surface-card p-8 rounded-xl border-t-4 border-slate-900 shadow-sm">
                        <h3 class="text-xs font-black text-primary uppercase tracking-[0.2em] mb-8 flex items-center justify-end gap-3">
                            ملاحظات الإدارة والفنيين
                            <span class="material-symbols-outlined text-primary text-[20px]">sticky_note_2</span>
                        </h3>
                        
                        <form @submit.prevent="submitNote" class="space-y-5">
                            <div class="relative">
                                <select 
                                    v-model="noteForm.type"
                                    class="w-full h-12 pr-10 pl-4 bg-surface-container-low rounded-lg text-[10px] font-black border-none appearance-none focus:ring-2 focus:ring-primary text-slate-700 uppercase tracking-widest shadow-inner"
                                >
                                    <option value="general">ملاحظة عامة (General)</option>
                                    <option value="technical">بلاغ فني (Tech Issue)</option>
                                    <option value="billing">متابعة مالية (Billing)</option>
                                </select>
                                <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-[20px]">label</span>
                            </div>
                            <textarea 
                                v-model="noteForm.content"
                                placeholder="..."
                                rows="3"
                                class="w-full p-5 bg-surface-container-low rounded-xl text-xs font-bold border-none outline-none focus:ring-2 focus:ring-primary text-slate-800 resize-none shadow-inner text-right"
                            ></textarea>
                            <button 
                                type="submit" 
                                :disabled="noteForm.processing || !noteForm.content"
                                class="w-full h-14 bg-slate-900 text-white rounded-xl font-black text-[10px] uppercase tracking-[0.2em] disabled:opacity-50 transition-all active:scale-95 shadow-2xl"
                            >
                                حفظ الملاحظة في السجل
                            </button>
                        </form>

                        <div class="mt-12 space-y-6">
                            <div v-for="note in client.client_notes" :key="note.id" class="p-6 bg-white rounded-xl border border-outline-variant/10 shadow-sm relative group overflow-hidden text-right">
                                <div class="absolute inset-y-0 right-0 w-1.5 bg-primary transform translate-x-full group-hover:translate-x-0 transition-transform duration-500"></div>
                                <p class="text-[13px] font-bold text-slate-800 leading-relaxed mb-5">{{ note.content }}</p>
                                <div class="flex items-center justify-between text-[9px] font-black uppercase tracking-widest border-t border-outline-variant/5 pt-4">
                                    <span class="font-headline font-extrabold text-slate-400">{{ note.created_at_human || 'الآن' }}</span>
                                    <span :class="note.type === 'technical' ? 'bg-indigo-50 text-indigo-600 border-indigo-100' : (note.type === 'billing' ? 'bg-amber-50 text-amber-600 border-amber-100' : 'bg-slate-50 text-slate-500 border-slate-100')" class="px-2 py-0.5 rounded border flex items-center gap-1.5">
                                        <span class="material-symbols-outlined text-[14px]">sell</span>
                                        {{ note.type }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </InstitutionalLayout>
</template>

<style scoped>
.font-headline {
    font-family: 'Manrope', sans-serif;
}
</style>
