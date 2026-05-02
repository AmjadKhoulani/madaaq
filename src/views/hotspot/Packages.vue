<template>
  <div>
    <div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
      <div>
        <h1>باقات الهوت سبوت</h1>
        <p style="color: var(--text-muted);">إدارة باقات الهوت سبوت ومزامنتها</p>
      </div>
      <button class="btn btn-primary">+ إضافة باقة جديدة</button>
    </div>

    <div class="card">
      <table class="data-table">
        <thead>
          <tr>
            <th>اسم الباقة</th>
            <th>السعر</th>
            <th>السرعة (ميجابت)</th>
            <th>الصلاحية</th>
            <th>الحالة</th>
            <th>الإجراءات</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><strong>باقة 1 جيجا</strong></td>
            <td>$5.00</td>
            <td>2M / 2M</td>
            <td>3 أيام</td>
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
