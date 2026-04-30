<script setup>
import { ref, computed } from 'vue';
import { Link, Head, usePage } from '@inertiajs/vue3';
import { 
    LayoutDashboard, 
    Users, 
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
    Bell,
    Search,
    LogOut,
    Plus,
    Menu,
    ChevronDown,
    Zap
} from 'lucide-vue-next';

defineProps({
    title: String,
});

const page = usePage();
const user = computed(() => page.props.auth.user);

const navigationGroups = [
    {
        title: 'الأساسية',
        items: [
            { name: 'لوحة التحكم', href: route('dashboard'), icon: 'LayoutDashboard', current: route().current('dashboard') },
            { name: 'المشتركون', href: route('crm.clients.index'), icon: 'Users', current: route().current('crm.clients.*') },
            { name: 'إدارة الشبكة', href: route('network.monitoring.index'), icon: 'Activity', current: route().current('network.monitoring.*') },
        ]
    },
    {
        title: 'البنية التحتية',
        items: [
            { name: 'السيرفرات', href: route('servers.index'), icon: 'Server', current: route().current('servers.*') },
            { name: 'الأبراج والقطاعات', href: route('network.towers.index'), icon: 'TowerControl', current: route().current('network.towers.*') },
        ]
    },
    {
        title: 'المالية',
        items: [
            { name: 'الفواتير', href: route('accounting.invoices.index'), icon: 'Wallet', current: route().current('accounting.invoices.*') },
            { name: 'التقارير', href: route('accounting.reports.index'), icon: 'BarChart3', current: route().current('accounting.reports.*') },
        ]
    }
];

const icons = {
    LayoutDashboard, Users, Activity, Server, TowerControl, Wallet, BarChart3, Settings
};
</script>

<template>
    <div class="h-screen flex bg-slate-50 overflow-hidden" dir="rtl">
        <Head :title="title" />

        <!-- Sidebar: Clean White Sidebar -->
        <aside class="w-72 bg-white border-l border-slate-200 flex flex-col z-50">
            <!-- Brand Unit -->
            <div class="h-20 flex items-center px-8 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-primary rounded-xl flex items-center justify-center text-white shadow-lg shadow-primary/20">
                        <Zap class="w-6 h-6 fill-current" />
                    </div>
                    <div>
                        <span class="text-xl font-black text-slate-800 tracking-tight">مدى <span class="text-primary">كيو</span></span>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest leading-none mt-1">نظام إدارة المزود</p>
                    </div>
                </div>
            </div>

            <!-- Nav Matrix -->
            <nav class="flex-1 overflow-y-auto py-6 px-4 space-y-8">
                <div v-for="group in navigationGroups" :key="group.title">
                    <p class="px-4 mb-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ group.title }}</p>
                    <div class="space-y-1">
                        <Link v-for="item in group.items" :key="item.name" :href="item.href"
                            class="flex items-center gap-4 px-4 py-3 rounded-xl transition-all group"
                            :class="[item.current ? 'bg-primary-soft text-primary font-bold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800']">
                            <component :is="icons[item.icon] || Activity" class="h-5 w-5 shrink-0" :class="item.current ? 'text-primary' : 'text-slate-400 group-hover:text-slate-600'" />
                            <span class="text-[13px]">{{ item.name }}</span>
                        </Link>
                    </div>
                </div>
            </nav>

            <!-- Bottom Action -->
            <div class="p-6 border-t border-slate-100 space-y-4">
                <Link :href="route('crm.clients.create')" class="flex items-center justify-center gap-2 w-full py-3.5 bg-primary text-white rounded-xl font-bold shadow-lg shadow-primary/20 hover:bg-blue-700 transition-all active:scale-95">
                    <Plus class="w-5 h-5" />
                    إضافة مشترك جديد
                </Link>
                <Link :href="route('logout')" method="post" as="button" class="flex items-center gap-3 px-4 py-3 w-full text-slate-500 hover:text-rose-600 transition-colors text-sm font-bold">
                    <LogOut class="w-5 h-5" />
                    تسجيل الخروج
                </Link>
            </div>
        </aside>

        <!-- Main Workspace -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Header -->
            <header class="h-20 bg-white border-b border-slate-200 flex items-center justify-between px-10 sticky top-0 z-40">
                <div class="flex items-center gap-8 flex-1">
                    <div class="relative w-96">
                        <Search class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-300 w-4 h-4" />
                        <input type="text" placeholder="البحث عن محطة، مشترك، أو عنوان IP..." 
                            class="w-full bg-slate-50 border-slate-200 rounded-xl pr-12 py-2.5 text-sm focus:ring-4 focus:ring-primary/5 focus:border-primary transition-all">
                    </div>
                </div>

                <div class="flex items-center gap-6">
                    <button class="relative p-2 text-slate-400 hover:text-primary transition-colors">
                        <Bell class="w-6 h-6" />
                        <span class="absolute top-2 right-2 w-2 h-2 bg-rose-500 rounded-full border-2 border-white"></span>
                    </button>
                    <div class="flex items-center gap-3 px-3 py-1.5 rounded-xl hover:bg-slate-50 transition-all cursor-pointer border border-transparent hover:border-slate-100">
                        <div class="text-right">
                            <p class="text-sm font-black text-slate-800 leading-none mb-1">{{ user?.name || 'المدير العام' }}</p>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">أحمد المدير</p>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-slate-900 border-2 border-white shadow-sm flex items-center justify-center text-white font-black overflow-hidden">
                             <img v-if="user?.profile_photo_url" :src="user.profile_photo_url" class="w-full h-full object-cover" />
                             <span v-else>{{ user?.name?.substring(0,1) || 'A' }}</span>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content Area -->
            <main class="flex-1 overflow-y-auto p-10">
                <div class="max-w-7xl mx-auto">
                    <slot />
                </div>
            </main>
        </div>
    </div>
</template>

<style>
/* Custom scrollbar matching reference */
::-webkit-scrollbar {
    width: 6px;
}
::-webkit-scrollbar-track {
    background: transparent;
}
::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 10px;
}
::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}
</style>
