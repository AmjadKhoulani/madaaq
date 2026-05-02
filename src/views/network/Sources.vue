<template>
  <div>
    <div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
      <div>
        <h1>مصادر الإنترنت (Internet Sources)</h1>
        <p style="color: var(--text-muted);">إدارة خطوط الإنترنت الأساسية المغذية للسيرفرات</p>
      </div>
      <button class="btn btn-primary" @click="showModal = true">+ إضافة مصدر جديد</button>
    </div>

    <div class="sources-grid">
      <div class="card source-card">
        <div class="source-header">
          <h3>خط ضوئي - الشركة الرئيسية</h3>
          <span class="badge badge-success">متصل</span>
        </div>
        <div class="source-info">
          <div class="info-item">
            <span class="label">السعة:</span>
            <span class="value">500 Mbps</span>
          </div>
          <div class="info-item">
            <span class="label">نوع الاتصال:</span>
            <span class="value">Fiber Optic</span>
          </div>
          <div class="info-item">
            <span class="label">الـ IP Gateway:</span>
            <span class="value">1.1.1.1</span>
          </div>
        </div>
        <div class="source-actions">
          <button class="btn btn-outline btn-sm">تعديل</button>
          <button class="btn btn-outline btn-sm">فحص السرعة</button>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.sources-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; }
.source-card { border-right: 4px solid var(--success); }
.source-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
.source-header h3 { margin: 0; font-size: 17px; }
.source-info { display: flex; flex-direction: column; gap: 10px; }
.info-item { display: flex; justify-content: space-between; font-size: 14px; }
.info-item .label { color: var(--text-muted); }
.info-item .value { font-weight: bold; color: var(--text-main); }
.source-actions { margin-top: 20px; display: flex; gap: 10px; border-top: 1px solid #f1f5f9; padding-top: 15px; }
.btn-sm { padding: 5px 10px; font-size: 12px; }
.badge { padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: bold; }
.badge-success { background: #dcfce7; color: #166534; }
</style>
