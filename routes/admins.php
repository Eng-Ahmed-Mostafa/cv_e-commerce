<?php

use App\Livewire\Admin\Brand\Create as BrandCreate;
use App\Livewire\Admin\Brand\Index as Brands;
use App\Livewire\Admin\Brand\Edit as BrandEdit;
use App\Livewire\Admin\Category\Index as Categories;
use App\Livewire\Admin\Coupon\Create as CouponCreate;
use App\Livewire\Admin\Coupon\Display as CouponDisplay;
use App\Livewire\Admin\Coupon\Edit as CouponEdit;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Order\Display as OrderDisplay;
use App\Livewire\Admin\Product\Index as Products;
use App\Livewire\Admin\User\Display as UserDisplay;
use App\Livewire\Admin\User\Setting;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('dashboard', Dashboard::class)->name('dashboard');

    // Users
    Route::get('user', UserDisplay::class)->name('user');
    Route::get('setting', Setting::class)->name('setting');

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
    Route::get('orders', OrderDisplay::class)->name('orders');

    // Coupons
    Route::get('coupon', CouponDisplay::class)->name('coupon');
    Route::get('coupon/create', CouponCreate::class)->name('coupon.create');
    Route::get('coupon/edit/{coupon}', CouponEdit::class)->name('coupon.edit');
});

// Route::middleware(['auth'])->group(function() {

// });
// Route::get('shop',Shop::class)->name('shop');
