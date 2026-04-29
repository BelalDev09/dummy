@extends('backend.app')


@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css" />
    {{-- SweetAlert2 CSS --}}
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
@endpush

@section('title', 'Home Page - Women Collection Section')

@section('content')

    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-12">
                <h5 class="card-title mb-4">Women Collection Section</h5>

                <div class="card shadow-sm border-0">
                    <div class="card-body">


                        <form action="{{ route('admin.cms.home_page.women_collection.update') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')
                            {{-- Main Text --}}
                            <div class="mb-3">
                                <label class="form-label">Title</label>
                                <input type="text" name="title" class="form-control"
                                    value="{{ old('title', $data->title) }}">
                            </div>

                            {{-- Sub Text --}}
                            <div class="mb-3">
                                <label class="form-label">Sub Title</label>
                                <input type="text" name="sub_title" class="form-control"
                                    value="{{ old('sub_title', $data->sub_title) }}">
                            </div>

                            {{-- Button Text --}}
                            <div class="mb-3">
                                <label class="form-label">Button Text</label>
                                <input type="text" name="button_text" class="form-control"
                                    value="{{ old('button_text', $data->button_text) }}">
                            </div>

                            {{-- Button Link --}}
                            {{-- <div class="mb-3">
                                <label class="form-label">Button Link</label>
                                <input type="url" name="button_link" class="form-control"
                                    value="{{ old('button_link', $data->button_link) }}">
                            </div> --}}

                            {{-- Submit --}}
                            <div class="d-flex justify-content-between mt-4">
                                <button type="submit" class="btn btn-success px-4">
                                    <i class="ri-save-3-line"></i>
                                    Save Changes
                                </button>

                                <a href="{{ route('admin.cms.home_page.women_collection_section') }}"
                                    class="btn btn-outline-secondary">
                                    <i class="ri-close-line"></i>
                                    Cancel
                                </a>
                            </div>

                        </form>
                    </div>



                </div>
            </div>
        </div>
    </div>
    </div>

@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.10.5/sweetalert2.min.js"></script>

    @if (session('notify-success') ||
            session('notify-error') ||
            session('notify-warning') ||
            session('notify-message') ||
            session('notify-info'))
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
                @if (session('notify-success'))
                    showToast('success', @json(session('notify-success')));
                @endif

                @if (session('notify-error'))
                    showToast('error', @json(session('notify-error')));
                @endif

                @if (session('notify-warning'))
                    showToast('warning', @json(session('notify-warning')));
                @endif

                @if (session('notify-info'))
                    showToast('info', @json(session('notify-info')));
                @endif

            });
        </script>
    @endif

@endpush
