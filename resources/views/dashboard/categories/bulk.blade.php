@extends('layouts.dashboard')
@section('page-title', 'Bulk Add Categories')
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
</style>

<div style="max-width:800px;">
    <div class="form-card">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; flex-wrap:wrap; gap:12px;">
            <div>
                <h3 style="font-size:18px; font-weight:700; color:#fff; margin:0;">Bulk Add Categories</h3>
                <p style="font-size:13px; color:#666; margin:4px 0 0;">Add multiple categories to your menu quickly.</p>
            </div>
            <button type="button" class="btn-secondary" onclick="togglePasteSection()" id="pasteBtn">
                📋 Quick Paste List
            </button>
        </div>

        {{-- Paste Box --}}
        <div id="pasteBox" class="paste-box">
            <label class="form-label">Paste Category Names (one per line)</label>
            <textarea id="pasteArea" rows="6" class="form-input" style="font-family:monospace; resize:vertical; margin-bottom:12px;" placeholder="Burgers&#10;Pizzas&#10;Pasta&#10;Desserts"></textarea>
            <div style="display:flex; gap:8px;">
                <button type="button" class="btn-primary" onclick="populateFromPaste()">Populate Rows</button>
                <button type="button" class="btn-secondary" onclick="togglePasteSection()">Cancel</button>
            </div>
        </div>

        <form method="POST" action="{{ route('dashboard.categories.bulk.store') }}">
            @csrf

            <table class="bulk-table">
                <thead>
                    <tr>
                        <th style="width:50px;">#</th>
                        <th>Category Name *</th>
                        <th style="width:120px;">Sort Order</th>
                        <th style="width:100px; text-align:center;">Active</th>
                        <th style="width:60px;"></th>
                    </tr>
                </thead>
                <tbody id="categoryRows">
                    {{-- Row elements will be inserted here dynamically --}}
                </tbody>
            </table>

            {{-- Actions --}}
            <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px; margin-top:20px;">
                <button type="button" class="btn-secondary" onclick="addCategoryRow()" style="border-style:dashed; border-color:#3b82f6; color:#60a5fa;">
                    ➕ Add Row
                </button>
                
                <div style="display:flex; gap:10px;">
                    <button type="submit" class="btn-primary">Save Categories</button>
                    <a href="{{ route('dashboard.categories.index') }}" class="btn-secondary">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
let rowCount = 0;

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

function addCategoryRow(name = '', sortOrder = '0', status = true) {
    const idx = rowCount++;
    const tbody = document.getElementById('categoryRows');
    const tr = document.createElement('tr');
    tr.id = `row_${idx}`;
    
    tr.innerHTML = `
        <td style="color:#555; font-weight:600; font-size:13px;" class="row-num"></td>
        <td>
            <input type="text" name="categories[${idx}][name]" value="${escHtml(name)}" required placeholder="e.g. Burgers" class="form-input">
        </td>
        <td>
            <input type="number" name="categories[${idx}][sort_order]" value="${sortOrder}" min="0" class="form-input">
        </td>
        <td style="text-align:center;">
            <input type="hidden" name="categories[${idx}][status]" value="0">
            <input type="checkbox" name="categories[${idx}][status]" value="1" ${status ? 'checked' : ''} style="width:18px; height:18px; accent-color:#3b82f6; cursor:pointer;">
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
    const rows = document.querySelectorAll('#categoryRows tr');
    rows.forEach((row, index) => {
        row.querySelector('.row-num').textContent = index + 1;
    });
    // Add default row if empty
    if (rows.length === 0) {
        addCategoryRow();
    }
}

function populateFromPaste() {
    const area = document.getElementById('pasteArea');
    const text = area.value;
    if (!text.trim()) return;
    
    const lines = text.split('\n');
    let added = 0;
    lines.forEach(line => {
        const name = line.trim();
        if (name) {
            // If the first row is empty, let's use/overwrite it
            const firstRowInput = document.querySelector('#categoryRows tr:first-child input[type="text"]');
            if (firstRowInput && !firstRowInput.value.trim() && added === 0) {
                firstRowInput.value = name;
            } else {
                addCategoryRow(name);
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
        addCategoryRow();
    }
});
</script>

@endsection
