<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="#0a0a0a">
    <meta name="color-scheme" content="dark">
    <title>{{ $restaurant->name }} — Menu</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg:        #0a0a0a;
            --surface:   #141414;
            --border:    #1f1f1f;
            --border2:   #2a2a2a;
            --text:      #f1f1f1;
            --muted:     #888;
            --subtle:    #444;
            --accent:    #f97316;
            --accent2:   #ea580c;
            --safe-b:    env(safe-area-inset-bottom);
        }

        html { scroll-behavior: smooth; }

        body {
            background: var(--bg);
            color: var(--text);
            font-family: system-ui, -apple-system, 'Segoe UI', sans-serif;
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }

        /* ===== HEADER ===== */
        .header {
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            padding: 16px;
            position: sticky;
            top: 0;
            z-index: 40;
        }
        .header-inner {
            max-width: 680px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .restaurant-logo {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            object-fit: cover;
            border: 1px solid var(--border2);
            flex-shrink: 0;
        }
        .restaurant-logo-placeholder {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            background: linear-gradient(135deg, var(--accent), var(--accent2));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            font-weight: 800;
            color: #fff;
            flex-shrink: 0;
        }
        .restaurant-info h1 {
            font-size: 17px;
            font-weight: 700;
            color: var(--text);
            line-height: 1.2;
        }
        .restaurant-meta {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-top: 3px;
            flex-wrap: wrap;
        }
        .restaurant-meta span {
            font-size: 12px;
            color: var(--muted);
            display: flex;
            align-items: center;
            gap: 3px;
        }
        .meta-dot { color: var(--border2); }

        /* ===== SEARCH BAR ===== */
        .search-wrap {
            background: var(--surface);
            padding: 10px 16px 14px;
            border-bottom: 1px solid var(--border);
        }
        .search-wrap-inner { max-width: 680px; margin: 0 auto; }
        .search-input-wrap {
            display: flex;
            align-items: center;
            gap: 10px;
            background: var(--bg);
            border: 1px solid var(--border2);
            border-radius: 12px;
            padding: 10px 14px;
            transition: border-color .2s;
        }
        .search-input-wrap:focus-within { border-color: var(--accent); }
        .search-input-wrap svg { width: 16px; height: 16px; color: var(--subtle); flex-shrink: 0; }
        .search-input-wrap input {
            background: none;
            border: none;
            outline: none;
            color: var(--text);
            font-size: 14px;
            width: 100%;
        }
        .search-input-wrap input::placeholder { color: var(--subtle); }

        /* ===== CATEGORY BAR ===== */
        .cat-bar {
            position: sticky;
            top: 85px;
            z-index: 30;
            background: var(--bg);
            border-bottom: 1px solid var(--border);
        }
        .cat-bar-inner {
            max-width: 680px;
            margin: 0 auto;
            display: flex;
            gap: 8px;
            overflow-x: auto;
            padding: 12px 16px;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
        }
        .cat-bar-inner::-webkit-scrollbar { display: none; }
        .cat-pill {
            display: flex;
            align-items: center;
            gap: 7px;
            padding: 7px 14px;
            border-radius: 99px;
            font-size: 13px;
            font-weight: 500;
            white-space: nowrap;
            flex-shrink: 0;
            text-decoration: none;
            color: var(--muted);
            background: var(--surface);
            border: 1px solid var(--border2);
            transition: all .2s;
        }
        .cat-pill img {
            width: 18px;
            height: 18px;
            border-radius: 4px;
            object-fit: cover;
        }
        .cat-pill.active,
        .cat-pill:hover {
            background: var(--accent);
            border-color: var(--accent);
            color: #fff;
            box-shadow: 0 0 14px rgba(249,115,22,.35);
        }

        /* ===== MAIN CONTENT ===== */
        .main { max-width: 680px; margin: 0 auto; padding: 16px; }

        /* Section title */
        .section-title {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 14px;
        }
        .section-title img {
            width: 28px;
            height: 28px;
            border-radius: 7px;
            object-fit: cover;
        }
        .section-title h2 {
            font-size: 16px;
            font-weight: 700;
            color: var(--text);
        }
        .section-title .count {
            margin-left: auto;
            font-size: 12px;
            color: var(--muted);
            background: var(--surface);
            border: 1px solid var(--border);
            padding: 2px 10px;
            border-radius: 99px;
        }

        /* ===== PRODUCT CARDS ===== */
        .product-list { display: flex; flex-direction: column; gap: 10px; }

        .product-card {
            display: flex;
            gap: 0;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            overflow: hidden;
            transition: border-color .2s, transform .2s;
            cursor: default;
        }
        .product-card:hover {
            border-color: var(--accent);
            transform: translateY(-1px);
        }
        .product-card:active { transform: scale(.99); }

        .product-img-wrap {
            width: 110px;
            min-height: 110px;
            flex-shrink: 0;
            position: relative;
            overflow: hidden;
        }
        .product-img-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }
        .product-unavailable-overlay {
            position: absolute;
            inset: 0;
            background: rgba(0,0,0,.55);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .product-unavailable-overlay span {
            background: #1a1a1a;
            color: var(--muted);
            font-size: 10px;
            font-weight: 600;
            padding: 3px 8px;
            border-radius: 99px;
            border: 1px solid var(--border2);
        }

        .product-info {
            flex: 1;
            padding: 12px 14px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-width: 0;
        }
        .product-name {
            font-size: 14px;
            font-weight: 600;
            color: var(--text);
            line-height: 1.3;
            margin-bottom: 4px;
        }
        .product-desc {
            font-size: 12px;
            color: var(--muted);
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            margin-bottom: 8px;
        }
        .product-footer { display: flex; align-items: center; justify-content: space-between; gap: 8px; }

        .price-wrap { display: flex; align-items: baseline; gap: 6px; }
        .price-main {
            font-size: 15px;
            font-weight: 700;
            color: var(--accent);
        }
        .price-old {
            font-size: 12px;
            color: var(--subtle);
            text-decoration: line-through;
        }
        .discount-tag {
            font-size: 10px;
            font-weight: 700;
            background: rgba(249,115,22,.15);
            color: var(--accent);
            border: 1px solid rgba(249,115,22,.3);
            padding: 2px 7px;
            border-radius: 99px;
        }

        /* Veg / Non-veg dot */
        .veg-dot {
            width: 14px;
            height: 14px;
            border-radius: 3px;
            border: 1.5px solid #22c55e;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .veg-dot::after {
            content: '';
            width: 7px;
            height: 7px;
            border-radius: 99px;
            background: #22c55e;
        }

        /* ===== EMPTY STATE ===== */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }
        .empty-icon {
            font-size: 48px;
            margin-bottom: 12px;
        }
        .empty-state h3 {
            font-size: 16px;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 6px;
        }
        .empty-state p { font-size: 13px; color: var(--muted); }

        /* ===== SEARCH RESULTS HIGHLIGHT ===== */
        .no-results { display: none; }
        .no-results.show { display: block; }

        /* ===== FOOTER ===== */
        .site-footer {
            text-align: center;
            padding: 24px 16px;
            padding-bottom: calc(24px + var(--safe-b));
            border-top: 1px solid var(--border);
            margin-top: 24px;
        }
        .site-footer p { font-size: 12px; color: var(--subtle); }
        .site-footer span { color: var(--accent); font-weight: 600; }

        /* ===== SCROLL TO TOP ===== */
        #scrollTop {
            position: fixed;
            bottom: calc(20px + var(--safe-b));
            right: 16px;
            width: 40px;
            height: 40px;
            background: var(--accent);
            border: none;
            border-radius: 99px;
            color: #fff;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transform: scale(.8) translateY(10px);
            transition: all .25s;
            z-index: 50;
            box-shadow: 0 4px 16px rgba(249,115,22,.4);
        }
        #scrollTop.show { opacity: 1; transform: scale(1) translateY(0); }
        #scrollTop svg { width: 18px; height: 18px; }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 480px) {
            .product-img-wrap { width: 90px; min-height: 90px; }
            .product-name { font-size: 13px; }
            .price-main { font-size: 14px; }
        }
    </style>
