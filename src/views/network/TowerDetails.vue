<template>
  <div class="tower-details-pro">
    <div class="final-header">
      <div>
        <div class="site-tag">Site ID: #{{ $route.params.id }}</div>
        <h1>مركز إدارة الموقع: برج الشمال الرئيسي</h1>
        <p>مراقبة الهيكلية الهندسية، مسارات المنافذ، وتوزيع الطاقة الميداني</p>
      </div>
      <div class="header-actions">
        <button class="btn-pro-outline" @click="$router.back()">عودة ⬅️</button>
        <router-link :to="{ path: '/vendor/network/inventory/add', query: { siteId: $route.params.id, siteName: 'برج الشمال الرئيسي' }}" class="btn-pro-primary-alt">+ إضافة جهاز للموقع 📡</router-link>
        <router-link :to="'/vendor/network/towers/edit/1'" class="btn-pro-primary">إعدادات الموقع ⚙️</router-link>
      </div>
    </div>

    <!-- 1. Network Topology Summary -->
    <div class="network-topology-banner mb-30">
      <div class="topo-card">
        <label>مصدر التغذية (Uplink)</label>
        <div class="topo-val">
          <span class="icon">🔗</span>
          <div>
            <strong>AirFiber 5XHD</strong>
            <span>From: Main Data Center (IP: 10.10.1.1)</span>
          </div>
        </div>
      </div>
      <div class="topo-card active">
        <label>قلب الموقع (Main Switch)</label>
        <div class="topo-val">
          <span class="icon">📦</span>
          <div>
            <strong>EdgeSwitch 24 PoE</strong>
            <span>IP: 10.10.20.1 | VLAN: 100</span>
          </div>
        </div>
      </div>
      <div class="topo-card">
        <label>توزيع البث (Downlink)</label>
        <div class="topo-val">
          <span class="icon">📡</span>
          <div>
            <strong>04 Sectors Active</strong>
            <span>Total Clients: 145</span>
          </div>
        </div>
      </div>
    </div>

    <div class="details-main-grid">
      <!-- 2. Equipment & Port Mapping -->
      <div class="card no-padding overflow-hidden">
        <div class="table-header-pro">
          <h3>خارطة توزيع المنافذ والتوصيل (Port Mapping)</h3>
        </div>
        <table class="logic-table">
          <thead>
            <tr>
              <th>المنفذ (Port)</th>
              <th>الجهاز المتصل</th>
              <th>نوع الجهاز</th>
              <th>الـ SSID / التردد</th>
              <th>الـ IP المحلي</th>
              <th>الحالة</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="i in 5" :key="i">
              <td><span class="port-num">P{{ i }}</span></td>
              <td>
                <div v-if="i === 1" class="connected-item"><strong>Uplink AirFiber</strong><small>Backhaul Link</small></div>
                <div v-else class="connected-item"><strong>Sector North-{{ i }}</strong><small>Ubiquiti AC</small></div>
              </td>
              <td>{{ i === 1 ? '🔗 Link' : '📡 Sector' }}</td>
              <td>
                <div v-if="i > 1" class="ssid-info">
                  <strong>MadaaQ_N{{ i }}</strong>
                  <small>5820 MHz</small>
                </div>
                <span v-else>-</span>
              </td>
              <td><code class="ip-code">10.10.20.{{ i + 10 }}</code></td>
              <td><span class="status-dot online"></span> نشط</td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- 3. Real-time Map & Power -->
      <div class="side-panel">
        <div class="card no-padding overflow-hidden map-side mb-20">
          <iframe 
            src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d3325.215!2d36.2765!3d33.5138!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2s!4v1700000000000" 
            width="100%" height="250" style="border:0;" allowfullscreen="" loading="lazy">
          </iframe>
        </div>

        <div class="card power-monitor-side">
          <h3>مراقب الطاقة ⚡</h3>
          <div class="power-stats mt-15">
            <div class="p-item">
              <label>البطاريات</label>
              <div class="p-bar"><div class="fill" style="width: 92%"></div></div>
              <span>92%</span>
            </div>
            <div class="p-item">
              <label>الطاقة الشمسية</label>
              <div class="p-bar"><div class="fill solar" style="width: 75%"></div></div>
              <span>450W</span>
            </div>
          </div>
          <div class="mt-20 location-box">
            <label>ملاحظات الوصول:</label>
            <p>بناء البركة، المدخل الخلفي، المفتاح مع الفني المسؤول.</p>
          </div>
        </div>
      </div> <!-- Close side-panel -->
    </div> <!-- Close details-main-grid -->

    <!-- 4. Site Expenses Section (NEW from OLD Style) -->
    <div class="card mt-30 no-padding overflow-hidden">
      <div class="table-header-pro flex-header">
        <h3>سجل المصاريف التشغيلية للموقع 💸</h3>
        <button class="btn-pro-outline-small">+ إضافة مصروف جديد</button>
      </div>
      <table class="logic-table">
        <thead>
          <tr>
            <th>البيان (Description)</th>
            <th>التاريخ</th>
            <th>الفئة</th>
            <th>المبلغ</th>
            <th>الحالة</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>أجار الموقع - شهر نيسان</td>
            <td>2024-04-01</td>
            <td><span class="category-pill blue">أجارات</span></td>
            <td class="price-text">500,000 ل.س</td>
            <td><span class="status-pill-small success">مدفوع</span></td>
          </tr>
          <tr>
            <td>صيانة دورية للمولد وتبديل زيت</td>
            <td>2024-04-15</td>
            <td><span class="category-pill orange">صيانة</span></td>
            <td class="price-text">250,000 ل.س</td>
            <td><span class="status-pill-small success">مدفوع</span></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
