@extends('layouts.dashboard')
@section('page-title', 'New Deal & Bundle')
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
.btn-primary { padding:11px 22px; background:#1d4ed8; color:#fff; border:none; border-radius:8px; font-size:14px; cursor:pointer; font-weight:600; text-decoration:none; display:inline-block; }
.btn-primary:hover { background:#1e40af; }
.btn-secondary { padding:11px 22px; background:#1a1a1a; color:#888; border:1px solid #2a2a2a; border-radius:8px; font-size:14px; cursor:pointer; text-decoration:none; display:inline-block; }
.btn-secondary:hover { background:#222; color:#aaa; }

.section-title { font-size:15px; font-weight:700; color:#fff; margin:0 0 4px; display:flex; align-items:center; gap:8px; }
.section-sub { font-size:12px; color:#666; margin:0 0 16px; }

/* Item rows styling */
.item-row { background:#111; border:1px solid #222; border-radius:10px; padding:14px; margin-bottom:12px; position:relative; }
.item-grid { display:grid; grid-template-columns: 2fr 2fr 1fr 40px; gap:12px; align-items:end; }
@media(max-width:640px){ .item-grid { grid-template-columns: 1fr; gap:10px; } .item-grid div { width:100%; } }

.add-item-btn {
    display:flex; align-items:center; justify-content:center; gap:8px;
    padding:10px 16px; background:#0f1729; border:1px dashed #1e3a5f;
    border-radius:10px; color:#60a5fa; font-size:13px; font-weight:600;
    cursor:pointer; width:100%; transition:all .15s; margin-top:8px;
}
.add-item-btn:hover { background:#162035; border-color:#3b82f6; }

.del-btn {
    width:38px; height:38px; background:#2d0a0a; border:1px solid #7f1d1d;
    border-radius:8px; color:#fca5a5; cursor:pointer; display:flex;
    align-items:center; justify-content:center;
}
.del-btn:hover { background:#3d1010; }

.price-summary {
    background:#0f1729; border:1px solid #1e3a5f; border-radius:10px;
    padding:14px 16px; display:flex; justify-content:space-between;
    align-items:center; font-size:13px; color:#93c5fd; margin-top:12px;
}
</style>

<div style="max-width:720px;">

    <form method="POST" action="{{ route('dashboard.deals.store') }}" enctype="multipart/form-data" id="dealForm">
    @csrf

    {{-- ===== BASIC INFO ===== --}}
    <div class="form-card">
        <p class="section-title">🎁 Deal Information</p>
        <p class="section-sub">Define the name, description, price, and image of this bundle</p>

        <div style="margin-bottom:16px;">
            <label class="form-label">Deal Name (English) *</label>
            <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. Family Feast Combo"
                   class="form-input {{ $errors->has('name') ? 'error' : '' }}">
            @error('name') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        <div style="margin-bottom:16px;">
            <label class="form-label">Description (English)</label>
            <textarea name="description" rows="3" placeholder="Describe what's in the deal..."
                      class="form-input" style="resize:vertical;">{{ old('description') }}</textarea>
            @error('description') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        <div class="two-col" style="margin-bottom:16px;">
            <div>
                <label class="form-label">Deal Price (Rs.) *</label>
                <input type="number" name="price" value="{{ old('price') }}" placeholder="e.g. 1500" step="any"
                       class="form-input {{ $errors->has('price') ? 'error' : '' }}" id="dealPriceInput">
                @error('price') <p class="form-error">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="form-label">Deal Image</label>
                <input type="file" name="image" class="form-input" accept="image/*">
                <p class="form-hint">JPG, PNG, WebP. Max 2MB.</p>
                @error('image') <p class="form-error">{{ $message }}</p> @enderror
            </div>
        </div>

        <div style="background:#111; border:1px solid #222; border-radius:8px; padding:14px 16px; display:flex; align-items:center; justify-content:space-between;">
            <div>
                <span style="font-weight:600; color:#fff; font-size:14px; display:block;">Available</span>
                <span style="font-size:12px; color:#555;">Toggle to enable/disable this deal on your menu</span>
            </div>
            <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" name="is_available" value="1" {{ old('is_available', 1) ? 'checked' : '' }} style="width:20px; height:20px;">
            </label>
        </div>
    </div>

    {{-- ===== DEAL ITEMS ===== --}}
    <div class="form-card">
        <p class="section-title">🍔 Bundle Products</p>
        <p class="section-sub">Add products and variants that are included in this deal</p>

        @if($errors->has('deal_items'))
            <div style="background:#2d0a0a; border:1px solid #7f1d1d; color:#fca5a5; padding:10px 14px; border-radius:8px; font-size:13px; margin-bottom:12px;">
                {{ $errors->first('deal_items') }}
            </div>
        @endif

        <div id="itemsContainer">
            {{-- Dynamic rows will be inserted here --}}
        </div>

        <button type="button" class="add-item-btn" id="addItemBtn">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:14px;height:14px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Product to Deal
        </button>

        {{-- Dynamic calculation summary --}}
        <div class="price-summary">
            <div>
                Regular Price: <strong id="regularPriceVal">Rs. 0</strong>
            </div>
            <div id="savingsPercentVal" style="font-weight:700; color:#4ade80;">
                Savings: 0%
            </div>
        </div>
    </div>

    <div style="display:flex; justify-content:flex-end; gap:12px; margin-top:24px;">
        <a href="{{ route('dashboard.deals.index') }}" class="btn-secondary">Cancel</a>
        <button type="submit" class="btn-primary">Create Deal</button>
    </div>

    </form>
</div>

<script>
// Pass products data to JS
const products = @json($products);

// Keep track of rows
let rowCount = 0;
const container = document.getElementById('itemsContainer');
const addItemBtn = document.getElementById('addItemBtn');
const dealPriceInput = document.getElementById('dealPriceInput');
const regularPriceVal = document.getElementById('regularPriceVal');
const savingsPercentVal = document.getElementById('savingsPercentVal');

// Add first row on load
window.addEventListener('DOMContentLoaded', () => {
    // If validation failed, Laravel might redirect with old deal_items
    const oldItems = @json(old('deal_items', []));
    if (oldItems && oldItems.length > 0) {
        oldItems.forEach(item => addItemRow(item));
    } else {
        addItemRow();
    }
    calculateTotals();
});

// Event listeners
addItemBtn.addEventListener('click', () => addItemRow());
dealPriceInput.addEventListener('input', calculateTotals);

function addItemRow(oldData = null) {
    const index = rowCount++;
    const row = document.createElement('div');
    row.className = 'item-row';
    row.id = `item-row-${index}`;

    // Populate products select options
    let productOptions = '<option value="">Select Product...</option>';
    products.forEach(p => {
        const selected = (oldData && oldData.product_id == p.id) ? 'selected' : '';
        productOptions += `<option value="${p.id}" ${selected}>${p.name}</option>`;
    });

    row.innerHTML = `
        <div class="item-grid">
            <div>
                <label class="form-label">Product</label>
                <select name="deal_items[${index}][product_id]" class="form-input product-select" data-index="${index}" required>
                    ${productOptions}
                </select>
            </div>
            <div>
                <label class="form-label">Variant (Optional)</label>
                <select name="deal_items[${index}][variant_id]" class="form-input variant-select" data-index="${index}">
                    <option value="">No Variant / Base</option>
                </select>
            </div>
            <div>
                <label class="form-label">Quantity</label>
                <input type="number" name="deal_items[${index}][quantity]" value="${oldData ? oldData.quantity : 1}" min="1" class="form-input quantity-input" data-index="${index}" required>
            </div>
            <div>
                <button type="button" class="del-btn" onclick="removeRow(${index})">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
            </div>
        </div>
    `;

    container.appendChild(row);

    const productSelect = row.querySelector('.product-select');
    const variantSelect = row.querySelector('.variant-select');
    const qtyInput = row.querySelector('.quantity-input');

    productSelect.addEventListener('change', (e) => {
        handleProductChange(e.target.value, variantSelect, oldData ? oldData.variant_id : null);
        calculateTotals();
    });

    qtyInput.addEventListener('input', calculateTotals);
    variantSelect.addEventListener('change', calculateTotals);

    // If we have old data, trigger change immediately
    if (oldData) {
        handleProductChange(oldData.product_id, variantSelect, oldData.variant_id);
    }
}

function removeRow(index) {
    const row = document.getElementById(`item-row-${index}`);
    if (row) {
        row.remove();
        calculateTotals();
    }
}

function handleProductChange(productId, variantSelect, selectedVariantId = null) {
    // Reset variant select
    variantSelect.innerHTML = '<option value="">No Variant / Base</option>';

    if (!productId) return;

    const product = products.find(p => p.id == productId);
    if (!product) return;

    if (product.variants && product.variants.length > 0) {
        product.variants.forEach(v => {
            const selected = (selectedVariantId && selectedVariantId == v.id) ? 'selected' : '';
            const price = v.discount_price ? v.discount_price : v.price;
            variantSelect.innerHTML += `<option value="${v.id}" data-price="${price}" ${selected}>${v.name} (Rs. ${parseFloat(price).toFixed(0)})</option>`;
        });
    } else {
        // base price info
        const basePrice = product.discount_price ? product.discount_price : product.price;
        variantSelect.innerHTML = `<option value="" data-price="${basePrice}">Base Product (Rs. ${parseFloat(basePrice).toFixed(0)})</option>`;
    }
}

function calculateTotals() {
    let totalRegularPrice = 0;

    const rows = container.querySelectorAll('.item-row');
    rows.forEach(row => {
        const productSelect = row.querySelector('.product-select');
        const variantSelect = row.querySelector('.variant-select');
        const qtyInput = row.querySelector('.quantity-input');

        const productId = productSelect.value;
        const qty = parseInt(qtyInput.value) || 0;

        if (productId) {
            const product = products.find(p => p.id == productId);
            if (product) {
                let itemPrice = 0;
                const selectedOption = variantSelect.options[variantSelect.selectedIndex];

                // If variant has data-price attribute, use it
                if (selectedOption && selectedOption.dataset.price) {
                    itemPrice = parseFloat(selectedOption.dataset.price);
                } else {
                    // fall back to base product price
                    itemPrice = parseFloat(product.discount_price || product.price || 0);
                }

                totalRegularPrice += itemPrice * qty;
            }
        }
    });

    regularPriceVal.textContent = `Rs. ${totalRegularPrice.toLocaleString(undefined, {minimumFractionDigits: 0, maximumFractionDigits: 2})}`;

    const dealPrice = parseFloat(dealPriceInput.value) || 0;
    if (totalRegularPrice > 0 && dealPrice > 0) {
        const savings = totalRegularPrice - dealPrice;
        const savingsPct = Math.round((savings / totalRegularPrice) * 100);
        if (savingsPct > 0) {
            savingsPercentVal.textContent = `Savings: ${savingsPct}%`;
            savingsPercentVal.style.color = '#4ade80';
        } else {
            savingsPercentVal.textContent = `Savings: ${savingsPct}%`;
            savingsPercentVal.style.color = '#f87171'; // red if overcharging
        }
    } else {
        savingsPercentVal.textContent = 'Savings: 0%';
        savingsPercentVal.style.color = '#666';
    }
}
</script>

@endsection
