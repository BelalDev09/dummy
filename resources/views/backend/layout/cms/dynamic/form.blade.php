@extends('backend.app')

@push('styles')
    <style>
        .dropify-wrapper .dropify-preview .dropify-render img {
            max-height: 200px;
            object-fit: contain;
        }
    </style>
@endpush

@section('content')
    <div class="container">

        <form id="cmsForm"
            action="{{ isset($cms) ? route('admin.dynamic.cms.update', $cms->id) : route('admin.dynamic.cms.store') }}"
            method="POST" enctype="multipart/form-data">

            @csrf
            @if (isset($cms))
                @method('PUT')
            @endif

            {{-- PAGE / SECTION --}}
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label>Page</label>
                    <select name="page" class="form-control">
                        <option value="home" {{ ($cms->page ?? '') == 'home' ? 'selected' : '' }}>Home</option>
                        <option value="about" {{ ($cms->page ?? '') == 'about' ? 'selected' : '' }}>About</option>
                        <option value="men" {{ ($cms->page ?? '') == 'men' ? 'selected' : '' }}>Men</option>
                        <option value="women" {{ ($cms->page ?? '') == 'women' ? 'selected' : '' }}>Women</option>
                    </select>
                </div>

                <div class="col-md-4 mb-3">
                    <label>Section</label>
                    <select name="section" class="form-control">
                        <option value="hero" {{ ($cms->section ?? '') == 'hero' ? 'selected' : '' }}>Hero</option>
                        <option value="about" {{ ($cms->section ?? '') == 'about' ? 'selected' : '' }}>About</option>
                        <option value="services" {{ ($cms->section ?? '') == 'services' ? 'selected' : '' }}>Services
                        </option>
                        <option value="gallery" {{ ($cms->section ?? '') == 'gallery' ? 'selected' : '' }}>Gallery</option>
                    </select>
                </div>

                <div class="col-md-4 mb-3">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="active" {{ ($cms->status ?? '') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ ($cms->status ?? '') == 'inactive' ? 'selected' : '' }}>Inactive
                        </option>
                    </select>
                </div>
            </div>

            {{-- BASIC CONTENT --}}
            <div class="card p-3 mb-3">
                <h5>Basic Content</h5>
                <label for="form-label">Title</label>
                <input type="text" name="title" class="form-control mb-2" placeholder="Title"
                    value="{{ $cms->title ?? '' }}">
                <label for="form-label">Sub Title</label>
                <input type="text" name="sub_title" class="form-control mb-2" placeholder="Sub Title"
                    value="{{ $cms->sub_title ?? '' }}">
                <label for="form-label">Description</label>
                <textarea name="description" class="form-control mb-2">{{ $cms->description ?? '' }}</textarea>
                <label for="form-label">Image</label>
                <input type="file" name="image" class="dropify mb-2"
                    @if (!empty($cms->image)) data-default-file="{{ asset($cms->image) }}" @endif>
                <label for="form-label">Button_text</label>
                <input type="text" name="button_text" class="form-control mb-2" placeholder="Button Text"
                    value="{{ $cms->button_text ?? '' }}">
                <label for="form-label">Link_url</label>
                <input type="url" name="link_url" class="form-control" placeholder="Button Link"
                    value="{{ $cms->link_url ?? '' }}">
            </div>

            {{-- REPEATER --}}
            <div class="card p-3">
                <h5>Dynamic Items</h5>

                <div id="v1-container"></div>

                <button type="button" class="btn btn-primary mt-2" onclick="addV1()">
                    + Add Item
                </button>
            </div>

            <button class="btn btn-success mt-3">Save</button>
        </form>
    </div>
@endsection
@push('scripts')
    @php
        $existingV1 = collect($cms->v1 ?? [])
            ->map(function ($item) {
                return array_merge($item, [
                    'image' => !empty($item['image']) ? asset($item['image']) : null,
                ]);
            })
            ->toArray();
    @endphp
    <script>
        let existing = @json($existingV1);

        function initDropify(el) {
            setTimeout(() => {
                $(el).dropify();
            }, 50);
        }

        function addV1(data = {}) {

            let index = $('#v1-container .v1-item').length;

            let html = `
    <div class="border p-3 mb-3 v1-item rounded">

        <input type="text"
            name="v1[${index}][title]"
            class="form-control mb-2"
            value="${data.title ?? ''}"
            placeholder="Title">

        <input type="text"
            name="v1[${index}][sub_title]"
            class="form-control mb-2"
            value="${data.sub_title ?? ''}"
            placeholder="Sub Title">

        <input type="text"
            name="v1[${index}][button_text]"
            class="form-control mb-2"
            value="${data.button_text ?? ''}"
            placeholder="Button Text">

        <input type="url"
            name="v1[${index}][button_link]"
            class="form-control mb-2"
            value="${data.button_link ?? ''}"
            placeholder="Button Link">

        <input type="file"
            name="v1[${index}][image]"
            class="dropify v1-image"
            data-default-file="${data.image ?? ''}">

        <button type="button"
            class="btn btn-danger btn-sm mt-2 remove-item">
            Remove
        </button>

    </div>
    `;

            $('#v1-container').append(html);

            let last = $('#v1-container .v1-item').last();

            initDropify(last.find('.v1-image'));
        }

        // LOAD
        if (existing.length > 0) {
            existing.forEach(item => addV1(item));
        } else {
            addV1();
        }

        // REMOVE FIX
        $(document).on('click', '.remove-item', function() {
            $(this).closest('.v1-item').remove();
        });
    </script>
@endpush
