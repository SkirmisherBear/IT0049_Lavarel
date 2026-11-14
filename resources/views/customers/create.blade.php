@extends('layouts.pos')
@section('title', 'Add Customer')
@section('heading', 'Add Customer')

@section('content')
<div class="page-heading">
    <div>
        <h1>Add Customer</h1>
        <p class="muted">Register a gym member for faster POS checkout.</p>
    </div>
    <a href="{{ route('customers.index') }}" class="btn btn-secondary">← Back</a>
</div>

<div class="card form-card">
    <form method="POST" action="{{ route('customers.store') }}">
        @csrf
        <div class="form-group">
            <label>Full Name</label>
            <input type="text" name="full_name" class="form-control" value="{{ old('full_name') }}" required>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
        </div>

        <div class="form-group">
            <label>Phone (Optional)</label>
            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
        </div>

        <button type="submit" class="btn">Save Customer</button>
    </form>
</div>
@endsection