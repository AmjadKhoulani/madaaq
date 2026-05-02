<template>
  <div class="packages-page">
    <div class="final-header">
      <div>
        <h1>باقات الـ Broadband (PPPoE)</h1>
        <p>تعريف سرعات الإنترنت، الأسعار الشهرية، وسياسات الخدمة المنزلية</p>
      </div>
      <div class="header-actions">
        <button class="btn-pro-primary">إضافة باقة جديدة ➕</button>
      </div>
    </div>

    <div class="packages-grid">
      <div v-for="pkg in broadbandPackages" :key="pkg.id" class="card package-card">
        <div class="pkg-head">
          <h3>{{ pkg.name }}</h3>
          <div class="pkg-price">{{ pkg.price }}<span>$ / شهرياً</span></div>
        </div>
        <div class="pkg-body">
          <div class="spec-item"><span>🚀 التحميل (DL):</span> <strong>{{ pkg.download }} Mbps</strong></div>
          <div class="spec-item"><span>📤 الرفع (UL):</span> <strong>{{ pkg.upload }} Mbps</strong></div>
          <div class="spec-item"><span>📉 الاستهلاك:</span> <strong>غير محدود</strong></div>
        </div>
        <div class="pkg-footer">
          <button class="btn-icon">✏️</button>
          <button class="btn-icon text-danger">🗑️</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
const broadbandPackages = [
  { id: 1, name: 'Basic 10M', price: 7, download: 10, upload: 2 },
  { id: 2, name: 'Standard 20M', price: 10, download: 20, upload: 5 },
  { id: 3, name: 'Premium 50M', price: 15, download: 50, upload: 10 }
];
</script>

<style scoped>
.packages-page { padding: 0; width: 100%; }
.final-header { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 35px; }
.final-header h1 { font-size: 24px; font-weight: 900; color: #1e293b; }
.final-header p { font-size: 14px; color: #64748b; }

.packages-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 25px; }
.package-card { border-top: 5px solid var(--primary); transition: transform 0.2s; }
.package-card:hover { transform: translateY(-5px); }

.pkg-head { text-align: center; margin-bottom: 25px; padding-bottom: 20px; border-bottom: 1px solid #f1f5f9; }
.pkg-head h3 { font-size: 17px; font-weight: 800; color: #1e293b; margin-bottom: 10px; }
.pkg-price { font-size: 28px; font-weight: 900; color: var(--primary); }
.pkg-price span { font-size: 12px; color: #94a3b8; margin-right: 5px; }

.pkg-body { display: flex; flex-direction: column; gap: 15px; margin-bottom: 25px; }
.spec-item { display: flex; justify-content: space-between; font-size: 13.5px; color: #475569; }
.spec-item strong { color: #1e293b; font-weight: 800; }

.pkg-footer { display: flex; justify-content: center; gap: 10px; padding-top: 20px; border-top: 1px solid #f1f5f9; }
.btn-icon { background: #f8fafc; border: 1px solid #e2e8f0; width: 40px; height: 40px; border-radius: 10px; cursor: pointer; transition: 0.2s; }
.btn-icon:hover { background: #eff6ff; color: var(--primary); border-color: var(--primary); }
.btn-icon.text-danger:hover { background: #fef2f2; color: #ef4444; border-color: #fecaca; }

.btn-pro-primary { background: var(--primary); color: white; border: none; padding: 12px 30px; border-radius: 10px; font-weight: 800; cursor: pointer; }
</style>
