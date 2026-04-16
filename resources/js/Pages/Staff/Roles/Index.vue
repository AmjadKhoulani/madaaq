<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import InstitutionalLayout from '@/Layouts/InstitutionalLayout.vue';

const props = defineProps({
    roles: Object,
});

const deleteRole = (id) => {
    if (confirm('Decommission this authority role? This will disrupt all associated subscribers.')) {
        router.delete(route('roles.destroy', id));
    }
};

</script>

<template>
    <InstitutionalLayout title="سجل الأدوار والصلاحيات">
        <Head title="حوكمة أدوار الكادر" />

        <div class="max-w-7xl mx-auto pb-24 text-right px-4">
            <!-- Strategic Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-8 mb-12 flex-row-reverse">
                <div class="flex items-center gap-6 justify-end">
                    <div class="text-right">
                        <h1 class="text-3xl font-black text-primary tracking-tight mb-2">سجل الأدوار والصلاحيات</h1>
                        <p class="text-slate-500 font-bold text-sm uppercase tracking-wider flex items-center gap-2 justify-end">
                            إدارة حدود الصلاحيات والوصول للمنظومة
                            <span class="material-symbols-outlined text-[20px]">lock_person</span>
                        </p>
                    </div>
                </div>
                <Link 
                    :href="route('roles.create')" 
                    class="inline-flex items-center gap-3 px-10 py-5 bg-primary text-white rounded-xl font-black shadow-xl shadow-primary/20 hover:bg-primary/90 transition-all active:scale-95"
                >
                    <span class="material-symbols-outlined text-[20px]">add_moderator</span>
                    <span class="text-sm">إنشاء دور جديد</span>
                </Link>
            </div>

            <!-- Role Registry Table -->
            <div class="surface-card rounded-xl overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-right border-collapse">
                        <thead>
                            <tr class="bg-surface-container-low/50 border-b border-outline-variant/10">
                                <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] leading-none">المُسمى الوظيفي / الدور</th>
                                <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] leading-none">المُعرف البرمجي</th>
                                <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] leading-none text-center">الأعضاء النشطون</th>
                                <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] leading-none">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant/5">
                            <tr v-for="role in roles.data" :key="role.id" class="group hover:bg-surface-container-low transition-all">
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-6">
                                        <div class="w-12 h-12 rounded-lg bg-primary text-white flex items-center justify-center shadow-lg shadow-primary/20 group-hover:scale-110 transition-transform">
                                            <span class="material-symbols-outlined text-[24px]">shield</span>
                                        </div>
                                        <div>
                                            <h4 class="text-[15px] font-black text-primary leading-tight">{{ role.display_name }}</h4>
                                            <p class="text-[10px] font-bold text-slate-400 mt-1.5 uppercase tracking-widest truncate max-w-[300px]">{{ role.description || 'صلاحيات إدارية عامة في النظام' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <span class="px-4 py-1 bg-surface-container-low border border-outline-variant/10 rounded-lg text-[10px] font-black font-mono text-primary uppercase">{{ role.name }}</span>
                                </td>
                                <td class="px-8 py-6 text-center">
                                    <div class="inline-flex items-center gap-2 px-5 py-2 bg-secondary-container/10 text-secondary rounded-full border border-secondary-container/20">
                                        <span class="material-symbols-outlined text-[18px]">groups</span>
                                        <span class="text-sm font-black">{{ role.users_count }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-6 text-left">
                                    <div class="flex items-center justify-end gap-3 opacity-0 group-hover:opacity-100 transition-all">
                                         <Link 
                                            :href="route('roles.edit', role.id)" 
                                            class="w-10 h-10 bg-white shadow-sm border border-outline-variant/10 rounded-lg flex items-center justify-center text-slate-400 hover:text-primary transition-all"
                                         >
                                            <span class="material-symbols-outlined text-[20px]">edit_note</span>
                                         </Link>
                                         <button 
                                            @click="deleteRole(role.id)"
                                            class="w-10 h-10 bg-white shadow-sm border border-outline-variant/10 rounded-lg flex items-center justify-center text-slate-400 hover:text-error transition-all"
                                         >
                                            <span class="material-symbols-outlined text-[20px]">delete_sweep</span>
                                         </button>
                                         <div class="h-8 w-px bg-outline-variant/10 mx-2"></div>
                                         <span class="material-symbols-outlined text-outline-variant text-[20px] group-hover:text-primary group-hover:translate-x-1 transition-all">chevron_left</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Empty State -->
                <div v-if="roles.data.length === 0" class="py-32 flex flex-col items-center gap-8">
                    <div class="w-24 h-24 rounded-full bg-surface-container-low flex items-center justify-center text-slate-200 border border-outline-variant/5">
                        <span class="material-symbols-outlined text-[48px]" style="font-variation-settings: 'wght' 100">admin_panel_settings</span>
                    </div>
                    <div class="text-center">
                        <h3 class="text-xl font-black text-primary mb-2">لا توجد أدوار مضافة</h3>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em]">لم يتم العثور على أي أدوار مسجلة حالياً في النظام.</p>
                    </div>
                </div>

                <!-- Professional Pagination -->
                <div v-if="roles.links.length > 3" class="px-8 py-6 border-t border-outline-variant/10 flex items-center justify-center gap-2 bg-surface-container/10">
                    <Link 
                        v-for="(link, k) in roles.links" 
                        :key="k"
                        :href="link.url || '#'"
                        v-html="link.label"
                        class="h-10 px-4 flex items-center justify-center rounded-lg text-[11px] font-black transition-all font-headline"
                        :class="[
                            link.active ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'hover:bg-primary-fixed/20 text-slate-500',
                            !link.url ? 'opacity-30 cursor-not-allowed' : ''
                        ]"
                    />
                </div>
            </div>
        </div>
    </InstitutionalLayout>
</template>
