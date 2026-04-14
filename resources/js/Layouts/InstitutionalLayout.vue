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
        title: 'الذكاء المركزي',
        items: [
            { name: 'لوحة القيادة', href: route('dashboard'), icon: 'dashboard', current: route().current('dashboard') },
            { name: 'إدارة المشتركين', href: route('crm.clients.index'), icon: 'group', current: route().current('crm.clients.*') },
        ]
    },
    {
        title: 'البنية التحتية',
        items: [
            { name: 'سيرفرات ميكروتيك', href: route('servers.index'), icon: 'dns', current: route().current('servers.*') },
            { name: 'أبراج التغطية', href: route('network.towers.index'), icon: 'router', current: route().current('network.towers.*') },
            { name: 'باقات الخدمة', href: route('broadband.profiles.index'), icon: 'inventory_2', current: route().current('broadband.*') },
            { name: 'النسخ الاحتياطي', href: route('network.backups.index'), icon: 'cloud_sync', current: route().current('network.backups.*') },
        ]
    },
    {
        title: 'العمليات المالية',
        items: [
            { name: 'الفواتير والتحصيل', href: route('accounting.invoices.index'), icon: 'account_balance_wallet', current: route().current('accounting.invoices.*') },
            { name: 'التقارير المالية', href: route('accounting.reports.index'), icon: 'analytics', current: route().current('accounting.reports.*') },
        ]
    },
    {
        title: 'النظام',
        items: [
            { name: 'سجل النشاطات', href: route('activity-logs.index'), icon: 'history', current: route().current('activity-logs.*') },
            { name: 'إعدادات المنصة', href: route('settings.index'), icon: 'settings', current: route().current('settings.*') },
        ]
    }
];
</script>

