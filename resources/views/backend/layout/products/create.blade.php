@extends('backend.app')

@section('title', 'Create Product')
@push('styles')
    <style>
        .thumb-dropzone {
            border: 2px dashed #d3d3d3;
            transition: border-color .2s;
        }

        .thumb-dropzone:hover {
            border-color: #405189;
        }

        .gallery-item {
            position: relative;
            width: 80px;
            height: 80px;
        }

        .gallery-item img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #dee2e6;
            display: block;
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
    </style>
@endpush

@section('content')
    <div class="container-fluid">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="mb-0">Create Product</h5>
                <small class="text-muted">Add a new product to your store</small>
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

        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" id="productForm">
            @csrf

            <div class="row g-4">

                {{-- ======================== LEFT COLUMN===============   --}}
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
                                <input type="text" name="name" id="productName"
                                    class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}"
                                    placeholder="e.g. Louis Vuitton Speedy Bag">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">SKU</label>
                                    <input type="text" name="sku"
                                        class="form-control @error('sku') is-invalid @enderror" value="{{ old('sku') }}"
                                        placeholder="Auto-generated if empty">
                                    @error('sku')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Material</label>
                                    <input type="text" name="material" class="form-control"
                                        value="{{ old('material') }}" placeholder="e.g. Leather, Cotton">
                                </div>
                            </div>

                            <div class="mt-3">
                                <label class="form-label fw-medium">Short Description</label>
                                <textarea name="short_description" rows="2" class="form-control"
                                    placeholder="Brief product summary (shown in listing cards)">{{ old('short_description') }}</textarea>
                            </div>

                            <div class="mt-3">
                                <label class="form-label fw-medium">Full Description</label>
                                <textarea name="description" rows="5" class="form-control" id="description"
                                    placeholder="Detailed product description">{{ old('description') }}</textarea>
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
                                            value="{{ old('price') }}" placeholder="0.00">
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
                                            value="{{ old('discount_price') }}" placeholder="0.00">
                                    </div>
                                </div>

                                <div class="col-md-4" id="stockFieldWrapper">
                                    <label class="form-label fw-medium">
                                        Stock <span class="text-danger" id="stockRequired">*</span>
                                    </label>
                                    <input type="number" name="stock" id="stockField"
                                        class="form-control @error('stock') is-invalid @enderror"
                                        value="{{ old('stock', 0) }}" min="0">
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
                                        value="{{ old('weight') }}" placeholder="e.g. 0.5">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Dimensions</label>
                                    <input type="text" name="dimensions" class="form-control"
                                        value="{{ old('dimensions') }}" placeholder="e.g. 30x20x10 cm">
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
                                    <span class="badge bg-warning text-dark ms-2" id="variantCount">0</span>
                                </h6>
                                <small class="text-muted">Add size/color combinations with individual stock</small>
                            </div>
                            <button type="button" class="btn btn-primary btn-sm" id="addVariantBtn">
                                <i class="ri-add-line me-1"></i> Add Variant
                            </button>
                        </div>
                        <div class="card-body p-0">

                            {{-- Empty State --}}
                            <div id="variantEmptyState" class="text-center py-5">
                                <i class="ri-layout-grid-line ri-2x text-muted opacity-50 d-block mb-2"></i>
                                <p class="text-muted mb-1">No variants added yet</p>
                                <small class="text-muted">Click "Add Variant" to add size/color combinations</small>
                            </div>

                            {{-- Variant Table --}}
                            <div id="variantTableWrapper" class="d-none">
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

                            {{-- Upload Area --}}
                            <div class="border-2 border-dashed rounded-3 p-4 text-center mb-3" id="galleryDropzone"
                                style="border: 2px dashed #d3d3d3; cursor:pointer; transition:border-color .2s"
                                onclick="document.getElementById('galleryInput').click()"
                                onmouseenter="this.style.borderColor='#405189'"
                                onmouseleave="this.style.borderColor='#d3d3d3'">
                                <i class="ri-image-add-line ri-2x text-muted mb-2 d-block"></i>
                                <p class="text-muted mb-0 small">
                                    Click to add images <br>
                                    <span class="text-muted" style="font-size:11px">
                                        JPG, PNG, WebP — multiple selection supported
                                    </span>
                                </p>
                            </div>


                            <input type="file" name="gallery[]" id="galleryInput" class="d-none" accept="image/*"
                                multiple>

                            {{-- Preview Grid --}}
                            <div id="galleryPreview" class="d-flex flex-wrap gap-2"></div>

                            <div id="galleryEmpty" class="text-muted small mt-2">
                                No images selected yet.
                            </div>

                        </div>
                    </div>

                </div>

                {{-- ======================== RIGHT COLUMN===============   --}}


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
                                <div id="thumbPlaceholder">
                                    <i class="ri-upload-cloud-line ri-2x text-muted mb-2 d-block"></i>
                                    <p class="text-muted small mb-0">Click to upload thumbnail</p>
                                </div>
                                <img id="thumbPreview" src="" alt="" class="img-fluid rounded d-none"
                                    style="max-height:200px;">
                            </div>
                            <input type="file" name="thumbnail" id="thumbnailInput" class="d-none" accept="image/*">
                            <button type="button" class="btn btn-outline-primary btn-sm w-100"
                                onclick="document.getElementById('thumbnailInput').click()">
                                <i class="ri-upload-line me-1"></i> Choose Image
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
                                            {{ old('category_id') == $cat->id ? 'selected' : '' }}>
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
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-medium">Brand</label>
                                <select name="brand_id" class="form-select @error('brand_id') is-invalid @enderror">
                                    <option value="">-- Select Brand --</option>
                                    @foreach ($brands as $brand)
                                        <option value="{{ $brand->id }}"
                                            {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                            {{ $brand->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('brand_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div>
                                <label class="form-label fw-medium">Tags</label>
                                <input type="text" name="tags" class="form-control" value="{{ old('tags') }}"
                                    placeholder="luxury, bag, leather (comma separated)">
                                <small class="text-muted">Separate tags with commas</small>
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
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" name="is_featured" id="isFeatured"
                                    value="1" {{ old('is_featured') ? 'checked' : '' }}>
                                <label class="form-check-label fw-medium" for="isFeatured">
                                    Featured Product
                                </label>
                                <div>
                                    <small class="text-muted">Show on homepage featured section</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Submit --}}
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                            <i class="ri-save-line me-2"></i> Save Product
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
        //  THUMBNAIL PREVIEW

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
                document.getElementById('thumbPreview').src = e.target.result;
                document.getElementById('thumbPreview').classList.remove('d-none');
                document.getElementById('thumbPlaceholder').classList.add('d-none');
            };
            reader.readAsDataURL(file);
        });

        document.getElementById('thumbDropzone').addEventListener('click', () => {
            document.getElementById('thumbnailInput').click();
        });



        //  GALLERY


        const galleryFiles = new Map();

        document.getElementById('galleryInput').addEventListener('change', function(e) {
            const newFiles = Array.from(e.target.files);

            newFiles.forEach((file) => {

                //  Size validation
                if (file.size > 2 * 1024 * 1024) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'File too large',
                        text: `"${file.name}" exceeds 2MB and was skipped.`
                    });
                    return;
                }

                //  Duplicate check by name+size
                let isDuplicate = false;
                galleryFiles.forEach((f) => {
                    if (f.name === file.name && f.size === file.size) isDuplicate = true;
                });
                if (isDuplicate) return;

                //  Generate unique key for THIS file
                const key = `${Date.now()}_${Math.random().toString(36).substr(2, 9)}`;
                galleryFiles.set(key, file);

                //  Preview
                const reader = new FileReader();
                reader.onload = (ev) => addGalleryPreview(key, file.name, ev.target.result);
                reader.readAsDataURL(file);
            });

            // Reset input so same file can be re-selected after remove
            this.value = '';

            syncGalleryInput();
        });

        function addGalleryPreview(key, name, src) {
            const preview = document.getElementById('galleryPreview');
            document.getElementById('galleryEmpty').style.display = 'none';

            const wrapper = document.createElement('div');
            wrapper.classList.add('gallery-item');
            wrapper.setAttribute('data-key', key);
            wrapper.title = name;

            wrapper.innerHTML = `
        <img src="${src}" alt="${name}">
        <button type="button"
                class="remove-img-btn"
                onclick="removeGalleryImage('${key}')"
                title="Remove">×</button>
    `;

            preview.appendChild(wrapper);
            updateGalleryCount();
        }

        function removeGalleryImage(key) {
            // Remove from Map
            galleryFiles.delete(key);

            // Remove preview element
            const el = document.querySelector(`[data-key="${key}"]`);
            if (el) el.remove();

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
            document.getElementById('galleryCount').textContent = galleryFiles.size;
        }



        //  VARIANTS — FIX: proper index & remove sync

        document.addEventListener('DOMContentLoaded', function() {

            @if (old('variants'))
                const oldVariants = @json(old('variants'));
                // old('variants') is array keyed by index: {0:{size,color,...}, 1:{...}}
                Object.values(oldVariants).forEach(function(v) {
                    addVariantRow({
                        size: v.size ?? '',
                        color: v.color ?? '',
                        color_hex: v.color_hex ?? '#405189',
                        price: v.price ?? '',
                        stock: v.stock ?? 0,
                    });
                });
            @endif

        });
        let variantIndex = 0;

        document.getElementById('addVariantBtn').addEventListener('click', () => addVariantRow());

        function addVariantRow(data = {}) {
            const i = variantIndex++;

            const row = document.createElement('tr');
            row.setAttribute('data-variant-index', i);
            row.innerHTML = `
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
                <small class="text-muted variant-hex-label">${data.color_hex ?? '#405189'}</small>
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
                   min="0"
                   required>
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

            //  Color picker hex label sync
            const picker = row.querySelector('.variant-color-picker');
            const label = row.querySelector('.variant-hex-label');
            picker.addEventListener('input', () => label.textContent = picker.value);

            //  Remove button
            row.querySelector('.remove-variant-btn').addEventListener('click', () => {
                row.remove();
                updateVariantUI();
            });

            //  Stock input
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

            // Stock field toggle
            const stockField = document.getElementById('stockField');
            const stockWrapper = document.getElementById('stockFieldWrapper');
            const stockHint = document.getElementById('stockHint');
            const stockReq = document.getElementById('stockRequired');

            if (!isEmpty) {
                // stockField.value = '';
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

            updateTotalStock()
        }

        function updateTotalStock() {
            let total = 0;
            document.querySelectorAll('.variant-stock').forEach((input) => {
                total += parseInt(input.value || 0, 10);
            });
            document.getElementById('totalVariantStock').textContent = total;

            // Sync the hidden stock display field with total
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

            fetch(`{{ url('admin/products/sub-categories') }}?category_id=${categoryId}`)
                .then((res) => {
                    if (!res.ok) throw new Error('HTTP ' + res.status);
                    return res.json();
                })
                .then((data) => {
                    subSelect.disabled = false;
                    if (!data.length) {
                        subSelect.innerHTML = '<option value="">No sub-categories found</option>';
                        return;
                    }
                    subSelect.innerHTML = '<option value="">-- Select Sub Category --</option>';
                    data.forEach((sub) => {
                        subSelect.innerHTML += `<option value="${sub.id}">${sub.name}</option>`;
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
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Saving...';
        });

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
