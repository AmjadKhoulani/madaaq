<template>
  <div class="portal-dashboard">
    <div class="welcome-card card">
      <h2>أهلاً بك يا محمد! 👋</h2>
      <p>اشتراكك الحالي نشط وينتهي خلال 15 يوماً.</p>
    </div>

    <div class="stats-grid">
      <div class="card stat-box">
        <span class="label">الباقة الحالية</span>
        <span class="value">المنزل الممتازة (10M)</span>
      </div>
      <div class="card stat-box">
        <span class="label">تاريخ الانتهاء</span>
        <span class="value">2026-05-15</span>
      </div>
    </div>

    <div class="usage-card card">
      <h3>استهلاك البيانات</h3>
      <div class="progress-container">
        <div class="progress-bar" style="width: 65%;"></div>
      </div>
      <div class="usage-info">
        <span>65 GB مستخدمة</span>
        <span>100 GB المتاح</span>
      </div>
    </div>

    <div class="quick-actions">
      <button class="btn btn-primary" style="width: 100%; padding: 15px; background-color: #059669;">تجديد الاشتراك الآن 💳</button>
    </div>
  </div>
</template>

<style scoped>
.portal-dashboard { display: flex; flex-direction: column; gap: 20px; }
.welcome-card { background: linear-gradient(135deg, #059669 0%, #10b981 100%); color: white; }
.welcome-card h2 { color: white; margin-bottom: 5px; }
.welcome-card p { color: #d1fae5; margin: 0; }
.stats-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
.stat-box { display: flex; flex-direction: column; gap: 5px; text-align: center; }
.stat-box .label { font-size: 12px; color: var(--text-muted); }
.stat-box .value { font-size: 14px; font-weight: bold; color: var(--text-main); }
.usage-card h3 { font-size: 16px; margin-bottom: 15px; }
.progress-container { height: 12px; background: #e5e7eb; border-radius: 10px; overflow: hidden; margin-bottom: 10px; }
.progress-bar { height: 100%; background: #10b981; border-radius: 10px; }
.usage-info { display: flex; justify-content: space-between; font-size: 13px; color: var(--text-muted); }
</style>
