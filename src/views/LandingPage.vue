<template>
  <div class="landing-pro">
    <!-- 1. Transparent Header -->
    <nav class="landing-nav">
      <div class="container">
        <div class="brand"><span class="dot"></span> MadaaQ</div>
        <div class="nav-links">
          <a href="#features">المميزات</a>
          <a href="#pricing">الأسعار</a>
          <a href="#contact">تواصل معنا</a>
          <router-link to="/vendor" class="btn-login-outline">دخول الموردين</router-link>
          <button class="btn-cta-nav">احصل على نسخة</button>
        </div>
      </div>
    </nav>

    <!-- 2. Hero Section -->
    <section class="hero-section">
      <div class="hero-bg-visual"></div>
      <div class="container">
        <div class="hero-content">
          <span class="hero-badge">مستقبل إدارة الـ ISP بين يديك 🚀</span>
          <h1>أقوى منصة لإدارة شبكات الإنترنت والـ MikroTik</h1>
          <p>تحكم كامل في المشتركين، الفواتير، والأبراج من واجهة واحدة احترافية. قل وداعاً للفوضى التقنية وابدأ بإدارة شبكتك بذكاء.</p>
          <div class="hero-btns">
            <button class="btn-primary-lg">ابدأ تجربتك المجانية</button>
            <button class="btn-outline-lg">شاهد العرض التجريبي 📺</button>
          </div>
        </div>
        <div class="hero-mockup">
          <div class="glass-screen">
             <div class="mock-ui-dots"><span></span><span></span><span></span></div>
             <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80" alt="Dashboard Preview" />
          </div>
        </div>
      </div>
    </section>

    <!-- 3. Trusted By Section -->
    <div class="trusted-bar">
      <div class="container">
        <label>موثوق من قبل أفضل مزودي الخدمة:</label>
        <div class="logos-wrap">
          <span class="mock-logo">NetLink</span>
          <span class="mock-logo">FastConnect</span>
          <span class="mock-logo">SkyWave</span>
          <span class="mock-logo">GigaNet</span>
        </div>
      </div>
    </div>

    <!-- 4. Features Section -->
    <section id="features" class="features-section">
      <div class="container">
        <div class="section-head">
          <h2>مميزات تتخطى التوقعات</h2>
          <p>لقد قمنا ببناء MadaaQ ليكون الحل الشامل لكل ثغرة تواجهك في إدارة شبكتك</p>
        </div>
        <div class="features-grid">
          <div class="feature-card">
            <div class="f-icon">🖥️</div>
            <h3>ربط MikroTik API</h3>
            <p>تحكم مباشر في السيرفرات، إدارة السرعات، وقطع الخدمة تلقائياً عند انتهاء الاشتراك.</p>
          </div>
          <div class="feature-card active">
            <div class="f-icon">👥</div>
            <h3>نظام CRM ذكي</h3>
            <p>إدارة كاملة لبيانات العملاء، عقود الاشتراك، وسجل النشاط التقني لكل مستخدم.</p>
          </div>
          <div class="feature-card">
            <div class="f-icon">💰</div>
            <h3>التحصيل الآلي</h3>
            <p>نظام فواتير متطور يدعم العملات المتعددة، الديون، وتقارير الـ ROI الدقيقة.</p>
          </div>
          <div class="feature-card">
            <div class="f-icon">🎫</div>
            <h3>مولد كروت الهوتسبوت</h3>
            <p>توليد آلاف الكروت بضغطة زر، مع قوالب طباعة احترافية وإحصائيات مبيعات فورية.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- 5. Dashboard Preview (Wide View) -->
    <section class="preview-section">
      <div class="container">
        <div class="preview-content">
          <h2>واجهة القيادة الأكثر تطوراً</h2>
          <p>راقب أداء أبراجك، استهلاك الباندويث، وحالة السيرفرات بلمحة بصر واحدة من خلال لوحة قيادة "Radiant Monolith".</p>
        </div>
        <div class="full-mockup-wrap">
          <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?ixlib=rb-1.2.1&auto=format&fit=crop&w=1500&q=80" alt="Full App Dashboard" />
        </div>
      </div>
    </section>

    <!-- 6. Pricing Section -->
    <section id="pricing" class="pricing-section">
      <div class="container">
        <div class="section-head">
          <h2>خطط تناسب طموحاتك</h2>
          <p>اختر الخطة الأنسب لحجم شبكتك، ويمكنك الترقية في أي وقت</p>
        </div>
        <div class="pricing-grid">
          <div class="price-card">
            <label>الباقة الأساسية</label>
            <div class="price">$29<span>/شهرياً</span></div>
            <ul>
              <li>5 سيرفرات ميكروتيك</li>
              <li>حتى 500 مشترك</li>
              <li>دعم فني عبر التذاكر</li>
            </ul>
            <button class="btn-outline-full">اشترك الآن</button>
          </div>
          <div class="price-card popular">
            <div class="popular-tag">الأكثر طلباً</div>
            <label>باقة المؤسسات</label>
            <div class="price">$79<span>/شهرياً</span></div>
            <ul>
              <li>سيرفرات غير محدودة</li>
              <li>مشتركين غير محدودين</li>
              <li>دعم فني VIP 24/7</li>
              <li>نظام المحاسبة المتقدم</li>
            </ul>
            <button class="btn-primary-full">اشترك الآن</button>
          </div>
        </div>
      </div>
    </section>

    <!-- 7. Footer -->
    <footer id="contact" class="landing-footer">
      <div class="container">
        <div class="footer-cols">
          <div class="col">
            <div class="brand"><span class="dot"></span> MadaaQ</div>
            <p>المنصة رقم #1 لإدارة مزودي خدمات الإنترنت في المنطقة العربية.</p>
          </div>
          <div class="col">
            <h4>روابط سريعة</h4>
            <a href="#">المميزات</a>
            <a href="#">الأسعار</a>
            <a href="#">التوثيق البرمجي</a>
          </div>
          <div class="col">
            <h4>تواصل معنا</h4>
            <p>sales@madaaq.com</p>
            <div class="social-icons">🌐 💬 🐦</div>
          </div>
        </div>
        <div class="footer-bottom">
          <p>جميع الحقوق محفوظة © 2024 - MadaaQ Team</p>
        </div>
      </div>
    </footer>
  </div>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Outfit:wght@400;700;900&display=swap');

