<template>
  <div class="hotspot-gen-page">
    <div class="final-header">
      <div>
        <h1>مولد كروت الهوتسبوت (Hotspot Generator)</h1>
        <p>توليد وتصدير كروت الوصول للشبكة مع خيارات الطباعة وتخصيص الباقات</p>
      </div>
      <div class="header-actions">
        <button class="btn-pro-outline" @click="printCards">طباعة الكروت 🖨️</button>
        <button class="btn-pro-primary" @click="generateCards">توليد الكروت الآن ⚡</button>
      </div>
    </div>

    <div class="generator-layout">
      <!-- 1. Generator Settings -->
      <div class="card settings-sidebar">
        <h3>إعدادات المولد</h3>
        <div class="form-body-pro mt-20">
          <div class="input-group">
            <label>الباقة (Package)</label>
            <select v-model="settings.package">
              <option value="1h">ساعة واحدة - 1,000 ل.س</option>
              <option value="1d">يوم كامل - 5,000 ل.س</option>
              <option value="1w">أسبوع - 25,000 ل.س</option>
              <option value="1m">شهر (Unlimited) - 80,000 ل.س</option>
              <option value="5gb">5 GB - محدود الحجم</option>
            </select>
          </div>
          <div class="input-group">
            <label>عدد الكروت</label>
            <input v-model="settings.count" type="number" min="1" max="100" />
          </div>
          <div class="input-group">
            <label>طول الكود (Code Length)</label>
            <select v-model="settings.length">
              <option :value="4">4 أرقام</option>
              <option :value="6">6 أرقام (موصى به)</option>
              <option :value="8">8 أرقام</option>
            </select>
          </div>
          <div class="divider-pro"></div>
          <div class="input-group">
            <label>نمط التصميم</label>
            <div class="design-options">
              <div class="d-opt active">قالب MadaaQ</div>
              <div class="d-opt">قالب بسيط</div>
            </div>
          </div>
        </div>
      </div>

      <!-- 2. Live Preview Area -->
      <div class="cards-preview-area">
        <div class="preview-header">
          <span>معاينة قبل الطباعة ({{ generatedCards.length }} كرت)</span>
          <div class="zoom-controls">
            <span>A+</span> <span>A-</span>
          </div>
        </div>
        
        <div class="cards-grid" id="printable-area">
          <div v-for="card in generatedCards" :key="card.id" class="voucher-card" :class="settings.package">
            <div class="card-top">
              <span class="mini-logo-text">MadaaQ</span>
              <div class="p-price">{{ card.price }}</div>
            </div>
            <div class="card-main">
              <label>كود التفعيل (Code)</label>
              <div class="v-code">{{ card.code }}</div>
            </div>
            <div class="card-footer">
              <div class="v-meta">
                <span>{{ card.duration }}</span>
                <span>{{ card.limit }}</span>
              </div>
              <div class="v-note">شبكة MadaaQ للإنترنت</div>
            </div>
          </div>
        </div>

        <div v-if="generatedCards.length === 0" class="empty-preview">
          <div class="empty-icon">🎟️</div>
          <p>لم يتم توليد أي كروت بعد. قم بضبط الإعدادات واضغط على "توليد".</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue';

const settings = reactive({
  package: '1d',
  count: 12,
  length: 6
});

const generatedCards = ref([]);

const generateCards = () => {
  const newCards = [];
  const prices = { '1h': '1,000 ل.س', '1d': '5,000 ل.س', '1w': '25,000 ل.س', '1m': '80,000 ل.س', '5gb': '15,000 ل.س' };
  const labels = { '1h': 'ساعة واحدة', '1d': 'يوم كامل', '1w': 'أسبوع كامل', '1m': 'شهر كامل', '5gb': 'باقة 5 جيجا' };
  
  for (let i = 0; i < settings.count; i++) {
    newCards.push({
      id: i,
      code: Math.random().toString(36).substring(2, 2 + settings.length).toUpperCase(),
      price: prices[settings.package],
      duration: labels[settings.package],
      limit: settings.package === '5gb' ? '5 GB' : 'Unlimited'
    });
  }
  generatedCards.value = newCards;
};

