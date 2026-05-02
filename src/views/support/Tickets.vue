<template>
  <div class="tickets-page">
    <div class="final-header">
      <div>
        <h1>مركز الدعم الفني والتذاكر</h1>
        <p>إدارة أعطال المشتركين، المهام الميدانية، ومتابعة أداء الفنيين</p>
      </div>
      <div class="header-actions">
        <button class="btn-pro-primary" @click="showNewTicket = true">+ فتح تذكرة عطل جديدة</button>
      </div>
    </div>

    <div class="tickets-stats-grid mb-30">
      <div class="t-stat-card card">
        <span class="icon">🚨</span>
        <div class="meta">
          <label>تذاكر مفتوحة</label>
          <h2>14</h2>
        </div>
      </div>
      <div class="t-stat-card card">
        <span class="icon">⏳</span>
        <div class="meta">
          <label>قيد المعالجة</label>
          <h2>8</h2>
        </div>
      </div>
      <div class="t-stat-card card">
        <span class="icon">✅</span>
        <div class="meta">
          <label>تم الحل اليوم</label>
          <h2>32</h2>
        </div>
      </div>
    </div>

    <div class="card no-padding overflow-hidden">
      <div class="filter-bar">
        <input type="text" placeholder="بحث برقم التذكرة أو اسم المشترك..." class="search-input" />
        <select class="filter-select">
          <option>كافة الحالات</option>
          <option>مفتوحة</option>
          <option>قيد المعالجة</option>
          <option>تم الحل</option>
        </select>
      </div>
      
      <table class="pro-table">
        <thead>
          <tr>
            <th>رقم التذكرة</th>
            <th>المشترك</th>
            <th>نوع العطل</th>
            <th>الفني المسؤول</th>
            <th>الأولوية</th>
            <th>الحالة</th>
            <th>الإجراءات</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="ticket in tickets" :key="ticket.id">
            <td><span class="ticket-id">#{{ ticket.id }}</span></td>
            <td>
              <div class="client-info">
                <strong>{{ ticket.clientName }}</strong>
                <small>{{ ticket.phone }}</small>
              </div>
            </td>
            <td>{{ ticket.type }}</td>
            <td>
              <div class="staff-tag">
                <span class="avatar">👨‍🔧</span>
                {{ ticket.staff }}
              </div>
            </td>
            <td>
              <span :class="['priority-pill', ticket.priority]">{{ ticket.priorityName }}</span>
            </td>
            <td>
              <span :class="['status-pill', ticket.status]">{{ ticket.statusName }}</span>
            </td>
            <td>
              <button class="btn-icon">👁️</button>
              <button class="btn-icon success">✅</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';

const showNewTicket = ref(false);

const tickets = ref([
  { id: 4502, clientName: 'أحمد المحمد', phone: '0933123456', type: 'انقطاع خدمة', staff: 'م. سامر', priority: 'high', priorityName: 'عالية', status: 'open', statusName: 'مفتوحة' },
  { id: 4501, clientName: 'شركة النور', phone: '011223344', type: 'ضعف إشارة', staff: 'م. خالد', priority: 'medium', priorityName: 'متوسطة', status: 'processing', statusName: 'قيد المعالجة' },
  { id: 4500, clientName: 'ياسين علي', phone: '0944888777', type: 'تركيب جديد', staff: 'م. عبيدة', priority: 'low', priorityName: 'منخفضة', status: 'done', statusName: 'تم الحل' }
]);
</script>

<style scoped>
.tickets-page { width: 100%; }
.final-header { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 35px; }

.tickets-stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
.t-stat-card { display: flex; align-items: center; gap: 20px; padding: 25px !important; }
.t-stat-card .icon { font-size: 30px; width: 60px; height: 60px; background: #f8fafc; border-radius: 15px; display: flex; align-items: center; justify-content: center; }
.t-stat-card label { font-size: 12px; font-weight: 800; color: #64748b; display: block; margin-bottom: 5px; }
.t-stat-card h2 { font-size: 24px; font-weight: 900; color: #1e293b; margin: 0; }

.filter-bar { padding: 20px; background: #f8fafc; display: flex; gap: 15px; border-bottom: 1px solid #e2e8f0; }
.search-input { flex: 1; padding: 10px 15px; border-radius: 10px; border: 1px solid #e2e8f0; outline: none; }
.filter-select { padding: 10px; border-radius: 10px; border: 1px solid #e2e8f0; outline: none; background: white; width: 150px; }

.pro-table { width: 100%; border-collapse: collapse; }
.pro-table th { text-align: right; padding: 15px 20px; background: #f8fafc; color: #64748b; font-size: 12px; font-weight: 800; text-transform: uppercase; border-bottom: 2px solid #e2e8f0; }
.pro-table td { padding: 15px 20px; border-bottom: 1px solid #f1f5f9; font-size: 14px; }

.ticket-id { font-family: monospace; font-weight: 900; color: var(--primary); }
.client-info strong { display: block; color: #1e293b; }
.client-info small { color: #94a3b8; font-size: 11px; font-weight: 700; }

.staff-tag { display: flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 700; color: #475569; }
.avatar { width: 24px; height: 24px; border-radius: 50%; background: #f1f5f9; display: flex; align-items: center; justify-content: center; font-size: 12px; }

.priority-pill { padding: 4px 10px; border-radius: 50px; font-size: 11px; font-weight: 800; }
.priority-pill.high { background: #fef2f2; color: #ef4444; }
.priority-pill.medium { background: #fffbeb; color: #f59e0b; }
.priority-pill.low { background: #f0fdf4; color: #16a34a; }

.status-pill { padding: 4px 12px; border-radius: 8px; font-size: 11px; font-weight: 800; }
.status-pill.open { background: #eff6ff; color: #3b82f6; }
.status-pill.processing { background: #fef3c7; color: #d97706; }
.status-pill.done { background: #dcfce7; color: #166534; }

.btn-icon { width: 32px; height: 32px; border-radius: 8px; border: 1px solid #e2e8f0; background: white; cursor: pointer; margin-left: 5px; transition: 0.2s; }
.btn-icon:hover { background: #f1f5f9; }
.btn-icon.success:hover { background: #dcfce7; border-color: #166534; }

.mb-30 { margin-bottom: 30px; }
.no-padding { padding: 0 !important; }
.overflow-hidden { overflow: hidden; }

.btn-pro-primary { background: var(--primary); color: white; border: none; padding: 12px 25px; border-radius: 10px; font-weight: 800; cursor: pointer; }
</style>
