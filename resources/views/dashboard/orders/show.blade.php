@extends('layouts.dashboard')
@section('page-title', 'Order ' . $order->order_number)
@section('content')

<style>
    /* ── Prevent horizontal overflow ── */
html, body { overflow-x: hidden; max-width: 100%; }
* { box-sizing: border-box; min-width: 0; }

/* Long text wrapping (order #, address, reference, email) */
.info-val,
.oi-name,
.oi-variant,
.order-hero-left h2 {
    word-break: break-word;
    overflow-wrap: anywhere;
    white-space: normal;
}

/* Hero & info rows: let right column shrink */
.order-hero { flex-wrap: wrap; }
.order-hero-total { text-align: left; }
.info-row { flex-wrap: wrap; }
.info-val { text-align: left; }

/* Pipeline buttons shouldn't force width */
.pipeline { width: 100%; }
.pipe-btn { min-width: 0; flex: 1 1 80px; }

/* Timeline on small screens */
.tl-wrap { overflow-x: auto; }
.tl-lbl { white-space: normal; }

/* Two-col grid safety */
.two-col { min-width: 0; }
.two-col > * { min-width: 0; }

/* ── Base ── */
.card { background:#141414; border:1px solid #1f1f1f; border-radius:16px; overflow:hidden; }
.card-hdr { padding:14px 18px; border-bottom:1px solid #1f1f1f; display:flex; align-items:center; justify-content:space-between; gap:10px; }
.card-hdr-title { font-size:11px; font-weight:700; color:#505050; text-transform:uppercase; letter-spacing:.08em; }

/* ── Info rows ── */
.info-row { display:flex; justify-content:space-between; align-items:center; gap:12px; padding:11px 18px; border-bottom:1px solid #161616; }
.info-row:last-child { border-bottom:none; }
.info-lbl { font-size:12px; color:#505050; flex-shrink:0; }
.info-val { font-size:13px; font-weight:600; color:#b0b0b0; text-align:right; }

/* ── Badges ── */
.badge { display:inline-flex; align-items:center; gap:5px; padding:4px 12px; border-radius:99px; font-size:11px; font-weight:700; white-space:nowrap; }
.b-pending   { background:#1c0d00; color:#fde68a; border:1px solid #78350f; }
.b-confirmed { background:#0a0f1e; color:#93c5fd; border:1px solid #1e3a5f; }
.b-preparing { background:#1a0f00; color:#fdba74; border:1px solid #7c2d12; }
.b-ready     { background:#001a0a; color:#86efac; border:1px solid #14532d; }
.b-delivered { background:#141414; color:#555;    border:1px solid #2a2a2a; }
.b-cancelled { background:#1a0505; color:#fca5a5; border:1px solid #7f1d1d; }
.b-paid      { background:#001a0a; color:#86efac; border:1px solid #14532d; }
.b-unpaid    { background:#1c0d00; color:#fde68a; border:1px solid #78350f; }

/* ── Order items ── */
.oi-row { display:flex; align-items:center; gap:14px; padding:13px 18px; border-bottom:1px solid #161616; transition:background .15s; }
.oi-row:last-child { border-bottom:none; }
.oi-row:hover { background:#181818; }
.oi-index { width:24px; height:24px; border-radius:99px; background:#1f1f1f; border:1px solid #2a2a2a; display:flex; align-items:center; justify-content:center; font-size:10px; font-weight:700; color:#555; flex-shrink:0; }
.oi-body  { flex:1; min-width:0; }
.oi-name  { font-size:13px; font-weight:600; color:#e0e0e0; }
.oi-variant { font-size:11px; color:#505050; margin-top:2px; }
.oi-meta  { font-size:11px; color:#505050; margin-top:2px; }
.oi-price { text-align:right; flex-shrink:0; }
.oi-total { font-size:14px; font-weight:800; color:#e8502a; }
.oi-unit  { font-size:11px; color:#505050; margin-top:2px; }

/* ── Status Pipeline ── */
.pipeline-wrap { padding:16px 18px; }
.pipeline-label { font-size:10px; font-weight:700; color:#505050; text-transform:uppercase; letter-spacing:.08em; margin-bottom:10px; }
.pipeline { display:flex; gap:6px; flex-wrap:wrap; }
.pipe-btn {
    flex:1; min-width:90px; padding:10px 8px;
    border-radius:10px; font-size:11px; font-weight:700;
    cursor:pointer; border:1px solid transparent;
    text-align:center; transition:all .18s;
    line-height:1.4;
}
.pipe-btn:disabled { opacity:.3; cursor:not-allowed; }
.pipe-confirm { background:#0a0f1e; color:#93c5fd; border-color:#1e3a5f; }
.pipe-confirm:not(:disabled):hover { background:#0f1729; border-color:#3b82f6; }
.pipe-prepare { background:#1a0f00; color:#fdba74; border-color:#7c2d12; }
.pipe-prepare:not(:disabled):hover { background:#261800; border-color:#ea580c; }
.pipe-ready   { background:#001a0a; color:#86efac; border-color:#14532d; }
.pipe-ready:not(:disabled):hover   { background:#063d1e; border-color:#22c55e; }
.pipe-deliver { background:#141414; color:#888;    border-color:#2a2a2a; }
.pipe-deliver:not(:disabled):hover { color:#ccc; border-color:#444; }
.pipe-cancel  { background:#1a0505; color:#fca5a5; border-color:#7f1d1d; }
.pipe-cancel:not(:disabled):hover  { background:#2d0a0a; }

/* ── Timeline ── */
.tl-wrap { display:flex; align-items:flex-start; padding:16px 18px; gap:0; }
.tl-step { display:flex; flex-direction:column; align-items:center; flex:1; }
.tl-dot { width:32px; height:32px; border-radius:99px; border:2px solid #222; background:#111; display:flex; align-items:center; justify-content:center; font-size:13px; flex-shrink:0; transition:all .3s; }
.tl-dot.done   { background:#14532d; border-color:#22c55e; color:#86efac; }
.tl-dot.active { background:#e8502a; border-color:#e8502a; color:#fff; box-shadow:0 0 0 5px rgba(232,80,42,.15); }
.tl-lbl { font-size:9px; color:#555; margin-top:5px; text-align:center; white-space:nowrap; letter-spacing:.03em; }
.tl-line { flex:1; height:2px; background:#1f1f1f; margin-top:15px; transition:background .3s; }
.tl-line.done { background:#22c55e; }

/* ── WhatsApp btn ── */
.wa-btn { display:flex; align-items:center; justify-content:center; gap:8px; padding:11px 16px; background:#0a2e14; color:#86efac; border:1px solid #14532d; border-radius:10px; font-size:13px; font-weight:700; text-decoration:none; transition:all .18s; }
.wa-btn:hover { background:#0f3d1c; border-color:#22c55e; }
.wa-btn svg { width:16px; height:16px; flex-shrink:0; }

/* ── Grid ── */
.two-col { display:grid; grid-template-columns:1fr; gap:14px; }
@media(min-width:960px){ .two-col { grid-template-columns:1fr 320px; } }

/* ── Back link ── */
.back-link { display:inline-flex; align-items:center; gap:6px; font-size:12px; color:#505050; text-decoration:none; margin-bottom:20px; transition:color .15s; }
.back-link:hover { color:#888; }
.back-link svg { width:14px; height:14px; }

/* ── Flash ── */
.flash-ok  { background:#001a0a; border:1px solid rgba(34,197,94,.25); border-radius:10px; padding:12px 16px; margin-bottom:16px; font-size:13px; color:#86efac; }
.flash-err { background:#1a0505; border:1px solid rgba(239,68,68,.25);  border-radius:10px; padding:12px 16px; margin-bottom:16px; font-size:13px; color:#fca5a5; }

/* ── Proof image ── */
.proof-img { width:100%; border-radius:10px; border:1px solid #1f1f1f; cursor:zoom-in; transition:opacity .18s; }
.proof-img:hover { opacity:.85; }

/* ── Confirm pay btn ── */
.btn-confirm-pay { width:100%; padding:11px; background:#001a0a; color:#86efac; border:1px solid #14532d; border-radius:10px; font-size:13px; font-weight:700; cursor:pointer; transition:all .18s; }
.btn-confirm-pay:hover { background:#063d1e; border-color:#22c55e; }

/* ── Order number hero ── */
.order-hero { background:linear-gradient(135deg,#1a1a1a,#141414); border:1px solid #1f1f1f; border-radius:16px; padding:20px 22px; margin-bottom:16px; display:flex; align-items:flex-start; justify-content:space-between; gap:12px; flex-wrap:wrap; }
.order-hero-left h2 { font-family:monospace; font-size:22px; font-weight:800; color:#fff; letter-spacing:.1em; margin:0 0 8px; }
.order-hero-badges { display:flex; align-items:center; gap:8px; flex-wrap:wrap; }
.order-hero-meta   { font-size:12px; color:#505050; margin-top:8px; line-height:1.6; }
.order-hero-total  { text-align:right; flex-shrink:0; }
.order-hero-total-lbl { font-size:11px; color:#505050; text-transform:uppercase; letter-spacing:.06em; margin-bottom:4px; }
.order-hero-total-amt { font-size:28px; font-weight:800; color:#e8502a; letter-spacing:-.5px; }
</style>

<div style="max-width:1000px;">

    {{-- Back --}}
    <a href="{{ route('dashboard.orders.index') }}" class="back-link">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        All Orders
    </a>

    {{-- Flash messages --}}
    @if(session('success'))
    <div class="flash-ok">✅ {{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="flash-err">❌ {{ session('error') }}</div>
    @endif

    {{-- ═══ ORDER HERO ═══ --}}
    <div class="order-hero">
        <div class="order-hero-left">
            <h2>{{ $order->order_number }}</h2>
            <div class="order-hero-badges">
                <span class="badge b-{{ $order->status }}">{{ $order->status_label }}</span>
                <span class="badge {{ $order->payment_status === 'paid' ? 'b-paid' : 'b-unpaid' }}">
                    {{ $order->payment_status === 'paid' ? '✓ Paid' : '⏳ Unpaid' }}
                </span>
                <span style="font-size:12px;color:#505050;">
                    {{ $order->type === 'dine_in' ? '🍽 Dine-in' : '🥡 Takeaway' }}
                    @if($order->table) · Table {{ $order->table->table_number }}@endif
                    @if($order->branch) · {{ $order->branch->name }}@endif
                </span>
            </div>
            <p class="order-hero-meta">
                Placed {{ $order->created_at->format('d M Y') }} at {{ $order->created_at->format('h:i A') }}
                · {{ $order->created_at->diffForHumans() }}
            </p>
        </div>
        <div class="order-hero-total">
            <p class="order-hero-total-lbl">Order Total</p>
            <p class="order-hero-total-amt">Rs. {{ number_format($order->total, 0) }}</p>
        </div>
    </div>

    {{-- ═══ STATUS PIPELINE ═══ --}}
    @if(!in_array($order->status, ['delivered','cancelled']))
    <div class="card" style="margin-bottom:14px;">
        <div class="pipeline-wrap">
            <p class="pipeline-label">Update Order Status</p>
            <div class="pipeline">
                @php
                    $flow = [
                        'confirmed' => ['label'=> "✅\nConfirm",    'class'=>'pipe-confirm', 'from'=>'pending'],
                        'preparing' => ['label'=> "👨‍🍳\nPreparing", 'class'=>'pipe-prepare', 'from'=>'confirmed'],
                        'ready'     => ['label'=> "🔔\nReady",      'class'=>'pipe-ready',   'from'=>'preparing'],
                        'delivered' => ['label'=> "✓\nDelivered",   'class'=>'pipe-deliver', 'from'=>'ready'],
                        'cancelled' => ['label'=> "✗\nCancel",      'class'=>'pipe-cancel',  'from'=>null],
                    ];
                @endphp
                @foreach($flow as $toStatus => $cfg)
                @php
                    $allowed = $cfg['from'] === $order->status ||
                               ($toStatus === 'cancelled' && !in_array($order->status, ['delivered','cancelled']));
                @endphp
                <form method="POST" action="{{ route('dashboard.orders.status', $order) }}" style="flex:1;min-width:80px;">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="{{ $toStatus }}">
                    <button class="pipe-btn {{ $cfg['class'] }}"
                            {{ !$allowed ? 'disabled' : '' }}
                            style="{{ $order->status === $toStatus ? 'box-shadow:0 0 0 2px rgba(232,80,42,.5);' : '' }}">
                        {!! nl2br(e($cfg['label'])) !!}
                    </button>
                </form>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    {{-- ═══ TWO COLUMN LAYOUT ═══ --}}
    <div class="two-col">

        {{-- ─── LEFT ─── --}}
        <div style="display:flex;flex-direction:column;gap:14px;">

            {{-- Progress Timeline --}}
            <div class="card">
                <div class="card-hdr">
                    <span class="card-hdr-title">Order Progress</span>
                    @if($order->status === 'cancelled')
                    <span class="badge b-cancelled">✗ Cancelled</span>
                    @endif
                </div>
                @php
                    $steps  = ['pending','confirmed','preparing','ready','delivered'];
                    $icons  = ['📋','✅','👨‍🍳','🔔','🎉'];
                    $labels = ['Placed','Confirmed','Preparing','Ready','Done'];
                    $cidx   = array_search($order->status === 'cancelled' ? 'pending' : $order->status, $steps);
                @endphp
                <div class="tl-wrap">
                    @foreach($steps as $i => $step)
                    @php
                        $isDone   = $cidx !== false && $i < $cidx;
                        $isActive = $cidx !== false && $i === $cidx && $order->status !== 'cancelled';
                    @endphp
                    <div class="tl-step">
                        <div class="tl-dot {{ $isDone ? 'done' : ($isActive ? 'active' : '') }}">
                            {{ $isDone ? '✓' : $icons[$i] }}
                        </div>
                        <span class="tl-lbl">{{ $labels[$i] }}</span>
                    </div>
                    @if($i < count($steps) - 1)
                    <div class="tl-line {{ $isDone ? 'done' : '' }}"></div>
                    @endif
                    @endforeach
                </div>
            </div>

            {{-- Order Items --}}
            <div class="card">
                <div class="card-hdr">
                    <span class="card-hdr-title">Order Items</span>
                    <span style="font-size:12px;color:#505050;font-weight:600;">
                        {{ $order->items->count() }} {{ Str::plural('item', $order->items->count()) }}
                    </span>
                </div>

                @foreach($order->items as $i => $item)
                <div class="oi-row">
                    <div class="oi-index">{{ $i + 1 }}</div>
                    <div class="oi-body">
                        <p class="oi-name">{{ $item->product_name }}</p>
                        @if($item->variant_name)
                        <p class="oi-variant">{{ $item->variant_name }}</p>
                        @endif
                        <p class="oi-meta">Qty: {{ $item->quantity }}</p>
                    </div>
                    <div class="oi-price">
                        <p class="oi-total">Rs. {{ number_format($item->subtotal, 0) }}</p>
                        <p class="oi-unit">@ Rs. {{ number_format($item->price, 0) }}</p>
                    </div>
                </div>
                @endforeach

                {{-- Total --}}
                <div style="display:flex;justify-content:space-between;align-items:center;padding:14px 18px;background:#181818;border-top:1px solid #1f1f1f;">
                    <div>
                        <p style="font-size:11px;color:#505050;text-transform:uppercase;letter-spacing:.06em;margin-bottom:2px;">Total Amount</p>
                        <p style="font-size:11px;color:#505050;">{{ $order->items->sum('quantity') }} items</p>
                    </div>
                    <p style="font-size:22px;font-weight:800;color:#fff;">
                        Rs. {{ number_format($order->total, 0) }}
                    </p>
                </div>
            </div>
            @php
                $notes = preg_replace('/\[WA:.*?\]/', '', $order->notes);
            @endphp
            {{-- Customer Notes --}}
            @if(trim($notes))
                <div class="card">
                    <div class="card-hdr">
                        <span class="card-hdr-title">Customer Notes</span>
                        <span style="font-size:16px;">📝</span>
                    </div>
                    <div style="padding:14px 18px;">
                        <p style="font-size:13px;color:#888;line-height:1.65;font-style:italic;">
                            "{{ trim($notes) }}"
                        </p>
                    </div>
                </div>
            @endif

        </div>

        {{-- ─── RIGHT ─── --}}
        <div style="display:flex;flex-direction:column;gap:14px;">

            {{-- Customer --}}
            <div class="card">
                <div class="card-hdr">
                    <span class="card-hdr-title">Customer</span>
                    <div style="width:28px;height:28px;border-radius:99px;background:#1f1f1f;border:1px solid #2a2a2a;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;color:#888;flex-shrink:0;">
                        {{ strtoupper(substr($order->customer_name, 0, 1)) }}
                    </div>
                </div>
                <div class="info-row">
                    <span class="info-lbl">Name</span>
                    <span class="info-val" style="color:#e0e0e0;">{{ $order->customer_name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-lbl">Phone</span>
                    <span class="info-val">
                        <a href="tel:{{ $order->customer_phone }}"
                           style="color:#60a5fa;text-decoration:none;">
                            {{ $order->customer_phone }}
                        </a>
                    </span>
                </div>
                @if($order->customer_address)
                <div class="info-row" style="align-items:flex-start;">
                    <span class="info-lbl">Address</span>
                    <span class="info-val" style="max-width:65%;line-height:1.5;">
                        {{ $order->customer_address }}
                    </span>
                </div>
                @endif

                <div style="padding:12px 16px;border-top:1px solid #1a1a1a;">
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $order->customer_phone) }}?text={{ urlencode('Hi ' . $order->customer_name . '! Your order ' . $order->order_number . ' is now: ' . $order->status_label . '. Thank you for ordering from ' . auth()->user()->restaurant->name . '!') }}"
                       target="_blank" class="wa-btn">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                        </svg>
                        WhatsApp Customer
                    </a>
                </div>
            </div>

            {{-- Order Details --}}
            <div class="card">
                <div class="card-hdr">
                    <span class="card-hdr-title">Order Details</span>
                </div>
                <div class="info-row">
                    <span class="info-lbl">Order #</span>
                    <span class="info-val" style="font-family:monospace;font-size:12px;color:#e0e0e0;">
                        {{ $order->order_number }}
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-lbl">Type</span>
                    <span class="info-val">{{ $order->type === 'dine_in' ? '🍽 Dine-in' : '🥡 Takeaway' }}</span>
                </div>
                @if($order->table)
                <div class="info-row">
                    <span class="info-lbl">Table</span>
                    <span class="info-val">Table {{ $order->table->table_number }}</span>
                </div>
                @endif
                @if($order->branch)
                <div class="info-row">
                    <span class="info-lbl">Branch</span>
                    <span class="info-val">{{ $order->branch->name }}</span>
                </div>
                @endif
                <div class="info-row">
                    <span class="info-lbl">Placed At</span>
                    <span class="info-val">{{ $order->created_at->format('d M Y, h:i A') }}</span>
                </div>
                @if($order->confirmed_at)
                <div class="info-row">
                    <span class="info-lbl">Confirmed</span>
                    <span class="info-val">{{ $order->confirmed_at->format('d M, h:i A') }}</span>
                </div>
                @endif
                @if($order->ready_at)
                <div class="info-row">
                    <span class="info-lbl">Ready At</span>
                    <span class="info-val">{{ $order->ready_at->format('d M, h:i A') }}</span>
                </div>
                @endif
            </div>

            {{-- Payment --}}
            <div class="card">
                <div class="card-hdr">
                    <span class="card-hdr-title">Payment</span>
                    <span class="badge {{ $order->payment_status === 'paid' ? 'b-paid' : 'b-unpaid' }}">
                        {{ $order->payment_status === 'paid' ? '✓ Paid' : '⏳ Pending' }}
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-lbl">Method</span>
                    <span class="info-val">
                        @if($order->payment_method === 'jazzcash')  💚 JazzCash
                        @elseif($order->payment_method === 'easypaisa') 💙 Easypaisa
                        @else 💵 Pay at Counter
                        @endif
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-lbl">Amount</span>
                    <span class="info-val" style="color:#e8502a;font-size:15px;">
                        Rs. {{ number_format($order->total, 0) }}
                    </span>
                </div>
                @if($order->payment_reference)
                <div class="info-row">
                    <span class="info-lbl">Reference</span>
                    <span class="info-val" style="font-family:monospace;font-size:12px;color:#a78bfa;">
                        {{ $order->payment_reference }}
                    </span>
                </div>
                @endif

                {{-- Payment screenshot --}}
                @if($order->payment_proof)
                <div style="padding:14px 18px;border-top:1px solid #1a1a1a;">
                    <p style="font-size:10px;font-weight:700;color:#505050;text-transform:uppercase;letter-spacing:.08em;margin-bottom:10px;">
                        Payment Screenshot
                    </p>
                    <img src="{{ Storage::url($order->payment_proof) }}"
                         alt="Payment proof"
                         class="proof-img"
                         title="Click to view full size"
                         onclick="window.open(this.src,'_blank')">
                </div>
                @endif

                {{-- Confirm payment button --}}
                @if($order->payment_status === 'pending' && ($order->payment_proof || $order->payment_method === 'pay_later'))
                <div style="padding:12px 16px;border-top:1px solid #1a1a1a;">
                    <form method="POST" action="{{ route('dashboard.orders.confirm-pay', $order) }}">
                        @csrf @method('PATCH')
                        <button class="btn-confirm-pay">
                            ✓ Confirm Payment Received
                        </button>
                    </form>
                </div>
                @endif
            </div>

        </div>
    </div>

</div>

@endsection
