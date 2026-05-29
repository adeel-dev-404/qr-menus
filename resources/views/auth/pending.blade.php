<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Pending — {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --bg:#080808; --surface:#111111; --surface2:#181818;
            --border:#1e1e1e; --border2:#2a2a2a;
            --text:#f0f0f0; --text2:#888888; --text3:#505050;
            --accent:#e8502a;
        }
        html, body {
            min-height:100vh; background:var(--bg); color:var(--text);
            font-family:-apple-system,BlinkMacSystemFont,'SF Pro Display','Segoe UI',sans-serif;
            -webkit-font-smoothing:antialiased;
            display:flex; align-items:center; justify-content:center;
        }
        body { padding:24px; }

        .card {
            width:100%; max-width:480px;
            background:var(--surface); border:1px solid var(--border);
            border-radius:20px; padding:40px 32px; text-align:center;
        }

        /* Animated pending icon */
        .icon-wrap {
            width:72px; height:72px; border-radius:20px;
            background:rgba(232,80,42,.1); border:1px solid rgba(232,80,42,.2);
            display:flex; align-items:center; justify-content:center;
            font-size:32px; margin:0 auto 24px;
            animation: float 3s ease-in-out infinite;
        }
        @keyframes float {
            0%,100% { transform:translateY(0); }
            50% { transform:translateY(-6px); }
        }

        h1 { font-size:22px; font-weight:800; color:var(--text); letter-spacing:-.4px; margin-bottom:8px; }
        .subtitle { font-size:14px; color:var(--text2); line-height:1.6; margin-bottom:28px; }

        /* Status timeline */
        .timeline { text-align:left; margin-bottom:28px; }
        .tl-item { display:flex; align-items:flex-start; gap:14px; margin-bottom:16px; }
        .tl-dot {
            width:28px; height:28px; border-radius:99px; flex-shrink:0;
            display:flex; align-items:center; justify-content:center;
            font-size:12px; font-weight:700; margin-top:1px;
        }
        .tl-dot.done   { background:#052e16; border:1px solid #166534; color:#4ade80; }
        .tl-dot.active { background:rgba(232,80,42,.15); border:1px solid rgba(232,80,42,.3); color:var(--accent); animation:pulse-dot 2s infinite; }
        .tl-dot.waiting{ background:var(--surface2); border:1px solid var(--border2); color:var(--text3); }
        @keyframes pulse-dot {
            0%,100% { box-shadow:0 0 0 0 rgba(232,80,42,.4); }
            50% { box-shadow:0 0 0 6px rgba(232,80,42,0); }
        }
        .tl-content h4 { font-size:13px; font-weight:600; color:var(--text); margin-bottom:2px; }
        .tl-content p  { font-size:12px; color:var(--text3); line-height:1.4; }

        /* Info box */
        .info-box {
            background:var(--surface2); border:1px solid var(--border2);
            border-radius:12px; padding:16px; margin-bottom:24px; text-align:left;
        }
        .info-row { display:flex; justify-content:space-between; padding:7px 0; border-bottom:1px solid var(--border); }
        .info-row:last-child { border:none; }
        .info-label { font-size:12px; color:var(--text3); }
        .info-value { font-size:12px; font-weight:600; color:var(--text2); }

        /* Buttons */
        .btn-refresh {
            display:block; width:100%; padding:12px;
            background:var(--accent); color:#fff; border:none;
            border-radius:10px; font-size:14px; font-weight:700;
            cursor:pointer; text-decoration:none; margin-bottom:10px;
            transition:background .2s;
        }
        .btn-refresh:hover { background:#c43e1c; }
        .btn-logout {
            display:block; width:100%; padding:11px;
            background:none; color:var(--text3); border:1px solid var(--border2);
            border-radius:10px; font-size:13px; cursor:pointer;
            text-decoration:none; transition:all .2s;
        }
        .btn-logout:hover { color:var(--text2); border-color:var(--border); }

        .contact-note { font-size:11px; color:var(--text3); margin-top:16px; line-height:1.5; }
        .contact-note a { color:var(--text2); text-decoration:none; }
    </style>
</head>
<body>

<div class="card">

    <div class="icon-wrap">⏳</div>

    <h1>Application Under Review</h1>
    <p class="subtitle">
        Thank you for registering <strong style="color:var(--text);">{{ auth()->user()->restaurant->name ?? 'your restaurant' }}</strong>!
        Our team will review your application and activate your account within 24 hours.
    </p>

    {{-- Timeline --}}
    <div class="timeline">
        <div class="tl-item">
            <div class="tl-dot done">✓</div>
            <div class="tl-content">
                <h4>Account Created</h4>
                <p>Your account has been created successfully.</p>
            </div>
        </div>
        <div class="tl-item">
            <div class="tl-dot done">✓</div>
            <div class="tl-content">
                <h4>Restaurant Registered</h4>
                <p>{{ auth()->user()->restaurant->name ?? '' }} has been submitted for review.</p>
            </div>
        </div>
        <div class="tl-item">
            <div class="tl-dot active">●</div>
            <div class="tl-content">
                <h4>Pending Approval</h4>
                <p>Our team is reviewing your application. Usually within 24 hours.</p>
            </div>
        </div>
        <div class="tl-item">
            <div class="tl-dot waiting">4</div>
            <div class="tl-content">
                <h4>Dashboard Access</h4>
                <p>Once approved, you'll have full access to your dashboard.</p>
            </div>
        </div>
    </div>

    {{-- Restaurant info --}}
    @if(auth()->user()->restaurant)
    <div class="info-box">
        <div class="info-row">
            <span class="info-label">Restaurant</span>
            <span class="info-value">{{ auth()->user()->restaurant->name }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Status</span>
            <span class="info-value" style="color:#f59e0b;">⏳ Pending Review</span>
        </div>
        <div class="info-row">
            <span class="info-label">Submitted</span>
            <span class="info-value">{{ auth()->user()->restaurant->created_at->format('d M Y, h:i A') }}</span>
        </div>
    </div>
    @endif

    {{-- Refresh button --}}
    <a href="{{ route('pending') }}" class="btn-refresh">
        🔄 Check Approval Status
    </a>

    {{-- Logout --}}
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn-logout">Sign Out</button>
    </form>

    <p class="contact-note">
        Need help? Contact us on
        <a href="https://wa.me/923001234567">WhatsApp</a> or
        <a href="mailto:hello@qrmenu.pk">hello@qrmenu.pk</a>
    </p>

</div>

</body>
</html>