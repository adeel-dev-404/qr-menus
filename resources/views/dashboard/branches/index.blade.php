@extends('layouts.dashboard')
@section('page-title', 'Branches')
@section('content')

<style>
.bc { background:#141414; border:1px solid #1f1f1f; border-radius:14px; overflow:hidden; }
.bc-h { padding:14px 18px; border-bottom:1px solid #1f1f1f; display:flex; align-items:center; justify-content:space-between; }
.btn-primary { padding:9px 18px; background:#3b82f6; color:#fff; border:none; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; gap:6px; transition:background .15s; }
.btn-primary:hover { background:#2563eb; }
.branch-card { background:#141414; border:1px solid #1f1f1f; border-radius:14px; overflow:hidden; transition:border-color .2s, transform .15s; }
.branch-card:hover { border-color:#3b82f6; transform:translateY(-1px); }
.badge-sm { display:inline-block; padding:2px 8px; border-radius:99px; font-size:10px; font-weight:600; }
</style>

<div style="max-width:960px; display:flex; flex-direction:column; gap:18px;">

    {{-- Header --}}
    <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px;">
        <div>
            <h2 style="font-size:20px; font-weight:700; color:#fff; margin:0;">Branches</h2>
            <p style="font-size:13px; color:#666; margin:4px 0 0;">Manage your restaurant locations and their tables</p>
        </div>
        @if(auth()->user()->restaurant->canAdd('branches'))
            <a href="{{ route('dashboard.branches.create') }}" class="btn-primary">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:15px;height:15px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Branch
            </a>
        @else
            <a href="{{ route('dashboard.subscription.index') }}"
               style="padding:9px 18px;background:#92400e;color:#fde68a;border-radius:8px;font-size:13px;text-decoration:none;font-weight:600;">
                ⚡ Upgrade for More Branches
            </a>
        @endif
    </div>

    {{-- Stats row --}}
    <div style="display:grid; grid-template-columns:repeat(2,1fr); gap:12px;">
        <div class="bc" style="padding:16px;">
            <p style="font-size:11px;color:#555;text-transform:uppercase;letter-spacing:.05em;margin:0 0 6px;">Total Branches</p>
            <p style="font-size:28px;font-weight:700;color:#fbbf24;margin:0;">{{ $branches->count() }}</p>
            <p style="font-size:11px;color:#555;margin:4px 0 0;">of {{ $restaurant->limitFor('branches') >= 999 ? '∞' : $restaurant->limitFor('branches') }} on plan</p>
        </div>
        <div class="bc" style="padding:16px;">
            <p style="font-size:11px;color:#555;text-transform:uppercase;letter-spacing:.05em;margin:0 0 6px;">Total Tables</p>
            <p style="font-size:28px;font-weight:700;color:#60a5fa;margin:0;">{{ $branches->sum('tables_count') }}</p>
            <p style="font-size:11px;color:#555;margin:4px 0 0;">across all branches</p>
        </div>
    </div>

    {{-- Branch Cards --}}
    @if($branches->isEmpty())
        <div class="bc" style="padding:56px 20px; text-align:center;">
            <div style="font-size:48px; margin-bottom:12px;">🏢</div>
            <h3 style="font-size:16px;font-weight:700;color:#fff;margin:0 0 8px;">No branches yet</h3>
            <p style="font-size:13px;color:#555;margin:0 0 20px;">Add your first branch to organize tables and QR codes by location.</p>
            @if(auth()->user()->restaurant->canAdd('branches'))
            <a href="{{ route('dashboard.branches.create') }}" class="btn-primary" style="margin:0 auto;">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:15px;height:15px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add First Branch
            </a>
            @endif
        </div>
    @else
        <div style="display:grid; grid-template-columns:1fr; gap:14px;">
            @foreach($branches as $branch)
            <div class="branch-card">
                {{-- Branch header --}}
                <div style="padding:16px 18px; display:flex; align-items:flex-start; justify-content:space-between; gap:12px; flex-wrap:wrap;">
                    <div style="display:flex; align-items:center; gap:14px;">
                        <div style="width:44px;height:44px;background:#0a1f3d;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:20px;flex-shrink:0;">🏢</div>
                        <div>
                            <p style="font-size:15px;font-weight:700;color:#fff;margin:0;">{{ $branch->name }}</p>
                            @if($branch->address)
                            <p style="font-size:12px;color:#666;margin:3px 0 0;">📍 {{ $branch->address }}</p>
                            @endif
                            @if($branch->phone)
                            <p style="font-size:12px;color:#666;margin:2px 0 0;">📞 {{ $branch->phone }}</p>
                            @endif
                        </div>
                    </div>
                    <div style="display:flex; align-items:center; gap:8px; flex-shrink:0;">
                        <span class="badge-sm" style="background:#0a1f3d;color:#60a5fa;">{{ $branch->tables_count }} tables</span>
                        <span class="badge-sm" style="background:#1a0a2e;color:#c084fc;">{{ $branch->qr_codes_count }} QR codes</span>
                        <a href="{{ route('dashboard.branches.edit', $branch) }}"
                           style="padding:6px 12px;background:#1a1a1a;border:1px solid #2a2a2a;border-radius:7px;color:#888;font-size:12px;text-decoration:none;transition:all .15s;"
                           onmouseover="this.style.borderColor='#3b82f6';this.style.color='#60a5fa'"
                           onmouseout="this.style.borderColor='#2a2a2a';this.style.color='#888'">
                            Edit / Manage Tables
                        </a>
                        <form method="POST" action="{{ route('dashboard.branches.destroy', $branch) }}"
                              onsubmit="return confirm('Delete branch {{ addslashes($branch->name) }} and all its tables?')"
                              style="margin:0;">
                            @csrf @method('DELETE')
                            <button type="submit"
                                style="padding:6px 10px;background:#2d0a0a;border:1px solid #3d1010;border-radius:7px;color:#fca5a5;font-size:12px;cursor:pointer;">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Tables preview --}}
                @if($branch->tables_count > 0)
                <div style="padding:0 18px 16px; display:flex; flex-wrap:wrap; gap:6px;">
                    @foreach($branch->tables as $table)
                    <div style="padding:4px 10px; background:#111; border:1px solid #222; border-radius:7px; font-size:12px; color:#aaa;">
                        Table {{ $table->table_number }}
                        @if($table->capacity)
                        <span style="color:#555;"> · {{ $table->capacity }}p</span>
                        @endif
                    </div>
                    @endforeach
                </div>
                @else
                <p style="padding:0 18px 16px; font-size:12px; color:#444;">No tables yet — <a href="{{ route('dashboard.branches.edit', $branch) }}" style="color:#3b82f6;text-decoration:none;">add tables →</a></p>
                @endif
            </div>
            @endforeach
        </div>
    @endif

</div>
@endsection
