<template>
  <div class="tower-form-page">
    <div class="final-header">
      <div>
        <h1>{{ isEdit ? 'تعديل بيانات الموقع' : 'إضافة موقع جديد للشبكة' }}</h1>
        <p>توثيق البيانات التقنية والجغرافية للبرج أو الكابينة</p>
      </div>
      <div class="header-actions">
        <router-link to="/cp/network/towers" class="btn-pro-outline">إلغاء</router-link>
        <button class="btn-pro-primary" @click="saveTower" :disabled="loading">
          {{ loading ? 'جاري الحفظ...' : 'حفظ بيانات الموقع ✅' }}
        </button>
      </div>
    </div>

    <!-- Section 1: Site Type & Name -->
    <div class="card mb-20">
      <div class="card-section-title">🗼 نوع الموقع والمعلومات الأساسية</div>
      <div class="site-type-selector">
        <div
          v-for="t in siteTypes" :key="t.value"
          :class="['site-type-card', { active: form.type === t.value }]"
          @click="form.type = t.value"
        >
          <span>{{ t.icon }}</span>
          <strong>{{ t.label }}</strong>
          <small>{{ t.desc }}</small>
        </div>
      </div>
      <div class="input-row mt-20">
        <div class="input-group span2">
          <label>اسم الموقع *</label>
          <input v-model="form.name" type="text" :placeholder="namePlaceholder" />
        </div>
        <div class="input-group">
          <label>المنطقة / الحي</label>
          <input v-model="form.location" type="text" placeholder="مثلاً: المزة - دمشق" />
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
        <div class="input-group">
          <label>ارتفاع البرج (متر)</label>
          <input v-model="form.height" type="number" placeholder="30" />
        </div>
      </div>
    </div>

    <!-- Section 2: Power System -->
    <div class="card mb-20 card-highlight">
      <div class="card-section-title">⚡ نظام الطاقة والتغذية</div>
      <div class="input-row">
        <div class="input-group">
          <label>نوع الطاقة</label>
          <select v-model="form.powerSystem">
            <option value="solar">طاقة شمسية ☀️</option>
            <option value="grid">كهرباء عامة + UPS 🔌</option>
            <option value="generator">مولد كهربائي ⛽</option>
            <option value="direct">تغذية مباشرة</option>
          </select>
        </div>
        <div class="input-group" v-if="form.powerSystem === 'solar'">
          <label>عدد الألواح الشمسية</label>
          <input v-model="form.solarPanels" type="number" placeholder="4" />
        </div>
        <div class="input-group" v-if="form.powerSystem === 'grid'">
          <label>موديل الـ UPS</label>
          <input v-model="form.upsModel" type="text" placeholder="Mercury 2KVA" />
        </div>
        <div class="input-group" v-if="form.powerSystem === 'generator'">
          <label>سعة الخزان (لتر)</label>
          <input v-model="form.fuelTank" type="number" placeholder="50" />
        </div>
        <div class="input-group">
          <label>نوع الاتصال الرئيسي (Uplink)</label>
          <select v-model="form.uplinkType">
            <option value="fiber">Fiber Optic (GPON)</option>
            <option value="wireless">Wireless (AirFiber)</option>
            <option value="cat6">Ethernet (Cat6)</option>
          </select>
        </div>
      </div>
    </div>

    <!-- Section 3: MikroTik Config -->
    <div class="card mb-20">
      <div class="card-section-title">⚙️ إعدادات الـ MikroTik</div>
      <div class="input-row">
        <div class="input-group">
          <label>IP الـ Router</label>
          <input v-model="form.routerIp" type="text" placeholder="192.168.88.1" />
        </div>
        <div class="input-group">
          <label>اسم المستخدم</label>
          <input v-model="form.routerUser" type="text" placeholder="admin" />
        </div>
        <div class="input-group">
          <label>كلمة المرور</label>
          <input v-model="form.routerPass" type="password" placeholder="••••••" />
        </div>
      </div>
    </div>

    <!-- Section 4: Notes -->
    <div class="card mb-20">
      <div class="card-section-title">📝 ملاحظات إضافية</div>
      <textarea v-model="form.notes" placeholder="أي ملاحظات تقنية حول الموقع، الأعطال السابقة، أو خصوصية الموقع..." rows="4"></textarea>
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

const siteTypes = [
  { value: 'tower', icon: '🗼', label: 'برج رئيسي', desc: 'Site with antenna mast' },
  { value: 'cabinet', icon: '📦', label: 'كابينة توزيع', desc: 'Distribution cabinet' },
  { value: 'node', icon: '📍', label: 'نقطة وصول', desc: 'Access point' }
];

