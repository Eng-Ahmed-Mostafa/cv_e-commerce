<div class="main-content">
                        <div class="main-content-inner">
                            <div class="main-content-wrap">
                                <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                                    <h3>Coupon infomation</h3>
                                    <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                                        <li>
                                            <a href="{{ route('admin.dashboard') }}">
                                                <div class="text-tiny">Dashboard</div>
                                            </a>
                                        </li>
                                        <li>
                                            <i class="icon-chevron-right"></i>
                                        </li>
                                        <li>
                                            <a href="{{ route('admin.coupon') }}">
                                                <div class="text-tiny">Coupons</div>
                                            </a>
                                        </li>
                                        <li>
                                            <i class="icon-chevron-right"></i>
                                        </li>
                                        <li>
                                            <div class="text-tiny">New Coupon</div>
                                        </li>
                                    </ul>
                                </div>
                                <div class="wg-box">
                                    <form class="form-new-product form-style-1" method="POST" action="#" wire:submit.prevent="save">
                                        @csrf
                                        <fieldset class="name">
                                            <div class="body-title">Coupon Code <span class="tf-color-1">*</span></div>
                                            <input class="flex-grow" type="text" placeholder="Coupon Code" name="code"
                                                tabindex="0" value="" aria-required="true" required="" wire:model="code">

                                            @error('code')
                                                <span class="text-danger">{{ $message }}</span>
                                                
                                            @enderror
                                        </fieldset>
                                        <fieldset class="category">
                                            <div class="body-title">Coupon Type</div>
                                            <div class="select flex-grow">
                                                <select class="" name="type" wire:model="type">
                                                    <option value="">Select</option>
                                                    <option value="fixed">Fixed</option>
                                                    <option value="percentage">Percent</option>
                                                </select>
                                            </div>
                                            @error('type')
                                                <span class="text-danger">{{ $message }}</span>
                                                
                                            @enderror
                                        </fieldset>
                                        <fieldset class="name">
                                            <div class="body-title">Value <span class="tf-color-1">*</span></div>
                                            <input class="flex-grow" type="text" placeholder="Coupon Value" name="value"
                                                tabindex="0" value="" aria-required="true" required="" wire:model="value">
                                            @error('value')
                                                <span class="text-danger">{{ $message }}</span>
                                                
                                            @enderror
                                        </fieldset>
                                        <fieldset class="name">
                                            <div class="body-title">Cart Value <span class="tf-color-1">*</span></div>
                                            <input class="flex-grow" type="text" placeholder="Cart Value"
                                                name="cart_value" tabindex="0" value="" aria-required="true"
                                                required="" wire:model="cart_value">
                                            @error('cart_value')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </fieldset>
                                        <fieldset class="name">
                                            <div class="body-title">Expiry Date <span class="tf-color-1">*</span></div>
                                            <input class="flex-grow" type="date" placeholder="Expiry Date"
                                                name="expiry_date" tabindex="0" value="" aria-required="true"
                                                required="" wire:model="expiry_date">
                                            @error('expire_date')
                                                <span class="text-danger">{{ $message }}</span>
                                                
                                            @enderror
                                        </fieldset>

                                        <div class="bot">
                                            <div></div>
                                            <button class="tf-button w208" type="submit">Save</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="bottom-page">
                            <div class="body-text">Copyright © 2024 SurfsideMedia</div>
                        </div>
                    </div>