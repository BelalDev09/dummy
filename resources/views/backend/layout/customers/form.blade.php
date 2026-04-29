@extends('backend.app')

@section('title', isset($customer) ? 'Edit Customer' : 'Create Customer')

@section('content')

    <div class="row justify-content-center">
        <div class="col-xl-8">

            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        {{ isset($customer) ? 'Edit Customer' : 'Create Customer' }}
                    </h4>
                </div>

                <div class="card-body">

                    <form action="{{ isset($customer) ? route('customer.update', $customer->id) : route('customer.store') }}"
                        method="POST">
                        @csrf
                        @if (isset($customer))
                            @method('PUT')
                        @endif

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Name</label>
                                <input type="text" name="name" class="form-control"
                                    value="{{ old('name', $customer->name ?? '') }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Phone</label>
                                <input type="text" name="phone" class="form-control"
                                    value="{{ old('phone', $customer->phone ?? '') }}" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control"
                                    value="{{ old('email', $customer->email ?? '') }}">
                            </div>

                            {{-- <div class="col-md-6">
                                <label class="form-label">Restaurant</label>
                                <select name="restaurant_id" class="form-control" required>
                                    <option value="">-- Select Restaurant --</option>
                                    @foreach ($restaurants as $restaurant)
                                        <option value="{{ $restaurant->id }}"
                                            {{ old('restaurant_id', $customer->restaurant_id ?? '') == $restaurant->id ? 'selected' : '' }}>
                                            {{ $restaurant->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div> --}}
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Address</label>
                                <textarea name="address" class="form-control">{{ old('address', $customer->address ?? '') }}</textarea>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Loyalty Points</label>
                                <input type="number" name="loyalty_points" class="form-control"
                                    value="{{ old('loyalty_points', $customer->loyalty_points ?? 0) }}">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success">
                            {{ isset($customer) ? 'Update Customer' : 'Create Customer' }}
                        </button>

                        <a href="{{ route('customer.index') }}" class="btn btn-secondary">Cancel</a>

                    </form>

                </div>
            </div>

        </div>
    </div>

@endsection
