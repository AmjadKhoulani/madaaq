<template>
  <div class="admin-center">
    <!-- Admin Top Nav -->
    <header class="top-nav">
      <div class="brand">
        <span class="icon">💎</span>
        <h1>MadaaQ Admin Hub</h1>
      </div>
      
      <div class="nav-controls">
        <div class="system-status">
          <span class="status-dot"></span>
          <span>وضع الإدارة العليا</span>
        </div>
        <div class="user-profile">
          <div class="avatar">SA</div>
          <span>Super Admin</span>
        </div>
      </div>
    </header>

    <div class="layout-body">
      <!-- Admin Side Nav -->
      <aside class="side-nav">
        <nav class="menu">
          <router-link to="/admin" class="menu-item" exact-active-class="active">
            <span class="icon">📈</span> نظرة عامة
          </router-link>
          <router-link to="/admin/tenants" class="menu-item" active-class="active">
            <span class="icon">🏢</span> إدارة المستأجرين
          </router-link>
          <router-link to="/admin/subscriptions" class="menu-item" active-class="active">
            <span class="icon">💳</span> الاشتراكات والخطط
          </router-link>
          <router-link to="/admin/reports" class="menu-item" active-class="active">
            <span class="icon">📊</span> تقارير المنصة
          </router-link>
          <router-link to="/admin/settings" class="menu-item" active-class="active">
            <span class="icon">🛠️</span> إعدادات النظام
          </router-link>
        </nav>
      </aside>

      <main class="workspace">
        <router-view></router-view>
      </main>
    </div>
  </div>
</template>

<style scoped>
.admin-center { display: flex; flex-direction: column; height: 100vh; background-color: #f5f3ff; }
.top-nav { height: 60px; background-color: #5b21b6; color: white; display: flex; justify-content: space-between; align-items: center; padding: 0 20px; z-index: 10; }
.brand { display: flex; align-items: center; gap: 10px; }
.brand h1 { font-size: 20px; color: white; }
.nav-controls { display: flex; align-items: center; gap: 20px; }
.system-status { display: flex; align-items: center; gap: 8px; background-color: rgba(255,255,255,0.15); padding: 5px 12px; border-radius: 20px; font-size: 13px; }
.status-dot { width: 8px; height: 8px; background-color: #a78bfa; border-radius: 50%; box-shadow: 0 0 8px #a78bfa; }
.avatar { width: 32px; height: 32px; background-color: #8b5cf6; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; }
.layout-body { display: flex; flex: 1; overflow: hidden; }
.side-nav { width: 240px; background-color: #ffffff; border-left: 1px solid #e2e8f0; }
.menu { padding: 15px 0; }
.menu-item { display: flex; align-items: center; gap: 12px; padding: 12px 20px; color: #4b5563; text-decoration: none; font-weight: 600; border-right: 3px solid transparent; }
.menu-item:hover { background-color: #f5f3ff; color: #5b21b6; }
.menu-item.active { background-color: #ede9fe; color: #7c3aed; border-right-color: #7c3aed; }
.workspace { flex: 1; padding: 30px; overflow-y: auto; }
</style>
