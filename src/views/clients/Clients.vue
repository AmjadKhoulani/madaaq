<template>
  <div class="clients-page">
    <div class="final-header">
      <div>
        <h1>قاعدة بيانات المشتركين الحية</h1>
        <p>إدارة الحسابات، متابعة الدفعات، والتحكم بالوصول للشبكة</p>
      </div>
      <div class="header-actions">
        <button class="btn-pro-outline" @click="fetchClients">تحديث البيانات 🔄</button>
        <router-link to="/vendor/clients/add" class="btn-pro-primary">+ إضافة مشترك جديد</router-link>
      </div>
    </div>

    <!-- Stats Dashboard Area -->
    <div class="clients-stats mb-30">
      <div class="stat-mini-card card">
        <label>إجمالي المشتركين</label>
        <div class="val">{{ clients.length }}</div>
      </div>
      <div class="stat-mini-card card">
        <label>نشط الآن</label>
        <div class="val text-success">{{ activeCount }}</div>
      </div>
      <div class="stat-mini-card card">
        <label>أوشك على الانتهاء</label>
        <div class="val text-warning">12</div>
      </div>
    </div>

    <div class="card no-padding overflow-hidden shadow-highlight">
      <div class="table-controls">
        <div class="search-box">
          <span class="icon">🔍</span>
          <input type="text" v-model="searchQuery" placeholder="بحث بالاسم، رقم الهاتف، أو اسم المستخدم..." />
        </div>
        <div class="filters">
          <select v-model="filterType">
            <option value="">كافة أنواع الربط</option>
            <option value="wired">برودباند (سلكي)</option>
            <option value="wireless">برودباند (لاسلكي)</option>
            <option value="hotspot">هوتسبوت</option>
          </select>
        </div>
      </div>

      <table class="madaaq-table">
        <thead>
          <tr>
            <th>المشترك</th>
            <th>نوع الاشتراك</th>
            <th>البرج المرتبط</th>
            <th>الحزمة (Profile)</th>
            <th>تاريخ الانضمام</th>
            <th>الحالة</th>
            <th>إجراءات</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="client in filteredClients" :key="client.id">
            <td>
              <div class="client-cell">
                <div class="avatar-mini">{{ client.name.charAt(0) }}</div>
                <div>
                  <strong>{{ client.name }}</strong>
                  <small>{{ client.phone }}</small>
                </div>
              </div>
            </td>
            <td>
              <span :class="['type-pill', client.connType]">
                {{ client.connType === 'wired' ? '🔗 سلكي' : (client.connType === 'wireless' ? '📡 لاسلكي' : '🎟️ كرت') }}
              </span>
            </td>
            <td>{{ client.linkedTower || 'غير محدد' }}</td>
            <td><strong>{{ client.package }}</strong></td>
            <td>{{ new Date(client.created_at).toLocaleDateString() }}</td>
            <td>
              <span class="status-dot online"></span>
              <span class="status-text">متصل</span>
            </td>
            <td>
              <div class="action-btns">
                <button class="btn-icon" title="تعديل">✏️</button>
                <button class="btn-icon" title="تمديد اشتراك">💰</button>
                <button class="btn-icon danger" title="حذف" @click="deleteClient(client.id)">🗑️</button>
              </div>
            </td>
          </tr>
          <tr v-if="filteredClients.length === 0">
            <td colspan="7" class="empty-state">
              <div class="empty-msg">لا يوجد مشتركين مطابقين للبحث...</div>
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

const clients = ref([]);
const searchQuery = ref('');
const filterType = ref('');
const activeCount = ref(0);

const fetchClients = async () => {
  try {
    const response = await axios.get('/api/clients');
    clients.value = response.data;
    activeCount.value = clients.value.length; // تجريبياً نعتبر الجميع متصلين
  } catch (error) {
    console.error('Error fetching clients:', error);
  }
};

const filteredClients = computed(() => {
  if (!Array.isArray(clients.value)) return [];
  
  return clients.value.filter(c => {
    if (!c) return false;
    const name = c.name || '';
    const phone = c.phone || '';
    const bbUser = c.bbUser || '';
    
    const matchesSearch = name.includes(searchQuery.value) || 
                        phone.includes(searchQuery.value) || 
                        bbUser.includes(searchQuery.value);
    const matchesType = !filterType.value || c.connType === filterType.value;
    return matchesSearch && matchesType;
  });
});

const deleteClient = async (id) => {
  if (!confirm('هل أنت متأكد من حذف هذا المشترك؟ سيتم حذفه من قاعدة البيانات والمايكروتك.')) return;
  try {
    // سنقوم بإضافة endpoint الحذف لاحقاً
    alert('سيتم تفعيل ميزة الحذف في التحديث القادم');
  } catch (error) {
    console.error(error);
  }
};

onMounted(fetchClients);
</script>

<style scoped>
.clients-page { width: 100%; }
.final-header { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 35px; }

.clients-stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
.stat-mini-card { padding: 20px !important; }
.stat-mini-card label { font-size: 12px; font-weight: 800; color: #94a3b8; display: block; margin-bottom: 8px; text-transform: uppercase; }
.stat-mini-card .val { font-size: 28px; font-weight: 900; color: #1e293b; }
.text-success { color: #10b981 !important; }
.text-warning { color: #f59e0b !important; }

.table-controls { padding: 20px; background: #f8fafc; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; }
.search-box { position: relative; width: 350px; }
.search-box .icon { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); opacity: 0.5; }
.search-box input { width: 100%; padding: 10px 10px 10px 40px; border-radius: 10px; border: 1px solid #e2e8f0; outline: none; transition: 0.2s; }
.search-box input:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1); }

.madaaq-table { width: 100%; border-collapse: collapse; }
.madaaq-table th { text-align: right; padding: 15px 20px; background: #f8fafc; color: #64748b; font-size: 11px; font-weight: 800; border-bottom: 2px solid #e2e8f0; text-transform: uppercase; }
.madaaq-table td { padding: 15px 20px; border-bottom: 1px solid #f1f5f9; font-size: 14px; }

.client-cell { display: flex; align-items: center; gap: 12px; }
.avatar-mini { width: 36px; height: 36px; background: #f1f5f9; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-weight: 900; color: var(--primary); }
.client-cell strong { display: block; color: #1e293b; }
.client-cell small { font-size: 11px; color: #94a3b8; font-weight: 700; }

.type-pill { font-size: 11px; font-weight: 800; padding: 4px 10px; border-radius: 6px; }
.type-pill.wired { background: #eff6ff; color: #3b82f6; }
.type-pill.wireless { background: #f5f3ff; color: #8b5cf6; }
.type-pill.hotspot { background: #fff7ed; color: #f59e0b; }

.status-dot { width: 8px; height: 8px; border-radius: 50%; display: inline-block; margin-left: 6px; }
.status-dot.online { background: #10b981; box-shadow: 0 0 8px #10b981; }
.status-text { font-size: 12px; font-weight: 700; color: #10b981; }

.action-btns { display: flex; gap: 8px; }
.btn-icon { width: 32px; height: 32px; border: 1px solid #e2e8f0; border-radius: 8px; background: white; cursor: pointer; transition: 0.2s; display: flex; align-items: center; justify-content: center; font-size: 14px; }
.btn-icon:hover { background: #f8fafc; border-color: var(--primary); }
.btn-icon.danger:hover { background: #fef2f2; border-color: #ef4444; }

.empty-state { padding: 60px !important; text-align: center; color: #94a3b8; font-weight: 700; }

.shadow-highlight { border-top: 4px solid var(--primary); }
</style>
