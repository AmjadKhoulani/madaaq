<script setup>
import { ref } from 'vue';
import { Link, Head } from '@inertiajs/vue3';
import { 
    LayoutDashboard, 
    Users, 
    Network, 
    CreditCard, 
    Settings, 
    Bell, 
    Search,
    ChevronLeft,
    ChevronRight,
    Monitor,
    ShieldCheck,
    LogOut,
    Menu,
    Clock
} from 'lucide-vue-next';

defineProps({
    title: String,
});

const isSidebarCollapsed = ref(false);
const activeTab = ref('dashboard');

// Track if sidebar is hovered for mini-mode expand (Optional)
const isSidebarHovered = ref(false);

const toggleSidebar = () => {
    isSidebarCollapsed.value = !isSidebarCollapsed.value;
};

</script>

<template>
    <div class="min-h-screen bg-[#f8fafc] flex flex-row-reverse overflow-hidden font-sans">
        <Head :title="title" />

        <!-- Institutional Sidebar (Fixed Width) -->
        <aside 
            :class="[
                'ent-sidebar h-screen z-30 flex flex-col shrink-0',
                isSidebarCollapsed ? 'w-20' : 'w-[260px]'
            ]"
            @mouseenter="isSidebarHovered = true"
            @mouseleave="isSidebarHovered = false"
        >
            <!-- System Title / Brand -->
            <div class="h-16 flex items-center px-6 bg-[#0c1427] border-b border-[#1e293b]">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-blue-600 flex items-center justify-center text-white font-black shadow-lg">
                        M
                    </div>
                    <div v-show="!isSidebarCollapsed" class="whitespace-nowrap">
                        <span class="text-base font-bold tracking-tight text-white">مدى كيو</span>
                        <p class="text-[8px] uppercase tracking-widest text-slate-400 font-bold -mt-1">MadaaQ Control</p>
                    </div>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 py-6 overflow-y-auto no-scrollbar px-3 space-y-1">
                <div class="mb-2 px-3 pb-2" v-show="!isSidebarCollapsed">
                    <p class="text-[9px] font-black text-slate-500 uppercase tracking-[0.2em]">العمليات الأساسية</p>
                </div>

                <Link 
                    :href="route('admin.dashboard')"
                    class="flex items-center px-4 py-2.5 rounded-lg transition-none group"
                    :class="route().current('admin.dashboard') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white'"
                >
                    <LayoutDashboard class="w-5 h-5 ml-3 shrink-0" />
                    <span v-show="!isSidebarCollapsed" class="text-sm font-semibold">لوحة التحكم</span>
                </Link>

                <Link 
                    :href="route('crm.clients.index')"
                    class="flex items-center px-4 py-2.5 rounded-lg transition-none group"
                    :class="route().current('crm.clients.*') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white'"
                >
                    <Users class="w-5 h-5 ml-3 shrink-0" />
                    <span v-show="!isSidebarCollapsed" class="text-sm font-semibold">إدارة المشتركين</span>
                </Link>

                <Link 
                    :href="route('network.towers.index')"
                    class="flex items-center px-4 py-2.5 rounded-lg transition-none group"
                    :class="route().current('network.*') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white'"
                >
                    <Network class="w-5 h-5 ml-3 shrink-0" />
                    <span v-show="!isSidebarCollapsed" class="text-sm font-semibold">البنية التحتية</span>
                </Link>

                <div class="mt-6 mb-2 px-3 pb-2" v-show="!isSidebarCollapsed">
                    <p class="text-[9px] font-black text-slate-500 uppercase tracking-[0.2em]">الإدارة المالية</p>
                </div>

                <Link 
                    :href="route('accounting.invoices.index')"
                    class="flex items-center px-4 py-2.5 rounded-lg transition-none group"
                    :class="route().current('accounting.*') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white'"
                >
                    <CreditCard class="w-5 h-5 ml-3 shrink-0" />
                    <span v-show="!isSidebarCollapsed" class="text-sm font-semibold">الفواتير والمالية</span>
                </Link>

                <div class="mt-6 mb-2 px-3 pb-2" v-show="!isSidebarCollapsed">
                    <p class="text-[9px] font-black text-slate-500 uppercase tracking-[0.2em] uppercase">النظام</p>
                </div>

                <Link 
                    :href="route('settings.index')"
                    class="flex items-center px-4 py-2.5 rounded-lg transition-none group"
                    :class="route().current('settings.*') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white'"
                >
                    <Settings class="w-5 h-5 ml-3 shrink-0" />
                    <span v-show="!isSidebarCollapsed" class="text-sm font-semibold">إعدادات المنصة</span>
                </Link>
            </nav>

            <!-- User Brief -->
            <div class="p-4 border-t border-[#1e293b] bg-[#0c1427]">
                <div class="flex items-center gap-3 p-1">
                    <div class="w-10 h-10 rounded-lg bg-slate-800 flex items-center justify-center text-xs font-bold text-white border border-slate-700">AK</div>
                    <div v-show="!isSidebarCollapsed" class="flex-1 min-w-0">
                        <p class="text-[11px] font-bold text-white truncate">أمجد الخولاني</p>
                        <p class="text-[9px] text-slate-400 font-black uppercase tracking-widest">مسؤول النظام</p>
                    </div>
                    <Link v-show="!isSidebarCollapsed" :href="route('logout')" method="post" as="button" class="text-slate-500 hover:text-rose-400">
                        <LogOut class="w-4 h-4" />
                    </Link>
                </div>
            </div>
        </aside>

        <!-- Main Workspace -->
        <div class="flex-1 flex flex-col min-w-0 bg-[#f8fafc]">
            <!-- Action Header -->
            <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-8 shrink-0">
                <div class="flex items-center gap-6">
                    <button @click="toggleSidebar" class="w-10 h-10 flex items-center justify-center text-slate-400 hover:text-slate-900 border border-slate-100 rounded-lg hover:bg-slate-50 transition-none">
                        <Menu v-if="isSidebarCollapsed" class="w-5 h-5" />
                        <ChevronRight v-else class="w-5 h-5" />
                    </button>
                    
                    <div class="h-8 w-px bg-slate-200"></div>

                    <div class="flex items-center gap-2 text-slate-400">
                        <Link :href="route('admin.dashboard')" class="text-xs font-medium hover:text-slate-900 transition-none">الرئيسية</Link>
                        <ChevronLeft class="w-3.5 h-3.5" />
                        <span class="text-xs font-bold text-slate-900">{{ title }}</span>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <div class="flex items-center bg-emerald-50 text-emerald-700 px-3 py-1.5 rounded-lg text-[10px] font-bold border border-emerald-100">
                        <div class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse ml-2"></div>
                        الشبكة مستقرة
                    </div>
                    
                    <div class="h-6 w-px bg-slate-200 mx-2"></div>

                    <div class="flex items-center gap-3 text-slate-500">
                         <Clock class="w-4 h-4" />
                         <span class="text-[11px] font-mono font-bold tracking-tight">13:52 PM</span>
                    </div>
                    
                    <button class="w-10 h-10 flex items-center justify-center text-slate-400 hover:text-slate-900 relative">
                        <Bell class="w-5 h-5" />
                        <span class="absolute top-2.5 right-2.5 w-2 h-2 bg-rose-500 rounded-full border-2 border-white"></span>
                    </button>
                </div>
            </header>

            <!-- Scrollable Page Content -->
            <main class="flex-1 overflow-y-auto">
                <div class="p-10 max-w-[1600px] mx-auto">
                    <slot />
                </div>
                
                <!-- Simple Institutional Footer -->
                <footer class="p-10 border-t border-slate-200 flex flex-col md:flex-row justify-between items-center gap-4">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">© 2026 MadaaQ Enterprise Intelligence. All rights reserved.</p>
                    <div class="flex items-center gap-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                        <a href="#" class="hover:text-slate-900 transition-none">دليل المستخدم</a>
                        <a href="#" class="hover:text-slate-900 transition-none">سياسة الخصوصية</a>
                        <a href="#" class="hover:text-slate-900 transition-none">الدعم الفني</a>
                    </div>
                </footer>
            </main>
        </div>
    </div>
</template>

<style>
/* Hide scrollbar for Chrome, Safari and Opera */
.no-scrollbar::-webkit-scrollbar {
    display: none;
}
</style>
