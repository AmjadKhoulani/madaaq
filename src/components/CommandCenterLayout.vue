<template>
  <div class="command-center-layout" :class="{ 'sidebar-collapsed': !isSidebarOpen }">
    <!-- 1. Mobile Drawer Overlay -->
    <div v-if="isMobile && isSidebarOpen" class="mobile-overlay" @click="isSidebarOpen = false"></div>

    <!-- 2. Sidebar Navigation -->
    <aside class="main-sidebar" :class="{ 'mobile-active': isMobile && isSidebarOpen }">
      <div class="sidebar-brand">
        <div class="brand-visual">
          <span class="dot"></span>
          <h2 v-if="isSidebarOpen">MadaaQ</h2>
        </div>
        <button v-if="!isMobile" class="collapse-btn" @click="isSidebarOpen = !isSidebarOpen">
          {{ isSidebarOpen ? '◀' : '▶' }}
        </button>
      </div>

      <nav class="sidebar-nav">
        <div v-for="group in menuGroups" :key="group.title" class="nav-group">
          <label v-if="isSidebarOpen">{{ group.title }}</label>
          <router-link v-for="item in group.items" :key="item.path" :to="item.path" class="nav-item">
            <span class="icon">{{ item.icon }}</span>
            <span v-if="isSidebarOpen" class="label">{{ item.label }}</span>
          </router-link>
        </div>
      </nav>

      <div class="sidebar-footer" v-if="isSidebarOpen">
        <div class="user-mini-card">
          <div class="avatar">A</div>
          <div class="user-meta">
            <strong>أمجــد خولانـي</strong>
            <span>مـالك الشبكة</span>
          </div>
        </div>
      </div>
    </aside>

    <!-- 3. Main Content Area -->
    <main class="content-wrapper">
      <!-- Top Navbar -->
      <header class="top-navbar">
        <div class="nav-left">
          <button v-if="isMobile" class="hamburger-btn" @click="isSidebarOpen = !isSidebarOpen">☰</button>
          <div class="search-bar-pro">
            <span class="search-icon">🔍</span>
            <input type="text" placeholder="البحث عن مشترك، فاتورة، أو برج... (Ctrl+K)" />
          </div>
        </div>
        <div class="nav-right">
          <button class="nav-tool-btn" title="الوضع الليلي">🌙</button>
          <button class="nav-tool-btn notify" title="التنبيهات">🔔<span class="badge">3</span></button>
          <div class="divider"></div>
          <button class="btn-logout">تسجيل الخروج 🚪</button>
        </div>
      </header>

      <!-- Dynamic Page Content -->
      <section class="workspace">
        <router-view v-slot="{ Component }">
          <transition name="fade-page" mode="out-in">
            <component :is="Component" />
          </transition>
        </router-view>
      </section>
    </main>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';

const isSidebarOpen = ref(true);
const isMobile = ref(false);

const menuGroups = [
  {
    title: 'الأساسية',
    items: [
      { path: '/vendor', label: 'الداشبورد', icon: '📊' },
      { path: '/vendor/clients', label: 'المشتركين (CRM)', icon: '👥' }
    ]
  },
  {
    title: 'المالية',
    items: [
      { path: '/vendor/finance/broadband-packages', label: 'باقات Broadband', icon: '🏠' },
      { path: '/vendor/finance/hotspot-packages', label: 'باقات Hotspot', icon: '🎫' },
      { path: '/vendor/finance/invoices', label: 'الفواتير والتحصيل', icon: '🧾' },
      { path: '/vendor/finance/reports', label: 'التقارير والـ ROI', icon: '📈' },
      { path: '/vendor/finance/tower-costs', label: 'تكاليف الأبراج', icon: '💸' }
    ]
  },
  {
    title: 'الشبكة',
    items: [
      { path: '/vendor/network/towers', label: 'الأبراج والكبائن', icon: '🗼' },
      { path: '/vendor/network/map', label: 'خريطة الشبكة', icon: '🗺️' },
      { path: '/vendor/network/inventory', label: 'التجهيزات والمخزن', icon: '📦' },
      { path: '/vendor/network/hotspot-gen', label: 'مولد الكروت', icon: '⚡' }
    ]
  },
  {
    title: 'الإدارة',
    items: [
      { path: '/vendor/marketing/whatsapp', label: 'واتساب الأعمال', icon: '💬' },
      { path: '/vendor/staff', label: 'فريق العمل', icon: '👔' },
      { path: '/vendor/activity', label: 'سجلات النشاط', icon: '📜' },
      { path: '/vendor/settings', label: 'إعدادات النظام', icon: '⚙️' }
    ]
  }
];

const checkMobile = () => {
  isMobile.ref = window.innerWidth <= 1024;
  if (isMobile.ref) isSidebarOpen.value = false;
  else isSidebarOpen.value = true;
};

