<template>
  <div class="client-form-pro">
    <div class="final-header">
      <div>
        <h1>{{ isEdit ? 'تعديل بيانات المشترك' : 'إضافة مشترك جديد' }}</h1>
        <p>تسجيل بيانات المشترك التقنية وربطه بالشبكة والـ MikroTik</p>
      </div>
      <div class="header-actions">
        <router-link to="/cp/clients" class="btn-pro-outline">إلغاء</router-link>
        <button class="btn-pro-primary" @click="handleSave" :disabled="loading">
          {{ loading ? 'جاري الحفظ...' : 'حفظ المشترك ✅' }}
        </button>
      </div>
    </div>

    <div class="form-layout">
      <!-- القسم 1: المعلومات الأساسية -->
      <div class="card mb-20">
        <div class="card-section-title">👤 المعلومات الأساسية</div>
        <div class="input-row">
          <div class="input-group" :class="{ 'has-error': errors.name }">
            <label>الاسم الكامل *</label>
            <input v-model="form.name" type="text" placeholder="أحمد محمد العلي" @input="clearError('name')" />
            <span v-if="errors.name" class="error-msg">{{ errors.name }}</span>
          </div>
          <div class="input-group" :class="{ 'has-error': errors.phone }">
            <label>رقم الهاتف (واتساب) *</label>
            <input v-model="form.phone" type="text" placeholder="09xxxxxxxx" @input="clearError('phone')" />
            <span v-if="errors.phone" class="error-msg">{{ errors.phone }}</span>
          </div>
          <div class="input-group">
            <label>المنطقة / العنوان</label>
            <input v-model="form.address" type="text" placeholder="حي الروضة - بناء 12" />
          </div>
        </div>
        <div class="input-row mt-15">
          <div class="input-group">
            <label>خط العرض (Lat)</label>
            <input v-model="form.lat" type="text" placeholder="33.5138" />
          </div>
          <div class="input-group">
            <label>خط الطول (Lng)</label>
            <input v-model="form.lng" type="text" placeholder="36.2765" />
          </div>
        </div>
      </div>

      <!-- القسم 2: نوع الاشتراك -->
      <div class="card mb-20 card-highlight">
        <div class="card-section-title">📡 نوع الاشتراك والربط</div>
        <div class="conn-type-selector">
          <div
            v-for="t in connTypes" :key="t.value"
            :class="['conn-card', { active: form.connType === t.value }]"
            @click="form.connType = t.value"
          >
            <span class="conn-icon">{{ t.icon }}</span>
            <strong>{{ t.label }}</strong>
            <small>{{ t.desc }}</small>
          </div>
        </div>

        <!-- Broadband Fields -->
        <div v-if="form.connType === 'wired' || form.connType === 'wireless'" class="input-row mt-20">
          <div class="input-group" :class="{ 'has-error': errors.bbUser }">
            <label>اسم مستخدم PPPoE *</label>
            <input v-model="form.bbUser" type="text" placeholder="ahmad_user" @input="clearError('bbUser')" />
            <span v-if="errors.bbUser" class="error-msg">{{ errors.bbUser }}</span>
          </div>
          <div class="input-group">
            <label>كلمة مرور PPPoE</label>
            <input v-model="form.bbPass" type="text" placeholder="سيتم توليدها تلقائياً" />
          </div>
          <div class="input-group" :class="{ 'has-error': errors.package }">
            <label>الباقة (Profile) *</label>
            <select v-model="form.package" @change="clearError('package')">
              <option value="">اختر الباقة</option>
              <option v-for="p in bbPackages" :key="p.id" :value="p.name">
                {{ p.name }} — ${{ p.price }}/شهر ({{ p.download }}Mbps)
              </option>
            </select>
            <span v-if="errors.package" class="error-msg">{{ errors.package }}</span>
          </div>
        </div>

        <!-- Hotspot Fields -->
        <div v-if="form.connType === 'hotspot'" class="input-row mt-20">
          <div class="input-group" :class="{ 'has-error': errors.portalUser }">
            <label>اسم مستخدم البوابة *</label>
            <input v-model="form.portalUser" type="text" placeholder="portal_user" @input="clearError('portalUser')" />
            <span v-if="errors.portalUser" class="error-msg">{{ errors.portalUser }}</span>
          </div>
          <div class="input-group">
            <label>كلمة المرور</label>
            <input v-model="form.portalPass" type="text" placeholder="123456" />
          </div>
          <div class="input-group" :class="{ 'has-error': errors.package }">
            <label>الباقة *</label>
            <select v-model="form.package" @change="clearError('package')">
              <option value="">اختر الباقة</option>
              <option v-for="p in hotPackages" :key="p.id" :value="p.name">
                {{ p.name }} — {{ p.duration }} يوم
              </option>
            </select>
            <span v-if="errors.package" class="error-msg">{{ errors.package }}</span>
          </div>
        </div>

        <div class="input-row mt-15">
          <div class="input-group" :class="{ 'has-error': errors.linkedTower }">
            <label>البرج المرتبط *</label>
            <select v-model="form.linkedTower" @change="clearError('linkedTower')">
              <option value="">اختر البرج</option>
              <option v-for="t in towers" :key="t.id" :value="t.name">{{ t.name }}</option>
            </select>
            <span v-if="errors.linkedTower" class="error-msg">{{ errors.linkedTower }}</span>
          </div>
        </div>
      </div>

      <!-- القسم 3: إعدادات MikroTik -->
      <div class="card mb-20">
        <div class="card-section-title">⚙️ إعدادات MikroTik</div>
        <div class="mt-settings">
          <div class="input-group" :class="{ 'has-error': errors.mtHost }">
            <label>IP عنوان الـ Router</label>
            <input v-model="mtSettings.host" type="text" placeholder="192.168.88.1" @input="clearError('mtHost')" />
            <span v-if="errors.mtHost" class="error-msg">{{ errors.mtHost }}</span>
          </div>
          <div class="input-group">
            <label>اسم المستخدم</label>
            <input v-model="mtSettings.user" type="text" placeholder="admin" />
          </div>
          <div class="input-group">
            <label>كلمة المرور</label>
            <input v-model="mtSettings.pass" type="password" placeholder="••••••" />
          </div>
        </div>
        <div class="mt-toggle mt-15">
          <label class="toggle-label">
            <input type="checkbox" v-model="createMikrotikAccount" />
            <span class="toggle-switch"></span>
            إنشاء حساب في MikroTik تلقائياً بعد الحفظ
          </label>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import axios from 'axios';

