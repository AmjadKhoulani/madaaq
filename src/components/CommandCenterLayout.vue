<script setup>
import { ref } from 'vue'
import { useRouter, useRoute } from 'vue-router'

const router = useRouter()
const route = useRoute()

const navItems = [
  { name: 'Dashboard', path: '/', icon: '📊' },
  { name: 'CRM Clients', path: '/crm', icon: '👥' },
  { name: 'Staff Management', path: '/staff', icon: '👨‍💻' },
  { name: 'Activity Logs', path: '/logs', icon: '📜' },
  { name: 'Settings', path: '/settings', icon: '⚙️' }
]
</script>

<template>
  <div class="layout-wrapper">
    <!-- Sidebar -->
    <aside class="sidebar glass-panel animate-fade-in">
      <div class="brand">
        <div class="logo"></div>
        <h2 class="text-gradient">MadaaQ</h2>
      </div>
      
      <nav class="nav-menu">
        <router-link 
          v-for="item in navItems" 
          :key="item.path"
          :to="item.path"
          class="nav-item"
          :class="{ active: route.path === item.path }"
        >
          <span class="nav-icon">{{ item.icon }}</span>
          <span class="nav-text">{{ item.name }}</span>
        </router-link>
      </nav>
      
      <div class="sidebar-footer">
        <button class="btn btn-secondary logout-btn">
          <span>🚪</span> Logout
        </button>
      </div>
    </aside>

    <!-- Main Content -->
    <div class="main-container">
      <!-- Top Bar -->
      <header class="topbar glass-panel animate-fade-in">
        <div class="search-bar">
          <input type="text" class="form-control" placeholder="Search across command center..." />
        </div>
        <div class="user-actions">
          <button class="icon-btn">🔔<span class="badge">3</span></button>
          <div class="avatar">Admin</div>
        </div>
      </header>

      <!-- Dynamic Content -->
      <main class="content-area animate-fade-in" style="animation-delay: 0.2s;">
        <router-view v-slot="{ Component }">
          <transition name="fade" mode="out-in">
            <component :is="Component" />
          </transition>
        </router-view>
      </main>
    </div>
  </div>
</template>

<style scoped>
.layout-wrapper {
  display: flex;
  height: 100vh;
  padding: 1rem;
  gap: 1.5rem;
}

/* Sidebar Styles */
.sidebar {
  width: 280px;
  display: flex;
  flex-direction: column;
  padding: 1.5rem;
  z-index: 10;
}

.brand {
  display: flex;
  align-items: center;
  gap: 1rem;
  margin-bottom: 2.5rem;
  padding-bottom: 1.5rem;
  border-bottom: 1px solid var(--border-light);
}

.logo {
  width: 40px;
  height: 40px;
  background: var(--grad-cyan-purple);
  border-radius: 12px;
  box-shadow: var(--shadow-neon);
}

.nav-menu {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
  flex: 1;
}

.nav-item {
  display: flex;
  align-items: center;
  gap: 1rem;
  padding: 1rem 1.25rem;
  border-radius: 10px;
  color: var(--text-muted);
  font-weight: 500;
  transition: all 0.3s ease;
  border: 1px solid transparent;
}

.nav-item:hover {
  background: rgba(255, 255, 255, 0.05);
  color: var(--text-main);
  transform: translateX(4px);
}

.nav-item.active {
  background: linear-gradient(90deg, rgba(0, 210, 255, 0.1) 0%, transparent 100%);
  color: var(--color-primary);
  border-left: 3px solid var(--color-primary);
  border-radius: 4px 10px 10px 4px;
}

.sidebar-footer {
  margin-top: auto;
  padding-top: 1.5rem;
  border-top: 1px solid var(--border-light);
}

.logout-btn {
  width: 100%;
  display: flex;
  gap: 0.75rem;
}

/* Main Container Styles */
.main-container {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
  overflow: hidden;
}

/* Topbar Styles */
.topbar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem 2rem;
  border-radius: 16px;
  z-index: 10;
}

.search-bar {
  flex: 0 1 400px;
}

.user-actions {
  display: flex;
  align-items: center;
  gap: 1.5rem;
}

.icon-btn {
  background: transparent;
  border: none;
  font-size: 1.5rem;
  cursor: pointer;
  position: relative;
  transition: transform 0.3s;
}

.icon-btn:hover {
  transform: scale(1.1);
}

.badge {
  position: absolute;
  top: -5px;
  right: -5px;
  background: var(--color-accent);
  color: white;
  font-size: 0.75rem;
  padding: 2px 6px;
  border-radius: 10px;
  font-family: var(--font-display);
}

.avatar {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background: var(--grad-blue-cyan);
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
  font-family: var(--font-display);
  box-shadow: 0 2px 10px rgba(0,0,0,0.2);
}

/* Content Area */
.content-area {
  flex: 1;
  overflow-y: auto;
  padding-right: 0.5rem;
}

/* Page Transitions */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease, transform 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
  transform: translateY(10px);
}
</style>