const form = reactive({
  name: '', type: 'tower', location: '', lat: '', lng: '', height: '',
  powerSystem: 'solar', solarPanels: '', upsModel: '', fuelTank: '',
  uplinkType: 'wireless', routerIp: '', routerUser: 'admin', routerPass: '',
  notes: '', status: 'online'
});

const namePlaceholder = computed(() => {
  if (form.type === 'tower') return 'برج الشمال الرئيسي';
  if (form.type === 'cabinet') return 'كابينة حي الجلاء - C15';
  return 'نقطة توزيع بناء البركة';
});

const saveTower = async () => {
  if (!form.name) return alert('اسم الموقع مطلوب');
  loading.value = true;
  try {
    const payload = {
      name: form.name, type: form.type, location: form.location,
      powerSystem: form.powerSystem, lat: form.lat, lng: form.lng,
      status: 'online', notes: form.notes
    };
    if (isEdit.value) {
      await axios.put(`/api/towers/${route.params.id}`, payload);
    } else {
      await axios.post('/api/towers', payload);
    }
    alert('✅ تم حفظ بيانات الموقع بنجاح!');
    router.push('/cp/network/towers');
  } catch (err) {
    alert('حدث خطأ: ' + (err.response?.data?.error || err.message));
  } finally {
    loading.value = false;
  }
};

const fetchTower = async () => {
  if (!isEdit.value) return;
  try {
    const res = await axios.get(`/api/towers/${route.params.id}`);
    const t = res.data;
    Object.assign(form, {
      name: t.name, type: t.type || 'tower', location: t.location,
      powerSystem: t.powerSystem, lat: t.lat, lng: t.lng, notes: t.notes
    });
  } catch (err) { console.error(err); }
};

onMounted(fetchTower);
</script>

<style scoped>
.tower-form-page { width: 100%; max-width: 1100px; }
.final-header { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 30px; }
.final-header h1 { font-size: 24px; font-weight: 900; color: #1e293b; margin: 0 0 5px; }
.final-header p { font-size: 14px; color: #64748b; margin: 0; }
.header-actions { display: flex; gap: 12px; align-items: center; }

.card-section-title { font-size: 15px; font-weight: 900; color: #1e293b; margin-bottom: 20px; padding-bottom: 12px; border-bottom: 1px solid #f1f5f9; }
.card-highlight { border-top: 4px solid #10b981; }

.site-type-selector { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; }
.site-type-card { padding: 20px; border: 2px solid #e2e8f0; border-radius: 14px; cursor: pointer; text-align: center; transition: 0.2s; display: flex; flex-direction: column; gap: 5px; }
.site-type-card span { font-size: 30px; }
.site-type-card strong { font-size: 14px; font-weight: 800; color: #1e293b; }
.site-type-card small { font-size: 11px; color: #94a3b8; }
.site-type-card:hover, .site-type-card.active { border-color: #3b82f6; background: #eff6ff; }

.input-row { display: grid; grid-template-columns: repeat(3, 1fr); gap: 18px; }
.input-row.mt-15 { margin-top: 15px; }
.input-row.mt-20 { margin-top: 20px; }
.input-group { display: flex; flex-direction: column; gap: 7px; }
.input-group.span2 { grid-column: span 2; }
.input-group label { font-size: 12px; font-weight: 800; color: #64748b; }
.input-group input, .input-group select { padding: 11px 14px; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 14px; outline: none; background: #f8fafc; }
.input-group input:focus, .input-group select:focus { border-color: #3b82f6; background: white; }

textarea { width: 100%; padding: 12px 14px; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 14px; outline: none; resize: vertical; background: #f8fafc; font-family: inherit; }
textarea:focus { border-color: #3b82f6; background: white; }

.mb-20 { margin-bottom: 20px; }
.mt-15 { margin-top: 15px; }
.mt-20 { margin-top: 20px; }

.btn-pro-primary { background: #10b981; color: white; border: none; padding: 12px 28px; border-radius: 10px; font-weight: 800; cursor: pointer; font-size: 14px; }
.btn-pro-primary:disabled { opacity: 0.6; cursor: not-allowed; }
.btn-pro-outline { background: white; border: 1px solid #e2e8f0; padding: 12px 22px; border-radius: 10px; font-weight: 700; cursor: pointer; font-size: 14px; color: #475569; text-decoration: none; display: inline-block; }
</style>
