<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfileController;

use App\Http\Controllers\Dashboard\HomeController;
use App\Http\Controllers\Dashboard\ProductController;
use App\Http\Controllers\Dashboard\CategoryController;
use App\Http\Controllers\Dashboard\QrCodeController;
use App\Http\Controllers\Dashboard\BranchController;
use App\Http\Controllers\Dashboard\StaffController;
use App\Http\Controllers\InviteController;

Route::get('/', function () {
    $plans = \App\Models\Subscription::where('is_active', true)->with('periods')->orderBy('sort_order')->orderBy('name')->get();
    return view('welcome', compact('plans'));
})->name('home');
Route::post('/', function () {
    return redirect('/dashboard');
});
Route::view('/pending', 'auth.pending')->name('pending');
Route::middleware('auth')->group(function () {


    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});
// ---- Restaurant Dashboard ----
Route::prefix('dashboard')->name('dashboard.')->middleware([
    'auth',
    'verified',
    'restaurant',
    // 'subscription',
    'role:restaurant_owner|restaurant_staff|super_admin',
])->group(function () {

    Route::get('/', [HomeController::class, 'index'])->name('home');

    Route::resource('products', ProductController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('qr-codes', QrCodeController::class);
    Route::resource('branches', BranchController::class);

    // ── Nested table management under branches ──
    Route::post('branches/{branch}/tables',              [BranchController::class, 'storeTable'])->name('branches.tables.store');
    Route::post('branches/{branch}/tables/bulk',         [BranchController::class, 'bulkStoreTables'])->name('branches.tables.bulk');
    Route::patch('branches/{branch}/tables/{table}',     [BranchController::class, 'updateTable'])->name('branches.tables.update');
    Route::delete('branches/{branch}/tables/{table}',    [BranchController::class, 'destroyTable'])->name('branches.tables.destroy');
});

Route::patch('products/{product}/toggle', [App\Http\Controllers\Dashboard\ProductController::class, 'toggleAvailability'])
    ->name('dashboard.products.toggle');

// ---- Public QR Menu Routes ----
Route::prefix('r')->name('menu.')->group(function () {
    Route::get('/{restaurant:slug}', [App\Http\Controllers\MenuController::class, 'show'])->name('show');
    Route::get('/{restaurant:slug}/category/{category:slug}', [App\Http\Controllers\MenuController::class, 'category'])->name('category');
});

// ---- QR Token Redirect ----
// Route::get('/m/{token}', [App\Http\Controllers\QrRedirectController::class, 'redirect'])->name('qr.redirect');

Route::get('/m/{token}', [App\Http\Controllers\QrRedirectController::class, 'redirect'])
    ->name('qr.redirect')
    ->middleware('throttle:qr-scan');


Route::prefix('r')->name('menu.')->middleware('throttle:public-menu')->group(function () {
    Route::get('/{restaurant:slug}', [App\Http\Controllers\MenuController::class, 'show'])->name('show');
    Route::get('/{restaurant:slug}/category/{category:slug}', [App\Http\Controllers\MenuController::class, 'category'])->name('category');
});

Route::resource('qr-codes', App\Http\Controllers\Dashboard\QrCodeController::class)
    ->except(['show', 'edit', 'update']);

Route::get('qr-codes/{qrCode}/download', [App\Http\Controllers\Dashboard\QrCodeController::class, 'download'])
    ->name('dashboard.qr-codes.download');

Route::get('qr-codes/{qrCode}/preview', [App\Http\Controllers\Dashboard\QrCodeController::class, 'preview'])
    ->name('dashboard.qr-codes.preview');

Route::get('qr-codes/{qrCode}/print', function (App\Models\QrCode $qrCode) {
    return view('dashboard.qr-codes.print', compact('qrCode'));
})->name('dashboard.qr-codes.print')->middleware(['auth', 'restaurant']);

Route::resource('staff', App\Http\Controllers\Dashboard\StaffController::class)
    ->only(['index', 'create', 'store', 'destroy'])
    ->middleware('role:restaurant_owner|super_admin');

Route::get('/invite/{token}',  [InviteController::class, 'show'])->name('invite.accept');
Route::post('/invite/{token}', [InviteController::class, 'store'])->name('invite.accept.store');

Route::prefix('subscription')->name('dashboard.subscription.')->group(function () {
    Route::get('/',                                   [App\Http\Controllers\Dashboard\SubscriptionController::class, 'index'])->name('index');
    Route::get('/checkout/{plan}/{period}',           [App\Http\Controllers\Dashboard\SubscriptionController::class, 'checkout'])->name('checkout');
    Route::post('/submit/{plan}/{period}',            [App\Http\Controllers\Dashboard\SubscriptionController::class, 'submit'])->name('submit');
});

// Profile routes
Route::prefix('profile')->name('dashboard.profile.')->group(function () {
    Route::get('/',                   [App\Http\Controllers\Dashboard\ProfileController::class, 'index'])->name('index');
    Route::post('/personal',          [App\Http\Controllers\Dashboard\ProfileController::class, 'updatePersonal'])->name('personal');
    Route::post('/password',          [App\Http\Controllers\Dashboard\ProfileController::class, 'updatePassword'])->name('password');
    Route::post('/restaurant',        [App\Http\Controllers\Dashboard\ProfileController::class, 'updateRestaurant'])->name('restaurant');
    Route::post('/languages',         [App\Http\Controllers\Dashboard\ProfileController::class, 'updateLanguages'])->name('languages');
    Route::delete('/logo',            [App\Http\Controllers\Dashboard\ProfileController::class, 'deleteLogo'])->name('logo.delete');
    Route::delete('/cover',           [App\Http\Controllers\Dashboard\ProfileController::class, 'deleteCover'])->name('cover.delete');
    Route::delete('/avatar',          [App\Http\Controllers\Dashboard\ProfileController::class, 'deleteAvatar'])->name('avatar.delete');
});
// ── Public Order Routes (no auth needed) ──
Route::prefix('order')->name('order.')->group(function () {
    Route::post('/{restaurant:slug}',            [App\Http\Controllers\OrderController::class, 'store'])->name('store');
    Route::get('/{restaurant:slug}/{order}',    [App\Http\Controllers\OrderController::class, 'show'])->name('show');
    Route::post('/{restaurant:slug}/{order}/pay', [App\Http\Controllers\OrderController::class, 'submitPayment'])->name('pay');
});

// ── Dashboard Order Routes ──
Route::prefix('dashboard/orders')->name('dashboard.orders.')->middleware(['auth', 'verified', 'restaurant'])->group(function () {
    Route::get('/',                     [App\Http\Controllers\Dashboard\OrderController::class, 'index'])->name('index');
    Route::get('/{order}',              [App\Http\Controllers\Dashboard\OrderController::class, 'show'])->name('show');
    Route::patch('/{order}/status',      [App\Http\Controllers\Dashboard\OrderController::class, 'updateStatus'])->name('status');
    Route::patch('/{order}/confirm-pay', [App\Http\Controllers\Dashboard\OrderController::class, 'confirmPayment'])->name('confirm-pay');
});

// ── Public waiter call (no auth — customer facing) ──
Route::prefix('waiter')->name('waiter.')->group(function () {
    Route::get('/{restaurant:slug}/options', [App\Http\Controllers\WaiterCallController::class, 'options'])->name('options');
    Route::post('/{restaurant:slug}/call',    [App\Http\Controllers\WaiterCallController::class, 'call'])->name('call');
});

// ── Dashboard waiter calls ──
Route::prefix('dashboard/waiter-calls')->name('dashboard.waiter-calls.')->middleware(['auth', 'verified', 'restaurant'])->group(function () {
    Route::get('/',               [App\Http\Controllers\Dashboard\WaiterCallController::class, 'index'])->name('index');
    Route::get('/live',           [App\Http\Controllers\Dashboard\WaiterCallController::class, 'live'])->name('live');
    Route::patch('/{call}/seen',   [App\Http\Controllers\Dashboard\WaiterCallController::class, 'markSeen'])->name('seen');
    Route::patch('/{call}/resolve', [App\Http\Controllers\Dashboard\WaiterCallController::class, 'resolve'])->name('resolve');
    Route::get('/count',          [App\Http\Controllers\Dashboard\WaiterCallController::class, 'count'])->name('count');
});

// ── Dashboard call options management ──
Route::prefix('dashboard/call-options')->name('dashboard.call-options.')->middleware(['auth', 'verified', 'restaurant'])->group(function () {
    Route::get('/',          [App\Http\Controllers\Dashboard\CallOptionController::class, 'index'])->name('index');
    Route::post('/',          [App\Http\Controllers\Dashboard\CallOptionController::class, 'store'])->name('store');
    Route::patch('/{option}', [App\Http\Controllers\Dashboard\CallOptionController::class, 'update'])->name('update');
    Route::delete('/{option}', [App\Http\Controllers\Dashboard\CallOptionController::class, 'destroy'])->name('destroy');
});

require __DIR__ . '/auth.php';
