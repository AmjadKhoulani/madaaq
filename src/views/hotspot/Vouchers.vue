<template>
  <div>
    <div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
      <div>
        <h1>كروت الشبكة (Vouchers)</h1>
        <p style="color: var(--text-muted);">توليد وإدارة كروت الهوت سبوت</p>
      </div>
      <button class="btn btn-primary">+ توليد كروت (Bulk)</button>
    </div>

    <!-- Filters -->
    <div class="card filters-section">
      <div class="search-box">
        <input type="text" placeholder="البحث برقم الكرت..." class="form-input search-input" />
      </div>
      <div class="filter-actions">
        <select class="form-input filter-select">
          <option value="">جميع الباقات</option>
          <option value="1">باقة 1 جيجا</option>
          <option value="2">باقة مفتوح يومي</option>
        </select>
        <button class="btn btn-outline">بحث</button>
      </div>
    </div>

    <div class="card">
      <table class="data-table">
        <thead>
          <tr>
            <th>رقم الكرت / Username</th>
            <th>كلمة المرور</th>
            <th>الباقة</th>
            <th>الحالة</th>
            <th>تاريخ التوليد</th>
            <th>الإجراءات</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><strong>1005020</strong></td>
            <td>5020</td>
            <td>باقة 1 جيجا</td>
            <td><span class="badge badge-success">جديد</span></td>
            <td>2026-05-01</td>
            <td>
              <button class="btn btn-outline btn-sm">طباعة</button>
            </td>
          </tr>
          <tr>
            <td><strong>1005021</strong></td>
            <td>5021</td>
            <td>باقة مفتوح يومي</td>
            <td><span class="badge badge-warning">مستخدم</span></td>
            <td>2026-05-01</td>
            <td>
              <button class="btn btn-outline btn-sm" disabled>طباعة</button>
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
.filters-section {
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  gap: 15px;
  margin-bottom: 20px;
  padding: 15px 20px;
}
.search-box {
  flex: 1;
  min-width: 250px;
}
.search-input {
  width: 100%;
}
.filter-actions {
  display: flex;
  gap: 10px;
}
.filter-select {
  min-width: 150px;
}

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
.badge-warning { background-color: #fef3c7; color: #92400e; }
.btn-sm {
  padding: 5px 10px;
  font-size: 12px;
}
</style>
