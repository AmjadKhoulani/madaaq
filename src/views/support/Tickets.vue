<template>
  <div>
    <div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
      <div>
        <h1>تذاكر الدعم الفني (Support Tickets)</h1>
        <p style="color: var(--text-muted);">إدارة وحل مشاكل المشتركين الفنية والمالية</p>
      </div>
      <div class="ticket-stats">
        <div class="stat-mini"><span class="dot open"></span> 5 مفتوحة</div>
        <div class="stat-mini"><span class="dot pending"></span> 3 معلقة</div>
      </div>
    </div>

    <!-- Ticket Filters -->
    <div class="card filters-card">
      <div class="filter-group">
        <input type="text" placeholder="البحث برقم التذكرة أو اسم العميل..." class="form-input search-input" />
        <select class="form-input">
          <option value="">جميع الحالات</option>
          <option value="open">مفتوحة</option>
          <option value="closed">مغلقة</option>
        </select>
        <button class="btn btn-primary">بحث</button>
      </div>
    </div>

    <div class="card">
      <table class="data-table">
        <thead>
          <tr>
            <th>رقم التذكرة</th>
            <th>المشترك</th>
            <th>الموضوع</th>
            <th>الأولوية</th>
            <th>الحالة</th>
            <th>آخر تحديث</th>
            <th>الإجراءات</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><strong>#TK-502</strong></td>
            <td>أحمد علي</td>
            <td>انقطاع متكرر في الخدمة</td>
            <td><span class="prio high">عالية</span></td>
            <td><span class="status open">مفتوحة</span></td>
            <td>منذ 10 دقائق</td>
            <td>
              <button class="btn btn-outline btn-sm">رد</button>
              <button class="btn btn-outline btn-sm">إغلاق</button>
            </td>
          </tr>
          <tr>
            <td><strong>#TK-498</strong></td>
            <td>سارة محمود</td>
            <td>استفسار عن الفاتورة</td>
            <td><span class="prio low">عادية</span></td>
            <td><span class="status closed">مغلقة</span></td>
            <td>منذ يومين</td>
            <td>
              <button class="btn btn-outline btn-sm">عرض</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<style scoped>
.ticket-stats { display: flex; gap: 15px; }
.stat-mini { background: #f8fafc; padding: 5px 12px; border-radius: 20px; font-size: 13px; font-weight: 600; display: flex; align-items: center; gap: 8px; border: 1px solid #e2e8f0; }
.dot { width: 8px; height: 8px; border-radius: 50%; }
.dot.open { background: #ef4444; }
.dot.pending { background: #f59e0b; }

.filters-card { padding: 15px; margin-bottom: 20px; }
.filter-group { display: flex; gap: 15px; }
.search-input { flex: 1; }

.data-table { width: 100%; border-collapse: collapse; }
.data-table th, .data-table td { padding: 15px; text-align: right; border-bottom: 1px solid var(--border-color); }
.data-table th { background: #f8fafc; color: var(--text-muted); font-size: 13px; }

/* Badges */
.prio { font-size: 11px; padding: 3px 8px; border-radius: 4px; font-weight: bold; }
.prio.high { background: #fee2e2; color: #991b1b; }
.prio.low { background: #f1f5f9; color: #475569; }

.status { font-size: 11px; padding: 3px 8px; border-radius: 4px; font-weight: bold; }
.status.open { background: #dcfce7; color: #166534; border: 1px solid #86efac; }
.status.closed { background: #f1f5f9; color: #64748b; }

.btn-sm { padding: 4px 8px; font-size: 12px; }
</style>
