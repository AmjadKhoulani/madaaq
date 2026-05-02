<template>
  <div>
    <div class="page-header">
      <h1>الشبكة والمعدات (Network)</h1>
      <p>إدارة السيرفرات، الراوترات، والأبراج المتصلة</p>
    </div>

    <div class="network-grid">
      <!-- Servers Section -->
      <div class="card network-card">
        <div class="card-header">
          <h2>سيرفرات MikroTik</h2>
          <span class="status-indicator online"></span>
        </div>
        <div class="card-body">
          <div class="server-info">
            <p><strong>اسم السيرفر:</strong> Main Core Router</p>
            <p><strong>IP:</strong> 192.168.88.1</p>
            <p><strong>الاستهلاك:</strong> CPU 15% | RAM 40%</p>
          </div>
        </div>
        <div class="card-footer">
          <button class="btn btn-outline btn-sm">إدارة السيرفر</button>
          <button class="btn btn-outline btn-sm">Terminal</button>
        </div>
      </div>

      <!-- Towers Section -->
      <div class="card network-card">
        <div class="card-header">
          <h2>الأبراج (Towers)</h2>
          <span class="badge badge-primary">3 أبراج مضافة</span>
        </div>
        <div class="card-body">
          <ul class="tower-list">
            <li>
              <span>برج الشمال</span>
              <span class="text-success">متصل</span>
            </li>
            <li>
              <span>برج الوسطى</span>
              <span class="text-success">متصل</span>
            </li>
            <li>
              <span>برج الجنوب</span>
              <span class="text-warning">صيانة</span>
            </li>
          </ul>
        </div>
        <div class="card-footer">
          <button class="btn btn-outline btn-sm w-100">إدارة الأبراج</button>
        </div>
      </div>
      
      <!-- Network Commands -->
      <div class="card network-card">
        <div class="card-header">
          <h2>أدوات الشبكة</h2>
        </div>
        <div class="card-body">
          <div class="tools-grid">
            <button class="btn btn-outline">فحص الاتصال (Ping)</button>
            <button class="btn btn-outline text-danger">إعادة تشغيل (Reboot)</button>
            <button class="btn btn-outline">تحديث السكربتات</button>
            <button class="btn btn-outline">مزامنة البيانات</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
</script>

<style scoped>
.page-header {
  margin-bottom: 25px;
}
.network-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
  gap: 20px;
}
.network-card {
  display: flex;
  flex-direction: column;
}
.card-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-bottom: 1px solid var(--border-color);
  padding-bottom: 15px;
  margin-bottom: 15px;
}
.card-header h2 {
  font-size: 18px;
  margin: 0;
}
.card-body {
  flex: 1;
}
.card-footer {
  margin-top: 20px;
  display: flex;
  gap: 10px;
}
.btn-sm {
  padding: 8px 12px;
  font-size: 13px;
  flex: 1;
}
.w-100 {
  width: 100%;
}

/* Server Info */
.server-info p {
  margin-bottom: 8px;
  color: var(--text-main);
}
.status-indicator {
  width: 12px;
  height: 12px;
  border-radius: 50%;
  display: inline-block;
}
.status-indicator.online { background-color: var(--success); }

/* Towers List */
.tower-list {
  list-style: none;
}
.tower-list li {
  display: flex;
  justify-content: space-between;
  padding: 10px 0;
  border-bottom: 1px solid #f3f4f6;
}
.tower-list li:last-child {
  border-bottom: none;
}
.badge-primary {
  background-color: #e0e7ff;
  color: #3730a3;
  padding: 4px 8px;
  border-radius: 12px;
  font-size: 12px;
}

/* Tools Grid */
.tools-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 10px;
}
.text-danger { color: var(--danger); }
.text-success { color: var(--success); }
.text-warning { color: var(--warning); }
</style>
