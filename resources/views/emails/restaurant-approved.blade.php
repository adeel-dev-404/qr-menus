<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Restaurant Approved</title>
<style>
  * { box-sizing:border-box; margin:0; padding:0; }
  body { background:#f4f4f5; font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif; -webkit-font-smoothing:antialiased; }
  .wrapper { max-width:580px; margin:32px auto; padding:0 16px 32px; }
  .card { background:#fff; border-radius:16px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,.06); }
  .header { background:linear-gradient(135deg,#16a34a,#15803d); padding:28px 32px; text-align:center; }
  .header-icon { font-size:36px; margin-bottom:8px; }
  .header h1 { color:#fff; font-size:20px; font-weight:800; letter-spacing:-.3px; margin-bottom:4px; }
  .header p  { color:rgba(255,255,255,.75); font-size:13px; }
  .body { padding:28px 32px; }
  .section-title { font-size:11px; font-weight:700; color:#9ca3af; text-transform:uppercase; letter-spacing:.08em; margin-bottom:12px; }
  .info-box { background:#f9fafb; border:1px solid #e5e7eb; border-radius:10px; overflow:hidden; margin-bottom:20px; }
  .meta-row { display:flex; justify-content:space-between; align-items:center; padding:10px 16px; border-bottom:1px solid #f3f4f6; }
  .meta-row:last-child { border-bottom:none; }
  .meta-lbl { font-size:12px; color:#9ca3af; }
  .meta-val { font-size:13px; font-weight:600; color:#374151; text-align:right; }
  .cta-btn { display:block; text-align:center; background:#e8502a; color:#fff; text-decoration:none; padding:14px 24px; border-radius:10px; font-size:14px; font-weight:700; margin-bottom:20px; }
  .steps { margin-bottom:20px; }
  .step { display:flex; gap:12px; margin-bottom:14px; }
  .step-num { flex-shrink:0; width:28px; height:28px; background:#e8502a; color:#fff; border-radius:50%; font-size:13px; font-weight:700; display:flex; align-items:center; justify-content:center; }
  .step-text { font-size:13px; color:#374151; line-height:1.5; padding-top:4px; }
  .badge-active { display:inline-block;padding:3px 10px;border-radius:99px;font-size:11px;font-weight:700;background:#d1fae5;color:#065f46; }
  .footer { background:#f9fafb; border-top:1px solid #e5e7eb; padding:20px 32px; text-align:center; }
  .footer p { font-size:11px; color:#9ca3af; line-height:1.6; }
  @media(max-width:600px){
    .body,.header,.footer { padding:20px; }
    .meta-row { flex-direction:column; align-items:flex-start; gap:2px; }
    .meta-val { text-align:left; }
  }
</style>
</head>
<body>
<div class="wrapper">
  <div class="card">

    <div class="header">
      <div class="header-icon">🎉</div>
      <h1>Your Restaurant is Approved!</h1>
      <p>You're all set to start using {{ config('app.name') }}</p>
    </div>

    <div class="body">

      <p style="font-size:14px;color:#374151;line-height:1.6;margin-bottom:20px;">
        Congratulations! Your restaurant <strong>{{ $restaurant->name }}</strong> has been approved and is now <span class="badge-active">✅ Active</span> on the platform. You can now set up your menu and start receiving orders.
      </p>

      <p class="section-title">What's Next?</p>
      <div class="steps">
        <div class="step">
          <span class="step-num">1</span>
          <span class="step-text"><strong>Add your categories & products</strong> — Create your menu items with photos and prices.</span>
        </div>
        <div class="step">
          <span class="step-num">2</span>
          <span class="step-text"><strong>Generate QR codes</strong> — Print QR codes for your tables so customers can scan and order.</span>
        </div>
        <div class="step">
          <span class="step-num">3</span>
          <span class="step-text"><strong>Start receiving orders</strong> — Manage orders from your dashboard in real-time.</span>
        </div>
      </div>

      <a href="{{ config('app.url') }}/dashboard" class="cta-btn">
        Go to Your Dashboard →
      </a>

      <p style="font-size:12px;color:#9ca3af;text-align:center;line-height:1.6;">
        Need help getting started? Contact our support team anytime.
      </p>

    </div>

    <div class="footer">
      <p>This email was sent from <strong>{{ config('app.name') }}</strong><br>
      {{ $restaurant->name }} · {{ config('app.url') }}</p>
    </div>

  </div>
</div>
</body>
</html>
