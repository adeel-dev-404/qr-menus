<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="#080808">
    <title>Order {{ $order->order_number }} — {{ $restaurant->name }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        *, *::before, *::after { box-sizing:border-box; margin:0; padding:0; }
        :root {
            --bg:#080808; --surface:#111; --surface2:#181818;
            --border:#1e1e1e; --border2:#2a2a2a;
            --text:#f0f0f0; --text2:#909090; --text3:#505050;
            --accent:#e8502a; --accent2:#c43e1c;
            --green:#22c55e; --yellow:#f59e0b; --blue:#3b82f6; --red:#ef4444;
            --safe-b:env(safe-area-inset-bottom,0px);
        }
        body { background:var(--bg); color:var(--text); font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif; -webkit-font-smoothing:antialiased; min-height:100vh; padding-bottom:calc(24px + var(--safe-b)); }

        .page { max-width:560px; margin:0 auto; padding:20px 16px; }

        /* Header */
        .top-bar { display:flex; align-items:center; gap:12px; margin-bottom:24px; }
        .brand-icon { width:38px;height:38px;border-radius:10px;background:linear-gradient(135deg,#e8502a,#c43e1c);display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0; }
        .brand-name { font-size:15px;font-weight:700;color:var(--text); }
        .brand-sub  { font-size:11px;color:var(--text3); }

        /* Status card */
        .status-card { border-radius:16px; padding:20px; margin-bottom:16px; text-align:center; }
        .status-icon { font-size:44px; margin-bottom:12px; }
        .status-title { font-size:18px; font-weight:800; color:var(--text); margin-bottom:4px; }
        .status-sub   { font-size:13px; color:var(--text2); }
        .order-num    { display:inline-block; font-family:monospace; font-size:13px; font-weight:700; padding:4px 12px; border-radius:99px; margin-top:10px; }

        /* Status colors */
        .s-pending   { background:#1c1100; border:1px solid #92400e; }
        .s-confirmed { background:#0f172a; border:1px solid #1e3a5f; }
        .s-preparing { background:#1c1100; border:1px solid #92400e; }
        .s-ready     { background:#052e16; border:1px solid #166534; }
        .s-delivered { background:var(--surface); border:1px solid var(--border2); }
        .s-cancelled { background:#2d0a0a; border:1px solid #7f1d1d; }
        .badge-pending   { background:#422006; color:#fde68a; }
        .badge-confirmed { background:#0f1729; color:#93c5fd; }
        .badge-preparing { background:#422006; color:#fb923c; }
        .badge-ready     { background:#052e16; color:#86efac; }
        .badge-delivered { background:var(--surface2); color:var(--text3); }
        .badge-cancelled { background:#2d0a0a; color:#fca5a5; }

        /* Timeline */
        .timeline { display:flex; align-items:flex-start; justify-content:space-between; padding:20px 0; margin-bottom:16px; }
        .tl-step { display:flex; flex-direction:column; align-items:center; flex:1; }
        .tl-dot { width:32px;height:32px;border-radius:99px;border:2px solid var(--border2);background:var(--surface2);display:flex;align-items:center;justify-content:center;font-size:13px;transition:all .3s;flex-shrink:0; }
        .tl-dot.done   { background:var(--green);border-color:var(--green);color:#fff; }
        .tl-dot.active { background:var(--accent);border-color:var(--accent);color:#fff;box-shadow:0 0 0 4px rgba(232,80,42,.2); }
        .tl-label { font-size:9px;color:var(--text3);margin-top:5px;text-align:center;white-space:nowrap; }
        .tl-line { flex:1;height:2px;background:var(--border2);margin-top:15px;transition:background .3s; }
        .tl-line.done { background:var(--green); }

        /* Cards */
        .card { background:var(--surface);border:1px solid var(--border);border-radius:14px;overflow:hidden;margin-bottom:12px; }
        .card-header { padding:14px 16px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between; }
        .card-header h3 { font-size:13px;font-weight:700;color:var(--text2);text-transform:uppercase;letter-spacing:.05em; }

        /* Order items */
        .order-item { display:flex;align-items:center;gap:12px;padding:12px 16px;border-bottom:1px solid var(--border); }
        .order-item:last-child { border-bottom:none; }
        .item-img { width:42px;height:42px;border-radius:8px;object-fit:cover;border:1px solid var(--border2);flex-shrink:0; }
        .item-info { flex:1;min-width:0; }
        .item-name { font-size:13px;font-weight:600;color:var(--text); }
        .item-variant { font-size:11px;color:var(--text3);margin-top:1px; }
        .item-qty-price { text-align:right;flex-shrink:0; }
        .item-price { font-size:13px;font-weight:700;color:var(--accent); }
        .item-qty   { font-size:11px;color:var(--text3); }

        /* Total row */
        .total-row { display:flex;justify-content:space-between;align-items:center;padding:14px 16px; }
        .total-label { font-size:14px;font-weight:600;color:var(--text2); }
        .total-amt   { font-size:18px;font-weight:800;color:var(--text); }

        /* Info rows */
        .info-row { display:flex;justify-content:space-between;align-items:flex-start;padding:10px 16px;border-bottom:1px solid var(--border); }
        .info-row:last-child { border-bottom:none; }
        .info-label { font-size:12px;color:var(--text3); }
        .info-value { font-size:12px;font-weight:600;color:var(--text2);text-align:right;max-width:60%; }

        /* Payment proof form */
        .proof-form { padding:16px; }
        .proof-label { display:block;font-size:11px;font-weight:600;color:var(--text3);text-transform:uppercase;letter-spacing:.05em;margin-bottom:6px; }
        .proof-input { width:100%;background:var(--bg);border:1px solid var(--border2);border-radius:8px;padding:9px 12px;font-size:14px;color:var(--text);outline:none;transition:border-color .2s;margin-bottom:10px; }
        .proof-input:focus { border-color:var(--accent); }
        .proof-input::placeholder { color:var(--text3); }
        .proof-upload { border:1px dashed var(--border2);border-radius:8px;padding:16px;text-align:center;cursor:pointer;margin-bottom:10px; }
        .proof-upload:hover { border-color:var(--accent); }
        .proof-upload p { font-size:13px;color:var(--text3); }
        .proof-upload small { font-size:11px;color:var(--text3); }

        /* Buttons */
        .btn-primary { width:100%;padding:13px;background:var(--accent);color:#fff;border:none;border-radius:10px;font-size:14px;font-weight:700;cursor:pointer;transition:background .18s; }
        .btn-primary:hover { background:var(--accent2); }
        .btn-wa { width:100%;padding:13px;background:#25D366;color:#fff;border:none;border-radius:10px;font-size:14px;font-weight:700;cursor:pointer;text-decoration:none;display:block;text-align:center;transition:opacity .2s;margin-top:10px; }
        .btn-wa:hover { opacity:.88; }
        .btn-back { display:flex;align-items:center;gap:6px;font-size:13px;color:var(--text3);text-decoration:none;margin-top:14px;justify-content:center; }
        .btn-back:hover { color:var(--text2); }
        .btn-back svg { width:14px;height:14px; }

        /* Refresh */
        .refresh-note { text-align:center;font-size:12px;color:var(--text3);margin-top:12px; }
        .refresh-note a { color:var(--accent); }

        /* Flash */
        .flash-success { background:#052e16;border:1px solid rgba(34,197,94,.3);border-radius:10px;padding:12px 14px;margin-bottom:16px; }
        .flash-success p { font-size:13px;color:#86efac; }
    </style>
</head>
<body>
<div class="page">

    {{-- Top bar --}}
    <div class="top-bar">
        <div class="brand-icon">🍽</div>
        <div>
            <p class="brand-name">{{ $restaurant->name }}</p>
            <p class="brand-sub">Order Tracking</p>
        </div>
    </div>

    {{-- Flash --}}
    @if(session('success'))
    <div class="flash-success"><p>✅ {{ session('success') }}</p></div>
    @endif

    {{-- Status Card --}}
    @php
        $statusConfig = [
            'pending'   => ['icon'=>'⏳', 'title'=>'Order Received',    'sub'=>'Waiting for restaurant to confirm.',    'cls'=>'s-pending',   'badge'=>'badge-pending'],
            'confirmed' => ['icon'=>'✅', 'title'=>'Order Confirmed',   'sub'=>'Your order is confirmed!',              'cls'=>'s-confirmed', 'badge'=>'badge-confirmed'],
            'preparing' => ['icon'=>'👨‍🍳', 'title'=>'Being Prepared',  'sub'=>'Your food is being prepared.',          'cls'=>'s-preparing', 'badge'=>'badge-preparing'],
            'ready'     => ['icon'=>'🔔', 'title'=>'Ready!',            'sub'=>'Your order is ready for pickup/serving.','cls'=>'s-ready',    'badge'=>'badge-ready'],
            'delivered' => ['icon'=>'🎉', 'title'=>'Order Complete',    'sub'=>'Enjoy your meal!',                      'cls'=>'s-delivered', 'badge'=>'badge-delivered'],
            'cancelled' => ['icon'=>'❌', 'title'=>'Order Cancelled',   'sub'=>'This order was cancelled.',             'cls'=>'s-cancelled', 'badge'=>'badge-cancelled'],
        ];
        $sc = $statusConfig[$order->status] ?? $statusConfig['pending'];
        $steps = ['pending','confirmed','preparing','ready','delivered'];
        $currentIdx = array_search($order->status, $steps);
    @endphp

    <div class="status-card {{ $sc['cls'] }}">
        <div class="status-icon">{{ $sc['icon'] }}</div>
        <p class="status-title">{{ $sc['title'] }}</p>
        <p class="status-sub">{{ $sc['sub'] }}</p>
        <span class="order-num {{ $sc['badge'] }}">{{ $order->order_number }}</span>
    </div>

    {{-- Progress Timeline --}}
    @if($order->status !== 'cancelled')
    <div class="card" style="padding:16px;">
        <div class="timeline">
            @foreach($steps as $i => $step)
            @php
                $isDone   = $currentIdx !== false && $i < $currentIdx;
                $isActive = $currentIdx !== false && $i === $currentIdx;
                $labels   = ['Placed','Confirmed','Preparing','Ready','Done'];
                $icons    = ['📋','✅','👨‍🍳','🔔','🎉'];
            @endphp
            <div class="tl-step">
                <div class="tl-dot {{ $isDone ? 'done' : ($isActive ? 'active' : '') }}">
                    {{ $isDone ? '✓' : $icons[$i] }}
                </div>
                <span class="tl-label">{{ $labels[$i] }}</span>
            </div>
            @if($i < count($steps) - 1)
            <div class="tl-line {{ $isDone ? 'done' : '' }}"></div>
            @endif
            @endforeach
        </div>
    </div>
    @endif

    {{-- Order Items --}}
    <div class="card">
        <div class="card-header">
            <h3>Your Order</h3>
            <span style="font-size:12px;color:var(--text3);">{{ $order->items->count() }} items</span>
        </div>
        @foreach($order->items as $item)
        <div class="order-item">
            <div class="item-info">
                <p class="item-name">{{ $item->product_name }}</p>
                @if($item->variant_name)
                <p class="item-variant">{{ $item->variant_name }}</p>
                @endif
            </div>
            <div class="item-qty-price">
                <p class="item-price">Rs. {{ number_format($item->subtotal, 0) }}</p>
                <p class="item-qty">x{{ $item->quantity }} × Rs. {{ number_format($item->price, 0) }}</p>
            </div>
        </div>
        @endforeach
        <div class="total-row">
            <span class="total-label">Total</span>
            <span class="total-amt">Rs. {{ number_format($order->total, 0) }}</span>
        </div>
    </div>

    {{-- Order Details --}}
    <div class="card">
        <div class="card-header"><h3>Order Details</h3></div>
        <div class="info-row">
            <span class="info-label">Order Type</span>
            <span class="info-value">{{ $order->type === 'dine_in' ? '🍽 Dine-in' : '🥡 Takeaway' }}</span>
        </div>
        @if($order->table)
        <div class="info-row">
            <span class="info-label">Table</span>
            <span class="info-value">Table {{ $order->table->table_number }}</span>
        </div>
        @endif
        @if($order->branch)
        <div class="info-row">
            <span class="info-label">Branch</span>
            <span class="info-value">{{ $order->branch->name }}</span>
        </div>
        @endif
        <div class="info-row">
            <span class="info-label">Customer</span>
            <span class="info-value">{{ $order->customer_name }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Phone</span>
            <span class="info-value">{{ $order->customer_phone }}</span>
        </div>
        @if($order->customer_address)
        <div class="info-row">
            <span class="info-label">Address</span>
            <span class="info-value">{{ $order->customer_address }}</span>
        </div>
        @endif
        <div class="info-row">
            <span class="info-label">Payment</span>
            <span class="info-value">
                @if($order->payment_method === 'jazzcash')  💚 JazzCash
                @elseif($order->payment_method === 'easypaisa') 💙 Easypaisa
                @else 💵 Pay at Counter
                @endif
                —
                <span style="color:{{ $order->payment_status === 'paid' ? '#86efac' : '#f59e0b' }}">
                    {{ $order->payment_status === 'paid' ? 'Paid ✓' : 'Pending' }}
                </span>
            </span>
        </div>
        @if($order->notes)
        <div class="info-row">
            <span class="info-label">Notes</span>
            <span class="info-value">{{ $order->notes }}</span>
        </div>
        @endif
        <div class="info-row">
            <span class="info-label">Placed At</span>
            <span class="info-value">{{ $order->created_at->format('d M Y, h:i A') }}</span>
        </div>
    </div>

    {{-- Payment Proof Upload (if payment pending and method is jazzcash/easypaisa) --}}
    @if($order->payment_status === 'pending' && in_array($order->payment_method, ['jazzcash','easypaisa']))
    <div class="card">
        <div class="card-header"><h3>Submit Payment Proof</h3></div>
        <div class="proof-form">
            <p style="font-size:13px;color:var(--text2);margin-bottom:14px;">
                Please transfer
                <strong style="color:var(--accent);">Rs. {{ number_format($order->total, 0) }}</strong>
                to
                @if($order->payment_method === 'jazzcash')
                    <strong style="color:#86efac;">JazzCash: {{ $restaurant->jazzcash_number }}</strong>
                @else
                    <strong style="color:#93c5fd;">Easypaisa: {{ $restaurant->easypaisa_number }}</strong>
                @endif
                then upload your receipt below.
            </p>

            <form method="POST"
                  action="{{ route('order.pay', [$restaurant->slug, $order->id]) }}"
                  enctype="multipart/form-data">
                @csrf

                <label class="proof-label">Transaction Reference *</label>
                <input type="text" name="payment_reference"
                       class="proof-input" placeholder="e.g. TXN-20240515-001" required>

                <label class="proof-label">Payment Screenshot *</label>
                <div class="proof-upload" onclick="document.getElementById('proofFile').click()">
                    <div id="proofPreview" style="display:none;margin-bottom:8px;">
                        <img id="proofImg" style="max-height:120px;border-radius:8px;margin:0 auto;display:block;">
                    </div>
                    <p id="proofText">📸 Tap to upload screenshot</p>
                    <small>JPG, PNG — Max 3MB</small>
                    <input type="file" id="proofFile" name="payment_proof"
                           accept="image/*" style="display:none;"
                           onchange="previewProof(this)">
                </div>

                <button type="submit" class="btn-primary">Submit Payment Proof</button>
            </form>
        </div>
    </div>
    @endif

    {{-- WhatsApp restaurant --}}
    @if($restaurant->whatsapp_number)
    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $restaurant->whatsapp_number) }}"
       target="_blank" class="btn-wa">
        💬 Contact Restaurant on WhatsApp
    </a>
    @endif

    {{-- Refresh note --}}
    @if(!in_array($order->status, ['delivered','cancelled']))
    <p class="refresh-note">
        Order status updates automatically.
        <a href="{{ route('order.show', [$restaurant->slug, $order->id]) }}">Refresh ↻</a>
    </p>
    @endif

    {{-- Back to menu --}}
    <a href="{{ route('menu.show', $restaurant->slug) }}" class="btn-back">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back to Menu
    </a>

</div>

<script>
function previewProof(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('proofImg').src = e.target.result;
            document.getElementById('proofPreview').style.display = 'block';
            document.getElementById('proofText').textContent = '✅ Screenshot selected';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Auto-refresh every 30 seconds if order is active
@if(!in_array($order->status, ['delivered','cancelled']))
setTimeout(() => location.reload(), 30000);
@endif
</script>
</body>
</html>