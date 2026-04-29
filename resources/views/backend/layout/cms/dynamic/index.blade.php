@extends('backend.app')

@section('title', 'CMS List')
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
    <div class="container-fluid">

        {{-- PAGE HEADER --}}
        <div class="row mb-3">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <h4 class="mb-0">
                    <i class="ri-file-list-3-line me-1"></i> CMS Management
                </h4>

                <a href="{{ route('admin.dynamic.cms.form') }}" class="btn btn-success btn-sm">
                    <i class="ri-add-line align-bottom me-1"></i> Create CMS
                </a>
            </div>
        </div>

        {{-- FILTER CARD --}}
        <div class="card">
            <div class="card-body">

                <form id="filterForm" class="row g-3">

                    <div class="col-md-3">
                        <label class="form-label">
                            <i class="ri-pages-line me-1"></i> Page
                        </label>
                        <select name="page" class="form-select">
                            <option value="">All Pages</option>
                            <option value="home">Home</option>
                            <option value="about">About</option>
                            <option value="men">Men</option>
                            <option value="women">Women</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">
                            <i class="ri-layout-4-line me-1"></i> Section
                        </label>
                        <select name="section" class="form-select">
                            <option value="">All Sections</option>
                            <option value="hero">Hero</option>
                            <option value="about">About</option>
                            <option value="services">Services</option>
                            <option value="gallery">Gallery</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">
                            <i class="ri-toggle-line me-1"></i> Status
                        </label>
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>

                    <div class="col-md-3 d-flex align-items-end">
                        <button type="button" class="btn btn-primary w-100" id="filterBtn">
                            <i class="ri-filter-3-line align-bottom me-1"></i> Apply Filter
                        </button>
                    </div>

                </form>

            </div>
        </div>

        {{-- TABLE CARD --}}
        <div class="card mt-3">
            <div class="card-body">

                <div class="table-responsive">
                    <table class="table table-nowrap align-middle" id="cmsTable" width="100%">
                        <thead class="table-light">
                            <tr>
                                <th>
                                    <i class="ri-hashtag"></i>
                                </th>
                                <th>
                                    <i class="ri-pages-line me-1"></i> Page
                                </th>
                                <th>
                                    <i class="ri-layout-4-line me-1"></i> Section
                                </th>
                                <th>
                                    <i class="ri-text"></i> Title
                                </th>
                                <th>
                                    <i class="ri-toggle-line me-1"></i> Status
                                </th>
                                <th>
                                    <i class="ri-settings-3-line"></i> Action
                                </th>
                            </tr>
                        </thead>
                    </table>
                </div>

            </div>
        </div>

    </div>
@endsection

{{-- ================= SCRIPT ================= --}}
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

    <script>
        $(function() {

            let table = $('#cmsTable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                autoWidth: false,

                ajax: {
                    url: "{{ route('admin.dynamic.cms.index') }}",
                    data: function(d) {
                        d.page = $('select[name=page]').val();
                        d.section = $('select[name=section]').val();
                        d.status = $('select[name=status]').val();
                    }
                },

                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'page',
                        name: 'page'
                    },
                    {
                        data: 'section',
                        name: 'section'
                    },
                    {
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            $('#filterBtn').click(function() {
                table.ajax.reload();
            });

        });
        // taggle status
        function changeStatus(id) {

            Swal.fire({
                title: 'Are you sure?',
                text: "You want to change status!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#405189',
                cancelButtonColor: '#f06548',
                confirmButtonText: 'Yes, update it',
                cancelButtonText: 'Cancel'
            }).then((result) => {

                if (result.isConfirmed) {

                    $.ajax({
                        url: `/admin/cms/status/${id}`,
                        type: "POST",
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },

                        success: function(res) {

                            Swal.fire({
                                icon: 'success',
                                title: 'Updated!',
                                text: res.message,
                                timer: 1200,
                                showConfirmButton: false
                            });

                            $('#cmsTable').DataTable().ajax.reload(null, false);
                        },

                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Something went wrong!'
                            });
                        }
                    });

                } else {
                    //  revert toggle if user cancels
                    $('#cmsTable').DataTable().ajax.reload(null, false);
                }
            });
        }
    </script>
@endpush
