<template>
  <div class="tower-form-page">
    <div class="final-header">
      <div>
        <h1>{{ isEdit ? 'تعديل بيانات الموقع' : 'إضافة موقع جديد للشبكة' }}</h1>
        <p>قم بتحديد نوع الموقع (برج، كابينة، أو نقطة) وتوثيق البيانات التقنية والجغرافية</p>
      </div>
      <div class="header-actions">
        <button class="btn-pro-outline" @click="$router.back()">إلغاء</button>
        <button class="btn-pro-primary">حفظ بيانات الموقع ✅</button>
      </div>
    </div>

    <!-- Section 1: Basic Info & Type -->
    <div class="card mb-30">
      <div class="card-head-pro"><h3>1. تصنيف ومعلومات الموقع</h3></div>
      <div class="form-body-pro">
        <div class="input-row-pro">
          <div class="input-group">
            <label>نوع الموقع</label>
            <select v-model="form.siteType">
              <option value="tower">برج رئيسي 🗼</option>
              <option value="cabinet">كابينة توزيع 📦</option>
              <option value="node">نقطة وصول (Node) 📍</option>
            </select>
          </div>
          <div class="input-group span-2">
            <label>اسم الموقع</label>
            <input v-model="form.name" type="text" :placeholder="sitePlaceholder" />
          </div>
        </div>
        
        <div class="input-row-pro mt-10">
          <div class="input-group">
            <label>خط العرض (Lat)</label>
            <input v-model="form.lat" type="text" placeholder="33.5138" />
          </div>
          <div class="input-group">
            <label>خط الطول (Lng)</label>
            <input v-model="form.lng" type="text" placeholder="36.2765" />
          </div>
          <div class="input-group">
            <label>المنطقة / الحي</label>
            <input v-model="form.area" type="text" placeholder="مثلاً: المزة - دمشق" />
          </div>
        </div>
      </div>
    </div>

    <!-- Section 2: Smart Power System -->
    <div class="card mb-30 shadow-highlight">
      <div class="card-head-pro"><h3>2. نظام الطاقة والتغذية ⚡</h3></div>
      <div class="form-body-pro">
        <div class="input-row-pro">
          <div class="input-group">
            <label>نظام الطاقة</label>
            <select v-model="form.powerType">
              <option value="solar">طاقة شمسية كاملة ☀️</option>
              <option value="grid">كهرباء عامة + UPS 🔌</option>
              <option value="generator">مولد خاص ⛽</option>
              <option value="none">بدون نظام طاقة (تغذية مباشرة)</option>
            </select>
          </div>
          
          <template v-if="form.powerType === 'solar'">
            <div class="input-group animate-fade">
              <label>عدد الألواح</label>
              <input v-model="form.solarPanels" type="number" placeholder="4" />
            </div>
            <div class="input-group animate-fade">
              <label>قدرة المنظم</label>
              <input v-model="form.mpptPower" type="text" placeholder="60A" />
            </div>
          </template>

          <template v-if="form.powerType === 'grid'">
            <div class="input-group animate-fade">
              <label>موديل الـ UPS</label>
              <input v-model="form.upsModel" type="text" placeholder="Mercury 2KVA" />
            </div>
            <div class="input-group animate-fade">
              <label>سعة البطاريات (Ah)</label>
              <input v-model="form.batteryCap" type="number" placeholder="200" />
            </div>
          </template>

          <template v-if="form.powerType === 'generator'">
            <div class="input-group animate-fade">
              <label>سعة الخزان (لتر)</label>
              <input v-model="form.fuelTank" type="number" placeholder="50" />
            </div>
            <div class="input-group animate-fade">
              <label>معدل الاستهلاك</label>
              <input v-model="form.fuelConsum" type="text" placeholder="0.5 L/H" />
            </div>
          </template>
        </div>

        <div class="divider-pro"></div>

        <div class="input-row-pro">
          <div class="input-group">
            <label>نوع التغذية (Uplink)</label>
            <select v-model="form.uplinkType">
              <option value="fiber">Fiber Optic (GPON)</option>
              <option value="wireless">Wireless (AirFiber)</option>
              <option value="cat6">Ethernet (Cat6)</option>
            </select>
          </div>
          <div v-if="form.siteType === 'tower'" class="input-group animate-fade">
            <label>ارتفاع البرج (متر)</label>
            <input v-model="form.height" type="number" placeholder="30" />
          </div>
          <div v-else class="input-group animate-fade">
            <label>ملاحظات الموقع</label>
            <input type="text" placeholder="مثلاً: داخل خزانة الدرج" />
          </div>
        </div>
      </div>
    </div>

    <!-- Section 3: Map Preview -->
    <div class="card no-padding overflow-hidden mb-30">
      <div class="card-head-pro-padded"><h3>3. الخريطة الميدانية للموقع 📍</h3></div>
      <div class="map-container-form">
        <iframe 
          src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d3325.215!2d36.2765!3d33.5138!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2s!4v1700000000000" 
          width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy">
        </iframe>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, reactive } from 'vue';
