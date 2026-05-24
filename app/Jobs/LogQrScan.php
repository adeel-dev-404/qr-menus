<?php

namespace App\Jobs;

use App\Models\QrCode;
use App\Models\ScanLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class LogQrScan implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public readonly int    $qrCodeId,
        public readonly int    $restaurantId,
        public readonly string $ipAddress,
        public readonly string $userAgent,
    ) {}

    public function handle(): void
    {
        ScanLog::create([
            'restaurant_id' => $this->restaurantId,
            'qr_code_id'    => $this->qrCodeId,
            'ip_address'    => $this->ipAddress,
            'device'        => $this->userAgent,
            'visited_at'    => now(),
        ]);

        QrCode::withoutGlobalScopes()
            ->where('id', $this->qrCodeId)
            ->increment('scan_count');
    }
}