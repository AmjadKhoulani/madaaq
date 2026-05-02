<template>
  <div class="packages-page">
    <div class="final-header">
      <div>
        <h1>باقات الـ Hotspot (كروت الإنترنت)</h1>
        <p>إدارة باقات الوقت والسرعة المقيّدة، وربطها بمولد الكروت</p>
      </div>
      <button class="btn-pro-primary" @click="openModal()">إضافة باقة جديدة ➕</button>
    </div>

    <div v-if="packages.length === 0 && !loading" class="empty-state card">
      <div class="empty-icon">🎫</div>
      <h3>لا توجد باقات هوتسبوت بعد</h3>
      <p>أضف باقات الهوتسبوت لتتمكن من توليد كروت الإنترنت</p>
      <button class="btn-pro-primary" @click="openModal()">إضافة أول باقة ➕</button>
    </div>

    <div class="packages-grid">
      <div v-for="pkg in packages" :key="pkg.id" class="package-card card hotspot-card">
        <div class="pkg-top">
          <div class="pkg-badge hotspot">Hotspot</div>
          <div class="pkg-actions">
            <button class="btn-icon" @click="openModal(pkg)">✏️</button>
            <button class="btn-icon danger" @click="deletePackage(pkg.id)">🗑️</button>
          </div>
        </div>
        <h3 class="pkg-name">{{ pkg.name }}</h3>
        <div class="pkg-price">{{ pkg.price }}<span> $/بطاقة</span></div>
        <div class="pkg-specs">
          <div class="spec"><span>⏳ مدة</span><strong>{{ pkg.duration }} يوم</strong></div>
          <div class="spec"><span>🔽 تحميل</span><strong>{{ pkg.download || '∞' }} Mbps</strong></div>
          <div class="spec" v-if="pkg.mikrotik_profile"><span>⚙️ Profile</span><strong>{{ pkg.mikrotik_profile }}</strong></div>
        </div>
      </div>
    </div>

    <div v-if="showModal" class="modal-overlay" @click.self="showModal = false">
      <div class="modal-box card">
        <h3>{{ editId ? 'تعديل الباقة' : 'إضافة باقة Hotspot جديدة' }}</h3>
        <div class="form-grid">
          <div class="fg span2">
            <label>اسم الباقة *</label>
            <input v-model="form.name" placeholder="مثال: بطاقة 3 أيام" />
          </div>
          <div class="fg">
            <label>السعر ($)</label>
            <input v-model="form.price" type="number" placeholder="1.5" />
          </div>
          <div class="fg">
            <label>مدة الصلاحية (أيام)</label>
            <input v-model="form.duration" type="number" placeholder="3" />
          </div>
          <div class="fg">
            <label>سرعة التحميل (Mbps)</label>
            <input v-model="form.download" type="number" placeholder="10" />
          </div>
          <div class="fg">
            <label>MikroTik Profile</label>
            <input v-model="form.mikrotik_profile" placeholder="hotspot-default" />
          </div>
        </div>
        <div class="modal-actions">
          <button class="btn-pro-outline" @click="showModal = false">إلغاء</button>
          <button class="btn-pro-primary" @click="savePackage" :disabled="saving">
            {{ saving ? 'جاري الحفظ...' : '💾 حفظ' }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const packages = ref([]);
const loading = ref(false);
const showModal = ref(false);
const saving = ref(false);
const editId = ref(null);
const emptyForm = { name: '', price: '', download: '', upload: 0, duration: 3, mikrotik_profile: '', notes: '' };
const form = ref({ ...emptyForm });

const fetchPackages = async () => {
  loading.value = true;
  try {
    const res = await axios.get('/api/packages?type=hotspot');
    packages.value = Array.isArray(res.data) ? res.data : [];
  } catch (err) { console.error(err); } 
  finally { loading.value = false; }
};

const openModal = (pkg = null) => {
  editId.value = pkg ? pkg.id : null;
  form.value = pkg ? { ...pkg } : { ...emptyForm };
  showModal.value = true;
};

const savePackage = async () => {
  if (!form.value.name) return alert('اسم الباقة مطلوب');
  saving.value = true;
  try {
    if (editId.value) {
      await axios.put(`/api/packages/${editId.value}`, form.value);
    } else {
      await axios.post('/api/packages', { ...form.value, type: 'hotspot' });
    }
    showModal.value = false;
    await fetchPackages();
  } catch (err) { alert('خطأ: ' + err.message); }
  finally { saving.value = false; }
};

const deletePackage = async (id) => {
  if (!confirm('حذف هذه الباقة؟')) return;
  await axios.delete(`/api/packages/${id}`);
  await fetchPackages();
};

onMounted(fetchPackages);
</script>

<style scoped>
.packages-page { width: 100%; }
.final-header { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 30px; }
.final-header h1 { font-size: 24px; font-weight: 900; color: #1e293b; margin: 0 0 5px; }
.final-header p { font-size: 14px; color: #64748b; margin: 0; }
.empty-state { text-align: center; padding: 60px; }
.empty-icon { font-size: 50px; margin-bottom: 15px; }
.empty-state h3 { font-size: 18px; font-weight: 800; margin: 0 0 8px; }
.empty-state p { color: #64748b; margin-bottom: 20px; }
.packages-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 22px; }
.package-card { border-top: 4px solid #8b5cf6; transition: transform 0.2s; }
.package-card:hover { transform: translateY(-4px); }
.pkg-top { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
.pkg-badge { font-size: 10px; font-weight: 800; padding: 3px 10px; border-radius: 20px; }
.pkg-badge.hotspot { background: #f5f3ff; color: #8b5cf6; }
.pkg-actions { display: flex; gap: 6px; }
.pkg-name { font-size: 18px; font-weight: 900; color: #1e293b; margin: 0 0 8px; }
.pkg-price { font-size: 30px; font-weight: 900; color: #8b5cf6; margin-bottom: 20px; }
.pkg-price span { font-size: 13px; color: #94a3b8; }
.pkg-specs { display: flex; flex-direction: column; gap: 8px; }
.spec { background: #f8fafc; border-radius: 8px; padding: 8px 12px; display: flex; justify-content: space-between; }
.spec span { font-size: 11px; color: #94a3b8; font-weight: 700; }
.spec strong { font-size: 13px; color: #1e293b; }
.btn-icon { background: #f8fafc; border: 1px solid #e2e8f0; width: 32px; height: 32px; border-radius: 8px; cursor: pointer; font-size: 14px; }
.btn-icon.danger:hover { background: #fef2f2; border-color: #fecaca; }
.modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 2000; display: flex; align-items: center; justify-content: center; }
.modal-box { width: 500px; padding: 35px; }
.modal-box h3 { font-size: 18px; font-weight: 900; margin: 0 0 25px; }
.form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 25px; }
.fg { display: flex; flex-direction: column; gap: 6px; }
.fg.span2 { grid-column: span 2; }
.fg label { font-size: 12px; font-weight: 800; color: #64748b; }
.fg input { padding: 10px 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px; outline: none; }
.modal-actions { display: flex; justify-content: flex-end; gap: 12px; }
.btn-pro-primary { background: #8b5cf6; color: white; border: none; padding: 12px 28px; border-radius: 10px; font-weight: 800; cursor: pointer; }
.btn-pro-primary:disabled { opacity: 0.6; }
.btn-pro-outline { background: white; border: 1px solid #e2e8f0; padding: 12px 22px; border-radius: 10px; font-weight: 700; cursor: pointer; }
</style>
