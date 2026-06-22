<?php

namespace App\Services;

use App\Models\Restaurant;
use App\Models\RestaurantSubscription;
use App\Models\Subscription;
use App\Models\SubscriptionPeriod;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\PaymentApprovedMail;
use App\Mail\PaymentRejectedMail;
use App\Mail\PaymentRequestSubmittedMail;

class SubscriptionService
{
    // ──────────────────────────────────────────────────────────────────────────
    // Free Trial
    // ──────────────────────────────────────────────────────────────────────────

    /**
     * Start a free trial for a restaurant automatically on registration.
     * Called when a new restaurant is created (if any active plan has trial_days > 0).
     * Uses the plan with the highest trial_days as the default trial plan.
     */
    public function startAutoTrial(Restaurant $restaurant): void
    {
        // Already has a trial or a paid subscription – skip
        if ($restaurant->hasUsedTrial() || $restaurant->hasActiveSubscription()) {
            return;
        }

        /** @var Subscription|null $trialPlan */
        $trialPlan = Subscription::where('is_active', true)
            ->where('trial_days', '>', 0)
            ->orderByDesc('trial_days')
            ->first();

        if (!$trialPlan) {
            return;
        }

        $trialEndsAt = now()->addDays($trialPlan->trial_days);

        $restaurant->update([
            'trial_ends_at'        => $trialEndsAt,
            'trial_subscription_id' => $trialPlan->id,
        ]);

        // Create a RestaurantSubscription record to track the trial
        RestaurantSubscription::create([
            'restaurant_id'   => $restaurant->id,
            'subscription_id' => $trialPlan->id,
            'status'          => 'active',
            'is_trial'        => true,
            'starts_at'       => now(),
            'expires_at'      => $trialEndsAt,
            'amount_paid'     => 0,
        ]);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Payment Request
    // ──────────────────────────────────────────────────────────────────────────

    /**
     * Restaurant submits a payment request for a specific billing period.
     */
    public function submitPaymentRequest(
        Restaurant $restaurant,
        Subscription $plan,
        SubscriptionPeriod $period,
        string $transactionRef,
        string $paymentProofPath,
        ?int $paymentMethodId = null
    ): RestaurantSubscription {
        $request = RestaurantSubscription::create([
            'restaurant_id'          => $restaurant->id,
            'subscription_id'        => $plan->id,
            'subscription_period_id' => $period->id,
            'status'                 => 'pending',
            'transaction_ref'        => $transactionRef,
            'payment_proof'          => $paymentProofPath,
            'amount_paid'            => $period->price,
            'is_trial'               => false,
            'payment_method_id'      => $paymentMethodId,
        ]);

        // Notify all super admins about the new payment request
        $admins = User::role('super_admin')->get();
        foreach ($admins as $admin) {
            Mail::to($admin->email)->queue(
                new PaymentRequestSubmittedMail($request->load('restaurant', 'subscription'))
            );
        }

        return $request;
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Approval
    // ──────────────────────────────────────────────────────────────────────────

    /**
     * Super admin approves a payment request.
     * Paid subscription immediately overrides any active trial.
     */
    public function approve(RestaurantSubscription $request): void
    {
        DB::transaction(function () use ($request) {
            $startsAt = now();

            // Use period duration_days if period is linked, otherwise fall back to plan duration
            $durationDays = $request->period?->duration_days
                ?? $request->subscription?->duration
                ?? 30;

            $expiresAt = now()->addDays($durationDays);

            $request->update([
                'status'      => 'active',
                'starts_at'   => $startsAt,
                'expires_at'  => $expiresAt,
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);

            // Paid plan immediately overrides any trial (trial_ends_at is cleared)
            $request->restaurant->update([
                'active_subscription_id'  => $request->id,
                'subscription_id'         => $request->subscription_id,
                'subscription_expires_at' => $expiresAt,
                'trial_ends_at'           => null,  // Trial override
                'trial_subscription_id'   => null,
                'status'                  => 'active',
            ]);

            $ownerEmail = $request->restaurant->users()
                ->whereHas('roles', fn($q) => $q->where('name', 'restaurant_owner'))
                ->value('email');

            if ($ownerEmail) {
                Mail::to($ownerEmail)->queue(
                    new PaymentApprovedMail($request->load('subscription'))
                );
            }
        });
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Rejection
    // ──────────────────────────────────────────────────────────────────────────

    /**
     * Super admin rejects a payment request.
     */
    public function reject(RestaurantSubscription $request, string $reason = ''): void
    {
        $request->update([
            'status' => 'rejected',
            'notes'  => $reason,
        ]);

        $ownerEmail = $request->restaurant->users()
            ->whereHas('roles', fn($q) => $q->where('name', 'restaurant_owner'))
            ->value('email');

        if ($ownerEmail) {
            Mail::to($ownerEmail)->queue(
                new PaymentRejectedMail($request->load('subscription'), $reason)
            );
        }
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Scheduled: Mark Expired
    // ──────────────────────────────────────────────────────────────────────────

    /**
     * Mark expired subscriptions and trials (run via scheduler).
     * Returns count of restaurants updated.
     */
    public function markExpired(): int
    {
        // Mark paid subscriptions expired
        $count = Restaurant::where('subscription_expires_at', '<', now())
            ->whereNotNull('subscription_expires_at')
            ->update([
                'active_subscription_id'  => null,
                'subscription_expires_at' => null,
            ]);

        // Mark trials expired (clear trial fields)
        Restaurant::where('trial_ends_at', '<', now())
            ->whereNotNull('trial_ends_at')
            ->whereNull('active_subscription_id')   // don't clear if they have a paid sub
            ->update([
                'trial_ends_at'        => null,
                'trial_subscription_id' => null,
            ]);

        // Update trial RestaurantSubscription records to expired status
        RestaurantSubscription::where('is_trial', true)
            ->where('status', 'active')
            ->where('expires_at', '<', now())
            ->update(['status' => 'expired']);

        return $count;
    }
}

