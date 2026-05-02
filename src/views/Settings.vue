<template>
  <div class="settings-page-pro">
    <div class="final-header">
      <div>
        <h1>إعدادات المنصة المركزية</h1>
        <p>إدارة بوابات الدفع، إشعارات الواتساب، وتخصيص تجربة المشتركين</p>
      </div>
      <div class="header-actions">
        <button class="btn-pro-primary" @click="saveSettings">حفظ كافة التغييرات ✅</button>
      </div>
    </div>

    <div class="settings-grid">
      <!-- 1. Navigation Sidebar -->
      <div class="settings-nav-sidebar">
        <div v-for="tab in tabs" :key="tab.id" 
             class="nav-item-pro" :class="{ active: activeTab === tab.id }"
             @click="activeTab = tab.id">
          <span class="nav-icon">{{ tab.icon }}</span>
          <div class="nav-text">
            <strong>{{ tab.name }}</strong>
            <small>{{ tab.desc }}</small>
          </div>
        </div>
      </div>

      <!-- 2. Dynamic Content Area -->
      <div class="settings-main-content">
        
        <!-- Tab: Payment Gateways -->
        <div v-if="activeTab === 'payment'" class="animate-fade">
          <h3 class="section-title">بوابات الدفع الإلكتروني 💳</h3>
          <div class="card mb-25 border-highlight">
            <div class="gateway-header-pro">
              <img src="https://upload.wikimedia.org/wikipedia/commons/b/ba/Stripe_Logo%2C_revised_2016.svg" alt="Stripe">
              <div class="status-pill success">نشط</div>
            </div>
            <div class="pro-grid-2 mt-20">
              <div class="pro-field">
                <label>Stripe Public Key</label>
                <input type="text" class="pro-input" placeholder="pk_live_..." />
              </div>
              <div class="pro-field">
                <label>Stripe Secret Key</label>
                <input type="password" class="pro-input" placeholder="sk_live_..." />
              </div>
            </div>
          </div>

          <div class="card mb-25">
            <div class="gateway-header-pro">
              <img src="https://upload.wikimedia.org/wikipedia/commons/b/b5/PayPal.svg" alt="PayPal" style="height: 25px;">
            </div>
            <div class="pro-grid-2 mt-20">
              <div class="pro-field"><label>Client ID</label><input type="text" class="pro-input" /></div>
              <div class="pro-field"><label>Secret Key</label><input type="password" class="pro-input" /></div>
            </div>
          </div>

          <div class="card local-payment-box">
            <h4>الدفع المحلي (سوريا) 🇸🇾</h4>
            <div class="pro-grid-2 mt-20">
              <div class="pro-field"><label>Syriatel Cash ID</label><input type="text" class="pro-input" /></div>
              <div class="pro-field"><label>Sham Cash API</label><input type="text" class="pro-input" /></div>
            </div>
          </div>
        </div>

        <!-- Tab: WhatsApp API -->
        <div v-if="activeTab === 'whatsapp'" class="animate-fade">
          <h3 class="section-title">ربط الواتساب (Meta API) 💬</h3>
          <div class="card">
            <div class="alert-info-pro">
              <span class="icon">💡</span>
              <p>يتم استخدام هذا الربط لإرسال تنبيهات انتهاء الاشتراك، الفواتير، ورسائل الترحيب للمشتركين الجدد.</p>
            </div>
            <div class="pro-field mt-20 full-width">
              <label>System Access Token</label>
              <textarea class="pro-input h-100" placeholder="EAA..."></textarea>
            </div>
            <div class="pro-grid-2 mt-20">
              <div class="pro-field"><label>Business Account ID</label><input type="text" class="pro-input" /></div>
              <div class="pro-field"><label>Phone Number ID</label><input type="text" class="pro-input" /></div>
            </div>
            <div class="mt-20">
              <button class="btn-pro-outline">اختبار الاتصال ⚡</button>
            </div>
          </div>
        </div>

        <!-- Tab: Client Portal -->
        <div v-if="activeTab === 'portal'" class="animate-fade">
          <h3 class="section-title">بوابة المشتركين (Client Portal) 🌐</h3>
          <div class="portal-hero-card card mb-25">
            <div class="hero-content">
              <h2>رابط البوابة الخاص بك جاهز</h2>
              <p>https://netnexus.madaaq.com</p>
              <button class="btn-pro-white mt-15">زيارة البوابة 🔗</button>
            </div>
            <div class="hero-icon">🏠</div>
          </div>
          
          <div class="card">
            <div class="flex-between">
              <div>
                <strong>تفعيل تطبيق الموبايل</strong>
                <p class="text-muted">السماح للمشتركين بالدخول عبر تطبيق MadaaQ الرسمي.</p>
              </div>
              <label class="switch-pro">
                <input type="checkbox" checked>
                <span class="slider"></span>
              </label>
            </div>
          </div>
        </div>

        <!-- Tab: General Settings (NEW) -->
        <div v-if="activeTab === 'general'" class="animate-fade">
          <h3 class="section-title">الهوية والإعدادات العامة ⚙️</h3>
          <div class="card mb-25">
            <div class="pro-grid-2">
              <div class="pro-field">
                <label>اسم الشبكة (Brand Name)</label>
                <input v-model="settings.brandName" type="text" class="pro-input" placeholder="مثلاً: MadaaQ Network" />
              </div>
              <div class="pro-field">
                <label>العملة الافتراضية</label>
                <select v-model="settings.currency" class="pro-input">
                  <option value="SYP">الليرة السورية (ل.س)</option>
                  <option value="USD">الدولار الأمريكي ($)</option>
                </select>
              </div>
            </div>
            <div class="pro-field mt-20 full-width">
              <label>رقم الدعم الفني الرئيسي (واتساب)</label>
              <input v-model="settings.supportPhone" type="text" class="pro-input" placeholder="09xxxxxxxx" />
            </div>
          </div>

          <div class="card">
             <label class="section-label-small">شعار الشبكة (Logo)</label>
             <div class="logo-upload-box mt-10">
                <div class="logo-preview">M</div>
                <button class="btn-pro-outline">تغيير الشعار 🖼️</button>
                <p class="helper-text-p">يفضل استخدام خلفية شفافة بصيغة PNG</p>
             </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue';

