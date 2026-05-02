<template>
  <div class="towers-page">
    <div class="final-header">
      <div>
        <h1>الأبراج والكبائن (Infrastructure)</h1>
        <p>مراقبة حية للمواقع الجغرافية، حالة الأبراج، وجودة الإشارة الميدانية</p>
      </div>
      <div class="header-actions">
        <router-link to="/cp/network/towers/add" class="btn-pro-primary">إضافة برج جديد 🗼</router-link>
      </div>
    </div>

    <!-- 1. Integrated Live Map (iFrame) -->
    <div class="card no-padding overflow-hidden mb-30 main-map-frame">
      <iframe 
        src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d13300!2d36.3!3d33.5!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2s!4v1700000000000" 
        width="100%" height="350" style="border:0;" allowfullscreen="" loading="lazy">
      </iframe>
      <div class="map-overlay-stats">
        <div class="m-pill">🟢 {{ towers.length }} برج متصل</div>
        <div class="m-pill">🔴 0 صيانة</div>
      </div>
    </div>

    <!-- 2. Stats Banner (Unified) -->
    <div class="stats-banner mb-30">
      <div class="stat-card-glass">
        <div class="stat-icon blue">🗼</div>
        <div class="stat-meta">
          <label>إجمالي المواقع</label>
          <h2>{{ towers.length }}</h2>
          <span class="trend">تغطية شاملة</span>
        </div>
      </div>
      <div class="stat-card-glass">
        <div class="stat-icon green">✅</div>
        <div class="stat-meta">
          <label>أبراج نشطة</label>
          <h2>{{ towers.length }}</h2>
          <span class="trend success">استقرار 100%</span>
        </div>
      </div>
    </div>

    <!-- 3. Towers Table -->
    <div class="card no-padding overflow-hidden">
      <div class="table-filters-pro">
        <div class="search-wrap">
          <span class="icon">🔍</span>
          <input type="text" v-model="searchQuery" placeholder="البحث باسم البرج أو المنطقة..." />
        </div>
      </div>
      
      <table class="final-table">
        <thead>
          <tr>
            <th>اسم البرج / الموقع</th>
            <th>المنطقة</th>
            <th>نوع الطاقة</th>
            <th>الحالة</th>
            <th>العمليات</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="tower in filteredTowers" :key="tower.id">
            <td>
              <div class="tower-mini">
                <div class="site-type-icon">🗼</div>
                <div>
                  <strong>{{ tower.name }}</strong>
                  <span>ID: SITE-00{{ tower.id }}</span>
                </div>
              </div>
            </td>
            <td>{{ tower.location }}</td>
            <td>{{ tower.powerSystem }}</td>
            <td><span class="status-pill success">Online</span></td>
            <td>
              <div class="table-actions">
                <router-link :to="'/cp/network/towers/view/' + tower.id" class="btn-icon">👁️</router-link>
                <router-link :to="'/cp/network/towers/edit/' + tower.id" class="btn-icon">✏️</router-link>
                <button class="btn-icon danger" @click="deleteTower(tower.id)">🗑️</button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';

const towers = ref([]);
const searchQuery = ref('');

const fetchTowers = async () => {
  try {
    const response = await axios.get('/api/towers');
    towers.value = response.data;
  } catch (error) {
    console.error('Error fetching towers:', error);
  }
};

const deleteTower = async (id) => {
  if (!confirm('هل أنت متأكد من حذف هذا البرج؟')) return;
  try {
    await axios.delete(`/api/towers/${id}`);
    fetchTowers();
  } catch (error) {
    console.error('Error deleting tower:', error);
  }
};

const filteredTowers = computed(() => {
  if (!Array.isArray(towers.value)) return [];
  return towers.value.filter(t => 
    (t.name || '').includes(searchQuery.value) || 
    (t.location || '').includes(searchQuery.value)
  );
});

onMounted(fetchTowers);
</script>

<style scoped>
/* إضافة نمط لزر الحذف */
.btn-icon.danger:hover { background: #fef2f2; border-color: #ef4444; color: #ef4444; }
.towers-page { width: 100%; }
.final-header { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 35px; }

/* Integrated Map Frame */
.main-map-frame { position: relative; border: 1px solid #e2e8f0; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
.map-overlay-stats { position: absolute; bottom: 20px; right: 20px; display: flex; gap: 10px; }
.m-pill { background: white; padding: 6px 15px; border-radius: 50px; font-size: 11px; font-weight: 800; box-shadow: 0 5px 15px rgba(0,0,0,0.1); border: 1px solid #f1f5f9; }

/* Stats Banner Unified */
.stats-banner { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; }
.stat-card-glass { background: white; padding: 20px; border-radius: 18px; display: flex; align-items: center; gap: 15px; border: 1px solid #e2e8f0; }
.stat-icon { width: 45px; height: 45px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px; }
.stat-icon.blue { background: #eff6ff; color: #3b82f6; }
.stat-icon.green { background: #f0fdf4; color: #10b981; }
.stat-icon.yellow { background: #fefce8; color: #eab308; }

.stat-meta label { display: block; font-size: 11px; font-weight: 800; color: #64748b; margin-bottom: 3px; }
.stat-meta h2 { font-size: 20px; font-weight: 900; color: #1e293b; margin: 0; }
.trend { font-size: 10px; font-weight: 800; color: #94a3b8; }
.trend.success { color: #10b981; }

.table-filters-pro { padding: 20px 25px; background: #f8fafc; border-bottom: 1px solid #e2e8f0; }
.search-wrap { position: relative; max-width: 400px; }
.search-wrap .icon { position: absolute; right: 15px; top: 12px; opacity: 0.5; }
.search-wrap input { width: 100%; padding: 10px 45px 10px 15px; border-radius: 8px; border: 1px solid #e2e8f0; outline: none; }

.final-table { width: 100%; border-collapse: collapse; }
.final-table th { text-align: right; padding: 15px 25px; font-size: 12px; color: #64748b; background: #f8fafc; }
.final-table td { padding: 15px 25px; border-bottom: 1px solid #f1f5f9; font-size: 14px; }

.tower-mini { display: flex; align-items: center; gap: 12px; }
.site-type-icon { width: 32px; height: 32px; background: #f1f5f9; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0; }
.tower-mini strong { display: block; font-size: 14px; }
.tower-mini span { font-size: 11px; color: #94a3b8; }

.load-mini { display: flex; align-items: center; gap: 10px; width: 120px; }
.load-bar { flex: 1; height: 6px; background: #e2e8f0; border-radius: 10px; overflow: hidden; }
.load-bar .fill { height: 100%; background: var(--primary); }
.load-mini span { font-size: 10px; font-weight: 800; color: #64748b; }

.status-pill { font-size: 11px; font-weight: 800; padding: 4px 12px; border-radius: 20px; }
.status-pill.success { background: #f0fdf4; color: #16a34a; }
.status-pill.danger { background: #fef2f2; color: #ef4444; }

.table-actions { display: flex; gap: 8px; }
.btn-icon { background: white; border: 1px solid #e2e8f0; width: 34px; height: 34px; border-radius: 8px; cursor: pointer; display: flex; align-items: center; justify-content: center; text-decoration: none; font-size: 14px; }

.mb-30 { margin-bottom: 30px; }
.no-padding { padding: 0 !important; }
.overflow-hidden { overflow: hidden; }
.btn-pro-primary { background: var(--primary); color: white; border: none; padding: 12px 30px; border-radius: 10px; font-weight: 800; cursor: pointer; text-decoration: none; display: inline-block; }
</style>
