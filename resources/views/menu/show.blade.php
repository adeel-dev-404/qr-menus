@php
    $langInfo = \App\Models\Restaurant::AVAILABLE_LANGUAGES[$lang ?? 'en'] ?? null;
    $isRtl = $langInfo['rtl'] ?? false;
    $translations = [
        'en' => [
            'viewing_only' => 'Viewing menu only — ordering is not available at this time.',
            'items' => 'items',
            'choose_size' => 'Choose size / option',
            'sold_out' => 'Sold out',
            'unavailable' => 'Unavailable',
            'add' => 'Add',
            'no_results' => 'No results',
            'try_different' => 'Try a different keyword',
            'nothing_here' => 'Nothing here yet',
            'being_prepared' => 'This category is being prepared',
            'coming_soon' => 'Menu coming soon',
            'setting_up' => "We're setting things up",
            'powered_by' => 'Powered by',
            'view_cart' => 'View Cart',
            'your_order' => 'Your Order',
            'cart_empty' => 'Your cart is empty',
            'total' => 'Total',
            'order_type' => 'Order Type',
            'dine_in' => 'Dine-in',
            'takeaway' => 'Takeaway',
            'your_details' => 'Your Details',
            'name' => 'Name',
            'phone' => 'Phone',
            'email' => 'Email (for order updates)',
            'address' => 'Delivery Address',
            'notes' => 'Special Notes',
            'payment_method' => 'Payment Method',
            'pay_counter' => 'Pay at Counter',
            'pay_ready' => 'Pay when order is ready',
            'place_order' => 'Place Order',
            'call_waiter' => 'Call Waiter',
            'loading_options' => 'Loading options...',
            'no_options' => 'No options available right now.',
            'failed_options' => 'Failed to load options.',
            'invalid_link' => 'Invalid Menu Link',
            'invalid_link_desc' => "This link appears to be modified or expired.\nPlease scan the QR code again to access the menu.",
            'go_back' => 'Go Back',
            'accepting_orders' => 'Accepting Orders',
            'search_placeholder' => 'Search dishes...',
            'send_jazzcash' => 'Send payment to JazzCash:',
            'send_easypaisa' => 'Send payment to Easypaisa:',
        ],
        'ur' => [
            'viewing_only' => 'صرف مینو دیکھ رہے ہیں — اس وقت آرڈر دستیاب نہیں ہے۔',
            'items' => 'اشیاء',
            'choose_size' => 'سائز / آپشن منتخب کریں',
            'sold_out' => 'ختم ہو گیا',
            'unavailable' => 'دستیاب نہیں',
            'add' => 'شامل کریں',
            'no_results' => 'کوئی نتیجہ نہیں ملا',
            'try_different' => 'کچھ اور تلاش کرنے کی کوشش کریں',
            'nothing_here' => 'ابھی یہاں کچھ نہیں ہے',
            'being_prepared' => 'یہ کیٹیگری تیار کی جا رہی ہے',
            'coming_soon' => 'مینو جلد آرہا ہے',
            'setting_up' => 'ہم چیزیں ترتیب دے رہے ہیں',
            'powered_by' => 'پیش کردہ بذریعہ',
            'view_cart' => 'کارٹ دیکھیں',
            'your_order' => 'آپ کا آرڈر',
            'cart_empty' => 'آپ کی کارٹ خالی ہے',
            'total' => 'کل رقم',
            'order_type' => 'آرڈر کی قسم',
            'dine_in' => 'یہاں کھائیں',
            'takeaway' => 'باہر لے جائیں',
            'your_details' => 'آپ کی معلومات',
            'name' => 'نام',
            'phone' => 'فون نمبر',
            'email' => 'ای میل (آرڈر اپڈیٹس کے لیے)',
            'address' => 'ڈیلیوری کا پتہ',
            'notes' => 'خصوصی ہدایات',
            'payment_method' => 'ادائیگی کا طریقہ',
            'pay_counter' => 'کاؤنٹر پر ادائیگی کریں',
            'pay_ready' => 'آرڈر تیار ہونے پر ادائیگی کریں',
            'place_order' => 'آرڈر کریں',
            'call_waiter' => 'ویٹر کو بلائیں',
            'loading_options' => 'لوڈ ہو رہا ہے...',
            'no_options' => 'اس وقت کوئی آپشن دستیاب نہیں ہے۔',
            'failed_options' => 'لوڈ کرنے میں ناکامی۔',
            'invalid_link' => 'غلط مینو لنک',
            'invalid_link_desc' => "یہ لنک تبدیل شدہ یا میعاد ختم شدہ معلوم ہوتا ہے۔\nمینو تک رسائی کے لیے براہ کرم دوبارہ کیو آر کوڈ اسکین کریں۔",
            'go_back' => 'واپس جائیں',
            'accepting_orders' => 'آرڈر قبول کیے جا رہے ہیں',
            'search_placeholder' => 'ڈشز تلاش کریں...',
            'send_jazzcash' => 'جاز کیش پر رقم بھیجیں:',
            'send_easypaisa' => 'ایزی پیسہ پر رقم بھیجیں:',
        ],
        'ar' => [
            'viewing_only' => 'عرض القائمة فقط - الطلب غير متاح في هذا الوقت.',
            'items' => 'عناصر',
            'choose_size' => 'اختر الحجم / الخيار',
            'sold_out' => 'نفدت الكمية',
            'unavailable' => 'غير متوفر',
            'add' => 'إضافة',
            'no_results' => 'لا توجد نتائج',
            'try_different' => 'حاول البحث عن كلمة أخرى',
            'nothing_here' => 'لا يوجد شيء هنا بعد',
            'being_prepared' => 'يتم إعداد هذا القسم حالياً',
            'coming_soon' => 'القائمة ستتوفر قريباً',
            'setting_up' => 'نحن نقوم بإعداد الأشياء',
            'powered_by' => 'مشغل بواسطة',
            'view_cart' => 'عرض السلة',
            'your_order' => 'طلبك',
            'cart_empty' => 'سلة التسوق فارغة',
            'total' => 'الإجمالي',
            'order_type' => 'نوع الطلب',
            'dine_in' => 'تناول الطعام هنا',
            'takeaway' => 'سفري / خارج المطعم',
            'your_details' => 'بياناتك',
            'name' => 'الاسم',
            'phone' => 'رقم الهاتف',
            'email' => 'البريد الإلكتروني (لتحديثات الطلب)',
            'address' => 'عنوان التوصيل',
            'notes' => 'ملاحظات خاصة',
            'payment_method' => 'طريقة الدفع',
            'pay_counter' => 'الدفع عند الكاونتر',
            'pay_ready' => 'الدفع عند استلام الطلب',
            'place_order' => 'إرسال الطلب',
            'call_waiter' => 'استدعاء النادل',
            'loading_options' => 'جاري التحميل...',
            'no_options' => 'لا توجد خيارات متاحة حالياً.',
            'failed_options' => 'فشل في تحميل الخيارات.',
            'invalid_link' => 'رابط قائمة غير صالح',
            'invalid_link_desc' => "يبدو أن هذا الرابط قد تم تعديله أو انتهت صلاحيته.\nيرجى مسح رمز QR مجدداً للوصول إلى القائمة.",
            'go_back' => 'العودة',
            'accepting_orders' => 'متاح للطلب',
            'search_placeholder' => 'ابحث عن أطباق...',
            'send_jazzcash' => 'أرسل الدفع إلى جاز كاش:',
            'send_easypaisa' => 'أرسل الدفع إلى إيزي بيسا:',
        ]
    ];
    $trans = $translations[$lang ?? 'en'] ?? $translations['en'];
