<template>
  <div class="invoices-page">
    <div class="final-header">
      <div>
        <h1>الفواتير والتحصيل (Billing)</h1>
        <p>إدارة الفواتير، متابعة الديون، وتسجيل عمليات التحصيل المالي</p>
      </div>
      <div class="header-actions">
        <button class="btn-pro-outline">تصدير كشف حساب 📊</button>
        <button class="btn-pro-primary">إنشاء فاتورة جديدة 🧾</button>
      </div>
    </div>

    <!-- Financial Stats Banner -->
    <div class="stats-banner mb-30">
      <div class="stat-card-glass">
        <div class="stat-icon gold">💰</div>
        <div class="stat-meta">
          <label>إيرادات الشهر</label>
          <h2>$14,250</h2>
          <span class="trend success">↑ 10% نمو</span>
        </div>
      </div>
      <div class="stat-card-glass">
        <div class="stat-icon red">📉</div>
        <div class="stat-meta">
          <label>ديون مستحقة</label>
          <h2>$3,420</h2>
          <span class="trend danger">تتطلب متابعة تحصيل</span>
        </div>
      </div>
      <div class="stat-card-glass">
        <div class="stat-icon green">✅</div>
        <div class="stat-meta">
          <label>فواتير محصلة (اليوم)</label>
          <h2>$1,120</h2>
          <span class="trend">تم تسجيل 24 عملية</span>
        </div>
      </div>
    </div>

    <!-- Invoices Table Section -->
    <div class="card no-padding overflow-hidden">
      <div class="table-filters-pro">
        <div class="search-wrap">
          <span class="icon">🔍</span>
          <input type="text" placeholder="البحث برقم الفاتورة أو اسم المشترك..." />
        </div>
        <div class="filter-group">
          <select class="filter-select-pro">
            <option>كل الفواتير</option>
            <option>مدفوعة</option>
            <option>غير مدفوعة</option>
          </select>
        </div>
      </div>
      
      <table class="final-table">
        <thead>
          <tr>
            <th>رقم الفاتورة</th>
            <th>المشترك</th>
            <th>التاريخ</th>
            <th>المبلغ</th>
            <th>الحالة</th>
            <th>العمليات</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="i in 5" :key="i">
            <td><code class="code-box">INV-2024-00{{ i }}</code></td>
            <td>
              <div class="client-mini">
                <strong>أحمد محمد العلي</strong>
                <span>0991234567</span>
              </div>
            </td>
            <td>2024/05/01</td>
            <td><strong>$15.00</strong></td>
            <td><span class="status-pill" :class="i % 2 === 0 ? 'success' : 'warning'">{{ i % 2 === 0 ? 'مدفوعة' : 'قيد الانتظار' }}</span></td>
            <td>
              <div class="table-actions">
                <button class="btn-icon">🧾</button>
                <button class="btn-icon">🖨️</button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<style scoped>
.invoices-page { width: 100%; }
.final-header { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 35px; }

.stats-banner { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; }
.stat-card-glass { background: white; padding: 20px; border-radius: 18px; display: flex; align-items: center; gap: 15px; border: 1px solid #e2e8f0; }
.stat-icon { width: 45px; height: 45px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px; }
.stat-icon.gold { background: #fffbeb; color: #f59e0b; }
.stat-icon.red { background: #fef2f2; color: #ef4444; }
.stat-icon.green { background: #f0fdf4; color: #10b981; }

.stat-meta h2 { font-size: 20px; font-weight: 900; color: #1e293b; margin: 0; }
.trend { font-size: 10px; font-weight: 800; color: #94a3b8; }
.trend.success { color: #10b981; }
.trend.danger { color: #ef4444; }

.table-filters-pro { padding: 20px 25px; background: #f8fafc; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; }
.search-wrap { position: relative; max-width: 400px; flex: 1; }
.search-wrap .icon { position: absolute; right: 15px; top: 12px; opacity: 0.5; }
.search-wrap input { width: 100%; padding: 10px 45px 10px 15px; border-radius: 8px; border: 1px solid #e2e8f0; outline: none; }
.filter-select-pro { padding: 8px 15px; border-radius: 8px; border: 1px solid #e2e8f0; background: white; font-weight: 700; color: #475569; outline: none; }

.final-table { width: 100%; border-collapse: collapse; }
.final-table th { text-align: right; padding: 15px 25px; font-size: 12px; color: #64748b; background: #f8fafc; }
.final-table td { padding: 15px 25px; border-bottom: 1px solid #f1f5f9; font-size: 14px; }

.code-box { background: #f1f5f9; padding: 3px 8px; border-radius: 6px; font-family: monospace; font-size: 12px; color: #475569; }
.client-mini strong { display: block; }
.client-mini span { font-size: 11px; color: #94a3b8; }

.status-pill { font-size: 11px; font-weight: 800; padding: 4px 12px; border-radius: 20px; }
.status-pill.success { background: #f0fdf4; color: #16a34a; }
.status-pill.warning { background: #fffbeb; color: #d97706; }

.table-actions { display: flex; gap: 8px; }
.btn-icon { background: white; border: 1px solid #e2e8f0; width: 34px; height: 34px; border-radius: 8px; cursor: pointer; }

.mb-30 { margin-bottom: 30px; }
.no-padding { padding: 0 !important; }
.overflow-hidden { overflow: hidden; }
.btn-pro-primary { background: var(--primary); color: white; border: none; padding: 12px 30px; border-radius: 10px; font-weight: 800; cursor: pointer; }
.btn-pro-outline { background: white; border: 1px solid #e2e8f0; padding: 12px 25px; border-radius: 10px; font-weight: 700; cursor: pointer; }
</style>
