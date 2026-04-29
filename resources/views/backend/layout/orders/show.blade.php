@extends('backend.app')

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- Page Title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Order Details</h4>

                        <div class="page-title-right">
                            <a href="{{ route('admin.orders.index') }}" class="btn btn-soft-primary btn-sm">
                                <i class="ri-arrow-left-line"></i> Back
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Info Cards -->
            <div class="row">

                <!-- Order Info -->
                <div class="col-xl-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Order Info</h5>
                        </div>

                        <div class="card-body">

                            <p><strong>Order No:</strong>
                                <span class="badge bg-dark">#{{ $order->order_number }}</span>
                            </p>

                            <p><strong>Status:</strong>
                                <span class="badge bg-info">{{ $order->order_status }}</span>
                            </p>

                            <p><strong>Payment:</strong>
                                <span class="badge bg-success">{{ $order->payment_status }}</span>
                            </p>

                            <p><strong>Subtotal:</strong> $ {{ number_format($order->sub_total, 2) }}</p>
                            <p><strong>Discount:</strong> $ {{ number_format($order->discount_amount, 2) }}</p>

                            <hr>

                            <h5>Total:
                                <span class="text-success">
                                    $ {{ number_format($order->total_amount, 2) }}
                                </span>
                            </h5>

                        </div>
                    </div>
                </div>

                <!-- Customer Info -->
                <div class="col-xl-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Customer Info</h5>
                        </div>

                        <div class="card-body">

                            <p><strong>Name:</strong> {{ $order->user->name ?? 'N/A' }}</p>
                            <p><strong>Email:</strong> {{ $order->user->email ?? 'N/A' }}</p>

                        </div>
                    </div>
                </div>

                <!-- Address Info -->
                <div class="col-xl-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Address</h5>
                        </div>

                        <div class="card-body">

                            <p><strong>Shipping:</strong><br>
                                {{ $order->shipping_address }}
                            </p>

                            <p><strong>Billing:</strong><br>
                                {{ $order->billing_address }}
                            </p>

                        </div>
                    </div>
                </div>

            </div>

            <!-- Notes -->
            <div class="row mt-3">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Notes</h5>
                        </div>

                        <div class="card-body">
                            {{ $order->notes ?? 'No notes available' }}
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