import { useRoute } from 'vue-router';

const route = useRoute();
const isEdit = computed(() => !!route.params.id);

const form = reactive({
  siteType: 'tower',
  name: '',
  lat: '',
  lng: '',
  area: '',
  powerType: 'solar',
  solarPanels: '',
  mpptPower: '',
  upsModel: '',
  batteryCap: '',
  fuelTank: '',
  fuelConsum: '',
  uplinkType: 'wireless',
  height: ''
});

const sitePlaceholder = computed(() => {
  if (form.siteType === 'tower') return 'مثلاً: برج الشمال الرئيسي';
  if (form.siteType === 'cabinet') return 'مثلاً: كابينة حي الجلاء - C15';
  return 'مثلاً: نقطة توزيع بناء البركة';
});
</script>

<style scoped>
.tower-form-page { width: 100%; max-width: 1200px; margin: 0 auto; }
.final-header { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 35px; }

.card-head-pro { padding-bottom: 20px; border-bottom: 1px solid #f1f5f9; margin-bottom: 25px; }
.card-head-pro-padded { padding: 25px 25px 15px; border-bottom: 1px solid #f1f5f9; }
.card-head-pro h3, .card-head-pro-padded h3 { font-size: 16px; font-weight: 800; color: #1e293b; }

.shadow-highlight { border-top: 5px solid var(--primary); box-shadow: 0 15px 35px rgba(37, 99, 235, 0.05); }

.input-group { margin-bottom: 20px; display: flex; flex-direction: column; gap: 8px; }
.input-group.span-2 { grid-column: span 2; }
.input-group label { font-size: 12px; font-weight: 800; color: #64748b; }
.input-group input, .input-group select { padding: 12px; border-radius: 10px; border: 1px solid #e2e8f0; outline: none; font-size: 14px; background: #f8fafc; transition: 0.2s; }
.input-group input:focus { border-color: var(--primary); background: white; }

.input-row-pro { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; }

.divider-pro { height: 1px; background: #f1f5f9; margin: 10px 0 25px; }

.animate-fade { animation: fadeIn 0.3s ease-in-out; }
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(5px); }
  to { opacity: 1; transform: translateY(0); }
}

.mt-10 { margin-top: 10px; }
.mb-30 { margin-bottom: 30px; }
.no-padding { padding: 0 !important; }
.overflow-hidden { overflow: hidden; }

.btn-pro-primary { background: var(--primary); color: white; border: none; padding: 12px 30px; border-radius: 10px; font-weight: 800; cursor: pointer; text-decoration: none; }
.btn-pro-outline { background: white; border: 1px solid #e2e8f0; padding: 12px 25px; border-radius: 10px; font-weight: 700; cursor: pointer; }

@media (max-width: 900px) {
  .input-row-pro { grid-template-columns: 1fr; }
  .input-group.span-2 { grid-column: span 1; }
}
</style>
