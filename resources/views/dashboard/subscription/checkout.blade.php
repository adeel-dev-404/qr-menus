@extends('layouts.dashboard')
@section('page-title', 'Complete Payment')
@section('content')

<style>
.form-card { background:#1a1a1a; border:1px solid #222; border-radius:14px; padding:24px; }
.form-label { display:block; font-size:13px; font-weight:600; color:#aaa; margin-bottom:6px; }
.form-input { width:100%; background:#111; border:1px solid #2a2a2a; border-radius:8px; padding:10px 12px; color:#e2e8f0; font-size:14px; outline:none; transition:border-color .15s; }
.form-input:focus { border-color:#7c3aed; }
.form-input.error { border-color:#ef4444; }
.form-error { color:#fca5a5; font-size:12px; margin-top:4px; }
.form-hint  { color:#555; font-size:12px; margin-top:4px; }
.btn-submit { padding:12px 24px; background:#7c3aed; color:#fff; border:none; border-radius:8px; font-size:15px; cursor:pointer; font-weight:700; width:100%; display:flex; align-items:center; justify-content:center; gap:8px; }
.btn-submit:hover { background:#6d28d9; }
.btn-secondary { padding:10px 20px; background:#1a1a1a; color:#888; border:1px solid #2a2a2a; border-radius:8px; font-size:14px; text-decoration:none; display:inline-block; text-align:center; }
.bank-row { display:flex; justify-content:space-between; align-items:center; padding:10px 0; border-bottom:1px solid #1f1f1f; }
.bank-row:last-child { border-bottom:none; }
.step-badge { width:24px; height:24px; background:#7c3aed; border-radius:99px; display:flex; align-items:center; justify-content:center; font-size:12px; font-weight:700; color:#fff; flex-shrink:0; }

.payment-mode-card.active { border-color:#7c3aed !important; background:linear-gradient(135deg, #1e1b4b, #111) !important; }
.payment-mode-card:hover { border-color:#5b21b6 !important; }
</style>

<div style="max-width:580px; display:flex; flex-direction:column; gap:16px;">

    {{-- Plan + Period Summary Banner --}}
    <div style="background:linear-gradient(135deg,#4c1d95,#1e1b4b);border-radius:14px;padding:20px;">
        <p style="font-size:12px;color:#a78bfa;text-transform:uppercase;letter-spacing:.08em;margin:0 0 4px;">Subscribing to</p>
        <h2 style="font-size:22px;font-weight:800;color:#fff;margin:0 0 6px;">{{ $plan->name }} Plan</h2>

        {{-- Period chip --}}
        <span style="display:inline-block;background:#312e81;color:#a5b4fc;font-size:11px;font-weight:700;padding:3px 10px;border-radius:99px;margin-bottom:12px;">
            🗓 {{ $period->billingLabel() }} — {{ $period->duration_days }} days
        </span>

        <div style="display:flex;align-items:baseline;gap:6px;">
            <span style="font-size:32px;font-weight:800;color:#fff;">Rs. {{ number_format($period->price, 0) }}</span>
            <span style="font-size:14px;color:#a78bfa;">/ {{ $period->duration_days }} days</span>
        </div>
        <p style="font-size:12px;color:#7c6fd0;margin:4px 0 0;">
            ≈ Rs. {{ number_format($period->perMonthPrice(), 0) }} per month
        </p>
    </div>

    {{-- Bank Transfer Instructions --}}
    <div class="form-card">
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:16px;">
            <div class="step-badge">1</div>
            <h3 style="font-size:15px;font-weight:700;color:#fff;margin:0;">Select Payment Mode & Transfer</h3>
        </div>

        @if($paymentMethods->isEmpty())
            <div style="background:#451a03;border:1px solid #78350f;color:#fef3c7;padding:16px;border-radius:10px;font-size:14px;">
                ⚠️ No payment methods are currently active. Please contact super admin to set up payment methods.
            </div>
        @else
            <label class="form-label" style="margin-bottom:10px;">Available Payment Modes *</label>
            <div style="display:grid;grid-template-columns:repeat(auto-fill, minmax(160px, 1fr));gap:12px;margin-bottom:20px;" id="payment-modes-grid">
                @foreach($paymentMethods as $index => $method)
                    <div class="payment-mode-card" 
                         data-id="{{ $method->id }}"
                         data-name="{{ $method->name }}"
                         data-title="{{ $method->account_title }}"
                         data-number="{{ $method->account_number }}"
                         data-instructions="{{ $method->instructions ?? 'Transfer the exact amount above and keep your transaction receipt handy.' }}"
                         onclick="selectPaymentMethod({{ $method->id }})"
                         style="background:#111;border:2px solid #2a2a2a;border-radius:10px;padding:16px;cursor:pointer;transition:all 0.2s ease-in-out;text-align:center;">
                        <div style="font-size:24px;margin-bottom:6px;">💳</div>
                        <div style="font-weight:700;color:#fff;font-size:14px;">{{ $method->name }}</div>
                    </div>
                @endforeach
            </div>

            <div style="background:#111;border:1px solid #1f1f1f;border-radius:10px;padding:16px;">
                <div class="bank-row">
                    <span style="font-size:13px;color:#666;">Payment Mode</span>
                    <span id="display-bank-name" style="font-size:13px;font-weight:600;color:#e2e8f0;">—</span>
                </div>
                <div class="bank-row">
                    <span style="font-size:13px;color:#666;">Account Title</span>
                    <span id="display-account-title" style="font-size:13px;font-weight:600;color:#e2e8f0;">—</span>
                </div>
                <div class="bank-row">
                    <span style="font-size:13px;color:#666;">Account Number</span>
                    <span id="display-account-number" style="font-family:monospace;font-size:14px;font-weight:700;color:#a78bfa;">—</span>
                </div>
                <div class="bank-row">
                    <span style="font-size:13px;color:#666;">Plan & Period</span>
                    <span style="font-size:13px;font-weight:600;color:#c4b5fd;">{{ $plan->name }} — {{ $period->billingLabel() }}</span>
                </div>
                <div class="bank-row">
                    <span style="font-size:13px;color:#666;">Amount to Send</span>
                    <span style="font-size:15px;font-weight:800;color:#86efac;">Rs. {{ number_format($period->price, 0) }}</span>
                </div>
            </div>

            <p id="display-instructions" style="font-size:12px;color:#888;margin:12px 0 0;line-height:1.5;">
                —
            </p>
        @endif
    </div>

    {{-- Payment Submission Form --}}
    @if(!$paymentMethods->isEmpty())
        <div class="form-card">
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:16px;">
                <div class="step-badge">2</div>
                <h3 style="font-size:15px;font-weight:700;color:#fff;margin:0;">Submit Payment Proof</h3>
            </div>

            <form method="POST"
                  action="{{ route('dashboard.subscription.submit', [$plan, $period]) }}"
                  enctype="multipart/form-data">
                @csrf

                {{-- Hidden input for selected payment method --}}
                <input type="hidden" name="payment_method_id" id="selected_payment_method_id" value="{{ old('payment_method_id') }}">
                @error('payment_method_id') 
                    <p class="form-error" style="margin-bottom:16px;">{{ $message }}</p> 
                @enderror

                {{-- Transaction Ref --}}
                <div style="margin-bottom:16px;">
                    <label class="form-label">Transaction Reference Number *</label>
                    <input type="text" name="transaction_ref" value="{{ old('transaction_ref') }}"
                           placeholder="e.g. TXN-20240515-001"
                           class="form-input {{ $errors->has('transaction_ref') ? 'error' : '' }}">
                    <p class="form-hint">The receipt/reference number from your bank transfer</p>
                    @error('transaction_ref') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                {{-- Screenshot Upload --}}
                <div style="margin-bottom:24px;">
                    <label class="form-label">Payment Screenshot *</label>
                    <div style="border:2px dashed #2a2a2a;border-radius:10px;padding:24px;text-align:center;cursor:pointer;transition:border-color .2s;"
                         id="dropzone"
                         onclick="document.getElementById('proofInput').click()"
                         ondragover="this.style.borderColor='#7c3aed'"
                         ondragleave="this.style.borderColor='#2a2a2a'">

                        <div id="previewWrap" style="display:none;margin-bottom:12px;">
                            <img id="previewImg" style="max-height:160px;border-radius:8px;margin:0 auto;display:block;border:1px solid #2a2a2a;">
                            <p id="previewName" style="color:#a78bfa;font-size:12px;margin:8px 0 0;"></p>
                        </div>

                        <div id="uploadPlaceholder">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:40px;height:40px;color:#444;margin:0 auto 10px;display:block">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                            </svg>
                            <p style="color:#666;font-size:14px;font-weight:600;margin:0;">Click or drag to upload screenshot</p>
                            <p style="color:#444;font-size:12px;margin:6px 0 0;">JPG, PNG — Max 3MB</p>
                        </div>

                        <input type="file" id="proofInput" name="payment_proof" accept="image/*"
                               style="display:none;" onchange="handleFileSelect(this)">
                    </div>
                    @error('payment_proof') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                {{-- What happens next --}}
                <div style="background:#0f172a;border:1px solid #1e3a5f;border-radius:10px;padding:14px;margin-bottom:20px;">
                    <p style="font-size:12px;color:#64748b;margin:0;line-height:1.6;">
                        🕐 After submitting, our team will verify your payment within <strong style="color:#93c5fd;">24 hours</strong>.
                        Your <strong style="color:#c4b5fd;">{{ $plan->name }} ({{ $period->billingLabel() }})</strong> plan will activate automatically once approved.
                    </p>
                </div>

                <div style="display:flex;flex-direction:column;gap:10px;">
                    <button type="submit" class="btn-submit">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:18px;height:18px">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Submit Payment — Rs. {{ number_format($period->price, 0) }}
                    </button>
                    <a href="{{ route('dashboard.subscription.index') }}" class="btn-secondary">← Back to Plans</a>
                </div>

            </form>
        </div>
    @else
        <div style="display:flex;flex-direction:column;gap:10px;">
            <a href="{{ route('dashboard.subscription.index') }}" class="btn-secondary">← Back to Plans</a>
        </div>
    @endif

</div>

<script>
function handleFileSelect(input) {
    if (input.files && input.files[0]) {
        const file   = input.files[0];
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('previewImg').src  = e.target.result;
            document.getElementById('previewName').textContent = file.name;
            document.getElementById('previewWrap').style.display    = 'block';
            document.getElementById('uploadPlaceholder').style.display = 'none';
            document.getElementById('dropzone').style.borderColor = '#7c3aed';
        };
        reader.readAsDataURL(file);
    }
}

function selectPaymentMethod(id) {
    document.querySelectorAll('.payment-mode-card').forEach(card => {
        card.classList.remove('active');
    });

    const selectedCard = document.querySelector(`.payment-mode-card[data-id="${id}"]`);
    if (selectedCard) {
        selectedCard.classList.add('active');

        const name = selectedCard.getAttribute('data-name');
        const title = selectedCard.getAttribute('data-title');
        const number = selectedCard.getAttribute('data-number');
        const instructions = selectedCard.getAttribute('data-instructions');

        document.getElementById('display-bank-name').textContent = name;
        document.getElementById('display-account-title').textContent = title;
        document.getElementById('display-account-number').textContent = number;
        document.getElementById('display-instructions').textContent = instructions;

        document.getElementById('selected_payment_method_id').value = id;
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const oldId = "{{ old('payment_method_id') }}";
    if (oldId) {
        selectPaymentMethod(oldId);
    } else {
        const firstCard = document.querySelector('.payment-mode-card');
        if (firstCard) {
            const id = firstCard.getAttribute('data-id');
            selectPaymentMethod(id);
        }
    }
});
</script>

@endsection