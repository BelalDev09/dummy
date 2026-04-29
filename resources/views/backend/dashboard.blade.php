@extends('backend.app')

@section('title', 'Dashboard')
@push('styles')
    <style>
        .modern-card {
            border-radius: 16px;
            transition: all .25s ease-in-out;
            background: #fff;
        }

        .modern-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        }

        .icon-box {
            width: 45px;
            height: 45px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }
    </style>
@endpush
@section('content')

    <div class="container-fluid">

        {{-- GREETING --}}
        <div class="mb-4 d-flex align-items-center justify-content-between">

            <div>
                <h4 class="mb-1 fw-semibold">
                    @if (isset($greeting))
                        <i class="{{ $greeting['icon'] }} {{ $greeting['color'] }}"></i>
                        {{ $greeting['message'] }}, {{ auth()->user()->name }}
                    @endif
                </h4>
                <p class="text-muted mb-0">Welcome back to your dashboard</p>
            </div>

        </div>

        {{-- KPI GRID --}}
        <div class="row g-3">

            {{-- USERS --}}
            <div class="col-xl-3 col-md-6">
                <div class="card modern-card border-0 shadow-sm">
                    <div class="card-body">

                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="text-muted mb-1">Total Users</p>
                                <h3 class="counter fw-bold" data-target="{{ $stats['total_users'] }}">0</h3>
                            </div>

                            <div class="icon-box bg-primary-subtle text-primary">
                                <i class="ri-user-3-line"></i>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- TODAY USERS --}}
            <div class="col-xl-3 col-md-6">
                <div class="card modern-card border-0 shadow-sm">
                    <div class="card-body">

                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="text-muted mb-1">New Users Today</p>
                                <h3 class="counter fw-bold" data-target="{{ $stats['today_users'] }}">0</h3>
                            </div>

                            <div class="icon-box bg-success-subtle text-success">
                                <i class="ri-user-add-line"></i>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- PRODUCTS --}}
            <div class="col-xl-3 col-md-6">
                <div class="card modern-card border-0 shadow-sm">
                    <div class="card-body">

                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="text-muted mb-1">Products</p>
                                <h3 class="counter fw-bold" data-target="{{ $stats['total_products'] }}">0</h3>
                            </div>

                            <div class="icon-box bg-warning-subtle text-warning">
                                <i class="ri-shopping-bag-3-line"></i>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- ORDERS --}}
            <div class="col-xl-3 col-md-6">
                <div class="card modern-card border-0 shadow-sm">
                    <div class="card-body">

                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="text-muted mb-1">Total Orders</p>
                                <h3 class="counter fw-bold" data-target="{{ $stats['total_orders'] }}">0</h3>
                            </div>

                            <div class="icon-box bg-info-subtle text-info">
                                <i class="ri-shopping-cart-2-line"></i>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- ACTIVE ORDERS --}}
            <div class="col-xl-3 col-md-6">
                <div class="card modern-card border-0 shadow-sm">
                    <div class="card-body">

                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="text-muted mb-1">Active Orders</p>
                                <h3 class="counter fw-bold" data-target="{{ $stats['active_orders'] }}">0</h3>
                            </div>

                            <div class="icon-box bg-danger-subtle text-danger">
                                <i class="ri-truck-line"></i>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- COMPLETED --}}
            <div class="col-xl-3 col-md-6">
                <div class="card modern-card border-0 shadow-sm">
                    <div class="card-body">

                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="text-muted mb-1">Completed Orders</p>
                                <h3 class="counter fw-bold" data-target="{{ $stats['completed_orders'] }}">0</h3>
                            </div>

                            <div class="icon-box bg-success-subtle text-success">
                                <i class="ri-check-double-line"></i>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>

    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            const counters = document.querySelectorAll('.counter');

            counters.forEach(counter => {

                let target = +counter.dataset.target;
                let current = 0;

                let step = Math.max(1, Math.floor(target / 60));

                function animate() {
                    current += step;

                    if (current < target) {
                        counter.innerText = current;
                        requestAnimationFrame(animate);
                    } else {
                        counter.innerText = target;
                    }
                }

                animate();
            });

        });
    </script>
@endpush