@endphp
<!DOCTYPE html>
<html lang="{{ $lang ?? 'en' }}" @if($isRtl) dir="rtl" @endif>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#080808">
    <title>{{ $restaurant->name }} — {{ $trans['your_order'] }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --bg: #080808;
            --surface: #111111;
            --surface2: #181818;
            --border: #1e1e1e;
            --border2: #2a2a2a;
            --text: #f0f0f0;
            --text2: #909090;
            --text3: #505050;
            --accent: #e8502a;
            --accent2: #c43e1c;
            --accent-bg: rgba(232, 80, 42, .1);
            --accent-border: rgba(232, 80, 42, .2);
            --safe-b: env(safe-area-inset-bottom, 0px);
        }

        @if($isRtl)
        body {
            font-family: 'Noto Nastaliq Urdu', 'Noto Sans Arabic', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }
        .item-count {
            margin-left: 0 !important;
            margin-right: auto !important;
        }
        .cart-btn-left {
            flex-direction: row-reverse !important;
        }
        #toTop {
            right: auto !important;
            left: 14px !important;
        }
        #waiterCallBtn {
            right: auto !important;
            left: 14px !important;
        }
        @endif

        html {
            scroll-behavior: smooth;
        }

        body {
            background: var(--bg);
            color: var(--text);
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            -webkit-font-smoothing: antialiased;
            min-height: 100vh;
            padding-bottom: calc(80px + var(--safe-b));
        }

        /* ── HEADER ── */
        .header {
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            padding: 14px 16px;
            position: sticky;
            top: 0;
            z-index: 40;
        }

        .header-inner {
            max-width: 640px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            object-fit: cover;
            border: 1px solid var(--border2);
            flex-shrink: 0;
        }

        .logo-placeholder {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            background: linear-gradient(135deg, #e8502a, #c43e1c);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            font-weight: 800;
            color: #fff;
            flex-shrink: 0;
        }

        .header-info h1 {
            font-size: 16px;
            font-weight: 700;
            color: var(--text);
            line-height: 1.2;
        }

        .header-meta {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 2px;
            flex-wrap: wrap;
        }

        .header-meta span {
            font-size: 11px;
            color: var(--text3);
            display: flex;
            align-items: center;
            gap: 3px;
        }

        .header-meta svg {
            width: 11px;
            height: 11px;
        }

        /* ── SEARCH ── */
        .search-wrap {
            background: var(--surface);
            padding: 8px 16px 12px;
            border-bottom: 1px solid var(--border);
        }

        .search-inner {
            max-width: 640px;
            margin: 0 auto;
        }

        .search-box {
            display: flex;
            align-items: center;
            gap: 10px;
            background: var(--bg);
            border: 1px solid var(--border2);
            border-radius: 10px;
            padding: 9px 12px;
            transition: border-color .2s;
        }

        .search-box:focus-within {
            border-color: var(--accent);
        }

        .search-box svg {
            width: 15px;
            height: 15px;
            color: var(--text3);
            flex-shrink: 0;
        }

        .search-box input {
            flex: 1;
            background: none;
            border: none;
            outline: none;
            color: var(--text);
            font-size: 14px;
        }

        .search-box input::placeholder {
            color: var(--text3);
        }

        #clearBtn {
            display: none;
            background: none;
            border: none;
            cursor: pointer;
            color: var(--text3);
            padding: 0;
        }

        #clearBtn svg {
            width: 14px;
            height: 14px;
            display: block;
        }

        /* ── CATEGORY BAR ── */
        .cat-bar {
            position: sticky;
            top: 73px;
            z-index: 30;
            background: var(--bg);
            border-bottom: 1px solid var(--border);
        }

        .cat-bar-inner {
            max-width: 640px;
            margin: 0 auto;
            display: flex;
            gap: 6px;
            overflow-x: auto;
            padding: 10px 16px;
            scrollbar-width: none;
            -webkit-overflow-scrolling: touch;
        }

        .cat-bar-inner::-webkit-scrollbar {
            display: none;
        }

        .cat-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 99px;
            font-size: 13px;
            font-weight: 500;
            white-space: nowrap;
            flex-shrink: 0;
            text-decoration: none;
            color: var(--text2);
            background: var(--surface);
            border: 1px solid var(--border2);
            transition: all .18s;
        }

        .cat-pill img {
            width: 16px;
            height: 16px;
            border-radius: 3px;
            object-fit: cover;
        }

        .cat-pill.active,
        .cat-pill:hover {
            background: var(--accent);
            border-color: var(--accent);
            color: #fff;
        }

        /* ── MAIN ── */
        .main {
            max-width: 640px;
            margin: 0 auto;
            padding: 16px;
        }

        .section-heading {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 12px;
        }

        .section-heading img {
            width: 26px;
            height: 26px;
            border-radius: 6px;
            object-fit: cover;
        }

        .section-heading h2 {
            font-size: 15px;
            font-weight: 700;
            color: var(--text);
        }

        .item-count {
            margin-left: auto;
            font-size: 11px;
            color: var(--text3);
            background: var(--surface2);
            border: 1px solid var(--border);
            padding: 2px 9px;
            border-radius: 99px;
        }

        /* ── PRODUCT CARD ── */
        .product-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .product-card {
            display: flex;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            overflow: hidden;
            transition: border-color .18s;
        }

        .product-list.grid-2, .product-list.grid-3 {
            display: grid;
            gap: 12px;
        }
        
        .product-list.grid-2 {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .product-list.grid-3 {
            grid-template-columns: repeat(3, 1fr);
        }

        @media(max-width: 480px) {
            .product-list.grid-3 {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        .product-list.grid-2 .product-card, .product-list.grid-3 .product-card {
            flex-direction: column;
        }

        .product-list.grid-2 .thumb, .product-list.grid-3 .thumb {
            width: 100%;
            height: 120px;
            border-bottom: 1px solid var(--border);
        }

        .product-list.grid-2 .card-body, .product-list.grid-3 .card-body {
            padding: 10px;
        }

        .product-card:hover {
            border-color: var(--border2);
        }

        .thumb {
            width: 104px;
            min-height: 104px;
            flex-shrink: 0;
            position: relative;
            overflow: hidden;
        }

        .thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .thumb-na {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, .55);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .thumb-na span {
            font-size: 10px;
            font-weight: 600;
            color: var(--text3);
            background: rgba(8, 8, 8, .8);
            border: 1px solid var(--border2);
            padding: 3px 8px;
            border-radius: 99px;
        }

        .card-body {
            flex: 1;
            padding: 12px 14px;
            display: flex;
            flex-direction: column;
            gap: 4px;
            min-width: 0;
        }

        .card-name {
            font-size: 14px;
            font-weight: 600;
            color: var(--text);
            letter-spacing: -.1px;
            line-height: 1.3;
        }

        .card-desc {
            font-size: 12px;
            color: var(--text2);
            line-height: 1.45;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Price row */
        .price-block {
            display: flex;
            align-items: center;
            gap: 7px;
            flex-wrap: wrap;
            margin-top: auto;
            padding-top: 6px;
        }

        .p-main {
            font-size: 15px;
            font-weight: 700;
            color: var(--accent);
        }

        .p-old {
            font-size: 12px;
            color: var(--text3);
            text-decoration: line-through;
        }

        .p-off {
            font-size: 10px;
            font-weight: 700;
            color: var(--accent);
            background: var(--accent-bg);
            border: 1px solid var(--accent-border);
            padding: 1px 6px;
            border-radius: 99px;
        }

        /* Variants */
        .variants-block {
            margin-top: auto;
            padding-top: 6px;
        }

        .v-title {
            font-size: 10px;
            font-weight: 600;
            color: var(--text3);
            text-transform: uppercase;
            letter-spacing: .06em;
            margin-bottom: 5px;
        }

        .v-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
        }

        .v-pill {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 5px 9px;
            border-radius: 9px;
            min-width: 54px;
            text-align: center;
            background: var(--surface2);
            border: 1px solid var(--border2);
            transition: all .15s;
            cursor: pointer;
        }

        .v-pill:hover,
        .v-pill.selected {
            border-color: var(--accent);
            background: var(--accent-bg);
        }

        .v-pill-name {
            font-size: 10px;
            font-weight: 500;
            color: var(--text2);
            white-space: nowrap;
        }

        .v-pill-price {
            font-size: 12px;
            font-weight: 700;
            color: var(--accent);
            white-space: nowrap;
        }

        .v-pill-old {
            font-size: 9px;
            color: var(--text3);
            text-decoration: line-through;
        }

        .v-pill.na {
            opacity: .35;
            pointer-events: none;
        }

        /* Add to cart button */
        .add-btn {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 12px;
            background: var(--accent);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: background .15s;
            margin-top: 8px;
            align-self: flex-start;
        }

        .add-btn:hover {
            background: var(--accent2);
        }

        .add-btn:disabled {
            background: var(--text3);
            cursor: not-allowed;
        }

        /* Qty control (shows after item added) */
        .qty-control {
            display: none;
            align-items: center;
            gap: 0;
            margin-top: 8px;
            align-self: flex-start;
        }

        .qty-btn {
            width: 28px;
            height: 28px;
            background: var(--surface2);
            border: 1px solid var(--border2);
            color: var(--text);
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all .15s;
        }

        .qty-btn:first-child {
            border-radius: 8px 0 0 8px;
        }

        .qty-btn:last-child {
            border-radius: 0 8px 8px 0;
        }

        .qty-btn:hover {
            background: var(--surface);
            border-color: var(--accent);
        }

        .qty-num {
            width: 32px;
            height: 28px;
            background: var(--surface);
            border: 1px solid var(--border2);
            border-left: none;
            border-right: none;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 700;
            color: var(--text);
        }

        /* ── CART BUTTON (sticky bottom) ── */
        .cart-btn-wrap {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 12px 16px;
            padding-bottom: calc(12px + var(--safe-b));
            background: var(--bg);
            border-top: 1px solid var(--border);
            z-index: 60;
            display: none;
        }

        .cart-btn-wrap.show {
            display: block;
        }

        .cart-btn {
            width: 100%;
            max-width: 640px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: var(--accent);
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: 14px 20px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: background .18s;
        }

        .cart-btn:hover {
            background: var(--accent2);
        }

        .cart-btn-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .cart-count-badge {
            background: rgba(255, 255, 255, .25);
            border-radius: 99px;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 800;
        }

        /* ── CART DRAWER ── */
        .drawer-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .7);
            z-index: 70;
            display: none;
            backdrop-filter: blur(4px);
        }

        .drawer-overlay.open {
            display: block;
        }

        .drawer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: var(--surface);
            border-radius: 20px 20px 0 0;
            border-top: 1px solid var(--border);
            z-index: 80;
            padding: 20px 16px;
            padding-bottom: calc(20px + var(--safe-b));
            max-height: 90vh;
            overflow-y: auto;
            transform: translateY(100%);
            transition: transform .3s cubic-bezier(.4, 0, .2, 1);
        }

        .drawer.open {
            transform: translateY(0);
        }

        .drawer-handle {
            width: 36px;
            height: 4px;
            background: var(--border2);
            border-radius: 99px;
            margin: 0 auto 20px;
        }

        .drawer-title {
            font-size: 17px;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 16px;
        }

        /* Cart items */
        .cart-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 0;
            border-bottom: 1px solid var(--border);
        }

        .cart-item:last-of-type {
            border-bottom: none;
        }

        .cart-item-img {
            width: 44px;
            height: 44px;
            border-radius: 8px;
            object-fit: cover;
            flex-shrink: 0;
            border: 1px solid var(--border2);
        }

        .cart-item-info {
            flex: 1;
            min-width: 0;
        }

        .cart-item-name {
            font-size: 13px;
            font-weight: 600;
            color: var(--text);
        }

        .cart-item-variant {
            font-size: 11px;
            color: var(--text3);
            margin-top: 1px;
        }

        .cart-item-price {
            font-size: 13px;
            font-weight: 700;
            color: var(--accent);
            white-space: nowrap;
        }

        .cart-item-qty {
            display: flex;
            align-items: center;
            gap: 0;
            flex-shrink: 0;
        }

        .ciq-btn {
            width: 26px;
            height: 26px;
            background: var(--surface2);
            border: 1px solid var(--border2);
            color: var(--text);
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
        }

        .ciq-num {
            width: 28px;
            text-align: center;
            font-size: 13px;
            font-weight: 700;
            color: var(--text);
        }

        /* Cart total */
        .cart-total {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 14px 0;
            border-top: 1px solid var(--border);
            margin-top: 4px;
        }

        .cart-total-label {
            font-size: 14px;
            font-weight: 600;
            color: var(--text2);
        }

        .cart-total-amount {
            font-size: 18px;
            font-weight: 800;
            color: var(--text);
        }

        /* Order type selector */
        .order-type-row {
            display: flex;
            gap: 10px;
            margin-bottom: 14px;
        }

        .order-type-btn {
            flex: 1;
            padding: 10px;
            background: var(--surface2);
            border: 1px solid var(--border2);
            border-radius: 10px;
            color: var(--text2);
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            text-align: center;
            transition: all .18s;
        }

        .order-type-btn.active {
            background: var(--accent-bg);
            border-color: var(--accent);
            color: var(--accent);
        }

        /* Form fields inside drawer */
        .drawer-field {
            margin-bottom: 10px;
        }

        .drawer-label {
            display: block;
            font-size: 11px;
            font-weight: 600;
            color: var(--text3);
            text-transform: uppercase;
            letter-spacing: .05em;
            margin-bottom: 5px;
        }

        .drawer-input {
            width: 100%;
            background: var(--bg);
            border: 1px solid var(--border2);
            border-radius: 8px;
            padding: 9px 12px;
            font-size: 14px;
            color: var(--text);
            outline: none;
            transition: border-color .2s;
        }

        .drawer-input:focus {
            border-color: var(--accent);
        }

        .drawer-input::placeholder {
            color: var(--text3);
        }

        .drawer-input:-webkit-autofill {
            -webkit-box-shadow: 0 0 0 100px var(--bg) inset;
            -webkit-text-fill-color: var(--text);
        }

        /* Payment method selector */
        .payment-row {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-bottom: 12px;
        }

        .payment-opt {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            background: var(--bg);
            border: 1px solid var(--border2);
            border-radius: 10px;
            cursor: pointer;
            transition: all .18s;
        }

        .payment-opt:hover,
        .payment-opt.active {
            border-color: var(--accent);
            background: var(--accent-bg);
        }

        .payment-opt input[type="radio"] {
            accent-color: var(--accent);
        }

        .payment-opt-icon {
            font-size: 20px;
            flex-shrink: 0;
        }

        .payment-opt-info h4 {
            font-size: 13px;
            font-weight: 700;
            color: var(--text);
        }

        .payment-opt-info p {
            font-size: 11px;
            color: var(--text3);
        }

        /* JazzCash/Easypaisa account display */
        .payment-account {
            background: var(--surface2);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 10px 12px;
            margin-bottom: 10px;
            display: none;
        }

        .payment-account.show {
            display: block;
        }

        .payment-account p {
            font-size: 12px;
            color: var(--text2);
            margin-bottom: 3px;
        }

        .payment-account strong {
            color: var(--accent);
            font-size: 15px;
            letter-spacing: .05em;
        }

        /* Place order button */
        .place-order-btn {
            width: 100%;
            background: var(--accent);
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: 14px;
            font-size: 15px;
            font-weight: 800;
            cursor: pointer;
            transition: background .18s;
            margin-top: 4px;
        }

        .place-order-btn:hover {
            background: var(--accent2);
        }

        /* Empty cart */
        .empty-cart {
            text-align: center;
            padding: 32px 0;
            color: var(--text3);
        }

        .empty-cart p {
            font-size: 14px;
            margin-top: 8px;
        }

        /* Ordering disabled notice */
        .ordering-disabled {
            background: var(--surface2);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 12px 14px;
            margin-bottom: 16px;
            font-size: 13px;
            color: var(--text3);
            text-align: center;
        }

        /* Empty/No results */
        .empty {
            text-align: center;
            padding: 56px 16px;
        }

        .empty-icon {
            font-size: 42px;
            margin-bottom: 10px;
        }

        .empty h3 {
            font-size: 15px;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 6px;
        }

        .empty p {
            font-size: 13px;
            color: var(--text3);
        }

        .no-results {
            display: none;
        }

        .no-results.show {
            display: block;
        }

        footer {
            text-align: center;
            padding: 18px 16px calc(18px + var(--safe-b));
            border-top: 1px solid var(--border);
            margin-top: 20px;
            font-size: 11px;
            color: var(--text3);
        }

        footer strong {
            color: var(--accent);
            font-weight: 600;
        }

        #toTop {
            position: fixed;
            bottom: calc(80px + var(--safe-b));
            right: 14px;
            width: 36px;
            height: 36px;
            background: var(--accent);
            border: none;
            border-radius: 99px;
            color: #fff;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transform: translateY(6px) scale(.88);
            transition: all .22s;
            z-index: 60;
        }

        #toTop.show {
            opacity: 1;
            transform: translateY(0) scale(1);
        }

        #toTop svg {
            width: 15px;
            height: 15px;
        }

        /* ── WAITER CALL ── */
        #waiterCallBtn {
            position: fixed;
            bottom: calc(130px + var(--safe-b));
            right: 14px;
            width: 44px;
            height: 44px;
            background: var(--surface2);
            border: 1px solid var(--border2);
            border-radius: 50%;
            color: var(--text);
            font-size: 20px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4);
            z-index: 60;
            transition: all .2s;
        }

        #waiterCallBtn:hover {
            background: var(--surface);
            border-color: var(--accent);
        }

        .waiter-opt {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            background: var(--surface2);
            border: 1px solid var(--border2);
            border-radius: 12px;
            cursor: pointer;
            transition: all .15s;
        }

        .waiter-opt:hover {
            background: var(--accent-bg);
            border-color: var(--accent);
        }

        .waiter-opt-icon {
            font-size: 24px;
        }

        .waiter-opt-label {
            font-size: 15px;
            font-weight: 600;
            color: var(--text);
        }

        #toast {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%) translateY(-100px);
            background: var(--surface);
            color: var(--text);
            padding: 12px 20px;
            border-radius: 99px;
            font-size: 14px;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5);
            border: 1px solid var(--border2);
            z-index: 9999;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            opacity: 0;
            pointer-events: none;
        }

        #toast.show {
            transform: translateX(-50%) translateY(0);
            opacity: 1;
        }

        @media(max-width:400px) {
            .thumb {
                width: 88px;
            }
        }

        /* ── TAMPER / RESCAN MODAL ── */
        .tamper-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .85);
            backdrop-filter: blur(12px);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .tamper-modal {
            background: var(--surface);
            border: 1px solid var(--border2);
            border-radius: 20px;
            padding: 36px 28px;
            max-width: 380px;
            width: 100%;
            text-align: center;
        }

        .tamper-modal .tamper-icon {
            font-size: 48px;
            margin-bottom: 16px;
        }

        .tamper-modal h2 {
            font-size: 18px;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 10px;
        }

        .tamper-modal p {
            font-size: 14px;
            color: var(--text2);
            line-height: 1.5;
            margin-bottom: 24px;
        }

        .tamper-modal button {
            background: var(--accent);
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: 12px 28px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: background .18s;
        }

        .tamper-modal button:hover {
            background: var(--accent2);
        }
    </style>
