@extends('layouts.dashboard')
@section('page-title', 'Waiter Calls')
@section('content')

<style>
    .stat-grid { display:grid; grid-template-columns:repeat(2,1fr); gap:12px; margin-bottom:20px; }
    @media(min-width:768px){ .stat-grid { grid-template-columns:repeat(3,1fr); } }
    .stat-card { background:#1a1a1a; border:1px solid #222; border-radius:12px; padding:14px 16px; }
    .stat-num  { font-size:24px; font-weight:800; margin-bottom:2px; }
    .stat-lbl  { font-size:11px; color:#555; }

    .calls-table { background:#1a1a1a; border:1px solid #222; border-radius:14px; overflow:hidden; }
    .tbl { width:100%; min-width:720px; border-collapse:collapse; font-size:13px; }
    .tbl thead tr { background:#111; border-bottom:1px solid #222; }
    .tbl thead th { padding:12px 16px; text-align:left; font-size:10px; font-weight:700; color:#555; text-transform:uppercase; letter-spacing:.06em; }
    .tbl tbody tr { border-bottom:1px solid #1a1a1a; transition:background .15s; }
    .tbl tbody tr:hover { background:#1f1f1f; }
    .tbl tbody td { padding:12px 16px; color:#ccc; vertical-align:middle; }

    .badge { display:inline-flex; align-items:center; gap:4px; padding:3px 10px; border-radius:99px; font-size:11px; font-weight:700; white-space:nowrap; }
    .b-pending { background:#422006; color:#fde68a; border:1px solid #92400e; }
    .b-seen    { background:#0f1729; color:#93c5fd; border:1px solid #1e3a5f; }
    .b-resolved{ background:#052e16; color:#86efac; border:1px solid #166534; }

    .btn { padding:5px 10px; border-radius:6px; font-size:11px; font-weight:600; cursor:pointer; border:none; transition:all .15s; }
    .btn-seen { background:#0f1729; color:#93c5fd; }
    .btn-seen:hover { background:#162035; }
    .btn-resolve { background:#052e16; color:#86efac; }
    .btn-resolve:hover { background:#063d1e; }
</style>

<div style="max-width:1100px;">
    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:20px;">
        <div>
            <h2 style="font-size:20px;font-weight:700;color:#fff;margin:0;">Waiter Calls</h2>
            <p style="font-size:13px;color:#555;margin:4px 0 0;">Manage customer requests</p>
        </div>
        <a href="{{ route('dashboard.call-options.index') }}" style="padding:8px 14px;background:#3b82f6;color:#fff;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;">
            Manage Call Options
        </a>
    </div>

    <div class="stat-grid">
        <div class="stat-card">
            <p class="stat-num" style="color:#fde68a;">{{ $stats['pending'] }}</p>
            <p class="stat-lbl">⏳ Pending</p>
        </div>
        <div class="stat-card">
            <p class="stat-num" style="color:#93c5fd;">{{ $stats['seen'] }}</p>
            <p class="stat-lbl">👁️ Seen</p>
        </div>
        <div class="stat-card">
            <p class="stat-num" style="color:#86efac;">{{ $stats['resolved'] }}</p>
            <p class="stat-lbl">✅ Resolved (Today)</p>
        </div>
    </div>

    <div class="calls-table">
        <div style="overflow-x:auto;-webkit-overflow-scrolling:touch;">
            <table class="tbl">
                <thead>
                    <tr>
                        <th>Table</th>
                        <th>Request</th>
                        <th>Time</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="live-calls">
                    @forelse($activeCalls as $call)
                    <tr id="call-{{ $call->id }}">
                        <td>
                            <p style="font-weight:600;color:#e2e8f0;margin:0;">{{ $call->table_label ?? 'No Table' }}</p>
                        </td>
                        <td>
                            <div style="display:flex;align-items:center;gap:6px;">
                                <span style="font-size:16px;">{{ $call->call_icon }}</span>
                                <span style="font-weight:500;">{{ $call->call_label }}</span>
                            </div>
                        </td>
                        <td style="color:#555;font-size:12px;">
                            {{ $call->created_at->diffForHumans() }}
                        </td>
                        <td>
                            <span class="badge b-{{ $call->status }}">
                                {{ ucfirst($call->status) }}
                            </span>
                        </td>
                        <td>
                            <div style="display:flex;gap:6px;">
                                @if($call->status === 'pending')
                                <button type="button" class="btn btn-seen" onclick="markSeen({{ $call->id }})">Mark Seen</button>
                                @endif
                                @if($call->status !== 'resolved')
                                <button type="button" class="btn btn-resolve" onclick="resolveCall({{ $call->id }})">Resolve</button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align:center;padding:48px 16px;">
                            <div style="font-size:40px;margin-bottom:12px;">🛎️</div>
                            <h3 style="font-size:15px;font-weight:600;color:#e2e8f0;margin-bottom:6px;">No active calls</h3>
                            <p style="font-size:13px;color:#555;">When customers request a waiter, it will appear here.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function markSeen(id) {
    fetch(`/dashboard/waiter-calls/${id}/seen`, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    }).then(r => r.json()).then(() => location.reload());
}

function resolveCall(id) {
    fetch(`/dashboard/waiter-calls/${id}/resolve`, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    }).then(r => r.json()).then(() => location.reload());
}

// Auto-refresh every 30 seconds
setTimeout(() => location.reload(), 30000);
</script>

@endsection
