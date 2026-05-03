@extends('backend.app')

@section('title', 'Create Brand')

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
        <h5 class="mb-0">Create Brand</h5>

        <a href="{{ route('admin.brands.index') }}" class="btn btn-secondary btn-sm">
            Back
        </a>
    </div>

    <!-- CARD START -->
    <div class="card shadow-sm border-0">
        <div class="card-body">

            <form action="{{ route('admin.brands.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">

                    <!-- Name -->
                    <div class="mb-3">
                        <label class="form-label">Brand Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}"
                            placeholder="Enter brand name">
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="summernote form-control">{{ old('description') }}</textarea>
                    </div>

                    <!-- Logo -->
                    <div class="mb-3">
                        <label class="form-label">Brand Logo</label>
                        <input type="file" name="logo" class="dropify"
                            data-allowed-file-extensions="jpg jpeg png webp" data-max-file-size="2M">
                    </div>

                    <!-- Banner -->
                    <div class="mb-3">
                        <label class="form-label">Brand Banner</label>
                        <input type="file" name="banner" class="dropify"
                            data-allowed-file-extensions="jpg jpeg png webp" data-max-file-size="4M">
                    </div>

                    <!-- Country -->
                    <div class="mb-3">
                        <label class="form-label">Country</label>
                        <input type="text" name="country" class="form-control" value="{{ old('country') }}"
                            placeholder="Enter country">
                    </div>

                    <!-- Website -->
                    <div class="mb-3">
                        <label class="form-label">Website</label>
                        <input type="url" name="website" class="form-control" value="{{ old('website') }}"
                            placeholder="http://example.com">
                    </div>

                </div>

                <!-- BUTTON -->
                <div class="d-flex justify-content-between mt-4">
                    <button class="btn btn-success px-4">
                        Save
                    </button>
                    <a href="{{ route('admin.brands.index') }}" class="btn btn-outline-secondary">
                        Cancel
                    </a>
                </div>

            </form>

        </div>
    </div>
    <!-- CARD END -->

</div>

@endsection
