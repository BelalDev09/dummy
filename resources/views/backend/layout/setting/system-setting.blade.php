@extends('backend.app')

@section('title', 'System Setting')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css" />
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
    <style>
        .dropify-wrapper {
            height: auto !important;
        }
    </style>
@endpush

@section('content')

    {{-- HEADER --}}
    <div class="row align-items-center mb-3">
        <div class="col">
            <h5 class="mb-0">System Setting</h5>
            <small class="text-muted">Manage global system configuration</small>
        </div>
    </div>

    {{-- CARD --}}
    <div class="card shadow-sm">
        <div class="card-body">

            <form method="POST" action="{{ route('admin.system.settingupdate') }}" enctype="multipart/form-data">
                @csrf

                <div class="row g-4">

                    {{-- LEFT: FILES --}}
                    <div class="col-lg-3">
                        <div class="mb-3">
                            <label class="form-label">Logo</label>
                            <input type="file" class="form-control dropify" name="logo"
                                data-default-file="{{ asset($setting->logo) }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Favicon</label>
                            <input type="file" class="form-control dropify" name="favicon"
                                data-default-file="{{ asset($setting->favicon) }}">
                        </div>

                    </div>

                    {{-- RIGHT: FIELDS --}}
                    <div class="col-lg-9">

                        <div class="row g-3">

                            <div class="col-md-4">
                                <label class="form-label">System Title</label>
                                <input type="text" class="form-control" name="system_title"
                                    value="{{ $setting->system_title ?? '' }}">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Short Title</label>
                                <input type="text" class="form-control" name="system_short_title"
                                    value="{{ $setting->system_short_title ?? '' }}">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Tag Line</label>
                                <input type="text" class="form-control" name="tag_line"
                                    value="{{ $setting->tag_line ?? '' }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Company Name</label>
                                <input type="text" class="form-control" name="company_name"
                                    value="{{ $setting->company_name ?? '' }}">
                            </div>

                            {{-- PHONE --}}
                            <div class="col-md-6">
                                <label class="form-label">Phone</label>
                                <div class="input-group">

                                    <select class="form-select" name="phone_code">
                                        <option value="+880" {{ $setting?->phone_code === '+880' ? 'selected' : '' }}>
                                            +880 (Bangladesh)
                                        </option>
                                    </select>

                                    <input type="text" class="form-control" name="phone_number"
                                        value="{{ $setting->phone_number ?? '' }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email"
                                    value="{{ $setting->email ?? '' }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Copyright</label>
                                <input type="text" class="form-control" name="copyright_text"
                                    value="{{ $setting->copyright_text ?? '' }}">
                            </div>

                        </div>



                    </div>
                    {{-- SUBMIT --}}
                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-success btn-sm">
                            <i class="ri-check-line me-1"></i> Update Settings
                        </button>
                    </div>
                </div>

            </form>

        </div>
    </div>

@endsection


@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.10.5/sweetalert2.min.js"></script>
    @if (session('success') || session('error') || session('warning') || session('message') || session('info'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {

                const toastConfig = {
                    success: {
                        background: '#ecfdf5',
                        color: '#065f46',
                        border: '#10b981',
                        iconColor: '#10b981'
                    },
                    error: {
                        background: '#fef2f2',
                        color: '#991b1b',
                        border: '#ef4444',
                        iconColor: '#ef4444'
                    },
                    warning: {
                        background: '#fffbeb',
                        color: '#92400e',
                        border: '#f59e0b',
                        iconColor: '#f59e0b'
                    },
                    info: {
                        background: '#eff6ff',
                        color: '#1e3a8a',
                        border: '#3b82f6',
                        iconColor: '#3b82f6'
                    }
                };

                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    showClass: {
                        popup: 'swal2-show-custom'
                    },
                    hideClass: {
                        popup: 'swal2-hide-custom'
                    },
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer);
                        toast.addEventListener('mouseleave', Swal.resumeTimer);
                    }
                });

                window.showToast = function(type, message) {

                    const config = toastConfig[type] || toastConfig.info;

                    Toast.fire({
                        icon: type,
                        title: message,
                        background: config.background,
                        color: config.color,
                        didOpen: (toast) => {
                            toast.style.borderLeft = `6px solid ${config.border}`;
                            toast.style.borderRadius = '10px';
                            toast.style.boxShadow = '0 10px 25px rgba(0,0,0,0.08)';

                            const icon = toast.querySelector('.swal2-icon');
                            if (icon) {
                                icon.style.color = config.iconColor;
                            }
                        }
                    });
                };

                // Laravel session auto trigger
                @if (session('success'))
                    showToast('success', @json(session('success')));
                @endif

                @if (session('error'))
                    showToast('error', @json(session('error')));
                @endif

                @if (session('warning'))
                    showToast('warning', @json(session('warning')));
                @endif

                @if (session('info'))
                    showToast('info', @json(session('info')));
                @endif

            });
        </script>
    @endif
    <script>
        $('.dropify').dropify();
    </script>
@endpush
