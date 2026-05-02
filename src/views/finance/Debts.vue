<template>
  <div>
    <div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
      <div>
        <h1>إدارة الديون (Debts Management)</h1>
        <p style="color: var(--text-muted);">متابعة العملاء المتأخرين عن السداد والمبالغ المستحقة</p>
      </div>
      <div class="debt-total card">
        <span class="label">إجمالي الديون المستحقة</span>
        <span class="value">$1,450.00</span>
      </div>
    </div>

    <div class="card">
      <table class="data-table">
        <thead>
          <tr>
            <th>اسم العميل</th>
            <th>رقم الهاتف</th>
            <th>المبلغ المستحق</th>
            <th>تاريخ الاستحقاق</th>
            <th>فترة التأخير</th>
            <th>الإجراءات</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><strong>خالد عبد الله</strong></td>
            <td>0501234567</td>
            <td class="text-danger">$45.00</td>
            <td>2026-04-15</td>
            <td><span class="delay-tag">15 يوم</span></td>
            <td>
              <button class="btn btn-outline btn-sm">مطالبة واتساب 💬</button>
              <button class="btn btn-primary btn-sm" style="margin-right: 5px;">تحصيل</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<style scoped>
.debt-total { display: flex; flex-direction: column; align-items: center; padding: 10px 30px; background: #fff1f2; border-color: #fecdd3; }
.debt-total .label { font-size: 12px; color: #9f1239; }
.debt-total .value { font-size: 24px; font-weight: 800; color: #e11d48; }

.data-table { width: 100%; border-collapse: collapse; }
.data-table th, .data-table td { padding: 15px; text-align: right; border-bottom: 1px solid var(--border-color); }
.data-table th { background: #f8fafc; font-size: 13px; color: var(--text-muted); }

.text-danger { color: #e11d48; font-weight: bold; }
.delay-tag { background: #fee2e2; color: #991b1b; padding: 2px 8px; border-radius: 20px; font-size: 12px; font-weight: bold; }

.btn-sm { padding: 4px 10px; font-size: 12px; }
</style>
