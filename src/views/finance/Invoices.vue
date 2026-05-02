<template>
  <div class="invoices-page">
    <div class="final-header">
      <div>
        <h1>الفواتير والتحصيل 🧾</h1>
        <p>تتبع دفعات المشتركين، الديون المستحقة، وتسجيل المدفوعات</p>
      </div>
      <button class="btn-pro-primary" @click="showAdd = true">إضافة فاتورة ➕</button>
    </div>

    <!-- Summary Stats -->
    <div class="inv-stats">
      <div class="stat-box card" style="border-top: 4px solid #10b981">
        <div class="s-val text-green">{{ paid.length }}</div>
        <div class="s-label">مدفوعة ✅</div>
      </div>
      <div class="stat-box card" style="border-top: 4px solid #f59e0b">
        <div class="s-val text-amber">{{ pending.length }}</div>
        <div class="s-label">معلّقة ⏳</div>
      </div>
      <div class="stat-box card" style="border-top: 4px solid #ef4444">
        <div class="s-val text-red">{{ overdue.length }}</div>
        <div class="s-label">متأخرة 🚨</div>
      </div>
      <div class="stat-box card" style="border-top: 4px solid #3b82f6">
        <div class="s-val">${{ totalRevenue }}</div>
        <div class="s-label">إجمالي المحصّل</div>
      </div>
    </div>

    <!-- Invoices Table -->
    <div class="card no-padding overflow-hidden">
      <div class="table-toolbar">
        <input v-model="search" class="search-inp" placeholder="🔍 بحث باسم المشترك أو الباقة..." />
        <select v-model="filterStatus" class="filter-sel">
          <option value="">كافة الفواتير</option>
          <option value="paid">مدفوعة</option>
          <option value="pending">معلّقة</option>
          <option value="overdue">متأخرة</option>
        </select>
      </div>
      <table class="inv-table">
        <thead>
          <tr>
            <th>#</th>
            <th>المشترك</th>
            <th>الباقة</th>
            <th>المبلغ</th>
            <th>تاريخ الاستحقاق</th>
            <th>الحالة</th>
            <th>إجراءات</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="inv in filteredInvoices" :key="inv.id">
            <td class="text-muted">#{{ inv.id }}</td>
            <td><strong>{{ inv.client_name }}</strong></td>
            <td>{{ inv.package_name }}</td>
            <td><strong>${{ inv.amount }}</strong></td>
            <td>{{ inv.due_date ? new Date(inv.due_date).toLocaleDateString('ar') : '—' }}</td>
            <td>
              <span :class="['status-pill', inv.status]">
                {{ inv.status === 'paid' ? '✅ مدفوعة' : inv.status === 'overdue' ? '🚨 متأخرة' : '⏳ معلّقة' }}
              </span>
            </td>
            <td>
              <button v-if="inv.status !== 'paid'" class="btn-pay" @click="markPaid(inv.id)">تسجيل الدفع 💰</button>
              <span v-else class="text-muted text-sm">تم الدفع</span>
            </td>
          </tr>
          <tr v-if="filteredInvoices.length === 0">
            <td colspan="7" class="empty-row">لا توجد فواتير مطابقة</td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Add Invoice Modal -->
    <div v-if="showAdd" class="modal-overlay" @click.self="showAdd = false">
      <div class="modal-box card">
        <h3>إضافة فاتورة جديدة</h3>
        <div class="form-grid">
          <div class="fg span2">
            <label>اسم المشترك *</label>
            <input v-model="form.client_name" placeholder="اسم المشترك" />
          </div>
          <div class="fg">
            <label>الباقة</label>
            <input v-model="form.package_name" placeholder="اسم الباقة" />
          </div>
          <div class="fg">
            <label>المبلغ ($)</label>
            <input v-model="form.amount" type="number" placeholder="10" />
          </div>
          <div class="fg">
            <label>تاريخ الاستحقاق</label>
            <input v-model="form.due_date" type="date" />
          </div>
          <div class="fg">
            <label>الحالة</label>
            <select v-model="form.status">
              <option value="pending">معلّقة</option>
              <option value="paid">مدفوعة</option>
              <option value="overdue">متأخرة</option>
            </select>
          </div>
        </div>
        <div class="modal-actions">
          <button class="btn-pro-outline" @click="showAdd = false">إلغاء</button>
          <button class="btn-pro-primary" @click="addInvoice" :disabled="saving">
            {{ saving ? 'جاري الحفظ...' : '💾 إضافة الفاتورة' }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';

const invoices = ref([]);
const search = ref('');
const filterStatus = ref('');
const showAdd = ref(false);
const saving = ref(false);
const form = ref({ client_name: '', package_name: '', amount: '', due_date: '', status: 'pending', notes: '' });

const fetchInvoices = async () => {
  try {
    const res = await axios.get('/api/invoices');
    invoices.value = Array.isArray(res.data) ? res.data : [];
  } catch (err) { console.error(err); }
};

const paid = computed(() => invoices.value.filter(i => i.status === 'paid'));
const pending = computed(() => invoices.value.filter(i => i.status === 'pending'));
const overdue = computed(() => invoices.value.filter(i => i.status === 'overdue'));
const totalRevenue = computed(() => paid.value.reduce((sum, i) => sum + Number(i.amount || 0), 0).toFixed(2));

const filteredInvoices = computed(() => {
  return invoices.value.filter(i => {
    const matchSearch = !search.value || (i.client_name || '').includes(search.value) || (i.package_name || '').includes(search.value);
    const matchStatus = !filterStatus.value || i.status === filterStatus.value;
    return matchSearch && matchStatus;
  });
});

const markPaid = async (id) => {
  if (!confirm('تسجيل هذه الفاتورة كمدفوعة؟')) return;
  await axios.put(`/api/invoices/${id}/pay`);
  await fetchInvoices();
};

const addInvoice = async () => {
  if (!form.value.client_name) return alert('اسم المشترك مطلوب');
  saving.value = true;
  try {
    await axios.post('/api/invoices', form.value);
    showAdd.value = false;
    form.value = { client_name: '', package_name: '', amount: '', due_date: '', status: 'pending', notes: '' };
    await fetchInvoices();
  } catch (err) { alert('خطأ: ' + err.message); }
  finally { saving.value = false; }
};

onMounted(fetchInvoices);
</script>

<style scoped>
.invoices-page { width: 100%; }
.final-header { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 25px; }
.final-header h1 { font-size: 24px; font-weight: 900; color: #1e293b; margin: 0 0 5px; }
.final-header p { font-size: 14px; color: #64748b; margin: 0; }

.inv-stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 18px; margin-bottom: 25px; }
.stat-box { padding: 20px; text-align: center; }
.s-val { font-size: 30px; font-weight: 900; color: #1e293b; }
.s-label { font-size: 12px; font-weight: 700; color: #64748b; margin-top: 5px; }
.text-green { color: #10b981; }
.text-amber { color: #f59e0b; }
.text-red { color: #ef4444; }

.table-toolbar { padding: 15px 20px; display: flex; gap: 12px; background: #f8fafc; border-bottom: 1px solid #e2e8f0; }
.search-inp { flex: 1; padding: 9px 14px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px; outline: none; }
.filter-sel { padding: 9px 14px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 13px; outline: none; }

.inv-table { width: 100%; border-collapse: collapse; }
.inv-table th { text-align: right; padding: 13px 18px; background: #f8fafc; color: #64748b; font-size: 12px; font-weight: 800; }
.inv-table td { padding: 13px 18px; border-bottom: 1px solid #f1f5f9; font-size: 14px; }
.text-muted { color: #94a3b8; }
.text-sm { font-size: 12px; }

.status-pill { font-size: 11px; font-weight: 800; padding: 4px 12px; border-radius: 20px; }
.status-pill.paid { background: #f0fdf4; color: #16a34a; }
.status-pill.pending { background: #fffbeb; color: #d97706; }
.status-pill.overdue { background: #fef2f2; color: #dc2626; }

.btn-pay { background: #10b981; color: white; border: none; padding: 6px 14px; border-radius: 8px; font-size: 12px; font-weight: 700; cursor: pointer; }
.empty-row { text-align: center; padding: 40px; color: #94a3b8; font-weight: 700; }

.modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 2000; display: flex; align-items: center; justify-content: center; }
.modal-box { width: 520px; padding: 35px; }
.modal-box h3 { font-size: 18px; font-weight: 900; margin: 0 0 25px; }
.form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 25px; }
.fg { display: flex; flex-direction: column; gap: 6px; }
.fg.span2 { grid-column: span 2; }
.fg label { font-size: 12px; font-weight: 800; color: #64748b; }
.fg input, .fg select { padding: 10px 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px; outline: none; }
.modal-actions { display: flex; justify-content: flex-end; gap: 12px; }
.btn-pro-primary { background: #3b82f6; color: white; border: none; padding: 12px 28px; border-radius: 10px; font-weight: 800; cursor: pointer; }
.btn-pro-primary:disabled { opacity: 0.6; }
.btn-pro-outline { background: white; border: 1px solid #e2e8f0; padding: 12px 22px; border-radius: 10px; font-weight: 700; cursor: pointer; }
.no-padding { padding: 0 !important; }
.overflow-hidden { overflow: hidden; }
</style>
