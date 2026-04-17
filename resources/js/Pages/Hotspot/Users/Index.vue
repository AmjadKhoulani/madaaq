<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import InstitutionalLayout from '@/Layouts/InstitutionalLayout.vue';

const props = defineProps({
    users: Array,
});

const deleteUser = (id) => {
    if (confirm('تأكيد إزالة هذا الحساب؟ سيتم إلغاء صلاحية الوصول من كافة العقد الطرفية ومسح بيانات الجلسات النشطة.')) {
        router.delete(route('hotspot.users.destroy', id));
    }
};

const getStatusDetails = (status) => {
    switch (status) {
        case 'active': return { label: 'نشط', color: 'text-emerald-600 bg-emerald-50 border-emerald-100' };
        case 'inactive': return { label: 'معطل', color: 'text-rose-600 bg-rose-50 border-rose-100' };
        default: return { label: status, color: 'text-slate-600 bg-slate-50 border-slate-100' };
    }
};

</script>

<template>
    <InstitutionalLayout title="حوكمة ضيوف الشبكة">
        <Head title="إدارة حسابات المشتركين المنفردة - MadaaQ" />

        <div class="max-w-7xl mx-auto pb-24 text-right px-4" dir="rtl">
            <!-- Strategic Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-8 mb-16 flex-row-reverse">
                <div class="text-right">
                    <h1 class="text-4xl font-black text-primary tracking-tight mb-2">سجل حسابات المشتركين</h1>
                    <p class="text-slate-500 font-bold text-sm uppercase tracking-wider flex items-center gap-3 justify-end">
                        <span>إدارة حسابات الوصول المباشر وضيوف الشبكة المسجلين</span>
                        <span class="material-symbols-outlined text-primary text-[20px]">group</span>
                    </p>
                </div>
                <div class="flex items-center gap-4 flex-row-reverse">
                     <div class="relative group hidden lg:block">
                        <input type="text" placeholder="ابحث عن مشترك أو رمز سري..." class="form-input-monolith pr-12 h-14 w-80 bg-surface-container-low border-transparent focus:bg-white focus:ring-primary/20 transition-all font-bold">
                        <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary transition-colors text-[22px]">search</span>
                     </div>
                     <Link 
                        :href="route('hotspot.users.create')" 
                        class="px-10 h-14 bg-primary text-white rounded-xl font-black text-[11px] uppercase tracking-[0.2em] shadow-xl shadow-primary/20 hover:bg-primary/90 active:scale-95 transition-all flex items-center gap-4 border border-white/10"
                     >
                        <span class="material-symbols-outlined text-[24px]">person_add</span> توليد حساب جديد
                     </Link>
                </div>
            </div>

            <!-- Users Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-10">
                <div 
                    v-for="user in users" 
                    :key="user.id"
                    class="surface-card p-10 flex flex-col relative group transition-all hover:scale-[1.03] animate-in fade-in duration-500 rounded-2xl border border-outline-variant/10 shadow-sm overflow-hidden"
                >
                    <!-- Status Pulse -->
                    <div class="absolute top-10 left-10">
                        <span 
                            class="px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest border flex items-center gap-2 shadow-sm"
                            :class="getStatusDetails(user.status).color"
                        >
                            <span v-if="user.status === 'active'" class="w-2 h-2 rounded-full bg-current animate-pulse"></span>
                            {{ getStatusDetails(user.status).label }}
                        </span>
                    </div>

                    <!-- User Identity -->
                    <div class="flex items-center gap-6 mb-12 flex-row-reverse">
                        <div class="w-20 h-20 rounded-[2rem] bg-slate-950 text-white flex items-center justify-center text-3xl font-black shadow-2xl group-hover:bg-primary transition-colors grow-0 shrink-0 border border-white/5">
                            {{ user.name.substring(0, 1) }}
                        </div>
                        <div class="text-right min-w-0 flex-1">
                             <h3 class="text-2xl font-black text-primary tracking-tight uppercase truncate leading-none mb-3">{{ user.name }}</h3>
                             <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] flex items-center gap-2 justify-end">
                                {{ user.package?.name || 'باقة افتراضية (Standard)' }}
                                <span class="material-symbols-outlined text-amber-500 text-[18px]">bolt</span>
                             </p>
                        </div>
                    </div>

                    <!-- Credentials Artifact -->
                    <div class="p-8 bg-surface-container-low/50 border border-outline-variant/5 rounded-2xl space-y-6 mb-10 shadow-inner">
                         <div class="flex justify-between items-center flex-row-reverse">
                             <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">مُعرف الوصول</span>
                             <span class="text-primary font-headline font-black text-base tracking-widest">{{ user.username }}</span>
                         </div>
                         <div class="flex justify-between items-center flex-row-reverse border-t border-outline-variant/5 pt-6">
                             <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">مفتاح السر</span>
                             <span class="text-primary font-headline font-black text-base tracking-widest">{{ user.password }}</span>
                         </div>
                    </div>

                    <!-- Edge Node & Expiry -->
                    <div class="grid grid-cols-2 gap-8 pt-8 border-t border-outline-variant/10 mt-auto">
                        <div class="space-y-3 text-right">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">بوابة التزويد (Node)</p>
                            <p class="text-[11px] font-black text-primary truncate flex items-center gap-2 justify-end">
                                {{ user.mikrotik_server?.name || 'عقدة محلية (Edge)' }}
                                <span class="material-symbols-outlined text-[18px] text-primary/40">nest_remote_comfort_sensor</span>
                            </p>
                        </div>
                        <div class="space-y-3 text-right border-r border-outline-variant/10 pr-8">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">أفق الصلاحية (Expiry)</p>
                            <p class="text-[11px] font-black flex items-center gap-2 justify-end font-headline" :class="user.expires_at ? 'text-amber-600' : 'text-emerald-600'">
                                {{ user.expires_at ? new Date(user.expires_at).toLocaleDateString('ar-SY') : 'صلاحية دائمة' }}
                                <span class="material-symbols-outlined text-[18px]">schedule</span>
                            </p>
                        </div>
                    </div>

                    <!-- Action Hover Reveal -->
                    <div class="absolute inset-x-10 bottom-10 flex items-center gap-4 opacity-0 group-hover:opacity-100 transition-all pointer-events-none group-hover:pointer-events-auto flex-row-reverse translate-y-4 group-hover:translate-y-0">
                         <Link 
                            :href="route('hotspot.users.print', user.id)" 
                            class="flex-1 h-14 bg-primary text-white rounded-xl text-[10px] font-black uppercase tracking-[0.2em] text-center shadow-2xl shadow-primary/30 hover:bg-primary/90 transition-all flex items-center justify-center gap-3 border border-white/10"
                         >
                            <span class="material-symbols-outlined text-[20px]">print</span> استخراج البطاقة
                         </Link>
                         <button 
                            @click="deleteUser(user.id)"
                            class="w-14 h-14 bg-error/5 text-error rounded-xl flex items-center justify-center hover:bg-error hover:text-white transition-all border border-error/10 shadow-sm"
                         >
                            <span class="material-symbols-outlined text-[24px]">delete</span>
                         </button>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-if="users.length === 0" class="col-span-full py-48 surface-card flex flex-col items-center justify-center text-center rounded-2xl border border-dashed border-outline-variant/30 animate-in fade-in duration-1000">
                     <div class="w-32 h-32 rounded-[3rem] bg-surface-container-low flex items-center justify-center mb-10 text-slate-200 shadow-inner border border-outline-variant/10">
                        <span class="material-symbols-outlined text-[64px]" style="font-variation-settings: 'wght' 100">person_off</span>
                     </div>
                     <h4 class="text-3xl font-black text-primary uppercase tracking-tight mb-4">لا يوجد حسابات مسجلة</h4>
                     <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.3em] max-w-sm mx-auto leading-relaxed">لم يتم رصد أي حسابات ضيوف في النظام حالياً. ابدأ بتوليد أول حساب وصول طرفي.</p>
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