</head>
<body>

    {{-- ===== HEADER ===== --}}
    <header class="header">
        <div class="header-inner">

            {{-- Logo --}}
            @if($restaurant->logo)
                <img src="{{ asset('storage/' . $restaurant->logo) }}"
                     alt="{{ $restaurant->name }}" class="restaurant-logo">
            @else
                <div class="restaurant-logo-placeholder">
                    {{ strtoupper(substr($restaurant->name, 0, 1)) }}
                </div>
            @endif

            {{-- Info --}}
            <div class="restaurant-info">
                <h1>{{ $restaurant->name }}</h1>
                <div class="restaurant-meta">
                    @if($restaurant->address)
                    <span>
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:11px;height:11px">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        {{ Str::limit($restaurant->address, 30) }}
                    </span>
                    @endif
                    @if($restaurant->phone)
                    <span class="meta-dot">·</span>
                    <span>
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:11px;height:11px">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.948V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        {{ $restaurant->phone }}
                    </span>
                    @endif
                </div>
            </div>
        </div>
    </header>

    {{-- ===== SEARCH ===== --}}
    <div class="search-wrap">
        <div class="search-wrap-inner">
            <div class="search-input-wrap">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" id="searchInput" placeholder="Search dishes..." autocomplete="off">
                <button id="clearSearch" onclick="clearSearch()"
                        style="background:none;border:none;cursor:pointer;color:var(--subtle);display:none;padding:0;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:15px;height:15px">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- ===== CATEGORY BAR ===== --}}
    @if($categories->count() > 0)
    <div class="cat-bar">
        <div class="cat-bar-inner" id="catBar">
            @foreach($categories as $cat)
            <a href="{{ route('menu.show', $restaurant->slug) }}?category={{ $cat->slug }}"
               class="cat-pill {{ isset($activeCategory) && $activeCategory->id === $cat->id ? 'active' : '' }}"
               data-cat="{{ $cat->id }}">
                @if($cat->getFirstMediaUrl('image'))
                    <img src="{{ $cat->image_url }}" alt="">
                @endif
                {{ $cat->name }}
            </a>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ===== MAIN ===== --}}
    <main class="main">

        @if(isset($activeCategory) && $products->count() > 0)

        {{-- Section Header --}}
        <div class="section-title">
            @if($activeCategory->getFirstMediaUrl('image'))
                <img src="{{ $activeCategory->image_url }}" alt="">
            @endif
            <h2>{{ $activeCategory->name }}</h2>
            <span class="count">{{ $products->count() }} items</span>
        </div>

        {{-- Products --}}
        <div class="product-list" id="productList">
            @foreach($products as $product)
            <div class="product-card" data-name="{{ strtolower($product->name) }} {{ strtolower($product->description ?? '') }}">

                {{-- Image --}}
                <div class="product-img-wrap">
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                         loading="lazy">
                    @if(!$product->is_available)
                    <div class="product-unavailable-overlay">
                        <span>Unavailable</span>
                    </div>
                    @endif
                </div>

                {{-- Info --}}
                <div class="product-info">
                    <div>
                        <p class="product-name">{{ $product->name }}</p>
                        @if($product->description)
                            <p class="product-desc">{{ $product->description }}</p>
                        @endif
                    </div>
                    {{-- <div class="product-footer">
                        <div class="price-wrap">
                            @if($product->discount_price)
                                <span class="price-main">Rs. {{ number_format($product->discount_price, 0) }}</span>
                                <span class="price-old">Rs. {{ number_format($product->price, 0) }}</span>
                                @php $saving = round((($product->price - $product->discount_price) / $product->price) * 100); @endphp
                                <span class="discount-tag">{{ $saving }}% off</span>
                            @else
                                <span class="price-main">Rs. {{ number_format($product->price, 0) }}</span>
                            @endif
                        </div>
                    </div> --}}
                    <div class="product-footer">
    @if($product->hasVariants())
 
        {{-- ===== VARIANT PILLS ===== --}}
        <div style="width:100%;">
            <p style="font-size:10px;color:var(--muted);text-transform:uppercase;letter-spacing:.07em;margin:0 0 7px;font-weight:600;">
                Choose Size / Option
            </p>
            <div style="display:flex;flex-wrap:wrap;gap:6px;">
                @foreach($product->variants->where('is_available', true) as $variant)
                <div style="
                    display:flex;flex-direction:column;align-items:center;
                    background:rgba(249,115,22,.08);
                    border:1px solid rgba(249,115,22,.2);
                    border-radius:9px;
                    padding:6px 12px;
                    min-width:64px;
                    text-align:center;
                    transition: background .15s, border-color .15s;
                " onmouseover="this.style.background='rgba(249,115,22,.16)';this.style.borderColor='rgba(249,115,22,.4)'"
                   onmouseout="this.style.background='rgba(249,115,22,.08)';this.style.borderColor='rgba(249,115,22,.2)'">
                    <span style="font-size:11px;color:var(--muted);font-weight:500;white-space:nowrap;">
                        {{ $variant->name }}
                    </span>
                    @if($variant->discount_price)
                        <span style="font-size:13px;font-weight:700;color:var(--accent);line-height:1.2;">
                            Rs. {{ number_format($variant->discount_price, 0) }}
                        </span>
                        <span style="font-size:10px;color:var(--subtle);text-decoration:line-through;">
                            Rs. {{ number_format($variant->price, 0) }}
                        </span>
                    @else
                        <span style="font-size:13px;font-weight:700;color:var(--accent);line-height:1.2;">
                            Rs. {{ number_format($variant->price, 0) }}
                        </span>
                    @endif
                </div>
                @endforeach
 
                {{-- Unavailable variants shown as greyed out --}}
                @foreach($product->variants->where('is_available', false) as $variant)
                <div style="
                    display:flex;flex-direction:column;align-items:center;
                    background:#111;border:1px solid #1f1f1f;
                    border-radius:9px;padding:6px 12px;min-width:64px;text-align:center;
                    opacity:.45;
                ">
                    <span style="font-size:11px;color:var(--subtle);font-weight:500;">{{ $variant->name }}</span>
                    <span style="font-size:11px;color:var(--subtle);">Unavailable</span>
                </div>
                @endforeach
            </div>
        </div>
 
    @else
        {{-- ===== SIMPLE PRICE ===== --}}
        <div class="price-wrap">
            @if($product->discount_price)
                <span class="price-main">Rs. {{ number_format($product->discount_price, 0) }}</span>
                <span class="price-old">Rs. {{ number_format($product->price, 0) }}</span>
                @php $saving = round((($product->price - $product->discount_price) / $product->price) * 100); @endphp
                <span class="discount-tag">{{ $saving }}% off</span>
            @else
                <span class="price-main">Rs. {{ number_format($product->price, 0) }}</span>
            @endif
        </div>
    @endif
