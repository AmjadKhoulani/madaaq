<script setup>
import { ref } from 'vue'

const clients = ref([
  { id: 'C-1001', name: 'Ahmad Al-Fahad', type: 'Broadband', status: 'Active', router: 'Tower North-B', balance: '$0.00' },
  { id: 'C-1002', name: 'Global Tech Corp', type: 'Enterprise', status: 'Active', router: 'Main Datacenter', balance: '$450.00' },
  { id: 'C-1003', name: 'Sara Khalid', type: 'Hotspot', status: 'Expired', router: 'Cafe Zone', balance: '$0.00' },
  { id: 'C-1004', name: 'Ali Mahmoud', type: 'Broadband', status: 'Suspended', router: 'Tower South-A', balance: '$50.00' },
])
</script>

<template>
  <div class="crm">
    <div class="header">
      <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
          <h1 class="heading-lg">CRM Clients</h1>
          <p class="text-muted">Manage your ISP customers, enterprise clients, and hotspot users.</p>
        </div>
        <button class="btn btn-primary">+ Add New Client</button>
      </div>
    </div>

    <!-- Filters Area -->
    <div class="glass-card filters-section">
      <div class="form-group" style="margin: 0;">
        <input type="text" class="form-control" placeholder="Search by name, ID, or phone..." />
      </div>
      <select class="form-control" style="width: auto;">
        <option>All Types</option>
        <option>Broadband</option>
        <option>Enterprise</option>
        <option>Hotspot</option>
      </select>
      <select class="form-control" style="width: auto;">
        <option>All Status</option>
        <option>Active</option>
        <option>Expired</option>
        <option>Suspended</option>
      </select>
    </div>

    <!-- Data Table -->
    <div class="glass-card table-wrapper">
      <table class="data-table">
        <thead>
          <tr>
            <th>Client ID</th>
            <th>Name</th>
            <th>Type</th>
            <th>Status</th>
            <th>Router / Zone</th>
            <th>Balance</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="client in clients" :key="client.id">
            <td class="text-muted">{{ client.id }}</td>
            <td style="font-weight: 500">{{ client.name }}</td>
            <td><span class="badge type-badge">{{ client.type }}</span></td>
            <td>
              <span class="badge" :class="client.status.toLowerCase()">{{ client.status }}</span>
            </td>
            <td class="text-muted">{{ client.router }}</td>
            <td :class="{'text-danger': client.balance !== '$0.00'}">{{ client.balance }}</td>
            <td>
              <button class="btn-icon" title="Edit">✏️</button>
              <button class="btn-icon" title="View Profile">👁️</button>
              <button class="btn-icon" title="WhatsApp Message">💬</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<style scoped>
.crm {
  display: flex;
  flex-direction: column;
  gap: 2rem;
}

.filters-section {
  display: flex;
  gap: 1rem;
  padding: 1rem 1.5rem;
}

.table-wrapper {
  padding: 0;
  overflow: hidden;
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

.badge {
  padding: 4px 8px;
  border-radius: 6px;
  font-size: 0.75rem;
  font-weight: 600;
  font-family: var(--font-sans);
}

.badge.active { background: rgba(16, 185, 129, 0.15); color: #10b981; }
.badge.expired { background: rgba(245, 158, 11, 0.15); color: #f59e0b; }
.badge.suspended { background: rgba(239, 68, 68, 0.15); color: #ef4444; }

.type-badge {
  background: rgba(0, 210, 255, 0.1);
  color: var(--color-primary);
  border: 1px solid rgba(0, 210, 255, 0.2);
}

.text-danger {
  color: #ef4444;
}

.btn-icon {
  background: transparent;
  border: none;
  font-size: 1.1rem;
  cursor: pointer;
  margin-right: 0.5rem;
  opacity: 0.7;
  transition: all 0.2s;
}

.btn-icon:hover {
  opacity: 1;
  transform: scale(1.1);
}
</style>
