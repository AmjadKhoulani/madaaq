import api from './api';

export const clientService = {
  // Clients CRUD
  getClients: (params) => api.get('/clients', { params }),
  getClientDetails: (id) => api.get(`/clients/${id}`),
  addClient: (data) => api.post('/clients', data),
  updateClient: (id, data) => api.put(`/clients/${id}`, data),
  
  // Subscription Control
  renewSubscription: (id, data) => api.post(`/clients/${id}/renew`, data),
  toggleStatus: (id) => api.post(`/clients/${id}/toggle-status`),
  
  // Remote Control (The functions requested)
  getRemoteControlLink: (clientId) => api.get(`/clients/${clientId}/remote-control`),
  getCPEManagementLink: (clientId) => api.get(`/clients/${clientId}/cpe-management`),
  
  // Packages
  getPackages: (type) => api.get('/packages', { params: { type } }) // hotspot or pppoe
};

export default clientService;
