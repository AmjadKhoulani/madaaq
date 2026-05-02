<template>
  <div class="dashboard-wrap">

    <!-- Welcome Header -->
    <div class="dash-header">
      <div>
        <h1 class="dash-title">أهلاً بك في غرفة القيادة 🚀</h1>
        <p class="dash-subtitle">إليك ملخص شبكتك في الوقت الحقيقي</p>
      </div>
      <div class="live-time">{{ currentTime }}</div>
    </div>

    <!-- KPI Cards -->
    <div class="kpi-grid">
      <div class="kpi-card" style="--accent: #3b82f6">
        <div class="kpi-icon">👥</div>
        <div class="kpi-body">
          <div class="kpi-value">{{ stats.clients }}</div>
          <div class="kpi-label">إجمالي المشتركين</div>
        </div>
        <router-link to="/cp/clients" class="kpi-link">عرض الكل ←</router-link>
      </div>

      <div class="kpi-card" style="--accent: #10b981">
        <div class="kpi-icon">📡</div>
        <div class="kpi-body">
          <div class="kpi-value">{{ stats.towers }}</div>
          <div class="kpi-label">الأبراج النشطة</div>
        </div>
        <router-link to="/cp/network/towers" class="kpi-link">عرض الكل ←</router-link>
      </div>

      <div class="kpi-card" style="--accent: #8b5cf6">
        <div class="kpi-icon">🎫</div>
        <div class="kpi-body">
          <div class="kpi-value">{{ stats.hotspot }}</div>
          <div class="kpi-label">مشتركو الهوتسبوت</div>
        </div>
        <router-link to="/cp/network/hotspot-gen" class="kpi-link">إنشاء كروت ←</router-link>
      </div>

      <div class="kpi-card" style="--accent: #f59e0b">
        <div class="kpi-icon">📶</div>
        <div class="kpi-body">
          <div class="kpi-value">{{ stats.broadband }}</div>
          <div class="kpi-label">مشتركو البرودباند</div>
        </div>
        <router-link to="/cp/clients" class="kpi-link">عرض الكل ←</router-link>
      </div>
    </div>

    <!-- Main Content -->
    <div class="dash-grid">
      <!-- Recent Clients -->
      <div class="dash-card">
        <div class="card-head">
          <h3>🆕 أحدث المشتركين</h3>
          <router-link to="/cp/clients/add" class="btn-add">+ إضافة</router-link>
        </div>
        <div v-if="recentClients.length === 0" class="empty-hint">
          لا يوجد مشتركين بعد. <router-link to="/cp/clients/add">أضف أول مشترك →</router-link>
        </div>
        <div v-else class="client-list">
          <div v-for="c in recentClients" :key="c.id" class="client-row">
            <div class="c-avatar">{{ (c.name || 'U').charAt(0) }}</div>
            <div class="c-info">
              <strong>{{ c.name }}</strong>
              <small>{{ c.phone }} · {{ c.package }}</small>
            </div>
            <span :class="['c-badge', c.connType]">
              {{ c.connType === 'wired' ? 'سلكي' : c.connType === 'wireless' ? 'لاسلكي' : 'هوتسبوت' }}
            </span>
          </div>
        </div>
      </div>

      <!-- Quick Actions -->
      <div class="dash-card">
        <div class="card-head"><h3>⚡ إجراءات سريعة</h3></div>
        <div class="quick-actions">
          <router-link to="/cp/clients/add" class="qa-btn blue">
            <span>👤</span><span>إضافة مشترك</span>
          </router-link>
          <router-link to="/cp/network/towers/add" class="qa-btn green">
            <span>🗼</span><span>إضافة برج</span>
          </router-link>
          <router-link to="/cp/network/hotspot-gen" class="qa-btn purple">
            <span>🎫</span><span>توليد كروت</span>
          </router-link>
          <router-link to="/cp/network/map" class="qa-btn amber">
            <span>🗺️</span><span>خريطة الشبكة</span>
          </router-link>
          <router-link to="/cp/finance/invoices" class="qa-btn teal">
            <span>🧾</span><span>الفواتير</span>
          </router-link>
          <router-link to="/cp/settings" class="qa-btn gray">
            <span>⚙️</span><span>الإعدادات</span>
          </router-link>
        </div>
      </div>

      <!-- Network Status -->
      <div class="dash-card">
        <div class="card-head"><h3>📡 حالة الشبكة</h3></div>
        <div v-if="stats.towers === 0" class="empty-hint">
          لا توجد أبراج مسجلة. <router-link to="/cp/network/towers/add">أضف أول برج →</router-link>
        </div>
        <div v-else class="tower-status-list">
          <div v-for="t in towers" :key="t.id" class="tower-row">
            <div class="t-dot online"></div>
            <div class="t-info">
              <strong>{{ t.name }}</strong>
              <small>{{ t.location }}</small>
            </div>
            <span class="t-status">Online</span>
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
const stats = ref({ clients: 0, towers: 0, hotspot: 0, broadband: 0 });
const recentClients = ref([]);
const towers = ref([]);

const fetchData = async () => {
  try {
    const [clientsRes, towersRes] = await Promise.allSettled([
      axios.get('/api/clients'),
      axios.get('/api/towers')
    ]);

    if (clientsRes.status === 'fulfilled') {
      const data = Array.isArray(clientsRes.value.data) ? clientsRes.value.data : [];
      stats.value.clients = data.length;
      stats.value.hotspot = data.filter(c => c && c.connType === 'hotspot').length;
      stats.value.broadband = data.filter(c => c && c.connType !== 'hotspot').length;
      recentClients.value = data.slice(0, 5);
    }

    if (towersRes.status === 'fulfilled') {
      const data = Array.isArray(towersRes.value.data) ? towersRes.value.data : [];
      stats.value.towers = data.length;
      towers.value = data.slice(0, 4);
    }
  } catch (err) {
    console.error('Dashboard fetch error:', err);
  }
};

