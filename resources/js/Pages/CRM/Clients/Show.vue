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

const copyToClipboard = () => {
    const portalUrl = 'http://' + props.client.tenant?.domain;
    const text = `Portal URL: ${portalUrl}\nUsername: ${props.client.username}`;
    
    navigator.clipboard.writeText(text).then(() => {
        alert('تم نسخ بيانات الدخول إلى الحافظة!');
    });
};

const formatDate = (date) => {
    if (!date) return 'دائم';
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
        <Head :title="'ملف المشترك: ' + client.username" />

        <!-- Profile Control Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-8 mb-10">
            <div class="flex items-center gap-6">
                <Link 
                    :href="route('crm.clients.index')" 
                    class="w-12 h-12 bg-white shadow-sm border border-outline-variant/10 rounded-lg flex items-center justify-center text-slate-400 hover:text-primary transition-all group"
                >
                    <span class="material-symbols-outlined text-[24px] group-hover:translate-x-1 transition-transform">arrow_forward</span>
                </Link>
                <div>
                    <h1 class="text-4xl font-black text-primary tracking-tight mb-1">{{ client.username }}</h1>
                    <div class="flex items-center gap-3">
                        <span class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">{{ client.name || 'مشترك عام' }}</span>
                        <div class="h-1.5 w-1.5 bg-outline-variant rounded-full"></div>
                        <span class="text-[10px] font-black uppercase tracking-[0.2em] text-primary">{{ client.mikrotik_server?.name || 'سيرفر الحافة' }}</span>
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-4">
                <Link 
                    :href="route('crm.clients.renew', client.id)" 
                    class="inline-flex items-center gap-3 px-8 py-3.5 bg-primary text-white rounded-lg font-bold shadow-xl shadow-primary/20 hover:bg-primary-container transition-all active:scale-95"
                >
                    <span class="material-symbols-outlined text-[20px]">refresh</span>
                    <span class="text-sm">تجديد العقد</span>
                </Link>

                <div class="h-10 w-px bg-outline-variant/20 mx-2 hidden md:block"></div>

                <Link 
                    :href="route('crm.clients.toggle-status', client.id)" 
                    method="patch" 
                    as="button"
                    class="px-6 py-3.5 rounded-lg font-bold text-[11px] uppercase tracking-widest transition-all active:scale-95 border flex items-center gap-2"
                    :class="client.status === 'active' 
                        ? 'bg-error-container/10 text-error border-error-container/20 hover:bg-error-container/20' 
                        : 'bg-secondary-container/20 text-on-secondary-container border-secondary-container/30 hover:bg-secondary-container/30'"
                >
                    <span class="material-symbols-outlined text-[20px]">{{ client.status === 'active' ? 'block' : 'verified_user' }}</span>
                    {{ client.status === 'active' ? 'تجميد الحساب' : 'تنشيط الحساب' }}
                </Link>

                <Link 
                    :href="route('crm.clients.edit', client.id)" 
                    class="w-12 h-12 bg-white shadow-sm border border-outline-variant/10 rounded-lg flex items-center justify-center text-slate-400 hover:text-primary transition-all shadow-lg"
                >
                    <span class="material-symbols-outlined text-[24px]">edit</span>
                </Link>
            </div>
        </div>

        <!-- Metric Command Center -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
            <div class="surface-card p-6 rounded-lg">
                <div class="w-10 h-10 rounded-lg bg-surface-container flex items-center justify-center text-primary mb-4 border border-outline-variant/10">
                    <span class="material-symbols-outlined text-[20px]">receipt_long</span>
                </div>
                <p class="metric-label mb-1">إجمالي الفواتير</p>
                <div class="text-3xl font-black font-headline tracking-tighter text-primary">{{ stats.total_invoices }}</div>
            </div>

            <div class="surface-card p-6 rounded-lg border-r-4 border-secondary">
                <div class="w-10 h-10 rounded-lg bg-secondary-container/20 text-on-secondary-container flex items-center justify-center mb-4 border border-secondary-container/30">
                    <span class="material-symbols-outlined text-[20px]">payments</span>
                </div>
                <p class="metric-label mb-1">المبالغ المحصلة</p>
                <div class="text-3xl font-black font-headline tracking-tighter text-secondary">{{ stats.total_paid.toLocaleString() }} <span class="text-xs font-bold text-slate-400">ر.س</span></div>
            </div>

            <div class="surface-card p-6 rounded-lg border-r-4 border-error">
                <div class="w-10 h-10 rounded-lg bg-error-container/10 text-error flex items-center justify-center mb-4 border border-error-container/20">
                    <span class="material-symbols-outlined text-[20px]">account_balance_wallet</span>
                </div>
                <p class="metric-label mb-1">المبالغ المعلقة</p>
                <div class="text-3xl font-black font-headline tracking-tighter text-error">{{ stats.pending_amount.toLocaleString() }} <span class="text-xs font-bold text-slate-400">ر.س</span></div>
            </div>

            <div class="surface-card p-6 rounded-lg">
                <div class="w-10 h-10 rounded-lg bg-primary-fixed/30 text-primary flex items-center justify-center mb-4 border border-primary-fixed/40">
                    <span class="material-symbols-outlined text-[20px]">calendar_month</span>
                </div>
                <p class="metric-label mb-1">تاريخ انتهاء الخدمة</p>
                <div class="text-[17px] font-black font-headline tracking-tight text-primary">{{ formatDate(client.expires_at) }}</div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Central Intelligence Column -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Data Cluster: Technical Identity -->
                <div class="surface-card p-8 rounded-lg relative overflow-hidden">
                    <h3 class="text-sm font-black text-primary uppercase tracking-widest mb-8 flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary text-[20px]">fingerprint</span>
                        الهوية التقنية والربط
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="p-6 bg-surface-container-low rounded-lg border border-outline-variant/5">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">اسم مستخدم الشبكة</p>
                            <p class="text-[15px] font-black font-headline tracking-tight text-primary">{{ client.username }}</p>
                        </div>
                        <div class="p-6 bg-surface-container-low rounded-lg border border-outline-variant/5">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">بروتوكول الخدمة</p>
                            <p class="text-[14px] font-black uppercase text-primary">{{ client.type }}</p>
                        </div>
                        <div class="p-6 bg-surface-container-low rounded-lg border border-outline-variant/5">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">الباقة المخصصة</p>
                            <p class="text-[14px] font-black text-primary">{{ client.package?.name || 'ذكاء مخصص' }}</p>
                        </div>
                        <div class="p-6 bg-surface-container-low rounded-lg border border-outline-variant/5">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">رقم الهاتف المرتبط</p>
                            <p class="text-[14px] font-black font-headline tracking-widest text-primary">{{ client.phone || 'غير مسجل' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Live Stream: Dynamic Telemetry -->
                <div class="surface-card p-8 rounded-lg border-2 border-primary/10 bg-surface-container-low">
                     <div class="flex items-center justify-between mb-8">
                        <h3 class="text-sm font-black text-primary uppercase tracking-widest flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary text-[20px]">radar</span>
                            تليميتري البث المباشر
                        </h3>
                        <div class="flex items-center gap-2 px-3 py-1 rounded bg-white border border-outline-variant/10">
                             <div class="h-2.5 w-2.5 rounded-full shadow-sm" :class="usage?.status === 'online' ? 'bg-secondary animate-pulse' : 'bg-error'"></div>
                             <span class="text-[10px] font-black uppercase tracking-widest text-primary">{{ usage?.status === 'online' ? 'إرسال فعال' : 'غير متصل' }}</span>
                        </div>
                     </div>
                     <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                         <div class="surface-card bg-white p-5 border border-outline-variant/5">
                             <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5">عنوان IP الهدف</p>
                             <p class="text-[13px] font-black font-headline text-primary tracking-tight">{{ activeIp || 'Detached' }}</p>
                         </div>
                         <div class="surface-card bg-white p-5 border border-outline-variant/5">
                             <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5">وقت الجلسة</p>
                             <p class="text-[13px] font-black font-headline text-primary/70 tracking-tight">{{ usage?.uptime || '00:00:00' }}</p>
                         </div>
                         <div class="surface-card bg-white p-5 border border-outline-variant/5">
                             <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5">سرعة التحميل (IN)</p>
                             <p class="text-[13px] font-black font-headline text-secondary tracking-tight">{{ usage?.download_speed || 0 }} Mbps</p>
                         </div>
                         <div class="surface-card bg-white p-5 border border-outline-variant/5">
                             <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5">سرعة الرفع (OUT)</p>
                             <p class="text-[13px] font-black font-headline text-indigo-500 tracking-tight">{{ usage?.upload_speed || 0 }} Mbps</p>
                         </div>
                     </div>
                     <div v-if="usage?.status === 'online'" class="mt-8 pt-6 border-t border-outline-variant/10 flex flex-col sm:flex-row justify-between items-center gap-4">
                         <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">
                             إجمالي استهلاك الجلسة: <span class="text-primary font-headline">{{ formatBytes(usage?.bytes_out) }}</span> تحميل / <span class="text-primary font-headline">{{ formatBytes(usage?.bytes_in) }}</span> رفع
                         </p>
                         <a v-if="client.type === 'pppoe' && activeIp" :href="route('crm.clients.cpe-proxy', { client: client.id, type: 'pppoe' })" target="_blank" class="px-6 py-2.5 bg-primary text-white text-[10px] font-black uppercase tracking-widest rounded transition-all flex items-center gap-2 hover:bg-primary-container shadow-lg shadow-primary/10">
                            حقن بروتوكول PPPoE <span class="material-symbols-outlined text-[16px]">terminal</span>
                         </a>
                     </div>
                </div>

                <!-- Hardware Nodes: Edge Deployment -->
                <div class="surface-card p-8 rounded-lg">
                    <h3 class="text-sm font-black text-primary uppercase tracking-widest mb-8 flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary text-[20px]">router</span>
                        الأجهزة والعقد الطرفية
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Internal CPE -->
                        <div class="space-y-4">
                            <h4 class="flex items-center gap-2 text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">
                                <span class="material-symbols-outlined text-[16px]">settings_input_component</span>
                                البوابة الداخلية (CPE)
                            </h4>
                            <div class="p-6 bg-surface-container-low rounded-lg border border-outline-variant/5 group">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5">عنوان السطح IP</p>
                                <p class="text-[15px] font-black font-headline text-primary tracking-tight">{{ client.cpe_ip || 'غير متصل' }}</p>
                                
                                <div v-if="client.cpe_ip" class="mt-6 pt-6 border-t border-outline-variant/10 flex items-center justify-between">
                                    <div class="text-[10px] font-black text-slate-500">
                                        <p class="uppercase">{{ client.cpe_username || 'admin' }}</p>
                                        <p class="text-[8px] opacity-70 tracking-widest">تحكم ميكروتيك</p>
                                    </div>
                                    <a 
                                        :href="route('crm.clients.cpe-proxy', { client: client.id, type: 'cpe' })" 
                                        target="_blank"
                                        class="px-5 py-2.5 bg-white border border-outline-variant/10 text-primary text-[10px] font-black uppercase tracking-widest rounded hover:bg-surface-container-high transition-all flex items-center gap-2 shadow-sm"
                                    >
                                        Webfig <span class="material-symbols-outlined text-[16px]">open_in_new</span>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- External Radio -->
                        <div class="space-y-4">
                            <h4 class="flex items-center gap-2 text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">
                                <span class="material-symbols-outlined text-[16px]">wifi_tethering</span>
                                الراديو الخارجي (Radio)
                            </h4>
                            <div class="p-6 bg-surface-container-high rounded-lg border border-outline-variant/10">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5">عنوان الهدف IP</p>
                                <p class="text-[15px] font-black font-headline text-secondary tracking-tight">{{ client.receiver_ip || 'بدون راديو' }}</p>

                                <div v-if="client.receiver_ip" class="mt-6 pt-6 border-t border-outline-variant/10 flex items-center justify-between">
                                    <div class="text-[10px] font-black text-slate-500">
                                        <p class="uppercase">{{ client.receiver_username || 'admin' }}</p>
                                        <p class="text-[8px] opacity-70 tracking-widest">عقدة استقبال SXT</p>
                                    </div>
                                    <a 
                                        :href="route('crm.clients.cpe-proxy', { client: client.id, type: 'receiver' })" 
                                        target="_blank"
                                        class="px-5 py-2.5 bg-primary text-white text-[10px] font-black uppercase tracking-widest rounded hover:bg-primary-container transition-all flex items-center gap-2 shadow-lg shadow-primary/10"
                                    >
                                         تحكم SXT <span class="material-symbols-outlined text-[16px]">sensors</span>
                                    </a>
                                </div>
                                <div v-else class="mt-6 py-4 text-center border-2 border-dashed border-outline-variant/20 rounded-lg">
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic opacity-50">لا توجد معدات خارجية</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Peripheral Intel Sidebar -->
            <div class="space-y-8">
                <!-- Data Pulse: Activity Tracking -->
                <div class="surface-card p-8 rounded-lg">
                    <h3 class="text-sm font-black text-primary uppercase tracking-widest mb-8 flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary text-[20px]">timeline</span>
                        نبض نشاط المشترك
                    </h3>
                    <div class="space-y-8 relative before:absolute before:right-0 before:top-2 before:bottom-0 before:w-px before:bg-outline-variant/15 pr-6">
                        <div v-for="activity in client.activities" :key="activity.id" class="relative group">
                            <div class="absolute -right-[27px] top-1 w-2.5 h-2.5 rounded-full border-2 border-white bg-primary shadow-sm z-10 transition-transform group-hover:scale-150"></div>
                            <p class="text-[13px] font-bold text-slate-700 leading-relaxed mb-2">{{ activity.description }}</p>
                            <p class="text-[9px] font-black uppercase tracking-widest text-slate-400 font-headline">{{ activity.created_at_human || 'Just now' }}</p>
                        </div>

                         <div v-if="!client.activities?.length" class="text-center py-12">
                            <span class="material-symbols-outlined text-slate-100 text-[48px] mb-4">history</span>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">لا توجد سجلات حالية</p>
                        </div>
                    </div>
                </div>

                <!-- Intelligence Log: Observer Comments -->
                <div class="surface-card p-8 rounded-lg border-2 border-outline-variant/5">
                    <h3 class="text-sm font-black text-primary uppercase tracking-widest mb-8 flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary text-[20px]">sticky_note_2</span>
                        سجل الملاحظات الفنية
                    </h3>
                    
                    <form @submit.prevent="submitNote" class="space-y-4">
                        <div class="relative">
                            <select 
                                v-model="noteForm.type"
                                class="w-full h-11 px-4 bg-surface-container-low rounded-lg text-xs font-black border-none appearance-none focus:ring-2 focus:ring-primary text-slate-700"
                            >
                                <option value="general">ملاحظة عامة</option>
                                <option value="technical">بلاغ فني</option>
                                <option value="billing">متابعة تحصيل</option>
                            </select>
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-[18px]">expand_more</span>
                        </div>
                        <textarea 
                            v-model="noteForm.content"
                            placeholder="دون ملاحظات استخباراتية حول المشترك..."
                            rows="4"
                            class="w-full p-5 bg-surface-container-low rounded-lg text-xs font-bold border-none outline-none focus:ring-2 focus:ring-primary text-slate-800 resize-none shadow-inner"
                        ></textarea>
                        <button 
                            type="submit" 
                            :disabled="noteForm.processing || !noteForm.content"
                            class="w-full h-12 bg-primary text-white rounded-lg font-black text-[11px] uppercase tracking-widest disabled:opacity-50 transition-all active:scale-95 shadow-xl shadow-primary/20"
                        >
                            حفظ الملاحظة في السجل
                        </button>
                    </form>

                    <div class="mt-10 space-y-5">
                        <div v-for="note in client.client_notes" :key="note.id" class="p-5 bg-white rounded-lg border border-outline-variant/10 shadow-sm relative group overflow-hidden">
                            <div class="absolute inset-y-0 right-0 w-1 bg-primary transform -translate-x-full group-hover:translate-x-0 transition-transform"></div>
                            <p class="text-[13px] font-bold text-slate-700 leading-relaxed mb-4">{{ note.content }}</p>
                            <div class="flex items-center justify-between text-[9px] font-black uppercase tracking-widest text-slate-400 border-t border-outline-variant/5 pt-3">
                                <span :class="note.type === 'technical' ? 'text-indigo-600' : (note.type === 'billing' ? 'text-secondary' : '')" class="flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px]">label</span>
                                    {{ note.type }}
                                </span>
                                <span class="font-headline font-extrabold">{{ note.created_at_human || 'الآن' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </InstitutionalLayout>
</template>

<style scoped>
/* High density adjustments */
.font-headline {
    letter-spacing: -0.02em;
}
</style>
