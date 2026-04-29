@extends('backend.app')

@section('title', 'Home Page - Men Collection Section')
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
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-12">
                <h5 class="card-title mb-4">Men Collection Section</h5>

                <div class="card shadow-sm border-0">
                    <div class="card-body">

                        <form action="{{ route('admin.cms.home_page.men_collection.update') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')
                            {{-- Main Text --}}
                            <div class="mb-3">
                                <label class="form-label">Title</label>
                                <input type="text" name="title" class="form-control"
                                    value="{{ old('title', $data->title) }}">
                            </div>

                            {{-- Sub Text --}}
                            <div class="mb-3">
                                <label class="form-label">Sub Title</label>
                                <input type="text" name="sub_title" class="form-control"
                                    value="{{ old('sub_title', $data->sub_title) }}">
                            </div>

                            {{-- Button Text --}}
                            <div class="mb-3">
                                <label class="form-label">Button Text</label>
                                <input type="text" name="button_text" class="form-control"
                                    value="{{ old('button_text', $data->button_text) }}">
                            </div>

                            {{-- Button Link --}}
                            {{-- <div class="mb-3">
                                <label class="form-label">Button Link</label>
                                <input type="url" name="button_link" class="form-control"
                                    value="{{ old('button_link', $data->button_link) }}">
                            </div> --}}


                            {{-- Submit --}}
                            <div class="d-flex justify-content-between mt-4">
                                <button type="submit" class="btn btn-success px-4">
                                    <i class="ri-save-3-line"></i>
                                    Save Changes
                                </button>

                                <a href="{{ route('admin.cms.home_page.men_collection_section') }}"
                                    class="btn btn-outline-secondary">
                                    <i class="ri-close-line"></i>
                                    Cancel
                                </a>
                            </div>

                        </form>

                    </div>


                </div>
            </div>
        </div>
    </div>
    </div>

@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.10.5/sweetalert2.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // ================= TOAST =================
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

            // ================= DROPIFY =================
            $('.dropify').dropify();

        });
    </script>
    <script>
        $(document).ready(function() {

            // init only once
            $('.dropify').dropify();

            // ADD ITEM
            $(document).on('click', '#add-item', function() {

                let html = `
        <div class="border rounded p-3 mb-3 item">
            <h6 class="mb-3">New Product</h6>

            <div class="mb-2">
                <label>Brand Name</label>
                <input type="text" name="brand_name[]" class="form-control" placeholder="Brand Name">
            </div>

            <div class="mb-2">
                <label>Title</label>
                <input type="text" name="title[]" class="form-control" placeholder="Title">
            </div>

            <div class="mb-2">
                <label>Price</label>
                <input type="number" name="price[]" class="form-control" placeholder="Price">
            </div>

            <div class="mb-2">
                <label>Image</label>
                <input type="file"
                       name="image[]"
                       class="dropify new-dropify"
                       data-allowed-file-extensions="jpg jpeg png webp"
                       data-height="120">
            </div>

            <button type="button" class="btn btn-danger btn-sm remove-item">
                Remove
            </button>
        </div>
        `;

                let newItem = $(html);
                $('#items-wrapper').append(newItem);

                // ONLY init new dropify (not all)
                newItem.find('.new-dropify').dropify();
            });

            // REMOVE ITEM
            $(document).on('click', '.remove-item', function() {
                $(this).closest('.item').remove();
            });

        });
    </script>
@endpush
