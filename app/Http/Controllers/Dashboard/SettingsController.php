<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function ordering()
    {
        $restaurant = auth()->user()->restaurant;
        return view('dashboard.settings.ordering', compact('restaurant'));
    }

    public function updateOrdering(Request $request)
    {
        $request->validate([
            'whatsapp_number'         => 'nullable|string|max:20',
            'jazzcash_number'         => 'nullable|string|max:20',
            'easypaisa_number'        => 'nullable|string|max:20',
            'easypaisa_name'          => 'nullable|string|max:100',
            'estimated_wait_minutes'  => 'integer|min:5|max:120',
        ]);

        auth()->user()->restaurant->update([
            'ordering_enabled'        => $request->boolean('ordering_enabled'),
            'whatsapp_number'         => $request->whatsapp_number,
            'jazzcash_number'         => $request->jazzcash_number,
            'easypaisa_number'        => $request->easypaisa_number,
            'easypaisa_name'          => $request->easypaisa_name,
            'estimated_wait_minutes'  => $request->estimated_wait_minutes ?? 30,
        ]);

        return back()->with('success', 'Ordering settings saved.');
    }
}
