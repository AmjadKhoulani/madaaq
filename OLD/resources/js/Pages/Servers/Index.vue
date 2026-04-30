<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import InstitutionalLayout from '@/Layouts/InstitutionalLayout.vue';
import axios from 'axios';
import { 
    Activity,
    CheckCircle2,
    Cpu,
    Plus,
    Power,
    RefreshCcw,
    Server,
    Settings2,
    Terminal,
    MapPin,
    Zap,
    XCircle,
    Monitor,
    Shield
} from 'lucide-vue-next';

const props = defineProps({
    servers: Array,
});

const testingStatus = ref({});

const testConnection = async (serverId) => {
    testingStatus.value[serverId] = 'testing';
    
    try {
        const response = await axios.post(route('servers.test-connection', serverId));
        if (response.data.success) {
            testingStatus.value[serverId] = 'success';
        } else {
            testingStatus.value[serverId] = 'error';
        }
    } catch (error) {
        testingStatus.value[serverId] = 'error';
    } finally {
        setTimeout(() => {
            delete testingStatus.value[serverId];
        }, 5000);
    }
};

const deleteServer = (serverId) => {
    if (confirm('هل أنت متأكد من رغبتك في حذف هذا السيرفر؟ قد يؤدي ذلك لانقطاع الاتصال بالعقد المرتبطة.')) {
        router.delete(route('servers.destroy', serverId), {
            preserveScroll: true,
        });
    }
};
</script>

<template>
    <InstitutionalLayout title="إدارة السيرفرات والعقد">
        <div class="space-y-8 pb-20">
            
            <!-- Page Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-black text-slate-900">إدارة السيرفرات والعقد</h1>
                    <p class="text-slate-400 font-bold text-xs mt-1">مراقبة وإدارة أجهزة الميكروتيك المركزية وبوابات العبور</p>
                </div>
                <Link :href="route('servers.create')" class="btn-primary flex items-center gap-2 px-8 py-2.5">
                    <Plus class="w-5 h-5" />
                    إضافة سيرفر جديد
                </Link>
            </div>

            <!-- Server Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <div v-for="server in servers" :key="server.id" class="clean-card p-6 group flex flex-col h-full">
                    
                    <!-- Server Header -->
                    <div class="flex items-start justify-between mb-6">
                        <div class="w-14 h-14 bg-primary-soft text-primary rounded-2xl flex items-center justify-center border border-primary/10 shadow-sm group-hover:scale-105 transition-transform">
                             <Server class="w-7 h-7" />
                        </div>
                        <div class="flex items-center gap-2">
                             <span class="text-[9px] font-black text-emerald-500 bg-emerald-50 px-2 py-1 rounded-lg border border-emerald-100">نشط الآن</span>
                             <div class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></div>
                        </div>
                    </div>

                    <!-- Server Info -->
                    <div class="flex-1 space-y-4">
                        <div>
                            <h3 class="text-xl font-black text-slate-800 leading-tight truncate">{{ server.name }}</h3>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">
                                {{ server.device_model?.model_name || 'MikroTik Cloud Core' }}
                            </p>
                        </div>

                        <div class="grid grid-cols-2 gap-4 py-4 border-y border-slate-50">
                            <div>
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">العنوان الرقمي</p>
                                <p class="text-sm font-black text-primary font-inter">{{ server.ip }}</p>
                            </div>
                            <div class="border-r border-slate-100 pr-4">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">المنفذ</p>
                                <p class="text-sm font-black text-slate-600 font-inter">{{ server.api_port }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 text-slate-500">
                             <MapPin class="w-3 h-3" />
                             <span class="text-[11px] font-bold">{{ server.location || 'موقع غير محدد' }}</span>
                        </div>
                    </div>

                    <!-- Actions Area -->
                    <div class="mt-8 space-y-3 pt-6 border-t border-slate-50">
                        <button 
                            @click="testConnection(server.id)"
                            :disabled="testingStatus[server.id] === 'testing'"
                            class="w-full py-3 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all border flex items-center justify-center gap-2"
                            :class="{
                                'bg-slate-50 text-slate-400 border-slate-100 hover:bg-slate-100': !testingStatus[server.id],
                                'bg-primary-soft text-primary border-primary/20 animate-pulse': testingStatus[server.id] === 'testing',
                                'bg-rose-50 text-rose-500 border-rose-100': testingStatus[server.id] === 'error',
                                'bg-emerald-50 text-emerald-600 border-emerald-100': testingStatus[server.id] === 'success'
                            }"
                        >
                            <RefreshCcw v-if="testingStatus[server.id] === 'testing'" class="w-3 h-3 animate-spin" />
                            <Zap v-else class="w-3 h-3" />
                            {{ testingStatus[server.id] === 'testing' ? 'جاري الفحص...' : (testingStatus[server.id] === 'error' ? 'خطأ في الاتصال' : (testingStatus[server.id] === 'success' ? 'اتصال مستقر' : 'اختبار الاتصال')) }}
                        </button>

                        <div class="grid grid-cols-3 gap-3">
                            <Link :href="route('servers.show', server.id)" class="bg-slate-900 text-white rounded-xl h-11 flex items-center justify-center hover:bg-primary transition-colors">
                                <Terminal class="w-4 h-4" />
                            </Link>
                            <Link :href="route('servers.edit', server.id)" class="bg-slate-50 text-slate-400 border border-slate-100 rounded-xl h-11 flex items-center justify-center hover:bg-amber-50 hover:text-amber-500 transition-colors">
                                <Settings2 class="w-4 h-4" />
                            </Link>
                            <button @click="deleteServer(server.id)" class="bg-slate-50 text-slate-400 border border-slate-100 rounded-xl h-11 flex items-center justify-center hover:bg-rose-50 hover:text-rose-600 transition-colors">
                                <Power class="w-4 h-4" />
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-if="servers.length === 0" class="col-span-full py-32 flex flex-col items-center gap-6 text-center">
                    <div class="w-24 h-24 bg-slate-50 rounded-[2rem] flex items-center justify-center text-slate-200 border border-slate-100">
                        <Monitor class="w-12 h-12" />
                    </div>
                    <div>
                        <h3 class="text-2xl font-black text-slate-800">لا يوجد سيرفرات مضافة</h3>
                        <p class="text-sm font-bold text-slate-400 mt-2">ابدأ بإضافة أول سيرفر ميكروتيك لربط الشبكة</p>
                    </div>
                    <Link :href="route('servers.create')" class="btn-primary px-10 py-3 text-xs">
                        إضافة سيرفر الآن
                    </Link>
                </div>
            </div>
        </div>
    </InstitutionalLayout>
</template>
