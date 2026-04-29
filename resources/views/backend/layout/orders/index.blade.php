@extends('backend.app')

@section('content')
    <div class="page-content">
        {{-- <div class="container-fluid"> --}}

        <!-- Page Title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Orders</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                            <li class="breadcrumb-item active">Orders</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card -->
        <div class="row">
            <div class="col-12">

                <div class="card shadow-sm">

                    <!-- Card Header -->
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title mb-0">Order Management</h5>

                        <div>
                            <button class="btn btn-soft-primary btn-sm">
                                <i class="ri-refresh-line"></i> Refresh
                            </button>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table table-hover align-middle yajra-datatable w-100">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>User</th>
                                        <th>Order No</th>
                                        <th>Amount</th>
                                        <th>Payment</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>

                    </div>

                </div>

            </div>
        </div>

        {{-- </div> --}}
    </div>
@endsection
@push('scripts')
    <script>
        $(function() {

            $('.yajra-datatable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: "{{ route('admin.orders.index') }}",

                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },

                    {
                        data: 'user',
                        name: 'user'
                    },

                    {
                        data: 'order_number',
                        name: 'order_number',
                        render: function(data) {
                            return `<span class="badge bg-dark">${data}</span>`;
                        }
                    },

                    {
                        data: 'amount',
                        name: 'amount',
                        render: function(data) {
                            return `<div class="small">${data}</div>`;
                        }
                    },

                    {
                        data: 'payment_status',
                        name: 'payment_status',
                        render: function(data) {

                            let color = {
                                paid: 'success',
                                pending: 'warning',
                                failed: 'danger'
                            };

                            return `<span class="badge bg-${color[data] ?? 'secondary'}">${data}</span>`;
                        }
                    },

                    {
                        data: 'order_status',
                        name: 'order_status',
                        render: function(data) {

                            let color = {
                                pending: 'warning',
                                processing: 'info',
                                shipped: 'primary',
                                delivered: 'success',
                                cancelled: 'danger'
                            };

                            return `<span class="badge bg-${color[data] ?? 'secondary'}">${data}</span>`;
                        }
                    },

                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });

        });
    </script>
@endpush
