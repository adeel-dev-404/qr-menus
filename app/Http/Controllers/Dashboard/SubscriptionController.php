<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\RestaurantSubscription;
use App\Models\Subscription;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    // public function index()
    // {
    //     $restaurant = auth()->user()->restaurant;
    //     $plans      = Subscription::orderBy('price')->get();
    //     $history    = RestaurantSubscription::where('restaurant_id', $restaurant->id)
    //         ->with('subscription')
    //         ->latest()
    //         ->get();

    //     return view('dashboard.subscription.index', compact('restaurant', 'plans', 'history'));
    // }
    public function index()
    {
        $restaurant    = auth()->user()->restaurant;
        $plans         = Subscription::orderBy('price')->get();
        $currentPlanId = $restaurant->currentPlanId();

        $history = RestaurantSubscription::where('restaurant_id', $restaurant->id)
            ->with('subscription')
            ->latest()
            ->get();
        $currentPlanId = $restaurant->currentPlanId();
        return view(
            'dashboard.subscription.index',
            compact('restaurant', 'plans', 'history', 'currentPlanId')
        );
    }

    public function checkout(Subscription $plan)
    {
        $restaurant = auth()->user()->restaurant;
        return view('dashboard.subscription.checkout', compact('restaurant', 'plan'));
    }

    public function submit(Request $request, Subscription $plan)
    {
        $request->validate([
            'transaction_ref' => 'required|string|max:100',
            'payment_proof'   => 'required|image|mimes:jpg,jpeg,png|max:3072',
        ]);

        $path = $request->file('payment_proof')
            ->store('payment-proofs', 'public');

        $service = new SubscriptionService();
        $service->submitPaymentRequest(
            auth()->user()->restaurant,
            $plan,
            $request->transaction_ref,
            $path
        );

        return redirect()->route('dashboard.subscription.index')
            ->with('success', 'Payment submitted! Our team will verify and activate your plan within 24 hours.');
    }
}
