<template>
  <div>
    <div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
      <div>
        <h1>تنبيهات وسجلات الشبكة (Network Alerts)</h1>
        <p style="color: var(--text-muted);">مراقبة حية لكافة الأحداث التقنية والتنبيهات في الأبراج والسيرفرات</p>
      </div>
      <div class="alert-stats">
        <span class="critical-count">🚨 3 حرجة</span>
      </div>
    </div>

    <div class="alerts-container">
      <!-- Warning Alert -->
      <div class="alert-item warning card">
        <div class="alert-icon">📡</div>
        <div class="alert-content">
          <div class="alert-title">فقدان إشارة (Receiver Down)</div>
          <p>كابينة شارع 15: توقف جهاز الاستقبال عن الاستجابة للـ Ping</p>
          <span class="alert-time">منذ 15 دقيقة</span>
        </div>
        <div class="alert-actions">
          <button class="btn btn-warning btn-sm">فحص الآن</button>
        </div>
      </div>

      <!-- Info Log -->
      <div class="alert-item info card">
        <div class="alert-icon">📝</div>
        <div class="alert-content">
          <div class="alert-title">نسخ احتياطي ناجح</div>
          <p>تم الانتهاء من النسخ الاحتياطي اليومي لسيرفر المايكروتك الرئيسي</p>
          <span class="alert-time">منذ ساعتين</span>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.alerts-container { display: flex; flex-direction: column; gap: 15px; }
.alert-item { display: flex; align-items: flex-start; gap: 20px; border-right: 5px solid transparent; padding: 20px !important; }

.alert-item.critical { border-right-color: #ef4444; background: #fff1f2; }
.alert-item.warning { border-right-color: #f59e0b; background: #fffbeb; }
.alert-item.info { border-right-color: #3b82f6; background: #f0f9ff; }

.alert-icon { font-size: 24px; }
.alert-title { font-weight: 800; font-size: 16px; margin-bottom: 5px; color: #1e293b; }
.alert-content p { font-size: 14px; margin: 0; color: #475569; }
.alert-time { font-size: 12px; color: #94a3b8; display: block; margin-top: 10px; }

.critical-count { background: #ef4444; color: white; padding: 5px 15px; border-radius: 20px; font-weight: 800; font-size: 13px; }

.alert-actions { margin-right: auto; }
.btn-sm { padding: 6px 12px; font-size: 12px; }
</style>
