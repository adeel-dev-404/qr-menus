@extends('layouts.dashboard')
@section('page-title', 'Generate QR Code')
@section('content')

<style>
.form-card { background:#1a1a1a; border:1px solid #222; border-radius:14px; padding:24px; }
.form-label { display:block; font-size:13px; font-weight:600; color:#aaa; margin-bottom:8px; }
.form-input { width:100%; background:#111; border:1px solid #2a2a2a; border-radius:8px; padding:10px 12px; color:#e2e8f0; font-size:14px; outline:none; transition:border-color .15s; }
.form-input:focus { border-color:#7c3aed; }
.btn-primary { padding:11px 22px; background:#7c3aed; color:#fff; border:none; border-radius:8px; font-size:14px; cursor:pointer; font-weight:600; display:inline-flex; align-items:center; gap:8px; }
.btn-primary:hover { background:#6d28d9; }
.btn-secondary { padding:11px 22px; background:#1a1a1a; color:#888; border:1px solid #2a2a2a; border-radius:8px; font-size:14px; cursor:pointer; text-decoration:none; display:inline-block; }
.btn-secondary:hover { color:#ccc; border-color:#444; }
.type-card { border:2px solid #2a2a2a; border-radius:12px; padding:16px; text-align:center; cursor:pointer; transition:all .2s; background:#111; }
.type-card:hover { border-color:#7c3aed; background:#1a1429; }
.type-card.selected { border-color:#7c3aed; background:#1a1429; box-shadow:0 0 0 3px rgba(124,58,237,.2); }
.type-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:10px; }
@media (max-width:480px) { .type-grid { grid-template-columns:1fr; } }
.info-box { background:#0f172a; border:1px solid #1e3a5f; border-radius:10px; padding:14px 16px; }
</style>

<div style="max-width:580px;">
    <div class="form-card">

        <h3 style="font-size:16px;font-weight:700;color:#fff;margin:0 0 6px;">Generate New QR Code</h3>
        <p style="font-size:13px;color:#666;margin:0 0 24px;">Choose what this QR code points to</p>

        <form method="POST" action="{{ route('dashboard.qr-codes.store') }}">
            @csrf

            {{-- QR Type Selector --}}
            <div style="margin-bottom:20px;">
                <label class="form-label">QR Code Type *</label>
                <div class="type-grid">

                    <label style="cursor:pointer;">
                        <input type="radio" name="type" value="restaurant"
                               style="display:none;" class="type-radio"
                               {{ old('type','restaurant') === 'restaurant' ? 'checked' : '' }}>
                        <div class="type-card {{ old('type','restaurant') === 'restaurant' ? 'selected' : '' }}" id="card-restaurant">
                            <div style="font-size:28px;margin-bottom:6px;">🏠</div>
                            <p style="font-size:13px;font-weight:600;color:#e2e8f0;margin:0;">Restaurant</p>
                            <p style="font-size:11px;color:#666;margin:4px 0 0;">Full menu</p>
                        </div>
                    </label>

                    <label style="cursor:pointer;">
                        <input type="radio" name="type" value="branch"
                               style="display:none;" class="type-radio"
                               {{ old('type') === 'branch' ? 'checked' : '' }}>
                        <div class="type-card {{ old('type') === 'branch' ? 'selected' : '' }}" id="card-branch">
                            <div style="font-size:28px;margin-bottom:6px;">🏢</div>
                            <p style="font-size:13px;font-weight:600;color:#e2e8f0;margin:0;">Branch</p>
                            <p style="font-size:11px;color:#666;margin:4px 0 0;">Specific branch</p>
                        </div>
                    </label>

                    <label style="cursor:pointer;">
                        <input type="radio" name="type" value="table"
                               style="display:none;" class="type-radio"
                               {{ old('type') === 'table' ? 'checked' : '' }}>
                        <div class="type-card {{ old('type') === 'table' ? 'selected' : '' }}" id="card-table">
                            <div style="font-size:28px;margin-bottom:6px;">🪑</div>
                            <p style="font-size:13px;font-weight:600;color:#e2e8f0;margin:0;">Table</p>
                            <p style="font-size:11px;color:#666;margin:4px 0 0;">Specific table</p>
                        </div>
                    </label>

                </div>
                @error('type') <p style="color:#fca5a5;font-size:12px;margin-top:6px;">{{ $message }}</p> @enderror
            </div>

            {{-- Branch Select --}}
            <div id="branch-field" style="margin-bottom:16px; {{ old('type','restaurant') === 'restaurant' ? 'display:none;' : '' }}">
                <label class="form-label">Select Branch</label>
                <select name="branch_id" class="form-input">
                    <option value="">Choose a branch...</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                            {{ $branch->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Table Select --}}
            <div id="table-field" style="margin-bottom:16px; {{ old('type') !== 'table' ? 'display:none;' : '' }}">
                <label class="form-label">Select Table</label>
                <select name="table_id" class="form-input">
                    <option value="">Choose a table...</option>
                    @foreach($tables as $table)
                        <option value="{{ $table->id }}" {{ old('table_id') == $table->id ? 'selected' : '' }}>
                            {{ $table->branch->name }} — Table {{ $table->table_number }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Info Box --}}
            <div class="info-box" style="margin-bottom:24px;">
                <div style="display:flex;gap:10px;align-items:flex-start;">
                    <span style="font-size:18px;flex-shrink:0;">📌</span>
                    <div>
                        <p style="font-size:13px;font-weight:600;color:#93c5fd;margin:0 0 4px;">How it works</p>
                        <p style="font-size:12px;color:#64748b;margin:0;line-height:1.5;">
                            A unique 8-character token is generated. When a customer scans the QR,
                            they're redirected to your menu and the scan is logged automatically.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Buttons --}}
            <div style="display:flex;gap:10px;flex-wrap:wrap;">
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
</div>

<script>
// Type card visual selection
document.querySelectorAll('.type-radio').forEach(radio => {
    radio.addEventListener('change', function() {
        // Reset all cards
        document.querySelectorAll('.type-card').forEach(c => c.classList.remove('selected'));
        // Highlight selected
        this.closest('label').querySelector('.type-card').classList.add('selected');
        // Show/hide fields
        handleTypeChange(this.value);
    });
});

function handleTypeChange(type) {
    const branchField = document.getElementById('branch-field');
    const tableField  = document.getElementById('table-field');
    branchField.style.display = type === 'restaurant' ? 'none' : 'block';
    tableField.style.display  = type === 'table' ? 'block' : 'none';
}

// Run on load for old() state
handleTypeChange('{{ old('type', 'restaurant') }}');
</script>

@endsection