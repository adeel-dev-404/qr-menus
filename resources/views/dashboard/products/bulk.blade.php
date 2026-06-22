@extends('layouts.dashboard')
@section('page-title', 'Bulk Add Products')
@section('content')

<style>
.form-card { background:#1a1a1a; border:1px solid #222; border-radius:14px; padding:24px; margin-bottom:16px; }
.form-label { display:block; font-size:13px; font-weight:600; color:#aaa; margin-bottom:6px; }
.form-input {
    width:100%; background:#111; border:1px solid #2a2a2a; border-radius:8px;
    padding:10px 12px; color:#e2e8f0; font-size:14px; outline:none;
    transition: border-color 0.15s;
}
.form-input:focus { border-color:#3b82f6; }
.form-input.error { border-color:#ef4444; }
.btn-primary { padding:10px 20px; background:#1d4ed8; color:#fff; border:none; border-radius:8px; font-size:14px; cursor:pointer; font-weight:600; display:inline-flex; align-items:center; gap:6px; }
.btn-primary:hover { background:#1e40af; }
.btn-secondary { padding:10px 20px; background:#1a1a1a; color:#888; border:1px solid #2a2a2a; border-radius:8px; font-size:14px; cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; justify-content:center; }
.btn-secondary:hover { color:#ccc; border-color:#444; }
.btn-danger { padding:8px; background:#2d0a0a; color:#fca5a5; border:1px solid #7f1d1d; border-radius:8px; cursor:pointer; display:inline-flex; align-items:center; justify-content:center; border:none; }
.btn-danger:hover { background:#3d1010; }

.bulk-table { width:100%; border-collapse:collapse; margin-bottom:16px; }
.bulk-table th { padding:12px; text-align:left; color:#555; font-size:11px; text-transform:uppercase; letter-spacing:.05em; font-weight:600; border-bottom:1px solid #222; }
.bulk-table td { padding:12px 8px; border-bottom:1px solid #222; vertical-align:middle; }
.paste-box { background:#111; border:1px dashed #2a2a2a; border-radius:10px; padding:16px; margin-bottom:20px; display:none; }
.limit-badge { background:#1e1e1e; border:1px solid #333; padding:4px 10px; border-radius:99px; font-size:12px; color:#aaa; font-weight:600; }
</style>

<div style="max-width:1000px;">

    @if($remaining < 999)
    <div style="background:#1e3a8a; border:1px solid #3b82f6; border-radius:10px; padding:12px 16px; margin-bottom:20px; display:flex; justify-content:space-between; align-items:center;">
        <p style="color:#93c5fd; font-size:13px; margin:0;">
            📌 Remaining product capacity: <strong>{{ $remaining }} products</strong>. Adding more than this limit will fail validation.
        </p>
        <a href="{{ route('dashboard.subscription.index') }}" style="color:#fff; font-size:12px; font-weight:600; text-decoration:underline;">Upgrade Plan</a>
    </div>
    @endif

    <div class="form-card">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; flex-wrap:wrap; gap:12px;">
            <div>
                <h3 style="font-size:18px; font-weight:700; color:#fff; margin:0;">Bulk Add Products</h3>
                <p style="font-size:13px; color:#666; margin:4px 0 0;">Add multiple items to your menu in one request.</p>
            </div>
            <div style="display:flex; gap:8px;">
                <button type="button" class="btn-secondary" onclick="togglePasteSection()" id="pasteBtn">
                    📋 Quick Paste List
                </button>
            </div>
        </div>

        {{-- Default Category Selection --}}
        <div style="background:#111; border:1px solid #222; border-radius:10px; padding:16px; margin-bottom:20px; display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
            <div style="flex:1; min-width:200px;">
                <label class="form-label" style="margin-bottom:4px;">Set Default Category for All Rows</label>
                <select id="default_category_id" class="form-input" onchange="updateAllCategories()">
                    <option value="">Select Default Category</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div style="font-size:12px; color:#666; max-width:300px; margin-top:20px;">
                💡 Changing this selector will automatically update the category selection for all product rows in the table.
            </div>
        </div>

        {{-- Paste Box --}}
        <div id="pasteBox" class="paste-box">
            <label class="form-label">Paste Product Details (Format: Name | Price | [Discount Price] | [Description] - one per line)</label>
            <textarea id="pasteArea" rows="6" class="form-input" style="font-family:monospace; resize:vertical; margin-bottom:12px;" placeholder="Chicken Burger | 450 | 400 | Spicy crispy chicken burger&#10;Margherita Pizza | 999 | | Double cheese pizza&#10;Club Sandwich | 350 | 300&#10;Fresh Lime | 150"></textarea>
            <div style="display:flex; gap:8px;">
                <button type="button" class="btn-primary" onclick="populateFromPaste()">Populate Rows</button>
                <button type="button" class="btn-secondary" onclick="togglePasteSection()">Cancel</button>
            </div>
        </div>

        <form method="POST" action="{{ route('dashboard.products.bulk.store') }}">
            @csrf

            <table class="bulk-table">
                <thead>
                    <tr>
                        <th style="width:40px;">#</th>
                        <th style="width:25%;">Product Name *</th>
                        <th style="width:20%;">Category *</th>
                        <th style="width:12%;">Price (Rs.) *</th>
                        <th style="width:12%;">Discount (Rs.)</th>
                        <th>Description</th>
                        <th style="width:80px; text-align:center;">Avail.</th>
                        <th style="width:50px;"></th>
                    </tr>
                </thead>
                <tbody id="productRows">
                    {{-- Row elements will be inserted here dynamically --}}
                </tbody>
            </table>

            {{-- Actions --}}
            <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px; margin-top:20px;">
                <button type="button" class="btn-secondary" onclick="addProductRow()" style="border-style:dashed; border-color:#3b82f6; color:#60a5fa;">
                    ➕ Add Row
                </button>
                
                <div style="display:flex; gap:10px;">
                    <button type="submit" class="btn-primary">Save Products</button>
                    <a href="{{ route('dashboard.products.index') }}" class="btn-secondary">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
let rowCount = 0;
const categories = @json($categories);

function togglePasteSection() {
    const box = document.getElementById('pasteBox');
    const btn = document.getElementById('pasteBtn');
    if (box.style.display === 'none' || box.style.display === '') {
        box.style.display = 'block';
        btn.textContent = '❌ Close Paste Box';
    } else {
        box.style.display = 'none';
        btn.textContent = '📋 Quick Paste List';
    }
}

function addProductRow(name = '', categoryId = '', price = '', discountPrice = '', description = '', isAvailable = true) {
    const idx = rowCount++;
    const tbody = document.getElementById('productRows');
    const tr = document.createElement('tr');
    tr.id = `row_${idx}`;
    
    const defaultCatId = document.getElementById('default_category_id').value;
    const selectedCatId = categoryId || defaultCatId;

    let catOptions = '<option value="">Select Category</option>';
    categories.forEach(cat => {
        catOptions += `<option value="${cat.id}" ${cat.id == selectedCatId ? 'selected' : ''}>${escHtml(cat.name)}</option>`;
    });
    
    tr.innerHTML = `
        <td style="color:#555; font-weight:600; font-size:13px;" class="row-num"></td>
        <td>
            <input type="text" name="products[${idx}][name]" value="${escHtml(name)}" required placeholder="e.g. Chicken Burger" class="form-input product-name-input">
        </td>
        <td>
            <select name="products[${idx}][category_id]" required class="form-input product-category-select">
                ${catOptions}
            </select>
        </td>
        <td>
            <input type="number" name="products[${idx}][price]" value="${price}" required min="0" step="0.01" placeholder="0.00" class="form-input product-price-input">
        </td>
        <td>
            <input type="number" name="products[${idx}][discount_price]" value="${discountPrice}" min="0" step="0.01" placeholder="None" class="form-input product-discount-input">
        </td>
        <td>
            <input type="text" name="products[${idx}][description]" value="${escHtml(description)}" placeholder="Short description" class="form-input product-desc-input">
        </td>
        <td style="text-align:center;">
            <input type="hidden" name="products[${idx}][is_available]" value="0">
            <input type="checkbox" name="products[${idx}][is_available]" value="1" ${isAvailable ? 'checked' : ''} style="width:18px; height:18px; accent-color:#3b82f6; cursor:pointer;">
        </td>
        <td style="text-align:center;">
            <button type="button" class="btn-danger" onclick="removeRow(${idx})" title="Remove Row">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </button>
        </td>
    `;
    
    tbody.appendChild(tr);
    updateRowNumbers();
}

function removeRow(idx) {
    const row = document.getElementById(`row_${idx}`);
    if (row) {
        row.remove();
        updateRowNumbers();
    }
}

function updateRowNumbers() {
    const rows = document.querySelectorAll('#productRows tr');
    rows.forEach((row, index) => {
        row.querySelector('.row-num').textContent = index + 1;
    });
    // Add default row if empty
    if (rows.length === 0) {
        addProductRow();
    }
}

function updateAllCategories() {
    const defaultCatId = document.getElementById('default_category_id').value;
    if (!defaultCatId) return;
    document.querySelectorAll('.product-category-select').forEach(select => {
        select.value = defaultCatId;
    });
}

function populateFromPaste() {
    const area = document.getElementById('pasteArea');
    const text = area.value;
    if (!text.trim()) return;
    
    const lines = text.split('\n');
    let added = 0;
    lines.forEach(line => {
        if (!line.trim()) return;
        const parts = line.split('|');
        const name = parts[0] ? parts[0].trim() : '';
        const price = parts[1] ? parts[1].trim() : '';
        const discountPrice = parts[2] ? parts[2].trim() : '';
        const description = parts[3] ? parts[3].trim() : '';
        
        if (name) {
            const firstRowInput = document.querySelector('#productRows tr:first-child input.product-name-input');
            if (firstRowInput && !firstRowInput.value.trim() && added === 0) {
                firstRowInput.value = name;
                const tr = firstRowInput.closest('tr');
                tr.querySelector('input.product-price-input').value = price;
                tr.querySelector('input.product-discount-input').value = discountPrice;
                tr.querySelector('input.product-desc-input').value = description;
            } else {
                addProductRow(name, '', price, discountPrice, description);
            }
            added++;
        }
    });
    
    area.value = '';
    togglePasteSection();
}

function escHtml(str) {
    return String(str)
        .replace(/&/g,'&amp;')
        .replace(/</g,'&lt;')
        .replace(/>/g,'&gt;')
        .replace(/"/g,'&quot;');
}

// Initial default rows
document.addEventListener('DOMContentLoaded', () => {
    for (let i = 0; i < 5; i++) {
        addProductRow();
    }
});
</script>

@endsection
