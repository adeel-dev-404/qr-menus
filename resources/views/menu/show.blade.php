<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="#0a0a0b">
    <meta name="color-scheme" content="dark">
    <title>{{ $restaurant->name }} — Menu</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Instrument+Serif&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            /* Refined dark palette */
            --bg:           #0a0a0b;
            --bg-elev:      #111113;
            --surface:      #141416;
            --surface2:     #1a1a1d;
            --surface3:     #232327;
            --border:       rgba(255,255,255,0.06);
            --border2:      rgba(255,255,255,0.10);
            --border-hl:    rgba(255,255,255,0.18);

            --text:         #f5f5f7;
            --text2:        #a1a1a6;
            --text3:        #6e6e73;
            --text4:        #48484a;

            /* Warm amber accent */
            --accent:       #f59e42;
            --accent-2:     #ea7c2a;
            --accent-soft:  rgba(245,158,66,0.10);
            --accent-glow:  rgba(245,158,66,0.22);

            --success:      #34d399;
            --danger:       #f87171;

            --radius-sm: 10px;
            --radius:    14px;
            --radius-lg: 20px;
            --radius-xl: 28px;

            --safe-b:    env(safe-area-inset-bottom, 0px);
            --max:       680px;

            --shadow-sm: 0 1px 2px rgba(0,0,0,0.4);
            --shadow:    0 8px 24px -8px rgba(0,0,0,0.5);
            --shadow-lg: 0 24px 48px -16px rgba(0,0,0,0.6);
        }

        html { scroll-behavior: smooth; }

        body {
            background: var(--bg);
            color: var(--text);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'SF Pro Display', 'Segoe UI', sans-serif;
            font-feature-settings: 'cv11','ss01','ss03';
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            min-height: 100vh;
            letter-spacing: -0.01em;
        }

        /* Ambient background glow */
        body::before {
            content: '';
            position: fixed; inset: 0;
            background:
                radial-gradient(900px 500px at 85% -10%, rgba(245,158,66,0.08), transparent 60%),
                radial-gradient(700px 400px at -10% 10%, rgba(234,124,42,0.05), transparent 60%);
            pointer-events: none;
            z-index: 0;
        }

        .page { position: relative; z-index: 1; }

        /* ══════ COVER ══════ */
        .cover {
            position: relative;
            max-width: var(--max);
            margin: 0 auto;
            height: 180px;
            overflow: hidden;
        }
        .cover img {
            width: 100%; height: 100%;
            object-fit: cover;
            display: block;
            transform: scale(1.02);
        }
        .cover::after {
            content: '';
            position: absolute; inset: 0;
            background: linear-gradient(180deg, rgba(10,10,11,0) 35%, var(--bg) 100%);
        }

        /* ══════ HERO HEADER ══════ */
        .hero {
            background: var(--bg);
            position: relative;
            z-index: 2;
        }
        .hero-inner {
            max-width: var(--max);
            margin: 0 auto;
            padding: 18px 18px 14px;
            display: flex; align-items: flex-start; gap: 14px;
        }
        .cover + .hero .hero-inner { margin-top: -32px; }

        .logo-wrap {
            flex-shrink: 0;
            width: 64px; height: 64px;
            border-radius: 18px;
            overflow: hidden;
            border: 1px solid var(--border2);
            background: var(--surface);
            box-shadow: var(--shadow);
        }
        .logo-wrap img { width: 100%; height: 100%; object-fit: cover; }
        .logo-initials {
            width: 100%; height: 100%;
            background: linear-gradient(145deg, var(--accent), var(--accent-2));
            display: flex; align-items: center; justify-content: center;
            font-size: 26px; font-weight: 800; color: #1a0e00;
            letter-spacing: -0.5px;
        }
        .hero-text { flex: 1; min-width: 0; padding-top: 4px; }
        .hero-name {
            font-size: 22px; font-weight: 700;
            color: var(--text); letter-spacing: -0.5px;
            line-height: 1.2;
        }
        .hero-about {
            font-size: 13px; color: var(--text2);
            margin-top: 4px; line-height: 1.45;
            display: -webkit-box;
            -webkit-line-clamp: 2; -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .hero-meta {
            display: flex; align-items: center; gap: 14px;
            margin-top: 10px; flex-wrap: wrap;
        }
        .hero-meta-item {
            display: inline-flex; align-items: center; gap: 5px;
            font-size: 11.5px; color: var(--text3);
            font-weight: 500;
        }
        .hero-meta-item svg { width: 12px; height: 12px; flex-shrink: 0; opacity: 0.8; }
        .hero-meta-item.is-open { color: var(--success); }
        .hero-meta-item.is-closed { color: var(--danger); }

        /* Social */
        .socials {
            display: flex; gap: 7px; flex-wrap: wrap;
            margin-top: 12px;
        }
        .social-chip {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 6px 11px;
            border-radius: 99px;
            font-size: 11.5px; font-weight: 600;
            text-decoration: none;
            background: var(--surface);
            border: 1px solid var(--border);
            color: var(--text2);
            transition: transform .15s ease, border-color .15s ease, background .15s ease;
        }
        .social-chip:hover { transform: translateY(-1px); border-color: var(--border-hl); }
        .social-chip svg { width: 12px; height: 12px; }
        .social-chip.wa { color: #5be584; }
        .social-chip.ig { color: #e879f9; }
        .social-chip.fb { color: #7aa8ff; }

        /* ══════ SEARCH ══════ */
        .search-strip {
            position: sticky; top: 0;
            z-index: 50;
            background: rgba(10,10,11,0.78);
            backdrop-filter: saturate(160%) blur(14px);
            -webkit-backdrop-filter: saturate(160%) blur(14px);
            padding: 12px 18px;
            border-bottom: 1px solid var(--border);
        }
        .search-strip-inner { max-width: var(--max); margin: 0 auto; }
        .search-field {
            display: flex; align-items: center; gap: 10px;
            background: var(--surface);
            border: 1px solid var(--border2);
            border-radius: 12px;
            /* padding: 11px 14px; */
            transition: border-color .2s, box-shadow .2s;
        }
        .search-field:focus-within {
            border-color: var(--accent);
            box-shadow: 0 0 0 4px var(--accent-soft);
        }
        .search-field svg { width: 16px; height: 16px; color: var(--text3); flex-shrink: 0; }
        .search-field input {
            flex: 1; background: none; border: none; outline: none;
            font-size: 14px; color: var(--text);
            font-family: inherit;
        }
        .search-field input::placeholder { color: var(--text3); }
        #clearBtn {
            display: none; background: none; border: none;
            cursor: pointer; color: var(--text3); padding: 0;
        }
        #clearBtn:hover { color: var(--text); }
        #clearBtn svg { width: 14px; height: 14px; display: block; }

        /* ══════ CATEGORY BAR ══════ */
        .cat-strip {
            position: sticky;
            top: 65px;
            z-index: 40;
            background: rgba(10,10,11,0.78);
            backdrop-filter: saturate(160%) blur(14px);
            -webkit-backdrop-filter: saturate(160%) blur(14px);
            border-bottom: 1px solid var(--border);
        }
        .cat-strip-inner {
            max-width: var(--max); margin: 0 auto;
            display: flex; gap: 6px;
            overflow-x: auto; padding: 10px 18px;
            scrollbar-width: none;
        }
        .cat-strip-inner::-webkit-scrollbar { display: none; }
        .cat-chip {
            display: inline-flex; align-items: center; gap: 7px;
            padding: 8px 14px; border-radius: 99px;
            font-size: 13px; font-weight: 500;
            white-space: nowrap; flex-shrink: 0;
            text-decoration: none; color: var(--text2);
            background: var(--surface);
            border: 1px solid var(--border);
            transition: all .18s ease;
        }
        .cat-chip img {
            width: 16px; height: 16px;
            border-radius: 4px; object-fit: cover;
        }
        .cat-chip:hover:not(.active) {
            color: var(--text);
            border-color: var(--border-hl);
        }
        .cat-chip.active {
            background: linear-gradient(145deg, var(--accent), var(--accent-2));
            border-color: transparent;
            color: #1a0e00;
            font-weight: 600;
            box-shadow: 0 4px 16px -4px var(--accent-glow);
        }

        /* ══════ CONTENT ══════ */
        .content { max-width: var(--max); margin: 0 auto; padding: 22px 18px 8px; }

        .section-head {
            display: flex; align-items: center; gap: 12px;
            margin-bottom: 16px;
        }
        .section-head img {
            width: 28px; height: 28px;
            border-radius: 8px; object-fit: cover;
            border: 1px solid var(--border);
        }
        .section-head h2 {
            font-family: 'Instrument Serif', serif;
            font-size: 26px; font-weight: 400; color: var(--text);
            letter-spacing: -0.5px;
            line-height: 1;
        }
        .item-badge {
            margin-left: auto;
            font-size: 11px; color: var(--text3);
            background: var(--surface);
            border: 1px solid var(--border);
            padding: 4px 10px; border-radius: 99px;
            font-weight: 500;
            letter-spacing: 0.02em;
        }

        /* ══════ PRODUCT CARD ══════ */
        .product-list { display: flex; flex-direction: column; gap: 10px; }

        .product-card {
            display: flex;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            overflow: hidden;
            transition: transform .2s ease, border-color .2s ease, background .2s ease;
            position: relative;
        }
        .product-card:hover {
            border-color: var(--border-hl);
            background: var(--surface2);
        }
        .product-card:active { transform: scale(0.995); }
        .product-card.unavailable { opacity: 0.6; }

        /* Thumbnail */
        .thumb {
            width: 120px; min-height: 120px;
            flex-shrink: 0; position: relative; overflow: hidden;
            background: var(--surface2);
        }
        .thumb img {
            width: 100%; height: 100%;
            object-fit: cover; display: block;
        }
        .thumb-na {
            position: absolute; inset: 0;
            background: rgba(10,10,11,0.65);
            backdrop-filter: blur(2px);
            display: flex; align-items: center; justify-content: center;
        }
        .thumb-na-label {
            font-size: 10px; font-weight: 600; color: var(--text);
            background: rgba(10,10,11,0.9);
            border: 1px solid var(--border-hl);
            padding: 4px 10px; border-radius: 99px;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        /* Body */
        .card-body {
            flex: 1; padding: 14px 16px;
            display: flex; flex-direction: column;
            gap: 5px; min-width: 0;
        }
        .card-name {
            font-size: 15px; font-weight: 600; color: var(--text);
            letter-spacing: -0.2px; line-height: 1.3;
        }
        .card-desc {
            font-size: 12.5px; color: var(--text2); line-height: 1.5;
            display: -webkit-box;
            -webkit-line-clamp: 2; -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* ──── Simple price ──── */
        .price-block { display: flex; align-items: baseline; gap: 8px; flex-wrap: wrap; margin-top: auto; padding-top: 8px; }
        .p-main { font-size: 16px; font-weight: 700; color: var(--text); letter-spacing: -0.3px; }
        .p-main.has-discount { color: var(--accent); }
        .p-old { font-size: 12px; color: var(--text3); text-decoration: line-through; }
        .p-off {
            font-size: 10px; font-weight: 700; color: var(--accent);
            background: var(--accent-soft);
            border: 1px solid var(--accent-glow);
            padding: 2px 7px; border-radius: 99px;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        /* ──── Variants ──── */
        .variants-block { margin-top: auto; padding-top: 10px; }
        .v-title {
            font-size: 10px; font-weight: 600; color: var(--text3);
            text-transform: uppercase; letter-spacing: 0.09em;
            margin-bottom: 7px;
        }
        .v-grid { display: flex; flex-wrap: wrap; gap: 6px; }

        .v-pill {
            display: flex; flex-direction: column; align-items: flex-start;
            padding: 7px 11px; border-radius: 10px; min-width: 60px;
            background: var(--surface2);
            border: 1px solid var(--border2);
            transition: all .15s ease;
            cursor: default;
        }
        .v-pill:hover {
            border-color: var(--accent);
            background: var(--accent-soft);
            transform: translateY(-1px);
        }
        .v-pill-name {
            font-size: 10px; font-weight: 500; color: var(--text3);
            white-space: nowrap; line-height: 1.3;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }
        .v-pill-price {
            font-size: 13px; font-weight: 700; color: var(--text);
            white-space: nowrap; line-height: 1.3;
            margin-top: 1px;
        }
        .v-pill-old {
            font-size: 9px; color: var(--text3);
            text-decoration: line-through; line-height: 1.2;
            margin-top: 1px;
        }
        .v-pill.na { opacity: 0.4; pointer-events: none; }
        .v-pill.na .v-pill-price {
            font-size: 10px; color: var(--text3);
            font-weight: 500;
        }

        /* ══════ EMPTY STATES ══════ */
        .empty {
            text-align: center;
            padding: 72px 20px;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            margin: 8px 0;
        }
        .empty-icon {
            font-size: 36px;
            margin-bottom: 14px;
            opacity: 0.7;
        }
        .empty h3 {
            font-family: 'Instrument Serif', serif;
            font-size: 22px; font-weight: 400; color: var(--text);
            margin-bottom: 6px;
            letter-spacing: -0.3px;
        }
        .empty p { font-size: 13px; color: var(--text3); }

        .no-results-box { display: none; }
        .no-results-box.show { display: block; }

        /* ══════ FOOTER ══════ */
        .site-footer {
            text-align: center;
            padding: 28px 18px calc(28px + var(--safe-b));
            margin-top: 32px;
            border-top: 1px solid var(--border);
            font-size: 11.5px; color: var(--text3);
            letter-spacing: 0.02em;
        }
        .site-footer strong {
            color: var(--accent);
            font-weight: 600;
        }

        /* ══════ SCROLL TOP ══════ */
        #toTop {
            position: fixed;
            bottom: calc(20px + var(--safe-b)); right: 16px;
            width: 42px; height: 42px;
            background: linear-gradient(145deg, var(--accent), var(--accent-2));
            border: none; border-radius: 99px;
            color: #1a0e00; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            opacity: 0; transform: translateY(8px) scale(0.85);
            transition: all .25s cubic-bezier(0.34, 1.56, 0.64, 1);
            z-index: 60;
            box-shadow: 0 8px 24px -4px var(--accent-glow);
        }
        #toTop.show { opacity: 1; transform: translateY(0) scale(1); }
        #toTop svg { width: 16px; height: 16px; }

        /* ══════ RESPONSIVE ══════ */
        @media (max-width: 400px) {
            .thumb { width: 100px; min-height: 100px; }
            .v-pill { min-width: 52px; padding: 6px 9px; }
            .hero-name { font-size: 20px; }
            .section-head h2 { font-size: 22px; }
        }
        .hidden { display: none !important; }
    </style>
</head>
<body>
<div class="page">

{{-- ════ COVER IMAGE ════ --}}
@if($restaurant->cover_image)
<div class="cover">
    <img src="{{ Storage::url($restaurant->cover_image) }}" alt="{{ $restaurant->name }}">
</div>
@endif

{{-- ════ HERO HEADER ════ --}}
<header class="hero">
    <div class="hero-inner">
        {{-- Logo --}}
        <div class="logo-wrap">
            @if($restaurant->logo)
                <img src="{{ Storage::url($restaurant->logo) }}" alt="{{ $restaurant->name }}">
            @else
                <div class="logo-initials">{{ strtoupper(substr($restaurant->name,0,1)) }}</div>
            @endif
        </div>

        {{-- Info --}}
        <div class="hero-text">
            <h1 class="hero-name">{{ $restaurant->name }}</h1>

            @if($restaurant->about)
                <p class="hero-about">{{ $restaurant->about }}</p>
            @endif

            <div class="hero-meta">
                @if($restaurant->address)
                <span class="hero-meta-item">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    {{ Str::limit($restaurant->address, 28) }}
                </span>
                @endif
                @if($restaurant->phone)
                <span class="hero-meta-item">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.948V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                    {{ $restaurant->phone }}
                </span>
                @endif

                @if($restaurant->opening_hours)
                @php
                    $todayKey   = strtolower(now()->format('l'));
                    $todayHours = $restaurant->opening_hours[$todayKey] ?? null;
                @endphp
                @if($todayHours)
                <span class="hero-meta-item {{ ($todayHours['open'] ?? false) ? 'is-open' : 'is-closed' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    @if($todayHours['open'] ?? false)
                        Open · {{ $todayHours['from'] ?? '' }} – {{ $todayHours['to'] ?? '' }}
                    @else
                        Closed Today
                    @endif
                </span>
                @endif
                @endif
            </div>

            {{-- Socials --}}
            @if($restaurant->whatsapp || $restaurant->instagram || $restaurant->facebook)
            <div class="socials">
                @if($restaurant->whatsapp)
                <a class="social-chip wa" target="_blank"
                   href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $restaurant->whatsapp) }}">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                    </svg>
                    WhatsApp
                </a>
                @endif
                @if($restaurant->instagram)
                <a class="social-chip ig" target="_blank"
                   href="https://instagram.com/{{ ltrim($restaurant->instagram, '@') }}">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                    </svg>
                    Instagram
                </a>
                @endif
                @if($restaurant->facebook)
                <a class="social-chip fb" target="_blank"
                   href="https://facebook.com/{{ ltrim($restaurant->facebook, '@') }}">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                    </svg>
                    Facebook
                </a>
                @endif
            </div>
            @endif
        </div>
    </div>
</header>

{{-- ════ SEARCH ════ --}}
<div class="search-strip">
    <div class="search-strip-inner">
        <div class="search-field">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"/>
            </svg>
            <input id="searchInput" type="text" placeholder="Search menu…" autocomplete="off">
            <button id="clearBtn" type="button" aria-label="Clear search">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>
</div>

{{-- ════ CATEGORY BAR ════ --}}
@if($categories->count() > 0)
<nav class="cat-strip">
    <div class="cat-strip-inner">
        @foreach($categories as $cat)
        <a href="{{ route('menu.show', $restaurant->slug) }}?category={{ $cat->slug }}"
           class="cat-chip {{ isset($activeCategory) && $activeCategory->id === $cat->id ? 'active' : '' }}">
            @if($cat->getFirstMediaUrl('image'))
                <img src="{{ $cat->image_url }}" alt="">
            @endif
            {{ $cat->name }}
        </a>
        @endforeach
    </div>
</nav>
@endif

{{-- ════ PRODUCTS ════ --}}
<main class="content">

    @if(isset($activeCategory) && $products->count() > 0)

    <div class="section-head">
        @if($activeCategory->getFirstMediaUrl('image'))
            <img src="{{ $activeCategory->image_url }}" alt="">
        @endif
        <h2>{{ $activeCategory->name }}</h2>
        <span class="item-badge">{{ $products->count() }} items</span>
    </div>

    <div class="product-list" id="productList">
        @foreach($products as $product)
        @php
            $variants = $product->relationLoaded('variants') ? $product->variants : collect();
            $availV   = $variants->where('is_available', true);
            $unavailV = $variants->where('is_available', false);
            $hasV     = $variants->isNotEmpty();
        @endphp

        <article class="product-card {{ !$product->is_available ? 'unavailable' : '' }}" data-name="{{ strtolower($product->name) }}">
            {{-- Thumbnail --}}
            <div class="thumb">
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" loading="lazy">
                        @if(!$product->is_available)
                            <div class="thumb-na"><span class="thumb-na-label">Unavailable</span></div>
                        @endif
                    </div>

            {{-- Body --}}
            <div class="card-body">
                <h3 class="card-name">{{ $product->name }}</h3>

                @if($product->description)
                <p class="card-desc">{{ $product->description }}</p>
                @endif

                @if($hasV)
                <div class="variants-block">
                    <div class="v-title">Choose size / option</div>
                    <div class="v-grid">
                        @foreach($availV as $v)
                        <div class="v-pill">
                            <span class="v-pill-name">{{ $v->name }}</span>
                            <span class="v-pill-price">Rs. {{ number_format($v->discount_price ?? $v->price, 0) }}</span>
                            @if($v->discount_price)
                            <span class="v-pill-old">Rs.&nbsp;{{ number_format($v->price, 0) }}</span>
                            @endif
                        </div>
                        @endforeach

                        @foreach($unavailV as $v)
                        <div class="v-pill na">
                            <span class="v-pill-name">{{ $v->name }}</span>
                            <span class="v-pill-price">Sold out</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @else
                <div class="price-block">
                    @if($product->discount_price)
                        @php $pct = round((($product->price - $product->discount_price) / $product->price) * 100); @endphp
                        <span class="p-main has-discount">Rs. {{ number_format($product->discount_price, 0) }}</span>
                        <span class="p-old">Rs. {{ number_format($product->price, 0) }}</span>
                        <span class="p-off">{{ $pct }}% off</span>
                    @else
                        <span class="p-main">Rs. {{ number_format($product->price, 0) }}</span>
                    @endif
                </div>
                @endif
            </div>
        </article>
        @endforeach
    </div>

    {{-- No search results --}}
    <div class="empty no-results-box" id="noResults">
        <div class="empty-icon">🔍</div>
        <h3>No results</h3>
        <p>Try a different keyword</p>
    </div>

    @elseif(isset($activeCategory))
    <div class="empty">
        <div class="empty-icon">🍽</div>
        <h3>Nothing here yet</h3>
        <p>This category is being prepared</p>
    </div>
    @else
    <div class="empty">
        <div class="empty-icon">🍽</div>
        <h3>Menu coming soon</h3>
        <p>We're setting things up</p>
    </div>
    @endif
</main>

<footer class="site-footer">
    Powered by <strong>QR Menu</strong>
</footer>

<button id="toTop" aria-label="Scroll to top">
    <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/>
    </svg>
</button>

</div>

<script>
    // Search filter
    const input    = document.getElementById('searchInput');
    const clearBtn = document.getElementById('clearBtn');
    const list     = document.getElementById('productList');
    const noRes    = document.getElementById('noResults');

    if (input && list) {
        input.addEventListener('input', () => {
            const q = input.value.trim().toLowerCase();
            clearBtn.style.display = q ? 'block' : 'none';
            let visible = 0;
            list.querySelectorAll('.product-card').forEach(card => {
                const match = !q || card.dataset.name.includes(q);
                card.classList.toggle('hidden', !match);
                if (match) visible++;
            });
            noRes && noRes.classList.toggle('show', visible === 0 && q.length > 0);
        });
        clearBtn.addEventListener('click', () => {
            input.value = '';
            input.dispatchEvent(new Event('input'));
            input.focus();
        });
    }

    // Scroll to top
    const toTop = document.getElementById('toTop');
    window.addEventListener('scroll', () => {
        toTop.classList.toggle('show', window.scrollY > 400);
    }, { passive: true });
    toTop.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
</script>
</body>
</html>
