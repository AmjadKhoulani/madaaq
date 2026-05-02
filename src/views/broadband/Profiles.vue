<template>
  <div>
    <div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
      <div>
        <h1>باقات البرودباند (PPPoE Profiles)</h1>
        <p style="color: var(--text-muted);">إدارة باقات المنازل ومزامنتها</p>
      </div>
      <button class="btn btn-primary">+ إضافة باقة جديدة</button>
    </div>

    <div class="card">
      <table class="data-table">
        <thead>
          <tr>
            <th>اسم الباقة (Profile)</th>
            <th>السعر</th>
            <th>السرعة (ميجابت)</th>
            <th>السيرفر المحلي</th>
            <th>الحالة</th>
            <th>الإجراءات</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><strong>10M_Home</strong></td>
            <td>$15.00</td>
            <td>10M / 10M</td>
            <td>Main Server</td>
            <td><span class="badge badge-success">مفعل</span></td>
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
