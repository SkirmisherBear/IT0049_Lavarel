
@extends('layouts.pos')

@section('title', 'Dashboard')
@section('heading', 'Dashboard Overview')

@section('content')

<div class="page-heading">
    <div>
        <h1>Good day, {{ auth()->user()->username ?? auth()->user()->name ?? 'Staff' }}!</h1>
        <p class="muted">
            Here's what's happening with your Overclock store today.
        </p>
    </div>

    <a href="{{ route('sales.create') }}" class="btn">
        + New Transaction
    </a>
</div>

<div class="stats">

    <div class="card">
        <div class="stat-icon">₱</div>
        <div class="stat-label">Today's Revenue</div>
        <div class="stat-value">
            ₱{{ number_format((float)$todaySales, 2) }}
        </div>
    </div>

    <div class="card">
        <div class="stat-icon">▤</div>
        <div class="stat-label">Transactions Today</div>
        <div class="stat-value">{{ $todayTransactions }}</div>
    </div>

    <div class="card">
        <div class="stat-icon">▦</div>
        <div class="stat-label">Total Products</div>
        <div class="stat-value">{{ $totalProducts }}</div>
    </div>

    <div class="card">
        <div class="stat-icon">⚠</div>
        <div class="stat-label">Low Stock Items</div>
        <div class="stat-value">{{ $lowStock }}</div>
    </div>

</div>

<div class="panel card">
    <div class="panel-title">Recent Transactions</div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Receipt</th>
                    <th>Product</th>
                    <th>Customer</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Date</th>
                </tr>
            </thead>

            <tbody>
                @forelse($recentSales as $sale)
                    <tr>
                        <td>#{{ str_pad($sale->id, 5, '0', STR_PAD_LEFT) }}</td>
                        <td>{{ $sale->product?->name ?? 'Unavailable' }}</td>
                        <td>{{ $sale->customer?->full_name ?? 'Walk-in' }}</td>
                        <td>{{ $sale->quantity }}</td>
                        <td>₱{{ number_format((float)$sale->total_price, 2) }}</td>
                        <td>{{ $sale->created_at->format('M d, Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align:center;padding:30px">
                            No transactions recorded yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="panel">
    <div class="panel-title">Quick Actions</div>

    <div style="display:flex;gap:12px;flex-wrap:wrap">
        <a href="{{ route('products.create') }}" class="btn">+ Add Product</a>
        <a href="{{ route('customers.create') }}" class="btn">+ Add Customer</a>
        <a href="{{ route('sales.create') }}" class="btn">+ Process Sale</a>
    </div>
</div>

@if(auth()->user()?->role === 'client')
    <div class="sidebar-label">MEMBER PORTAL</div>

    <a href="{{ route('member.portal') }}"
       class="nav-link {{ request()->routeIs('member.portal') ? 'active' : '' }}">
        <span class="nav-icon">🪪</span>
        <span class="nav-text">My QR Pass & Hub</span>
    </a>
@endif
@endsection