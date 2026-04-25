<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import InstitutionalLayout from '@/Layouts/InstitutionalLayout.vue';
import { 
    UserPlus, 
    ShieldCheck, 
    ShieldAlert, 
    UserCog, 
    SupportAgent, 
    Mail, 
    Phone, 
    CheckCircle2, 
    Lock, 
    Edit3, 
    UserX,
    UserCircle,
    ChevronLeft,
    ChevronRight,
    Search
} from 'lucide-vue-next';

const props = defineProps({
    staff: Object,
});

const deleteStaff = (id) => {
    if (confirm('تأكيد سحب كافة صلاحيات العبور وتجميد الهوية السيادية لهذا العضو؟ سيتم الإغلاق الفوري لكافة منافذ الحوكمة المرتبطة.')) {
        router.delete(route('staff.destroy', id), {
            preserveScroll: true,
        });
    }
};

const getRoleDetails = (roleName) => {
    const roles = {
        'super-admin': { label: 'المدير السيادي المطلق', class: 'text-vendor bg-vendor/10 border-vendor/20 shadow-vendor/10', icon: ShieldCheck },
        'admin': { label: 'مدير العمليات المركزية', class: 'text-indigo-600 bg-indigo-500/10 border-indigo-500/20 shadow-indigo-500/10', icon: UserCog },
        'operator': { label: 'مشغل المصفوفة الفنية', class: 'text-emerald-600 bg-emerald-500/10 border-emerald-500/20 shadow-emerald-500/10', icon: ShieldCheck },
        'support': { label: 'وحدة الدعم اللوجستي', class: 'text-amber-600 bg-amber-500/10 border-amber-500/20 shadow-amber-500/10', icon: SupportAgent },
    };
    return roles[roleName.toLowerCase()] || { label: roleName, class: 'text-slate-500 bg-slate-500/10 border-slate-500/20', icon: UserCircle };
};
</script>

