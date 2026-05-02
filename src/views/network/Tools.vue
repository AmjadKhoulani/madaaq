<template>
  <div>
    <div class="page-header" style="margin-bottom: 20px;">
      <h1>أدوات الصيانة والنسخ الاحتياطي (Maintenance Tools)</h1>
      <p style="color: var(--text-muted);">أدوات تقنية لفحص استقرار الشبكة وتأمين البيانات</p>
    </div>

    <div class="tools-grid">
      <!-- Network Link Test -->
      <div class="card tool-card">
        <div class="tool-icon">📡</div>
        <div class="tool-info">
          <h3>فحص جودة الربط (Link Test)</h3>
          <p>قياس سرعة النقل الحقيقية وجودة الإشارة بين الأبراج والأجهزة.</p>
        </div>
        <button class="btn btn-primary btn-block">بدء الفحص ⚡</button>
      </div>

      <!-- Server Backups -->
      <div class="card tool-card">
        <div class="tool-icon">💾</div>
        <div class="tool-info">
          <h3>النسخ الاحتياطي (Backups)</h3>
          <p>جدولة وتحميل نسخ احتياطية لإعدادات سيرفرات المايكروتيك وقاعدة البيانات.</p>
        </div>
        <div class="backup-status">
          <span class="label">آخر نسخة:</span>
          <span class="date">منذ 6 ساعات</span>
        </div>
        <button class="btn btn-outline btn-block">تصدير نسخة الآن</button>
      </div>

      <!-- IP Scanner -->
      <div class="card tool-card">
        <div class="tool-icon">🔍</div>
        <div class="tool-info">
          <h3>فحص الـ IPs النشطة</h3>
          <p>اكتشاف الأجهزة المتصلة بالشبكة وفحص حالة الـ Ping لكل عنوان.</p>
        </div>
        <button class="btn btn-outline btn-block">فحص الشبكة</button>
      </div>
    </div>

    <!-- Backup History -->
    <div class="card" style="margin-top: 30px;">
      <h3 style="margin-bottom: 20px; font-size: 16px;">سجل النسخ الاحتياطية</h3>
      <table class="data-table">
        <thead>
          <tr>
            <th>اسم الملف</th>
            <th>التاريخ</th>
            <th>الحجم</th>
            <th>الإجراءات</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><strong>backup_mikrotik_main_20260501.rsc</strong></td>
            <td>2026-05-01 10:00 AM</td>
            <td>1.2 MB</td>
            <td><button class="btn btn-outline btn-sm">تحميل</button></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<style scoped>
.tools-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; }
.tool-card { display: flex; flex-direction: column; align-items: center; text-align: center; padding: 30px; }
.tool-icon { font-size: 40px; margin-bottom: 15px; }
.tool-info h3 { font-size: 17px; margin-bottom: 10px; }
.tool-info p { font-size: 13px; color: var(--text-muted); margin-bottom: 20px; line-height: 1.5; }
.btn-block { width: 100%; margin-top: auto; }

.backup-status { margin-bottom: 15px; font-size: 12px; }
.backup-status .label { color: var(--text-muted); }
.backup-status .date { font-weight: bold; color: var(--success); margin-right: 5px; }

.data-table { width: 100%; border-collapse: collapse; }
.data-table th, .data-table td { padding: 12px 15px; text-align: right; border-bottom: 1px solid var(--border-color); }
.data-table th { background: #f8fafc; font-size: 13px; color: var(--text-muted); }
.btn-sm { padding: 4px 8px; font-size: 12px; }
</style>
