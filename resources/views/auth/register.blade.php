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
        }
        html, body { min-height:100vh; background:var(--bg); color:var(--text);
            font-family:-apple-system,BlinkMacSystemFont,'SF Pro Display','Segoe UI',sans-serif;
            -webkit-font-smoothing:antialiased; }
        .page { min-height:100vh; display:flex; align-items:center; justify-content:center; padding:32px 20px; }
        .form-box { width:100%; max-width:460px; }

        .brand { display:flex; align-items:center; gap:10px; justify-content:center; margin-bottom:28px; }
        .brand-icon { width:38px;height:38px;border-radius:10px;
            background:linear-gradient(145deg,#e8a23a,#e8502a);
            display:flex;align-items:center;justify-content:center;font-size:18px; }
        .brand-name { font-size:17px;font-weight:700;color:var(--text); }

        .form-title { font-size:22px;font-weight:700;color:var(--text);letter-spacing:-.4px;margin-bottom:4px; }
        .form-sub { font-size:14px;color:var(--text2);margin-bottom:24px; }

        .error-box { background:var(--error-bg);border:1px solid rgba(239,68,68,.3);border-radius:10px;padding:12px 14px;margin-bottom:18px; }
        .error-box p { font-size:13px;color:#fca5a5;line-height:1.5; }

        .two-col { display:grid;grid-template-columns:1fr 1fr;gap:14px; }
        @media(max-width:480px){ .two-col { grid-template-columns:1fr; } }

        .field { margin-bottom:14px; }
        .field-label { display:block;font-size:12px;font-weight:600;color:var(--text2);margin-bottom:6px;text-transform:uppercase;letter-spacing:.05em; }
        .field-input { width:100%;background:var(--surface);border:1px solid var(--border2);border-radius:10px;padding:11px 14px;font-size:14px;color:var(--text);outline:none;transition:border-color .2s;-webkit-appearance:none; }
        .field-input:focus { border-color:var(--accent); }
        .field-input.has-error { border-color:var(--error); }
        .field-input::placeholder { color:var(--text3); }
        .field-input:-webkit-autofill { -webkit-box-shadow:0 0 0 100px var(--surface) inset;-webkit-text-fill-color:var(--text); }
        .field-error { font-size:11px;color:#fca5a5;margin-top:4px; }

        .pw-wrap { position:relative; }
        .pw-wrap .field-input { padding-right:42px; }
        .pw-toggle { position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:var(--text3);padding:4px; }
        .pw-toggle:hover { color:var(--text2); }
        .pw-toggle svg { width:16px;height:16px;display:block; }

        /* Password strength */
        .pw-strength { margin-top:6px; }
        .pw-strength-bar { height:3px;border-radius:99px;background:var(--border2);overflow:hidden;margin-bottom:4px; }
        .pw-strength-fill { height:100%;border-radius:99px;width:0%;transition:width .3s,background .3s; }
        .pw-strength-label { font-size:11px;color:var(--text3); }

        .btn-submit { width:100%;background:var(--accent);color:#fff;border:none;border-radius:10px;padding:13px;font-size:15px;font-weight:700;cursor:pointer;transition:background .18s,transform .1s;letter-spacing:-.1px;margin-top:6px; }
        .btn-submit:hover { background:var(--accent2); }
        .btn-submit:active { transform:scale(.98); }

        .terms { font-size:12px;color:var(--text3);text-align:center;margin-top:14px;line-height:1.5; }
        .terms a { color:var(--text2);text-decoration:none; }
        .terms a:hover { color:var(--accent); }

        .divider { display:flex;align-items:center;gap:12px;margin:18px 0;color:var(--text3);font-size:12px; }
        .divider::before,.divider::after { content:'';flex:1;height:1px;background:var(--border); }

        .link-row { text-align:center;font-size:13px;color:var(--text2); }
        .link-row a { color:var(--accent);text-decoration:none;font-weight:600; }
        .link-row a:hover { color:#f97316; }

        .plan-cards { display:grid;grid-template-columns:repeat(3,1fr);gap:8px;margin-bottom:20px; }
        @media(max-width:400px){ .plan-cards { grid-template-columns:1fr; } }
        .plan-card { background:var(--surface);border:1px solid var(--border2);border-radius:10px;padding:12px;text-align:center;cursor:pointer;transition:border-color .18s; }
        .plan-card:hover { border-color:var(--border); }
        .plan-card.selected { border-color:var(--accent);background:rgba(232,80,42,.06); }
        .plan-card h4 { font-size:13px;font-weight:700;color:var(--text);margin-bottom:3px; }
        .plan-card p { font-size:11px;color:var(--text3); }
        .plan-card .plan-price { font-size:12px;font-weight:600;color:var(--accent);margin-top:4px; }
    </style>
</head>
<body>
<div class="page">
    <div class="form-box">

        <div class="brand">
            <div class="brand-icon">🍽</div>
            <span class="brand-name">QR Menu SaaS</span>
        </div>

        <h2 class="form-title">Create your account</h2>
        <p class="form-sub">Get your restaurant's digital menu live in minutes</p>

        @if ($errors->any())
        <div class="error-box">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="two-col">
                <div class="field">
                    <label class="field-label" for="name">Full Name</label>
                    <input id="name" type="text" name="name"
                           value="{{ old('name') }}"
                           class="field-input {{ $errors->has('name') ? 'has-error' : '' }}"
                           placeholder="Your name" autofocus required>
                    @error('name') <p class="field-error">{{ $message }}</p> @enderror
                </div>
                <div class="field">
                    <label class="field-label" for="email">Email</label>
                    <input id="email" type="email" name="email"
                           value="{{ old('email') }}"
                           class="field-input {{ $errors->has('email') ? 'has-error' : '' }}"
                           placeholder="you@restaurant.com" required>
                    @error('email') <p class="field-error">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="field">
                <label class="field-label" for="password">Password</label>
                <div class="pw-wrap">
                    <input id="password" type="password" name="password"
                           class="field-input {{ $errors->has('password') ? 'has-error' : '' }}"
                           placeholder="Min. 8 characters"
                           autocomplete="new-password"
                           oninput="checkStrength(this.value)"
                           required>
                    <button type="button" class="pw-toggle" onclick="togglePw('password', this)">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                </div>
                <div class="pw-strength">
                    <div class="pw-strength-bar">
                        <div class="pw-strength-fill" id="strengthFill"></div>
                    </div>
                    <span class="pw-strength-label" id="strengthLabel"></span>
                </div>
                @error('password') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div class="field">
                <label class="field-label" for="password_confirmation">Confirm Password</label>
                <div class="pw-wrap">
                    <input id="password_confirmation" type="password"
                           name="password_confirmation"
                           class="field-input"
                           placeholder="Re-enter your password"
                           autocomplete="new-password" required>
                    <button type="button" class="pw-toggle" onclick="togglePw('password_confirmation', this)">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-submit">Create Account</button>

        </form>

        <p class="terms">
            By registering you agree to our
            <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>
        </p>

        <div class="divider">already have an account?</div>
        <div class="link-row">
            <a href="{{ route('login') }}">Sign in instead</a>
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

function checkStrength(pw) {
    const fill  = document.getElementById('strengthFill');
    const label = document.getElementById('strengthLabel');
    let score = 0;
    if (pw.length >= 8)  score++;
    if (/[A-Z]/.test(pw)) score++;
    if (/[0-9]/.test(pw)) score++;
    if (/[^A-Za-z0-9]/.test(pw)) score++;

    const map = [
        { w:'0%',   bg:'transparent', t:'' },
        { w:'25%',  bg:'#ef4444',     t:'Weak' },
        { w:'50%',  bg:'#f97316',     t:'Fair' },
        { w:'75%',  bg:'#facc15',     t:'Good' },
        { w:'100%', bg:'#22c55e',     t:'Strong' },
    ];
    fill.style.width      = map[score].w;
    fill.style.background = map[score].bg;
    label.textContent     = map[score].t;
    label.style.color     = map[score].bg;
}
</script>
</body>
</html>