<template>
    <InstitutionalLayout title="حوكمة الكادر السيادي">
        <div class="space-y-10 pb-20 animate-in fade-in slide-in-from-bottom-4 duration-700">
            
            <!-- Strategic Header -->
            <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-8">
                <div>
                    <div class="flex items-center gap-3 mb-3">
                        <span class="w-12 h-1 bg-vendor rounded-full"></span>
                        <p class="text-[10px] font-black text-vendor uppercase tracking-[0.3em] font-inter">Personnel Registry Governance</p>
                    </div>
                    <h1 class="text-4xl font-black text-slate-900 tracking-tighter italic">سجلات <span class="text-vendor">الكادر</span> السيادي</h1>
                    <p class="text-slate-400 font-bold mt-2 uppercase tracking-widest text-[11px] opacity-80 italic">إدارة هويات العبور وحوكمة مستويات الصلاحية الإدارية</p>
                </div>
                
                <Link :href="route('staff.create')" class="btn-radiant btn-vendor px-8 py-4 text-xs font-black uppercase tracking-[0.2em] flex items-center gap-3">
                    <UserPlus class="w-5 h-5 stroke-[3]" />
                    تعيين هوية جديدة
                </Link>
            </div>

            <!-- Identity Register Matrix -->
            <div class="glass-card overflow-hidden bg-white/40 border-white/60">
                <div class="overflow-x-auto">
                    <table class="w-full text-right border-collapse">
                        <thead>
                            <tr class="bg-slate-900 text-white/40 text-[10px] font-black uppercase tracking-[0.3em] italic font-inter">
                                <th class="px-10 py-8">هوية العضو (Personnel Identity)</th>
                                <th class="px-8 py-8 text-center">أذونات المصفوفة</th>
                                <th class="px-8 py-8 text-center">قنوات التواصل السيادية</th>
                                <th class="px-8 py-8 text-center">وضعية العبور</th>
                                <th class="px-10 py-8"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/20">
                            <tr v-for="member in staff.data" :key="member.id" class="group hover:bg-white/60 transition-all duration-500">
                                <td class="px-10 py-8">
                                    <div class="flex items-center gap-5 justify-end">
                                        <div class="text-right">
                                            <h4 class="text-lg font-black text-slate-900 italic tracking-tighter group-hover:translate-x-2 transition-transform uppercase font-inter">{{ member.name }}</h4>
                                            <div class="flex items-center gap-2 mt-1 opacity-40 justify-end">
                                                <span class="text-[9px] font-black uppercase tracking-widest">{{ member.position || 'Central Matrix Operator' }}</span>
                                                <ShieldCheck class="w-3 h-3" />
                                            </div>
                                        </div>
                                        <div class="w-14 h-14 rounded-2xl bg-slate-900 text-vendor flex items-center justify-center shadow-xl group-hover:scale-110 group-hover:rotate-6 transition-all duration-500 shrink-0 border border-white/10 font-black text-xl font-inter uppercase">
                                            {{ member.name.substring(0, 1) }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-8 text-center">
                                    <div class="flex flex-wrap gap-2 justify-center">
                                        <span 
                                            v-for="role in member.roles" 
                                            :key="role.id"
                                            :class="['inline-flex items-center gap-2 px-4 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-widest border transition-all shadow-sm', getRoleDetails(role.name).class]"
                                        >
                                            <component :is="getRoleDetails(role.name).icon" class="w-3.5 h-3.5" />
                                            {{ getRoleDetails(role.name).label }}
                                        </span>
                                        <span v-if="member.roles.length === 0" class="inline-flex items-center gap-2 px-4 py-1.5 rounded-xl text-[9px] font-black text-rose-500 bg-rose-500/10 border border-rose-500/20 uppercase tracking-widest">
                                            <ShieldAlert class="w-3.5 h-3.5" />
                                            هوية غير مصرحة
                                        </span>
                                    </div>
                                </td>
                                <td class="px-8 py-8 text-center">
                                    <div class="flex flex-col items-center gap-2">
                                        <div class="flex items-center gap-2 text-slate-700 bg-white/50 px-4 py-1.5 rounded-xl border border-white/60 shadow-inner group-hover:bg-slate-900 group-hover:text-white transition-all duration-500">
                                            <Mail class="w-3.5 h-3.5 opacity-40" />
                                            <span class="text-[11px] font-black font-inter tracking-widest">{{ member.email }}</span>
                                        </div>
                                        <div class="flex items-center gap-2 opacity-40 group-hover:opacity-100 transition-opacity">
                                            <Phone class="w-3 h-3" />
                                            <span class="text-[10px] font-black font-inter tracking-widest">{{ member.phone || 'HIDDEN_CONTACT' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-8 text-center">
                                    <div :class="[
                                        'inline-flex items-center justify-center gap-3 px-6 py-3 rounded-xl text-[9px] font-black uppercase tracking-widest border transition-all shadow-sm group-hover:translate-y-[-2px]',
                                        member.is_active ? 'bg-emerald-500/10 text-emerald-600 border-emerald-500/20 shadow-emerald-500/10' : 'bg-rose-500/10 text-rose-500 border-rose-500/20 shadow-rose-500/10'
                                    ]">
                                        <span class="relative flex h-2.5 w-2.5">
                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-current opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-current"></span>
                                        </span>
                                        {{ member.is_active ? 'صلاحيات نشطة' : 'وصول مجمد' }}
                                    </div>
                                </td>
                                <td class="px-10 py-8">
                                    <div class="flex items-center justify-end gap-3 opacity-0 group-hover:opacity-100 transition-all translate-x-4 group-hover:translate-x-0">
                                         <Link :href="route('staff.edit', member.id)" class="w-12 h-12 bg-white shadow-xl border border-white/60 rounded-xl flex items-center justify-center text-slate-400 hover:text-vendor hover:scale-110 active:scale-90 transition-all">
                                            <Edit3 class="w-5 h-5" />
                                         </Link>
                                         <button @click="deleteStaff(member.id)" class="w-12 h-12 bg-white shadow-xl border border-white/60 rounded-xl flex items-center justify-center text-slate-400 hover:text-rose-600 hover:scale-110 active:scale-90 transition-all">
                                            <UserX class="w-5 h-5" />
                                         </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Empty State Protocol -->
                <div v-if="staff.data.length === 0" class="py-40 flex flex-col items-center gap-8 text-center">
                    <div class="w-32 h-32 rounded-[2.5rem] bg-slate-900 text-vendor flex items-center justify-center border-8 border-white/20 shadow-2xl relative group">
                        <div class="absolute inset-0 bg-vendor/20 opacity-20 blur-2xl animate-pulse"></div>
                        <UserCircle class="w-12 h-12 relative z-10" />
                    </div>
                    <div>
                        <h3 class="text-3xl font-black text-slate-900 italic tracking-tighter uppercase">مصفوفة الكادر فارغة (Null Personnel)</h3>
                        <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.3em] max-w-sm mt-3 opacity-70 italic font-inter">No administrative identities detected in the current sovereign domain.</p>
                    </div>
                    <Link :href="route('staff.create')" class="btn-radiant btn-vendor px-12 py-5 text-xs font-black uppercase tracking-[0.3em]">
                        تعيين أول هوية
                    </Link>
                </div>

                <!-- Strategic Pagination -->
                <div v-if="staff.links.length > 3" class="px-10 py-8 bg-slate-900/5 border-t border-white/40 flex justify-between items-center">
                    <div class="text-[9px] font-black text-slate-400 uppercase tracking-widest font-inter opacity-40">Personnel Registry v5.1</div>
                    <nav class="flex gap-2">
                        <Link 
                            v-for="link in staff.links" 
                            :key="link.label"
                            :href="link.url || '#'"
                            class="h-10 min-w-[40px] flex items-center justify-center rounded-xl text-[10px] font-black transition-all border px-4"
                            :class="link.active ? 'bg-vendor text-white border-vendor shadow-lg' : 'bg-white/50 text-slate-400 border-white/60 hover:text-vendor hover:bg-white'"
                            v-html="link.label"
                        />
                    </nav>
                </div>
            </div>
        </div>
    </InstitutionalLayout>
</template>

<style scoped>
.glass-card {
    @apply border border-white/40 shadow-glass rounded-[2.5rem] transition-all duration-500;
}
.glass-card:hover {
    @apply border-white/60 shadow-radiant -translate-y-1;
}
</style>
