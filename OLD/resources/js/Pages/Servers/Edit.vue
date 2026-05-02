<script setup>
import { useForm, Head, Link } from '@inertiajs/vue3';
import InstitutionalLayout from '@/Layouts/InstitutionalLayout.vue';

const props = defineProps({
    server: Object,
    internetSources: Array,
    towers: Array,
});

const form = useForm({
    name: props.server.name,
    ip: props.server.ip,
    api_port: props.server.api_port,
    username: props.server.username,
    password: '',
    internet_source_id: props.server.internet_source_id || '',
    location_tower_id: props.server.location_tower_id || '',
    location: props.server.location || '',
    lat: props.server.lat || '',
    lng: props.server.lng || '',
});

const submit = () => {
    form.put(route('servers.update', props.server.id));
};

</script>

<template>
    <InstitutionalLayout :title="'تعديل ' + server.name">
        <Head :title="'تعديل العقدة: ' + server.name" />

        <div class="max-w-5xl mx-auto pb-24 text-right px-4">
            <!-- Institutional Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-8 mb-12 flex-row-reverse">
                <div class="flex items-center gap-6 justify-end">
                    <div class="text-right">
                        <h1 class="text-3xl font-black text-primary tracking-tight mb-2">تعديل إعدادات السيرفر</h1>
                        <p class="text-slate-500 font-bold text-sm uppercase tracking-wider">ضبط معايير الحوكمة لعقدة: {{ server.name }}</p>
                    </div>
                </div>
                <Link 
                    :href="route('servers.show', server.id)" 
                    class="w-12 h-12 bg-white border border-outline-variant/10 rounded-lg flex items-center justify-center text-slate-400 hover:text-primary transition-all shadow-sm group"
                >
                    <span class="material-symbols-outlined text-[24px] group-hover:translate-x-1 transition-transform">chevron_right</span>
                </Link>
            </div>

            <form @submit.prevent="submit" class="space-y-10">
                <!-- 1. Infrastructure Blueprint Matrix -->
                <div class="surface-card p-10 rounded-xl border border-outline-variant/5 shadow-sm">
                    <div class="flex items-center gap-4 mb-12 justify-end">
                        <h3 class="text-sm font-black text-primary uppercase tracking-widest">بيانات السيرفر والطراز</h3>
                        <div class="w-1.5 h-6 bg-primary rounded-full"></div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-center flex-row-reverse">
                        <div class="space-y-4">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mr-2">المُعرف المنطقي للعقدة (Node Identity)</label>
                            <input v-model="form.name" type="text" class="form-input-monolith h-16 font-black uppercase tracking-tight" required>
                        </div>

                        <!-- Hardware Visual Pod -->
                        <div class="bg-primary p-8 rounded-xl flex items-center gap-8 shadow-xl shadow-primary/20 relative overflow-hidden group/pod">
                            <div class="absolute -top-10 -left-10 w-40 h-40 bg-white/5 rounded-full blur-3xl group-hover/pod:scale-150 transition-transform duration-1000"></div>
                            <div class="w-20 h-20 bg-white/10 rounded-lg flex items-center justify-center p-4 border border-white/10 shrink-0">
                                <span class="material-symbols-outlined text-white/50 text-[36px]">router</span>
                            </div>
                            <div class="min-w-0 text-right">
                                <p class="text-[10px] font-black text-white/40 uppercase tracking-widest mb-1.5 leading-none">الطراز المعتمد</p>
                                <p class="text-sm font-black text-white truncate uppercase">{{ server.device_model?.model_name || 'Generic Core Node' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Connection Governance Stack -->
                <div class="surface-card p-10 rounded-xl border border-outline-variant/5 shadow-sm">
                    <div class="flex items-center gap-4 mb-12 justify-end">
                        <h3 class="text-sm font-black text-primary uppercase tracking-widest">إعدادات الاتصال والوصول</h3>
                        <div class="w-1.5 h-6 bg-secondary rounded-full"></div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-4">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mr-2">بوابة API المشفرة (IP)</label>
                            <input v-model="form.ip" type="text" class="form-input-monolith h-16 font-mono font-black" required>
                        </div>
                        <div class="space-y-4">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mr-2">فهرس منفذ البروتوكول</label>
                            <input v-model="form.api_port" type="number" class="form-input-monolith h-16 font-mono font-black" required>
                        </div>
                        <div class="space-y-4">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mr-2">مُعرف مسؤول الحوكمة</label>
                            <input v-model="form.username" type="text" class="form-input-monolith h-16 font-black" required>
                        </div>
                        <div class="space-y-4">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mr-2">مفتاح الوصول (اتركه فارغاً للاحتفاظ بالقديم)</label>
                            <input v-model="form.password" type="password" class="form-input-monolith h-16 font-mono" placeholder="••••••••••••">
                        </div>
                    </div>
                </div>

                <!-- 3. Geospatial Infrastructure Points -->
                <div class="surface-card p-10 rounded-xl border border-outline-variant/5 shadow-sm border-r-4 border-error">
                    <div class="flex items-center gap-4 mb-12 justify-end">
                        <h3 class="text-sm font-black text-primary uppercase tracking-widest">الموقع المادي والتوزيع الجغرافي</h3>
                        <div class="w-1.5 h-6 bg-error rounded-full"></div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        <div class="space-y-4">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mr-2">مصدر تزويد العمود الفقري (Backbone)</label>
                            <select v-model="form.internet_source_id" class="form-input-monolith h-16 font-black text-[13px] uppercase">
                                <option value="">بوابة عقدة مستقلة (Independent Gateway)</option>
                                <option v-for="source in internetSources" :key="source.id" :value="source.id">
                                    {{ source.name }} ({{ source.type }})
                                </option>
                            </select>
                        </div>

                        <div class="space-y-4">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mr-2">البنية المظلية (البرج)</label>
                            <select v-model="form.location_tower_id" class="form-input-monolith h-16 font-black text-[13px] uppercase">
                                <option value="">توضع في عقدة بديلة</option>
                                <option v-for="tower in towers" :key="tower.id" :value="tower.id">
                                     {{ tower.name }} — 🗼
                                </option>
                            </select>
                        </div>

                        <div class="md:col-span-2 space-y-4">
                             <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mr-2">توصيف الموقع المادي الدقيق</label>
                             <input v-model="form.location" type="text" class="form-input-monolith h-16 text-sm font-bold" placeholder="مثلاً: خزانة التوزيع الثانية">
                        </div>

                        <div class="space-y-4">
                             <label class="text-[10px] font-black text-rose-500 uppercase tracking-widest mr-2">خط العرض (Latitude)</label>
                             <input v-model="form.lat" type="text" class="form-input-monolith h-16 font-mono bg-rose-50/10 border-rose-100/20" placeholder="33.xxx">
                        </div>
                        <div class="space-y-4">
                             <label class="text-[10px] font-black text-emerald-500 uppercase tracking-widest mr-2">خط الطول (Longitude)</label>
                             <input v-model="form.lng" type="text" class="form-input-monolith h-16 font-mono bg-emerald-50/10 border-emerald-100/20" placeholder="36.xxx">
                        </div>
                    </div>
                </div>

                <!-- Strategic Update Submission -->
                <div class="surface-card p-12 bg-slate-900 rounded-xl shadow-2xl overflow-hidden relative group/final">
                    <div class="absolute -bottom-20 -right-20 w-80 h-80 bg-primary/5 rounded-full blur-3xl group-hover/final:scale-150 transition-transform duration-1000"></div>
                    <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-10 flex-row-reverse">
                        <div class="flex items-center gap-8 flex-1 justify-end">
                            <div class="text-right">
                                 <h4 class="text-xl font-black text-white tracking-tight">تأكيد تعديل السيرفر</h4>
                                 <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mt-2">
                                    سيتم إعادة مزامنة بروتوكولات العقدة فور تأطير الالتزام.
                                 </p>
                            </div>
                            <div class="w-20 h-20 bg-white/5 rounded-2xl flex items-center justify-center border border-white/10 shadow-inner group-hover/final:scale-110 transition-transform">
                                <span class="material-symbols-outlined text-white text-[32px]" style="font-variation-settings: 'FILL' 1">terminal</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-6 relative z-10 flex-row-reverse">
                            <Link 
                                :href="route('servers.show', server.id)" 
                                class="px-10 py-5 bg-white/5 text-slate-400 font-black text-[11px] uppercase tracking-widest rounded-lg hover:bg-white/10 transition-all active:scale-95"
                            >
                                تجاهل التعديلات
                            </Link>
                            <button 
                                type="submit" 
                                class="px-14 py-5 bg-primary text-white font-black text-[11px] uppercase tracking-[0.2em] rounded-lg shadow-2xl shadow-primary/20 hover:bg-emerald-600 transition-all hover:scale-[1.05] active:scale-95 flex items-center gap-3"
                                :disabled="form.processing"
                            >
                                <span class="material-symbols-outlined text-[20px]">sync</span>
                                حفظ التغييرات
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </InstitutionalLayout>
</template>

