@extends('layouts.dashboard')
@section('page-title', 'New Product')
@section('content')

<style>
.form-card { background:#1a1a1a; border:1px solid #222; border-radius:14px; padding:24px; margin-bottom:16px; }
.form-label { display:block; font-size:13px; font-weight:600; color:#aaa; margin-bottom:6px; }
.form-input { width:100%; background:#111; border:1px solid #2a2a2a; border-radius:8px; padding:10px 12px; color:#e2e8f0; font-size:14px; outline:none; transition:border-color .15s; }
.form-input:focus { border-color:#3b82f6; }
.form-input.error { border-color:#ef4444; }
.form-error { color:#fca5a5; font-size:12px; margin-top:4px; }
.form-hint  { color:#555; font-size:12px; margin-top:4px; }
.two-col { display:grid; grid-template-columns:1fr 1fr; gap:16px; }
@media(max-width:480px){ .two-col { grid-template-columns:1fr; } }
.btn-primary { padding:11px 22px; background:#1d4ed8; color:#fff; border:none; border-radius:8px; font-size:14px; cursor:pointer; font-weight:600; }
.btn-primary:hover { background:#1e40af; }
.btn-secondary { padding:11px 22px; background:#1a1a1a; color:#888; border:1px solid #2a2a2a; border-radius:8px; font-size:14px; cursor:pointer; text-decoration:none; display:inline-block; }

/* Variant rows */
.variant-row {
    display:grid;
    grid-template-columns: 1fr 100px 100px 36px 36px;
    gap:8px;
    align-items:center;
    background:#111;
    border:1px solid #222;
    border-radius:10px;
    padding:10px 12px;
    margin-bottom:8px;
}
@media(max-width:560px){
    .variant-row {
        grid-template-columns: 1fr 1fr;
        grid-template-rows: auto auto auto;
    }
    .variant-row .v-avail { grid-column: 1; }
    .variant-row .v-del   { grid-column: 2; justify-self:end; }
}
.variant-input {
    background:#0f0f0f;
    border:1px solid #2a2a2a;
    border-radius:6px;
    padding:7px 10px;
    color:#e2e8f0;
    font-size:13px;
    outline:none;
    width:100%;
}
.variant-input:focus { border-color:#3b82f6; }
.variant-input::placeholder { color:#444; }
.v-del-btn {
    width:32px; height:32px;
    background:#2d0a0a;
    border:1px solid #7f1d1d;
    border-radius:7px;
    color:#fca5a5;
    cursor:pointer;
    display:flex; align-items:center; justify-content:center;
    flex-shrink:0;
}
.v-del-btn:hover { background:#3d1010; }
.v-avail-btn {
    width:32px; height:32px;
    background:#052e16;
    border:1px solid #166534;
    border-radius:7px;
    color:#4ade80;
    cursor:pointer;
    display:flex; align-items:center; justify-content:center;
    flex-shrink:0;
    position:relative;
}
.v-avail-btn.off {
    background:#1a1a1a;
    border-color:#2a2a2a;
    color:#555;
}
.add-variant-btn {
    display:flex; align-items:center; gap:8px;
    padding:10px 16px;
    background:#0f1729;
    border:1px dashed #1e3a5f;
    border-radius:10px;
    color:#60a5fa;
    font-size:13px;
    font-weight:600;
    cursor:pointer;
    width:100%;
    transition:all .15s;
}
.add-variant-btn:hover { background:#162035; border-color:#3b82f6; }
.variant-header {
    display:grid;
    grid-template-columns: 1fr 100px 100px 36px 36px;
    gap:8px;
    padding:0 12px 6px;
    font-size:11px;
    font-weight:600;
    color:#555;
    text-transform:uppercase;
    letter-spacing:.05em;
}
@media(max-width:560px){ .variant-header { display:none; } }
.section-title {
    font-size:15px; font-weight:700; color:#fff; margin:0 0 4px;
    display:flex; align-items:center; gap:8px;
}
.section-sub { font-size:12px; color:#666; margin:0 0 16px; }
.toggle-row {
    background:#111; border:1px solid #222; border-radius:8px;
    padding:14px 16px; display:flex; align-items:center; justify-content:space-between;
}
</style>

<div style="max-width:680px;">

    <form method="POST" action="{{ route('dashboard.products.store') }}" enctype="multipart/form-data" id="productForm">
    @csrf

    {{-- ===== BASIC INFO ===== --}}
    <div class="form-card">
        <p class="section-title">🍔 Product Info</p>
        <p class="section-sub">Basic details shown on the menu</p>

        <div style="margin-bottom:16px;">
            <label class="form-label">Product Name *</label>
            <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. Margherita Pizza"
                   class="form-input {{ $errors->has('name') ? 'error' : '' }}">
            @error('name') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        <div style="margin-bottom:16px;">
            <label class="form-label">Category *</label>
            <select name="category_id" class="form-input {{ $errors->has('category_id') ? 'error' : '' }}">
                <option value="">Select category</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
            @error('category_id') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        <div style="margin-bottom:16px;">
            <label class="form-label">Description</label>
            <textarea name="description" rows="3" placeholder="Short description shown on the menu..."
                      class="form-input" style="resize:vertical;">{{ old('description') }}</textarea>
        </div>

        {{-- Image --}}
        <div>
            <label class="form-label">Product Image</label>
            <div style="border:1px dashed #2a2a2a;border-radius:8px;padding:20px;text-align:center;cursor:pointer;"
                 onclick="document.getElementById('productImg').click()">
                <div id="imgPreview" style="display:none;margin-bottom:10px;">
                    <img id="previewImg" style="max-height:120px;border-radius:8px;margin:0 auto;display:block;">
                </div>
                <div id="imgPlaceholder">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:32px;height:32px;color:#444;margin:0 auto 8px;display:block">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p style="color:#555;font-size:13px;margin:0;">Click to upload product image</p>
                    <p style="color:#444;font-size:11px;margin:4px 0 0;">JPG, PNG, WebP — max 2MB</p>
                </div>
                <input type="file" id="productImg" name="image" accept="image/*" style="display:none;"
                       onchange="previewImage(this)">
            </div>
        </div>
    </div>

    {{-- ===== PRICING ===== --}}
    <div class="form-card">
        <p class="section-title">💰 Pricing</p>
        <p class="section-sub" id="pricingSubtext">Set a base price, or use variants below for multiple sizes/options</p>

        {{-- Base price (hidden when variants exist) --}}
        <div id="basePriceSection">
            <div class="two-col" style="margin-bottom:0;">
                <div>
                    <label class="form-label">Base Price (Rs.) *</label>
                    <input type="number" name="price" id="basePrice" value="{{ old('price', 0) }}"
                           step="0.01" min="0" placeholder="0.00"
                           class="form-input {{ $errors->has('price') ? 'error' : '' }}">
                    @error('price') <p class="form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">Discount Price (Rs.)</label>
                    <input type="number" name="discount_price" id="discountPrice"
                           value="{{ old('discount_price') }}" step="0.01" min="0"
                           placeholder="Leave empty if none" class="form-input">
                    <p class="form-hint">Must be lower than base price</p>
                </div>
            </div>
        </div>

        {{-- Note when variants are added --}}
        <div id="variantPriceNote" style="display:none; background:#0f172a; border:1px solid #1e3a5f; border-radius:8px; padding:12px 14px;">
            <p style="font-size:13px; color:#93c5fd; margin:0;">
                📌 Pricing is set per variant below. The base price will be set to the lowest variant price automatically.
            </p>
        </div>
    </div>

    {{-- ===== VARIANTS ===== --}}
    <div class="form-card">
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:4px;">
            <p class="section-title">🔀 Variants <span style="font-size:12px;font-weight:400;color:#555;">(optional)</span></p>
            <span style="font-size:12px;color:#555;" id="variantCount">0 variants</span>
        </div>
        <p class="section-sub">Add sizes, options or extras with different prices. e.g. Small / Medium / Large</p>

        {{-- Variant column headers --}}
        <div class="variant-header">
            <span>Variant Name</span>
            <span>Price (Rs.)</span>
            <span>Discount</span>
            <span>On</span>
            <span></span>
        </div>

        {{-- Variant rows container --}}
        <div id="variantRows"></div>

        {{-- Add variant button --}}
        <button type="button" class="add-variant-btn" onclick="addVariantRow()">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add Variant
        </button>

        <p style="font-size:11px;color:#444;margin:10px 0 0;">
            💡 Examples: Small / Medium / Large &nbsp;|&nbsp; Thin Crust / Thick Crust &nbsp;|&nbsp; Regular / Large / Family
        </p>
    </div>

    {{-- ===== AVAILABILITY ===== --}}
    <div class="form-card">
        <p class="section-title" style="margin-bottom:12px;">⚙️ Settings</p>
        <div class="toggle-row">
            <div>
                <p style="color:#e2e8f0;font-size:14px;font-weight:600;margin:0;">Available for Order</p>
                <p style="color:#555;font-size:12px;margin:2px 0 0;">Show this item as orderable on the menu</p>
            </div>
            <div style="display:flex;align-items:center;gap:8px;">
                <input type="hidden" name="is_available" value="0">
                <input type="checkbox" name="is_available" value="1" id="is_available"
                       {{ old('is_available', 1) ? 'checked' : '' }}
                       style="width:18px;height:18px;accent-color:#3b82f6;cursor:pointer;">
                <label for="is_available" style="color:#888;font-size:13px;cursor:pointer;">Active</label>
            </div>
        </div>
    </div>

    {{-- Buttons --}}
    <div style="display:flex;gap:10px;flex-wrap:wrap;padding-bottom:20px;">
        <button type="submit" class="btn-primary">Create Product</button>
        <a href="{{ route('dashboard.products.index') }}" class="btn-secondary">Cancel</a>
    </div>

    </form>
</div>

<script>
let variantCount = 0;

function addVariantRow(name='', price='', discount='', available=true) {
    const idx = variantCount++;
    const row = document.createElement('div');
    row.className = 'variant-row';
    row.dataset.idx = idx;

    row.innerHTML = `
        <input type="text"
               name="variant_names[]"
               value="${escHtml(name)}"
               placeholder="e.g. Small / Medium / Large"
               class="variant-input"
               oninput="updateVariantCount()">

        <input type="number"
               name="variant_prices[]"
               value="${escHtml(price)}"
               placeholder="Price"
               step="0.01" min="0"
               class="variant-input"
               oninput="updateBasePrice()">

        <input type="number"
               name="variant_discount_prices[]"
               value="${escHtml(discount)}"
               placeholder="Discount"
               step="0.01" min="0"
               class="variant-input">

        <button type="button"
                class="v-avail-btn v-avail ${!available ? 'off' : ''}"
                title="Toggle availability"
                onclick="toggleVariantAvail(this)">
            ${available
                ? `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:14px;height:14px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>`
                : `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:14px;height:14px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>`
            }
            <input type="hidden" name="variant_available[]" value="${available ? '1' : '0'}">
        </button>

        <button type="button" class="v-del-btn" onclick="removeVariantRow(this)">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:13px;height:13px">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
        </button>
    `;

    document.getElementById('variantRows').appendChild(row);
    updateVariantCount();
    updateBasePrice();
}

function removeVariantRow(btn) {
    btn.closest('.variant-row').remove();
    updateVariantCount();
    updateBasePrice();
}

// function toggleVariantAvail(btn) {
//     const isOn  = !btn.classList.contains('off');
//     const input = btn.querySelector('input[type="hidden"]');

//     if (isOn) {
//         // Turn off
//         btn.classList.add('off');
//         input.value = '0';
//         btn.innerHTML = `
//             <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:14px;height:14px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
//             <input type="hidden" name="variant_available[]" value="0">
//         `;
//     } else {
//         // Turn on
//         btn.classList.remove('off');
//         input.value = '1';
//         btn.innerHTML = `
//             <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:14px;height:14px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
//             <input type="hidden" name="variant_available[]" value="1">
//         `;
//     }
// }
function toggleVariantAvail(btn) {
    const isOn = !btn.classList.contains('off');
    btn.classList.toggle('off', isOn);

    btn.innerHTML = (!isOn
        ? `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:14px;height:14px">
               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
           </svg>`
        : `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:14px;height:14px">
               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
           </svg>`)
        + `<input type="hidden" name="variant_available[]" value="${!isOn ? '1' : '0'}">`;
}

function updateVariantCount() {
    const rows  = document.querySelectorAll('.variant-row');
    const count = rows.length;
    document.getElementById('variantCount').textContent = count + ' variant' + (count !== 1 ? 's' : '');

    const hasvariants = count > 0;
    document.getElementById('basePriceSection').style.display   = hasvariants ? 'none' : 'block';
    document.getElementById('variantPriceNote').style.display   = hasvariants ? 'block' : 'none';
    document.getElementById('pricingSubtext').textContent       = hasvariants
        ? 'Pricing is controlled by your variants below'
        : 'Set a base price, or use variants below for multiple sizes/options';
}

function updateBasePrice() {
    // Auto-set base price to lowest variant price
    const prices = [...document.querySelectorAll('input[name="variant_prices[]"]')]
        .map(i => parseFloat(i.value))
        .filter(v => !isNaN(v) && v > 0);

    const bp = document.getElementById('basePrice');
    if (bp && prices.length > 0) {
        bp.value = Math.min(...prices).toFixed(2);
    }
}

function escHtml(str) {
    return String(str)
        .replace(/&/g,'&amp;')
        .replace(/</g,'&lt;')
        .replace(/>/g,'&gt;')
        .replace(/"/g,'&quot;');
}

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

// Restore old() variants on validation fail
@if(old('variant_names'))
    @foreach(old('variant_names', []) as $i => $vName)
        addVariantRow(
            '{{ addslashes($vName) }}',
            '{{ old("variant_prices." . $i, "") }}',
            '{{ old("variant_discount_prices." . $i, "") }}',
            {{ old("variant_available." . $i, "1") == "1" ? "true" : "false" }}
        );
    @endforeach
@endif
</script>

@endsection