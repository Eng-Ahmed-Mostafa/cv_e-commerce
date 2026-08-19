<div class="main-content-wrap">

    {{-- Page Header --}}
    <div class="flex items-center flex-wrap justify-between gap20 mb-27">
        <h3>Order Details</h3>

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
                <a href="{{ route('admin.orders') }}">
                    <div class="text-tiny">Orders</div>
                </a>
            </li>

            <li>
                <i class="icon-chevron-right"></i>
            </li>

            <li>
                <div class="text-tiny">Order Details</div>
            </li>
        </ul>
    </div>

    {{-- Ordered Items --}}
    <div class="wg-box">

        <div class="flex items-center justify-between gap10 flex-wrap mb-20">

            <div class="wg-filter flex-grow">
                <h5>Ordered Items</h5>
            </div>

            <a class="tf-button style-1 w208" href="{{ route('admin.orders') }}">
                Back
            </a>

        </div>

        <div class="table-responsive">

            <table class="table table-striped table-bordered">

                <thead>
                    <tr>
                        <th>Name</th>
                        <th class="text-center">Price</th>
                        <th class="text-center">Quantity</th>
                        <th class="text-center">SKU</th>
                        <th class="text-center">Category</th>
                        <th class="text-center">Brand</th>
                        <th class="text-center">Options</th>
                        <th class="text-center">Return Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($order->order_items as $item)
                        @php
                            $product = $item->product;
                        @endphp

                        <tr>

                            {{-- Product --}}
                            <td class="pname">

                                <div class="image">

                                    @if ($product && $product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                            class="image">
                                    @else
                                        <img src="{{ asset('images/no-image.png') }}" alt="No Image" class="image">
                                    @endif

                                </div>

                                <div class="name">

                                    <a href="#" class="body-title-2">
                                        {{ $product->name ?? 'Product Deleted' }}
                                    </a>

                                </div>

                            </td>

                            {{-- Price --}}
                            <td class="text-center">
                                ${{ number_format($item->price ?? 0, 2) }}
                            </td>

                            {{-- Quantity --}}
                            <td class="text-center">
                                {{ $item->quantity ?? 0 }}
                            </td>

                            {{-- SKU --}}
                            <td class="text-center">
                                {{ $product->SKU ?? '-' }}
                            </td>

                            {{-- Category --}}
                            <td class="text-center">

                                @if ($product && $product->category)
                                    {{ $product->category->name }}
                                @else
                                    -
                                @endif

                            </td>


                            {{-- Brand --}}
                            <td class="text-center">

                                @if ($product && $product->brand)
                                    {{ $product->brand->name }}
                                @else
                                    -
                                @endif

                            </td>


                            {{-- Options --}}
                            <td class="text-center">
                                {{ $item->options ?? '-' }}
                            </td>

                            {{-- Return Status --}}
                            <td class="text-center">
                                {{ $item->return_status ?? '-' }}
                            </td>


                            {{-- Action --}}
                            <td class="text-center">

                                <div class="list-icon-function view-icon">

                                    <div class="item eye">
                                        <i class="icon-eye"></i>
                                    </div>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="9" class="text-center">
                                No products found for this order.
                            </td>
                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

    </div>


    {{-- Shipping Address --}}
    <div class="wg-box mt-5">

        <h5>Shipping Address</h5>

        <div class="my-account__address-item col-md-6">

            <div class="my-account__address-item__detail">

                @if ($order->detail)
                    <p>
                        <strong>Name:</strong>
                        {{ $order->detail->full_name ?? '-' }}
                    </p>

                    <p>
                        <strong>Address:</strong>
                        {{ $order->detail->town ?? '-' }}
                    </p>

                    <p>
                        <strong>City:</strong>
                        {{ $order->detail->city ?? '-' }}
                    </p>

                    <p>
                        <strong>State:</strong>
                        {{ $order->detail->state ?? '-' }}
                    </p>

                    <p>
                        <strong>Country:</strong>
                        {{ $order->detail->area ?? '-' }}
                    </p>

                    <p>
                        <strong>Postal Code:</strong>
                        {{ $order->detail->pincode ?? '-' }}
                    </p>

                    <br>

                    <p>
                        <strong>Mobile:</strong>
                        {{ $order->detail->phone ?? '-' }}
                    </p>
                @else
                    <p>No shipping address found.</p>
                @endif

            </div>

        </div>

    </div>


    {{-- Transactions --}}
    <div class="wg-box mt-5">

        <h5>Transactions</h5>

        <div class="table-responsive">

            <table class="table table-striped table-bordered table-transaction">

                <tbody>

                    {{-- Row 1 --}}
                    <tr>

                        <th>Subtotal</th>

                        <td>
                            ${{ number_format($order->total ?? 0, 2) }}
                        </td>


                        <th>Tax</th>

                        <td>
                            ${{ number_format($order->tax ?? 0, 2) }}
                        </td>


                        <th>Discount</th>

                        <td>
                            ${{ number_format($order->discount ?? 0, 2) }}
                        </td>

                    </tr>


                    {{-- Row 2 --}}
                    <tr>

                        <th>Total</th>

                        <td>
                            <strong>
                                ${{ number_format($order->total_amount ?? 0, 2) }}
                            </strong>
                        </td>


                        <th>Payment Mode</th>

                        <td>
                            {{ $order->payment_mode ?? '-' }}
                        </td>


                        <th>Status</th>

                        <td>
                            {{ $order->status ?? '-' }}
                        </td>

                    </tr>


                    {{-- Row 3 --}}
                    <tr>

                        <th>Order Date</th>

                        <td>
                            {{ $order->ordered_date ?? '-' }}
                        </td>


                        <th>Delivered Date</th>

                        <td>
                            {{ $order->delivered_date ?? '-' }}
                        </td>


                        <th>Canceled Date</th>

                        <td>
                            {{ $order->canceled_date ?? '-' }}
                        </td>

                    </tr>

                </tbody>

            </table>

        </div>

    </div>

</div>
