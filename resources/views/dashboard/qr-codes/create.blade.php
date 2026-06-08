@extends('layouts.dashboard')
@section('page-title', 'Generate QR Code')
@section('content')

<style>
.form-card { background:#141414; border:1px solid #1f1f1f; border-radius:14px; padding:24px; }
.form-label { display:block; font-size:13px; font-weight:600; color:#aaa; margin-bottom:8px; }
.form-input { width:100%; background:#111; border:1px solid #2a2a2a; border-radius:8px; padding:10px 12px; color:#e2e8f0; font-size:14px; outline:none; transition:border-color .15s; box-sizing:border-box; }
.form-input:focus { border-color:#7c3aed; }
.btn-primary { padding:11px 22px; background:#7c3aed; color:#fff; border:none; border-radius:8px; font-size:14px; cursor:pointer; font-weight:600; display:inline-flex; align-items:center; gap:8px; transition:background .15s; }
.btn-primary:hover { background:#6d28d9; }
.btn-secondary { padding:11px 22px; background:#141414; color:#888; border:1px solid #2a2a2a; border-radius:8px; font-size:14px; cursor:pointer; text-decoration:none; display:inline-block; transition:all .15s; }
.btn-secondary:hover { color:#ccc; border-color:#444; }

/* Type selector */
.type-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:10px; }
@media(max-width:480px) { .type-grid { grid-template-columns:1fr; } }
.type-card { border:2px solid #2a2a2a; border-radius:12px; padding:16px; text-align:center; cursor:pointer; transition:all .2s; background:#0d0d0d; }
.type-card:hover { border-color:#7c3aed; background:#160d29; }
.type-card.selected { border-color:#7c3aed; background:#160d29; box-shadow:0 0 0 3px rgba(124,58,237,.2); }

/* Info / warning box */
.info-box { background:#0a1f3d; border:1px solid #1e3a5f; border-radius:10px; padding:14px 16px; }
.warn-box  { background:#2d1400; border:1px solid #92400e; border-radius:10px; padding:14px 16px; }

/* Branch/table panels */
.field-group { margin-bottom:16px; animation: fadeIn .2s ease; }
@keyframes fadeIn { from { opacity:0; transform:translateY(-4px); } to { opacity:1; transform:none; } }

select.form-input option { background:#111; }
</style>

<div style="max-width:600px;">

    {{-- Breadcrumb --}}
    <div style="display:flex; align-items:center; gap:8px; font-size:13px; color:#555; margin-bottom:18px;">
        <a href="{{ route('dashboard.qr-codes.index') }}" style="color:#7c3aed; text-decoration:none;">QR Codes</a>
        <span>›</span>
        <span>Generate New</span>
    </div>

    <div class="form-card">

        <h3 style="font-size:16px; font-weight:700; color:#fff; margin:0 0 4px;">Generate New QR Code</h3>
        <p style="font-size:13px; color:#666; margin:0 0 24px;">Choose what this QR code points to</p>

        @if($branches->isEmpty() && request('type') !== 'restaurant')
        <div class="warn-box" style="margin-bottom:20px;">
            <p style="font-size:13px; color:#fb923c; margin:0; line-height:1.5;">
                ⚠️ You need at least one branch before creating a Branch or Table QR code.
                <a href="{{ route('dashboard.branches.create') }}" style="color:#fb923c; font-weight:600;">Add a branch →</a>
            </p>
        </div>
        @endif

        <form method="POST" action="{{ route('dashboard.qr-codes.store') }}">
            @csrf

            {{-- ── Type Selector ── --}}
            <div style="margin-bottom:22px;">
                <label class="form-label">QR Code Type *</label>
                <div class="type-grid">

                    <label style="cursor:pointer;">
                        <input type="radio" name="type" value="restaurant" style="display:none;" class="type-radio"
                               {{ old('type', request('type', 'restaurant')) === 'restaurant' ? 'checked' : '' }}>
                        <div class="type-card {{ old('type', request('type', 'restaurant')) === 'restaurant' ? 'selected' : '' }}" id="card-restaurant">
                            <div style="font-size:28px; margin-bottom:6px;">🏠</div>
                            <p style="font-size:13px; font-weight:600; color:#e2e8f0; margin:0;">Restaurant</p>
                            <p style="font-size:11px; color:#666; margin:4px 0 0;">Entire menu</p>
                        </div>
                    </label>

                    <label style="cursor:pointer;">
                        <input type="radio" name="type" value="branch" style="display:none;" class="type-radio"
                               {{ old('type', request('type')) === 'branch' ? 'checked' : '' }}>
                        <div class="type-card {{ old('type', request('type')) === 'branch' ? 'selected' : '' }}" id="card-branch">
                            <div style="font-size:28px; margin-bottom:6px;">🏢</div>
                            <p style="font-size:13px; font-weight:600; color:#e2e8f0; margin:0;">Branch</p>
                            <p style="font-size:11px; color:#666; margin:4px 0 0;">Specific branch</p>
                        </div>
                    </label>

                    <label style="cursor:pointer;">
                        <input type="radio" name="type" value="table" style="display:none;" class="type-radio"
                               {{ old('type', request('type')) === 'table' ? 'checked' : '' }}>
                        <div class="type-card {{ old('type', request('type')) === 'table' ? 'selected' : '' }}" id="card-table">
                            <div style="font-size:28px; margin-bottom:6px;">🪑</div>
                            <p style="font-size:13px; font-weight:600; color:#e2e8f0; margin:0;">Table</p>
                            <p style="font-size:11px; color:#666; margin:4px 0 0;">Specific table</p>
                        </div>
                    </label>

                </div>
                @error('type') <p style="color:#fca5a5; font-size:12px; margin-top:6px;">{{ $message }}</p> @enderror
            </div>

            {{-- ── Branch Select (shown for branch + table types) ── --}}
            <div id="branch-field" class="field-group" style="display:none;">
                <label class="form-label" for="branch-select">
                    Select Branch <span style="color:#ef4444;">*</span>
                </label>
                @if($branches->isEmpty())
                    <div class="warn-box">
                        <p style="font-size:13px; color:#fb923c; margin:0;">
                            No branches found. <a href="{{ route('dashboard.branches.create') }}" style="color:#fb923c; font-weight:600;">Create one first →</a>
                        </p>
                    </div>
                @else
                    <select name="branch_id" id="branch-select" class="form-input" onchange="onBranchChange(this.value)">
                        <option value="">Choose a branch...</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}"
                                    data-tables="{{ $branch->tables->map(fn($t)=>['id'=>$t->id,'number'=>$t->table_number,'capacity'=>$t->capacity])->toJson() }}"
                                    {{ old('branch_id', request('branch_id')) == $branch->id ? 'selected' : '' }}>
                                {{ $branch->name }}
                                @if($branch->address) — {{ Str::limit($branch->address, 30) }} @endif
                            </option>
                        @endforeach
                    </select>
                @endif
                @error('branch_id') <p style="color:#fca5a5; font-size:12px; margin-top:6px;">{{ $message }}</p> @enderror
            </div>

            {{-- ── Table Select (shown only for table type) ── --}}
            <div id="table-field" class="field-group" style="display:none;">
                <label class="form-label" for="table-select">
                    Select Table <span style="color:#ef4444;">*</span>
                </label>
                <select name="table_id" id="table-select" class="form-input">
                    <option value="">— Select a branch first —</option>
                </select>
                <p id="table-empty-hint" style="font-size:12px; color:#fb923c; margin-top:6px; display:none;">
                    This branch has no tables yet.
                    <a href="{{ route('dashboard.branches.index') }}" style="color:#fb923c; font-weight:600;">Add tables to this branch →</a>
                </p>
                @error('table_id') <p style="color:#fca5a5; font-size:12px; margin-top:6px;">{{ $message }}</p> @enderror
            </div>

            {{-- ── Info Box ── --}}
            <div class="info-box" id="info-box" style="margin-bottom:24px;">
                <div style="display:flex; gap:10px; align-items:flex-start;">
                    <span style="font-size:18px; flex-shrink:0;" id="info-icon">🏠</span>
                    <div>
                        <p style="font-size:13px; font-weight:600; color:#93c5fd; margin:0 0 4px;" id="info-title">Restaurant QR Code</p>
                        <p style="font-size:12px; color:#64748b; margin:0; line-height:1.5;" id="info-desc">
                            Links directly to your full menu. Great for front-door, window stickers, or flyers.
                        </p>
                    </div>
                </div>
            </div>

            {{-- ── Buttons ── --}}
            <div style="display:flex; gap:10px; flex-wrap:wrap;">
                <button type="submit" class="btn-primary">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                    </svg>
                    Generate QR Code
                </button>
                <a href="{{ route('dashboard.qr-codes.index') }}" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>

    {{-- No branches tip --}}
    @if($branches->isEmpty())
    <div style="background:#141414; border:1px solid #1f1f1f; border-radius:14px; padding:18px; margin-top:12px;">
        <p style="font-size:13px; font-weight:600; color:#ccc; margin:0 0 6px;">💡 Want branch & table QR codes?</p>
        <p style="font-size:12px; color:#555; margin:0 0 12px; line-height:1.6;">
            First create a branch, then add tables to it. Each table can have its own QR code so customers can order right from their seat.
        </p>
        <a href="{{ route('dashboard.branches.create') }}"
           style="display:inline-flex; align-items:center; gap:6px; padding:8px 14px; background:#0a1f3d; border:1px solid #1e3a5f; border-radius:8px; color:#60a5fa; font-size:13px; text-decoration:none; font-weight:600;">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:14px;height:14px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Create First Branch
        </a>
    </div>
    @endif

</div>

<script>
// ── Info content by type ──
const infoMap = {
    restaurant: {
        icon: '🏠', title: 'Restaurant QR Code',
        desc: 'Links directly to your full menu. Great for front-door, window stickers, or flyers.'
    },
    branch: {
        icon: '🏢', title: 'Branch QR Code',
        desc: 'Links to the menu for a specific branch location. Perfect for multi-location restaurants.'
    },
    table: {
        icon: '🪑', title: 'Table QR Code',
        desc: 'Placed on a specific table. When a customer scans it, the menu pre-fills their table number for ordering.'
    },
};

// ── All tables data from server ──
const allBranches = {};
document.querySelectorAll('#branch-select option[data-tables]').forEach(opt => {
    allBranches[opt.value] = JSON.parse(opt.dataset.tables || '[]');
});

function handleTypeChange(type) {
    const branchField = document.getElementById('branch-field');
    const tableField  = document.getElementById('table-field');

    branchField.style.display = (type === 'branch' || type === 'table') ? 'block' : 'none';
    tableField.style.display  = type === 'table' ? 'block' : 'none';

    // Update info box
    const info = infoMap[type] || infoMap.restaurant;
    document.getElementById('info-icon').textContent  = info.icon;
    document.getElementById('info-title').textContent = info.title;
    document.getElementById('info-desc').textContent  = info.desc;
}

function onBranchChange(branchId) {
    const sel   = document.getElementById('table-select');
    const hint  = document.getElementById('table-empty-hint');
    const tables = allBranches[branchId] || [];

    // Clear
    sel.innerHTML = '';

    if (!branchId) {
        sel.innerHTML = '<option value="">— Select a branch first —</option>';
        hint.style.display = 'none';
        return;
    }

    if (tables.length === 0) {
        sel.innerHTML = '<option value="">No tables in this branch</option>';
        hint.style.display = 'block';
        return;
    }

    hint.style.display = 'none';
    sel.innerHTML = '<option value="">Choose a table...</option>';
    tables.forEach(t => {
        const opt = document.createElement('option');
        opt.value = t.id;
        opt.textContent = `Table ${t.number}` + (t.capacity ? ` (${t.capacity} seats)` : '');
        // Pre-select from query param
        if (String(t.id) === '{{ request("table_id") }}') opt.selected = true;
        sel.appendChild(opt);
    });
}

// ── Type card selection ──
document.querySelectorAll('.type-radio').forEach(radio => {
    radio.addEventListener('change', function () {
        document.querySelectorAll('.type-card').forEach(c => c.classList.remove('selected'));
        this.closest('label').querySelector('.type-card').classList.add('selected');
        handleTypeChange(this.value);
    });
});

// ── Init on page load ──
const initialType = '{{ old("type", request("type", "restaurant")) }}';
handleTypeChange(initialType);

// Trigger branch change to populate tables if we have a pre-selected branch
const branchSel = document.getElementById('branch-select');
if (branchSel && branchSel.value) {
    onBranchChange(branchSel.value);
    // Then re-select the table
    setTimeout(() => {
        const tableSel = document.getElementById('table-select');
        const preTable = '{{ request("table_id") }}';
        if (tableSel && preTable) {
            Array.from(tableSel.options).forEach(opt => {
                if (opt.value === preTable) opt.selected = true;
            });
        }
    }, 50);
}
</script>

@endsection