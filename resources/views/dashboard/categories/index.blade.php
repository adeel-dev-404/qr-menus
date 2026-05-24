@extends('layouts.dashboard')
@section('page-title', 'Categories')
@section('content')

<style>
.dark-card { background:#1a1a1a; border:1px solid #222; border-radius:14px; overflow:hidden; }
.dark-input { width:100%; background:#111; border:1px solid #2a2a2a; border-radius:8px; padding:10px 12px; color:#e2e8f0; font-size:14px; outline:none; }
.dark-input:focus { border-color:#3b82f6; }
.btn-primary { padding:10px 18px; background:#1d4ed8; color:#fff; border:none; border-radius:8px; font-size:14px; cursor:pointer; white-space:nowrap; text-decoration:none; display:inline-flex; align-items:center; gap:6px; }
.btn-primary:hover { background:#1e40af; }
.btn-danger { padding:6px 12px; background:#2d0a0a; color:#fca5a5; border:1px solid #7f1d1d; border-radius:6px; font-size:12px; cursor:pointer; text-decoration:none; }
.btn-edit { padding:6px 12px; background:#0f1729; color:#60a5fa; border:1px solid #1e3a5f; border-radius:6px; font-size:12px; cursor:pointer; text-decoration:none; }
.badge-active   { background:#052e16; color:#86efac; border:1px solid #166534; padding:3px 10px; border-radius:99px; font-size:11px; font-weight:600; }
.badge-inactive { background:#2d0a0a; color:#fca5a5; border:1px solid #7f1d1d; padding:3px 10px; border-radius:99px; font-size:11px; font-weight:600; }
</style>

<div style="max-width:960px; display:flex; flex-direction:column; gap:16px;">

    {{-- Header --}}
    <div style="display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap;">
        <div>
            <h2 style="font-size:20px; font-weight:700; color:#fff; margin:0;">Categories</h2>
            <p style="font-size:13px; color:#666; margin:4px 0 0;">Manage your menu categories</p>
        </div>
        <a href="{{ route('dashboard.categories.create') }}" class="btn-primary">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            New Category
        </a>
    </div>

    {{-- Table Card --}}
    <div class="dark-card">
        <div style="overflow-x:auto; -webkit-overflow-scrolling:touch;">
            <table style="width:100%; min-width:560px; border-collapse:collapse; font-size:14px;">
                <thead>
                    <tr style="background:#111; border-bottom:1px solid #222;">
                        <th style="padding:12px 16px; text-align:left; color:#555; font-size:11px; text-transform:uppercase; letter-spacing:.05em; font-weight:600;">Image</th>
                        <th style="padding:12px 16px; text-align:left; color:#555; font-size:11px; text-transform:uppercase; letter-spacing:.05em; font-weight:600;">Name</th>
                        <th style="padding:12px 16px; text-align:left; color:#555; font-size:11px; text-transform:uppercase; letter-spacing:.05em; font-weight:600;">Products</th>
                        <th style="padding:12px 16px; text-align:left; color:#555; font-size:11px; text-transform:uppercase; letter-spacing:.05em; font-weight:600;">Status</th>
                        <th style="padding:12px 16px; text-align:left; color:#555; font-size:11px; text-transform:uppercase; letter-spacing:.05em; font-weight:600;">Order</th>
                        <th style="padding:12px 16px; text-align:left; color:#555; font-size:11px; text-transform:uppercase; letter-spacing:.05em; font-weight:600;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                    <tr style="border-bottom:1px solid #1a1a1a;" onmouseover="this.style.background='#1f1f1f'" onmouseout="this.style.background=''">
                        <td style="padding:12px 16px;">
                            <img src="{{ $category->image_url }}" alt=""
                                 style="width:44px;height:44px;border-radius:8px;object-fit:cover;border:1px solid #2a2a2a;">
                        </td>
                        <td style="padding:12px 16px; font-weight:600; color:#e2e8f0;">{{ $category->name }}</td>
                        <td style="padding:12px 16px; color:#888;">{{ $category->products_count ?? $category->products()->count() }}</td>
                        <td style="padding:12px 16px;">
                            <span class="{{ $category->status ? 'badge-active' : 'badge-inactive' }}">
                                {{ $category->status ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td style="padding:12px 16px; color:#666;">{{ $category->sort_order }}</td>
                        <td style="padding:12px 16px;">
                            <div style="display:flex; gap:8px; align-items:center;">
                                <a href="{{ route('dashboard.categories.edit', $category) }}" class="btn-edit">Edit</a>
                                <form method="POST" action="{{ route('dashboard.categories.destroy', $category) }}"
                                      onsubmit="return confirm('Delete {{ $category->name }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-danger">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="padding:48px 16px; text-align:center; color:#555;">
                            <div style="font-size:36px; margin-bottom:12px;">📂</div>
                            <p style="margin:0 0 12px; font-size:14px;">No categories yet.</p>
                            <a href="{{ route('dashboard.categories.create') }}" class="btn-primary" style="display:inline-flex;">+ Create First Category</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection