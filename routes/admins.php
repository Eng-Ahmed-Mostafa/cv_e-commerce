<?php

use App\Livewire\Admin\Brand\Index as Brands;
use App\Livewire\Admin\Category\Index as Categories;
use App\Livewire\Admin\Coupon\Index as Coupon;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Order\Index as Order;
use App\Livewire\Admin\Product\Index as Products;
use App\Livewire\Admin\User\Index as User;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('dashboard', Dashboard::class)->name('dashboard');

    // Users
    Route::get('users', User::class)->name('user');
    Route::get('users/settings', User::class)->name('user.settings');

    // Categories
    Route::get('categories', Categories::class)->name('category');
    Route::get('categories/create', Categories::class)->name('category.create');
    Route::get('categories/edit/{category}', Categories::class)->name('category.edit');

    // Brands
    Route::get('brands', Brands::class)->name('brand');
    Route::get('brands/create', Brands::class)->name('brand.create');
    Route::get('brands/edit/{brand}', Brands::class)->name('brand.edit');

    // Products
    Route::get('products', Products::class)->name('product');
    Route::get('products/create', Products::class)->name('product.create');
    Route::get('products/edit/{product}', Products::class)->name('product.edit');

    // Orders
    Route::get('orders', Order::class)->name('orders');
    Route::get('orders/{id}/details', Order::class)->name('orders.details');

    // Coupons
    Route::get('coupons', Coupon::class)->name('coupon');
    Route::get('coupons/create', Coupon::class)->name('coupon.create');
    Route::get('coupons/edit/{coupon}', Coupon::class)->name('coupon.edit');
});

// Route::middleware(['auth'])->group(function() {

// });
// Route::get('shop',Shop::class)->name('shop');
