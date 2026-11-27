@extends('layouts.pos')

@section('title', 'Admin Dashboard')
@section('heading', 'Admin Overview')

@section('content')
<div class="page-heading">
    <div>
        <h1>Store overview</h1>
        <p class="muted">Sales performance, inventory, customers, and staff at a glance.</p>
    </div>
    <a href="{{ route('sales.create') }}" class="btn">+ New Sale</a>
</div>

<div class="stats">
    <div class="card"><div class="stat-label">Revenue Today</div><div class="stat-value">₱{{ number_format((float) $todaySales, 2) }}</div></div>
    <div class="card"><div class="stat-label">Revenue This Month</div><div class="stat-value">₱{{ number_format((float) $monthSales, 2) }}</div></div>
    <div class="card"><div class="stat-label">All-time Revenue</div><div class="stat-value">₱{{ number_format((float) $totalSales, 2) }}</div></div>
    <div class="card"><div class="stat-label">Transactions Today</div><div class="stat-value">{{ $todayTransactions }}</div></div>
    <div class="card"><div class="stat-label">All Transactions</div><div class="stat-value">{{ $totalTransactions }}</div></div>
    <div class="card"><div class="stat-label">Products</div><div class="stat-value">{{ $totalProducts }}</div></div>
    <div class="card"><div class="stat-label">Low Stock Items</div><div class="stat-value">{{ $lowStock }}</div></div>
    <div class="card"><div class="stat-label">Customers</div><div class="stat-value">{{ $totalCustomers }}</div></div>
    <div class="card"><div class="stat-label">Employees</div><div class="stat-value">{{ $totalStaff }}</div></div>
</div>

<div class="panel card">
    <div class="panel-title">Recent Store Sales</div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Receipt</th><th>Product</th><th>Customer</th><th>Qty</th><th>Total</th><th>Cashier</th><th>Date</th></tr></thead>
            <tbody>
                @forelse($recentSales as $sale)
                    <tr>
                        <td>#{{ str_pad($sale->id, 5, '0', STR_PAD_LEFT) }}</td>
                        <td>{{ $sale->product?->name ?? 'Unavailable' }}</td>
                        <td>{{ $sale->customer?->full_name ?? 'Walk-in' }}</td>
                        <td>{{ $sale->quantity }}</td>
                        <td>₱{{ number_format((float) $sale->total_price, 2) }}</td>
                        <td>{{ $sale->staff?->name ?? 'Staff' }}</td>
                        <td>{{ $sale->created_at->format('M d, Y h:i A') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="7" style="text-align:center;padding:24px;">No sales recorded yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="panel">
    <div class="panel-title">Management Modules</div>
    <div style="display:flex;gap:12px;flex-wrap:wrap">
        <a href="{{ route('products.index') }}" class="btn">Manage Products</a>
        <a href="{{ route('customers.index') }}" class="btn">Manage Customers</a>
        <a href="{{ route('sales.index') }}" class="btn">View All Sales</a>
        <a href="{{ route('staff.index') }}" class="btn">Manage Staff & Admins</a>
    </div>
</div>
@endsection
