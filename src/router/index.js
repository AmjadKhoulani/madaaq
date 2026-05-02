import { createRouter, createWebHistory } from 'vue-router'
import CommandCenterLayout from '@/components/CommandCenterLayout.vue'
import SuperAdminLayout from '@/components/SuperAdminLayout.vue'
import ClientPortalLayout from '@/components/ClientPortalLayout.vue'
import LandingPage from '@/views/LandingPage.vue'

const routes = [
  {
    path: '/',
    name: 'Landing',
    component: LandingPage
  },
  {
    path: '/login',
    name: 'Login',
    component: () => import('@/views/Login.vue'),
    meta: { guest: true }
  },
  // Vendor (Tenant) Routes
  {
    path: '/vendor',
    component: () => import('@/components/CommandCenterLayout.vue'),
    children: [
      { path: '', name: 'VendorDashboard', component: () => import('@/views/Dashboard.vue') },

      // المشتركين
      { path: 'clients', name: 'VendorClients', component: () => import('@/views/Clients.vue') },
      { path: 'clients/add', name: 'VendorClientsAdd', component: () => import('@/views/clients/ClientForm.vue') },

      // باقات الخدمة
      { path: 'finance/broadband-packages', name: 'VendorBroadbandPackages', component: () => import('@/views/finance/BroadbandPackages.vue') },
      { path: 'finance/hotspot-packages', name: 'VendorHotspotPackages', component: () => import('@/views/finance/HotspotPackages.vue') },

      // الشبكة
      { path: 'network/towers', name: 'VendorNetworkTowers', component: () => import('@/views/network/Towers.vue') },
      { path: 'network/towers/add', name: 'VendorTowerAdd', component: () => import('@/views/network/TowerForm.vue') },
      { path: 'network/towers/view/:id', name: 'VendorTowerDetails', component: () => import('@/views/network/TowerDetails.vue') },
      { path: 'network/towers/edit/:id', name: 'VendorTowerEdit', component: () => import('@/views/network/TowerForm.vue') },
      { path: 'network/map', name: 'VendorMap', component: () => import('@/views/network/Map.vue') },
      { path: 'network/inventory', name: 'VendorInventory', component: () => import('@/views/network/Inventory.vue') },
      { path: 'network/inventory/add', name: 'VendorInventoryAdd', component: () => import('@/views/network/InventoryForm.vue') },
      { path: 'network/inventory/edit/:id', name: 'VendorInventoryEdit', component: () => import('@/views/network/InventoryForm.vue') },
      { path: 'network/hotspot-gen', name: 'VendorHotspotGen', component: () => import('@/views/network/HotspotGen.vue') },

      // المالية والتسويق
      { path: 'finance/invoices', name: 'VendorInvoices', component: () => import('@/views/finance/Invoices.vue') },
      { path: 'finance/reports', name: 'VendorReports', component: () => import('@/views/finance/Reports.vue') },
      { path: 'finance/tower-costs', name: 'VendorTowerCosts', component: () => import('@/views/finance/TowerCosts.vue') },
      { path: 'finance/debts', name: 'VendorDebts', component: () => import('@/views/finance/Debts.vue') },
      { path: 'marketing/whatsapp', name: 'VendorWhatsApp', component: () => import('@/views/marketing/WhatsApp.vue') },

      // النظام
      { path: 'support', name: 'VendorSupport', component: () => import('@/views/support/Tickets.vue') },
      { path: 'staff', name: 'VendorStaff', component: () => import('@/views/Staff.vue') },
      { path: 'roles', name: 'VendorRoles', component: () => import('@/views/vendor/Roles.vue') },
      { path: 'activity', name: 'VendorActivity', component: () => import('@/views/ActivityLogs.vue') },
      { path: 'settings', name: 'VendorSettings', component: () => import('@/views/Settings.vue') }
    ]
  },
  // Super Admin Routes
  {
    path: '/admin',
    component: SuperAdminLayout,
    children: [
      { path: '', name: 'AdminDashboard', component: () => import('@/views/Dashboard.vue') }
    ]
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

// Navigation Guard
router.beforeEach((to, from, next) => {
  next();
});

export default router
