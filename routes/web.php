<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\SalesReportController;
use App\Http\Controllers\Kitchen\KitchenOrderController;
use App\Http\Controllers\Cashier\CashierOrderController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\TableController;
use App\Models\RestaurantTable;

Route::get('/', function () {
    return redirect()->route('admin.tables');
});

// ROUTE PENGELOLAAN MEJA ADMIN
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {

        Route::get('/tables', [TableController::class, 'index'])
            ->name('tables');

        Route::get('/tables/create', [TableController::class, 'create'])
            ->name('tables.create');

        Route::post('/tables', [TableController::class, 'store'])
            ->name('tables.store');

        Route::get('/tables/{table}/edit', [TableController::class, 'edit'])
            ->name('tables.edit');

        Route::put('/tables/{table}', [TableController::class, 'update'])
            ->name('tables.update');

        Route::delete('/tables/{table}', [TableController::class, 'destroy'])
            ->name('tables.destroy');

    });

Route::get('/order/dine-in/{qrToken}', [
    OrderController::class,
    'dineIn'
])->name('customer.dine-in');

Route::post('/order/dine-in/{qrToken}/checkout', [
    OrderController::class, 'checkout'
])->name('customer.checkout');

Route::get('/order/success/{orderCode}', [
    OrderController::class, 'success'
])->name('customer.order-success');

// Halaman pemantauan status pesanan
Route::get('/order/status/{orderCode}', [
    OrderController::class,
    'status'
])->name('customer.order-status');

// Data status untuk pembaruan otomatis
Route::get('/order/status/{orderCode}/data', [
    OrderController::class,
    'statusData'
])->name('customer.order-status-data');

// ROUTE ADMIN KELOLA MENU
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {

    Route::resource('menus', MenuController::class)
        ->except(['show']);

});


// ROUTE KASIR
Route::prefix('cashier')
    ->name('cashier.')
    ->middleware(['auth', 'role:cashier'])
    ->group(function () {

        // Halaman daftar pesanan yang belum dibayar
        Route::get('/orders', [
            CashierOrderController::class,
            'index'
        ])->name('orders.index');

        // Konfirmasi pembayaran pelanggan
        Route::post('/orders/{order}/confirm-payment', [
            CashierOrderController::class,
            'confirmPayment'
        ])->name('orders.confirm-payment');

    });


// ROUTE DAPUR
Route::prefix('kitchen')
    ->name('kitchen.')
    ->middleware(['auth', 'role:kitchen'])
    ->group(function () {

    // Halaman daftar pesanan dapur
    Route::get('/orders', [
        KitchenOrderController::class,
        'index'
    ])->name('orders.index');

    // Tandai pesanan siap disajikan
    Route::post('/orders/{order}/ready', [
        KitchenOrderController::class,
        'markReady'
    ])->name('orders.ready');

});


// ROUTE LAPORAN PENJUALAN ADMIN
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {

    Route::get('/reports/sales', [
        SalesReportController::class,
        'index'
    ])->name('reports.sales');

});


// ROUTE LOGIN DAN LOGOUT
Route::get('/login', [
    AuthController::class,
    'showLogin'
])->name('login');

Route::post('/login', [
    AuthController::class,
    'login'
])->name('login.process');

Route::post('/logout', [
    AuthController::class,
    'logout'
])->name('logout');