const activeTab = ref('general');
const settings = reactive({
  brandName: 'MadaaQ Network',
  currency: 'SYP',
  supportPhone: '0930000000'
});

const tabs = [
  { id: 'general', name: 'إعدادات عامة', desc: 'الهوية، الاسم، العملة', icon: '⚙️' },
  { id: 'payment', name: 'بوابات الدفع', desc: 'Stripe, PayPal, Local', icon: '💳' },
  { id: 'whatsapp', name: 'ربط الواتساب', desc: 'إشعارات وتنبيهات آلياً', icon: '💬' },
  { id: 'portal', name: 'بوابة المشتركين', desc: 'الرابط، التطبيق، الهوية', icon: '🌐' }
];

const saveSettings = () => {
  alert('تم حفظ كافة الإعدادات بنجاح ✅');
};
</script>

<style scoped>
.settings-page-pro { width: 100%; max-width: 1300px; margin: 0 auto; }
.final-header { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 35px; }

.settings-grid { display: grid; grid-template-columns: 320px 1fr; gap: 40px; }

/* Sidebar Navigation */
.settings-nav-sidebar { display: flex; flex-direction: column; gap: 12px; }
.nav-item-pro { padding: 18px 25px; border-radius: 18px; cursor: pointer; display: flex; align-items: center; gap: 15px; transition: 0.3s; background: white; border: 1px solid transparent; }
.nav-item-pro:hover { background: #f8fafc; border-color: #e2e8f0; }
.nav-item-pro.active { background: white; border-color: var(--primary); box-shadow: 0 10px 25px rgba(37, 99, 235, 0.08); }

.nav-icon { font-size: 22px; width: 45px; height: 45px; background: #f8fafc; border-radius: 12px; display: flex; align-items: center; justify-content: center; }
.active .nav-icon { background: #eff6ff; color: var(--primary); }

.nav-text strong { display: block; font-size: 15px; color: #1e293b; }
.nav-text small { font-size: 11px; color: #94a3b8; font-weight: 700; }

/* Section Styles */
.section-title { font-size: 18px; font-weight: 800; color: #1e293b; margin-bottom: 30px; }
.gateway-header-pro { display: flex; justify-content: space-between; align-items: center; padding-bottom: 15px; border-bottom: 1px solid #f1f5f9; }
.gateway-header-pro img { height: 28px; }

.local-payment-box { border-right: 4px solid #7c3aed; background: #f5f3ff !important; }

/* Common UI Elements */
.pro-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
.pro-field label { font-size: 12px; font-weight: 800; color: #64748b; margin-bottom: 8px; display: block; }
.pro-input { width: 100%; padding: 12px 15px; border-radius: 10px; border: 1px solid #e2e8f0; font-size: 14px; outline: none; background: #f8fafc; }
.pro-input:focus { border-color: var(--primary); background: white; }
.h-100 { height: 100px; resize: none; }

.alert-info-pro { background: #eff6ff; padding: 15px; border-radius: 12px; display: flex; gap: 12px; color: #3b82f6; font-size: 13px; line-height: 1.5; }

/* Portal Hero Card */
.portal-hero-card { background: linear-gradient(135deg, var(--primary) 0%, #60a5fa 100%); color: white; padding: 40px !important; display: flex; justify-content: space-between; align-items: center; }
.hero-content h2 { font-size: 22px; font-weight: 900; margin-bottom: 10px; }
.hero-content p { font-size: 14px; opacity: 0.9; font-family: monospace; }
.hero-icon { font-size: 60px; opacity: 0.3; }

.btn-pro-white { background: white; color: var(--primary); border: none; padding: 10px 25px; border-radius: 10px; font-weight: 800; cursor: pointer; }

/* Switch Pro */
.switch-pro { position: relative; display: inline-block; width: 44px; height: 22px; }
.switch-pro input { opacity: 0; width: 0; height: 0; }
.slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #cbd5e1; transition: .4s; border-radius: 34px; }
.slider:before { position: absolute; content: ""; height: 16px; width: 16px; left: 3px; bottom: 3px; background-color: white; transition: .4s; border-radius: 50%; }
input:checked + .slider { background-color: var(--primary); }
input:checked + .slider:before { transform: translateX(22px); }

.animate-fade { animation: fadeIn 0.4s ease-out; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

.logo-upload-box { display: flex; align-items: center; gap: 20px; padding: 15px; background: #f8fafc; border-radius: 12px; }
.logo-preview { width: 60px; height: 60px; background: var(--primary); color: white; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 24px; font-weight: 900; }
.helper-text-p { font-size: 11px; color: #94a3b8; margin: 0; }
.section-label-small { font-size: 11px; font-weight: 800; color: #94a3b8; display: block; margin-bottom: 10px; }
.flex-between { display: flex; justify-content: space-between; align-items: center; }
.text-muted { color: #64748b; font-size: 13px; }

.btn-pro-primary { background: var(--primary); color: white; border: none; padding: 12px 30px; border-radius: 10px; font-weight: 800; cursor: pointer; }
.btn-pro-outline { background: white; border: 1px solid #e2e8f0; padding: 10px 25px; border-radius: 10px; font-weight: 700; cursor: pointer; }

@media (max-width: 1000px) {
  .settings-grid { grid-template-columns: 1fr; }
}
</style>