.landing-pro { direction: rtl; font-family: 'Noto Sans Arabic', 'Outfit', sans-serif; background: #fdfdfd; color: #1e293b; overflow-x: hidden; }
.container { max-width: 1300px; margin: 0 auto; padding: 0 30px; }

/* Nav */
.landing-nav { height: 90px; display: flex; align-items: center; position: fixed; top: 0; left: 0; right: 0; background: rgba(255,255,255,0.9); backdrop-filter: blur(10px); z-index: 2000; border-bottom: 1px solid rgba(0,0,0,0.05); }
.landing-nav .container { display: flex; justify-content: space-between; align-items: center; width: 100%; }
.brand { font-size: 24px; font-weight: 900; color: #1e293b; display: flex; align-items: center; gap: 10px; }
.dot { width: 12px; height: 12px; background: #0061ff; border-radius: 4px; transform: rotate(45deg); }
.nav-links { display: flex; align-items: center; gap: 30px; }
.nav-links a { text-decoration: none; color: #64748b; font-weight: 700; font-size: 15px; transition: 0.2s; }
.nav-links a:hover { color: #0061ff; }

/* Buttons */
.btn-cta-nav { background: #0061ff; color: white; border: none; padding: 12px 25px; border-radius: 12px; font-weight: 800; cursor: pointer; box-shadow: 0 10px 20px rgba(0,97,255,0.2); }
.btn-login-outline { border: 1px solid #e2e8f0; color: #1e293b; padding: 10px 20px; border-radius: 12px; text-decoration: none; font-size: 14px; font-weight: 700; }

/* Hero Section */
.hero-section { padding: 180px 0 120px; position: relative; overflow: hidden; background: linear-gradient(135deg, #f8faff 0%, #fff 100%); }
.hero-bg-visual { position: absolute; top: -200px; left: -200px; width: 600px; height: 600px; background: radial-gradient(circle, rgba(0,97,255,0.05) 0%, transparent 70%); z-index: 0; }
.hero-section .container { display: flex; align-items: center; gap: 80px; position: relative; z-index: 1; }
.hero-content { flex: 1; }
.hero-badge { background: #eff6ff; color: #0061ff; padding: 8px 20px; border-radius: 50px; font-size: 13px; font-weight: 800; margin-bottom: 25px; display: inline-block; }
.hero-content h1 { font-size: 56px; font-weight: 900; color: #1e293b; line-height: 1.2; margin-bottom: 25px; }
.hero-content p { font-size: 19px; color: #64748b; line-height: 1.6; margin-bottom: 40px; max-width: 550px; }
.hero-btns { display: flex; gap: 20px; }
.btn-primary-lg { background: #0061ff; color: white; border: none; padding: 18px 40px; border-radius: 15px; font-size: 16px; font-weight: 800; cursor: pointer; box-shadow: 0 20px 40px rgba(0,97,255,0.3); }
.btn-outline-lg { background: white; border: 1px solid #e2e8f0; color: #1e293b; padding: 18px 40px; border-radius: 15px; font-size: 16px; font-weight: 800; cursor: pointer; }

.hero-mockup { flex: 1.2; }
.glass-screen { background: white; padding: 15px; border-radius: 20px; box-shadow: 0 50px 100px rgba(0,0,0,0.1); border: 1px solid #f1f5f9; transform: perspective(1000px) rotateY(-5deg); }
.mock-ui-dots { display: flex; gap: 6px; margin-bottom: 12px; }
.mock-ui-dots span { width: 8px; height: 8px; background: #e2e8f0; border-radius: 50%; }
.hero-mockup img { width: 100%; border-radius: 10px; border: 1px solid #f1f5f9; }

/* Trusted Bar */
.trusted-bar { padding: 40px 0; border-top: 1px solid #f1f5f9; border-bottom: 1px solid #f1f5f9; background: white; }
.trusted-bar .container { display: flex; align-items: center; justify-content: center; gap: 40px; }
.trusted-bar label { font-size: 14px; font-weight: 700; color: #94a3b8; }
.logos-wrap { display: flex; gap: 50px; opacity: 0.5; filter: grayscale(1); }
.mock-logo { font-size: 20px; font-weight: 900; color: #1e293b; }

/* Features Section */
.features-section { padding: 120px 0; background: #fff; }
.section-head { text-align: center; margin-bottom: 80px; }
.section-head h2 { font-size: 36px; font-weight: 900; margin-bottom: 20px; }
.section-head p { color: #64748b; font-size: 18px; }

.features-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 30px; }
.feature-card { padding: 45px 35px; border-radius: 25px; border: 1px solid #f1f5f9; background: #fdfdfd; transition: 0.3s; }
.feature-card:hover { transform: translateY(-10px); border-color: #0061ff; box-shadow: 0 20px 40px rgba(0,0,0,0.03); }
.feature-card.active { background: white; border-color: transparent; box-shadow: 0 30px 60px rgba(0,0,0,0.08); }
.f-icon { font-size: 40px; margin-bottom: 25px; }
.feature-card h3 { font-size: 20px; font-weight: 800; margin-bottom: 15px; }
.feature-card p { color: #64748b; line-height: 1.6; font-size: 15px; }

/* Preview Section */
.preview-section { padding: 100px 0; background: #f8faff; overflow: hidden; }
.preview-content { text-align: center; margin-bottom: 60px; }
.preview-content h2 { font-size: 32px; font-weight: 900; margin-bottom: 20px; }
.full-mockup-wrap { max-width: 1100px; margin: 0 auto; border-radius: 20px; overflow: hidden; box-shadow: 0 50px 100px rgba(0,0,0,0.1); border: 10px solid white; }
.full-mockup-wrap img { width: 100%; display: block; }

/* Pricing Section */
.pricing-section { padding: 120px 0; }
.pricing-grid { display: flex; justify-content: center; gap: 30px; margin-top: 60px; }
.price-card { background: white; border: 1px solid #f1f5f9; padding: 50px 40px; border-radius: 30px; width: 350px; text-align: center; position: relative; }
.price-card.popular { border: 2px solid #0061ff; box-shadow: 0 30px 60px rgba(0,97,255,0.1); }
.popular-tag { position: absolute; top: -15px; left: 50%; transform: translateX(-50%); background: #0061ff; color: white; padding: 6px 15px; border-radius: 50px; font-size: 12px; font-weight: 800; }
.price-card label { font-size: 16px; font-weight: 800; color: #64748b; }
.price { font-size: 52px; font-weight: 900; color: #1e293b; margin: 20px 0; }
.price span { font-size: 16px; color: #94a3b8; }
.price-card ul { list-style: none; padding: 0; margin: 30px 0; display: flex; flex-direction: column; gap: 15px; }
.price-card li { font-size: 15px; color: #475569; font-weight: 700; }
.btn-outline-full { width: 100%; padding: 15px; border-radius: 12px; border: 1px solid #e2e8f0; background: white; font-weight: 800; cursor: pointer; }
.btn-primary-full { width: 100%; padding: 15px; border-radius: 12px; border: none; background: #0061ff; color: white; font-weight: 800; cursor: pointer; box-shadow: 0 10px 20px rgba(0,97,255,0.2); }

/* Footer */
.landing-footer { background: #1e293b; color: white; padding: 100px 0 40px; }
.footer-cols { display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 60px; padding-bottom: 60px; border-bottom: 1px solid rgba(255,255,255,0.1); }
.landing-footer .brand { color: white; margin-bottom: 20px; }
.landing-footer .brand .dot { background: #0061ff; }
.footer-cols p { color: #94a3b8; line-height: 1.6; }
.footer-cols h4 { margin-bottom: 25px; font-size: 18px; font-weight: 800; }
.footer-cols a { display: block; color: #94a3b8; text-decoration: none; margin-bottom: 12px; font-weight: 700; }
.footer-cols a:hover { color: #0061ff; }
.footer-bottom { text-align: center; padding-top: 40px; color: #64748b; font-size: 14px; }

@media (max-width: 1024px) {
  .hero-section .container { flex-direction: column; text-align: center; }
  .hero-content h1 { font-size: 42px; }
  .hero-content p { margin: 0 auto 40px; }
  .hero-btns { justify-content: center; }
  .hero-mockup { transform: none; margin-top: 50px; width: 100%; }
  .pricing-grid { flex-direction: column; align-items: center; }
}
</style>
