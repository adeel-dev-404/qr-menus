<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\WaiterCallOption;
use Illuminate\Http\Request;

class CallOptionController extends Controller
{
    public function index()
    {
        $options = WaiterCallOption::where('restaurant_id', auth()->user()->restaurant_id)
            ->orderBy('sort_order')
            ->get();

        return view('dashboard.call-options.index', compact('options'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'label' => 'required|string|max:50',
            'icon'  => 'required|string|max:10',
        ]);

        $max = WaiterCallOption::where('restaurant_id', auth()->user()->restaurant_id)
            ->max('sort_order') ?? 0;

        WaiterCallOption::create([
            'restaurant_id' => auth()->user()->restaurant_id,
            'label'         => $request->label,
            'icon'          => $request->icon,
            'sort_order'    => $max + 1,
            'is_active'     => true,
        ]);

        return back()->with('success', 'Call option added.');
    }

    public function update(Request $request, WaiterCallOption $option)
    {
        abort_if($option->restaurant_id !== auth()->user()->restaurant_id, 403);

        $option->update([
            'label'     => $request->label     ?? $option->label,
            'icon'      => $request->icon      ?? $option->icon,
            'is_active' => $request->has('is_active') ? (bool)$request->is_active : $option->is_active,
        ]);

        return response()->json(['success' => true]);
    }

    public function destroy(WaiterCallOption $option)
    {
        abort_if($option->restaurant_id !== auth()->user()->restaurant_id, 403);
        $option->delete();
        return back()->with('success', 'Call option removed.');
    }
}
