<?php

namespace App\Http\Controllers;

use App\Models\QrCode;
use App\Jobs\LogQrScan;
use App\Models\ScanLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

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

        // Build an encrypted context token so table/branch IDs are never exposed in the URL
        $url = route('menu.show', $qrCode->restaurant->slug);

        $payload = [
            'type' => $qrCode->type,
        ];

        if ($qrCode->type === 'table' && $qrCode->table_id) {
            $payload['t'] = $qrCode->table_id;
            $payload['b'] = $qrCode->branch_id;
        } elseif ($qrCode->type === 'branch' && $qrCode->branch_id) {
            $payload['b'] = $qrCode->branch_id;
        }

        // Only append ctx if there's meaningful context (branch or table QR)
        if ($qrCode->type !== 'restaurant') {
            $url .= '?ctx=' . urlencode(Crypt::encryptString(json_encode($payload)));
        }

        return redirect($url);
    }
}

