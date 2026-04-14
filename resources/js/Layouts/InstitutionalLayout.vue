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
    <div class="min-h-screen bg-[var(--app-bg)] flex flex-row-reverse overflow-hidden font-sans">
        <Head :title="title" />

        <!-- Premium Silk Sidebar -->
        <aside 
            :class="[
                'silk-sidebar h-screen z-30 flex flex-col shrink-0 sticky top-0',
                isSidebarCollapsed ? 'w-20' : 'w-[280px]'
            ]"
            @mouseenter="isSidebarHovered = true"
            @mouseleave="isSidebarHovered = false"
        >
            <!-- Brand Intelligence -->
            <div class="h-20 flex items-center px-6 border-b border-white/5 bg-black/10">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center text-white font-black shadow-lg shadow-indigo-600/40 border border-white/10">
                        <Monitor class="w-6 h-6" />
                    </div>
                    <div v-show="!isSidebarCollapsed" class="whitespace-nowrap">
                        <span class="text-lg font-black tracking-tight text-white leading-none block">مدى كيو</span>
                        <p class="text-[9px] uppercase tracking-[0.2em] text-indigo-400 font-bold mt-1">إدارة الشبكات الذكية</p>
                    </div>
                </div>
            </div>

            <!-- Strategic Navigation -->
            <nav class="flex-1 py-8 overflow-y-auto no-scrollbar px-4 space-y-2">
                <div class="mb-4 px-3" v-show="!isSidebarCollapsed">
                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest leading-none">العمليات والتحكم</p>
                </div>

                <Link 
                    :href="route('admin.dashboard')"
                    class="flex items-center px-4 py-3 rounded-xl transition-all duration-300 group relative"
                    :class="route().current('admin.dashboard') ? 'bg-indigo-600 text-white shadow-xl shadow-indigo-600/20' : 'text-slate-400 hover:bg-white/5 hover:text-white'"
                >
                    <LayoutDashboard class="w-5 h-5 ml-4 shrink-0" />
                    <span v-show="!isSidebarCollapsed" class="text-[13px] font-bold">لوحة المعلومات</span>
                    <div v-if="route().current('admin.dashboard')" class="absolute right-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-white rounded-l-full"></div>
                </Link>

                <Link 
                    :href="route('crm.clients.index')"
                    class="flex items-center px-4 py-3 rounded-xl transition-all duration-300 group relative"
                    :class="route().current('crm.clients.*') ? 'bg-indigo-600 text-white shadow-xl shadow-indigo-600/20' : 'text-slate-400 hover:bg-white/5 hover:text-white'"
                >
                    <Users class="w-5 h-5 ml-4 shrink-0" />
                    <span v-show="!isSidebarCollapsed" class="text-[13px] font-bold">إدارة المشتركين</span>
                </Link>

                <Link 
                    :href="route('network.towers.index')"
                    class="flex items-center px-4 py-3 rounded-xl transition-all duration-300 group relative"
                    :class="route().current('network.*') ? 'bg-indigo-600 text-white shadow-xl shadow-indigo-600/20' : 'text-slate-400 hover:bg-white/5 hover:text-white'"
                >
                    <Network class="w-5 h-5 ml-4 shrink-0" />
                    <span v-show="!isSidebarCollapsed" class="text-[13px] font-bold">البنية التحتية</span>
                </Link>

                <div class="mt-10 mb-4 px-3" v-show="!isSidebarCollapsed">
                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest leading-none">الإدارة المالية</p>
                </div>

                <Link 
                    :href="route('accounting.invoices.index')"
                    class="flex items-center px-4 py-3 rounded-xl transition-all duration-300 group relative"
                    :class="route().current('accounting.*') ? 'bg-indigo-600 text-white shadow-xl shadow-indigo-600/20' : 'text-slate-400 hover:bg-white/5 hover:text-white'"
                >
                    <CreditCard class="w-5 h-5 ml-4 shrink-0" />
                    <span v-show="!isSidebarCollapsed" class="text-[13px] font-bold">الفوترة والتحصيل</span>
                </Link>

                <div class="mt-10 mb-4 px-3" v-show="!isSidebarCollapsed">
                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest leading-none">إعدادات النظام</p>
                </div>

                <Link 
                    :href="route('settings.index')"
                    class="flex items-center px-4 py-3 rounded-xl transition-all duration-300 group relative"
                    :class="route().current('settings.*') ? 'bg-indigo-600 text-white shadow-xl shadow-indigo-600/20' : 'text-slate-400 hover:bg-white/5 hover:text-white'"
                >
                    <Settings class="w-5 h-5 ml-4 shrink-0" />
                    <span v-show="!isSidebarCollapsed" class="text-[13px] font-bold">تفضيلات المنصة</span>
                </Link>
            </nav>

            <!-- User Pulse Card -->
            <div class="p-6 border-t border-white/5">
                <div class="glass-surface bg-white/5 border-white/10 rounded-2xl p-4 flex items-center gap-4 transition-all hover:bg-white/10">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-xs font-black text-white shadow-lg border border-white/20">AK</div>
                    <div v-show="!isSidebarCollapsed" class="flex-1 min-w-0">
                        <p class="text-[12px] font-black text-white truncate">أمجد الخولاني</p>
                        <p class="text-[9px] text-indigo-400 font-black uppercase tracking-widest mt-0.5">مسؤول النظام</p>
                    </div>
                </div>
                <Link v-show="!isSidebarCollapsed" :href="route('logout')" method="post" as="button" class="w-full mt-4 py-2.5 rounded-xl text-slate-500 hover:text-rose-400 hover:bg-rose-400/10 flex items-center justify-center gap-2 text-[11px] font-black transition-all">
                    <LogOut class="w-4 h-4" /> تسجيل الخروج
                </Link>
            </div>
        </aside>

        <!-- Main Workspace -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Glass Header -->
            <header class="h-20 glass-surface border-b border-slate-200/50 flex items-center justify-between px-10 shrink-0 sticky top-0 z-20">
                <div class="flex items-center gap-8">
                    <button @click="toggleSidebar" class="w-11 h-11 flex items-center justify-center text-slate-500 hover:text-indigo-600 border border-slate-200 rounded-xl hover:bg-indigo-50 hover:border-indigo-200 transition-all shadow-sm">
                        <Menu v-if="isSidebarCollapsed" class="w-5 h-5" />
                        <ChevronRight v-else class="w-5 h-5" />
                    </button>
                    
                    <div class="h-8 w-px bg-slate-200/60"></div>

                    <div class="flex items-center gap-3 text-slate-400">
                        <Link :href="route('admin.dashboard')" class="text-xs font-bold hover:text-indigo-600 transition-all">الرئيسية</Link>
                        <ChevronLeft class="w-3.5 h-3.5 opacity-50" />
                        <span class="text-xs font-black text-slate-900 tracking-tight">{{ title }}</span>
                    </div>
                </div>

                <div class="flex items-center gap-6">
                    <div class="flex items-center bg-emerald-50 text-emerald-700 px-4 py-2 rounded-xl text-[11px] font-black border border-emerald-100/50 shadow-sm shadow-emerald-500/5">
                        <div class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse ml-3 shadow-lg shadow-emerald-500/40"></div>
                        الشبكة مستقرة
                    </div>
                    
                    <div class="h-6 w-px bg-slate-200/60 mx-2"></div>

                    <div class="flex items-center gap-4 text-slate-600 bg-slate-50 px-4 py-2 rounded-xl border border-slate-200/50">
                         <Clock class="w-4 h-4 text-indigo-500" />
                         <span class="text-[11px] font-mono font-black tracking-tighter">15:40 PM</span>
                    </div>
                    
                    <button class="w-11 h-11 flex items-center justify-center text-slate-500 hover:text-indigo-600 relative group transition-all">
                        <div class="absolute inset-0 bg-indigo-50 rounded-xl scale-0 group-hover:scale-100 transition-transform"></div>
                        <Bell class="w-5 h-5 relative z-10" />
                        <span class="absolute top-3 right-3 w-2.5 h-2.5 bg-rose-500 rounded-full border-2 border-white z-20 shadow-sm"></span>
                    </button>
                </div>
            </header>

            <!-- Silk Workspace -->
            <main class="flex-1 overflow-y-auto">
                <div class="p-12 max-w-[1600px] mx-auto min-h-[calc(100vh-160px)]">
                    <slot />
                </div>
                
                <!-- Premium Institutional Footer -->
                <footer class="p-12 border-t border-slate-200/60 flex flex-col lg:flex-row justify-between items-center gap-6 bg-slate-50/50">
                    <div class="flex items-center gap-4">
                        <div class="w-8 h-8 rounded-lg bg-slate-900 flex items-center justify-center text-white">
                             <ShieldCheck class="w-5 h-5 text-indigo-400" />
                        </div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-loose">
                            حقوق الطبع محفوظة © 2026 ذكاء مدى كيو المؤسساتي.<br/>
                            <span class="text-indigo-400/60">MadaaQ Enterprise Intelligence Node v2.4</span>
                        </p>
                    </div>
                    <div class="flex items-center gap-8 text-[11px] font-black text-slate-500 uppercase tracking-widest">
                        <a href="#" class="hover:text-indigo-600 transition-all border-b-2 border-transparent hover:border-indigo-600 pb-1">دليل المستخدم</a>
                        <a href="#" class="hover:text-indigo-600 transition-all border-b-2 border-transparent hover:border-indigo-600 pb-1">الخصوصية والأمان</a>
                        <a href="#" class="hover:text-indigo-600 transition-all border-b-2 border-transparent hover:border-indigo-600 pb-1">مركز الدعم</a>
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
