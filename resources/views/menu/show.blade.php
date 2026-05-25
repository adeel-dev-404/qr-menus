<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="#080808">
    <meta name="color-scheme" content="dark">
    <title>{{ $restaurant->name }} — Menu</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg:           #080808;
            --surface:      #111111;
            --surface2:     #181818;
            --surface3:     #202020;
            --border:       #1e1e1e;
            --border2:      #2a2a2a;
            --text:         #f2f2f2;
            --text2:        #9a9a9a;
            --text3:        #555555;
            --accent:       #e86c2a;
            --accent-light: rgba(232,108,42,.12);
            --accent-glow:  rgba(232,108,42,.2);
            --safe-b:       env(safe-area-inset-bottom, 0px);
            --max:          640px;
        }

        html { scroll-behavior: smooth; }

        body {
            background: var(--bg);
            color: var(--text);
            font-family: -apple-system, BlinkMacSystemFont, 'SF Pro Display', 'Segoe UI', sans-serif;
            -webkit-font-smoothing: antialiased;
            min-height: 100vh;
        }

        /* ══════ HERO HEADER ══════ */
        .hero {
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            position: sticky; top: 0; z-index: 50;
        }
        .hero-inner {
            max-width: var(--max);
            margin: 0 auto;
            padding: 14px 16px 12px;
            display: flex; align-items: center; gap: 12px;
        }
        .logo-wrap {
            flex-shrink: 0;
            width: 48px; height: 48px;
            border-radius: 14px;
            overflow: hidden;
            border: 1px solid var(--border2);
        }
        .logo-wrap img { width: 100%; height: 100%; object-fit: cover; }
        .logo-initials {
            width: 100%; height: 100%;
            background: linear-gradient(145deg, #e8a23a, #e8502a);
            display: flex; align-items: center; justify-content: center;
            font-size: 20px; font-weight: 800; color: #fff;
            letter-spacing: -.5px;
        }
        .hero-text { flex: 1; min-width: 0; }
        .hero-name {
            font-size: 17px; font-weight: 700;
            color: var(--text); letter-spacing: -.3px;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .hero-meta {
            display: flex; align-items: center; gap: 10px;
            margin-top: 3px; flex-wrap: wrap;
        }
        .hero-meta-item {
            display: flex; align-items: center; gap: 4px;
            font-size: 11px; color: var(--text3);
        }
        .hero-meta-item svg { width: 11px; height: 11px; flex-shrink: 0; }

        /* ══════ SEARCH ══════ */
        .search-strip {
            background: var(--surface);
            padding: 0 16px 12px;
            border-bottom: 1px solid var(--border);
        }
        .search-strip-inner { max-width: var(--max); margin: 0 auto; }
        .search-field {
            display: flex; align-items: center; gap: 10px;
            background: var(--bg);
            border: 1px solid var(--border2);
            border-radius: 12px;
            padding: 9px 14px;
            transition: border-color .2s;
        }
        .search-field:focus-within { border-color: var(--accent); }
        .search-field svg { width: 15px; height: 15px; color: var(--text3); flex-shrink: 0; }
        .search-field input {
            flex: 1; background: none; border: none; outline: none;
            font-size: 14px; color: var(--text);
        }
        .search-field input::placeholder { color: var(--text3); }
        #clearBtn {
            display: none; background: none; border: none;
            cursor: pointer; color: var(--text3); padding: 0;
        }
        #clearBtn:hover { color: var(--text2); }
        #clearBtn svg { width: 14px; height: 14px; display: block; }

        /* ══════ CATEGORY BAR ══════ */
        .cat-strip {
            position: sticky;
            top: 75px;
            z-index: 40;
            background: var(--bg);
            border-bottom: 1px solid var(--border);
        }
        .cat-strip-inner {
            max-width: var(--max); margin: 0 auto;
            display: flex; gap: 6px;
            overflow-x: auto; padding: 10px 16px;
            scrollbar-width: none;
        }
        .cat-strip-inner::-webkit-scrollbar { display: none; }
        .cat-chip {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 6px 14px; border-radius: 99px;
            font-size: 13px; font-weight: 500;
            white-space: nowrap; flex-shrink: 0;
            text-decoration: none; color: var(--text2);
            background: var(--surface2); border: 1px solid var(--border2);
            transition: all .18s;
        }
        .cat-chip img {
            width: 16px; height: 16px;
            border-radius: 4px; object-fit: cover;
        }
        .cat-chip.active {
            background: var(--accent);
            border-color: var(--accent);
            color: #fff;
        }
        .cat-chip:hover:not(.active) {
            border-color: var(--accent);
            color: var(--accent);
        }

        /* ══════ CONTENT ══════ */
        .content { max-width: var(--max); margin: 0 auto; padding: 16px; }

        .section-head {
            display: flex; align-items: center; gap: 10px;
            margin-bottom: 14px;
        }
        .section-head img {
            width: 24px; height: 24px;
            border-radius: 6px; object-fit: cover;
        }
        .section-head h2 {
            font-size: 15px; font-weight: 700; color: var(--text);
            letter-spacing: -.2px;
        }
        .item-badge {
            margin-left: auto; font-size: 11px; color: var(--text3);
            background: var(--surface2); border: 1px solid var(--border);
            padding: 2px 9px; border-radius: 99px;
        }

        /* ══════ PRODUCT CARD ══════ */
        .product-list { display: flex; flex-direction: column; gap: 8px; }

        .product-card {
            display: flex;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            overflow: hidden;
            transition: border-color .2s;
        }
        .product-card:active { opacity: .9; }

        /* Thumbnail */
        .thumb {
            width: 104px; min-height: 104px;
            flex-shrink: 0; position: relative; overflow: hidden;
        }
        .thumb img {
            width: 100%; height: 100%;
            object-fit: cover; display: block;
        }
        .thumb-na {
            position: absolute; inset: 0;
            background: rgba(0,0,0,.55);
            display: flex; align-items: center; justify-content: center;
        }
        .thumb-na-label {
            font-size: 10px; font-weight: 600; color: var(--text3);
            background: rgba(8,8,8,.8); border: 1px solid var(--border2);
            padding: 3px 8px; border-radius: 99px;
        }

        /* Body */
        .card-body {
            flex: 1; padding: 12px 14px;
            display: flex; flex-direction: column;
            gap: 4px; min-width: 0;
        }
        .card-name {
            font-size: 14px; font-weight: 600; color: var(--text);
            letter-spacing: -.15px; line-height: 1.3;
        }
        .card-desc {
            font-size: 12px; color: var(--text2); line-height: 1.45;
            display: -webkit-box;
            -webkit-line-clamp: 2; -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* ──── Simple price ──── */
        .price-block { display: flex; align-items: center; gap: 7px; flex-wrap: wrap; margin-top: auto; padding-top: 6px; }
        .p-main { font-size: 15px; font-weight: 700; color: var(--accent); }
        .p-old { font-size: 12px; color: var(--text3); text-decoration: line-through; }
        .p-off {
            font-size: 10px; font-weight: 700; color: var(--accent);
            background: var(--accent-light); border: 1px solid var(--accent-glow);
            padding: 1px 6px; border-radius: 99px;
        }

        /* ──── Variants ──── */
        .variants-block { margin-top: auto; padding-top: 6px; }
        .v-title {
            font-size: 10px; font-weight: 600; color: var(--text3);
            text-transform: uppercase; letter-spacing: .07em;
            margin-bottom: 6px;
        }
        .v-grid { display: flex; flex-wrap: wrap; gap: 5px; }

        /* Single variant pill */
        .v-pill {
            display: flex; flex-direction: column; align-items: center;
            padding: 5px 9px; border-radius: 9px; min-width: 54px;
            text-align: center;
            background: var(--surface2); border: 1px solid var(--border2);
            transition: border-color .15s, background .15s;
        }
        .v-pill:hover {
            border-color: var(--accent-glow);
            background: var(--accent-light);
        }
        .v-pill-name {
            font-size: 10px; font-weight: 500; color: var(--text2);
            white-space: nowrap; line-height: 1.3;
        }
        .v-pill-price {
            font-size: 12px; font-weight: 700; color: var(--accent);
            white-space: nowrap; line-height: 1.3;
        }
        .v-pill-old {
            font-size: 9px; color: var(--text3);
            text-decoration: line-through; line-height: 1.2;
        }
        .v-pill.na {
            opacity: .35; pointer-events: none;
        }
        .v-pill.na .v-pill-price {
            font-size: 9px; color: var(--text3);
        }

        /* ══════ EMPTY STATES ══════ */
        .empty { text-align: center; padding: 56px 16px; }
        .empty-icon { font-size: 42px; margin-bottom: 10px; }
        .empty h3 { font-size: 15px; font-weight: 600; color: var(--text); margin-bottom: 6px; }
        .empty p  { font-size: 13px; color: var(--text3); }

        .no-results-box { display: none; }
        .no-results-box.show { display: block; }

        /* ══════ FOOTER ══════ */
        .site-footer {
            text-align: center;
            padding: 18px 16px calc(18px + var(--safe-b));
            border-top: 1px solid var(--border);
            margin-top: 20px;
            font-size: 11px; color: var(--text3);
        }
        .site-footer strong { color: var(--accent); font-weight: 600; }

        /* ══════ SCROLL TOP ══════ */
        #toTop {
            position: fixed;
            bottom: calc(16px + var(--safe-b)); right: 14px;
            width: 36px; height: 36px;
            background: var(--accent); border: none; border-radius: 99px;
            color: #fff; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            opacity: 0; transform: translateY(6px) scale(.88);
            transition: all .22s; z-index: 60;
        }
        #toTop.show { opacity: 1; transform: translateY(0) scale(1); }
        #toTop svg { width: 15px; height: 15px; }

        /* ══════ RESPONSIVE ══════ */
        @media (max-width: 400px) {
            .thumb { width: 88px; }
            .v-pill { min-width: 48px; padding: 4px 7px; }
        }
    </style>
</head>
<body>

{{-- ════ HERO ════ --}}
<header class="hero">
    <div class="hero-inner">
        <div class="logo-wrap">
            @if($restaurant->logo)
                <img src="{{ asset('storage/' . $restaurant->logo) }}" alt="{{ $restaurant->name }}">
            @else
                <div class="logo-initials">{{ strtoupper(substr($restaurant->name,0,1)) }}</div>
            @endif
        </div>
        <div class="hero-text">
            <p class="hero-name">{{ $restaurant->name }}</p>
            <div class="hero-meta">
                @if($restaurant->address)
                <span class="hero-meta-item">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    {{ Str::limit($restaurant->address, 30) }}
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
            </div>
        </div>
    </div>

    {{-- Search inside hero for cohesion --}}
    <div class="search-strip">
        <div class="search-strip-inner">
            <div class="search-field">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" id="searchInput"
                       placeholder="Search dishes…" autocomplete="off">
                <button id="clearBtn" onclick="clearSearch()">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</header>

{{-- ════ CATEGORY BAR ════ --}}
@if($categories->count() > 0)
<nav class="cat-strip" aria-label="Menu categories">
    <div class="cat-strip-inner" id="catBar">
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
            $variants      = $product->relationLoaded('variants') ? $product->variants : collect();
            $availV        = $variants->where('is_available', true);
            $unavailV      = $variants->where('is_available', false);
            $hasV          = $variants->isNotEmpty();
        @endphp

        <article class="product-card"
                 data-name="{{ strtolower($product->name . ' ' . ($product->description ?? '')) }}">

            {{-- Thumbnail --}}
            <div class="thumb">
                <img src="{{ $product->image_url }}"
                     alt="{{ $product->name }}" loading="lazy">
                @if(!$product->is_available)
                <div class="thumb-na">
                    <span class="thumb-na-label">Unavailable</span>
                </div>
                @endif
            </div>

            {{-- Body --}}
            <div class="card-body">
                <p class="card-name">{{ $product->name }}</p>

                @if($product->description)
                <p class="card-desc">{{ $product->description }}</p>
                @endif

                @if($hasV)
                {{-- ── Variant Pills ── --}}
                <div class="variants-block">
                    <p class="v-title">Choose size / option</p>
                    <div class="v-grid">

                        @foreach($availV as $v)
                        <div class="v-pill">
                            <span class="v-pill-name">{{ $v->name }}</span>
                            <span class="v-pill-price">
                                Rs.&nbsp;{{ number_format($v->discount_price ?? $v->price, 0) }}
                            </span>
                            @if($v->discount_price)
                            <span class="v-pill-old">
                                Rs.&nbsp;{{ number_format($v->price, 0) }}
                            </span>
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
                {{-- ── Simple Price ── --}}
                <div class="price-block">
                    @if($product->discount_price)
                        <span class="p-main">Rs. {{ number_format($product->discount_price, 0) }}</span>
                        <span class="p-old">Rs. {{ number_format($product->price, 0) }}</span>
                        @php $pct = round((($product->price - $product->discount_price) / $product->price) * 100); @endphp
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

    <div class="no-results-box" id="noResults">
        <div class="empty">
            <div class="empty-icon">🔍</div>
            <h3>No results</h3>
            <p>Try a different keyword</p>
        </div>
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

<footer class="site-footer">Powered by <strong>QR Menu</strong></footer>

<button id="toTop" onclick="window.scrollTo({top:0,behavior:'smooth'})" aria-label="Back to top">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
              d="M5 10l7-7m0 0l7 7m-7-7v18"/>
    </svg>
</button>

<script>
const searchInput = document.getElementById('searchInput');
const clearBtn    = document.getElementById('clearBtn');
const noResults   = document.getElementById('noResults');

searchInput?.addEventListener('input', function () {
    const q = this.value.toLowerCase().trim();
    clearBtn.style.display = q ? 'block' : 'none';
    let visible = 0;
    document.querySelectorAll('.product-card').forEach(c => {
        const show = !q || c.dataset.name.includes(q);
        c.style.display = show ? '' : 'none';
        if (show) visible++;
    });
    noResults?.classList.toggle('show', visible === 0 && q.length > 0);
});

function clearSearch() {
    searchInput.value = '';
    clearBtn.style.display = 'none';
    document.querySelectorAll('.product-card').forEach(c => c.style.display = '');
    noResults?.classList.remove('show');
    searchInput.focus();
}

const toTop = document.getElementById('toTop');
window.addEventListener('scroll', () => {
    toTop.classList.toggle('show', window.scrollY > 260);
}, { passive: true });

document.querySelector('.cat-chip.active')
    ?.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });

document.querySelectorAll('img[loading="lazy"]').forEach(img => {
    img.style.opacity = '0';
    img.style.transition = 'opacity .22s';
    const show = () => { img.style.opacity = '1'; };
    img.complete ? show() : (img.onload = show);
});
</script>
</body>
</html>