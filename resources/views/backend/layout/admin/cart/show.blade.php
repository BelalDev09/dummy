@extends('backend.app')

@section('title', 'Cart Detail')

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.10.5/sweetalert2.min.js"></script>

    @if (session('notify-success') ||
            session('notify-error') ||
            session('notify-warning') ||
            session('notify-message') ||
            session('notify-info'))
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
                @if (session('notify-success'))
                    showToast('success', @json(session('notify-success')));
                @endif

                @if (session('notify-error'))
                    showToast('error', @json(session('notify-error')));
                @endif

                @if (session('notify-warning'))
                    showToast('warning', @json(session('notify-warning')));
                @endif

                @if (session('notify-info'))
                    showToast('info', @json(session('notify-info')));
                @endif

            });
        </script>
    @endif

@endpush
@section('content')
    <div class="container-fluid">

        <!-- Header -->
        <div class="row align-items-center mb-3">
            <div class="col-md-6 d-flex align-items-center gap-2">
                <a href="{{ route('admin.carts.index') }}" class="btn btn-light btn-sm">
                    <i class="ri-arrow-left-line"></i>
                </a>
                <div>
                    <h4 class="mb-0 fw-semibold">Cart Detail</h4>
                    <p class="text-muted small mb-0">
                        ID #{{ str_pad($cart->id, 3, '0', STR_PAD_LEFT) }}
                    </p>
                </div>
            </div>
        </div>

        {{-- @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif --}}

        <div class="row g-4">

            <!-- LEFT -->
            <div class="col-xl-8">

                <!-- PRODUCT CARD -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white fw-semibold">
                        <i class="ri-shopping-bag-line me-2 text-primary"></i>
                        Product Information
                    </div>

                    <div class="card-body">

                        <!-- PRODUCT TOP -->
                        @php
                            $firstItem = $cart->items->first();
                            $cartQuantity = $cart->items->sum('quantity');
                            $cartTotal = $cart->items->sum(function ($item) {
                                return $item->price * $item->quantity;
                            });
                        @endphp

                        <div class="d-flex align-items-start gap-3 mb-4">
                            <img src="{{ $firstItem?->product?->thumbnail ? $firstItem->product->thumbnail : asset('Backend/assets/images/demos/default.png') }}"
                                class="rounded" width="90" height="90" style="object-fit:cover">
                            <div class="flex-grow-1">
                                <h5 class="fw-semibold mb-1">Cart #{{ $cart->id }}</h5>
                                <div class="text-muted small">
                                    {{ $cart->items->count() }} item{{ $cart->items->count() === 1 ? '' : 's' }}
                                    • {{ $cartQuantity }} unit{{ $cartQuantity === 1 ? '' : 's' }}
                                </div>
                            </div>
                        </div>

                        @if ($cart->items->isNotEmpty())
                            <div class="mb-4">
                                <h6 class="fw-semibold mb-2">Cart Items</h6>
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Product</th>
                                                <th>Variant</th>
                                                <th class="text-center">Qty</th>
                                                <th>Unit Price</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($cart->items as $item)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center gap-2">
                                                            <img src="{{ $item->product?->thumbnail ? $item->product->thumbnail : asset('Backend/assets/images/demos/default.png') }}"
                                                                class="rounded" width="50" height="50"
                                                                style="object-fit:cover">
                                                            <div>
                                                                <div class="fw-semibold small">
                                                                    {{ $item->product?->name ?? 'Product removed' }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="small">
                                                        {{ $item->variant?->name ?? 'Default' }}
                                                    </td>
                                                    <td class="text-center">{{ $item->quantity }}</td>
                                                    <td>${{ number_format($item->price, 2) }}</td>
                                                    <td class="text-success fw-semibold">
                                                        ${{ number_format($item->price * $item->quantity, 2) }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif

                        @php
                            $galleryItems = $cart->items->filter(fn($item) => !empty($item->product?->gallery));
                        @endphp

                        @if ($galleryItems->isNotEmpty())
                            <div class="mb-4">
                                <h6 class="fw-semibold mb-2">Product Galleries</h6>
                                @foreach ($galleryItems as $item)
                                    @php
                                        $images = is_array($item->product->gallery)
                                            ? $item->product->gallery
                                            : json_decode($item->product->gallery, true);
                                    @endphp

                                    @if (!empty($images))
                                        <div class="mb-3">
                                            <div class="fw-semibold small mb-2">{{ $item->product->name }}</div>
                                            <div class="d-flex flex-wrap gap-2">
                                                @foreach ($images as $img)
                                                    @php
                                                        if (filter_var($img, FILTER_VALIDATE_URL)) {
                                                            $src = $img;
                                                        } elseif (str_starts_with($img, '/')) {
                                                            $src = asset(ltrim($img, '/'));
                                                        } else {
                                                            $src = asset($img);
                                                        }
                                                    @endphp

                                                    <img src="{{ $src }}" class="rounded border" width="70"
                                                        height="70" style="object-fit:cover"
                                                        onerror="this.src='{{ asset('Backend/assets/images/demos/default.png') }}'">
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif

                        <!-- PRICE INFO -->
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="bg-light rounded p-3 text-center">
                                    <small class="text-muted">Cart Items</small>
                                    <div class="fw-bold">{{ $cart->items->count() }}</div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="bg-light rounded p-3 text-center">
                                    <small class="text-muted">Total Quantity</small>
                                    <div class="fw-bold">{{ $cartQuantity }}</div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="bg-success bg-opacity-10 rounded p-3 text-center">
                                    <small class="text-muted">Total</small>
                                    <div class="fw-bold text-success">
                                        ${{ number_format($cartTotal, 2) }}
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- STATUS -->
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white fw-semibold">
                        <i class="ri-settings-3-line me-2 text-primary"></i>
                        Status Update
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.carts.updateStatus', $cart) }}" class="d-flex gap-2">
                            @csrf
                            @method('PATCH')

                            <select name="status" class="form-select" style="max-width:200px">
                                @foreach (['active', 'ordered', 'removed'] as $s)
                                    <option value="{{ $s }}" @selected($cart->status == $s)>
                                        {{ ucfirst($s) }}
                                    </option>
                                @endforeach
                            </select>

                            <button class="btn btn-success">
                                <i class="ri-check-line me-1"></i> Update
                            </button>
                        </form>
                    </div>
                </div>

            </div>

            <!-- RIGHT -->
            <div class="col-xl-4">

                <!-- USER -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white fw-semibold">
                        <i class="ri-user-line me-2 text-primary"></i>User Info
                    </div>

                    <div class="card-body">

                        @if ($cart->user)
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <img src="{{ $cart->user?->avatar ? asset($cart->user->avatar) : asset('backend/assets/images/user-illustarator-1.png') }}"
                                    class="rounded-circle" width="34" height="34" style="object-fit:cover">

                                <div>
                                    <div class="fw-semibold">{{ $cart->user->name }}</div>
                                    <small class="text-muted">{{ $cart->user->email }}</small>
                                </div>
                            </div>

                            <hr>

                            <div class="d-flex justify-content-between small">
                                <span>User ID</span>
                                <strong>#{{ $cart->user->id }}</strong>
                            </div>

                            <div class="d-flex justify-content-between small mt-1">
                                <span>Joined</span>
                                <strong>{{ $cart->user->created_at->format('d M Y') }}</strong>
                            </div>
                        @else
                            <div class="text-center text-muted py-3">
                                Guest Checkout
                            </div>
                        @endif

                    </div>
                </div>

                <!-- TIMELINE -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white fw-semibold">
                        <i class="ri-time-line me-2 text-primary"></i>Timeline
                    </div>

                    <div class="card-body small">
                        <p>Added: {{ $cart->created_at->format('d M Y, h:i A') }}</p>

                        @if ($cart->status == 'ordered')
                            <p>Ordered: {{ $cart->updated_at->format('d M Y, h:i A') }}</p>
                        @endif

                        @if ($cart->status == 'removed')
                            <p class="text-danger">Removed</p>
                        @endif
                    </div>
                </div>

                <!-- DELETE -->
                {{-- <div class="card border-danger shadow-sm">
                    <div class="card-header text-danger fw-semibold">
                        Danger Zone
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.carts.destroy', $cart) }}"
                            onsubmit="return confirm('Are you sure?')">
                            @csrf
                            @method('DELETE')

                            <button class="btn btn-danger w-100">
                                Delete Cart
                            </button>
                        </form>
                    </div>
                </div> --}}

            </div>

        </div>
    </div>
@endsection


@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.10.5/sweetalert2.min.js"></script>

    @if (session('notify-success') ||
            session('notify-error') ||
            session('notify-warning') ||
            session('notify-message') ||
            session('notify-info'))
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
                @if (session('notify-success'))
                    showToast('success', @json(session('notify-success')));
                @endif

                @if (session('notify-error'))
                    showToast('error', @json(session('notify-error')));
                @endif

                @if (session('notify-warning'))
                    showToast('warning', @json(session('notify-warning')));
                @endif

                @if (session('notify-info'))
                    showToast('info', @json(session('notify-info')));
                @endif

            });
        </script>
    @endif

@endpush
