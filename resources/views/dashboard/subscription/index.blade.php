@extends('layouts.dashboard')
@section('page-title', 'Subscription & Billing')
@section('content')

<style>
/* ── Base Cards ──────────────────────────────────────────────────── */
.dark-card { background:#1a1a1a; border:1px solid #222; border-radius:14px; overflow:hidden; }
.plan-card { background:#111; border:2px solid #222; border-radius:14px; padding:20px; display:flex; flex-direction:column; transition:border-color .2s,box-shadow .2s; }
.plan-card.popular { border-color:#7c3aed; box-shadow:0 0 0 1px #7c3aed22; }
.plan-card:hover { border-color:#4f46e5; }
.plan-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:14px; }
@media (max-width:768px) { .plan-grid { grid-template-columns:1fr; } }
@media (min-width:480px) and (max-width:768px) { .plan-grid { grid-template-columns:repeat(2,1fr); } }

/* ── Feature Items ───────────────────────────────────────────────── */
.feature-item { display:flex; align-items:center; gap:8px; font-size:13px; color:#888; padding:5px 0; }
.feature-item span.check { color:#4ade80; font-size:14px; }

/* ── Buttons ─────────────────────────────────────────────────────── */
.btn-plan { display:block; text-align:center; padding:10px; border-radius:8px; font-size:13px; font-weight:600; text-decoration:none; border:none; cursor:pointer; width:100%; margin-top:auto; transition:all .2s; }
.btn-plan-current  { background:#052e16; color:#86efac; }
.btn-plan-pending  { background:#422006; color:#fde68a; }
.btn-plan-upgrade  { background:#7c3aed; color:#fff; }
.btn-plan-upgrade:hover { background:#6d28d9; }
.btn-plan-popular  { background:#7c3aed; color:#fff; }
.btn-plan-popular:hover { background:#6d28d9; }
.btn-plan-free     { background:#1a1a1a; color:#555; border:1px solid #2a2a2a; }
.btn-plan-trial    { background:linear-gradient(135deg,#065f46,#047857); color:#6ee7b7; border:1px solid #065f46; }
.btn-plan-trial:hover { background:linear-gradient(135deg,#047857,#059669); }
.btn-plan-disabled { background:#1a1a1a !important; color:#444 !important; border:1px solid #222 !important; cursor:not-allowed !important; pointer-events:none !important; }

/* ── Global Period Switcher ──────────────────────────────────────── */
.global-switcher-container { display:flex; justify-content:center; margin:10px 0 25px; }
.global-switcher { display:inline-flex; background:#0d0d0d; border:1px solid #1f1f1f; border-radius:99px; padding:4px; gap:4px; }
.global-cycle-btn { border:none; background:transparent; color:#666; font-size:13px; font-weight:600; padding:8px 20px; border-radius:99px; cursor:pointer; transition:all .2s ease; display:inline-flex; align-items:center; gap:6px; white-space:nowrap; }
.global-cycle-btn:hover:not(.active) { color:#aaa; }
.global-cycle-btn.active { background:#7c3aed; color:#fff; }
.global-cycle-btn .savings-badge { background:#064e3b; color:#6ee7b7; font-size:9px; font-weight:700; padding:1px 6px; border-radius:99px; }
.global-cycle-btn.active .savings-badge { background:#fff; color:#7c3aed; }

/* ── History Table ───────────────────────────────────────────────── */
.status-badge { padding:3px 10px; border-radius:99px; font-size:11px; font-weight:600; white-space:nowrap; }
.status-active   { background:#052e16; color:#86efac; border:1px solid #166534; }
.status-pending  { background:#422006; color:#fde68a; border:1px solid #92400e; }
.status-rejected { background:#2d0a0a; color:#fca5a5; border:1px solid #7f1d1d; }
.status-expired  { background:#1a1a1a; color:#555;    border:1px solid #2a2a2a; }
</style>

@php
    $pending = $history->where('status', 'pending')->first();
    
    // Find active plans that have periods, to determine what billing cycles exist
    $cyclesAvailable = ['monthly', 'quarterly', 'half_yearly', 'yearly'];
    $maxSavingsByCycle = [];
    foreach ($cyclesAvailable as $cycle) {
        if ($cycle === 'monthly') continue;
        $maxSavings = 0;
        foreach ($plans as $plan) {
            $monthlyP = $plan->periods->firstWhere('billing_cycle', 'monthly');
            $cycleP = $plan->periods->firstWhere('billing_cycle', $cycle);
            if ($monthlyP && $cycleP) {
                $savings = $cycleP->savingsPercent($monthlyP);
                if ($savings > $maxSavings) {
                    $maxSavings = $savings;
                }
            }
        }
        if ($maxSavings > 0) {
            $maxSavingsByCycle[$cycle] = $maxSavings;
        }
    }
@endphp

<div style="max-width:960px; display:flex; flex-direction:column; gap:16px;">

    {{-- Header --}}
    <div>
        <h2 style="font-size:20px;font-weight:700;color:#fff;margin:0;">Subscription & Billing</h2>
        <p style="font-size:13px;color:#666;margin:4px 0 0;">Manage your plan and payment history</p>
    </div>

    {{-- Current Plan / Trial Status Banner --}}
    @php
        $bannerBg     = '#1c1100';
        $bannerBorder = '#92400e';
        $bannerEmoji  = '⚠️';
        $bannerTitle  = 'No Active Subscription';
        $bannerSub    = 'Choose a plan below to unlock full features';
        $bannerTag    = null;

        if ($restaurant->hasActiveSubscription()) {
            $activePlan   = \App\Models\Subscription::find($restaurant->subscription_id);
            $bannerBg     = '#052e16';
            $bannerBorder = '#166534';
            $bannerEmoji  = '✅';
            $bannerTitle  = 'Active — ' . ($activePlan->name ?? 'Subscribed') . ' Plan';
            $bannerSub    = 'Expires ' . $restaurant->subscription_expires_at->format('d M Y') . ' — ' . $restaurant->subscriptionDaysLeft() . ' days left';
            $bannerTag    = ['bg' => '#166534', 'color' => '#86efac', 'text' => $restaurant->subscriptionDaysLeft() . ' days left'];
        } elseif ($restaurant->isOnTrial()) {
            $trialPlan    = \App\Models\Subscription::find($restaurant->trial_subscription_id);
            $bannerBg     = '#0c1a2e';
            $bannerBorder = '#1e3a5f';
            $bannerEmoji  = '🎁';
            $bannerTitle  = 'Free Trial — ' . ($trialPlan->name ?? 'Trial Plan');
            $bannerSub    = 'Trial ends ' . $restaurant->trial_ends_at->format('d M Y') . ' — ' . $restaurant->trialDaysLeft() . ' days left';
            $bannerTag    = ['bg' => '#1e3a5f', 'color' => '#93c5fd', 'text' => $restaurant->trialDaysLeft() . ' trial days left'];
        }
    @endphp

    <div style="background:{{ $bannerBg }}; border:1px solid {{ $bannerBorder }};
                border-radius:14px; padding:20px;
                display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap;">
        <div style="display:flex;align-items:center;gap:14px;">
            <div style="font-size:32px;">{{ $bannerEmoji }}</div>
            <div>
                <p style="font-size:16px;font-weight:700;color:#fff;margin:0;">{{ $bannerTitle }}</p>
                <p style="font-size:13px;color:{{ $restaurant->hasActiveSubscription() ? '#86efac' : ($restaurant->isOnTrial() ? '#93c5fd' : '#f59e0b') }};margin:4px 0 0;">
                    {{ $bannerSub }}
                </p>
            </div>
        </div>
        @if($bannerTag)
            <div style="background:{{ $bannerTag['bg'] }};color:{{ $bannerTag['color'] }};padding:8px 16px;border-radius:99px;font-size:13px;font-weight:700;white-space:nowrap;">
                {{ $bannerTag['text'] }}
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
        <div style="display:flex; flex-direction:column; align-items:center; margin-bottom:20px; gap:8px;">
            <h3 style="font-size:14px;font-weight:700;color:#aaa;margin:0;text-transform:uppercase;letter-spacing:.05em;">Available Plans</h3>
            
            {{-- Centered Global Switcher --}}
            <div class="global-switcher">
                @foreach($cyclesAvailable as $cycle)
                    @php
                        $label = \App\Models\SubscriptionPeriod::CYCLE_LABELS[$cycle] ?? ucfirst($cycle);
                        $savings = $maxSavingsByCycle[$cycle] ?? null;
                    @endphp
                    <button type="button"
                            class="global-cycle-btn {{ $cycle === 'monthly' ? 'active' : '' }}"
                            data-cycle="{{ $cycle }}"
                            onclick="switchGlobalCycle('{{ $cycle }}')">
                        {{ $label }}
                        @if($savings)
                            <span class="savings-badge">-{{ $savings }}%</span>
                        @endif
                    </button>
                @endforeach
            </div>
        </div>

        <div class="plan-grid">
            @foreach($plans as $plan)
            @php
                $isCurrentPlan         = isset($currentPlanId) && $currentPlanId === $plan->id;
                $hasPendingForThisPlan = $history->where('status','pending')->where('subscription_id',$plan->id)->isNotEmpty();
                $features              = $plan->features ?? [];
                $periods               = $plan->periods;   // sorted by sort_order
                $monthlyPeriod         = $periods->firstWhere('billing_cycle', 'monthly');
                $initialPeriod         = $monthlyPeriod ?: $periods->first();
            @endphp

            <div class="plan-card {{ $plan->id == 2 ? 'popular' : '' }}">

                {{-- Popular badge (keep for middle plan) --}}
                @if($plan->id == 2)
                    <span style="display:inline-block;background:#7c3aed;color:#fff;font-size:10px;font-weight:700;letter-spacing:.08em;padding:3px 10px;border-radius:99px;margin-bottom:10px;align-self:flex-start;">
                        ⭐ POPULAR
                    </span>
                @endif

                {{-- Trial badge --}}
                @if($plan->hasTrial())
                    <span style="display:inline-block;background:#065f46;color:#6ee7b7;font-size:10px;font-weight:700;padding:3px 10px;border-radius:99px;margin-bottom:8px;align-self:flex-start;border:1px solid #047857;">
                        🎁 {{ $plan->trial_days }}-Day Free Trial
                    </span>
                @endif

                <h4 style="font-size:18px;font-weight:700;color:#fff;margin:0 0 10px;">{{ $plan->name }}</h4>

                {{-- Price Display --}}
                <div style="margin-bottom:14px;" id="price-{{ $plan->id }}">
                    @if($periods->count() > 0)
                        @if($initialPeriod)
                            @php
                                $initialSavings = $initialPeriod->savingsPercent($monthlyPeriod);
                            @endphp
                            <span style="font-size:28px;font-weight:800;color:#e2e8f0;">Rs. {{ number_format($initialPeriod->price, 0) }}</span>
                            <span style="font-size:13px;color:#555;">/ {{ $initialPeriod->duration_days }} days</span>
                            <div style="font-size:11px;color:#666;margin-top:2px;">
                                ≈ Rs. {{ number_format($initialPeriod->perMonthPrice(), 0) }}/month
                                @if($initialSavings)
                                    <span style="color:#6ee7b7;font-weight:600;margin-left:4px;">(Save {{ $initialSavings }}%)</span>
                                @endif
                            </div>
                        @else
                            <span style="font-size:28px;font-weight:800;color:#e2e8f0;">Free</span>
                        @endif
                    @else
                        <span style="font-size:28px;font-weight:800;color:#e2e8f0;">Free</span>
                    @endif
                </div>

                {{-- Features --}}
                <div style="flex:1;margin-bottom:16px;border-top:1px solid #1f1f1f;padding-top:12px;">
                    <div class="feature-item"><span class="check">✓</span>
                        {{ ($features['products'] ?? 999) >= 999 ? 'Unlimited' : $features['products'] }} Products
                    </div>
                    <div class="feature-item"><span class="check">✓</span>
                        {{ ($features['qr_codes'] ?? 999) >= 999 ? 'Unlimited' : $features['qr_codes'] }} QR Codes
                    </div>
                    <div class="feature-item"><span class="check">✓</span>
                        {{ ($features['branches'] ?? 999) >= 999 ? 'Unlimited' : $features['branches'] }} Branches
                    </div>
                    <div class="feature-item"><span class="check">✓</span> QR Scan Analytics</div>
                    @if(($features['products'] ?? 0) >= 999)
                    <div class="feature-item"><span class="check">✓</span> Priority Support</div>
                    @endif
                </div>

                {{-- Smart CTA Button --}}
                @if($periods->count() === 0)
                    <span class="btn-plan btn-plan-free">Default Plan</span>
                @elseif($isCurrentPlan)
                    <span class="btn-plan btn-plan-current">✅ Current Plan</span>
                @elseif($hasPendingForThisPlan)
                    <span class="btn-plan btn-plan-pending">⏳ Payment Pending</span>
                @elseif($plan->hasTrial() && !$restaurant->hasUsedTrial() && !$restaurant->hasActiveSubscription())
                    {{-- Trial available --}}
                    <div style="display:flex;flex-direction:column;gap:6px;" id="cta-{{ $plan->id }}">
                        <span class="btn-plan btn-plan-trial" style="text-align:center;padding:10px;">
                            🎁 {{ $plan->trial_days }}-Day Free Trial — Auto-Started
                        </span>
                        @if($initialPeriod)
                        <a id="checkout-link-{{ $plan->id }}"
                           href="{{ route('dashboard.subscription.checkout', [$plan, $initialPeriod]) }}"
                           class="btn-plan btn-plan-upgrade"
                           data-plan-name="{{ $plan->name }}"
                           data-is-current="{{ $isCurrentPlan ? 'true' : 'false' }}"
                           data-is-pending="{{ $hasPendingForThisPlan ? 'true' : 'false' }}"
                           data-is-trial-eligible="true"
                           data-has-active-sub="{{ $restaurant->hasActiveSubscription() ? 'true' : 'false' }}">
                            Subscribe to {{ $plan->name }}
                        </a>
                        @endif
                    </div>
                @else
                    @if($initialPeriod)
                    <a id="checkout-link-{{ $plan->id }}"
                       href="{{ route('dashboard.subscription.checkout', [$plan, $initialPeriod]) }}"
                       class="btn-plan {{ $plan->id == 2 ? 'btn-plan-popular' : 'btn-plan-upgrade' }}"
                       data-plan-name="{{ $plan->name }}"
                       data-is-current="{{ $isCurrentPlan ? 'true' : 'false' }}"
                       data-is-pending="{{ $hasPendingForThisPlan ? 'true' : 'false' }}"
                       data-is-trial-eligible="false"
                       data-has-active-sub="{{ $restaurant->hasActiveSubscription() ? 'true' : 'false' }}">
                        {{ $restaurant->hasActiveSubscription() ? 'Switch to '.$plan->name : 'Get '.$plan->name }}
                    </a>
                    @endif
                @endif

            </div>
            @endforeach
        </div>
    </div>

    {{-- Payment History --}}
    @if($history->where('is_trial', false)->count() > 0)
    <div>
        <h3 style="font-size:15px;font-weight:700;color:#aaa;margin:0 0 14px;text-transform:uppercase;letter-spacing:.05em;">Payment History</h3>
        <div class="dark-card">
            <div style="overflow-x:auto;-webkit-overflow-scrolling:touch;">
                <table style="width:100%;min-width:620px;border-collapse:collapse;font-size:14px;">
                    <thead>
                        <tr style="background:#111;border-bottom:1px solid #222;">
                            <th style="padding:12px 16px;text-align:left;color:#555;font-size:11px;text-transform:uppercase;letter-spacing:.05em;">Plan</th>
                            <th style="padding:12px 16px;text-align:left;color:#555;font-size:11px;text-transform:uppercase;letter-spacing:.05em;">Period</th>
                            <th style="padding:12px 16px;text-align:left;color:#555;font-size:11px;text-transform:uppercase;letter-spacing:.05em;">Amount</th>
                            <th style="padding:12px 16px;text-align:left;color:#555;font-size:11px;text-transform:uppercase;letter-spacing:.05em;">Reference</th>
                            <th style="padding:12px 16px;text-align:left;color:#555;font-size:11px;text-transform:uppercase;letter-spacing:.05em;">Status</th>
                            <th style="padding:12px 16px;text-align:left;color:#555;font-size:11px;text-transform:uppercase;letter-spacing:.05em;">Expires</th>
                            <th style="padding:12px 16px;text-align:left;color:#555;font-size:11px;text-transform:uppercase;letter-spacing:.05em;">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($history->where('is_trial', false) as $h)
                        <tr style="border-bottom:1px solid #1a1a1a;" onmouseover="this.style.background='#1f1f1f'" onmouseout="this.style.background=''">
                            <td style="padding:12px 16px;font-weight:600;color:#e2e8f0;">{{ $h->subscription->name }}</td>
                            <td style="padding:12px 16px;">
                                @if($h->period)
                                    <span style="background:#1e1b4b;color:#a5b4fc;padding:2px 8px;border-radius:99px;font-size:11px;font-weight:600;">
                                        {{ $h->period->billingLabel() }}
                                    </span>
                                @else
                                    <span style="color:#444;">—</span>
                                @endif
                            </td>
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

<script>
// planPeriods maps planId => cycle => period details, generated server-side
const planPeriods = {
    @foreach($plans as $plan)
        @if($plan->periods->count() > 0)
        {{ $plan->id }}: {
            @foreach($plan->periods as $p)
                @php
                    $savings = $p->savingsPercent($monthlyPeriod);
                @endphp
                "{{ $p->billing_cycle }}": {
                    id: {{ $p->id }},
                    price: {{ $p->price }},
                    duration_days: {{ $p->duration_days }},
                    label: "{{ $p->billingLabel() }}",
                    per_month: {{ $p->perMonthPrice() }},
                    savings_percent: {{ $savings ?? 'null' }},
                    url: "{{ route('dashboard.subscription.checkout', [$plan, $p]) }}"
                },
            @endforeach
        },
        @endif
    @endforeach
};

function switchGlobalCycle(cycle) {
    // 1. Update active tab/button styling on the global switcher
    document.querySelectorAll('.global-cycle-btn').forEach(btn => {
        if (btn.getAttribute('data-cycle') === cycle) {
            btn.classList.add('active');
        } else {
            btn.classList.remove('active');
        }
    });

    // 2. Loop through each plan and update its price & checkout link
    for (const planId in planPeriods) {
        const periods = planPeriods[planId];
        const period = periods[cycle];

        const priceEl = document.getElementById(`price-${planId}`);
        const linkEl = document.getElementById(`checkout-link-${planId}`);

        if (!priceEl) continue;

        if (period) {
            // Update price display
            const formattedPrice = Number(period.price).toLocaleString('en-US', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
            const formattedPerMonth = Math.round(period.per_month).toLocaleString('en-US', { minimumFractionDigits: 0, maximumFractionDigits: 0 });

            let priceHtml = `
                <span style="font-size:28px;font-weight:800;color:#e2e8f0;">Rs. ${formattedPrice}</span>
                <span style="font-size:13px;color:#555;">/ ${period.duration_days} days</span>
            `;
            
            let subtext = `≈ Rs. ${formattedPerMonth}/month`;
            if (period.savings_percent) {
                subtext += ` <span style="color:#6ee7b7;font-weight:600;margin-left:4px;">(Save ${period.savings_percent}%)</span>`;
            }
            
            priceHtml += `<div style="font-size:11px;color:#666;margin-top:2px;">${subtext}</div>`;
            priceEl.innerHTML = priceHtml;

            // Update CTA Link if it exists
            if (linkEl) {
                linkEl.classList.remove('btn-plan-disabled');
                linkEl.href = period.url;

                const planName = linkEl.getAttribute('data-plan-name');
                const hasActive = linkEl.getAttribute('data-has-active-sub') === 'true';
                const isTrialEligible = linkEl.getAttribute('data-is-trial-eligible') === 'true';

                if (isTrialEligible) {
                    linkEl.innerText = `Subscribe to ${planName}`;
                } else {
                    linkEl.innerText = hasActive ? `Switch to ${planName}` : `Get ${planName}`;
                }
            }
        } else {
            // Period not available for this plan
            priceEl.innerHTML = `
                <span style="font-size:24px;font-weight:800;color:#555;">Not Available</span>
                <div style="font-size:11px;color:#444;margin-top:2px;">Select another period</div>
            `;

            if (linkEl) {
                linkEl.classList.add('btn-plan-disabled');
                linkEl.innerText = 'Not Available';
                linkEl.href = '#';
            }
        }
    }
}
</script>

@endsection