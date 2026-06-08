@extends('layouts.dashboard')
@section('page-title', 'Edit Branch')
@section('content')

<style>
.bc { background:#141414; border:1px solid #1f1f1f; border-radius:14px; overflow:hidden; }
.bc-h { padding:14px 18px; border-bottom:1px solid #1f1f1f; display:flex; align-items:center; justify-content:space-between; font-size:13px; font-weight:600; color:#bbb; }
.form-label { display:block; font-size:12px; font-weight:600; color:#aaa; margin-bottom:6px; }
.form-input { width:100%; background:#111; border:1px solid #2a2a2a; border-radius:8px; padding:9px 12px; color:#e2e8f0; font-size:13px; outline:none; transition:border-color .15s; box-sizing:border-box; }
.form-input:focus { border-color:#3b82f6; }
.btn-blue { padding:9px 18px; background:#3b82f6; color:#fff; border:none; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer; transition:background .15s; }
.btn-blue:hover { background:#2563eb; }
.btn-ghost { padding:9px 16px; background:#141414; color:#888; border:1px solid #2a2a2a; border-radius:8px; font-size:13px; text-decoration:none; display:inline-block; cursor:pointer; transition:all .15s; }
.btn-ghost:hover { border-color:#555; color:#ccc; }
.btn-danger { padding:7px 12px; background:#2d0a0a; border:1px solid #3d1010; border-radius:7px; color:#fca5a5; font-size:12px; cursor:pointer; transition:background .15s; }
.btn-danger:hover { background:#3d1010; }
.table-row { display:grid; grid-template-columns: 1fr 70px auto auto auto; gap:10px; align-items:center; padding:10px 18px; border-bottom:1px solid #1a1a1a; }
.table-row:last-child { border-bottom:none; }
.table-row:hover { background:#181818; }
@media(max-width:600px) {
    .table-row { grid-template-columns: 1fr 60px auto; }
    .table-row .capacity-col, .table-row .edit-col { display:none; }
}
.tag { display:inline-flex; align-items:center; gap:4px; padding:3px 10px; background:#111; border:1px solid #222; border-radius:7px; font-size:12px; color:#aaa; }
</style>

<div style="max-width:760px; display:flex; flex-direction:column; gap:16px;">

    {{-- Breadcrumb --}}
    <div style="display:flex; align-items:center; gap:8px; font-size:13px; color:#555;">
        <a href="{{ route('dashboard.branches.index') }}" style="color:#3b82f6; text-decoration:none;">Branches</a>
        <span>›</span>
        <span style="color:#ccc;">{{ $branch->name }}</span>
    </div>

    {{-- ═══ Edit Branch Info ═══ --}}
    <div class="bc">
        <div class="bc-h">
            <span>🏢 Branch Details</span>
        </div>
        <div style="padding:20px 18px;">
            <form method="POST" action="{{ route('dashboard.branches.update', $branch) }}">
                @csrf @method('PATCH')
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px; margin-bottom:14px;">
                    <div>
                        <label class="form-label" for="b-name">Branch Name *</label>
                        <input type="text" id="b-name" name="name" class="form-input"
                               value="{{ old('name', $branch->name) }}" required>
                        @error('name') <p style="color:#fca5a5;font-size:11px;margin-top:4px;">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label" for="b-phone">Phone</label>
                        <input type="text" id="b-phone" name="phone" class="form-input"
                               value="{{ old('phone', $branch->phone) }}" placeholder="Optional">
                    </div>
                </div>
                <div style="margin-bottom:18px;">
                    <label class="form-label" for="b-address">Address</label>
                    <textarea id="b-address" name="address" class="form-input" rows="2"
                              placeholder="Optional">{{ old('address', $branch->address) }}</textarea>
                </div>
                <div style="display:flex; gap:10px; align-items:center;">
                    <button type="submit" class="btn-blue">Save Changes</button>
                    <a href="{{ route('dashboard.branches.index') }}" class="btn-ghost">← Back</a>
                </div>
            </form>
        </div>
    </div>

    {{-- ═══ Tables Management ═══ --}}
    <div class="bc">
        <div class="bc-h">
            <span>🪑 Tables <span style="color:#555;font-weight:400;">({{ $branch->tables->count() }})</span></span>
            <button onclick="document.getElementById('add-table-panel').classList.toggle('hidden')"
                    class="btn-blue" style="font-size:12px;padding:6px 14px;">
                + Add Table
            </button>
        </div>

        {{-- Add Table Form (hidden by default) --}}
        <div id="add-table-panel" class="hidden" style="padding:16px 18px; background:#0d0d0d; border-bottom:1px solid #1f1f1f;">
            <form method="POST" action="{{ route('dashboard.branches.tables.store', $branch) }}">
                @csrf
                <div style="display:grid; grid-template-columns:1fr 100px auto; gap:10px; align-items:flex-end;">
                    <div>
                        <label class="form-label">Table Number *</label>
                        <input type="text" name="table_number" class="form-input"
                               value="{{ old('table_number') }}"
                               placeholder="e.g. 1, 2, A1, VIP-1" required>
                        @error('table_number') <p style="color:#fca5a5;font-size:11px;margin-top:4px;">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Capacity</label>
                        <input type="number" name="capacity" class="form-input"
                               value="{{ old('capacity', 4) }}" min="1" max="100" placeholder="4">
                    </div>
                    <button type="submit" class="btn-blue">Add</button>
                </div>
            </form>

            {{-- Bulk add helper --}}
            <div style="margin-top:12px; padding-top:12px; border-top:1px solid #1f1f1f;">
                <p style="font-size:12px; color:#555; margin:0 0 10px;">Or bulk-add multiple tables at once:</p>
                <form method="POST" action="{{ route('dashboard.branches.tables.bulk', $branch) }}">
                    @csrf
                    <div style="display:grid; grid-template-columns:80px 80px 100px auto; gap:10px; align-items:flex-end;">
                        <div>
                            <label class="form-label">From #</label>
                            <input type="number" name="from" class="form-input" value="1" min="1" placeholder="1">
                        </div>
                        <div>
                            <label class="form-label">To #</label>
                            <input type="number" name="to" class="form-input" value="10" min="1" placeholder="10">
                        </div>
                        <div>
                            <label class="form-label">Capacity each</label>
                            <input type="number" name="capacity" class="form-input" value="4" min="1" max="100">
                        </div>
                        <button type="submit" class="btn-ghost" style="background:#052e16;border-color:#166534;color:#4ade80;">
                            Bulk Add
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Tables List --}}
        @if($branch->tables->isEmpty())
            <div style="padding:32px 18px; text-align:center;">
                <p style="font-size:36px; margin:0 0 8px;">🪑</p>
                <p style="font-size:14px; font-weight:600; color:#fff; margin:0 0 4px;">No tables yet</p>
                <p style="font-size:13px; color:#555; margin:0;">Click "Add Table" above to add your first table to this branch.</p>
            </div>
        @else
            {{-- Header --}}
            <div style="display:grid; grid-template-columns:1fr 70px auto auto auto; gap:10px; padding:8px 18px; border-bottom:1px solid #1a1a1a;">
                <p style="font-size:10px;font-weight:600;color:#555;text-transform:uppercase;margin:0;">Table #</p>
                <p style="font-size:10px;font-weight:600;color:#555;text-transform:uppercase;margin:0;">Capacity</p>
                <p style="font-size:10px;font-weight:600;color:#555;text-transform:uppercase;margin:0; min-width:80px;">QR Code</p>
                <p style="font-size:10px;font-weight:600;color:#555;text-transform:uppercase;margin:0;"></p>
                <p style="font-size:10px;font-weight:600;color:#555;text-transform:uppercase;margin:0;"></p>
            </div>

            @foreach($branch->tables as $table)
            <div class="table-row" id="table-row-{{ $table->id }}">
                {{-- Table number (inline edit) --}}
                <form method="POST" action="{{ route('dashboard.branches.tables.update', [$branch, $table]) }}"
                      id="form-{{ $table->id }}" style="display:contents;">
                    @csrf @method('PATCH')
                    <div style="display:flex; align-items:center; gap:10px;">
                        <div style="width:32px;height:32px;background:#0a1f3d;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:700;color:#60a5fa;flex-shrink:0;">
                            {{ is_numeric($table->table_number) ? $table->table_number : '🪑' }}
                        </div>
                        <div>
                            <input type="text" name="table_number"
                                   class="form-input" style="padding:5px 8px; font-size:13px; width:100px;"
                                   value="{{ $table->table_number }}">
                        </div>
                    </div>
                    <div class="capacity-col">
                        <input type="number" name="capacity" class="form-input"
                               style="padding:5px 8px; font-size:13px;"
                               value="{{ $table->capacity }}" min="1" max="100">
                    </div>
                </form>

                {{-- QR link --}}
                <div style="min-width:80px;">
                    @php
                        $tableQr = $table->qrCodes()->first() ?? null;
                    @endphp
                    @if($tableQr)
                        <a href="{{ route('dashboard.qr-codes.index') }}"
                           class="tag" style="color:#4ade80; border-color:#166534;">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:10px;height:10px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"/></svg>
                            Has QR
                        </a>
                    @else
                        <a href="{{ route('dashboard.qr-codes.create', ['type'=>'table', 'table_id'=>$table->id, 'branch_id'=>$branch->id]) }}"
                           class="tag" style="color:#fb923c; border-color:#92400e;">
                            + QR Code
                        </a>
                    @endif
                </div>

                {{-- Save button --}}
                <div class="edit-col">
                    <button type="submit" form="form-{{ $table->id }}"
                            class="btn-blue" style="font-size:11px; padding:5px 10px;">Save</button>
                </div>

                {{-- Delete --}}
                <div>
                    <form method="POST" action="{{ route('dashboard.branches.tables.destroy', [$branch, $table]) }}"
                          onsubmit="return confirm('Delete Table #{{ $table->table_number }}?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-danger">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:13px;height:13px">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        @endif
    </div>

    {{-- Generate QR for this branch --}}
    <div class="bc" style="padding:16px 18px; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px;">
        <div>
            <p style="font-size:13px; font-weight:600; color:#ccc; margin:0 0 3px;">Branch QR Code</p>
            <p style="font-size:12px; color:#555; margin:0;">Generate a QR code for this specific branch location</p>
        </div>
        <a href="{{ route('dashboard.qr-codes.create', ['type'=>'branch', 'branch_id'=>$branch->id]) }}"
           class="btn-blue" style="font-size:13px;">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:14px;height:14px">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
            </svg>
            Generate Branch QR
        </a>
    </div>

</div>

<style>
.hidden { display:none !important; }
</style>

<script>
// Auto-show add panel if there are validation errors for table_number
@if($errors->has('table_number') || $errors->has('from') || $errors->has('to'))
    document.getElementById('add-table-panel').classList.remove('hidden');
@endif
</script>

@endsection
