@extends('backend.app')

@section('content')
    <div class="container-fluid">

        <div class="row justify-content-center">
            <div class="col-lg-12">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5>Create Social Setting</h5>
                </div>

                <div class="card modern-card border-0">

                    <div class="card-body">

                        <form action="{{ route('admin.social-settings.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Title</label>
                                <input type="text" name="title" class="form-control"
                                    placeholder="Facebook / Instagram">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Link</label>
                                <input type="url" name="link" class="form-control" placeholder="https://...">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Icon / Image</label>
                                <input type="file" name="image" class="form-control dropify" data-height="180">
                            </div>

                            <div class="form-check form-switch mb-4">
                                <input class="form-check-input" type="checkbox" name="status" value="active">
                                <label class="form-check-label">Active</label>
                            </div>

                            <button class="btn btn-success w-100">
                                Save Setting
                            </button>

                        </form>

                    </div>

                </div>

            </div>
        </div>

    </div>
@endsection
