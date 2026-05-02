<template>
  <div class="whatsapp-center">
    <div class="whatsapp-container">
      <!-- 1. Conversations Sidebar -->
      <div class="chat-sidebar">
        <div class="sidebar-header">
          <div class="user-avatar">M</div>
          <div class="sidebar-actions">
            <span>💬</span>
            <span>⋮</span>
          </div>
        </div>
        <div class="search-bar-chat">
          <input type="text" placeholder="البحث في المشتركين..." />
        </div>
        <div class="chats-list">
          <div v-for="i in 6" :key="i" class="chat-item" :class="{ active: i === 1 }">
            <div class="item-avatar-wrapper">
              <div class="item-avatar">{{ ['أ', 'م', 'خ', 'س', 'ل', 'ن'][i-1] }}</div>
              <div class="status-dot" :class="i % 2 === 0 ? 'offline' : 'online'"></div>
            </div>
            <div class="item-meta">
              <div class="item-top">
                <strong>
                  {{ ['أحمد محمد', 'محمود العلي', 'خالد القدري', 'سامر حموي', 'ليلى مراد', 'نور الدين'][i-1] }}
                  <span class="site-icon" title="مرتبط ببرج الشمال">🗼</span>
                </strong>
                <span class="time">10:45 AM</span>
              </div>
              <div class="item-bottom">
                <p>مرحباً، هل يمكنني تجديد الاشتراك؟</p>
                <span v-if="i === 1" class="unread">2</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- 2. Main Chat Window -->
      <div class="chat-main">
        <div class="chat-header">
          <div class="header-info">
            <div class="user-avatar-small">أ</div>
            <div>
              <h3>أحمد محمد</h3>
              <span class="status">متصل الآن - IP: 10.10.50.22</span>
            </div>
          </div>
          <div class="header-actions">
            <button class="btn-template">قوالب الفواتير 📄</button>
            <span>🔍</span>
            <span @click="showInfo = !showInfo" style="cursor:pointer">ℹ️</span>
          </div>
        </div>

        <div class="chat-messages-area">
          <div class="message-date"><span>اليوم</span></div>
          <div class="message received">
            <div class="msg-content">
              مرحباً، كم الرصيد المتبقي في حسابي؟
              <span class="msg-time">11:00 AM</span>
            </div>
          </div>
          <div class="message sent">
            <div class="msg-content">
              أهلاً أحمد، رصيدك الحالي هو 15,000 ل.س، واشتراكك ينتهي بعد 4 أيام.
              <span class="msg-time">11:05 AM <span class="check">✓✓</span></span>
            </div>
          </div>
        </div>

        <div class="chat-footer">
          <div class="emoji-btn">😊</div>
          <div class="input-area">
            <input type="text" placeholder="اكتب رسالة للمشترك..." />
          </div>
          <div class="voice-btn">🎤</div>
        </div>
      </div>

      <!-- 3. Subscriber Info Card (NEW Panel) -->
      <div v-if="showInfo" class="subscriber-info-panel animate-slide">
        <div class="info-header">
          <h3>تفاصيل المشترك</h3>
          <span class="close-info" @click="showInfo = false">×</span>
        </div>
        <div class="info-body">
          <div class="client-mini-card">
            <div class="main-stat">
              <label>حالة الاشتراك</label>
              <div class="status-badge active">نشط (Active)</div>
            </div>
            <div class="divider"></div>
            <div class="data-row">
              <label>الباقة الحالية:</label>
              <strong>Premium 50M</strong>
            </div>
            <div class="data-row">
              <label>تاريخ الانتهاء:</label>
              <strong class="text-danger">2024-05-05</strong>
            </div>
            <div class="data-row">
              <label>الرصيد:</label>
              <strong class="text-success">15,000 ل.س</strong>
            </div>
            <div class="divider"></div>
            <div class="tech-box">
              <label>بيانات الـ CPE:</label>
              <div class="tech-item"><span>IP:</span> <code>192.168.10.45</code></div>
              <div class="tech-item"><span>Tower:</span> <strong>برج الشمال</strong></div>
              <div class="tech-item"><span>Signal:</span> <span class="signal-good">Excellent (-55 dBm)</span></div>
            </div>
            <div class="mt-20">
              <button class="btn-action-full">تجديد الاشتراك الآن ⚡</button>
              <button class="btn-action-full-outline mt-10">إرسال تنبيه بالانتهاء 🔔</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
const showInfo = ref(true);
</script>

