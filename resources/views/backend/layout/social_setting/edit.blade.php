@extends('backend.app')

@section('content')
    <div class="container-fluid">

        <div class="row justify-content-center">
            <div class="col-lg-12">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5>Edit Social Setting</h5>
                </div>

                <div class="card modern-card border-0">

                    <div class="card-body">

                        <form action="{{ route('admin.social-settings.update', $socialSetting->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label">Title</label>
                                <input type="text" name="title" value="{{ $socialSetting->title }}"
                                    class="form-control">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Link</label>
                                <input type="url" name="link" value="{{ $socialSetting->link }}"
                                    class="form-control">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Change Image</label>
                                <input type="file" name="image" class="form-control dropify" data-height="180"
                                    data-default-file="{{ $socialSetting->image ? asset('storage/' . $socialSetting->image) : '' }}">
                            </div>

                            <div class="form-check form-switch mb-4">
                                <input class="form-check-input" type="checkbox" name="status" value="active"
                                    {{ $socialSetting->status == 'active' ? 'checked' : '' }}>

                                <label class="form-check-label">
                                    {{ $socialSetting->status == 'active' ? 'Active' : 'Inactive' }}
                                </label>
                            </div>

                            <button class="btn btn-success w-100">
                                Update Setting
                            </button>

                        </form>

                    </div>

                </div>

            </div>
        </div>

    </div>
@endsection
