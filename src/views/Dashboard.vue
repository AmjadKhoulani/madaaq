<template>
  <div class="dashboard-pro">
    <!-- 1. Quick Stats Banner -->
    <div class="stats-banner">
      <div class="stat-card-glass">
        <div class="stat-icon blue">🌐</div>
        <div class="stat-meta">
          <label>إجمالي الباندويث</label>
          <h2>2.4 <small>Gbps</small></h2>
          <span class="trend up">↑ 12% من أمس</span>
        </div>
      </div>
      <div class="stat-card-glass">
        <div class="stat-icon green">👥</div>
        <div class="stat-meta">
          <label>المشتركين النشطين</label>
          <h2>1,452</h2>
          <span class="trend">98% حالة الاتصال</span>
        </div>
      </div>
      <div class="stat-card-glass">
        <div class="stat-icon gold">💰</div>
        <div class="stat-meta">
          <label>تحصيل اليوم</label>
          <h2>$850.50</h2>
          <span class="trend up">↑ 5% المتوقع</span>
        </div>
      </div>
      <div class="stat-card-glass">
        <div class="stat-icon red">⚠️</div>
        <div class="stat-meta">
          <label>أعطال الشبكة</label>
          <h2>02</h2>
          <span class="trend down">جاري الإصلاح</span>
        </div>
      </div>
    </div>

    <div class="dashboard-grid">
      <!-- 2. Bandwidth Chart Area (Advanced with Numbers) -->
      <div class="card chart-container-pro main-chart">
        <div class="card-head">
          <h3>مراقبة استهلاك البيانات (Live Traffic)</h3>
          <div class="chart-legend">
            <span class="dot dl"></span> تحميل
            <span class="dot ul"></span> رفع
          </div>
        </div>
        <div class="visual-chart-area">
          <div class="y-axis">
            <span>2 Gbps</span>
            <span>1.5 Gbps</span>
            <span>1 Gbps</span>
            <span>500 Mbps</span>
            <span>0</span>
          </div>
          <div class="visual-chart-placeholder">
            <div class="mock-bars">
              <div v-for="i in 20" :key="i" class="bar" :style="{ height: Math.random() * 80 + 20 + '%' }"></div>
            </div>
          </div>
        </div>
        <div class="x-axis">
          <span>08:00 ص</span>
          <span>12:00 م</span>
          <span>04:00 م</span>
          <span>08:00 م</span>
          <span>12:00 ص</span>
        </div>
      </div>

      <!-- 3. NAS / Server Status -->
      <div class="card nas-monitor">
        <div class="card-head"><h3>حالة السيرفرات (NAS)</h3></div>
        <div class="nas-list">
          <div v-for="nas in nasServers" :key="nas.id" class="nas-item">
            <div class="nas-info">
              <strong>{{ nas.name }}</strong>
              <span>IP: {{ nas.ip }}</span>
            </div>
            <div class="nas-stats">
              <div class="mini-progress">
                <label>CPU</label>
                <div class="bar-bg"><div class="bar-fill" :style="{ width: nas.cpu + '%' }"></div></div>
              </div>
              <div class="mini-progress">
                <label>RAM</label>
                <div class="bar-bg"><div class="bar-fill blue" :style="{ width: nas.ram + '%' }"></div></div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- 4. Recent Activity -->
      <div class="card activity-recent">
        <div class="card-head"><h3>أحدث العمليات</h3></div>
        <div class="activity-feed">
          <div v-for="i in 5" :key="i" class="feed-item">
            <div class="feed-dot"></div>
            <div class="feed-content">
              <strong>تفعيل مشترك جديد</strong>
              <p>قام الفني ببرمجة CPE للعميل أحمد محمد</p>
              <small>منذ 5 دقائق</small>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
const nasServers = [
  { id: 1, name: 'Core-Router-01', ip: '10.10.0.1', cpu: 45, ram: 60 },
  { id: 2, name: 'Hotspot-NAS-02', ip: '10.10.0.5', cpu: 12, ram: 30 },
  { id: 3, name: 'Broadband-PPPoE', ip: '10.10.0.10', cpu: 85, ram: 90 }
];
</script>

