@extends('layouts.dashboard')
@section('page-title', 'Add Branch')
@section('content')

<style>
.form-card { background:#141414; border:1px solid #1f1f1f; border-radius:14px; padding:24px; }
.form-label { display:block; font-size:13px; font-weight:600; color:#aaa; margin-bottom:8px; }
.form-input { width:100%; background:#111; border:1px solid #2a2a2a; border-radius:8px; padding:10px 12px; color:#e2e8f0; font-size:14px; outline:none; transition:border-color .15s; }
.form-input:focus { border-color:#3b82f6; }
.btn-primary { padding:11px 22px; background:#3b82f6; color:#fff; border:none; border-radius:8px; font-size:14px; cursor:pointer; font-weight:600; display:inline-flex; align-items:center; gap:8px; transition:background .15s; }
.btn-primary:hover { background:#2563eb; }
.btn-cancel { padding:11px 22px; background:#141414; color:#888; border:1px solid #2a2a2a; border-radius:8px; font-size:14px; text-decoration:none; display:inline-block; transition:all .15s; }
.btn-cancel:hover { border-color:#444; color:#ccc; }
</style>

<div style="max-width:540px;">

    {{-- Breadcrumb --}}
    <div style="display:flex; align-items:center; gap:8px; font-size:13px; color:#555; margin-bottom:18px;">
        <a href="{{ route('dashboard.branches.index') }}" style="color:#3b82f6; text-decoration:none;">Branches</a>
        <span>›</span>
        <span>Add Branch</span>
    </div>

    <div class="form-card">
        <h3 style="font-size:16px; font-weight:700; color:#fff; margin:0 0 6px;">Add New Branch</h3>
        <p style="font-size:13px; color:#666; margin:0 0 24px;">Create a new location for your restaurant. You can add tables after creating the branch.</p>

        <form method="POST" action="{{ route('dashboard.branches.store') }}">
            @csrf

            <div style="margin-bottom:18px;">
                <label class="form-label" for="branch-name">Branch Name *</label>
                <input type="text" id="branch-name" name="name" class="form-input"
                       value="{{ old('name') }}" placeholder="e.g. Main Branch, Downtown, Airport"
                       required autofocus>
                @error('name') <p style="color:#fca5a5;font-size:12px;margin-top:6px;">{{ $message }}</p> @enderror
            </div>

            <div style="margin-bottom:18px;">
                <label class="form-label" for="branch-address">Address <span style="color:#555;font-weight:400;">(optional)</span></label>
                <textarea id="branch-address" name="address" class="form-input" rows="2"
                          placeholder="Street address, city...">{{ old('address') }}</textarea>
                @error('address') <p style="color:#fca5a5;font-size:12px;margin-top:6px;">{{ $message }}</p> @enderror
            </div>

            <div style="margin-bottom:24px;">
                <label class="form-label" for="branch-phone">Phone <span style="color:#555;font-weight:400;">(optional)</span></label>
                <input type="text" id="branch-phone" name="phone" class="form-input"
                       value="{{ old('phone') }}" placeholder="e.g. 0300-1234567">
                @error('phone') <p style="color:#fca5a5;font-size:12px;margin-top:6px;">{{ $message }}</p> @enderror
            </div>

            {{-- Info --}}
            <div style="background:#0a1f3d; border:1px solid #1e3a5f; border-radius:10px; padding:12px 14px; margin-bottom:24px;">
                <p style="font-size:12px; color:#64748b; margin:0; line-height:1.6;">
                    💡 After creating the branch, you can add tables and generate QR codes for individual tables.
                </p>
            </div>

            <div style="display:flex; gap:10px; flex-wrap:wrap;">
                <button type="submit" class="btn-primary">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Create Branch
                </button>
                <a href="{{ route('dashboard.branches.index') }}" class="btn-cancel">Cancel</a>
            </div>
        </form>
    </div>
</div>

@endsection
