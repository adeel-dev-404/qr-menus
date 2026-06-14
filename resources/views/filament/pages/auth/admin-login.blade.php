<div class="split-layout">
    {{-- Inject custom styles for the split-screen design --}}
    <style>
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --bg-darker:  #050505;
            --bg-dark:    #0a0a0a;
            --surface:    #111111;
            --surface-hover: #161616;
            --border:     #1c1c1c;
            --border-focus: #e8502a;
            --text-primary: #f5f5f5;
            --text-secondary: #888888;
            --text-muted: #4e4e4e;
            --accent:     #e8502a;
            --accent-glow: rgba(232, 80, 42, 0.15);
            --accent-glow-strong: rgba(232, 80, 42, 0.3);
            --gradient-accent: linear-gradient(135deg, #e8502a 0%, #f97316 100%);
            --error:      #ef4444;
            --error-bg:   #1f0b0b;
            --error-border: #441616;
        }

        /* Set full height and reset default margin/background for the wrapper */
        .split-layout {
            min-height: 100vh;
            display: flex;
            width: 100%;
            position: relative;
            background-color: var(--bg-darker);
            color: var(--text-primary);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }

        /* ── Left panel (branding & overview) ── */
        .left-panel {
            display: none;
            width: 44%;
            background: var(--bg-dark);
            border-right: 1px solid var(--border);
            flex-direction: column;
            justify-content: space-between;
            padding: 56px 56px 44px;
            position: relative;
            overflow: hidden;
            flex-shrink: 0;
        }

        @media (min-width: 1024px) {
            .left-panel {
                display: flex;
            }
        }

        /* Ambient background glow effects */
        .ambient-glow-1 {
            position: absolute;
            width: 600px;
            height: 600px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(232, 80, 42, 0.05) 0%, transparent 70%);
            top: -150px;
            left: -150px;
            pointer-events: none;
            animation: drift 20s ease-in-out infinite alternate;
        }

        .ambient-glow-2 {
            position: absolute;
            width: 500px;
            height: 500px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(249, 115, 22, 0.03) 0%, transparent 70%);
            bottom: -100px;
            right: -100px;
            pointer-events: none;
            animation: drift 15s ease-in-out infinite alternate-reverse;
        }

        @keyframes drift {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(30px, 20px) scale(1.05); }
        }

        /* Top Brand Header */
        .brand-section {
            display: flex;
            align-items: center;
            gap: 14px;
            position: relative;
            z-index: 10;
        }

        .brand-logo-container {
            width: 46px;
            height: 46px;
            border-radius: 14px;
            background: linear-gradient(135deg, #e8a23a 0%, #e8502a 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            box-shadow: 0 4px 20px var(--accent-glow-strong);
            animation: pulse-glow 4s ease-in-out infinite;
        }

        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 4px 20px var(--accent-glow-strong); }
            50% { box-shadow: 0 4px 30px rgba(232, 80, 42, 0.5), 0 0 40px rgba(232, 80, 42, 0.15); }
        }

        .brand-title {
            font-size: 20px;
            font-weight: 800;
            color: var(--text-primary);
            letter-spacing: -0.4px;
        }

        /* Center Content */
        .brand-hero-content {
            position: relative;
            z-index: 10;
            margin-top: auto;
            margin-bottom: auto;
            max-width: 460px;
        }

        .hero-heading {
            font-size: 38px;
            font-weight: 900;
            line-height: 1.25;
            letter-spacing: -1px;
            color: var(--text-primary);
            margin-bottom: 20px;
        }

        .hero-heading span {
            background: var(--gradient-accent);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-subheading {
            font-size: 16px;
            color: var(--text-secondary);
            line-height: 1.65;
        }

        /* Bottom Feature Grid */
        .features-list {
            position: relative;
            z-index: 10;
            display: flex;
            flex-direction: column;
            gap: 20px;
            margin-bottom: 40px;
        }

        .feature-row {
            display: flex;
            align-items: flex-start;
            gap: 16px;
        }

        .feature-badge {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: var(--surface);
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
            transition: all 0.3s ease;
        }

        .feature-row:hover .feature-badge {
            border-color: var(--accent);
            background: var(--bg-darker);
            box-shadow: 0 0 12px var(--accent-glow);
            transform: translateY(-2px);
        }

        .feature-details h4 {
            font-size: 14px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 4px;
        }

        .feature-details p {
            font-size: 13px;
            color: var(--text-secondary);
            line-height: 1.45;
        }

        .left-footer-text {
            font-size: 12px;
            color: var(--text-muted);
            position: relative;
            z-index: 10;
            letter-spacing: 0.2px;
        }

        /* ── Right panel (login form container) ── */
        .right-panel {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 24px;
            background: var(--bg-darker);
            position: relative;
        }

        /* Decorative background light for right panel */
        .right-panel::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse 60% 60% at 50% 50%, rgba(232,80,42,0.02), transparent);
            pointer-events: none;
            z-index: 0;
        }

        .form-box {
            width: 100%;
            max-width: 420px;
            position: relative;
            z-index: 10;
        }

        /* Mobile Brand view */
        .mobile-brand-row {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-bottom: 36px;
        }

        @media (min-width: 1024px) {
            .mobile-brand-row {
                display: none;
            }
        }

        .mobile-logo {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: linear-gradient(135deg, #e8a23a 0%, #e8502a 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .mobile-logo-name {
            font-size: 18px;
            font-weight: 800;
            color: var(--text-primary);
        }

        /* Admin Badge indicator */
        .admin-badge-container {
            display: flex;
            justify-content: flex-start;
            margin-bottom: 20px;
        }

        @media (max-width: 1023px) {
            .admin-badge-container {
                justify-content: center;
            }
        }

        .admin-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(232, 80, 42, 0.06);
            border: 1px solid rgba(232, 80, 42, 0.18);
            border-radius: 99px;
            padding: 6px 16px;
        }

        .admin-badge-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background-color: var(--accent);
            animation: dot-pulse 2s infinite;
        }

        @keyframes dot-pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.3; transform: scale(1.5); }
        }

        .admin-badge span {
            font-size: 11px;
            font-weight: 700;
            color: var(--accent);
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .form-heading-title {
            font-size: 26px;
            font-weight: 900;
            color: var(--text-primary);
            letter-spacing: -0.5px;
            margin-bottom: 8px;
        }

        .form-heading-desc {
            font-size: 14px;
            color: var(--text-secondary);
            margin-bottom: 32px;
        }

        @media (max-width: 1023px) {
            .form-heading-title, .form-heading-desc {
                text-align: center;
            }
        }

        /* ── High-Fidelity Override for Filament form elements ── */
        
        /* Form container spacing */
        .fi-form {
            display: grid;
            gap: 22px !important;
        }

        /* Form Labels styling */
        .fi-fo-field-wrp-label label,
        .fi-fo-field-wrp label {
            color: var(--text-secondary) !important;
            font-size: 11px !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.08em !important;
        }

        /* Inputs Override */
        .fi-input,
        .fi-fo-text-input,
        .fi-input-wrp,
        input[type="email"],
        input[type="password"],
        input[type="text"] {
            background-color: var(--surface) !important;
            border: 1px solid var(--border) !important;
            border-radius: 12px !important;
            color: var(--text-primary) !important;
            font-size: 14px !important;
            padding: 12px 14px !important;
            box-shadow: none !important;
            outline: none !important;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }

        /* Input field container overrides (wrapper class in Filament V3) */
        .fi-input-wrp {
            padding: 0 !important; /* Let children padding handle it */
            overflow: hidden;
            display: flex;
            align-items: center;
        }

        .fi-input-wrp input {
            background: transparent !important;
            border: none !important;
            width: 100%;
            box-shadow: none !important;
            padding: 12px 14px !important;
        }

        .fi-input-wrp:focus-within,
        .fi-input:focus,
        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: var(--accent) !important;
            background-color: var(--bg-darker) !important;
            box-shadow: 0 0 0 3px rgba(232, 80, 42, 0.15) !important;
        }

        input::placeholder {
            color: var(--text-muted) !important;
        }

        /* Hide default focus ring from tailwind/filament on anything else */
        *:focus, *:focus-visible, .ring-primary-600, .ring-offset-background {
            outline: none !important;
            box-shadow: none !important;
            --tw-ring-offset-width: 0px !important;
            --tw-ring-color: transparent !important;
        }

        /* Checkbox Override */
        .fi-checkbox,
        input[type="checkbox"] {
            accent-color: var(--accent) !important;
            border-radius: 4px !important;
            border: 1px solid var(--border) !important;
            background-color: var(--surface) !important;
            width: 16px !important;
            height: 16px !important;
            cursor: pointer;
        }

        /* Link Override */
        .fi-link, a {
            color: var(--accent) !important;
            font-size: 13px !important;
            font-weight: 600 !important;
            text-decoration: none !important;
            transition: color 0.2s ease !important;
        }

        .fi-link:hover, a:hover {
            color: #f97316 !important;
        }

        /* Submit Button Override */
        .fi-btn-primary,
        button[type="submit"],
        .fi-btn {
            background: var(--gradient-accent) !important;
            border: none !important;
            border-radius: 12px !important;
            color: #ffffff !important;
            font-size: 15px !important;
            font-weight: 700 !important;
            padding: 14px 24px !important;
            cursor: pointer !important;
            width: 100% !important;
            box-shadow: 0 4px 18px rgba(232, 80, 42, 0.25) !important;
            letter-spacing: -0.1px !important;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }

        .fi-btn-primary:hover,
        button[type="submit"]:hover {
            opacity: 0.95 !important;
            box-shadow: 0 6px 24px rgba(232, 80, 42, 0.4) !important;
            transform: translateY(-1px) !important;
        }

        .fi-btn-primary:active,
        button[type="submit"]:active {
            transform: translateY(0) !important;
            box-shadow: 0 2px 8px rgba(232, 80, 42, 0.2) !important;
        }

        /* Form helpers/errors text */
        .fi-fo-field-wrp-error-message,
        [data-fi-field-error] {
            color: var(--error) !important;
            font-size: 12px !important;
            margin-top: 5px !important;
            font-weight: 500 !important;
        }

        /* Security Glassmorphism Footer */
        .security-footer {
            display: flex;
            align-items: center;
            gap: 12px;
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            padding: 14px 16px;
            margin-top: 28px;
        }

        .security-icon {
            width: 18px;
            height: 18px;
            color: var(--text-muted);
            flex-shrink: 0;
        }

        .security-text {
            font-size: 11px;
            color: var(--text-secondary);
            line-height: 1.5;
            margin: 0;
        }

        /* Back to Site link row */
        .back-link-row {
            text-align: center;
            margin-top: 24px;
        }

        .back-link {
            font-size: 13px;
            color: var(--text-secondary) !important;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: color 0.2s ease !important;
        }

        .back-link:hover {
            color: var(--text-primary) !important;
        }

        .back-link svg {
            width: 14px;
            height: 14px;
            transition: transform 0.2s ease;
        }

        .back-link:hover svg {
            transform: translateX(-3px);
        }
    </style>

    {{-- ── Left Branding Panel ── --}}
    <div class="left-panel">
        <div class="ambient-glow-1"></div>
        <div class="ambient-glow-2"></div>

        <div class="brand-section">
            <div class="brand-logo-container">👑</div>
            <span class="brand-title">QR Menu SaaS</span>
        </div>

        <div class="brand-hero-content">
            <h1 class="hero-heading">
                Control Center<br><span>Platform Admin.</span>
            </h1>
            <p class="hero-subheading">
                Manage restaurant registrations, plans, system metrics, and billing operations from the central command dashboard.
            </p>
        </div>

        <div class="features-list">
            <div class="feature-row">
                <div class="feature-badge">🏪</div>
                <div class="feature-details">
                    <h4>Restaurant Operations</h4>
                    <p>Approve pending accounts, manage branches, and assign roles.</p>
                </div>
            </div>
            <div class="feature-row">
                <div class="feature-badge">💳</div>
                <div class="feature-details">
                    <h4>Billing &amp; Subscriptions</h4>
                    <p>Configure subscription plans, track payments, and manage invoices.</p>
                </div>
            </div>
            <div class="feature-row">
                <div class="feature-badge">📈</div>
                <div class="feature-details">
                    <h4>System Analytics</h4>
                    <p>Monitor system traffic, QR scan metrics, and platform revenue.</p>
                </div>
            </div>
        </div>

        <div class="left-footer-text">
            © {{ date('Y') }} QR Menu SaaS · Admin Suite
        </div>
    </div>

    {{-- ── Right Form Panel ── --}}
    <div class="right-panel">
        <div class="form-box">
            {{-- Mobile Brand Header --}}
            <div class="mobile-brand-row">
                <div class="mobile-logo">👑</div>
                <span class="mobile-logo-name">QR Menu SaaS</span>
            </div>

            {{-- Admin badge indicator --}}
            <div class="admin-badge-container">
                <div class="admin-badge">
                    <div class="admin-badge-dot"></div>
                    <span>Super Admin Portal</span>
                </div>
            </div>

            <h2 class="form-heading-title">Welcome back</h2>
            <p class="form-heading-desc">Sign in to the administration panel</p>

            {{-- Filament login form component (Livewire-powered) --}}
            <x-filament-panels::form wire:submit="authenticate">
                {{ $this->form }}

                <x-filament-panels::form.actions
                    :actions="$this->getCachedFormActions()"
                    :full-width="$this->hasFullWidthFormActions()"
                />
            </x-filament-panels::form>

            {{-- Restricted access notice --}}
            <div class="security-footer">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="security-icon">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                <p class="security-text">
                    Restricted area · Unauthorized access is prohibited and logged
                </p>
            </div>

            {{-- Return to website link --}}
            <div class="back-link-row">
                <a href="{{ url('/') }}" class="back-link">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to main site
                </a>
            </div>
        </div>
    </div>
</div>