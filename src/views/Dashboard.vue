<template>
  <div class="dashboard-container">
    <div class="welcome-section">
      <div class="greet">
        <h1>أهلاً بك في غرفة القيادة 🚀</h1>
        <p>إليك ملخص شامل لحالة شبكة MadaaQ اليوم</p>
      </div>
      <div class="live-clock">{{ currentTime }}</div>
    </div>

    <!-- Top Stats Grid -->
    <div class="stats-grid">
      <div class="stat-card glass animate-up">
        <div class="stat-icon clients">👥</div>
        <div class="stat-info">
          <label>إجمالي المشتركين</label>
          <div class="value">{{ stats.clients }}</div>
          <div class="trend positive">+12% هذا الشهر</div>
        </div>
      </div>
      <div class="stat-card glass animate-up delay-1">
        <div class="stat-icon towers">📡</div>
        <div class="stat-info">
          <label>الأبراج النشطة</label>
          <div class="value">{{ stats.towers }}</div>
          <div class="trend">98% استقرار</div>
        </div>
      </div>
      <div class="stat-card glass animate-up delay-2">
        <div class="stat-icon income">💰</div>
        <div class="stat-info">
          <label>أرباح اليوم المتوقعة</label>
          <div class="value">{{ stats.income }} ل.س</div>
          <div class="trend positive">تحسن ملحوظ</div>
        </div>
      </div>
      <div class="stat-card glass animate-up delay-3">
        <div class="stat-icon tickets">🚨</div>
        <div class="stat-info">
          <label>تذاكر دعم فني</label>
          <div class="value">{{ stats.tickets }}</div>
          <div class="trend negative">3 تذاكر حرجة</div>
        </div>
      </div>
    </div>

    <div class="dashboard-layout">
      <!-- Main Chart Section -->
      <div class="main-content card glass overflow-hidden">
        <div class="card-header">
          <h3>مخطط نمو المشتركين (أسبوعي)</h3>
          <div class="chart-legend">
            <span class="dot pppoe"></span> برودباند
            <span class="dot hotspot"></span> هوتسبوت
          </div>
        </div>
        <div class="chart-placeholder">
          <!-- هنا سيتم ربط Chart.js لاحقاً -->
          <div class="mock-chart">
            <div class="bar" style="height: 40%"></div>
            <div class="bar" style="height: 60%"></div>
            <div class="bar" style="height: 55%"></div>
            <div class="bar" style="height: 85%"></div>
            <div class="bar" style="height: 75%"></div>
            <div class="bar" style="height: 95%"></div>
            <div class="bar highlight" style="height: 100%"></div>
          </div>
        </div>
      </div>

      <!-- Sidebar Notifications -->
      <div class="sidebar-alerts">
        <div class="card glass mb-20">
          <h3>تنبيهات الشبكة ⚠️</h3>
          <div class="alert-item critical">
            <span class="pulse"></span>
            <div>
              <strong>برج طريق حلب</strong>
              <p>انقطاع طاقة رئيسي منذ 5 دقائق</p>
            </div>
          </div>
          <div class="alert-item info">
            <span class="dot"></span>
            <div>
              <strong>تحديث نظام</strong>
              <p>سيتم إجراء صيانة مجدولة الساعة 2:00 صباحاً</p>
            </div>
          </div>
        </div>

        <div class="card glass">
          <h3>أحدث المشتركين 🆕</h3>
          <div class="recent-list">
            <div v-for="c in recentClients" :key="c.id" class="r-client">
              <div class="r-avatar">{{ c.name.charAt(0) }}</div>
              <div class="r-info">
                <strong>{{ c.name }}</strong>
                <small>{{ c.package }}</small>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const currentTime = ref(new Date().toLocaleTimeString('ar-SY'));
const stats = ref({ clients: 0, towers: 0, income: '450,000', tickets: 14 });
const recentClients = ref([]);

const fetchDashboardData = async () => {
  try {
    const clientsRes = await axios.get('/api/clients');
    const towersRes = await axios.get('/api/towers');
    stats.value.clients = clientsRes.data.length;
    stats.value.towers = towersRes.data.length;
    recentClients.value = clientsRes.data.slice(0, 5);
  } catch (error) {
    console.error('Error fetching dashboard data:', error);
  }
};

