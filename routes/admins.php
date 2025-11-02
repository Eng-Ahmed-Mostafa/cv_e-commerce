<?php

use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\User\Setting;
use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\Coupon\Display as CouponDisplay;
use App\Livewire\Admin\Coupon\Create as CouponCreate;
use App\Livewire\Admin\Coupon\Edit as CouponEdit;
use App\Livewire\Admin\Brand\Edit as BrandEdit;
use App\Livewire\Admin\Brand\Create as BrandCreate;
use App\Livewire\Admin\Product\Edit as ProductEdit;
use App\Livewire\Admin\Brand\Display as BrandDisplay;
use App\Livewire\Admin\Category\Edit as CategoryEdit;
use App\Livewire\Admin\Order\Display as OrderDisplay;
use App\Livewire\Admin\Product\Create as ProductCreate;
use App\Livewire\Admin\Category\Create as CategoryCreate;
use App\Livewire\Admin\Product\Display as ProductDisplay;
use App\Livewire\Admin\Category\Display as CategoryDisplay;
use App\Livewire\Admin\User\Display as UserDisplay;



Route::middleware(['auth','role:admin'])->prefix('admin')->name('admin.')->group(function() {

    Route::get('dashboard',Dashboard::class)->name('dashboard');

    // Users
    Route::get('user',UserDisplay::class)->name('user');
    Route::get('setting',Setting::class)->name('setting');

    // Categories
    Route::get('category',CategoryDisplay::class)->name('category');
    Route::get('category/create',CategoryCreate::class)->name('category.create');
    Route::get('category/edit/{category}',CategoryEdit::class)->name('category.edit');

    // Brands
    Route::get('brand',BrandDisplay::class)->name('brand');
    Route::get('brand/create',BrandCreate::class)->name('brand.create');
    Route::get('brand/edit/{brand}',BrandEdit::class)->name('brand.edit');

    // Brands
    Route::get('product',ProductDisplay::class)->name('product');
    Route::get('product/create',ProductCreate::class)->name('product.create');
    Route::get('product/edit/{product}',ProductEdit::class)->name('product.edit');

    // Orders
    Route::get('order',OrderDisplay::class)->name('order');

    // Coupons
    Route::get('coupon',CouponDisplay::class)->name('coupon');
    Route::get('coupon/create',CouponCreate::class)->name('coupon.create');
    Route::get('coupon/edit/{coupon}',CouponEdit::class)->name('coupon.edit');
});

// Route::middleware(['auth'])->group(function() {
    
// });
// Route::get('shop',Shop::class)->name('shop');