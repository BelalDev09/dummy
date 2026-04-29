@extends('backend.app')

@section('title', 'Create Sub Category')

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

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Create Sub Category</h5>

            <a href="{{ route('admin.sub-categories.index') }}" class="btn btn-secondary btn-sm">
                Back
            </a>
        </div>

        <!-- Card -->
        <div class="card shadow-sm border-0">
            <div class="card-body">

                <form action="{{ route('admin.sub-categories.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">

                        <!-- Category -->
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select name="category_id" class="form-control">
                                <option value="">-- Select Category --</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}"
                                        {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>

                            @error('category_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Name -->
                        <div class="mb-3">
                            <label class="form-label">Sub Category Name</label>
                            <input type="text" name="name" value="{{ old('name') }}" class="form-control"
                                placeholder="Enter sub category name">

                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Image -->
                        <div class="mb-3">
                            <label class="form-label">Sub Category Image</label>

                            <input type="file" name="image" class="dropify" accept="image/*"
                                data-allowed-file-extensions="jpg jpeg png gif webp" data-max-file-size="5M">

                            @error('image')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                    </div>

                    <!-- Buttons -->
                    <div class="d-flex justify-content-between mt-3">
                        <button class="btn btn-success px-4">
                            Create
                        </button>

                        <a href="{{ route('admin.sub-categories.index') }}" class="btn btn-outline-secondary">
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
                    error: 'Invalid file'
                }
            });
        });
    </script>
@endpush
