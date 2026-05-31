<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Checkout — {{ $restaurant->name }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --bg:#080808; --surface:#111; --surface2:#181818;
            --border:#1e1e1e; --border2:#2a2a2a;
            --text:#f0f0f0; --text2:#909090; --text3:#505050;
            --accent:#e8502a; --accent2:#c43e1c;
            --safe-b: env(safe-area-inset-bottom, 0px);
        }
        body { background:var(--bg);color:var(--text);font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;-webkit-font-smoothing:antialiased;min-height:100vh; }

        /* Header */
        .header { background:var(--surface);border-bottom:1px solid var(--border);padding:12px 16px;display:flex;align-items:center;gap:12px;position:sticky;top:0;z-index:40; }
        .header-back { background:none;border:none;cursor:pointer;color:var(--text2);padding:4px;display:flex;align-items:center; }
        .header-back svg { width:20px;height:20px; }
        .header h1 { font-size:16px;font-weight:700;color:var(--text);flex:1; }

        .main { max-width:640px;margin:0 auto;padding:16px; }

        /* Section cards */
        .section-card { background:var(--surface);border:1px solid var(--border);border-radius:14px;overflow:hidden;margin-bottom:12px; }
        .section-head { padding:14px 16px;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:8px; }
        .section-head span { font-size:18px; }
        .section-head h3 { font-size:14px;font-weight:700;color:var(--text); }
        .section-body { padding:16px; }

        /* Cart items */
        .cart-item { display:flex;align-items:center;gap:12px;padding:10px 0;border-bottom:1px solid var(--border); }
        .cart-item:last-child { border:none; }
        .cart-img { width:44px;height:44px;border-radius:8px;object-fit:cover;flex-shrink:0;border:1px solid var(--border2); }
        .cart-name { font-size:13px;font-weight:600;color:var(--text);flex:1; }
        .cart-variant { font-size:11px;color:var(--text3); }
        .cart-price { font-size:13px;font-weight:700;color:var(--accent);white-space:nowrap; }
        .qty-ctrl { display:flex;align-items:center;gap:6px; }
        .qty-btn { width:26px;height:26px;border-radius:6px;background:var(--surface2);border:1px solid var(--border2);color:var(--text2);font-size:16px;cursor:pointer;display:flex;align-items:center;justify-content:center;line-height:1; }
        .qty-num { font-size:13px;font-weight:600;color:var(--text);min-width:20px;text-align:center; }

        /* Order type */
        .type-grid { display:grid;grid-template-columns:1fr 1fr;gap:10px; }
        .type-card { border:2px solid var(--border2);border-radius:10px;padding:14px;text-align:center;cursor:pointer;transition:all .18s;background:var(--surface2); }
        .type-card.selected { border-color:var(--accent);background:rgba(232,80,42,.08); }
        .type-card .type-icon { font-size:24px;margin-bottom:6px; }
        .type-card .type-name { font-size:13px;font-weight:700;color:var(--text); }
        .type-card .type-desc { font-size:11px;color:var(--text3);margin-top:2px; }

        /* Payment */
        .pay-grid { display:grid;grid-template-columns:1fr 1fr;gap:10px; }
        .pay-card { border:2px solid var(--border2);border-radius:10px;padding:12px;text-align:center;cursor:pointer;transition:all .18s;background:var(--surface2); }
        .pay-card.selected { border-color:var(--accent);background:rgba(232,80,42,.08); }
        .pay-card .pay-icon { font-size:20px;margin-bottom:4px; }
        .pay-card .pay-name { font-size:12px;font-weight:700;color:var(--text); }

        /* Inputs */
        .field { margin-bottom:12px; }
        .field label { display:block;font-size:11px;font-weight:600;color:var(--text2);margin-bottom:5px;text-transform:uppercase;letter-spacing:.05em; }
        .field input, .field textarea, .field select {
            width:100%;background:var(--bg);border:1px solid var(--border2);
            border-radius:8px;padding:10px 12px;font-size:14px;color:var(--text);
            outline:none;transition:border-color .2s;-webkit-appearance:none;
        }
        .field input:focus,.field textarea:focus,.field select:focus { border-color:var(--accent); }
        .field input::placeholder,.field textarea::placeholder { color:var(--text3); }

        /* Payment instructions box */
        .pay-instructions { background:rgba(232,80,42,.06);border:1px solid rgba(232,80,42,.15);border-radius:10px;padding:14px;margin:12px 0; }
        .pay-instructions h4 { font-size:13px;font-weight:700;color:var(--text);margin-bottom:8px; }
        .pay-row { display:flex;justify-content:space-between;padding:6px 0;border-bottom:1px solid rgba(232,80,42,.1);font-size:13px; }
        .pay-row:last-child { border:none; }
        .pay-label { color:var(--text3); }
        .pay-value { font-weight:700;color:var(--text);font-family:monospace; }

        /* Order summary */
        .summary-row { display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--border);font-size:13px; }
        .summary-row:last-child { border:none; }
        .summary-total { font-size:16px;font-weight:800;color:var(--accent); }

        /* Submit button */
        .btn-place { width:100%;background:var(--accent);color:#fff;border:none;border-radius:12px;padding:14px;font-size:15px;font-weight:700;cursor:pointer;transition:background .18s;margin-top:4px; }
        .btn-place:hover { background:var(--accent2); }
        .btn-place:disabled { background:#333;color:#666;cursor:not-allowed; }

        /* Upload area */
        .upload-area { border:1px dashed var(--border2);border-radius:8px;padding:16px;text-align:center;cursor:pointer;transition:border-color .2s; }
        .upload-area:hover { border-color:var(--accent); }

        /* Hidden sections */
        .payment-extra { display:none; }

        @media(max-width:420px){ .type-grid,.pay-grid { grid-template-columns:1fr; } }
    </style>
</head>
<body>

<header class="header">
    <a href="{{ route('menu.show', $restaurant->slug) }}" class="header-back">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
    </a>
    <h1>Checkout — {{ $restaurant->name }}</h1>
</header>

<main class="main">
<form method="POST" action="{{ route('order.place', $restaurant->slug) }}" enctype="multipart/form-data" id="checkoutForm">
@csrf

{{-- ── Cart Items ── --}}
<div class="section-card">
    <div class="section-head">
        <span>🛒</span>
        <h3>Your Order</h3>
    </div>
    <div class="section-body" id="cartBody">
        @php $total = 0; @endphp
        @foreach($cartData as $key => $item)
        @php $itemTotal = $item['price'] * $item['quantity']; $total += $itemTotal; @endphp
        <div class="cart-item" id="item-{{ $key }}">
            <img src="{{ $item['image'] }}" alt="{{ $item['product_name'] }}" class="cart-img">
            <div style="flex:1;min-width:0;">
                <p class="cart-name">{{ $item['product_name'] }}</p>
                @if($item['variant_name'])<p class="cart-variant">{{ $item['variant_name'] }}</p>@endif
            </div>
            <div style="display:flex;flex-direction:column;align-items:flex-end;gap:6px;">
                <span class="cart-price">Rs. {{ number_format($itemTotal, 0) }}</span>
                <div class="qty-ctrl">
                    <button type="button" class="qty-btn" onclick="updateQty('{{ $key }}', -1, '{{ $restaurant->slug }}')">−</button>
                    <span class="qty-num" id="qty-{{ $key }}">{{ $item['quantity'] }}</span>
                    <button type="button" class="qty-btn" onclick="updateQty('{{ $key }}', 1, '{{ $restaurant->slug }}')">+</button>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

{{-- ── Order Type ── --}}
<div class="section-card">
    <div class="section-head"><span>🍽</span><h3>Order Type</h3></div>
    <div class="section-body">
        <div class="type-grid">
            <label>
                <input type="radio" name="order_type" value="dine_in" style="display:none;" onchange="handleOrderType('dine_in')">
                <div class="type-card" id="type-dine_in">
                    <div class="type-icon">🍽</div>
                    <div class="type-name">Dine-in</div>
                    <div class="type-desc">Eat at the restaurant</div>
                </div>
            </label>
            <label>
                <input type="radio" name="order_type" value="takeaway" style="display:none;" onchange="handleOrderType('takeaway')">
                <div class="type-card" id="type-takeaway">
                    <div class="type-icon">🥡</div>
                    <div class="type-name">Takeaway</div>
                    <div class="type-desc">Pick up your order</div>
                </div>
            </label>
        </div>

        <div id="table-field" style="display:none;margin-top:12px;">
            <div class="field">
                <label>Table Number</label>
                <input type="text" name="table_number" placeholder="e.g. T5 or 12">
            </div>
        </div>
    </div>
</div>

{{-- ── Customer Info ── --}}
<div class="section-card">
    <div class="section-head"><span>👤</span><h3>Your Details</h3></div>
    <div class="section-body">
        <div class="field">
            <label>Your Name *</label>
            <input type="text" name="customer_name" placeholder="Enter your name" required>
        </div>
        <div class="field">
            <label>Phone Number *</label>
            <input type="tel" name="customer_phone" placeholder="0300-1234567" required>
        </div>
        <div class="field">
            <label>Special Instructions</label>
            <textarea name="notes" rows="2" placeholder="Any special requests? (optional)"></textarea>
        </div>
    </div>
</div>

{{-- ── Payment ── --}}
<div class="section-card">
    <div class="section-head"><span>💳</span><h3>Payment Method</h3></div>
    <div class="section-body">
        <div class="pay-grid">
            @if($restaurant->jazzcash_number)
            <label>
                <input type="radio" name="payment_method" value="jazzcash" style="display:none;" onchange="handlePayment('jazzcash')">
                <div class="pay-card" id="pay-jazzcash">
                    <div class="pay-icon">📱</div>
                    <div class="pay-name">JazzCash</div>
                </div>
            </label>
            @endif
            @if($restaurant->easypaisa_number)
            <label>
                <input type="radio" name="payment_method" value="easypaisa" style="display:none;" onchange="handlePayment('easypaisa')">
                <div class="pay-card" id="pay-easypaisa">
                    <div class="pay-icon">🟢</div>
                    <div class="pay-name">Easypaisa</div>
                </div>
            </label>
            @endif
            <label>
                <input type="radio" name="payment_method" value="cash" style="display:none;" checked onchange="handlePayment('cash')">
                <div class="pay-card selected" id="pay-cash">
                    <div class="pay-icon">💵</div>
                    <div class="pay-name">Cash</div>
                </div>
            </label>
        </div>

        {{-- JazzCash instructions --}}
        @if($restaurant->jazzcash_number)
        <div class="payment-extra" id="extra-jazzcash">
            <div class="pay-instructions">
                <h4>📱 Send via JazzCash</h4>
                <div class="pay-row"><span class="pay-label">JazzCash Number</span><span class="pay-value">{{ $restaurant->jazzcash_number }}</span></div>
                <div class="pay-row"><span class="pay-label">Amount to Send</span><span class="pay-value">Rs. {{ number_format($total, 0) }}</span></div>
            </div>
            <div class="field">
                <label>Transaction Reference *</label>
                <input type="text" name="payment_ref" placeholder="JazzCash transaction ID">
            </div>
            <div class="field">
                <label>Payment Screenshot *</label>
                <div class="upload-area" onclick="document.getElementById('proofImg').click()">
                    <div id="proofPreview" style="display:none;margin-bottom:8px;">
                        <img id="proofThumb" style="max-height:80px;border-radius:6px;margin:0 auto;display:block;">
                    </div>
                    <p style="color:var(--text3);font-size:13px;" id="proofLabel">📎 Upload screenshot</p>
                </div>
                <input type="file" id="proofImg" name="payment_proof" accept="image/*" style="display:none;" onchange="previewProof(this)">
            </div>
        </div>
        @endif

        {{-- Easypaisa instructions --}}
        @if($restaurant->easypaisa_number)
        <div class="payment-extra" id="extra-easypaisa">
            <div class="pay-instructions">
                <h4>🟢 Send via Easypaisa</h4>
                <div class="pay-row"><span class="pay-label">Easypaisa Number</span><span class="pay-value">{{ $restaurant->easypaisa_number }}</span></div>
                @if($restaurant->easypaisa_name)<div class="pay-row"><span class="pay-label">Account Name</span><span class="pay-value">{{ $restaurant->easypaisa_name }}</span></div>@endif
                <div class="pay-row"><span class="pay-label">Amount to Send</span><span class="pay-value">Rs. {{ number_format($total, 0) }}</span></div>
            </div>
            <div class="field">
                <label>Transaction Reference *</label>
                <input type="text" name="payment_ref" placeholder="Easypaisa transaction ID">
            </div>
            <div class="field">
                <label>Payment Screenshot *</label>
                <div class="upload-area" onclick="document.getElementById('proofImg').click()">
                    <div id="proofPreview2" style="display:none;margin-bottom:8px;">
                        <img id="proofThumb2" style="max-height:80px;border-radius:6px;margin:0 auto;display:block;">
                    </div>
                    <p style="color:var(--text3);font-size:13px;">📎 Upload screenshot</p>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

{{-- ── Order Summary ── --}}
<div class="section-card">
    <div class="section-head"><span>🧾</span><h3>Order Summary</h3></div>
    <div class="section-body">
        <div class="summary-row">
            <span style="color:var(--text2);">Subtotal</span>
            <span>Rs. {{ number_format($total, 0) }}</span>
        </div>
        <div class="summary-row">
            <span style="color:var(--text2);">Estimated Wait</span>
            <span>~{{ $restaurant->estimated_wait_minutes ?? 30 }} mins</span>
        </div>
        <div class="summary-row">
            <span style="font-weight:700;color:var(--text);">Total</span>
            <span class="summary-total">Rs. {{ number_format($total, 0) }}</span>
        </div>
    </div>
</div>

{{-- Place Order --}}
<button type="submit" class="btn-place" id="placeBtn">
    🚀 Place Order — Rs. {{ number_format($total, 0) }}
</button>

<p style="font-size:11px;color:var(--text3);text-align:center;margin-top:12px;padding-bottom:calc(16px + var(--safe-b));">
    By placing this order you agree to our terms.
</p>

</form>
</main>

<script>
function handleOrderType(type) {
    document.querySelectorAll('.type-card').forEach(c => c.classList.remove('selected'));
    document.getElementById('type-' + type).classList.add('selected');
    document.getElementById('table-field').style.display = type === 'dine_in' ? 'block' : 'none';
}

function handlePayment(method) {
    document.querySelectorAll('.pay-card').forEach(c => c.classList.remove('selected'));
    document.getElementById('pay-' + method).classList.add('selected');
    document.querySelectorAll('.payment-extra').forEach(e => e.style.display = 'none');
    const extra = document.getElementById('extra-' + method);
    if (extra) extra.style.display = 'block';
}

function updateQty(key, delta, slug) {
    const qtyEl = document.getElementById('qty-' + key);
    let qty = parseInt(qtyEl.textContent) + delta;
    if (qty < 0) qty = 0;

    fetch('/r/' + slug + '/cart/update', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({ key, quantity: qty }),
    })
    .then(r => r.json())
    .then(data => {
        if (qty === 0) {
            document.getElementById('item-' + key)?.remove();
        } else {
            qtyEl.textContent = qty;
        }
        if (data.cart_count === 0) window.location.href = '/r/' + slug;
    });
}

function previewProof(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('proofThumb').src = e.target.result;
            document.getElementById('proofPreview').style.display = 'block';
            document.getElementById('proofLabel').textContent = '✓ ' + input.files[0].name;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

document.getElementById('checkoutForm').addEventListener('submit', function() {
    const btn = document.getElementById('placeBtn');
    btn.disabled = true;
    btn.textContent = 'Placing Order...';
});
</script>
</body>
</html>