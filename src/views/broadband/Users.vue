<template>
  <div>
    <div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
      <div>
        <h1>مشتركي البرودباند (PPPoE)</h1>
        <p style="color: var(--text-muted);">إدارة الاشتراكات المنزلية (PPPoE)</p>
      </div>
      <button class="btn btn-primary">+ إضافة مشترك PPPoE</button>
    </div>

    <div class="card">
      <table class="data-table">
        <thead>
          <tr>
            <th>الاسم</th>
            <th>اسم المستخدم (PPPoE)</th>
            <th>الباقة (Profile)</th>
            <th>تاريخ الانتهاء</th>
            <th>الحالة</th>
            <th>الإجراءات</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><strong>محمد أحمد</strong></td>
            <td>mohammad_ppp</td>
            <td>10M / 10M</td>
            <td>2026-06-01</td>
            <td><span class="badge badge-success">نشط</span></td>
            <td>
              <button class="btn btn-outline btn-sm">تعديل</button>
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
.data-table {
  width: 100%;
  border-collapse: collapse;
}
.data-table th, .data-table td {
  padding: 15px;
  text-align: right;
  border-bottom: 1px solid var(--border-color);
}
.data-table th {
  background-color: #f8fafc;
  font-weight: 600;
  color: var(--text-muted);
  font-size: 14px;
}
.badge {
  padding: 5px 10px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 600;
  display: inline-block;
}
.badge-success { background-color: #dcfce7; color: #166534; }
.btn-sm { padding: 5px 10px; font-size: 12px; }
</style>
