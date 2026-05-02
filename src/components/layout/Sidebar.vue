<template>
  <aside class="sidebar">
    <div class="brand">
      <span class="brand-icon">⚡</span> MadaaQ
    </div>
    
    <div class="user-profile">
      <div class="avatar">م</div>
      <div class="user-info">
        <strong>مدير النظام</strong>
        <span class="text-muted">admin@madaaq.local</span>
      </div>
    </div>

    <ul class="menu-list">
      <li>
        <router-link to="/" class="menu-item" exact-active-class="active">
          <span class="icon">📊</span> لوحة القيادة
        </router-link>
      </li>
      <li>
        <router-link to="/clients" class="menu-item" active-class="active">
          <span class="icon">👥</span> المشتركون
        </router-link>
      </li>
      <li>
        <router-link to="/network" class="menu-item" active-class="active">
          <span class="icon">📡</span> الشبكة والمعدات
        </router-link>
      </li>
      <li>
        <router-link to="/staff" class="menu-item" active-class="active">
          <span class="icon">👔</span> فريق العمل
        </router-link>
      </li>
      <li>
        <router-link to="/activity-logs" class="menu-item" active-class="active">
          <span class="icon">📝</span> سجل النشاطات
        </router-link>
      </li>
      <li>
        <router-link to="/settings" class="menu-item" active-class="active">
          <span class="icon">⚙️</span> الإعدادات
        </router-link>
      </li>
    </ul>

    <div class="sidebar-footer">
      <a href="#" @click.prevent="logout" class="menu-item logout-btn">
        <span class="icon">🚪</span> تسجيل الخروج
      </a>
    </div>
  </aside>
</template>

<script setup>
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/store/auth';

const router = useRouter();
const authStore = useAuthStore();

const logout = async () => {
  await authStore.logout();
  router.push('/login');
};
</script>

<style scoped>
.sidebar {
  display: flex;
  flex-direction: column;
  height: 100vh;
  position: sticky;
  top: 0;
}
.brand {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
  font-size: 26px;
}
.brand-icon {
  font-size: 24px;
}
.user-profile {
  display: flex;
  align-items: center;
  gap: 15px;
  padding: 20px;
  margin: 0 20px 20px 20px;
  background-color: #f8fafc;
  border-radius: var(--radius-md);
  border: 1px solid var(--border-color);
}
.avatar {
  width: 40px;
  height: 40px;
  background-color: var(--primary);
  color: white;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
  font-size: 18px;
}
.user-info {
  display: flex;
  flex-direction: column;
  font-size: 13px;
}
.user-info strong {
  color: var(--text-main);
  font-size: 14px;
}
.menu-list {
  flex: 1;
}
.menu-item {
  display: flex;
  align-items: center;
  gap: 12px;
}
.icon {
  font-size: 18px;
}
.menu-item.active {
  color: var(--primary);
  background-color: #eff6ff;
  border-right-color: var(--primary);
}
.sidebar-footer {
  padding: 20px;
  border-top: 1px solid var(--border-color);
}
.logout-btn {
  color: var(--danger);
}
.logout-btn:hover {
  background-color: #fef2f2;
  color: var(--danger);
  border-right-color: var(--danger);
}
</style>
