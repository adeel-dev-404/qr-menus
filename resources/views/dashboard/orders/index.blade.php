@extends('layouts.dashboard')
@section('page-title', 'Orders')
@section('content')

<style>
.stat-card { background:#1a1a1a;border:1px solid #222;border-radius:12px;padding:16px; }
.stat-grid { display:grid;grid-template-columns:repeat(2,1fr);gap:12px; }
@media(min-width:768px){ .stat-grid { grid-template-columns:repeat(4,1fr); } }
.order-card { background:#1a1a1a;border:1px solid #222;border-radius:14px;overflow:hidden;margin-bottom:10px;transition:border-color .2s; }
.order-card:hover { border-color:#2a2a2a; }
.status-badge { padding:3px 10px;border-radius:99px;font-size:11px;font-weight:700;display:inline-flex;align-items:center;gap:4px; }
.s-pending   { background:#422006;color:#fde68a;border:1px solid #92400e; }
.s-confirmed { background:#0f172a;color:#93c5fd;border:1px solid #1e3a5f; }
.s-preparing { background:#1c1100;color:#fed7aa;border:1px solid #92400e; }
.s-ready     { background:#052e16;color:#86efac;border:1px solid #166534; }
.s-completed { background:#111;color:#555;border:1px solid #222; }
.s-cancelled { background:#2d0a0a;color:#fca5a5;border:1px solid #7f1d1d; }
.filter-input { background:#111;border:1px solid #2a2a2a;border-radius:8px;padding:8px 12px;color:#e2e8f0;font-size:13px;outline:none; }
.filter-input:focus { border-color:#e8502a; }
.btn-sm { padding:6px 12px;border-radius:7px;font-size:12px;font-weight:600;cursor:pointer;text-decoration:none;border:none;display:inline-block; }
.btn-confirm  { background:#0f172a;color:#60a5fa;border:1px solid #1e3a5f; }
.btn-prepare  { background:#1c1100;color:#fb923c;border:1px solid #78350f; }
.btn-ready    { background:#052e16;color:#4ade80;border:1px solid #166534; }
.btn-complete { background:#111;color:#888;border:1px solid #222; }
.btn-cancel   { background:#2d0a0a;color:#fca5a5;border:1px solid #7f1d1d; }
.btn-view     { background:#1a1a1a;color:#aaa;border:1px solid #2a2a2a; }
.pay-badge-paid    { background:#052e16;color:#86efac;border:1px solid #166534;padding:2px 8px;border-radius:99px;font-size:10px;font-weight:700; }
.pay-badge-pending { background:#422006;color:#fde68a;border:1px solid #92400e;padding:2px 8px;border-radius:99px;font-size:10px;font-weight:700; }
.pay-badge-unpaid  { background:#1a1a1a;color:#555;border:1px solid #222;padding:2px 8px;border-radius:99px;font-size:10px;font-weight:700; }
</style>

<div style="max-width:1000px;display:flex;flex-direction:column;gap:16px;">

    {{-- Header --}}
    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
        <div>
            <h2 style="font-size:20px;font-weight:700;color:#fff;margin:0;">Orders</h2>
            <p style="font-size:13px;color:#666;margin:4px 0 0;">Manage incoming orders in real time</p>
        </div>
        <a href="{{ route('dashboard.settings.ordering') }}"
           style="padding:8px 16px;background:#1a1a1a;color:#aaa;border:1px solid #2a2a2a;border-radius:8px;font-size:13px;text-decoration:none;">
            ⚙️ Ordering Settings
        </a>
    </div>

    {{-- Stats --}}
    <div class="stat-grid">
        <div class="stat-card text-center" style="text-align:center;">
            <p style="font-size:28px;font-weight:700;color:#fde68a;margin:0;">{{ $stats['pending'] }}</p>
            <p style="font-size:12px;color:#666;margin:4px 0 0;">Pending</p>
        </div>
        <div class="stat-card" style="text-align:center;">
            <p style="font-size:28px;font-weight:700;color:#fb923c;margin:0;">{{ $stats['preparing'] }}</p>
            <p style="font-size:12px;color:#666;margin:4px 0 0;">Preparing</p>
        </div>
        <div class="stat-card" style="text-align:center;">
            <p style="font-size:28px;font-weight:700;color:#60a5fa;margin:0;">{{ $stats['today'] }}</p>
            <p style="font-size:12px;color:#666;margin:4px 0 0;">Today's Orders</p>
        </div>
        <div class="stat-card" style="text-align:center;">
            <p style="font-size:28px;font-weight:700;color:#4ade80;margin:0;">Rs. {{ number_format($stats['revenue'], 0) }}</p>
            <p style="font-size:12px;color:#666;margin:4px 0 0;">Today's Revenue</p>
        </div>
    </div>

    {{-- Filters --}}
    <form method="GET" style="display:flex;gap:8px;flex-wrap:wrap;">
        <select name="status" class="filter-input">
            <option value="">All Status</option>
            @foreach(['pending','confirmed','preparing','ready','completed','cancelled'] as $s)
            <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
        <input type="date" name="date" value="{{ request('date', date('Y-m-d')) }}" class="filter-input">
        <button type="submit" style="padding:8px 14px;background:#1a1a1a;color:#aaa;border:1px solid #2a2a2a;border-radius:8px;font-size:13px;cursor:pointer;">Filter</button>
        <a href="{{ route('dashboard.orders.index') }}" style="padding:8px 14px;background:#111;color:#555;border:1px solid #1f1f1f;border-radius:8px;font-size:13px;text-decoration:none;">Reset</a>
    </form>

    {{-- Orders --}}
    @forelse($orders as $order)
    <div class="order-card">
        {{-- Order Header --}}
        <div style="padding:14px 16px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;border-bottom:1px solid #1f1f1f;">
            <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
                <span style="font-family:monospace;font-size:14px;font-weight:700;color:#e2e8f0;">{{ $order->order_number }}</span>
                <span class="status-badge s-{{ $order->status }}">{{ $order->status_label }}</span>
                <span style="font-size:12px;color:#555;">
                    {{ $order->order_type === 'dine_in' ? '🍽 Dine-in' : '🥡 Takeaway' }}
                    @if($order->table_number) — Table {{ $order->table_number }} @endif
                </span>
            </div>
            <div style="display:flex;align-items:center;gap:8px;">
                <span style="font-size:13px;font-weight:700;color:#e8502a;">Rs. {{ number_format($order->total, 0) }}</span>
                <span class="pay-badge-{{ $order->payment_status }}">{{ ucfirst($order->payment_status) }}</span>
                <span style="font-size:11px;color:#444;">{{ $order->created_at->diffForHumans() }}</span>
            </div>
        </div>

        {{-- Order Items Summary --}}
        <div style="padding:12px 16px;border-bottom:1px solid #1f1f1f;">
            <div style="display:flex;flex-wrap:wrap;gap:6px;">
                @foreach($order->items as $item)
                <span style="background:#111;border:1px solid #1f1f1f;border-radius:6px;padding:3px 10px;font-size:12px;color:#888;">
                    {{ $item->product_name }}
                    @if($item->variant_name)({{ $item->variant_name }})@endif
                    x{{ $item->quantity }}
                </span>
                @endforeach
            </div>
            @if($order->customer_name)
            <p style="font-size:12px;color:#555;margin:8px 0 0;">
                👤 {{ $order->customer_name }}
                @if($order->customer_phone) · 📱 {{ $order->customer_phone }} @endif
                @if($order->notes) · 📝 {{ $order->notes }} @endif
            </p>
            @endif
        </div>

        {{-- Actions --}}
        <div style="padding:10px 16px;display:flex;gap:8px;flex-wrap:wrap;align-items:center;">

            {{-- Status update buttons --}}
            @if($order->status === 'pending')
            <form method="POST" action="{{ route('dashboard.orders.status', $order) }}">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="confirmed">
                <button class="btn-sm btn-confirm">✅ Confirm</button>
            </form>
            <form method="POST" action="{{ route('dashboard.orders.status', $order) }}">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="cancelled">
                <button class="btn-sm btn-cancel">✗ Cancel</button>
            </form>
            @endif

            @if($order->status === 'confirmed')
            <form method="POST" action="{{ route('dashboard.orders.status', $order) }}">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="preparing">
                <button class="btn-sm btn-prepare">👨‍🍳 Start Preparing</button>
            </form>
            @endif

            @if($order->status === 'preparing')
            <form method="POST" action="{{ route('dashboard.orders.status', $order) }}">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="ready">
                <button class="btn-sm btn-ready">🔔 Mark Ready</button>
            </form>
            @endif

            @if($order->status === 'ready')
            <form method="POST" action="{{ route('dashboard.orders.status', $order) }}">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="completed">
                <button class="btn-sm btn-complete">✓ Complete</button>
            </form>
            @endif

            {{-- Approve payment --}}
            @if($order->payment_status === 'pending')
            <form method="POST" action="{{ route('dashboard.orders.approve-payment', $order) }}">
                @csrf @method('PATCH')
                <button class="btn-sm" style="background:#052e16;color:#4ade80;border:1px solid #166534;">
                    💳 Verify Payment
                </button>
            </form>
            @endif

            {{-- View details --}}
            <a href="{{ route('dashboard.orders.show', $order) }}" class="btn-sm btn-view">View Details</a>

            {{-- WhatsApp customer (if phone exists) --}}
            @if($order->customer_phone)
            @php
                $phone = '92' . ltrim(preg_replace('/[^0-9]/', '', $order->customer_phone), '0');
                $msg   = "Hello {$order->customer_name}, your order {$order->order_number} update.";
            @endphp
            <a href="https://wa.me/{{ $phone }}?text={{ urlencode($msg) }}" target="_blank"
               class="btn-sm" style="background:#052613;color:#4ade80;border:1px solid #166534;">
                💬 WhatsApp
            </a>
            @endif

        </div>
    </div>
    @empty
    <div style="background:#1a1a1a;border:1px solid #222;border-radius:14px;padding:48px;text-align:center;">
        <div style="font-size:40px;margin-bottom:12px;">📋</div>
        <p style="color:#555;font-size:14px;">No orders found.</p>
    </div>
    @endforelse

    {{-- Pagination --}}
    @if($orders->hasPages())
    <div>{{ $orders->withQueryString()->links() }}</div>
    @endif

</div>

{{-- Auto-refresh every 30 seconds for new orders --}}
<script>
setTimeout(() => window.location.reload(), 30000);
</script>

@endsection