<main class="pt-90" style="padding-top: 0px;">
    <div class="mb-4 pb-4"></div>
    <section class="my-account container">
        <h2 class="page-title">Orders</h2>
        <div class="row">
            <x-users.dashborad></x-users.dashborad>

            <div class="col-lg-9">
                <div class="wg-table table-all-user">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th style="width: 80px">OrderNo</th>
                                    <th>Name</th>
                                    <th class="text-center">Phone</th>
                                    <th class="text-center">Subtotal</th>
                                    <th class="text-center">Tax</th>
                                    <th class="text-center">Total</th>

                                    <th class="text-center">Status</th>
                                    <th class="text-center">Order Date</th>
                                    <th class="text-center">Items</th>
                                    <th class="text-center">Delivered On</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $order)
                                    <tr>
                                        <td class="text-center">{{ $order->id }}</td>
                                        <td class="text-center">{{ $order->detail->full_name }}</td>
                                        <td class="text-center">{{ $order->detail->phone }}</td>
                                        <td class="text-center">${{ $order->total }}</td>
                                        <td class="text-center">${{ $order->tax }}</td>
                                        <td class="text-center">${{ $order->total_amount }}</td>

                                        <td class="text-center">
                                            <span
                                                class="badge 
    {{ $order->status == 'pending'
        ? 'bg-info'
        : ($order->status == 'ordered'
            ? 'bg-success'
            : ($order->status == 'cancelled'
                ? 'bg-danger'
                : 'bg-primary')) }}">
                                                {{ $order->status }}
                                            </span>

                                        </td>
                                        <td class="text-center">{{ $order->ordered_date }}</td>
                                        <td class="text-center">{{ count($order->order_items) }}</td>
                                        <td>{{ $order->delivered_date ?? '' }}</td>
                                        <td class="text-center">
                                            <a href="account-orders-details.html">
                                                <div class="list-icon-function view-icon">
                                                    <div class="item eye">
                                                        <i class="fa fa-eye"></i>
                                                    </div>
                                                </div>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="divider"></div>
                <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">

                </div>
            </div>

        </div>
    </section>
</main>