const route = useRoute();
const router = useRouter();
const isEdit = computed(() => !!route.params.id);
const loading = ref(false);
const createMikrotikAccount = ref(false);

const bbPackages = ref([]);
const hotPackages = ref([]);
const towers = ref([]);
const errors = reactive({});

const connTypes = [
  { value: 'wired', icon: '🔗', label: 'Broadband سلكي', desc: 'كابل إيثرنت أو فايبر' },
  { value: 'wireless', icon: '📡', label: 'Broadband لاسلكي', desc: 'Wireless Bridge' },
  { value: 'hotspot', icon: '🎟️', label: 'Hotspot كرت', desc: 'بوابة تسجيل دخول' }
];

const form = reactive({
  name: '', phone: '', address: '', lat: '', lng: '',
  connType: 'wired', linkedTower: '',
  bbUser: '', bbPass: '', portalUser: '', portalPass: '', package: ''
});

const mtSettings = reactive({ host: '', user: 'admin', pass: '' });

const clearError = (field) => {
  if (errors[field]) delete errors[field];
};

const validateForm = () => {
  let isValid = true;
  Object.keys(errors).forEach(key => delete errors[key]);

  if (!form.name) { errors.name = 'الاسم الكامل مطلوب'; isValid = false; }
  if (!form.phone) { errors.phone = 'رقم الهاتف مطلوب'; isValid = false; }
  if (!form.package) { errors.package = 'يرجى اختيار باقة'; isValid = false; }
  if (!form.linkedTower) { errors.linkedTower = 'يرجى اختيار برج'; isValid = false; }

  if (form.connType === 'wired' || form.connType === 'wireless') {
    if (!form.bbUser) { errors.bbUser = 'اسم المستخدم مطلوب للبرودباند'; isValid = false; }
  } else if (form.connType === 'hotspot') {
    if (!form.portalUser) { errors.portalUser = 'اسم المستخدم مطلوب للهوتسبوت'; isValid = false; }
  }

  if (createMikrotikAccount.value && !mtSettings.host) {
    errors.mtHost = 'عنوان IP الـ Router مطلوب للربط';
    isValid = false;
  }

  return isValid;
};

const fetchData = async () => {
  try {
    const [bbRes, hotRes, towersRes] = await Promise.allSettled([
      axios.get('/api/packages?type=broadband'),
      axios.get('/api/packages?type=hotspot'),
      axios.get('/api/towers')
    ]);
    bbPackages.value = bbRes.status === 'fulfilled' && Array.isArray(bbRes.value.data) ? bbRes.value.data : [];
    hotPackages.value = hotRes.status === 'fulfilled' && Array.isArray(hotRes.value.data) ? hotRes.value.data : [];
    towers.value = towersRes.status === 'fulfilled' && Array.isArray(towersRes.value.data) ? towersRes.value.data : [];
  } catch (err) { console.error(err); }
};

const handleSave = async () => {
  if (!validateForm()) {
    // التمرير لأعلى خطأ تلقائياً
    const firstError = document.querySelector('.has-error');
    if (firstError) firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
    return;
  }

  loading.value = true;
  try {
    const res = await axios.post('/api/clients', { ...form });
    
    if (createMikrotikAccount.value && (form.connType === 'wired' || form.connType === 'wireless') && form.bbUser) {
      await axios.post('/api/mikrotik/connect', { host: mtSettings.host, user: mtSettings.user, pass: mtSettings.pass });
      await axios.post('/api/mikrotik/add-pppoe', {
        username: form.bbUser,
        password: form.bbPass || Math.random().toString(36).slice(2, 10),
        profile: form.package || 'default'
      });
    }

    alert('✅ تم حفظ المشترك بنجاح!');
    router.push('/cp/clients');
  } catch (err) {
    console.error(err);
    alert('حدث خطأ: ' + (err.response?.data?.error || err.message));
  } finally {
    loading.value = false;
  }
};

