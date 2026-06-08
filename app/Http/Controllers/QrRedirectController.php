<?php

namespace App\Http\Controllers;

use App\Models\QrCode;
use App\Jobs\LogQrScan;
use App\Models\ScanLog;
use Illuminate\Http\Request;

class QrRedirectController extends Controller
{
    public function redirect(Request $request, string $token)
    {
        $qrCode = QrCode::with(['restaurant', 'table', 'branch'])
            ->withoutGlobalScopes()
            ->where('token', $token)
            ->firstOrFail();

        // Log the scan
        ScanLog::create([
            'restaurant_id' => $qrCode->restaurant_id,
            'qr_code_id'    => $qrCode->id,
            'ip_address'    => $request->ip(),
            'device'        => $request->userAgent(),
            'visited_at'    => now(),
        ]);

        // Increment scan count
        $qrCode->increment('scan_count');

        // Dispatch to queue — non-blocking, redirect is instant
        LogQrScan::dispatch(
            $qrCode->id,
            $qrCode->restaurant_id,
            $request->ip(),
            $request->userAgent() ?? 'unknown',
        );

        // Build query params based on QR type so the menu knows which table/branch the customer is at
        $params = [];

        if ($qrCode->type === 'table' && $qrCode->table_id) {
            $params['table']  = $qrCode->table_id;
            $params['branch'] = $qrCode->branch_id;
        } elseif ($qrCode->type === 'branch' && $qrCode->branch_id) {
            $params['branch'] = $qrCode->branch_id;
        }

        $url = route('menu.show', $qrCode->restaurant->slug);

        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }

        return redirect($url);
    }
}

