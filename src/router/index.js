import { createRouter, createWebHistory } from 'vue-router'
import CommandCenterLayout from '../components/CommandCenterLayout.vue'
import Dashboard from '../views/Dashboard.vue'
import Settings from '../views/Settings.vue'
import CRM from '../views/CRM.vue'
import Staff from '../views/Staff.vue'
import ActivityLogs from '../views/ActivityLogs.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      component: CommandCenterLayout,
      children: [
        {
          path: '',
          name: 'dashboard',
          component: Dashboard
        },
        {
          path: 'settings',
          name: 'settings',
          component: Settings
        },
        {
          path: 'crm',
          name: 'crm',
          component: CRM
        },
        {
          path: 'staff',
          name: 'staff',
          component: Staff
        },
        {
          path: 'logs',
          name: 'logs',
          component: ActivityLogs
        }
      ]
    }
  ]
})

export default router
