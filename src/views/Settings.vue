<script setup>
</script>

<template>
  <div class="settings">
    <div class="header">
      <h1 class="heading-lg">System Settings</h1>
      <p class="text-muted">Manage your ISP/WISP configuration and API keys.</p>
    </div>

    <div class="settings-grid">
      <!-- WhatsApp Config -->
      <div class="glass-card settings-section">
        <h2 class="heading-md">WhatsApp Integration</h2>
        <form class="settings-form" @submit.prevent>
          <div class="form-group">
            <label class="form-label">WhatsApp Type</label>
            <select class="form-control">
              <option value="api">Meta Cloud API</option>
              <option value="regular">Regular WhatsApp</option>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">API Token</label>
            <input type="password" class="form-control" value="••••••••••••••••••••••••••••••••" />
          </div>
          <div class="form-group">
            <label class="form-label">Phone Number ID</label>
            <input type="text" class="form-control" value="10123456789" />
          </div>
          <button type="submit" class="btn btn-primary">Save Configuration</button>
        </form>
      </div>

      <!-- MikroTik Config -->
      <div class="glass-card settings-section">
        <h2 class="heading-md">MikroTik Defaults</h2>
        <form class="settings-form" @submit.prevent>
          <div class="form-group">
            <label class="form-label">Default API Port</label>
            <input type="number" class="form-control" value="8728" />
          </div>
          <div class="form-group">
            <label class="form-label">Timeout (seconds)</label>
            <input type="number" class="form-control" value="30" />
          </div>
          <div class="form-group">
            <label class="form-label">Use SSL (api-ssl)</label>
            <div style="margin-top: 0.5rem">
              <input type="checkbox" id="use-ssl" />
              <label for="use-ssl" style="margin-left: 0.5rem; color: var(--text-main)">Enable secure connection</label>
            </div>
          </div>
          <button type="submit" class="btn btn-primary">Save Configuration</button>
        </form>
      </div>
    </div>
  </div>
</template>

<style scoped>
.settings {
  display: flex;
  flex-direction: column;
  gap: 2rem;
}

.settings-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
  gap: 1.5rem;
}

.settings-form {
  margin-top: 1.5rem;
}

.form-control {
  background: rgba(30, 41, 59, 0.4);
}

.form-control:focus {
  background: rgba(30, 41, 59, 0.8);
}
</style>
