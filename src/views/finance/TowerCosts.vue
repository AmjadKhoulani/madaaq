<template>
  <div class="tower-costs-page">
    <div class="final-header">
      <div>
        <h1>تكاليف المواقع والمصاريف (Expenses)</h1>
        <p>إدارة نفقات الأبراج، الإيجارات، وتكاليف التشغيل الشهرية</p>
      </div>
      <div class="header-actions">
        <button class="btn-pro-primary" @click="showAddExpense = true">إضافة مصروف جديد 💸</button>
      </div>
    </div>

    <!-- Quick Expense Stats -->
    <div class="stats-banner mb-30">
      <div class="stat-card-glass">
        <div class="stat-icon red">📉</div>
        <div class="stat-meta">
          <label>إجمالي مصاريف الشهر</label>
          <h2>$2,150.00</h2>
          <span class="trend danger">↑ 3% زيادة طاقة</span>
        </div>
      </div>
      <div class="stat-card-glass">
        <div class="stat-icon gold">🗼</div>
        <div class="stat-meta">
          <label>متوسط تكلفة البرج</label>
          <h2>$145.00</h2>
          <span class="trend">شامل الإيجار والصيانة</span>
        </div>
      </div>
    </div>

    <!-- Expense Entry Modal -->
    <div v-if="showAddExpense" class="modal-overlay">
      <div class="card modal-content-pro">
        <h3>تسجيل مصروف جديد</h3>
        <div class="pro-grid mt-20">
          <div class="input-group">
            <label>وصف المصروف</label>
            <input type="text" placeholder="مثلاً: إيجار موقع برج الشمال" />
          </div>
          <div class="input-group">
            <label>المبلغ ($)</label>
            <input type="number" placeholder="0.00" />
          </div>
          <div class="input-group">
            <label>الفئة</label>
            <select>
              <option>إيجار موقع</option>
              <option>طاقة / كهرباء</option>
              <option>صيانة طارئة</option>
              <option>تجهيزات جديدة</option>
            </select>
          </div>
          <div class="input-group">
            <label>مرتبط ببرج</label>
            <select>
              <option>برج الشمال الرئيسي</option>
              <option>كابينة شارع 15</option>
              <option>عام (غير مرتبط)</option>
            </select>
          </div>
        </div>
        <div class="modal-actions mt-30">
          <button class="btn-pro-primary" @click="showAddExpense = false">حفظ المصروف ✅</button>
          <button class="btn-pro-outline" @click="showAddExpense = false">إلغاء</button>
        </div>
      </div>
    </div>

    <!-- Expenses Table -->
    <div class="card no-padding overflow-hidden">
      <div class="table-header-pro"><h3>سجل المصاريف والنفقات</h3></div>
      <table class="final-table">
        <thead>
          <tr>
            <th>التاريخ</th>
            <th>المصروف</th>
            <th>الفئة</th>
            <th>البرج / الموقع</th>
            <th>المبلغ</th>
            <th>العمليات</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="i in 5" :key="i">
            <td>2024/05/01</td>
            <td><strong>إيجار موقع - دفعة مايو</strong></td>
            <td><span class="category-badge blue">إيجار</span></td>
            <td>برج الشمال الرئيسي</td>
            <td><strong class="text-danger">$150.00</strong></td>
            <td><button class="btn-icon">✏️</button></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
const showAddExpense = ref(false);
</script>

<style scoped>
.tower-costs-page { width: 100%; }
.final-header { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 35px; }

.modal-overlay { position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 2000; }
.modal-content-pro { width: 100%; max-width: 650px; padding: 40px !important; }
.modal-actions { display: flex; gap: 15px; justify-content: flex-end; }

.pro-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
.input-group { display: flex; flex-direction: column; gap: 8px; }
.input-group label { font-size: 12px; font-weight: 800; color: #64748b; }
.input-group input, .input-group select { padding: 12px; border-radius: 10px; border: 1px solid #e2e8f0; outline: none; }

.stats-banner { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; }
.stat-card-glass { background: white; padding: 20px; border-radius: 18px; display: flex; align-items: center; gap: 15px; border: 1px solid #e2e8f0; }
.stat-icon { width: 45px; height: 45px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px; }
.stat-icon.red { background: #fef2f2; color: #ef4444; }
.stat-icon.gold { background: #fffbeb; color: #f59e0b; }

.stat-meta h2 { font-size: 20px; font-weight: 900; color: #1e293b; margin: 0; }
.trend { font-size: 10px; font-weight: 800; color: #94a3b8; }
.trend.danger { color: #ef4444; }

.category-badge { padding: 4px 12px; border-radius: 6px; font-size: 11px; font-weight: 800; }
.category-badge.blue { background: #eff6ff; color: #3b82f6; }

.text-danger { color: #ef4444; }
.mt-20 { margin-top: 20px; }
.mt-30 { margin-top: 30px; }
.mb-30 { margin-bottom: 30px; }
.no-padding { padding: 0 !important; }
.overflow-hidden { overflow: hidden; }

.table-header-pro { padding: 20px 25px; background: #f8fafc; border-bottom: 1px solid #e2e8f0; }
.final-table { width: 100%; border-collapse: collapse; }
.final-table th { text-align: right; padding: 15px 25px; font-size: 12px; color: #64748b; background: #f8fafc; }
.final-table td { padding: 15px 25px; border-bottom: 1px solid #f1f5f9; font-size: 14px; }

.btn-icon { background: white; border: 1px solid #e2e8f0; width: 34px; height: 34px; border-radius: 8px; cursor: pointer; }
.btn-pro-primary { background: var(--primary); color: white; border: none; padding: 12px 30px; border-radius: 10px; font-weight: 800; cursor: pointer; }
.btn-pro-outline { background: white; border: 1px solid #e2e8f0; padding: 12px 25px; border-radius: 10px; font-weight: 700; cursor: pointer; }
</style>
