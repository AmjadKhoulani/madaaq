<script setup>
import { ref, computed } from 'vue';
import { Link, Head, usePage } from '@inertiajs/vue3';
import { 
    LayoutDashboard, 
    Users, 
    Zap, 
    Activity, 
    Server, 
    TowerControl, 
    Network, 
    Terminal, 
    CloudSync, 
    Wallet, 
    BarChart3, 
    History, 
    Badge, 
    Settings, 
    Globe,
    Bell,
    Heart,
    Search,
    LogOut,
    Building2
} from 'lucide-vue-next';

defineProps({
    title: String,
});

const page = usePage();
const user = computed(() => page.props.auth.user);

const navigationGroups = [
    {
        title: 'الاستخبارات والعمليات',
        items: [
            { name: 'مركز العمليات الرئيسي', href: route('dashboard'), icon: 'LayoutDashboard', current: route().current('dashboard') },
            { name: 'حوكمة هويات المشتركين', href: route('crm.clients.index'), icon: 'Users', current: route().current('crm.clients.*') },
            { name: 'مركز المراقبة (NOC)', href: route('network.monitoring.index'), icon: 'Activity', current: route().current('network.monitoring.*') },
        ]
    },
    {
        title: 'البنية التحتية المركزية',
        items: [
            { name: 'العقد البرمجية (Core Nodes)', href: route('servers.index'), icon: 'Server', current: route().current('servers.*') },
            { name: 'محطات البث الراديوي', href: route('network.towers.index'), icon: 'TowerControl', current: route().current('network.towers.*') },
            { name: 'بوابات تزويد البيانات', href: route('network.internet-sources.index'), icon: 'Activity', current: route().current('network.internet-sources.*') },
            { name: 'المخطط الطوبولوجي', href: route('network.topology.index'), icon: 'Network', current: route().current('network.topology.*') },
        ]
    },
    {
        title: 'إدارة الخدمات والأرشفة',
        items: [
            { name: 'كتالوج باقات الاستهلاك', href: route('broadband.profiles.index'), icon: 'Zap', current: route().current('broadband.profiles.*') },
            { name: 'محطة الطرفية (Terminal)', href: route('network.commands.index'), icon: 'Terminal', current: route().current('network.commands.*') },
            { name: 'سجل الأرشفة السيادي', href: route('network.backups.index'), icon: 'CloudSync', current: route().current('network.backups.*') },
        ]
    },
    {
        title: 'الحوكمة المالية والنزاهة',
        items: [
            { name: 'إدارة السيولة والتحصيل', href: route('accounting.invoices.index'), icon: 'Wallet', current: route().current('accounting.invoices.*') },
            { name: 'استخبارات الأداء المالي', href: route('accounting.reports.index'), icon: 'BarChart3', current: route().current('accounting.reports.*') },
            { name: 'سجل تتبع النزاهة (Audit)', href: route('activity-logs.index'), icon: 'History', current: route().current('activity-logs.*') },
        ]
    },
    {
        title: 'الضبط والكوادر الفنية',
        items: [
            { name: 'هرمية الكوادر الفنية', href: route('staff.index'), icon: 'Badge', current: route().current('staff.*') },
            { name: 'حوكمة إعدادات المادة', href: route('settings.index'), icon: 'Settings', current: route().current('settings.*') },
            { name: 'استخبارات زوار الويب', href: route('network.website.analytics'), icon: 'Activity', current: route().current('network.website.*') },
        ]
    }
];

// Simple icon mapper for lucide
const icons = {
    LayoutDashboard, Users, Zap, Activity, Server, TowerControl, Network, Terminal, CloudSync, Wallet, BarChart3, History, Badge, Settings, Building2
};
</script>

