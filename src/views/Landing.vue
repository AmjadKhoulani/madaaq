<template>
  <div class="landing-page" :class="{ 'rtl': $i18n.locale === 'ar' }">
    <!-- Navbar -->
    <nav class="navbar">
      <div class="container">
        <div class="logo">
          <span class="icon">⚡</span>
          <span class="text">MadaaQ</span>
        </div>
        <div class="nav-links">
          <a href="#features">{{ $t('landing.features') }}</a>
          <a href="#pricing">{{ $t('landing.pricing') }}</a>
          <button @click="toggleLang" class="lang-toggle">
            {{ $i18n.locale === 'ar' ? 'English' : 'العربية' }}
          </button>
          <router-link to="/login" class="btn btn-primary">{{ $t('landing.login') }}</router-link>
        </div>
      </div>
    </nav>

    <!-- Hero Section -->
    <header class="hero">
      <div class="container">
        <div class="hero-content">
          <h1>{{ $t('landing.hero_title') }}</h1>
          <p>{{ $t('landing.hero_subtitle') }}</p>
          <div class="cta-group">
            <router-link to="/login" class="btn btn-lg btn-primary">{{ $t('landing.get_started') }}</router-link>
            <a href="#features" class="btn btn-lg btn-outline">{{ $t('landing.learn_more') }}</a>
          </div>
        </div>
        <div class="hero-image">
          <div class="dashboard-preview">
            <!-- Placeholder for a dashboard mockup -->
            <div class="mock-ui">
              <div class="mock-sidebar"></div>
              <div class="mock-main">
                <div class="mock-card"></div>
                <div class="mock-card"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </header>

    <!-- Features Section -->
    <section id="features" class="features">
      <div class="container">
        <h2 class="section-title">{{ $t('landing.why_madaaq') }}</h2>
        <div class="features-grid">
          <div class="feature-card">
            <div class="f-icon">📡</div>
            <h3>{{ $t('landing.mikrotik_title') }}</h3>
            <p>{{ $t('landing.mikrotik_desc') }}</p>
          </div>
          <div class="feature-card">
            <div class="f-icon">💬</div>
            <h3>{{ $t('landing.whatsapp_title') }}</h3>
            <p>{{ $t('landing.whatsapp_desc') }}</p>
          </div>
          <div class="feature-card">
            <div class="f-icon">💳</div>
            <h3>{{ $t('landing.billing_title') }}</h3>
            <p>{{ $t('landing.billing_desc') }}</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
      <div class="container">
        <p>&copy; 2026 MadaaQ ISP Management System. All rights reserved.</p>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { useI18n } from 'vue-i18n';
const { locale } = useI18n();

const toggleLang = () => {
  locale.value = locale.value === 'ar' ? 'en' : 'ar';
  document.documentElement.dir = locale.value === 'ar' ? 'rtl' : 'ltr';
};
</script>

<style scoped>
.landing-page { font-family: 'Tajawal', sans-serif; color: #1e293b; background: #fff; }
.container { max-width: 1200px; margin: 0 auto; padding: 0 20px; }

/* Navbar */
.navbar { height: 80px; display: flex; align-items: center; border-bottom: 1px solid #f1f5f9; position: sticky; top: 0; background: rgba(255,255,255,0.9); backdrop-filter: blur(10px); z-index: 100; }
.navbar .container { display: flex; justify-content: space-between; width: 100%; align-items: center; }
.logo { display: flex; align-items: center; gap: 10px; font-weight: 800; font-size: 24px; color: #2563eb; }
.nav-links { display: flex; align-items: center; gap: 30px; }
.nav-links a { text-decoration: none; color: #64748b; font-weight: 600; transition: color 0.2s; }
.nav-links a:hover { color: #2563eb; }
.lang-toggle { background: none; border: 1px solid #e2e8f0; padding: 5px 12px; border-radius: 6px; cursor: pointer; font-family: inherit; }

/* Hero */
.hero { padding: 100px 0; background: linear-gradient(135deg, #f8fafc 0%, #eff6ff 100%); overflow: hidden; }
.hero .container { display: flex; align-items: center; gap: 60px; }
.hero-content { flex: 1; }
.hero-content h1 { font-size: 48px; line-height: 1.2; margin-bottom: 20px; color: #0f172a; }
.hero-content p { font-size: 18px; color: #64748b; margin-bottom: 40px; line-height: 1.6; }
.hero-image { flex: 1; }
.dashboard-preview { background: #fff; border-radius: 12px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.15); padding: 20px; border: 1px solid #e2e8f0; transform: perspective(1000px) rotateY(-10deg); }
.mock-ui { height: 300px; display: flex; gap: 15px; }
.mock-sidebar { width: 60px; background: #f1f5f9; border-radius: 6px; }
.mock-main { flex: 1; display: flex; flex-direction: column; gap: 15px; }
.mock-card { flex: 1; background: #f8fafc; border-radius: 6px; }

/* Features */
.features { padding: 100px 0; }
.section-title { text-align: center; font-size: 32px; margin-bottom: 60px; }
.features-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px; }
.feature-card { padding: 40px; border-radius: 16px; border: 1px solid #f1f5f9; transition: transform 0.3s; text-align: center; }
.feature-card:hover { transform: translateY(-10px); box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
.f-icon { font-size: 40px; margin-bottom: 20px; }
.feature-card h3 { margin-bottom: 15px; font-size: 20px; }
.feature-card p { color: #64748b; line-height: 1.6; }

/* Buttons */
.btn { padding: 10px 24px; border-radius: 8px; font-weight: 700; cursor: pointer; text-decoration: none; display: inline-block; transition: all 0.2s; border: none; }
.btn-primary { background: #2563eb; color: #fff; }
.btn-primary:hover { background: #1d4ed8; transform: scale(1.02); }
.btn-outline { border: 2px solid #e2e8f0; color: #475569; }
.btn-outline:hover { background: #f8fafc; }
.btn-lg { padding: 15px 32px; font-size: 18px; }

.cta-group { display: flex; gap: 15px; }

/* Footer */
.footer { padding: 40px 0; border-top: 1px solid #f1f5f9; text-align: center; color: #94a3b8; font-size: 14px; }

/* RTL Adjustments */
.rtl { direction: rtl; }
.rtl .hero-image .dashboard-preview { transform: perspective(1000px) rotateY(10deg); }
</style>
