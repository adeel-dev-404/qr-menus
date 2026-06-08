<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\WaiterCall;

class WaiterCallController extends Controller
{
    public function index()
    {
        $restaurantId = auth()->user()->restaurant_id;

        $activeCalls = WaiterCall::with(['table', 'branch'])
            ->forRestaurant($restaurantId)
            ->active()
            ->latest()
            ->get();

        $todayResolved = WaiterCall::forRestaurant($restaurantId)
            ->where('status', 'resolved')
            ->whereDate('created_at', today())
            ->count();

        $stats = [
            'pending'  => WaiterCall::forRestaurant($restaurantId)->where('status', 'pending')->count(),
            'seen'     => WaiterCall::forRestaurant($restaurantId)->where('status', 'seen')->count(),
            'resolved' => $todayResolved,
        ];

        return view('dashboard.waiter-calls.index', compact('activeCalls', 'stats'));
    }

    // Polling endpoint — returns JSON of active calls for live updates
    public function live()
    {
        $restaurantId = auth()->user()->restaurant_id;

        $calls = WaiterCall::with(['table', 'branch'])
            ->forRestaurant($restaurantId)
            ->active()
            ->latest()
            ->get()
            ->map(fn($c) => [
                'id'          => $c->id,
                'call_icon'   => $c->call_icon,
                'call_label'  => $c->call_label,
                'table_label' => $c->table_label ?? 'No table',
                'status'      => $c->status,
                'time_ago'    => $c->time_ago,
                'created_at'  => $c->created_at->toISOString(),
            ]);

        $pendingCount = $calls->where('status', 'pending')->count();

        return response()->json([
            'calls'         => $calls,
            'pending_count' => $pendingCount,
        ]);
    }

    public function count()
    {
        $count = WaiterCall::forRestaurant(auth()->user()->restaurant_id)
            ->where('status', 'pending')
            ->count();

        return response()->json(['count' => $count]);
    }

    public function markSeen(WaiterCall $call)
    {
        abort_if($call->restaurant_id !== auth()->user()->restaurant_id, 403);
        $call->update(['status' => 'seen', 'seen_at' => now()]);
        return response()->json(['success' => true]);
    }

    public function resolve(WaiterCall $call)
    {
        abort_if($call->restaurant_id !== auth()->user()->restaurant_id, 403);
        $call->update(['status' => 'resolved', 'resolved_at' => now()]);
        return response()->json(['success' => true]);
    }
}