<template>
    <div class="h-full flex relative">
        <Head :title="title" />

        <!-- SideNavBar: Radiant Glass Sidebar -->
        <aside class="fixed inset-y-0 right-0 z-50 w-80 glass-sidebar flex flex-col transition-transform duration-500 cubic-bezier(0.4, 0, 0.2, 1) lg:static lg:translate-x-0">
            
            <!-- Branding Unit -->
            <div class="h-24 flex items-center px-10 border-b border-white/40 shrink-0">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-slate-950 rounded-2xl flex items-center justify-center text-white text-3xl font-black shadow-2xl shadow-vendor/20 transform rotate-2">
                        M
                    </div>
                    <div>
                        <span class="text-2xl font-black text-slate-900 tracking-tighter">مدى<span class="text-vendor">Q</span></span>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.3em] mt-1 font-inter">INFRASTRUCTURE MATRIX</p>
                    </div>
                </div>
            </div>

            <!-- Navigation Matrix -->
            <nav class="flex-grow overflow-y-auto custom-scrollbar py-8 space-y-10 px-6">
                <div v-for="group in navigationGroups" :key="group.title">
                    <p class="px-5 mb-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.4em] leading-none font-inter opacity-60">{{ group.title }}</p>
                    <div class="space-y-1.5">
                        <Link v-for="item in group.items" :key="item.name" :href="item.href"
                            class="flex items-center gap-4 px-5 py-3.5 rounded-2xl transition-all group relative overflow-hidden"
                            :class="[item.current ? 'bg-vendor text-white shadow-vendor-glow translate-x-[-4px]' : 'text-slate-500 hover:bg-white/60 hover:text-vendor']">
                            <component :is="icons[item.icon] || Activity" class="h-5 w-5 shrink-0 transition-transform group-hover:scale-110" />
                            <span class="text-[13px] font-black tracking-tight">{{ item.name }}</span>
                        </Link>
                    </div>
                </div>
            </nav>

            <!-- Operator Control Unit -->
            <div class="mt-auto bg-slate-900 p-8 space-y-6 rounded-t-[3rem] shadow-[0_-20px_50px_rgba(0,0,0,0.2)] border-t border-white/5 shrink-0">
                <div class="flex items-center gap-4 p-4 bg-white/5 rounded-2xl border border-white/5 shadow-inner">
                    <div class="w-12 h-12 rounded-xl bg-radiant-indigo text-white flex items-center justify-center font-black border-2 border-slate-900 shadow-xl overflow-hidden">
                         <img v-if="user?.profile_photo_url" :src="user.profile_photo_url" class="w-full h-full object-cover" />
                         <span v-else class="text-xl">{{ user?.name?.substring(0,1) || 'O' }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-[14px] font-black text-white truncate leading-none mb-2">{{ user?.name || 'مشغل المنظومة' }}</p>
                        <p class="text-[9px] text-white/30 font-black uppercase tracking-[0.3em] font-inter">Global Operator</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <Link :href="route('settings.index')" class="flex items-center justify-center h-12 bg-white/5 text-white/40 rounded-xl border border-white/5 hover:bg-white/10 hover:text-white transition-all group">
                        <Settings class="h-5 w-5 group-hover:rotate-45 transition-transform" />
                    </Link>
                    <Link :href="route('logout')" method="post" as="button" class="flex items-center justify-center h-12 bg-rose-500/10 text-rose-500 rounded-xl border border-rose-500/20 hover:bg-rose-500 hover:text-white transition-all group">
                        <LogOut class="h-5 w-5 group-hover:translate-x-[-4px] transition-transform" />
                    </Link>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 flex flex-col min-w-0 relative">
            <!-- Strategic Intelligence Header -->
            <header class="h-24 shrink-0 bg-white/40 backdrop-blur-xl border-b border-white/60 flex justify-between items-center px-10 sticky top-0 z-40">
                
                <div class="flex items-center gap-10">
                    <div class="relative group hidden xl:block">
                        <input type="text" 
                            placeholder="تـتبع استخباراتي عام (Trace)..."
                            class="bg-white/50 border-none rounded-2xl pr-14 pl-6 h-12 text-sm font-bold focus:ring-4 focus:ring-vendor/5 w-[400px] transition-all shadow-sm placeholder:text-slate-300" />
                        <Search class="absolute right-5 top-1/2 -translate-y-1/2 text-slate-300 h-5 w-5 group-focus-within:text-vendor transition-colors stroke-[3]" />
                    </div>

                    <div class="flex items-center gap-3 px-5 py-2 rounded-2xl bg-emerald-500/5 border border-emerald-500/10 shadow-sm relative group">
                         <div class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></div>
                         <span class="text-[10px] font-black text-emerald-600 uppercase tracking-[0.3em] font-inter">Core Online</span>
                    </div>
                </div>

                <div class="flex items-center gap-6">
                    <div class="flex items-center gap-3">
                         <button class="w-12 h-12 rounded-2xl bg-white/50 border border-white/60 flex items-center justify-center text-slate-400 hover:text-vendor hover:border-vendor transition-all relative shadow-sm group">
                            <Bell class="h-6 w-6 group-hover:rotate-12" />
                            <span class="absolute top-3.5 right-3.5 w-2.5 h-2.5 bg-rose-500 rounded-full border-2 border-white animate-bounce"></span>
                        </button>
                        <button class="w-12 h-12 rounded-2xl bg-white/50 border border-white/60 flex items-center justify-center text-slate-400 hover:text-vendor hover:border-vendor transition-all shadow-sm group">
                            <Activity class="h-6 w-6 group-hover:scale-110" />
                        </button>
                    </div>
                </div>
            </header>

            <!-- Operational Content Area -->
            <div class="flex-1 overflow-y-auto p-10 custom-scrollbar">
                <div class="max-w-[1600px] mx-auto">
                    <slot />
                </div>
            </div>
        </main>
    </div>
</template>

<style>
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: rgba(148, 163, 184, 0.4);
    border-radius: 9999px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: rgba(148, 163, 184, 0.6);
}
</style>
