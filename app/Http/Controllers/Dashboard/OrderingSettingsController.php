<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class OrderingSettingsController extends Controller
{
    public function index()
    {
        $restaurant = auth()->user()->restaurant;
        return view('dashboard.settings.ordering', compact('restaurant'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'whatsapp_number'  => 'nullable|string|max:20',
            'jazzcash_number'  => 'nullable|string|max:20',
            'easypaisa_number' => 'nullable|string|max:20',
            'account_title'    => 'nullable|string|max:100',
        ]);

        auth()->user()->restaurant->update([
            'ordering_enabled'  => $request->boolean('ordering_enabled'),
            'whatsapp_number'   => $request->whatsapp_number,
            'jazzcash_enabled'  => $request->boolean('jazzcash_enabled'),
            'jazzcash_number'   => $request->jazzcash_number,
            'easypaisa_enabled' => $request->boolean('easypaisa_enabled'),
            'easypaisa_number'  => $request->easypaisa_number,
            'account_title'     => $request->account_title,
        ]);

        return back()->with('success', 'Ordering settings saved.');
    }
}
