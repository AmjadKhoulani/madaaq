<template>
  <div class="crm-page">
    <div class="final-header">
      <div>
        <h1>إدارة المشتركين (CRM)</h1>
        <p>متابعة بيانات المشتركين، حالة الاتصال، والاشتراكات النشطة</p>
      </div>
      <div class="header-actions">
        <router-link to="/vendor/clients/add" class="btn-pro-primary">إضافة مشترك جديد ➕</router-link>
      </div>
    </div>

    <!-- Stats Banner (Unified Identity) -->
    <div class="stats-banner mb-30">
      <div class="stat-card-glass">
        <div class="stat-icon blue">👥</div>
        <div class="stat-meta">
          <label>إجمالي المشتركين</label>
          <h2>1,452</h2>
          <span class="trend">نمو +5% هذا الشهر</span>
        </div>
      </div>
      <div class="stat-card-glass">
        <div class="stat-icon green">⚡</div>
        <div class="stat-meta">
          <label>المتصلون الآن</label>
          <h2>1,120</h2>
          <span class="trend success">أداء الشبكة مستقر</span>
        </div>
      </div>
      <div class="stat-card-glass">
        <div class="stat-icon red">🛑</div>
        <div class="stat-meta">
          <label>اشتراكات منتهية</label>
          <h2>45</h2>
          <span class="trend danger">تتطلب تحصيل</span>
        </div>
      </div>
    </div>

    <!-- CRM Table Section -->
    <div class="card no-padding overflow-hidden">
      <div class="table-filters-pro">
        <div class="search-wrap">
          <span class="icon">🔍</span>
          <input type="text" placeholder="البحث باسم المشترك، رقم الهاتف، أو الـ IP..." />
        </div>
        <select class="filter-select-pro">
          <option>كل الحالات</option>
          <option>نشط</option>
          <option>منتهي</option>
        </select>
      </div>
      
      <table class="final-table">
        <thead>
          <tr>
            <th>المشترك</th>
            <th>نوع الاشتراك</th>
            <th>الباقة</th>
            <th>تاريخ الانتهاء</th>
            <th>الحالة</th>
            <th>العمليات</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="i in 5" :key="i">
            <td>
              <div class="client-mini">
                <strong>أحمد محمد العلي</strong>
                <span>0991234567</span>
              </div>
            </td>
            <td>Broadband</td>
            <td>Premium 50M</td>
            <td>2024/05/20</td>
            <td><span class="status-pill success">متصل</span></td>
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
.crm-page { width: 100%; }
.final-header { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 35px; }
.final-header h1 { font-size: 24px; font-weight: 900; color: #1e293b; }
.final-header p { font-size: 14px; color: #64748b; }

/* Stats Banner Unified */
.stats-banner { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; }
.stat-card-glass { background: white; padding: 20px; border-radius: 18px; display: flex; align-items: center; gap: 15px; border: 1px solid #e2e8f0; }
.stat-icon { width: 45px; height: 45px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px; }
.stat-icon.blue { background: #eff6ff; color: #3b82f6; }
.stat-icon.green { background: #f0fdf4; color: #10b981; }
.stat-icon.red { background: #fef2f2; color: #ef4444; }

.stat-meta label { display: block; font-size: 11px; font-weight: 800; color: #64748b; margin-bottom: 3px; }
.stat-meta h2 { font-size: 20px; font-weight: 900; color: #1e293b; margin: 0; }
.trend { font-size: 10px; font-weight: 800; color: #94a3b8; }
.trend.success { color: #10b981; }
.trend.danger { color: #ef4444; }

.mb-30 { margin-bottom: 30px; }
.no-padding { padding: 0 !important; }
.overflow-hidden { overflow: hidden; }

.table-filters-pro { padding: 20px 25px; background: #f8fafc; border-bottom: 1px solid #e2e8f0; display: flex; gap: 15px; }
.search-wrap { flex: 1; position: relative; }
.search-wrap .icon { position: absolute; right: 15px; top: 12px; opacity: 0.5; }
.search-wrap input { width: 100%; padding: 10px 45px 10px 15px; border-radius: 8px; border: 1px solid #e2e8f0; outline: none; }
.filter-select-pro { padding: 10px 20px; border-radius: 8px; border: 1px solid #e2e8f0; font-weight: 700; color: #475569; }

.final-table { width: 100%; border-collapse: collapse; }
.final-table th { text-align: right; padding: 15px 25px; font-size: 12px; color: #64748b; background: #f8fafc; }
.final-table td { padding: 15px 25px; border-bottom: 1px solid #f1f5f9; font-size: 14px; }

.client-mini { display: flex; flex-direction: column; }
.client-mini span { font-size: 11px; color: #94a3b8; }

.status-pill { font-size: 11px; font-weight: 800; padding: 4px 12px; border-radius: 20px; }
.status-pill.success { background: #f0fdf4; color: #16a34a; }

.table-actions { display: flex; gap: 8px; }
.btn-icon { background: white; border: 1px solid #e2e8f0; width: 34px; height: 34px; border-radius: 8px; cursor: pointer; display: flex; align-items: center; justify-content: center; }

.btn-pro-primary { background: var(--primary); color: white; border: none; padding: 12px 30px; border-radius: 10px; font-weight: 800; cursor: pointer; text-decoration: none; display: inline-block; }
</style>
