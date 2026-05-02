<template>
  <div class="map-page">
    <div class="final-header">
      <div>
        <h1>خريطة الشبكة التفاعلية</h1>
        <p>عرض جغرافي حي لكافة الأبراج، المشتركين، ومسارات الربط</p>
      </div>
      <div class="header-actions">
        <div class="legend glass">
          <div class="l-item"><span class="dot tower"></span> أبراج</div>
          <div class="l-item"><span class="dot client"></span> مشتركين</div>
          <div class="l-item"><span class="dot issue"></span> أعطال</div>
        </div>
      </div>
    </div>

    <div class="map-container glass">
      <!-- هذه حاوية الخريطة - سنستخدم Leaflet أو Google Maps لاحقاً -->
      <div class="map-placeholder">
        <div class="grid-overlay"></div>
        
        <!-- الأبراج الحقيقية -->
        <div v-for="tower in towers" 
             :key="'t'+tower.id" 
             class="map-node tower" 
             :style="{ top: tower.lat + '%', left: tower.lng + '%' }"
             @click="selectedNode = tower">
          <div class="node-pulse"></div>
          <span class="node-icon">📡</span>
          <div class="node-label">{{ tower.name }}</div>
        </div>

        <!-- المشتركين الحقيقيين -->
        <div v-for="client in clients" 
             :key="'c'+client.id" 
             class="map-node client" 
             :style="{ top: client.lat + '%', left: client.lng + '%' }"
             @click="selectedNode = client">
          <span class="node-icon">🏠</span>
          <div class="node-label">{{ client.name }}</div>
        </div>

        <!-- معلومات العقدة المختارة -->
        <div v-if="selectedNode" class="node-details glass animate-fade">
          <button class="close-btn" @click="selectedNode = null">×</button>
          <h3>{{ selectedNode.name }}</h3>
          <p>{{ selectedNode.package ? 'باقة: ' + selectedNode.package : 'برج رئيسي' }}</p>
          <hr />
          <div class="d-meta">
            <span>الحالة: <b class="text-success">متصل</b></span>
            <span>القوة: <b>-65 dBm</b></span>
          </div>
          <button class="btn-action">فتح التفاصيل الكاملة</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const towers = ref([]);
const clients = ref([]);
const selectedNode = ref(null);

const fetchMapData = async () => {
  try {
    const towersRes = await axios.get('/api/towers');
    const clientsRes = await axios.get('/api/clients');
    
    // سنقوم بتوليد إحداثيات تجريبية إذا كانت فارغة للعرض فقط
    towers.value = towersRes.data.map(t => ({
      ...t,
      lat: t.lat || Math.random() * 80 + 10,
      lng: t.lng || Math.random() * 80 + 10
    }));
    
    clients.value = clientsRes.data.map(c => ({
      ...c,
      lat: c.lat || Math.random() * 80 + 10,
      lng: c.lng || Math.random() * 80 + 10
    }));
  } catch (error) {
    console.error('Error fetching map data:', error);
  }
};

onMounted(fetchMapData);
</script>

<style scoped>
.map-page { width: 100%; height: 100%; }
.final-header { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 25px; }

.legend { display: flex; gap: 20px; padding: 10px 20px; border-radius: 12px; font-size: 12px; font-weight: 800; }
.l-item { display: flex; align-items: center; gap: 8px; }
.dot { width: 10px; height: 10px; border-radius: 50%; }
.dot.tower { background: var(--primary); box-shadow: 0 0 10px var(--primary); }
.dot.client { background: #10b981; box-shadow: 0 0 10px #10b981; }
.dot.issue { background: #ef4444; box-shadow: 0 0 10px #ef4444; }

.map-container { height: calc(100vh - 250px); border-radius: 25px; overflow: hidden; position: relative; }
.map-placeholder { width: 100%; height: 100%; background: #f8fafc; position: relative; background-image: radial-gradient(#e2e8f0 1px, transparent 1px); background-size: 30px 30px; }

.grid-overlay { position: absolute; width: 100%; height: 100%; pointer-events: none; opacity: 0.1; background: linear-gradient(90deg, #cbd5e1 1px, transparent 1px), linear-gradient(#cbd5e1 1px, transparent 1px); background-size: 100px 100px; }

.map-node { position: absolute; cursor: pointer; transition: 0.3s; z-index: 5; transform: translate(-50%, -50%); }
.map-node:hover { transform: translate(-50%, -50%) scale(1.2); z-index: 10; }

.node-icon { font-size: 24px; filter: drop-shadow(0 4px 6px rgba(0,0,0,0.1)); }
.node-label { position: absolute; top: 100%; left: 50%; transform: translateX(-50%); background: white; padding: 2px 8px; border-radius: 6px; font-size: 10px; font-weight: 800; white-space: nowrap; margin-top: 5px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }

.node-pulse { position: absolute; width: 40px; height: 40px; background: rgba(37, 99, 235, 0.2); border-radius: 50%; animation: pulse 2s infinite; pointer-events: none; top: 50%; left: 50%; transform: translate(-50%, -50%); }
@keyframes pulse { 0% { transform: translate(-50%, -50%) scale(0.5); opacity: 1; } 100% { transform: translate(-50%, -50%) scale(2.5); opacity: 0; } }

.node-details { position: absolute; bottom: 30px; right: 30px; width: 300px; padding: 25px; z-index: 20; border-radius: 20px; box-shadow: 0 20px 50px rgba(0,0,0,0.15); }
.close-btn { position: absolute; top: 15px; left: 15px; border: none; background: none; font-size: 24px; cursor: pointer; color: #94a3b8; }

.d-meta { display: flex; justify-content: space-between; font-size: 12px; margin: 15px 0; }
.btn-action { width: 100%; background: var(--primary); color: white; border: none; padding: 12px; border-radius: 10px; font-weight: 800; cursor: pointer; }

.text-success { color: #10b981; }
.glass { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(15px); border: 1px solid rgba(255, 255, 255, 0.5); }
.animate-fade { animation: fadeIn 0.3s ease-out; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
</style>
