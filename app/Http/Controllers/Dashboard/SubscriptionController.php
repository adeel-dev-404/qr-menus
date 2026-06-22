<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\RestaurantSubscription;
use App\Models\Subscription;
use App\Models\SubscriptionPeriod;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    /**
     * Show subscription plans page with period toggles and trial status.
     */
    public function index()
    {
        $restaurant    = auth()->user()->restaurant;
        $plans         = Subscription::where('is_active', true)
            ->with('periods')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
        $currentPlanId = $restaurant->currentPlanId();

        $history = RestaurantSubscription::where('restaurant_id', $restaurant->id)
            ->with(['subscription', 'period'])
            ->latest()
            ->get();

        return view(
            'dashboard.subscription.index',
            compact('restaurant', 'plans', 'history', 'currentPlanId')
        );
    }

    /**
     * Show checkout form for a specific plan + billing period.
     */
    public function checkout(Subscription $plan, SubscriptionPeriod $period)
    {
        // Make sure the period belongs to this plan
        abort_if($period->subscription_id != $plan->id, 404);

        $restaurant = auth()->user()->restaurant;
        $paymentMethods = \App\Models\PaymentMethod::where('is_active', true)->get();

        return view('dashboard.subscription.checkout', compact('restaurant', 'plan', 'period', 'paymentMethods'));
    }

    /**
     * Submit payment proof for a specific plan + period.
     */
    public function submit(Request $request, Subscription $plan, SubscriptionPeriod $period)
    {
        abort_if($period->subscription_id != $plan->id, 404);

        $request->validate([
            'payment_method_id' => [
                'required',
                \Illuminate\Validation\Rule::exists('payment_methods', 'id')->where('is_active', true)
            ],
            'transaction_ref' => 'required|string|max:100',
            'payment_proof'   => 'required|image|mimes:jpg,jpeg,png|max:3072',
        ]);

        $path = $request->file('payment_proof')
            ->store('payment-proofs', 'public');

        $service = new SubscriptionService();
        $service->submitPaymentRequest(
            auth()->user()->restaurant,
            $plan,
            $period,
            $request->transaction_ref,
            $path,
            $request->payment_method_id
        );

        return redirect()->route('dashboard.subscription.index')
            ->with('success', 'Payment submitted! Our team will verify and activate your plan within 24 hours.');
    }
}

