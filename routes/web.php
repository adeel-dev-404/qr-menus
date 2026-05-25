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
    return view('auth.login');
})->name('login');

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
    Route::get('/',                      [App\Http\Controllers\Dashboard\SubscriptionController::class, 'index'])->name('index');
    Route::get('/checkout/{plan}',       [App\Http\Controllers\Dashboard\SubscriptionController::class, 'checkout'])->name('checkout');
    Route::post('/submit/{plan}',        [App\Http\Controllers\Dashboard\SubscriptionController::class, 'submit'])->name('submit');
});

require __DIR__ . '/auth.php';
