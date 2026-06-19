@extends('layouts.dashboard')
@section('page-title', 'Deals & Bundles')
@section('content')

<style>
.dark-card { background:#1a1a1a; border:1px solid #222; border-radius:14px; overflow:hidden; }
.btn-primary { padding:9px 16px; background:#1d4ed8; color:#fff; border:none; border-radius:8px; font-size:13px; cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; gap:6px; white-space:nowrap; }
.btn-primary:hover { background:#1e40af; }
.badge-available   { background:#052e16; color:#86efac; border:1px solid #166534; padding:3px 8px; border-radius:99px; font-size:11px; font-weight:600; cursor:pointer; }
.badge-unavailable { background:#2d0a0a; color:#fca5a5; border:1px solid #7f1d1d; padding:3px 8px; border-radius:99px; font-size:11px; font-weight:600; cursor:pointer; }
.item-badge { background:#111; border:1px solid #2a2a2a; color:#aaa; font-size:11px; padding:2px 6px; border-radius:4px; margin-right:4px; margin-bottom:4px; display:inline-block; }
</style>

<div style="max-width:1000px; display:flex; flex-direction:column; gap:16px;">

    {{-- Header --}}
    <div style="display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap;">
        <div>
            <h2 style="font-size:20px; font-weight:700; color:#fff; margin:0;">Deals & Bundles</h2>
            <p style="font-size:13px; color:#666; margin:4px 0 0;">Create and manage combo offers for your restaurant</p>
        </div>
        <div>
            <a href="{{ route('dashboard.deals.create') }}" class="btn-primary">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:15px;height:15px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                New Deal
            </a>
        </div>
    </div>

    {{-- Alert Messages --}}
    @if(session('success'))
        <div style="background:#052e16; border:1px solid #166534; color:#86efac; padding:12px 16px; border-radius:8px; font-size:14px;">
            {{ session('success') }}
        </div>
    @endif

    {{-- Table --}}
    <div class="dark-card">
        <div style="overflow-x:auto;-webkit-overflow-scrolling:touch;">
            <table style="width:100%;min-width:640px;border-collapse:collapse;font-size:14px;">
                <thead>
                    <tr style="background:#111;border-bottom:1px solid #222;">
                        <th style="padding:12px 16px;text-align:left;color:#555;font-size:11px;text-transform:uppercase;letter-spacing:.05em;font-weight:600;width:80px;">Image</th>
                        <th style="padding:12px 16px;text-align:left;color:#555;font-size:11px;text-transform:uppercase;letter-spacing:.05em;font-weight:600;">Deal Name</th>
                        <th style="padding:12px 16px;text-align:left;color:#555;font-size:11px;text-transform:uppercase;letter-spacing:.05em;font-weight:600;">Included Items</th>
                        <th style="padding:12px 16px;text-align:left;color:#555;font-size:11px;text-transform:uppercase;letter-spacing:.05em;font-weight:600;width:100px;">Price</th>
                        <th style="padding:12px 16px;text-align:left;color:#555;font-size:11px;text-transform:uppercase;letter-spacing:.05em;font-weight:600;width:120px;">Available</th>
                        <th style="padding:12px 16px;text-align:left;color:#555;font-size:11px;text-transform:uppercase;letter-spacing:.05em;font-weight:600;width:150px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($deals as $deal)
                    <tr style="border-bottom:1px solid #1a1a1a;" onmouseover="this.style.background='#1f1f1f'" onmouseout="this.style.background=''">
                        <td style="padding:10px 16px;">
                            <img src="{{ $deal->image_url }}" alt=""
                                 style="width:50px;height:50px;border-radius:8px;object-fit:cover;border:1px solid #2a2a2a;">
                        </td>
                        <td style="padding:10px 16px;font-weight:600;color:#e2e8f0;max-width:200px;">
                            <p style="margin:0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $deal->name }}</p>
                            @if($deal->description)
                                <p style="margin:2px 0 0;font-size:12px;color:#555;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;font-weight:400;">{{ $deal->description }}</p>
                            @endif
                        </td>
                        <td style="padding:10px 16px;color:#888;">
                            <div style="display:flex; flex-wrap:wrap; gap:4px;">
                                @foreach($deal->items as $item)
                                    @if($item->product)
                                        <span class="item-badge">
                                            {{ $item->quantity }}x {{ $item->product->name }}
                                            @if($item->variant)
                                                ({{ $item->variant->name }})
                                            @endif
                                        </span>
                                    @endif
                                @endforeach
                            </div>
                        </td>
                        <td style="padding:10px 16px;white-space:nowrap;font-weight:700;color:#86efac;">
                            Rs. {{ number_format($deal->price, 0) }}
                        </td>
                        <td style="padding:10px 16px;">
                            <button onclick="toggleAvailability({{ $deal->id }}, this)"
                                    class="{{ $deal->is_available ? 'badge-available' : 'badge-unavailable' }}"
                                    style="border:none;cursor:pointer;">
                                {{ $deal->is_available ? 'Available' : 'Unavailable' }}
                            </button>
                        </td>
                        <td style="padding:10px 16px;">
                            <div style="display:flex;gap:8px;align-items:center;">
                                <a href="{{ route('dashboard.deals.edit', $deal) }}"
                                   style="padding:6px 12px;background:#0f1729;color:#60a5fa;border:1px solid #1e3a5f;border-radius:6px;font-size:12px;text-decoration:none;">Edit</a>
                                <form method="POST" action="{{ route('dashboard.deals.destroy', $deal) }}"
                                      onsubmit="return confirm('Delete {{ $deal->name }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" style="padding:6px 12px;background:#2d0a0a;color:#fca5a5;border:1px solid #7f1d1d;border-radius:6px;font-size:12px;cursor:pointer;">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="padding:48px 16px;text-align:center;color:#555;">
                            <div style="font-size:36px;margin-bottom:12px;">🎁</div>
                            <p style="margin:0 0 12px;font-size:14px;">No deals created yet.</p>
                            <a href="{{ route('dashboard.deals.create') }}" class="btn-primary" style="display:inline-flex;">+ Create First Deal</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($deals->hasPages())
        <div style="padding:14px 16px;border-top:1px solid #1f1f1f;">
            {{ $deals->links() }}
        </div>
        @endif
    </div>

</div>

<script>
function toggleAvailability(dealId, btn) {
    fetch(`/dashboard/deals/${dealId}/toggle`, {
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