onMounted(() => {
  checkMobile();
  window.addEventListener('resize', checkMobile);
});

onUnmounted(() => {
  window.removeEventListener('resize', checkMobile);
});
</script>

<style scoped>
.command-center-layout { display: flex; min-height: 100vh; background: #f8faff; transition: 0.3s; }

/* Sidebar */
.main-sidebar { width: 280px; background: white; border-left: 1px solid #e2e8f0; display: flex; flex-direction: column; transition: 0.3s; z-index: 1000; position: sticky; top: 0; height: 100vh; }
.sidebar-collapsed .main-sidebar { width: 85px; }

.sidebar-brand { height: 80px; display: flex; align-items: center; justify-content: space-between; padding: 0 25px; border-bottom: 1px solid #f1f5f9; }
.brand-visual { display: flex; align-items: center; gap: 12px; }
.brand-visual .dot { width: 12px; height: 12px; background: var(--primary); border-radius: 4px; transform: rotate(45deg); }
.brand-visual h2 { font-size: 20px; font-weight: 900; color: #1e293b; }
.collapse-btn { background: none; border: none; cursor: pointer; color: #94a3b8; font-size: 12px; }

.sidebar-nav { flex: 1; padding: 25px 15px; overflow-y: auto; }
.nav-group { margin-bottom: 30px; }
.nav-group label { display: block; font-size: 11px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px; padding: 0 15px 12px; }
.nav-item { display: flex; align-items: center; gap: 15px; padding: 12px 15px; text-decoration: none; border-radius: 12px; transition: 0.2s; margin-bottom: 5px; }
.nav-item .icon { font-size: 18px; }
.nav-item .label { font-size: 14px; font-weight: 700; color: #64748b; }
.nav-item:hover { background: #f1f5f9; }
.nav-item.router-link-active { background: #eff6ff; }
.nav-item.router-link-active .label { color: var(--primary); }

.sidebar-footer { padding: 20px; border-top: 1px solid #f1f5f9; }
.user-mini-card { display: flex; align-items: center; gap: 12px; background: #f8fafc; padding: 10px; border-radius: 12px; }
.avatar { width: 35px; height: 35px; background: #e2e8f0; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-weight: 900; font-size: 14px; }
.user-meta strong { display: block; font-size: 12.5px; color: #1e293b; }
.user-meta span { font-size: 10px; color: #94a3b8; font-weight: 700; }

/* Content Wrapper */
.content-wrapper { flex: 1; display: flex; flex-direction: column; overflow-x: hidden; }
.top-navbar { height: 80px; background: white; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; padding: 0 35px; position: sticky; top: 0; z-index: 900; }

.nav-left { display: flex; align-items: center; gap: 20px; flex: 1; }
.hamburger-btn { font-size: 24px; background: none; border: none; cursor: pointer; }
.search-bar-pro { background: #f1f5f9; border-radius: 12px; padding: 10px 15px; display: flex; align-items: center; gap: 12px; width: 100%; max-width: 450px; }
.search-bar-pro input { background: none; border: none; outline: none; flex: 1; font-size: 14px; }
.search-icon { opacity: 0.4; }

.nav-right { display: flex; align-items: center; gap: 15px; }
.nav-tool-btn { background: #f8fafc; border: 1px solid #e2e8f0; width: 42px; height: 42px; border-radius: 12px; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 18px; position: relative; }
.nav-tool-btn .badge { position: absolute; top: -5px; right: -5px; background: #ef4444; color: white; width: 18px; height: 18px; border-radius: 50%; font-size: 10px; font-weight: 800; display: flex; align-items: center; justify-content: center; border: 2px solid white; }
.divider { width: 1px; height: 30px; background: #e2e8f0; margin: 0 10px; }
.btn-logout { background: #fef2f2; color: #ef4444; border: 1px solid #fecaca; padding: 10px 20px; border-radius: 12px; font-weight: 800; font-size: 13px; cursor: pointer; }

.workspace { padding: 35px; flex: 1; width: 100%; max-width: 1600px; margin: 0 auto; }

/* Mobile Support */
.mobile-overlay { position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 999; }
@media (max-width: 1024px) {
  .main-sidebar { position: fixed; right: -280px; height: 100vh; }
  .main-sidebar.mobile-active { right: 0; }
  .top-navbar { padding: 0 20px; }
  .workspace { padding: 20px; }
  .search-bar-pro { display: none; } /* Hide search on mobile top bar */
}

/* Animations */
.fade-page-enter-active, .fade-page-leave-active { transition: opacity 0.2s, transform 0.2s; }
.fade-page-enter-from { opacity: 0; transform: translateY(10px); }
.fade-page-leave-to { opacity: 0; transform: translateY(-10px); }
</style>
