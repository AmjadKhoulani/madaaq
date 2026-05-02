<template>
  <div class="inventory-form-page">
    <div class="final-header">
      <div>
        <h1>{{ isEdit ? 'تعديل إعدادات الجهاز' : 'إضافة وتكوين جهاز جديد' }}</h1>
        <p>التوثيق الهندسي لمسارات الكيابل، المنافذ، وتوزيع البيانات في الموقع</p>
      </div>
      <div class="header-actions">
        <button class="btn-pro-outline" @click="$router.back()">إلغاء</button>
        <button class="btn-pro-primary">تفعيل وحفظ التكوين ✅</button>
      </div>
    </div>

    <div class="form-grid-layout">
      <!-- Section 1: Technical & Access Config -->
      <div class="card">
        <div class="card-head-pro"><h3>1. التكوين التقني والوصول</h3></div>
        <div class="form-body">
          <div class="input-row-pro">
            <div class="input-group">
              <label>نوع الجهاز</label>
              <select v-model="form.deviceType">
                <option value="ap">Access Point / Sector 📡</option>
                <option value="switch">Switch / Distribution 📦</option>
                <option value="router">Router / Gateway 🌐</option>
                <option value="ptp">Wireless Link (PTP) 🔗</option>
              </select>
            </div>
            <div class="input-group">
              <label>الماركة</label>
              <select v-model="form.brand">
                <option>Ubiquiti</option>
                <option>MikroTik</option>
                <option>Cisco</option>
              </select>
            </div>
          </div>

          <!-- Switch Specific Configuration (MOVED HERE FOR VISIBILITY) -->
          <div v-if="form.deviceType === 'switch'" class="switch-config-box animate-fade">
             <div class="input-row-pro">
              <div class="input-group">
                <label>عدد المنافذ الإجمالي (Ports)</label>
                <select v-model="form.totalPorts" class="highlight-input">
                  <option :value="5">5 Ports</option>
                  <option :value="8">8 Ports</option>
                  <option :value="10">10 Ports</option>
                  <option :value="16">16 Ports</option>
                  <option :value="24">24 Ports</option>
                  <option :value="48">48 Ports</option>
                </select>
              </div>
              <div class="input-group">
                <label>منفذ الدخول الرئيسي (Uplink)</label>
                <input v-model="form.uplinkPort" type="number" placeholder="رقم المنفذ..." class="highlight-input" />
              </div>
            </div>
          </div>
          
          <div class="input-group mt-15">
            <label>اسم الجهاز الوصفي</label>
            <input type="text" placeholder="مثلاً: EdgeSwitch 24 - Main" />
          </div>

          <div class="input-row-pro">
            <div class="input-group">
              <label>يوزر الإدارة (User)</label>
              <input v-model="form.user" type="text" placeholder="admin" />
            </div>
            <div class="input-group">
              <label>باسورد الإدارة (Pass)</label>
              <input v-model="form.pass" type="text" placeholder="••••••••" />
            </div>
          </div>
          
          <div class="divider-pro"></div>
          
          <!-- IP & Wireless -->
          <div v-if="form.deviceType === 'ap' || form.deviceType === 'ptp'" class="input-row-pro animate-fade">
            <div class="input-group">
              <label>اسم الشبكة (SSID)</label>
              <input type="text" placeholder="MadaaQ_S_North" />
            </div>
            <div class="input-group">
              <label>التردد (Freq)</label>
              <input type="text" placeholder="5820 MHz" />
            </div>
          </div>

          <div class="input-row-pro">
            <div class="input-group">
              <label>Management IP</label>
              <input type="text" placeholder="10.10.x.x" />
            </div>
            <div class="input-group">
              <label>VLAN ID</label>
              <input type="number" placeholder="100" />
            </div>
          </div>
        </div>
      </div>

      <!-- Section 2: Physical Connection & Downlink -->
      <div class="card shadow-highlight">
        <div class="card-head-pro"><h3>2. مسار الربط الميداني 🔌</h3></div>
        <div class="form-body">
          <div class="input-group mb-20">
            <label>الموقع المركب فيه (Site)</label>
            <select v-model="form.siteId" :class="{ 'highlight-select': fromSite }">
              <option value="">-- اختر الموقع --</option>
              <option value="1">برج الشمال الرئيسي</option>
              <option value="2">كابينة حي الجلاء</option>
              <option value="3">نقطة توزيع البركة</option>
            </select>
          </div>

          <div class="divider-pro"></div>

          <!-- Downlink Mapping -->
          <div v-if="form.deviceType === 'switch' || form.deviceType === 'router'" class="animate-fade">
            <label class="section-label">توثيق مخارج السويتش (Downlink Ports Mapping):</label>
            <p class="helper-text-p">يرجى تحديد الأجهزة المتصلة بكل منفذ خروج.</p>
            <div class="ports-grid mt-10">
              <div v-for="p in portsToShow" :key="p" class="port-item">
                <span class="p-num" :class="{ 'is-uplink': form.uplinkPort == p }">Port {{ p }}</span>
                <input v-if="form.uplinkPort == p" type="text" disabled value="--- مدخل رئيسي (UPLINK) ---" class="uplink-bg" />
                <input v-else type="text" placeholder="الجهاز/المسار المرتبط..." />
              </div>
            </div>
          </div>

          <div v-else class="input-group">
            <label>متصل عبر (Connected Via)</label>
            <select>
              <option>Switch P1 - EdgeSwitch 24</option>
              <option>Direct Server Link</option>
            </select>
            <textarea class="mt-15" placeholder="ملاحظات توصيل إضافية..."></textarea>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue';
