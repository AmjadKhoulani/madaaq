const RouterOSClient = require('node-routeros').RouterOSClient;

class MikroTikManager {
  constructor(host, user, password) {
    this.config = { host, user, password };
  }

  async connect() {
    const client = new RouterOSClient({
      host: this.config.host,
      user: this.config.user,
      password: this.config.password,
    });
    return await client.connect();
  }

  // إضافة مشترك برودباند (PPPoE Secret) - للمشتركين الدائمين
  async addPPPoEUser(username, password, profile, service = 'pppoe') {
    const conn = await this.connect();
    try {
      const result = await conn.write('/ppp/secret/add', [
        `=name=${username}`,
        `=password=${password}`,
        `=profile=${profile}`,
        `=service=${service}`,
        `=comment=Created by MadaaQ System - ${new Date().toLocaleDateString()}`
      ]);
      return { success: true, data: result };
    } catch (err) {
      return { success: false, error: err.message };
    } finally {
      conn.close();
    }
  }

  // إضافة كود هوتسبوت (من الكروت المطبوعة)
  async addHotspotVoucher(code, profile) {
    const conn = await this.connect();
    try {
      const result = await conn.write('/ip/hotspot/user/add', [
        `=name=${code}`,
        `=password=${code}`, // الكود هو اليوزر والباسورد معاً
        `=profile=${profile}`
      ]);
      return { success: true, data: result };
    } catch (err) {
      return { success: false, error: err.message };
    } finally {
      conn.close();
    }
  }

  // جلب المستخدمين النشطين واستهلاكهم
  async getActiveSessions() {
    const conn = await this.connect();
    try {
      const active = await conn.write('/ip/hotspot/active/print');
      return active;
    } finally {
      conn.close();
    }
  }

  // فحص قوة الإشارة لجهاز معين (CPE)
  async getSignalStrength(macAddress) {
    const conn = await this.connect();
    try {
      const info = await conn.write('/interface/wireless/registration-table/print', [
        `?last-mac-address=${macAddress}`
      ]);
      return info;
    } finally {
      conn.close();
    }
  }
}

module.exports = MikroTikManager;
