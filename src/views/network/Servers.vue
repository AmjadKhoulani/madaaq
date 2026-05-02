<template>
  <div>
    <div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
      <div>
        <h1>سيرفرات MikroTik</h1>
        <p style="color: var(--text-muted);">إدارة السيرفرات المركزية ومزامنة البيانات</p>
      </div>
      <button class="btn btn-primary">+ إضافة سيرفر</button>
    </div>

    <div class="card network-card">
      <div class="card-header">
        <h2>Main Core Router</h2>
        <span class="status-indicator online"></span>
      </div>
      <div class="card-body" style="padding: 15px 0;">
        <p><strong>IP:</strong> 192.168.88.1</p>
        <p><strong>الاستهلاك:</strong> CPU 15% | RAM 40%</p>
      </div>
      <div style="display: flex; gap: 10px; margin-top: 15px;">
        <button class="btn btn-outline">إعدادات السيرفر</button>
        <button class="btn btn-outline">Terminal</button>
        <button class="btn btn-outline">مزامنة</button>
      </div>
    </div>
  </div>
</template>

<script setup>
</script>

<style scoped>
.network-card {
  max-width: 500px;
}
.card-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-bottom: 1px solid var(--border-color);
  padding-bottom: 15px;
}
.card-header h2 { font-size: 18px; margin: 0; }
.status-indicator {
  width: 12px; height: 12px; border-radius: 50%; display: inline-block;
}
.status-indicator.online { background-color: var(--success); }
</style>
