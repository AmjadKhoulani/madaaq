<template>
  <div class="reports-page-pro">
    <div class="final-header">
      <div>
        <h1>التقارير والإحصائيات المالية</h1>
        <p>تحليل الأرباح، المصاريف التشغيلية، ونمو قاعدة المشتركين</p>
      </div>
      <div class="header-actions">
        <button class="btn-pro-outline">تصدير تقرير سنوي (PDF) 📄</button>
        <button class="btn-pro-primary">تحديث البيانات 🔄</button>
      </div>
    </div>

    <!-- 1. Key Performance Indicators (KPIs) -->
    <div class="kpi-grid mb-30">
      <div class="kpi-card-glass">
        <div class="kpi-icon green">💰</div>
        <div class="kpi-meta">
          <label>إجمالي الإيرادات (الشهر)</label>
          <h2>45,200,000 ل.س</h2>
          <span class="trend success">+12% عن الشهر الماضي</span>
        </div>
      </div>
      <div class="kpi-card-glass">
        <div class="kpi-icon red">📉</div>
        <div class="kpi-meta">
          <label>إجمالي المصاريف</label>
          <h2>12,400,000 ل.س</h2>
          <span class="trend">أجار مواقع، فواتير، صيانة</span>
        </div>
      </div>
      <div class="kpi-card-glass">
        <div class="kpi-icon blue">✨</div>
        <div class="kpi-meta">
          <label>صافي الأرباح</label>
          <h2>32,800,000 ل.س</h2>
          <span class="trend success">أداء ممتاز</span>
        </div>
      </div>
    </div>

    <div class="reports-main-layout">
      <!-- 2. Growth Chart (Simulated with CSS/SVG) -->
      <div class="card chart-container-pro">
        <div class="chart-header-pro">
          <h3>منحنى نمو المشتركين (آخر 6 أشهر)</h3>
          <div class="chart-legend">
            <span><i class="dot blue"></i> مشتركين جدد</span>
            <span><i class="dot purple"></i> تجديد اشتراك</span>
          </div>
        </div>
        <div class="chart-visual-mockup mt-30">
          <div class="bar-chart-sim">
            <div v-for="h in [40, 65, 50, 85, 75, 95]" :key="h" class="bar-wrapper">
              <div class="bar" :style="{ height: h + '%' }">
                <span class="bar-val">{{ h * 10 }}</span>
              </div>
              <span class="bar-label">Month {{ h }}</span>
            </div>
          </div>
        </div>
      </div>

      <!-- 3. Expenses Distribution -->
      <div class="card">
        <h3>توزيع المصاريف 🥧</h3>
        <div class="expenses-list-pro mt-20">
          <div class="exp-item-pro">
            <div class="exp-info">
              <div class="color-tag" style="background: #3b82f6;"></div>
              <strong>أجارات المواقع</strong>
            </div>
            <span>45%</span>
          </div>
          <div class="exp-item-pro">
            <div class="exp-info">
              <div class="color-tag" style="background: #f59e0b;"></div>
              <strong>فواتير الكهرباء</strong>
            </div>
            <span>25%</span>
          </div>
          <div class="exp-item-pro">
            <div class="exp-info">
              <div class="color-tag" style="background: #ef4444;"></div>
              <strong>صيانة طوارئ</strong>
            </div>
            <span>15%</span>
          </div>
          <div class="exp-item-pro">
            <div class="exp-info">
              <div class="color-tag" style="background: #10b981;"></div>
              <strong>رواتب موظفين</strong>
            </div>
            <span>15%</span>
          </div>
        </div>
        <div class="mt-30 text-center">
          <router-link to="/vendor/finance/tower-costs" class="btn-link-action">تفاصيل مصاريف الأبراج ←</router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.reports-page-pro { width: 100%; }
.final-header { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 35px; }

/* KPI Grid */
.kpi-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; }
.kpi-card-glass { background: white; padding: 25px; border-radius: 20px; border: 1px solid #e2e8f0; display: flex; align-items: center; gap: 20px; transition: 0.3s; }
.kpi-card-glass:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(0,0,0,0.05); }

.kpi-icon { width: 60px; height: 60px; border-radius: 18px; display: flex; align-items: center; justify-content: center; font-size: 26px; }
.kpi-icon.green { background: #f0fdf4; color: #16a34a; }
.kpi-icon.red { background: #fef2f2; color: #ef4444; }
.kpi-icon.blue { background: #eff6ff; color: #3b82f6; }

.kpi-meta label { font-size: 12px; font-weight: 800; color: #64748b; display: block; margin-bottom: 5px; }
.kpi-meta h2 { font-size: 22px; font-weight: 900; color: #1e293b; margin: 0; }
.trend { font-size: 11px; font-weight: 800; color: #94a3b8; }
.trend.success { color: #16a34a; }

/* Layout & Charts */
.reports-main-layout { display: grid; grid-template-columns: 1fr 350px; gap: 30px; }
.chart-header-pro { display: flex; justify-content: space-between; align-items: center; }
.chart-header-pro h3 { font-size: 16px; font-weight: 800; color: #1e293b; }

.chart-legend { display: flex; gap: 15px; font-size: 12px; font-weight: 700; color: #64748b; }
.dot { display: inline-block; width: 8px; height: 8px; border-radius: 50%; margin-left: 5px; }
.dot.blue { background: #3b82f6; }
.dot.purple { background: #8b5cf6; }

.chart-visual-mockup { height: 300px; display: flex; align-items: flex-end; padding: 20px; background: #f8fafc; border-radius: 15px; border: 1px dashed #e2e8f0; }
.bar-chart-sim { display: flex; align-items: flex-end; justify-content: space-around; width: 100%; height: 100%; gap: 10px; }
.bar-wrapper { flex: 1; display: flex; flex-direction: column; align-items: center; height: 100%; justify-content: flex-end; }
.bar { width: 100%; max-width: 40px; background: linear-gradient(to top, var(--primary), #60a5fa); border-radius: 8px 8px 0 0; position: relative; transition: 0.5s; }
.bar:hover { filter: brightness(1.1); }
.bar-val { position: absolute; top: -25px; left: 50%; transform: translateX(-50%); font-size: 10px; font-weight: 900; color: #1e293b; }
.bar-label { font-size: 10px; font-weight: 800; color: #94a3b8; margin-top: 10px; }

/* Expenses List */
.expenses-list-pro { display: flex; flex-direction: column; gap: 15px; }
.exp-item-pro { display: flex; justify-content: space-between; align-items: center; font-size: 14px; }
.exp-info { display: flex; align-items: center; gap: 12px; }
.color-tag { width: 12px; height: 12px; border-radius: 3px; }
.exp-item-pro strong { color: #1e293b; }
.exp-item-pro span { font-weight: 900; color: #64748b; }

.btn-link-action { color: var(--primary); text-decoration: none; font-size: 13px; font-weight: 800; }
.btn-link-action:hover { text-decoration: underline; }

.mb-30 { margin-bottom: 30px; }
.mt-20 { margin-top: 20px; }
.mt-30 { margin-top: 30px; }
.text-center { text-align: center; }

.btn-pro-primary { background: var(--primary); color: white; border: none; padding: 12px 30px; border-radius: 10px; font-weight: 800; cursor: pointer; }
.btn-pro-outline { background: white; border: 1px solid #e2e8f0; padding: 12px 25px; border-radius: 10px; font-weight: 700; cursor: pointer; }

@media (max-width: 1000px) {
  .reports-main-layout { grid-template-columns: 1fr; }
}
</style>
