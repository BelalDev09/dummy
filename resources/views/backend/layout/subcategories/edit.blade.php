@extends('backend.app')

@section('title', 'Edit Sub Category')

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
            <h5 class="mb-0">Edit Sub Category</h5>

            <a href="{{ route('admin.sub-categories.index') }}" class="btn btn-secondary btn-sm">
                Back
            </a>
        </div>

        <!-- Card -->
        <div class="card shadow-sm border-0">
            <div class="card-body">

                <form action="{{ route('admin.sub-categories.update', $data->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">

                        <!-- Category -->
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select name="category_id" class="form-control @error('category_id') is-invalid @enderror">
                                <option value="">-- Select Category --</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}"
                                        {{ old('category_id', $data->category_id) == $cat->id ? 'selected' : '' }}>
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
                            <input type="text" name="name" value="{{ old('name', $data->name) }}"
                                class="form-control @error('name') is-invalid @enderror"
                                placeholder="Enter sub category name">

                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Image -->
                        <div class="mb-3">
                            <label class="form-label">Sub Category Image</label>

                            <input type="file" name="image" class="dropify"
                                data-allowed-file-extensions="jpg jpeg png gif webp" data-max-file-size="5M"
                                data-default-file="{{ $data->image ? (filter_var($data->image, FILTER_VALIDATE_URL) ? $data->image : asset($data->image)) : '' }}">

                            @error('image')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                    </div>

                    <!-- Buttons -->
                    <div class="d-flex justify-content-between mt-3">
                        <button class="btn btn-success px-4">
                            Update
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
                    error: 'File error'
                }
            });
        });
    </script>
@endpush
