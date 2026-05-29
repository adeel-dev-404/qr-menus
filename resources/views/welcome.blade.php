<!DOCTYPE html>
<html lang="en" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="QR Menu SaaS — Digital menu system for Pakistani restaurants. Scan QR, view menu instantly.">
    <title>QR Menu SaaS — Digital Menus for Restaurants</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg:       #080808;
            --surface:  #111111;
            --surface2: #181818;
            --border:   #1e1e1e;
            --border2:  #2a2a2a;
            --text:     #f0f0f0;
            --text2:    #909090;
            --text3:    #505050;
            --accent:   #e8502a;
            --accent2:  #c43e1c;
            --accent-bg: rgba(232,80,42,.1);
            --accent-border: rgba(232,80,42,.2);
            --max:      1100px;
        }

        html { scroll-behavior: smooth; }
        body {
            background: var(--bg);
            color: var(--text);
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
        }

        /* ── RTL for Urdu ── */
        [lang="ur"] { direction: rtl; font-family: 'Noto Nastaliq Urdu', 'Jameel Noori Nastaleeq', sans-serif; }
        [lang="ur"] .nav-links { flex-direction: row-reverse; }

        /* ── NAVBAR ── */
        .navbar {
            position: fixed; top: 0; left: 0; right: 0; z-index: 100;
            background: rgba(8,8,8,.85);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border);
            padding: 0 24px;
            height: 64px;
            display: flex; align-items: center; justify-content: space-between;
        }
        .nav-inner { max-width: var(--max); width: 100%; margin: 0 auto; display: flex; align-items: center; justify-content: space-between; }
        .nav-logo { display: flex; align-items: center; gap: 10px; text-decoration: none; }
        .nav-logo-icon { width: 36px; height: 36px; border-radius: 10px; background: linear-gradient(145deg,#e8a23a,#e8502a); display: flex; align-items: center; justify-content: center; font-size: 17px; }
        .nav-logo-name { font-size: 16px; font-weight: 700; color: var(--text); letter-spacing: -.3px; }
        .nav-links { display: flex; align-items: center; gap: 28px; }
        .nav-links a { font-size: 14px; color: var(--text2); text-decoration: none; transition: color .2s; }
        .nav-links a:hover { color: var(--text); }
        .nav-cta { display: flex; align-items: center; gap: 10px; }
        .btn-ghost { padding: 8px 16px; font-size: 13px; color: var(--text2); text-decoration: none; border-radius: 8px; border: 1px solid var(--border2); transition: all .2s; }
        .btn-ghost:hover { color: var(--text); border-color: var(--border); }
        .btn-accent { padding: 8px 18px; font-size: 13px; font-weight: 600; color: #fff; background: var(--accent); border: none; border-radius: 8px; text-decoration: none; cursor: pointer; transition: background .2s; }
        .btn-accent:hover { background: var(--accent2); }

        /* Language toggle */
        .lang-toggle { display: flex; align-items: center; gap: 2px; background: var(--surface2); border: 1px solid var(--border2); border-radius: 8px; padding: 3px; }
        .lang-btn { padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer; border: none; background: none; color: var(--text3); transition: all .2s; }
        .lang-btn.active { background: var(--accent); color: #fff; }

        /* Mobile menu */
        .hamburger { display: none; background: none; border: none; cursor: pointer; color: var(--text2); padding: 4px; }
        .hamburger svg { width: 22px; height: 22px; }
        .mobile-menu { display: none; position: fixed; top: 64px; left: 0; right: 0; background: var(--surface); border-bottom: 1px solid var(--border); padding: 20px 24px; z-index: 99; }
        .mobile-menu.open { display: block; }
        .mobile-menu a { display: block; padding: 12px 0; font-size: 15px; color: var(--text2); text-decoration: none; border-bottom: 1px solid var(--border); }
        .mobile-menu a:last-child { border: none; }

        @media(max-width: 768px) {
            .nav-links, .nav-cta .btn-ghost { display: none; }
            .hamburger { display: block; }
            .lang-toggle { display: flex; }
        }

        /* ── SECTIONS BASE ── */
        section { padding: 96px 24px; }
        .container { max-width: var(--max); margin: 0 auto; }

        /* ── HERO ── */
        .hero {
            min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
            text-align: center; padding: 120px 24px 80px;
            position: relative; overflow: hidden;
        }
        .hero-glow-1 {
            position: absolute; width: 600px; height: 600px; border-radius: 50%;
            background: radial-gradient(circle, rgba(232,80,42,.08) 0%, transparent 65%);
            top: -100px; left: 50%; transform: translateX(-50%);
            pointer-events: none;
        }
        .hero-glow-2 {
            position: absolute; width: 400px; height: 400px; border-radius: 50%;
            background: radial-gradient(circle, rgba(232,162,58,.05) 0%, transparent 65%);
            bottom: 0; right: -100px; pointer-events: none;
        }
        .hero-inner { position: relative; max-width: 780px; }
        .hero-badge {
            display: inline-flex; align-items: center; gap: 8px;
            background: var(--accent-bg); border: 1px solid var(--accent-border);
            border-radius: 99px; padding: 6px 16px;
            font-size: 12px; font-weight: 600; color: var(--accent);
            margin-bottom: 28px; letter-spacing: .04em;
        }
        .hero-badge-dot { width: 6px; height: 6px; border-radius: 99px; background: var(--accent); animation: pulse 2s infinite; }
        @keyframes pulse { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:.4;transform:scale(1.4)} }
        .hero-title {
            font-size: clamp(36px, 6vw, 68px);
            font-weight: 800; line-height: 1.1;
            letter-spacing: -1.5px; color: var(--text);
            margin-bottom: 20px;
        }
        .hero-title span { color: var(--accent); }
        .hero-subtitle {
            font-size: clamp(15px, 2vw, 18px);
            color: var(--text2); line-height: 1.65;
            max-width: 560px; margin: 0 auto 36px;
        }
        .hero-buttons { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; }
        .btn-primary-lg {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 14px 28px; background: var(--accent); color: #fff;
            border: none; border-radius: 12px; font-size: 15px; font-weight: 700;
            text-decoration: none; cursor: pointer; transition: all .2s;
            box-shadow: 0 8px 24px rgba(232,80,42,.3);
        }
        .btn-primary-lg:hover { background: var(--accent2); transform: translateY(-1px); box-shadow: 0 12px 32px rgba(232,80,42,.4); }
        .btn-secondary-lg {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 14px 28px; background: var(--surface2); color: var(--text);
            border: 1px solid var(--border2); border-radius: 12px;
            font-size: 15px; font-weight: 600; text-decoration: none;
            transition: all .2s;
        }
        .btn-secondary-lg:hover { border-color: var(--border); background: var(--surface); }
        .hero-stats {
            display: flex; justify-content: center; gap: 40px;
            margin-top: 56px; flex-wrap: wrap;
        }
        .hero-stat { text-align: center; }
        .hero-stat-num { font-size: 28px; font-weight: 800; color: var(--text); letter-spacing: -1px; }
        .hero-stat-label { font-size: 12px; color: var(--text3); margin-top: 2px; }

        /* ── SECTION LABELS ── */
        .section-label {
            display: inline-flex; align-items: center; gap: 6px;
            background: var(--accent-bg); border: 1px solid var(--accent-border);
            border-radius: 99px; padding: 4px 14px;
            font-size: 11px; font-weight: 700; color: var(--accent);
            letter-spacing: .08em; text-transform: uppercase;
            margin-bottom: 16px;
        }
        .section-title { font-size: clamp(26px, 4vw, 40px); font-weight: 800; color: var(--text); letter-spacing: -1px; line-height: 1.15; margin-bottom: 14px; }
        .section-sub   { font-size: 16px; color: var(--text2); line-height: 1.6; max-width: 520px; }
        .text-center   { text-align: center; }
        .section-sub.center { margin: 0 auto; }

        /* ── PROBLEM ── */
        .problem-section { background: var(--surface); border-top: 1px solid var(--border); border-bottom: 1px solid var(--border); }
        .problem-grid { display: grid; grid-template-columns: repeat(2,1fr); gap: 16px; margin-top: 48px; }
        @media(min-width:768px){ .problem-grid { grid-template-columns: repeat(4,1fr); } }
        .problem-card {
            background: var(--bg); border: 1px solid var(--border2);
            border-radius: 14px; padding: 20px;
        }
        .problem-icon { font-size: 28px; margin-bottom: 12px; }
        .problem-card h4 { font-size: 14px; font-weight: 700; color: var(--text); margin-bottom: 6px; }
        .problem-card p  { font-size: 13px; color: var(--text3); line-height: 1.5; }

        /* ── HOW IT WORKS ── */
        .steps-grid { display: grid; grid-template-columns: 1fr; gap: 0; margin-top: 56px; position: relative; }
        @media(min-width:768px) { .steps-grid { grid-template-columns: repeat(3,1fr); } }
        .step-card { padding: 32px; position: relative; }
        .step-card:not(:last-child)::after {
            content: '→'; position: absolute;
            right: -12px; top: 36px;
            font-size: 20px; color: var(--text3);
            display: none;
        }
        @media(min-width:768px){ .step-card:not(:last-child)::after { display: block; } }
        .step-num {
            width: 44px; height: 44px; border-radius: 12px;
            background: var(--accent-bg); border: 1px solid var(--accent-border);
            display: flex; align-items: center; justify-content: center;
            font-size: 18px; font-weight: 800; color: var(--accent);
            margin-bottom: 16px;
        }
        .step-card h3 { font-size: 17px; font-weight: 700; color: var(--text); margin-bottom: 8px; }
        .step-card p  { font-size: 14px; color: var(--text2); line-height: 1.6; }

        /* ── FEATURES ── */
        .features-grid { display: grid; grid-template-columns: 1fr; gap: 16px; margin-top: 56px; }
        @media(min-width:640px)  { .features-grid { grid-template-columns: repeat(2,1fr); } }
        @media(min-width:1024px) { .features-grid { grid-template-columns: repeat(3,1fr); } }
        .feature-card {
            background: var(--surface); border: 1px solid var(--border);
            border-radius: 16px; padding: 24px;
            transition: border-color .2s;
        }
        .feature-card:hover { border-color: var(--border2); }
        .feature-icon {
            width: 44px; height: 44px; border-radius: 12px;
            background: var(--surface2); border: 1px solid var(--border2);
            display: flex; align-items: center; justify-content: center;
            font-size: 20px; margin-bottom: 14px;
        }
        .feature-card h3 { font-size: 15px; font-weight: 700; color: var(--text); margin-bottom: 6px; }
        .feature-card p  { font-size: 13px; color: var(--text2); line-height: 1.55; }

        /* ── PRICING ── */
        .pricing-section { background: var(--surface); border-top: 1px solid var(--border); border-bottom: 1px solid var(--border); }
        .pricing-grid { display: grid; grid-template-columns: 1fr; gap: 20px; margin-top: 56px; }
        @media(min-width:640px) { .pricing-grid { grid-template-columns: repeat(3,1fr); } }
        .pricing-card {
            background: var(--bg); border: 2px solid var(--border);
            border-radius: 20px; padding: 28px; display: flex; flex-direction: column;
            transition: border-color .2s;
        }
        .pricing-card:hover { border-color: var(--border2); }
        .pricing-card.popular { border-color: var(--accent); position: relative; }
        .popular-badge {
            position: absolute; top: -13px; left: 50%; transform: translateX(-50%);
            background: var(--accent); color: #fff; font-size: 10px; font-weight: 800;
            padding: 4px 14px; border-radius: 99px; letter-spacing: .08em;
            text-transform: uppercase; white-space: nowrap;
        }
        .plan-name  { font-size: 14px; font-weight: 700; color: var(--text2); text-transform: uppercase; letter-spacing: .08em; margin-bottom: 12px; }
        .plan-price { font-size: 36px; font-weight: 800; color: var(--text); letter-spacing: -1px; margin-bottom: 4px; }
        .plan-price span { font-size: 14px; font-weight: 400; color: var(--text3); }
        .plan-desc  { font-size: 13px; color: var(--text3); margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px solid var(--border); }
        .plan-features { list-style: none; flex: 1; margin-bottom: 24px; }
        .plan-features li { display: flex; align-items: center; gap: 8px; font-size: 13px; color: var(--text2); padding: 7px 0; border-bottom: 1px solid var(--border); }
        .plan-features li:last-child { border: none; }
        .plan-features li::before { content: '✓'; color: var(--accent); font-weight: 700; flex-shrink: 0; }
        .plan-btn {
            display: block; text-align: center; padding: 12px;
            border-radius: 10px; font-size: 14px; font-weight: 700;
            text-decoration: none; transition: all .2s;
        }
        .plan-btn-outline { background: none; border: 1px solid var(--border2); color: var(--text2); }
        .plan-btn-outline:hover { border-color: var(--accent); color: var(--accent); }
        .plan-btn-filled  { background: var(--accent); color: #fff; border: none; }
        .plan-btn-filled:hover { background: var(--accent2); }

        /* ── TESTIMONIALS ── */
        .testimonials-grid { display: grid; grid-template-columns: 1fr; gap: 16px; margin-top: 48px; }
        @media(min-width:640px)  { .testimonials-grid { grid-template-columns: repeat(2,1fr); } }
        @media(min-width:1024px) { .testimonials-grid { grid-template-columns: repeat(3,1fr); } }
        .testimonial-card {
            background: var(--surface); border: 1px solid var(--border);
            border-radius: 16px; padding: 22px;
        }
        .testimonial-stars { color: #f59e0b; font-size: 14px; margin-bottom: 10px; }
        .testimonial-text  { font-size: 14px; color: var(--text2); line-height: 1.6; margin-bottom: 16px; font-style: italic; }
        .testimonial-author { display: flex; align-items: center; gap: 10px; }
        .author-avatar {
            width: 36px; height: 36px; border-radius: 99px;
            background: linear-gradient(135deg,#e8a23a,#e8502a);
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 14px; color: #fff; flex-shrink: 0;
        }
        .author-name { font-size: 13px; font-weight: 600; color: var(--text); }
        .author-role { font-size: 11px; color: var(--text3); }

        /* ── FAQ ── */
        .faq-list { margin-top: 48px; max-width: 720px; margin-left: auto; margin-right: auto; }
        .faq-item { border-bottom: 1px solid var(--border); }
        .faq-question {
            width: 100%; background: none; border: none; cursor: pointer;
            display: flex; align-items: center; justify-content: space-between;
            padding: 18px 0; text-align: left;
            font-size: 15px; font-weight: 600; color: var(--text);
            gap: 16px;
        }
        .faq-question svg { width: 18px; height: 18px; color: var(--text3); flex-shrink: 0; transition: transform .25s; }
        .faq-question.open svg { transform: rotate(45deg); }
        .faq-answer { max-height: 0; overflow: hidden; transition: max-height .3s ease; }
        .faq-answer.open { max-height: 300px; }
        .faq-answer p { padding-bottom: 18px; font-size: 14px; color: var(--text2); line-height: 1.65; }

        /* ── CTA BANNER ── */
        .cta-banner {
            background: linear-gradient(135deg, var(--surface) 0%, #1a0f0a 100%);
            border: 1px solid var(--border);
            border-radius: 24px; padding: 56px 40px;
            text-align: center; position: relative; overflow: hidden;
            margin: 0 24px;
        }
        .cta-glow {
            position: absolute; width: 400px; height: 400px; border-radius: 50%;
            background: radial-gradient(circle, rgba(232,80,42,.08) 0%, transparent 65%);
            top: -100px; left: 50%; transform: translateX(-50%);
            pointer-events: none;
        }
        .cta-banner h2 { font-size: clamp(24px, 4vw, 40px); font-weight: 800; color: var(--text); letter-spacing: -1px; margin-bottom: 14px; position: relative; }
        .cta-banner h2 span { color: var(--accent); }
        .cta-banner p  { font-size: 16px; color: var(--text2); margin-bottom: 32px; position: relative; }

        /* ── FOOTER ── */
        footer {
            background: var(--surface); border-top: 1px solid var(--border);
            padding: 48px 24px 32px;
        }
        .footer-inner { max-width: var(--max); margin: 0 auto; }
        .footer-top { display: grid; grid-template-columns: 1fr; gap: 32px; margin-bottom: 40px; }
        @media(min-width:640px) { .footer-top { grid-template-columns: 2fr 1fr 1fr 1fr; } }
        .footer-brand p  { font-size: 13px; color: var(--text3); line-height: 1.6; margin-top: 10px; max-width: 220px; }
        .footer-col h4   { font-size: 12px; font-weight: 700; color: var(--text2); text-transform: uppercase; letter-spacing: .08em; margin-bottom: 14px; }
        .footer-col a    { display: block; font-size: 13px; color: var(--text3); text-decoration: none; margin-bottom: 8px; transition: color .2s; }
        .footer-col a:hover { color: var(--text2); }
        .footer-bottom { border-top: 1px solid var(--border); padding-top: 24px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; }
        .footer-bottom p { font-size: 12px; color: var(--text3); }

        /* ── SCROLL ANIMATION ── */
        .fade-up { opacity: 0; transform: translateY(24px); transition: opacity .6s, transform .6s; }
        .fade-up.visible { opacity: 1; transform: translateY(0); }

        /* ── RESPONSIVE HELPERS ── */
        @media(max-width:640px) {
            section { padding: 64px 20px; }
            .hero-stats { gap: 24px; }
            .cta-banner { padding: 36px 24px; margin: 0 16px; }
        }
    </style>
</head>
<body>

{{-- ══════════════════════════════════════════
     NAVBAR
══════════════════════════════════════════ --}}
<nav class="navbar">
    <div class="nav-inner">
        <a href="/" class="nav-logo">
            <div class="nav-logo-icon">🍽</div>
            <span class="nav-logo-name">QR Menu</span>
        </a>

        <div class="nav-links">
            <a href="#how-it-works" class="en-text">How it Works</a>
            <a href="#features"     class="en-text">Features</a>
            <a href="#pricing"      class="en-text">Pricing</a>
            <a href="#faq"          class="en-text">FAQ</a>
            <a href="#how-it-works" class="ur-text" style="display:none;">یہ کیسے کام کرتا ہے</a>
            <a href="#features"     class="ur-text" style="display:none;">خصوصیات</a>
            <a href="#pricing"      class="ur-text" style="display:none;">قیمتیں</a>
            <a href="#faq"          class="ur-text" style="display:none;">سوالات</a>
        </div>

        <div class="nav-cta">
            <div class="lang-toggle">
                <button class="lang-btn active" id="btn-en" onclick="setLang('en')">EN</button>
                <button class="lang-btn"         id="btn-ur" onclick="setLang('ur')">اردو</button>
            </div>
            <a href="/login"    class="btn-ghost en-text">Login</a>
            <a href="/register" class="btn-accent en-text">Get Started</a>
            <a href="/login"    class="btn-ghost ur-text" style="display:none;">لاگ ان</a>
            <a href="/register" class="btn-accent ur-text" style="display:none;">شروع کریں</a>
        </div>

        <button class="hamburger" onclick="toggleMenu()">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
    </div>
</nav>

<div class="mobile-menu" id="mobileMenu">
    <a href="#how-it-works">How it Works / یہ کیسے کام کرتا ہے</a>
    <a href="#features">Features / خصوصیات</a>
    <a href="#pricing">Pricing / قیمتیں</a>
    <a href="#faq">FAQ / سوالات</a>
    <a href="/login">Login / لاگ ان</a>
    <a href="/register" style="color:var(--accent);font-weight:700;">Get Started / شروع کریں</a>
</div>

{{-- ══════════════════════════════════════════
     HERO
══════════════════════════════════════════ --}}
<section class="hero">
    <div class="hero-glow-1"></div>
    <div class="hero-glow-2"></div>
    <div class="hero-inner">

        <div class="hero-badge">
            <div class="hero-badge-dot"></div>
            <span class="en-text">🇵🇰 Built for Pakistani Restaurants</span>
            <span class="ur-text" style="display:none;">🇵🇰 پاکستانی ریسٹورانٹس کے لیے</span>
        </div>

        <h1 class="hero-title en-text">
            Your Restaurant Menu,<br><span>Always Up to Date.</span>
        </h1>
        <h1 class="hero-title ur-text" style="display:none;">
            آپ کا ریسٹورانٹ مینو،<br><span>ہمیشہ تازہ ترین۔</span>
        </h1>

        <p class="hero-subtitle en-text">
            Customers scan a QR code and instantly see your full digital menu — no app needed.
            Update prices, add dishes, and track scans in real time.
        </p>
        <p class="hero-subtitle ur-text" style="display:none;">
            کسٹمر QR کوڈ اسکین کریں اور فوری طور پر آپ کا مکمل ڈیجیٹل مینو دیکھیں — کوئی ایپ ضروری نہیں۔
            قیمتیں اپ ڈیٹ کریں، ڈشز شامل کریں اور اسکین ریئل ٹائم میں ٹریک کریں۔
        </p>

        <div class="hero-buttons">
            <a href="/register" class="btn-primary-lg en-text">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Start Free Today
            </a>
            <a href="/register" class="btn-primary-lg ur-text" style="display:none;">
                آج مفت شروع کریں
            </a>
            <a href="#how-it-works" class="btn-secondary-lg en-text">See How it Works</a>
            <a href="#how-it-works" class="btn-secondary-lg ur-text" style="display:none;">دیکھیں کیسے کام کرتا ہے</a>
        </div>

        <div class="hero-stats">
            <div class="hero-stat">
                <div class="hero-stat-num">500+</div>
                <div class="hero-stat-label en-text">Restaurants</div>
                <div class="hero-stat-label ur-text" style="display:none;">ریسٹورانٹس</div>
            </div>
            <div class="hero-stat">
                <div class="hero-stat-num">50k+</div>
                <div class="hero-stat-label en-text">Menu Scans</div>
                <div class="hero-stat-label ur-text" style="display:none;">مینو اسکین</div>
            </div>
            <div class="hero-stat">
                <div class="hero-stat-num">Rs.0</div>
                <div class="hero-stat-label en-text">To Get Started</div>
                <div class="hero-stat-label ur-text" style="display:none;">شروع کرنے کے لیے</div>
            </div>
            <div class="hero-stat">
                <div class="hero-stat-num">24/7</div>
                <div class="hero-stat-label en-text">Menu Available</div>
                <div class="hero-stat-label ur-text" style="display:none;">مینو دستیاب</div>
            </div>
        </div>

    </div>
</section>

{{-- ══════════════════════════════════════════
     PROBLEM SECTION
══════════════════════════════════════════ --}}
<section class="problem-section">
    <div class="container text-center fade-up">
        <div class="section-label">❌ <span class="en-text">The Problem</span><span class="ur-text" style="display:none;">مسئلہ</span></div>
        <h2 class="section-title en-text">Printed menus are costing you money</h2>
        <h2 class="section-title ur-text" style="display:none;">پرنٹڈ مینو آپ کا پیسہ ضائع کر رہے ہیں</h2>
        <p class="section-sub center en-text">Every time prices change, you reprint. Every time a dish runs out, customers order it. Here's what restaurants lose with paper menus.</p>
        <p class="section-sub center ur-text" style="display:none;">جب بھی قیمتیں بدلتی ہیں، آپ دوبارہ پرنٹ کرتے ہیں۔ جب بھی کوئی ڈش ختم ہوتی ہے، کسٹمر اسے آرڈر کرتے ہیں۔</p>
    </div>
    <div class="container">
        <div class="problem-grid">
            @foreach([
                ['icon'=>'💸','en_title'=>'Expensive Reprinting','en_desc'=>'Every price change means paying for new menus. Costs add up fast.','ur_title'=>'مہنگا دوبارہ پرنٹ','ur_desc'=>'ہر قیمت تبدیلی پر نئے مینو پرنٹ کرنے کے اخراجات۔'],
                ['icon'=>'⏰','en_title'=>'Outdated Information','en_desc'=>'Customers see old prices and unavailable dishes, causing confusion.','ur_title'=>'پرانی معلومات','ur_desc'=>'کسٹمر پرانی قیمتیں اور ناموجود ڈشز دیکھتے ہیں۔'],
                ['icon'=>'📉','en_title'=>'No Analytics','en_desc'=>'You have no idea which dishes are most popular or when customers visit.','ur_title'=>'کوئی تجزیہ نہیں','ur_desc'=>'آپ کو نہیں پتہ کون سی ڈش مقبول ہے یا کسٹمر کب آتے ہیں۔'],
                ['icon'=>'😓','en_title'=>'Hard to Manage','en_desc'=>'Multiple branches mean multiple menus, all out of sync.','ur_title'=>'مشکل انتظام','ur_desc'=>'متعدد برانچوں کے لیے متعدد مینو، سب غیر ہم آہنگ۔'],
            ] as $p)
            <div class="problem-card fade-up">
                <div class="problem-icon">{{ $p['icon'] }}</div>
                <h4 class="en-text">{{ $p['en_title'] }}</h4>
                <h4 class="ur-text" style="display:none;">{{ $p['ur_title'] }}</h4>
                <p class="en-text">{{ $p['en_desc'] }}</p>
                <p class="ur-text" style="display:none;">{{ $p['ur_desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════
     HOW IT WORKS
══════════════════════════════════════════ --}}
<section id="how-it-works">
    <div class="container text-center fade-up">
        <div class="section-label">✨ <span class="en-text">How It Works</span><span class="ur-text" style="display:none;">یہ کیسے کام کرتا ہے</span></div>
        <h2 class="section-title en-text">Live in 3 simple steps</h2>
        <h2 class="section-title ur-text" style="display:none;">3 آسان مراحل میں لائیو</h2>
    </div>
    <div class="container">
        <div class="steps-grid">
            @foreach([
                ['n'=>'1','en_h'=>'Register & Add Menu','en_p'=>'Sign up, add your categories and products with images and prices. Takes less than 30 minutes.','ur_h'=>'رجسٹر کریں اور مینو شامل کریں','ur_p'=>'سائن اپ کریں، اپنی کیٹیگریز اور پروڈکٹس شامل کریں۔ 30 منٹ سے کم وقت لگتا ہے۔'],
                ['n'=>'2','en_h'=>'Generate QR Code','en_p'=>'Generate a QR code for your restaurant, branch, or table. Download and print it anywhere.','ur_h'=>'QR کوڈ بنائیں','ur_p'=>'اپنے ریسٹورانٹ، برانچ یا ٹیبل کے لیے QR کوڈ بنائیں۔ کہیں بھی پرنٹ کریں۔'],
                ['n'=>'3','en_h'=>'Customers Scan & Order','en_p'=>'Customers point their phone camera at the QR. Instantly see your beautiful digital menu. No app needed.','ur_h'=>'کسٹمر اسکین کریں','ur_p'=>'کسٹمر فون کیمرہ QR پر پوائنٹ کریں۔ فوری طور پر خوبصورت ڈیجیٹل مینو دیکھیں۔'],
            ] as $s)
            <div class="step-card fade-up">
                <div class="step-num">{{ $s['n'] }}</div>
                <h3 class="en-text">{{ $s['en_h'] }}</h3>
                <h3 class="ur-text" style="display:none;">{{ $s['ur_h'] }}</h3>
                <p class="en-text">{{ $s['en_p'] }}</p>
                <p class="ur-text" style="display:none;">{{ $s['ur_p'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════
     FEATURES
══════════════════════════════════════════ --}}
<section id="features" style="background:var(--surface);border-top:1px solid var(--border);border-bottom:1px solid var(--border);">
    <div class="container text-center fade-up">
        <div class="section-label">⚡ <span class="en-text">Features</span><span class="ur-text" style="display:none;">خصوصیات</span></div>
        <h2 class="section-title en-text">Everything your restaurant needs</h2>
        <h2 class="section-title ur-text" style="display:none;">آپ کے ریسٹورانٹ کو جو کچھ چاہیے</h2>
    </div>
    <div class="container">
        <div class="features-grid">
            @foreach([
                ['icon'=>'📱','en_h'=>'Mobile-First Menu','en_p'=>'Beautiful, fast menu that works perfectly on any phone. No app download needed.','ur_h'=>'موبائل فرسٹ مینو','ur_p'=>'خوبصورت، تیز مینو جو کسی بھی فون پر کام کرتا ہے۔'],
                ['icon'=>'✏️','en_h'=>'Instant Updates','en_p'=>'Change prices, add items, mark dishes unavailable — changes go live in seconds.','ur_h'=>'فوری اپ ڈیٹس','ur_p'=>'قیمتیں تبدیل کریں، آئٹمز شامل کریں — تبدیلیاں فوری لائیو ہوتی ہیں۔'],
                ['icon'=>'📊','en_h'=>'Scan Analytics','en_p'=>'See daily scan counts, most popular QR codes, and weekly trends in your dashboard.','ur_h'=>'اسکین تجزیہ','ur_p'=>'روزانہ اسکین، مقبول QR کوڈز اور ہفتہ وار رجحانات دیکھیں۔'],
                ['icon'=>'🏢','en_h'=>'Multi-Branch Support','en_p'=>'Manage all your branches from one dashboard. Each branch gets its own QR code.','ur_h'=>'کثیر برانچ سپورٹ','ur_p'=>'ایک ڈیش بورڈ سے تمام برانچیں مینیج کریں۔'],
                ['icon'=>'🔀','en_h'=>'Product Variants','en_p'=>'Set different prices for Small, Medium, Large or any custom size/option.','ur_h'=>'پروڈکٹ ویریئنٹس','ur_p'=>'Small، Medium، Large یا کسی بھی سائز کے لیے الگ قیمتیں۔'],
                ['icon'=>'🔐','en_h'=>'Secure & Reliable','en_p'=>'Each restaurant gets its own secure login. Data is fully isolated between restaurants.','ur_h'=>'محفوظ اور قابل اعتماد','ur_p'=>'ہر ریسٹورانٹ کا اپنا محفوظ لاگ ان۔ ڈیٹا مکمل طور پر الگ۔'],
                ['icon'=>'📲','en_h'=>'QR Code Generator','en_p'=>'Generate, download and print QR codes for your restaurant, branches, or individual tables.','ur_h'=>'QR کوڈ جنریٹر','ur_p'=>'ریسٹورانٹ، برانچ یا ٹیبل کے لیے QR کوڈ بنائیں اور پرنٹ کریں۔'],
                ['icon'=>'👥','en_h'=>'Staff Management','en_p'=>'Add staff accounts with limited access. Keep your menu management organized.','ur_h'=>'اسٹاف مینجمنٹ','ur_p'=>'محدود رسائی کے ساتھ اسٹاف اکاؤنٹس شامل کریں۔'],
                ['icon'=>'💳','en_h'=>'Simple Billing','en_p'=>'Submit payment via bank transfer. Admin approves and your plan activates instantly.','ur_h'=>'آسان بلنگ','ur_p'=>'بینک ٹرانسفر سے ادائیگی جمع کریں۔ ایڈمن منظور کرے اور پلان فوری فعال ہو۔'],
            ] as $f)
            <div class="feature-card fade-up">
                <div class="feature-icon">{{ $f['icon'] }}</div>
                <h3 class="en-text">{{ $f['en_h'] }}</h3>
                <h3 class="ur-text" style="display:none;">{{ $f['ur_h'] }}</h3>
                <p class="en-text">{{ $f['en_p'] }}</p>
                <p class="ur-text" style="display:none;">{{ $f['ur_p'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════
     PRICING
══════════════════════════════════════════ --}}
<section id="pricing" class="pricing-section">
    <div class="container text-center fade-up">
        <div class="section-label">💳 <span class="en-text">Pricing</span><span class="ur-text" style="display:none;">قیمتیں</span></div>
        <h2 class="section-title en-text">Simple, transparent pricing</h2>
        <h2 class="section-title ur-text" style="display:none;">سادہ، شفاف قیمتیں</h2>
        <p class="section-sub center en-text">Start free. Upgrade when you're ready. No hidden fees, no contracts.</p>
        <p class="section-sub center ur-text" style="display:none;">مفت شروع کریں۔ جب تیار ہوں اپ گریڈ کریں۔ کوئی پوشیدہ فیس نہیں۔</p>
    </div>
    <div class="container">
        <div class="pricing-grid">

            {{-- Free --}}
            <div class="pricing-card fade-up">
                <p class="plan-name en-text">Free</p>
                <p class="plan-name ur-text" style="display:none;">مفت</p>
                <p class="plan-price">Rs.0 <span class="en-text">/ month</span><span class="ur-text" style="display:none;">/ مہینہ</span></p>
                <p class="plan-desc en-text">Perfect to get started</p>
                <p class="plan-desc ur-text" style="display:none;">شروع کرنے کے لیے بہترین</p>
                <ul class="plan-features">
                    <li class="en-text">10 Products</li><li class="ur-text" style="display:none;">10 پروڈکٹس</li>
                    <li class="en-text">1 QR Code</li><li class="ur-text" style="display:none;">1 QR کوڈ</li>
                    <li class="en-text">1 Branch</li><li class="ur-text" style="display:none;">1 برانچ</li>
                    <li class="en-text">Basic Analytics</li><li class="ur-text" style="display:none;">بنیادی تجزیہ</li>
                </ul>
                <a href="/register" class="plan-btn plan-btn-outline en-text">Get Started Free</a>
                <a href="/register" class="plan-btn plan-btn-outline ur-text" style="display:none;">مفت شروع کریں</a>
            </div>

            {{-- Basic --}}
            <div class="pricing-card popular fade-up">
                <div class="popular-badge en-text">MOST POPULAR</div>
                <div class="popular-badge ur-text" style="display:none;">سب سے مقبول</div>
                <p class="plan-name en-text">Basic</p>
                <p class="plan-name ur-text" style="display:none;">بیسک</p>
                <p class="plan-price">Rs.1,999 <span class="en-text">/ 30 days</span><span class="ur-text" style="display:none;">/ 30 دن</span></p>
                <p class="plan-desc en-text">For growing restaurants</p>
                <p class="plan-desc ur-text" style="display:none;">بڑھتے ریسٹورانٹس کے لیے</p>
                <ul class="plan-features">
                    <li class="en-text">50 Products</li><li class="ur-text" style="display:none;">50 پروڈکٹس</li>
                    <li class="en-text">5 QR Codes</li><li class="ur-text" style="display:none;">5 QR کوڈز</li>
                    <li class="en-text">2 Branches</li><li class="ur-text" style="display:none;">2 برانچیں</li>
                    <li class="en-text">Full Analytics</li><li class="ur-text" style="display:none;">مکمل تجزیہ</li>
                    <li class="en-text">Staff Accounts</li><li class="ur-text" style="display:none;">اسٹاف اکاؤنٹس</li>
                </ul>
                <a href="/register" class="plan-btn plan-btn-filled en-text">Get Basic</a>
                <a href="/register" class="plan-btn plan-btn-filled ur-text" style="display:none;">بیسک لیں</a>
            </div>

            {{-- Pro --}}
            <div class="pricing-card fade-up">
                <p class="plan-name en-text">Pro</p>
                <p class="plan-name ur-text" style="display:none;">پرو</p>
                <p class="plan-price">Rs.4,999 <span class="en-text">/ 30 days</span><span class="ur-text" style="display:none;">/ 30 دن</span></p>
                <p class="plan-desc en-text">For established restaurants</p>
                <p class="plan-desc ur-text" style="display:none;">قائم ریسٹورانٹس کے لیے</p>
                <ul class="plan-features">
                    <li class="en-text">Unlimited Products</li><li class="ur-text" style="display:none;">لامحدود پروڈکٹس</li>
                    <li class="en-text">20 QR Codes</li><li class="ur-text" style="display:none;">20 QR کوڈز</li>
                    <li class="en-text">10 Branches</li><li class="ur-text" style="display:none;">10 برانچیں</li>
                    <li class="en-text">Advanced Analytics</li><li class="ur-text" style="display:none;">ایڈوانس تجزیہ</li>
                    <li class="en-text">Priority Support</li><li class="ur-text" style="display:none;">ترجیحی سپورٹ</li>
                </ul>
                <a href="/register" class="plan-btn plan-btn-outline en-text">Get Pro</a>
                <a href="/register" class="plan-btn plan-btn-outline ur-text" style="display:none;">پرو لیں</a>
            </div>

        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════
     TESTIMONIALS
══════════════════════════════════════════ --}}
<section>
    <div class="container text-center fade-up">
        <div class="section-label">⭐ <span class="en-text">Testimonials</span><span class="ur-text" style="display:none;">تجربات</span></div>
        <h2 class="section-title en-text">Loved by restaurants across Pakistan</h2>
        <h2 class="section-title ur-text" style="display:none;">پاکستان بھر کے ریسٹورانٹس کا پسندیدہ</h2>
    </div>
    <div class="container">
        <div class="testimonials-grid">
            @foreach([
                ['name'=>'Ahmed Khan','role_en'=>'Owner, Kababjees Karachi','role_ur'=>'مالک، کبابجیز کراچی','en'=>'We switched to QR Menu and our customers love it. No more confusing over outdated prices. Saved us thousands on printing.','ur'=>'ہم نے QR Menu پر سوئچ کیا اور ہمارے کسٹمر اسے پسند کرتے ہیں۔ پرنٹنگ پر ہزاروں روپے بچائے۔'],
                ['name'=>'Fatima Malik','role_en'=>'Manager, Pizza Hub Lahore','role_ur'=>'مینیجر، پیزا ہب لاہور','en'=>'Setting up took less than an hour. Our menu looks amazing on phone screens. The analytics are very helpful.','ur'=>'سیٹ اپ میں ایک گھنٹے سے کم وقت لگا۔ ہمارا مینو فون اسکرین پر شاندار لگتا ہے۔'],
                ['name'=>'Usman Shah','role_en'=>'CEO, Burger Factory Islamabad','role_ur'=>'سی ای او، برگر فیکٹری اسلام آباد','en'=>'Managing 3 branches from one dashboard is a game changer. QR codes for each table was exactly what we needed.','ur'=>'ایک ڈیش بورڈ سے 3 برانچیں مینیج کرنا گیم چینجر ہے۔ ہر ٹیبل کے لیے QR کوڈز بالکل وہی تھے جو ہمیں چاہیے تھا۔'],
            ] as $t)
            <div class="testimonial-card fade-up">
                <div class="testimonial-stars">★★★★★</div>
                <p class="testimonial-text en-text">"{{ $t['en'] }}"</p>
                <p class="testimonial-text ur-text" style="display:none;">"{{ $t['ur'] }}"</p>
                <div class="testimonial-author">
                    <div class="author-avatar">{{ strtoupper(substr($t['name'],0,1)) }}</div>
                    <div>
                        <p class="author-name">{{ $t['name'] }}</p>
                        <p class="author-role en-text">{{ $t['role_en'] }}</p>
                        <p class="author-role ur-text" style="display:none;">{{ $t['role_ur'] }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════
     FAQ
══════════════════════════════════════════ --}}
<section id="faq" style="background:var(--surface);border-top:1px solid var(--border);">
    <div class="container text-center fade-up">
        <div class="section-label">❓ FAQ</div>
        <h2 class="section-title en-text">Frequently Asked Questions</h2>
        <h2 class="section-title ur-text" style="display:none;">اکثر پوچھے جانے والے سوالات</h2>
    </div>
    <div class="container">
        <div class="faq-list">
            @foreach([
                ['en_q'=>'Do customers need to download an app?','en_a'=>'No. Customers simply point their phone camera at the QR code. The menu opens instantly in their browser — no app download required.','ur_q'=>'کیا کسٹمر کو ایپ ڈاؤن لوڈ کرنی ہوگی؟','ur_a'=>'نہیں۔ کسٹمر صرف اپنا فون کیمرہ QR کوڈ پر پوائنٹ کریں۔ مینو فوری طور پر ان کے براؤزر میں کھل جاتا ہے۔'],
                ['en_q'=>'Can I update my menu anytime?','en_a'=>'Yes. You can update prices, add or remove items, and mark dishes as unavailable instantly. Changes go live immediately.','ur_q'=>'کیا میں اپنا مینو کسی بھی وقت اپ ڈیٹ کر سکتا ہوں؟','ur_a'=>'جی ہاں۔ آپ فوری طور پر قیمتیں تبدیل کر سکتے ہیں، آئٹمز شامل یا ہٹا سکتے ہیں۔'],
                ['en_q'=>'What happens if I exceed my plan limit?','en_a'=>'You will see a clear message when you reach your limit. Simply upgrade your plan to add more products, QR codes, or branches.','ur_q'=>'اگر میں اپنے پلان کی حد پار کر لوں تو کیا ہوگا؟','ur_a'=>'جب آپ اپنی حد تک پہنچیں گے تو آپ کو واضح پیغام نظر آئے گا۔ مزید شامل کرنے کے لیے پلان اپ گریڈ کریں۔'],
                ['en_q'=>'How do I pay for a subscription?','en_a'=>'We accept bank transfers (HBL, Meezan, UBL). You submit your payment screenshot and transaction reference. Our team verifies within 24 hours.','ur_q'=>'میں سبسکرپشن کے لیے کیسے ادائیگی کروں؟','ur_a'=>'ہم بینک ٹرانسفر (HBL، میزان، UBL) قبول کرتے ہیں۔ آپ ادائیگی کا اسکرین شاٹ اور ٹرانزیکشن ریفرنس جمع کریں۔ ہماری ٹیم 24 گھنٹوں میں تصدیق کرتی ہے۔'],
                ['en_q'=>'Can I manage multiple branches?','en_a'=>'Yes. The Basic plan supports 2 branches and Pro supports 10. Each branch can have its own QR codes and tables.','ur_q'=>'کیا میں متعدد برانچیں مینیج کر سکتا ہوں؟','ur_a'=>'جی ہاں۔ بیسک پلان 2 برانچیں اور پرو 10 برانچیں سپورٹ کرتا ہے۔'],
                ['en_q'=>'Is my data secure?','en_a'=>'Yes. Each restaurant\'s data is completely isolated. Only your staff can access your dashboard and menu data.','ur_q'=>'کیا میرا ڈیٹا محفوظ ہے؟','ur_a'=>'جی ہاں۔ ہر ریسٹورانٹ کا ڈیٹا مکمل طور پر الگ ہے۔ صرف آپ کا اسٹاف آپ کے ڈیش بورڈ تک رسائی حاصل کر سکتا ہے۔'],
            ] as $i => $faq)
            <div class="faq-item fade-up">
                <button class="faq-question" onclick="toggleFaq({{ $i }}, this)">
                    <span class="en-text">{{ $faq['en_q'] }}</span>
                    <span class="ur-text" style="display:none;">{{ $faq['ur_q'] }}</span>
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </button>
                <div class="faq-answer" id="faq-{{ $i }}">
                    <p class="en-text">{{ $faq['en_a'] }}</p>
                    <p class="ur-text" style="display:none;">{{ $faq['ur_a'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════
     CTA BANNER
══════════════════════════════════════════ --}}
<section style="padding: 80px 0;">
    <div class="cta-banner fade-up">
        <div class="cta-glow"></div>
        <h2 class="en-text">Ready to go <span>digital?</span></h2>
        <h2 class="ur-text" style="display:none;">کیا آپ <span>ڈیجیٹل</span> جانے کے لیے تیار ہیں؟</h2>
        <p class="en-text">Join 500+ Pakistani restaurants already using QR Menu. Start free today.</p>
        <p class="ur-text" style="display:none;">500+ پاکستانی ریسٹورانٹس میں شامل ہوں جو پہلے سے QR Menu استعمال کر رہے ہیں۔ آج مفت شروع کریں۔</p>
        <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap;position:relative;">
            <a href="/register" class="btn-primary-lg en-text">
                🚀 Start Free — No Credit Card
            </a>
            <a href="/register" class="btn-primary-lg ur-text" style="display:none;">
                🚀 مفت شروع کریں — کریڈٹ کارڈ نہیں
            </a>
            <a href="https://wa.me/923001234567" target="_blank"
               style="display:inline-flex;align-items:center;gap:8px;padding:14px 24px;background:#25D366;color:#fff;border-radius:12px;font-size:15px;font-weight:700;text-decoration:none;transition:opacity .2s;"
               onmouseover="this.style.opacity='.85'" onmouseout="this.style.opacity='1'">
                💬 <span class="en-text">WhatsApp Us</span><span class="ur-text" style="display:none;">واٹس ایپ کریں</span>
            </a>
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════
     FOOTER
══════════════════════════════════════════ --}}
<footer>
    <div class="footer-inner">
        <div class="footer-top">
            <div class="footer-brand">
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px;">
                    <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(145deg,#e8a23a,#e8502a);display:flex;align-items:center;justify-content:center;font-size:15px;">🍽</div>
                    <span style="font-size:15px;font-weight:700;color:var(--text);">QR Menu SaaS</span>
                </div>
                <p class="en-text">Digital menu solution built for Pakistani restaurants. Fast, beautiful, always up to date.</p>
                <p class="ur-text" style="display:none;">پاکستانی ریسٹورانٹس کے لیے ڈیجیٹل مینو حل۔ تیز، خوبصورت، ہمیشہ تازہ ترین۔</p>
            </div>
            <div class="footer-col">
                <h4 class="en-text">Product</h4>
                <h4 class="ur-text" style="display:none;">پروڈکٹ</h4>
                <a href="#features">Features</a>
                <a href="#pricing">Pricing</a>
                <a href="#how-it-works">How it Works</a>
            </div>
            <div class="footer-col">
                <h4 class="en-text">Account</h4>
                <h4 class="ur-text" style="display:none;">اکاؤنٹ</h4>
                <a href="/login">Login</a>
                <a href="/register">Register</a>
                <a href="/dashboard">Dashboard</a>
            </div>
            <div class="footer-col">
                <h4 class="en-text">Contact</h4>
                <h4 class="ur-text" style="display:none;">رابطہ</h4>
                <a href="https://wa.me/923001234567">WhatsApp</a>
                <a href="mailto:hello@qrmenu.pk">Email Us</a>
                <a href="#faq">FAQ</a>
            </div>
        </div>
        <div class="footer-bottom">
            <p class="en-text">© {{ date('Y') }} QR Menu SaaS. All rights reserved. 🇵🇰 Made in Pakistan.</p>
            <p class="ur-text" style="display:none;">© {{ date('Y') }} QR Menu SaaS۔ تمام حقوق محفوظ ہیں۔ 🇵🇰 پاکستان میں بنایا گیا۔</p>
            <div style="display:flex;gap:16px;">
                <a href="#" style="font-size:12px;color:var(--text3);text-decoration:none;">Privacy</a>
                <a href="#" style="font-size:12px;color:var(--text3);text-decoration:none;">Terms</a>
            </div>
        </div>
    </div>
</footer>

<script>
// ── Language Toggle ──
function setLang(lang) {
    const isUr = lang === 'ur';
    document.querySelectorAll('.en-text').forEach(el => el.style.display = isUr ? 'none' : '');
    document.querySelectorAll('.ur-text').forEach(el => el.style.display = isUr ? '' : 'none');
    document.getElementById('btn-en').classList.toggle('active', !isUr);
    document.getElementById('btn-ur').classList.toggle('active',  isUr);
    document.getElementById('html-root').setAttribute('lang', isUr ? 'ur' : 'en');
    localStorage.setItem('lang', lang);
}

// Restore saved language
const saved = localStorage.getItem('lang');
if (saved === 'ur') setLang('ur');

// ── Mobile Menu ──
function toggleMenu() {
    document.getElementById('mobileMenu').classList.toggle('open');
}
document.querySelectorAll('.mobile-menu a').forEach(a => {
    a.addEventListener('click', () => document.getElementById('mobileMenu').classList.remove('open'));
});

// ── FAQ ──
function toggleFaq(i, btn) {
    const answer = document.getElementById('faq-' + i);
    const isOpen = answer.classList.contains('open');
    // Close all
    document.querySelectorAll('.faq-answer').forEach(a => a.classList.remove('open'));
    document.querySelectorAll('.faq-question').forEach(b => b.classList.remove('open'));
    // Open clicked if was closed
    if (!isOpen) { answer.classList.add('open'); btn.classList.add('open'); }
}

// ── Scroll Animations ──
const observer = new IntersectionObserver(entries => {
    entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); });
}, { threshold: 0.1 });
document.querySelectorAll('.fade-up').forEach(el => observer.observe(el));

// ── Smooth scroll for nav links ──
document.querySelectorAll('a[href^="#"]').forEach(a => {
    a.addEventListener('click', e => {
        const target = document.querySelector(a.getAttribute('href'));
        if (target) { e.preventDefault(); target.scrollIntoView({ behavior: 'smooth' }); }
    });
});
</script>

</body>
</html>
