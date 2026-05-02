<template>
  <div class="client-form-pro">
    <div class="final-header">
      <div>
        <h1>{{ isEdit ? 'تعديل بيانات المشترك' : 'إضافة مشترك جديد' }}</h1>
        <p>تسجيل بيانات المشترك التقنية، جهاز الـ CPE، ومسار الربط بالشبكة</p>
      </div>
      <div class="header-actions">
        <router-link to="/vendor/clients" class="btn-pro-outline">إلغاء</router-link>
        <button class="btn-pro-primary" @click="saveClient">حفظ المشترك ✅</button>
      </div>
    </div>

    <div class="form-grid">
      <!-- 1. Basic Info Section -->
      <div class="card mb-20">
        <h3>1. المعلومات الأساسية والاتصال</h3>
        <div class="input-row">
          <div class="input-group">
            <label>الاسم الكامل للمشترك</label>
            <input v-model="form.name" type="text" placeholder="أحمد محمد العلي" />
          </div>
          <div class="input-group">
            <label>رقم الهاتف (واتساب)</label>
            <input v-model="form.phone" type="text" placeholder="09xxxxxxxx" />
          </div>
          <div class="input-group">
            <label>المنطقة / العنوان</label>
            <input v-model="form.address" type="text" placeholder="حي الروضة - بناء 12" />
          </div>
        </div>
        
        <!-- Interactive Map Picker (NEW) -->
        <div class="map-picker-wrapper mt-15">
          <label class="section-label-small">تحديد الموقع الجغرافي للمنزل 📍</label>
          <div class="map-box-mini">
            <iframe 
              src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d3325.215!2d36.2765!3d33.5138!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2s!4v1700000000000" 
              width="100%" height="200" style="border:0; border-radius: 12px;" allowfullscreen="" loading="lazy">
            </iframe>
            <div class="lat-lng-display">
              <span>Lat: {{ form.lat || '33.5138' }}</span>
              <span>Lng: {{ form.lng || '36.2765' }}</span>
            </div>
          </div>
        </div>
      </div>

      <!-- 2. Technical Connection (CPE & Port Logic) -->
      <div class="card mb-20 shadow-highlight">
        <h3>2. طريقة التوصيل والربط الفيزيائي 📡</h3>
        <div class="input-row">
          <div class="input-group">
            <label>نوع التوصيل</label>
            <select v-model="form.connType">
              <option value="wireless">لاسلكي (Wireless CPE)</option>
              <option value="wired">سلكي (Wired / LAN)</option>
              <option value="fiber">ألياف (Fiber / FTTH)</option>
            </select>
          </div>
          <div class="input-group">
            <label>البرج / الموقع المرتبط</label>
            <select v-model="form.linkedTower">
              <option value="">-- اختر الموقع --</option>
              <option value="1">برج الشمال الرئيسي</option>
              <option value="2">كابينة حي الجلاء</option>
            </select>
          </div>
        </div>

        <!-- Conditional: Wireless Fields -->
        <div v-if="form.connType === 'wireless'" class="input-row animate-fade mt-15">
          <div class="input-group">
            <label>السيكتور المرتبط (SSID)</label>
            <select>
              <option>MadaaQ_Sector_01</option>
              <option>MadaaQ_Sector_02</option>
            </select>
          </div>
          <div class="input-group">
            <label>موديل جهاز العميل (CPE)</label>
            <input type="text" placeholder="NanoStation M5" />
          </div>
        </div>

        <!-- Conditional: Wired Fields (Switch/Server Logic) -->
        <div v-if="form.connType === 'wired'" class="wired-logic-box animate-fade mt-15">
          <div class="input-row">
            <div class="input-group">
              <label>مصدر التغذية السلكي</label>
              <select v-model="form.wiredSource">
                <option value="switch">سويتش ميداني (Switch)</option>
                <option value="server">سيرفر مباشر (Main Server)</option>
              </select>
            </div>
            
            <template v-if="form.wiredSource === 'switch'">
              <div class="input-group">
                <label>اختر السويتش</label>
                <select>
                  <option>EdgeSwitch 24 - Tower Top</option>
                  <option>PoE Switch 08 - Building B</option>
                </select>
              </div>
              <div class="input-group">
                <label>رقم البورت (Port)</label>
                <input type="number" placeholder="12" />
              </div>
            </template>

            <template v-if="form.wiredSource === 'server'">
              <div class="input-group">
                <label>السيرفر الرئيسي</label>
                <select>
                  <option>Core Router CCR</option>
                </select>
              </div>
              <div class="input-group">
                <label>رقم المنفذ (Ether)</label>
                <input type="text" placeholder="ether10" />
              </div>
            </template>
          </div>
        </div>

        <div class="divider-pro"></div>

        <!-- Device Management -->
        <div class="input-row">
          <div class="input-group">
            <label>عنوان الـ IP للجهاز</label>
            <input type="text" placeholder="192.168.x.x" />
          </div>
          <div class="input-group">
            <label>يوزر الجهاز (User)</label>
            <input type="text" placeholder="admin" />
          </div>
          <div class="input-group">
            <label>باسورد الجهاز (Pass)</label>
            <input type="text" placeholder="••••••••" />
          </div>
        </div>
      </div>

      <!-- 3. Internet Account Info -->
      <div class="card mb-20">
        <h3>3. حساب الإنترنت ودخول تطبيق MadaaQ</h3>
        <div class="input-row">
          <div class="input-group">
            <label>يوزر الاشتراك (PPPoE)</label>
            <input v-model="form.bbUser" type="text" placeholder="user123" />
          </div>
          <div class="input-group">
            <label>كلمة مرور الاشتراك</label>
            <input v-model="form.bbPass" type="text" placeholder="••••••••" />
          </div>
          <div class="input-group">
            <label>باقة الإنترنت</label>
            <select v-model="form.package">
              <option value="1">Basic 10M</option>
              <option value="2">Standard 20M</option>
              <option value="3">Premium 50M</option>
            </select>
          </div>
        </div>
        
        <div class="divider-pro"></div>
        
        <div class="input-row">
          <div class="input-group">
            <label>يوزر التطبيق (Portal User)</label>
            <input v-model="form.portalUser" type="text" placeholder="p_user123" />
          </div>
          <div class="input-group">
            <label>كلمة مرور التطبيق</label>
            <input v-model="form.portalPass" type="text" placeholder="pass123" />
          </div>
          <div class="input-group flex-end">
             <div class="quick-actions-btns">
                <button class="btn-action-outline-small" @click="copyCredentials">نسخ البيانات 📋</button>
                <button class="btn-action-outline-small primary-border" @click="sendToWhatsapp">واتساب 💬</button>
             </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive, computed } from 'vue';
