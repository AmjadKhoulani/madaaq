<template>
  <div class="client-portal">
    <!-- Portal Top Nav -->
    <header class="top-nav">
      <div class="brand">
        <span class="icon">🏠</span>
        <h1>بوابة المشترك</h1>
      </div>
      
      <div class="user-profile">
        <span>أهلاً بك، محمد</span>
        <div class="avatar">M</div>
      </div>
    </header>

    <div class="portal-body">
      <nav class="bottom-tabs">
        <router-link to="/portal" class="tab-item" exact-active-class="active">
          <span class="icon">📊</span> الرئيسية
        </router-link>
        <router-link to="/portal/renew" class="tab-item" active-class="active">
          <span class="icon">💳</span> التجديد
        </router-link>
        <router-link to="/portal/usage" class="tab-item" active-class="active">
          <span class="icon">📉</span> الاستهلاك
        </router-link>
        <router-link to="/portal/support" class="tab-item" active-class="active">
          <span class="icon">💬</span> الدعم
        </router-link>
      </nav>

      <main class="workspace">
        <router-view></router-view>
      </main>
    </div>
  </div>
</template>

<style scoped>
.client-portal { display: flex; flex-direction: column; height: 100vh; background-color: #f0fdf4; }
.top-nav { height: 60px; background-color: #059669; color: white; display: flex; justify-content: space-between; align-items: center; padding: 0 20px; }
.brand { display: flex; align-items: center; gap: 10px; }
.user-profile { display: flex; align-items: center; gap: 10px; }
.avatar { width: 32px; height: 32px; background-color: #34d399; border-radius: 50%; display: flex; align-items: center; justify-content: center; }
.portal-body { flex: 1; display: flex; flex-direction: column; overflow: hidden; }
.workspace { flex: 1; padding: 20px; overflow-y: auto; padding-bottom: 80px; }
.bottom-tabs { position: fixed; bottom: 0; width: 100%; height: 70px; background: white; display: flex; justify-content: space-around; align-items: center; border-top: 1px solid #e5e7eb; box-shadow: 0 -2px 10px rgba(0,0,0,0.05); }
.tab-item { display: flex; flex-direction: column; align-items: center; font-size: 12px; color: #6b7280; text-decoration: none; gap: 4px; }
.tab-item .icon { font-size: 20px; }
.tab-item.active { color: #059669; font-weight: bold; }
</style>
