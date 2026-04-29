@extends('backend.app')

@section('title', 'Cart Management')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css" />
    {{-- SweetAlert2 CSS --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.10.5/sweetalert2.min.css">
    <style>
        .dropify-wrapper {
            height: auto !important;
        }
    </style>
    <style>
        .swal2-show-custom {
            animation: slideInRight 0.35s ease-out;
        }

        .swal2-hide-custom {
            animation: fadeOut 0.2s ease-in;
        }

        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes fadeOut {
            from {
                opacity: 1;
            }

            to {
                opacity: 0;
            }
        }
    </style>
@endpush
@section('content')
    <div class="container-fluid">

        <!-- Page Title -->
        <div class="row align-items-center mb-3">
            <div class="col-md-6">
                <h4 class="mb-0 fw-semibold">Cart Management</h4>
                <p class="text-muted mb-0">Monitor all user cart activities in real-time</p>
            </div>
            <div class="col-md-6 text-md-end mt-2 mt-md-0">
                <a href="{{ route('admin.carts.export', request()->only('status', 'search')) }}"
                    class="btn btn-success btn-sm">
                    <i class="ri-download-line me-1"></i> Export CSV
                </a>
            </div>
        </div>

        <!-- Flash -->
        {{-- @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif --}}

        <!-- Stats -->
        <div class="row g-3 mb-3">
            <div class="col-xl-3 col-md-6">
                <div class="card card-animate shadow-sm">
                    <div class="card-body d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div
                                class="avatar-sm bg-primary-subtle rounded-circle d-flex align-items-center justify-content-center">
                                <i class="ri-shopping-cart-2-line text-primary fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <p class="text-muted mb-1">Active Carts</p>
                            <h4 class="mb-0">{{ number_format($stats['total_active']) }}</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card card-animate shadow-sm">
                    <div class="card-body d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div
                                class="avatar-sm bg-success-subtle rounded-circle d-flex align-items-center justify-content-center">
                                <i class="ri-money-dollar-circle-line text-success fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <p class="text-muted mb-1">Total Value</p>
                            <h4 class="mb-0">${{ number_format($stats['total_value']) }}</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card card-animate shadow-sm">
                    <div class="card-body d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div
                                class="avatar-sm bg-warning-subtle rounded-circle d-flex align-items-center justify-content-center">
                                <i class="ri-group-line text-warning fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <p class="text-muted mb-1">Users</p>
                            <h4 class="mb-0">{{ number_format($stats['unique_users']) }}</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card card-animate shadow-sm">
                    <div class="card-body d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div
                                class="avatar-sm bg-danger-subtle rounded-circle d-flex align-items-center justify-content-center">
                                <i class="ri-check-double-line text-danger fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <p class="text-muted mb-1">Ordered Today</p>
                            <h4 class="mb-0">{{ number_format($stats['ordered_today']) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter -->
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.carts.index') }}" class="row g-2 align-items-center">
                    <div class="col-md-3">
                        <input type="text" name="search" value="{{ request('search') }}"
                            class="form-control form-control-sm" placeholder="Search user/product">
                    </div>

                    <div class="col-md-2">
                        <select name="status" class="form-select form-select-sm">
                            <option value="">All Status</option>
                            @foreach (['active', 'ordered', 'removed'] as $s)
                                <option value="{{ $s }}" @selected(request('status') == $s)>{{ ucfirst($s) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <button class="btn btn-primary btn-sm">
                            <i class="ri-search-line me-1"></i> Filter
                        </button>

                        @if (request()->hasAny(['search', 'status']))
                            <a href="{{ route('admin.carts.index') }}" class="btn btn-light btn-sm">
                                Reset
                            </a>
                        @endif
                    </div>

                    <div class="col-md-3 text-md-end">
                        <span class="text-muted small">{{ $carts->total() }} results</span>
                    </div>
                </form>
            </div>
        </div>

        <!-- Table -->
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>User</th>
                                <th>Product</th>
                                <th class="text-center">Qty</th>
                                <th>Price</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Added</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($carts as $cart)
                                @php
                                    $item = $cart->items->first();
                                    $itemCount = $cart->items->sum('quantity');
                                    $totalAmount = $cart->items->sum(function ($item) {
                                        return $item->price * $item->quantity;
                                    });
                                @endphp
                                <tr>
                                    <td>{{ $cart->id }}</td>

                                    <!-- USER WITH IMAGE -->
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <img src="{{ $cart->user?->avatar ? asset($cart->user->avatar) : asset('Backend/assets/images/user-illustarator-1.png') }}"
                                                class="rounded-circle" width="34" height="34"
                                                style="object-fit:cover">
                                            <div class="fw-semibold small">
                                                {{ $cart->user?->name ?? 'Guest' }}
                                            </div>
                                        </div>
                                    </td>

                                    <!-- PRODUCT WITH IMAGE -->
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <img src="{{ $item && $item->product?->thumbnail ? $item->product->thumbnail : asset('Backend/assets/images/demos/default.png') }}"
                                                class="rounded" width="38" height="38" style="object-fit:cover">

                                            <div>
                                                <div class="fw-semibold small">
                                                    {{ $item?->product?->name ?? 'No products' }}
                                                </div>

                                                <div class="text-muted small">
                                                    {{ $item?->product?->brand->name ?? '' }}
                                                    {{ $item?->product?->category->name ?? '' }}
                                                    @if ($cart->items->count() > 1)
                                                        • {{ $cart->items->count() }} items
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="text-center">{{ $itemCount }}</td>

                                    <td>
                                        @if ($cart->items->count() === 1)
                                            ${{ number_format($item?->price ?? 0, 2) }}
                                        @else
                                            Multiple
                                        @endif
                                    </td>

                                    <td class="text-success fw-semibold">
                                        ${{ number_format($totalAmount, 2) }}
                                    </td>

                                    <td>
                                        <span
                                            class="badge bg-{{ $cart->status == 'active' ? 'success' : ($cart->status == 'ordered' ? 'primary' : 'danger') }}">
                                            {{ ucfirst($cart->status) }}
                                        </span>
                                    </td>

                                    <td>{{ $cart->created_at->diffForHumans() }}</td>

                                    <td class="text-end">
                                        <a href="{{ route('admin.carts.show', $cart) }}" class="btn btn-sm btn-light">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4 text-muted">No data found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if ($carts->hasPages())
                <div class="card-footer d-flex justify-content-between">
                    <div class="text-muted small">
                        Showing {{ $carts->firstItem() }} - {{ $carts->lastItem() }} of {{ $carts->total() }}
                    </div>
                    {{ $carts->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>

    </div>
@endsection


@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.10.5/sweetalert2.min.js"></script>

    @if (session('success') || session('error') || session('warning') || session('message') || session('info'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {

                const toastConfig = {
                    success: {
                        background: '#ecfdf5',
                        color: '#065f46',
                        border: '#10b981',
                        iconColor: '#10b981'
                    },
                    error: {
                        background: '#fef2f2',
                        color: '#991b1b',
                        border: '#ef4444',
                        iconColor: '#ef4444'
                    },
                    warning: {
                        background: '#fffbeb',
                        color: '#92400e',
                        border: '#f59e0b',
                        iconColor: '#f59e0b'
                    },
                    info: {
                        background: '#eff6ff',
                        color: '#1e3a8a',
                        border: '#3b82f6',
                        iconColor: '#3b82f6'
                    }
                };

                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    showClass: {
                        popup: 'swal2-show-custom'
                    },
                    hideClass: {
                        popup: 'swal2-hide-custom'
                    },
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer);
                        toast.addEventListener('mouseleave', Swal.resumeTimer);
                    }
                });

                window.showToast = function(type, message) {

                    const config = toastConfig[type] || toastConfig.info;

                    Toast.fire({
                        icon: type,
                        title: message,
                        background: config.background,
                        color: config.color,
                        didOpen: (toast) => {
                            toast.style.borderLeft = `6px solid ${config.border}`;
                            toast.style.borderRadius = '10px';
                            toast.style.boxShadow = '0 10px 25px rgba(0,0,0,0.08)';

                            const icon = toast.querySelector('.swal2-icon');
                            if (icon) {
                                icon.style.color = config.iconColor;
                            }
                        }
                    });
                };

                // Laravel session auto trigger
                @if (session('success'))
                    showToast('success', @json(session('success')));
                @endif

                @if (session('error'))
                    showToast('error', @json(session('error')));
                @endif

                @if (session('warning'))
                    showToast('warning', @json(session('warning')));
                @endif

                @if (session('info'))
                    showToast('info', @json(session('info')));
                @endif

            });
        </script>
    @endif

@endpush
