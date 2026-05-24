<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print QR — {{ $qrCode->token }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background: #0f0f0f;
            font-family: system-ui, -apple-system, sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 24px;
            color: #e2e8f0;
        }

        .card {
            background: #1a1a1a;
            border: 1px solid #2a2a2a;
            border-radius: 20px;
            padding: 32px 28px;
            max-width: 360px;
            width: 100%;
            text-align: center;
        }

        .restaurant-name {
            font-size: 13px;
            font-weight: 600;
            color: #7c3aed;
            text-transform: uppercase;
            letter-spacing: .12em;
            margin-bottom: 20px;
        }

        .qr-wrap {
            width: 200px;
            height: 200px;
            background: #fff;
            border-radius: 16px;
            padding: 12px;
            margin: 0 auto 20px;
            box-shadow: 0 8px 32px rgba(124,58,237,.3);
        }

        .qr-wrap img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .token {
            font-family: monospace;
            font-size: 22px;
            font-weight: 800;
            color: #fff;
            letter-spacing: .22em;
            margin-bottom: 6px;
        }

        .scan-url {
            font-size: 11px;
            color: #555;
            word-break: break-all;
            margin-bottom: 14px;
        }

        .type-badge {
            display: inline-block;
            background: #1a0a2e;
            color: #c084fc;
            border: 1px solid #4c1d95;
            padding: 4px 14px;
            border-radius: 99px;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 24px;
        }

        .divider {
            border: none;
            border-top: 1px dashed #2a2a2a;
            margin: 0 0 20px;
        }

        .scan-label {
            font-size: 13px;
            color: #888;
            margin-bottom: 4px;
        }

        .scan-arrow {
            font-size: 28px;
            margin-bottom: 4px;
        }

        .scan-instruction {
            font-size: 12px;
            color: #555;
            line-height: 1.5;
        }

        /* Action buttons (screen only) */
        .actions {
            display: flex;
            gap: 10px;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .btn-print {
            flex: 1;
            padding: 12px;
            background: #7c3aed;
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .btn-print:hover { background: #6d28d9; }

        .btn-back {
            padding: 12px 18px;
            background: #111;
            color: #888;
            border: 1px solid #2a2a2a;
            border-radius: 10px;
            font-size: 14px;
            cursor: pointer;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .btn-back:hover { color: #ccc; border-color: #444; }

        /* Powered by footer */
        .powered-by {
            margin-top: 16px;
            font-size: 11px;
            color: #333;
        }
        .powered-by span { color: #7c3aed; }

        /* ===== PRINT STYLES ===== */
        @media print {
            body {
                background: #fff !important;
                color: #000 !important;
            }

            .card {
                background: #fff !important;
                border: 2px solid #ddd !important;
                box-shadow: none !important;
                max-width: 300px !important;
                padding: 24px !important;
            }

            .restaurant-name { color: #7c3aed !important; }

            .qr-wrap {
                box-shadow: none !important;
                border: 1px solid #eee !important;
            }

            .token { color: #000 !important; }

            .scan-url { color: #888 !important; }

            .type-badge {
                background: #f3e8ff !important;
                color: #7c3aed !important;
                border-color: #ddd8fe !important;
            }

            .divider { border-color: #eee !important; }

            .scan-label   { color: #555 !important; }
            .scan-instruction { color: #777 !important; }

            .actions { display: none !important; }
            .powered-by { color: #bbb !important; }
            .powered-by span { color: #7c3aed !important; }
        }
    </style>
</head>
<body>

    <div class="card" id="printCard">

        {{-- Restaurant Name --}}
        <p class="restaurant-name">{{ auth()->user()->restaurant->name }}</p>

        {{-- QR Code --}}
        <div class="qr-wrap">
            <img src="{{ route('dashboard.qr-codes.preview', $qrCode) }}" alt="QR Code {{ $qrCode->token }}">
        </div>

        {{-- Token --}}
        <p class="token">{{ $qrCode->token }}</p>

        {{-- URL --}}
        <p class="scan-url">{{ url('/m/' . $qrCode->token) }}</p>

        {{-- Type Badge --}}
        <span class="type-badge">
            @if($qrCode->type === 'restaurant') 🏠 Restaurant Menu
            @elseif($qrCode->type === 'branch') 🏢 {{ $qrCode->branch?->name ?? 'Branch' }}
            @else 🪑 Table {{ $qrCode->table?->table_number ?? '' }}
            @endif
        </span>

        <hr class="divider">

        {{-- Scan Instruction --}}
        <p class="scan-arrow">📱</p>
        <p class="scan-label">Scan to view our menu</p>
        <p class="scan-instruction">
            Open your phone camera and point it at the QR code above. No app needed.
        </p>

    </div>

    {{-- Powered By --}}
    <p class="powered-by">Powered by <span>QR Menu SaaS</span></p>

    {{-- Action Buttons (hidden on print) --}}
    <div class="actions" style="max-width:360px; width:100%;">
        <a href="{{ route('dashboard.qr-codes.index') }}" class="btn-back">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:15px;height:15px">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back
        </a>
        <button onclick="window.print()" class="btn-print">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
            </svg>
            Print QR Code
        </button>
    </div>

</body>
</html>