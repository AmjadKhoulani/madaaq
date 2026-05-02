<template>
  <div>
    <div class="page-header" style="margin-bottom: 30px;">
      <h1>مراقبة الباندويث (Bandwidth Monitoring)</h1>
      <p style="color: var(--text-muted);">تحليل استهلاك البيانات اللحظي والتاريخي للشبكة</p>
    </div>

    <!-- Live Traffic Chart Placeholder -->
    <div class="card chart-card">
      <div class="chart-header">
        <h3>الاستهلاك اللحظي (Live Traffic)</h3>
        <div class="traffic-stats">
          <span class="up">▲ 120 Mbps</span>
          <span class="down">▼ 850 Mbps</span>
        </div>
      </div>
      <div class="mock-chart">
        <!-- Visual representation of a wave chart -->
        <div class="wave-bg">
          <div class="wave"></div>
        </div>
      </div>
    </div>

    <div class="grid-cols-2" style="display: grid; grid-template-columns: 1fr 1fr; gap: 25px; margin-top: 25px;">
      <!-- Server Usage -->
      <div class="card">
        <h3>أكثر السيرفرات استهلاكاً</h3>
        <div class="usage-list">
          <div class="usage-item">
            <span class="name">سيرفر الشمال</span>
            <div class="bar-container"><div class="bar" style="width: 85%;"></div></div>
            <span class="val">85%</span>
          </div>
          <div class="usage-item">
            <span class="name">سيرفر المركز</span>
            <div class="bar-container"><div class="bar" style="width: 45%;"></div></div>
            <span class="val">45%</span>
          </div>
        </div>
      </div>

      <!-- Top Clients -->
      <div class="card">
        <h3>أكثر المشتركين استهلاكاً (آخر 24 ساعة)</h3>
        <table class="simple-table">
          <thead>
            <tr>
              <th>المشترك</th>
              <th>إجمالي التحميل</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>أحمد محمد</td>
              <td>120 GB</td>
            </tr>
            <tr>
              <td>شركة النور</td>
              <td>85 GB</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<style scoped>
.chart-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
.traffic-stats { display: flex; gap: 15px; font-weight: 800; font-size: 16px; }
.up { color: #3b82f6; }
.down { color: #10b981; }

.mock-chart { height: 250px; background: #f8fafc; border-radius: 12px; position: relative; overflow: hidden; border: 1px solid #e2e8f0; }
.wave-bg { position: absolute; bottom: 0; width: 100%; height: 100px; background: linear-gradient(to top, rgba(37, 99, 235, 0.1), transparent); }
.wave { height: 100%; width: 200%; background: url('https://upload.wikimedia.org/wikipedia/commons/e/ec/Sine_wave.svg'); background-repeat: repeat-x; opacity: 0.2; }

.usage-item { display: flex; align-items: center; gap: 15px; margin-bottom: 15px; }
.name { width: 100px; font-size: 13px; font-weight: 600; }
.bar-container { flex: 1; height: 8px; background: #f1f5f9; border-radius: 4px; overflow: hidden; }
.bar { height: 100%; background: #2563eb; }
.val { font-size: 12px; font-weight: 800; color: #64748b; }

.simple-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
.simple-table th { text-align: right; font-size: 12px; color: #94a3b8; padding-bottom: 10px; }
.simple-table td { padding: 10px 0; border-bottom: 1px solid #f1f5f9; font-size: 14px; font-weight: 600; }
</style>
