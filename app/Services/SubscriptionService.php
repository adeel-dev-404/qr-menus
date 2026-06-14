<?php

namespace App\Services;

use App\Models\Restaurant;
use App\Models\RestaurantSubscription;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\PaymentApprovedMail;
use App\Mail\PaymentRejectedMail;
use App\Mail\PaymentRequestSubmittedMail;

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
        $request = RestaurantSubscription::create([
            'restaurant_id'   => $restaurant->id,
            'subscription_id' => $plan->id,
            'status'          => 'pending',
            'transaction_ref' => $transactionRef,
            'payment_proof'   => $paymentProofPath,
            'amount_paid'     => $plan->price,
        ]);

        // Notify all super admins about the new payment request
        $admins = User::role('super_admin')->get();
        foreach ($admins as $admin) {
            Mail::to($admin->email)->queue(new PaymentRequestSubmittedMail($request->load('restaurant', 'subscription')));
        }

        return $request;
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
            $ownerEmail = $request->restaurant->users()
                ->whereHas('roles', fn($q) => $q->where('name', 'restaurant_owner'))
                ->value('email');

            if ($ownerEmail) {
                Mail::to($ownerEmail)->queue(new PaymentApprovedMail($request->load('subscription')));
            }
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

        // Notify the restaurant owner about the rejection
        $ownerEmail = $request->restaurant->users()
            ->whereHas('roles', fn($q) => $q->where('name', 'restaurant_owner'))
            ->value('email');

        if ($ownerEmail) {
            Mail::to($ownerEmail)->queue(new PaymentRejectedMail($request->load('subscription'), $reason));
        }
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
