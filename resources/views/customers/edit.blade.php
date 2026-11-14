@extends('layouts.pos')
@section('title', 'Edit Customer')
@section('heading', 'Edit Customer')

@section('content')
<div class="page-heading">
    <div>
        <h1>Edit Customer</h1>
        <p class="muted">Update gym member contact information.</p>
    </div>
    <a href="{{ route('customers.index') }}" class="btn btn-secondary">← Back</a>
</div>

<div class="card form-card">
    <form method="POST" action="{{ route('customers.update', $customer) }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Full Name</label>
            <input type="text" name="full_name" class="form-control" value="{{ old('full_name', $customer->full_name) }}" required>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $customer->email) }}" required>
        </div>

        <div class="form-group">
            <label>Phone (Optional)</label>
            <input type="text" name="phone" class="form-control" value="{{ old('phone', $customer->phone) }}">
        </div>

        <div style="display:flex;gap:10px;">
            <button type="submit" class="btn">Update Customer</button>
            <a href="{{ route('customers.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection