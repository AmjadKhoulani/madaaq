<script setup>
import { ref } from 'vue';
import { Link, Head } from '@inertiajs/vue3';

defineProps({
    title: String,
});

const navigation = [
    { name: 'لوحة القيادة', href: route('vendor.dashboard'), icon: 'dashboard', current: route().current('vendor.dashboard') },
    { name: 'إدارة العملاء', href: route('crm.clients.index'), icon: 'group', current: route().current('crm.clients.*') },
    { name: 'الشبكة', href: '#', icon: 'router', current: false },
    { name: 'الفوترة', href: route('accounting.invoices.index'), icon: 'account_balance_wallet', current: route().current('accounting.*') },
    { name: 'الإعدادات', href: route('settings.index'), icon: 'settings', current: route().current('settings.*') },
];
</script>

<template>
    <div class="min-h-screen bg-background text-on-surface flex flex-row-reverse">
        <Head :title="title" />

        <!-- SideNavBar (Right Aligned Monolith) -->
        <aside class="h-screen w-72 fixed right-0 top-0 bg-slate-50 flex flex-col py-6 z-50 sidebar-monolith">
            <div class="px-8 mb-10">
                <h1 class="text-2xl font-black text-primary tracking-tight">مدى كيو</h1>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">إدارة ISP الذكية</p>
            </div>

            <nav class="flex-grow space-y-1">
                <Link v-for="item in navigation" :key="item.name" :href="item.href"
                    :class="[item.current ? 'nav-link-active' : 'nav-link-inactive']">
                    <span class="material-symbols-outlined text-[22px]">{{ item.icon }}</span>
                    <span class="text-[14px] font-bold">{{ item.name }}</span>
                </Link>
            </nav>

            <div class="mt-auto border-t border-outline-variant/15 pt-4">
                <Link href="#" class="nav-link-inactive">
                    <span class="material-symbols-outlined text-[20px]">support_agent</span>
                    <span class="text-[13px] font-bold">الدعم التقني</span>
                </Link>
                <Link :href="route('logout')" method="post" as="button" class="w-full nav-link-inactive hover:text-error hover:bg-error-container/10">
                    <span class="material-symbols-outlined text-[20px]">logout</span>
                    <span class="text-[13px] font-bold">خروج</span>
                </Link>
            </div>
        </aside>

        <!-- Main Workspace -->
        <div class="flex-1 mr-72 flex flex-col min-w-0">
            <!-- TopNavBar (Frosted Crystal) -->
            <header class="h-16 bg-white/80 backdrop-blur-md border-b border-outline-variant/15 flex flex-row-reverse justify-between items-center px-8 sticky top-0 z-40">
                <!-- Search & Profile Context -->
                <div class="flex items-center gap-6 flex-row-reverse">
                    <div class="relative group">
                        <input type="text" 
                            placeholder="بحث سريع..."
                            class="bg-surface-container-low border-none rounded-lg pr-10 pl-4 py-2 text-sm focus:ring-2 focus:ring-primary w-64 transition-all" />
                        <span class="material-symbols-outlined absolute right-3 top-2 text-slate-400 text-[20px]">search</span>
                    </div>
                    
                    <div class="flex items-center gap-4">
                        <button class="text-slate-500 hover:text-primary transition-all relative">
                            <span class="material-symbols-outlined">notifications</span>
                            <span class="absolute top-0 right-0 w-2 h-2 bg-error rounded-full"></span>
                        </button>
                    </div>
                </div>

                <!-- Identity Context -->
                <div class="flex items-center gap-4">
                    <div class="text-left">
                        <p class="text-sm font-bold text-primary leading-tight">{{ $page.props.auth.user.name }}</p>
                        <p class="text-[10px] text-slate-500 font-headline uppercase tracking-widest mt-0.5">مسؤول المدوّنة</p>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-primary-fixed flex items-center justify-center text-primary font-bold border-2 border-white shadow-sm overflow-hidden">
                        <img v-if="$page.props.auth.user.profile_photo_url" :src="$page.props.auth.user.profile_photo_url" class="w-full h-full object-cover" />
                        <span v-else>{{ $page.props.auth.user.name.substring(0,2) }}</span>
                    </div>
                </div>
            </header>

            <!-- Content Area -->
            <main class="p-8">
                <slot />
            </main>
        </div>
    </div>
</template>

<style>
/* Refined Typography for Monolith design */
.font-body {
    letter-spacing: -0.01em;
}
</style>
