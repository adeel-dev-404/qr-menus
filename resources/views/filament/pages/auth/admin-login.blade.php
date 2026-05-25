<x-filament-panels::page.simple>

    {{-- Inject custom dark styles --}}
    <style>
        /* Override Filament's default light styles */
        .fi-simple-main {
            background: #0f0f0f !important;
            border: 1px solid #1c1c1c !important;
            border-radius: 16px !important;
            box-shadow: 0 0 0 1px #1c1c1c, 0 24px 48px rgba(0,0,0,.6) !important;
        }

        body, html, .fi-body, .fi-simple-layout {
            background: #080808 !important;
        }

        /* Logo area */
        .fi-logo { margin-bottom: 8px !important; }

        /* Panel heading */
        .fi-simple-header-heading {
            color: #f0f0f0 !important;
            font-size: 20px !important;
            font-weight: 700 !important;
            letter-spacing: -.3px !important;
        }
        .fi-simple-header-subheading {
            color: #606060 !important;
            font-size: 13px !important;
        }

        /* Form labels */
        .fi-fo-field-wrp-label label,
        .fi-fo-field-wrp label {
            color: #888 !important;
            font-size: 12px !important;
            font-weight: 600 !important;
            text-transform: uppercase !important;
            letter-spacing: .05em !important;
        }

        /* Inputs */
        .fi-input,
        .fi-fo-text-input,
        input[type="email"],
        input[type="password"],
        input[type="text"] {
            background: #111 !important;
            border: 1px solid #2a2a2a !important;
            border-radius: 10px !important;
            color: #f0f0f0 !important;
            font-size: 14px !important;
            padding: 11px 14px !important;
            outline: none !important;
            transition: border-color .2s !important;
        }
        .fi-input:focus,
        input[type="email"]:focus,
        input[type="password"]:focus,
        input[type="text"]:focus {
            border-color: #e8502a !important;
            box-shadow: none !important;
            ring: none !important;
        }
        input::placeholder { color: #444 !important; }

        /* Focus ring override */
        .fi-input-wrp:focus-within {
            box-shadow: none !important;
            border-color: #e8502a !important;
        }
        *:focus { outline: none !important; box-shadow: none !important; }
        *:focus-visible { outline: none !important; box-shadow: 0 0 0 2px #e8502a !important; }

        /* Submit button */
        .fi-btn-primary,
        button[type="submit"],
        .fi-btn {
            background: #e8502a !important;
            border: none !important;
            border-radius: 10px !important;
            color: #fff !important;
            font-size: 14px !important;
            font-weight: 700 !important;
            padding: 12px 20px !important;
            cursor: pointer !important;
            transition: background .18s !important;
            width: 100% !important;
        }
        .fi-btn-primary:hover,
        button[type="submit"]:hover {
            background: #c43e1c !important;
        }

        /* Error messages */
        .fi-fo-field-wrp-error-message,
        [data-fi-field-error] {
            color: #fca5a5 !important;
            font-size: 12px !important;
        }

        /* Validation notification */
        .fi-no-notification,
        .fi-notifications { background: #1a1a1a !important; }

        /* Checkbox (remember me) */
        input[type="checkbox"] { accent-color: #e8502a !important; }

        /* Links */
        .fi-link, a {
            color: #e8502a !important;
        }
        .fi-link:hover, a:hover { color: #f97316 !important; }

        /* Dividers */
        hr, .fi-hr { border-color: #1c1c1c !important; }

        /* Card inner padding */
        .fi-simple-main .fi-simple-main-ctn {
            padding: 28px 32px !important;
        }
    </style>

    {{-- Custom header with dark branding --}}
    <div style="text-align:center; margin-bottom: 24px;">

        {{-- Brand --}}
        <div style="display:inline-flex; align-items:center; gap:10px; margin-bottom:16px;">
            <div style="width:40px;height:40px;border-radius:12px;
                        background:linear-gradient(145deg,#e8a23a,#e8502a);
                        display:flex;align-items:center;justify-content:center;font-size:19px;">
                🍽
            </div>
            <span style="font-size:17px;font-weight:700;color:#f0f0f0;letter-spacing:-.3px;">
                QR Menu SaaS
            </span>
        </div>

        {{-- Admin badge --}}
        <div style="display:inline-flex; align-items:center; gap:6px;
                    background:rgba(232,80,42,.1); border:1px solid rgba(232,80,42,.25);
                    border-radius:99px; padding:5px 14px; margin-bottom:0;">
            <div style="width:6px;height:6px;border-radius:99px;background:#e8502a;
                        animation:dot-pulse 2s infinite;"></div>
            <span style="font-size:11px;font-weight:600;color:#e8502a;letter-spacing:.05em;">
                Super Admin Access
            </span>
        </div>

        <style>
        @keyframes dot-pulse {
            0%,100% { opacity:1; transform:scale(1); }
            50% { opacity:.4; transform:scale(1.4); }
        }
        </style>
    </div>

    {{-- Filament renders the actual login form here (Livewire-powered) --}}
    <x-filament-panels::form wire:submit="authenticate">
        {{ $this->form }}

        <x-filament-panels::form.actions
            :actions="$this->getCachedFormActions()"
            :full-width="$this->hasFullWidthFormActions()"
        />
    </x-filament-panels::form>

    {{-- Security note below form --}}
    <div style="display:flex;align-items:center;gap:8px;
                background:#161616;border:1px solid #1c1c1c;
                border-radius:8px;padding:10px 12px;margin-top:16px;">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"
             style="width:14px;height:14px;color:#484848;flex-shrink:0;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
        </svg>
        <p style="font-size:11px;color:#484848;line-height:1.4;">
            Restricted area. Unauthorized access is prohibited and logged.
        </p>
    </div>

    {{-- Back to main site --}}
    <div style="text-align:center;margin-top:16px;">
        <a href="{{ url('/') }}"
           style="font-size:12px;color:#484848;text-decoration:none;
                  display:inline-flex;align-items:center;gap:5px;">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"
                 style="width:12px;height:12px;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to main site
        </a>
    </div>

</x-filament-panels::page.simple>