const printCards = () => {
  window.print();
};
</script>

<style scoped>
.hotspot-gen-page { width: 100%; }
.final-header { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 30px; }

.generator-layout { display: grid; grid-template-columns: 320px 1fr; gap: 30px; align-items: start; }

.settings-sidebar h3 { font-size: 15px; font-weight: 800; color: #1e293b; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px; }

.input-group { margin-bottom: 20px; display: flex; flex-direction: column; gap: 8px; }
.input-group label { font-size: 12px; font-weight: 800; color: #64748b; }
.input-group input, .input-group select { padding: 10px; border-radius: 8px; border: 1px solid #e2e8f0; outline: none; font-size: 14px; background: #f8fafc; }

.design-options { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
.d-opt { padding: 8px; border: 1px solid #e2e8f0; border-radius: 6px; text-align: center; font-size: 11px; font-weight: 700; cursor: pointer; }
.d-opt.active { background: var(--primary); color: white; border-color: var(--primary); }

.cards-preview-area { background: #f8fafc; border-radius: 20px; border: 1px dashed #cbd5e1; min-height: 600px; display: flex; flex-direction: column; }
.preview-header { padding: 15px 25px; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; font-size: 13px; font-weight: 700; color: #64748b; }

.cards-grid { padding: 30px; display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 15px; }

/* Voucher Card Styling */
.voucher-card { background: white; border: 1px solid #e2e8f0; border-radius: 12px; padding: 15px; display: flex; flex-direction: column; gap: 10px; position: relative; overflow: hidden; }
.voucher-card::before { content: ""; position: absolute; top: 0; left: 0; width: 4px; height: 100%; }

/* Color coding by package */
.voucher-card.1h::before { background: #ef4444; }
.voucher-card.1d::before { background: #3b82f6; }
.voucher-card.1w::before { background: #8b5cf6; }
.voucher-card.1m::before { background: #10b981; }

.card-top { display: flex; justify-content: space-between; align-items: center; }
.mini-logo-text { font-size: 10px; font-weight: 900; color: var(--primary); letter-spacing: 1px; text-transform: uppercase; }
.p-price { font-size: 11px; font-weight: 900; color: #1e293b; background: #f1f5f9; padding: 2px 6px; border-radius: 4px; }

.card-main { text-align: center; padding: 10px 0; }
.card-main label { font-size: 9px; font-weight: 800; color: #94a3b8; display: block; margin-bottom: 5px; }
.v-code { font-family: monospace; font-size: 18px; font-weight: 900; color: #111827; letter-spacing: 2px; }

.card-footer { display: flex; flex-direction: column; gap: 5px; border-top: 1px dashed #e2e8f0; padding-top: 8px; }
.v-meta { display: flex; justify-content: space-between; font-size: 10px; font-weight: 800; color: #64748b; }
.v-note { font-size: 8px; color: #94a3b8; text-align: center; }

.empty-preview { flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; color: #94a3b8; gap: 15px; }
.empty-icon { font-size: 60px; opacity: 0.3; }

.divider-pro { height: 1px; background: #f1f5f9; margin: 10px 0; }

.btn-pro-primary { background: var(--primary); color: white; border: none; padding: 12px 30px; border-radius: 10px; font-weight: 800; cursor: pointer; }
.btn-pro-outline { background: white; border: 1px solid #e2e8f0; padding: 12px 25px; border-radius: 10px; font-weight: 700; cursor: pointer; }

@media print {
  .final-header, .settings-sidebar, .preview-header { display: none !important; }
  .hotspot-gen-page { margin: 0; padding: 0; }
  .cards-preview-area { background: white; border: none; padding: 0; }
  .cards-grid { padding: 0; gap: 10px; grid-template-columns: repeat(3, 1fr); }
  .voucher-card { border: 1px solid #000; box-shadow: none; break-inside: avoid; }
}

@media (max-width: 900px) {
  .generator-layout { grid-template-columns: 1fr; }
}
</style>
