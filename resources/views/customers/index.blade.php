@extends('layouts.pos')
@section('title', 'Customers')
@section('heading', 'Customers Directory')

@section('content')
<div class="page-heading">
    <div>
        <h1>Customers</h1>
        <p class="muted">Registered gym members and repeat buyers.</p>
    </div>
    <a href="{{ route('customers.create') }}" class="btn">+ Add Customer</a>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $customer)
                    <tr>
                        <td>#{{ $customer->id }}</td>
                        <td><strong>{{ $customer->full_name }}</strong></td>
                        <td>{{ $customer->email }}</td>
                        <td>{{ $customer->phone ?? '—' }}</td>
                        <td class="actions-cell">
                            <a href="{{ route('customers.edit', $customer) }}" class="btn btn-secondary">Edit</a>
                            <form method="POST" action="{{ route('customers.destroy', $customer) }}" onsubmit="return confirm('Delete this customer?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align:center;padding:30px">No customers recorded yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection