<template>
  <div class="packages-page">
    <div class="final-header">
      <div>
        <h1>فئات كروت الـ Hotspot</h1>
        <p>إدارة فئات الكروت، تحديد حجم البيانات، صلاحية الوقت، والأسعار</p>
      </div>
      <div class="header-actions">
        <button class="btn-pro-primary">إضافة فئة كروت جديدة ➕</button>
      </div>
    </div>

    <div class="packages-grid">
      <div v-for="pkg in hotspotPackages" :key="pkg.id" class="card package-card">
        <div class="pkg-head">
          <h3>{{ pkg.name }}</h3>
          <div class="pkg-price">{{ pkg.price }}<span>SYP</span></div>
        </div>
        <div class="pkg-body">
          <div class="spec-item"><span>📊 حجم البيانات:</span> <strong>{{ pkg.data }} GB</strong></div>
          <div class="spec-item"><span>⏳ مدة الصلاحية:</span> <strong>{{ pkg.time }}</strong></div>
          <div class="spec-item"><span>⚡ سرعة البث:</span> <strong>{{ pkg.speed }}</strong></div>
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
const hotspotPackages = [
  { id: 1, name: 'كرت الساعة الواحدة', price: 500, data: 1, time: '1 ساعة', speed: 'Open' },
  { id: 2, name: 'كرت اليوم الكامل', price: 2000, data: 5, time: '24 ساعة', speed: '5 Mbps' },
  { id: 3, name: 'الكرت الذهبي (أسبوع)', price: 10000, data: 25, time: '7 أيام', speed: '10 Mbps' }
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