// No script needed for now, using mock data
</script>

<style scoped>
.tower-details-pro { width: 100%; }
.final-header { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 35px; }
.site-tag { font-size: 11px; font-weight: 900; color: var(--primary); background: rgba(37, 99, 235, 0.1); padding: 4px 12px; border-radius: 50px; display: inline-block; margin-bottom: 8px; }

.network-topology-banner { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; }
.topo-card { background: white; border: 1px solid #e2e8f0; padding: 20px; border-radius: 15px; position: relative; }
.topo-card.active { border-color: var(--primary); box-shadow: 0 10px 25px rgba(37, 99, 235, 0.05); }
.topo-card label { font-size: 11px; font-weight: 800; color: #94a3b8; text-transform: uppercase; display: block; margin-bottom: 12px; }
.topo-val { display: flex; align-items: center; gap: 15px; }
.topo-val .icon { width: 40px; height: 40px; background: #f8fafc; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 18px; }
.topo-val strong { display: block; font-size: 15px; color: #1e293b; }
.topo-val span { font-size: 11px; color: #64748b; }

.details-main-grid { display: grid; grid-template-columns: 1fr 380px; gap: 30px; }
.logic-table { width: 100%; border-collapse: collapse; }
.logic-table th { text-align: right; padding: 15px 20px; background: #f8fafc; color: #64748b; font-size: 12px; border-bottom: 2px solid #e2e8f0; }
.logic-table td { padding: 15px 20px; border-bottom: 1px solid #f1f5f9; font-size: 13.5px; }

.port-num { background: #1e293b; color: white; padding: 2px 8px; border-radius: 4px; font-size: 11px; font-weight: 800; font-family: monospace; }
.connected-item strong { display: block; }
.connected-item small { color: #94a3b8; font-size: 11px; }
.ssid-info strong { display: block; color: var(--primary); }
.ssid-info small { color: #64748b; font-size: 11px; }
.ip-code { font-family: monospace; background: #f1f5f9; padding: 3px 6px; border-radius: 4px; font-size: 12px; }

.status-dot { display: inline-block; width: 8px; height: 8px; border-radius: 50%; margin-left: 5px; }
.status-dot.online { background: #22c55e; box-shadow: 0 0 8px #22c55e; }

.power-monitor-side h3 { font-size: 15px; font-weight: 800; color: #1e293b; }
.p-item { margin-bottom: 15px; }
.p-item label { font-size: 11px; font-weight: 800; color: #64748b; display: block; margin-bottom: 5px; }
.p-bar { height: 8px; background: #f1f5f9; border-radius: 10px; overflow: hidden; margin-bottom: 5px; }
.p-bar .fill { height: 100%; background: #22c55e; }
.p-bar .fill.solar { background: #f59e0b; }
.p-item span { font-size: 12px; font-weight: 800; color: #1e293b; }

.location-box { background: #f8fafc; padding: 15px; border-radius: 12px; border: 1px solid #f1f5f9; }
.location-box label { font-size: 11px; font-weight: 800; color: #94a3b8; display: block; margin-bottom: 5px; }
.location-box p { font-size: 12px; color: #475569; margin: 0; line-height: 1.6; }

.table-header-pro { padding: 20px; border-bottom: 1px solid #f1f5f9; background: #fff; }
.table-header-pro h3 { font-size: 15px; font-weight: 800; color: #1e293b; margin: 0; }
.flex-header { display: flex; justify-content: space-between; align-items: center; }

.category-pill { padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 800; }
.category-pill.blue { background: #eff6ff; color: #3b82f6; }
.category-pill.orange { background: #fff7ed; color: #f59e0b; }
.status-pill-small { padding: 2px 8px; border-radius: 4px; font-size: 10px; font-weight: 800; }
.status-pill-small.success { background: #f0fdf4; color: #16a34a; }
.price-text { font-weight: 900; color: #1e293b; }

.mb-30 { margin-bottom: 30px; }
.mb-20 { margin-bottom: 20px; }
.mt-30 { margin-top: 30px; }
.mt-15 { margin-top: 15px; }
.mt-20 { margin-top: 20px; }
.no-padding { padding: 0 !important; }
.overflow-hidden { overflow: hidden; }

.btn-pro-primary { background: var(--primary); color: white; border: none; padding: 12px 25px; border-radius: 10px; font-weight: 800; cursor: pointer; text-decoration: none; font-size: 13px; }
.btn-pro-primary-alt { background: white; color: var(--primary); border: 1px solid var(--primary); padding: 12px 25px; border-radius: 10px; font-weight: 800; cursor: pointer; text-decoration: none; font-size: 13px; }
.btn-pro-outline { background: white; border: 1px solid #e2e8f0; padding: 12px 20px; border-radius: 10px; font-weight: 700; cursor: pointer; font-size: 13px; }
.btn-pro-outline-small { background: white; border: 1px solid #e2e8f0; padding: 6px 12px; border-radius: 6px; font-size: 11px; font-weight: 700; cursor: pointer; }

@media (max-width: 1200px) {
  .details-main-grid { grid-template-columns: 1fr; }
  .network-topology-banner { grid-template-columns: 1fr; }
}
</style>
