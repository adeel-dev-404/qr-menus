<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register — {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --bg:#080808; --surface:#111111; --surface2:#181818;
            --border:#222222; --border2:#2e2e2e;
            --text:#f0f0f0; --text2:#888888; --text3:#505050;
            --accent:#e8502a; --accent2:#c43e1c;
            --error:#ef4444; --error-bg:#2d0a0a;
            --success-bg:#052e16; --success-border:rgba(34,197,94,.3);
        }
        html, body { min-height:100vh; background:var(--bg); color:var(--text);
            font-family:-apple-system,BlinkMacSystemFont,'SF Pro Display','Segoe UI',sans-serif;
            -webkit-font-smoothing:antialiased; }

        /* Layout */
        .page { min-height:100vh; display:flex; }

        /* Left panel */
        .left {
            display:none; width:44%;
            background:var(--surface); border-right:1px solid var(--border);
            flex-direction:column; justify-content:center;
            padding:60px 52px; position:relative; overflow:hidden;
        }
        @media(min-width:1000px){ .left { display:flex; } }
        .glow { position:absolute; border-radius:50%; pointer-events:none; }
        .glow-1 { width:480px;height:480px; background:radial-gradient(circle,rgba(232,80,42,.06) 0%,transparent 65%); top:-120px;left:-100px; }
        .glow-2 { width:360px;height:360px; background:radial-gradient(circle,rgba(232,80,42,.04) 0%,transparent 65%); bottom:-80px;right:-60px; }

        .brand { display:flex;align-items:center;gap:12px;margin-bottom:40px;position:relative; }
        .brand-icon { width:44px;height:44px;border-radius:12px;background:linear-gradient(145deg,#e8a23a,#e8502a);display:flex;align-items:center;justify-content:center;font-size:20px; }
        .brand-name { font-size:18px;font-weight:700;color:var(--text);letter-spacing:-.3px; }

        .left-title { font-size:30px;font-weight:800;color:var(--text);letter-spacing:-.6px;line-height:1.2;margin-bottom:14px;position:relative; }
        .left-title span { color:var(--accent); }
        .left-desc { font-size:14px;color:var(--text2);line-height:1.65;margin-bottom:36px;position:relative; }

        .steps-list { position:relative; }
        .step-item { display:flex;align-items:flex-start;gap:14px;margin-bottom:20px;position:relative; }
        .step-num { width:28px;height:28px;border-radius:8px;background:var(--accent-bg,rgba(232,80,42,.1));border:1px solid rgba(232,80,42,.2);display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:800;color:var(--accent);flex-shrink:0;margin-top:2px; }
        .step-text h4 { font-size:13px;font-weight:700;color:var(--text);margin-bottom:3px; }
        .step-text p  { font-size:12px;color:var(--text3);line-height:1.4; }

        /* Right panel */
        .right { flex:1;display:flex;align-items:center;justify-content:center;padding:32px 20px;overflow-y:auto; }
        .form-box { width:100%;max-width:480px;padding:8px 0; }

        /* Mobile brand */
        .mobile-brand { display:flex;align-items:center;gap:10px;justify-content:center;margin-bottom:28px; }
        @media(min-width:1000px){ .mobile-brand { display:none; } }
        .mobile-brand-icon { width:36px;height:36px;border-radius:10px;background:linear-gradient(145deg,#e8a23a,#e8502a);display:flex;align-items:center;justify-content:center;font-size:17px; }
        .mobile-brand-name { font-size:16px;font-weight:700;color:var(--text); }

        /* Progress steps */
        .progress-bar { display:flex;align-items:center;gap:0;margin-bottom:28px; }
        .prog-step { display:flex;align-items:center;flex:1; }
        .prog-dot { width:28px;height:28px;border-radius:99px;border:2px solid var(--border2);background:var(--surface2);display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;color:var(--text3);flex-shrink:0;transition:all .3s; }
        .prog-dot.active { border-color:var(--accent);background:var(--accent);color:#fff; }
        .prog-dot.done   { border-color:var(--accent);background:var(--accent);color:#fff; }
        .prog-line { flex:1;height:2px;background:var(--border2);transition:background .3s; }
        .prog-line.done { background:var(--accent); }
        .prog-label { font-size:10px;color:var(--text3);margin-top:6px;text-align:center;white-space:nowrap; }
        .prog-step-wrap { display:flex;flex-direction:column;align-items:center; }

        /* Form styles */
        .form-title { font-size:20px;font-weight:700;color:var(--text);letter-spacing:-.3px;margin-bottom:4px; }
        .form-sub   { font-size:13px;color:var(--text2);margin-bottom:22px; }

        .error-box { background:var(--error-bg);border:1px solid rgba(239,68,68,.3);border-radius:10px;padding:12px 14px;margin-bottom:18px; }
        .error-box p { font-size:13px;color:#fca5a5;line-height:1.5; }

        .step-form { display:none; }
        .step-form.active { display:block; }

        .two-col { display:grid;grid-template-columns:1fr 1fr;gap:14px; }
        @media(max-width:500px){ .two-col { grid-template-columns:1fr; } }

        .field { margin-bottom:14px; }
        .field-label { display:block;font-size:11px;font-weight:600;color:var(--text2);margin-bottom:6px;text-transform:uppercase;letter-spacing:.06em; }
        .field-input { width:100%;background:var(--surface);border:1px solid var(--border2);border-radius:10px;padding:11px 14px;font-size:14px;color:var(--text);outline:none;transition:border-color .2s;-webkit-appearance:none; }
        .field-input:focus { border-color:var(--accent); }
        .field-input.has-error { border-color:var(--error); }
        .field-input::placeholder { color:var(--text3); }
        .field-input:-webkit-autofill { -webkit-box-shadow:0 0 0 100px var(--surface) inset;-webkit-text-fill-color:var(--text); }
        .field-error { font-size:11px;color:#fca5a5;margin-top:4px; }
        .field-hint  { font-size:11px;color:var(--text3);margin-top:4px; }

        .pw-wrap { position:relative; }
        .pw-wrap .field-input { padding-right:42px; }
        .pw-toggle { position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:var(--text3);padding:4px; }
        .pw-toggle svg { width:15px;height:15px;display:block; }

        /* Strength bar */
        .pw-strength { margin-top:5px; }
        .pw-bar { height:3px;border-radius:99px;background:var(--border2);overflow:hidden;margin-bottom:3px; }
        .pw-fill { height:100%;border-radius:99px;width:0%;transition:width .3s,background .3s; }
        .pw-label { font-size:10px;color:var(--text3); }

        /* Section divider */
        .section-divider { display:flex;align-items:center;gap:10px;margin:18px 0 16px;color:var(--text3);font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.06em; }
        .section-divider::before,.section-divider::after { content:'';flex:1;height:1px;background:var(--border); }

        /* Buttons */
        .btn-next { width:100%;background:var(--accent);color:#fff;border:none;border-radius:10px;padding:13px;font-size:15px;font-weight:700;cursor:pointer;transition:background .18s;margin-top:4px; }
        .btn-next:hover { background:var(--accent2); }
        .btn-back { width:100%;background:var(--surface2);color:var(--text2);border:1px solid var(--border2);border-radius:10px;padding:12px;font-size:14px;cursor:pointer;transition:all .2s;margin-top:8px; }
        .btn-back:hover { color:var(--text);border-color:var(--border); }

        .link-row { text-align:center;font-size:13px;color:var(--text2);margin-top:16px; }
        .link-row a { color:var(--accent);text-decoration:none;font-weight:600; }

        /* Terms */
        .terms-note { font-size:11px;color:var(--text3);text-align:center;margin-top:12px;line-height:1.5; }
        .terms-note a { color:var(--text2);text-decoration:none; }

        /* Review box */
        .review-box { background:var(--surface2);border:1px solid var(--border);border-radius:12px;padding:16px;margin-bottom:18px; }
        .review-row { display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px solid var(--border); }
        .review-row:last-child { border-bottom:none; }
        .review-label { font-size:12px;color:var(--text3); }
        .review-value { font-size:13px;font-weight:600;color:var(--text);text-align:right;max-width:60%;overflow:hidden;text-overflow:ellipsis;white-space:nowrap; }

        /* Pending notice */
        .pending-notice { background:rgba(232,80,42,.06);border:1px solid rgba(232,80,42,.15);border-radius:10px;padding:12px 14px;margin-bottom:20px; }
        .pending-notice p { font-size:12px;color:var(--text2);line-height:1.5; }
        .pending-notice strong { color:var(--accent); }
    </style>
</head>
<body>
<div class="page">

    {{-- Left Panel --}}
    <div class="left">
        <div class="glow glow-1"></div>
        <div class="glow glow-2"></div>

        <div class="brand">
            <div class="brand-icon">🍽</div>
            <div>
                <p class="brand-name">QR Menu SaaS</p>
            </div>
        </div>

        <h1 class="left-title">Get your restaurant<br><span>online today.</span></h1>
        <p class="left-desc">Register in 2 minutes. Your customers will be scanning your menu instantly.</p>

        <div class="steps-list">
            <div class="step-item">
                <div class="step-num">1</div>
                <div class="step-text">
                    <h4>Create Your Account</h4>
                    <p>Enter your name, email and a secure password.</p>
                </div>
            </div>
            <div class="step-item">
                <div class="step-num">2</div>
                <div class="step-text">
                    <h4>Add Restaurant Details</h4>
                    <p>Your restaurant name, phone and address.</p>
                </div>
            </div>
            <div class="step-item">
                <div class="step-num">3</div>
                <div class="step-text">
                    <h4>Wait for Approval</h4>
                    <p>Our team reviews your application within 24 hours.</p>
                </div>
            </div>
            <div class="step-item">
                <div class="step-num">4</div>
                <div class="step-text">
                    <h4>Go Live!</h4>
                    <p>Add your menu, generate QR codes, and start serving.</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Right Form Panel --}}
    <div class="right">
        <div class="form-box">

            {{-- Mobile brand --}}
            <div class="mobile-brand">
                <div class="mobile-brand-icon">🍽</div>
                <span class="mobile-brand-name">QR Menu SaaS</span>
            </div>

            {{-- Progress Bar --}}
            <div style="display:flex;align-items:flex-start;gap:0;margin-bottom:28px;">
                <div style="display:flex;flex-direction:column;align-items:center;flex:1;">
                    <div class="prog-dot active" id="dot-1">1</div>
                    <span style="font-size:10px;color:var(--text3);margin-top:5px;text-align:center;">Account</span>
                </div>
                <div style="flex:1;height:2px;background:var(--border2);margin-top:14px;transition:background .3s;" id="line-1"></div>
                <div style="display:flex;flex-direction:column;align-items:center;flex:1;">
                    <div class="prog-dot" id="dot-2">2</div>
                    <span style="font-size:10px;color:var(--text3);margin-top:5px;text-align:center;">Restaurant</span>
                </div>
                <div style="flex:1;height:2px;background:var(--border2);margin-top:14px;transition:background .3s;" id="line-2"></div>
                <div style="display:flex;flex-direction:column;align-items:center;flex:1;">
                    <div class="prog-dot" id="dot-3">3</div>
                    <span style="font-size:10px;color:var(--text3);margin-top:5px;text-align:center;">Review</span>
                </div>
            </div>

            {{-- Validation Errors --}}
            @if ($errors->any())
            <div class="error-box">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
            @endif

            <form method="POST" action="{{ route('register') }}" id="registerForm">
            @csrf

            {{-- ══ STEP 1: Account Info ══ --}}
            <div class="step-form active" id="step-1">
                <h2 class="form-title">Create your account</h2>
                <p class="form-sub">You'll use these credentials to log into your dashboard</p>

                <div class="field">
                    <label class="field-label" for="name">Full Name *</label>
                    <input id="name" type="text" name="name"
                           value="{{ old('name') }}"
                           class="field-input {{ $errors->has('name') ? 'has-error' : '' }}"
                           placeholder="Your full name" autofocus required>
                    @error('name') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                <div class="field">
                    <label class="field-label" for="email">Email Address *</label>
                    <input id="email" type="email" name="email"
                           value="{{ old('email') }}"
                           class="field-input {{ $errors->has('email') ? 'has-error' : '' }}"
                           placeholder="you@restaurant.com" required>
                    @error('email') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                <div class="field">
                    <label class="field-label" for="password">Password *</label>
                    <div class="pw-wrap">
                        <input id="password" type="password" name="password"
                               class="field-input {{ $errors->has('password') ? 'has-error' : '' }}"
                               placeholder="Min. 8 characters"
                               autocomplete="new-password"
                               oninput="checkStrength(this.value)"
                               required>
                        <button type="button" class="pw-toggle" onclick="togglePw('password', this)">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </button>
                    </div>
                    <div class="pw-strength">
                        <div class="pw-bar"><div class="pw-fill" id="pwFill"></div></div>
                        <span class="pw-label" id="pwLabel"></span>
                    </div>
                    @error('password') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                <div class="field">
                    <label class="field-label" for="password_confirmation">Confirm Password *</label>
                    <div class="pw-wrap">
                        <input id="password_confirmation" type="password"
                               name="password_confirmation"
                               class="field-input"
                               placeholder="Re-enter password"
                               autocomplete="new-password" required>
                        <button type="button" class="pw-toggle" onclick="togglePw('password_confirmation', this)">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </button>
                    </div>
                </div>

                <button type="button" class="btn-next" onclick="goStep(2)">
                    Continue → Restaurant Details
                </button>

                <div class="link-row">
                    Already have an account? <a href="{{ route('login') }}">Sign in</a>
                </div>
            </div>

            {{-- ══ STEP 2: Restaurant Info ══ --}}
            <div class="step-form" id="step-2">
                <h2 class="form-title">Your Restaurant</h2>
                <p class="form-sub">Tell us about your restaurant — this will be your menu page</p>

                <div class="field">
                    <label class="field-label" for="restaurant_name">Restaurant Name *</label>
                    <input id="restaurant_name" type="text" name="restaurant_name"
                           value="{{ old('restaurant_name') }}"
                           class="field-input {{ $errors->has('restaurant_name') ? 'has-error' : '' }}"
                           placeholder="e.g. Kababjees Restaurant"
                           oninput="updateSlugPreview(this.value)"
                           required>
                    <p class="field-hint" id="slugPreview"></p>
                    @error('restaurant_name') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                <div class="two-col">
                    <div class="field">
                        <label class="field-label" for="restaurant_phone">Phone Number</label>
                        <input id="restaurant_phone" type="text" name="restaurant_phone"
                               value="{{ old('restaurant_phone') }}"
                               class="field-input"
                               placeholder="0300-1234567">
                    </div>
                    <div class="field">
                        <label class="field-label" for="restaurant_city">City</label>
                        <input id="restaurant_city" type="text" name="restaurant_city"
                               value="{{ old('restaurant_city') }}"
                               class="field-input"
                               placeholder="Karachi">
                    </div>
                </div>

                <div class="field">
                    <label class="field-label" for="restaurant_address">Full Address</label>
                    <textarea id="restaurant_address" name="restaurant_address" rows="2"
                              class="field-input" style="resize:none;"
                              placeholder="Shop #5, Block 4, Gulshan-e-Iqbal, Karachi">{{ old('restaurant_address') }}</textarea>
                </div>

                <div class="pending-notice">
                    <p>⏳ <strong>Review Process:</strong> After registration, your restaurant will be reviewed by our team within <strong>24 hours</strong>. You'll be able to access the full dashboard once approved.</p>
                </div>

                <button type="button" class="btn-next" onclick="goStep(3)">
                    Continue → Review Details
                </button>
                <button type="button" class="btn-back" onclick="goStep(1)">
                    ← Back
                </button>
            </div>

            {{-- ══ STEP 3: Review & Submit ══ --}}
            <div class="step-form" id="step-3">
                <h2 class="form-title">Review & Submit</h2>
                <p class="form-sub">Please review your details before submitting</p>

                {{-- Account Summary --}}
                <p style="font-size:11px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.06em;margin-bottom:8px;">Account</p>
                <div class="review-box">
                    <div class="review-row">
                        <span class="review-label">Name</span>
                        <span class="review-value" id="review-name">—</span>
                    </div>
                    <div class="review-row">
                        <span class="review-label">Email</span>
                        <span class="review-value" id="review-email">—</span>
                    </div>
                </div>

                {{-- Restaurant Summary --}}
                <p style="font-size:11px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.06em;margin-bottom:8px;">Restaurant</p>
                <div class="review-box">
                    <div class="review-row">
                        <span class="review-label">Name</span>
                        <span class="review-value" id="review-restaurant">—</span>
                    </div>
                    <div class="review-row">
                        <span class="review-label">Phone</span>
                        <span class="review-value" id="review-phone">—</span>
                    </div>
                    <div class="review-row">
                        <span class="review-label">Address</span>
                        <span class="review-value" id="review-address">—</span>
                    </div>
                </div>

                {{-- What happens next --}}
                <div style="background:var(--surface2);border:1px solid var(--border);border-radius:10px;padding:14px;margin-bottom:18px;">
                    <p style="font-size:12px;font-weight:600;color:var(--text2);margin-bottom:8px;">What happens next:</p>
                    <div style="display:flex;flex-direction:column;gap:6px;">
                        <div style="display:flex;align-items:center;gap:8px;font-size:12px;color:var(--text3);">
                            <span style="color:var(--accent);font-weight:700;">1.</span> Your application is submitted for review
                        </div>
                        <div style="display:flex;align-items:center;gap:8px;font-size:12px;color:var(--text3);">
                            <span style="color:var(--accent);font-weight:700;">2.</span> Our team reviews within 24 hours
                        </div>
                        <div style="display:flex;align-items:center;gap:8px;font-size:12px;color:var(--text3);">
                            <span style="color:var(--accent);font-weight:700;">3.</span> You get access to your full dashboard
                        </div>
                        <div style="display:flex;align-items:center;gap:8px;font-size:12px;color:var(--text3);">
                            <span style="color:var(--accent);font-weight:700;">4.</span> Add menu items and generate QR codes
                        </div>
                    </div>
                </div>

                <div style="display:flex;align-items:flex-start;gap:8px;margin-bottom:18px;">
                    <input type="checkbox" id="terms" style="width:15px;height:15px;accent-color:var(--accent);cursor:pointer;margin-top:2px;flex-shrink:0;">
                    <label for="terms" style="font-size:12px;color:var(--text3);cursor:pointer;line-height:1.5;">
                        I agree to the <a href="#" style="color:var(--text2);">Terms of Service</a> and <a href="#" style="color:var(--text2);">Privacy Policy</a>
                    </label>
                </div>

                {{-- <button type="submit" class="btn-next" id="submitBtn" onclick="return checkTerms()"> --}}
                    <button type="submit" class="btn-next" id="submitBtn">
                    🚀 Submit Application
                </button>
                <button type="button" class="btn-back" onclick="goStep(2)">
                    ← Back
                </button>
            </div>

            </form>

        </div>
    </div>

</div>

<script>
let currentStep = 1;

// Restore step if validation failed (server-side)
@if($errors->any())
    currentStep = {{ old('restaurant_name') ? 3 : (old('name') ? 2 : 1) }};
    document.addEventListener('DOMContentLoaded', () => showStep(currentStep));
@endif

function showStep(step) {
    document.querySelectorAll('.step-form').forEach(f => f.classList.remove('active'));
    document.getElementById('step-' + step).classList.add('active');

    // Update progress dots
    for (let i = 1; i <= 3; i++) {
        const dot  = document.getElementById('dot-' + i);
        const line = document.getElementById('line-' + i);
        if (i < step)  { dot.classList.add('done'); dot.innerHTML = '✓'; }
        else if (i === step) { dot.classList.add('active'); dot.classList.remove('done'); dot.innerHTML = i; }
        else { dot.classList.remove('active','done'); dot.innerHTML = i; }
        if (line && i < step) line.style.background = '#e8502a';
        else if (line) line.style.background = '';
    }
    currentStep = step;
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function goStep(step) {
    if (step === 2) {
        // Validate step 1 fields
        const name  = document.getElementById('name').value.trim();
        const email = document.getElementById('email').value.trim();
        const pw    = document.getElementById('password').value;
        const pwc   = document.getElementById('password_confirmation').value;

        if (!name)  { highlight('name', 'Name is required'); return; }
        if (!email) { highlight('email', 'Email is required'); return; }
        if (!pw || pw.length < 8) { highlight('password', 'Password must be at least 8 characters'); return; }
        if (pw !== pwc) { highlight('password_confirmation', 'Passwords do not match'); return; }
    }

    if (step === 3) {
        const rName = document.getElementById('restaurant_name').value.trim();
        if (!rName) { highlight('restaurant_name', 'Restaurant name is required'); return; }

        // Populate review
        document.getElementById('review-name').textContent       = document.getElementById('name').value;
        document.getElementById('review-email').textContent      = document.getElementById('email').value;
        document.getElementById('review-restaurant').textContent = document.getElementById('restaurant_name').value;
        document.getElementById('review-phone').textContent      = document.getElementById('restaurant_phone').value || '—';
        document.getElementById('review-address').textContent    = document.getElementById('restaurant_address').value || '—';
    }

    showStep(step);
}

function highlight(id, msg) {
    const input = document.getElementById(id);
    input.classList.add('has-error');
    input.focus();
    let err = input.parentElement.querySelector('.field-error');
    if (!err) { err = document.createElement('p'); err.className = 'field-error'; input.parentElement.appendChild(err); }
    err.textContent = msg;
    input.addEventListener('input', () => { input.classList.remove('has-error'); err.textContent = ''; }, { once: true });
}

function checkTerms() {
    if (!document.getElementById('terms').checked) {
        alert('Please agree to the Terms of Service before submitting.');
        return false;
    }
    document.getElementById('submitBtn').textContent = 'Submitting...';
    document.getElementById('submitBtn').disabled = true;
    return true;
}

function updateSlugPreview(val) {
    if (!val) { document.getElementById('slugPreview').textContent = ''; return; }
    const slug = val.toLowerCase().replace(/[^a-z0-9]+/g,'-').replace(/^-|-$/g,'');
    document.getElementById('slugPreview').textContent = 'Your menu URL: /r/' + slug;
}

function togglePw(id, btn) {
    const input = document.getElementById(id);
    const isText = input.type === 'text';
    input.type = isText ? 'password' : 'text';
    btn.querySelector('svg').innerHTML = isText
        ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>'
        : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>';
}

function checkStrength(pw) {
    let score = 0;
    if (pw.length >= 8)         score++;
    if (/[A-Z]/.test(pw))       score++;
    if (/[0-9]/.test(pw))       score++;
    if (/[^A-Za-z0-9]/.test(pw)) score++;
    const map = [
        {w:'0%',   bg:'transparent', t:''},
        {w:'25%',  bg:'#ef4444',     t:'Weak'},
        {w:'50%',  bg:'#f97316',     t:'Fair'},
        {w:'75%',  bg:'#facc15',     t:'Good'},
        {w:'100%', bg:'#22c55e',     t:'Strong'},
    ];
    document.getElementById('pwFill').style.width      = map[score].w;
    document.getElementById('pwFill').style.background = map[score].bg;
    document.getElementById('pwLabel').textContent     = map[score].t;
    document.getElementById('pwLabel').style.color     = map[score].bg;
}
</script>
</body>
</html>