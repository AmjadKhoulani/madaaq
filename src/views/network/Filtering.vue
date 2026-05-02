<template>
  <div>
    <div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
      <div>
        <h1>حظر المحتوى والمواقع (Content Filtering)</h1>
        <p style="color: var(--text-muted);">إدارة المواقع المحظورة على مستوى سيرفرات المايكروتك</p>
      </div>
      <button class="btn btn-danger">+ حظر موقع جديد</button>
    </div>

    <div class="card" style="margin-bottom: 25px;">
      <div class="filter-row" style="display: flex; gap: 15px;">
        <input type="text" class="form-input" style="flex: 1;" placeholder="رابط الموقع (مثال: example.com)" />
        <select class="form-input" style="width: 250px;">
          <option value="all">تطبيق على جميع السيرفرات</option>
          <option value="1">سيرفر المايكروتك الرئيسي</option>
        </select>
        <button class="btn btn-primary">إضافة للحظر</button>
      </div>
    </div>

    <div class="card no-padding">
      <table class="data-table">
        <thead>
          <tr>
            <th>الموقع المحظور</th>
            <th>السيرفر</th>
            <th>تاريخ الحظر</th>
            <th>الحالة</th>
            <th>الإجراءات</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><code>*.malicious-site.net</code></td>
            <td>الكل</td>
            <td>2026-04-10</td>
            <td><span class="badge badge-danger">محظور حالياً</span></td>
            <td><button class="btn btn-outline btn-sm">فك الحظر</button></td>
          </tr>
          <tr>
            <td><code>illegal-content.com</code></td>
            <td>سيرفر 1</td>
            <td>2026-03-25</td>
            <td><span class="badge badge-danger">محظور حالياً</span></td>
            <td><button class="btn btn-outline btn-sm">فك الحظر</button></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<style scoped>
.no-padding { padding: 0; overflow: hidden; }
.badge { padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 700; }
.badge-danger { background: #fee2e2; color: #991b1b; }
.btn-sm { padding: 6px 12px; font-size: 12px; }
code { background: #f1f5f9; padding: 2px 6px; border-radius: 4px; color: #e11d48; font-weight: 600; }
</style>