<style scoped>
.dashboard-pro { width: 100%; display: flex; flex-direction: column; gap: 30px; }

/* Stats Banner */
.stats-banner { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px; }
.stat-card-glass { background: white; padding: 25px; border-radius: 20px; display: flex; align-items: center; gap: 20px; border: 1px solid #e2e8f0; box-shadow: 0 4px 15px rgba(0,0,0,0.02); }
.stat-icon { width: 55px; height: 55px; border-radius: 15px; display: flex; align-items: center; justify-content: center; font-size: 24px; }
.stat-icon.blue { background: #eff6ff; color: #3b82f6; }
.stat-icon.green { background: #f0fdf4; color: #10b981; }
.stat-icon.gold { background: #fffbeb; color: #f59e0b; }
.stat-icon.red { background: #fef2f2; color: #ef4444; }

.stat-meta label { display: block; font-size: 12px; font-weight: 800; color: #64748b; margin-bottom: 5px; }
.stat-meta h2 { font-size: 24px; font-weight: 900; color: #1e293b; margin: 0; }
.stat-meta h2 small { font-size: 14px; font-weight: 700; color: #94a3b8; }
.trend { font-size: 11px; font-weight: 800; color: #94a3b8; }
.trend.up { color: #10b981; }

/* Dashboard Grid */
.dashboard-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 25px; }
.main-chart { grid-column: span 1; height: 400px; }

.card-head { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; }
.card-head h3 { font-size: 16px; font-weight: 800; color: #1e293b; }

/* Advanced Visual Chart */
.visual-chart-area { display: flex; gap: 15px; align-items: stretch; height: 250px; }
.y-axis { display: flex; flex-direction: column; justify-content: space-between; font-size: 10px; font-weight: 800; color: #94a3b8; text-align: left; width: 50px; }
.visual-chart-placeholder { flex: 1; background: #f8fafc; border-radius: 15px; display: flex; align-items: flex-end; justify-content: space-between; padding: 20px; border: 1px solid #f1f5f9; }
.mock-bars { display: flex; align-items: flex-end; gap: 8px; width: 100%; height: 100%; }
.bar { flex: 1; background: linear-gradient(to top, var(--primary), #60a5fa); border-radius: 4px; opacity: 0.8; }
.x-axis { display: flex; justify-content: space-between; padding-right: 65px; margin-top: 15px; font-size: 10px; font-weight: 800; color: #94a3b8; }

/* NAS Monitor */
.nas-list { display: flex; flex-direction: column; gap: 15px; }
.nas-item { padding: 15px; background: #f8fafc; border-radius: 12px; border: 1px solid #f1f5f9; }
.nas-info { display: flex; justify-content: space-between; margin-bottom: 10px; }
.nas-info strong { font-size: 13px; color: #1e293b; }
.nas-info span { font-size: 11px; color: #94a3b8; }

.nas-stats { display: flex; gap: 15px; }
.mini-progress { flex: 1; }
.mini-progress label { font-size: 9px; font-weight: 800; color: #64748b; margin-bottom: 4px; display: block; }
.bar-bg { height: 6px; background: #e2e8f0; border-radius: 10px; overflow: hidden; }
.bar-fill { height: 100%; background: #10b981; }
.bar-fill.blue { background: var(--primary); }

/* Activity Feed */
.activity-recent { height: 100%; }
.activity-feed { display: flex; flex-direction: column; gap: 20px; }
.feed-item { display: flex; gap: 15px; position: relative; }
.feed-dot { width: 10px; height: 10px; background: var(--primary); border-radius: 50%; border: 2px solid white; box-shadow: 0 0 0 4px rgba(0,97,255,0.1); margin-top: 5px; flex-shrink: 0; }
.feed-content strong { font-size: 13px; display: block; color: #1e293b; }
.feed-content p { font-size: 11px; color: #64748b; margin: 4px 0; }
.feed-content small { font-size: 10px; color: #94a3b8; font-weight: 700; }

@media (max-width: 1200px) {
  .dashboard-grid { grid-template-columns: 1fr; }
}
</style>
