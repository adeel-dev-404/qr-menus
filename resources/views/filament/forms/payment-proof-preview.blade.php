@php
    $proof = $getRecord()?->payment_proof;
@endphp

@if($proof)
    <div style="text-align: center;">
        <img src="{{ Storage::disk('public')->url($proof) }}"
             alt="Payment Proof"
             style="max-width: 100%; max-height: 400px; border-radius: 12px; border: 1px solid #1e1e1e; cursor: zoom-in;"
             onclick="window.open(this.src, '_blank')">
        <p style="font-size: 12px; color: #666; margin-top: 8px;">
            Click image to view full size
        </p>
    </div>
@else
    <div style="text-align: center; padding: 32px; color: #555;">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 48px; height: 48px; margin: 0 auto 12px;">
            <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5a1.5 1.5 0 0 0 1.5-1.5V5.25a1.5 1.5 0 0 0-1.5-1.5H3.75a1.5 1.5 0 0 0-1.5 1.5v14.25a1.5 1.5 0 0 0 1.5 1.5Z" />
        </svg>
        <p style="font-size: 13px;">No payment proof uploaded</p>
    </div>
@endif