onMounted(fetchData);
</script>

<style scoped>
.client-form-pro { width: 100%; max-width: 1100px; font-family: 'Inter', 'Roboto', sans-serif; }
.final-header { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 30px; }
.final-header h1 { font-size: 24px; font-weight: 900; color: #1e293b; margin: 0 0 5px; }
.final-header p { font-size: 14px; color: #64748b; margin: 0; }

.form-layout { display: flex; flex-direction: column; }
.card-section-title { font-size: 15px; font-weight: 900; color: #1e293b; margin-bottom: 20px; padding-bottom: 12px; border-bottom: 1px solid #f1f5f9; }
.card-highlight { border-top: 4px solid #3b82f6; }

.input-row { display: grid; grid-template-columns: repeat(3, 1fr); gap: 18px; }
.input-row.mt-15 { margin-top: 15px; }
.input-row.mt-20 { margin-top: 20px; }

.input-group { display: flex; flex-direction: column; gap: 7px; position: relative; }
.input-group label { font-size: 13px; font-weight: 800; color: #64748b; font-family: inherit; }
.input-group input, .input-group select {
  padding: 12px 14px; 
  border: 1px solid #e2e8f0; 
  border-radius: 12px; 
  font-size: 14px; 
  outline: none; 
  background: #f8fafc; 
  transition: all 0.2s ease;
  font-family: inherit;
  color: #1e293b;
}

.input-group input:focus, .input-group select:focus { 
  border-color: #3b82f6; 
  background: white; 
  box-shadow: 0 0 0 4px rgba(59,130,246,0.1); 
}

/* Error States */
.has-error input, .has-error select { border-color: #ef4444; background: #fff5f5; }
.has-error label { color: #ef4444; }
.error-msg { font-size: 11px; color: #ef4444; font-weight: 700; margin-top: 4px; }

/* Connection Type Selector */
.conn-type-selector { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; }
.conn-card { padding: 18px; border: 2px solid #e2e8f0; border-radius: 16px; cursor: pointer; transition: 0.2s; text-align: center; display: flex; flex-direction: column; gap: 6px; background: white; }
.conn-card:hover { border-color: #93c5fd; background: #f0f7ff; }
.conn-card.active { border-color: #3b82f6; background: #eff6ff; box-shadow: 0 4px 12px rgba(59,130,246,0.08); }
.conn-card .conn-icon { font-size: 28px; margin-bottom: 5px; }
.conn-card strong { font-size: 14px; font-weight: 800; color: #1e293b; font-family: inherit; }
.conn-card small { font-size: 11px; color: #64748b; font-weight: 600; }

/* MikroTik Settings */
.mt-settings { display: grid; grid-template-columns: repeat(3, 1fr); gap: 18px; }
.mt-toggle { display: flex; align-items: center; }
.toggle-label { display: flex; align-items: center; gap: 10px; cursor: pointer; font-size: 14px; font-weight: 700; color: #475569; }
.toggle-label input[type="checkbox"] { display: none; }
.toggle-switch { width: 44px; height: 24px; background: #e2e8f0; border-radius: 12px; position: relative; transition: 0.3s; flex-shrink: 0; }
.toggle-label input:checked + .toggle-switch { background: #3b82f6; }
.toggle-switch::after { content: ''; position: absolute; width: 18px; height: 18px; background: white; border-radius: 50%; top: 3px; right: 3px; transition: 0.3s; box-shadow: 0 1px 3px rgba(0,0,0,0.2); }
.toggle-label input:checked + .toggle-switch::after { right: calc(100% - 21px); }

.mb-20 { margin-bottom: 20px; }
.mt-15 { margin-top: 15px; }
.btn-pro-primary { background: #3b82f6; color: white; border: none; padding: 12px 32px; border-radius: 12px; font-weight: 800; cursor: pointer; font-size: 14px; font-family: inherit; transition: all 0.2s; }
.btn-pro-primary:hover { background: #2563eb; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(37,99,235,0.2); }
.btn-pro-primary:active { transform: translateY(0); }
.btn-pro-primary:disabled { opacity: 0.6; cursor: not-allowed; }

.btn-pro-outline { background: white; border: 1px solid #e2e8f0; padding: 12px 24px; border-radius: 12px; font-weight: 700; cursor: pointer; font-size: 14px; color: #475569; text-decoration: none; display: inline-block; font-family: inherit; transition: all 0.2s; }
.btn-pro-outline:hover { background: #f8fafc; border-color: #cbd5e1; }

.header-actions { display: flex; gap: 12px; align-items: center; }

@media (max-width: 900px) {
  .input-row, .conn-type-selector, .mt-settings { grid-template-columns: 1fr; }
}
</style>
