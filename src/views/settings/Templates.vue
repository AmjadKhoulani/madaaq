<template>
  <div>
    <div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
      <div>
        <h1>قوالب الإشعارات (Notification Templates)</h1>
        <p style="color: var(--text-muted);">تخصيص الرسائل التلقائية المرسلة عبر الواتساب والرسائل النصية</p>
      </div>
      <button class="btn btn-primary">+ إضافة قالب جديد</button>
    </div>

    <div class="templates-grid">
      <!-- Expiry Notification -->
      <div class="card template-card">
        <div class="template-header">
          <h3>تنبيه انتهاء الاشتراك</h3>
          <span class="channel-badge whatsapp">WhatsApp</span>
        </div>
        <div class="template-body">
          <p class="preview-label">نص الرسالة:</p>
          <div class="message-preview">
            أهلاً {client_name}، نود تذكيرك بأن اشتراكك في باقة {package_name} سينتهي خلال {days} أيام. لتجنب انقطاع الخدمة يرجى التجديد.
          </div>
        </div>
        <div class="template-footer">
          <div class="variables">
            <span class="var-tag">{client_name}</span>
            <span class="var-tag">{days}</span>
          </div>
          <button class="btn btn-outline btn-sm">تعديل</button>
        </div>
      </div>

      <!-- Welcome Message -->
      <div class="card template-card">
        <div class="template-header">
          <h3>رسالة الترحيب (مشترك جديد)</h3>
          <span class="channel-badge sms">SMS</span>
        </div>
        <div class="template-body">
          <p class="preview-label">نص الرسالة:</p>
          <div class="message-preview">
            مرحباً بك في شبكة MadaaQ! تم تفعيل اشتراكك بنجاح. بيانات الدخول هي: المستخدم {username} وكلمة المرور {password}.
          </div>
        </div>
        <div class="template-footer">
          <div class="variables">
            <span class="var-tag">{username}</span>
            <span class="var-tag">{password}</span>
          </div>
          <button class="btn btn-outline btn-sm">تعديل</button>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.templates-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 25px; }
.template-card { border-top: 4px solid #10b981; }
.template-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
.template-header h3 { margin: 0; font-size: 16px; }

.channel-badge { font-size: 11px; padding: 3px 8px; border-radius: 4px; font-weight: bold; }
.channel-badge.whatsapp { background: #dcfce7; color: #166534; }
.channel-badge.sms { background: #eff6ff; color: #1e40af; }

.preview-label { font-size: 12px; color: var(--text-muted); margin-bottom: 8px; }
.message-preview { background: #f8fafc; padding: 15px; border-radius: 8px; font-size: 13px; line-height: 1.6; border: 1px solid #e2e8f0; color: #334155; }

.template-footer { margin-top: 20px; display: flex; justify-content: space-between; align-items: center; }
.variables { display: flex; gap: 5px; flex-wrap: wrap; }
.var-tag { font-size: 10px; background: #f1f5f9; padding: 2px 6px; border-radius: 3px; color: #64748b; font-family: monospace; }
.btn-sm { padding: 4px 10px; font-size: 12px; }
</style>
