<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\QrCode;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode as QrGenerator;

class QrCodeController extends Controller
{
    public function index()
    {
        $qrCodes = QrCode::with(['branch', 'table'])
            ->latest()
            ->get();

        return view('dashboard.qr-codes.index', compact('qrCodes'));
    }

    // public function create()
    // {
    //     $branches = Branch::orderBy('name', 'asc')->get();
    //     $tables   = Table::with('branch')->get();

    //     return view('dashboard.qr-codes.create', compact('branches', 'tables'));
    // }
    public function create()
    {
        $restaurant = auth()->user()->restaurant;

        if (!$restaurant->canAdd('qr_codes')) {
            return redirect()->route('dashboard.qr-codes.index')
                ->with('error', "You've reached the {$restaurant->limitFor('qr_codes')} QR code limit on your current plan. Please upgrade.");
        }

        $branches = Branch::orderBy('name')->get();
        $tables   = Table::with('branch')->get();
        return view('dashboard.qr-codes.create', compact('branches', 'tables'));
    }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'type'      => 'required|in:restaurant,branch,table',
    //         'branch_id' => 'nullable|exists:branches,id',
    //         'table_id'  => 'nullable|exists:tables,id',
    //     ]);

    //     QrCode::create([
    //         'restaurant_id' => auth()->user()->restaurant_id,
    //         'branch_id'     => $request->branch_id,
    //         'table_id'      => $request->table_id,
    //         'token'         => strtoupper(Str::random(8)),
    //         'type'          => $request->type,
    //         'scan_count'    => 0,
    //     ]);

    //     return redirect()->route('dashboard.qr-codes.index')
    //         ->with('success', 'QR Code generated successfully.');
    // }
    public function store(Request $request)
    {
        $restaurant = auth()->user()->restaurant;

        if (!$restaurant->canAdd('qr_codes')) {
            return redirect()->route('dashboard.qr-codes.index')
                ->with('error', "QR code limit reached ({$restaurant->limitFor('qr_codes')}). Please upgrade your plan.");
        }

        $request->validate([
            'type'      => 'required|in:restaurant,branch,table',
            'branch_id' => 'nullable|exists:branches,id',
            'table_id'  => 'nullable|exists:tables,id',
        ]);

        QrCode::create([
            'restaurant_id' => auth()->user()->restaurant_id,
            'branch_id'     => $request->branch_id,
            'table_id'      => $request->table_id,
            'token'         => strtoupper(\Illuminate\Support\Str::random(8)),
            'type'          => $request->type,
            'scan_count'    => 0,
        ]);

        return redirect()->route('dashboard.qr-codes.index')
            ->with('success', 'QR Code generated successfully.');
    }

    public function destroy(QrCode $qrCode)
    {
        $qrCode->delete();

        return redirect()->route('dashboard.qr-codes.index')
            ->with('success', 'QR Code deleted.');
    }

    // Download QR as PNG
    public function download(QrCode $qrCode)
    {
        $url = url('/m/' . $qrCode->token);

        $image = QrGenerator::format('png')
            ->size(400)
            ->margin(2)
            ->generate($url);

        return response($image, 200, [
            'Content-Type'        => 'image/png',
            'Content-Disposition' => 'attachment; filename="qr-' . $qrCode->token . '.png"',
        ]);
    }

    // Preview QR as SVG inline (for display in browser)
    public function preview(QrCode $qrCode)
    {
        $url = url('/m/' . $qrCode->token);

        $svg = QrGenerator::format('svg')
            ->size(200)
            ->margin(1)
            ->generate($url);

        return response($svg, 200, [
            'Content-Type' => 'image/svg+xml',
        ]);
    }
}
