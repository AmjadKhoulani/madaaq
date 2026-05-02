<template>
  <div class="super-admin-tenants">
    <div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
      <div>
        <h1>إدارة المستأجرين (Vendors)</h1>
        <p style="color: var(--text-muted);">مراقبة وإدارة شركات تزويد الخدمة المشتركة في منصة MadaaQ</p>
      </div>
      <button class="btn btn-primary">+ إضافة مستأجر جديد</button>
    </div>

    <div class="tenants-grid">
      <!-- Tenant Card Example -->
      <div class="card tenant-card">
        <div class="tenant-info">
          <div class="logo-circle">N</div>
          <div>
            <h3>Net Nexus Hosting</h3>
            <span class="sub-text">بواسطة: أمجد خولاني</span>
          </div>
          <span class="status-badge active">نشط</span>
        </div>
        
        <div class="tenant-stats">
          <div class="t-stat">
            <span class="label">المشتركين</span>
            <span class="val">1,240</span>
          </div>
          <div class="t-stat">
            <span class="label">السيرفرات</span>
            <span class="val">12</span>
          </div>
          <div class="t-stat">
            <span class="label">الباقة</span>
            <span class="val text-purple">الذهبية</span>
          </div>
        </div>

        <div class="card-footer">
          <button class="btn btn-outline btn-sm">إدارة الحساب</button>
          <button class="btn btn-outline btn-sm">التقارير</button>
          <button class="btn btn-danger btn-sm">تعطيل</button>
        </div>
      </div>

      <!-- More Tenants -->
      <div class="card tenant-card">
        <div class="tenant-info">
          <div class="logo-circle bg-orange">A</div>
          <div>
            <h3>Al-Amal ISP</h3>
            <span class="sub-text">بواسطة: أحمد علي</span>
          </div>
          <span class="status-badge active">نشط</span>
        </div>
        
        <div class="tenant-stats">
          <div class="t-stat">
            <span class="label">المشتركين</span>
            <span class="val">450</span>
          </div>
          <div class="t-stat">
            <span class="label">السيرفرات</span>
            <span class="val">3</span>
          </div>
          <div class="t-stat">
            <span class="label">الباقة</span>
            <span class="val text-blue">الفضية</span>
          </div>
        </div>

        <div class="card-footer">
          <button class="btn btn-outline btn-sm">إدارة الحساب</button>
          <button class="btn btn-outline btn-sm">التقارير</button>
          <button class="btn btn-danger btn-sm">تعطيل</button>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.tenants-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(380px, 1fr)); gap: 25px; }
.tenant-card { border-top: 4px solid #7c3aed; padding-top: 20px; }

.tenant-info { display: flex; align-items: center; gap: 15px; margin-bottom: 25px; position: relative; }
.logo-circle { width: 50px; height: 50px; border-radius: 50%; background: #f3e8ff; color: #7c3aed; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 20px; }
.logo-circle.bg-orange { background: #fff7ed; color: #f97316; }

.tenant-info h3 { font-size: 18px; margin: 0; }
.sub-text { font-size: 12px; color: #94a3b8; }

.status-badge { position: absolute; left: 0; top: 0; font-size: 10px; padding: 3px 8px; border-radius: 20px; font-weight: 800; }
.status-badge.active { background: #dcfce7; color: #166534; }

.tenant-stats { display: flex; justify-content: space-between; background: #f8fafc; padding: 15px; border-radius: 12px; margin-bottom: 20px; }
.t-stat { text-align: center; }
.t-stat .label { display: block; font-size: 11px; color: #64748b; margin-bottom: 4px; font-weight: 700; }
.t-stat .val { font-size: 16px; font-weight: 800; color: #0f172a; }

.text-purple { color: #7c3aed !important; }
.text-blue { color: #2563eb !important; }

.card-footer { display: flex; gap: 10px; border-top: 1px solid #f1f5f9; padding-top: 15px; }
.btn-sm { padding: 6px 12px; font-size: 12px; }
.btn-danger { background: #fee2e2; color: #991b1b; }
.btn-danger:hover { background: #ef4444; color: white; }
</style>
