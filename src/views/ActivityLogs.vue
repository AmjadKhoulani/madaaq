<script setup>
import { ref } from 'vue'

const logs = ref([
  { id: 101, user: 'Admin', action: 'Updated router credentials', target: 'Router "Core-SW"', ip: '192.168.1.100', date: '2026-04-30 14:22' },
  { id: 102, user: 'System', action: 'Auto-suspended client', target: 'Client C-1004', ip: 'Server', date: '2026-04-30 12:00' },
  { id: 103, user: 'Support Team', action: 'Reset password', target: 'Client C-1001', ip: '10.0.0.55', date: '2026-04-29 18:45' },
  { id: 104, user: 'Ahmad Tech', action: 'Rebooted AP', target: 'Tower North-B', ip: '10.0.0.21', date: '2026-04-29 10:15' },
])
</script>

<template>
  <div class="activity-logs">
    <div class="header">
      <h1 class="heading-lg">Activity Logs</h1>
      <p class="text-muted">Audit trail of all administrative and system actions.</p>
    </div>

    <!-- Filters Area -->
    <div class="glass-card filters-section">
      <div class="form-group" style="margin: 0; flex: 1;">
        <input type="text" class="form-control" placeholder="Search logs..." />
      </div>
      <input type="date" class="form-control" style="width: auto;" />
      <button class="btn btn-secondary">Export to CSV</button>
    </div>

    <!-- Log Table -->
    <div class="glass-card table-wrapper">
      <table class="data-table">
        <thead>
          <tr>
            <th>Date & Time</th>
            <th>User / Agent</th>
            <th>Action</th>
            <th>Target Resource</th>
            <th>IP Address</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="log in logs" :key="log.id">
            <td class="text-muted" style="white-space: nowrap;">{{ log.date }}</td>
            <td style="font-weight: 500">
              <span class="user-badge" :class="log.user === 'System' ? 'system' : 'human'">
                {{ log.user }}
              </span>
            </td>
            <td>{{ log.action }}</td>
            <td class="text-muted">{{ log.target }}</td>
            <td style="font-family: monospace;">{{ log.ip }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<style scoped>
.activity-logs {
  display: flex;
  flex-direction: column;
  gap: 2rem;
}

.filters-section {
  display: flex;
  gap: 1rem;
  padding: 1rem 1.5rem;
  align-items: center;
}

.table-wrapper {
  padding: 0;
  overflow-x: auto;
}

.data-table {
  width: 100%;
  border-collapse: collapse;
  text-align: left;
}

.data-table th {
  padding: 1rem 1.5rem;
  font-family: var(--font-display);
  font-weight: 600;
  color: var(--text-muted);
  border-bottom: 1px solid var(--border-light);
  background: rgba(15, 23, 42, 0.4);
}

.data-table td {
  padding: 1rem 1.5rem;
  border-bottom: 1px solid rgba(255, 255, 255, 0.05);
}

.data-table tbody tr:hover {
  background: rgba(255, 255, 255, 0.02);
}

.user-badge {
  padding: 4px 8px;
  border-radius: 6px;
  font-size: 0.75rem;
  font-weight: 600;
}

.user-badge.system {
  background: rgba(176, 38, 255, 0.15);
  color: #b026ff;
}

.user-badge.human {
  background: rgba(0, 210, 255, 0.15);
  color: #00d2ff;
}
</style>
