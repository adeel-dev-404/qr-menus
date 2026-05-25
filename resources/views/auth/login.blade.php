<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login — {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg:       #080808;
            --surface:  #111111;
            --surface2: #181818;
            --border:   #222222;
            --border2:  #2e2e2e;
            --text:     #f0f0f0;
            --text2:    #888888;
            --text3:    #505050;
            --accent:   #e8502a;
            --accent2:  #c43e1c;
            --error:    #ef4444;
            --error-bg: #2d0a0a;
            --success:  #22c55e;
        }

        html, body {
            min-height: 100vh;
            background: var(--bg);
            color: var(--text);
            font-family: -apple-system, BlinkMacSystemFont, 'SF Pro Display', 'Segoe UI', sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        .page {
            min-height: 100vh;
            display: flex;
        }

        /* ── Left panel (branding) ── */
        .left-panel {
            display: none;
            width: 44%;
            background: var(--surface);
            border-right: 1px solid var(--border);
            flex-direction: column;
            justify-content: space-between;
            padding: 48px 48px 40px;
            position: relative;
            overflow: hidden;
        }
        @media (min-width: 900px) { .left-panel { display: flex; } }

        .left-bg-circle {
            position: absolute;
            width: 500px; height: 500px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(232,80,42,.06) 0%, transparent 70%);
            top: -100px; left: -100px;
            pointer-events: none;
        }
        .left-bg-circle2 {
            position: absolute;
            width: 400px; height: 400px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(232,80,42,.04) 0%, transparent 70%);
            bottom: -80px; right: -80px;
            pointer-events: none;
        }

        .brand {
            display: flex; align-items: center; gap: 12px; position: relative;
        }
        .brand-icon {
            width: 42px; height: 42px; border-radius: 12px;
            background: linear-gradient(145deg, #e8a23a, #e8502a);
            display: flex; align-items: center; justify-content: center;
            font-size: 20px;
        }
        .brand-name {
            font-size: 18px; font-weight: 700; color: var(--text);
            letter-spacing: -.3px;
        }

        .left-content { position: relative; }
        .left-title {
            font-size: 32px; font-weight: 700; color: var(--text);
            letter-spacing: -.5px; line-height: 1.2; margin-bottom: 16px;
        }
        .left-title span { color: var(--accent); }
        .left-desc { font-size: 15px; color: var(--text2); line-height: 1.6; }

        .features { position: relative; }
        .feature-item {
            display: flex; align-items: flex-start; gap: 12px;
            margin-bottom: 16px;
        }
        .feature-icon {
            width: 36px; height: 36px; border-radius: 10px;
            background: var(--surface2); border: 1px solid var(--border2);
            display: flex; align-items: center; justify-content: center;
            font-size: 16px; flex-shrink: 0;
        }
        .feature-text h4 { font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 2px; }
        .feature-text p  { font-size: 12px; color: var(--text3); line-height: 1.4; }

        .left-footer { font-size: 11px; color: var(--text3); position: relative; }

        /* ── Right panel (form) ── */
        .right-panel {
            flex: 1;
            display: flex; align-items: center; justify-content: center;
            padding: 32px 20px;
        }

        .form-box {
            width: 100%; max-width: 400px;
        }

        /* Mobile brand */
        .mobile-brand {
            display: flex; align-items: center; gap: 10px;
            justify-content: center; margin-bottom: 32px;
        }
        @media (min-width: 900px) { .mobile-brand { display: none; } }
        .mobile-brand-icon {
            width: 38px; height: 38px; border-radius: 10px;
            background: linear-gradient(145deg, #e8a23a, #e8502a);
            display: flex; align-items: center; justify-content: center;
            font-size: 18px;
        }
        .mobile-brand-name { font-size: 17px; font-weight: 700; color: var(--text); }

        .form-title {
            font-size: 22px; font-weight: 700; color: var(--text);
            letter-spacing: -.4px; margin-bottom: 6px;
        }
        .form-subtitle { font-size: 14px; color: var(--text2); margin-bottom: 28px; }

        /* Error box */
        .error-box {
            background: var(--error-bg); border: 1px solid rgba(239,68,68,.3);
            border-radius: 10px; padding: 12px 14px; margin-bottom: 20px;
        }
        .error-box p { font-size: 13px; color: #fca5a5; line-height: 1.5; }

        /* Form fields */
        .field { margin-bottom: 16px; }
        .field-label {
            display: block; font-size: 12px; font-weight: 600;
            color: var(--text2); margin-bottom: 6px;
            text-transform: uppercase; letter-spacing: .05em;
        }
        .field-input {
            width: 100%;
            background: var(--surface);
            border: 1px solid var(--border2);
            border-radius: 10px;
            padding: 11px 14px;
            font-size: 14px; color: var(--text);
            outline: none;
            transition: border-color .2s;
            -webkit-appearance: none;
        }
        .field-input:focus { border-color: var(--accent); }
        .field-input.has-error { border-color: var(--error); }
        .field-input::placeholder { color: var(--text3); }
        .field-input:-webkit-autofill {
            -webkit-box-shadow: 0 0 0 100px var(--surface) inset;
            -webkit-text-fill-color: var(--text);
        }

        .field-error { font-size: 11px; color: #fca5a5; margin-top: 4px; }

        /* Password toggle wrapper */
        .pw-wrap { position: relative; }
        .pw-wrap .field-input { padding-right: 42px; }
        .pw-toggle {
            position: absolute; right: 12px; top: 50%;
            transform: translateY(-50%);
            background: none; border: none; cursor: pointer;
            color: var(--text3); padding: 4px;
        }
        .pw-toggle:hover { color: var(--text2); }
        .pw-toggle svg { width: 16px; height: 16px; display: block; }

        /* Remember + forgot row */
        .form-meta {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 20px;
        }
        .remember {
            display: flex; align-items: center; gap: 8px; cursor: pointer;
        }
        .remember input[type="checkbox"] {
            width: 16px; height: 16px; accent-color: var(--accent); cursor: pointer;
        }
        .remember span { font-size: 13px; color: var(--text2); }
        .forgot { font-size: 13px; color: var(--accent); text-decoration: none; }
        .forgot:hover { color: #f97316; }

        /* Submit button */
        .btn-submit {
            width: 100%;
            background: var(--accent);
            color: #fff; border: none;
            border-radius: 10px;
            padding: 13px;
            font-size: 15px; font-weight: 700;
            cursor: pointer;
            transition: background .18s, transform .1s;
            letter-spacing: -.1px;
        }
        .btn-submit:hover  { background: var(--accent2); }
        .btn-submit:active { transform: scale(.98); }

        .form-divider {
            display: flex; align-items: center; gap: 12px;
            margin: 20px 0; color: var(--text3); font-size: 12px;
        }
        .form-divider::before, .form-divider::after {
            content: ''; flex: 1; height: 1px; background: var(--border);
        }

        .link-row {
            text-align: center; font-size: 13px; color: var(--text2);
        }
        .link-row a { color: var(--accent); text-decoration: none; font-weight: 600; }
        .link-row a:hover { color: #f97316; }
    </style>
</head>
<body>
<div class="page">

    {{-- ── Left Branding Panel ── --}}
    <div class="left-panel">
        <div class="left-bg-circle"></div>
        <div class="left-bg-circle2"></div>

        <div class="brand">
            <div class="brand-icon">🍽</div>
            <span class="brand-name">QR Menu SaaS</span>
        </div>

        <div class="left-content">
            <h1 class="left-title">
                Your digital menu,<br><span>always up to date.</span>
            </h1>
            <p class="left-desc">
                Manage your restaurant's menu, QR codes, and analytics from one powerful dashboard.
            </p>
        </div>

        <div class="features">
            <div class="feature-item">
                <div class="feature-icon">📱</div>
                <div class="feature-text">
                    <h4>Instant QR Menus</h4>
                    <p>Customers scan and see your menu in seconds — no app needed.</p>
                </div>
            </div>
            <div class="feature-item">
                <div class="feature-icon">📊</div>
                <div class="feature-text">
                    <h4>Scan Analytics</h4>
                    <p>Track how many customers view your menu daily and weekly.</p>
                </div>
            </div>
            <div class="feature-item">
                <div class="feature-icon">⚡</div>
                <div class="feature-text">
                    <h4>Update in Seconds</h4>
                    <p>Change prices, add items, or hide dishes instantly.</p>
                </div>
            </div>
        </div>

        <div class="left-footer">© {{ date('Y') }} QR Menu SaaS · All rights reserved</div>
    </div>

    {{-- ── Right Form Panel ── --}}
    <div class="right-panel">
        <div class="form-box">

            {{-- Mobile brand --}}
            <div class="mobile-brand">
                <div class="mobile-brand-icon">🍽</div>
                <span class="mobile-brand-name">QR Menu SaaS</span>
            </div>

            <h2 class="form-title">Welcome back</h2>
            <p class="form-subtitle">Sign in to your restaurant dashboard</p>

            {{-- Validation errors --}}
            @if ($errors->any())
            <div class="error-box">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
            @endif

            {{-- Session status --}}
            @if (session('status'))
            <div style="background:#052e16;border:1px solid rgba(34,197,94,.3);border-radius:10px;padding:12px 14px;margin-bottom:20px;">
                <p style="font-size:13px;color:#86efac;">{{ session('status') }}</p>
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="field">
                    <label class="field-label" for="email">Email Address</label>
                    <input id="email" type="email" name="email"
                           value="{{ old('email') }}"
                           class="field-input {{ $errors->has('email') ? 'has-error' : '' }}"
                           placeholder="you@restaurant.com"
                           autofocus autocomplete="email" required>
                    @error('email') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                <div class="field">
                    <label class="field-label" for="password">Password</label>
                    <div class="pw-wrap">
                        <input id="password" type="password" name="password"
                               class="field-input {{ $errors->has('password') ? 'has-error' : '' }}"
                               placeholder="Enter your password"
                               autocomplete="current-password" required>
                        <button type="button" class="pw-toggle" onclick="togglePw('password', this)">
                            <svg id="eye-password" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                    @error('password') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                <div class="form-meta">
                    <label class="remember">
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        <span>Remember me</span>
                    </label>
                    @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="forgot">Forgot password?</a>
                    @endif
                </div>

                <button type="submit" class="btn-submit">Sign In</button>
            </form>

            @if (Route::has('register'))
            <div class="form-divider">or</div>
            <div class="link-row">
                Don't have an account?
                <a href="{{ route('register') }}">Create one</a>
            </div>
            @endif

        </div>
    </div>

</div>

<script>
function togglePw(id, btn) {
    const input = document.getElementById(id);
    const isText = input.type === 'text';
    input.type = isText ? 'password' : 'text';
    btn.querySelector('svg').innerHTML = isText
        ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>'
        : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>';
}
</script>
</body>
</html>