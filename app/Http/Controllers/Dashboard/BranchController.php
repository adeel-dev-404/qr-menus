<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $restaurant = auth()->user()->restaurant;

        if (!$restaurant->canAdd('branches')) {
            return redirect()->route('dashboard.branches.index')
                ->with('error', "You've reached the {$restaurant->limitFor('branches')} branch limit on your current plan. Please upgrade.");
        }

        return view('dashboard.branches.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $restaurant = auth()->user()->restaurant;

        if (!$restaurant->canAdd('branches')) {
            return redirect()->route('dashboard.branches.index')
                ->with('error', "Branch limit reached ({$restaurant->limitFor('branches')}). Please upgrade your plan.");
        }

        $request->validate([
            'name'    => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone'   => 'nullable|string|max:20',
        ]);

        \App\Models\Branch::create([
            'restaurant_id' => $restaurant->id,
            'name'          => $request->name,
            'address'       => $request->address,
            'phone'         => $request->phone,
        ]);

        return redirect()->route('dashboard.branches.index')
            ->with('success', 'Branch created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
