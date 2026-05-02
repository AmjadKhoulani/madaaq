<script setup>
import { ref, watch, onMounted } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import InstitutionalLayout from '@/Layouts/InstitutionalLayout.vue';
import { 
    Activity,
    TowerControl,
    CheckCircle2,
    AlertTriangle,
    Wifi,
    MapPin,
    Plus,
    Search,
    Download,
    Eye,
    Edit3,
    Trash2,
    Clock,
    Zap,
    Map
} from 'lucide-vue-next';
import Chart from 'chart.js/auto';

const props = defineProps({
    towers: Array,
    cities: Array,
    stats: Object,
    filters: Object,
});

const filter = ref({
    city: props.filters.city || 'all',
    status: props.filters.status || 'all',
});

const trafficChartRef = ref(null);

onMounted(() => {
    if (trafficChartRef.value) {
        new Chart(trafficChartRef.value, {
            type: 'bar',
            data: {
                labels: ['1', '2', '3', '4', '5', '6', '7'],
                datasets: [{
                    label: 'المرور (Gbps)',
                    data: [1.2, 3.5, 4.2, 5.8, 6.2, 4.0, 4.2],
                    backgroundColor: '#0061f2',
                    borderRadius: 4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { border: { display: false }, grid: { color: '#f1f5f9' }, ticks: { font: { size: 10 } } },
                    x: { border: { display: false }, grid: { display: false }, ticks: { font: { size: 10 } } }
                }
            }
        });
    }
});

const getStatusBadge = (status) => {
    switch (status) {
        case 'active': return 'badge-active';
        case 'maintenance': return 'badge-warning';
        default: return 'badge-danger';
    }
};

</script>

<template>
    <InstitutionalLayout title="إدارة الأبراج والقطاعات">
        <div class="space-y-8 pb-20">
            
            <!-- Page Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-black text-slate-900">إدارة الأبراج والقطاعات</h1>
                    <p class="text-slate-400 font-bold text-xs mt-1">مراقبة البنية التحتية والاتصال اللاسلكي في الوقت الفعلي</p>
                </div>
                <div class="flex items-center gap-4">
                    <button class="btn-outline flex items-center gap-2">
                        <MapPin class="w-4 h-4" />
                        عرض ملء الشاشة
                    </button>
                    <Link :href="route('network.towers.create')" class="btn-primary flex items-center gap-2">
                        <Plus class="w-5 h-5" />
                        إضافة موقع جديد
                    </Link>
                </div>
            </div>

            <!-- Stats Overview Grid -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Sites Count -->
                <div class="clean-card p-6 text-center">
                    <div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center text-primary mx-auto mb-4 border border-blue-100">
                        <TowerControl class="w-8 h-8" />
                    </div>
                    <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest mb-1">الأبراج النشطة</p>
                    <h3 class="text-3xl font-black text-slate-800 tracking-tighter">30 / 28</h3>
                    <p class="text-[10px] font-bold text-emerald-500 mt-2">+2 جديد</p>
                </div>

                <!-- Uptime Gauge Placeholder -->
                <div class="clean-card p-6 text-center">
                    <div class="w-16 h-16 relative mx-auto mb-4 flex items-center justify-center">
                         <div class="absolute inset-0 border-4 border-slate-50 rounded-full"></div>
                         <div class="absolute inset-0 border-4 border-amber-500 rounded-full border-t-transparent border-r-transparent -rotate-45"></div>
                         <span class="material-symbols-outlined text-amber-500">schedule</span>
                    </div>
                    <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest mb-1">وقت التشغيل</p>
                    <h3 class="text-3xl font-black text-slate-800 tracking-tighter">142d</h3>
                    <p class="text-[10px] font-bold text-slate-400 mt-2">مثالي</p>
                </div>

                <!-- Resource Usage -->
                <div class="clean-card p-6 text-center">
                    <div class="w-16 h-16 bg-purple-50 rounded-full flex items-center justify-center text-purple-600 mx-auto mb-4 border border-purple-100">
                        <span class="material-symbols-outlined">memory</span>
                    </div>
                    <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest mb-1">استهلاك الرام</p>
                    <h3 class="text-3xl font-black text-slate-800 tracking-tighter">GB 8.4</h3>
                    <div class="w-24 h-1 bg-slate-100 rounded-full mx-auto mt-4 overflow-hidden">
                        <div class="w-[52%] h-full bg-purple-500"></div>
                    </div>
                </div>

                <!-- CPU Load -->
                <div class="clean-card p-6 text-center">
                    <div class="w-16 h-16 relative mx-auto mb-4 flex items-center justify-center">
                         <div class="w-12 h-12 border-4 border-slate-50 rounded-full border-t-emerald-500 rotate-[120deg]"></div>
                         <span class="material-symbols-outlined absolute text-emerald-500 text-sm">check_circle</span>
                    </div>
                    <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest mb-1">حمولة المعالج</p>
                    <h3 class="text-3xl font-black text-slate-800 tracking-tighter">24%</h3>
                    <p class="text-[10px] font-bold text-emerald-500 mt-2">مستقر</p>
                </div>
            </div>

            <!-- Middle Section: Map and Table -->
            <div class="grid grid-cols-12 gap-8">
                <!-- Stations Table -->
                <div class="col-span-12 lg:col-span-5 clean-card overflow-hidden">
                    <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                         <h3 class="text-lg font-black text-slate-900">قائمة المحطات النشطة</h3>
                         <div class="flex gap-2">
                             <button class="p-2 text-slate-400 hover:text-primary"><Download class="w-4 h-4" /></button>
                             <button class="p-2 text-slate-400 hover:text-primary"><Search class="w-4 h-4" /></button>
                         </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="table-clean text-xs">
                            <thead>
                                <tr class="bg-slate-50/50">
                                    <th class="text-right">اسم البرج/القطاع</th>
                                    <th>عنوان IP</th>
                                    <th>SNR</th>
                                    <th>الحالة</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                <tr v-for="tower in towers.slice(0, 5)" :key="tower.id" class="hover:bg-slate-50/30 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-lg bg-blue-50 text-primary flex items-center justify-center">
                                                <Wifi class="w-4 h-4" />
                                            </div>
                                            <div>
                                                <p class="font-black text-slate-800">{{ tower.name }}</p>
                                                <p class="text-[9px] text-slate-400">قطاع الشمال (A)</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center font-mono font-bold text-slate-500">192.168.10.12</td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex items-center justify-center gap-1">
                                            <span class="font-black text-slate-700">dB 28</span>
                                            <div class="flex items-end gap-0.5 h-3">
                                                <div class="w-0.5 h-full bg-primary"></div>
                                                <div class="w-0.5 h-[80%] bg-primary"></div>
                                                <div class="w-0.5 h-[60%] bg-primary"></div>
                                                <div class="w-0.5 h-[40%] bg-slate-200"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span :class="['badge-active', getStatusBadge(tower.status)]">نشط</span>
                                    </td>
                                    <td class="px-6 py-4 text-left">
                                        <button class="text-slate-300 hover:text-slate-600"><MoreVertical class="w-4 h-4" /></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Geography Card -->
                <div class="col-span-12 lg:col-span-7 clean-card overflow-hidden flex flex-col">
                    <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                         <h3 class="text-lg font-black text-slate-900">التغطية الجغرافية</h3>
                         <button class="text-xs font-bold text-primary hover:underline">عرض ملء الشاشة</button>
                    </div>
                    <div class="flex-1 bg-slate-100 relative min-h-[400px]">
                         <!-- Map Placeholder -->
                         <div class="absolute inset-0 flex items-center justify-center opacity-20 flex-col gap-4">
                             <Map class="w-20 h-20" />
                             <p class="font-black text-xl">خريطة الأبراج التفاعلية</p>
                         </div>
                         <!-- Map Legend -->
                         <div class="absolute bottom-6 left-6 bg-white p-4 rounded-xl shadow-lg border border-slate-100 text-[10px] font-bold space-y-2">
                             <div class="flex items-center gap-2"><div class="w-2 h-2 rounded-full bg-emerald-500"></div> برج نشط (إشارة ممتازة)</div>
                             <div class="flex items-center gap-2"><div class="w-2 h-2 rounded-full bg-rose-500"></div> برج معطل (تحت الصيانة)</div>
                         </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Section: Charts and Tickets -->
            <div class="grid grid-cols-12 gap-8">
                <!-- Bandwidth Chart -->
                <div class="col-span-12 lg:col-span-4 clean-card p-8">
                    <div class="flex items-center justify-between mb-8">
                         <h3 class="text-sm font-black text-slate-900">حركة مرور البيانات (الآن)</h3>
                         <div class="flex items-center gap-2">
                             <div class="w-2 h-2 bg-emerald-500 rounded-full animate-ping"></div>
                             <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">مباشر</span>
                         </div>
                    </div>
                    <div class="h-[200px]">
                        <canvas ref="trafficChartRef"></canvas>
                    </div>
                    <div class="mt-6 flex items-baseline gap-2">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Gbps</span>
                        <h4 class="text-3xl font-black text-slate-800 italic tracking-tighter">4.2</h4>
                    </div>
                </div>

                <!-- Frequency Usage -->
                <div class="col-span-12 lg:col-span-4 clean-card p-8">
                    <h3 class="text-sm font-black text-slate-900 mb-8">إشغال الترددات</h3>
                    <div class="space-y-6">
                        <div class="space-y-2">
                            <div class="flex justify-between items-center text-[10px] font-black uppercase tracking-widest">
                                <span class="text-slate-400">5GHz Band</span>
                                <span class="text-slate-800">70%</span>
                            </div>
                            <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                                <div class="w-[70%] h-full bg-primary rounded-full"></div>
                            </div>
                            <p class="text-[10px] font-bold text-slate-400">نسبة الإشغال الحالية</p>
                        </div>
                        <div class="p-4 bg-blue-50/30 border border-primary/5 rounded-xl">
                            <p class="text-[10px] font-bold text-slate-500 leading-relaxed italic">
                                تحذير: قناة 36 تعاني من تداخل بسيط في برج العليا.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Maintenance Tickets -->
                <div class="col-span-12 lg:col-span-4 clean-card p-8">
                    <div class="flex items-center justify-between mb-8">
                        <h3 class="text-sm font-black text-slate-900">تذاكر الصيانة النشطة</h3>
                        <div class="w-8 h-8 bg-slate-50 rounded-lg flex items-center justify-center text-slate-400 font-black text-xs italic">
                            03
                        </div>
                    </div>
                    <div class="space-y-6">
                        <div class="flex items-center justify-between">
                            <div class="flex -space-x-3 rtl:space-x-reverse">
                                <div class="w-8 h-8 rounded-full border-2 border-white bg-slate-200 overflow-hidden"></div>
                                <div class="w-8 h-8 rounded-full border-2 border-white bg-slate-300 overflow-hidden"></div>
                                <div class="w-8 h-8 rounded-full border-2 border-white bg-slate-400 overflow-hidden"></div>
                                <div class="w-8 h-8 rounded-full border-2 border-white bg-slate-50 flex items-center justify-center text-[8px] font-black text-slate-400">+1</div>
                            </div>
                            <button class="text-xs font-black text-primary hover:underline">إدارة التذاكر</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </InstitutionalLayout>
</template>
