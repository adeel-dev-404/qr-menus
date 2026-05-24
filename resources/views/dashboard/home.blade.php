@extends('layouts.dashboard')
@section('page-title', 'Dashboard')
@section('content')

<style>
    .stat-card {
        background: #1a1a1a;
        border: 1px solid #222;
        border-radius: 14px;
        padding: 16px;
        text-decoration: none;
        display: block;
        transition: border-color 0.2s;
    }
    .stat-card:hover { border-color: #3b82f6; }
    .stat-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }
    .scan-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }
    .action-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
    }
    @media (min-width: 768px) {
        .stat-grid  { grid-template-columns: repeat(4, 1fr); }
        .scan-grid  { grid-template-columns: repeat(4, 1fr); }
        .action-grid { grid-template-columns: repeat(4, 1fr); }
    }
    .action-btn {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        padding: 16px 12px;
        background: #1a1a1a;
        border: 1px dashed #2a2a2a;
        border-radius: 14px;
        color: #888;
        text-decoration: none;
        font-size: 13px;
        transition: all 0.2s;
        text-align: center;
    }
    .action-btn:hover { border-color: #3b82f6; color: #60a5fa; background: #0f1729; }
    .section-card {
        background: #1a1a1a;
        border: 1px solid #222;
        border-radius: 14px;
        overflow: hidden;
    }
    .section-header {
        padding: 14px 16px;
        border-bottom: 1px solid #222;
        font-size: 14px;
        font-weight: 600;
        color: #ccc;
    }
</style>

<div style="max-width: 960px; display: flex; flex-direction: column; gap: 16px;">

    {{-- Welcome Banner --}}
    <div style="background: linear-gradient(135deg, #1d4ed8, #1e40af); border-radius: 14px; padding: 20px;">
        <p style="font-size: 18px; font-weight: 700; color: #fff; margin: 0;">
            Welcome, {{ auth()->user()->name }} 👋
        </p>
        <p style="font-size: 13px; color: #93c5fd; margin: 6px 0 0;">
            {{ $restaurant->name }} &mdash; {{ now()->format('l, d M Y') }}
        </p>
    </div>

    {{-- Menu Stats --}}
    <div class="stat-grid">
        <a href="{{ route('dashboard.products.index') }}" class="stat-card">
            <p style="font-size:11px;color:#666;text-transform:uppercase;letter-spacing:.05em;margin:0 0 8px;">Products</p>
            <p style="font-size:28px;font-weight:700;color:#60a5fa;margin:0;">{{ $stats['products'] }}</p>
            <p style="font-size:11px;color:#555;margin:4px 0 0;">of {{ $limits['products'] >= 999 ? '∞' : $limits['products'] }}</p>
        </a>
        <a href="{{ route('dashboard.categories.index') }}" class="stat-card">
            <p style="font-size:11px;color:#666;text-transform:uppercase;letter-spacing:.05em;margin:0 0 8px;">Categories</p>
            <p style="font-size:28px;font-weight:700;color:#4ade80;margin:0;">{{ $stats['categories'] }}</p>
            <p style="font-size:11px;color:#555;margin:4px 0 0;">&nbsp;</p>
        </a>
        <a href="{{ route('dashboard.qr-codes.index') }}" class="stat-card">
            <p style="font-size:11px;color:#666;text-transform:uppercase;letter-spacing:.05em;margin:0 0 8px;">QR Codes</p>
            <p style="font-size:28px;font-weight:700;color:#c084fc;margin:0;">{{ $stats['qr_codes'] }}</p>
            <p style="font-size:11px;color:#555;margin:4px 0 0;">of {{ $limits['qr_codes'] >= 999 ? '∞' : $limits['qr_codes'] }}</p>
        </a>
        <a href="{{ route('dashboard.branches.index') }}" class="stat-card">
            <p style="font-size:11px;color:#666;text-transform:uppercase;letter-spacing:.05em;margin:0 0 8px;">Branches</p>
            <p style="font-size:28px;font-weight:700;color:#fbbf24;margin:0;">{{ $stats['branches'] }}</p>
            <p style="font-size:11px;color:#555;margin:4px 0 0;">of {{ $limits['branches'] >= 999 ? '∞' : $limits['branches'] }}</p>
        </a>
    </div>

    {{-- Scan Stats --}}
    <div class="scan-grid">
        @foreach([
            ['label'=>'Today',      'value'=>$scanStats['today'],      'color'=>'#fb923c'],
            ['label'=>'This Week',  'value'=>$scanStats['this_week'],  'color'=>'#f472b6'],
            ['label'=>'This Month', 'value'=>$scanStats['this_month'], 'color'=>'#818cf8'],
            ['label'=>'All Time',   'value'=>$scanStats['all_time'],   'color'=>'#2dd4bf'],
        ] as $s)
        <div class="stat-card" style="text-align:center;">
            <p style="font-size:11px;color:#666;margin:0 0 6px;">{{ $s['label'] }} Scans</p>
            <p style="font-size:26px;font-weight:700;color:{{ $s['color'] }};margin:0;">{{ number_format($s['value']) }}</p>
        </div>
        @endforeach
    </div>

    {{-- Chart + Top QR --}}
    <div style="display:grid;grid-template-columns:1fr;gap:16px;">
        {{-- @media (min-width: 768px) {
            .chart-row { grid-template-columns: 2fr 1fr !important; }
        } --}}

        {{-- Scan Chart --}}
        <div class="section-card">
            <div class="section-header">📈 Scans — Last 14 Days</div>
            <div style="padding:16px;">
                <canvas id="scansChart" height="120"></canvas>
            </div>
        </div>
    </div>

    {{-- Chart + Top QR side by side on desktop --}}
    <div style="display:grid;grid-template-columns:1fr;gap:16px;" id="chart-row">
        <div class="section-card">
            <div class="section-header">📈 Scans — Last 14 Days</div>
            <div style="padding:16px;">
                <canvas id="scansChart2" height="100"></canvas>
            </div>
        </div>
        <div class="section-card">
            <div class="section-header">🔥 Top QR Codes</div>
            <div style="padding:8px 0;">
                @forelse($topQrCodes as $qr)
                <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 16px;border-bottom:1px solid #1f1f1f;">
                    <div>
                        <p style="font-family:monospace;font-size:14px;font-weight:700;color:#e2e8f0;margin:0;">{{ $qr->token }}</p>
                        <p style="font-size:11px;color:#555;margin:2px 0 0;">{{ ucfirst($qr->type) }}</p>
                    </div>
                    <span style="font-size:14px;font-weight:700;color:#c084fc;">{{ number_format($qr->scan_count) }}</span>
                </div>
                @empty
                <p style="color:#555;font-size:13px;text-align:center;padding:20px;">No scans yet</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="section-card">
        <div class="section-header">⚡ Quick Actions</div>
        <div style="padding:16px;">
            <div class="action-grid">
                <a href="{{ route('dashboard.categories.create') }}" class="action-btn">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:24px;height:24px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Add Category
                </a>
                <a href="{{ route('dashboard.products.create') }}" class="action-btn">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:24px;height:24px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Add Product
                </a>
                <a href="{{ route('dashboard.qr-codes.create') }}" class="action-btn">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:24px;height:24px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                    Generate QR
                </a>
                <a href="{{ url('/r/' . $restaurant->slug) }}" target="_blank" class="action-btn">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:24px;height:24px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    View Menu ↗
                </a>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const labels = @json(array_keys($scansPerDay));
const data   = @json(array_values($scansPerDay));
const cfg = {
    type: 'line',
    data: {
        labels,
        datasets: [{
            data,
            borderColor: '#3b82f6',
            backgroundColor: 'rgba(59,130,246,0.07)',
            borderWidth: 2,
            tension: 0.4,
            fill: true,
            pointBackgroundColor: '#3b82f6',
            pointRadius: 3,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, ticks: { color: '#555', stepSize: 1 }, grid: { color: '#1f1f1f' } },
            x: { ticks: { color: '#555', maxTicksLimit: 7 }, grid: { color: '#1f1f1f' } }
        }
    }
};
new Chart(document.getElementById('scansChart'), cfg);

// Make chart-row 2-col on desktop
const row = document.getElementById('chart-row');
if (window.innerWidth >= 768) {
    row.style.gridTemplateColumns = '2fr 1fr';
}
// Hide the first chart canvas (duplicate), show only chart-row
document.querySelector('#scansChart').closest('.section-card').style.display = 'none';
new Chart(document.getElementById('scansChart2'), cfg);
</script>

@endsection