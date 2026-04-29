@extends('backend.app')

@section('title', 'Product Details')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" rel="stylesheet">

    <style>
        .product-title {
            font-size: 24px;
            font-weight: 700;
            color: #212529;
        }

        .product-meta {
            color: #878a99;
            font-size: 14px;
        }

        .thumb-container {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 15px;
        }

        .main-image {
            width: 100%;
            max-height: 420px;
            object-fit: contain;
            border-radius: 12px;
            border: 1px solid #e9ebec;
        }

        .nav-slide-item {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid transparent;
            cursor: pointer;
            transition: all 0.3s;
        }

        .nav-slide-item.active,
        .nav-slide-item:hover {
            border-color: #4b93ff;
            transform: scale(1.05);
        }

        .info-box {
            background: #f9fafb;
            border: 1px solid #eef0f3;
            border-radius: 10px;
            padding: 14px 16px;
        }

        .info-label {
            font-size: 12px;
            color: #878a99;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-value {
            font-weight: 600;
            font-size: 15px;
            color: #212529;
        }

        .section-title {
            font-size: 15px;
            font-weight: 600;
            color: #495057;
            margin-bottom: 12px;
        }

        .rating-star {
            color: #f7b84b;
        }

        .review-card {
            border: 1px dashed #e9ebec;
            border-radius: 10px;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid mt-2">

        <!-- Page Title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Product Details</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Products</a></li>
                            <li class="breadcrumb-item active">Details</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body">

                        <div class="row gx-lg-5">

                            <!-- ================== IMAGE SECTION ================== -->
                            <div class="col-xl-4 col-md-6 mx-auto">
                                <div class="product-img-slider sticky-side-div">

                                    <!-- Main Image -->
                                    <div class="thumb-container mb-3">
                                        <img src="{{ $product->thumbnail ? asset($product->thumbnail) : asset('images/no-image.png') }}"
                                            class="main-image d-block" alt="{{ $product->name }}">
                                    </div>

                                    <!-- Gallery Thumbnails -->
                                    @php
                                        $gallery = is_array($product->gallery)
                                            ? $product->gallery
                                            : json_decode($product->gallery, true) ?? [];
                                    @endphp

                                    @if (!empty($gallery))
                                        <div class="swiper product-nav-slider">
                                            <div class="swiper-wrapper">

                                                <!-- Thumbnail as first -->
                                                @if ($product->thumbnail)
                                                    <div class="swiper-slide">
                                                        <img src="{{ asset($product->thumbnail) }}"
                                                            class="nav-slide-item active">
                                                    </div>
                                                @endif

                                                <!-- Gallery Images -->
                                                @foreach ($gallery as $img)
                                                    <div class="swiper-slide">
                                                        <img src="{{ asset($img) }}" class="nav-slide-item">
                                                    </div>
                                                @endforeach

                                            </div>
                                        </div>
                                    @endif

                                </div>
                            </div>

                            <!-- ================== PRODUCT INFO SECTION ================== -->
                            <div class="col-xl-8">
                                <div class="mt-xl-0 mt-4">

                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <h4 class="product-title">{{ $product->name }}</h4>
                                            <div class="product-meta mt-1">
                                                SKU: <strong>{{ $product->sku ?? 'N/A' }}</strong>
                                                • Brand: <strong>{{ $product->brand->name ?? 'N/A' }}</strong>
                                            </div>
                                        </div>

                                        <div>
                                            <a href="{{ route('admin.products.edit', $product->id) }}"
                                                class="btn btn-soft-primary btn-sm">
                                                <i class="ri-edit-line"></i> Edit Product
                                            </a>
                                        </div>
                                    </div>

                                    <!-- Rating -->
                                    {{-- <div class="d-flex align-items-center gap-2 mt-3">
                                        <div class="text-warning fs-18">
                                            ★★★★☆
                                        </div>
                                        <span class="text-muted">(4.5) • 2,450 Reviews</span>
                                    </div> --}}

                                    <!-- Price & Stock -->
                                    <div class="row mt-4 g-3">
                                        <div class="col-md-4">
                                            <div class="info-box text-center">
                                                <div class="info-label">Price</div>
                                                <h4 class="mb-0 text-success">
                                                    ${{ number_format($product->price) }}
                                                </h4>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="info-box text-center">
                                                <div class="info-label">Stock</div>
                                                <h4
                                                    class="mb-0 {{ $product->stock > 10 ? 'text-success' : 'text-danger' }}">
                                                    {{ $product->stock }} pcs
                                                </h4>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="info-box text-center">
                                                <div class="info-label">Status</div>
                                                <span class="badge bg-success fs-6 px-3 py-2">
                                                    {{ ucfirst($product->status ?? 'Active') }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Description -->
                                    <div class="mt-5">
                                        <div class="section-title">Description</div>
                                        <div class="text-muted lh-base">
                                            {!! $product->description !!}
                                        </div>
                                    </div>

                                    <!-- Features -->
                                    @if ($product->features)
                                        <div class="mt-4">
                                            <div class="section-title">Key Features</div>
                                            <ul class="list-unstyled">
                                                @foreach (explode("\n", $product->features) as $feature)
                                                    @if (trim($feature))
                                                        <li class="py-1">
                                                            <i class="ri-check-line text-success me-2"></i>
                                                            {{ trim($feature) }}
                                                        </li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif


                                    <!-- Variants -->
                                    @if ($product->variants && $product->variants->count() > 0)
                                        <div class="mt-5">
                                            <div class="section-title">Available Variants</div>
                                            <div class="table-responsive">
                                                <table class="table table-bordered align-middle">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>Size</th>
                                                            <th>Color</th>
                                                            <th>Price</th>
                                                            <th>Stock</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($product->variants as $variant)
                                                            <tr>
                                                                <td>
                                                                    <strong>{{ $variant->size ?? 'N/A' }}</strong>
                                                                </td>
                                                                <td>
                                                                    @if ($variant->color)
                                                                        <span class="badge px-3 py-2"
                                                                            style="background-color: {{ $variant->color_hex ?? '#6c757d' }}; color: white;">
                                                                            {{ $variant->color }}
                                                                        </span>
                                                                    @else
                                                                        <span class="text-muted">N/A</span>
                                                                    @endif
                                                                </td>
                                                                <td class="fw-semibold text-success">
                                                                    ${{ number_format($variant->price ?? $product->price, 2) }}
                                                                </td>
                                                                <td
                                                                    class="fw-semibold {{ $variant->stock > 0 ? 'text-success' : 'text-danger' }}">
                                                                    {{ $variant->stock }} pcs
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    @else
                                        <div class="mt-4 alert alert-info">
                                            <i class="ri-information-line"></i> No variants available for this product.
                                        </div>
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Info Row -->
        <div class="row g-4 mt-2">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="section-title">Product Information</div>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="info-box">
                                    <div class="info-label">Category</div>
                                    <div class="info-value">{{ $product->category->name ?? 'N/A' }}</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-box">
                                    <div class="info-label">Sub Category</div>
                                    <div class="info-value">{{ $product->subCategory->name ?? 'N/A' }}</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-box">
                                    <div class="info-label">Featured</div>
                                    <div class="info-value">
                                        @if ($product->is_featured)
                                            <span class="text-success">Yes</span>
                                        @else
                                            <span class="text-muted">No</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <div class="section-title">Gallery Images</div>
                        <div class="d-flex flex-wrap gap-2 justify-content-center">
                            @foreach ($gallery as $img)
                                <img src="{{ asset($img) }}" class="rounded"
                                    style="width: 70px; height: 70px; object-fit: cover;">
                            @endforeach

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Simple Swiper for gallery thumbnails
            new Swiper('.product-nav-slider', {
                slidesPerView: "auto",
                spaceBetween: 10,
                freeMode: true,
                watchSlidesProgress: true,
            });
        });
    </script>
@endpush