import { useRoute } from 'vue-router';

const route = useRoute();
const isEdit = computed(() => !!route.params.id);

const form = reactive({
  name: '',
  phone: '',
  address: '',
  lat: '',
  lng: '',
  connType: 'wireless',
  linkedTower: '',
  wiredSource: 'switch',
  bbUser: '',
  bbPass: '',
  portalUser: '',
  portalPass: '',
  package: '1'
});

const copyCredentials = () => {
  const text = `بيانات اشتراك MadaaQ:\n---\nحساب الإنترنت: ${form.bbUser} / ${form.bbPass}\nتطبيق الموبايل: ${form.portalUser} / ${form.portalPass}`;
  navigator.clipboard.writeText(text);
  alert('تم نسخ البيانات للحافظة 📋');
};

const sendToWhatsapp = () => {
  const msg = encodeURIComponent(`مرحباً ${form.name}، إليك بيانات اشتراكك:\nيوزر الإنترنت: ${form.bbUser}\nيوزر التطبيق: ${form.portalUser}\nكلمة المرور: ${form.portalPass}`);
  window.open(`https://wa.me/${form.phone}?text=${msg}`, '_blank');
};

import axios from 'axios';

const saveClient = async () => {
  try {
    // 1. حفظ البيانات في قاعدة البيانات
    const response = await axios.post('http://localhost:3000/api/clients', form);
    
    if (response.data.success) {
      // 2. إذا كان برودباند، ننشئ الحساب في المايكروتك
      if (form.connType === 'wired' || form.connType === 'wireless') {
        await axios.post('http://localhost:3000/api/mikrotik/add-pppoe', {
          username: form.bbUser,
          password: form.bbPass,
          profile: form.package // نستخدم الباقة كـ Profile
        });
      }
      
      alert('تم حفظ المشترك وتفعيل حسابه في المايكروتك بنجاح ✅');
    }
  } catch (error) {
    console.error(error);
    alert('حدث خطأ أثناء الحفظ، تأكد من تشغيل السيرفر.');
  }
};
</script>

<style scoped>
.client-form-pro { width: 100%; max-width: 1200px; margin: 0 auto; }
.final-header { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 35px; }
.quick-actions-btns { display: flex; gap: 10px; }
.btn-action-outline-small { background: white; border: 1px solid #e2e8f0; padding: 8px 15px; border-radius: 8px; font-size: 12px; font-weight: 700; cursor: pointer; transition: 0.2s; }
.btn-action-outline-small:hover { background: #f8fafc; border-color: var(--primary); color: var(--primary); }
.primary-border { border-color: var(--primary); color: var(--primary); }
.flex-end { display: flex; align-items: flex-end; }

.card h3 { font-size: 15px; font-weight: 800; color: #1e293b; margin-bottom: 20px; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px; }
.shadow-highlight { border-top: 5px solid var(--primary); box-shadow: 0 10px 30px rgba(37, 99, 235, 0.05); }

.wired-logic-box { background: #f8fafc; padding: 15px; border-radius: 12px; border: 1px solid #e2e8f0; }

.input-group { margin-bottom: 15px; display: flex; flex-direction: column; gap: 8px; }
.input-group label { font-size: 12px; font-weight: 800; color: #64748b; }
.input-group input, .input-group select { padding: 12px; border-radius: 10px; border: 1px solid #e2e8f0; outline: none; font-size: 14px; background: white; transition: 0.2s; }
.input-group input:focus { border-color: var(--primary); }

.input-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; }

.divider-pro { height: 1px; background: #f1f5f9; margin: 20px 0; }

.animate-fade { animation: fadeIn 0.3s ease-in-out; }
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(5px); }
  to { opacity: 1; transform: translateY(0); }
}

.map-picker-wrapper { border-top: 1px solid #f1f5f9; padding-top: 20px; }
.section-label-small { font-size: 11px; font-weight: 800; color: #94a3b8; display: block; margin-bottom: 10px; }
.map-box-mini { position: relative; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; }
.lat-lng-display { position: absolute; bottom: 10px; left: 10px; background: rgba(255,255,255,0.9); padding: 5px 12px; border-radius: 8px; font-size: 10px; font-weight: 800; color: #1e293b; display: flex; gap: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
.mt-15 { margin-top: 15px; }
.mb-20 { margin-bottom: 20px; }
.btn-pro-primary { background: var(--primary); color: white; border: none; padding: 12px 35px; border-radius: 10px; font-weight: 800; cursor: pointer; text-decoration: none; }
.btn-pro-outline { background: white; border: 1px solid #e2e8f0; padding: 12px 25px; border-radius: 10px; font-weight: 700; color: #64748b; text-decoration: none; display: inline-block; }

@media (max-width: 768px) {
  .input-row { grid-template-columns: 1fr; }
}
</style>
