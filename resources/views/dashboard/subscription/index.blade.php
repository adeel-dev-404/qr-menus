@extends('layouts.dashboard')
@section('page-title', 'Subscription & Billing')
@section('content')

<style>
.dark-card { background:#1a1a1a; border:1px solid #222; border-radius:14px; overflow:hidden; }
.plan-card { background:#111; border:2px solid #222; border-radius:14px; padding:20px; display:flex; flex-direction:column; transition:border-color .2s; }
.plan-card.popular { border-color:#7c3aed; }
.plan-card:hover { border-color:#4f46e5; }
.plan-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:14px; }
@media (max-width:768px) { .plan-grid { grid-template-columns:1fr; } }
@media (min-width:480px) and (max-width:768px) { .plan-grid { grid-template-columns:repeat(2,1fr); } }
.feature-item { display:flex; align-items:center; gap:8px; font-size:13px; color:#888; padding:5px 0; }
.feature-item span.check { color:#4ade80; font-size:14px; }
.btn-plan { display:block; text-align:center; padding:10px; border-radius:8px; font-size:13px; font-weight:600; text-decoration:none; border:none; cursor:pointer; width:100%; margin-top:auto; transition:all .2s; }
.btn-plan-current  { background:#052e16; color:#86efac; }
.btn-plan-pending  { background:#422006; color:#fde68a; }
.btn-plan-upgrade  { background:#7c3aed; color:#fff; }
.btn-plan-upgrade:hover { background:#6d28d9; }
.btn-plan-popular  { background:#7c3aed; color:#fff; }
.btn-plan-popular:hover { background:#6d28d9; }
.btn-plan-free     { background:#1a1a1a; color:#555; border:1px solid #2a2a2a; }
.history-row { display:flex; align-items:center; justify-content:space-between; padding:12px 16px; border-bottom:1px solid #1f1f1f; gap:12px; flex-wrap:wrap; }
.history-row:last-child { border-bottom:none; }
.status-badge { padding:3px 10px; border-radius:99px; font-size:11px; font-weight:600; white-space:nowrap; }
.status-active   { background:#052e16; color:#86efac; border:1px solid #166534; }
.status-pending  { background:#422006; color:#fde68a; border:1px solid #92400e; }
.status-rejected { background:#2d0a0a; color:#fca5a5; border:1px solid #7f1d1d; }
.status-expired  { background:#1a1a1a; color:#555;    border:1px solid #2a2a2a; }
</style>

@php
    $pending       = $history->where('status', 'pending')->first();
@endphp

<div style="max-width:960px; display:flex; flex-direction:column; gap:16px;">

    {{-- Header --}}
    <div>
        <h2 style="font-size:20px;font-weight:700;color:#fff;margin:0;">Subscription & Billing</h2>
        <p style="font-size:13px;color:#666;margin:4px 0 0;">Manage your plan and payment history</p>
    </div>

    {{-- Current Plan Status Banner --}}
    <div style="background:{{ $restaurant->hasActiveSubscription() ? '#052e16' : '#1c1100' }};
                border:1px solid {{ $restaurant->hasActiveSubscription() ? '#166534' : '#92400e' }};
                border-radius:14px; padding:20px;
                display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap;">
        <div style="display:flex;align-items:center;gap:14px;">
            <div style="font-size:32px;">{{ $restaurant->hasActiveSubscription() ? '✅' : '⚠️' }}</div>
            <div>
                @if($restaurant->hasActiveSubscription())
                    @php $activePlan = \App\Models\Subscription::find($restaurant->subscription_id); @endphp
                    <p style="font-size:16px;font-weight:700;color:#fff;margin:0;">
                        Active — {{ $activePlan->name ?? 'Subscribed' }} Plan
                    </p>
                    <p style="font-size:13px;color:#86efac;margin:4px 0 0;">
                        Expires {{ $restaurant->subscription_expires_at->format('d M Y') }}
                        &mdash; {{ $restaurant->subscriptionDaysLeft() }} days left
                    </p>
                @else
                    <p style="font-size:16px;font-weight:700;color:#fff;margin:0;">No Active Subscription</p>
                    <p style="font-size:13px;color:#f59e0b;margin:4px 0 0;">Choose a plan below to unlock full features</p>
                @endif
            </div>
        </div>
        @if($restaurant->hasActiveSubscription())
            <div style="background:#166534;color:#86efac;padding:8px 16px;border-radius:99px;font-size:13px;font-weight:700;white-space:nowrap;">
                {{ $restaurant->subscriptionDaysLeft() }} days left
            </div>
        @endif
    </div>

    {{-- Pending Payment Notice --}}
    @if($pending)
    <div style="background:#0f172a;border:1px solid #1e3a5f;border-radius:12px;padding:16px;display:flex;gap:12px;align-items:flex-start;">
        <span style="font-size:22px;flex-shrink:0;">⏳</span>
        <div>
            <p style="font-size:14px;font-weight:600;color:#93c5fd;margin:0 0 4px;">Payment Under Review</p>
            <p style="font-size:13px;color:#64748b;margin:0;">
                Your <strong style="color:#e2e8f0;">{{ $pending->subscription->name }}</strong> plan payment
                (Ref: <span style="font-family:monospace;color:#a78bfa;">{{ $pending->transaction_ref }}</span>)
                is being verified. Usually takes up to 24 hours.
            </p>
        </div>
    </div>
    @endif

    {{-- Plans Grid --}}
    <div>
        <h3 style="font-size:15px;font-weight:700;color:#aaa;margin:0 0 14px;text-transform:uppercase;letter-spacing:.05em;">Available Plans</h3>
        <div class="plan-grid">
            @foreach($plans as $plan)
            @php
                $isCurrentPlan         = isset($currentPlanId) && $currentPlanId === $plan->id;
                $hasPendingForThisPlan = $history->where('status','pending')->where('subscription_id',$plan->id)->isNotEmpty();
                $features = $plan->features ?? [];
            @endphp

            <div class="plan-card {{ $plan->price == 4999 ? 'popular' : '' }}">

                @if($plan->price == 4999)
                    <span style="display:inline-block;background:#7c3aed;color:#fff;font-size:10px;font-weight:700;letter-spacing:.08em;padding:3px 10px;border-radius:99px;margin-bottom:12px;align-self:flex-start;">
                        ⭐ POPULAR
                    </span>
                @endif

                <h4 style="font-size:18px;font-weight:700;color:#fff;margin:0 0 8px;">{{ $plan->name }}</h4>

                <div style="margin-bottom:16px;">
                    @if($plan->price == 0)
                        <span style="font-size:28px;font-weight:800;color:#e2e8f0;">Free</span>
                    @else
                        <span style="font-size:28px;font-weight:800;color:#e2e8f0;">Rs. {{ number_format($plan->price, 0) }}</span>
                        <span style="font-size:13px;color:#555;">/ {{ $plan->duration }} days</span>
                    @endif
                </div>

                <div style="flex:1;margin-bottom:16px;border-top:1px solid #1f1f1f;padding-top:12px;">
                    <div class="feature-item"><span class="check">✓</span> {{ $features['products'] ?? '∞' }} Products</div>
                    <div class="feature-item"><span class="check">✓</span> {{ $features['qr_codes'] ?? '∞' }} QR Codes</div>
                    <div class="feature-item"><span class="check">✓</span> {{ $features['branches'] ?? '∞' }} Branches</div>
                    <div class="feature-item"><span class="check">✓</span> QR Scan Analytics</div>
                    @if($plan->price >= 4999)
                    <div class="feature-item"><span class="check">✓</span> Priority Support</div>
                    @endif
                </div>

                {{-- Smart Button --}}
                @if($plan->price == 0)
                    <span class="btn-plan btn-plan-free">Default Plan</span>
                @elseif($isCurrentPlan)
                    <span class="btn-plan btn-plan-current">✅ Current Plan</span>
                @elseif($hasPendingForThisPlan)
                    <span class="btn-plan btn-plan-pending">⏳ Payment Pending</span>
                @else
                    <a href="{{ route('dashboard.subscription.checkout', $plan) }}"
                       class="btn-plan {{ $plan->price == 4999 ? 'btn-plan-popular' : 'btn-plan-upgrade' }}">
                        {{ $restaurant->hasActiveSubscription() ? 'Upgrade to '.$plan->name : 'Get '.$plan->name }}
                    </a>
                @endif

            </div>
            @endforeach
        </div>
    </div>

    {{-- Payment History --}}
    @if($history->count() > 0)
    <div>
        <h3 style="font-size:15px;font-weight:700;color:#aaa;margin:0 0 14px;text-transform:uppercase;letter-spacing:.05em;">Payment History</h3>
        <div class="dark-card">
            {{-- Desktop table --}}
            <div style="overflow-x:auto;-webkit-overflow-scrolling:touch;">
                <table style="width:100%;min-width:580px;border-collapse:collapse;font-size:14px;">
                    <thead>
                        <tr style="background:#111;border-bottom:1px solid #222;">
                            <th style="padding:12px 16px;text-align:left;color:#555;font-size:11px;text-transform:uppercase;letter-spacing:.05em;">Plan</th>
                            <th style="padding:12px 16px;text-align:left;color:#555;font-size:11px;text-transform:uppercase;letter-spacing:.05em;">Amount</th>
                            <th style="padding:12px 16px;text-align:left;color:#555;font-size:11px;text-transform:uppercase;letter-spacing:.05em;">Reference</th>
                            <th style="padding:12px 16px;text-align:left;color:#555;font-size:11px;text-transform:uppercase;letter-spacing:.05em;">Status</th>
                            <th style="padding:12px 16px;text-align:left;color:#555;font-size:11px;text-transform:uppercase;letter-spacing:.05em;">Expires</th>
                            <th style="padding:12px 16px;text-align:left;color:#555;font-size:11px;text-transform:uppercase;letter-spacing:.05em;">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($history as $h)
                        <tr style="border-bottom:1px solid #1a1a1a;" onmouseover="this.style.background='#1f1f1f'" onmouseout="this.style.background=''">
                            <td style="padding:12px 16px;font-weight:600;color:#e2e8f0;">{{ $h->subscription->name }}</td>
                            <td style="padding:12px 16px;color:#86efac;font-weight:600;">Rs. {{ number_format($h->amount_paid, 0) }}</td>
                            <td style="padding:12px 16px;font-family:monospace;color:#a78bfa;">{{ $h->transaction_ref }}</td>
                            <td style="padding:12px 16px;">
                                <span class="status-badge status-{{ $h->status }}">{{ ucfirst($h->status) }}</span>
                            </td>
                            <td style="padding:12px 16px;color:#666;">{{ $h->expires_at?->format('d M Y') ?? '—' }}</td>
                            <td style="padding:12px 16px;color:#555;">{{ $h->created_at->format('d M Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

</div>
@endsection