<?php

use App\Http\Controllers\BkashController;
use App\Http\Controllers\BkashRefundController;
use App\Http\Controllers\CashPaymentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SslCommerzPaymentController;
use App\Http\Controllers\UserController;
use App\Livewire\Cart;
use App\Livewire\Checkout;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/product/search', [ProductController::class, 'search'])->name('product.search');
Route::get('/product/{product}', [ProductController::class, 'details'])->name('product.details');
Route::get('/order-success', [UserController::class, 'orderSuccess'])->name('order-success');
Route::get('/order-failure', [UserController::class, 'orderFailure'])->name('order-failure');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::get('/privacy-policy', [HomeController::class, 'privacyPolicy'])->name('privacy-policy');


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/cart', Cart::class)->name('cart');
    Route::get('/checkout', Checkout::class)->name('checkout');
    Route::get('/user/orders', [UserController::class, 'orders'])->name('user.orders');
    Route::get('/user/orders/{order}', [UserController::class, 'orderDetails'])->name('user.order.details');
    Route::get('/cash-payment', [CashPaymentController::class, 'index'])->name('cash-payment');
    Route::post('/pay-cash', [CashPaymentController::class, 'pay'])->name('pay-cash');
});

// Email verification
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return back()->with('message', 'Login Successful!');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/user/dashboard', [UserController::class, 'dashboard'])->name('user.dashboard');
});



// SSLCOMMERZ Start
Route::get('/online-payment', [SslCommerzPaymentController::class, 'index'])->name('online-payment');

Route::post('/pay-online', [SslCommerzPaymentController::class, 'pay'])->name('pay-online');

Route::post('/success', [SslCommerzPaymentController::class, 'success']);
Route::post('/fail', [SslCommerzPaymentController::class, 'fail']);
Route::post('/cancel', [SslCommerzPaymentController::class, 'cancel']);

Route::post('/ipn', [SslCommerzPaymentController::class, 'ipn']);
//SSLCOMMERZ END


// BKASH START
Route::get('/bkash-payment', [BkashController::class, 'index'])->name('bkash-payment');
Route::post('bkash/get-token', [BkashController::class, 'getToken'])->name('bkash-get-token');
Route::post('bkash/create-payment', [BkashController::class, 'createPayment'])->name('bkash-create-payment');
Route::post('bkash/execute-payment', [BkashController::class, 'executePayment'])->name('bkash-execute-payment');
Route::post('bkash/success', [BkashController::class, 'successPayment'])->name('bkash-success');

// Refund Routes for bKash
Route::get('bkash/refund', [BkashRefundController::class, 'index'])->name('bkash-refund');
Route::post('bkash/refund', [BkashRefundController::class, 'refund'])->name('bkash-refund');
// BKASH END
