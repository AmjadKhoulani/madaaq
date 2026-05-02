<template>
  <div>
    <div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
      <div>
        <h1>الحملات التسويقية (Campaigns)</h1>
        <p style="color: var(--text-muted);">جدولة وإرسال العروض والتنبيهات للعملاء</p>
      </div>
      <button class="btn btn-primary">+ إنشاء حملة جديدة</button>
    </div>

    <div class="card">
      <table class="data-table">
        <thead>
          <tr>
            <th>اسم الحملة</th>
            <th>الاستهداف</th>
            <th>تاريخ الإرسال</th>
            <th>الحالة</th>
            <th>الإجراءات</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><strong>تذكير بتجديد الاشتراك</strong></td>
            <td>العملاء المنتهية باقاتهم</td>
            <td>2026-05-02 10:00 AM</td>
            <td><span class="badge badge-warning">مجدولة</span></td>
            <td>
              <button class="btn btn-outline btn-sm">تعديل</button>
              <button class="btn btn-danger btn-sm" style="margin-left: 5px;">إلغاء</button>
            </td>
          </tr>
          <tr>
            <td><strong>عرض رمضان 50%</strong></td>
            <td>جميع العملاء النشطين</td>
            <td>2026-03-01 12:00 PM</td>
            <td><span class="badge badge-success">تم الإرسال</span></td>
            <td>
              <button class="btn btn-outline btn-sm">التقرير</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
</script>

<style scoped>
.data-table { width: 100%; border-collapse: collapse; }
.data-table th, .data-table td { padding: 15px; text-align: right; border-bottom: 1px solid var(--border-color); }
.data-table th { background-color: #f8fafc; font-weight: 600; color: var(--text-muted); font-size: 14px; }
.badge { padding: 5px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; display: inline-block; }
.badge-success { background-color: #dcfce7; color: #166534; }
.badge-warning { background-color: #fef3c7; color: #92400e; }
.btn-sm { padding: 5px 10px; font-size: 12px; }
</style>
