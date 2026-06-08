@extends('layouts.dashboard')
@section('page-title', 'Dashboard')
@section('content')

<style>
    /* ── Tokens ── */
    :root {
        --card-bg:    #141414;
        --card-border: #1f1f1f;
        --card-radius: 14px;
        --text-muted: #555;
        --text-sub:   #888;
    }

    /* ── Base card ── */
    .dc {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: var(--card-radius);
        overflow: hidden;
    }

    .dc-header {
        padding: 13px 18px;
        border-bottom: 1px solid var(--card-border);
        font-size: 13px;
        font-weight: 600;
        color: #bbb;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .dc-body { padding: 16px 18px; }

    /* ── Stat card ── */
    .stat-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: var(--card-radius);
        padding: 16px;
        text-decoration: none;
        display: block;
        transition: border-color 0.2s, transform 0.15s;
    }
    .stat-card:hover { border-color: #3b82f6; transform: translateY(-1px); }
    .stat-number { font-size: 28px; font-weight: 700; margin: 6px 0 2px; line-height: 1; }
    .stat-label  { font-size: 11px; color: var(--text-muted); text-transform: uppercase; letter-spacing: .05em; }
    .stat-sub    { font-size: 11px; color: var(--text-muted); margin-top: 4px; }

    /* ── Order stat card (smaller number) ── */
    .ostat-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: var(--card-radius);
        padding: 14px 16px;
        display: flex;
        align-items: center;
        gap: 14px;
    }
    .ostat-icon {
        width: 40px; height: 40px;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 18px; flex-shrink: 0;
    }
    .ostat-val  { font-size: 22px; font-weight: 700; line-height: 1; }
    .ostat-lbl  { font-size: 11px; color: var(--text-sub); margin-top: 2px; }

    /* ── Grids ── */
    .grid-2  { display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; }
    .grid-4  { display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; }
    .grid-5  { display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; }
    .grid-actions { display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; }

    @media (min-width: 640px) {
        .grid-4 { grid-template-columns: repeat(4, 1fr); }
        .grid-5 { grid-template-columns: repeat(3, 1fr); }
    }
    @media (min-width: 900px) {
        .grid-5 { grid-template-columns: repeat(5, 1fr); }
        .grid-actions { grid-template-columns: repeat(4, 1fr); }
    }
    @media (min-width: 768px) {
        .chart-split { grid-template-columns: 2fr 1fr !important; }
        .content-split { grid-template-columns: 3fr 2fr !important; }
    }

    /* ── Action buttons ── */
    .action-btn {
        display: flex; flex-direction: column;
        align-items: center; gap: 8px;
        padding: 16px 12px;
        background: var(--card-bg);
        border: 1px dashed #252525;
        border-radius: var(--card-radius);
        color: #777; text-decoration: none;
        font-size: 12px; transition: all 0.2s;
        text-align: center; cursor: pointer;
    }
    .action-btn:hover { border-color: #3b82f6; color: #60a5fa; background: #0b1528; }
    .action-btn svg { width: 22px; height: 22px; }

    /* ── Status badge ── */
    .badge {
        display: inline-block; padding: 2px 9px;
        border-radius: 99px; font-size: 11px; font-weight: 600;
    }
    .badge-pending   { background: #2d2000; color: #fbbf24; }
    .badge-confirmed { background: #0a1f3d; color: #60a5fa; }
    .badge-preparing { background: #2d1400; color: #fb923c; }
    .badge-ready     { background: #062014; color: #4ade80; }
    .badge-delivered { background: #1a1a1a; color: #888; }
    .badge-cancelled { background: #2d0a0a; color: #f87171; }

    /* ── Table ── */
    .dash-table { width: 100%; border-collapse: collapse; font-size: 13px; }
    .dash-table th {
        padding: 10px 14px; text-align: left;
        font-size: 10px; font-weight: 600;
        color: var(--text-muted); text-transform: uppercase;
        letter-spacing: .07em;
        border-bottom: 1px solid var(--card-border);
    }
    .dash-table td {
        padding: 11px 14px;
        border-bottom: 1px solid #161616;
        color: #ccc; vertical-align: middle;
    }
    .dash-table tr:last-child td { border-bottom: none; }
    .dash-table tr:hover td { background: #181818; }
    .dash-table a { color: inherit; text-decoration: none; }
    .dash-table a:hover { color: #60a5fa; }

    /* ── Progress bar ── */
    .prog-bar { height: 4px; background: #222; border-radius: 99px; overflow: hidden; margin-top: 6px; }
    .prog-fill { height: 100%; border-radius: 99px; transition: width .3s; }

    /* ── Waiter call row ── */
    .wc-item {
        display: flex; align-items: center;
        gap: 12px; padding: 10px 18px;
        border-bottom: 1px solid var(--card-border);
    }
    .wc-item:last-child { border-bottom: none; }

    /* ── Top product bar ── */
    .tp-item { padding: 10px 18px; }
    .tp-name { font-size: 13px; color: #ccc; font-weight: 500; }
    .tp-meta { font-size: 11px; color: var(--text-sub); margin-top: 2px; }
</style>

<div style="max-width: 1100px; display: flex; flex-direction: column; gap: 18px;">

    {{-- ═══ 1. WELCOME BANNER ═══ --}}
    <div style="background: linear-gradient(135deg, #1d3f8a 0%, #1a2f6a 60%, #0f1f4d 100%);
                border-radius: 16px; padding: 20px 24px;
                display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px;">
        <div>
            <p style="font-size: 18px; font-weight: 700; color: #fff; margin: 0;">
                Welcome back, {{ auth()->user()->name }} 👋
            </p>
            <p style="font-size: 13px; color: #93c5fd; margin: 5px 0 0;">
                {{ $restaurant->name }} &mdash; {{ now()->format('l, d M Y') }}
            </p>
        </div>
        <div style="display:flex; gap:10px; flex-wrap:wrap;">
            <div style="background:rgba(255,255,255,.08);border-radius:10px;padding:10px 16px;text-align:center;">
                <p style="font-size:20px;font-weight:700;color:#fff;margin:0;">{{ $orderStats['today_total'] }}</p>
                <p style="font-size:11px;color:#93c5fd;margin:2px 0 0;">Orders Today</p>
            </div>
            <div style="background:rgba(255,255,255,.08);border-radius:10px;padding:10px 16px;text-align:center;">
                <p style="font-size:20px;font-weight:700;color:#4ade80;margin:0;">Rs.{{ number_format($orderStats['today_revenue'], 0) }}</p>
                <p style="font-size:11px;color:#93c5fd;margin:2px 0 0;">Revenue Today</p>
            </div>
            @if($orderStats['pending'] > 0)
            <div style="background:rgba(251,191,36,.12);border:1px solid rgba(251,191,36,.3);border-radius:10px;padding:10px 16px;text-align:center;">
                <p style="font-size:20px;font-weight:700;color:#fbbf24;margin:0;">{{ $orderStats['pending'] }}</p>
                <p style="font-size:11px;color:#fcd34d;margin:2px 0 0;">Pending Orders</p>
            </div>
            @endif
        </div>
    </div>

    {{-- ═══ 2. MENU STATS ═══ --}}
    <div class="grid-4">
        <a href="{{ route('dashboard.products.index') }}" class="stat-card">
            <p class="stat-label">Products</p>
            <p class="stat-number" style="color:#60a5fa;">{{ $stats['products'] }}</p>
            <div class="prog-bar">
                <div class="prog-fill" style="background:#3b82f6;width:{{ $limits['products'] >= 999 ? 100 : min(100, ($stats['products'] / max(1,$limits['products'])) * 100) }}%;"></div>
            </div>
            <p class="stat-sub">{{ $limits['products'] >= 999 ? '∞ unlimited' : $stats['products'].' / '.$limits['products'] }}</p>
        </a>
        <a href="{{ route('dashboard.categories.index') }}" class="stat-card">
            <p class="stat-label">Categories</p>
            <p class="stat-number" style="color:#4ade80;">{{ $stats['categories'] }}</p>
            <div class="prog-bar"><div class="prog-fill" style="background:#22c55e;width:100%;"></div></div>
            <p class="stat-sub">active categories</p>
        </a>
        <a href="{{ route('dashboard.qr-codes.index') }}" class="stat-card">
            <p class="stat-label">QR Codes</p>
            <p class="stat-number" style="color:#c084fc;">{{ $stats['qr_codes'] }}</p>
            <div class="prog-bar">
                <div class="prog-fill" style="background:#a855f7;width:{{ $limits['qr_codes'] >= 999 ? 100 : min(100, ($stats['qr_codes'] / max(1,$limits['qr_codes'])) * 100) }}%;"></div>
            </div>
            <p class="stat-sub">{{ $limits['qr_codes'] >= 999 ? '∞ unlimited' : $stats['qr_codes'].' / '.$limits['qr_codes'] }}</p>
        </a>
        <a href="{{ route('dashboard.branches.index') }}" class="stat-card">
            <p class="stat-label">Branches</p>
            <p class="stat-number" style="color:#fbbf24;">{{ $stats['branches'] }}</p>
            <div class="prog-bar">
                <div class="prog-fill" style="background:#f59e0b;width:{{ $limits['branches'] >= 999 ? 100 : min(100, ($stats['branches'] / max(1,$limits['branches'])) * 100) }}%;"></div>
            </div>
            <p class="stat-sub">{{ $limits['branches'] >= 999 ? '∞ unlimited' : $stats['branches'].' / '.$limits['branches'] }}</p>
        </a>
    </div>

    {{-- ═══ 3. ORDER STATS ═══ --}}
    <div>
        <p style="font-size:11px;font-weight:600;color:#555;text-transform:uppercase;letter-spacing:.07em;margin:0 0 10px;">Live Order Pipeline</p>
        <div class="grid-5">
            <a href="{{ route('dashboard.orders.index', ['status'=>'pending']) }}" class="ostat-card" style="text-decoration:none;">
                <div class="ostat-icon" style="background:#2d2000;">⏳</div>
                <div>
                    <div class="ostat-val" style="color:#fbbf24;">{{ $orderStats['pending'] }}</div>
                    <div class="ostat-lbl">Pending</div>
                </div>
            </a>
            <a href="{{ route('dashboard.orders.index', ['status'=>'confirmed']) }}" class="ostat-card" style="text-decoration:none;">
                <div class="ostat-icon" style="background:#0a1f3d;">✅</div>
                <div>
                    <div class="ostat-val" style="color:#60a5fa;">{{ $orderStats['confirmed'] }}</div>
                    <div class="ostat-lbl">Confirmed</div>
                </div>
            </a>
            <a href="{{ route('dashboard.orders.index', ['status'=>'preparing']) }}" class="ostat-card" style="text-decoration:none;">
                <div class="ostat-icon" style="background:#2d1400;">👨‍🍳</div>
                <div>
                    <div class="ostat-val" style="color:#fb923c;">{{ $orderStats['preparing'] }}</div>
                    <div class="ostat-lbl">Preparing</div>
                </div>
            </a>
            <a href="{{ route('dashboard.orders.index', ['status'=>'ready']) }}" class="ostat-card" style="text-decoration:none;">
                <div class="ostat-icon" style="background:#062014;">🔔</div>
                <div>
                    <div class="ostat-val" style="color:#4ade80;">{{ $orderStats['ready'] }}</div>
                    <div class="ostat-lbl">Ready</div>
                </div>
            </a>
            <div class="ostat-card">
                <div class="ostat-icon" style="background:#0e1f14;">💰</div>
                <div>
                    <div class="ostat-val" style="color:#34d399;font-size:17px;">Rs.{{ number_format($orderStats['month_revenue'], 0) }}</div>
                    <div class="ostat-lbl">This Month</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══ 4. CHARTS ROW ═══ --}}
    <div style="display:grid;grid-template-columns:1fr;gap:16px;" class="chart-split">
        {{-- Scans + Revenue chart --}}
        <div class="dc">
            <div class="dc-header">
                <span>📈 Scans & Revenue — Last 14 Days</span>
                <div style="display:flex;gap:14px;font-size:11px;color:#555;">
                    <span style="display:flex;align-items:center;gap:4px;"><span style="display:inline-block;width:12px;height:2px;background:#3b82f6;border-radius:2px;"></span> Scans</span>
                    <span style="display:flex;align-items:center;gap:4px;"><span style="display:inline-block;width:12px;height:2px;background:#4ade80;border-radius:2px;"></span> Revenue</span>
                </div>
            </div>
            <div style="padding:16px;">
                <canvas id="mainChart" height="110"></canvas>
            </div>
        </div>

        {{-- Top QR Codes --}}
        <div class="dc">
            <div class="dc-header">🔥 Top QR Codes</div>
            <div style="padding:4px 0;">
                @forelse($topQrCodes as $qr)
                <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 18px;border-bottom:1px solid #161616;">
                    <div>
                        <p style="font-family:monospace;font-size:13px;font-weight:700;color:#e2e8f0;margin:0;">{{ $qr->token }}</p>
                        <p style="font-size:11px;color:#555;margin:2px 0 0;">{{ ucfirst($qr->type) }}</p>
                    </div>
                    <span style="font-size:13px;font-weight:700;color:#c084fc;">{{ number_format($qr->scan_count) }} scans</span>
                </div>
                @empty
                <p style="color:#555;font-size:13px;text-align:center;padding:24px;">No scans yet</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ═══ 5. RECENT ORDERS + TOP PRODUCTS ═══ --}}
    <div style="display:grid;grid-template-columns:1fr;gap:16px;" class="content-split">

        {{-- Recent Orders --}}
        <div class="dc">
            <div class="dc-header">
                <span>🛒 Recent Orders</span>
                <a href="{{ route('dashboard.orders.index') }}" style="font-size:12px;color:#3b82f6;text-decoration:none;">View all →</a>
            </div>
            @if($recentOrders->isEmpty())
                <p style="color:#555;font-size:13px;text-align:center;padding:28px;">No orders yet</p>
            @else
            <div style="overflow-x:auto;">
                <table class="dash-table">
                    <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Customer</th>
                            <th>Type</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentOrders as $order)
                        <tr>
                            <td>
                                <a href="{{ route('dashboard.orders.show', $order) }}" style="font-family:monospace;font-size:12px;color:#60a5fa;">
                                    {{ $order->order_number }}
                                </a>
                            </td>
                            <td style="max-width:120px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                {{ $order->customer_name }}
                            </td>
                            <td>
                                @if($order->type === 'dine_in')
                                    <span style="font-size:11px;color:#60a5fa;">🍽 Dine-in</span>
                                    @if($order->table)
                                        <span style="font-size:10px;color:#555;"> · T{{ $order->table->table_number }}</span>
                                    @endif
                                @else
                                    <span style="font-size:11px;color:#fb923c;">🥡 Takeaway</span>
                                @endif
                            </td>
                            <td style="font-weight:600;color:#e2e8f0;">Rs.{{ number_format($order->total, 0) }}</td>
                            <td>
                                <span class="badge badge-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
                            </td>
                            <td style="font-size:11px;color:#555;white-space:nowrap;">{{ $order->created_at->diffForHumans() }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>

        {{-- Right column: Top Products + Waiter Calls --}}
        <div style="display:flex;flex-direction:column;gap:16px;">

            {{-- Top Products --}}
            <div class="dc">
                <div class="dc-header">🏆 Top Products</div>
                @if($topProducts->isEmpty())
                    <p style="color:#555;font-size:13px;text-align:center;padding:20px;">No orders yet</p>
                @else
                @php $maxQty = $topProducts->first()->total_qty; @endphp
                @foreach($topProducts as $i => $tp)
                <div class="tp-item" style="{{ !$loop->last ? 'border-bottom:1px solid #161616;' : '' }}">
                    <div style="display:flex;align-items:center;justify-content:space-between;">
                        <div style="display:flex;align-items:center;gap:10px;">
                            <span style="font-size:11px;font-weight:700;color:#555;width:16px;text-align:center;">{{ $i+1 }}</span>
                            <div>
                                <div class="tp-name">{{ $tp->product_name }}</div>
                                <div class="tp-meta">{{ $tp->order_count }} orders</div>
                            </div>
                        </div>
                        <span style="font-size:13px;font-weight:700;color:#c084fc;">×{{ number_format($tp->total_qty) }}</span>
                    </div>
                    <div class="prog-bar" style="margin-top:8px;">
                        <div class="prog-fill" style="background: linear-gradient(90deg, #7c3aed, #a855f7); width:{{ min(100, ($tp->total_qty / $maxQty) * 100) }}%;"></div>
                    </div>
                </div>
                @endforeach
                @endif
            </div>

            {{-- Waiter Calls (only if enabled) --}}
            @if($waiterStats !== null)
            <div class="dc">
                <div class="dc-header">
                    <span>🔔 Waiter Calls</span>
                    <a href="{{ route('dashboard.waiter-calls.index') }}" style="font-size:12px;color:#3b82f6;text-decoration:none;">Manage →</a>
                </div>
                <div style="padding:14px 18px;display:flex;gap:12px;flex-wrap:wrap;">
                    <div style="flex:1;min-width:70px;text-align:center;background:#2d0a00;border-radius:10px;padding:12px 8px;">
                        <div style="font-size:22px;font-weight:700;color:#f87171;">{{ $waiterStats['pending'] }}</div>
                        <div style="font-size:10px;color:#f87171;margin-top:2px;">Pending</div>
                    </div>
                    <div style="flex:1;min-width:70px;text-align:center;background:#0a1f3d;border-radius:10px;padding:12px 8px;">
                        <div style="font-size:22px;font-weight:700;color:#60a5fa;">{{ $waiterStats['seen'] }}</div>
                        <div style="font-size:10px;color:#60a5fa;margin-top:2px;">Seen</div>
                    </div>
                    <div style="flex:1;min-width:70px;text-align:center;background:#062014;border-radius:10px;padding:12px 8px;">
                        <div style="font-size:22px;font-weight:700;color:#4ade80;">{{ $waiterStats['resolved_today'] }}</div>
                        <div style="font-size:10px;color:#4ade80;margin-top:2px;">Resolved</div>
                    </div>
                </div>
            </div>
            @endif

            {{-- Today Order Type Breakdown --}}
            @if($orderStats['today_total'] > 0)
            <div class="dc">
                <div class="dc-header">📊 Today's Order Mix</div>
                <div style="padding:16px 18px;">
                    @php
                        $total = $orderStats['today_total'];
                        $dineIn = $orderStats['dine_in_today'];
                        $takeaway = $orderStats['takeaway_today'];
                        $dineInPct = $total > 0 ? round(($dineIn / $total) * 100) : 0;
                        $takeawayPct = $total > 0 ? round(($takeaway / $total) * 100) : 0;
                    @endphp
                    <div style="display:flex;align-items:center;gap:4px;border-radius:99px;overflow:hidden;height:10px;background:#1a1a1a;">
                        @if($dineIn > 0)
                        <div style="height:100%;background:#3b82f6;width:{{ $dineInPct }}%;border-radius:99px 0 0 99px;transition:width .4s;"></div>
                        @endif
                        @if($takeaway > 0)
                        <div style="height:100%;background:#fb923c;width:{{ $takeawayPct }}%;border-radius:0 99px 99px 0;transition:width .4s;"></div>
                        @endif
                    </div>
                    <div style="display:flex;justify-content:space-between;margin-top:10px;">
                        <div style="display:flex;align-items:center;gap:6px;">
                            <span style="width:10px;height:10px;border-radius:50%;background:#3b82f6;display:inline-block;"></span>
                            <span style="font-size:12px;color:#888;">Dine-in: <strong style="color:#60a5fa;">{{ $dineIn }}</strong> ({{ $dineInPct }}%)</span>
                        </div>
                        <div style="display:flex;align-items:center;gap:6px;">
                            <span style="width:10px;height:10px;border-radius:50%;background:#fb923c;display:inline-block;"></span>
                            <span style="font-size:12px;color:#888;">Takeaway: <strong style="color:#fb923c;">{{ $takeaway }}</strong> ({{ $takeawayPct }}%)</span>
                        </div>
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>

    {{-- ═══ 6. QR SCAN STATS ═══ --}}
    <div>
        <p style="font-size:11px;font-weight:600;color:#555;text-transform:uppercase;letter-spacing:.07em;margin:0 0 10px;">QR Code Scans</p>
        <div class="grid-4">
            @foreach([
                ['label'=>'Today',      'value'=>$scanStats['today'],      'color'=>'#fb923c'],
                ['label'=>'This Week',  'value'=>$scanStats['this_week'],  'color'=>'#f472b6'],
                ['label'=>'This Month', 'value'=>$scanStats['this_month'], 'color'=>'#818cf8'],
                ['label'=>'All Time',   'value'=>$scanStats['all_time'],   'color'=>'#2dd4bf'],
            ] as $s)
            <div class="stat-card" style="text-align:center;">
                <p class="stat-label">{{ $s['label'] }} Scans</p>
                <p class="stat-number" style="color:{{ $s['color'] }};">{{ number_format($s['value']) }}</p>
            </div>
            @endforeach
        </div>
    </div>

    {{-- ═══ 7. QUICK ACTIONS ═══ --}}
    <div class="dc">
        <div class="dc-header">⚡ Quick Actions</div>
        <div style="padding:16px;">
            <div class="grid-actions">
                <a href="{{ route('dashboard.orders.index') }}" class="action-btn">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    View Orders
                </a>
                <a href="{{ route('dashboard.products.create') }}" class="action-btn">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Add Product
                </a>
                <a href="{{ route('dashboard.categories.create') }}" class="action-btn">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                    Add Category
                </a>
                <a href="{{ route('dashboard.qr-codes.create') }}" class="action-btn">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                    Generate QR
                </a>
                @if($restaurant->waiter_call_enabled)
                <a href="{{ route('dashboard.waiter-calls.index') }}" class="action-btn">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    Waiter Calls
                </a>
                @endif
                <a href="{{ route('dashboard.profile.index') }}" class="action-btn">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Settings
                </a>
                <a href="{{ url('/r/' . $restaurant->slug) }}" target="_blank" class="action-btn">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    View Menu ↗
                </a>
                <a href="{{ route('dashboard.subscription.index') }}" class="action-btn">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    Subscription
                </a>
            </div>
        </div>
    </div>

</div>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const scanLabels   = @json(array_keys($scansPerDay));
    const scanData     = @json(array_values($scansPerDay));
    const revenueData  = @json(array_values($revenuePerDay));

    const ctx = document.getElementById('mainChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: scanLabels,
            datasets: [
                {
                    label: 'Scans',
                    data: scanData,
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59,130,246,0.06)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#3b82f6',
                    pointRadius: 3,
                    yAxisID: 'yScan',
                },
                {
                    label: 'Revenue (Rs.)',
                    data: revenueData,
                    borderColor: '#4ade80',
                    backgroundColor: 'rgba(74,222,128,0.04)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#4ade80',
                    pointRadius: 3,
                    yAxisID: 'yRevenue',
                }
            ]
        },
        options: {
            responsive: true,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1a1a1a',
                    borderColor: '#2a2a2a',
                    borderWidth: 1,
                    titleColor: '#ccc',
                    bodyColor: '#aaa',
                    padding: 10,
                    callbacks: {
                        label: ctx => ctx.datasetIndex === 1
                            ? ` Rs.${ctx.parsed.y.toLocaleString()}`
                            : ` ${ctx.parsed.y} scans`
                    }
                }
            },
            scales: {
                yScan: {
                    type: 'linear',
                    position: 'left',
                    beginAtZero: true,
                    ticks: { color: '#555', stepSize: 1 },
                    grid: { color: '#1a1a1a' },
                },
                yRevenue: {
                    type: 'linear',
                    position: 'right',
                    beginAtZero: true,
                    ticks: { color: '#4ade80', callback: v => 'Rs.' + v.toLocaleString() },
                    grid: { drawOnChartArea: false },
                },
                x: {
                    ticks: { color: '#555', maxTicksLimit: 7 },
                    grid: { color: '#1a1a1a' },
                }
            }
        }
    });

    // Make chart-split and content-split responsive
    function applyResponsive() {
        const w = window.innerWidth;
        document.querySelectorAll('.chart-split').forEach(el => {
            el.style.gridTemplateColumns = w >= 768 ? '2fr 1fr' : '1fr';
        });
        document.querySelectorAll('.content-split').forEach(el => {
            el.style.gridTemplateColumns = w >= 768 ? '3fr 2fr' : '1fr';
        });
    }
    applyResponsive();
    window.addEventListener('resize', applyResponsive);
</script>

@endsection