<style scoped>
.whatsapp-center { height: calc(100vh - 120px); width: 100%; display: flex; align-items: center; justify-content: center; background: #f0f2f5; margin-top: -20px; }
.whatsapp-container { width: 100%; height: 100%; max-width: 100%; display: grid; grid-template-columns: 350px 1fr 320px; background: white; overflow: hidden; }

/* Sidebar & Avatar Status */
.chat-sidebar { border-left: 1px solid #e2e8f0; display: flex; flex-direction: column; }
.item-avatar-wrapper { position: relative; }
.status-dot { position: absolute; bottom: 2px; right: 2px; width: 12px; height: 12px; border: 2px solid white; border-radius: 50%; }
.status-dot.online { background: #22c55e; }
.status-dot.offline { background: #94a3b8; }
.site-icon { font-size: 10px; margin-right: 5px; opacity: 0.6; }

/* Main Chat Area */
.chat-main { display: flex; flex-direction: column; background: #efeae2; position: relative; border-left: 1px solid #e2e8f0; }
.chat-main::before { content: ""; position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: url('https://user-images.githubusercontent.com/15075759/28719144-86dc0f70-73b1-11e7-911d-60d70fcded21.png'); opacity: 0.06; }

/* Info Panel (NEW) */
.subscriber-info-panel { background: white; border-right: 1px solid #e2e8f0; display: flex; flex-direction: column; z-index: 20; }
.info-header { padding: 15px 20px; background: #f0f2f5; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e2e8f0; }
.info-header h3 { font-size: 15px; margin: 0; color: #1e293b; }
.close-info { cursor: pointer; font-size: 24px; color: #64748b; }

.info-body { padding: 20px; overflow-y: auto; }
.client-mini-card { display: flex; flex-direction: column; gap: 15px; }
.main-stat { text-align: center; padding: 10px; }
.main-stat label { font-size: 11px; color: #64748b; display: block; margin-bottom: 8px; }
.status-badge { display: inline-block; padding: 5px 15px; border-radius: 20px; font-size: 12px; font-weight: 800; }
.status-badge.active { background: #f0fdf4; color: #16a34a; }

.data-row { display: flex; justify-content: space-between; font-size: 13px; }
.data-row label { color: #64748b; }
.divider { height: 1px; background: #f1f5f9; margin: 5px 0; }

.tech-box { background: #f8fafc; padding: 15px; border-radius: 12px; border: 1px solid #f1f5f9; }
.tech-box label { font-size: 11px; font-weight: 800; color: #94a3b8; display: block; margin-bottom: 10px; }
.tech-item { display: flex; justify-content: space-between; font-size: 12px; margin-bottom: 8px; }
.tech-item code { font-family: monospace; color: var(--primary); }
.signal-good { color: #16a34a; font-weight: 700; }

.btn-action-full { width: 100%; background: var(--primary); color: white; border: none; padding: 12px; border-radius: 10px; font-weight: 800; cursor: pointer; }
.btn-action-full-outline { width: 100%; background: white; color: var(--primary); border: 1px solid var(--primary); padding: 12px; border-radius: 10px; font-weight: 800; cursor: pointer; }

/* Animations & Base */
.sidebar-header { padding: 12px 16px; background: #f0f2f5; display: flex; justify-content: space-between; }
.chats-list { flex: 1; overflow-y: auto; }
.chat-item { display: flex; padding: 12px 15px; gap: 15px; cursor: pointer; border-bottom: 1px solid #f8fafc; }
.chat-item.active { background: #f0f2f5; }
.item-avatar { width: 45px; height: 45px; background: #6366f1; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; }
.item-meta { flex: 1; min-width: 0; }
.item-top { display: flex; justify-content: space-between; }
.item-top strong { font-size: 14px; }
.item-top .time { font-size: 11px; color: #667781; }
.item-bottom { display: flex; justify-content: space-between; }
.item-bottom p { font-size: 12.5px; color: #667781; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin: 0; }

.chat-header { padding: 10px 16px; background: #f0f2f5; display: flex; justify-content: space-between; align-items: center; z-index: 10; }
.chat-messages-area { flex: 1; padding: 20px; overflow-y: auto; display: flex; flex-direction: column; gap: 10px; z-index: 5; }
.message { max-width: 70%; padding: 8px 12px; border-radius: 8px; font-size: 13.5px; box-shadow: 0 1px 1px rgba(0,0,0,0.1); }
.message.received { background: white; align-self: flex-start; }
.message.sent { background: #d9fdd3; align-self: flex-end; }

.chat-footer { padding: 10px 16px; background: #f0f2f5; display: flex; align-items: center; gap: 15px; }
.input-area { flex: 1; }
.input-area input { width: 100%; padding: 10px 15px; border-radius: 8px; border: none; outline: none; }

.text-danger { color: #ef4444; }
.text-success { color: #16a34a; }

.animate-slide { animation: slideIn 0.3s ease-out; }
@keyframes slideIn { from { transform: translateX(100%); } to { transform: translateX(0); } }

@media (max-width: 1300px) {
  .whatsapp-container { grid-template-columns: 350px 1fr; }
  .subscriber-info-panel { position: absolute; right: 0; top: 0; height: 100%; width: 320px; box-shadow: -10px 0 30px rgba(0,0,0,0.1); }
}
</style>
