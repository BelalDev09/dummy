@extends('backend.app')

@section('title', 'Customers List')

@push('style')
    <link rel="stylesheet" href="{{ asset('backend/assets/datatable/css/datatables.min.css') }}">
@endpush

@section('content')
    <div class="card">
        <div class="card-header">
            <a href="{{ route('customer.create') }}" class="btn btn-primary">Add Customer</a>
        </div>
        <div class="card-body">
            <table class="table table-bordered" id="customers-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Loyalty Points</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('backend/assets/datatable/js/datatables.min.js') }}"></script>
    <script>
        $(function() {
            $('#customers-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('customer.index') !!}',
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'phone',
                        name: 'phone'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },

                    {
                        data: 'loyalty_points',
                        name: 'loyalty_points'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            // Delete customer
            $(document).on('click', '.delete-customer', function() {
                let id = $(this).data('id');
                if (confirm('Are you sure you want to delete this customer?')) {
                    $.ajax({
                        url: '/admin/customers/' + id,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            $('#customers-table').DataTable().ajax.reload();
                            alert(response.message);
                        }
                    });
                }
            });
        });
    </script>
@endpush
