<template>
  <div>
    <div class="page-header" style="margin-bottom: 20px;">
      <h1>المستخدمين النشطين (Active Hotspot)</h1>
      <p style="color: var(--text-muted);">المتصلين حالياً بشبكة الهوت سبوت</p>
    </div>

    <div class="card">
      <table class="data-table">
        <thead>
          <tr>
            <th>المستخدم</th>
            <th>IP Address</th>
            <th>MAC Address</th>
            <th>الاستهلاك (U/D)</th>
            <th>مدة الاتصال</th>
            <th>السيرفر</th>
            <th>الإجراءات</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><strong>ahmad_22</strong></td>
            <td>10.0.0.15</td>
            <td>00:1A:2B:3C:4D:5E</td>
            <td>12 MB / 45 MB</td>
            <td>02:15:30</td>
            <td>Main Server</td>
            <td>
              <button class="btn btn-danger btn-sm">فصل (Disconnect)</button>
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
.btn-sm {
  padding: 5px 10px;
  font-size: 12px;
}
</style>
