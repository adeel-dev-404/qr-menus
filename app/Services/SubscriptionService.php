<?php

namespace App\Services;

use App\Models\Restaurant;
use App\Models\RestaurantSubscription;
use App\Models\Subscription;
use Illuminate\Support\Facades\DB;

class SubscriptionService
{
    /**
     * Restaurant submits a payment request.
     */
    public function submitPaymentRequest(
        Restaurant $restaurant,
        Subscription $plan,
        string $transactionRef,
        string $paymentProofPath
    ): RestaurantSubscription {
        return RestaurantSubscription::create([
            'restaurant_id'   => $restaurant->id,
            'subscription_id' => $plan->id,
            'status'          => 'pending',
            'transaction_ref' => $transactionRef,
            'payment_proof'   => $paymentProofPath,
            'amount_paid'     => $plan->price,
        ]);
    }

    /**
     * Super admin approves a payment request.
     */
    public function approve(RestaurantSubscription $request): void
    {
        DB::transaction(function () use ($request) {
            $startsAt  = now();
            $expiresAt = now()->addDays($request->subscription->duration);

            $request->update([
                'status'      => 'active',
                'starts_at'   => $startsAt,
                'expires_at'  => $expiresAt,
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);

            $request->restaurant->update([
                'active_subscription_id'  => $request->id,
                'subscription_id'         => $request->subscription_id,
                'subscription_expires_at' => $expiresAt,
                'status'                  => 'active',
            ]);
        });
    }

    /**
     * Super admin rejects a payment request.
     */
    public function reject(RestaurantSubscription $request, string $reason = ''): void
    {
        $request->update([
            'status' => 'rejected',
            'notes'  => $reason,
        ]);
    }

    /**
     * Mark expired subscriptions (run via scheduler).
     */
    public function markExpired(): int
    {
        return Restaurant::where('subscription_expires_at', '<', now())
            ->whereNotNull('subscription_expires_at')
            ->update([
                'active_subscription_id'  => null,
                'subscription_expires_at' => null,
            ]);
    }
}