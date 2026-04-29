@extends('backend.app')

@section('title', 'My Profile')
@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.10.5/sweetalert2.min.css">

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

        <!-- Header Card -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex align-items-center flex-column flex-md-row">

                    <div class="me-md-4 mb-3 mb-md-0 profile-img-main">
                        <img src="{{ asset(Auth::user()->avatar) }}" class="rounded-circle border"
                            style="width: 90px; height: 90px; object-fit: cover;">
                    </div>

                    <button class="btn btn-soft-primary btn-sm" id="uploadImageBtn">
                        <i class="ri-edit-2-line"></i> Update Profile Picture
                    </button>

                    <input type="file" id="profile_picture_input" name="avatar" hidden>
                </div>

            </div>
        </div>
    </div>

    <!-- Tabs Card -->
    <div class="card shadow-sm">

        <div class="card-header bg-white border-bottom-0">
            <ul class="nav nav-tabs nav-tabs-custom nav-justified">

                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#editProfile">
                        Edit Profile
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#updatePassword">
                        Change Password
                    </a>
                </li>

            </ul>
        </div>

        <div class="card-body">

            <div class="tab-content">

                <!-- PROFILE UPDATE -->
                <div class="tab-pane fade show active" id="editProfile">

                    <form method="POST" action="{{ route('admin.profile.update') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" value="{{ Auth::user()->name }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ Auth::user()->email }}">
                        </div>

                        <button class="btn btn-primary">
                            Update Profile
                        </button>

                    </form>

                </div>

                <!-- PASSWORD UPDATE -->
                <div class="tab-pane fade" id="updatePassword">

                    <form method="POST" action="{{ route('admin.profile.update.password') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Current Password</label>
                            <input type="password" name="old_password" class="form-control">
                        </div>

                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label class="form-label">New Password</label>
                                <input type="password" name="password" class="form-control">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Confirm Password</label>
                                <input type="password" name="password_confirmation" class="form-control">
                            </div>

                        </div>

                        <button class="btn btn-primary">
                            Update Password
                        </button>

                    </form>

                </div>

            </div>

        </div>
    </div>

    </div>
@endsection

@push('scripts')
    <script>
        $(function() {

            $('#uploadImageBtn').on('click', function() {
                $('#profile_picture_input').trigger('click');
            });

            $('#profile_picture_input').on('change', function() {

                let file = this.files[0];
                if (!file) return;

                let formData = new FormData();
                formData.append('avatar', file);
                formData.append('_token', '{{ csrf_token() }}');

                $.ajax({
                    url: "{{ route('admin.profile.update.profile.picture') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,

                    beforeSend: function() {
                        $('#uploadImageBtn').text('Uploading...');
                    },

                    success: function(res) {

                        $('#uploadImageBtn').text('Update Profile Picture');

                        if (res.success) {

                            $('.profile-img-main img').attr(
                                'src',
                                res.image_url + '?t=' + new Date().getTime()
                            );

                            showToast('success', res.message);

                        } else {
                            showToast('error', res.message);
                        }
                    },

                    error: function() {
                        $('#uploadImageBtn').text('Update Profile Picture');
                        showToast('error', 'Upload failed');
                    }
                });

            });

        });
    </script>
@endpush