import { useRoute } from 'vue-router';

const route = useRoute();
const isEdit = computed(() => !!route.params.id);
const fromSite = ref(false);

const form = reactive({
  deviceType: 'switch',
  brand: 'Ubiquiti',
  siteId: '',
  totalPorts: 8,
  uplinkPort: 1,
  user: '',
  pass: ''
});

const portsToShow = computed(() => {
  return parseInt(form.totalPorts) || 8;
});

onMounted(() => {
  if (route.query.siteId) {
    form.siteId = route.query.siteId;
    fromSite.value = true;
  }
});
</script>

<style scoped>
.inventory-form-page { width: 100%; max-width: 1300px; margin: 0 auto; }
.final-header { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 35px; }

.form-grid-layout { display: grid; grid-template-columns: 1fr 1fr; gap: 30px; }

.card-head-pro { padding-bottom: 20px; border-bottom: 1px solid #f1f5f9; margin-bottom: 25px; }
.card-head-pro h3 { font-size: 16px; font-weight: 800; color: #1e293b; }

.shadow-highlight { border-top: 5px solid var(--primary); }

.switch-config-box { background: #eff6ff; padding: 20px; border-radius: 12px; border: 1px solid #dbeafe; margin-bottom: 15px; }
.highlight-input { border-color: var(--primary) !important; font-weight: 700; background: white !important; }

.input-group { margin-bottom: 15px; display: flex; flex-direction: column; gap: 8px; }
.input-group label { font-size: 12px; font-weight: 800; color: #64748b; }
.input-group input, .input-group select, .input-group textarea { padding: 12px; border-radius: 10px; border: 1px solid #e2e8f0; outline: none; font-size: 14px; background: #f8fafc; transition: 0.2s; }
.input-group input:focus { border-color: var(--primary); background: white; }

.highlight-select { border-color: var(--primary) !important; background: #eff6ff !important; font-weight: 700; }

.input-row-pro { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
.divider-pro { height: 1px; background: #f1f5f9; margin: 15px 0; }

.ports-grid { display: grid; grid-template-columns: 1fr; gap: 8px; max-height: 400px; overflow-y: auto; padding-right: 5px; }
.port-item { display: flex; align-items: center; gap: 10px; }
.port-item .p-num { font-size: 10px; font-weight: 900; color: #94a3b8; width: 55px; background: #f1f5f9; padding: 4px; border-radius: 4px; text-align: center; }
.port-item .p-num.is-uplink { background: var(--primary); color: white; }
.port-item input { flex: 1; padding: 8px 12px; font-size: 12px; border: 1px solid #e2e8f0; border-radius: 8px; }
.port-item input.uplink-bg { background: #f0fdf4; border-color: #10b981; color: #16a34a; font-weight: 800; font-size: 11px; }

.section-label { font-size: 12px; font-weight: 800; color: #64748b; margin-top: 10px; display: block; }
.helper-text-p { font-size: 11px; color: #94a3b8; margin-bottom: 10px; }

.animate-fade { animation: fadeIn 0.3s ease-in-out; }
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(5px); }
  to { opacity: 1; transform: translateY(0); }
}

.mt-15 { margin-top: 15px; }
.btn-pro-primary { background: var(--primary); color: white; border: none; padding: 12px 30px; border-radius: 10px; font-weight: 800; cursor: pointer; text-decoration: none; }
.btn-pro-outline { background: white; border: 1px solid #e2e8f0; padding: 12px 25px; border-radius: 10px; font-weight: 700; cursor: pointer; }

@media (max-width: 1000px) {
  .form-grid-layout { grid-template-columns: 1fr; }
}
</style>
