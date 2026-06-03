@extends('layouts.dashboard')
@section('page-title', 'Orders')
@section('content')

<style>
.stat-grid { display:grid; grid-template-columns:repeat(2,1fr); gap:12px; margin-bottom:20px; }
@media(min-width:768px){ .stat-grid { grid-template-columns:repeat(5,1fr); } }
.stat-card { background:#1a1a1a; border:1px solid #222; border-radius:12px; padding:14px 16px; }
.stat-num  { font-size:24px; font-weight:800; margin-bottom:2px; }
.stat-lbl  { font-size:11px; color:#555; }

.filter-select { background:#111; border:1px solid #2a2a2a; border-radius:8px; padding:8px 12px; color:#e2e8f0; font-size:13px; outline:none; cursor:pointer; }
.filter-select:focus { border-color:#e8502a; }
.filter-select option { background:#111; }

.tab { padding:7px 14px; border-radius:8px; font-size:13px; font-weight:500; cursor:pointer; border:1px solid #2a2a2a; background:#111; color:#666; text-decoration:none; transition:all .15s; display:inline-block; }
.tab.active { background:#e8502a; color:#fff; border-color:#e8502a; }
.tab:hover:not(.active) { color:#ccc; border-color:#444; }

.orders-table { background:#1a1a1a; border:1px solid #222; border-radius:14px; overflow:hidden; }
.tbl { width:100%; min-width:720px; border-collapse:collapse; font-size:13px; }
.tbl thead tr { background:#111; border-bottom:1px solid #222; }
.tbl thead th { padding:12px 16px; text-align:left; font-size:10px; font-weight:700; color:#555; text-transform:uppercase; letter-spacing:.06em; }
.tbl tbody tr { border-bottom:1px solid #1a1a1a; transition:background .15s; cursor:pointer; }
.tbl tbody tr:hover { background:#1f1f1f; }
.tbl tbody td { padding:12px 16px; color:#ccc; vertical-align:middle; }

.badge { display:inline-flex; align-items:center; gap:4px; padding:3px 10px; border-radius:99px; font-size:11px; font-weight:700; white-space:nowrap; }
.b-pending   { background:#422006; color:#fde68a; border:1px solid #92400e; }
.b-confirmed { background:#0f1729; color:#93c5fd; border:1px solid #1e3a5f; }
.b-preparing { background:#1c1100; color:#fb923c; border:1px solid #92400e; }
.b-ready     { background:#052e16; color:#86efac; border:1px solid #166534; }
.b-delivered { background:#1a1a1a; color:#666;    border:1px solid #2a2a2a; }
.b-cancelled { background:#2d0a0a; color:#fca5a5; border:1px solid #7f1d1d; }
.b-paid      { background:#052e16; color:#86efac; border:1px solid #166534; }
.b-unpaid    { background:#422006; color:#fde68a; border:1px solid #92400e; }

.quick-actions { display:flex; gap:6px; }
.qa-btn { padding:5px 10px; border-radius:6px; font-size:11px; font-weight:600; cursor:pointer; border:none; white-space:nowrap; transition:all .15s; }
.qa-confirm  { background:#0f1729; color:#93c5fd; }
.qa-confirm:hover  { background:#162035; }
.qa-prepare  { background:#1c1100; color:#fb923c; }
.qa-prepare:hover  { background:#261800; }
.qa-ready    { background:#052e16; color:#86efac; }
.qa-ready:hover    { background:#063d1e; }
.qa-deliver  { background:#1a1a1a; color:#888; border:1px solid #2a2a2a; }
.qa-deliver:hover  { color:#ccc; }
.qa-view     { background:#1a1a1a; color:#888; border:1px solid #2a2a2a; }
.qa-view:hover     { color:#ccc; border-color:#444; }

.new-badge { display:inline-block; width:8px; height:8px; border-radius:99px; background:#e8502a; animation:pulse-dot 2s infinite; margin-right:4px; }
@keyframes pulse-dot { 0%,100%{box-shadow:0 0 0 0 rgba(232,80,42,.5)} 50%{box-shadow:0 0 0 5px rgba(232,80,42,0)} }

.empty-state { text-align:center; padding:48px 16px; }
.empty-icon  { font-size:40px; margin-bottom:12px; }
.empty-state h3 { font-size:15px; font-weight:600; color:#e2e8f0; margin-bottom:6px; }
.empty-state p  { font-size:13px; color:#555; }
</style>

<div style="max-width:1100px;">

    {{-- Header --}}
    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:20px;">
        <div>
            <h2 style="font-size:20px;font-weight:700;color:#fff;margin:0;">Orders</h2>
            <p style="font-size:13px;color:#555;margin:4px 0 0;">Manage and track customer orders</p>
        </div>
        <div style="display:flex;align-items:center;gap:8px;">
            <div style="display:flex;align-items:center;gap:6px;background:#1a1a1a;border:1px solid #222;border-radius:8px;padding:6px 12px;">
                <span class="new-badge"></span>
                <span style="font-size:12px;color:#888;">Live — auto refreshes</span>
            </div>
        </div>
    </div>

    {{-- Stats --}}
    <div class="stat-grid">
        <div class="stat-card">
            <p class="stat-num" style="color:#fde68a;">{{ $stats['pending'] }}</p>
            <p class="stat-lbl">⏳ Pending</p>
        </div>
        <div class="stat-card">
            <p class="stat-num" style="color:#fb923c;">{{ $stats['preparing'] }}</p>
            <p class="stat-lbl">👨‍🍳 Preparing</p>
        </div>
        <div class="stat-card">
            <p class="stat-num" style="color:#86efac;">{{ $stats['ready'] }}</p>
            <p class="stat-lbl">🔔 Ready</p>
        </div>
        <div class="stat-card">
            <p class="stat-num" style="color:#60a5fa;">{{ $stats['today'] }}</p>
            <p class="stat-lbl">📦 Today's Orders</p>
        </div>
        <div class="stat-card">
            <p class="stat-num" style="color:#4ade80;">Rs. {{ number_format($stats['revenue'], 0) }}</p>
            <p class="stat-lbl">💰 Today's Revenue</p>
        </div>
    </div>

    {{-- ══════════════════════════════════
         FILTERS — single unified form
         All filters inside ONE form so
         they don't conflict with each other
    ══════════════════════════════════ --}}
    <form method="GET" action="{{ route('dashboard.orders.index') }}" id="filterForm">

    @php
        $statusTabs = [
            ''          => 'All',
            'pending'   => 'Pending',
            'confirmed' => 'Confirmed',
            'preparing' => 'Preparing',
            'ready'     => 'Ready',
            'delivered' => 'Delivered',
            'cancelled' => 'Cancelled',
        ];
        $activeStatus  = request('status', '');
        $activeType    = request('type', '');
        $activePayment = request('payment', '');
    @endphp

    {{-- Status Tabs --}}
    <div style="display:flex;gap:4px;flex-wrap:wrap;margin-bottom:14px;">
        @foreach($statusTabs as $val => $label)
        <button type="submit"        name="status"        value="{{ $val }}"        class="tab {{ $activeStatus === $val ? 'active' : '' }}">
            {{ $label }}
        </button>
        @endforeach
    </div>

    {{-- NO hidden inputs needed — selects submit themselves --}}

    {{-- Dropdown Filters --}}
    <div style="display:flex;gap:8px;flex-wrap:wrap;align-items:center;margin-bottom:16px;">

        <select name="type" class="filter-select" onchange="this.form.submit()">
            <option value="" {{ $activeType === '' ? 'selected' : '' }}>All Types</option>
            <option value="dine_in"  {{ $activeType === 'dine_in'  ? 'selected' : '' }}>Dine-in</option>
            <option value="takeaway" {{ $activeType === 'takeaway' ? 'selected' : '' }}>Takeaway</option>
        </select>

        <select name="payment" class="filter-select" onchange="this.form.submit()">
            <option value="" {{ $activePayment === '' ? 'selected' : '' }}>All Payments</option>
            <option value="pending" {{ $activePayment === 'pending' ? 'selected' : '' }}>Unpaid</option>
            <option value="paid"    {{ $activePayment === 'paid'    ? 'selected' : '' }}>Paid</option>
        </select>

        @if($activeStatus || $activeType || $activePayment)
        <a href="{{ route('dashboard.orders.index') }}"
           style="padding:8px 14px;background:#2d0a0a;color:#fca5a5;border:1px solid #7f1d1d;border-radius:8px;font-size:12px;font-weight:600;text-decoration:none;">
            Clear Filters
        </a>
        <span style="font-size:12px;color:#555;margin-left:4px;">
            Showing {{ $orders->total() }} {{ Str::plural('order', $orders->total()) }}
            @if($activeStatus) &middot; Status: <strong style="color:#e8502a;">{{ ucfirst($activeStatus) }}</strong>@endif
            @if($activeType)   &middot; Type: <strong style="color:#e8502a;">{{ $activeType === 'dine_in' ? 'Dine-in' : 'Takeaway' }}</strong>@endif
            @if($activePayment)&middot; Payment: <strong style="color:#e8502a;">{{ ucfirst($activePayment) }}</strong>@endif
        </span>
        @endif

    </div>

</form>

    {{-- end filter form --}}

    {{-- Orders Table --}}
    <div class="orders-table">
        <div style="overflow-x:auto;-webkit-overflow-scrolling:touch;">
            <table class="tbl">
                <thead>
                    <tr>
                        <th>Order</th>
                        <th>Customer</th>
                        <th>Items</th>
                        <th>Total</th>
                        <th>Type</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th>Time</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr onclick="window.location='{{ route('dashboard.orders.show', $order) }}'">
                        <td>
                            @if($order->status === 'pending' && $order->created_at->gt(now()->subMinutes(10)))
                                <span class="new-badge" title="New order"></span>
                            @endif
                            <span style="font-family:monospace;font-size:12px;font-weight:700;color:#e2e8f0;">
                                {{ $order->order_number }}
                            </span>
                        </td>
                        <td>
                            <p style="font-weight:600;color:#e2e8f0;margin:0;">{{ $order->customer_name }}</p>
                            <p style="font-size:11px;color:#555;margin:2px 0 0;">{{ $order->customer_phone }}</p>
                        </td>
                        <td style="color:#888;">{{ $order->items->count() }} items</td>
                        <td style="font-weight:700;color:#e8502a;">Rs.&nbsp;{{ number_format($order->total, 0) }}</td>
                        <td>
                            <span style="font-size:12px;color:#888;">
                                {{ $order->type === 'dine_in' ? '🍽 Dine-in' : '🥡 Takeaway' }}
                            </span>
                            @if($order->table)
                            <br><span style="font-size:11px;color:#555;">Table {{ $order->table->table_number }}</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $order->payment_status === 'paid' ? 'b-paid' : 'b-unpaid' }}">
                                {{ $order->payment_status === 'paid' ? '✓ Paid' : '⏳ Unpaid' }}
                            </span>
                        </td>
                        <td>
                            <span class="badge b-{{ $order->status }}">
                                {{ $order->status_label }}
                            </span>
                        </td>
                        <td style="color:#555;font-size:12px;white-space:nowrap;">
                            {{ $order->created_at->diffForHumans() }}
                        </td>
                        <td onclick="event.stopPropagation()">
                            <div class="quick-actions">
                                @if($order->status === 'pending')
                                <form method="POST" action="{{ route('dashboard.orders.status', $order) }}">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="confirmed">
                                    <button class="qa-btn qa-confirm">Confirm</button>
                                </form>
                                @elseif($order->status === 'confirmed')
                                <form method="POST" action="{{ route('dashboard.orders.status', $order) }}">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="preparing">
                                    <button class="qa-btn qa-prepare">Prepare</button>
                                </form>
                                @elseif($order->status === 'preparing')
                                <form method="POST" action="{{ route('dashboard.orders.status', $order) }}">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="ready">
                                    <button class="qa-btn qa-ready">Ready</button>
                                </form>
                                @elseif($order->status === 'ready')
                                <form method="POST" action="{{ route('dashboard.orders.status', $order) }}">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="delivered">
                                    <button class="qa-btn qa-deliver">Deliver</button>
                                </form>
                                @endif
                                <a href="{{ route('dashboard.orders.show', $order) }}"
                                   class="qa-btn qa-view">View</a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" style="padding:0;">
                            <div class="empty-state">
                                <div class="empty-icon">📋</div>
                                <h3>No orders found</h3>
                                <p>
                                    @if($activeStatus || $activeType || $activePayment)
                                        No orders match the selected filters.
                                        <a href="{{ route('dashboard.orders.index') }}" style="color:#e8502a;">Clear filters</a>
                                    @else
                                        Orders placed by customers will appear here.
                                    @endif
                                </p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
        <div style="padding:14px 16px;border-top:1px solid #1f1f1f;">
            {{ $orders->withQueryString()->links() }}
        </div>
        @endif
    </div>

</div>

<script>
// When a status tab button is clicked, make sure
// the dropdown filter values are preserved in the URL
function syncHidden(field, value) {
    // Status is sent via button name/value directly
    // We need to make sure type + payment are still in the form
    // They're already in the selects so the form will pick them up
    // This function is just a hook in case extra logic is needed
}

// Auto-refresh every 30 seconds
setTimeout(() => location.reload(), 30000);
</script>

@endsection
