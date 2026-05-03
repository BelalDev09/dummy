@extends('backend.app')

@section('title', 'Brands')
@push('styles')
    <style>
        .dropify-wrapper {
            height: auto !important;
        }
    </style>
@endpush
@section('content')
    <div class="container-fluid">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-3">

            <h5 class="mb-0">Brands</h5>

            <div class="d-flex gap-2">

                <button class="btn btn-soft-danger btn-sm d-none" id="bulkDeleteBtn">
                    <i class="ri-close-large-fill"></i> Delete Selected
                </button>

                <a href="{{ route('admin.brands.create') }}" class="btn btn-success btn-sm">
                    <i class="ri-add-line"></i> Add Brand
                </a>

            </div>

        </div>
        <div class="card shadow-sm">
            <div class="card-body">

                {{-- Table --}}
                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="brandsTable" width="100%">

                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>
                                    <input type="checkbox" id="select_all">
                                </th>
                                <th>Logo</th>
                                <th>Name</th>
                                <th>Status</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>

                    </table>
                </div>

            </div>
        </div>

    </div>
@endsection


@push('scripts')
    <script>
        let selectedIds = [];

        function getAjaxErrorMessage(xhr, fallback) {
            return xhr?.responseJSON?.message || xhr?.responseJSON?.error || fallback;
        }

        $(function() {

            $('#brandsTable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                autoWidth: false,

                ajax: "{{ route('admin.brands.index') }}",

                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },

                    {
                        data: 'bulk_check',
                        orderable: false,
                        searchable: false
                    },

                    {
                        data: 'logo',
                        orderable: false,
                        searchable: false
                    },

                    {
                        data: 'name'
                    },

                    {
                        data: 'status',
                        orderable: false,
                        searchable: false
                    },

                    {
                        data: 'action',
                        orderable: false,
                        searchable: false,
                        className: "text-end"
                    },
                ]
            });

            // SELECT ALL
            $('#select_all').on('change', function() {

                $('.select_data').prop('checked', this.checked);

                selectedIds = this.checked ?
                    $('.select_data').map(function() {
                        return $(this).val();
                    }).get() : [];

                toggleBulkButton();
            });

        });


        // SINGLE SELECT
        function select_single_item(id) {

            if (selectedIds.includes(id)) {
                selectedIds = selectedIds.filter(i => i != id);
            } else {
                selectedIds.push(id);
            }

            toggleBulkButton();
        }


        // BULK BUTTON SHOW/HIDE
        function toggleBulkButton() {

            if (selectedIds.length > 0) {
                $('#bulkDeleteBtn').removeClass('d-none');
            } else {
                $('#bulkDeleteBtn').addClass('d-none');
            }
        }


        // DELETE SINGLE
        function deleteBrand(id) {

            Swal.fire({
                title: 'Are you sure?',
                text: "This brand will be deleted!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#f06548',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {

                if (result.isConfirmed) {

                    $.ajax({
                        url: `/admin/brands/${id}`,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(res) {

                            Swal.fire({
                                icon: 'success',
                                title: res.message,
                                timer: 1200,
                                showConfirmButton: false
                            });

                            $('#brandsTable').DataTable().ajax.reload(null, false);
                        },
                        error: function(xhr) {
                            showToast('error', getAjaxErrorMessage(xhr, 'Brand was not deleted.'));
                        }
                    });

                }
            });
        }


        // STATUS TOGGLE
        function changeStatus(id) {

            Swal.fire({
                title: 'Change status?',
                text: "This will update brand status",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0ab39c',
                confirmButtonText: 'Yes, change it'
            }).then((result) => {

                if (result.isConfirmed) {

                    $.post(`/admin/brands/status/${id}`, {
                        _token: '{{ csrf_token() }}'
                    }, function(res) {

                        Swal.fire({
                            icon: 'success',
                            title: res.message,
                            timer: 1200,
                            showConfirmButton: false
                        });

                        $('#brandsTable').DataTable().ajax.reload(null, false);
                    }).fail(function(xhr) {
                        showToast('error', getAjaxErrorMessage(xhr, 'Status was not updated.'));
                    });

                }
            });
        }


        // BULK DELETE
        $('#bulkDeleteBtn').on('click', function() {

            if (selectedIds.length === 0) return;

            Swal.fire({
                title: 'Delete selected brands?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#f06548',
                confirmButtonText: 'Yes, delete'
            }).then((result) => {

                if (result.isConfirmed) {

                    $.ajax({
                        url: "{{ route('admin.brands.bulk-delete') }}",
                        type: "POST",
                        data: {
                            _token: '{{ csrf_token() }}',
                            ids: selectedIds
                        },

                        success: function(res) {

                            Swal.fire({
                                icon: 'success',
                                title: res.message,
                                timer: 1200,
                                showConfirmButton: false
                            });

                            selectedIds = [];
                            toggleBulkButton();

                            $('#select_all').prop('checked', false);
                            $('#brandsTable').DataTable().ajax.reload();
                        },
                        error: function(xhr) {
                            showToast('error', getAjaxErrorMessage(xhr, 'Selected brands were not deleted.'));
                        }
                    });

                }
            });
        });
    </script>
@endpush