onMounted(() => {
  fetchData();
  setInterval(() => {
    currentTime.value = new Date().toLocaleTimeString('ar-SY');
  }, 1000);
});
</script>

<style scoped>
.dashboard-wrap { width: 100%; direction: rtl; }

/* Header */
.dash-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 35px; }
.dash-title { font-size: 28px; font-weight: 900; color: #1e293b; margin: 0 0 5px; }
.dash-subtitle { color: #64748b; font-weight: 600; margin: 0; }
.live-time { background: #1e293b; color: #a5f3fc; padding: 10px 22px; border-radius: 50px; font-family: monospace; font-size: 18px; font-weight: 700; letter-spacing: 2px; }

/* KPI Grid */
.kpi-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 30px; }
.kpi-card { background: white; border-radius: 18px; padding: 22px; display: flex; flex-direction: column; gap: 10px; border: 1px solid #e2e8f0; border-top: 4px solid var(--accent); transition: transform 0.2s, box-shadow 0.2s; position: relative; overflow: hidden; }
.kpi-card::before { content: ''; position: absolute; inset: 0; background: linear-gradient(135deg, color-mix(in srgb, var(--accent) 5%, transparent), transparent); pointer-events: none; }
.kpi-card:hover { transform: translateY(-4px); box-shadow: 0 15px 35px rgba(0,0,0,0.06); }
.kpi-icon { font-size: 30px; }
.kpi-value { font-size: 36px; font-weight: 900; color: #1e293b; line-height: 1; }
.kpi-label { font-size: 13px; font-weight: 700; color: #64748b; }
.kpi-link { font-size: 12px; font-weight: 700; color: var(--accent); text-decoration: none; }

/* Dashboard Grid */
.dash-grid { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 25px; }
.dash-card { background: white; border-radius: 18px; padding: 25px; border: 1px solid #e2e8f0; }
.card-head { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
.card-head h3 { font-size: 15px; font-weight: 800; color: #1e293b; margin: 0; }
.btn-add { background: #eff6ff; color: #3b82f6; border: none; padding: 6px 14px; border-radius: 8px; font-size: 13px; font-weight: 700; cursor: pointer; text-decoration: none; }

/* Empty State */
.empty-hint { text-align: center; padding: 30px; color: #94a3b8; font-size: 14px; font-weight: 600; }
.empty-hint a { color: #3b82f6; text-decoration: none; font-weight: 700; }

/* Client List */
.client-list { display: flex; flex-direction: column; gap: 12px; }
.client-row { display: flex; align-items: center; gap: 12px; padding: 10px; border-radius: 10px; transition: background 0.2s; }
.client-row:hover { background: #f8fafc; }
.c-avatar { width: 36px; height: 36px; background: #eff6ff; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-weight: 900; font-size: 15px; color: #3b82f6; flex-shrink: 0; }
.c-info { flex: 1; }
.c-info strong { display: block; font-size: 14px; color: #1e293b; }
.c-info small { font-size: 11px; color: #94a3b8; font-weight: 600; }
.c-badge { font-size: 10px; font-weight: 800; padding: 3px 10px; border-radius: 20px; }
.c-badge.wired { background: #eff6ff; color: #3b82f6; }
.c-badge.wireless { background: #f5f3ff; color: #8b5cf6; }
.c-badge.hotspot { background: #fff7ed; color: #f59e0b; }

/* Quick Actions */
.quick-actions { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
.qa-btn { display: flex; align-items: center; gap: 10px; padding: 14px; border-radius: 12px; text-decoration: none; font-size: 13px; font-weight: 700; transition: transform 0.15s; }
.qa-btn:hover { transform: scale(1.03); }
.qa-btn.blue { background: #eff6ff; color: #3b82f6; }
.qa-btn.green { background: #f0fdf4; color: #10b981; }
.qa-btn.purple { background: #f5f3ff; color: #8b5cf6; }
.qa-btn.amber { background: #fffbeb; color: #f59e0b; }
.qa-btn.teal { background: #f0fdfa; color: #14b8a6; }
.qa-btn.gray { background: #f8fafc; color: #64748b; }
.qa-btn span:first-child { font-size: 20px; }

/* Tower Status */
.tower-status-list { display: flex; flex-direction: column; gap: 12px; }
.tower-row { display: flex; align-items: center; gap: 12px; padding: 10px; border-radius: 10px; }
.tower-row:hover { background: #f8fafc; }
.t-dot { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; }
.t-dot.online { background: #10b981; box-shadow: 0 0 6px #10b981; }
.t-dot.offline { background: #ef4444; }
.t-info { flex: 1; }
.t-info strong { display: block; font-size: 14px; color: #1e293b; }
.t-info small { font-size: 11px; color: #94a3b8; font-weight: 600; }
.t-status { font-size: 11px; font-weight: 800; color: #10b981; background: #f0fdf4; padding: 3px 10px; border-radius: 20px; }

@media (max-width: 1100px) {
  .kpi-grid { grid-template-columns: repeat(2, 1fr); }
  .dash-grid { grid-template-columns: 1fr; }
}
</style>
