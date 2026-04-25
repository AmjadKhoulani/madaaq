<script setup>
import { ref, computed } from 'vue';
import { useForm, Head, Link } from '@inertiajs/vue3';
import InstitutionalLayout from '@/Layouts/InstitutionalLayout.vue';

const props = defineProps({
    permissions: Object,
    baseRoles: Array
});

const form = useForm({
    name: '',
    display_name: '',
    description: '',
    base_role: 'operator',
    additional_roles: [],
});

const submit = () => {
    form.post(route('roles.store'));
};

const toggleAdditionalRole = (roleName) => {
    const index = form.additional_roles.indexOf(roleName);
    if (index === -1) {
        form.additional_roles.push(roleName);
    } else {
        form.additional_roles.splice(index, 1);
    }
};

const isRoleSelected = (roleName) => {
    return form.additional_roles.includes(roleName);
};

</script>

<template>
    <InstitutionalLayout title="إضافة دور جديد">
        <Head title="إعداد بروتوكول الصلاحيات" />

        <div class="max-w-4xl mx-auto pb-24 text-right">
            <!-- Navigation Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-8 mb-12 flex-row-reverse">
                <div class="flex items-center gap-6 justify-end">
                    <div class="text-right">
                        <h1 class="text-3xl font-black text-primary tracking-tight mb-1">إضافة دور جديد</h1>
                        <p class="text-slate-500 font-bold text-sm uppercase tracking-wider">توصيف الصلاحيات والوصول لمنظومة مدى كيو</p>
                    </div>
                    <Link 
                        :href="route('roles.index')" 
                        class="w-12 h-12 bg-white shadow-sm border border-outline-variant/10 rounded-lg flex items-center justify-center text-slate-400 hover:text-primary transition-all group"
                    >
                        <span class="material-symbols-outlined text-[24px] group-hover:translate-x-1 transition-transform">arrow_forward</span>
                    </Link>
                </div>
            </div>

            <form @submit.prevent="submit" class="space-y-8">
                <!-- 1. Identity & Purpose -->
                <div class="surface-card p-10 rounded-xl relative overflow-hidden">
                    <h3 class="text-sm font-black text-primary uppercase tracking-[0.2em] mb-10 flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary text-[20px]">id_card</span>
                        هوية الدور الإداري
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        <div class="space-y-4">
                            <label class="text-[11px] font-black text-primary uppercase tracking-widest mr-2 flex items-center gap-2">المُعرف البرمجي الفريد (Unique Name)</label>
                            <input v-model="form.name" type="text" class="form-input-monolith font-mono font-bold uppercase" placeholder="ADMIN_SUPPORT" required>
                        </div>
                        <div class="space-y-4">
                            <label class="text-[11px] font-black text-primary uppercase tracking-widest mr-2 flex items-center gap-2">المُسمى الوظيفي (قابل للقراءة)</label>
                            <input v-model="form.display_name" type="text" class="form-input-monolith font-bold text-lg" placeholder="مدير الدعم الفني" required>
                        </div>
                        <div class="space-y-4 md:col-span-2">
                            <label class="text-[11px] font-black text-primary uppercase tracking-widest mr-2 flex items-center gap-2">وصف المهام والصلاحيات</label>
                            <textarea v-model="form.description" class="form-input-monolith py-6 min-h-[120px] font-bold leading-relaxed" placeholder="اكتب وصفاً مختصراً لحدود الصلاحيات والمسؤوليات لهذا الدور..."></textarea>
                        </div>
                    </div>
                </div>

                <!-- 2. Synthesis Logic -->
                <div class="surface-card p-10 rounded-xl">
                    <h3 class="text-sm font-black text-primary uppercase tracking-[0.2em] mb-10 flex items-center gap-3">
                        <span class="material-symbols-outlined text-secondary text-[24px]">schema</span>
                        منطق توزيع الصلاحيات
                    </h3>

                    <div class="space-y-12">
                        <!-- Base Template Selection -->
                        <div class="space-y-6">
                            <label class="text-[11px] font-black text-primary uppercase tracking-widest mr-2">قالب الصلاحيات الأساسي (Foundation)</label>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <button 
                                    v-for="base in ['admin', 'operator', 'custom']" 
                                    :key="base"
                                    type="button"
                                    @click="form.base_role = base"
                                    class="p-8 rounded-xl border-2 transition-all flex flex-col items-center gap-4 group"
                                    :class="form.base_role === base ? 'bg-primary text-white border-primary shadow-xl scale-105' : 'bg-surface-container-low border-outline-variant/10 text-slate-400 hover:bg-white hover:text-primary'"
                                >
                                    <span class="material-symbols-outlined text-[40px] group-hover:rotate-12 transition-transform">
                                        {{ base === 'admin' ? 'admin_panel_settings' : (base === 'operator' ? 'engineering' : 'settings_suggest') }}
                                    </span>
                                    <span class="text-[11px] font-black uppercase tracking-widest">قالب: {{ base === 'admin' ? 'مدير' : (base === 'operator' ? 'مشغل' : 'مخصص') }}</span>
                                </button>
                            </div>
                        </div>

                        <!-- Additional Role Layering (Only for Custom) -->
                        <div v-if="form.base_role === 'custom'" class="space-y-6">
                             <div class="flex items-center justify-between">
                                <label class="text-[11px] font-black text-primary uppercase tracking-widest mr-2">طبقات الصلاحيات الموروثة (Merge Roles)</label>
                                <span class="text-[9px] font-black text-secondary uppercase tracking-widest bg-secondary-container/20 px-3 py-1 rounded-full">بروتوكول تجميعي</span>
                             </div>
                             <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                <button 
                                    v-for="role in baseRoles" 
                                    :key="role.id"
                                    type="button"
                                    @click="toggleAdditionalRole(role.name)"
                                    class="p-6 rounded-xl border transition-all flex items-center justify-between group"
                                    :class="isRoleSelected(role.name) ? 'bg-secondary-container/10 text-secondary border-secondary-container/20 shadow-md' : 'bg-surface-container-low border-outline-variant/10 text-slate-500 hover:border-primary/30'"
                                >
                                    <span class="text-[12px] font-black uppercase tracking-tight">{{ role.display_name }}</span>
                                    <span class="material-symbols-outlined text-[20px]" :class="isRoleSelected(role.name) ? 'text-secondary' : 'text-slate-200'">
                                        {{ isRoleSelected(role.name) ? 'check_circle' : 'add_circle' }}
                                    </span>
                                </button>
                             </div>
                        </div>

                        <!-- Insight Card -->
                        <div class="p-10 bg-primary text-white rounded-xl relative overflow-hidden group shadow-2xl shadow-primary/20">
                            <div class="absolute -top-16 -right-16 w-48 h-48 bg-white/10 rounded-full blur-3xl transition-all group-hover:scale-125"></div>
                            <div class="relative z-10 flex items-center gap-10">
                                <span class="material-symbols-outlined text-[48px] text-white/40">verified_user</span>
                                <div>
                                     <h4 class="text-sm font-black uppercase tracking-tight mb-2">سلامة توزيع الصلاحيات</h4>
                                     <p class="text-[11px] opacity-70 font-bold leading-relaxed">سيتم دمج كافة الصلاحيات الفرعية للقوالب المحددة تلقائياً. استخدم النمط "المخصص" لدمج صلاحيات من أدوار موجودة مسبقاً في النظام.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. Commitment Protocol -->
                <div class="flex flex-col md:flex-row items-center justify-between gap-10 surface-card p-12 bg-slate-950 text-white rounded-xl relative overflow-hidden shadow-2xl">
                    <div class="absolute -bottom-32 -left-32 w-80 h-80 bg-primary/10 rounded-full blur-3xl"></div>
                    <div class="relative z-10 flex items-center gap-8 text-right flex-row-reverse">
                        <div class="w-16 h-16 bg-white/10 rounded-xl flex items-center justify-center text-3xl">
                            🛡️
                        </div>
                        <div>
                             <h4 class="text-xl font-black uppercase tracking-tight">اعتماد الدور الإداري</h4>
                             <p class="text-[10px] font-black text-white/40 uppercase tracking-widest mt-1 leading-relaxed">
                                سيتم إدراج هذا الدور ضمن الهيكل التنظيمي وصلاحيات الوصول المركزية.
                             </p>
                        </div>
                    </div>
                    <div class="relative z-10 flex items-center gap-6">
                        <Link 
                            :href="route('roles.index')" 
                            class="px-8 py-5 bg-white/5 text-white/50 font-black text-[11px] uppercase tracking-widest rounded-lg hover:bg-white/10 hover:text-white transition-all active:scale-95 border border-white/10"
                        >
                            إلغاء التغييرات
                        </Link>
                        <button 
                            type="submit" 
                            class="px-12 py-5 bg-white text-slate-950 font-black text-[11px] uppercase tracking-[0.2em] rounded-lg shadow-xl hover:bg-emerald-500 hover:text-white transition-all hover:scale-105 active:scale-95 disabled:opacity-30 flex items-center gap-3"
                            :disabled="form.processing || !form.name || !form.display_name"
                        >
                            <span class="material-symbols-outlined text-[20px]">save_as</span>
                            حفظ واعتماد الدور
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </InstitutionalLayout>yout>
</template>

