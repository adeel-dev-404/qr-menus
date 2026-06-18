<?php

namespace App\Http\Controllers;


use App\Models\Restaurant;
use App\Models\WaiterCall;
use App\Models\WaiterCallOption;
use Illuminate\Http\Request;

class WaiterCallController extends Controller
{
    // Get available call options for a restaurant (JSON for menu page)
    public function options(Request $request, Restaurant $restaurant)
    {
        $lang = $request->get('lang', $restaurant->default_language ?? 'en');
        app()->setLocale($lang);

        $options = WaiterCallOption::with('translations')
            ->where('restaurant_id', $restaurant->id)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $mapped = $options->map(function ($opt) use ($lang) {
            return [
                'id'    => $opt->id,
                'label' => $opt->trans('label', $lang),
                'icon'  => $opt->icon,
            ];
        });

        return response()->json($mapped);
    }

    // Customer submits a waiter call
    public function call(Request $request, Restaurant $restaurant)
    {
        $request->validate([
            'option_id' => 'required|exists:waiter_call_options,id',
            'table_id'  => 'nullable|exists:tables,id',
            'branch_id' => 'nullable|exists:branches,id',
            'lang'      => 'nullable|string',
        ]);

        $lang = $request->get('lang', $restaurant->default_language ?? 'en');
        app()->setLocale($lang);

        $option = WaiterCallOption::find($request->option_id);
        $table  = $request->table_id
            ? \App\Models\Table::with('branch')->find($request->table_id)
            : null;

        // Resolve branch: from table if not provided directly
        $branchId = $table?->branch_id ?? $request->branch_id;

        // Prevent spam — 1 call per table per option per 30 seconds
        $recent = WaiterCall::where('restaurant_id', $restaurant->id)
            ->where('table_id', $request->table_id)
            ->where('waiter_call_option_id', $request->option_id)
            ->where('status', '!=', 'resolved')
            ->where('created_at', '>=', now()->subSeconds(30))
            ->exists();

        if ($recent) {
            return response()->json([
                'success' => false,
                'message' => 'Request already sent. Please wait.'
            ], 429);
        }

        $label = $option->trans('label', $lang);

        WaiterCall::create([
            'restaurant_id'         => $restaurant->id,
            'branch_id'             => $branchId,
            'table_id'              => $request->table_id,
            'waiter_call_option_id' => $option->id,
            'table_label'           => $table ? 'Table ' . $table->table_number : null,
            'call_label'            => $label,
            'call_icon'             => $option->icon,
            'status'                => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => $label . ' request sent!',
        ]);
    }
}
