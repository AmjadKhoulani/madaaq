<script setup>
import { useForm, Head, Link } from '@inertiajs/vue3';
import InstitutionalLayout from '@/Layouts/InstitutionalLayout.vue';

const props = defineProps({
    role: Object,
    permissions: Array
});

const form = useForm({
    name: props.role.name,
    display_name: props.role.display_name,
    description: props.role.description,
    permissions: props.role.permissions.map(p => p.id),
});

const submit = () => {
    form.put(route('roles.update', props.role.id));
};

const togglePermission = (id) => {
    const index = form.permissions.indexOf(id);
    if (index === -1) {
        form.permissions.push(id);
    } else {
        form.permissions.splice(index, 1);
    }
};

const isPermissionSelected = (id) => {
    return form.permissions.includes(id);
};

// Group permissions by category (assuming naming convention like 'clients.index' or similar)
const groupedPermissions = computed(() => {
    const Users = {};
    props.permissions.forEach(p => {
        const parts = p.name.split('.');
        const category = parts.length > 1 ? parts[0] : 'general';
        if (!Users[category]) Users[category] = [];
        Users[category].push(p);
    });
    return Users;
});

import { computed } from 'vue';

</script>

<template>
    <InstitutionalLayout :title="'تعديل: ' + role.display_name">
        <Head :title="'حوكمة الصلاحيات: ' + role.display_name" />

        <div class="max-w-5xl mx-auto pb-24 text-right">
            <!-- Navigation Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-8 mb-12 flex-row-reverse">
                <div class="flex items-center gap-6 justify-end">
                    <div class="text-right">
                        <h1 class="text-3xl font-black text-primary tracking-tight mb-1">تعديل حدود الصلاحيات</h1>
                        <p class="text-slate-500 font-bold text-sm uppercase tracking-wider">تحديث بروتوكول الوصول للدور: {{ role.display_name }}</p>
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
                <!-- 1. Identity & Context -->
                <div class="surface-card p-10 rounded-xl relative overflow-hidden">
                    <h3 class="text-sm font-black text-primary uppercase tracking-[0.2em] mb-10 flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary text-[20px]">fingerprint</span>
                        هوية البروتوكول
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        <div class="space-y-4">
                            <label class="text-[11px] font-black text-primary uppercase tracking-widest mr-2 flex items-center gap-2">المُعرف البرمجي الداخلي (Name)</label>
                            <input v-model="form.name" type="text" class="form-input-monolith font-mono font-bold uppercase" required>
                        </div>
                        <div class="space-y-4">
                            <label class="text-[11px] font-black text-primary uppercase tracking-widest mr-2 flex items-center gap-2">المُسمى الوظيفي</label>
                            <input v-model="form.display_name" type="text" class="form-input-monolith font-bold text-lg" required>
                        </div>
                        <div class="space-y-4 md:col-span-2">
                            <label class="text-[11px] font-black text-primary uppercase tracking-widest mr-2 flex items-center gap-2">وصف حدود الصلاحيات</label>
                            <textarea v-model="form.description" class="form-input-monolith py-6 min-h-[100px] font-bold leading-relaxed"></textarea>
                        </div>
                    </div>
                </div>

                <!-- 2. Atomic Permissions Matrix -->
                <div class="surface-card p-10 rounded-xl">
                    <h3 class="text-sm font-black text-primary uppercase tracking-[0.2em] mb-10 flex items-center gap-3">
                        <span class="material-symbols-outlined text-secondary text-[24px]">key_visualizer</span>
                        مصفوفة الصلاحيات التفصيلية
                    </h3>

                    <div class="space-y-16">
                        <div v-for="(perms, category) in groupedPermissions" :key="category" class="space-y-8">
                             <div class="flex items-center gap-4 flex-row-reverse">
                                <h4 class="text-xs font-black uppercase tracking-widest text-slate-400">بروتوكول: {{ category }}</h4>
                                <div class="flex-1 h-px bg-outline-variant/10"></div>
                             </div>

                             <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                <button 
                                    v-for="perm in perms" 
                                    :key="perm.id"
                                    type="button"
                                    @click="togglePermission(perm.id)"
                                    class="p-6 rounded-xl border transition-all flex items-center justify-between group relative overflow-hidden"
                                    :class="isPermissionSelected(perm.id) ? 'bg-primary text-white border-primary shadow-lg scale-[1.02]' : 'bg-surface-container-low border-outline-variant/10 text-slate-400 hover:bg-white hover:text-primary'"
                                >
                                    <div class="flex items-center gap-4 relative z-10 min-w-0 flex-row-reverse">
                                        <div class="w-8 h-8 rounded-lg flex items-center justify-center transition-all bg-black/5" :class="isPermissionSelected(perm.id) ? 'bg-white/10' : ''">
                                            <span class="material-symbols-outlined text-[18px]">key</span>
                                        </div>
                                        <span class="text-[10px] font-black uppercase tracking-tight truncate">{{ perm.name.split('.').slice(1).join(' ') || perm.name }}</span>
                                    </div>
                                    <span v-if="isPermissionSelected(perm.id)" class="material-symbols-outlined text-white text-[20px] relative z-10">check_circle</span>
                                </button>
                             </div>
                        </div>
                    </div>
                </div>

                <!-- 3. Commitment Protocol -->
                <div class="flex flex-col md:flex-row items-center justify-between gap-10 surface-card p-12 bg-slate-950 text-white rounded-xl relative overflow-hidden shadow-2xl">
                    <div class="absolute -bottom-32 -left-32 w-80 h-80 bg-primary/10 rounded-full blur-3xl"></div>
                    <div class="relative z-10 flex items-center gap-8 text-right flex-row-reverse">
                        <div class="w-16 h-16 bg-white/10 rounded-xl flex items-center justify-center text-3xl shrink-0">
                            <span class="material-symbols-outlined text-[32px] text-secondary">verified_user</span>
                        </div>
                        <div>
                             <h4 class="text-xl font-black uppercase tracking-tight">تأكيد المزامنة</h4>
                             <p class="text-[10px] font-black text-white/40 uppercase tracking-widest mt-1 leading-relaxed">
                                سيتم تحديث حدود الصلاحيات لهذا الدور وتطبيقها فوراً على الكادر المرتبط.
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
                            :disabled="form.processing"
                        >
                            <span class="material-symbols-outlined text-[20px]">sync_alt</span>
                            حفظ التغييرات
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </InstitutionalLayout>
</template>

