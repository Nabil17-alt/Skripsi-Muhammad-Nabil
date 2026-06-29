<?php

require __DIR__.'/fix_storage.php';

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CaptchaController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\BankAccountController as AdminBankAccountController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\ChatController as AdminChatController;
use Illuminate\Http\Request;

// MIDTRANS WEBHOOK CALLBACK
Route::post('/payments/callback/gateway', [PaymentController::class, 'gatewayCallback'])->name('midtrans.callback');

// CAPTCHA
Route::get('/captcha', [CaptchaController::class, 'generate'])->name('captcha');

// AUTENTIKASI (CUSTOMER & ADMIN)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// WEB E-COMMERCE (CUSTOMER)
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::prefix('produk')->name('products.')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::get('/{slug}', [ProductController::class, 'show'])->name('show');
});

Route::prefix('keranjang')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/tambah', [CartController::class, 'store'])->name('store');
    Route::post('/update', [CartController::class, 'update'])->name('update');
    Route::post('/hapus', [CartController::class, 'destroy'])->name('destroy');
});

Route::prefix('checkout')->name('checkout.')->middleware('auth')->group(function () {
    Route::get('/', [CheckoutController::class, 'index'])->name('index');
    Route::post('/', [CheckoutController::class, 'process'])->name('process');
});

Route::prefix('pesanan')->name('orders.')->group(function () {
    // Riwayat pesanan milik user yang login
    Route::middleware('auth')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/{orderNumber}', [OrderController::class, 'show'])->name('show');
    });

    // Pelacakan pesanan publik berdasarkan nomor pesanan
    Route::get('/lacak/form', [OrderController::class, 'trackForm'])->name('track-form');
    Route::post('/lacak', [OrderController::class, 'track'])->name('track');
});

Route::prefix('pembayaran')->name('payments.')->group(function () {
    Route::get('/{orderNumber}', [PaymentController::class, 'show'])->name('show');
    Route::get('/{orderNumber}/nota', [PaymentController::class, 'invoice'])->name('invoice');
    Route::post('/{orderNumber}/retry-gateway', [PaymentController::class, 'retryGateway'])
        ->middleware('auth')
        ->name('retry-gateway');
    // Callback dari payment gateway (QRIS / e-wallet) - endpoint stub
    Route::post('/{orderNumber}/upload-bukti', [PaymentController::class, 'uploadProof'])
        ->middleware('auth')
        ->name('upload-proof');
    Route::get('/simulate-auto/{payment}', [PaymentController::class, 'simulateAuto'])
        ->name('simulate-auto');
    Route::get('/simulate-success/{payment}', [PaymentController::class, 'simulateSuccess'])
        ->name('simulate-success');
    Route::get('/simulate-failure/{payment}', [PaymentController::class, 'simulateFailure'])
        ->name('simulate-failure');
});

Route::prefix('chat')->name('chat.')->middleware('auth')->group(function () {
    Route::get('/', [ChatController::class, 'index'])->name('index');
    Route::post('/kirim', [ChatController::class, 'send'])->name('send');
});

// WEB ADMIN (BACK OFFICE)
Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // 1. Beranda / Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/dashboard/sales-data', [AdminDashboardController::class, 'salesData'])->name('dashboard.sales-data');

    // 2. Akun (Manajemen User)
    Route::resource('users', AdminUserController::class);
    Route::post('users/{user}/reset-password', [AdminUserController::class, 'resetPassword'])->name('users.reset-password');

    // 3. Produk
    Route::resource('products', AdminProductController::class);
    Route::delete('product-images/{image}', [AdminProductController::class, 'deleteImage'])->name('products.delete-image');

    // 4. Pesanan
    Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::post('orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');

    // 5. Pembayaran
    Route::get('payments', [AdminPaymentController::class, 'index'])->name('payments.index');
    Route::get('payments/{payment}', [AdminPaymentController::class, 'show'])->name('payments.show');
    Route::post('payments/{payment}/verify', [AdminPaymentController::class, 'verify'])->name('payments.verify');
    Route::post('payments/{payment}/installments/{sequence}/paid', [AdminPaymentController::class, 'markInstallmentPaid'])
        ->name('payments.installments.paid');
    Route::get('installments', [AdminPaymentController::class, 'installments'])->name('installments.index');

    // 6. Transfer Bank
    Route::resource('bank-accounts', AdminBankAccountController::class)->except(['show']);
    Route::post('payment-methods/{method}/toggle-status', [AdminBankAccountController::class, 'toggleMethodStatus'])
        ->name('payment-methods.toggle-status');
    Route::post('payment-methods/update-settings', [AdminBankAccountController::class, 'updateInstallmentSettings'])
        ->name('payment-methods.update-settings');

    // 7. Pesan Pembeli (Chat System)
    Route::get('chats', [AdminChatController::class, 'index'])->name('chats.index');
    Route::get('chats/{chat}', [AdminChatController::class, 'show'])->name('chats.show');
    Route::post('chats/{chat}/reply', [AdminChatController::class, 'reply'])->name('chats.reply');

    // 8. Lihat Website (redirect ke frontend)
    Route::get('lihat-website', function () {
        return redirect()->route('home');
    })->name('lihat-website');



    // 9. Keluar (Logout)
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});
