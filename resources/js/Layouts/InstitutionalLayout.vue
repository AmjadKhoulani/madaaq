<script setup>
import { ref, computed } from 'vue';
import { Link, Head, usePage } from '@inertiajs/vue3';

defineProps({
    title: String,
});

const page = usePage();
const user = computed(() => page.props.auth.user);

const navigationGroups = [
    {
        title: 'الاستخبارات والعمليات',
        items: [
            { name: 'مركز العمليات الرئيسي', href: route('dashboard'), icon: 'dashboard', current: route().current('dashboard') },
            { name: 'حوكمة هويات المشتركين', href: route('crm.clients.index'), icon: 'group', current: route().current('crm.clients.*') },
            { name: 'مركز المراقبة (NOC)', href: route('network.monitoring.index'), icon: 'query_stats', current: route().current('network.monitoring.*') },
        ]
    },
    {
        title: 'البنية التحتية المركزية',
        items: [
            { name: 'العقد البرمجية (Core Nodes)', href: route('servers.index'), icon: 'dns', current: route().current('servers.*') },
            { name: 'محطات البث الراديوي', href: route('network.towers.index'), icon: 'cell_tower', current: route().current('network.towers.*') },
            { name: 'بوابات تزويد البيانات', href: route('network.internet-sources.index'), icon: 'hub', current: route().current('network.internet-sources.*') },
            { name: 'المخطط الطوبولوجي', href: route('network.topology.index'), icon: 'account_tree', current: route().current('network.topology.*') },
        ]
    },
    {
        title: 'إدارة الخدمات والأرشفة',
        items: [
            { name: 'كتالوج باقات الاستهلاك', href: route('broadband.profiles.index'), icon: 'inventory_2', current: route().current('broadband.profiles.*') },
            { name: 'محطة الطرفية (Terminal)', href: route('network.commands.index'), icon: 'terminal', current: route().current('network.commands.*') },
            { name: 'سجل الأرشفة السيادي', href: route('network.backups.index'), icon: 'cloud_sync', current: route().current('network.backups.*') },
        ]
    },
    {
        title: 'الحوكمة المالية والنزاهة',
        items: [
            { name: 'إدارة السيولة والتحصيل', href: route('accounting.invoices.index'), icon: 'account_balance_wallet', current: route().current('accounting.invoices.*') },
            { name: 'استخبارات الأداء المالي', href: route('accounting.reports.index'), icon: 'analytics', current: route().current('accounting.reports.*') },
            { name: 'سجل تتبع النزاهة (Audit)', href: route('activity-logs.index'), icon: 'history_edu', current: route().current('activity-logs.*') },
        ]
    },
    {
        title: 'الضبط والكوادر الفنية',
        items: [
            { name: 'هرمية الكوادر الفنية', href: route('staff.index'), icon: 'badge', current: route().current('staff.*') },
            { name: 'حوكمة إعدادات المادة', href: route('settings.index'), icon: 'settings', current: route().current('settings.*') },
            { name: 'استخبارات زوار الويب', href: route('network.website.analytics'), icon: 'web_traffic', current: route().current('network.website.*') },
        ]
    }
];
</script>

