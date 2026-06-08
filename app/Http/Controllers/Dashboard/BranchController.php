<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Table;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index()
    {
        $branches = Branch::withCount(['tables', 'qrCodes'])
            ->latest()
            ->get();

        $restaurant = auth()->user()->restaurant;

        return view('dashboard.branches.index', compact('branches', 'restaurant'));
    }

    public function create()
    {
        $restaurant = auth()->user()->restaurant;

        if (!$restaurant->canAdd('branches')) {
            return redirect()->route('dashboard.branches.index')
                ->with('error', "You've reached the {$restaurant->limitFor('branches')} branch limit on your current plan. Please upgrade.");
        }

        return view('dashboard.branches.create');
    }

    public function store(Request $request)
    {
        $restaurant = auth()->user()->restaurant;

        if (!$restaurant->canAdd('branches')) {
            return redirect()->route('dashboard.branches.index')
                ->with('error', "Branch limit reached ({$restaurant->limitFor('branches')}). Please upgrade your plan.");
        }

        $request->validate([
            'name'    => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
            'phone'   => 'nullable|string|max:20',
        ]);

        Branch::create([
            'restaurant_id' => $restaurant->id,
            'name'          => $request->name,
            'address'       => $request->address,
            'phone'         => $request->phone,
        ]);

        return redirect()->route('dashboard.branches.index')
            ->with('success', 'Branch created successfully.');
    }

    public function edit(Branch $branch)
    {
        // Load with tables sorted by table_number
        $branch->load(['tables' => fn($q) => $q->orderBy('table_number')]);
        return view('dashboard.branches.edit', compact('branch'));
    }

    public function update(Request $request, Branch $branch)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
            'phone'   => 'nullable|string|max:20',
        ]);

        $branch->update([
            'name'    => $request->name,
            'address' => $request->address,
            'phone'   => $request->phone,
        ]);

        return redirect()->route('dashboard.branches.edit', $branch)
            ->with('success', 'Branch updated successfully.');
    }

    public function destroy(Branch $branch)
    {
        // Tables will cascade-delete via DB constraint
        $branch->delete();

        return redirect()->route('dashboard.branches.index')
            ->with('success', 'Branch deleted.');
    }

    // ── Table sub-resource actions (nested under branch) ──

    public function storeTable(Request $request, Branch $branch)
    {
        $request->validate([
            'table_number' => 'required|string|max:20',
            'capacity'     => 'nullable|integer|min:1|max:100',
        ]);

        // Prevent duplicate table number in same branch
        if ($branch->tables()->where('table_number', $request->table_number)->exists()) {
            return back()->with('error', "Table #{$request->table_number} already exists in this branch.");
        }

        $branch->tables()->create([
            'table_number' => $request->table_number,
            'capacity'     => $request->capacity ?? 4,
        ]);

        return back()->with('success', "Table #{$request->table_number} added.");
    }

    public function destroyTable(Branch $branch, Table $table)
    {
        abort_if($table->branch_id !== $branch->id, 403);
        $table->delete();

        return back()->with('success', "Table #{$table->table_number} deleted.");
    }

    public function updateTable(Request $request, Branch $branch, Table $table)
    {
        abort_if($table->branch_id !== $branch->id, 403);

        $request->validate([
            'table_number' => 'required|string|max:20',
            'capacity'     => 'nullable|integer|min:1|max:100',
        ]);

        $table->update([
            'table_number' => $request->table_number,
            'capacity'     => $request->capacity ?? 4,
        ]);

        return back()->with('success', "Table updated.");
    }

    public function bulkStoreTables(Request $request, Branch $branch)
    {
        $request->validate([
            'from'     => 'required|integer|min:1|max:500',
            'to'       => 'required|integer|min:1|max:500|gte:from',
            'capacity' => 'nullable|integer|min:1|max:100',
        ]);

        $capacity = $request->capacity ?? 4;
        $created  = 0;
        $skipped  = 0;

        for ($i = $request->from; $i <= $request->to; $i++) {
            if ($branch->tables()->where('table_number', (string) $i)->exists()) {
                $skipped++;
                continue;
            }
            $branch->tables()->create([
                'table_number' => (string) $i,
                'capacity'     => $capacity,
            ]);
            $created++;
        }

        $msg = "{$created} table(s) added.";
        if ($skipped > 0) $msg .= " {$skipped} skipped (already exist).";

        return back()->with('success', $msg);
    }
}
