<script setup>
import { ref, onMounted } from 'vue';
import { Link, Head } from '@inertiajs/vue3';
import { 
    LayoutDashboard, 
    Users, 
    Network, 
    CreditCard, 
    Settings, 
    Bell, 
    Search,
    Menu,
    ChevronLeft,
    Monitor,
    ShieldCheck
} from 'lucide-vue-next';

defineProps({
    title: String,
});

const isSidebarCollapsed = ref(false);
const activeTab = ref('dashboard');

// Simple multi-pane state
const secondaryPaneVisible = ref(false);

</script>

<template>
    <div class="min-h-screen bg-[var(--app-bg)] flex overflow-hidden">
        <Head :title="title" />

        <!-- Static Sidebar (Desktop) -->
        <aside 
            :class="[
                'apple-sidebar h-screen transition-all duration-500 ease-in-out z-30 flex flex-col',
                isSidebarCollapsed ? 'w-20' : 'w-[280px]'
            ]"
        >
            <!-- Logo Section -->
            <div class="h-20 flex items-center px-6 border-b border-[var(--app-border)]">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-[10px] bg-black dark:bg-white flex items-center justify-center text-white dark:text-black font-black shadow-lg">
                        M
                    </div>
                    <div v-show="!isSidebarCollapsed" class="transition-opacity duration-300">
                        <span class="text-lg font-bold tracking-tight">MadaaQ</span>
                        <p class="text-[9px] uppercase tracking-widest text-[#86868b] font-bold -mt-0.5">Control Center</p>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-3 py-6 space-y-1 overflow-y-auto">
                <div class="mb-4 px-3" v-show="!isSidebarCollapsed">
                    <p class="text-[10px] font-bold text-[#86868b] uppercase tracking-widest">Core Intelligence</p>
                </div>

                <Link 
                    :href="route('admin.dashboard')"
                    class="flex items-center px-4 py-2.5 rounded-[10px] transition-all group"
                    :class="activeTab === 'dashboard' ? 'bg-[var(--app-accent)] text-white shadow-md' : 'text-[var(--app-text)] hover:bg-black/5'"
                >
                    <LayoutDashboard :class="['w-5 h-5 ml-4', activeTab === 'dashboard' ? 'text-white' : 'text-[#86868b] group-hover:text-black']" />
                    <span v-show="!isSidebarCollapsed" class="text-sm font-medium">Dashboard</span>
                </Link>

                <Link 
                    href="#"
                    class="flex items-center px-4 py-2.5 rounded-[10px] transition-all group"
                    :class="activeTab === 'clients' ? 'bg-[var(--app-accent)] text-white' : 'text-[var(--app-text)] hover:bg-black/5'"
                >
                    <Users :class="['w-5 h-5 ml-4', activeTab === 'clients' ? 'text-white' : 'text-[#86868b] group-hover:text-black']" />
                    <span v-show="!isSidebarCollapsed" class="text-sm font-medium">Subscribers</span>
                </Link>

                <Link 
                    href="#"
                    class="flex items-center px-4 py-2.5 rounded-[10px] transition-all group"
                >
                    <Network class="w-5 h-5 ml-4 text-[#86868b] group-hover:text-black" />
                    <span v-show="!isSidebarCollapsed" class="text-sm font-medium">Infrastructure</span>
                </Link>

                <div class="mt-8 mb-4 px-3" v-show="!isSidebarCollapsed">
                    <p class="text-[10px] font-bold text-[#86868b] uppercase tracking-widest">Financials</p>
                </div>

                <Link 
                    href="#"
                    class="flex items-center px-4 py-2.5 rounded-[10px] transition-all group"
                >
                    <CreditCard class="w-5 h-5 ml-4 text-[#86868b] group-hover:text-black" />
                    <span v-show="!isSidebarCollapsed" class="text-sm font-medium">Billing</span>
                </Link>
            </nav>

            <!-- Bottom Section -->
            <div class="p-4 border-t border-[var(--app-border)]">
                <div class="apple-card p-3 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center text-[10px] font-bold">AK</div>
                    <div v-show="!isSidebarCollapsed" class="flex-1 min-w-0">
                        <p class="text-xs font-bold truncate">Amjad Khoulani</p>
                        <p class="text-[9px] text-[#86868b] uppercase">Super Admin</p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 flex flex-col relative overflow-hidden">
            <!-- Top Navigation Bar -->
            <header class="h-16 apple-glass border-b flex items-center justify-between px-8 z-20">
                <div class="flex items-center gap-6">
                    <button @click="isSidebarCollapsed = !isSidebarCollapsed" class="p-2 hover:bg-black/5 rounded-full transition-colors">
                        <Menu class="w-5 h-5 text-[#86868b]" />
                    </button>
                    <div class="relative w-64">
                        <Search class="w-4 h-4 absolute right-3 top-1/2 -translate-y-1/2 text-[#86868b]" />
                        <input 
                            type="text" 
                            placeholder="Quick search..." 
                            class="w-full h-9 bg-black/5 rounded-[8px] pr-10 pl-4 text-xs focus:ring-1 focus:ring-[var(--app-accent)] outline-none border-none"
                        >
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <div class="flex items-center bg-emerald-50 text-emerald-600 px-3 py-1 rounded-full text-[10px] font-black tracking-wider uppercase border border-emerald-100">
                        <ShieldCheck class="w-3 h-3 ml-1" />
                        Network Live
                    </div>
                    <button class="p-2 hover:bg-black/5 rounded-full transition-colors relative">
                        <Bell class="w-5 h-5 text-[#86868b]" />
                        <span class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full border-2 border-white"></span>
                    </button>
                    <button class="p-2 hover:bg-black/5 rounded-full transition-colors">
                        <Monitor class="w-5 h-5 text-[#86868b]" />
                    </button>
                </div>
            </header>

            <!-- Content Container -->
            <div class="flex-1 overflow-y-auto p-8 relative">
                <div class="max-w-7xl mx-auto">
                    <slot />
                </div>
            </div>
        </main>
    </div>
</template>

<style scoped>
.apple-sidebar {
    background: var(--app-surface);
    backdrop-filter: blur(40px);
    -webkit-backdrop-filter: blur(40px);
}
</style>
