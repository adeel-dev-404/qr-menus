<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Order Update — {{ $order->order_number }}</title>
<style>
  * { box-sizing:border-box; margin:0; padding:0; }
  body { background:#f4f4f5; font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif; -webkit-font-smoothing:antialiased; }
  .wrapper { max-width:580px; margin:32px auto; padding:0 16px 32px; }
  .card { background:#fff; border-radius:16px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,.06); }
  .header { padding:28px 32px; text-align:center; }
  .header-icon { font-size:48px; margin-bottom:12px; }
  .header h1 { font-size:20px; font-weight:800; letter-spacing:-.3px; margin-bottom:4px; }
  .header p  { color:rgba(255,255,255,.75); font-size:13px; }
  .header-confirmed  { background:linear-gradient(135deg,#2563eb,#1d4ed8); }
  .header-confirmed h1  { color:#fff; }
  .header-preparing  { background:linear-gradient(135deg,#f59e0b,#d97706); }
  .header-preparing h1  { color:#fff; }
  .header-ready      { background:linear-gradient(135deg,#16a34a,#15803d); }
  .header-ready h1      { color:#fff; }
  .header-delivered   { background:linear-gradient(135deg,#6b7280,#4b5563); }
  .header-delivered h1  { color:#fff; }
  .header-cancelled   { background:linear-gradient(135deg,#dc2626,#b91c1c); }
  .header-cancelled h1  { color:#fff; }
  .body { padding:28px 32px; }
  .section-title { font-size:11px; font-weight:700; color:#9ca3af; text-transform:uppercase; letter-spacing:.08em; margin-bottom:12px; }
  .status-msg { background:#f9fafb; border:1px solid #e5e7eb; border-radius:10px; padding:16px; text-align:center; margin-bottom:20px; }
  .status-msg p { font-size:14px; color:#374151; line-height:1.6; }
  .order-meta { background:#f9fafb; border:1px solid #e5e7eb; border-radius:10px; overflow:hidden; margin-bottom:20px; }
  .meta-row { display:flex; justify-content:space-between; align-items:center; padding:10px 16px; border-bottom:1px solid #f3f4f6; }
  .meta-row:last-child { border-bottom:none; }
  .meta-lbl { font-size:12px; color:#9ca3af; }
  .meta-val { font-size:13px; font-weight:600; color:#374151; text-align:right; }
  .items-table { width:100%; border-collapse:collapse; margin-bottom:20px; }
  .items-table th { font-size:10px; font-weight:700; color:#9ca3af; text-transform:uppercase; letter-spacing:.06em; padding:8px 12px; border-bottom:1px solid #e5e7eb; text-align:left; }
  .items-table td { padding:10px 12px; font-size:13px; color:#374151; border-bottom:1px solid #f3f4f6; }
  .items-table tr:last-child td { border-bottom:none; }
  .total-row { background:#f9fafb; border:1px solid #e5e7eb; border-radius:10px; padding:14px 16px; display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; }
  .total-lbl { font-size:13px; font-weight:600; color:#6b7280; }
  .total-amt { font-size:20px; font-weight:800; color:#e8502a; }
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

    @php
      $statusConfig = [
        'confirmed' => ['icon' => '✅', 'title' => 'Order Confirmed!', 'class' => 'header-confirmed', 'msg' => 'Great news! The restaurant has confirmed your order and will begin preparing it shortly.'],
        'preparing' => ['icon' => '👨‍🍳', 'title' => 'Your Order is Being Prepared', 'class' => 'header-preparing', 'msg' => 'The kitchen is now preparing your order. Hang tight — it won\'t be long!'],
        'ready'     => ['icon' => '🔔', 'title' => 'Your Order is Ready!', 'class' => 'header-ready', 'msg' => 'Your order is ready for pickup / serving. Head to the counter or your waiter will bring it to you.'],
        'delivered' => ['icon' => '🎉', 'title' => 'Order Delivered', 'class' => 'header-delivered', 'msg' => 'Your order has been delivered. Thank you for dining with us — we hope you enjoyed your meal!'],
        'cancelled' => ['icon' => '❌', 'title' => 'Order Cancelled', 'class' => 'header-cancelled', 'msg' => 'Unfortunately, your order has been cancelled. Please contact the restaurant for more information.'],
      ];
      $cfg = $statusConfig[$status] ?? ['icon' => '📋', 'title' => 'Order Update', 'class' => 'header-confirmed', 'msg' => 'Your order status has been updated.'];
    @endphp

    <div class="header {{ $cfg['class'] }}">
      <div class="header-icon">{{ $cfg['icon'] }}</div>
      <h1>{{ $cfg['title'] }}</h1>
      <p>{{ $order->restaurant->name ?? 'Restaurant' }} · {{ $order->order_number }}</p>
    </div>

    <div class="body">

      {{-- Status message --}}
      <div class="status-msg">
        <p>{{ $cfg['msg'] }}</p>
      </div>

      {{-- Order meta --}}
      <p class="section-title">Order Details</p>
      <div class="order-meta">
        <div class="meta-row">
          <span class="meta-lbl">Order Number</span>
          <span class="meta-val" style="font-family:monospace;color:#111;">{{ $order->order_number }}</span>
        </div>
        <div class="meta-row">
          <span class="meta-lbl">Customer</span>
          <span class="meta-val">{{ $order->customer_name }}</span>
        </div>
        <div class="meta-row">
          <span class="meta-lbl">Order Type</span>
          <span class="meta-val">{{ $order->type === 'dine_in' ? '🍽 Dine-in' : '🥡 Takeaway' }}</span>
        </div>
      </div>

      {{-- Items --}}
      <p class="section-title">Ordered Items</p>
      <table class="items-table">
        <thead>
          <tr>
            <th>Item</th>
            <th style="text-align:center;">Qty</th>
            <th style="text-align:right;">Price</th>
          </tr>
        </thead>
        <tbody>
          @foreach($order->items as $item)
          <tr>
            <td>
              {{ $item->product_name }}
              @if($item->variant_name)
              <br><span style="font-size:11px;color:#9ca3af;">{{ $item->variant_name }}</span>
              @endif
            </td>
            <td style="text-align:center;color:#6b7280;">× {{ $item->quantity }}</td>
            <td style="text-align:right;font-weight:600;">Rs. {{ number_format($item->subtotal, 0) }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>

      {{-- Total --}}
      <div class="total-row">
        <span class="total-lbl">Total Amount</span>
        <span class="total-amt">Rs. {{ number_format($order->total, 0) }}</span>
      </div>

      <p style="font-size:12px;color:#9ca3af;text-align:center;line-height:1.6;">
        If you have any questions about your order, please contact the restaurant directly.
      </p>

    </div>

    <div class="footer">
      <p>This email was sent from <strong>{{ config('app.name') }}</strong><br>
      {{ $order->restaurant->name ?? '' }} · {{ config('app.url') }}</p>
    </div>

  </div>
</div>
</body>
</html>
