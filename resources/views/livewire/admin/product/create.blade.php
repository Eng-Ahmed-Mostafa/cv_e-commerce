<!-- main-content-wrap -->
<div class="main-content-wrap">
    <div class="flex items-center flex-wrap justify-between gap20 mb-27">
        <h3>Add Product</h3>
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
                <a href="{{ route('admin.product') }}">
                    <div class="text-tiny">Products</div>
                </a>
            </li>
            <li>
                <i class="icon-chevron-right"></i>
            </li>
            <li>
                <div class="text-tiny">Add product</div>
            </li>
        </ul>
    </div>
    <!-- form-add-product -->
    <form class="tf-section-2 form-add-product" method="POST" enctype="multipart/form-data" wire:submit.prevent="storeProduct()">
        @csrf
        <input type="hidden" name="_token" value="8LNRTO4LPXHvbK2vgRcXqMeLgqtqNGjzWSNru7Xx" autocomplete="off">
        <div class="wg-box">
            <fieldset class="name">
                <div class="body-title mb-10">Product name <span class="tf-color-1">*</span>
                </div>
                <input class="mb-10" type="text" placeholder="Enter product name" name="name" tabindex="0"
                    value="" aria-required="true" required="" wire:model.lazy="name">
                <div class="text-tiny">Do not exceed 100 characters when entering the
                    product name.</div>
                @error('name')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
            </fieldset>

            <fieldset class="name">
                <div class="body-title mb-10">Slug <span class="tf-color-1">*</span></div>
                <input class="mb-10" type="text" placeholder="Enter product slug" name="slug" tabindex="0"
                    value="" aria-required="true" required="" wire:model="slug">
                <div class="text-tiny">Do not exceed 100 characters when entering the
                    product name.</div>
                @error('slug')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
            </fieldset>

            <div class="gap22 cols">
                <fieldset class="category">
                    <div class="body-title mb-10">Category <span class="tf-color-1">*</span>
                    </div>
                    <div class="select">
                        <select class="" name="category_id" wire:model="category_id">
                            <option>Choose category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach

                        </select>
                    </div>
                    @error('category_id')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                </fieldset>
                <fieldset class="brand">
                    <div class="body-title mb-10">Brand <span class="tf-color-1">*</span>
                    </div>
                    <div class="select">
                        <select class="" name="brand_id" wire:model="brand_id">
                            <option>Choose Brand</option>
                            @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('brand_id')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                </fieldset>
            </div>

            <fieldset class="shortdescription">
                <div class="body-title mb-10">Short Description <span class="tf-color-1">*</span></div>
                <textarea class="mb-10 ht-150" name="short_description" placeholder="Short Description" tabindex="0"
                    aria-required="true" required="" wire:model="short_description"></textarea>
                <div class="text-tiny">Do not exceed 100 characters when entering the
                    product name.</div>
                @error('short_description')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
            </fieldset>

            <fieldset class="description">
                <div class="body-title mb-10">Description <span class="tf-color-1">*</span>
                </div>
                <textarea class="mb-10" name="description" placeholder="Description" tabindex="0" aria-required="true" required=""
                    wire:model="description"></textarea>
                <div class="text-tiny">Do not exceed 100 characters when entering the
                    product name.</div>
                @error('description')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
            </fieldset>
        </div>
        <div class="wg-box">
            <fieldset>
                <div class="body-title">Upload images <span class="tf-color-1">*</span>
                </div>
                <div class="upload-image flex-grow">
                    @if ($image)
                        <div class="item" id="imgpreview">
                            <img src="{{ $image->temporaryUrl() }}" class="effect8" alt="">
                        </div>
                    @endif
                    <div id="upload-file" class="item up-load">
                        <label class="uploadfile" for="myFile">
                            <span class="icon">
                                <i class="icon-upload-cloud"></i>
                            </span>
                            <span class="body-text">Drop your images here or select <span class="tf-color">click to
                                    browse</span></span>
                            <input type="file" id="myFile" name="image" accept="image/*"
                                wire:model.live="image">
                        </label>
                    </div>
                </div>
                @error('image')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
            </fieldset>

            <fieldset>
                <div class="body-title mb-10">Upload Gallery Images</div>
                <div class="upload-image mb-16">
                    @if (!empty($images))
                        @foreach ($images as $image)
                            <div class="item">
                                <img src="{{ $image->temporaryUrl() }}" class="effect8" alt="">
                            </div>
                        @endforeach
                    @else
                        @foreach ($existingImages as $image)
                            <div class="item">
                                <img src="{{ asset('storage/' . $image) }}" class="effect8" alt="">
                            </div>
                        @endforeach
                    @endif
                    <div id="galUpload" class="item up-load">
                        <label class="uploadfile" for="gFile">
                            <span class="icon">
                                <i class="icon-upload-cloud"></i>
                            </span>
                            <span class="text-tiny">Drop your images here or select <span class="tf-color">click to
                                    browse</span></span>
                            <input type="file" id="gFile" name="images[]" accept="image/*" multiple
                                wire:model.live="images">
                        </label>
                    </div>
                </div>
                @error('images')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
            </fieldset>

            <div class="cols gap22">
                <fieldset class="name">
                    <div class="body-title mb-10">Regular Price <span class="tf-color-1">*</span></div>
                    <input class="mb-10" type="text" placeholder="Enter regular price" name="regular_price"
                        tabindex="0" value="" aria-required="true" required="" wire:model="price">
                    @error('price')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                </fieldset>
                <fieldset class="name">
                    <div class="body-title mb-10">Sale Price <span class="tf-color-1">*</span></div>
                    <input class="mb-10" type="text" placeholder="Enter sale price" name="sale_price"
                        tabindex="0" value="" aria-required="true" required="" wire:model="sale_price">
                    @error('sale_price')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                </fieldset>

            </div>


            <div class="cols gap22">
                <fieldset class="name">
                    <div class="body-title mb-10">SKU <span class="tf-color-1">*</span>
                    </div>
                    <input class="mb-10" type="text" placeholder="Enter SKU" name="sku" tabindex="0"
                        value="" aria-required="true" required="" wire:model="sku">
                    @error('sku')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                </fieldset>
                <fieldset class="name">
                    <div class="body-title mb-10">Quantity <span class="tf-color-1">*</span>
                    </div>
                    <input class="mb-10" type="text" placeholder="Enter quantity" name="quantity"
                        tabindex="0" value="" aria-required="true" required="" wire:model="quantity">
                    @error('quantity')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                </fieldset>
            </div>

            <div class="cols gap22">
                <fieldset class="name">
                    <div class="body-title mb-10">Stock</div>
                    <div class="select mb-10">
                        <select class="" wire:model="stock">
                            <option value="">-- Select Stock Status --</option>
                            <option value="instock">In Stock</option>
                            <option value="outstock">Out of Stock</option>
                        </select>
                    </div>
                    @error('stock')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                </fieldset>
                <fieldset class="name">
                    <div class="body-title mb-10">Featured</div>
                    <div class="select mb-10">
                        <select class="" name="feature" wire:model="feature">
                            <option value="">-- Select Feature --</option>
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                    </div>
                    @error('feature')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                </fieldset>
            </div>
            <div class="cols gap10">
                <button class="tf-button w-full" type="submit">Add product</button>
            </div>
        </div>
    </form>
    <!-- /form-add-product -->
</div>
<!-- /main-content-wrap -->