</head>

<body>

    <header class="header">
        <div class="header-inner">
            @if ($restaurant->logo)
            <img src="{{ Storage::url($restaurant->logo) }}" alt="{{ $restaurant->name }}" class="logo">
            @else
            <div class="logo-placeholder">{{ strtoupper(substr($restaurant->name, 0, 1)) }}</div>
            @endif
            <div class="header-info">
                <h1>{{ $restaurant->name }}</h1>
                <div class="header-meta">
                    @if ($restaurant->address)
                    <span>
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        {{ Str::limit($restaurant->address, 30) }}
                    </span>
                    @endif
                    @if ($restaurant->isOrderingEnabled())
                    <span style="color:#4ade80;">● {{ $trans['accepting_orders'] }}</span>
                    @endif
                </div>

                <div style="display:flex; flex-wrap:wrap; gap:8px; margin-top:6px; font-size:11px;">
                    @if($restaurant->facebook || $restaurant->instagram || $restaurant->whatsapp)
                    <div style="display:flex; gap:6px;">
                        @if($restaurant->facebook)
                        <a href="{{ $restaurant->facebook }}" target="_blank" style="color:var(--text); text-decoration:none; background:var(--surface2); padding:2px 8px; border-radius:4px; border:1px solid var(--border2);">FB</a>
                        @endif
                        @if($restaurant->instagram)
                        <a href="{{ $restaurant->instagram }}" target="_blank" style="color:var(--text); text-decoration:none; background:var(--surface2); padding:2px 8px; border-radius:4px; border:1px solid var(--border2);">IG</a>
                        @endif
                        @if($restaurant->whatsapp)
                        <a href="https://wa.me/{{ $restaurant->whatsapp }}" target="_blank" style="color:var(--text); text-decoration:none; background:var(--surface2); padding:2px 8px; border-radius:4px; border:1px solid var(--border2);">WA</a>
                        @endif
                    </div>
                    @endif
                    @if($restaurant->opening_hours)
                    @php
                        $today = strtolower(now()->timezone('Asia/Karachi')->format('l'));
                        $todayHours = $restaurant->opening_hours[$today] ?? null;
                    @endphp
                    @if($todayHours)
                        <div style="color:var(--text3); display:flex; align-items:center;">
                            🕒 {{ $todayHours['open'] ? $todayHours['from'] . ' - ' . $todayHours['to'] : 'Closed' }}
                        </div>
                    @endif
                    @endif
                </div>
            </div>

            {{-- Language Toggle --}}
            @php $supportedLangs = $restaurant->supported_languages ?? ['en']; @endphp
            @if(count($supportedLangs) > 1)
            <div style="margin-inline-start: auto; display: flex; gap: 4px; background: var(--surface2); padding: 4px; border-radius: 99px; border: 1px solid var(--border2); z-index: 5;" id="langToggle">
                @foreach($supportedLangs as $slang)
                    @php $sInfo = \App\Models\Restaurant::AVAILABLE_LANGUAGES[$slang] ?? null; @endphp
                    @if($sInfo)
                        <button onclick="switchLanguage('{{ $slang }}')"
                                style="background: {{ ($lang ?? 'en') === $slang ? 'var(--accent)' : 'none' }};
                                       color: {{ ($lang ?? 'en') === $slang ? '#fff' : 'var(--text2)' }};
                                       border: none; padding: 4px 10px; border-radius: 99px; font-size: 11px; font-weight: 700; cursor: pointer; transition: all .15s; white-space: nowrap;">
                            {{ $sInfo['native'] }}
                        </button>
                    @endif
                @endforeach
            </div>
            @endif
        </div>
        <div class="search-wrap">
            <div class="search-inner">
                <div class="search-box">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" id="searchInput" placeholder="{{ $trans['search_placeholder'] }}" autocomplete="off">
                    <button id="clearBtn" onclick="clearSearch()"><svg fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg></button>
                </div>
            </div>
        </div>
    </header>

    {{-- CATEGORY BAR --}}
    @php
        $hasDeals = isset($deals) && $deals->count() > 0;
        $isDealsActive = $hasDeals && (request()->get('category') === 'deals' || (!request()->has('category') && $hasDeals));
    @endphp
    @if ($categories->count() > 0 || $hasDeals)
    <nav class="cat-bar">
        <div class="cat-bar-inner">
            @if ($hasDeals)
            <a href="{{ route('menu.show', $restaurant->slug) }}?category=deals{{ request()->has('ctx') ? '&ctx=' . urlencode(request()->get('ctx')) : '' }}&lang={{ $lang ?? 'en' }}"
                class="cat-pill {{ $isDealsActive ? 'active' : '' }}">
                <span>🔥</span>
                {{ $lang === 'ur' ? 'ڈیلز' : ($lang === 'ar' ? 'العروض' : 'Deals') }}
            </a>
            @endif

            @foreach ($categories as $cat)
            @php
                $isCatActive = !$isDealsActive && ((isset($activeCategory) && $activeCategory->id === $cat->id) || (!request()->has('category') && !$hasDeals && $loop->first));
            @endphp
            <a href="{{ route('menu.show', $restaurant->slug) }}?category={{ $cat->slug }}{{ request()->has('ctx') ? '&ctx=' . urlencode(request()->get('ctx')) : '' }}&lang={{ $lang ?? 'en' }}"
                class="cat-pill {{ $isCatActive ? 'active' : '' }}">
                @if ($cat->getFirstMediaUrl('image'))
                <img src="{{ $cat->image_url }}" alt="">
                @endif
                {{ $cat->trans('name', $lang ?? 'en') }}
            </a>
            @endforeach
        </div>
    </nav>
    @endif

    {{-- MAIN --}}
    <main class="main">

        @if (!$restaurant->isOrderingEnabled())
        <div class="ordering-disabled">📋 {{ $trans['viewing_only'] }}</div>
        @endif

        {{-- DEALS SECTION --}}
        @if(isset($deals) && $deals->count() > 0 && $isDealsActive && !request('search'))
        <div style="margin-bottom: 24px;">
            <div class="section-heading" style="margin-bottom:12px;">
                <h2 style="font-size:18px; color:var(--accent);">🔥 {{ $lang === 'ur' ? 'ہاٹ ڈیلز' : ($lang === 'ar' ? 'عروض ساخنة' : 'Hot Deals') }}</h2>
                <span class="item-count">{{ $deals->count() }} {{ $trans['items'] }}</span>
            </div>
            
            <div class="product-list {{ $restaurant->menu_layout ?? 'list' }}" id="dealsList">
                @foreach($deals as $deal)
                    <div class="product-card"
                        data-name="{{ strtolower($deal->name . ' ' . ($deal->description ?? '')) }}"
                        data-id="deal-{{ $deal->id }}" data-name-text="{{ $deal->name }}"
                        data-price="{{ $deal->price }}" data-image="{{ $deal->image_url }}"
                        data-has-variants="0" data-is-deal="1">
                        <div class="thumb">
                            <img src="{{ $deal->image_url }}" alt="{{ $deal->name }}" loading="lazy">
                            <div style="position:absolute; top:8px; right:8px; background:var(--accent); color:#fff; font-size:9px; font-weight:800; padding:2px 8px; border-radius:99px; box-shadow:0 4px 10px rgba(232,80,42,0.3); z-index:2;">DEAL</div>
                            @if (!$deal->is_available)
                            <div class="thumb-na"><span>{{ $trans['unavailable'] }}</span></div>
                            @endif
                        </div>
                        <div class="card-body">
                            <p class="card-name">{{ $deal->name }}</p>
                            
                            {{-- Included Items --}}
                            <div style="font-size:11px; color:var(--text3); margin-top:2px; margin-bottom:6px; display:flex; flex-direction:column; gap:1px; line-height:1.3;">
                                @foreach($deal->items as $item)
                                    @if($item->product)
                                        <div style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                            • {{ $item->quantity }}x {{ $item->product->trans('name', $lang ?? 'en') }}
                                            @if($item->variant)
                                                ({{ $item->variant->trans('name', $lang ?? 'en') }})
                                            @endif
                                        </div>
                                    @endif
                                @endforeach
                            </div>

                            @if ($deal->description)
                            <p class="card-desc">{{ $deal->description }}</p>
                            @endif
                            
                            <div class="price-block">
                                <span class="p-main">Rs. {{ number_format($deal->price, 0) }}</span>
                            </div>
                            @if ($deal->is_available && $restaurant->isOrderingEnabled())
                            <div style="display:flex;align-items:center;gap:8px;margin-top:8px;">
                                <button class="add-btn add-btn-deal-{{ $deal->id }}" onclick="addToCart('deal-{{ $deal->id }}')">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        style="width:13px;height:13px">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M12 4v16m8-8H4" />
                                    </svg>
                                    {{ $trans['add'] }}
                                </button>
                                <div class="qty-control qty-deal-{{ $deal->id }}">
                                    <button class="qty-btn" onclick="changeQty('deal-{{ $deal->id }}', -1)">−</button>
                                    <div class="qty-num qty-num-deal-{{ $deal->id }}">1</div>
                                    <button class="qty-btn" onclick="changeQty('deal-{{ $deal->id }}', 1)">+</button>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        @if (isset($activeCategory) && !$isDealsActive && $products->count() > 0)

        <div class="section-heading">
            @if ($activeCategory->getFirstMediaUrl('image'))
            <img src="{{ $activeCategory->image_url }}" alt="">
            @endif
            <h2>{{ $activeCategory->trans('name', $lang ?? 'en') }}</h2>
            <span class="item-count">{{ $products->count() }} {{ $trans['items'] }}</span>
        </div>

        <div class="product-list {{ $restaurant->menu_layout ?? 'list' }}" id="productList">
            @foreach ($products as $product)
            @php
            $variants = $product->relationLoaded('variants') ? $product->variants : collect();
            $availV = $variants->where('is_available', true);
            $unavailV = $variants->where('is_available', false);
            $hasV = $variants->isNotEmpty();
            $basePrice = $hasV ? $availV->min('price') : $product->discount_price ?? $product->price;
            @endphp

            <article class="product-card"
                data-name="{{ strtolower($product->trans('name', $lang ?? 'en') . ' ' . ($product->trans('description', $lang ?? 'en') ?? '')) }}"
                data-id="{{ $product->id }}" data-name-text="{{ $product->trans('name', $lang ?? 'en') }}"
                data-price="{{ $basePrice }}" data-image="{{ $product->image_url }}"
                data-has-variants="{{ $hasV ? '1' : '0' }}">

                <div class="thumb">
                    <img src="{{ $product->image_url }}" alt="{{ $product->trans('name', $lang ?? 'en') }}" loading="lazy">
                    @if (!$product->is_available)
                    <div class="thumb-na"><span>{{ $trans['unavailable'] }}</span></div>
                    @endif
                </div>

                <div class="card-body">
                    <p class="card-name">{{ $product->trans('name', $lang ?? 'en') }}</p>
                    @if ($product->trans('description', $lang ?? 'en'))
                    <p class="card-desc">{{ $product->trans('description', $lang ?? 'en') }}</p>
                    @endif

                    @if ($hasV)
                    <div class="variants-block">
                        <p class="v-title">{{ $trans['choose_size'] }}</p>
                        <div class="v-grid">
                            @foreach ($availV as $v)
                            @php $vName = $v->trans('name', $lang ?? 'en'); @endphp
                            <div class="v-pill"
                                onclick="selectVariant(this, {{ $product->id }}, {{ $v->id }}, '{{ addslashes($vName) }}', {{ $v->discount_price ?? $v->price }})"
                                data-variant-id="{{ $v->id }}"
                                data-variant-name="{{ $vName }}"
                                data-variant-price="{{ $v->discount_price ?? $v->price }}">
                                <span class="v-pill-name">{{ $vName }}</span>
                                <span
                                    class="v-pill-price">Rs.&nbsp;{{ number_format($v->discount_price ?? $v->price, 0) }}</span>
                                @if ($v->discount_price)
                                <span
                                    class="v-pill-old">Rs.&nbsp;{{ number_format($v->price, 0) }}</span>
                                @endif
                            </div>
                            @endforeach
                            @foreach ($unavailV as $v)
                            <div class="v-pill na">
                                <span class="v-pill-name">{{ $v->trans('name', $lang ?? 'en') }}</span>
                                <span class="v-pill-price">{{ $trans['sold_out'] }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @else
                    <div class="price-block">
                        @if ($product->discount_price)
                        <span class="p-main">Rs.
                            {{ number_format($product->discount_price, 0) }}</span>
                        <span class="p-old">Rs. {{ number_format($product->price, 0) }}</span>
                        @php $pct = round((($product->price - $product->discount_price)/$product->price)*100); @endphp
                        <span class="p-off">{{ $pct }}% {{ $lang === 'ur' ? 'رعایت' : ($lang === 'ar' ? 'خصم' : 'off') }}</span>
                        @else
                        <span class="p-main">Rs. {{ number_format($product->price, 0) }}</span>
                        @endif
                    </div>
                    @endif

                    @if ($product->is_available && $restaurant->isOrderingEnabled())
                    <div style="display:flex;align-items:center;gap:8px;">
                        <button class="add-btn add-btn-{{ $product->id }}"
                            onclick="addToCart({{ $product->id }})">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                style="width:13px;height:13px">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            {{ $trans['add'] }}
                        </button>
                        <div class="qty-control qty-{{ $product->id }}">
                            <button class="qty-btn"
                                onclick="changeQty({{ $product->id }}, -1)">−</button>
                            <div class="qty-num qty-num-{{ $product->id }}">1</div>
                            <button class="qty-btn"
                                onclick="changeQty({{ $product->id }}, 1)">+</button>
                        </div>
                    </div>
                    @endif
                </div>
            </article>
            @endforeach
        </div>

        <div class="no-results" id="noResults">
            <div class="empty">
                <div class="empty-icon">🔍</div>
                <h3>{{ $trans['no_results'] }}</h3>
                <p>{{ $trans['try_different'] }}</p>
            </div>
        </div>
        @elseif(isset($activeCategory))
        <div class="empty">
            <div class="empty-icon">🍽</div>
            <h3>{{ $trans['nothing_here'] }}</h3>
            <p>{{ $trans['being_prepared'] }}</p>
        </div>
        @elseif(!$isDealsActive)
        <div class="empty">
            <div class="empty-icon">🍽</div>
            <h3>{{ $trans['coming_soon'] }}</h3>
            <p>{{ $trans['setting_up'] }}</p>
        </div>
        @endif

    </main>

    <footer>{{ $trans['powered_by'] }} <strong>QR Menu</strong></footer>

    {{-- CART BUTTON --}}
    @if ($restaurant->isOrderingEnabled())
    <div class="cart-btn-wrap" id="cartBtnWrap">
        <div style="max-width:640px;margin:0 auto;">
            <button class="cart-btn" onclick="openCart()">
                <div class="cart-btn-left">
                    <span class="cart-count-badge" id="cartCountBadge">0</span>
                    {{ $trans['view_cart'] }}
                </div>
                <span id="cartTotalDisplay">Rs. 0</span>
            </button>
        </div>
    </div>
    @endif

    {{-- SCROLL TOP --}}
    <button id="toTop" onclick="window.scrollTo({top:0,behavior:'smooth'})">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 10l7-7m0 0l7 7m-7-7v18" />
        </svg>
    </button>

    {{-- CART DRAWER --}}
    @if ($restaurant->isOrderingEnabled())
    <div class="drawer-overlay" id="drawerOverlay" onclick="closeCart()"></div>
    <div class="drawer" id="cartDrawer">
        <div class="drawer-handle"></div>
        <div class="drawer-title">{{ $trans['your_order'] }} 🛒</div>

        {{-- Cart items --}}
        <div id="cartItemsContainer">
            <div class="empty-cart" id="emptyCartMsg">
                <div style="font-size:32px;">🛒</div>
                <p>{{ $trans['cart_empty'] }}</p>
            </div>
        </div>

        {{-- Cart total --}}
        <div class="cart-total" id="cartTotalRow" style="display:none;">
            <span class="cart-total-label">{{ $trans['total'] }}</span>
            <span class="cart-total-amount" id="cartTotalAmount">Rs. 0</span>
        </div>

        {{-- Order form --}}
        <div id="orderForm" style="display:none;">

            {{-- Order type --}}
            <p
                style="font-size:12px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.05em;margin-bottom:8px;">
                {{ $trans['order_type'] }}</p>
            <div class="order-type-row">
                <button class="order-type-btn active" id="type-dine" onclick="setOrderType('dine_in')">🍽
                    {{ $trans['dine_in'] }}</button>
                <button class="order-type-btn" id="type-take" onclick="setOrderType('takeaway')">🥡
                    {{ $trans['takeaway'] }}</button>
            </div>

            {{-- Customer details --}}
            <p
                style="font-size:12px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.05em;margin:12px 0 8px;">
                {{ $trans['your_details'] }}</p>
            <div class="drawer-field">
                <label class="drawer-label">{{ $trans['name'] }} *</label>
                <input type="text" id="customerName" class="drawer-input" placeholder="{{ $trans['name'] }}">
            </div>
            <div class="drawer-field">
                <label class="drawer-label">{{ $trans['phone'] }} *</label>
                <input type="tel" id="customerPhone" class="drawer-input" placeholder="0300-1234567">
            </div>
            <div class="drawer-field">
                <label class="drawer-label">{{ $trans['email'] }}</label>
                <input type="email" id="customerEmail" class="drawer-input"
                    placeholder="your@email.com (optional)">
            </div>
            <div class="drawer-field" id="addressField" style="display:none;">
                <label class="drawer-label">{{ $trans['address'] }} *</label>
                <textarea id="customerAddress" class="drawer-input" rows="2" placeholder="{{ $trans['address'] }}"
                    style="resize:none;"></textarea>
            </div>
            <div class="drawer-field">
                <label class="drawer-label">{{ $trans['notes'] }}</label>
                <input type="text" id="orderNotes" class="drawer-input"
                    placeholder="{{ $trans['notes'] }}...">
            </div>

            {{-- Payment method --}}
            <p
                style="font-size:12px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.05em;margin:12px 0 8px;">
                {{ $trans['payment_method'] }}</p>
            <div class="payment-row">
                @if ($restaurant->jazzcash_number)
                <label class="payment-opt" onclick="setPayment('jazzcash', this)">
                    <input type="radio" name="payment" value="jazzcash"
                         style="accent-color:var(--accent);">
                    <span class="payment-opt-icon">💚</span>
                    <div class="payment-opt-info">
                        <h4>JazzCash</h4>
                        <p>{{ $lang === 'ur' ? 'جاز کیش موبائل اکاؤنٹ کے ذریعے ادائیگی کریں' : ($lang === 'ar' ? 'ادفع عبر حساب جاز كاش للهاتف المحمول' : 'Pay via JazzCash mobile account') }}</p>
                    </div>
                </label>
                @endif
                @if ($restaurant->easypaisa_number)
                <label class="payment-opt" onclick="setPayment('easypaisa', this)">
                    <input type="radio" name="payment" value="easypaisa"
                         style="accent-color:var(--accent);">
                    <span class="payment-opt-icon">💙</span>
                    <div class="payment-opt-info">
                        <h4>Easypaisa</h4>
                        <p>{{ $lang === 'ur' ? 'ایزی پیسہ اکاؤنٹ کے ذریعے ادائیگی کریں' : ($lang === 'ar' ? 'ادفع عبر حساب إيزي بيسا' : 'Pay via Easypaisa account') }}</p>
                    </div>
                </label>
                @endif
                <label class="payment-opt" onclick="setPayment('pay_later', this)">
                    <input type="radio" name="payment" value="pay_later" style="accent-color:var(--accent);"
                        checked>
                    <span class="payment-opt-icon">💵</span>
                    <div class="payment-opt-info">
                        <h4>{{ $trans['pay_counter'] }}</h4>
                        <p>{{ $trans['pay_ready'] }}</p>
                    </div>
                </label>
            </div>

            {{-- JazzCash account info --}}
            <div class="payment-account" id="jazzcash-info">
                <p>{{ $trans['send_jazzcash'] }}</p>
                <strong>{{ $restaurant->jazzcash_number }}</strong>
            </div>
            <div class="payment-account" id="easypaisa-info">
                <p>{{ $trans['send_easypaisa'] }}</p>
                <strong>{{ $restaurant->easypaisa_number }}</strong>
            </div>

            <button class="place-order-btn" onclick="placeOrder()">
                {{ $trans['place_order'] }} →
            </button>
        </div>

        <form method="POST" action="{{ route('order.store', $restaurant->slug) }}" id="hiddenOrderForm"
            style="display:none;">
            @csrf
            <input type="hidden" name="customer_name" id="f_name">
            <input type="hidden" name="customer_phone" id="f_phone">
            <input type="hidden" name="customer_email" id="f_email">
            <input type="hidden" name="customer_address" id="f_address">
            <input type="hidden" name="type" id="f_type" value="dine_in">
            <input type="hidden" name="payment_method" id="f_payment" value="pay_later">
            <input type="hidden" name="notes" id="f_notes">
            <input type="hidden" name="cart" id="f_cart">
            <input type="hidden" name="table_id" id="f_table_id">
            <input type="hidden" name="branch_id" id="f_branch_id">
        </form>

    </div>
    @endif

    {{-- TOAST --}}
    <div id="toast"></div>

    @if($restaurant->waiter_call_enabled && isset($qrContext) && ($qrContext['type'] ?? '') === 'table')
    {{-- WAITER CALL BUTTON — only for table QR scans --}}
    <button id="waiterCallBtn" onclick="openWaiterDrawer()">
        🛎️
    </button>

    {{-- WAITER DRAWER --}}
    <div class="drawer-overlay" id="waiterDrawerOverlay" onclick="closeWaiterDrawer()"></div>
    <div class="drawer" id="waiterDrawer">
        <div class="drawer-handle"></div>
        <div class="drawer-title">
            {{ $trans['call_waiter'] }} 🛎️
            <span id="waiterTableBadge" style="display:none;font-size:13px;font-weight:500;color:var(--text3);margin-left:8px;"></span>
        </div>
        <div id="waiterOptionsContainer" style="display:flex; flex-direction:column; gap:10px;">
            <div style="text-align:center; padding: 20px; color: var(--text3);">{{ $trans['loading_options'] }}</div>
        </div>
    </div>
    @endif

    {{-- TAMPER DETECTION MODAL --}}
    @if(!empty($qrTampered))
    <div class="tamper-overlay" id="tamperModal">
        <div class="tamper-modal">
            <div class="tamper-icon">⚠️</div>
            <h2>Invalid Menu Link</h2>
            <p>This link appears to be modified or expired.<br>Please scan the QR code again to access the menu.</p>
            <button onclick="window.history.length > 1 ? window.history.back() : window.location.href='/';">← Go Back</button>
        </div>
    </div>
    @endif

    <script>
        // ── Cart State ──
        let cart = {};
        let orderType = 'dine_in';
        let paymentMethod = 'pay_later';

        // ── Read table/branch from encrypted QR context (server-injected) ──
        const _tableId  = '{{ ($qrContext["t"] ?? "") }}';
        const _branchId = '{{ ($qrContext["b"] ?? "") }}';
        const _qrType   = '{{ ($qrContext["type"] ?? "") }}';

        // Pre-fill hidden order form fields if table QR was scanned
        if (_tableId)  document.getElementById('f_table_id').value  = _tableId;
        if (_branchId) document.getElementById('f_branch_id').value = _branchId;

        // ── Product data from DOM ──
        function getProductData(productId) {
            const card = document.querySelector(`[data-id="${productId}"]`);
            if (!card) return null;
            const isDeal = card.dataset.isDeal === '1';
            return {
                id: isDeal ? productId : parseInt(productId),
                isDeal: isDeal,
                dealId: isDeal ? parseInt(productId.replace('deal-', '')) : null,
                name: card.dataset.nameText,
                price: parseFloat(card.dataset.price),
                image: card.dataset.image,
                hasVariants: card.dataset.hasVariants === '1',
                variantId: card.dataset.selectedVariantId || null,
                variantName: card.dataset.selectedVariantName || null,
                variantPrice: card.dataset.selectedVariantPrice || null,
            };
        }

        function selectVariant(el, productId, variantId, variantName, variantPrice) {
            // Deselect others in same product
            const card = document.querySelector(`[data-id="${productId}"]`);
            card.querySelectorAll('.v-pill').forEach(p => p.classList.remove('selected'));
            el.classList.add('selected');

            card.dataset.selectedVariantId = variantId;
            card.dataset.selectedVariantName = variantName;
            card.dataset.selectedVariantPrice = variantPrice;

            // Update add button
            const addBtns = document.querySelectorAll('.add-btn-' + productId);
            addBtns.forEach(btn => btn.textContent = '+ Add');
        }

        function getCartKey(productId, variantId) {
            return variantId ? `${productId}_v${variantId}` : `${productId}`;
        }

        function addToCart(productId) {
            const p = getProductData(productId);
            if (!p) return;

            if (p.hasVariants && !p.variantId) {
                // Flash the variants section
                const card = document.querySelector(`[data-id="${productId}"]`);
                card.querySelectorAll('.v-pill').forEach(el => {
                    el.style.borderColor = '#e8502a';
                    setTimeout(() => el.style.borderColor = '', 1200);
                });
                return;
            }

            const key = getCartKey(productId, p.variantId);
            const price = p.variantPrice ? parseFloat(p.variantPrice) : p.price;

            if (cart[key]) {
                cart[key].qty++;
            } else {
                cart[key] = {
                    key,
                    productId: p.isDeal ? null : p.id,
                    dealId: p.dealId,
                    isDeal: p.isDeal,
                    variantId: p.variantId,
                    name: p.name,
                    variantName: p.variantName,
                    price,
                    image: p.image,
                    qty: 1,
                };
            }

            updateCartUI(productId, key);
            showCartButton();
        }

        function changeQty(productId, delta) {
            const p = getProductData(productId);
            const key = getCartKey(productId, p?.variantId);
            if (!cart[key]) return;

            cart[key].qty += delta;

            if (cart[key].qty <= 0) {
                delete cart[key];
                // Show add button again
                document.querySelectorAll('.add-btn-' + productId).forEach(b => b.style.display = '');
                document.querySelectorAll('.qty-' + productId).forEach(q => q.style.display = 'none');
            } else {
                document.querySelectorAll('.qty-num-' + productId).forEach(n => n.textContent = cart[key].qty);
            }

            showCartButton();
        }

        function updateCartUI(productId, key) {
            document.querySelectorAll('.add-btn-' + productId).forEach(b => b.style.display = 'none');
            document.querySelectorAll('.qty-' + productId).forEach(q => q.style.display = 'flex');
            document.querySelectorAll('.qty-num-' + productId).forEach(n => n.textContent = cart[key]?.qty ?? 1);
        }

        function showCartButton() {
            const total = getCartTotal();
            const count = getCartCount();
            const wrap = document.getElementById('cartBtnWrap');
            const badge = document.getElementById('cartCountBadge');
            const disp = document.getElementById('cartTotalDisplay');

            if (count > 0) {
                if (wrap) {
                    wrap.classList.add('show');
                }
                if (badge) badge.textContent = count;
                if (disp) disp.textContent = 'Rs. ' + total.toLocaleString();
            } else {
                if (wrap) wrap.classList.remove('show');
            }
        }

        function getCartTotal() {
            return Object.values(cart).reduce((s, i) => s + i.price * i.qty, 0);
        }

        function getCartCount() {
            return Object.values(cart).reduce((s, i) => s + i.qty, 0);
        }

        // ── Cart Drawer ──
        function openCart() {
            renderCartDrawer();
            document.getElementById('cartDrawer').classList.add('open');
            document.getElementById('drawerOverlay').classList.add('open');
            document.body.style.overflow = 'hidden';
        }

        function closeCart() {
            document.getElementById('cartDrawer').classList.remove('open');
            document.getElementById('drawerOverlay').classList.remove('open');
            document.body.style.overflow = '';
        }

        function renderCartDrawer() {
            const container = document.getElementById('cartItemsContainer');
            const emptyMsg = document.getElementById('emptyCartMsg');
            const totalRow = document.getElementById('cartTotalRow');
            const form = document.getElementById('orderForm');
            const totalAmt = document.getElementById('cartTotalAmount');

            const items = Object.values(cart);
            if (items.length === 0) {
                container.innerHTML = '';
                container.appendChild(emptyMsg);
                emptyMsg.style.display = 'block';
                totalRow.style.display = 'none';
                form.style.display = 'none';
                return;
            }

            emptyMsg.style.display = 'none';
            let html = '';
            items.forEach(item => {
                html += `
        <div class="cart-item">
            <img src="${item.image}" class="cart-item-img" alt="${item.name}">
            <div class="cart-item-info">
                <p class="cart-item-name">${item.name}</p>
                ${item.variantName ? `<p class="cart-item-variant">${item.variantName}</p>` : ''}
            </div>
            <div style="display:flex;align-items:center;gap:12px;flex-shrink:0;">
                <div class="cart-item-qty">
                    <button class="ciq-btn" onclick="drawerQty('${item.key}', -1)">−</button>
                    <span class="ciq-num" id="dqty-${item.key}">${item.qty}</span>
                    <button class="ciq-btn" onclick="drawerQty('${item.key}', 1)">+</button>
                </div>
                <span class="cart-item-price">Rs. ${(item.price * item.qty).toLocaleString()}</span>
            </div>
        </div>`;
            });

            container.innerHTML = html;
            const total = getCartTotal();
            totalRow.style.display = 'flex';
            totalAmt.textContent = 'Rs. ' + total.toLocaleString();
            form.style.display = 'block';
        }

        function drawerQty(key, delta) {
            if (!cart[key]) return;
            cart[key].qty += delta;
            if (cart[key].qty <= 0) delete cart[key];
            showCartButton();
            renderCartDrawer();
        }

        function setOrderType(type) {
            orderType = type;
            document.getElementById('type-dine').classList.toggle('active', type === 'dine_in');
            document.getElementById('type-take').classList.toggle('active', type === 'takeaway');
            const addrField = document.getElementById('addressField');
            if (addrField) addrField.style.display = type === 'takeaway' ? 'block' : 'none';
        }

        function setPayment(method, el) {
            paymentMethod = method;
            document.querySelectorAll('.payment-opt').forEach(o => o.classList.remove('active'));
            if (el) el.classList.add('active');
            document.getElementById('jazzcash-info').classList.toggle('show', method === 'jazzcash');
            document.getElementById('easypaisa-info').classList.toggle('show', method === 'easypaisa');
        }

        const _currentLang = '{{ $lang ?? 'en' }}';

        const _trans = {
            name_required: "{{ $lang === 'ur' ? 'براہ کرم اپنا نام درج کریں۔' : ($lang === 'ar' ? 'يرجى إدخال اسمك.' : 'Please enter your name.') }}",
            phone_required: "{{ $lang === 'ur' ? 'براہ کرم اپنا فون نمبر درج کریں۔' : ($lang === 'ar' ? 'يرجى إدخال رقم هاتفك.' : 'Please enter your phone number.') }}",
            address_required: "{{ $lang === 'ur' ? 'براہ کرم ڈلیوری کا پتہ درج کریں۔' : ($lang === 'ar' ? 'يرجى إدخال عنوان التوصيل.' : 'Please enter your delivery address.') }}",
            loading_options: "{{ $trans['loading_options'] }}",
            no_options: "{{ $trans['no_options'] }}",
            failed_options: "{{ $trans['failed_options'] }}"
        };

        function switchLanguage(lang) {
            const url = new URL(window.location.href);
            url.searchParams.set('lang', lang);
            window.location.href = url.toString();
        }

        function placeOrder() {
            const name = document.getElementById('customerName').value.trim();
            const phone = document.getElementById('customerPhone').value.trim();
            const addr = document.getElementById('customerAddress')?.value.trim();
            document.getElementById('f_email').value = document.getElementById('customerEmail').value;


            if (!name) {
                document.getElementById('customerName').focus();
                alert(_trans.name_required);
                return;
            }
            if (!phone) {
                document.getElementById('customerPhone').focus();
                alert(_trans.phone_required);
                return;
            }
            if (orderType === 'takeaway' && !addr) {
                document.getElementById('customerAddress').focus();
                alert(_trans.address_required);
                return;
            }

            const cartArr = Object.values(cart).map(i => ({
                product_id: i.isDeal ? null : i.productId,
                deal_id: i.isDeal ? i.dealId : null,
                variant_id: i.variantId || null,
                name: i.name,
                variant_name: i.variantName || null,
                price: i.price,
                quantity: i.qty,
            }));

            document.getElementById('f_name').value = name;
            document.getElementById('f_phone').value = phone;
            document.getElementById('f_address').value = addr || '';
            document.getElementById('f_type').value = orderType;
            document.getElementById('f_payment').value = paymentMethod;
            document.getElementById('f_notes').value = document.getElementById('orderNotes').value;
            document.getElementById('f_cart').value = JSON.stringify(cartArr);

            document.getElementById('hiddenOrderForm').submit();
        }

        // ── Search ──
        const searchInput = document.getElementById('searchInput');
        const clearBtn = document.getElementById('clearBtn');
        const noResults = document.getElementById('noResults');

        searchInput?.addEventListener('input', function() {
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

        // ── Scroll top ──
        const toTop = document.getElementById('toTop');
        window.addEventListener('scroll', () => {
            toTop.classList.toggle('show', window.scrollY > 260);
        }, {
            passive: true
        });

        // ── Active category scroll ──
        document.querySelector('.cat-pill.active')?.scrollIntoView({
            behavior: 'smooth',
            block: 'nearest',
            inline: 'center'
        });

        // ── Image fade in ──
        document.querySelectorAll('img[loading="lazy"]').forEach(img => {
            img.style.opacity = '0';
            img.style.transition = 'opacity .22s';
            const show = () => {
                img.style.opacity = '1';
            };
            img.complete ? show() : (img.onload = show);
        });

        // ── Waiter Call ──
        function openWaiterDrawer() {
            document.getElementById('waiterDrawer').classList.add('open');
            document.getElementById('waiterDrawerOverlay').classList.add('open');
            document.body.style.overflow = 'hidden';

            // Show table badge if a table QR was scanned
            const badge = document.getElementById('waiterTableBadge');
            if (badge && _tableId) {
                badge.textContent = '— Table ' + _tableId;
                badge.style.display = 'inline';
            }

            fetch(`{{ route('waiter.options', $restaurant->slug) }}?lang=${_currentLang}`)
                .then(res => res.json())
                .then(data => {
                    const container = document.getElementById('waiterOptionsContainer');
                    if (data.length === 0) {
                        container.innerHTML = `<div style="text-align:center; padding: 20px; color: var(--text3);">${_trans.no_options}</div>`;
                        return;
                    }
                    let html = '';
                    data.forEach(opt => {
                        html += `
                        <div class="waiter-opt" onclick="callWaiter(${opt.id})">
                            <span class="waiter-opt-icon">${opt.icon || '🛎️'}</span>
                            <span class="waiter-opt-label">${opt.label}</span>
                        </div>
                        `;
                    });
                    container.innerHTML = html;
                })
                .catch(err => {
                    document.getElementById('waiterOptionsContainer').innerHTML = `<div style="text-align:center; padding: 20px; color: #ef4444;">${_trans.failed_options}</div>`;
                });
        }

        function closeWaiterDrawer() {
            document.getElementById('waiterDrawer').classList.remove('open');
            document.getElementById('waiterDrawerOverlay').classList.remove('open');
            document.body.style.overflow = '';
        }

        function callWaiter(optionId) {
            fetch(`{{ route('waiter.call', $restaurant->slug) }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        option_id: optionId,
                        table_id:  _tableId  ? parseInt(_tableId)  : null,
                        branch_id: _branchId ? parseInt(_branchId) : null,
                        lang:      _currentLang,
                    })
                })
                .then(async res => {
                    const data = await res.json();
                    if (!res.ok) throw new Error(data.message || 'Something went wrong');
                    showToast(data.message || 'Waiter called!');
                    closeWaiterDrawer();
                })
                .catch(err => {
                    showToast(err.message);
                });
        }

        function showToast(msg) {
            const toast = document.getElementById('toast');
            if (!toast) return;
            toast.textContent = msg;
            toast.classList.add('show');
            setTimeout(() => {
                toast.classList.remove('show');
            }, 3000);
        }
    </script>

</body>

</html>