@extends('backend.app')

@section('title', 'Social Settings')
@push('styles')
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
@section('content')

    {{-- HEADER --}}
    <div class="row align-items-center mb-3">
        <div class="col">
            <h4 class="mb-0 fw-semibold">Social Settings</h4>
        </div>

        <div class="col-auto">
            <a href="{{ route('admin.social-settings.create') }}" class="btn btn-success btn-sm">
                <i class="ri-add-line me-1"></i> Add New
            </a>
        </div>
    </div>

    {{-- TABLE CARD --}}
    <div class="card shadow-sm">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-hover align-middle table-nowrap" id="social-datatable">

                    <thead class="table-light text-center">
                        <tr>
                            <th>#</th>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Link</th>
                            <th>Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody></tbody>

                </table>
            </div>

        </div>
    </div>

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.10.5/sweetalert2.min.js"></script> --}}

    <script>
        $(function() {

            let table = $('#social-datatable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                autoWidth: false,
                ajax: "{{ route('admin.social-settings.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'image',
                        name: 'image',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'link',
                        name: 'link'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });

            // ==============================
            // STATUS CHANGE (VELZON STYLE)
            // ==============================
            $(document).on('change', '.status-toggle', function() {

                let id = $(this).data('id');
                let checkbox = $(this);

                let url = "{{ route('admin.social-settings.status', ':id') }}".replace(':id', id);

                $.ajax({
                    url: url,
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },

                    success: function(res) {

                        Swal.fire({
                            icon: 'success',
                            title: 'Updated!',
                            text: res.message,
                            timer: 1200,
                            showConfirmButton: false,
                            toast: true,
                            position: 'top-end'
                        });

                        checkbox.prop('checked', res.status === 'active');

                        table.ajax.reload(null, false); // ❗ no page reload
                    },

                    error: function() {

                        Swal.fire({
                            icon: 'error',
                            title: 'Failed!',
                            text: 'Something went wrong',
                            toast: true,
                            position: 'top-end',
                            timer: 1500,
                            showConfirmButton: false
                        });

                        checkbox.prop('checked', !checkbox.prop('checked'));
                    }
                });

            });

        });
    </script>
    @if (session('success') || session('error') || session('warning') || session('info'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {

                if (typeof showToast === 'function') {

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

                }

            });
        </script>
    @endif
@endpush
