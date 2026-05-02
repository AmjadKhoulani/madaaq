import api from './api';

export const networkService = {
  // Internet Sources
  getSources: () => api.get('/network/sources'),
  addSource: (data) => api.post('/network/sources', data),
  
  // MikroTik Servers
  getServers: () => api.get('/network/servers'),
  getServerDetails: (id) => api.get(`/network/servers/${id}`),
  addServer: (data) => api.post('/network/servers', data),
  syncServer: (id) => api.post(`/network/servers/${id}/sync`),
  
  // Towers & Hubs
  getTowers: () => api.get('/network/towers'),
  getTowerDetails: (id) => api.get(`/network/towers/${id}`),
  addTower: (data) => api.post('/network/towers', data),
  
  // Infrastructure Devices
  getDevices: (towerId) => api.get(`/network/devices?tower_id=${towerId}`),
  addDevice: (data) => api.post('/network/devices', data),
  pingDevice: (id) => api.get(`/network/devices/${id}/ping`),
  rebootDevice: (id) => api.post(`/network/devices/${id}/reboot`)
};

export default networkService;
