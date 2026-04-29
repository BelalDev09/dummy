@extends('backend.app')

@section('title', 'Home Page - Top Section')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.10.5/sweetalert2.min.css">

    <style>
        .dropify-wrapper {
            height: auto !important;
        }

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

        .dropify-wrapper .dropify-preview .dropify-render img {
            width: 100%;
            height: auto;
            max-height: 220px;
            object-fit: contain;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">

        <div class="row justify-content-center">
            <div class="col-12 col-lg-12">

                <h5 class="card-title mb-4">Top Section</h5>

                <div class="card shadow-sm border-0">
                    <div class="card-body">

                        <form method="POST" action="{{ route('admin.cms.home_page.top_section.update') }}"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')

                            {{-- TITLE --}}
                            <div class="mb-3">
                                <label class="form-label">Title</label>
                                <input type="text" name="title" class="form-control"
                                    value="{{ old('title', $data?->title) }}">
                            </div>

                            {{-- SUB TITLE --}}
                            <div class="mb-3">
                                <label class="form-label">Sub Title</label>
                                <input type="text" name="sub_title" class="form-control"
                                    value="{{ old('sub_title', $data?->sub_title) }}">
                            </div>

                            {{-- GALLERY UPLOAD --}}
                            <div class="mb-3">
                                <label class="form-label">Gallery Images</label>

                                <div class="border rounded p-4 text-center mb-3"
                                    style="cursor:pointer; border:2px dashed #ccc;"
                                    onclick="document.getElementById('imageInput').click()">

                                    <i class="ri-image-add-line ri-2x text-muted"></i>
                                    <div class="text-muted">Click to upload multiple images</div>
                                </div>

                                <input type="file" name="image[]" id="imageInput" class="d-none" multiple
                                    accept="image/*">

                                {{-- OLD GALLERY --}}
                                @php
                                    $images = $data?->gallery ?? [];
                                @endphp

                                @if (!empty($images))
                                    <div class="d-flex flex-wrap gap-2 mb-3">
                                        @foreach ($images as $img)
                                            <img src="{{ Storage::url($img) }}"
                                                style="width:80px;height:80px;object-fit:cover;border-radius:8px;border:1px solid #ddd;">
                                        @endforeach
                                    </div>
                                @endif

                                {{-- NEW PREVIEW --}}
                                <div id="imagePreview" class="d-flex flex-wrap gap-2"></div>
                            </div>

                            {{-- BUTTON TEXT --}}
                            <div class="mb-3">
                                <label class="form-label">Button Text</label>
                                <input type="text" name="button_text" class="form-control"
                                    value="{{ old('button_text', $data?->button_text) }}">
                            </div>

                            {{-- ACTION --}}
                            <div class="d-flex justify-content-between mt-4">
                                <button type="submit" class="btn btn-success px-4">
                                    Save Changes
                                </button>

                                <a href="{{ route('admin.cms.home_page.top_section') }}" class="btn btn-outline-secondary">
                                    Cancel
                                </a>
                            </div>

                        </form>

                    </div>
                </div>

            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.10.5/sweetalert2.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
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

            @if ($errors->any())
                showToast('error', `{!! implode('\n', $errors->all()) !!}`);
            @endif


            const imageInput = document.getElementById('imageInput');
            const previewBox = document.getElementById('imagePreview');
            const imageFiles = new Map();

            imageInput.addEventListener('change', function(e) {

                const files = Array.from(e.target.files || []);

                files.forEach(file => {

                    if (file.size > 10 * 1024 * 1024) return;

                    let exists = false;
                    imageFiles.forEach(f => {
                        if (f.name === file.name && f.size === file.size) {
                            exists = true;
                        }
                    });

                    if (exists) return;

                    const key = Date.now() + Math.random().toString(36).substr(2, 9);
                    imageFiles.set(key, file);

                    const reader = new FileReader();
                    reader.onload = function(ev) {
                        renderImage(key, ev.target.result);
                    };
                    reader.readAsDataURL(file);
                });

                syncInput();
            });

            function renderImage(key, src) {
                const div = document.createElement('div');
                div.className = 'position-relative';
                div.setAttribute('data-key', key);

                div.innerHTML = `
            <img src="${src}" style="width:80px;height:80px;object-fit:cover;border-radius:8px;border:1px solid #ddd;">
            <button type="button"
                onclick="removeImage('${key}')"
                style="position:absolute;top:-6px;right:-6px;width:20px;height:20px;border-radius:50%;background:red;color:#fff;border:none;">
                ×
            </button>
        `;

                previewBox.appendChild(div);
            }

            window.removeImage = function(key) {
                imageFiles.delete(key);
                document.querySelector(`[data-key="${key}"]`)?.remove();
                syncInput();
            }

            function syncInput() {
                const dt = new DataTransfer();
                imageFiles.forEach(file => dt.items.add(file));
                imageInput.files = dt.files;
            }

        });
    </script>
@endpush
