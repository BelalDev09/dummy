@extends('backend.app')

@section('title', 'Edit Brand')

@push('styles')
    <style>
        .dropify-wrapper .dropify-preview .dropify-render img {
            max-height: 200px;
            object-fit: contain;
        }
    </style>
@endpush

@section('content')

    <div class="container-fluid">

        <!-- HEADER (Outside Card) -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Edit Brand</h5>

            <a href="{{ route('admin.brands.index') }}" class="btn btn-secondary btn-sm">
                Back
            </a>
        </div>

        <!-- CARD -->
        <div class="card shadow-sm border-0">
            <div class="card-body">

                <form action="{{ route('admin.brands.update', $brand->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">

                        <!-- Name -->
                        <div class="mb-3">
                            <label class="form-label">Brand Name</label>
                            <input type="text" name="name" class="form-control"
                                value="{{ old('name', $brand->name) }}">
                        </div>
                        <!-- Description -->
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="summernote form-control">{!! old('description', $brand->description) !!}</textarea>
                        </div>

                        <!-- Logo -->
                        <div class="mb-3">
                            <label class="form-label">Brand Logo</label>
                            <input type="file" name="logo" class="dropify"
                                data-default-file="{{ $brand->logo ? (filter_var($brand->logo, FILTER_VALIDATE_URL) ? $brand->logo : asset($brand->logo)) : '' }}"
                                data-allowed-file-extensions="jpg jpeg png gif webp" data-max-file-size="5M">
                        </div>

                        <!-- Banner -->
                        <div class="mb-3">
                            <label class="form-label">Brand Banner</label>
                            <input type="file" name="banner" class="dropify"
                                data-default-file="{{ $brand->banner ? (filter_var($brand->banner, FILTER_VALIDATE_URL) ? $brand->banner : asset($brand->banner)) : '' }}"
                                data-allowed-file-extensions="jpg jpeg png gif webp" data-max-file-size="5M">
                        </div>

                        <!-- Country -->
                        <div class="mb-3">
                            <label class="form-label">Country</label>
                            <input type="text" name="country" class="form-control"
                                value="{{ old('country', $brand->country) }}">
                        </div>

                        <!-- Website -->
                        <div class="mb-3">
                            <label class="form-label">Website</label>
                            <input type="text" name="website" class="form-control"
                                value="{{ old('website', $brand->website) }}">
                        </div>
                    </div>

                    <!-- BUTTON -->
                    <div class="d-flex justify-content-between mt-4">
                        <button class="btn btn-success px-4">
                            Update
                        </button>
                        <a href="{{ route('admin.brands.index') }}" class="btn btn-outline-secondary">
                            Cancel
                        </a>
                    </div>

                </form>

            </div>
        </div>

    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {

            $('.dropify').dropify({
                messages: {
                    default: 'Drag and drop or click',
                    replace: 'Drag and drop or click to replace',
                    remove: 'Remove',
                    error: 'Ooops, something wrong happened.'
                }
            });

        });
    </script>
@endpush