</div>
 
                </div>

            </div>
            @endforeach
        </div>

        {{-- No search results --}}
        <div class="no-results" id="noResults">
            <div class="empty-state">
                <div class="empty-icon">🔍</div>
                <h3>No results found</h3>
                <p>Try searching with a different keyword</p>
            </div>
        </div>

        @elseif(isset($activeCategory) && $products->count() === 0)
        <div class="empty-state">
            <div class="empty-icon">🍽</div>
            <h3>No items here yet</h3>
            <p>This category is being prepared. Check back soon!</p>
        </div>

        @else
        <div class="empty-state">
            <div class="empty-icon">🍽</div>
            <h3>Menu coming soon</h3>
            <p>We're setting things up. Check back shortly!</p>
        </div>
        @endif

    </main>

    {{-- ===== FOOTER ===== --}}
    <footer class="site-footer">
        <p>Powered by <span>QR Menu</span></p>
    </footer>

    {{-- Scroll to top --}}
    <button id="scrollTop" onclick="window.scrollTo({top:0,behavior:'smooth'})" aria-label="Scroll to top">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
        </svg>
    </button>

    <script>
    // ===== SEARCH =====
    const searchInput  = document.getElementById('searchInput');
    const clearBtn     = document.getElementById('clearSearch');
    const productList  = document.getElementById('productList');
    const noResults    = document.getElementById('noResults');

    searchInput?.addEventListener('input', function() {
        const q = this.value.toLowerCase().trim();
        clearBtn.style.display = q ? 'block' : 'none';

        if (!productList) return;

        const cards = productList.querySelectorAll('.product-card');
        let visible = 0;

        cards.forEach(card => {
            const name = card.dataset.name || '';
            const show = !q || name.includes(q);
            card.style.display = show ? '' : 'none';
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

    // ===== SCROLL TO TOP =====
    const scrollTopBtn = document.getElementById('scrollTop');
    window.addEventListener('scroll', () => {
        scrollTopBtn.classList.toggle('show', window.scrollY > 300);
    });

    // ===== AUTO-SCROLL ACTIVE CATEGORY INTO VIEW =====
    const activePill = document.querySelector('.cat-pill.active');
    if (activePill) {
        activePill.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
    }

    // ===== LAZY IMAGE FADE-IN =====
    document.querySelectorAll('img[loading="lazy"]').forEach(img => {
        img.style.opacity = '0';
        img.style.transition = 'opacity .3s';
        img.onload = () => { img.style.opacity = '1'; };
        if (img.complete) img.style.opacity = '1';
    });
    </script>

</body>
</html>