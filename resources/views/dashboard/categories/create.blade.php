@extends('layouts.dashboard')
@section('page-title', 'New Category')
@section('content')

<style>
.form-card { background:#1a1a1a; border:1px solid #222; border-radius:14px; padding:24px; }
.form-label { display:block; font-size:13px; font-weight:600; color:#aaa; margin-bottom:6px; }
.form-input {
    width:100%; background:#111; border:1px solid #2a2a2a; border-radius:8px;
    padding:10px 12px; color:#e2e8f0; font-size:14px; outline:none;
    transition: border-color 0.15s;
}
.form-input:focus { border-color:#3b82f6; }
.form-input.error { border-color:#ef4444; }
.form-error { color:#fca5a5; font-size:12px; margin-top:4px; }
.form-hint  { color:#555; font-size:12px; margin-top:4px; }
.btn-primary { padding:10px 20px; background:#1d4ed8; color:#fff; border:none; border-radius:8px; font-size:14px; cursor:pointer; font-weight:600; }
.btn-primary:hover { background:#1e40af; }
.btn-secondary { padding:10px 20px; background:#1a1a1a; color:#888; border:1px solid #2a2a2a; border-radius:8px; font-size:14px; cursor:pointer; text-decoration:none; }
.btn-secondary:hover { color:#ccc; border-color:#444; }
.toggle-wrap { display:flex; align-items:center; gap:10px; cursor:pointer; }
.toggle-wrap input[type="checkbox"] { width:18px; height:18px; accent-color:#3b82f6; cursor:pointer; }
</style>

<div style="max-width:560px;">
    <div class="form-card">
        <h3 style="font-size:16px; font-weight:700; color:#fff; margin:0 0 20px;">New Category</h3>

        <form method="POST" action="{{ route('dashboard.categories.store') }}" enctype="multipart/form-data">
            @csrf

            {{-- Name --}}
            <div style="margin-bottom:16px;">
                <label class="form-label">Name *</label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. Burgers"
                       class="form-input {{ $errors->has('name') ? 'error' : '' }}">
                @error('name') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            {{-- Image Upload --}}
            <div style="margin-bottom:16px;">
                <label class="form-label">Category Image</label>
                <div style="border:1px dashed #2a2a2a; border-radius:8px; padding:20px; text-align:center; cursor:pointer; position:relative;"
                     onclick="document.getElementById('imgInput').click()"
                     id="imgDropZone">
                    <div id="imgPreview" style="display:none; margin-bottom:10px;">
                        <img id="previewImg" style="max-height:100px; border-radius:8px; margin:0 auto;">
                    </div>
                    <div id="imgPlaceholder">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:32px;height:32px;color:#444;margin:0 auto 8px;display:block">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p style="color:#555; font-size:13px; margin:0;">Click to upload image</p>
                        <p style="color:#444; font-size:11px; margin:4px 0 0;">JPG, PNG, WebP — max 2MB</p>
                    </div>
                    <input type="file" id="imgInput" name="image" accept="image/*" style="display:none;"
                           onchange="previewImage(this)">
                </div>
                @error('image') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            {{-- Sort Order --}}
            <div style="margin-bottom:16px;">
                <label class="form-label">Sort Order</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0"
                       class="form-input" style="max-width:120px;">
                <p class="form-hint">Lower number = appears first in menu</p>
            </div>

            {{-- Status Toggle --}}
            <div style="margin-bottom:24px; background:#111; border:1px solid #222; border-radius:8px; padding:14px 16px; display:flex; align-items:center; justify-content:space-between;">
                <div>
                    <p style="color:#e2e8f0; font-size:14px; font-weight:600; margin:0;">Active</p>
                    <p style="color:#555; font-size:12px; margin:2px 0 0;">Show this category on the public menu</p>
                </div>
                <div class="toggle-wrap">
                    <input type="hidden" name="status" value="0">
                    <input type="checkbox" name="status" value="1" id="status"
                           {{ old('status', 1) ? 'checked' : '' }}>
                    <label for="status" style="color:#888; font-size:13px;">{{ old('status', 1) ? 'Yes' : 'No' }}</label>
                </div>
            </div>

            {{-- Buttons --}}
            <div style="display:flex; gap:10px; flex-wrap:wrap;">
                <button type="submit" class="btn-primary">Create Category</button>
                <a href="{{ route('dashboard.categories.index') }}" class="btn-secondary">Cancel</a>
            </div>

        </form>
    </div>
</div>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('imgPreview').style.display = 'block';
            document.getElementById('imgPlaceholder').style.display = 'none';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

@endsection