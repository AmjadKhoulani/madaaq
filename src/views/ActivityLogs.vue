<template>
  <div class="activity-page">
    <div class="final-header">
      <div>
        <h1>سجلات النشاط (Activity Logs)</h1>
        <p>تتبع كافة العمليات، التعديلات، والتحركات التي تتم في النظام من قبل الموظفين</p>
      </div>
      <div class="header-actions">
        <button class="btn-pro-outline">تفريغ السجلات 🗑️</button>
        <button class="btn-pro-primary">تصدير تقرير أمني 🛡️</button>
      </div>
    </div>

    <!-- Activity Log Table -->
    <div class="card no-padding overflow-hidden mt-30">
      <div class="table-filters-pro">
        <div class="search-wrap">
          <span class="icon">🔍</span>
          <input type="text" placeholder="البحث بالموظف، الإجراء، أو التاريخ..." />
        </div>
      </div>
      
      <table class="final-table">
        <thead>
          <tr>
            <th>الوقت والتاريخ</th>
            <th>الموظف</th>
            <th>العملية (Action)</th>
            <th>التفاصيل</th>
            <th>IP العنوان</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="i in 6" :key="i">
            <td>
              <div class="time-cell">
                <strong>2024/05/01</strong>
                <span>12:45:{{ i }}0 م</span>
              </div>
            </td>
            <td>
              <div class="staff-mini">
                <div class="avatar-xs">{{ i % 2 === 0 ? 'A' : 'M' }}</div>
                <span>{{ i % 2 === 0 ? 'أمجــد خولانـي' : 'محمد أحمد' }}</span>
              </div>
            </td>
            <td><span class="action-badge" :class="i % 2 === 0 ? 'update' : 'create'">{{ i % 2 === 0 ? 'تحديث' : 'إضافة' }}</span></td>
            <td>تعديل بيانات المشترك #CUST-992{{ i }}</td>
            <td><code class="ip-text">192.168.1.{{ i }}0</code></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<style scoped>
.activity-page { padding: 0; width: 100%; }
.final-header { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 35px; }
.final-header h1 { font-size: 24px; font-weight: 900; color: #1e293b; }
.final-header p { font-size: 14px; color: #64748b; }

.table-filters-pro { padding: 20px 25px; background: #f8fafc; border-bottom: 1px solid #e2e8f0; }
.search-wrap { position: relative; max-width: 400px; }
.search-wrap .icon { position: absolute; right: 15px; top: 12px; opacity: 0.5; }
.search-wrap input { width: 100%; padding: 10px 45px 10px 15px; border-radius: 8px; border: 1px solid #e2e8f0; outline: none; }

.final-table { width: 100%; border-collapse: collapse; }
.final-table th { text-align: right; padding: 15px 25px; font-size: 12px; color: #64748b; background: #f8fafc; }
.final-table td { padding: 15px 25px; border-bottom: 1px solid #f1f5f9; font-size: 13.5px; }

.time-cell { display: flex; flex-direction: column; }
.time-cell strong { font-size: 13px; color: #1e293b; }
.time-cell span { font-size: 10px; color: #94a3b8; }

.staff-mini { display: flex; align-items: center; gap: 10px; }
.avatar-xs { width: 24px; height: 24px; background: #e2e8f0; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: 10px; font-weight: 800; }

.action-badge { padding: 3px 10px; border-radius: 6px; font-size: 10px; font-weight: 800; }
.action-badge.update { background: #fff7ed; color: #c2410c; }
.action-badge.create { background: #f0fdf4; color: #16a34a; }

.ip-text { font-family: monospace; background: #f1f5f9; padding: 2px 6px; border-radius: 4px; font-size: 11px; color: #475569; }

.mt-30 { margin-top: 30px; }
.no-padding { padding: 0 !important; }
.overflow-hidden { overflow: hidden; }

.btn-pro-primary { background: var(--primary); color: white; border: none; padding: 12px 30px; border-radius: 10px; font-weight: 800; cursor: pointer; }
.btn-pro-outline { background: white; border: 1px solid #e2e8f0; padding: 12px 25px; border-radius: 10px; font-weight: 700; cursor: pointer; }
</style>
