@extends('layouts.dashboard')
@section('page-title', 'QR Codes')
@section('content')

<style>
.stat-card { background:#1a1a1a; border:1px solid #222; border-radius:12px; padding:16px; }
.stat-grid { display:grid; grid-template-columns:repeat(2,1fr); gap:12px; }
@media(min-width:768px){ .stat-grid { grid-template-columns:repeat(4,1fr); } }

.qr-grid { display:grid; grid-template-columns:1fr; gap:16px; }
@media(min-width:540px)  { .qr-grid { grid-template-columns:repeat(2,1fr); } }
@media(min-width:900px)  { .qr-grid { grid-template-columns:repeat(3,1fr); } }

.qr-card { background:#1a1a1a; border:1px solid #222; border-radius:16px; overflow:hidden; transition:border-color .2s, transform .2s; }
.qr-card:hover { border-color:#7c3aed; transform:translateY(-2px); }

.tab-btn { padding:7px 16px; border-radius:99px; font-size:13px; font-weight:500; cursor:pointer; border:1px solid #2a2a2a; background:#111; color:#666; transition:all .15s; }
.tab-btn.active { background:#7c3aed; color:#fff; border-color:#7c3aed; }
.tab-btn:hover:not(.active) { border-color:#444; color:#ccc; }

.action-btn { flex:1; display:flex; flex-direction:column; align-items:center; gap:4px; padding:10px 6px; border-radius:10px; font-size:11px; font-weight:600; text-decoration:none; cursor:pointer; border:none; transition:all .15s; }
.action-btn svg { width:16px; height:16px; }
.action-download { background:#1a0a2e; color:#c084fc; }
.action-download:hover { background:#2d1155; }
.action-print { background:#111; color:#888; border:1px solid #222; }
.action-print:hover { background:#1a1a1a; color:#ccc; }
.action-delete { background:#2d0a0a; color:#fca5a5; }
.action-delete:hover { background:#3d1010; }

.copy-btn { font-size:11px; font-weight:600; color:#7c3aed; background:none; border:none; cursor:pointer; white-space:nowrap; flex-shrink:0; }
.copy-btn:hover { color:#a78bfa; }

.usage-bar-wrap { background:#1a1a1a; border:1px solid #222; border-radius:10px; padding:12px 16px; }

/* Empty state */
.empty-state { background:#1a1a1a; border:1px solid #222; border-radius:16px; padding:60px 20px; text-align:center; }

/* Toast */
#toast { position:fixed; bottom:80px; right:20px; background:#1a1a1a; border:1px solid #7c3aed; color:#e2e8f0; font-size:13px; padding:10px 16px; border-radius:10px; opacity:0; transform:translateY(8px); transition:all .25s; pointer-events:none; z-index:100; }
#toast.show { opacity:1; transform:translateY(0); }
@media(min-width:1024px){ #toast { bottom:24px; } }
</style>

@php
    $qrLimit  = auth()->user()->restaurant->limitFor('qr_codes');
    $qrCount  = $qrCodes->count();
    $usagePct = $qrLimit >= 999 ? 0 : min(100, ($qrCount / $qrLimit) * 100);
    $atLimit  = !auth()->user()->restaurant->canAdd('qr_codes');
@endphp

<div style="max-width:1000px; display:flex; flex-direction:column; gap:16px;">

    {{-- Header --}}
    <div style="display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap;">
        <div>
            <h2 style="font-size:20px; font-weight:700; color:#fff; margin:0;">QR Codes</h2>
            <p style="font-size:13px; color:#666; margin:4px 0 0;">Generate, download and track your QR codes</p>
        </div>
        @if($atLimit)
            <a href="{{ route('dashboard.subscription.index') }}"
               style="padding:9px 16px; background:#92400e; color:#fde68a; border:none; border-radius:8px; font-size:13px; text-decoration:none; display:inline-flex; align-items:center; gap:6px; font-weight:600;">
                ⚡ Upgrade for More QR Codes
            </a>
        @else
            <a href="{{ route('dashboard.qr-codes.create') }}"
               style="padding:9px 16px; background:#7c3aed; color:#fff; border:none; border-radius:8px; font-size:13px; text-decoration:none; display:inline-flex; align-items:center; gap:6px; font-weight:600;">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:15px;height:15px">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Generate QR Code
            </a>
        @endif
    </div>

    {{-- Stats Row --}}
    <div class="stat-grid">
        <div class="stat-card">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:10px;">
                <span style="font-size:11px; color:#555; text-transform:uppercase; letter-spacing:.05em; font-weight:600;">Total QR Codes</span>
                <div style="width:30px;height:30px;background:#1a0a2e;border-radius:8px;display:flex;align-items:center;justify-content:center;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:15px;height:15px;color:#c084fc">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                    </svg>
                </div>
            </div>
            <p style="font-size:28px; font-weight:700; color:#c084fc; margin:0;">{{ $qrCount }}</p>
            <p style="font-size:11px; color:#555; margin:4px 0 0;">of {{ $qrLimit >= 999 ? '∞' : $qrLimit }} on plan</p>
            @if($qrLimit < 999)
            <div style="margin-top:8px; background:#111; border-radius:99px; height:4px;">
                <div style="height:4px; border-radius:99px; width:{{ $usagePct }}%;
                     background:{{ $usagePct >= 100 ? '#ef4444' : ($usagePct >= 80 ? '#facc15' : '#7c3aed') }};"></div>
            </div>
            @endif
        </div>

        <div class="stat-card">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:10px;">
                <span style="font-size:11px; color:#555; text-transform:uppercase; letter-spacing:.05em; font-weight:600;">Total Scans</span>
                <div style="width:30px;height:30px;background:#0f1729;border-radius:8px;display:flex;align-items:center;justify-content:center;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:15px;height:15px;color:#60a5fa">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </div>
            </div>
            <p style="font-size:28px; font-weight:700; color:#60a5fa; margin:0;">{{ number_format($qrCodes->sum('scan_count')) }}</p>
            <p style="font-size:11px; color:#555; margin:4px 0 0;">across all QR codes</p>
        </div>

        <div class="stat-card">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:10px;">
                <span style="font-size:11px; color:#555; text-transform:uppercase; letter-spacing:.05em; font-weight:600;">Been Scanned</span>
                <div style="width:30px;height:30px;background:#052e16;border-radius:8px;display:flex;align-items:center;justify-content:center;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:15px;height:15px;color:#4ade80">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <p style="font-size:28px; font-weight:700; color:#4ade80; margin:0;">{{ $qrCodes->where('scan_count','>',0)->count() }}</p>
            <p style="font-size:11px; color:#555; margin:4px 0 0;">QR codes with scans</p>
        </div>

        <div class="stat-card">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:10px;">
                <span style="font-size:11px; color:#555; text-transform:uppercase; letter-spacing:.05em; font-weight:600;">Most Scanned</span>
                <div style="width:30px;height:30px;background:#1c1100;border-radius:8px;display:flex;align-items:center;justify-content:center;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:15px;height:15px;color:#fb923c">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
            </div>
            <p style="font-size:28px; font-weight:700; color:#fb923c; margin:0;">{{ $qrCodes->max('scan_count') ?? 0 }}</p>
            <p style="font-size:11px; color:#555; margin:4px 0 0;">scans on best QR</p>
        </div>
    </div>

    {{-- Filter Tabs --}}
    @if($qrCodes->count() > 0)
    <div style="display:flex; gap:8px; flex-wrap:wrap;">
        <button class="tab-btn active" onclick="filterQR('all', this)">All ({{ $qrCodes->count() }})</button>
        @if($qrCodes->where('type','restaurant')->count())
        <button class="tab-btn" onclick="filterQR('restaurant', this)">🏠 Restaurant ({{ $qrCodes->where('type','restaurant')->count() }})</button>
        @endif
        @if($qrCodes->where('type','branch')->count())
        <button class="tab-btn" onclick="filterQR('branch', this)">🏢 Branch ({{ $qrCodes->where('type','branch')->count() }})</button>
        @endif
        @if($qrCodes->where('type','table')->count())
        <button class="tab-btn" onclick="filterQR('table', this)">🪑 Table ({{ $qrCodes->where('type','table')->count() }})</button>
        @endif
    </div>

    {{-- QR Cards Grid --}}
    <div class="qr-grid" id="qr-grid">
        @foreach($qrCodes as $qr)
        <div class="qr-card" data-type="{{ $qr->type }}">

            {{-- Card Top — Gradient Header --}}
            <div style="background:linear-gradient(135deg,#4c1d95,#1e1b4b); padding:20px; display:flex; flex-direction:column; align-items:center; position:relative;">

                {{-- Type badge --}}
                <span style="position:absolute; top:10px; right:10px; padding:3px 10px; border-radius:99px; font-size:10px; font-weight:700;
                      background:rgba(255,255,255,.12); color:rgba(255,255,255,.8);">
                    {{ ucfirst($qr->type) }}
                    @if($qr->branch) &mdash; {{ Str::limit($qr->branch->name, 12) }} @endif
                    @if($qr->table) &mdash; T{{ $qr->table->table_number }} @endif
                </span>

                {{-- QR Image --}}
                <div style="width:120px; height:120px; background:#fff; border-radius:12px; padding:8px; box-shadow:0 8px 24px rgba(0,0,0,.4);">
                    <img src="{{ route('dashboard.qr-codes.preview', $qr) }}"
                         alt="QR {{ $qr->token }}"
                         style="width:100%; height:100%; object-fit:contain;">
                </div>

                {{-- Token --}}
                <p style="font-family:monospace; font-size:16px; font-weight:700; color:#fff; margin:12px 0 4px; letter-spacing:.18em;">
                    {{ $qr->token }}
                </p>

                {{-- Scan count pill --}}
                <div style="display:flex; align-items:center; gap:5px; background:rgba(255,255,255,.1); padding:4px 12px; border-radius:99px;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:12px;height:12px;color:rgba(255,255,255,.7)">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <span style="font-size:12px; color:rgba(255,255,255,.8); font-weight:600;">
                        {{ number_format($qr->scan_count) }} {{ Str::plural('scan', $qr->scan_count) }}
                    </span>
                </div>
            </div>

            {{-- Card Bottom --}}
            <div style="padding:14px;">

                {{-- URL Row --}}
                <div style="display:flex; align-items:center; gap:8px; background:#111; border:1px solid #1f1f1f; border-radius:8px; padding:8px 12px; margin-bottom:12px;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:12px;height:12px;color:#555;flex-shrink:0">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                    </svg>
                    <span style="font-size:11px; color:#555; flex:1; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                        {{ url('/m/' . $qr->token) }}
                    </span>
                    <button class="copy-btn" onclick="copyURL('{{ url('/m/' . $qr->token) }}', this)">Copy</button>
                </div>

                {{-- Date --}}
                <p style="font-size:11px; color:#444; margin:0 0 12px;">
                    Created {{ $qr->created_at->format('d M Y') }}
                </p>

                {{-- Action Buttons --}}
                <div style="display:flex; gap:8px;">
                    <a href="{{ route('dashboard.qr-codes.download', $qr) }}" class="action-btn action-download">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        Download
                    </a>
                    <a href="{{ route('dashboard.qr-codes.print', $qr) }}" target="_blank" class="action-btn action-print">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        Print
                    </a>
                    <form method="POST" action="{{ route('dashboard.qr-codes.destroy', $qr) }}"
                          onsubmit="return confirm('Delete QR code {{ $qr->token }}?')" style="flex:1;">
                        @csrf @method('DELETE')
                        <button type="submit" class="action-btn action-delete" style="width:100%;">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            Delete
                        </button>
                    </form>
                </div>

            </div>
        </div>
        @endforeach
    </div>

    @else
    {{-- Empty State --}}
    <div class="empty-state">
        <div style="width:64px;height:64px;background:#1a0a2e;border-radius:16px;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:32px;height:32px;color:#7c3aed">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
            </svg>
        </div>
        <h3 style="font-size:16px;font-weight:700;color:#fff;margin:0 0 8px;">No QR Codes Yet</h3>
        <p style="font-size:13px;color:#555;margin:0 0 20px;max-width:280px;margin-left:auto;margin-right:auto;">
            Generate your first QR code so customers can scan and view your menu instantly.
        </p>
        <a href="{{ route('dashboard.qr-codes.create') }}"
           style="display:inline-flex;align-items:center;gap:8px;padding:10px 20px;background:#7c3aed;color:#fff;border-radius:8px;text-decoration:none;font-size:14px;font-weight:600;">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:15px;height:15px">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Generate First QR Code
        </a>
    </div>
    @endif

</div>

{{-- Toast --}}
<div id="toast">✅ URL copied!</div>

<script>
function filterQR(type, btn) {
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    document.querySelectorAll('.qr-card').forEach(card => {
        card.style.display = (type === 'all' || card.dataset.type === type) ? '' : 'none';
    });
}

function copyURL(url, btn) {
    navigator.clipboard.writeText(url).then(() => {
        const orig = btn.textContent;
        btn.textContent = '✓ Done';
        btn.style.color = '#4ade80';
        setTimeout(() => { btn.textContent = orig; btn.style.color = ''; }, 2000);
        const t = document.getElementById('toast');
        t.classList.add('show');
        setTimeout(() => t.classList.remove('show'), 2500);
    });
}
</script>

@endsection