<template>
    <div class="min-h-screen bg-slate-50 text-slate-900 flex flex-row-reverse selection:bg-primary selection:text-white">
        <Head :title="title" />

        <!-- SideNavBar (Right Aligned Sovereign Sidebar) -->
        <aside class="h-screen w-80 fixed right-0 top-0 bg-white flex flex-col z-50 border-l border-outline-variant/10 shadow-[20px_0_50px_rgba(0,0,0,0.02)] overflow-hidden">
            <!-- Strategic Branding Cluster -->
            <div class="h-24 flex items-center px-10 border-b border-slate-50 bg-white/50 backdrop-blur-md">
                <div class="flex items-center gap-5">
                    <div class="w-12 h-12 bg-slate-950 rounded-2xl flex items-center justify-center text-white text-3xl font-black shadow-2xl relative overflow-hidden group">
                        <div class="absolute inset-0 bg-primary/20 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        <span class="relative z-10">M</span>
                    </div>
                    <div class="text-right">
                        <span class="text-2xl font-black text-primary tracking-tight block leading-none">مدى كيو</span>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.3em] mt-2">MadaaQ Infrastructure Matrix</p>
                    </div>
                </div>
            </div>

            <!-- Precision Navigation content -->
            <nav class="flex-grow overflow-y-auto no-scrollbar py-8 space-y-10 px-6">
                <div v-for="group in navigationGroups" :key="group.title">
                    <p class="px-5 mb-5 text-[10px] font-black text-slate-300 uppercase tracking-[0.4em] leading-none">{{ group.title }}</p>
                    <div class="space-y-2">
                        <Link v-for="item in group.items" :key="item.name" :href="item.href"
                            class="flex items-center gap-5 px-5 py-4 rounded-2xl transition-all group relative overflow-hidden"
                            :class="[item.current ? 'bg-primary text-white shadow-2xl shadow-primary/30' : 'text-slate-500 hover:bg-slate-50 hover:text-primary hover:translate-x-[-4px]']">
                            <span class="material-symbols-outlined text-[22px] transition-transform group-hover:scale-110 relative z-10" :style="item.current ? { 'font-variation-settings': '\'FILL\' 1' } : {}">{{ item.icon }}</span>
                            <span class="text-[13px] font-black tracking-tight relative z-10">{{ item.name }}</span>
                            <div v-if="item.current" class="absolute inset-0 bg-gradient-to-l from-white/10 to-transparent"></div>
                        </Link>
                    </div>
                </div>
            </nav>

            <!-- Operational Operator Cluster -->
            <div class="mt-auto bg-slate-950 p-8 space-y-6 rounded-t-[3rem] shadow-[0_-20px_50px_rgba(0,0,0,0.2)] border-t border-white/5">
                <div class="flex items-center gap-5 p-4 bg-white/5 rounded-[1.5rem] border border-white/5 shadow-inner">
                    <div class="w-12 h-12 rounded-xl bg-primary text-white flex items-center justify-center font-black border-2 border-slate-950 shadow-2xl overflow-hidden shadow-primary/20">
                         <img v-if="user?.profile_photo_url" :src="user.profile_photo_url" class="w-full h-full object-cover" />
                         <span v-else class="text-xl">{{ user?.name?.substring(0,1) || 'O' }}</span>
                    </div>
                    <div class="flex-1 min-w-0 text-right">
                        <p class="text-[14px] font-black text-white truncate leading-none mb-2">{{ user?.name || 'مشغل المنظومة' }}</p>
                        <p class="text-[9px] text-white/30 font-black uppercase tracking-[0.3em]">رتبة: مدير المنظومة الأعلى</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <Link :href="route('settings.index')" class="flex items-center justify-center h-12 bg-white/5 text-white/40 rounded-xl border border-white/5 hover:bg-white/10 hover:text-white transition-all group">
                        <span class="material-symbols-outlined text-[20px] group-hover:rotate-45 transition-transform">settings</span>
                    </Link>
                    <Link :href="route('logout')" method="post" as="button" class="flex items-center justify-center h-12 bg-rose-500/10 text-rose-500 rounded-xl border border-rose-500/20 hover:bg-rose-500 hover:text-white transition-all group">
                        <span class="material-symbols-outlined text-[20px] group-hover:translate-x-[-4px] transition-transform">logout</span>
                    </Link>
                </div>
            </div>
        </aside>

        <!-- Dynamic Main Workspace -->
        <div class="flex-1 mr-80 flex flex-col min-w-0">
            <!-- Strategic Intelligence Header (TopNavBar) -->
            <header class="h-20 bg-white/70 backdrop-blur-xl border-b border-slate-100 flex flex-row-reverse justify-between items-center px-10 sticky top-0 z-40">
                <!-- Trace Search & Connectivity Context -->
                <div class="flex items-center gap-10 flex-row-reverse">
                    <div class="relative group hidden xl:block">
                        <input type="text" 
                            placeholder="تـتبع استخباراتي عام (Trace)..."
                            class="bg-slate-50 border-none rounded-2xl pr-14 pl-6 h-12 text-sm font-bold focus:ring-2 focus:ring-primary/20 w-[400px] transition-all shadow-inner placeholder:text-slate-300" />
                        <span class="material-symbols-outlined absolute right-5 top-1/2 -translate-y-1/2 text-slate-300 text-[20px] group-focus-within:text-primary transition-colors">manage_search</span>
                    </div>

                    <div class="flex items-center gap-4 bg-emerald-50 text-emerald-600 px-5 py-2 rounded-full border border-emerald-100 shadow-sm overflow-hidden relative group">
                         <div class="absolute inset-0 bg-emerald-500/5 animate-pulse"></div>
                         <div class="w-2 h-2 bg-emerald-500 rounded-full animate-ping"></div>
                         <span class="text-[10px] font-black uppercase tracking-[0.3em] relative z-10">حالة النواة: متصلة (ONLINE)</span>
                    </div>
                </div>

                <!-- Strategic Contextual Tools -->
                <div class="flex items-center gap-6">
                    <div class="flex items-center gap-3">
                         <button class="w-12 h-12 rounded-2xl bg-white border border-slate-100 flex items-center justify-center text-slate-400 hover:text-primary hover:border-primary transition-all relative shadow-sm group">
                            <span class="material-symbols-outlined text-[24px] group-hover:rotate-12">notifications</span>
                            <span class="absolute top-3.5 right-3.5 w-2.5 h-2.5 bg-rose-500 rounded-full border-2 border-white animate-bounce"></span>
                        </button>
                        <button class="w-12 h-12 rounded-2xl bg-white border border-slate-100 flex items-center justify-center text-slate-400 hover:text-primary hover:border-primary transition-all shadow-sm group">
                            <span class="material-symbols-outlined text-[24px] group-hover:scale-110">monitor_heart</span>
                        </button>
                    </div>
                </div>
            </header>

            <!-- Operational Content Area -->
            <main class="p-10 bg-[radial-gradient(#e2e8f0_1px,transparent_1px)] [background-size:24px_24px]">
                <div class="max-w-[1600px] mx-auto animate-in fade-in duration-1000">
                    <slot />
                </div>
            </main>
        </div>
    </div>
</template>

<style>
/* High-Density Monolith Layout Parity */
.no-scrollbar::-webkit-scrollbar {
    display: none;
}
.no-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
.font-headline {
    font-family: 'Manrope', sans-serif;
}
</style>
