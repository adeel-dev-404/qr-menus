@extends('layouts.dashboard')
@section('page-title', 'Products')
@section('content')

<style>
.dark-card { background:#1a1a1a; border:1px solid #222; border-radius:14px; overflow:hidden; }
.btn-primary { padding:9px 16px; background:#1d4ed8; color:#fff; border:none; border-radius:8px; font-size:13px; cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; gap:6px; white-space:nowrap; }
.btn-primary:hover { background:#1e40af; }
.filter-input { background:#111; border:1px solid #2a2a2a; border-radius:8px; padding:8px 12px; color:#e2e8f0; font-size:13px; outline:none; }
.filter-input:focus { border-color:#3b82f6; }
.badge-available   { background:#052e16; color:#86efac; border:1px solid #166534; padding:3px 8px; border-radius:99px; font-size:11px; font-weight:600; cursor:pointer; }
.badge-unavailable { background:#2d0a0a; color:#fca5a5; border:1px solid #7f1d1d; padding:3px 8px; border-radius:99px; font-size:11px; font-weight:600; cursor:pointer; }
</style>

@php
    $productLimit = auth()->user()->restaurant->limitFor('products');
    $productCount = auth()->user()->restaurant->countOf('products');
    $usagePct     = $productLimit >= 999 ? 0 : min(100, ($productCount / $productLimit) * 100);
    $atLimit      = !auth()->user()->restaurant->canAdd('products');
@endphp

<div style="max-width:1000px; display:flex; flex-direction:column; gap:16px;">

    {{-- Header --}}
    <div style="display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap;">
        <div>
            <h2 style="font-size:20px; font-weight:700; color:#fff; margin:0;">Products</h2>
            <p style="font-size:13px; color:#666; margin:4px 0 0;">Manage your menu items</p>
        </div>
        @if($atLimit)
            <a href="{{ route('dashboard.subscription.index') }}"
               style="padding:9px 16px;background:#92400e;color:#fde68a;border:none;border-radius:8px;font-size:13px;text-decoration:none;display:inline-flex;align-items:center;gap:6px;">
                ⚡ Upgrade to Add More
            </a>
        @else
            <a href="{{ route('dashboard.products.create') }}" class="btn-primary">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:15px;height:15px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                New Product
            </a>
        @endif
    </div>

    {{-- Usage Bar --}}
    @if($productLimit < 999)
    <div style="background:#1a1a1a;border:1px solid #222;border-radius:10px;padding:12px 16px;">
        <div style="display:flex;justify-content:space-between;margin-bottom:8px;">
            <span style="font-size:13px;color:#888;">Products Used</span>
            <span style="font-size:13px;font-weight:600;color:{{ $atLimit ? '#fca5a5' : '#aaa' }};">
                {{ $productCount }} / {{ $productLimit }}
                @if($atLimit)
                    &mdash; <a href="{{ route('dashboard.subscription.index') }}" style="color:#60a5fa;">Upgrade</a>
                @endif
            </span>
        </div>
        <div style="width:100%;background:#111;border-radius:99px;height:6px;">
            <div style="height:6px;border-radius:99px;width:{{ $usagePct }}%;background:{{ $usagePct >= 100 ? '#ef4444' : ($usagePct >= 80 ? '#facc15' : '#3b82f6') }};transition:width .3s;"></div>
        </div>
    </div>
    @endif

    {{-- Filters --}}
    <form method="GET" style="display:flex;gap:8px;flex-wrap:wrap;align-items:center;">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Search products..." class="filter-input" style="flex:1;min-width:160px;">
        <select name="category" class="filter-input">
            <option value="">All Categories</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
        </select>
        <select name="status" class="filter-input">
            <option value="">All Status</option>
            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Available</option>
            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Unavailable</option>
        </select>
        <button type="submit" style="padding:8px 14px;background:#1a1a1a;color:#aaa;border:1px solid #2a2a2a;border-radius:8px;font-size:13px;cursor:pointer;">Filter</button>
        <a href="{{ route('dashboard.products.index') }}" style="padding:8px 14px;background:#111;color:#666;border:1px solid #1f1f1f;border-radius:8px;font-size:13px;text-decoration:none;">Reset</a>
    </form>

    {{-- Table --}}
    <div class="dark-card">
        <div style="overflow-x:auto;-webkit-overflow-scrolling:touch;">
            <table style="width:100%;min-width:640px;border-collapse:collapse;font-size:14px;">
                <thead>
                    <tr style="background:#111;border-bottom:1px solid #222;">
                        <th style="padding:12px 16px;text-align:left;color:#555;font-size:11px;text-transform:uppercase;letter-spacing:.05em;font-weight:600;">Image</th>
                        <th style="padding:12px 16px;text-align:left;color:#555;font-size:11px;text-transform:uppercase;letter-spacing:.05em;font-weight:600;">Name</th>
                        <th style="padding:12px 16px;text-align:left;color:#555;font-size:11px;text-transform:uppercase;letter-spacing:.05em;font-weight:600;">Category</th>
                        <th style="padding:12px 16px;text-align:left;color:#555;font-size:11px;text-transform:uppercase;letter-spacing:.05em;font-weight:600;">Price</th>
                        <th style="padding:12px 16px;text-align:left;color:#555;font-size:11px;text-transform:uppercase;letter-spacing:.05em;font-weight:600;">Available</th>
                        <th style="padding:12px 16px;text-align:left;color:#555;font-size:11px;text-transform:uppercase;letter-spacing:.05em;font-weight:600;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr style="border-bottom:1px solid #1a1a1a;" onmouseover="this.style.background='#1f1f1f'" onmouseout="this.style.background=''">
                        <td style="padding:10px 16px;">
                            <img src="{{ $product->image_url }}" alt=""
                                 style="width:44px;height:44px;border-radius:8px;object-fit:cover;border:1px solid #2a2a2a;">
                        </td>
                        <td style="padding:10px 16px;font-weight:600;color:#e2e8f0;max-width:180px;">
                            <p style="margin:0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $product->name }}</p>
                            @if($product->description)
                                <p style="margin:2px 0 0;font-size:12px;color:#555;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;font-weight:400;">{{ $product->description }}</p>
                            @endif
                        </td>
                        <td style="padding:10px 16px;color:#888;white-space:nowrap;">{{ $product->category->name }}</td>
                        <td style="padding:10px 16px;white-space:nowrap;">
                            @if($product->discount_price)
                                <p style="margin:0;font-weight:700;color:#86efac;font-size:13px;">Rs. {{ number_format($product->discount_price, 0) }}</p>
                                <p style="margin:0;text-decoration:line-through;color:#555;font-size:11px;">Rs. {{ number_format($product->price, 0) }}</p>
                            @else
                                <span style="color:#e2e8f0;font-weight:600;">Rs. {{ number_format($product->price, 0) }}</span>
                            @endif
                        </td>
                        <td style="padding:10px 16px;">
                            <button onclick="toggleAvailability({{ $product->id }}, this)"
                                    class="{{ $product->is_available ? 'badge-available' : 'badge-unavailable' }}"
                                    style="border:none;cursor:pointer;">
                                {{ $product->is_available ? 'Available' : 'Unavailable' }}
                            </button>
                        </td>
                        <td style="padding:10px 16px;">
                            <div style="display:flex;gap:8px;align-items:center;">
                                <a href="{{ route('dashboard.products.edit', $product) }}"
                                   style="padding:6px 12px;background:#0f1729;color:#60a5fa;border:1px solid #1e3a5f;border-radius:6px;font-size:12px;text-decoration:none;">Edit</a>
                                <form method="POST" action="{{ route('dashboard.products.destroy', $product) }}"
                                      onsubmit="return confirm('Delete {{ $product->name }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" style="padding:6px 12px;background:#2d0a0a;color:#fca5a5;border:1px solid #7f1d1d;border-radius:6px;font-size:12px;cursor:pointer;">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="padding:48px 16px;text-align:center;color:#555;">
                            <div style="font-size:36px;margin-bottom:12px;">🍔</div>
                            <p style="margin:0 0 12px;font-size:14px;">No products yet.</p>
                            <a href="{{ route('dashboard.products.create') }}" class="btn-primary" style="display:inline-flex;">+ Add First Product</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($products->hasPages())
        <div style="padding:14px 16px;border-top:1px solid #1f1f1f;">
            {{ $products->withQueryString()->links() }}
        </div>
        @endif
    </div>

</div>

<script>
function toggleAvailability(productId, btn) {
    fetch(`/dashboard/products/${productId}/toggle`, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.is_available) {
            btn.textContent = 'Available';
            btn.className = 'badge-available';
            btn.style.border = 'none';
            btn.style.cursor = 'pointer';
        } else {
            btn.textContent = 'Unavailable';
            btn.className = 'badge-unavailable';
            btn.style.border = 'none';
            btn.style.cursor = 'pointer';
        }
    });
}
</script>

@endsection