onMounted(() => {
  fetchDashboardData();
  setInterval(() => {
    currentTime.value = new Date().toLocaleTimeString('ar-SY');
  }, 1000);
});
</script>

<style scoped>
.dashboard-container { width: 100%; padding-bottom: 50px; }
.welcome-section { display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; }
.greet h1 { font-size: 32px; font-weight: 900; color: #1e293b; margin-bottom: 5px; }
.greet p { color: #64748b; font-weight: 700; }
.live-clock { background: #1e293b; color: white; padding: 10px 20px; border-radius: 50px; font-weight: 900; font-family: monospace; font-size: 18px; box-shadow: 0 10px 20px rgba(0,0,0,0.1); }

.stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 40px; }
.stat-card { padding: 25px; display: flex; align-items: center; gap: 20px; transition: 0.3s; }
.stat-card:hover { transform: translateY(-5px); box-shadow: 0 20px 40px rgba(0,0,0,0.05); }
.stat-icon { width: 60px; height: 60px; border-radius: 18px; display: flex; align-items: center; justify-content: center; font-size: 28px; background: rgba(255,255,255,0.5); }
.stat-info label { font-size: 12px; font-weight: 800; color: #94a3b8; display: block; margin-bottom: 5px; }
.stat-info .value { font-size: 26px; font-weight: 900; color: #1e293b; }
.trend { font-size: 11px; font-weight: 800; color: #94a3b8; margin-top: 5px; }
.trend.positive { color: #10b981; }
.trend.negative { color: #ef4444; }

.dashboard-layout { display: grid; grid-template-columns: 1fr 350px; gap: 30px; }

.card-header { display: flex; justify-content: space-between; align-items: center; padding: 25px; border-bottom: 1px solid rgba(0,0,0,0.05); }
.card-header h3 { font-size: 16px; font-weight: 900; }
.chart-legend { display: flex; gap: 15px; font-size: 12px; font-weight: 700; color: #64748b; }
.dot { width: 8px; height: 8px; border-radius: 50%; display: inline-block; margin-left: 5px; }
.dot.pppoe { background: var(--primary); }
.dot.hotspot { background: #8b5cf6; }

.chart-placeholder { height: 400px; padding: 40px; display: flex; align-items: flex-end; justify-content: space-between; }
.mock-chart { width: 100%; height: 100%; display: flex; align-items: flex-end; gap: 15px; }
.bar { flex: 1; background: rgba(37, 99, 235, 0.1); border-radius: 8px 8px 0 0; transition: 0.5s; position: relative; }
.bar.highlight { background: var(--primary); box-shadow: 0 10px 20px rgba(37, 99, 235, 0.2); }
.bar::after { content: ""; position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(to top, rgba(255,255,255,0.1), transparent); }

.alert-item { display: flex; gap: 15px; padding: 15px; border-radius: 12px; margin-bottom: 10px; border: 1px solid transparent; }
.alert-item.critical { background: #fef2f2; border-color: #fee2e2; }
.alert-item.info { background: #f0f9ff; border-color: #e0f2fe; }
.alert-item strong { font-size: 13px; color: #1e293b; display: block; }
.alert-item p { font-size: 11px; color: #64748b; margin: 0; font-weight: 700; }

.pulse { width: 10px; height: 10px; background: #ef4444; border-radius: 50%; display: inline-block; margin-top: 5px; animation: pulseAni 1.5s infinite; }
@keyframes pulseAni { 0% { transform: scale(1); opacity: 1; } 100% { transform: scale(2.5); opacity: 0; } }

.recent-list { display: flex; flex-direction: column; gap: 15px; margin-top: 15px; }
.r-client { display: flex; align-items: center; gap: 12px; }
.r-avatar { width: 32px; height: 32px; background: #f1f5f9; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 900; color: var(--primary); }
.r-info strong { font-size: 13px; display: block; }
.r-info small { font-size: 10px; color: #94a3b8; font-weight: 800; }

.animate-up { animation: upFade 0.6s ease-out both; }
.delay-1 { animation-delay: 0.1s; }
.delay-2 { animation-delay: 0.2s; }
.delay-3 { animation-delay: 0.3s; }
@keyframes upFade { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

.glass { background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.5); }
.mb-20 { margin-bottom: 20px; }
</style>
