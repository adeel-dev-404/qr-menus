@extends('layouts.dashboard')
@section('page-title', 'Call Options')
@section('content')

<style>
/* Minimal dashboard styling */
.card { background:#1a1a1a; border:1px solid #222; border-radius:14px; padding:20px; margin-bottom:20px; }
.form-group { margin-bottom:16px; }
.form-label { display:block; font-size:12px; font-weight:600; color:#888; margin-bottom:6px; }
.form-input { width:100%; background:#111; border:1px solid #2a2a2a; border-radius:8px; padding:10px; color:#e2e8f0; font-size:14px; outline:none; }
.form-input:focus { border-color:#e8502a; }
.btn-primary { background:#3b82f6; color:#fff; border:none; border-radius:8px; padding:10px 16px; font-size:14px; font-weight:600; cursor:pointer; }
.btn-primary:hover { background:#2563eb; }

.opt-table { width:100%; border-collapse:collapse; font-size:14px; }
.opt-table th { padding:12px; text-align:left; color:#555; font-size:11px; text-transform:uppercase; border-bottom:1px solid #222; }
.opt-table td { padding:12px; color:#ccc; border-bottom:1px solid #222; }
.btn-danger { background:transparent; color:#ef4444; border:1px solid #7f1d1d; border-radius:6px; padding:4px 8px; font-size:12px; cursor:pointer; }
.btn-danger:hover { background:#7f1d1d; color:#fca5a5; }
</style>

<div style="max-width:800px;">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
        <div>
            <h2 style="font-size:20px;font-weight:700;color:#fff;margin:0;">Call Options</h2>
            <p style="font-size:13px;color:#555;margin:4px 0 0;">Options customers can choose when calling a waiter</p>
        </div>
        <a href="{{ route('dashboard.waiter-calls.index') }}" style="padding:8px 14px;background:#1a1a1a;color:#ccc;border:1px solid #2a2a2a;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;">
            ← Back to Calls
        </a>
    </div>

    <div class="card">
        <h3 style="font-size:16px;font-weight:600;color:#fff;margin:0 0 16px;">Add New Option</h3>
        <form action="{{ route('dashboard.call-options.store') }}" method="POST" style="display:flex;gap:12px;align-items:flex-end;flex-wrap:wrap;">
            @csrf
            <div style="flex:1;min-width:150px;">
                <label class="form-label">Icon (Emoji or text)</label>
                <input type="text" name="icon" class="form-input" placeholder="e.g. 🍷, 💧, 🛎️" required>
            </div>
            <div style="flex:2;min-width:200px;">
                <label class="form-label">Label</label>
                <input type="text" name="label" class="form-input" placeholder="e.g. Water Refill, Bill Please" required>
            </div>
            <div>
                <button type="submit" class="btn-primary">Add Option</button>
            </div>
        </form>
    </div>

    <div class="card" style="padding:0;overflow:hidden;">
        <div style="overflow-x:auto;-webkit-overflow-scrolling:touch;">
            <table class="opt-table">
                <thead>
                    <tr>
                        <th>Icon</th>
                        <th>Label</th>
                        <th style="text-align:center;">Active</th>
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($options as $opt)
                    <tr>
                        <td style="font-size:20px;">{{ $opt->icon }}</td>
                        <td>{{ $opt->label }}</td>
                        <td style="text-align:center;">
                            <input type="checkbox" {{ $opt->is_active ? 'checked' : '' }} onchange="toggleActive({{ $opt->id }}, this.checked)">
                        </td>
                        <td style="text-align:right;">
                            <form action="{{ route('dashboard.call-options.destroy', $opt) }}" method="POST" onsubmit="return confirm('Delete this option?');" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align:center;padding:32px;">No options added yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function toggleActive(id, checked) {
    fetch(`/dashboard/call-options/${id}`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ is_active: checked ? 1 : 0 })
    });
}
</script>

@endsection
