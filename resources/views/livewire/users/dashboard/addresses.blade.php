<main class="pt-90">
    <div class="mb-4 pb-4"></div>
    <section class="my-account container">
        <h2 class="page-title">Addresses</h2>
        <div class="row">
            <x-users.dashborad></x-users.dashborad>
            <div class="col-lg-9">
                @if($order)
                    <div class="page-content my-account__address">
                        <div class="row">
                            <div class="col-6">
                                <p class="notice">The following addresses will be used on the checkout page by default.</p>
                            </div>
                            <div class="col-6 text-right">
                                <a href="#" class="btn btn-sm btn-info">Add New</a>
                            </div>
                        </div>
                        <div class="my-account__address-list row">
                            <h5>Shipping Address</h5>

                            <div class="my-account__address-item col-md-6">
                                <div class="my-account__address-item__title">
                                    <h5>{{ $order->detail->full_name }} <i class="fa fa-check-circle text-success"></i></h5>
                                    <a href="#">Edit</a>
                                </div>
                                <div class="my-account__address-item__detail">
                                    <p>Flat No - 13, R. K. Wing - B</p>
                                    <p>{{ $order->detail->city ?? "cairo"}}</p>
                                    <p>{{ $order->detail->town }} </p>
                                    <p>{{ $order->detail->area }}</p>
                                    <p>{{ $order->detail->pincode }}</p>
                                    <br>
                                    <p>Mobile : {{ $order->detail->phone }}</p>
                                </div>
                            </div>
                            <hr>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
</main>
