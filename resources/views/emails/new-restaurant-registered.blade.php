<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>New Restaurant Pending Approval</title>
<style>
  * { box-sizing:border-box; margin:0; padding:0; }
  body { background:#f4f4f5; font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif; -webkit-font-smoothing:antialiased; }
  .wrapper { max-width:580px; margin:32px auto; padding:0 16px 32px; }
  .card { background:#fff; border-radius:16px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,.06); }
  .header { background:linear-gradient(135deg,#f59e0b,#d97706); padding:28px 32px; text-align:center; }
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
      <div class="header-icon">🏪</div>
      <h1>New Restaurant Pending Approval</h1>
      <p>{{ now()->format('d M Y, h:i A') }}</p>
    </div>

    <div class="body">

      <p style="font-size:14px;color:#374151;line-height:1.6;margin-bottom:20px;">
        A new restaurant has just registered on the platform and is waiting for your approval.
      </p>

      <p class="section-title">Restaurant Details</p>
      <div class="info-box">
        <div class="meta-row">
          <span class="meta-lbl">Restaurant Name</span>
          <span class="meta-val">{{ $restaurantName }}</span>
        </div>
        @if(isset($ownerName))
        <div class="meta-row">
          <span class="meta-lbl">Owner Name</span>
          <span class="meta-val">{{ $ownerName }}</span>
        </div>
        @endif
        <div class="meta-row">
          <span class="meta-lbl">Status</span>
          <span class="meta-val">
            <span style="display:inline-block;padding:3px 10px;border-radius:99px;font-size:11px;font-weight:700;background:#fef3c7;color:#92400e;">⏳ Pending</span>
          </span>
        </div>
      </div>

      <a href="{{ config('app.url') }}/admin/restaurants" class="cta-btn">
        Review & Approve →
      </a>

      <p style="font-size:12px;color:#9ca3af;text-align:center;line-height:1.6;">
        Log into the admin panel to review the restaurant details and approve or reject the registration.
      </p>

    </div>

    <div class="footer">
      <p>This email was sent from <strong>{{ config('app.name') }}</strong><br>
      Admin Notification · {{ config('app.url') }}</p>
    </div>

  </div>
</div>
</body>
</html>
