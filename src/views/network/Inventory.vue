<template>
  <div class="inventory-page">
    <div class="final-header">
      <div>
        <h1>مخزون تجهيزات الشبكة (Infrastructure Assets)</h1>
        <p>إدارة وتتبع كافة الأصول التقنية، السيرفرات، وأجهزة البث الميدانية</p>
      </div>
      <div class="header-actions">
        <button class="btn-pro-outline">تصدير جرد (Excel) 📊</button>
        <router-link to="/vendor/network/inventory/add" class="btn-pro-primary">إضافة قطعة/جهاز جديد ➕</router-link>
      </div>
    </div>

    <!-- 1. Inventory Stats -->
    <div class="stats-banner mb-30">
      <div class="stat-card-glass">
        <div class="stat-icon blue">📦</div>
        <div class="stat-meta">
          <label>إجمالي القطع</label>
          <h2>342</h2>
          <span class="trend">في كافة المواقع</span>
        </div>
      </div>
      <div class="stat-card-glass">
        <div class="stat-icon green">🗼</div>
        <div class="stat-meta">
          <label>أجهزة قيد التشغيل</label>
          <h2>280</h2>
          <span class="trend success">نشطة ميدانياً</span>
        </div>
      </div>
      <div class="stat-card-glass">
        <div class="stat-icon yellow">🛠️</div>
        <div class="stat-meta">
          <label>في المستودع (Spare)</label>
          <h2>62</h2>
          <span class="trend">جاهزة للتركيب</span>
        </div>
      </div>
    </div>

    <!-- 2. Filters & Inventory Table -->
    <div class="card no-padding overflow-hidden">
      <div class="table-filters-pro">
        <div class="filter-row">
          <div class="search-wrap">
            <input type="text" placeholder="البحث بالاسم، السيريال، أو الموديل..." />
          </div>
          <select>
            <option>كافة الأنواع (All Types)</option>
            <option>سيكتورات (Sectors)</option>
            <option>روترات (Routers)</option>
            <option>سويتشات (Switches)</option>
            <option>وصلات لاسلكية (Links)</option>
          </select>
          <select>
            <option>كافة المواقع</option>
            <option>برج الشمال</option>
            <option>المستودع الرئيسي</option>
          </select>
        </div>
      </div>
      
      <table class="final-table">
        <thead>
          <tr>
            <th>اسم الجهاز / الموديل</th>
            <th>الرقم التسلسلي (SN)</th>
            <th>الموقع الحالي</th>
            <th>تاريخ التركيب</th>
            <th>الحالة التقنية</th>
            <th>العمليات</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="i in 6" :key="i">
            <td>
              <div class="item-info">
                <strong>Ubiquiti Rocket Prism 5AC</strong>
                <span>Broadcasting Sector</span>
              </div>
            </td>
            <td><code class="sn-code">UBQ-{{ 1000 + i }}X-SF</code></td>
            <td><span class="location-pill">{{ i % 2 === 0 ? 'برج الشمال' : 'المستودع' }}</span></td>
            <td>2024-03-{{ 10 + i }}</td>
            <td><span class="status-pill" :class="i % 2 === 0 ? 'success' : 'warning'">{{ i % 2 === 0 ? 'In Use' : 'Spare' }}</span></td>
            <td>
              <div class="table-actions">
                <button class="btn-icon">👁️</button>
                <button class="btn-icon">✏️</button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<style scoped>
.inventory-page { width: 100%; }
.final-header { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 35px; }

.stats-banner { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; }
.stat-card-glass { background: white; padding: 20px; border-radius: 18px; display: flex; align-items: center; gap: 15px; border: 1px solid #e2e8f0; }
.stat-icon { width: 45px; height: 45px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px; }
.stat-icon.blue { background: #eff6ff; color: #3b82f6; }
.stat-icon.green { background: #f0fdf4; color: #10b981; }
.stat-icon.yellow { background: #fefce8; color: #eab308; }

.table-filters-pro { padding: 20px 25px; background: #f8fafc; border-bottom: 1px solid #e2e8f0; }
.filter-row { display: flex; gap: 15px; }
.search-wrap { flex: 1; }
.search-wrap input { width: 100%; padding: 10px 15px; border-radius: 8px; border: 1px solid #e2e8f0; outline: none; }
.filter-row select { padding: 10px 15px; border-radius: 8px; border: 1px solid #e2e8f0; background: white; outline: none; min-width: 180px; }

.final-table { width: 100%; border-collapse: collapse; }
.final-table th { text-align: right; padding: 15px 25px; font-size: 12px; color: #64748b; background: #f8fafc; }
.final-table td { padding: 15px 25px; border-bottom: 1px solid #f1f5f9; font-size: 14px; }

.item-info strong { display: block; font-size: 14px; color: #1e293b; }
.item-info span { font-size: 11px; color: #94a3b8; }

.sn-code { background: #f1f5f9; padding: 4px 8px; border-radius: 6px; font-family: monospace; font-size: 12px; color: #475569; }
.location-pill { background: #f8fafc; border: 1px solid #e2e8f0; padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 700; color: #64748b; }

.status-pill { font-size: 11px; font-weight: 800; padding: 4px 12px; border-radius: 20px; }
.status-pill.success { background: #f0fdf4; color: #16a34a; }
.status-pill.warning { background: #fffbeb; color: #d97706; }

.table-actions { display: flex; gap: 8px; }
.btn-icon { background: white; border: 1px solid #e2e8f0; width: 34px; height: 34px; border-radius: 8px; cursor: pointer; display: flex; align-items: center; justify-content: center; }

.mb-30 { margin-bottom: 30px; }
.no-padding { padding: 0 !important; }
.overflow-hidden { overflow: hidden; }

.btn-pro-primary { background: var(--primary); color: white; border: none; padding: 12px 30px; border-radius: 10px; font-weight: 800; cursor: pointer; text-decoration: none; display: inline-block; }
.btn-pro-outline { background: white; border: 1px solid #e2e8f0; padding: 12px 25px; border-radius: 10px; font-weight: 700; cursor: pointer; }
</style>
