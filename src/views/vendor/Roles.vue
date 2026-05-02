<template>
  <div>
    <div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
      <div>
        <h1>الأدوار والصلاحيات (Roles & Permissions)</h1>
        <p style="color: var(--text-muted);">إدارة أدوار الموظفين وتحديد صلاحيات الوصول لكل قسم</p>
      </div>
      <button class="btn btn-primary" @click="showModal = true">+ إضافة دور جديد</button>
    </div>

    <div class="roles-grid">
      <!-- Fixed Roles -->
      <div class="card role-card fixed">
        <div class="role-header">
          <h3>محاسب (Accountant)</h3>
          <span class="badge badge-fixed">دور ثابت</span>
        </div>
        <p>الوصول للتقارير المالية، الفواتير، وتحصيل الاشتراكات.</p>
        <div class="permissions-tags">
          <span class="tag">المالية</span>
          <span class="tag">الفواتير</span>
          <span class="tag">العملاء (عرض)</span>
        </div>
      </div>

      <div class="card role-card fixed">
        <div class="role-header">
          <h3>تقني (Technician)</h3>
          <span class="badge badge-fixed">دور ثابت</span>
        </div>
        <p>الوصول لإدارة السيرفرات، الأبراج، وحل مشاكل المشتركين.</p>
        <div class="permissions-tags">
          <span class="tag">الشبكة</span>
          <span class="tag">الأبراج</span>
          <span class="tag">السيرفرات</span>
        </div>
      </div>

      <!-- Custom Roles Placeholder -->
      <div class="card role-card custom">
        <div class="role-header">
          <h3>موظف مبيعات</h3>
          <span class="badge badge-custom">دور مخصص</span>
        </div>
        <p>إضافة مشتركين جديد ومتابعة الطلبات.</p>
        <div class="permissions-tags">
          <span class="tag">العملاء (إضافة)</span>
        </div>
        <div class="role-actions">
          <button class="btn btn-outline btn-sm">تعديل</button>
          <button class="btn btn-danger btn-sm">حذف</button>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.roles-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; }
.role-card { border-top: 4px solid #3b82f6; }
.role-card.fixed { border-top-color: #94a3b8; }
.role-card.custom { border-top-color: #3b82f6; }
.role-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; }
.role-header h3 { margin: 0; font-size: 18px; }
.badge { font-size: 11px; padding: 2px 8px; border-radius: 4px; }
.badge-fixed { background: #f1f5f9; color: #64748b; }
.badge-custom { background: #eff6ff; color: #3b82f6; }
.permissions-tags { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 15px; }
.tag { font-size: 12px; background: #f8fafc; padding: 4px 8px; border-radius: 4px; border: 1px solid #e2e8f0; }
.role-actions { margin-top: 20px; display: flex; gap: 10px; border-top: 1px solid #f1f5f9; padding-top: 15px; }
.btn-sm { padding: 4px 8px; font-size: 12px; }
</style>
