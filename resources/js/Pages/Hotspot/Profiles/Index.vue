<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import InstitutionalLayout from '@/Layouts/InstitutionalLayout.vue';

const props = defineProps({
    profiles: Array
});

const deleteProfile = (id) => {
    if (confirm('هل أنت متأكد من رغبتك في إخراج هذه الباقة من الخدمة؟ سيتم حذف التعريف الخاص بها من كافة العقد الطرفية ومزامنة التغييرات فوراً.')) {
        router.delete(route('hotspot.profiles.destroy', id));
    }
};

</script>

<template>
    <InstitutionalLayout title="باقات الهوت سبوت">
        <Head title="تعريفات الهوت سبوت - MadaaQ" />

        <div class="max-w-7xl mx-auto pb-24 text-right px-4" dir="rtl">
            <!-- Strategic Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-8 mb-12 flex-row-reverse">
                <div class="flex items-center gap-6 justify-end">
                    <div class="text-right">
                        <h1 class="text-3xl font-black text-primary tracking-tight mb-2">إدارة باقات المشتركين</h1>
                        <p class="text-slate-500 font-bold text-sm uppercase tracking-wider flex items-center gap-2 justify-end">
                             يوجد حالياً {{ profiles.length }} باقة فنية معرفة في النظام
                            <span class="w-2 h-2 bg-amber-500 rounded-full animate-pulse shadow-[0_0_8px_rgba(245,158,11,0.5)]"></span>
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                     <Link 
                        :href="route('hotspot.profiles.create')" 
                        class="inline-flex items-center gap-3 px-8 py-4 bg-primary text-white rounded-xl font-black shadow-xl shadow-primary/20 hover:bg-primary/90 transition-all active:scale-95"
                     >
                        <span class="material-symbols-outlined text-[20px]">add_card</span>
                        <span class="text-sm">إضافة باقة جديدة</span>
                     </Link>
                </div>
            </div>

            <!-- Profiles Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
                <div 
                    v-for="profile in profiles" 
                    :key="profile.id"
                    class="surface-card group hover:scale-[1.02] transition-all flex flex-col rounded-xl overflow-hidden shadow-sm border border-outline-variant/10"
                >
                    <!-- Visual Identity -->
                    <div class="p-10 pb-6 relative overflow-hidden">
                        <div class="absolute -top-10 -right-10 w-40 h-40 bg-primary/5 rounded-full blur-3xl group-hover:bg-primary/10 transition-all"></div>
                        <div class="flex items-start justify-between mb-8 relative z-10 flex-row-reverse">
                            <div class="flex items-center gap-6 flex-row-reverse">
                                <div class="w-16 h-16 rounded-2xl flex items-center justify-center transition-all shadow-inner bg-surface-container-low text-primary border border-outline-variant/5">
                                    <span class="material-symbols-outlined text-[36px]">confirmation_number</span>
                                </div>
                                <div class="text-right">
                                    <h3 class="text-xl font-black text-primary leading-tight uppercase truncate max-w-[150px]">{{ profile.name }}</h3>
                                    <p class="text-[9px] font-black text-slate-400 font-headline uppercase tracking-widest mt-1.5">بروتوكول وصول الهوت سبوت</p>
                                </div>
                            </div>
                            <div class="text-left flex flex-col items-start translate-y-1">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-2">سعر القسيمة</p>
                                <p class="text-2xl font-black tracking-tight text-secondary leading-none font-headline">{{ profile.price }} <span class="text-[10px] opacity-60 mr-1 uppercase">S.P</span></p>
                            </div>
                        </div>

                        <!-- Velocity Matrix -->
                        <div class="grid grid-cols-2 gap-5 relative z-10">
                            <div class="p-6 bg-surface-container-low/50 border border-outline-variant/5 rounded-2xl text-center group/speed shadow-inner">
                                <div class="flex items-center justify-center gap-2 mb-2">
                                    <span class="material-symbols-outlined text-emerald-500 text-[18px]">download_for_offline</span>
                                    <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest">تنزيل</span>
                                </div>
                                <p class="text-2xl font-black text-primary tracking-tighter font-headline">{{ profile.speed_down }} <span class="text-[10px] text-slate-400 mr-1 uppercase">Mbps</span></p>
                            </div>
                            <div class="p-6 bg-surface-container-low/50 border border-outline-variant/5 rounded-2xl text-center group/speed shadow-inner">
                                <div class="flex items-center justify-center gap-2 mb-2">
                                    <span class="material-symbols-outlined text-indigo-500 text-[18px]">upload_file</span>
                                    <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest">رفع</span>
                                </div>
                                <p class="text-2xl font-black text-primary tracking-tighter font-headline">{{ profile.speed_up }} <span class="text-[10px] text-slate-400 mr-1 uppercase">Mbps</span></p>
                            </div>
                        </div>
                    </div>

                    <!-- Topology Sync -->
                    <div class="px-10 py-5 border-t border-outline-variant/5 bg-surface-container-low/30">
                         <div class="flex items-center justify-between flex-row-reverse">
                             <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest">عقد الاستخراج المتزامنة</p>
                             <div class="flex -space-x-2 flex-row-reverse space-x-reverse">
                                 <div v-for="i in Math.min(3, (profile.routers?.length || 0) + (profile.mikrotik_servers?.length || 0))" :key="i" class="w-8 h-8 rounded-xl bg-white border border-outline-variant/10 flex items-center justify-center text-[10px] font-black text-primary shadow-sm">
                                      {{ i }}
                                 </div>
                                 <div v-if="((profile.routers?.length || 0) + (profile.mikrotik_servers?.length || 0)) > 3" class="w-8 h-8 rounded-xl bg-primary text-white border border-primary flex items-center justify-center text-[10px] font-black shadow-sm">
                                      +{{ (profile.routers?.length || 0) + (profile.mikrotik_servers?.length || 0) - 3 }}
                                 </div>
                             </div>
                         </div>
                    </div>

                    <!-- Commit Actions -->
                    <div class="p-5 bg-white mt-auto flex items-center justify-between border-t border-outline-variant/5">
                        <div class="flex items-center gap-3">
                            <Link 
                                :href="route('hotspot.profiles.edit', profile.id)" 
                                class="w-12 h-12 flex items-center justify-center text-slate-400 hover:text-primary hover:bg-surface-container-low rounded-xl transition-all border border-transparent hover:border-outline-variant/10"
                            >
                                <span class="material-symbols-outlined text-[20px]">tune</span>
                            </Link>
                            <button 
                                @click="deleteProfile(profile.id)"
                                class="w-12 h-12 flex items-center justify-center text-slate-400 hover:text-error hover:bg-error/5 rounded-xl transition-all border border-transparent hover:border-error/10"
                            >
                                <span class="material-symbols-outlined text-[20px]">delete_sweep</span>
                            </button>
                        </div>
                        <Link 
                            :href="route('hotspot.vouchers.index', { profile_id: profile.id })" 
                            class="px-6 py-3 text-[10px] font-black uppercase tracking-widest text-primary border border-outline-variant/10 hover:bg-primary hover:text-white rounded-xl transition-all flex items-center gap-3 group/btn"
                        >
                            <span class="material-symbols-outlined text-[18px]">history</span>
                            سجل القسائم
                            <span class="material-symbols-outlined text-[18px] group-hover:-translate-x-1 transition-transform">chevron_left</span>
                        </Link>
                    </div>
                </div>

                <!-- Add Placeholder -->
                <Link 
                    :href="route('hotspot.profiles.create')" 
                    class="surface-card border-dashed border-2 border-outline-variant/20 bg-transparent flex flex-col items-center justify-center p-12 group hover:border-primary/30 transition-all hover:bg-surface-container-low/50 rounded-xl min-h-[300px]"
                >
                    <div class="w-20 h-20 bg-surface-container-low rounded-[2rem] flex items-center justify-center text-slate-300 group-hover:scale-110 group-hover:bg-primary group-hover:text-white transition-all mb-6 shadow-inner">
                        <span class="material-symbols-outlined text-[40px]">add_task</span>
                    </div>
                    <p class="text-[12px] font-black uppercase tracking-[0.2em] text-slate-400 group-hover:text-primary transition-colors">تأسيس فئة باقة جديدة</p>
                </Link>
            </div>
        </div>
    </InstitutionalLayout>
</template>

<style scoped>
.font-headline {
    font-family: 'Manrope', sans-serif;
}
</style>
