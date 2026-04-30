<script setup>
import { ref } from 'vue'

const stats = [
  { title: 'Total Revenue', value: '$124,563', trend: '+14%', isPositive: true },
  { title: 'Active Subscribers', value: '8,234', trend: '+5%', isPositive: true },
  { title: 'Network Load', value: '78%', trend: '-2%', isPositive: false },
  { title: 'Pending Tickets', value: '42', trend: '-12%', isPositive: true }
]

const recentActivity = [
  { id: 1, action: 'User "John Doe" upgraded to Pro Plan', time: '2 mins ago' },
  { id: 2, action: 'Tower "North-B" connection restored', time: '15 mins ago' },
  { id: 3, action: 'Daily backup completed successfully', time: '1 hour ago' },
]
</script>

<template>
  <div class="dashboard">
    <div class="header">
      <h1 class="heading-lg">Overview</h1>
      <p class="text-muted">Welcome back, system is operating normally.</p>
    </div>

    <!-- Stats Grid -->
    <div class="stats-grid">
      <div v-for="stat in stats" :key="stat.title" class="glass-card stat-card">
        <h3 class="stat-title">{{ stat.title }}</h3>
        <div class="stat-value-row">
          <span class="stat-value text-gradient">{{ stat.value }}</span>
          <span class="stat-trend" :class="stat.isPositive ? 'trend-up' : 'trend-down'">
            {{ stat.trend }}
          </span>
        </div>
      </div>
    </div>

    <!-- Main Content Area -->
    <div class="content-grid">
      <!-- Chart Area -->
      <div class="glass-card chart-section">
        <h2 class="heading-md">Network Traffic (24h)</h2>
        <div class="chart-placeholder">
          <!-- Mock Chart Graphic -->
          <div class="bar" style="height: 40%"></div>
          <div class="bar" style="height: 60%"></div>
          <div class="bar" style="height: 30%"></div>
          <div class="bar" style="height: 80%"></div>
          <div class="bar" style="height: 50%"></div>
          <div class="bar" style="height: 90%"></div>
          <div class="bar" style="height: 70%"></div>
        </div>
      </div>

      <!-- Activity Feed -->
      <div class="glass-card activity-section">
        <h2 class="heading-md">Live Activity Feed</h2>
        <ul class="activity-list">
          <li v-for="log in recentActivity" :key="log.id" class="activity-item">
            <div class="activity-indicator"></div>
            <div class="activity-content">
              <p>{{ log.action }}</p>
              <span class="text-muted text-sm">{{ log.time }}</span>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<style scoped>
.dashboard {
  display: flex;
  flex-direction: column;
  gap: 2rem;
}

.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
  gap: 1.5rem;
}

.stat-title {
  color: var(--text-muted);
  font-size: 1rem;
  margin-bottom: 0.5rem;
}

.stat-value-row {
  display: flex;
  justify-content: space-between;
  align-items: flex-end;
}

.stat-value {
  font-size: 2rem;
  font-weight: 700;
  font-family: var(--font-display);
}

.stat-trend {
  font-size: 0.875rem;
  font-weight: 600;
  padding: 2px 8px;
  border-radius: 4px;
}

.trend-up {
  background: rgba(16, 185, 129, 0.1);
  color: #10b981;
}

.trend-down {
  background: rgba(239, 68, 68, 0.1);
  color: #ef4444;
}

.content-grid {
  display: grid;
  grid-template-columns: 2fr 1fr;
  gap: 1.5rem;
}

.chart-placeholder {
  height: 300px;
  display: flex;
  align-items: flex-end;
  gap: 1rem;
  padding-top: 2rem;
  border-bottom: 1px solid var(--border-light);
}

.bar {
  flex: 1;
  background: var(--grad-cyan-purple);
  border-radius: 4px 4px 0 0;
  opacity: 0.8;
  transition: opacity 0.3s, height 1s ease-out;
}

.bar:hover {
  opacity: 1;
  box-shadow: var(--shadow-neon);
}

.activity-list {
  list-style: none;
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
  margin-top: 1.5rem;
}

.activity-item {
  display: flex;
  gap: 1rem;
  align-items: flex-start;
}

.activity-indicator {
  width: 10px;
  height: 10px;
  background: var(--color-primary);
  border-radius: 50%;
  margin-top: 6px;
  box-shadow: 0 0 8px var(--color-primary);
}

.activity-content p {
  margin-bottom: 0.25rem;
}

.text-sm {
  font-size: 0.875rem;
}

@media (max-width: 1024px) {
  .content-grid {
    grid-template-columns: 1fr;
  }
}
</style>
