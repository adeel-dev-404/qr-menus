<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>New Order — {{ $order->order_number }}</title>
<style>
  * { box-sizing:border-box; margin:0; padding:0; }
  body { background:#f4f4f5; font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif; -webkit-font-smoothing:antialiased; }
  .wrapper { max-width:580px; margin:32px auto; padding:0 16px 32px; }
  .card { background:#fff; border-radius:16px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,.06); }
  .header { background:linear-gradient(135deg,#e8502a,#c43e1c); padding:28px 32px; text-align:center; }
  .header-icon { font-size:36px; margin-bottom:8px; }
  .header h1 { color:#fff; font-size:20px; font-weight:800; letter-spacing:-.3px; margin-bottom:4px; }
  .header p  { color:rgba(255,255,255,.75); font-size:13px; }
  .body { padding:28px 32px; }
  .section-title { font-size:11px; font-weight:700; color:#9ca3af; text-transform:uppercase; letter-spacing:.08em; margin-bottom:12px; }
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
  .cta-btn { display:block; text-align:center; background:#e8502a; color:#fff; text-decoration:none; padding:14px 24px; border-radius:10px; font-size:14px; font-weight:700; margin-bottom:20px; }
  .badge { display:inline-block; padding:3px 10px; border-radius:99px; font-size:11px; font-weight:700; }
  .badge-pending   { background:#fef3c7; color:#92400e; }
  .badge-dine      { background:#dbeafe; color:#1e40af; }
  .badge-takeaway  { background:#f3e8ff; color:#6d28d9; }
  .badge-jazzcash  { background:#d1fae5; color:#065f46; }
  .badge-easypaisa { background:#dbeafe; color:#1e40af; }
  .badge-paylater  { background:#f3f4f6; color:#6b7280; }
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
      <div class="header-icon">🔔</div>
      <h1>New Order Received!</h1>
      <p>{{ $order->restaurant->name }} — {{ now()->format('d M Y, h:i A') }}</p>
    </div>

    <div class="body">

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
          <span class="meta-lbl">Phone</span>
          <span class="meta-val">{{ $order->customer_phone }}</span>
        </div>
        <div class="meta-row">
          <span class="meta-lbl">Order Type</span>
          <span class="meta-val">
            <span class="badge {{ $order->type === 'dine_in' ? 'badge-dine' : 'badge-takeaway' }}">
              {{ $order->type === 'dine_in' ? '🍽 Dine-in' : '🥡 Takeaway' }}
            </span>
          </span>
        </div>
        @if($order->table)
        <div class="meta-row">
          <span class="meta-lbl">Table</span>
          <span class="meta-val">Table {{ $order->table->table_number }}</span>
        </div>
        @endif
        <div class="meta-row">
          <span class="meta-lbl">Payment</span>
          <span class="meta-val">
            @if($order->payment_method === 'jazzcash')
              <span class="badge badge-jazzcash">💚 JazzCash</span>
            @elseif($order->payment_method === 'easypaisa')
              <span class="badge badge-easypaisa">💙 Easypaisa</span>
            @else
              <span class="badge badge-paylater">💵 Pay at Counter</span>
            @endif
          </span>
        </div>
        @if($order->notes)
        <div class="meta-row">
          <span class="meta-lbl">Notes</span>
          <span class="meta-val" style="font-style:italic;color:#6b7280;">{{ $order->notes }}</span>
        </div>
        @endif
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

      {{-- CTA --}}
      <a href="{{ config('app.url') }}/dashboard/orders/{{ $order->id }}" class="cta-btn">
        View & Manage Order →
      </a>

      <p style="font-size:12px;color:#9ca3af;text-align:center;line-height:1.6;">
        Log into your dashboard to confirm, prepare and track this order.<br>
        The customer is waiting for your confirmation.
      </p>

    </div>

    <div class="footer">
      <p>This email was sent from <strong>QR Menu SaaS</strong><br>
      {{ $order->restaurant->name }} · {{ config('app.url') }}</p>
    </div>

  </div>
</div>
</body>
</html>