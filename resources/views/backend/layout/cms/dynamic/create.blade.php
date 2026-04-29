@extends('backend.app')

@section('content')
    {{-- <div class="container-fluid"> --}}

    <div class="card">
        <div class="card-header">
            <h4>Create CMS Block</h4>
        </div>

        <div class="card-body">

            <form action="{{ route('admin.dynamic.cms.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">

                    {{-- PAGE --}}
                    <div class="col-md-6">
                        <label class="form-label">Page</label>
                        <select name="page" class="form-select">
                            <option value="home">Home</option>
                            <option value="about">About</option>
                            <option value="contact">Contact</option>
                        </select>
                    </div>

                    {{-- SECTION --}}
                    <div class="col-md-6">
                        <label class="form-label">Section</label>
                        <select name="section" class="form-select">
                            <option value="hero">Hero</option>
                            <option value="feature">Feature</option>
                            <option value="banner">Banner</option>
                            <option value="faq">FAQ</option>
                        </select>
                    </div>

                    <hr class="mt-3">

                    {{-- CONTENT --}}
                    <div class="col-md-12 mt-2">
                        <label>Title</label>
                        <input type="text" name="title" class="form-control">
                    </div>

                    <div class="col-md-12 mt-2">
                        <label>Subtitle</label>
                        <input type="text" name="sub_title" class="form-control">
                    </div>

                    <div class="col-md-12 mt-2">
                        <label>Description</label>
                        <textarea name="description" class="form-control" rows="4"></textarea>
                    </div>

                    {{-- MEDIA --}}
                    <div class="col-md-6 mt-2">
                        <label>Image</label>
                        <input type="file" name="image" class="form-control dropify">
                    </div>

                    <div class="col-md-6 mt-2">
                        <label>Icon</label>
                        <input type="file" name="icon" class="form-control">
                    </div>

                    {{-- BUTTON --}}
                    <div class="col-md-6 mt-2">
                        <label>Button Text</label>
                        <input type="text" name="button_text" class="form-control">
                    </div>

                    <div class="col-md-6 mt-2">
                        <label>Button Link</label>
                        <input type="text" name="link_url" class="form-control">
                    </div>

                    {{-- STATUS --}}
                    <div class="col-md-6 mt-2">
                        <label>Status</label>
                        <select name="status" class="form-select">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>

                </div>

                <button class="btn btn-success mt-4">
                    Save Block
                </button>

            </form>

        </div>
    </div>

    {{-- </div> --}}
@endsection
