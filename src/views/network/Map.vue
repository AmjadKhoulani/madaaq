<template>
  <div class="live-map-page">
    <!-- Map Sidebar -->
    <div class="map-controls-sidebar card">
      <div class="sidebar-head">
        <h3>رادار الشبكة الحية 🛰️</h3>
        <p>مراقبة جغرافية للحالة التقنية</p>
      </div>
      
      <div class="map-stats-mini mt-20">
        <div class="m-stat green">
          <strong>12</strong>
          <span>أبراج نشطة</span>
        </div>
        <div class="m-stat red pulse">
          <strong>2</strong>
          <span>أعطال حالية</span>
        </div>
      </div>

      <div class="divider-pro"></div>

      <div class="map-layers mt-20">
        <label class="section-label">طبقات العرض</label>
        <div class="layer-item">
          <input type="checkbox" checked /> <span>إظهار مسارات الربط</span>
        </div>
        <div class="layer-item">
          <input type="checkbox" checked /> <span>إظهار المشتركين</span>
        </div>
        <div class="layer-item">
          <input type="checkbox" /> <span>خريطة حرارية (Heatmap)</span>
        </div>
      </div>

      <div class="incident-list mt-30">
        <label class="section-label">تنبيهات نشطة ⚠️</label>
        <div class="incident-card danger">
          <strong>برج حي الجلاء</strong>
          <p>انقطاع التيار الكهربائي - منذ 15 دقيقة</p>
        </div>
      </div>
    </div>

    <!-- Main Map Container -->
    <div class="main-map-container card">
      <div class="map-visual-area">
        <!-- Mockup of a Map with SVG Links & Markers -->
        <svg class="network-map-svg" viewBox="0 0 800 600">
          <!-- Connection Links -->
          <line x1="400" y1="300" x2="200" y2="150" class="link-line ok" />
          <line x1="400" y1="300" x2="600" y2="200" class="link-line ok" />
          <line x1="400" y1="300" x2="500" y2="450" class="link-line broken" />
          
          <!-- Tower Markers -->
          <g class="tower-marker main" transform="translate(400, 300)">
            <circle r="12" class="outer-ring pulse-green" />
            <circle r="6" fill="#10b981" />
            <text y="-20" text-anchor="middle" class="t-name">المركز الرئيسي</text>
          </g>

          <g class="tower-marker" transform="translate(200, 150)">
            <circle r="8" fill="#10b981" />
            <text y="-15" text-anchor="middle" class="t-name">برج الشمال</text>
          </g>

          <g class="tower-marker" transform="translate(600, 200)">
            <circle r="8" fill="#10b981" />
            <text y="-15" text-anchor="middle" class="t-name">كابينة الجلاء</text>
          </g>

          <g class="tower-marker error" transform="translate(500, 450)">
            <circle r="12" class="outer-ring pulse-red" />
            <circle r="8" fill="#ef4444" />
            <text y="-20" text-anchor="middle" class="t-name">نقطة البركة (Offline)</text>
          </g>

          <!-- Subscribers Dots -->
          <circle cx="220" cy="130" r="3" fill="#3b82f6" opacity="0.6" />
          <circle cx="190" cy="170" r="3" fill="#3b82f6" opacity="0.6" />
          <circle cx="240" cy="160" r="3" fill="#3b82f6" opacity="0.6" />
        </svg>

        <div class="map-overlay-tools">
          <button class="map-btn">卫星 (Satellite)</button>
          <button class="map-btn">2D Map</button>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.live-map-page { display: grid; grid-template-columns: 320px 1fr; gap: 20px; height: calc(100vh - 120px); }

.map-controls-sidebar { display: flex; flex-direction: column; overflow-y: auto; }
.sidebar-head h3 { font-size: 16px; font-weight: 800; color: #1e293b; margin-bottom: 5px; }
.sidebar-head p { font-size: 11px; color: #64748b; font-weight: 700; }

.map-stats-mini { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
.m-stat { padding: 15px; border-radius: 12px; text-align: center; }
.m-stat.green { background: #f0fdf4; color: #16a34a; }
.m-stat.red { background: #fef2f2; color: #ef4444; }
.m-stat strong { font-size: 22px; display: block; }
.m-stat span { font-size: 10px; font-weight: 800; }

.pulse { animation: pulseAnim 2s infinite; }
@keyframes pulseAnim {
  0% { transform: scale(1); }
  50% { transform: scale(1.05); }
  100% { transform: scale(1); }
}

.section-label { font-size: 11px; font-weight: 900; color: #94a3b8; display: block; margin-bottom: 15px; text-transform: uppercase; letter-spacing: 1px; }

.layer-item { display: flex; align-items: center; gap: 10px; margin-bottom: 12px; font-size: 13px; font-weight: 700; color: #475569; }
.layer-item input { width: 16px; height: 16px; cursor: pointer; }

.incident-card { padding: 12px; border-radius: 10px; border-right: 4px solid; margin-bottom: 10px; }
.incident-card.danger { background: #fff1f2; border-color: #ef4444; }
.incident-card strong { font-size: 13px; color: #1e293b; display: block; }
.incident-card p { font-size: 11px; color: #64748b; margin-top: 4px; }

/* Map Canvas Area */
.main-map-container { padding: 0 !important; overflow: hidden; position: relative; background: #e2e8f0; }
.map-visual-area { width: 100%; height: 100%; position: relative; background: #f1f5f9; background-image: radial-gradient(#cbd5e1 1px, transparent 1px); background-size: 30px 30px; }

.network-map-svg { width: 100%; height: 100%; }

.link-line { stroke-width: 2; stroke-dasharray: 5,5; animation: dashMove 10s linear infinite; }
@keyframes dashMove { to { stroke-dashoffset: -50; } }

.link-line.ok { stroke: #10b981; opacity: 0.6; }
.link-line.broken { stroke: #ef4444; opacity: 0.8; stroke-dasharray: none; }

.tower-marker .t-name { font-size: 12px; font-weight: 800; fill: #334155; pointer-events: none; }
.tower-marker.error .t-name { fill: #ef4444; }

.outer-ring { opacity: 0.3; }
.pulse-green { fill: #10b981; animation: grow 2s infinite; }
.pulse-red { fill: #ef4444; animation: grow 1.5s infinite; }

@keyframes grow {
  0% { transform: scale(0.8); opacity: 0.5; }
  100% { transform: scale(2); opacity: 0; }
}

.map-overlay-tools { position: absolute; bottom: 20px; right: 20px; display: flex; gap: 10px; }
.map-btn { background: white; border: none; padding: 8px 15px; border-radius: 8px; font-size: 12px; font-weight: 800; box-shadow: 0 5px 15px rgba(0,0,0,0.1); cursor: pointer; }

.divider-pro { height: 1px; background: #f1f5f9; margin: 15px 0; }
</style>
