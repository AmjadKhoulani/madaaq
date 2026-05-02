<template>
  <div class="staff-page">
    <div class="final-header">
      <div>
        <h1>فريق العمل 👔</h1>
        <p>إدارة الفنيين وموظفي الدعم الفني وصلاحياتهم في النظام</p>
      </div>
      <button class="btn-pro-primary" @click="openModal()">إضافة موظف جديد ➕</button>
    </div>

    <div v-if="staff.length === 0" class="empty-state card">
      <div class="empty-icon">👔</div>
      <h3>لا يوجد موظفون مسجلون بعد</h3>
      <p>أضف فريقك الفني لمتابعة المهام الميدانية</p>
      <button class="btn-pro-primary" @click="openModal()">إضافة أول موظف ➕</button>
    </div>

    <div class="staff-grid">
      <div v-for="s in staff" :key="s.id" class="staff-card card">
        <div class="staff-avatar">{{ (s.name || 'U').charAt(0) }}</div>
        <h3>{{ s.name }}</h3>
        <div :class="['role-badge', s.role]">{{ roleLabel(s.role) }}</div>
        <div class="staff-info">
          <div class="si-row"><span>📱</span>{{ s.phone }}</div>
          <div class="si-row"><span>📍</span>{{ s.area || 'غير محدد' }}</div>
        </div>
        <div class="staff-actions">
          <button class="btn-icon" @click="openModal(s)">✏️</button>
          <button class="btn-icon danger" @click="deleteStaff(s.id)">🗑️</button>
        </div>
      </div>
    </div>

    <!-- Modal -->
    <div v-if="showModal" class="modal-overlay" @click.self="showModal = false">
      <div class="modal-box card">
        <h3>{{ editId ? 'تعديل بيانات الموظف' : 'إضافة موظف جديد' }}</h3>
        <div class="form-grid">
          <div class="fg span2">
            <label>الاسم الكامل *</label>
            <input v-model="form.name" placeholder="أحمد الفني" />
          </div>
          <div class="fg">
            <label>رقم الهاتف</label>
            <input v-model="form.phone" placeholder="09xxxxxxxx" />
          </div>
          <div class="fg">
            <label>الدور الوظيفي</label>
            <select v-model="form.role">
              <option value="technician">فني ميداني</option>
              <option value="support">دعم فني</option>
              <option value="admin">مدير</option>
              <option value="accountant">محاسب</option>
            </select>
          </div>
          <div class="fg span2">
            <label>منطقة العمل</label>
            <input v-model="form.area" placeholder="مثلاً: المزة، كفرسوسة" />
          </div>
        </div>
        <div class="modal-actions">
          <button class="btn-pro-outline" @click="showModal = false">إلغاء</button>
          <button class="btn-pro-primary" @click="saveStaff" :disabled="saving">
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

const staff = ref([]);
const showModal = ref(false);
const saving = ref(false);
const editId = ref(null);
const form = ref({ name: '', phone: '', role: 'technician', area: '' });

const roleLabel = (r) => ({ technician: '🔧 فني', support: '💬 دعم', admin: '👑 مدير', accountant: '💰 محاسب' }[r] || r);

const fetchStaff = async () => {
  try {
    const res = await axios.get('/api/staff');
    staff.value = Array.isArray(res.data) ? res.data : [];
  } catch (err) { staff.value = []; }
};

const openModal = (s = null) => {
  editId.value = s?.id || null;
  form.value = s ? { ...s } : { name: '', phone: '', role: 'technician', area: '' };
  showModal.value = true;
};

const saveStaff = async () => {
  if (!form.value.name) return alert('الاسم مطلوب');
  saving.value = true;
  try {
    if (editId.value) {
      await axios.put(`/api/staff/${editId.value}`, form.value);
    } else {
      await axios.post('/api/staff', form.value);
    }
    showModal.value = false;
    await fetchStaff();
  } catch (err) { alert('خطأ: ' + err.message); }
  finally { saving.value = false; }
};

const deleteStaff = async (id) => {
  if (!confirm('حذف هذا الموظف؟')) return;
  await axios.delete(`/api/staff/${id}`);
  await fetchStaff();
};

onMounted(fetchStaff);
</script>

<style scoped>
.staff-page { width: 100%; }
.final-header { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 30px; }
.final-header h1 { font-size: 24px; font-weight: 900; color: #1e293b; margin: 0 0 5px; }
.final-header p { font-size: 14px; color: #64748b; margin: 0; }
.empty-state { text-align: center; padding: 60px; }
.empty-icon { font-size: 50px; margin-bottom: 15px; }
.empty-state h3 { font-size: 18px; font-weight: 800; margin-bottom: 8px; }
.empty-state p { color: #64748b; margin-bottom: 20px; }
.staff-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 22px; }
.staff-card { text-align: center; padding: 30px 20px; border-top: 4px solid #8b5cf6; transition: transform 0.2s; }
.staff-card:hover { transform: translateY(-4px); }
.staff-avatar { width: 64px; height: 64px; background: #f5f3ff; border-radius: 20px; display: flex; align-items: center; justify-content: center; font-size: 26px; font-weight: 900; color: #8b5cf6; margin: 0 auto 14px; }
.staff-card h3 { font-size: 16px; font-weight: 900; color: #1e293b; margin: 0 0 10px; }
.role-badge { font-size: 11px; font-weight: 800; padding: 4px 14px; border-radius: 20px; display: inline-block; margin-bottom: 15px; }
.role-badge.technician { background: #eff6ff; color: #3b82f6; }
.role-badge.support { background: #f0fdf4; color: #10b981; }
.role-badge.admin { background: #fef9c3; color: #ca8a04; }
.role-badge.accountant { background: #fff7ed; color: #f59e0b; }
.staff-info { text-align: right; display: flex; flex-direction: column; gap: 8px; margin-bottom: 15px; }
.si-row { font-size: 13px; color: #64748b; display: flex; align-items: center; gap: 8px; }
.staff-actions { display: flex; justify-content: center; gap: 8px; }
.btn-icon { background: #f8fafc; border: 1px solid #e2e8f0; width: 32px; height: 32px; border-radius: 8px; cursor: pointer; }
.btn-icon.danger:hover { background: #fef2f2; border-color: #fecaca; }
.modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 2000; display: flex; align-items: center; justify-content: center; }
.modal-box { width: 480px; padding: 35px; }
.modal-box h3 { font-size: 18px; font-weight: 900; margin: 0 0 25px; }
.form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 25px; }
.fg { display: flex; flex-direction: column; gap: 6px; }
.fg.span2 { grid-column: span 2; }
.fg label { font-size: 12px; font-weight: 800; color: #64748b; }
.fg input, .fg select { padding: 10px 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px; outline: none; }
.modal-actions { display: flex; justify-content: flex-end; gap: 12px; }
.btn-pro-primary { background: #8b5cf6; color: white; border: none; padding: 12px 28px; border-radius: 10px; font-weight: 800; cursor: pointer; }
.btn-pro-outline { background: white; border: 1px solid #e2e8f0; padding: 12px 22px; border-radius: 10px; font-weight: 700; cursor: pointer; }
</style>
