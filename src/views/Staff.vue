<template>
  <div>
    <div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
      <div>
        <h1>إدارة الموظفين (Staff)</h1>
        <p style="color: var(--text-muted);">إدارة صلاحيات وحسابات فريق العمل</p>
      </div>
      <button class="btn btn-primary">+ إضافة موظف جديد</button>
    </div>

    <div class="card">
      <table class="data-table">
        <thead>
          <tr>
            <th>الاسم</th>
            <th>البريد الإلكتروني</th>
            <th>الدور (الصلاحية)</th>
            <th>حالة الحساب</th>
            <th>الإجراءات</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>
              <div class="client-name">أحمد المدير</div>
            </td>
            <td>ahmad@madaaq.com</td>
            <td><span class="badge" style="background-color: #f3e8ff; color: #7e22ce;">مدير نظام</span></td>
            <td><span class="badge badge-success">نشط</span></td>
            <td>
              <button class="btn btn-outline" style="padding: 5px 10px; font-size: 12px; margin-left: 5px;">تعديل</button>
            </td>
          </tr>
          <tr>
            <td>
              <div class="client-name">سعيد دعم فني</div>
            </td>
            <td>support@madaaq.com</td>
            <td><span class="badge" style="background-color: #e0f2fe; color: #0369a1;">دعم فني</span></td>
            <td><span class="badge badge-success">نشط</span></td>
            <td>
              <button class="btn btn-outline" style="padding: 5px 10px; font-size: 12px; margin-left: 5px;">تعديل</button>
              <button class="btn btn-danger" style="padding: 5px 10px; font-size: 12px;">إيقاف</button>
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
.client-name {
  font-weight: 600;
  color: var(--text-main);
  margin-bottom: 2px;
}
.badge {
  padding: 5px 10px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 600;
  display: inline-block;
}
.badge-success {
  background-color: #dcfce7;
  color: #166534;
}
</style>