<template>
    <div class="min-h-screen bg-background text-on-surface flex flex-row-reverse">
        <Head :title="title" />

        <!-- SideNavBar (Right Aligned Institutional Sidebar) -->
        <aside class="h-screen w-72 fixed right-0 top-0 bg-slate-50 flex flex-col z-50 border-l border-outline-variant/10 shadow-sm overflow-hidden">
            <!-- Branding Section -->
            <div class="h-20 flex items-center px-8 border-b border-outline-variant/5 bg-white">
                <div class="flex items-center gap-4">
                    <div class="w-11 h-11 bg-primary rounded-lg flex items-center justify-center text-white text-2xl font-black shadow-lg shadow-primary/20">M</div>
                    <div class="text-right">
                        <span class="text-xl font-black text-primary tracking-tight block leading-none">مدى كيو</span>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mt-1">مركز التحكم المركزي</p>
                    </div>
                </div>
            </div>

            <!-- Scrollable Navigation content -->
            <nav class="flex-grow overflow-y-auto no-scrollbar py-6 space-y-8 px-4">
                <div v-for="group in navigationGroups" :key="group.title">
                    <p class="px-4 mb-3 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">{{ group.title }}</p>
                    <div class="space-y-1">
                        <Link v-for="item in group.items" :key="item.name" :href="item.href"
                            class="flex items-center gap-4 px-4 py-3 rounded-lg transition-all group"
                            :class="[item.current ? 'bg-primary text-white shadow-xl shadow-primary/20' : 'text-slate-600 hover:bg-white hover:text-primary border border-transparent hover:border-outline-variant/10 shadow-sm hover:shadow']">
                            <span class="material-symbols-outlined text-[20px] transition-transform group-hover:scale-110" :style="item.current ? { 'font-variation-settings': '\'FILL\' 1' } : {}">{{ item.icon }}</span>
                            <span class="text-[13px] font-black tracking-tight">{{ item.name }}</span>
                        </Link>
                    </div>
                </div>
            </nav>

            <!-- Bottom Exit & Profile -->
            <div class="mt-auto bg-white border-t border-outline-variant/10 p-5 space-y-4">
                <div class="flex items-center gap-4 p-3 bg-surface-container-low rounded-lg border border-outline-variant/5">
                    <div class="w-10 h-10 rounded-lg bg-primary-fixed flex items-center justify-center text-primary font-black border-2 border-white shadow-sm overflow-hidden">
                         <img v-if="user?.profile_photo_url" :src="user.profile_photo_url" class="w-full h-full object-cover" />
                         <span v-else>{{ user?.name?.substring(0,2) || 'AD' }}</span>
                    </div>
                    <div class="flex-1 min-w-0 text-right">
                        <p class="text-xs font-black text-primary truncate leading-tight">{{ user?.name || 'مدير النظام' }}</p>
                        <p class="text-[9px] text-slate-400 font-black uppercase tracking-widest mt-0.5">مسؤول النظام الأعلى</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <Link href="#" class="flex items-center justify-center h-10 bg-slate-50 text-slate-500 rounded-lg border border-outline-variant/10 hover:text-primary transition-all">
                        <span class="material-symbols-outlined text-[18px]">support_agent</span>
                    </Link>
                    <Link :href="route('logout')" method="post" as="button" class="flex items-center justify-center h-10 bg-error-container/5 text-error rounded-lg border border-error-container/20 hover:bg-error-container/10 transition-all">
                        <span class="material-symbols-outlined text-[18px]">logout</span>
                    </Link>
                </div>
            </div>
        </aside>

        <!-- Main Workspace -->
        <div class="flex-1 mr-72 flex flex-col min-w-0">
            <!-- TopNavBar (Frosted Crystal Intelligence) -->
            <header class="h-16 bg-white/80 backdrop-blur-md border-b border-outline-variant/15 flex flex-row-reverse justify-between items-center px-8 sticky top-0 z-40">
                <!-- Search & Status Context -->
                <div class="flex items-center gap-8 flex-row-reverse">
                    <div class="relative group hidden md:block">
                        <input type="text" 
                            placeholder="بحث استخباراتي سريع..."
                            class="bg-surface-container-low border-none rounded-lg pr-10 pl-4 py-2 text-sm focus:ring-2 focus:ring-primary w-80 transition-all shadow-inner" />
                        <span class="material-symbols-outlined absolute right-3 top-2.5 text-slate-400 text-[18px]">search</span>
                    </div>

                    <div class="flex items-center gap-3 bg-secondary-container/15 text-on-secondary-container px-4 py-1.5 rounded-full border border-secondary-container/30">
                         <div class="w-2 h-2 bg-secondary rounded-full animate-pulse shadow-[0_0_8px_rgba(var(--secondary-rgb),0.5)]"></div>
                         <span class="text-[10px] font-black uppercase tracking-[0.2em]">الشبكة متصلة</span>
                    </div>
                </div>

                <!-- Notification & Monitor Context -->
                <div class="flex items-center gap-6">
                    <div class="flex items-center gap-2">
                         <button class="w-10 h-10 rounded-lg bg-white border border-outline-variant/10 flex items-center justify-center text-slate-400 hover:text-primary hover:border-primary transition-all relative">
                            <span class="material-symbols-outlined text-[20px]">notifications</span>
                            <span class="absolute top-2 right-2 w-2 h-2 bg-error rounded-full border-2 border-white"></span>
                        </button>
                        <button class="w-10 h-10 rounded-lg bg-white border border-outline-variant/10 flex items-center justify-center text-slate-400 hover:text-primary hover:border-primary transition-all">
                            <span class="material-symbols-outlined text-[20px]">monitor_heart</span>
                        </button>
                    </div>
                </div>
            </header>

            <!-- Content Area -->
            <main class="p-8">
                <div class="max-w-[1600px] mx-auto">
                    <slot />
                </div>
            </main>
        </div>
    </div>
</template>

<style>
/* Refined Monolith Layout Overrides */
.no-scrollbar::-webkit-scrollbar {
    display: none;
}
.no-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>
