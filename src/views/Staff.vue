<script setup>
import { ref } from 'vue'

const staff = ref([
  { id: 1, name: 'Amjad Khoulani', role: 'Super Admin', status: 'Online', lastActive: 'Just now' },
  { id: 2, name: 'Ahmad Tech', role: 'Network Engineer', status: 'Offline', lastActive: '2 hours ago' },
  { id: 3, name: 'Support Team', role: 'Agent', status: 'Online', lastActive: '5 mins ago' },
])
</script>

<template>
  <div class="staff">
    <div class="header">
      <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
          <h1 class="heading-lg">Staff Management</h1>
          <p class="text-muted">Manage system administrators, engineers, and support agents.</p>
        </div>
        <button class="btn btn-primary">+ Add Staff Member</button>
      </div>
    </div>

    <div class="staff-grid">
      <div v-for="member in staff" :key="member.id" class="glass-card staff-card">
        <div class="staff-header">
          <div class="avatar-large">{{ member.name.charAt(0) }}</div>
          <div class="status-indicator" :class="member.status.toLowerCase()"></div>
        </div>
        <h3 class="staff-name">{{ member.name }}</h3>
        <p class="staff-role text-gradient">{{ member.role }}</p>
        <div class="staff-meta">
          <span class="text-muted text-sm">Last active: {{ member.lastActive }}</span>
        </div>
        <div class="staff-actions">
          <button class="btn btn-secondary">Permissions</button>
          <button class="btn btn-secondary">Edit</button>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.staff {
  display: flex;
  flex-direction: column;
  gap: 2rem;
}

.staff-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  gap: 1.5rem;
}

.staff-card {
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
}

.staff-header {
  position: relative;
  margin-bottom: 1rem;
}

.avatar-large {
  width: 80px;
  height: 80px;
  border-radius: 50%;
  background: var(--grad-blue-cyan);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 2rem;
  font-weight: bold;
  color: white;
  box-shadow: 0 4px 15px rgba(0,0,0,0.3);
}

.status-indicator {
  position: absolute;
  bottom: 5px;
  right: 5px;
  width: 16px;
  height: 16px;
  border-radius: 50%;
  border: 3px solid var(--bg-surface);
}

.status-indicator.online { background: #10b981; }
.status-indicator.offline { background: #64748b; }

.staff-name {
  font-size: 1.25rem;
  font-family: var(--font-display);
  margin-bottom: 0.25rem;
}

.staff-role {
  font-weight: 600;
  margin-bottom: 1rem;
}

.staff-actions {
  display: flex;
  gap: 0.5rem;
  width: 100%;
  margin-top: 1.5rem;
}

.staff-actions .btn {
  flex: 1;
  padding: 0.5rem;
  font-size: 0.875rem;
}
</style>
