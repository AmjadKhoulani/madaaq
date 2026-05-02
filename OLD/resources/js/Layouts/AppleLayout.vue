<script setup>
import { ref } from 'vue';
import { Link, Head, usePage } from '@inertiajs/vue3';
import { 
    LayoutDashboard, 
    Users, 
    Network, 
    CreditCard, 
    Settings, 
    Bell, 
    Search,
    Menu,
    X,
    Database,
    Zap,
    LifeBuoy,
    LogOut,
    Plus,
    Activity,
    TowerControl as TowerIcon,
    Server as ServerIcon,
    Wifi
} from 'lucide-vue-next';

defineProps({
    title: String,
});

const isSidebarOpen = ref(false);
const page = usePage();
const user = page.props.auth.user;

</script>

<template>
    <div class="h-full flex relative">
        <Head :title="title" />

        <!-- Mobile Sidebar Backdrop -->
        <div v-show="isSidebarOpen" 
             class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-40 lg:hidden" 
             @click="isSidebarOpen = false"></div>

        <!-- Sidebar: Glass Architecture -->
        <aside 
            :class="[
                'fixed inset-y-0 right-0 z-50 w-72 glass-sidebar transition-transform duration-500 cubic-bezier(0.4, 0, 0.2, 1) lg:static lg:flex lg:flex-col',
                isSidebarOpen ? 'translate-x-0' : 'translate-x-full lg:translate-x-0'
            ]"
        >
            <!-- Logo Section -->
            <div class="h-20 flex items-center gap-3 px-8 border-b border-white/40 shrink-0">
                <div class="flex items-center justify-center w-11 h-11 rounded-2xl bg-radiant-indigo text-white shadow-lg shadow-vendor/30 transform rotate-2">
                    <Zap class="w-6 h-6 fill-white/20" />
                </div>
                <div>
                    <span class="text-2xl font-black tracking-tighter text-slate-900">مدى<span class="text-vendor">Q</span></span>
                    <p class="text-[9px] uppercase tracking-[0.2em] text-slate-400 font-black -mt-1 font-inter">COMMAND CENTER</p>
                </div>
                
                <button @click="isSidebarOpen = false" class="mr-auto lg:hidden text-slate-400 hover:text-vendor transition-colors">
                    <X class="w-6 h-6" />
                </button>
            </div>

            <!-- Main Navigation -->
            <nav class="flex-1 overflow-y-auto px-4 py-6 space-y-1.5 custom-scrollbar">
                
                <Link 
                    :href="route('dashboard')"
                    class="group flex items-center gap-x-3 rounded-2xl px-4 py-3.5 text-sm font-bold transition-all duration-300"
                    :class="route().current('dashboard') ? 'bg-vendor text-white shadow-vendor-glow translate-x-[-4px]' : 'text-slate-500 hover:bg-white/60 hover:text-vendor'"
                >
                    <LayoutDashboard :class="['h-5 w-5 shrink-0', route().current('dashboard') ? 'text-white' : 'text-slate-400 group-hover:text-vendor']" />
                    لوحة التحكم
                </Link>

                <!-- Network Management -->
                <div class="pt-4 pb-2 px-6">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">إدارة البنية التحتية</p>
                </div>

                <Link 
                    :href="route('servers.index')"
                    class="group flex items-center gap-x-3 rounded-2xl px-4 py-3.5 text-sm font-bold transition-all duration-300"
                    :class="route().current('servers.*') ? 'bg-vendor text-white shadow-vendor-glow translate-x-[-4px]' : 'text-slate-500 hover:bg-white/60 hover:text-vendor'"
                >
                    <ServerIcon :class="['h-5 w-5 shrink-0', route().current('servers.*') ? 'text-white' : 'text-slate-400 group-hover:text-vendor']" />
                    السيرفرات المركزية
                </Link>

                <Link 
                    :href="route('network.towers.index')"
                    class="group flex items-center gap-x-3 rounded-2xl px-4 py-3.5 text-sm font-bold transition-all duration-300"
                    :class="route().current('network.towers.*') ? 'bg-vendor text-white shadow-vendor-glow translate-x-[-4px]' : 'text-slate-500 hover:bg-white/60 hover:text-vendor'"
                >
                    <TowerIcon :class="['h-5 w-5 shrink-0', route().current('network.towers.*') ? 'text-white' : 'text-slate-400 group-hover:text-vendor']" />
                    الأبراج والمواقع
                </Link>

                <!-- Customers Section -->
                <div class="pt-4 pb-2 px-6">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">المشتركين والخدمات</p>
                </div>

                <Link 
                    :href="route('crm.clients.index')"
                    class="group flex items-center gap-x-3 rounded-2xl px-4 py-3.5 text-sm font-bold transition-all duration-300"
                    :class="route().current('crm.clients.*') ? 'bg-vendor text-white shadow-vendor-glow translate-x-[-4px]' : 'text-slate-500 hover:bg-white/60 hover:text-vendor'"
                >
                    <Users :class="['h-5 w-5 shrink-0', route().current('crm.clients.*') ? 'text-white' : 'text-slate-400 group-hover:text-vendor']" />
                    دليل المشتركين
                </Link>

                <Link 
                    :href="route('broadband.users.index')"
                    class="group flex items-center gap-x-3 rounded-2xl px-4 py-3.5 text-sm font-bold transition-all duration-300"
                    :class="route().current('broadband.users.*') ? 'bg-vendor text-white shadow-vendor-glow translate-x-[-4px]' : 'text-slate-500 hover:bg-white/60 hover:text-vendor'"
                >
                    <Activity :class="['h-5 w-5 shrink-0', route().current('broadband.users.*') ? 'text-white' : 'text-slate-400 group-hover:text-vendor']" />
                    اشتراكات البرودباند
                </Link>

                <Link 
                    :href="route('accounting.invoices.index')"
                    class="group flex items-center gap-x-3 rounded-2xl px-4 py-3.5 text-sm font-bold transition-all duration-300"
                    :class="route().current('accounting.invoices.*') ? 'bg-vendor text-white shadow-vendor-glow translate-x-[-4px]' : 'text-slate-500 hover:bg-white/60 hover:text-vendor'"
                >
                    <CreditCard :class="['h-5 w-5 shrink-0', route().current('accounting.invoices.*') ? 'text-white' : 'text-slate-400 group-hover:text-vendor']" />
                    المالية والفواتير
                </Link>

            </nav>

            <!-- User Context -->
            <div class="border-t border-white/40 p-6 shrink-0">
                <div class="flex items-center gap-4 px-2">
                    <div class="h-11 w-11 shrink-0 rounded-2xl bg-radiant-indigo flex items-center justify-center text-white font-black shadow-lg">
                        {{ user.name.charAt(0) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="truncate text-sm font-black text-slate-900">{{ user.name }}</p>
                        <p class="truncate text-[10px] text-slate-400 font-black uppercase tracking-widest">Network Architect</p>
                    </div>
                    <Link :href="route('logout')" method="post" as="button" class="text-slate-300 hover:text-red-500 transition-colors">
                        <LogOut class="h-5 w-5 stroke-[2.5]" />
                    </Link>
                </div>
            </div>
        </aside>

        <!-- Main Viewport -->
        <main class="flex-1 flex flex-col min-w-0 relative h-full">
            
            <!-- Ultra-Clean Header -->
            <header class="h-20 shrink-0 flex items-center justify-between gap-x-6 bg-white/40 backdrop-blur-md px-8 border-b border-white/60 z-30">
                
                <div class="flex flex-1 items-center gap-x-4">
                    <button @click="isSidebarOpen = true" class="p-2 text-slate-400 lg:hidden">
                        <Menu class="h-6 w-6" />
                    </button>
                    
                    <div class="relative w-full max-w-md hidden sm:block">
                        <Search class="absolute inset-y-0 right-4 h-full w-4 text-slate-400 stroke-[3]" />
                        <input class="block w-full border-0 bg-white/50 rounded-2xl py-3 pr-11 text-sm font-bold placeholder:text-slate-400 focus:ring-4 focus:ring-vendor/5 focus:bg-white transition-all shadow-sm" placeholder="ابحث في المنظومة..." type="search">
                    </div>
                </div>

                <div class="flex items-center gap-x-6">
                    <!-- Status Badge -->
                    <div class="hidden md:flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-500/5 border border-emerald-500/10">
                        <span class="flex h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span class="text-[10px] font-black text-emerald-600 uppercase tracking-widest">Network Live</span>
                    </div>

                    <button class="relative text-slate-400 hover:text-vendor transition-colors">
                        <span class="absolute -top-1 -right-1 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-[10px] font-black text-white ring-2 ring-white">3</span>
                        <Bell class="h-6 w-6" />
                    </button>

                    <div class="h-8 w-px bg-slate-200"></div>

                    <Link :href="route('crm.clients.create')" class="hidden md:flex items-center gap-2 btn-radiant btn-vendor px-5 py-2.5 text-xs font-black uppercase tracking-wider">
                        <Plus class="w-4 h-4 stroke-[3]" />
                        إضافة مشترك
                    </Link>
                </div>
            </header>

            <!-- Main Content Core -->
            <div class="flex-1 overflow-y-auto p-8 custom-scrollbar">
                <div class="max-w-[1600px] mx-auto">
                    <slot />
                </div>
            </div>
        </main>
    </div>
</template>

<style>
/* Custom Scrollbar */
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    @apply bg-slate-300/40 rounded-full hover:bg-slate-400/60;
}
</style>
