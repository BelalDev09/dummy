@extends('backend.app')

@section('title', 'Edit Product — ' . $product->name)

@push('styles')
    <style>
        .thumb-dropzone {
            border: 2px dashed #d3d3d3;
            transition: border-color .2s;
        }

        .thumb-dropzone:hover {
            border-color: #405189;
        }

        /* Gallery Items */
        .gallery-item {
            position: relative;
            width: 80px;
            height: 80px;
            flex-shrink: 0;
        }

        .gallery-item img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #dee2e6;
            display: block;
            transition: opacity .2s;
        }

        .gallery-item.marked-delete img {
            opacity: .35;
            border-color: #f06548;
        }

        .gallery-item .remove-img-btn {
            position: absolute;
            top: -7px;
            right: -7px;
            background: #f06548;
            border: 2px solid #fff;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 13px;
            line-height: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            padding: 0;
        }

        .gallery-item .remove-img-btn:hover {
            background: #c44931;
        }

        .gallery-item .deleted-overlay {
            position: absolute;
            inset: 0;
            background: rgba(240, 101, 72, .15);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            color: #f06548;
            font-weight: 600;
            pointer-events: none;
        }

        .gallery-item .undo-btn {
            position: absolute;
            bottom: -7px;
            right: -7px;
            background: #0ab39c;
            border: 2px solid #fff;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 11px;
            line-height: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            padding: 0;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="mb-0">Edit Product</h5>
                <small class="text-muted">{{ $product->name }}</small>
            </div>
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="ri-arrow-left-line me-1"></i> Back to Products
            </a>
        </div>

        {{-- Session Success --}}
        @if (session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: "{{ session('success') }}",
                        timer: 2000,
                        showConfirmButton: false,
                        toast: true,
                        position: 'top-end'
                    });
                });
            </script>
        @endif

        {{-- Session Error — specific message  --}}
        @if (session('error'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Could Not Save Product',
                        html: `
                <div class="text-start">
                    <p class="mb-2">{{ session('error') }}</p>
                    <hr>
                    <p class="text-muted small mb-0">
                        <i class="ri-information-line me-1"></i>
                        Your form data has been preserved. Please fix the issue and try again.
                    </p>
                </div>`,
                        confirmButtonColor: '#405189',
                        confirmButtonText: 'OK, fix it'
                    });
                });
            </script>
        @endif

        {{-- Validation Errors  --}}
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <div class="d-flex align-items-start gap-2">
                    <i class="ri-error-warning-line fs-5 mt-1 flex-shrink-0"></i>
                    <div class="flex-grow-1">
                        <h6 class="alert-heading fw-semibold mb-2">
                            {{ $errors->count() }} error(s) found — please fix before saving:
                        </h6>
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li class="small">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data"
            id="productForm">
            @csrf
            @method('PUT')

            {{-- Hidden: existing gallery images to delete --}}
            <div id="deleteGalleryInputs"></div>

            <div class="row g-4">

                {{-- ======= LEFT COLUMN ===========  --}}
                <div class="col-lg-8">

                    {{-- Basic Info --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-bottom py-3">
                            <h6 class="mb-0 fw-semibold">
                                <i class="ri-box-line me-2 text-primary"></i>Basic Information
                            </h6>
                        </div>
                        <div class="card-body p-4">

                            <div class="mb-3">
                                <label class="form-label fw-medium">
                                    Product Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $product->name) }}" placeholder="Product name">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">SKU</label>
                                    <input type="text" name="sku" class="form-control"
                                        value="{{ old('sku', $product->sku) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Material</label>
                                    <input type="text" name="material" class="form-control"
                                        value="{{ old('material', $product->material) }}">
                                </div>
                            </div>

                            <div class="mt-3">
                                <label class="form-label fw-medium">Short Description</label>
                                <textarea name="short_description" rows="2" class="form-control">{{ old('short_description', $product->short_description) }}</textarea>
                            </div>

                            <div class="mt-3">
                                <label class="form-label fw-medium">Full Description</label>
                                <textarea name="description" rows="5" class="form-control" id="description">{{ old('description', $product->description) }}</textarea>
                            </div>

                        </div>
                    </div>

                    {{-- Pricing & Stock --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-bottom py-3">
                            <h6 class="mb-0 fw-semibold">
                                <i class="ri-price-tag-line me-2 text-success"></i>Pricing & Stock
                            </h6>
                        </div>
                        <div class="card-body p-4">

                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-medium">
                                        Price <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" name="price" step="0.01"
                                            class="form-control @error('price') is-invalid @enderror"
                                            value="{{ old('price', $product->price) }}">
                                    </div>
                                    @error('price')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-medium">Discount Price</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" name="discount_price" step="0.01" class="form-control"
                                            value="{{ old('discount_price', $product->discount_price) }}">
                                    </div>
                                </div>

                                <div class="col-md-4" id="stockFieldWrapper">
                                    <label class="form-label fw-medium">
                                        Stock
                                        <span class="text-danger" id="stockRequired">*</span>
                                    </label>
                                    <input type="number" name="stock" id="stockField"
                                        class="form-control @error('stock') is-invalid @enderror"
                                        value="{{ old('stock', $product->stock) }}" min="0">
                                    @error('stock')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted" id="stockHint">
                                        Auto-calculated when variants added
                                    </small>
                                </div>
                            </div>

                            <div class="row g-3 mt-1">
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Weight (kg)</label>
                                    <input type="number" name="weight" step="0.01" class="form-control"
                                        value="{{ old('weight', $product->weight) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Dimensions</label>
                                    <input type="text" name="dimensions" class="form-control"
                                        value="{{ old('dimensions', $product->dimensions) }}"
                                        placeholder="e.g. 30x20x10 cm">
                                </div>
                            </div>

                        </div>
                    </div>

                    {{--  Variants  --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div
                            class="card-header bg-white border-bottom py-3
                                d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0 fw-semibold">
                                    <i class="ri-layout-grid-line me-2 text-warning"></i>
                                    Product Variants
                                    <span class="badge bg-warning text-dark ms-2"
                                        id="variantCount">{{ $product->variants->count() }}</span>
                                </h6>
                                <small class="text-muted">
                                    Add size/color combinations with individual stock
                                </small>
                            </div>
                            <button type="button" class="btn btn-primary btn-sm" id="addVariantBtn">
                                <i class="ri-add-line me-1"></i> Add Variant
                            </button>
                        </div>
                        <div class="card-body p-0">

                            <div id="variantEmptyState"
                                class="text-center py-5
                             {{ $product->variants->count() > 0 ? 'd-none' : '' }}">
                                <i class="ri-layout-grid-line ri-2x text-muted opacity-50 d-block mb-2"></i>
                                <p class="text-muted mb-1">No variants added yet</p>
                                <small class="text-muted">
                                    Click "Add Variant" to add size/color combinations
                                </small>
                            </div>

                            <div id="variantTableWrapper"
                                class="{{ $product->variants->count() === 0 ? 'd-none' : '' }}">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="ps-4" style="width:20%">Size</th>
                                                <th style="width:20%">Color Name</th>
                                                <th style="width:15%">Color</th>
                                                <th style="width:18%">Price Override</th>
                                                <th style="width:15%">
                                                    Stock <span class="text-danger">*</span>
                                                </th>
                                                <th style="width:12%" class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="variantBody"></tbody>
                                        <tfoot class="table-light">
                                            <tr>
                                                <td colspan="4" class="text-end fw-medium ps-4">
                                                    Total Stock:
                                                </td>
                                                <td>
                                                    <span id="totalVariantStock" class="fw-bold text-success">0</span>
                                                </td>
                                                <td></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{--  Gallery  --}}
                    @php
                        $galleryImages = $product->gallery;
                        if (is_string($galleryImages)) {
                            $galleryImages = json_decode($galleryImages, true) ?: [];
                        }
                        $galleryImages = is_array($galleryImages) ? $galleryImages : [];
                    @endphp

                    <div class="card border-0 shadow-sm mb-4">
                        <div
                            class="card-header bg-white border-bottom py-3
                                d-flex align-items-center justify-content-between">
                            <h6 class="mb-0 fw-semibold">
                                <i class="ri-gallery-line me-2 text-info"></i>
                                Product Gallery
                                <span class="badge bg-info text-white ms-2" id="galleryCount">0</span>
                            </h6>
                            <small class="text-muted">Max 2MB per image</small>
                        </div>
                        <div class="card-body p-4">

                            {{--  Existing Gallery  --}}
                            @if (count($galleryImages) > 0)
                                <div class="mb-3">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <p class="text-muted small mb-0 fw-medium">
                                            Current Images
                                            <span class="badge bg-secondary ms-1">
                                                {{ count($galleryImages) }}
                                            </span>
                                        </p>
                                        <small class="text-muted">
                                            Click <span class="text-danger fw-medium">×</span>
                                            to mark for deletion
                                        </small>
                                    </div>
                                    <div class="d-flex flex-wrap gap-2" id="existingGallery">
                                        @foreach ($galleryImages as $index => $img)
                                            <div class="gallery-item" id="existing-{{ $index }}"
                                                data-path="{{ $img }}">
                                                <img src="{{ filter_var($img, FILTER_VALIDATE_URL) ? $img : asset($img) }}"
                                                    alt="Gallery image">
                                                <button type="button" class="remove-img-btn"
                                                    onclick="markExistingForDelete({{ $index }}, '{{ $img }}')"
                                                    title="Mark for deletion">×</button>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            {{--  Upload New Images  --}}
                            <div class="border-2 rounded-3 p-4 text-center mb-3" id="galleryDropzone"
                                style="border: 2px dashed #d3d3d3; cursor:pointer; transition:border-color .2s"
                                onclick="document.getElementById('galleryInput').click()"
                                onmouseenter="this.style.borderColor='#405189'"
                                onmouseleave="this.style.borderColor='#d3d3d3'">
                                <i class="ri-image-add-line ri-2x text-muted mb-2 d-block"></i>
                                <p class="text-muted mb-0 small">
                                    Click to add new images
                                    <br>
                                    <span style="font-size:11px">
                                        JPG, PNG, WebP — multiple selection supported
                                    </span>
                                </p>
                            </div>

                            <input type="file" name="gallery[]" id="galleryInput" class="d-none" accept="image/*"
                                multiple>

                            {{-- New image previews --}}
                            <div id="galleryPreview" class="d-flex flex-wrap gap-2"></div>
                            <div id="galleryEmpty" class="text-muted small mt-1"
                                style="{{ count($galleryImages) > 0 ? 'display:none' : '' }}">
                                No new images selected.
                            </div>

                        </div>
                    </div>

                </div>{{-- end left col --}}

                {{-- ========== RIGHT COLUMN =========== --}}
                <div class="col-lg-4">

                    {{-- Thumbnail --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-bottom py-3">
                            <h6 class="mb-0 fw-semibold">
                                <i class="ri-image-line me-2 text-primary"></i>Thumbnail
                            </h6>
                        </div>
                        <div class="card-body p-4">
                            <div class="rounded-3 p-3 text-center mb-3 thumb-dropzone" id="thumbDropzone"
                                style="cursor:pointer; min-height:160px;
                                    display:flex; align-items:center; justify-content:center;">
                                @if ($product->thumbnail)
                                    <img id="thumbPreview"
                                        src="{{ filter_var($product->thumbnail, FILTER_VALIDATE_URL) ? $product->thumbnail : asset($product->thumbnail) }}"
                                        class="img-fluid rounded" style="max-height:200px;">
                                @else
                                    <div id="thumbPlaceholder">
                                        <i class="ri-upload-cloud-line ri-2x text-muted mb-2 d-block"></i>
                                        <p class="text-muted small mb-0">Click to upload thumbnail</p>
                                    </div>
                                    <img id="thumbPreview" src="" alt=""
                                        class="img-fluid rounded d-none" style="max-height:200px;">
                                @endif
                            </div>
                            <input type="file" name="thumbnail" id="thumbnailInput" class="d-none" accept="image/*">
                            <button type="button" class="btn btn-outline-primary btn-sm w-100"
                                onclick="document.getElementById('thumbnailInput').click()">
                                <i class="ri-upload-line me-1"></i>
                                {{ $product->thumbnail ? 'Change Thumbnail' : 'Choose Image' }}
                            </button>
                            @error('thumbnail')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Organization --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-bottom py-3">
                            <h6 class="mb-0 fw-semibold">
                                <i class="ri-folder-tree-line me-2 text-secondary"></i>Organization
                            </h6>
                        </div>
                        <div class="card-body p-4">

                            <div class="mb-3">
                                <label class="form-label fw-medium">
                                    Category <span class="text-danger">*</span>
                                </label>
                                <select name="category_id" id="categorySelect"
                                    class="form-select @error('category_id') is-invalid @enderror">
                                    <option value="">-- Select Category --</option>
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat->id }}"
                                            {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-medium">Sub Category</label>
                                <select name="sub_category_id" id="subCategorySelect" class="form-select">
                                    <option value="">-- Select Category First --</option>
                                    @foreach ($subCategories as $sub)
                                        <option value="{{ $sub->id }}"
                                            {{ old('sub_category_id', $product->sub_category_id) == $sub->id ? 'selected' : '' }}>
                                            {{ $sub->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-medium">Brand</label>
                                <select name="brand_id" class="form-select">
                                    <option value="">-- Select Brand --</option>
                                    @foreach ($brands as $brand)
                                        <option value="{{ $brand->id }}"
                                            {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                                            {{ $brand->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="form-label fw-medium">Tags</label>
                                @php
                                    $tags = $product->tags;
                                    if (is_string($tags)) {
                                        $decoded = json_decode($tags, true);
                                        $tags = is_array($decoded) ? $decoded : explode(',', $tags);
                                    }
                                    if (!is_array($tags)) {
                                        $tags = [$tags];
                                    }
                                @endphp
                                <input type="text" name="tags" class="form-control"
                                    value="{{ old('tags', implode(', ', array_filter($tags))) }}"
                                    placeholder="luxury, bag, leather">
                                <small class="text-muted">Separate with commas</small>
                            </div>

                        </div>
                    </div>

                    {{-- Settings --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-bottom py-3">
                            <h6 class="mb-0 fw-semibold">
                                <i class="ri-settings-line me-2 text-secondary"></i>Settings
                            </h6>
                        </div>
                        <div class="card-body p-4">

                            {{-- Product status info --}}
                            <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                                <span class="text-muted small">Current Status</span>
                                <span class="badge bg-{{ $product->status ? 'success' : 'danger' }}">
                                    {{ $product->status ? 'Active' : 'Inactive' }}
                                </span>
                            </div>

                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" name="is_featured" id="isFeatured"
                                    value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                                <label class="form-check-label fw-medium" for="isFeatured">
                                    Featured Product
                                </label>
                                <div>
                                    <small class="text-muted">Show on homepage featured section</small>
                                </div>
                            </div>

                            {{-- Meta --}}
                            <div class="mt-3 pt-3 border-top">
                                <div class="d-flex justify-content-between small py-1">
                                    <span class="text-muted">Created</span>
                                    <span>{{ $product->created_at->format('d M Y') }}</span>
                                </div>
                                <div class="d-flex justify-content-between small py-1">
                                    <span class="text-muted">Last Updated</span>
                                    <span>{{ $product->updated_at->diffForHumans() }}</span>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                            <i class="ri-save-line me-2"></i> Update Product
                        </button>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>

                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        //  EXISTING DATA

        const existingVariants = @json($product->variants);

        // Tracks existing gallery paths marked for deletion
        const markedForDelete = new Set();


        //  INIT on DOM ready
        document.addEventListener('DOMContentLoaded', function() {

            // Load existing variants into table
            existingVariants.forEach((v) => {
                addVariantRow({
                    id: v.id,
                    size: v.size,
                    color: v.color,
                    color_hex: v.color_hex ?? '#405189',
                    price: v.price,
                    stock: v.stock,
                });
            });

            updateGalleryCount();
        });


        //  THUMBNAIL
        document.getElementById('thumbnailInput').addEventListener('change', function() {
            const file = this.files[0];
            if (!file) return;

            if (file.size > 2 * 1024 * 1024) {
                Swal.fire({
                    icon: 'warning',
                    title: 'File too large',
                    text: 'Thumbnail must be under 2MB.'
                });
                this.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = (e) => {
                const preview = document.getElementById('thumbPreview');
                const placeholder = document.getElementById('thumbPlaceholder');
                preview.src = e.target.result;
                preview.classList.remove('d-none');
                if (placeholder) placeholder.classList.add('d-none');
            };
            reader.readAsDataURL(file);
        });

        document.getElementById('thumbDropzone').addEventListener('click', () => {
            document.getElementById('thumbnailInput').click();
        });


        //  EXISTING GALLERY
        function markExistingForDelete(index, path) {
            const el = document.getElementById(`existing-${index}`);
            const btn = el.querySelector('.remove-img-btn');

            if (markedForDelete.has(path)) {
                // Undo
                markedForDelete.delete(path);
                el.classList.remove('marked-delete');
                el.querySelector('.deleted-overlay')?.remove();
                el.querySelector('.undo-btn')?.remove();
                btn.style.display = 'flex';
                // Remove hidden input
                document.querySelector(`input[name="delete_gallery[]"][value="${CSS.escape(path)}"]`)?.remove();
            } else {
                // Mark for delete
                markedForDelete.add(path);
                el.classList.add('marked-delete');
                btn.style.display = 'none';

                // Overlay label
                const overlay = document.createElement('div');
                overlay.className = 'deleted-overlay';
                overlay.textContent = 'DELETE';
                el.appendChild(overlay);

                // Undo button
                const undoBtn = document.createElement('button');
                undoBtn.type = 'button';
                undoBtn.className = 'undo-btn';
                undoBtn.title = 'Undo';
                undoBtn.innerHTML = '↩';
                undoBtn.addEventListener('click', () => markExistingForDelete(index, path));
                el.appendChild(undoBtn);

                // Hidden input so controller knows which to delete
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'delete_gallery[]';
                input.value = path;
                document.getElementById('deleteGalleryInputs').appendChild(input);
            }
        }


        //  NEW GALLERY UPLOAD
        const galleryFiles = new Map();

        document.getElementById('galleryInput').addEventListener('change', function(e) {
            Array.from(e.target.files).forEach((file) => {

                // Size validation
                if (file.size > 2 * 1024 * 1024) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'File too large',
                        text: `"${file.name}" exceeds 2MB and was skipped.`
                    });
                    return;
                }

                // Duplicate check
                let isDuplicate = false;
                galleryFiles.forEach((f) => {
                    if (f.name === file.name && f.size === file.size) isDuplicate = true;
                });
                if (isDuplicate) return;

                const key = `${Date.now()}_${Math.random().toString(36).substr(2, 9)}`;
                galleryFiles.set(key, file);

                const reader = new FileReader();
                reader.onload = (ev) => addNewGalleryPreview(key, file.name, ev.target.result);
                reader.readAsDataURL(file);
            });

            // Reset so same file can be re-selected after removal
            this.value = '';
            syncGalleryInput();
        });

        function addNewGalleryPreview(key, name, src) {
            document.getElementById('galleryEmpty').style.display = 'none';

            const wrapper = document.createElement('div');
            wrapper.classList.add('gallery-item');
            wrapper.setAttribute('data-key', key);
            wrapper.title = name;
            wrapper.innerHTML = `
        <img src="${src}" alt="${name}">
        <button type="button"
                class="remove-img-btn"
                onclick="removeNewGalleryImage('${key}')"
                title="Remove">×</button>
    `;

            document.getElementById('galleryPreview').appendChild(wrapper);
            updateGalleryCount();
        }

        function removeNewGalleryImage(key) {
            galleryFiles.delete(key);
            document.querySelector(`[data-key="${key}"]`)?.remove();

            if (galleryFiles.size === 0) {
                document.getElementById('galleryEmpty').style.display = '';
            }

            syncGalleryInput();
            updateGalleryCount();
        }

        function syncGalleryInput() {
            const dt = new DataTransfer();
            galleryFiles.forEach((file) => dt.items.add(file));
            document.getElementById('galleryInput').files = dt.files;
        }

        function updateGalleryCount() {
            // Count = new files + existing (not marked for delete)
            const existingCount = document.querySelectorAll(
                '#existingGallery .gallery-item:not(.marked-delete)'
            ).length;
            const newCount = galleryFiles.size;
            document.getElementById('galleryCount').textContent = existingCount + newCount;
        }


        //  VARIANTS
        let variantIndex = 0;

        document.getElementById('addVariantBtn').addEventListener('click', () => addVariantRow());

        function addVariantRow(data = {}) {
            const i = variantIndex++;

            const row = document.createElement('tr');
            row.setAttribute('data-variant-index', i);

            // Pass existing variant id
            const variantIdInput = data.id ?
                `<input type="hidden" name="variants[${i}][id]" value="${data.id}">` :
                '';

            row.innerHTML = `
        ${variantIdInput}
        <td class="ps-4">
            <input type="text"
                   name="variants[${i}][size]"
                   class="form-control form-control-sm"
                   value="${escapeHtml(data.size ?? '')}"
                   placeholder="S, M, L, XL...">
        </td>
        <td>
            <input type="text"
                   name="variants[${i}][color]"
                   class="form-control form-control-sm"
                   value="${escapeHtml(data.color ?? '')}"
                   placeholder="Black, Brown...">
        </td>
        <td>
            <div class="d-flex align-items-center gap-2">
                <input type="color"
                       name="variants[${i}][color_hex]"
                       class="form-control form-control-color form-control-sm variant-color-picker"
                       value="${data.color_hex ?? '#405189'}"
                       style="width:36px;height:32px;padding:2px;cursor:pointer;">
                <small class="text-muted variant-hex-label">
                    ${data.color_hex ?? '#405189'}
                </small>
            </div>
        </td>
        <td>
            <div class="input-group input-group-sm">
                <span class="input-group-text">$</span>
                <input type="number"
                       name="variants[${i}][price]"
                       class="form-control form-control-sm"
                       value="${data.price ?? ''}"
                       placeholder="Base price if empty"
                       step="0.01" min="0">
            </div>
        </td>
        <td>
            <input type="number"
                   name="variants[${i}][stock]"
                   class="form-control form-control-sm variant-stock"
                   value="${data.stock ?? 0}"
                   min="0" required>
        </td>
        <td class="text-center">
            <button type="button"
                    class="btn btn-soft-danger btn-sm remove-variant-btn"
                    title="Remove variant">
                <i class="ri-close-line"></i>
            </button>
        </td>
    `;

            document.getElementById('variantBody').appendChild(row);

            // Color picker label sync
            const picker = row.querySelector('.variant-color-picker');
            const label = row.querySelector('.variant-hex-label');
            picker.addEventListener('input', () => label.textContent = picker.value);

            // FIX: Direct listener on row's button — no global function
            row.querySelector('.remove-variant-btn').addEventListener('click', () => {
                row.remove();
                updateVariantUI();
            });

            row.querySelector('.variant-stock').addEventListener('input', updateTotalStock);

            updateVariantUI();
        }

        function updateVariantUI() {
            const rows = document.querySelectorAll('#variantBody tr');
            const count = rows.length;
            const isEmpty = count === 0;

            document.getElementById('variantEmptyState')
                .classList.toggle('d-none', !isEmpty);
            document.getElementById('variantTableWrapper')
                .classList.toggle('d-none', isEmpty);
            document.getElementById('variantCount').textContent = count;

            const stockField = document.getElementById('stockField');
            const stockWrapper = document.getElementById('stockFieldWrapper');
            const stockHint = document.getElementById('stockHint');
            const stockReq = document.getElementById('stockRequired');

            if (!isEmpty) {
                stockField.disabled = true;
                stockField.removeAttribute('required');
                stockWrapper.style.opacity = '0.5';
                if (stockHint) stockHint.textContent = '⚡ Auto-calculated from variants';
                if (stockReq) stockReq.style.display = 'none';
            } else {
                stockField.disabled = false;
                stockWrapper.style.opacity = '1';
                if (stockHint) stockHint.textContent = 'Auto-calculated when variants added';
                if (stockReq) stockReq.style.display = '';
            }

            updateTotalStock();
        }

        function updateTotalStock() {
            let total = 0;
            document.querySelectorAll('.variant-stock').forEach((input) => {
                total += parseInt(input.value || 0, 10);
            });
            document.getElementById('totalVariantStock').textContent = total;

            // Sync stock field
            const stockField = document.getElementById('stockField');
            if (document.querySelectorAll('#variantBody tr').length > 0) {
                stockField.value = total;
            }
        }


        //  SUB-CATEGORY AJAX
        document.getElementById('categorySelect').addEventListener('change', function() {
            const categoryId = this.value;
            const subSelect = document.getElementById('subCategorySelect');

            if (!categoryId) {
                subSelect.innerHTML = '<option value="">-- Select Category First --</option>';
                return;
            }

            subSelect.innerHTML = '<option value="">Loading...</option>';
            subSelect.disabled = true;

            fetch(`{{ route('admin.products.sub-categories') }}?category_id=${categoryId}`)
                .then((res) => {
                    if (!res.ok) throw new Error('HTTP ' + res.status);
                    return res.json();
                })
                .then((data) => {
                    subSelect.disabled = false;
                    subSelect.innerHTML = '<option value="">-- Select Sub Category --</option>';
                    if (!data.length) {
                        subSelect.innerHTML = '<option value="">No sub-categories found</option>';
                        return;
                    }
                    data.forEach((sub) => {
                        subSelect.innerHTML +=
                            `<option value="${sub.id}">${sub.name}</option>`;
                    });
                })
                .catch(() => {
                    subSelect.disabled = false;
                    subSelect.innerHTML = '<option value="">Error loading sub-categories</option>';
                });
        });


        //  FORM SUBMIT
        document.getElementById('productForm').addEventListener('submit', function() {
            const btn = document.getElementById('submitBtn');
            btn.disabled = true;
            btn.innerHTML =
                '<span class="spinner-border spinner-border-sm me-2"></span> Updating...';
        });


        //  HELPERS
        function escapeHtml(str) {
            if (!str) return '';
            return String(str)
                .replace(/&/g, '&amp;')
                .replace(/"/g, '&quot;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;');
        }
    </script>
@endpush
